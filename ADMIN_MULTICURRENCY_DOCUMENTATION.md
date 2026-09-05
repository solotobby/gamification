# Freebyz Admin Portal - Multi-Currency & UI Modernization Documentation

**Branch**: `feature/modern-admin-multicurrency`  
**Target Repository**: `gamification` (Admin Portal, Laravel 8 + Blade)  
**Associated Repositories**: `gamification-backend-api` (API & Migrations), `freebyz` (User Web Frontend)  
**Database**: `freebyz-new` (Shared MySQL Database)

---

## 1. Executive Summary

This update modernizes the Freebyz Admin Portal to support the **single-currency-per-user multi-currency architecture** introduced in `gamification-backend-api`. In this architecture:
- Each user is entitled to **one base currency** (e.g., `NGN`, `USD`, `GHS`, `KES`, `ZAR`, `RWF`, etc.).
- Balances are stored in designated columns on the `wallets` table based on the currency.
- Admin management actions (wallet topup, wallet debit, verification, currency switching, user lists, and withdrawal queues) now dynamically adapt to each user's assigned currency without hardcoded Naira assumptions.
- The Admin UI has been overhauled with a modern styling system (`public/css/modern-admin.css`), and the sidebar has been organized into **8 collapsed, intuitive functional modules**.
- **Important Constraint Maintained**: Database migrations remain exclusively owned and executed by `gamification-backend-api`. No new migrations were created in `gamification`.

---

## 2. Multi-Currency Storage & Exchange Architecture

### 2.1 Wallet Column Mapping

In `wallets`, user balances are partitioned by currency:
| Currency | Wallet Balance Column | Notes |
| :--- | :--- | :--- |
| **NGN (Naira)** | `wallets.balance` | Primary currency for Nigerian users |
| **USD (Dollar)** | `wallets.usd_balance` | Primary currency for international users |
| **Other (GHS, KES, ZAR, etc.)** | `wallets.base_currency_balance` | Used for all other foreign fiat currencies |
| **Active Currency Code** | `wallets.base_currency` & `users.base_currency` | Must stay synchronized |

### 2.2 Currency Rate System (`currencies.base_rate`)

All currency rates are calibrated relative to **USD = 1.0**:
- `USD`: base rate = `1.0`
- `NGN`: base rate = `1300.0` (1 USD = ₦1,300)
- `GHS`: base rate = `10.0` (1 USD = GH₵10)
- `KES`: base rate = `14.0` (1 USD = KSh 14)

The official Freebyz conversion formula is:
$$\text{Rate} = \frac{\text{BaseRate}(\text{To})}{\text{BaseRate}(\text{From})}$$
$$\text{Converted Amount} = \text{Amount} \times \text{Rate}$$

*Example: Converting 10,000 NGN to GHS:*
$$\text{Rate} = \frac{10}{1300} = 0.007692 \implies 10,000 \times 0.007692 = \text{GH₵} 76.92$$

---

## 3. Core Helper Functions (`app/Helpers/globalhelpers.php`)

| Function | Purpose | Implementation Details |
| :--- | :--- | :--- |
| `walletBalance($userId)` | Resolves active live balance for any user | Inspects `wallets.base_currency`. If `NGN` returns `balance`, if `USD` returns `usd_balance`, otherwise returns `base_currency_balance`. |
| `formatCurrency($amount, $currencyCode)` | Formats amount with proper symbol | Returns `₦`, `$`, `GH₵`, `KSh`, `R`, `FRw`, `FCFA`, etc., with 2 decimal places. |
| `currencySymbol($currencyCode)` | Returns currency symbol string | Provides symbol mapping for UI labels and input adornments. |
| `convertUserCurrency($amount, $from, $to)` | Converts amount between any two currencies | Calculates live rate using `getBaseRate($to) / getBaseRate($from)`. Returns `['amount', 'rate', 'source']`. |
| `baseCurrency($user)` | Robust base currency resolver | Accepts User model, user ID, or null (Auth). Resolves currency code from wallet with fallback to `users.base_currency` and normalizes legacy values (`Naira` $\to$ `NGN`, `Dollar` $\to$ `USD`). |

---

## 4. Model Enhancements (`User.php` & `Wallet.php`)

### `app/Models/User.php`
- **`currency_code` Accessor**: Resolves user's currency (`$user->currency_code`), defaulting to `'NGN'`.
- **`active_balance` Accessor**: Retrieves user's live balance in their assigned currency (`$user->active_balance`).
- **`formatted_balance` Accessor**: Returns formatted balance with symbol (`$user->formatted_balance`, e.g. `GH₵450.00`).
- **`isVerifiedInCurrency()` Method**: Checks verification state in the user's currency. Returns `true` if `is_verified == 1` (and if USD, verifies `USD_verified` record exists).

### `app/Models/Wallet.php`
- **`currency_code` Accessor**: `$wallet->currency_code`.
- **`active_balance` Accessor**: `$wallet->active_balance`.
- **`formatted_balance` Accessor**: `$wallet->formatted_balance`.

---

## 5. Admin Controller Upgrades (`AdminController.php` & `HomeController.php`)

### 5.1 Automated Currency Switching (`switch(Request $request)`)
- **Route**: `POST admin/switch/wallet`
- **Behavior**:
  1. Validates `user_id` and target `currency`.
  2. Detects previous currency (`$oldCurrency`) and active balance (`$oldBalance`).
  3. If `$oldBalance > 0`:
     - Calls `convertUserCurrency($oldBalance, $oldCurrency, $targetCurrency)`.
     - Credits the converted balance to the new currency column.
     - Zeros out the old currency balance column.
     - Logs an audit `PaymentTransaction` with `type = 'currency_conversion'`, `tx_type = 'Conversion'`.
     - Sends in-app notification to the user detailing the conversion and exchange rate.
  4. Updates both `wallets.base_currency` and `users.base_currency`.
  5. Returns with flash success message detailing converted amounts and rate applied.

### 5.2 Multi-Currency Wallet Topup & Debit (`adminWalletTopUp(Request $request)`)
- **Route**: `POST admin/wallet/topup`
- **Behavior**:
  - Automatically identifies whether crediting or debiting.
  - Detects user's currency (or allows admin to specify).
  - Credits or debits the specific wallet column (`balance`, `usd_balance`, or `base_currency_balance`).
  - Enforces balance validation on debits so users cannot be debited below 0.
  - Creates `PaymentTransaction` with user's currency and accurate post-transaction balance.
  - Sends email and in-app notifications with properly formatted currency amounts.

### 5.3 Unified Verification (`upgradeUser($id)`)
- **Routes**: `GET admin/upgrade/{id}`, aliased to `admin/upgrade/{id}/naira` and `admin/upgrade/{id}/dollar`.
- **Behavior**:
  - Unifies Naira and Dollar verification into a single method that handles all currencies.
  - Toggles verification state based on `$user->isVerifiedInCurrency()`.
  - When verifying:
    - Sets `is_verified = 1` and creates `Usdverified` if USD.
    - Resolves upgrade fee and referral commission from `currencies` for user's currency.
    - Logs `PaymentTransaction` in user's currency.
    - If referrer exists, converts referral commission into referrer's currency using `convertUserCurrency`, credits referrer wallet, and logs transaction in referrer's currency.
  - When unverifying: resets verification flags and cleans up verification records.

### 5.4 Multi-Currency User Directory & Search (`userList`, `userSearch`)
- **Routes**: `GET users`, `GET users/search`
- **Behavior**:
  - Added currency filtering dropdown and quick filter pills (`?currency=GHS`).
  - Fixed search input query handling (`$request->input('search') ?: $request->input('q')`).
  - Displays user's assigned currency pill badge and active balance in table.

### 5.5 Multi-Currency Withdrawal Queues (`withdrawalRequest`, `withdrawalRequestQueued`, `withdrawalRequestQueuedCurrent`)
- **Routes**: `GET admin/withdrawal`, `GET admin/withdrawal/request/queued`, `GET admin/withdrawal/request/queued/current`
- **Behavior**:
  - Computes grouped sums and counts per currency (`currencyTotals`), replacing the inaccurate single Naira summation.
  - Added currency filtering pills and dropdowns.
  - Shows recipient payout details dynamically (PayStack/Bank account for NGN/local, PayPal email for USD).

### 5.6 Multi-Currency Treasury Dashboard (`adminHome`)
- **Route**: `GET home` / `GET admin/home`
- **Behavior**:
  - Calculates active liabilities broken down by currency across all user wallets.
  - Calculates pending withdrawal liabilities per currency.
  - Renders multi-currency cards with quick links to queued requests.

---

## 6. UI Modernization & Master Sidebar Optimization

### 5.7 Multi-Currency Transactions Ledger (`adminTransaction` & `userTransaction`)
- **Routes**: `GET admin/transactions`, `GET user/transactions`
- **Behavior**:
  - Eager-loads `user` relationship to eliminate N+1 query bottlenecks.
  - Aggregates multi-currency totals (`currencyTotals`) grouped by currency code.
  - Adds real-time search across reference, description, tx_type, and user name/email.
  - Adds currency filter pills (`?currency=GHS`) and currency dropdown.
  - Formats transaction amounts and running balances with `formatCurrency($list->amount, $list->currency)`.

### 5.8 Multi-Currency Campaigns & Job Reversals
- **Routes**: `GET campaigns`, `GET campaigns/pending`, `GET campaigns/completed`, `GET campaigns/paused`, `GET campaigns/denied`, `GET campaigns/flagged`, `GET campaign/info/{id}`, `POST campaign/status`, `GET reverse/transaction/{id}`
- **Behavior**:
  - **Dynamic Currency Filtering**: Filter live, pending, completed, paused, denied, and flagged campaigns by specific currency (`?currency=USD`).
  - **Eager Loading**: Relations `user`, `campaignType`, and `campaignCategory` eager-loaded.
  - **Multi-Currency Decline Refund**: When an admin declines a campaign in `campaignStatus`, refunds are credited in the campaign's specific currency (`$camp->currency`) using `creditWallet($camp->user, $currency, $amount)`, and admin commission is debited in that same currency.
  - **Multi-Currency Job Reversal**: `jobReversal($id)` debits the worker in `$campaign->currency` using `debitWallet` and refunds the campaigner in `$campaign->currency` using `creditWallet`.
  - **Task Approvals**: `unapproved_list.blade.php` and `approved_list.blade.php` format payouts with `formatCurrency` and support multi-currency task payouts without hardcoding Naira.

---

## 6. UI Modernization & Master Sidebar Optimization

### 6.1 Sleek Modern CSS (`public/css/modern-admin.css`)
- Linked in `resources/views/layouts/main/master.blade.php`.
- Provides:
  - Card designs with subtle elevations and smooth hover states.
  - Currency badge pills: `.badge-currency-ngn`, `.badge-currency-usd`, `.badge-currency-ghs`, `.badge-currency-kes`, `.badge-currency-zar`.
  - Responsive financial stat cards (`.currency-stat-card`).
  - Interactive filter pills (`.pill-filter`).
  - Modern table styles (`.table-modern`).

### 6.2 Streamlined & Neater Sidebar (`resources/views/layouts/sidebar/admin.blade.php`)
- **Removed redundant links**: Completely removed "USD Verified" (as all currencies are handled uniformly).
- **Reduced sprawling options**: Condensed down from 25+ loose items into 8 organized modules with 3–6 high-frequency actions per group:
  1. **Dashboard**: Single overview entry (`url('home')`).
  2. **Users**: All Users, Verified Users, Email Verified, Duplicate Accounts.
  3. **Financials**: Queued Payouts (with pending badge count), Disbursed Payouts, User Transactions, Admin Transactions, Manual Fundings, Currencies, Conversion Rates.
  4. **Campaigns & Tasks**: Active Campaigns, Pending Review (with count badge), Paused Campaigns, Completed Campaigns, Task Disputes (with unresolved badge), Flagged Campaigns, Task Proof Approvals, Categories (`route('create.category')`), Campaign Creators.
  5. **Jobs & Career**: Job Vacancies, Post Vacancy, Career Profiles, Professional Jobs.
  6. **Operations**: Business Accounts, Banner Ads (with badge count), Safelock & Partners, Interactive Games, Blogs (`route('admin.blogs.index')`).
  7. **Support & Comms**: Feedbacks, Mass Email, Notifications, Knowledge Base.
  8. **Settings & Staff**: Staff Management, Process Salary, Preferences & Audit.

---

## 7. Modified & Created Files Summary

| File | Change Type | Description |
| :--- | :--- | :--- |
| `app/Helpers/globalhelpers.php` | Modified | Updated `baseCurrency`, `walletBalance`, `formatCurrency`, `currencySymbol`, and `convertUserCurrency` (aligned to Freebyz formula) |
| `app/Models/User.php` | Modified | Added `currency_code`, `active_balance`, `formatted_balance`, `isVerifiedInCurrency` |
| `app/Models/Wallet.php` | Modified | Added `currency_code`, `active_balance`, `formatted_balance` |
| `routes/web.php` | Modified | Added `admin.user.upgrade` and `admin.user.toggle-business` aliases |
| `app/Http/Controllers/Admin/AdminController.php` | Modified | Multi-currency controller logic for users, wallets, withdrawals, transactions, campaigns, and reversals |
| `app/Http/Controllers/HomeController.php` | Modified | Added multi-currency treasury breakdowns and liabilities to `adminHome` |
| `public/css/modern-admin.css` | **Created** | Modern styling tokens, currency badge classes, metric cards |
| `resources/views/layouts/main/master.blade.php` | Modified | Linked `modern-admin.css` stylesheet |
| `resources/views/layouts/sidebar/admin.blade.php` | Modified | Condensed 8-module sidebar layout and removed USD verified |
| `resources/views/admin/users/list.blade.php` | Modified | Added currency pills, active balances, currency badges, search |
| `resources/views/admin/users/user_info_new.blade.php` | Modified | Single-currency user header, unified verification card, live conversion switcher |
| `resources/views/admin/users/user_transactions.blade.php` | Modified | Multi-currency summary stat cards, currency filter pills, formatted ledger |
| `resources/views/admin/admin_transactions.blade.php` | Modified | Multi-currency admin pool cards, currency pills, formatted ledger |
| `resources/views/admin/withdrawals/queued.blade.php` | Modified | Multi-currency summary cards, currency filter tabs, accurate amounts |
| `resources/views/admin/withdrawals/sent.blade.php` | Modified | Multi-currency sent totals, badges, and filters |
| `resources/views/admin/index_new.blade.php` | Modified | Multi-currency treasury and liability cards |
| `resources/views/admin/campaign_list.blade.php` | Modified | Currency pills, formatted unit/total amounts, progress bars |
| `resources/views/admin/pending_campaigns.blade.php` | Modified | Currency badges, pagination, formatted escrow budget |
| `resources/views/admin/campaign_completed.blade.php` | Modified | Currency pills, pagination, formatted disbursed totals |
| `resources/views/admin/campaign_paused.blade.php` | Modified | Currency badges, unpause actions, formatted amounts |
| `resources/views/admin/flagged_campaigns.blade.php` | Modified | Currency badges, eager-loaded denial rates, unflag modal |
| `resources/views/admin/denied_list.blade.php` | Modified | Currency pills, formatted unit prices and refunded budgets |
| `resources/views/admin/campaign_mgt/info.blade.php` | Modified | Multi-currency badges, formatted cost per worker and total budget |
| `resources/views/admin/unapproved_list.blade.php` | Modified | Multi-currency badges, formatCurrency, select-all checkbox |
| `resources/views/admin/approved_list.blade.php` | Modified | Multi-currency badges, formatCurrency, reversal confirmation |

---

## 8. Verification & Validation Results

1. **Syntax Checking**:
   - `php -l app/Http/Controllers/Admin/AdminController.php` $\implies$ **No syntax errors detected**.
   - `php -l app/Http/Controllers/HomeController.php` $\implies$ **No syntax errors detected**.
   - `php -l app/Helpers/globalhelpers.php` $\implies$ **No syntax errors detected**.
   - `php -l app/Models/User.php` $\implies$ **No syntax errors detected**.
   - `php -l app/Models/Wallet.php` $\implies$ **No syntax errors detected**.

2. **Controller Actions & Blade Rendering Verification**:
   - `adminTransaction`: Successfully rendered (150 KB HTML) with 0 errors.
   - `userTransaction`: Successfully rendered (174 KB HTML) with 0 errors.
   - `campaignList`: Successfully rendered (123 KB HTML) with 0 errors.
   - `campaignPending`: Successfully rendered (173 KB HTML) with 0 errors.
   - `campaignPaused`: Successfully rendered (53 KB HTML) with 0 errors.
   - `campaignCompleted`: Successfully rendered (66 KB HTML) with 0 errors.
   - `deniedCampaigns`: Successfully rendered (55 KB HTML) with 0 errors.
   - `flaggedCampaigns`: Successfully rendered (62 KB HTML) with 0 errors.

3. **Currency Conversion Verification**:
   - `convertUserCurrency(1000, 'NGN', 'USD')` $\implies$ `0.77 USD` (Rate: `0.000769`)
   - `convertUserCurrency(1, 'USD', 'NGN')` $\implies$ `1300.00 NGN` (Rate: `1300.0`)
   - `convertUserCurrency(100, 'GHS', 'NGN')` $\implies$ `13000.00 NGN` (Rate: `130.0`)
   - `convertUserCurrency(1000, 'NGN', 'GHS')` $\implies$ `7.69 GHS` (Rate: `0.007692`)
   - `convertUserCurrency(100, 'USD', 'GHS')` $\implies$ `1000.00 GHS` (Rate: `10.0`)


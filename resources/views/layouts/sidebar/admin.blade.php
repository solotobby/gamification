<div class="content-side">
    <ul class="nav-main">
        <!-- 1. Dashboard -->
        <li class="nav-main-item">
            <a class="nav-main-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('home') }}">
                <i class="nav-main-link-icon fa fa-home"></i>
                <span class="nav-main-link-name">Dashboard</span>
            </a>
        </li>

        <!-- 2. User Management -->
        <li class="nav-main-item {{ request()->is('users*') || request()->is('admin/user*') || request()->is('verified/users*') || request()->is('user/email*') || request()->is('remove/duplicate/account*') || request()->is('user/tracker*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-users"></i>
                <span class="nav-main-link-name">Users</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('user.list') ? 'active' : '' }}" href="{{ route('user.list') }}">
                        <span class="nav-main-link-name">All Users</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('verified.user.list') ? 'active' : '' }}" href="{{ route('verified.user.list') }}">
                        <span class="nav-main-link-name">Verified Users</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('user.email.verified') ? 'active' : '' }}" href="{{ route('user.email.verified') }}">
                        <span class="nav-main-link-name">Email Verified</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('user.tracker') ? 'active' : '' }}" href="{{ route('user.tracker') }}">
                        <span class="nav-main-link-name">User Tracker</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('remove/duplicate/account') ? 'active' : '' }}" href="{{ url('remove/duplicate/account') }}">
                        <span class="nav-main-link-name">Duplicate Accounts</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 3. Payouts -->
        @php
            $pendingWithdrawals = \App\Models\Withrawal::where('status', false)->count();
        @endphp
        <li class="nav-main-item {{ request()->is('admin/withdrawal*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-money-bill-transfer"></i>
                <span class="nav-main-link-name">Payouts</span>
                @if($pendingWithdrawals > 0)
                    <span class="nav-main-link-badge badge rounded-pill bg-danger">{{ $pendingWithdrawals }}</span>
                @endif
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.withdrawal.queued') || request()->routeIs('admin.withdrawal.queued.current') ? 'active' : '' }}" href="{{ route('admin.withdrawal.queued') }}">
                        <span class="nav-main-link-name">Queued Payouts</span>
                        @if($pendingWithdrawals > 0)
                            <span class="badge rounded-pill bg-danger ms-auto">{{ $pendingWithdrawals }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.withdrawal') ? 'active' : '' }}" href="{{ route('admin.withdrawal') }}">
                        <span class="nav-main-link-name">Disbursed Payouts</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 4. Transactions & Funding -->
        <li class="nav-main-item {{ request()->is('admin/manual/fundings*') || request()->is('admin/transaction*') || request()->is('admin/wallet/discrepancies*') || request()->is('user/transaction*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-receipt"></i>
                <span class="nav-main-link-name">Transactions & Funding</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.wallet.discrepancies') ? 'active' : '' }}" href="{{ route('admin.wallet.discrepancies') }}">
                        <span class="nav-main-link-name">Balance Discrepancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('user.transaction') ? 'active' : '' }}" href="{{ route('user.transaction') }}">
                        <span class="nav-main-link-name">User Transactions</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.transaction') ? 'active' : '' }}" href="{{ route('admin.transaction') }}">
                        <span class="nav-main-link-name">Admin Transactions</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.manual.fundings') ? 'active' : '' }}" href="{{ route('admin.manual.fundings') }}">
                        <span class="nav-main-link-name">Manual Fundings</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 5. Currencies & Conversion Rates -->
        <li class="nav-main-item {{ request()->is('currencies*') || request()->is('conversion-rates*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-coins"></i>
                <span class="nav-main-link-name">Currencies & Rates</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('currencies*') ? 'active' : '' }}" href="{{ url('currencies') }}">
                        <span class="nav-main-link-name">Currencies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('conversion-rates*') ? 'active' : '' }}" href="{{ url('conversion-rates') }}">
                        <span class="nav-main-link-name">Conversion Rates</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 6. Campaigns & Tasks -->
        <li class="nav-main-item {{ request()->is('campaign*') || request()->is('admin/campaign*') || request()->is('unapproved*') || request()->is('approved*') || request()->is('admin/task*') || request()->routeIs('create.category') || request()->is('create/category*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-tasks"></i>
                <span class="nav-main-link-name">Campaigns & Tasks</span>
                @php
                    $pendingCampaignsCount = \App\Models\Campaign::where('status', 'Offline')->count();
                    $unresolvedDisputesCount = \App\Models\CampaignWorker::where('is_dispute', true)->where('is_dispute_resolved', false)->count();
                    $totalCampaignBadges = $pendingCampaignsCount + $unresolvedDisputesCount;
                @endphp
                @if($totalCampaignBadges > 0)
                    <span class="nav-main-link-badge badge rounded-pill bg-warning text-dark">{{ $totalCampaignBadges }}</span>
                @endif
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('campaigns') ? 'active' : '' }}" href="{{ url('campaigns') }}">
                        <span class="nav-main-link-name">Active Campaigns</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('campaigns/pending') ? 'active' : '' }}" href="{{ url('campaigns/pending') }}">
                        <span class="nav-main-link-name">Pending Review</span>
                        @if($pendingCampaignsCount > 0)
                            <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $pendingCampaignsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('campaigns/paused') ? 'active' : '' }}" href="{{ url('campaigns/paused') }}">
                        <span class="nav-main-link-name">Paused Campaigns</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('campaigns/completed') ? 'active' : '' }}" href="{{ url('campaigns/completed') }}">
                        <span class="nav-main-link-name">Completed Campaigns</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/campaign/disputes*') ? 'active' : '' }}" href="{{ url('admin/campaign/disputes') }}">
                        <span class="nav-main-link-name">Task Disputes</span>
                        @if($unresolvedDisputesCount > 0)
                            <span class="badge rounded-pill bg-danger ms-auto">{{ $unresolvedDisputesCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('campaigns/flagged') ? 'active' : '' }}" href="{{ url('campaigns/flagged') }}">
                        <span class="nav-main-link-name">Flagged Campaigns</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('unapproved') ? 'active' : '' }}" href="{{ route('unapproved') }}">
                        <span class="nav-main-link-name">Task Proof Approvals</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('create.category') || request()->is('create/category*') ? 'active' : '' }}" href="{{ route('create.category') }}">
                        <span class="nav-main-link-name">Categories</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('campaign.creator.list') ? 'active' : '' }}" href="{{ route('campaign.creator.list') }}">
                        <span class="nav-main-link-name">Campaign Creators</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 7. Job Vacancy -->
        @php
            $pendingJobsCount = \App\Models\JobListing::where('user_posted', true)->where('is_active', false)->whereNull('decision_reason')->count();
        @endphp
        <li class="nav-main-item {{ request()->is('admin/career-hub*') || request()->is('career-hub*') || request()->is('jobs*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-briefcase"></i>
                <span class="nav-main-link-name">Job Vacancy</span>
                @if($pendingJobsCount > 0)
                    <span class="nav-main-link-badge badge rounded-pill bg-warning text-dark">{{ $pendingJobsCount }}</span>
                @endif
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.index') ? 'active' : '' }}" href="{{ route('admin.career-hub.index') }}">
                        <span class="nav-main-link-name">All Vacancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.create') ? 'active' : '' }}" href="{{ route('admin.career-hub.create') }}">
                        <span class="nav-main-link-name">Post Vacancy</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.pending') ? 'active' : '' }}" href="{{ route('admin.career-hub.pending') }}">
                        <span class="nav-main-link-name">Pending Review</span>
                        @if($pendingJobsCount > 0)
                            <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $pendingJobsCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.expired') ? 'active' : '' }}" href="{{ route('admin.career-hub.expired') }}">
                        <span class="nav-main-link-name">Expired Vacancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.declined') ? 'active' : '' }}" href="{{ route('admin.career-hub.declined') }}">
                        <span class="nav-main-link-name">Declined Vacancies</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 8. Career Profiles & Professional -->
        <li class="nav-main-item {{ request()->is('admin/career-profiles*') || request()->is('admin/professional*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-user-tie"></i>
                <span class="nav-main-link-name">Career Profiles</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-profiles*') ? 'active' : '' }}" href="{{ route('admin.career-profiles.index') }}">
                        <span class="nav-main-link-name">Career Profiles</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/professional') ? 'active' : '' }}" href="{{ url('admin/professional') }}">
                        <span class="nav-main-link-name">Professional Hub</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/professional/category*') ? 'active' : '' }}" href="{{ url('admin/professional/category') }}">
                        <span class="nav-main-link-name">Skill Categories</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/professional/list/approved*') ? 'active' : '' }}" href="{{ url('admin/professional/list/approved') }}">
                        <span class="nav-main-link-name">Approved Profiles</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/professional/list/pending*') ? 'active' : '' }}" href="{{ url('admin/professional/list/pending') }}">
                        <span class="nav-main-link-name">Pending Approvals</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/professional/list/denied*') ? 'active' : '' }}" href="{{ url('admin/professional/list/denied') }}">
                        <span class="nav-main-link-name">Denied Profiles</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 9. Operations -->
        <li class="nav-main-item {{ request()->is('admin/banner*') || request()->is('admin/blogs*') || request()->is('admin/business*') || request()->is('admin/safelock*') || request()->is('admin/spin*') || request()->is('admin/finger*') || request()->is('admin/partner*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-layer-group"></i>
                <span class="nav-main-link-name">Operations</span>
                @php $bannerCount = \App\Models\Banner::where('status', false)->count(); @endphp
                @if($bannerCount > 0)
                    <span class="nav-main-link-badge badge rounded-pill bg-info">{{ $bannerCount }}</span>
                @endif
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/banner/list') ? 'active' : '' }}" href="{{ url('admin/banner/list') }}">
                        <span class="nav-main-link-name">Banner Ads</span>
                        @if($bannerCount > 0)
                            <span class="badge rounded-pill bg-info ms-auto">{{ $bannerCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.blogs*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                        <span class="nav-main-link-name">Blogs</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/business*') ? 'active' : '' }}" href="{{ url('admin/business') }}">
                        <span class="nav-main-link-name">Business Accounts</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/safelock*') || request()->is('admin/partner*') ? 'active' : '' }}" href="{{ url('admin/safelock') }}">
                        <span class="nav-main-link-name">Safelock & Partners</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/spin*') || request()->is('admin/finger*') ? 'active' : '' }}" href="{{ url('admin/spin') }}">
                        <span class="nav-main-link-name">Interactive Games</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 10. Support & Messages -->
        <li class="nav-main-item {{ request()->is('admin/feedback*') || request()->is('mass/mail*') || request()->is('admin/notifications*') || request()->is('admin/knowledgebase*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-comments"></i>
                <span class="nav-main-link-name">Support & Comms</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}" href="{{ route('admin.feedback') }}">
                        <span class="nav-main-link-name">Feedbacks</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('mass.mail') ? 'active' : '' }}" href="{{ route('mass.mail') }}">
                        <span class="nav-main-link-name">Mass Email</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/notifications') ? 'active' : '' }}" href="{{ url('admin/notifications') }}">
                        <span class="nav-main-link-name">Notifications</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/knowledgebase') ? 'active' : '' }}" href="{{ url('admin/knowledgebase') }}">
                        <span class="nav-main-link-name">Knowledge Base</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 11. Settings & Staff -->
        <li class="nav-main-item {{ request()->is('staff*') || request()->is('preferences*') || request()->is('audit/trail*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-cog"></i>
                <span class="nav-main-link-name">Settings & Staff</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('staff.list') || request()->routeIs('staff.create') ? 'active' : '' }}" href="{{ route('staff.list') }}">
                        <span class="nav-main-link-name">Staff Management</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('staff.salary') ? 'active' : '' }}" href="{{ route('staff.salary') }}">
                        <span class="nav-main-link-name">Process Salary</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('preferences') || request()->is('audit/trail') ? 'active' : '' }}" href="{{ url('preferences') }}">
                        <span class="nav-main-link-name">Preferences & Audit</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>

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

        <!-- 3. Financials & Wallets -->
        <li class="nav-main-item {{ request()->is('admin/withdrawal*') || request()->is('admin/manual/fundings*') || request()->is('admin/transaction*') || request()->is('user/transaction*') || request()->is('currencies*') || request()->is('conversion-rates*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-wallet"></i>
                <span class="nav-main-link-name">Financials</span>
                @php
                    $pendingWithdrawals = \App\Models\Withrawal::where('status', false)->count();
                @endphp
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

        <!-- 4. Campaigns & Tasks -->
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

        <!-- 5. Job & Career Hub -->
        <li class="nav-main-item {{ request()->is('admin/career*') || request()->is('admin/professional*') || request()->is('jobs*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-briefcase"></i>
                <span class="nav-main-link-name">Jobs & Career</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.index') || request()->routeIs('admin.career-hub.pending') ? 'active' : '' }}" href="{{ route('admin.career-hub.index') }}">
                        <span class="nav-main-link-name">Job Vacancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-hub.create') ? 'active' : '' }}" href="{{ route('admin.career-hub.create') }}">
                        <span class="nav-main-link-name">Post Vacancy</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.career-profiles*') ? 'active' : '' }}" href="{{ route('admin.career-profiles.index') }}">
                        <span class="nav-main-link-name">Career Profiles</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/professional*') ? 'active' : '' }}" href="{{ url('admin/professional') }}">
                        <span class="nav-main-link-name">Professional Jobs</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 6. Operations & Features -->
        <li class="nav-main-item {{ request()->is('admin/business*') || request()->is('admin/safelock*') || request()->is('admin/spin*') || request()->is('admin/finger*') || request()->is('admin/partner*') || request()->is('admin/banner*') || request()->is('admin/blogs*') ? 'open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-layer-group"></i>
                <span class="nav-main-link-name">Operations</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/business*') ? 'active' : '' }}" href="{{ url('admin/business') }}">
                        <span class="nav-main-link-name">Business Accounts</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('admin/banner/list') ? 'active' : '' }}" href="{{ url('admin/banner/list') }}">
                        <span class="nav-main-link-name">Banner Ads</span>
                        @php $bannerCount = \App\Models\Banner::where('status', false)->count(); @endphp
                        @if($bannerCount > 0)
                            <span class="badge rounded-pill bg-info ms-auto">{{ $bannerCount }}</span>
                        @endif
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
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.blogs*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                        <span class="nav-main-link-name">Blogs</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 7. Support & Messages -->
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

        <!-- 8. Settings & Staff -->
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

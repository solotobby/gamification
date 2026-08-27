<div class="content-side">
    <ul class="nav-main">


        <li class="nav-main-heading">Main Menu</li>
        <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('home') }}">
                <i class="nav-main-link-icon fa fa-home"></i>
                <span class="nav-main-link-name">Dashboard</span>
                {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">8</span> --}}
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-star"></i>
                <span class="nav-main-link-name">Campaigns</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ url('campaigns') }}">
                        <span class="nav-main-link-name">Live</span>
                    </a>
                    <a class="nav-main-link" href="{{ url('campaigns/pending') }}">
                        <span class="nav-main-link-name">Pending</span>
                    </a>
                    <a class="nav-main-link" href="{{ url('campaigns/paused') }}">
                        <span class="nav-main-link-name">Paused</span>
                    </a>
                    <a class="nav-main-link" href="{{ url('campaigns/denied') }}">
                        <span class="nav-main-link-name">Denied</span>
                    </a>
                    <a class="nav-main-link" href="{{ url('campaigns/flagged') }}">
                        <span class="nav-main-link-name">Flagged</span>
                    </a>
                    <a class="nav-main-link" href="{{ url('campaigns/completed') }}">
                        <span class="nav-main-link-name">Completed</span>
                    </a>
                    {{-- <a class="nav-main-link" href="{{ url('admin/campaign/metrics') }}">
                        <span class="nav-main-link-name">Metrics</span>
                    </a> --}}
                    <a class="nav-main-link" href="{{ url('admin/campaign/disputes') }}">
                        <span class="nav-main-link-name">In Dispute</span>
                    </a>
                    {{-- <a class="nav-main-link" href="{{ route('campaign.creator.list') }}">
                        <span class="nav-main-link-name">Creator List</span>
                    </a> --}}

                    <a class="nav-main-link" href="{{ route('unapproved') }}">
                        <span class="nav-main-link-name">Unapproved Jobs</span>
                    </a>

                </li>
            </ul>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-briefcase"></i>
                <span class="nav-main-link-name">Jobs</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('approved') }}">
                        <span class="nav-main-link-name">Approved</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('unapproved') }}">
                        <span class="nav-main-link-name">Unapproved</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-list"></i>
                <span class="nav-main-link-name">Categories</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('create.category') }}">
                        <span class="nav-main-link-name">Create</span>
                    </a>
                </li>
            </ul>
        </li> --}}

        <li class="nav-main-item">
            <a class="nav-main-link {{ request()->routeIs('admin.blogs*') ? 'active' : '' }}"
                href="{{ route('admin.blogs.index') }}">
                <i class="nav-main-link-icon si si-book-open"></i>
                <span class="nav-main-link-name">Blog</span>
                {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">{{ App\Models\Blog::where('status',
                    'draft')->count() }}</span> --}}
            </a>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('preferences') }}">
                <i class="nav-main-link-icon fa fa-star-of-life"></i>
                <span class="nav-main-link-name">Preferences</span>
            </a>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-list"></i>
                <span class="nav-main-link-name">Users</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('user.list') }}">
                        <span class="nav-main-link-name">All</span>
                    </a>
                    <a class="nav-main-link" href="{{ route('verified.user.list') }}">
                        <span class="nav-main-link-name">Verified</span>
                    </a>
                    <a class="nav-main-link" href="{{ route('user.email.verified') }}">
                        <span class="nav-main-link-name">Email Verified</span>
                    </a>
                    <a class="nav-main-link" href="{{ route('usd.verified.user.list') }}">
                        <span class="nav-main-link-name">USD Verified</span>
                    </a>

                </li>
            </ul>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('admin/banner/list') }}">
                <i class="nav-main-link-icon fa fa-star-of-life"></i>
                <span class="nav-main-link-name">Banner Ad</span>
                <?php
$count = \App\Models\Banner::where('status', false)->count();
          ?>
                <span class="nav-main-link-badge badge rounded-pill bg-default">{{ $count }}</span>
            </a>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-briefcase"></i>
                <span class="nav-main-link-name">Job Vacancy</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.career-hub.index') }}">
                        <span class="nav-main-link-name">List</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.career-hub.pending') }}">
                        <span class="nav-main-link-name">Pending Approvals Vacancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.career-hub.declined') }}">
                        <span class="nav-main-link-name">Declined Vacancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.career-hub.expired') }}">
                        <span class="nav-main-link-name">Expired Vacancies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.career-hub.create') }}">
                        <span class="nav-main-link-name">Create Vacancy</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon fa fa-table"></i>
                <span class="nav-main-link-name">Withdrawals</span>
                <span
                    class="nav-main-link-badge badge rounded-pill bg-default">{{ App\Models\Withrawal::where('status', false)->count() }}</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.withdrawal.queued.current') }}">
                        <span class="nav-main-link-name">Queued this Week</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.withdrawal.queued') }}">
                        <span class="nav-main-link-name">Queued</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.withdrawal') }}">
                        <span class="nav-main-link-name">Sent</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon si si-note"></i>
                <span class="nav-main-link-name">Feedbacks</span>
                <span class="nav-main-link-badge badge rounded-pill bg-default">{{ App\Models\Feedback::where('status',
                    false)->count() }}</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.feedback.unread') }}">
                        <span class="nav-main-link-name">Unread</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('admin.feedback') }}">
                        <span class="nav-main-link-name">Read</span>
                    </a>
                </li>
            </ul>
        </li> --}}

        <li class="nav-main-item">
            <a class="nav-main-link {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}"
                href="{{ route('admin.feedback') }}">
                <i class="nav-main-link-icon si si-note"></i>
                <span class="nav-main-link-name">Feedbacks</span>
                {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">{{
                    App\Models\Feedback::where('status', false)->count() }}</span> --}}
                {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">{{
    App\Models\Feedback::whereRaw('(
                            select fr.user_id from feedback_replies fr
                            where fr.feedback_id = feedback.id
                            order by fr.id desc
                            limit 1
                        ) = feedback.user_id')->count() }}</span> --}}
            </a>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="false" href="#">
                <i class="nav-main-link-icon si si-note"></i>
                <span class=" nav-main-link-name">Fraud Mgt</span>

            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ url('remove/duplicate/account') }}">
                        <span class="nav-main-link-name">Remove Duplicate Account</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('staff.payslip') }}">
                <i class="nav-main-link-icon fa fa-file-invoice-dollar"></i>
                <span class="nav-main-link-name">Pay Slip</span>
            </a>
        </li>

    </ul>
</div>

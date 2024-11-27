<div class="content-side">
    <ul class="nav-main">
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('home') }}">
          <i class="nav-main-link-icon fa fa-home"></i>
          <span class="nav-main-link-name">Dashboard</span>
          {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">8</span> --}}
        </a>
      </li> 

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-th"></i>
          <span class="nav-main-link-name">Business</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('admin/business') }}">
              <span class="nav-main-link-name">View</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('admin/business/category') }}">
              <span class="nav-main-link-name">Category</span>
            </a>
          </li>
        </ul>
      </li>


      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/safelock') }}">
          <i class="nav-main-link-icon fa fa-th"></i>
          <span class="nav-main-link-name">Safelock Mgt</span>
        </a>
      </li> 

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/finger') }}">
          <i class="nav-main-link-icon fa fa-th"></i>
          <span class="nav-main-link-name">Fastest Finger</span>
        </a>
      </li> 

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/partner') }}">
          <i class="nav-main-link-icon fa fa-th"></i>
          <span class="nav-main-link-name">Partners</span>
        </a>
      </li> 

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
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
            <a class="nav-main-link" href="{{ url('campaigns/denied') }}">
              <span class="nav-main-link-name">Denied</span>
            </a>
            <a class="nav-main-link" href="{{ url('campaigns/completed') }}">
              <span class="nav-main-link-name">Completed</span>
            </a>
            <a class="nav-main-link" href="{{ url('admin/campaign/metrics') }}">
              <span class="nav-main-link-name">Metrics</span>
            </a>
            <a class="nav-main-link" href="{{ url('admin/campaign/disputes') }}">
              <span class="nav-main-link-name">In Dispute</span>
            </a>
            <a class="nav-main-link" href="{{ route('campaign.creator.list') }}">
              <span class="nav-main-link-name">Creator List</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
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

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('preferences') }}">
          <i class="nav-main-link-icon fa fa-star-of-life"></i>
          <span class="nav-main-link-name">Preferences</span>
        </a>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/notifications') }}">
          <i class="nav-main-link-icon fa fa-bell"></i>
          <span class="nav-main-link-name">Notifications</span>
        </a>
      </li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('currency') }}">
          <i class="nav-main-link-icon fa fa-money-bill-wave"></i>
          <span class="nav-main-link-name">Currency</span>
        </a>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('conversions') }}">
          <i class="nav-main-link-icon fa fa-money-bill-wave"></i>
          <span class="nav-main-link-name">Rate</span>
        </a>
      </li>



      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
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
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
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
            <a class="nav-main-link" href="{{ route('usd.verified.user.list') }}">
              <span class="nav-main-link-name">USD Verified</span>
            </a>
            
            
          </li>
        </ul>
      </li>

      

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-table"></i>
          <span class="nav-main-link-name">Transactions</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('admin.transaction') }}">
              <span class="nav-main-link-name">Admin List</span>
            </a>
            <a class="nav-main-link" href="{{ route('user.transaction') }}">
              <span class="nav-main-link-name">Users List</span>
            </a>
          </li>
        </ul>
      </li>

      {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-snowflake"></i>
          <span class="nav-main-link-name">Market Place</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('marketplace.create.product') }}">
              <span class="nav-main-link-name">Create Product</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('view.admin.marketplace') }}">
              <span class="nav-main-link-name">View Products</span>
            </a>
          </li>
        </ul>
      </li>
       --}}

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-users"></i>
          <span class="nav-main-link-name">Staff Mgt.</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('staff.create') }}">
              <span class="nav-main-link-name">Create</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('staff.list') }}">
              <span class="nav-main-link-name">View</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('staff.salary') }}">
              <span class="nav-main-link-name">Process Salary</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-table"></i>
          <span class="nav-main-link-name">Withdrawals</span>
          <span class="nav-main-link-badge badge rounded-pill bg-default">{{ App\Models\Withrawal::where('status', false)->count() }}</span>
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
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon si si-note"></i>
          <span class="nav-main-link-name">Accounts</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('account.view') }}">
              <span class="nav-main-link-name">View</span>
            </a>
          </li>
        </ul> --}}
      </li>

      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('mass.mail') }}">
          <i class="nav-main-link-icon fa fa-envelope"></i>
          <span class="nav-main-link-name">Mass Mail</span>
          <span class="nav-main-link-badge badge rounded-pill bg-default">8</span>
        </a>
      </li> --}}
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/banner/list') }}">
          <i class="nav-main-link-icon fa fa-envelopes"></i>
          <span class="nav-main-link-name">Banner Ad</span>
          <?php 
            $count = \App\Models\Banner::where('status', false)->count();
          ?>
          <span class="nav-main-link-badge badge rounded-pill bg-default">{{ $count }}</span>
        </a>
      </li>

      

      {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-cogs"></i>
          <span class="nav-main-link-name">Points</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('admin.points') }}">
              <span class="nav-main-link-name">Create</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('admin.points.redeemed') }}">
              <span class="nav-main-link-name">Redeemed</span>
            </a>
          </li>
        </ul>
      </li> --}}

      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('mass.sms') }}">
          <i class="nav-main-link-icon fa fa-envelope"></i>
          <span class="nav-main-link-name">BroadCast SMS</span>
         
        </a>
      </li> --}}
     
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('audit/trail') }}">
          <i class="nav-main-link-icon fa fa-th"></i>
          <span class="nav-main-link-name">Audit Trail</span>
        </a>
      </li>

       {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('settings') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Settings</span>
        </a>
      </li> --}}

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon si si-note"></i>
          <span class="nav-main-link-name">Feedbacks</span>
          <span class="nav-main-link-badge badge rounded-pill bg-default">{{ App\Models\Feedback::where('status', false)->count() }}</span>
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
      </li>

      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/virtual/list') }}">
          <i class="nav-main-link-icon fa fa-th"></i>
          <span class="nav-main-link-name">Virtual Accounts</span>
        </a>
      </li> --}}

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('user.tracker') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">User Tacker</span>
        </a>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('admin/knowledgebase') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Knowledge Base</span>
        </a>
      </li>


    </ul>
  </div>
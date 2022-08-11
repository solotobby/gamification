<div class="content-side">
    <ul class="nav-main">
      
      
      <li class="nav-main-heading">Main Menu</li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('home') }}">
          <i class="nav-main-link-icon fa fa-location-arrow"></i>
          <span class="nav-main-link-name">Dashboard</span>
          {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">8</span> --}}
        </a>
      </li>
      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-list"></i>
          <span class="nav-main-link-name">Jobs</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('home') }}">
              <span class="nav-main-link-name">Available Jobs</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{route('my.jobs')}}">
              <span class="nav-main-link-name">My Jobs</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('completed/jobs') }}">
              <span class="nav-main-link-name">Completed Job</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-briefcase"></i>
          <span class="nav-main-link-name">Post Campaign</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('campaign.create') }}">
              <span class="nav-main-link-name">Create Campaign</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('my.campaigns') }}">
              <span class="nav-main-link-name">View Campaigns</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('approved/campaigns') }}">
              <span class="nav-main-link-name">Approved Campaigns</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('denied/campaigns') }}"">
              <span class="nav-main-link-name">Denied Campaigns</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-wallet"></i>
          <span class="nav-main-link-name">Wallet</span>
           <span class="nav-main-link-badge badge rounded-pill bg-default">{{ number_format(auth()->user()->wallet->balance) }}</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="be_blocks_styles.html">
              <span class="nav-main-link-name">Fund Wallet</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="be_blocks_options.html">
              <span class="nav-main-link-name">Withdraw</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-users"></i>
          <span class="nav-main-link-name">Referral</span>
           <span class="nav-main-link-badge badge rounded-pill bg-default">{{ auth()->user()->referees()->count(); }}</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{route('ref.all')}}">
              <span class="nav-main-link-name">View All</span>
            </a>
          </li>
          {{-- <li class="nav-main-item">
            <a class="nav-main-link" href="be_blocks_options.html">
              <span class="nav-main-link-name">Withdraw</span>
            </a>
          </li> --}}
        </ul>
      </li>

    </ul>
  </div>
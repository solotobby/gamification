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
          <i class="nav-main-link-icon fa fa-location-arrow"></i>
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
          </li>
        </ul>
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

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-snowflake"></i>
          <span class="nav-main-link-name">Market Place</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="">
              <span class="nav-main-link-name">Create Product</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('admin.withdrawal') }}">
          <i class="nav-main-link-icon fa fa-table"></i>
          <span class="nav-main-link-name">Withdrawal Requests</span>
          {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">8</span> --}}
        </a>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('unapproved') }}">
          <i class="nav-main-link-icon fa fa-table"></i>
          <span class="nav-main-link-name">UnApproved Jobs</span>
          {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">8</span> --}}
        </a>
      </li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('mass.mail') }}">
          <i class="nav-main-link-icon fa fa-envelope"></i>
          <span class="nav-main-link-name">Mass Mail</span>
          {{-- <span class="nav-main-link-badge badge rounded-pill bg-default">8</span> --}}
        </a>
      </li>
      


    </ul>
  </div>
<div class="content-side">
    <ul class="nav-main">
      <li class="nav-main-heading">Main Menu</li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('home') }}">
          <i class="nav-main-link-icon fa fa-location-arrow"></i>
          <span class="nav-main-link-name">Dashboard</span>
          <?php 
          $count = App\Helpers\SystemActivities::badgeCount();
          $color = '';
            if($count == 10){
              $color = 'blue';
            }elseif($count >= 11 && $count <= 20){
              $color = 'silver';
            }elseif($count >= 21 && $count <= 50){
              $color = 'gold';
            }else{
              $color = 'antiquewhite';
            }
          ?>
          {{-- <span class="nav-main-link-badge badge rounded-pill "> <i class="fa fa-star fa-lg" aria-hidden="true" style="color: {{$color}}"></i> </span> --}}
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
          
          @if(auth()->user()->wallet->base_currency == "Naira")
          <span class="nav-main-link-badge badge rounded-pill bg-default">&#8358;{{ @number_format(auth()->user()->wallet->balance) }}</span>
          @else
          <span class="nav-main-link-badge badge rounded-pill bg-default">${{ number_format(auth()->user()->wallet->usd_balance,2) }}</span>
          @endif
           
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('fund') }}">
              <span class="nav-main-link-name">Fund Wallet</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('withdraw') }}">
              <span class="nav-main-link-name">Withdraw</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('withdraw.requests') }}">
              <span class="nav-main-link-name">Withdrawal Requests</span>
            </a>
          </li>
        </ul>
      </li>

      {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-ring"></i>
          <span class="nav-main-link-name">Market Place</span>
           <span class="nav-main-link-badge badge rounded-pill bg-default">&#8358;{{ number_format(auth()->user()->wallet->balance) }}</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('my.marketplace.products') }}">
              <span class="nav-main-link-name">My Products</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('marketplace') }}">
              <span class="nav-main-link-name">View</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('create.marketplace') }}">
              <span class="nav-main-link-name">Create</span>
            </a>
          </li>
        </ul>
      </li> --}}
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
        </ul>
      </li>
      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('airtime') }}">
          <i class="nav-main-link-icon fa fa-phone"></i>
          <span class="nav-main-link-name">Buy Airtime</span>
        </a>
      </li> --}}
      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('points') }}">
          <i class="nav-main-link-icon fa fa-gifts"></i>
          <span class="nav-main-link-name">Login Points</span>
        </a>
      </li> --}}


       {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('badge') }}">
          <i class="nav-main-link-icon si si-badge"></i>
          <span class="nav-main-link-name">Badge</span>
        </a>
      </li> --}}

      
      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('databundle') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Buy DataBundle</span>
        </a>
      </li> --}}

        <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('converter') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Currency Converter</span>
        </a>
      </li>
      
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('transactions') }}">
          <i class="nav-main-link-icon fa fa-table"></i>
          <span class="nav-main-link-name">Transactions List</span>
        </a>
      </li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('howto') }}">
          <i class="nav-main-link-icon fa fa-grip-vertical"></i>
          <span class="nav-main-link-name">How to Approve Jobs</span>
        </a>
      </li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="https://vm.tiktok.com/ZM2v58wXg/" target="_blank">
          <i class="nav-main-link-icon fa fa-grip-vertical"></i>
          <span class="nav-main-link-name">How to Complete Tasks</span>
        </a>
      </li>

      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('instruction') }}">
          <i class="nav-main-link-icon fa fa-award"></i>
          <span class="nav-main-link-name">Win Weekly Prizes</span>
        </a>
      </li> --}}

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('feedback') }}">
          <i class="nav-main-link-icon fa fa-paper-plane"></i>
          <span class="nav-main-link-name">Talk to Us</span>
          
        </a>
      </li>
    </ul>
  </div>
<div class="content-side">
    <ul class="nav-main">
      <li class="nav-main-heading">Main Menu</li>
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('home') }}">
          <i class="nav-main-link-icon fa fa-home"></i>
          <span class="nav-main-link-name">Dashboard</span>
          <?php 
          $badge = badge();
          ?>
          <span class="nav-main-link-badge badge rounded-pill "> <i class="fa fa-star fa-lg" aria-hidden="true" style="color: {{$badge['color']}}"></i> </span>
        </a>
      </li>

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('fastest/finger') }}">
          <i class="nav-main-link-icon fa fa-fingerprint"></i>
          <span class="nav-main-link-name">Fastest Finger</span>   
          <span class="nav-main-link-badge badge rounded-pill bg-default">New</span>
        </a>
      </li> 

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('user/business') }}">
          <i class="nav-main-link-icon fa fa-briefcase"></i>
          <span class="nav-main-link-name">Promote Business</span>   
          <span class="nav-main-link-badge badge rounded-pill "> <i class="fa fa-star fa-lg" aria-hidden="true" style="color: goldenrod"></i> </span>
        </a>
      </li>  

    {{-- <li class="nav-main-item">
      <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
        <i class="nav-main-link-icon fa fa-star"></i>
        <span class="nav-main-link-name">Promote Business</span>
        <span class="nav-main-link-badge badge rounded-pill "> <i class="fa fa-star fa-lg" aria-hidden="true" style="color: goldenrod"></i> </span>
      </a>
      <ul class="nav-main-submenu">
        <li class="nav-main-item">
          <a class="nav-main-link" href="{{ route('create.business') }}">
            <span class="nav-main-link-name">Setup Business</span>
          </a>
          <a class="nav-main-link" href="{{ route('create.product') }}">
            <span class="nav-main-link-name">Add Product</span>
          </a>
        </li>
      </ul>
    </li>  --}}


    {{-- <li class="nav-main-item">
      <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
        <i class="nav-main-link-icon fa fa-star"></i>
        <span class="nav-main-link-name">Achievers</span>
      </a>
      <ul class="nav-main-submenu">
        <li class="nav-main-item">
          <a class="nav-main-link" href="{{url('top/earners')}}">
            <span class="nav-main-link-name">Top Earners</span>
          </a>
          
         
        </li>
      </ul>
    </li>  --}}

     

      {{-- <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-snowflake"></i>
          <span class="nav-main-link-name">Surveys</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{url('create/survey')}}">
              <span class="nav-main-link-name">Create</span>
            </a>
            <a class="nav-main-link" href="{{url('list/survey')}}">
              <span class="nav-main-link-name">List</span>
            </a>
           
          </li>
        </ul>
      </li> 
 --}}


      @if(auth()->user()->wallet->base_currency == 'Naira')
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('safelock') }}">
          <i class="nav-main-link-icon fa fa-snowflake"></i>
          <span class="nav-main-link-name">Safelock Funds</span>   
         
        </a>
      </li>
     

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-star"></i>
          <span class="nav-main-link-name">Create Banner Ads</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{url('banner/create')}}">
              <span class="nav-main-link-name">Create</span>
            </a>
            <a class="nav-main-link" href="{{url('banner')}}">
              <span class="nav-main-link-name">List</span>
            </a>
           
          </li>
        </ul>
      </li> 
      @endif

      <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-list"></i>
          <span class="nav-main-link-name" style="color:gr">Jobs</span>
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
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ url('disputed/jobs') }}">
              <span class="nav-main-link-name">Disputed Jobs</span>
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
          <span class="nav-main-link-badge badge rounded-pill bg-default"> &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}</span>
            @elseif(auth()->user()->wallet->base_currency == 'GHS')
            <span class="nav-main-link-badge badge rounded-pill bg-default"> &#8373;{{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>

            @elseif(auth()->user()->wallet->base_currency == 'KES')
            <span class="nav-main-link-badge badge rounded-pill bg-default"> KES {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>
            @elseif(auth()->user()->wallet->base_currency == 'TZS')
            <span class="nav-main-link-badge badge rounded-pill bg-default">TZS {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>
            @elseif(auth()->user()->wallet->base_currency == 'RWF')
            <span class="nav-main-link-badge badge rounded-pill bg-default"> RWF {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>
            @elseif(auth()->user()->wallet->base_currency == 'MWK')
            <span class="nav-main-link-badge badge rounded-pill bg-default">MWK {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>
            @elseif(auth()->user()->wallet->base_currency == 'UGX')
            <span class="nav-main-link-badge badge rounded-pill bg-default">UGX {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>
            @elseif(auth()->user()->wallet->base_currency == 'ZAR')
            <span class="nav-main-link-badge badge rounded-pill bg-default">ZAR {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}</span>
           
            @else  

            <span class="nav-main-link-badge badge rounded-pill bg-default">${{ number_format(auth()->user()->wallet->usd_balance,2) }}</span>
          @endif
          
          {{-- @if(auth()->user()->wallet->base_currency == "Naira")
          <span class="nav-main-link-badge badge rounded-pill bg-default">&#8358;{{ @number_format(auth()->user()->wallet->balance) }}</span>
          @else
          <span class="nav-main-link-badge badge rounded-pill bg-default">${{ number_format(auth()->user()->wallet->usd_balance,3) }}</span>
          @endif --}}
           
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
            @if(auth()->user()->wallet->base_currency == 'Naira')
            <a class="nav-main-link" href="{{route('ref.all')}}">
              <span class="nav-main-link-name">View All</span>
            </a>
            @else
            <a class="nav-main-link" href="{{route('ref.usd')}}">
              <span class="nav-main-link-name">View All</span>
            </a>
            @endif
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

      @if(auth()->user()->wallet->base_currency == 'Naira')
       <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('badge') }}">
          <i class="nav-main-link-icon si si-badge"></i>
          <span class="nav-main-link-name">Badge</span>
        </a>
      </li>


     


        <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('converter') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Currency Converter</span>
        </a>
      </li>
      @endif

      {{-- <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('databundle') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Buy DataBundle</span>
        </a>
      </li> --}}
      
      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ route('transactions') }}">
          <i class="nav-main-link-icon fa fa-table"></i>
          <span class="nav-main-link-name">Transactions List</span>
        </a>
      </li>


      
       <li class="nav-main-item">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
          <i class="nav-main-link-icon fa fa-ring"></i>
          <span class="nav-main-link-name">Tutorials</span>
        </a>
        <ul class="nav-main-submenu">
          <li class="nav-main-item">
            <a class="nav-main-link" href="{{ route('howto') }}">
              <span class="nav-main-link-name">How to Approve Jobs</span>
            </a>
          </li>
          <li class="nav-main-item">
            <a class="nav-main-link" href="https://vm.tiktok.com/ZM6f5mU2a/">
              <span class="nav-main-link-name">How to Complete Tasks</span>
            </a>
          </li>
          
        </ul>
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

      <li class="nav-main-item">
        <a class="nav-main-link" href="{{ url('knowledgebase') }}">
          <i class="nav-main-link-icon fa fa-tty"></i>
          <span class="nav-main-link-name">Knowledge Base</span>
        </a>
      </li>
    </ul>
  </div>
@extends('layouts.main.master')
@section('content')

 <!-- Page Content -->
    <div class="row g-0 justify-content-center bg-black-75">
      <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
        <!-- Reminder Block -->
        <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
          <div class="block-content block-content-full px-lg-5 px-xl-6 py-4 py-md-5 py-lg-6 bg-body-extra-light">
            <!-- Header -->
            <div class="mb-2 text-center">
                <p class="text-uppercase fw-bold fs-sm text-muted">Fund Wallet</p>
                <p class="link-fx fw-bold fs-1">
                    &#8358;{{ number_format(auth()->user()->wallet->balance) }}
                </p>
                <p>Wallet Balance</p>
            </div>
            <!-- END Header -->

            <!-- Reminder Form -->
            <form class="js-validation-reminder" action="{{ route('store.funds') }}" method="POST">
                @csrf
              <div class="mb-4">
                <div class="input-group input-group-lg">
                  <input type="number" class="form-control" id="reminder-credential" name="balance" min="1000" placeholder="Enter Amount" required>
                </div>
              </div>
              <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Fund Wallet
                </button>
              </div>
            </form>
            <!-- END Reminder Form -->
          </div>
        </div>
        <!-- END Reminder Block -->
      </div>
    </div>
  <!-- END Page Content -->

@endsection

@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>
@endsection



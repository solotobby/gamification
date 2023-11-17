@extends('layouts.main.master')
@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
            Christmas Giveaway
        </h1>
      </div>
    </div>
  </div>

 
  <div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">GIVEAWAY</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <img src="{{asset('xmas.jpg') }}" class="img-responsive img-thumbnail">

        <br><br>
        <h3>CHRISTMAS GIVEAWAY</h3>

        <p>
            As part of our dreams to put smiles on the faces of our numerous users, we are giving out
            <strong>100 LIVE CHICKENS and 50 Bags of Rice</strong> to very active users this christmas.
         </p>

         <p>
             <b>
                 Please read the steps to claim yours below
             </b>
                 <br>
                 1. Users with 100 verified referrals between November 20th and December 20th, 2023 will be given 1 LIVE CHICKEN (in addition to their referral bonus).<br>
                 2. Users with 300 verified referrals between November 20th and December 20th, 2023 will be given 1 BAG of RICE  (in addition to their referral bonus).<br>
                 3. This giveaway is currently applicable to users globally. Users in Nigeria will provide their full address and active phone numbers for delivery through our agents.<br>
                 4. Only verified referrals between November 20th and December 20th, 2023 will be considered<br>
                 5. Additional monthly referral bonus for bronze, silver and gold membership will not apply during this period<br>
                 6. Users will be able to track their number of referrals from their dashboard. You are free to use any social media, blogs or paid adverts to promote your referral links<br>
                 7. Please provide your full address and active phone numbers below to receive your gift items. Thank you for using FREEBYZ<br>
             <br>
         </p>
         <hr>
         @if(!auth()->user()->profile->is_xmas)
         <form action="{{ url('christmas') }}" method="POST">
            @csrf
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="mb-4">
                    <label for="exampleInputEmail1">Address</label>
                    <input type="text" class="form-control" required name="address" aria-describedby="emailHelp"  placeholder="Enter Address">
                    <small id="emailHelp" class="form-text text-muted">Enter the address you want your chicken delivered</small>
                </div>
                <div class="mb-4">
                    <label for="exampleInputEmail1">Phone Number</label>
                    <input type="text" class="form-control" required name="phone" aria-describedby="emailHelp" placeholder="Enter Phone Number">
                    {{-- <small id="emailHelp" class="form-text text-muted">Enter the address you want your chicken delivered</small> --}}
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="col-lg-3"></div>
          </form>
          @else

            <div class="alert alert-info">
                You have successfully declared an interest in the Christmas Giveaway!
            </div>

          @endif

      </div>
    </div>
  </div>
@endsection
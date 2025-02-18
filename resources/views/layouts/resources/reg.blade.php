<div class="col-md-12 mb-3">
    <label>First Name</label>
    <input id="text" type="text" class="form-control intput-lg @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required placeholder="Enter First Name" >
    @error('first_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 mb-3">
    <label>Last Name</label>
    <input id="text" type="text" class="form-control intput-lg @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required placeholder="Enter Last Name" >
    @error('last_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 mb-3">
    <label>Email Address</label>
    <input id="email" type="email" class="form-control intput-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter Email Address" >
    @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<?php 
    // $loc = \App\Helpers\PaystackHelpers::getLocation();
    // $loc->countryName;
?>
<div class="col-md-12 mb-3">
    <label>Phone Number</label>
   
   @if(env('APP_ENV')  == 'local_test')
    <input type="text" name="phone" id="phone_numbers" class="form-control" placeholder="Enter Phone number" value="{{old('phone')}}" required>
   @else
    <?php 
        $curLocation = currentLocation();
    ?>
        @if($curLocation == 'Nigeria')
        <input type="text" name="phone" id="phone_numbers" class="form-control" placeholder="08070000000" value="{{old('phone')}}" required>
        @else
        <input type="text" name="phone" id="phone_numbers" class="form-control" placeholder="Enter Phone number" value="{{old('phone')}}" required>
        @endif
        {{-- <input type="tel" name="phone_number[full]" id="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone')}}" required size="100%"  /> --}}
        @error('phone_number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    @endif
</div>

<div class="col-md-12 mb-3">
    <label>Select Country</label>
    @include('layouts.resources.countries')
    {{-- <select class="selectpicker countrypicker form-control" data-flag="true" data-live-search="true" required name="country"></select> --}}
</div>

<div class="col-md-12 mb-3">
    <label>Password</label>
    <input id="passwordj" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter Password">
    @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 mb-3">
    <label>Confirm Password</label>
    <input id="password" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required placeholder="Repeat Password">
</div>
<div class="col-md-12 mb-3">
    <label>How did you hear about Freebyz.com</label>
   <select class="form-control" name="source" required>
        <option value="">Select One</option>
        <option>Facebook</option>
        <option>WhatsApp</option>
        <option>Youtube</option>
        <option>Instagram</option>
        <option>TikTok</option>
        <option>Twitter</option>
        <option>Online Ads</option>
        <option>Referred by a Friend</option>
   </select>
   
</div>



<input hidden name="ref_id" value="null">

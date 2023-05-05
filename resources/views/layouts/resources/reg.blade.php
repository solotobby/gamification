<div class="col-md-12 form-group">

    <label>Full Name</label>
    <input id="text" type="text" class="form-control intput-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Enter Name" >
    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 form-group">
    <label>Email Address</label>
    <input id="email" type="email" class="form-control intput-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter Email Address" >
    @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 form-group">
    <label>Phone Number</label>
    <input type="tel" name="phone_number[main]" id="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone')}}" required size="100%" />
    {{-- <input id="text" type="text" class="form-control intput-lg @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Enter Phone Number" > --}}
    @error('phone_number')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 form-group">
    <label>Select Country</label>
    @include('layouts.resources.countries')
    {{-- <select class="selectpicker countrypicker form-control" data-flag="true" data-live-search="true" required name="country"></select> --}}
</div>

<div class="col-md-12 form-group">
    <label>Password</label>
    <input id="passwordj" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter Password">
    @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="col-md-12 form-group">
    <label>Confirm Password</label>
    <input id="password" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required placeholder="Repeat Password">
</div>
<div class="col-md-12 form-group">
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

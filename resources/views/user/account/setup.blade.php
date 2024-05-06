@extends('layouts.main.master')
@section('content')
  <!-- Page Content -->
<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Account Setup</h3>
    </div>
  <div class="block-content">
 
    <form class="js-validation-reminder" action="{{ url('setup/account') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-lg-3"></div>
          <div class="col-lg-6">
            @if(!$info)
            <h5>Fill the form below</h5>
            @if (session('success'))
              <div class="alert alert-success" role="alert">
                  {{ session('success') }}
              </div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger" role="alert">
                  {{ session('error') }}
              </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
              <div>
                <label>Enter BVN</label>
                <input type="text" class="form-control @error('bvn') is-invalid @enderror"  siz="12" name="bvn" value="{{ old('bvn') }}" placeholder="e.g 123456789013" required>
              </div>
            </div>

            <div class="mb-4">
              <div>
                <label>Enter Phone Number</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" siz="11" name="phone" value="{{ old('phone') }}" placeholder="e.g 08067551234" required>
              </div>
            </div>

            <div class="mb-4">
              <div>
                <label>Upload your photo</label>
                <input type="file" class="form-control @error('picture') is-invalid @enderror"  name="picture" value="{{ old('picture') }}" required>
              </div>
            </div>

            <div class="text-center mb-4">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-fw fa-arrow-right opacity-50 me-1"></i> Continue
              </button>

            </div>

            @else

            <p>Bank Name: {{ $info->bank_name }}</p>
            <p>Bank Number: {{ $info->account_number }}</p>
            <p>Balance: {{ $info->balance }}</p>

            <a href="{{ url('setup/account') }}" class="btn btn-primary btn-sm btn-primary rounded-pill px-3">
                  <i class="fa fa-fw fa-share opacity-50 me-1"></i>  Get Virtual Naira Card
            </a>



            {{-- <a href="{{ url('setup/account') }}" class="btn btn-primary btn-sm btn-primary rounded-pill px-3">
                  <i class="fa fa-fw fa-share opacity-50 me-1"></i> Get Virtual USD Card
            </a> --}}
           
          

            @endif
          </div>

          <div class="col-lg-3"></div>
       
      </div>
      <!-- END Text -->
    </form>
  </div>
</div>

@endsection

@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>
@endsection


<!-- Pop Out Default Modal
<div class="modal fade" id="modal-default-popout" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
      <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Enter BVN Information</i> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pb-1">
              
              <form class="js-validation-reminder" action="{{ url('setup/account') }}" method="POST">
                @csrf
              
                {{-- <div class="mb-4">
                  <label>First Name</label>
                    <input type="text" class="form-control @error('bvn') is-invalid @enderror" id="reminder-credential"  name="fname" value="{{ old('fname') }}" placeholder="Enter First Name" required>  
                </div>
                <div class="mb-4">
                  <label>Last Name</label>
                    <input type="text" class="form-control @error('bvn') is-invalid @enderror" id="reminder-credential"  name="lname" value="{{ old('fname') }}" placeholder="Enter Last Name" required>  
                </div> --}}

                <div class="mb-4">
                  <label>Phone Number</label>
                    <input type="text" class="form-control @error('bvn') is-invalid @enderror" id="reminder-credential"  name="phone" value="{{ old('phone') }}" placeholder="Enter Phone" required>  
                </div>

                <div class="mb-4">
                  <label>Enter BVN</label>
                    <input type="text" class="form-control @error('bvn') is-invalid @enderror" id="reminder-credential"  name="bvn" value="{{ old('bvn') }}" placeholder="Enter BVN" required>  
                </div>
 
                <button type="submit" class="btn btn-primary">
                  <i class="si si-paper-plane opacity-50 me-1"></i> Setup
                </button>

              </form>
                
            </div>
        </div>
            
            <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
            </div>
        </div>
      </div>
  </div> -->

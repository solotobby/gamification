@extends('layouts.main.master')

@section('content')
<div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Enter Bank Information</h3>
      </div>
    <div class="block-content">

        <form id="contact-form" action="{{ route('save.bank.information') }}" method="post">
            @csrf
          <!-- Text -->
    
    
          <div class="row">
            <div class="col-lg-3"></div>
              <div class="col-lg-6">
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
                    <label>Select Bank Name</label>
                    <div class="input-group">
                        
                    <select class="form-control" name="bank_code" required>
                        <option value="">Select Bank</option>
                        @foreach ($bankList as $bank)
                            <option value="{{ $bank['code'] }}"> {{ $bank['name'] }}</option>
                            {{--  <input type="hidden" name="bank_name" value="{{ $bank['name'] }}">  --}}
                        @endforeach    
                        </select> 
                    </div>
                </div>

                <div class="mb-4">
                    <label>Enter Account Number</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      
                    </span>
                    {{-- <input type="text" class="form-control text-center" id="example-group1-input3" name="example-group1-input3" placeholder="00"> --}}
                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="reminder-credential" name="account_number" placeholder="Enter Account Number" required value="{{ old('account_number') }}">
                    {{-- <span class="input-group-text">,00</span> --}}
                  </div>
                </div>
    
                
                <div class="text-center mb-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save
                  </button>
    
                </div>
              </div>
              <div class="col-lg-3"></div>
           
          </div>
          <!-- END Text -->
        </form>

    </div>
    </div>
</div>

@endsection

@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>
@endsection
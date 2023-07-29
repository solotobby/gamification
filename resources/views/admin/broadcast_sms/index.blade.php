@extends('layouts.main.master')

@section('style')
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
{{-- <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/dropzone/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/flatpickr/flatpickr.min.css')}}"> --}}
{{-- <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
{{-- <script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script> --}}
@endsection

@section('content')
 <!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Send Broadcast SMS</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Broadcast SMS</li>
          </ol>
        </nav>
      </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
        <h3 class="block-title">Send Broadcast SMS</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                <i class="si si-settings"></i>
                </button>
            </div>
        </div>

        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="alert alert-info">
                The sms is sent only to Nigerian contact
                <br>
                
            </div>
            {{-- send.mass.sms --}}
            <form action="{{ route('send.mass.sms') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label>Select Date Range</label>
                    <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                    <input type="date" class="form-control" id="start" name="start_date" placeholder="From" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                    <span class="input-group-text fw-semibold">
                        <i class="fa fa-fw fa-arrow-right"></i>
                    </span>
                    <input type="date" class="form-control" id="end" name="end_date" placeholder="To" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                    </div>
                </div>
                <div class="mb-4">
                    <label>Select Audience</label>
                    <select class="form-control" name="type" id="type" required>
                    <option value="">Select One</option>
                    <option value="unverified">Unverified Users</option>
                    <option value="verified">Verified Users</option>
                    <option value="survey">Unsurveyed</option>
                    </select>
                </div>

                
               
                <div class="mb-4">
                    <label>Enter Message</label>
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="example-text-input-floating" name="message" required>
                        <label class="form-label" for="example-text-input-floating">Enter Message</label>
                    </div>
                    {{-- <textarea id="js-ckeditor5-classic" name="message">{{ old('message') }}</textarea> --}}
                </div>
                Cost Breakdown<hr>
                Total Number of Recipient: <span id="length"></span>
                <br>
                Estimated Amount: &#8358;<span id="amount"></span>
                <br><br>
                <button class="btn btn-primary mb-3" type="submit"><i class="fa fa-envelope"></i> Send SMS </button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('script')
 <!-- Page JS Plugins -->
 {{-- <script src="{{ asset('src/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script> --}}
 {{-- <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
 <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script>
 <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script> --}}
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <!-- Page JS Helpers (CKEditor 5 plugins) -->
 {{-- <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script> --}}
 {{-- <script src="{{ asset('src/assets/js/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/pwstrength-bootstrap/pwstrength-bootstrap.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
 <script>Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker', 'jq-colorpicker', 'jq-maxlength', 'jq-select2', 'jq-rangeslider', 'jq-masked-inputs', 'jq-pw-strength']);</script> --}}
 <script>
    $(document).ready(function(){
       
            $('#type').change(function(){
                var startDate = document.getElementById("start").value;
                var endDate = document.getElementById("end").value;
                var userType = document.getElementById("type").value;
                $.ajax({
                    url: '{{ url("mass/sms/preview") }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        type: userType
                    },
                    success: function(response) {
                       // console.log(response.length);
                       var length = response.length;
                       var amount = length*2;

                       document.getElementById("length").innerHTML = length;
                       document.getElementById("amount").innerHTML = amount;
                    },
                    error: function(xhr, status, error) {
                        console.error(status);
                    }
                });
            });
        // });
   
    });
</script>
 @endsection

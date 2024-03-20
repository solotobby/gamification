@extends('layouts.main.master')
@section('style')
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/slick-carousel/slick.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/slick-carousel/slick-theme.css')}}">
@endsection
@section('content')

 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Survey</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Survey</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Elements -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title"></h3>
      </div>
      <div class="block-content">
        <div class="alert alert-info">One more thing, please complete this survey to continue!</div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{route('store.survey')}}" method="POST" enctype="multipart/form-data">
            @csrf
          <!-- Basic Elements -->
          <h2 class="content-heading pt-0">Gender</h2>
          <div class="row push">
            <div class="col-lg-12">
                <div class="row items-push">
                  <div class="col-md-6">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="Male" id="gender-1" name="gender">
                      <label class="form-check-label" for="gender-1">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">Male</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="Female" id="gender-2" name="gender">
                      <label class="form-check-label" for="gender-2">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">Female</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
          </div>

          <h2 class="content-heading pt-0">Age Range <small>(Select One)</small></h2>
          <div class="row push">
            <div class="col-lg-12">
                <div class="row items-push">
                  <div class="col-md-4">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="15-20" id="age-range-1" name="age_range" >
                      <label class="form-check-label" for="age-range-1">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">15-20</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="21-25" id="age-range-2" name="age_range" >
                      <label class="form-check-label" for="age-range-2">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">21-25</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="26-30" id="age-range-3" name="age_range" >
                      <label class="form-check-label" for="age-range-3">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">26-30</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="31-40" id="age-range-4" name="age_range" >
                      <label class="form-check-label" for="age-range-4">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">31-40</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="41-50" id="age-range-5" name="age_range" >
                      <label class="form-check-label" for="age-range-5">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">41-50</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-check form-block">
                      <input class="form-check-input" type="radio" value="{{ old('50+') }}" id="age-range-6" name="age_range">
                      <label class="form-check-label" for="age-range-6">
                        <span class="d-flex align-items-center">
                          <span class="ms-2">
                            <span class="fw-bold">50+</span>
                          </span>
                        </span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
          </div>

          <h2 class="content-heading pt-0">What are your Interests <small>(Please select at least 10)</small></h2>
          <div class="row push">
                @foreach ($interests as $interest)
                    <div class="col-md-4 mb-3">
                        <div class="form-check form-block">
                        <input class="form-check-input" type="checkbox" value="{{$interest->id}}" id="interest-{{$interest->id}}" name="interest[]" >
                        <label class="form-check-label" for="interest-{{$interest->id}}">
                            <span class="d-flex align-items-center">
                            <span class="ms-2">
                                <span class="fw-bold">{{$interest->name}}</span>
                            </span>
                            </span>
                        </label>
                        </div>
                    </div>
                @endforeach
          </div>


          <div class="col-md-4 mb-4">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>

        </form>
       
      </div>
    </div>
    <!-- END Elements -->
  </div>
  <!-- END Page Content -->

@endsection

@section('script')
<script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>
<!-- Page JS Plugins -->
<script src="{{asset('src/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/slick-carousel/slick.min.js')}}"></script>

 {{-- <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script> --}}

<!-- Page JS Helpers (BS Datepicker + Ion Range Slider + Slick Slider plugins) -->
<script>Dashmix.helpersOnLoad(['jq-datepicker', 'jq-rangeslider', 'jq-slick']);</script>

<script>
    // alert($('input[name=checkbox_name]').attr('checked'));
// alert(document.querySelectorAll('input[type="checkbox"]:checked').length);
</script>

@endsection

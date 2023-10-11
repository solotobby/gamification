@extends('layouts.main.master')

@section('style')
{{-- <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origind"></script> --}}
{{-- <script src="https://cdn.tiny.cloud/1/no-api/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script> --}}
@endsection

@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
            Create Banner
        </h1>
      </div>
    </div>
  </div>

  <!-- Page Content -->
  <div class="content content-boxed">

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


    <form action="{{ url('banner') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="block block-rounded">
        <!-- Files section -->
            <div class="block-content">
                <h2 class="content-heading">Banner Information</h2>
                <div class="row items-push">
                <div class="col-lg-3">
                    <p class="text-muted">
                    Give detailed description of the banner
                    </p>
                </div>

                <div class="col-lg-9">
                <div class="mb-4">
                    <label class="form-label" for="post-title">Upload Image of your banner</label>
                    <input type="file" class="form-control" id="banner-url" name="banner_url" required>
                    <small><i>Upload an image. Must be of high quality.</i></small>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="post-title">External Link</label>
                    <input type="url" class="form-control" id="post-title" name="external_link" value="{{ old('post_link') }}" required>
                    <small><i>The url you want to redirect users to</i></small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Ad Placement</label>
                    <div class="space-y-2">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" id="ad_placement1" name="ad_placement" value="15">
                        <input type="hidden" name="adplacement" value="top">
                        <label class="form-check-label" for="ad_placement1">Dashboard - Top only</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" id="ad_placement2" name="ad_placement" value="10">
                        <input type="hidden" name="adplacement" value="bottom">
                        <label class="form-check-label" for="ad_placement2">Dashboard - Bottom only</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" id="ad_placement3" name="ad_placement" value="20">
                        <input type="hidden" name="adplacement" value="both">
                        <label class="form-check-label" for="ad_placement3">Dashboard - Top & Bottom </label>
                      </div>
                    </div>
                </div>

                <div class="col-lg-12 col-xl-12">
                    <div class="row mb-4">
                        <label class="form-label">Choose Audience Interest</label>
                        @foreach ($preferences as $pref)
                            <div class="col-sm-12 col-md-4 col-xl-6 mt-1 d-md-flex align-items-md-center fs-sm mb-2">
                                <div class="form-check form-switch form-check-inline">
                                    {{-- <input type="hidden" name="id[]" value="{{ $pref['id'] }}"> --}}
                                    <input class="form-check-input" type="checkbox" value="{{ $pref['percentage'] }}|{{ $pref['id'] }}" id="count" name="count[]">
                                    <label class="form-check-label" for="example-switch-inline1">{{ $pref['name'] }} </label>
                                    <span class="nav-main-link-badge badge rounded-pill bg-primary">{{ $pref['count'] }}</span>
                                </div>
                            </div>  
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="post-title">Duration of Ad</label>
                    <select class="form-control" id="duration" name="duration" required>
                        <option value="">Select One</option>
                        <option value="3">3 Days</option>
                        <option value="7">7 Days</option>
                        <option value="14">14 Days</option>
                        <option value="30">30 Days</option>
                        <option value="60">60 Days</option>
                        <option value="90">90 Days</option>
                    </select>
                </div>
                

                <div class="mb-4">
                    <label class="form-label" for="post-title">Select Age Bracket</label>
                    <select class="form-control" id="age_bracket" name="age_bracket" required>
                        <option value="">Select One</option>
                        <option value="5">15-20</option>
                        <option value="4">21-25</option>
                        <option value="3">26-30</option>
                        <option value="2">31-40</option>
                        <option value="1">40-50</option>
                    </select>
                </div>
               

                <div class="mb-4">
                    <label class="form-label" for="post-title">Select Country</label>
                    <select class="form-control" id="country" name="country" required>
                        <option value="">Select One</option>
                        @foreach ($countryLists as $list)
                        <option value="{{ $list['total'] }}">{{ $list['country'] }} | {{ $list['total'] }}</option>
                        @endforeach
                    </select>
                    <small><i>Your Banner ad goes Live immediately it get approved</i></small>
                </div>
                
                <hr>
                <h4>Estimated Cost: &#8358;<span id="totalValue">0</span></h4>
        
                </div>
                </div>
            </div>
            <!-- END Files section -->

            <!-- Submit Form -->
            <div class="block-content block-content-full pt-0">
            <div class="row mb-2">
              <div class="col-lg-3"></div>
              <div class="col-lg-9">
                <button type="submit" class="btn btn-alt-primary" id="submitButton">
                  <i class="fa fa-plus opacity-50 me-1"></i> Post Banner
                </button>
              </div>
            </div>
          </div>

        </div>
    </form>

  </div>


  @endsection

  @section('script')

 <!-- Page JS Plugins -->
 {{-- <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script> --}}
 {{-- <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script> --}}
 {{-- <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script> --}}
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <!-- Page JS Helpers (CKEditor 5 plugins) -->
 {{-- <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script> --}}
 <script>
    $(document).ready(function(){ 
        // const submitButton = document.getElementById("submitButton");
        // submitButton.disabled = true;



        function calculateTotal() {

            // Initialize the total
            let total = 0;

            // Calculate the total based on the selected radio button value
            const selectedAdPlacementValue = parseFloat(document.querySelector('input[name="ad_placement"]:checked').value);
            if (!isNaN(selectedAdPlacementValue)) {
                total += selectedAdPlacementValue;

            }

            // Calculate the total based on the selected duration value
            const selectedDurationValue = parseFloat(document.getElementById('duration').value);
            if (!isNaN(selectedDurationValue)) {
                total += selectedDurationValue;
            }

            // Calculate the total based on the selected age bracket value
            const selectedAgeBracketValue = parseFloat(document.getElementById('age_bracket').value);
            if (!isNaN(selectedAgeBracketValue)) {
                total += selectedAgeBracketValue;
            }

            // Calculate the total based on the selected country value
            const selectedCountryValue = parseFloat(document.getElementById('country').value);
            if (!isNaN(selectedCountryValue)) {
                total += selectedCountryValue;
            }

            // Calculate the total based on the selected checkbox values
            const selectedCheckboxValues = Array.from(document.querySelectorAll('input[name="count[]"]:checked')).map(checkbox => parseFloat(checkbox.value));
            if (selectedCheckboxValues.length > 0) {
                total += selectedCheckboxValues.reduce((a, b) => a + b, 0);
            }

            // console.log(total);

            var finalTotal = total * 500;
            const submitButton = document.getElementById("submitButton");
            const calculated = finalTotal;
            const walletBalance = 10000;
            
            if (walletBalance < calculated) {
                submitButton.disabled = false;
            }else{
                submitButton.disabled = true;
            }

            // Display the total in the "totalValue" span
            document.getElementById('totalValue').textContent = finalTotal.toFixed(2);
        }


        // Add change event listeners to the relevant elements
        document.querySelectorAll('input[name="ad_placement"]').forEach(radio => {
            radio.addEventListener('change', calculateTotal);
        });
        document.getElementById('duration').addEventListener('change', calculateTotal);
        document.getElementById('age_bracket').addEventListener('change', calculateTotal);
        document.getElementById('country').addEventListener('change', calculateTotal);
        document.querySelectorAll('input[name="count[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        // Initial calculation
       calculateTotal();

    });
</script>

 @endsection
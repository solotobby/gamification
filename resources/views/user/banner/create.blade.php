@extends('layouts.main.master')

@section('style')
{{-- <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origind"></script> --}}
<script src="https://cdn.tiny.cloud/1/no-api/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
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


    <form action="{{ url('banner.store') }}" method="POST" >
        @csrf
        <div class="block block-rounded">
        <!-- Files section -->
            <div class="block-content">
                <h2 class="content-heading">Banner Information</h2>
                <div class="row items-push">
                <div class="col-lg-3">
                    <p class="text-muted">
                    Give detailed decription of the banner
                    </p>
                </div>

                <div class="col-lg-9">
                   
                        {{-- <div class="mb-4">
                          <label class="form-label">Checkboxes</label>
                          <div class="space-y-2">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="example-checkbox-default1" name="example-checkbox-default1" checked>
                              <label class="form-check-label" for="example-checkbox-default1">Option 1</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="example-checkbox-default2" name="example-checkbox-default2">
                              <label class="form-check-label" for="example-checkbox-default2">Option 2</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="example-checkbox-default3" name="example-checkbox-default3" disabled>
                              <label class="form-check-label" for="example-checkbox-default3">Option 3</label>
                            </div>
                          </div>
                        </div>
                        <div class="mb-4">
                          <label class="form-label">Inline Checkboxes</label>
                          <div class="space-x-2">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" value="" id="example-checkbox-inline1" name="example-checkbox-inline1" checked>
                              <label class="form-check-label" for="example-checkbox-inline1">Option 1</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" value="" id="example-checkbox-inline2" name="example-checkbox-inline2">
                              <label class="form-check-label" for="example-checkbox-inline2">Option 2</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" value="" id="example-checkbox-inline3" name="example-checkbox-inline3" disabled>
                              <label class="form-check-label" for="example-checkbox-inline3">Option 3</label>
                            </div>
                          </div>
                        </div>
                        <div class="mb-4">
                          <label class="form-label">Radios</label>
                          <div class="space-y-2">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="example-radios-default1" name="example-radios-default" value="option1" checked>
                              <label class="form-check-label" for="example-radios-default1">Option 1</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="example-radios-default2" name="example-radios-default" value="option2">
                              <label class="form-check-label" for="example-radios-default2">Option 2</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" id="example-radios-default3" name="example-radios-default" value="option2" disabled>
                              <label class="form-check-label" for="example-radios-default3">Option 3</label>
                            </div>
                          </div>
                        </div>
                        <div class="mb-4">
                          <label class="form-label">Inline Radios</label>
                          <div class="space-x-2">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="example-radios-inline1" name="example-radios-inline" value="option1" checked>
                              <label class="form-check-label" for="example-radios-inline1">Option 1</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="example-radios-inline2" name="example-radios-inline" value="option2">
                              <label class="form-check-label" for="example-radios-inline2">Option 2</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" id="example-radios-inline3" name="example-radios-inline" value="option2" disabled>
                              <label class="form-check-label" for="example-radios-inline3">Option 3</label>
                            </div>
                          </div>
                        </div>
                        <div class="mb-4">
                          <label class="form-label">Switches</label>
                          <div class="space-y-2">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" value="" id="example-switch-default1" name="example-switch-default1" checked>
                              <label class="form-check-label" for="example-switch-default1">Option 1</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" value="" id="example-switch-default2" name="example-switch-default2">
                              <label class="form-check-label" for="example-switch-default2">Option 2</label>
                            </div>
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" value="" id="example-switch-default3" name="example-switch-default3" disabled>
                              <label class="form-check-label" for="example-switch-default3">Option 3</label>
                            </div>
                          </div>
                        </div> --}}
                
                    

                <div class="mb-4">
                    <label class="form-label" for="post-title">Upload Image of your banner</label>
                    <input type="file" class="form-control" id="post-title" name="banner_url" required>
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
                   
                    adPl: <span id="adPlacement">0</span>
                </div>

                <div class="col-lg-12 col-xl-12">
                    <div class="row mb-4">
                        <label class="form-label">Choose Audience Interest</label>
                        @foreach ($preferences as $pref)
                            <div class="col-sm-12 col-md-4 col-xl-6 mt-1 d-md-flex align-items-md-center fs-sm mb-2">
                                <div class="form-check form-switch form-check-inline">
                                    <input type="hidden" name="id" value="{{ $pref['id'] }}">
                                    <input class="form-check-input" type="checkbox" value="{{ $pref['percentage'] }}" id="count" name="count[]">
                                    <label class="form-check-label" for="example-switch-inline1">{{ $pref['name'] }} </label>
                                    <span class="nav-main-link-badge badge rounded-pill bg-primary">{{ $pref['count'] }} - {{ $pref['percentage'] }}</span>
                                </div>
                            </div>
                            
                        @endforeach
                        
                    </div>
                    Estimated Audience Reach -  <span id="estimated_reach">0</span>
                    <br>

                    
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
                    <small><i>Your Banner ad goes Live immediately it is approved</i></small>
                </div>
                Duration: <span id="durationss">0</span>
                <hr>
                <h4>Estimated Cost: &#8358;<span id="demo">0</span></h4>
    
                {{-- <div class="mb-4">
                    <label class="form-label" for="post-files">Banner Description <small></small></label>
                    <textarea class="form-control" name="description" id="js-ckeditor5-classic" required> {{ old('description') }}</textarea>
                </div> --}}
            
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
        const checkboxes = document.querySelectorAll('input[name="count[]"]');
        const radio = document.querySelectorAll('input[name="adplacement"]');
        const totalSpan = document.getElementById('estimated_reach');

        // Initialize the estimated_reach to 0

        $('input[type="radio"]').change(function(){
            document.getElementById("adPlacement").innerHTML = $(this).val();  

            var ad_placements = $(this).val();
        });

       


        let estimated_reach = 0;

            // Add event listeners to checkboxes to update the total
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        // If the checkbox is checked, add its value to the total
                        estimated_reach += parseFloat(this.value);
                    } else {
                        // If the checkbox is unchecked, subtract its value from the total
                        estimated_reach -= parseFloat(this.value);
                    }
                        // console.log(estimated_reach);
                        document.getElementById("estimated_reach").innerHTML = estimated_reach;
                });
            });


            $('#duration').change(function(){
            
                // alert();
                var adPlacement = document.getElementById("adPlacement").innerHTML;
                var estimatedReach = document.getElementById("estimated_reach").innerHTML;
                var adDuration = $(this).val();
                document.getElementById("durationss").innerHTML = $(this).val();

                totalParameters = Number(adPlacement)+Number(estimatedReach)+Number(adDuration);

                document.getElementById("demo").innerHTML = totalParameters * 500;
                console.log(totalParameters);

            });

    });
</script>

 @endsection
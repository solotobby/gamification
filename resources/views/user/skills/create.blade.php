@extends('layouts.main.master')

@section('style')
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/flatpickr/flatpickr.min.css')}}">
@endsection

@section('content')
   <!-- Hero -->
   <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
    <div class="bg-black-50">
      <div class="content content-full">
        <h1 class="fs-2 text-white my-2">
          <i class="fa fa-plus text-white-50 me-1"></i>Setup Skillset
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero -->

   <!-- Page Content -->
   <div class="content">
    <div class="alert alert-info">

      Position yourself to get hired for remote and full time job opportunities.

    </div>
    <div class="block block-rounded block-bordered">
      <div class="block-content">
        @if (session('success'))
                  <script>
                      Swal.fire('Success!', '{{ session('success') }}', 'success');
                  </script>
              @endif

              @if (session('error'))
                  <script>
                      Swal.fire('Error!', '{{ session('error') }}', 'error');
                  </script>
              @endif

              @if (session('error'))
                  <script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Validation Error',
                          text: '{{ session('error') }}',
                      });
                  </script>
              @endif

              @if ($errors->any())
                  <script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Validation Error',
                          html: '{!! implode("<br>", $errors->all()) !!}', // Displays all errors in SweetAlert
                      });
                  </script>
              @endif

              @if (session('success'))
                  <script>
                      Swal.fire({
                          icon: 'success',
                          title: 'Success',
                          text: '{{ session('success') }}',
                      });
                  </script>
              @endif

              @if ($errors->any())
                  @php
                      Alert::error('Validation Error', implode("\n", $errors->all()));
                  @endphp
              @endif

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

        @if(!@$skillAsset)
       
          <!-- Vital Info -->
          <h2 class="content-heading pt-0">Vital Info</h2>
            <form action="{{ route('setup.skill') }}" method="POST">
              @csrf
              <div class="row push">
                <div class="col-lg-2">
                  <p class="text-muted">
                    Set up your profile to start getting Skilled Jobs
                  </p>
                </div>
                <div class="col-lg-10">
                  

                    {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif --}}

                  <div class="mb-4">
                    <label class="form-label" for="dm-project-new-name">
                      Title <small> (What defines your skill)</small><span class="text-danger">*</span>
                    </label>
                    <input type="text" required class="form-control" id="dm-project-new-name" name="title" value="{{ @$skillAsset->title }}" placeholder="eg: software engineer, ui/ux, cv writer e.t.c">
                  </div>
                  <div class="row mb-4">
                    <div class="col-lg-12">
                      <label class="form-label" for="dm-project-new-category">
                        Select your Skillset<span class="text-danger">*</span>
                      </label>
                      <select class="form-select" required id="dm-project-new-category" name="skill_id">
                        <option value="">Select a Skillset</option>
                          @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}" {{ @$skillAsset->skill_id == $skill->id ? 'selected' : '' }}>{{ $skill->name }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row mb-4">
                    <div class="col-lg-12">
                        <label class="form-label" for="dm-project-new-category">
                            Brief Description of yourself and what you do <span class="text-danger">*</span>
                        </label>
                        
                        <textarea class="form-control" required name="description" rows="6" placeholder="e.g My Name is Joan, I have over 6 years experience as a UI/UX expert using Figma. I deliver averagely 20-30 screens in 6hours.">{{ @$skillAsset->description }}</textarea>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label class="form-label" for="dm-project-new-color">Enter a Range of how much you will charge per Job (in {{ baseCurrency() }}) <span class="text-danger">*</span> </label>
                    <div class="col-md-6">
                      Minimum Price
                      <input type="number" required class="form-control" id="dm-project-new-color" value="{{ @$skillAsset->min_price }}" name="min_price" placeholder="Min. Price">
                    </div>
                    <div class="col-md-6">
                      Maximum Price
                        <input type="number" required class="form-control" id="dm-project-new-color" value="{{ @$skillAsset->max_price }}" name="max_price" placeholder="Max. Price">
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-lg-12">
                      <label class="form-label" for="dm-project-new-category">
                        Level of Proficiency<span class="text-danger">*</span>
                      </label>
                      <select class="form-select" required id="dm-project-new-category" name="profeciency_level">
                        <option value="">Select a category</option>
                      
                        @foreach ($profeciencies as $prof)
                          <option value="{{ $prof->id }}" {{ @$skillAsset->profeciency_level == $prof->id ? 'selected' : '' }}>{{ $prof->name }}</option>
                        @endforeach
                      
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <div class="col-lg-12">
                      <label class="form-label" for="dm-project-new-category">
                        Years of Experience<span class="text-danger">*</span>
                      </label>
                      <select class="form-select" required id="dm-project-new-category" name="year_experience">
                        <option value="">Select a  One</option>
                        <option value="0-2" {{ @$skillAsset->year_experience == '0-2' ? 'selected' : '' }}>0-2 years</option>
                        <option value="3-5" {{ @$skillAsset->year_experience == '3-5' ? 'selected' : '' }}>3-5 years</option>
                        <option value="6-10" {{ @$skillAsset->year_experience == '6-10' ? 'selected' : '' }}>6-10 years</option>
                        <option value="10+" {{ @$skillAsset->year_experience == '10+' ? 'selected' : '' }}>10+ years</option>

                      </select>
                    </div>
                  </div>



                  {{-- <div class="row mb-4">
                    <div class="col-lg-12">
                      <label class="form-label" for="dm-project-new-category">
                        Payment Model<span class="text-danger">*</span>
                      </label>
                      <select class="form-select" required id="dm-project-new-category" name="payment_mode">
                        <option value="">Select a category</option>
                        <option value="pay_per_hour">Pay per Hour</option>
                        <option value="pay_per_project">Pay per Project</option>
                        <option value="pay_per_milestone">Pay per Milestone</option>
                        
                      </select>
                    </div>
                  </div> --}}

                  <div class="mb-4">
                    <label class="form-label" for="dm-project-new-name">
                      Location <small></small><span class="text-danger">*</span>
                    </label>
                    <input type="text" required class="form-control" id="dm-project-new-name" name="location" value="{{ @$skillAsset->location }}" placeholder="eg: Lagos, Port Harcourt, ">
                  </div>

                  <div class="row mb-4">
                    <div class="col-lg-12">
                      <label class="form-label" for="dm-project-new-category">
                        Availability<span class="text-danger">*</span>
                      </label>
                      <select class="form-select" required id="dm-project-new-category" name="availability">
                        <option value="">Select a category</option>
                        {{--<option value="remote">Remote</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="physical">Physical</option> --}}

                        <option value="remote" {{ @$skillAsset->availability == 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="hybrid" {{ @$skillAsset->availability == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="physical" {{ @$skillAsset->availability == 'physical' ? 'selected' : '' }}>Physical</option>
                        {{-- <option value="10+" {{ request('year_experience') == '10+' ? 'selected' : '' }}>10+ years</option> --}}

                        
                      </select>
                    </div>
                  </div>

                </div>
              </div>
                <!-- END Vital Info -->
                <!-- Submit -->
                <div class="row push">
                  <div class="col-lg-10 col-xl-5 offset-lg-2">
                    <div class="mb-4">
                      @if(@$skillAsset)
                      <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check-circle me-1 opacity-50"></i>  Update
                      </button>
                      @else
                      <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-plus-circle me-1 opacity-50"></i>  Submit
                      </button>
                      @endif
                    </div>
                  </div>
                </div>
                <!-- END Submit -->
            </form>
          
          @else

          @if(@$skillAsset)
          <!-- Optional Info -->
          <h2 class="content-heading pt-0">Portfolio</h2>
          <form action="{{ route('add.portfolio') }}" method="POST">
            @csrf
            <div class="row push">
           
              <div class="col-lg-2">
                <p class="text-muted">
                   Tell us about what you have done. You will be hired based on your experience
                </p>
                </div>
                <div class="col-lg-10">
                    <div class="row mb-4">
                        <div class="col-md-12">
                        <label class="form-label" for="dm-project-new-color">Project Title</label>
                        <input type="text" class="form-control" value="{{ old('title') }}" id="dm-project-new-color" name="title" placeholder="Brief Title of the Project">
                        </div>
                    </div>

                <div class="mb-4">
                    <label class="form-label" for="dm-project-new-description">Description of the Project</label>
                    <textarea class="form-control" id="dm-project-new-description" name="description" rows="6" placeholder="What the project is about?">{{ old('description') }}</textarea>
                </div>

             
                    {{-- <div class="mb-4">
                        <label class="form-label" for="dm-project-new-description">Choose Skills Used</label>
                        <select class="js-select2 form-select" id="example-select2-multiple" name="tools[]" style="width: 100%;" data-placeholder="Choose as many skills..." multiple required>
                          <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                              @foreach ($tools as $tool)
                                  <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                              @endforeach
                        </select>
                    </div> --}}

                    <input type="hidden" name="skill_id" value="{{ @$skillAsset->id }}" required>
                 
                <!-- Submit -->
                    <div class="row push">
                        <div class="col-lg-8">
                        <div class="mb-4">
                            <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-plus-circle me-1 opacity-50"></i> Add Portfolio
                            </button>
                        </div>
                        </div>
                    </div>
                
                
                </div>
            </div>
            <!-- END Optional Info -->
          </form>
          @endif
     
              {{-- @if($portfolio->count() > 0)
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Your Portfolio
                        </p>
                    </div>
                    <div class="col-lg-8">
                        @foreach ($portfolio as $port)
                            <div class=" mb-2">
                                <div class="col-md-12">
                                <label class="form-label" for="dm-project-new-color">Project Title</label>
                                <p>{{ $port->title }} </p>
                                { <input type="text" class="form-control" id="dm-project-new-color" name="title" placeholder="Brief Title of the Project"> --
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="dm-project-new-description">Description of the Project</label>
                                <p>{!! $port->description !!} </p>
                                <textarea class="form-control" id="dm-project-new-description" name="description" rows="6" placeholder="What is this project about?"></textarea> --
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="dm-project-new-description">Skills</label>
                              
                              <p> 
                                  @foreach ($port->skills as $key=>$value)
                                      {{ $value->name }},
                                  @endforeach  
                              </p>  
                            </div>
                          
                            <hr>
                        @endforeach
                    </div>
                </div>
              @endif  --}}

          @endif

      </div>
    </div>
  </div>
  <!-- END Page Content -->

@endsection

@section('script')
<script src="{{ asset('src/assets/js/lib/jquery.min.js')}}"></script>
<!-- Page JS Plugins -->
<script src="{{ asset('src/assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/dropzone/min/dropzone.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/pwstrength-bootstrap/pwstrength-bootstrap.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>

<!-- Page JS Helpers (Flatpickr + BS Datepicker + BS Maxlength + Select2 + Ion Range Slider + Masked Inputs + Password Strength Meter plugins) -->
<script>Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker', 'jq-maxlength', 'jq-select2', 'jq-rangeslider', 'jq-masked-inputs', 'jq-pw-strength']);</script>

  {{-- <!-- Page JS Plugins -->
  <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>

  <!-- Page JS Helpers (CKEditor 5 plugins) -->
  <script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script> --}}
  

@endsection

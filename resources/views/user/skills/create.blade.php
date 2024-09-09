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
    <div class="block block-rounded block-bordered">
      <div class="block-content">
        @if(!$skill)
       
          <!-- Vital Info -->
          <h2 class="content-heading pt-0">Vital Info</h2>
          <form action="{{ route('setup.skill') }}" method="POST">
            @csrf
            <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Some vital information about your new project
              </p>
            </div>
            <div class="col-lg-8">
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
                <label class="form-label" for="dm-project-new-name">
                  Title <small> (What defines your skill)</small><span class="text-danger">*</span>
                </label>
                <input type="text" required class="form-control" id="dm-project-new-name" name="title" placeholder="eg: software engineer, ui/ux, cv writer e.t.c">
              </div>
              <div class="row mb-4">
                <div class="col-lg-12">
                  <label class="form-label" for="dm-project-new-category">
                    Category of your profession<span class="text-danger">*</span>
                  </label>
                  <select class="form-select" required id="dm-project-new-category" name="skill_category">
                    <option value="">Select a category</option>
                      @foreach ($skillCategory as $skill)
                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-lg-12">
                    <label class="form-label" for="dm-project-new-category">
                        Brief Description of yourself and what you do <span class="text-danger">*</span>
                    </label>
                    
                    <textarea class="form-control" required name="description" rows="6" placeholder="e.g My Name is Joan, I have over 6 years experience as a UI/UX expert using Figma. I deliver averagely 20-30 screens in 6hours."></textarea>
                </div>
              </div>

              {{-- <div class="row mb-4">
                <label class="form-label" for="dm-project-new-color">Price Range <span class="text-danger">*</span></label>
                <div class="col-md-6">
                  <input type="number" required class="form-control" id="dm-project-new-color" name="min_price" placeholder="Min. Price">
                </div>
                <div class="col-md-6">
                    <input type="number" required class="form-control" id="dm-project-new-color" name="max_price" placeholder="Max. Price">
                </div>
              </div> --}}

              <div class="row mb-4">
                <div class="col-lg-12">
                  <label class="form-label" for="dm-project-new-category">
                    Level of Proficiency<span class="text-danger">*</span>
                  </label>
                  <select class="form-select" required id="dm-project-new-category" name="profeciency_level">
                    <option value="">Select a category</option>
                   
                    @foreach ($profeciencies as $prof)
                      <option value="{{ $prof->id }}">{{ $prof->name }}</option>
                    @endforeach
                   
                  </select>
                </div>
              </div>

              <div class="row mb-4">
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
              </div>

              <div class="row mb-4">
                <div class="col-lg-12">
                  <label class="form-label" for="dm-project-new-category">
                    Availability<span class="text-danger">*</span>
                  </label>
                  <select class="form-select" required id="dm-project-new-category" name="availability">
                    <option value="">Select a category</option>
                    <option value="remote">Remote</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="physical">Physical</option>
                    
                  </select>
                </div>
              </div>

            </div>
          </div>
          <!-- END Vital Info -->
           <!-- Submit -->
           <div class="row push">
            <div class="col-lg-8 col-xl-5 offset-lg-4">
              <div class="mb-4">
                <button type="submit" class="btn btn-alt-primary">
                  <i class="fa fa-check-circle me-1 opacity-50"></i> Continue
                </button>
              </div>
            </div>
          </div>
          <!-- END Submit -->
          </form>
          @else


          <!-- Optional Info -->
          <h2 class="content-heading pt-0">Portfolio</h2>
          <form action="{{ route('add.portfolio') }}" method="POST">
            @csrf
            <div class="row push">
                <div class="col-lg-4">
                <p class="text-muted">
                   Tell us about what you have done. You will be hired based on your experience
                </p>
                </div>
                <div class="col-lg-8 ">
                    <div class="row mb-4">
                        <div class="col-md-12">
                        <label class="form-label" for="dm-project-new-color">Project Title</label>
                        <input type="text" class="form-control" id="dm-project-new-color" name="title" placeholder="Brief Title of the Project">
                        </div>
                    </div>

                <div class="mb-4">
                    <label class="form-label" for="dm-project-new-description">Description of the Project</label>
                    <textarea class="form-control" id="dm-project-new-description" name="description" rows="6" placeholder="What is this project about?"></textarea>
                </div>

             
                    <div class="mb-4">
                        <label class="form-label" for="dm-project-new-description">Choose Skills Used</label>
                      <select class="js-select2 form-select" id="example-select2-multiple" name="tools[]" style="width: 100%;" data-placeholder="Choose as many skills..." multiple required>
                        <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                            @foreach ($tools as $tool)
                                <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                            @endforeach
                      </select>
                    </div>

                    <input type="hidden" name="skill_id" value="{{ @$skill->id }}" required>
                 
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
        <hr>
              @if($portfolio->count() > 0)
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
                                {{-- <input type="text" class="form-control" id="dm-project-new-color" name="title" placeholder="Brief Title of the Project"> --}}
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="dm-project-new-description">Description of the Project</label>
                                <p>{!! $port->description !!} </p>
                                {{-- <textarea class="form-control" id="dm-project-new-description" name="description" rows="6" placeholder="What is this project about?"></textarea> --}}
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
              @endif

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


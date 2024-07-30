@extends('layouts.main.master')

@section('style')

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
        <form action="be_pages_projects_create.html" method="POST">
          <!-- Vital Info -->
          <h2 class="content-heading pt-0">Vital Info</h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Some vital information about your new project
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              <div class="mb-4">
                <label class="form-label" for="dm-project-new-name">
                  Full Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="dm-project-new-name" name="dm-project-new-name" placeholder="eg: example.com">
              </div>
              <div class="row mb-4">
                <div class="col-lg-12">
                  <label class="form-label" for="dm-project-new-category">
                    Category of your profession<span class="text-danger">*</span>
                  </label>
                  <select class="form-select" id="dm-project-new-category" name="dm-project-new-category">
                    <option value="0">Select a category</option>
                    <option value="design-web">Web Design</option>
                    <option value="design-logo">Logo Design</option>
                    <option value="design-ux-ui">UX/UI Design</option>
                    <option value="dev-web">Web Development</option>
                    <option value="dev-app">App Development</option>
                    <option value="dev-mobile">Mobile Development</option>
                    <option value="identity">Identity</option>
                    <option value="marketing">Marketing</option>
                  </select>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-lg-12">
                    <label class="form-label" for="dm-project-new-category">
                        Brief Description of yourself and what you do <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="dm-project-new-description" name="dm-project-new-description" rows="6" placeholder="What is this project about?"></textarea>
                </div>
              </div>
            </div>
          </div>
          <!-- END Vital Info -->

          <!-- Optional Info -->
          <h2 class="content-heading pt-0">Portfolio</h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                You can add more details if you like but it is not required
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              <div class="mb-4">
                <label class="form-label" for="dm-project-new-description">Description</label>
                <textarea class="form-control" id="dm-project-new-description" name="dm-project-new-description" rows="6" placeholder="What is this project about?"></textarea>
              </div>
              <div class="row mb-4">
                <div class="col-md-6">
                  <!-- Bootstrap Datepicker (.js-datepicker class are initialized in Helpers.jqDatepicker()) -->
                  <!-- For more info and examples you can check out https://github.com/eternicode/bootstrap-datepicker -->
                  <label class="form-label" for="dm-project-new-deadline">Deadline</label>
                  <input type="text" class="js-datepicker form-control" id="dm-project-new-deadline" name="dm-project-new-deadline" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label" for="dm-project-new-color">Color</label>
                  <input type="text" class="form-control" id="dm-project-new-color" name="dm-project-new-color" placeholder="#0665d0">
                </div>
              </div>
            </div>
          </div>
          <!-- END Optional Info -->

          

          <!-- Submit -->
          <div class="row push">
            <div class="col-lg-8 col-xl-5 offset-lg-4">
              <div class="mb-4">
                <button type="submit" class="btn btn-alt-primary">
                  <i class="fa fa-check-circle me-1 opacity-50"></i> Create Project
                </button>
              </div>
            </div>
          </div>
          <!-- END Submit -->
        </form>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

@endsection


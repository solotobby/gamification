@extends('layouts.main.master')
@section('style')
<script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
@endsection
@section('content')
 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Mass Mail</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Mass</li>
            <li class="breadcrumb-item active" aria-current="page">Mail</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- SimpleMDE Editor (js-simplemde class is initialized in Helpers.jsSimpleMDE()) -->
    <!-- For more info and examples you can check out https://github.com/NextStepWebs/simplemde-markdown-editor -->
    <h2 class="content-heading">Send Mass Email</h2>
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        {{-- <h3 class="block-title">Markdown Editor</h3> --}}
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <form action="{{ route('send.mass.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label>Subject </label>
               <input type="text" class="form-control" name="subject" required value="{{ old('subject') }}">
            </div>
            <div class="mb-4">
                <label>Select Audience</label>
                <select class="form-control" name="type" required>
                <option value="">Select One</option>
                <option value="all">All Users</option>
                <option value="verified">Verified Users</option>
                </select>
            </div>
          <div class="mb-4">
            <label>Enter Message</label>
            <textarea id="js-ckeditor5-classic" name="message">{{ old('message') }}</textarea>
          </div>
          <button class="btn btn-primary mb-3" type="submit"><i class="fa fa-envelope"></i> Send Mail </button>
        </form>
      </div>
    </div>
    <!-- END SimpleMDE Editor -->

   
  </div>
  <!-- END Page Content -->

@endsection


@section('script')

 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
 <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script>
 <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <!-- Page JS Helpers (CKEditor 5 plugins) -->
 <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>

 @endsection
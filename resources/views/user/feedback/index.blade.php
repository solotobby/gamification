@extends('layouts.main.master')
@section('content')
<div class="content">
    <div class="row">
      <div class="col-xl-12">
        <!-- Chat #1 -->
        <div class="block block-rounded">
          <!-- Chat #1 Header -->
          <div class="block-content block-content-full bg-primary text-center">
            {{-- <img class="img-avatar img-avatar-thumb" src="{{asset('assets/media/avatars/avatar10.jpg')}}" alt=""> --}}
            <p class="fs-lg fw-semibold text-white mt-3 mb-0">
              Talk to Us!
            </p>
            
          </div>
          <!-- END Chat #1 Header -->
          <!-- Chat #1 Input -->
          <div class="block-content">
            <div class="row">
                <div class="alert alert-info"> 
                    We want to hear from you. Please let us know how you feel about Freebyz.
                    <br>
                    Note: <i>If you are reporting a worker, please include the worker name, email and the Job done. This will enable use take proper action.</i>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('store.feedback') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mb-3">
                        <label>Pick an option</label>
                        <select class="form-control" name="category" required>
                            <option value="">Select an Option</option>
                            <option value="feedback">Feedback</option>
                            <option value="complaint">Complaint</option>
                            <option value="transfer_issue">Transfer Issue</option>
                            <option value="report_worker">Report A Worker</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Meesage</label>
                        <textarea class="form-control" name="message" id="js-ckeditor5-classic"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="formFileMultiple" class="form-label">Upload Proof</label>
                        <input class="form-control" type="file" name="proof" id="example-file-input-multiple" required>
                        {{-- <label>Proof (image format only)</label>
                        <input type="file" class="form-control" name="proof"> --}}
                    </div>
                    <input type="hidden" name='user_id' value="{{ auth()->user()->id }}">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-paper-plane opacity-50 me-1"></i> Send
                            </button>
                        </div>
                    </div>
                </form>
            </div>
          </div>
          <!-- END Chat #1 Input -->
        </div>
        <!-- END Chat #1 -->
      </div>
     
    </div>
  </div>
@endsection
@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
 
@endsection
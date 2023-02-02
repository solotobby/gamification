@extends('layouts.main.master')
@section('content')
<div class="content">
    <div class="row">
      <div class="col-xl-12">
        <!-- Chat #1 -->
        <div class="block block-rounded">
          <!-- Chat #1 Header -->
          <div class="block-content block-content-full bg-primary text-center">
            <img class="img-avatar img-avatar-thumb" src="assets/media/avatars/avatar10.jpg" alt="">
            <p class="fs-lg fw-semibold text-white mt-3 mb-0">
              James Smith
            </p>
            <p class="text-white-75 mb-0">
              Web Developer
            </p>
          </div>
          <!-- END Chat #1 Header -->

          <!-- Chat #1 Messages -->
          <div class="js-chat-messages block-content block-content-full text-break overflow-y-auto" data-chat-id="1" style="height: 300px;"></div>

          <!-- Chat #1 Input -->
          <div class="js-chat-form block-content p-2 bg-body-dark">
            <form action="be_comp_chat.html" method="POST">
              <input type="text" class="js-chat-input form-control form-control-alt" data-target-chat-id="1" placeholder="Type a message..">
            </form>
          </div>
          <!-- END Chat #1 Input -->
        </div>
        <!-- END Chat #1 -->
      </div>
     
    </div>
  </div>
@endsection
@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/be_comp_chat.min.js')}}"></script>
 
@endsection
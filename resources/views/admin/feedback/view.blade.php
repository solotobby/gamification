@extends('layouts.main.master')
@section('style')

@endsection

@section('content')
 <!-- Page Content -->
 <div class="content">
    <!-- Layouts -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Feedback Thread</h3>
      </div>
      <div class="block-content">
        <!-- Inline Layout -->
        <h2 class="content-heading">Feedback/Complaint</h2>
        <div class="row">
          <div class="col-lg-12">
            <div class="alert alert-warning text-small">
                Sender Name - <a href="{{ url('user/'.$feedback->user->id.'/info')}} "> {{$feedback->user->name }} </a><br>
                Sender Email - {{ $feedback->user->email }}<br>
                Category - {{ $feedback->category }}<br>
               
            </div>

            <p class="text-muted">
                {!! $feedback->message !!}
            </p>
          </div>
        </div>
        <!-- END Inline Layout -->

        <!-- Label on top Layout -->
        <h2 class="content-heading">Replies</h2>
        <div class="row">
          <div class="col-lg-12">
            @foreach ($replies as $reply)
            <p class="text-muted">

                <small>{{$reply->user->name}} </small>:
               <code> {!! $reply->message !!} </code>
            </p>
            @endforeach
            
            
        </div>
        </div>
        <!-- END Label on top Layout -->

        <!-- Horizontal Layout -->
        {{-- <h2 class="content-heading">Horizontal</h2> --}}
        <hr>
        <div class="row">
          <div class="col-lg-12 col-xl-12">
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

        <form action="{{ route('store.admin.feedbackreplies') }}" method="POST">
            @csrf
            <div class="col-md-12">
                <label>Meesage</label>
                <textarea class="form-control" name="message" id="js-ckeditor5-classic"></textarea>
            </div>
            <input type="hidden" name='user_id' value="{{ auth()->user()->id }}">
            <input type="hidden" name="feedback_id" value="{{ $feedback->id }}">
            <div class="row mb-4 mt-4">
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-paper-plane opacity-50 me-1"></i> Send
                    </button>
                </div>
            </div>
        </form>
          </div>
        </div>
        <!-- END Horizontal Layout -->

       
      </div>
    </div>
    <!-- END Layouts -->
  </div>

@endsection
@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
 
@endsection
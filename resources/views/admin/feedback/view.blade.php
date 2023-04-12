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
        {{-- <h2 class="content-heading">Feedback/Complaint</h2> --}}
        <div class="row">
          <div class="col-lg-12">
            <div class="alert alert-info text-small">
                <strong>Sender Name:</strong> <a href="{{ url('user/'.$feedback->user->id.'/info')}} "> {{$feedback->user->name }} </a><br>
                <strong> Email:</strong> {{ $feedback->user->email }}<br>
                <strong> Category:</strong> {{ $feedback->category }}<br>
                <strong> Verification:</strong> {{ isset($feedback->user->is_verified) &&  $feedback->user->is_verified == true ? 'Verified' : 'Not verified' }}<br>
            </div>

            <p class="text-muted">
              <i><small>{{$feedback->user->name}} | {{ \Carbon\Carbon::parse($feedback->created_at)->format('d/m/Y @ h:i:sa') }}</small>:</i>
                {!! $feedback->message !!}
            </p>
            @if($feedback->proof_url != null)
            <img src="{{ $feedback->proof_url }}" class="img-thumbnail rounded float-left " alt="Proof">
            @else
            <div class="alert alert-warning text-small">
              No Image attached
            </div>
            @endif
          </div>
        </div>
        <!-- END Inline Layout -->

        <!-- Label on top Layout -->
        <h2 class="content-heading">Replies</h2>
        <div class="row">
          <div class="col-lg-12">
            @foreach (@$feedback->reply as $reply)
            <p class="text-muted">

                <small>{{$reply->user->name}} | {{ \Carbon\Carbon::parse($reply->created_at)->format('d/m/Y @ h:i:sa') }}</small>:
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
          
            
              <div class="col-lg-6 ">
                  <a href="{{ url('admin/feedback') }}" class="btn btn-alt-warning pull-right">
                      <i class="fa fa-back opacity-50 me-1"></i> Back to List
                  </a>
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
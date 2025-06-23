@extends('layouts.main.master')
@section('style')

@endsection

@section('content')

<div class="row g-0 flex-md-grow-1">
  <div class="col-md-12 col-lg-12 col-xl-12 bg-body-dark">
  
    <div class="content">
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


      <div class="block block-rounded">

        <div class="block-content block-content-sm block-content-full bg-body-light">
          <div class="d-flex py-3">
              <div class="flex-shrink-0 me-3 ms-2 overlay-container overlay-right">
              <img class="img-avatar img-avatar48" src="{{asset('src/assets/media/avatars/avatar7.jpg')}}" alt="">
              {{-- <i class="far fa-circle text-white overlay-item fs-sm bg-success rounded-circle"></i> --}}
              </div>
              <div class="flex-grow-1">
              <div class="row">
                  <div class="col-sm-7">
                  <a class="fw-semibold link-fx" href="{{ url('user/'.$feedback->user->id.'/info')}}" target="_blank">{{$feedback->user->name}} </a> &#x2022; {{ isset($feedback->user->is_verified) &&  $feedback->user->is_verified == true ? 'Verified' : 'Not verified' }}
                  <div class="fs-sm text-muted">{{$feedback->user->email}} . </div>
                  <div class="fs-sm text-muted">
                    {{ $feedback->category }}
                   
                  </div>
                  </div>
                  <div class="col-sm-5 d-sm-flex align-items-sm-center">
                  <div class="fs-sm text-muted text-sm-end w-100 mt-2 mt-sm-0">
                      <p class="mb-0"> {{ \Carbon\Carbon::parse($feedback->created_at)->format('d/m/Y @ h:i:sa') }}</p>
                  </div>
                  </div>
              </div>
              </div>
          </div>
        </div>

        <div class="block-content">
          {!! $feedback->message !!}
        </div>

        <div class="block-content bg-body-light">
          <div class="row g-sm">
              <div class="col-6 col-sm-4 col-md-5 col-lg-4 col-xl-3">
              <div class="options-container fx-item-zoom-in">
                  <img class="img-fluid options-item" src="{{asset($feedback->proof_url)}}" alt="">
                  <div class="options-overlay bg-black-75">
                  {{-- <div class="options-overlay-content">
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)">
                      <i class="fa fa-download me-1"></i> Download
                      </a>
                  </div> --}}
                  </div>
              </div>
              <p class="fs-sm text-muted pt-2">
                  <i class="fa fa-paperclip"></i> Ticket #{{$feedback->id}}
              </p>
              </div>
              
          </div>
        </div>

      </div>

      @if($feedback->replies()->count() > 0)
            
            
            <!-- Discussion -->
          <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Conversation - {{ $feedback->replies()->count() }}</h3>
            <div class="block-options">
              {{-- <a class="btn-block-option me-2" href="#forum-reply-form">
                <i class="fa fa-reply me-1"></i> Reply
              </a> --}}
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                <i class="si si-refresh"></i>
              </button>
            </div>
          </div>
          <div class="block-content">
            <table class="table table-borderless">
              <tbody>
              @foreach ($feedback->replies as $reply)
                <tr class="table-active">
                  <td class="d-none d-sm-table-cell"></td>
                  <td class="fs-sm text-muted">
                    <a href="">{{ $reply->user->id == auth()->user()->id ? 'You' : $reply->user->name}}</a> on <span>{{ \Carbon\Carbon::parse($reply->created_at)->format('d l, Y : h:i:s:a') }}</span>
                  </td>
                </tr>
                <tr>
                  <td class="d-none d-sm-table-cell text-center" style="width: 140px;">
                    <p>
                      <a href="be_pages_generic_profile.html">
                        <img class="img-avatar" src="{{asset('src/assets/media/avatars/avatar9.jpg')}}" alt="">
                      </a>
                    </p>
                    {{-- <p class="fs-sm fw-medium">
                      289 Posts<br>Level 3              
                      </p> --}}
                  </td>
                  <td>
                    <p class="fs-sm text-muted">
                      {!! $reply->message !!}
                    </p>
                    {{-- <hr>
                    <p class="fs-sm text-muted">Be yourself; everyone else is already taken.</p> --}}
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          </div>
          <!-- END Discussion -->
      
      @else
          <div class="alert alert-info">
              No Replies at the moment
          </div>
      @endif



       <!-- Reply -->
  <div class="block block-rounded">
    <div class="block-content block-content-full">
      {{-- {{ route('reply.feedback') }} --}}
      <form action=" {{ route('store.admin.feedbackreplies') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="col-md-12 mb-3">
              <label>Meesage</label>
              <textarea class="form-control" name="message" id="js-ckeditor5-classic"></textarea>
          </div>
          
          <input type="hidden" name='user_id' value="{{ auth()->user()->id }}">
          <input type="hidden" name='feedback_id' value="{{ $feedback->id }}">
          <input type="hidden" name='0' value="false">
          <div class="row mb-4">
              <div class="col-lg-6">
                  <button type="submit" class="btn btn-alt-primary">
                      <i class="fa fa-paper-plane opacity-50 me-1"></i> Send Reply
                  </button>
                  <a href="{{ url('admin/feedback/unread') }}" class="btn btn-alt-warning pull-right">
                    <i class="fa fa-back opacity-50 me-1"></i> Back to List
                </a>
              </div>
          </div>
         
      </form>
    </div>
  </div>
  <!-- END Reply -->

    </div>


  </div>
  

  

</div>

{{-- ------------------------------------------------------ --}}

 <!-- Page Content -->
 {{-- <div class="content">
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
            @foreach (@$feedback->replies as $reply)
            <p class="text-muted">

                <small>{{$reply->user->name}} | {{ \Carbon\Carbon::parse($reply->created_at)->format('d/m/Y @ h:i:sa') }}</small>:
               <code> {!! $reply->message !!} </code>
            </p>
            @endforeach
            
            
        </div>
        </div>
        <!-- END Label on top Layout -->

        <!-- Horizontal Layout -->
        <h2 class="content-heading">Horizontal</h2>
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
 --}}
@endsection
@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
 
@endsection
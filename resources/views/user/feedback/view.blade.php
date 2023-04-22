@extends('layouts.main.master')
@section('content')
  <!-- Page Content -->
  <div class="row g-0 flex-md-grow-1">
    <div class="col-md-4 col-lg-5 col-xl-3">
      <div class="content">
        <!-- Toggle Side Content -->
        <div class="d-md-none push">
          <!-- Class Toggle, functionality initialized in Helpers.dmToggleClass() -->
          <button type="button" class="btn w-100 btn-alt-primary" data-toggle="class-toggle" data-target="#side-content" data-class="d-none">
            Inbox Menu
          </button>
        </div>
        <!-- END Toggle Side Content -->

        <!-- Side Content -->
        <div id="side-content" class="d-none d-md-block push">
          <!-- New Message -->
          <a href="{{ url('feedback/create') }}" class="btn w-100 btn-alt-primary mb-3">
            <i class="fa fa-plus opacity-50 me-1"></i> New Ticket
          </a>
          <!-- END New Message -->

          <!-- Search Messages -->
          {{-- <form action="be_pages_generic_inbox.html" method="POST" onsubmit="return false;">
            <div class="mb-4">
              <div class="input-group">
                <input type="text" class="form-control border-0" placeholder="Search Messages..">
                <span class="input-group-text border-0 bg-body-extra-light">
                  <i class="fa fa-fw fa-search"></i>
                </span>
              </div>
            </div>
          </form> --}}
          <!-- END Search Messages -->

          <!-- Sorting/Filtering -->
          {{-- <div class="d-flex justify-content-between mb-2"> --}}
            {{-- <div class="dropdown">
              <button type="button" class="btn btn-sm btn-link fw-semibold dropdown-toggle" id="inbox-msg-sort" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by
              </button>
              <div class="dropdown-menu fs-sm" aria-labelledby="inbox-msg-sort">
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-sort-amount-down me-1"></i> Newest
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-sort-amount-up me-1"></i> Oldest
                </a>
              </div>
            </div>
            <div class="dropdown">
              <button type="button" class="btn btn-sm btn-link fw-semibold dropdown-toggle" id="inbox-msg-filter" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filter by
              </button>
              <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="inbox-msg-filter">
                <a class="dropdown-item active" href="javascript:void(0)">
                  <i class="fa fa-fw fa-asterisk me-1"></i> New
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-archive me-1"></i> Archived
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-times-circle me-1"></i> Deleted
                </a>
              </div>
            </div> --}}
          {{-- </div> --}}
          <!-- END Sorting/Filtering -->

          <!-- Messages -->
          <div class="list-group fs-sm">
            @foreach ($feedbacks as $feedback)
            <a class="list-group-item list-group-item-action" href="{{url('feedback/view/'.$feedback->id)}}">
                {{-- <span class="badge rounded-pill bg-dark m-1 float-end">3</span> --}}
                <p class="fs-6 fw-bold mb-0">
                    Ticket #{{$feedback->id}}
                </p>
                <p class="text-muted mb-2">
                    {!! \Illuminate\Support\Str::words($feedback->message, 20) !!}
                </p>
                <p class="fs-sm text-muted mb-0">
                    <strong>{{$feedback->category}}</strong>, {{\Carbon\Carbon::parse($feedback->updated_at)->diffForHumans()}} 
                </p>
              </a>
            @endforeach
            
           
          </div>
          <!-- END Messages -->
        </div>
        <!-- END Side Content -->
      </div>
    </div>

    <div class="col-md-8 col-lg-7 col-xl-9 bg-body-dark">
        
      <!-- Main Content -->
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
                    <a class="fw-semibold link-fx" href="#">{{$replies->user->name}} </a>
                    {{-- <div class="fs-sm text-muted">s.fields@example.com</div> --}}
                    </div>
                    <div class="col-sm-5 d-sm-flex align-items-sm-center">
                    <div class="fs-sm text-muted text-sm-end w-100 mt-2 mt-sm-0">
                        <p class="mb-0"> {{ \Carbon\Carbon::parse($replies->created_at)->format('d/m/Y') }}</p>
                        <p class="mb-0"> {{ \Carbon\Carbon::parse($replies->created_at)->format('h:i:sa') }}</p>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="block-content">
                {!! $replies->message !!}
            </div>
            <div class="block-content bg-body-light">
                <div class="row g-sm">
                    <div class="col-6 col-sm-4 col-md-5 col-lg-4 col-xl-3">
                    <div class="options-container fx-item-zoom-in">
                        <img class="img-fluid options-item" src="{{$replies->proof_url}}" alt="">
                        <div class="options-overlay bg-black-75">
                        {{-- <div class="options-overlay-content">
                            <a class="btn btn-sm btn-primary" href="javascript:void(0)">
                            <i class="fa fa-download me-1"></i> Download
                            </a>
                        </div> --}}
                        </div>
                    </div>
                    <p class="fs-sm text-muted pt-2">
                        <i class="fa fa-paperclip"></i> Ticket #{{$replies->id}}
                    </p>
                    </div>
                    
                </div>
            </div>
        </div>
        @if($replies->replies()->count() > 0)
            
            
                 <!-- Discussion -->
          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Conversation - {{ $replies->replies()->count() }}</h3>
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
                @foreach ($replies->replies as $reply)
                  <tr class="table-active">
                    <td class="d-none d-sm-table-cell"></td>
                    <td class="fs-sm text-muted">
                      <a href="">{{$reply->user->name}}</a> on <span>{{ \Carbon\Carbon::parse($replies->created_at)->format('d l, Y : h:i:s:a') }}</span>
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
            <form action="{{ route('reply.feedback') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12 mb-3">
                    <label>Meesage</label>
                    <textarea class="form-control" name="message" id="js-ckeditor5-classic"></textarea>
                </div>
                
                <input type="hidden" name='user_id' value="{{ auth()->user()->id }}">
                <input type="hidden" name='feedback_id' value="{{ $replies->id }}">
                <input type="hidden" name='0' value="false">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-paper-plane opacity-50 me-1"></i> Send Reply
                        </button>
                    </div>
                </div>
               
            </form>
          </div>
        </div>
        <!-- END Reply -->
      </div>
      <!-- END Main Content -->
    </div>
  </div>
  <!-- END Page Content -->
@endsection
@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
 
@endsection
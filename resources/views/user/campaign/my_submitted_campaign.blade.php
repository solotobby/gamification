@extends('layouts.main.master')

@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: url({{ asset('assets/media/photos/photo19@2x.jpg') }});">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
          View Job
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero Section -->

   <!-- Page Content -->
   <div class="content content-boxed">
    <!-- Job -->
    <h2 class="content-heading">
      <i class="fa fa-check text-success me-1"></i> You completed this Campaign
    </h2>
    <div class="block block-rounded">
      <div class="block-content block-content-full">
        <div class="d-sm-flex">
          <div class="ms-sm-2 me-sm-4 py-2">
            <a class="item item-rounded bg-body-dark text-dark fs-4 mb-2 mx-auto" href="{{ url('campaign/'.$work->campaign->job_id) }}">
              {{-- @if($work->campaign->currency == 'NGN')  
              &#8358;{{$work->amount}}
              @else
              ${{$work->amount}}
              @endif --}}
              {{$work->currency}} {{$work->amount}}
            </a>
          </div>
          <div class="py-2">
            <a class="link-fx h4 mb-1 d-inline-block text-dark" href="{{ url('campaign/'.$work->campaign->job_id) }}">
             {{$work->campaign->post_title}}
            </a>
            <div class="fs-sm fw-semibold text-muted mb-2">
              Completed - {{ $work->campaign->completed()->where('status', 'Approved')->count() }}  of {{ $work->campaign->number_of_staff }}
            </div>
            <p class="text-muted mb-2">
              {!! \Illuminate\Support\Str::words($work->campaign->description, 50) !!}  
            </p>
            <div>
              <span class="badge bg-primary fw-semibold">{{$work->campaign->campaignType->name}}</span>
              <span class="badge bg-primary fw-semibold">{{$work->campaign->campaignCategory->name}}</span>
              {{-- <span class="badge bg-primary fw-semibold">Social</span> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END Job -->

    <!-- Apply form -->
    <h2 class="content-heading">
      {{$work->currency }} {{$work->amount}}
      {{-- @if($work->campaign->currency == 'NGN')  
          <i class="fa fa-arrow-right text-info me-1"></i> You will get &#8358;{{$work->amount}}
      @else
      <i class="fa fa-arrow-right text-info me-1"></i> You will get ${{$work->amount}}
      @endif --}}
    </h2>
   
      <div class="block block-rounded">
        <!-- Personal Information section -->
        <div class="block-content block-content-full">
          <h2 class="content-heading">Your response
            @if($work->status == 'Pending')
              <button type="button" class="btn btn-alt-warning btn-sm">
                <i class="fa fa-info opacity-50 me-1"></i> {{$work->status}}
              </button> 
              @elseif($work->status == 'Approved')
              <button type="button" class="btn btn-alt-primary btn-sm">
                <i class="fa fa-check opacity-50 me-1"></i> {{$work->status}}
              </button> 
              @else
              <button type="button" class="btn btn-alt-danger btn-sm">
                <i class="fa fa-times opacity-50 me-1"></i> {{$work->status}}
              </button> 
              @endif
          </h2> 
          
            {!! $work->comment !!}

            @if($work->proof_url != null)
            <hr>
            <h5>Proof of your work Image</h5>
            <img src="{{ $work->proof_url }}" class="img-thumbnail rounded float-left " alt="Proof">
            @else
            <div class="alert alert-warning text-small">
              No Image attached
            </div>
            @endif

            @if($work->status == 'Denied' && $work->updated_at >= '2024-01-16 14:07:14')
            <hr>
            @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <h4>Dispute Job Denial</h4> 
           
            @if($work->is_dispute == false)
              
            @if($check['is_completed'] == true) {{--  check if campaign is completed --}}
                  <div class="alert alert-success" role="alert">
                    This campaign has recieved complete Approved responses. 
                </div>
              @else
                <form action="{{ url('process/disputed/jobs') }}" method="POST">
                  @csrf
                  <div class="mb-4">
                    <label class="form-label" for="post-files">Give reason why you feel this job shouldn't be denied</small></label>
                        <textarea class="form-control" name="reason" id="js-ckeditor5-classic" required> {{ old('reason') }}</textarea>
                  </div>
                  <input type="hidden" name="id" value="{{ $work->id }}">
                  <div class="mb-4">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Submit </button>
                  </div>
                </form>
              @endif

            @else
                <div class="alert alert-success" role="alert">
                  Dispute submitted, expecting response
              </div>
            @endif

            @endif

        </div>
        <!-- END Personal Information section -->

        <!-- END Submit Form -->
      </div>
   
    <!-- END Apply Form -->
  </div>
  <!-- END Page Content -->


@endsection


@section('script')
<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>
@endsection
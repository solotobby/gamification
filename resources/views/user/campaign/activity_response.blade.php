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
 <!-- Hero Section -->
 <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
       {{ $campaign['campaign']['post_title'] }}
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero Section -->

  <div class="content content-boxed">
    <!-- Post Job form -->
    {{-- <h2 class="content-heading">
      <i class="fa fa-plus text-success me-1"></i> Create Campaign
    </h2> --}}
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

    <form action="{{  route('campaign.decision') }}" method="POST">
        @csrf
      <div class="block block-rounded">
       

        <!-- Files section -->
        <div class="block-content">
          <h2 class="content-heading">Campaign information | Submitted by <i>{{ $campaign['user']['name'] }}</i></h2>
          <div class="row items-push">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
             
              <div class="mb-4">
                <label class="form-label" for="post-files">Campaign Description </label>
                <p>{!! $campaign['campaign']['description'] !!}</p>
               
              </div>
              <div class="mb-4">
                <label class="form-label" for="post-files">Proof of completion of campaign
                </label>
                <p>{!! $campaign['campaign']['proof'] !!}</p>
                    
              </div>
              <hr>
              <div class="mb-4">
                <label class="form-label" for="post-files">Proof Submitted by Worker
                </label>
                <p>  {!! $campaign['comment'] !!}</p> 
              </div>
            

              @if($campaign['proof_url'] != null)
                <hr>
                <h5>Proof of work Image</h5>
                <img src="{{ asset($campaign['proof_url']) }}" class="img-thumbnail rounded float-left " alt="Proof">
                @else
                <div class="alert alert-warning text-small">
                  No Image attached
                </div>
              @endif
             
              <div class="mb-4 mt-4">
                <label class="form-label" for="post-files">Give reason for Approval or Denial</small></label>
                    <textarea class="form-control" name="reason" id="js-ckeditor5-classic" required> {{ old('reason') }}</textarea>
              </div>
              <input type="hidden" name="id" value="{{ $campaign['id'] }}">
              <input type="hidden" name="campaign_job_id" value="{{ $campaign['campaign']['job_id'] }}">
              <div class="mb-4">
                <button type="submit" name="action" value="approve" class="btn btn-success"><i class="fa fa-check"></i> Approve</button>
                <button type="submit" name="action" value="deny" class="btn btn-danger"><i class="fa fa-times"></i> Deny</button>
              </div>
              <a href="{{ url('campaign/activities/'.$campaign['campaign']['job_id']) }}" class="btn btn-secondary btn-sm mb-2"><i class="fa fa-backspace"></i> Back To List</a>
            </div>
            <div class="col-lg-2"></div>
           
          </div>
          
        </div>
        
        <!-- END Files section -->
      
      </div>
    </form>
    <!-- END Post Job Form -->
  </div>



@endsection

@section('script')

 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
 {{-- <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script> --}}
 {{-- <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script> --}}
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <!-- Page JS Helpers (CKEditor 5 plugins) -->
 <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>

@endsection
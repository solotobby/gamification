@extends('layouts.main.master')

@section('content')
 <!-- Hero Section -->
 <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
        Edit - {{ $campaign->post_title }}
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

    <form action="{{ route('campaign.status') }}" method="POST">
        @csrf
      <div class="block block-rounded">
        <div class="block-content block-content-full">
          <h2 class="content-heading">Campaign Information</h2>
          <div class="row items-push">
            <div class="col-lg-3">
              <p class="text-muted">
              Detailed information about the campaign
              </p>
            </div>
            <div class="col-lg-9">
               
              <div class="mb-4">
                <label class="form-label" for="post-type">Category</label>
                <br>
                {{ $campaign->campaignType->name }}
              </div>

              <div class="mb-4">
                <label class="form-label" for="post-category">Sub-Category</label>
                <br>
                {{ $campaign->campaignCategory->name }}
              </div>

              <div class="row mb-4">
                <div class="col-6">
                    <label class="form-label" for="post-salary-min">Number of Workers</label>
                    <br>
                  {{$campaign->number_of_staff}}
                </div>
                <div class="col-6">
                  @if(auth()->user()->wallet->base_currency == "Naira")
                      <label class="form-label" for="post-salary-min">Cost per Campaign(&#8358;)</label>
                    @else
                      <label class="form-label" for="post-salary-min">Cost per Campaign($)</label>
                    @endif
                    <br>
                    {{$campaign->campaign_amount}}
                </div>
              </div>
              <hr>
              @if(auth()->user()->wallet->base_currency == "Naira")
              <h4>Estimated Cost: &#8358;{{ $campaign->total_amount}} </h4>
              @else
              <h4>Estimated Cost: ${{ $campaign->total_amount}}</h4>
              @endif
              
            </div>
          </div>
        </div>
        <!-- END Job Meta section -->

        <!-- Files section -->
        <div class="block-content">
          <h2 class="content-heading">Campaign Description</h2>
          <div class="row items-push">
            <div class="col-lg-3">
              <p class="text-muted">
                Give detailed decription of the campaign
              </p>
            </div>
            <div class="col-lg-9">
              <div class="mb-4">
                <label class="form-label" for="post-title">Title</label>
                <input type="text" class="form-control" id="post-title" name="post_title" value="{{ $campaign->post_title }}" required>
                {{-- <small><i>Please give a simple campaign title e.g Facebook Like or Youtube comment</i></small> --}}
            </div>

            <div class="mb-4">
                <label class="form-label" for="post-title">External Link</label>
                <input type="url" class="form-control" id="post-title" name="post_link" value="{{ $campaign->post_link }}" required>
                {{-- <small><i>Please provide an external link for your campaign e.g https://myhotjobz.com or https://youtube.com/abc </i></small> --}}
            </div>

              <div class="mb-4">
                <label class="form-label" for="post-files">Campaign Description <small>(Ensure you provide simple and clear instruction on task to be done)</small></label>
                <textarea class="form-control" name="description" id="js-ckeditor5-classic" required> {!! $campaign->description !!}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label" for="post-files">Expected Campaign Proof
                </label>
                    <iframe name="server_answer" style="display:none"></iframe>
                    <textarea id="mytextareas" class="form-control" name="proof" required>{{ $campaign->proof }}</textarea>
              </div>
             
              <div class="mb-2">
                <div class="form-group">
                    <label for="exampleInputEmail1">Enter Reason for decline or aparroval</label>
                    <textarea class="form-control" id="exampleInputEmail1" name="reason" required>{{ $campaign->reason }}</textarea>
                  </div>
              </div>
             

            </div>
          </div>
        </div>
        <!-- END Files section -->
        <input type="hidden" name="id" value="{{ $campaign->id }}">
        <!-- Submit Form -->
        <div class="block-content block-content-full pt-0">
          <div class="row mb-4">
            {{-- offset-lg-5 --}}
            <div class="col-lg-3"></div>
            <div class="col-lg-9">
                <button type="submit"  class="btn btn-alt-primary" name="status" value="Live"> <i class="fa fa-save opacity-50 me-1"></i> Update & Approve Campaign</button>
               
                <button type="submit" class="btn btn-alt-danger" name="status" value="Decline"><i class="fa fa-times opacity-50 me-1"></i> Decline Campaign Abruptly</button>
              
            </div>
          </div>
        </div>
        <!-- END Submit Form -->
      </div>
    </form>
    <!-- END Post Job Form -->
  </div>
@if($campaign->status == 'Live')

  <div class="content content-boxed">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <h2 class="content-heading">Campaign Activities - {{ $activities->count() }}
            </h2>
            <table class="table table-hover">
                <thead>
                <tr>
                    {{-- <th scope="col">#</th> --}}
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Status</th>
                    <th scope="col">When</th>
                </tr>
                </thead>
                <tbody>
                 @foreach ($activities as $list )
                    <tr>
                        {{-- <th scope="row">1</th> --}}
                        <td>{{ $list->user->name }}</td>
                        <td>{{ $list->user->email }}</td>
                        <td>{{ $list->user->phone }}</td>
                        <td>{{ $list->status }}</td>
                        <td>{{ $list->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>

@endif


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
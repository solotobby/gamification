@extends('layouts.main.master')

@section('content')

 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Gist Groove Posts</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Manage Posts</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <div class="content">
    @php
    $url = 'http://gistgroove.com/details.php?slug=' . $slug;
        $message = urlencode("Check this out: " . $url);
    @endphp

    <div class="alert alert-info">
      {{-- <li class="fa fa-info"></li> --}}
      Fantastic! Share your post to get more views. Gistgroove pays you up to $30 per unique 1000 views. (include the whatsapp, facebook, twitter and linkedin share buttons)


    </div>


    <a href="https://api.whatsapp.com/send?text={{ $message }}" target="_blank" class="btn btn-success">
        <i class="fab fa-whatsapp"></i> Share on WhatsApp
    </a>
    <a class="btn btn-sm btn-secondary" href="https://twitter.com/intent/tweet?url=https://gistgroove.com/?slug={{ $slug }}" target="_blank">
        <span class="si si-social-twitter lg"></span> Share on X
      </a>
      <a class="btn btn-sm btn-primary" href="https://www.facebook.com/sharer/sharer.php?u=https://gistgroove.com/?slug={{ $slug }}" target="_blank">
        <span class="si si-social-facebook lg"></span> Share on Facebook
      </a>
      <a class="btn btn-sm btn-info" href="https://www.linkedin.com/sharing/share-offsite/?url=https://gistgroove.com/?slug={{ $slug }}" target="_blank">
        <span class="fab fa-linkedin-in lg"></span> Share on Linkedin
      </a>
    <br><br>
    <a href="{{ url('view/posts') }}" class="btn btn-secondary"> Make another Post </a>
  </div>

@endsection
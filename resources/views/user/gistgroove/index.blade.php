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
    <div class="alert alert-info">
      {{-- <li class="fa fa-info"></li> --}}
      Freebyz has partnered with GISTGROOVE BLOG to help you earn from trending news and gist around you. 
      When you share what's trending, Gistgroove will pay you for the views you get on your post. 
      Gistgroove pays up to $30 for every 1000 unique views your post get on Gistgroove. Payment calculated monthly.
    </div>
    <!-- Full Table -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Make A Post</h3>
          <div class="block-options">
            <button type="button" class="btn-block-option">
              <i class="si si-pencil"></i>
            </button>
          </div>
        </div>
        <form action="{{ route('save.post') }}" method="POST">
            @csrf
            <div class="block-content">
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
                

                <div class="mb-4">
                    <label class="form-label" for="dm-post-add-title">Title</label>
                    <input type="text" class="form-control" id="dm-post-add-title" name="title" placeholder="Post Title" required>
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" for="dm-post-add-title">Select A Category</label>
                   <select name="category_id" class="form-control" required>
                        <option value="">Select One</option>
                        @foreach ($categories as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                        @endforeach
                    </select>
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Body</label>
                    <textarea name="body" rows="5" class="form-control" required></textarea>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-alt-primary"  >
                    <i class="fa fa-fw fa-plus opacity-50 me-1"></i> Post On Gist Groove
                    </button>
                </div>


            </div>
        </form>
    </div>


    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">List of Post </h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
       
            
        <div class="alert alert-info">
          <li class="fa fa-info"></li> 
          Earn from every 1000 unique views your post get on Gistgroove. Payment will be calculated monthly.
            
            {{-- You'll get 50 points on every daily login. Accumulated points can be converted to cash which will be credited into your wallet. Every 1,000 points is equivalent to &#8358;50  --}}
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>Title</th>
                <th>Total</th>
                <th>Unique</th>
                <th>Share</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($posts as $point)
                <tr>
                    {{-- <td>
                     {{ \Carbon\Carbon::parse($point->created_at)->format('d F, Y') }}
                    </td> --}}
                    <td>
                        <a href="https://gistgroove.com/details.php?slug={{ $point->slug }}" target="_blank"> {{ $point->title }} </a>
                    </td>
                    <td>
                        {{ $point->pageViews()->count() }}
                    </td>
                    <td>
                        {{ $point->pageViews()->distinct('ip_address')->count() }}
                    </td>
                    <td>
                      <a class="btn btn-sm btn-secondary" href="https://twitter.com/intent/tweet?url=https://gistgroove.com/?slug={{ $point->slug }}" target="_blank">
                        <span class="si si-social-twitter lg"></span>
                      </a>
                      <a class="btn btn-sm btn-primary" href="https://www.facebook.com/sharer/sharer.php?u=https://gistgroove.com/?slug={{ $point->slug }}" target="_blank">
                        <span class="si si-social-facebook lg"></span>
                      </a>
                      <a class="btn btn-sm btn-info" href="https://www.linkedin.com/sharing/share-offsite/?url=https://gistgroove.com/?slug={{ $point->slug }}" target="_blank">
                        <span class="fab fa-linkedin-in lg"></span>
                      </a>
                      
                    </td>
                </tr>
                @endforeach
              
            </tbody>
          </table>
          {{-- <a href="{{ route('redeem.point') }}" class="btn btn-secondary mb-3 disabled">Redeem Points</a> --}}
        </div>
      </div>
    </div>
    <div class="d-flex">
      {!! $posts->links('pagination::bootstrap-4') !!}
    </div>
    <!-- END Full Table -->

  </div>


  @endsection
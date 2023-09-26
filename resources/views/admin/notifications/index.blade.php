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
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3"> Notifications </h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"> Notifications </li>
            <li class="breadcrumb-item active" aria-current="page">All</li>
          </ol>
        </nav>
      </div>
    </div>
</div>

 <!-- Page Content -->
 <div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Create Notifications</h3>
        </div>
        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
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

            <form action="{{ url('store/notification') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                        <div class="alert alert-info">
                            <strong>Announcement</strong>: Only displays at the user dashboard and pop up.<br>
                            <strong>Notification</strong>: Sent to all user notification<br>
                            <strong>Both</strong>: Shows on dashboard and sent to all users notification
                        </div>
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-6">
                      <div class="mb-4">
                        <label class="form-label" for="example-text-input">Title</label>
                        <input type="text" class="form-control" id="example-text-input-floating" name="title" required>
                    </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="">Select one</option>
                                <option value="announcement">Only Announcement</option>
                                <option value="notification">Only Notification</option>
                                <option value="both">Announncement & Notification</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Content</label>
                            <div class="form-floating mb-4">
                                
                                <textarea class="form-control" name="content" id="js-ckeditor5-classic" required> {{ old('description') }}</textarea>
                                {{-- <label class="form-label" for="example-text-input-floating">Enter Content</label> --}}
                            </div>
                            {{-- <input type="number" class="form-control" id="example-text-input" name="point" placeholder="Point"> --}}
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>

    <div class="block-content">
        <p>
            {{-- List of Categories --}}
        </p>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                {{-- <th>#</th> --}}
                <th>Message</th>
                <th>Status</th>
                <th>When</th>
              </tr>
            </thead>
            <tbody>
                {{-- <?php $i = 1; ?> --}}
                @foreach ($notifications as $s)
                <tr>
                    {{-- <td>{{ $i++ }}</td> --}}
                    <td>{!! $s->content !!}</td>
                    <td>{{ $s->status == 1 ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $s->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ url('change/notification/status/'.$s->id) }}" class="btn btn-alt-primary btn-sm">Change Status</a>
                        {{-- <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">View</button> --}}
                    </td>
                </tr>
                <div class="modal fade" id="modal-default-popout-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">SubCategories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pb-1">
                            <div class="col-xl-12">
                                <!-- With Badges -->
                                <div class="block block-rounded">
                                  <div class="block-header block-header-default">
                                    <h3 class="block-title">{{ $s->name }}</h3>
                                  </div>
                                  <div class="block-content">
                                    <ul class="list-group push">
                                       
                                        Lilly
                                    </ul>
                                  </div>
                                </div>
                                <!-- END With Badges -->
                              </div>
                            
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>

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
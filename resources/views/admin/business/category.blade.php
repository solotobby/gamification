@extends('layouts.main.master')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create Categories</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Create</li>
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
          </ol>
        </nav>
      </div>
    </div>
</div>

<div class="content">

    <!-- Elements -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Create Category</h3>
        </div>
        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
    
            <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                        Create The Categories here
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                        <label class="form-label" for="example-text-input">Name</label>
                        <input type="text" class="form-control" id="example-text-input" name="name" placeholder="Category">
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Create Category</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div class="block-content">
            <p>
                List of Categories
            </p>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-vcenter">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    {{-- <th>Count</th> --}}
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($category as $s)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{$s->name}}</td>
                       
                        {{-- <td>
                            <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">View</button>
                            <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-{{ $s->id }}">Edit Dollar Price</button>
                            <button type="button" class="btn btn-alt-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-naira-{{ $s->id }}">Edit Naira Price</button>
                        </td> --}}
                    </tr>
                
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
      </div>

@endsection
@extends('layouts.main.master')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Manage Categories</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"> Home </li>
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
          </ol>
        </nav>
      </div>
    </div>
</div>



 <div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Create Professional Category</h3>
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

            <form action="{{ route('professional.store.category') }}" method="POST" enctype="multipart/form-data">
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
            <form action="{{ route('professional.sub.store.category') }}" method="POST">
                @csrf
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                        Enter Subcategory Information
                        </p>
                    </div>
                
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                        <label class="form-label" for="example-select">Select Category</label>
                        <select class="form-select" id="example-select" name="category_id" required>
                            <option value="">Select One</option>
                            @foreach ($category as $cate)
                                <option value="{{ $cate->id }}">{{$cate->name}}</option>
                            @endforeach
                        </select>
                        </div>
    
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">SubCategory Name</label>
                            <input type="text" class="form-control" id="example-text-input" name="name" placeholder="Category">
                        </div>
    
                      
    
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Create SubCategory</button>
                        </div>
                    
                    </div>
             
                </div>
            </form>

            <hr>
            
            <form action="{{ route('professional.store.skillset') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                        Enter Skills
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                        <label class="form-label" for="example-text-input">Name of Skill set</label>
                        <input type="text" class="form-control" id="example-text-input" name="name" placeholder="Enter Skillset Name">
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-secondary" type="submit">Create Skillset</button>
                        </div>
                    </div>
                </div>
            </form>





        </div>


    </div>

    {{-- <div class="block-content">
        <p>
            List of Categories
        </p>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Point</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($points as $s)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$s->name}}</td>
                    <td>{{ $s->point }}</td>
                    <td>
                        <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">View</button>
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
                        {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach
            </tbody>
          </table>
        </div>
    </div> --}}

 </div>





@endsection
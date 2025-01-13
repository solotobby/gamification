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
 <!-- Page Content -->
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
        <form action="{{ route('store.subcategory') }}" method="POST">
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
                        <label class="form-label" for="example-text-input">Unit Price (Naira)</label>
                        <input type="number" class="form-control" id="example-text-input" name="amount" placeholder="unit price(naira)">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="example-text-input">Unit Price (USD)</label>
                      <input type="number" class="form-control" id="example-text-input" name="usd" placeholder="unit price(usd)">
                  </div>

                    <div class="mb-4">
                        <button class="btn btn-primary" type="submit">Create SubCategory</button>
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
                <th>Count</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($category as $s)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$s->name}}</td>
                    <td>{{ $s->subCate->count() }}</td>
                    <td>
                        <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">View</button>
                        {{-- <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-{{ $s->id }}">Edit Price</button> --}}
                        <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-naira-{{ $s->id }}">Edit Price</button>
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
                                        {{--  --}}
                                        @foreach ($s->subCate as $n)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                           {{$n->name}}
                                            <span class="badge rounded-pill bg-info">&#8358;{{ number_format($n->amount) }} - ${{ ($n->usd) }}</span>
                                          </li>
                                        @endforeach
                                      
                                      
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

                <div class="modal fade" id="modal-default-popout-edit-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-popout" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title">Edit SubCategories</h5>
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
                                  <form action="{{ url('edit/subcategories') }}" method="POST">
                                    @csrf
                                  <ul class="list-group push">
                                      
                                      @foreach ($s->subCate as $n)
                                      <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                          {{$n->name}}
                                          <span class="badge rounded-pill bg-info">&#8358;{{ number_format($n->amount) }}</span>
                                          <span class="badge rounded-pill bg-info">${{ number_format($n->usd) }}</span>
                                        </li>
                                        <div class="mb-4">
                                          <label class="form-label" for="example-text-input">Unit Price (Naira)</label>
                                          <input type="text" name="amount[]" class="form-control" size="5" value="{{ $n->amount }}" required>
                                        </div>
                                        <div class="mb-4">
                                          <label class="form-label" for="example-text-input">Unit Price (USD)</label>
                                          <input type="text" name="usd[]" class="form-control" size="5" value="{{ $n->usd }}" required>
                                        </div>
                                        <div class="mb-4">
                                          <label class="form-label" for="example-text-input">Unit Price (GHS)</label>
                                          <input type="text" name="ghs[]" class="form-control" size="5" value="{{ $n->usd }}" required>
                                        </div>
                                      <input type="hidden" name="id[]"  value="{{ $n->id }}" required>
                                      <hr>
                                      @endforeach
                                     
                                  </ul>
                                  <button class="btn btn-primary" type="submit">Update</button>
                                  </form>
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

                <div class="modal fade" id="modal-default-popout-edit-naira-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-popout" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title">Edit SubCategories(Naira)</h5>
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
                                  <form action="{{ url('edit/subcategories/naira') }}" method="POST">
                                    @csrf
                                  <ul class="list-group push">
                                      
                                      @foreach ($s->subCate as $n)
                                      <li class="list-group-item d-flex justify-content-between align-items-center">
                                          {{$n->name}}
                                          <span class="badge rounded-pill bg-info">${{ number_format($n->usd,2) }}</span>
                                        </li>
                                        <div class="mb-4">
                                          <label class="form-label" for="example-text-input">Unit Price (Naira)</label>
                                          <input type="text" name="amount[]" class="form-control" size="5" value="{{ $n->amount }}" required>
                                        </div>
                                       
                                      <input type="hidden" name="id[]"  value="{{ $n->id }}" required>
                                      <hr>
                                      @endforeach
                                    
                                  </ul>
                                  <button class="btn btn-primary" type="submit">Update</button>
                                  </form>
                                </div>
                              </div>
                              <!-- END With Badges -->
                            </div>
                          
                      </div>
                      
                      <div class="modal-footer">
                      <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
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
  <!-- END Elements -->
@endsection
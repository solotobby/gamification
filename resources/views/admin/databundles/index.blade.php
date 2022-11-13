@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create DataBundles</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Create</li>
            <li class="breadcrumb-item active" aria-current="page">DataBundles</li>
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
          <h3 class="block-title">Create Databundles</h3>
        </div>
        <div class="block-content">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
    
            <form action="{{ route('store.databundles') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-3">
                       
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Name</label>
                            <input type="text" class="form-control" id="example-text-input" name="name" placeholder="1 Gig Data" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Amount</label>
                            <input type="text" class="form-control" id="example-text-input" name="amount" placeholder="200" required>
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Create Databundle</button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                       
                    </div>
                </div>
                
            </form>

            <div class="row push">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($databundles as $data)
                                <tr>
                                    <th scope="row">{{ $i++ }}.</th>
                                    <td>{{ $data->name }}</td>
                                    <td>&#8358;{{ number_format($data->amount) }}</td>
                                    <td>{{ $data->status == '1' ? 'Active' : 'Inactive' }}</td>
                                    <td><a href="" class="btn btn-sm btn-primary">Edit</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
 </div>

@endsection
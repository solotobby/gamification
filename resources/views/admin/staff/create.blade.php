@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create Categories</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Create</li>
            <li class="breadcrumb-item active" aria-current="page">Staff</li>
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
      <h3 class="block-title">Enter Staff Information</h3>
    </div>
    <div class="block-content">
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif


        <form action="{{ route('staff.store') }}" method="POST">
            @csrf
            <div class="row push">
               
                <div class="col-lg-2">
                </div>
                <div class="col-lg-12">
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Select One</option>
                            <option value="Chief Technology Officer">Chief Technology Officer</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Name</label>
                        <input type="text" class="form-control" id="example-text-input" name="name" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Email</label>
                        <input type="email" class="form-control" id="example-text-input" name="email" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Phone</label>
                        <input type="number" class="form-control" id="example-text-input" name="phone" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Basic Salary</label>
                        <input type="number" class="form-control" id="example-text-input" name="basic_salary" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Bonus</label>
                        <input type="number" class="form-control" id="example-text-input" name="bonus" >
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Select Bank</label>
                        <select name="bank_code" class="form-control" required>
                            <option value="">Select One</option>
                            @foreach ($banklist as $list)
                                <option value="{{ $list['code']}}:{{ $list['name'] }}">{{ $list['name'] }}</option>
                            @endforeach
                           
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="example-text-input">Account Number</label>
                        <input type="number" class="form-control" id="example-text-input" name="account_number" required>
                    </div>

                    <div class="mb-4">
                        <button class="btn btn-primary" type="submit">Create</button>
                    </div>
                
                </div>

                <div class="col-lg-2">
                </div>
            </div>
        </form>
    </div>

  
  <!-- END Elements -->
@endsection
@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Remove Duplicate Account</h1>
        {{-- <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">Users List</li>
          </ol>
        </nav> --}}
      </div>
    </div>
  </div>

  
  <!-- Page Content -->
  <div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Duplicate Account Numbers with Different User account - {{ $duplicates->count() }}</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">

        <div class="table-responsive">
            {{-- <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons"> --}}
            <table class="table table-bordered table-striped table-vcenter">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Count</th>
                      <th>Account Name</th>
                      <th>Account Number</th>
                      <th>User Name</th>
                      <th>Email</th>
                      <th>Phone Number</th>
                     
                      {{-- <th>Country</th> --}}
                      {{-- <th>When Created</th> --}}
                      </tr>
              </thead>
              @foreach ($duplicates as $row) 
                {{-- echo "Account: {$row->account_number}, Name: {$row->name}, Count: {$row->total}\n"; --}}
            
                <tbody>
                    <tr>
                        <th scope="row">{{ $row->id }}</th>
                        <th scope="row">{{ $row->total }}</th>
                        <th scope="row">{{ $row->account_name }}</th>
                        <th scope="row">{{ $row->bank_name }}</th>
                        <th scope="row">{{ $row->account_number }}</th>
                        <th scope="row">{{ $row->created_at }}</th>
                        <th scope="row">{{ $row->user_name }}</th>
                        <th scope="row">{{ $row->email }}</th>
                        <th scope="row">{{ $row->phone }}</th>
                        
                    </tr>

                </tbody>
              @endforeach
            </table>
            </table>


      </div>
    </div>
  </div>

@endsection
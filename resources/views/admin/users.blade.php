@extends('layouts.main.master')

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Users</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">Users List</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <!-- Page Content -->
  <div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Users List</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
                <tr>
                    
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Balance</th>
                    <th>Ref. No</th>
                    <th>Status</th>
                    <th>When Created</th>
                    </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($users as $user)
                    <tr>
                        {{-- <th scope="row">{{ $i++ }}.</th> --}}
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ number_format(@$user->wallet->balance) }}</td>
                        <td>{{ $user->referees->count() }}</td>
                        <td>{{ $user->is_verified == "1" ? 'Verified' : 'unverified' }}</td>

                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    </tr>
                @endforeach
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- END Full Table -->

  </div>
@endsection
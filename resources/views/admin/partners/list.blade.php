@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Our Subscriber</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Subscriber</li>
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
                <h3 class="block-title">List</h3>
                <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
                </button>
                </div>
            </div>

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
        
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>SubCode</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Product</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($lists as $list)
                            <tr>
                                <td class="fw-semibold"> <a href=""> {{ $list->subscription_code }} </a> </td>
                                <td>{{ $list->subscriber->firstName }} {{ $list->subscriber->lastName }}</td>
                                <td>{{ $list->subscriber->email }}</td>
                                <td>{{ $list->subscriber->phone }}</td>
                                <td>{{ $list->amount }}</td>
                                <td>{{ $list->product }}</td>
                                <td>{{ $list->is_paid == true ? 'Paid' : 'Not Paid' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                </div>
            </div>
        </div>
   </div>

@endsection
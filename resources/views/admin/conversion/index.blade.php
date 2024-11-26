@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Currency Rate</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Currency Rate</li>
          </ol>
        </nav>
      </div>
    </div>
</div>
 <!-- Page Content -->
<div class="content">  
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Currency Rate</h3>
          </div>
          <div class="block-content">
        <p>
            @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
        </p>
        <div class="table-responsive">
        <table class="table table-bordered table-striped table-vcenter">
            <thead>
            <tr>
                <th>#</th>
                <th>From</th>
                <th>To</th>
                <th>Rate</th>
            </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($baseRates as $s)
                <tr>
                    <td>{{ $i++ }}.</td>
                    <td>{{ $s['from'] }}</td>
                    <td>{{ $s['to'] }}</td>
                    <td>{{ $s['rate']}}</td>
                </tr>
               
                @endforeach
            </tbody>
        </table>
        </div>
  </div>
</div>

@endsection
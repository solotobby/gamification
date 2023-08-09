@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="assets/js/plugins/raty-js/jquery.raty.css">
@endsection
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Send Broadcast SMS/Mail</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Broadcast SMS/Mail</li>
          </ol>
        </nav>
      </div>
    </div>
</div>
<!-- END Hero -->



@endsection
@section('script')
<script src="assets/js/plugins/raty-js/jquery.raty.js"></script>
@endsection
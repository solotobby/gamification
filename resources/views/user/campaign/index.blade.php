
@extends('layouts.main.master')

@section('title', 'Winner List')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Campaigns</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Campaign</li>
            <li class="breadcrumb-item active" aria-current="page">View Campaign</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Job List</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>Name</th>
                <th>Progress</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($lists as $list)
                <tr>
                    <td>
                      {{ $list->post_title }}
                    </td>
                    <td>
                        {{ $list->completed()->where('status', 'Approved')->count(); }}/{{ $list->number_of_staff }}
                     </td>
                    <td>
                        &#8358; {{ $list->campaign_amount }}
                      </td>
                      <td>
                        &#8358; {{ number_format($list->total_amount) }}
                      </td>
                     
                   
                    <td>{{ $list->status }}</td>
                    <td>
                      @if($list->status == "Offline")
                        <a href="{{ url('golive/'.$list->job_id) }}" class="btn btn-primary btn-sm"> Go Live </a>
                      @else
                      <a href="{{ url('campaign/activities/'.$list->job_id) }}" class="btn btn-success btn-sm"> View Activities </a>
                      @endif
                      {{-- <a href="{{ url('campaign/'.$list->job_id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>  --}}
                    </td>
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

@section('script')

<script src="{{asset('src/assets/js/pages/be_ui_progress.min.js')}}"></script>
@endsection
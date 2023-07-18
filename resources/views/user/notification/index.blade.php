@extends('layouts.main.master')

@section('content')

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Notifications</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Notifications</li>
            <li class="breadcrumb-item active" aria-current="page">All</li>
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
        <h3 class="block-title">Notifications </h3>
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
                <th>Status</th>
                <th>Title</th>
                <th>Message</th>
                <th>When</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $notify)
                <tr>
                    <td>
                        @if($notify->is_read)
                            <i class="fa fa-fw fa-check-circle text-success"></i>
                        @else
                            <i class="fa fa-fw fa-check-circle text-success"></i>
                        @endif
                    </td>
                    <td>
                        {{ $notify->title}}
                    </td>
                    <td>
                        {{ $notify->message}}
                    </td>
                    <td>
                        {{ $notify->created_at->diffForHumans() }}
                    </td>
                </tr>
                @endforeach
              
            </tbody>
          </table>
        
        </div>
      </div>
    </div>
    <div class="d-flex">
      {!! $notifications->links('pagination::bootstrap-4') !!}
    </div>
    <!-- END Full Table -->

  </div>

@endsection
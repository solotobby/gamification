
@extends('layouts.main.master')

@section('title', 'Winner List')


@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">My Jobs</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Jobs</li>
            <li class="breadcrumb-item active" aria-current="page">My Job</li>
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
        <p>
        </p>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>Name</th>
                <th>&#8358; Amount</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($lists as $list)
                <tr>
                    <td class="fw-semibold">
                      {{ $list->campaign->post_title }}
                    </td>
                    <td>
                        {{ $list->campaign->campaign_amount }}
                      </td>
                    <td>{{ $list->status }}</td>
                    <td><a href="{{ url('campaign/my/submitted/'.$list->id) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-eye opacity-20 me-1"> View</i></a></td>
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
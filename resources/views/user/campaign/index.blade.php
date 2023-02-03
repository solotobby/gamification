
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
                        {{-- <a href="#" class="btn btn-primary btn-sm disabled"> Go Live </a> --}}
                      @else
                      <a href="{{ url('campaign/activities/'.$list->job_id) }}" class="btn btn-success btn-sm"> View Activities </a>
                      @endif
                      <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $list->job_id }}">Add More Workers</button>
                      {{-- <a href="{{ url('campaign/'.$list->job_id.'/edit') }}" class="btn btn-warning btn-sm">Modify Workers</a>  --}}
                      {{-- <a href="{{ url('campaign/'.$list->job_id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>  --}}
                    </td>
                  </tr>


                   <!-- Pop Out Default Modal -->
                   <div class="modal fade" id="modal-default-popout-{{ $list->job_id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title"> Add More Worker </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body pb-1">
                          Current Number of Workers - {{ $list->number_of_staff }} <br>
                          Current Value per Job  - {{ number_format($list->campaign_amount) }} <br>
                          Current Value  - &#8358;{{ number_format($list->total_amount) }} <br>
                          <hr>
                          <form action="{{ route('addmore.workers') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                              <label class="form-label" for="post-files">Number of Worker</small></label>
                                  <input class="form-control" name="new_number" type="number" required>
                            </div>
                            <input type="hidden" name="id" value="{{ $list->job_id }}">
                            <input type="hidden" name="amount" value="{{ $list->campaign_amount }}">
                            <div class="mb-4">
                              <button class="btn btn-primary" type="submit">Add</button>
                            </div>
                           </form>

                           
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                        </div>
                    </div>
                    </div>
                </div>

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
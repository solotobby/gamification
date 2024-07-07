
@extends('layouts.main.master')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Surveys</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Surveys</li>
            <li class="breadcrumb-item active" aria-current="page">List Surveys</li>
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
        <h3 class="block-title">Survey List</h3>
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
                  <th>Title</th>
                  <th>Unit Price</th>
                  <th>Total Price</th>
                  <th>Response Expt.</th>
                  <th>Status</th>
                  
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($lists as $list)
                  <tr>
                      <td>
                        @if($list->status == 'in_progress')
                            {{ $list->title }}
                        @else
                            <a href="{{ url('preview/form/'.$list->survey_code) }}"> {{ $list->title }}</a>
                        @endif
                      </td>
                      <td>
                          {{ $list->amount }}
                       </td>
                      <td>
                        {{ $list->total_amount }}
                        {{-- @if($list->currency == 'NGN')
                          &#8358;{{ $list->campaign_amount }}
                          @else
                           ${{ $list->campaign_amount }}
                          @endif --}}
                      </td>
                        <td>
                         {{$list->number_of_response}}
                        </td>
                        <td>
                            {{$list->status}}
                           </td>
                       
                     
                     
                      <td>
                           @if($list->status == 'in_progress')
                                <a href="{{ url('survey/'.$list->survey_code) }}" class="btn btn-alt-info btn-sm"> Continue  </a>
                           @else
                           <a href="#" class="btn btn-warning btn-sm"> View Responses </a>
                           @endif
                      </td>
                    </tr>
  
  
                     <!-- Pop Out Default Modal -->
                     {{-- <div class="modal fade" id="modal-default-popout-{{ $list->job_id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-popout" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                          <h5 class="modal-title"> Add More Worker </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
  
                          <div class="modal-body pb-1">
                            Current Number of Workers - {{ $list->number_of_staff }} <br>
                            @if($list->currency == 'NGN')
                            Value per Job  - &#8358;{{ number_format($list->campaign_amount) }} <br>
                            Total Value of Job  - &#8358;{{ number_format($list->total_amount) }} <br>
                            @else
                            Value per Job  - ${{ number_format($list->campaign_amount, 2) }} <br>
                            Total Value of Job  - ${{ number_format($list->total_amount, 2) }} <br>
                            @endif
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
                          </div>
                      </div>
                      </div>
                  </div> --}}
  
                  @endforeach
                
              </tbody>
            </table>
            <div class="d-flex">
              {!! $lists->links('pagination::bootstrap-4') !!}
            </div>
          </div>

      </div>
    </div>


  @endsection
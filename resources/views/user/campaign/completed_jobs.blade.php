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
            <li class="breadcrumb-item active" aria-current="page">Completed Campaigns</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Completed Campaign</h3>
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
                <th>Amount</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Dispute</th>
                <th>Time Remaining</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($lists as $list)
                  <tr>
                    <td>
                      {{ @$list->campaign->post_title }}
                    </td>
                    <td>
                      {{ @$list->currency }} {{ @$list->amount }}
                    </td>
                    <td>
                      @if(@$list->status == 'Denied')
                        <span class="badge bg-danger">{{ @$list->status }}</span>
                        @if($list->slot_released)
                          <br><small class="text-muted">Slot Released</small>
                        @endif
                      @else
                        <span class="badge bg-success">{{ @$list->status }}</span>
                      @endif
                    </td>
                    <td>
                      <code>{!! @$list->reason !!}</code>
                    </td>
                    <td>
                      @if(@$list->status == 'Denied')
                        @if($list->is_dispute)
                          <span class="badge bg-warning">Dispute Pending</span>
                        @elseif($list->slot_released)
                          <span class="badge bg-secondary">Slot Released</span>
                        @elseif($list->canDispute())
                          <a href="{{ url('campaign/my/submitted/'.$list->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-exclamation-triangle"></i> Dispute Now
                          </a>
                        @else
                          <span class="badge bg-secondary">Dispute Window Closed</span>
                        @endif
                      @else
                        <span class="badge bg-secondary">N/A</span>
                      @endif
                    </td>
                    <td>
                      @if(@$list->status == 'Denied' && !$list->slot_released && !$list->is_dispute)
                        @php
                          $remaining = $list->remainingDisputeTime();
                        @endphp
                        @if($remaining > 0)
                          <span class="badge bg-info">{{ $remaining }}h remaining</span>
                        @else
                          <span class="badge bg-danger">Expired</span>
                        @endif
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>
                      {{ @$list->created_at->format('M d, Y H:i') }}
                    </td>
                  </tr>
                @endforeach
            </tbody>
          </table>
          <div class="d-flex">
            {!! $lists->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
<script src="{{asset('src/assets/js/pages/be_ui_progress.min.js')}}"></script>
@endsection

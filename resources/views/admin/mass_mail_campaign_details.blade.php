@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Campaign Details</h1>
            <a href="{{ route('mass.mail.campaigns') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Campaigns
            </a>
        </div>
    </div>
</div>

<div class="content">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                        {{-- <div class="fs-2 fw-bold">{{ number_format($campaign->total_recipients) }}</div> --}}
                        <div class="fs-2 fw-bold">{{ number_format($totalSent) }}</div>
                        <div class="text-muted">Total Sent</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                        {{-- <div class="fs-2 fw-bold text-success">{{ number_format($campaign->delivered) }}</div> --}}
                        <div class="fs-2 fw-bold text-success">{{ number_format($delivered) }}</div>
                        <div class="text-muted">
                            Delivered
                                <small>({{ $totalSent > 0 ? round(($delivered/$totalSent)*100, 1) : 0 }}%)</small>
                            {{-- <small>({{ $campaign->total_recipients > 0 ? round(($campaign->delivered/$campaign->total_recipients)*100, 1) : 0 }}%)</small> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                      <div class="fs-2 fw-bold text-primary">{{ number_format($opened) }}</div>
                        {{-- <div class="fs-2 fw-bold text-primary">{{ number_format($campaign->opened) }}</div> --}}
                        <div class="text-muted">
                            Opened
                                <small>({{ $delivered > 0 ? round(($opened/$delivered)*100, 1) : 0 }}%)</small>
                            {{-- <small>({{ $campaign->delivered > 0 ? round(($campaign->opened/$campaign->delivered)*100, 1) : 0 }}%)</small> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                      <div class="fs-2 fw-bold text-danger">{{ number_format($failedBounced) }}</div>
                        {{-- <div class="fs-2 fw-bold text-danger">{{ number_format($campaign->bounced + $campaign->failed) }}</div> --}}
                        <div class="text-muted">Bounced/Failed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Info -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Campaign Information</h3>
        </div>
        <div class="block-content">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Subject:</strong> {{ $campaign->subject }}
                </div>
                <div class="col-md-6">
                    <strong>Sent:</strong> {{ $campaign->created_at->format('M d, Y H:i') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Audience:</strong> {{ ucfirst($campaign->audience_type) }}
                    @if($campaign->country_filter)
                        - {{ $campaign->country_filter }}
                    @endif
                </div>
                <div class="col-md-6">
                    <strong>Sent By:</strong> {{ $campaign->sentBy->name ?? 'N/A' }}
                </div>
            </div>
            <div>
                <strong>Message Preview:</strong>
                <div class="border p-3 mt-2" style="max-height: 200px; overflow-y: auto;">
                    {!! $campaign->message !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Recipients Table -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Recipients ({{ number_format($logs->total()) }})</h3>
        </div>
        <div class="block-content">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Delivered At</th>
                        <th>Opened At</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                        <td>{{ $log->email }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'secondary',
                                    'sent' => 'info',
                                    'delivered' => 'success',
                                    'opened' => 'primary',
                                    'bounced' => 'warning',
                                    'failed' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$log->status] ?? 'secondary' }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td>{{ $log->sent_at ? $log->sent_at->format('M d, H:i') : '-' }}</td>
                        <td>{{ $log->delivered_at ? $log->delivered_at->format('M d, H:i') : '-' }}</td>
                        <td>{{ $log->opened_at ? $log->opened_at->format('M d, H:i') : '-' }}</td>
                        <td>
                            @if($log->error_message)
                                <small class="text-danger">{{ Str::limit($log->error_message, 50) }}</small>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No recipients found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

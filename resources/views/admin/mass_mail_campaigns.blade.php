{{-- resources/views/admin/mass_mail_campaigns.blade.php --}}
@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Email Campaigns</h1>
            <a href="{{ route('mass.mail') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> New Campaign
            </a>
        </div>
    </div>
</div>

<div class="content">
    <!-- Stats Row -->
    <div class="row">
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                        <div class="fs-2 fw-bold">{{ number_format($stats['total_campaigns']) }}</div>
                        <div class="text-muted">Total Campaigns</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                        <div class="fs-2 fw-bold text-info">{{ number_format($stats['total_sent']) }}</div>
                        <div class="text-muted">Emails Sent</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                        <div class="fs-2 fw-bold text-success">{{ number_format($stats['total_delivered']) }}</div>
                        <div class="text-muted">
                            Delivered
                            <small>({{ $stats['total_sent'] > 0 ? round(($stats['total_delivered']/$stats['total_sent'])*100, 1) : 0 }}%)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center">
                        <div class="fs-2 fw-bold text-primary">{{ number_format($stats['total_opened']) }}</div>
                        <div class="text-muted">
                            Opened
                            <small>({{ $stats['total_delivered'] > 0 ? round(($stats['total_opened']/$stats['total_delivered'])*100, 1) : 0 }}%)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Audience</th>
                        <th>Total</th>
                        <th>Delivered</th>
                        <th>Opened</th>
                        <th>Bounced</th>
                        <th>Failed</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->subject }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($campaign->audience_type) }}</span>
                            @if($campaign->country_filter)
                                <span class="badge bg-secondary">{{ $campaign->country_filter }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($campaign->total_recipients) }}</td>
                        <td>
                            <span class="text-success">{{ number_format($campaign->delivered) }}</span>
                            <small class="text-muted">({{ $campaign->total_recipients > 0 ? round(($campaign->delivered/$campaign->total_recipients)*100, 1) : 0 }}%)</small>
                        </td>
                        <td>
                            <span class="text-primary">{{ number_format($campaign->opened) }}</span>
                            <small class="text-muted">({{ $campaign->delivered > 0 ? round(($campaign->opened/$campaign->delivered)*100, 1) : 0 }}%)</small>
                        </td>
                        <td><span class="text-warning">{{ number_format($campaign->bounced) }}</span></td>
                        <td><span class="text-danger">{{ number_format($campaign->failed) }}</span></td>
                        <td>{{ $campaign->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('mass.mail.campaign.details', $campaign->id) }}" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No campaigns found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Complete applications.blade.php --}}
@extends('layouts.main.master')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Job Applications</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.career-hub.index') }}">Career Hub</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Applications</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <!-- Job Info -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $job->title }}</h3>
                <div class="block-options">
                    <a href="{{ route('career-hub.show', $job->slug) }}" target="_blank" class="btn btn-sm btn-alt-primary">
                        <i class="fa fa-external-link-alt me-1"></i> View Job
                    </a>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Company:</strong> {{ $job->company_name }}
                    </div>
                    <div class="col-md-3">
                        <strong>Type:</strong> {{ ucfirst($job->type) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Views:</strong> {{ number_format($job->views_count) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Applications:</strong> {{ number_format($applications->total()) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Applications - {{ $applications->total() }}</h3>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Applicant</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = ($applications->currentPage() - 1) * $applications->perPage() + 1; @endphp
                            @forelse($applications as $app)
                                <tr>
                                    <th>{{ $i++ }}</th>
                                    <td>
                                        <a href="{{ url('user/' . $app->user->id . '/info') }}" target="_blank">
                                            {{ $app->user->name }}
                                        </a>
                                    </td>
                                    <td>{{ $app->user->email }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.career-hub.applications.status', $app) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="reviewing" {{ $app->status == 'reviewing' ? 'selected' : '' }}>
                                                    Reviewing</option>
                                                <option value="shortlisted" {{ $app->status == 'shortlisted' ? 'selected' : '' }}>
                                                    Shortlisted</option>
                                                <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>
                                                    Rejected</option>
                                                <option value="accepted" {{ $app->status == 'accepted' ? 'selected' : '' }}>
                                                    Accepted</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{ $app->created_at->format('d/m/Y @ h:i a') }}</td>
                                    <td>
                                        @if($app->resume_path)
                                            @php
                                                $resumeUrl = Str::startsWith($app->resume_path, ['http://', 'https://'])
                                                    ? $app->resume_path
                                                    : Storage::url($app->resume_path);
                                            @endphp

                                            <a href="{{ $resumeUrl }}" target="_blank" class="btn btn-sm btn-alt-info">
                                                <i class="fa fa-file-pdf me-1"></i> Resume
                                            </a>
                                        @endif

                                        @if($app->cover_letter)
                                            <button class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                                data-bs-target="#coverLetterModal{{ $app->id }}">
                                                <i class="fa fa-envelope me-1"></i> Letter
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Cover Letter Modal --}}
                                @if($app->cover_letter)
                                    <div class="modal fade" id="coverLetterModal{{ $app->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cover Letter - {{ $app->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p style="white-space: pre-wrap;">{{ $app->cover_letter }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <p class="text-muted">No applications yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($applications->hasPages())
                        <div class="d-flex">
                            {!! $applications->links('pagination::bootstrap-4') !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

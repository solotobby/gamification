@extends('layouts.main.master')

@section('content')
@php
    $user   = auth()->user();
    $wallet = $user->wallet;
    $base   = $wallet->base_currency;
@endphp

<!-- Hero -->
<div class="bg-image" style="background-image:url('https://i.natgeofe.com/n/9e7c6381-8205-4a0c-a3a6-e744bf86a751/climbing-8000-meters-first-winter-ascents-everest.jpg');">
    <div class="bg-primary-dark-op">
        <div class="content content-full">
            <div class="row my-3">

                <!-- User Info -->
                <div class="col-md-5 d-md-flex align-items-md-center">
                    <div class="py-4 text-center text-md-start">
                        <h1 class="fs-2 text-white mb-2">
                            {{ $user->name }}

                            @if ($base === 'NGN' && $user->is_verified)
                                <i class="fa fa-check opacity-75 ms-1"></i>
                            @elseif ($base === 'USD' && $user->USD_verified)
                                <i class="fa fa-check-double opacity-75 ms-1"></i>
                            @endif
                        </h1>

                        <h2 class="fs-lg fw-normal text-white-75 mb-0">
                            Complete Simple Jobs and Get Paid!
                        </h2>
                    </div>
                </div>

                <!-- Stats -->
                <div class="col-md-7 d-md-flex align-items-md-center">
                    <div class="row w-100 text-center">

                        <!-- Balance -->
                        <div class="col-4">
                            <p class="fs-3 fw-semibold text-white mb-0">
                                {{ $base }}
                                {{ number_format(
                                    $base === 'NGN'
                                        ? $wallet->balance
                                        : ($base === 'USD'
                                            ? $wallet->usd_balance
                                            : $wallet->base_currency_balance), 2) }}
                            </p>
                            <p class="fs-sm text-white-75">
                                <i class="fa fa-money-bill me-1"></i> Balance
                            </p>
                        </div>

                        <!-- Referrals -->
                        <div class="col-4">
                            <p class="fs-3 fw-semibold text-white mb-0">
                                {{ $user->referrals_count ?? 0 }}
                            </p>
                            <p class="fs-sm text-white-75">
                                <i class="fa fa-users me-1"></i> Referrals
                            </p>
                        </div>

                        <!-- Jobs -->
                        <div class="col-4">
                            <p class="fs-3 fw-semibold text-white mb-0">
                                {{ $completed ?? 0 }}
                            </p>
                            <p class="fs-sm text-white-75">
                                <i class="fa fa-briefcase me-1"></i> Jobs Done
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Alerts -->
<div class="content content-full">

    @if ($announcement)
        <div class="alert alert-info">{!! $announcement->content !!}</div>
    @endif

    @foreach (['success','error'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }}">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    <!-- Category Filter -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-select" id="jobs-categories">
                <option value="0">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Jobs -->
    <div id="display-jobs"></div>
    <div id="pagination-controls" class="mt-3"></div>

</div>

@include('layouts.resources.pathway')

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const baseApi = "{{ url('available/jobs') }}";
    const baseUrl = "{{ url('campaign') }}";

    function loadJobs(url) {
        fetch(url)
            .then(r => r.json())
            .then(({ data, current_page, last_page }) => {

                const box = document.getElementById('display-jobs');
                box.innerHTML = '';

                if (!data.length) {
                    box.innerHTML = `<div class="alert alert-info">No jobs found</div>`;
                    return;
                }

                data.filter(j => !j.is_completed).forEach(job => {
                    box.innerHTML += `
                        <a href="${baseUrl}/${job.job_id}">
                            <div class="block block-rounded mb-2">
                                <div class="block-content border-start border-3 border-primary">
                                    <h3 class="h5">${job.post_title}</h3>
                                    <p>${job.local_currency} ${job.local_converted_amount}</p>
                                </div>
                            </div>
                        </a>
                    `;
                });

                renderPagination(current_page, last_page);
            })
            .catch(() => {
                document.getElementById('display-jobs')
                    .innerHTML = `<div class="alert alert-warning">Failed to load jobs</div>`;
            });
    }

    function renderPagination(page, last) {
        const controls = document.getElementById('pagination-controls');
        controls.innerHTML = '';

        if (page > 1)
            controls.innerHTML += `<button class="btn btn-sm btn-primary me-2" onclick="loadJobs('${baseApi}/0?page=${page-1}')">Prev</button>`;

        if (page < last)
            controls.innerHTML += `<button class="btn btn-sm btn-primary" onclick="loadJobs('${baseApi}/0?page=${page+1}')">Next</button>`;
    }

    loadJobs(`${baseApi}/0?page=1`);

    document.getElementById('jobs-categories').addEventListener('change', e => {
        loadJobs(`${baseApi}/${e.target.value}?page=1`);
    });
});
</script>
@endsection

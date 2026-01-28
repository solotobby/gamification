@extends('layouts.main.master')
@section('style')
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection

@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Paused Campaign</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Campaign</li>
                        <li class="breadcrumb-item active" aria-current="page">Paused Campaign</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Paused Campaign - {{ $campaigns->count() }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="block block-rounded mb-3">
                    <div class="block-content">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                    placeholder="Search by job ID, campaign name, or creator..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                @if(request('search'))
                                    <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Creator</th>
                                <th>Name</th>
                                <th>Staffs</th>
                                <th>Completed</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Impression</th>
                                <th>Action</th>
                                <th>When Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaigns as $camp)
                                <tr>
                                    <th scope="row">{{ $camp->job_id }}</th>
                                    <td class="fw-semibold"><a href="{{ url('campaign/info/' . $camp->id) }}"
                                            target="_blank">{{$camp->post_title}}</a></td>
                                    <td><a href="{{ url('user/' . $camp->user->id . '/info') }}">{{ $camp->user->name }}</a></td>
                                    <td>{{ $camp->pending_count }}/{{ $camp->number_of_staff }}</td>
                                    <td>{{ $camp->completed_count }}/{{ $camp->number_of_staff }}</td>
                                    <td>
                                        @if($camp->currency == 'NGN')
                                            &#8358;{{ number_format($camp->campaign_amount) }}
                                        @elseif($camp->currency == 'USD')
                                            ${{ $camp->campaign_amount }}
                                        @else
                                            {{ $camp->currency }} {{ $camp->campaign_amount }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($camp->currency == 'NGN')
                                            &#8358;{{ number_format($camp->total_amount) }}
                                        @elseif($camp->currency == 'USD')
                                            ${{ $camp->total_amount }}
                                        @else
                                            {{ $camp->currency }} {{ $camp->total_amount }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($camp->impressions) }}</td>
                                    <td>
                                        <a href="{{ url('campaign/unpause/' . $camp->id) }}" class="btn btn-sm btn-success"
                                            onclick="return confirm('Unpause this campaign?')">
                                            <i class="fa fa-play"></i> Unpause
                                        </a>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($camp->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex">
                        {!! $campaigns->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
@endsection

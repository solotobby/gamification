@extends('layouts.main.master')
@section('content')

 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Login Points</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Login Points</li>
            <li class="breadcrumb-item active" aria-current="page">View All</li>
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
        <h3 class="block-title">List of Points | Redeemed Points - {{ $point->where('is_redeemed', true)->sum('point') }} | Unredeemed Point - {{$point->where('is_redeemed', false)->sum('point') }}</h3>
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

            @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
            @endif
            
        <div class="alert alert-info">
          Hi, Login point is not longer active.
            {{-- <li class="fa fa-info"></li>  --}}
            {{-- You'll get 50 points on every daily login. Accumulated points can be converted to cash which will be credited into your wallet. Every 1,000 points is equivalent to &#8358;50  --}}
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>Date</th>
                <th>Point</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($loginpoints as $point)
                <tr>
                    <td>
                     {{ \Carbon\Carbon::parse($point->date)->format('d F, Y') }}
                    </td>
                    <td>
                        {{ $point->point }}
                    </td>
                    <td>
                        {{ $point->is_redeemed == true ? 'Redeemed' : 'Not Redeemed' }}
                    </td>
                </tr>
                @endforeach
              
            </tbody>
          </table>
          <a href="{{ route('redeem.point') }}" class="btn btn-secondary mb-3 disabled">Redeem Points</a>
        </div>
      </div>
    </div>
    <div class="d-flex">
      {!! $loginpoints->links('pagination::bootstrap-4') !!}
    </div>
    <!-- END Full Table -->

  </div>
@endsection
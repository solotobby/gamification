
@extends('layouts.main.master')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Banners</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Ad Banner</li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
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
        <h3 class="block-title">Banners</h3>
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
            
        {{-- <div class="alert alert-info">
          Hi, Login point is not longer active.
        </div> --}}

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Budget</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Date Created</th>
                {{-- <th>End Date</th> --}}
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($bannerList as $banner)
                <tr>
                    <td>
                       {{$banner->banner_id}}
                    </td>  
                    <td>
                      <a href="{{ url('user/'.$banner->user->id.'/info') }}" target="_blank"> {{ $banner->user->name}} </a>
                  </td>  
                    <td>
                        @if($banner->currency == 'NGN')
                        &#8358;{{ number_format($banner->amount,2) }}
                        @else
                            {{ number_format($banner->amount,2) }}
                        @endif
                    </td>
                    <td>
                      {{$banner->click_count == null ? '0' : $banner->click_count}}/{{$banner->clicks}}
                    </td>
                    
                    <td>
                      {{ $banner->live_state == null ? 'Under Review' : $banner->live_state .' on '. \Carbon\Carbon::parse($banner->banner_end_date)->format('d F, Y') }}
                        
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($banner->date)->format('d F, Y') }}
                    </td>
                    {{-- <td>
                      {{ \Carbon\Carbon::parse($banner->banner_end_date)->format('d F, Y') }}
                    </td> --}}
                    <td>
                      @if($banner->status == true)
                          <button class="btn btn-secondary btn-sm disabled"> {{ $banner->live_state}}</button>
                      @else
                          <a href="{{ url('admin/banner/activate/'.$banner->id) }}" class="btn btn-secondary btn-sm">Take Live</a>
                      @endif
                    </td>
                </tr>
                @endforeach
              
            </tbody>
          </table>
          {{-- <a href="{{ route('redeem.point') }}" class="btn btn-secondary mb-3 disabled">Redeem Points</a> --}}
        </div>
      </div>
    </div>
    <div class="d-flex">
      {{-- {!! $loginpoints->links('pagination::bootstrap-4') !!} --}}
    </div>
    <!-- END Full Table -->

  </div>
  @endsection

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
                <th>Clicks</th>
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
                   
                    <td>
                     

                      <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $banner->id }}">View</button>
                    </td>
                </tr>


                <div class="modal fade" id="modal-default-popout-{{ $banner->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title">Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body pb-1">
                          <div class="col-xl-12">
                              <!-- With Badges -->
                              <div class="block block-rounded">
                                <div class="block-header block-header-default">
                                  <h3 class="block-title">{{ $banner->banner_id }}</h3>
                                </div>
                                <div class="block-content">
                                  
                                  <img src="{{ url($banner->banner_url)  }}" width="100%" height="300" class="img-responsive mb-4">
                                  
                                  <ul class="list-group push">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                      @if($banner->currency == 'NGN')
                                         Amount -  &#8358;{{ number_format($banner->amount,2) }}
                                      @else
                                         Amount - {{ number_format($banner->amount,2) }}
                                      @endif
                                       
                                     </li>

                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Creator - {{ $banner->user->name}}
                                     </li>

                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Clicks -  {{$banner->click_count == null ? '0' : $banner->click_count}}/{{$banner->clicks}}
                                     </li>
                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Impression -  {{$banner->impression}}
                                     </li>

                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                      Status -   {{ $banner->live_state == null ? 'Under Review' : $banner->live_state .' on '. \Carbon\Carbon::parse($banner->banner_end_date)->format('d F, Y') }}
                                     </li>

                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                     Date Created -   {{ \Carbon\Carbon::parse($banner->date)->format('d F, Y') }}
                                     </li>                                    
                                  </ul>

                                   @if($banner->status == true)
                                        <button class="btn btn-secondary btn-sm disabled"> {{ $banner->live_state}}</button>
                                    @else
                                        <a href="{{ url('admin/banner/activate/'.$banner->id) }}" class="btn btn-secondary btn-sm">Take Live</a>
                                    @endif 

                                </div>
                              </div>
                              <!-- END With Badges -->
                            </div>
                          
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
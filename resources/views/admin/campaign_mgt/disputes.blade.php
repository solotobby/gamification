@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Disputes</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Campaign</li>
            <li class="breadcrumb-item active" aria-current="page">Disputes</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <!-- Page Content -->
  <div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Campaign List - {{ $disputes->count() }}</h3>
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

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Campaign</th>
                    <th>Worker</th>
                    <th>Poster Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>When Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($disputes as $camp)
                <tr>
                    <th scope="row">{{ $i++ }}.</th>
                    <td class="fw-semibold"><a href="{{ url('campaign/info/'.$camp->campaign->id) }}" target="_blank"> {{$camp->campaign->post_title }}</a></td>
                    <td><a href="{{ url('user/'.$camp->user->id.'/info') }}"> {{ $camp->user->name }} </td>
                    <td><a href="{{ url('user/'.$camp->campaign->user->id.'/info') }}"> {{ $camp->campaign->user->name }} </td>
                    <td>  
                        @if($camp->campaign->currency == 'NGN')
                            &#8358;{{ number_format($camp->campaign->campaign_amount) }}
                        @else
                            ${{ $camp->campaign->campaign_amount }}
                        @endif 
                    </td>
                    <td>{{ $camp->status }} </td>
                    <td>{{ \Carbon\Carbon::parse($camp->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    <td><a href="{{ url('admin/campaign/disputes/'.$camp->id) }}" class="btn btn-alt-primary btn-sm">View</a></td>
                </tr>
                @endforeach
            </tbody>
          </table>
          <div class="d-flex">
            {!! $disputes->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>
    <!-- END Full Table -->

  </div>
@endsection
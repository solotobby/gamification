@extends('layouts.main.master')

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3"> Redeemed Points </h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Points</li>
            <li class="breadcrumb-item active" aria-current="page"> Redeemed Points</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Reddemed Points | Amount - &#8358;{{ number_format($redeemed->sum('amount')) }} | Point - {{ number_format($redeemed->sum('point')) }}</h3>
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

      <div class="table-responsive">
        <table class="table table-bordered table-striped table-vcenter">
          <thead>
              <tr>
                  
                  <th>Name</th>
                  <th>Point</th>
                  <th>Amount</th>
                  <th></th>
                  </tr>
          </thead>
          <tbody>
              @foreach ($redeemed as $with)
                  <tr>
                      <td>{{ $with->user->name }}</td>
                      <td>{{ $with->point }}</td>
                      <td>&#8358;{{ number_format(@$with->amount) }}</td>
                      <td>{{ \Carbon\Carbon::parse(@$with->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                  </tr>
                @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  



  @endsection
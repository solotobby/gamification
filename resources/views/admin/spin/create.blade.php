@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Spinner</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Spinner</li>
          </ol>
        </nav>
      </div>
    </div>
</div>



<div class="content">

    <!-- Elements -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Spin Params</h3>
        </div>
        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
    
            <form action="{{ route('admin.spin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                        Create spin params here
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Number of Spins</label>
                            <input type="number" class="form-control" id="example-text-input" name="total_spins_allowed" placeholder="100" value="{{ @$param->total_spins_allowed }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Total Payout Amount</label>
                            <input type="number" class="form-control" id="example-text-input" name="total_payouts_allowed" placeholder="50000" value="{{ @$param->total_payouts_allowed }}">
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>


        </div>

        <div class="block-content">
            <p>
                Spin Tracker
            </p>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-vcenter">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Total Spins</th>
                    <th>Total Payout</th>
                
                  </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($spinTracker as $s)
                    <tr>
                        
                        <td>{{$s->date}}</td>
                        <td>{{ $s->total_spins }}</td>
                        <td>{{ number_format($s->total_payout) }}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>


@endsection
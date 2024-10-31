@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Rate/Fees Management</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Rate/Fees Management</li>
          </ol>
        </nav>
      </div>
    </div>
</div>
 <!-- Page Content -->
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Rate/Fees Management</h3>
          </div>
          <div class="block-content">
                <form action="{{ url('conversions') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="text-muted">
                            Create Currency Variations here
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">From (Only active currencies)</label>
                                <select class="form-control" name="from" required>
                                    <option value="">Select Currency</option>
                                    @foreach (currencyList('NGN',true) as $currency)
                                        <option value="{{$currency->code}}">{{$currency->country}} - {{$currency->code}}</option>
                                    @endforeach
                             
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">To (Only active currencies)</label>
                                <select class="form-control" name="to" required>
                                    <option value="">Select Currency</option>
                                    @foreach (currencyList('NGN',true) as $currency)
                                        <option value="{{$currency->code}}">{{$currency->country}} - {{$currency->code}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">Rate </label>
                                <input type="text" class="form-control" id="example-text-input" name="rate" placeholder="19" required>
                            </div>
                            <div class="mb-4">
                                <button class="btn btn-primary" type="submit">Create Category</button>
                            </div>
                        </div>
                    </div>
                </form>
          </div>
    </div>
    
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Rate/Fees Management</h3>
          </div>
          <div class="block-content">
        <p>
            @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
        </p>
        <div class="table-responsive">
        <table class="table table-bordered table-striped table-vcenter">
            <thead>
            <tr>
                <th>#</th>
                <th>From</th>
                <th>To</th>
                <th>Rate</th>
                <th>Upgrade</th>
                <th>UploadFee</th>
                <th>PriotizeFee</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($rates as $s)
                <tr>
                    <td>{{ $i++ }}.</td>
                    <td>{{ $s->from }}</td>
                    <td>{{ $s->to }}</td>
                    <td>{{ $s->amount }}</td>
                    <td>{{ $s->upgrade_fee }}</td>
                    <td>{{ $s->allow_upload }}</td>
                    <td>{{ $s->priotize }}</td>
                    <td>{{ $s->status == 1 ? 'Active' : 'Inactive' }}</td>
                    {{-- <td>{{ $s->updated_at->diffForHumans() }}</td> --}}
                    <td>
                        {{-- <a href="{{ url('change/notification/status/'.$s->id) }}" class="btn btn-alt-primary btn-sm">Change Status</a> --}}
                        <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}" {{ $s->status == 1 ? '' : 'disabled' }}>Change</button>
                    </td>
                </tr>
                <div class="modal fade" id="modal-default-popout-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Fees/Rate Management</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pb-1">
                            <strong>From:</strong> {{  $s->from }}<br>
                            <strong>To:</strong> {{ $s->to }}
                            <hr>
                            <form action="{{ route('conversions.update', $s) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row push">
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label class="form-label" for="example-text-input">Rate</label>
                                            <input type="text" class="form-control" id="example-text-input" name="rate" value="{{ $s->amount }}">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label" for="example-text-input">Upgrade Fee</label>
                                            <input type="text" class="form-control" id="example-text-input" name="upgrade_fee" value="{{ $s->upgrade_fee }}">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label" for="example-text-input">Referral Commission</label>
                                            <input type="text" class="form-control" id="example-text-input" name="referral_commission" value="{{ $s->referral_commission }}">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label" for="example-text-input">File Upload fee when creating Campaign</label>
                                            <input type="text" class="form-control" id="example-text-input" name="allow_upload" value="{{ $s->allow_upload }}">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label" for="example-text-input">Priotize Fee</label>
                                            <input type="text" class="form-control" id="example-text-input" name="priotize" value="{{ $s->priotize }}">
                                        </div>
                                        <input type="hidden" name="id" value="{{ $s->id}}" required>
                                        <div class="mb-4">
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
        </div>
  </div>
</div>

@endsection
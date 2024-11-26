@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection

@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Currency Management</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Currency</li>
            </ol>
            </nav>
        </div>
        </div>
    </div>

    <div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Currency List</h3>
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
                        
                        <th>Code</th>
                        <th>Country</th>
                        <th>BaseRate(USD)</th>
                        <th>Upgrade</th>
                        <th>Ref. Com.</th>
                        <th>UploadFee</th>
                        <th>PriotizeFee</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($currencies as $s)
                        <tr>
                           
                            <td>{{ $s->code }}</td>
                            <td>{{ $s->country }}</td>
                            <td>{{ $s->base_rate }}</td>
                            <td>{{ $s->upgrade_fee }}</td>
                            <td>{{ $s->referral_commission }}</td>
                            <td>{{ $s->allow_upload }}</td>
                            <td>{{ $s->priotize }}</td>
                            <td>{{ $s->is_active == 1 ? 'Active' : 'Inactive' }}</td>
                            {{-- <td>{{ $s->updated_at->diffForHumans() }}</td> --}}
                            <td>
                                {{-- <a href="{{ url('change/notification/status/'.$s->id) }}" class="btn btn-alt-primary btn-sm">Change Status</a> --}}
                                <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">Change</button>
                            </td>
                        </tr>
                        <div class="modal fade" id="modal-default-popout-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Update Currency Parameter</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-1">
                                    <strong>Currency:</strong> {{$s->code}}<br>
                                    <strong>Country:</strong> {{ $s->country }}
                                    <hr>
                                    <form action="{{ url('update/currency') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="row push">
                                            <div class="col-lg-12">
                                                <div class="mb-4">
                                                    <label class="form-label" for="example-text-input">Base Rate (against USD)</label>
                                                    <input type="text" class="form-control" id="example-text-input" name="base_rate" value="{{ $s->base_rate }}">
                                                </div>
                                                 <div class="mb-4">
                                                    <label class="form-label" for="example-text-input">Upgrade Fee</label>
                                                    <input type="text" class="form-control" id="example-text-input" name="upgrade_fee" value="{{ $s->upgrade_fee }}">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label" for="example-text-input">Min Upgrade Amount Required (Required amount for upgrade)</label>
                                                    <input type="text" class="form-control" id="example-text-input" name="min_upgrade_amount" value="{{ $s->min_upgrade_amount }}">
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
</div>


@endsection



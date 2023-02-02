@extends('layouts.main.master')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Settings</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">View Settings</li>
          </ol>
        </nav>
      </div>
    </div>
</div>

 <!-- Page Content -->
 <div class="content">

    <!-- Elements -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Settings</h3>
        </div>
        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('store.settings') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-3">
                       
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Name</label>
                            <input type="text" class="form-control" id="example-text-input" name="name" placeholder="Payment GateWay" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Value</label>
                            <input type="text" class="form-control" id="example-text-input" name="value" placeholder="Paystack" required>
                        </div>
                        {{-- <input type="hidden" name="id" value=""> --}}
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Create</button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                       
                    </div>
                </div>
                
            </form>


            <div class="row push">
                <hr>
                <div class="table-responsive">
                    <form action="{{ route('store.settings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Value</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($settings as $setting)
                                <tr>
                                    <th scope="row">{{ $i++ }}.</th>
                                    <td>{{ $setting->name }}</td>
                                    <td>{{ $setting->status == '1' ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $setting->value }}</td>
                                    <td> <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $setting->id }}">Update</button></td>
                                </tr>

                                <div class="modal fade" id="modal-default-popout-{{ $setting->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">{{ $setting->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
            
                                        <div class="modal-body pb-1">
                                            <form action="{{ route('store.settings') }}" method="POST">
                                              @csrf
                                              <div class="mb-4">
                                                <label class="form-label" for="post-files">Value</small></label>
                                                    <input class="form-control" name="value" value="{{ $setting->value }}" required>
                                              </div>
                                              <input type="text" name="id" value="{{ $setting->id }}">
                                            </form>
                                            <br>
                                        </div>
                                        
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Update</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @endforeach 
                        </tbody>

                        
                    </table>
                    <button class="btn btn-primary" type="submit">Update</button>
                </form>
                </div>
            </div>


        </div>
    </div>
 </div>

@endsection
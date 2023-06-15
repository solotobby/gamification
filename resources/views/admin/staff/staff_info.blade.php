@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')
 <!-- Hero -->
 <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo17@2x.jpg')}}');">
    <div class="bg-black-25">
      <div class="content content-full">
        <div class="py-5 text-center">
          <a class="img-link" href="be_pages_generic_profile.html">
            <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{ asset('src/assets/media/avatars/avatar10.jpg')}}" alt="">
          </a>
          <h1 class="fw-bold my-2 text-white">{{ $info->staff->account_name }}</h1>
          <h2 class="h4 fw-bold text-white-75">
            {{ $info->staff->role}} <a class="text-primary-lighter" href="javascript:void(0)">@ {{$info->staff->staff_id}}</a>
          </h2>
          {{-- <button type="button" class="btn btn-primary m-1">
            <i class="fa fa-fw fa-user-plus opacity-50 me-1"></i> Add
          </button> --}}
          <button type="button" class="btn btn-secondary m-1">
            <i class="fa fa-fw fa-envelope opacity-50 me-1"></i>Edit
          </button>
          {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-block-vcenter">Block Design</button> --}}
        </div>
      </div>
    </div>
</div>
  <!-- END Hero -->

   <!-- Page Content -->
   <div class="content content-full content-boxed">
    <!-- Latest Posts -->
    <h2 class="content-heading">
        <i class="si si-note me-1"></i> Pay Slips
    </h2>
    @foreach ($info->staff->payslips as $payslip)
    <a class="block block-rounded block-link-shadow mb-3" href="javascript:void(0)">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
        <h4 class="fs-base text-primary mb-0">
            <i class="fa fa-file-pdf text-muted me-1"></i> {{$payslip->date}} | &#8358;{{ number_format($payslip->amount) }}
        </h4>
        <p class="fs-sm text-muted mb-0 ms-2 text-end">
           {{$payslip->payment_type}}
        </p>
        </div>
    </a>
    @endforeach
   
    {{-- <div class="text-end">
        <button type="button" class="btn btn-alt-primary">
        Check out more <i class="fa fa-arrow-right ms-1"></i>
        </button>
    </div> --}}
    <!-- END Latest Posts -->

    <div class="block block-rounded">
      <div class="block-content">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{url('edit/staff')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <!-- User Profile -->
          <h2 class="content-heading pt-0">
            <i class="fa fa-fw fa-user-circle text-muted me-1"></i> Staff Information
          </h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Edit Information
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-name">Salary</label>
                <input type="number" class="form-control" id="dm-profile-edit-name" name="basic_salary" value="{{ $info->staff->basic_salary}}">
              </div>
            </div>
          </div>
          <!-- END User Profile -->
          <input type="hidden" name="id" value="{{ $info->staff->id }}">

          <!-- Submit -->
          <div class="row push">
            <div class="col-lg-8 col-xl-5 offset-lg-4">
              <div class="mb-4">
                <button type="submit" class="btn btn-alt-primary">
                  <i class="fa fa-check-circle opacity-50 me-1"></i> Update Information
                </button>
              </div>
            </div>
          </div>
          <!-- END Submit -->
        </form>
      </div>
    </div>
    </div>
    <!-- END Page Content -->
   </div>

@endsection

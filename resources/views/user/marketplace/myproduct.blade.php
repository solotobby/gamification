@extends('layouts.main.master')
@section('content')

 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">My Products</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Product</li>
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
        <h3 class="block-title">Number of Product - {{ $lists->count() }}  </h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <p>
        </p>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>
                  #
                </th>
                <th>Name</th>
                <th>Impressions</th>
                <th>Price</th>
                <th>Commission</th>
                <th>Sales</th>
                {{-- <th>Status</th> --}}
              </tr>
            </thead>
            <tbody>
                <?php  $i = 1;?>
                @foreach ($lists as $list)
                <tr>
                    <td class="text-center">
                     {{ $i++ }}.
                    </td>
                    <td class="fw-semibold">
                      {{ $list->name }}
                    </td>
                    <td>{{$list->views}}</td>
                    <td>&#8358;{{number_format($list->amount)}}</td>
                    <td>&#8358;{{number_format($list->commission_payment)}}</td>
                    <td>{{number_format($list->sales()->count())}}</td>
                    
                  </tr>
                @endforeach
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- END Full Table -->

  </div>
@endsection
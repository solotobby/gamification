@extends('layouts.main.master')

@section('title', 'Winner List')

@section('content')


 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Transactions</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Transactions List</li>
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
        <h3 class="block-title">Transaction List
          {{-- Successful: &#8358;{{ number_format($lists->where('status', 'successful')->where('currency', 'NGN')->sum('amount')) }} | ${{ number_format($lists->where('status', 'successful')->where('currency', 'USD')->sum('amount'), 2) }} --}}
        </h3>
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
                <th>Reference</th>
                <th>Amount</th>
                {{-- <th>Currency</th> --}}
                <th>Status</th>
                <th>Description</th>
                <th>When</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($lists as $list)
                    @if($list->tx_type == 'Credit')
                      <tr style="color: forestgreen">
                    @else
                      <tr style="color: chocolate">
                    @endif
                    <td>
                      {{ $list->reference }}
                    </td>
                    <td>

                      
                      @if($list->currency == 'NGN')
                      &#8358;
                        {{ number_format($list->amount,2) }}
                      @elseif($list->currency == 'USD')
                      ${{ ($list->amount) }}
                      @else
                        {{ $list->currency }} {{ ($list->amount) }}
                      @endif

                    </td>
                  
                    <td>
                        {{ $list->status }}
                    </td>
                    <td>
                        {{ $list->description }}
                    </td>
                    <td>
                        {{ $list->created_at }}
                    </td>
                  </tr>
                @endforeach
              
            </tbody>
          </table>
          <div class="d-flex">
            {!! $lists->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>
    <!-- END Full Table -->

  </div>

@endsection
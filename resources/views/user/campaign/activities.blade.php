@extends('layouts.main.master')
@section('style')
<script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
@endsection
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $lists->post_title }} </h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Campaign</li>
            <li class="breadcrumb-item active" aria-current="page">View Activities</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- Page Content -->
  <div class="content">
    <ol class="list-group list-group-numbered mb-3">

      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Number of Worker</div>
        
        </div>
        <span class="badge bg-primary rounded-pill">{{ $lists->number_of_staff }}</span>
      </li>

      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Approved</div>
        </div>
        <span class="badge bg-primary rounded-pill">{{ $campaignStat['Approved'] ?? 0 }}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Pending</div>
         
        </div>
        <span class="badge bg-primary rounded-pill">{{ $campaignStat['Pending'] ?? 0 }}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Denied</div>
        
        </div>
        <span class="badge bg-primary rounded-pill">{{ $campaignStat['Denied'] ?? 0 }}</span>
      </li>
     

      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Campaign Amount</div>
        
        </div>
        <span class="badge bg-primary rounded-pill">{{ baseCurrency() }} {{ $amount }}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Amount Spent</div>
        
        </div>
        <span class="badge bg-primary rounded-pill">{{ baseCurrency() }} {{ $campaignStat['Approved'] ?? 0 * $amount }} / {{ baseCurrency() }} {{ number_format($amount * $lists->number_of_staff, 2) }}</span>
      </li>

      <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold">Campaign Status</div>
        
        </div>
        <?php 
        $c = $campaignStat['Pending'] ?? 0 + $campaignStat['Approved'] ?? 0;
        ?>
        <span class="badge bg-primary rounded-pill"> {{ $c >= $lists->number_of_staff ? 'Completed' : 'Not Completed' }} </span>
      </li>

    </ol>

    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title"> 

        </h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
              <tr>
                <th>Worker Name</th>
                <th>Campaign Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

                  {{-- @foreach ($lists->completed()->orderBy('created_at', 'DESC')->get() as $list) --}}
                  @foreach ($responses as $list)    
                  <tr>
                          <td>
                              {{ @$list->user->name }}
                              </td>
                          <td>
                          {{ @$list->campaign->post_title }}
                          </td>
                          <td>
                            {{-- @if($list->campaign->currency == 'NGN')
                              &#8358;{{ $list->amount }}
                              @else
                              ${{ $list->amount }}
                              @endif --}}

                              {{ baseCurrency() }} {{ currencyConverter($list->currency, baseCurrency(), $list->amount)  }}
                          </td>
                          <td>{{ $list->status }}</td>
                          <td>
                              @if($list->status == 'Pending')
                              
                                  @if($lists->completed()->where('status', 'Approved')->count() >= @$list->campaign->number_of_staff)
                                      <button type="button" class="btn btn-alt-warning btn-sm disabled">Worker Completed</button>
                                  @else
                                  
                                  <a  href="{{ url('campaign/activities/'.$list->id.'/response') }}" class="btn btn-alt-primary btn-sm" > View Response </a>
                                      {{-- <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $list->id }}">View</button> --}}
                                  @endif

                                @else
                                  <a href="#" class="btn btn-alt-info btn-sm disabled">Completed</a>
                              @endif
                          </td>
                      </tr>
                  @endforeach
            </tbody>
          </table>
          <div class="d-flex">
            {!! $responses->links('pagination::bootstrap-4') !!}
          </div>
        </form>
        </div>
      </div>
    </div>
    <!-- END Full Table -->
  </div>
@endsection


@section('script')
{{-- <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script> --}}
@endsection
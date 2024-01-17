@extends('layouts.main.master')
@section('content')
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
              <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Knowledge Base</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    
                    <li class="breadcrumb-item active" aria-current="page">Knowledge Base</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
          <!-- END Hero -->
  
          <!-- Page Content -->
          <div class="content">
            <!-- Frequently Asked Questions -->
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                <h3 class="block-title">Freebyz Knowledge Base</h3>
              </div>
              <div class="block-content">
                @foreach ($lists as $category=>$list)
                    <h2 class="content-heading"> {{ $category }}</h2>
                    <div class="row items-push">
                        @foreach ($list as $l)
                            <div class="col-lg-12">
                                <div id="faq{{$l->id}}" role="tablist" aria-multiselectable="true">
                                
                                    <div class="block block-rounded mb-1">
                                        <div class="block-header block-header-default" role="tab" id="faq2_h{{$l->id}}">
                                        <a class="fw-semibold" data-bs-toggle="collapse" data-bs-parent="#faq{{$l->id}}" href="#faq2_q{{$l->id}}" aria-expanded="true" aria-controls="faq2_q1">{{ $l->question }}</a>
                                        </div>
                                        <div id="faq2_q{{$l->id}}" class="collapse" role="tabpanel" aria-labelledby="faq2_h{{$l->id}}" data-bs-parent="#faq{{ $l->id}}">
                                        <div class="block-content">
                                            {!! $l->answer !!}
                                        </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                @endforeach
               
               
  
              </div>
            </div>
            <!-- END Frequently Asked Questions -->
          </div>
          <!-- END Page Content -->
@endsection
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


        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
              <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Knowledge <Base></Base></h1>
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
                <h3 class="block-title">Create Knowledge Base Content</h3>
              </div>
              <div class="block-content">
                <!-- Introduction -->
                <h2 class="content-heading"><strong>1.</strong> Introduction</h2>
                <div class="row items-push">
                  <div class="col-lg-4">
                    <p class="text-muted">
                     Create content for our Knowledge base
                    </p>
                  </div>
                  <div class="col-lg-8">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                  @endif
                    <div id="faq1" role="tablist" aria-multiselectable="true">
                      <div class="block block-rounded mb-1">
                       
                        <form action="{{ url('knowledgebase') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="post-files">Category</small></label>
                                   <select name="category" class="form-control" required>
                                    <option value="Introduction">Introduction</option>
                                    <option value="Account">Account</option>
                                    <option value="Payment">Payment</option>
                                    <option value="Campaigns and Jobs">Campaigns and Jobs</option>
                                    <option value="Wallets">Wallets</option>
                                   </select>
                              </div>
                              <div class="mb-4">
                                <label class="form-label" for="post-files">Question</small></label>
                                   <input type="text" class="form-control" name="question" value="{{ old('question') }}" required>
                              </div>
                            <div class="mb-4">
                              <label class="form-label" for="post-files">Enter Answer</small></label>
                                  <textarea class="form-control" name="answer" id="js-ckeditor5-classic" required> {{ old('answer') }}</textarea>
                            </div>
                            <input type="hidden" name="id" value="1">
                            <div class="mb-4">
                              <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Submit</button>
                              
                            </div>
                          </form>

                    </div>
                  </div>
                </div>
                <!-- END Introduction -->
  
                <!-- Features -->
                <h2 class="content-heading">Content</h2>
                <div class="row items-push">
                  
                  <div class="col-lg-12">
                   
                   
          
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Category</th>
                              <th>Question</th>
                              <th>Answer</th>
                              <th>Status</th>
                              <th>Action</th>
                              <th>When Created</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php $i = 1; ?>
                          @foreach ($knowledgebase as $base)
                              <tr>
                                  <th scope="row">{{ $i++ }}.</th>
                                  <td>{{ $base->category }}</td>
                                  <td>{{ $base->question }}</td>
                                  <td>{{ $base->answer }}</td>
                                  <td>{{ $base->status == false ? 'Offline' : 'Live' }} </td>
                                  <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                                  <td>{{ \Carbon\Carbon::parse($base->created_at)->format('d/m/Y @ h:i:sa') }}</td>
                              </tr>
                          @endforeach
                        
                      </tbody>
                    </table>
                  </div>


                  </div>
                </div>
                <!-- END Features -->
              </div>
            </div>
            <!-- END Frequently Asked Questions -->
          </div>
          <!-- END Page Content -->


@endsection


@section('script')
<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>
@endsection
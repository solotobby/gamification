@extends('layouts.main.master')

@section('style')
{{-- <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js" referrerpolicy="origind"></script> --}}
<script src="https://cdn.tiny.cloud/1/no-api/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
@endsection

@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
            SignUp Business for Freebyz Promotion
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero Section -->


  <div class="content content-boxed">

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif


    <div class="block block-rounded">
        
        <div class="block-content block-content-full">
          <h2 class="content-heading">Business Information</h2>
          <div class="row items-push">
            <div class="col-lg-3">
              <p class="text-muted">
                    Please provide detailed information of your business
              </p>
            </div>
            
            <div class="col-lg-9">
            @if(!$business)
                <form action="{{ route('store.business') }}" method="POST">
                        @csrf
                    <div class="alert alert-success">
                        Setup your business on Freebyz Business Promotion. Businesses will be randomly selected to display at the topmost part of our page. 
                        You will also have a unique business link where you can show case your product for free. 
                    </div>


                <div class="mb-2">
                    <label class="form-label" for="post-title">Official Business Name</label>
                    <input type="text" class="form-control" id="post-title" name="business_name" value="{{ old('business_name') }}" placeholder="Glowzconcept Design" required>  
                </div>

                <div class="mb-2">
                    <label class="form-label" for="post-title">Official Business Phone Number</label>
                    <input type="text" class="form-control" id="post-title" name="business_phone" value="{{ old('business_name') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label" for="post-title">Business Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value=""> Select One</option>
                        @foreach ($categories as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                        @endforeach
                        
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="post-files">Description of your business<small>(This information will help us channel your business to the right audience)</small></label>
                    <textarea class="form-control" name="description" id="js-ckeditor5-classic" required> {{ old('description') }}</textarea>
                </div>

                    <div class="mb-2">
                        <label class="form-label" for="post-title">Facebook Link</label>
                        <input type="url" class="form-control" id="post-title" name="facebook_link" value="{{ old('facebook_link') }}" placeholder="">
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="post-title">Instagram Link</label>
                        <input type="url" class="form-control" id="post-title" name="instagram_link" value="{{ old('instagram_link') }}" placeholder="">
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="post-title">X Link</label>
                        <input type="url" class="form-control" id="post-title" name="x_link" value="{{ old('x_link') }}" placeholder="">
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="post-title">Tiktok Link <small>(Can also be your tiktok shop link)</small></label>
                        <input type="url" class="form-control" id="post-title" name="tiktok_link" value="{{ old('tiktok_link') }}" placeholder="">
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="post-title">Pinterest Link </label>
                        <input type="url" class="form-control" id="post-title" name="pinterest_link" value="{{ old('pinterest_link') }}" placeholder="">
                    </div>

                    </div>

                    <div class="block-content block-content-full pt-0">
                        <div class="row mb-2">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9">
                            <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check opacity-50 me-1"></i> Submit Business
                            </button>
                        </div>
                        </div>
                    </div>
                </form>
            @else

               

                <div class="block h-100 mb-0">
                    <div class="block-header block-header-default">
                      <h3 class="block-title">Your Business Information</h3>
                    </div>
                    <div class="block-content">
                      <ul class="nav-items push">
                        <li>
                          <a class="d-flex py-2" href="javascript:void(0)">
                            <div class="flex-grow-1">
                              <div class="fw-semibold">Business Name</div>
                              <div class="fs-sm text-muted">{{$business->business_name}}</div>
                            </div>
                          </a>
                        </li>

                        <li>
                            <a class="d-flex py-2" href="javascript:void(0)">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Business Phone Number</div>
                                <div class="fs-sm text-muted">{{$business->business_phone}}</div>
                              </div>
                            </a>
                        </li>

                        <li>
                            <a class="d-flex py-2" href="javascript:void(0)">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Description</div>
                                <div class="fs-sm text-muted">{!! $business->description !!}</div>
                              </div>
                            </a>
                        </li>

                        <li>
                            <a class="d-flex py-2" target="_blank" href="{{ url('m/'.$business->business_link) }}">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Freebyz Page</div>
                                <div class="fs-sm text-muted">{{ url('m/'.$business->business_link) }}</div>
                              </div>
                            </a>
                        </li>

                        <li>
                            <a class="d-flex py-2" target="_blank" href="{{ $business->facebook_link == null ? '#' :  $business->facebook_link }}">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Facebook Page</div>
                                <div class="fs-sm text-muted">{{ $business->facebook_link == null ? 'NOT SET' :  $business->facebook_link}}</div>
                              </div>
                            </a>
                        </li>

                        <li>
                            <a class="d-flex py-2" target="_blank" href="{{ $business->x_link== null ? '#' : $business->x_link}}">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">X Page</div>
                                <div class="fs-sm text-muted">{{ $business->x_link == null ? 'NOT SET' : $business->x_link }}</div>
                              </div>
                            </a>
                        </li>

                        <li>
                            <a class="d-flex py-2" target="_blank" href="{{ $business->instagram_link == null ? '#' : $business->instagram_link }}">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Instagram Page</div>
                                <div class="fs-sm text-muted">{{ $business->instagram_link == null ? 'NOT SET' : $business->instagram_link }}</div>
                              </div>
                            </a>
                        </li>
                    
                        <li>
                            <a class="d-flex py-2" target="_blank" href="{{ $business->tiktok_link  == null ? '#' : $business->tiktok_link}}">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Tiktok Page</div>
                                <div class="fs-sm text-muted">{{ $business->tiktok_link == null ? 'NOT SET' : $business->tiktok_link }}</div>
                              </div>
                            </a>
                        </li>
                        <li>
                            <a class="d-flex py-2" target="_blank" href="{{ $business->pinterest_link == null ? '#' : $business->pinterest_link }}">
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Pinterest Page</div>
                                <div class="fs-sm text-muted">{{ $business->pinterest_link == null ? 'NOT SET' :  $business->pinterest_link}}</div>
                              </div>
                            </a>
                        </li>

                        <li>
                            
                              <div class="flex-grow-1">
                                <div class="fw-semibold">Page Visit</div>
                                <div class="fs-sm text-muted">{{ $business->visits}}</div>
                              </div>
                            
                        </li>

                        <li>
                            <div class="flex-grow-1">
                              <div class="fw-semibold">Page Status</div>
                              <div class="fs-sm text-muted">{{ $business->status}}</div>
                            </div>
                      </li>
                       
                      </ul>
                    </div>

                  </div>
            @endif
           
            </div>
            
        </div>


        @if($business->status == 'ACTIVE')
        <div class="block-content block-content-full">
            <h2 class="content-heading">Add Product</h2>
            <div class="row items-push">
                <form action="{{ route('create.product') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="text-muted">
                               Enter Product Information
                            </p>
                        </div>
                    
                        <div class="col-lg-8">
                            {{-- <div class="mb-4">
                                <label class="form-label" for="example-select">Select Category</label>
                                <select class="form-select" id="example-select" name="category_id" required>
                                    <option value="">Select One</option>
                                    @foreach ($category as $cate)
                                        <option value="{{ $cate->id }}">{{$cate->name}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
        
                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">Product Name</label>
                                <input type="text" class="form-control" id="example-text-input" name="name" placeholder="Natural hair cleanser(250ml)">
                            </div>
        
                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">Price</label>
                                <input type="number" class="form-control" id="example-text-input" name="price" placeholder="2500">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">Product Description</label>
                                <textarea class="form-control" name="description" id="js-ckeditor5-classic" required> {{ old('description') }}</textarea>
                            </div>

                            <div class="mb-4">
                              <label class="form-label" for="example-text-input">Product Image</label>
                              <input type="file" class="form-control" id="example-text-input" name="img">
                          </div>
                          <input type="hidden" name="business_id" value="{{ $business->id}}">
        
                            <div class="mb-4">
                                <button class="btn btn-primary" type="submit">Create Product</button>
                            </div>
                        
                        </div>
                 
                    </div>
                </form>
                
            </div>
        </div>


        @endif

    
  </div>

@endsection

@section('script')

 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
 {{-- <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script> --}}
 {{-- <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script> --}}
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <!-- Page JS Helpers (CKEditor 5 plugins) -->
 <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script>


 @endsection
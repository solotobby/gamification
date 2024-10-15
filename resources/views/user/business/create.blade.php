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
   <!-- Hero -->
   <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
   {{-- <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo17@2x.jpg')}});"> --}}
    <div class="bg-black-25">
      <div class="content content-full">
        <div class="py-5 text-center">
          {{-- <a class="img-link" href="be_pages_generic_profile.html">
            <img class="img-avatar img-avatar96 img-avatar-thumb" src="assets/media/avatars/avatar10.jpg" alt="">
          </a> --}}
          <h1 class="fw-bold my-2 text-white"> {{ isset($business) ? $business->business_name : 'SignUp your Business for Freebyz Promotion'; }}</h1>
          @if($business)
          <h2 class="h4 fw-bold text-white-75">
            
            Business Page <a class="text-primary-lighter" href="{{url('m/'.$business->business_link) }}" target="_blank">{{url('m/'.$business->business_link) }}</a>
          </h2>
          @else


          @endif
          @if(@$business->status == 'ACTIVE')
            <a href="#addProduct" id="" class="btn btn-primary m-1">
                <i class="fa fa-fw fa-plus opacity-50 me-1"></i> Add Product
            </a>
            {{-- <button type="button" class="btn btn-secondary m-1">
                <i class="fa fa-fw fa-envelope opacity-50 me-1"></i> Message
            </button> --}}
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <div class="content content-full content-boxed">

    @if($business)
        @if(@$business->status == 'PENDING')
            <div class="alert alert-info">
                Your business is created and awaiting confirmation. This usually takes 2 business days. Thank you for your patience!
            </div>

            @else

            

            @if(@$business->status == 'ACTIVE')
                <h2 class="content-heading">
                    <i class="si si-briefcase me-1"></i> Product Management
                </h2>
                <!-- Quick Overview + Actions -->
          <div class="row items-push">
            <div class="col-6 col-lg-4">
              <a class="block block-rounded block-link-shadow text-center h-100 mb-0" href="be_pages_ecom_orders.html">
                <div class="block-content py-5">
                  <div class="fs-3 fw-semibold mb-1">{{ number_format($business->products->count())}}</div>
                  <p class="fw-semibold fs-sm text-muted text-uppercase mb-0">
                    Total Products
                  </p>
                </div>
              </a>
            </div>
            <div class="col-6 col-lg-4">
              <a class="block block-rounded block-link-shadow text-center h-100 mb-0" href="javascript:void(0)">
                <div class="block-content py-5">
                  <div class="fs-3 fw-semibold mb-1">{{ number_format($business->products->sum('visits'))}}</div>
                  <p class="fw-semibold fs-sm text-muted text-uppercase mb-0">
                    Total Views
                  </p>
                </div>
              </a>
            </div>
            <div class="col-lg-4">
              <a class="block block-rounded block-link-shadow text-center h-100 mb-0" href="be_pages_ecom_product_edit.html">
                <div class="block-content py-5">
                  <div class="fs-3 fw-semibold mb-1">
                    {{ number_format($business->visits)}}
                  </div>
                  <p class="fw-semibold fs-sm  text-uppercase mb-0">
                   Total Page Visit
                  </p>
                </div>
              </a>
            </div>
          </div>
          <!-- END Quick Overview + Actions -->

          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Product List</h3>
            </div>
            <div class="block-content">
              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-12">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-vcenter">
                          <thead>
                            <tr>
                              <th >ID</th>
                              <th>Added</th>
                              <th >Product</th>
                              <th>Status</th>
                              <th>Views</th>
                              <th >Value</th>
                              <th class="text-center">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($business->products()->orderBy('created_at', 'desc')->get() as $product)
                            <tr>
                                <td>
                                  <a class="fw-semibold" href="{{  url('product/edit/'.$product->unique) }}">
                                    <strong>{{$product->pid}}</strong>
                                  </a>
                                </td>
                                <td > {{ \Carbon\Carbon::parse($product->created_at)->format('d F, Y') }}</td>
                                <td>
                                  <a class="fw-semibold" href="{{  url('product/edit/'.$product->unique) }}">{{$product->name}}</a>
                                </td>
                                <td>
                                  <span class="badge bg-success">{{$product->is_live == true ? 'Live' : 'Offline'}}</span>
                                </td>
                                <td>
                                    {{$product->visits}}
                                </td>
                                <td >
                                  <strong>&#8358;{{number_format($product->price,2)}}</strong>
                                </td>
                                <td >
                                  <a class="btn btn-sm btn-alt-secondary" href="{{  url('product/edit/'.$product->unique) }}" >
                                    <i class="fa fa-fw fa-edit"></i>
                                  </a>
                                  <a class="btn btn-sm btn-alt-secondary" href="{{  url('product/delete/'.$product->unique) }}"  onclick="return confirm('Are you sure you want to delete this product?');">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                            
                            
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
            </div>
          </div>



                <div class="block block-rounded justify-content-center">
                    <div class="block-content block-content-full" id="addProduct">
                        <h2 class="content-heading">Add Product</h2>
                        <div class="row items-push">
                            <form action="{{ route('create.product') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row push">
                                
                                
                                    <div class="col-md-10 col-lg-8">
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
                </div>

        


            @endif
    

        @endif

        <h2 class="content-heading">
            <i class="si si-note me-1"></i> Social Media Handle
        </h2>

        <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ url('m/'.$business->business_link) }}">
            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
            <h4 class="fs-base text-primary mb-0">
                <i class="fa fa-newspaper text-muted me-1"></i> Freebyz Page
            </h4>
            <p class="fs-sm text-muted mb-0 ms-2 text-end">
                {{ $business->status}}
            </p>
            </div>
        </a>

      <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->facebook_link == null ? '#' :  $business->facebook_link }}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fab fa-facebook text-muted me-1"></i> Facebook
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->facebook_link == null ? 'Not set' : $business->facebook_link }}
          </p>
        </div>
      </a>
      <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->instagram_link == null ? '#' :  $business->instagram_link }}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fab fa-instagram-square text-muted me-1"></i> Instagram
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->instagram_link == null ? 'Not set' : $business->instagram_link }}
          </p>
        </div>
      </a>

      <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->x_link == null ? '#' :  $business->x_link }}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fab fa-x-twitter text-muted me-1"></i> X 
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->x_link == null ? 'Not set' : $business->x_link }}
          </p>
        </div>
      </a>

      <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->tiktok_link == null ? '#' :  $business->tiktok_link }}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fab fa-tiktok text-muted me-1"></i> Tiktok
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->tiktok_link == null ? 'Not set' : $business->tiktok_link }}
          </p>
        </div>
      </a>

      <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->pinterest_link == null ? '#' :  $business->pinterest_link }}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fab fa-pinterest text-muted me-1"></i> Pinterest
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->pinterest_link == null ? 'Not set' : $business->pinterest_link }}
          </p>
        </div>
      </a>

      <a class="block block-rounded block-link-shadow mb-3" href="javascript:void(0)">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fa fa-cog text-muted me-1"></i>Page Status
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->status}}
          </p>
        </div>
      </a>

      <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="javascript:void(0)}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <h4 class="fs-base text-primary mb-0">
            <i class="fa fa-newspaper text-muted me-1"></i> Page Visit
          </h4>
          <p class="fs-sm text-muted mb-0 ms-2 text-end">
            {{ $business->visits}}
          </p>
        </div>
      </a>

      @else

       <!-- Info -->
       <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Setup Business</h3>
        </div>
        <div class="block-content">
          <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

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
                <input type="text" class="form-control" id="post-title" name="business_phone" value="{{ old('business_phone') }}" placeholder="0808277***" required>
            </div>

            <div class="mb-2">
                <label class="form-label" for="post-title">Unique Business username</label>
                <input type="text" class="form-control" id="post-title" name="business_link" value="{{ old('business_link') }}" placeholder="sammy" required>
                <small>if your username is sammy, your store name will be https://freebyz.com/m/sammy</small>
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
                <div class="mt-2 mb-4">
                <button type="submit" class="btn btn-alt-primary">
                    <i class="fa fa-check opacity-50 me-1"></i> Submit Business
                </button>
                </div>

                </div>
            </form>
              {{-- <form action="be_pages_ecom_product_edit.html" method="POST" onsubmit="return false;">
                <div class="mb-4">
                  <label class="form-label" for="dm-ecom-product-id">PID</label>
                  <input type="text" class="form-control" id="dm-ecom-product-id" name="dm-ecom-product-id" value="1256" readonly>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="dm-ecom-product-name">Name</label>
                  <input type="text" class="form-control" id="dm-ecom-product-name" name="dm-ecom-product-name" value="Bloodborne">
                </div>
                <div class="mb-4">
                  <!-- CKEditor (js-ckeditor-inline + js-ckeditor ids are initialized in Helpers.jsCkeditor()) -->
                  <!-- For more info and examples you can check out http://ckeditor.com -->
                  <label class="form-label">Description</label>
                  <textarea id="js-ckeditor" name="dm-ecom-product-description"></textarea>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="dm-ecom-product-description-short">Short Description</label>
                  <textarea class="form-control" id="dm-ecom-product-description-short" name="dm-ecom-product-description-short" rows="4"></textarea>
                </div>
                <div class="mb-4">
                  <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                  <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                  <label class="form-label" for="dm-ecom-product-category">Category</label>
                  <select class="js-select2 form-select" id="dm-ecom-product-category" name="dm-ecom-product-category" style="width: 100%;" data-placeholder="Choose one..">
                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                    <option value="1">Cables</option>
                    <option value="2" selected>Video Games</option>
                    <option value="3">Tablets</option>
                    <option value="4">Laptops</option>
                    <option value="5">PC</option>
                    <option value="6">Home Cinema</option>
                    <option value="7">Sound</option>
                    <option value="8">Office</option>
                    <option value="9">Adapters</option>
                  </select>
                </div>
                <div class="row mb-4">
                  <div class="col-md-6">
                    <label class="form-label" for="dm-ecom-product-price">Price in USD ($)</label>
                    <input type="text" class="form-control" id="dm-ecom-product-price" name="dm-ecom-product-price" value="59,00">
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col-md-6">
                    <label class="form-label" for="dm-ecom-product-stock">Stock</label>
                    <input type="text" class="form-control" id="dm-ecom-product-stock" name="dm-ecom-product-stock" value="29">
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label d-block">Condition</label>
                  <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="dm-ecom-product-condition-new" name="dm-ecom-product-condition" checked>
                    <label class="form-check-label" for="dm-ecom-product-condition-new">New</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="dm-ecom-product-condition-old" name="dm-ecom-product-condition">
                    <label class="form-check-label" for="dm-ecom-product-condition-old">Old</label>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label">Published?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" value="" id="dm-ecom-product-published" name="dm-ecom-product-published">
                    <label class="form-check-label" for="dm-ecom-product-published"></label>
                  </div>
                </div>
                <div class="mb-4">
                  <button type="submit" class="btn btn-alt-primary">Update</button>
                </div>
              </form> --}}
            </div>
          </div>
        </div>
      </div>
      <!-- END Info -->





      @endif


  </div>



  <div class="content content-boxed">

    <div class="block block-rounded">
        
        


       
    
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
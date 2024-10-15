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
           <h1 class="fw-bold my-2 text-white"> {{ isset($product->business) ? $product->business->business_name : 'SignUp your Business for Freebyz Promotion'; }}</h1>
         
           <h2 class="h4 fw-bold text-white-75">
             
             Business Page <a class="text-primary-lighter" href="{{url('m/'.$product->business->business_link) }}" target="_blank">{{url('m/'.$product->business->business_link) }}</a>
           </h2>
          
           
         </div>
       </div>
     </div>
</div>

   <!-- END Hero -->
   <div class="content">
   <div class="block block-rounded">
   <div class="block block-rounded justify-content-center">

    <div class="block-content block-content-full" id="addProduct">
        <h2 class="content-heading">Edit Product: {{ $product->name }}</h2>
        <div class="row items-push">
            <form action="{{ route('edit.business.product') }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" id="example-text-input" name="name" value="{{ $product->name }}" placeholder="Natural hair cleanser(250ml)">
                        </div>
    
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Price</label>
                            <input type="number" class="form-control" id="example-text-input" name="price" value="{{ $product->price }}" placeholder="2500">
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Product Description</label>
                            <textarea class="form-control" name="description" id="js-ckeditor5-classic" required> {{ $product->description }}</textarea>
                        </div>

                        {{-- <div class="mb-4">
                        <label class="form-label" for="example-text-input">Product Image</label>
                        <input type="file" class="form-control" id="example-text-input" name="img">
                    </div> --}}
                    <input type="hidden" name="product_id" value="{{ $product->unique}}">
    
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Update Product</button>
                        </div>
                    
                    </div>

                    {{-- <a href=" {{ url('user/business') }}" class="btn btn-secondary">Back Product List</a> --}}
            
                </div>
            </form>
            
        </div>
    </div>
    </div>
   </div>
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

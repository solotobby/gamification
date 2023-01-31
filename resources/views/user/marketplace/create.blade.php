@extends('layouts.main.master')

@section('content')

<div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create Product</h3>
      </div>
    <div class="block-content">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

        <form class="js-validation-reminder" action="{{ route('store.marketplace.product') }}" method="POST" enctype="multipart/form-data">
            @csrf
             <!-- Text -->
             {{-- <h2 class="content-heading pt-0">Text</h2> --}}
             <div class="row">
               <div class="col-lg-4">
                 <p class="text-muted">
                   Enter the information accordingly
                 </p>
               </div>
               <div class="col-lg-8 col-xl-5">
                 <div class="mb-4">
                    <label class="form-label" for="formFileMultiple" class="form-label">Product Name</label>
                   <div class="input-group">
                     <span class="input-group-text">
                       Name
                     </span>
                     <input type="text" class="form-control" required id="example-group1-input1" name="name" value="{{ old('name') }}">
                   </div>
                 </div>
                 
                 <div class="mb-4">
                    <label class="form-label" for="formFileMultiple" class="form-label">Amount</label>
                   <div class="input-group">
                     <span class="input-group-text">
                        &#8358;
                     </span>
                     <input type="text" class="form-control text-center" required id="input-amount" name="amount" placeholder="00" value="{{ old('amount') }}">
                     <span class="input-group-text">.00</span>
                   </div>
                 </div>

                <div class="mb-4">
                    <label class="form-label" for="formFileMultiplesss" class="form-label">Commission</label>
                    <div class="input-group">
                      <select class="form-control" name="commission" id="commission" required>
                        <option value="">Select Affiliate Commission</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                        <option value="30">30%</option>
                        <option value="40">40%</option>
                      </select>
                    </div>
                </div>
                  
                 
                  <div class="mb-4">
                    <label class="form-label" for="formFileMultiple" class="form-label">Upload Banner</label>
                    <input class="form-control" type="file" name="banner" id="example-file-input-multiple" required>
                  </div>
                  <div class="mb-4">
                    <label class="form-label" for="formFileMultiples" class="form-label">Enter Product Url e.g www.facebook.com/img.png</label>
                    {{-- <input class="form-control" type="file" name="product" id="example-file-input-multiple" required> --}}
                    <input type="url" name="product" class="form-control" required>
                  </div>
                  <div class="mb-4">
                    <label class="form-label" for="formFileMultiples" class="form-label">Desctiption of product</label>
                    {{-- <input class="form-control" type="file" name="product" id="example-file-input-multiple" required> --}}
                    {{-- <input type="url" name="product" class="form-control" required> --}}
                    <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
                  </div>
                  <input type="hidden" name="total_payment" value="" id="demos">
                  <input type="hidden" name="commission_payment" value="" id="demos_">
                  <h5>Amount Payable: &#8358;<span id="demo"></span></h5>
               </div>
             </div>

             <div class="block-content block-content-full pt-0">
                <div class="row mb-4">
                  <div class="col-lg-6 offset-lg-5">
                    <button type="submit" class="btn btn-alt-primary">
                      <i class="fa fa-plus opacity-50 me-1"></i> Post Product
                    </button>
                  </div>
                </div>
              </div>
            

        </form>
    </div>

</div>

@endsection
@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 
 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>

 <script>
    $(document).ready(function(){
        
    $('#commission').change(function(){

        

            var input_amount = document.getElementById("input-amount").value;
            var percent = document.getElementById("commission").value;

            var percentageValue = (percent / 100 ) * input_amount;

            var new_total = Number(percentageValue) + Number(input_amount);
            

            document.getElementById("demo").innerHTML = new_total;
            document.getElementById("demos").value = new_total;
            document.getElementById("demos_").value = percentageValue;

            
            // var z = document.getElementById("amount_per_campaign").value;
            // var x = Number(y) * Number(z);
            // // document.getElementById("demo").innerHTML = x;

            // var percentToGet = 50;
            // var percent = (percentToGet / 100) * x;

            // document.getElementById("demo").innerHTML = x + percent;
            // alert(x);
        });

    });

 </script>
@endsection
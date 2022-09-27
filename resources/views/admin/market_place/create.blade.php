@extends('layouts.main.master')

@section('content')

<div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Create Product</h3>
      </div>
    <div class="block-content">

        <form class="js-validation-reminder" action="{{ route('store.marketplace') }}" method="POST" enctype="multipart/form-data">
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
                   <div class="input-group">
                     <span class="input-group-text">
                       Name
                     </span>
                     <input type="text" class="form-control" required id="example-group1-input1" name="name">
                   </div>
                 </div>
                 
                 <div class="mb-4">
                   <div class="input-group">
                     <span class="input-group-text">
                        &#8358;
                     </span>
                     <input type="text" class="form-control text-center" required id="input-amount" name="amount" placeholder="00">
                     <span class="input-group-text">.00</span>
                   </div>
                 </div>

                 <div class="mb-4">
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
                    
                    <div class="input-group">
                      <select class="form-control" name="type" id="type_id" required>
                        <option value="">Select Type</option>
                        
                        @foreach ($product_type as $type)
                            <option value="{{ $type->id }}">{{$type->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="mb-4">
                    <hr>
                    {{-- <label class="form-label" for="formFileMultiple" class="form-label">File Input (Multiple)</label> --}}
                    <input class="form-control" type="file" name="images" id="example-file-input-multiple" multiple>
                  </div>
                 
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
@extends('layouts.main.master')


@section('content')
<div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center py-5">
        <h1 class="h2 text-white mb-2">
            Build Forms for  {{ $survey->title }} Survey
        </h1>
      </div>
    </div>
</div>


<!-- END Hero Section -->
<div class="content content-boxed">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
          <h2 class="content-heading">Start Building your Survey Form</h2>
          <form method="post" action="{{ url('build/form') }}" enctype="multipart/form-data" oninput="price.value=parseFloat(rate.value)*parseFloat(qty.value)">
            @csrf
      
          <div class="row items-push">


            <div class="col-lg-3">
                Start Building Your Form
            </div>
           
              <div class="col-lg-9" >
                <div id="dynamic_field">
                    <fieldset class="border p-2 mb-2">
                     
                    <div class="mb-2">
                        <label class="form-label" for="post-title">Form Name</label>
                        <input type="text" class="form-control" id="post-title" name="name[]" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="post-title">Form Type</label>
                        <select class="selectpicker form-control type" id="type" required data-show-subtext="true" data-live-search="true" onchange="getModel(this.value);" name="type[]">
                            <option value="" disabled selected>Choose Form Type</option>
                            <option data-subtext="(INR 450/-)" value="TEXT">Text</option>
                            <option data-subtext="(INR 450/-)" value="EMAIL">Email</option>
                            <option data-subtext="(INR 450/-)" value="TEXTAREA">Textarea</option>
                            <option data-subtext="(INR 450/-)" value="CHECKBOX">Checkbox</option>
                            <option data-subtext="(INR 450/-)" value="RADIO">Radio</option>
                            <option data-subtext="(INR 450/-)" value="SELECT">Select</option>
                          </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="post-title">Make Compulsory</label>
                        <input type="checkbox" name="required[]" value="1">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="post-title">Options</label>
                        <input type="text" name="choices[]" class="form-control choices" id="choices">
                    </div>
                    </fieldset>
                    
                </div>

              

                <div class="mt-2">
                    <button type="button" name="add" id="add" class="btn btn-success btn-sm">Add More</button>
                </div>

              </div>

              <input type="hidden" name="survey_id" value="{{ $survey->id }}">
              <input type="hidden" name="survey_code" value="{{ $survey->survey_code }}">

        <div class="col-lg-12">

               <!-- Submit Form -->
                <div class="block-content block-content-full pt-0">
                    <hr>
                    <div class="row mb-2">
                        
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-plus opacity-50 me-1"></i> Submit & Preview Form
                        </button>
                    </div>
                    </div>
                </div>
                <!-- END Submit Form -->
        </div>
      </div>
    </form>
    </div>
</div>







          </div>

          </form>
        </div>
    </div>
</div>


@endsection


@section('script')



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
    <script>
      
        $(document).ready(function(){
            var i=1;
            $('#add').click(function(){
                i++;
                // $('#dynamic_field').append('<tr id="row'+i+'"><td><h4>'+i+'</h4></td><td class="text-semibold text-dark"><select class="selectpicker" data-show-subtext="true" data-live-search="true" onchange="getModel(this.value);" name="products"><option value="" disabled selected>Choose Products</option><option data-subtext="(INR 450/-)" value="1">Hello 1</option><option data-subtext="(INR 450/-)" value="2">Hello 2</option></select></td><td><input type="number" name="rate" id="rate" min="1" style="width:70px;"></td><td><input type="number" name="qty" id="qty" min="1" style="width:70px;"></td><td><input type="number" name="price" id="price" min="1" style="width:70px;" readonly></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                // $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" required class="form-control" name="name[]" id="name"></td><td class="text-semibold text-dark"><select class="selectpicker form-control type" id="type" data-show-subtext="true" required data-live-search="true" onchange="getModel(this.value);" name="type[]"><option value="" disabled selected>Choose Form Type</option><option data-subtext="(INR 450/-)" value="TEXT">Text</option><option data-subtext="(INR 450/-)" value="EMAIL">Email</option><option data-subtext="(INR 450/-)" value="TEXTAREA">Textarea</option><option data-subtext="(INR 450/-)" value="CHECKBOX">Checkbox</option><option data-subtext="(INR 450/-)" value="RADIO">Radio</option><option data-subtext="(INR 450/-)" value="SELECT">Select</option></select> <td><input type="checkbox" name="required[]" value="1"></td><td><input type="text" name="choices[]" id="choices" class="form-control choices"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove">X</button></td></tr>');

                $('#dynamic_field').append('<fieldset class="border p-2 mb-2" id="row'+i+'"> <div class="mb-2"><label class="form-label" for="post-title">Form Name</label><input type="text" class="form-control" id="post-title" name="name[]" required></div><div class="mb-2"><label class="form-label" for="post-title">Form Type</label><select class="selectpicker form-control type" id="type" required data-show-subtext="true" data-live-search="true" onchange="getModel(this.value);" name="type[]"><option value="" disabled selected>Choose Form Type</option><option data-subtext="(INR 450/-)" value="TEXT">Text</option><option data-subtext="(INR 450/-)" value="EMAIL">Email</option><option data-subtext="(INR 450/-)" value="TEXTAREA">Textarea</option><option data-subtext="(INR 450/-)" value="CHECKBOX">Checkbox</option><option data-subtext="(INR 450/-)" value="RADIO">Radio</option><option data-subtext="(INR 450/-)" value="SELECT">Select</option></select></div><div class="mb-2"><label class="form-label" for="post-title">Make Compulsory</label><input type="checkbox" name="required[]" value="1"></div><div class="mb-2"><label class="form-label" for="post-title">Options</label><input type="text" name="choices[]" class="form-control choices" id="choices"></div><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></fieldset>');
            }); 

            // const choicesInputs = document.querySelectorAll(".choices");
            // const typeSelects = document.querySelectorAll(".type");

            // typeSelects.forEach((select, index) => {
            //     select.addEventListener("change", function() {
            //         const selectedValue = select.value;
            //         if (selectedValue === "CHECKBOX" || selectedValue === "RADIO" || selectedValue === "SELECT") {
            //             choicesInputs.readOnly = true;
            //         } else {
            //             choicesInputs.readOnly = false;
            //         }
            //     });
            // });



            // const choicesInputs = document.querySelectorAll(".choices");
            // const typeSelects = document.querySelectorAll(".type");

            // typeSelects.forEach((select, index) => {
            //     select.addEventListener("change", function() {
            //         const selectedValue = select.value;
            //         if (selectedValue === "TEXT" || selectedValue === "EMAIL" || selectedValue === "SELECT") {
            //             choicesInputs[index].disabled = true;
            //         } else {
            //             choicesInputs[index].disabled = false;
            //         }
            //     });
            // });

            $(document).on('click', '.btn_remove', function(){
                var button_id = $(this).attr("id"); 
                $('#row'+button_id+'').remove();
            });
      
    });

    </script>


@endsection


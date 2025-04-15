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
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">List Professionals</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"> Home </li>
            <li class="breadcrumb-item active" aria-current="page">Professional</li>
          </ol>
        </nav>
      </div>
    </div>
</div>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Total Professionals - {{ $total }}</h3>
        </div>


        <div class="block-content">
            <p>
                List of Skill set
            </p>
            <div class="table-responsive">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

            <table class="table table-bordered table-striped table-vcenter">
                <thead>
                <tr>
                    {{-- <th>#</th> --}}
                    <th>Title</th>
                    <th>Skill</th>
                    <th>Name</th>
                    <th>Prof. Level</th>
                    <th>Yr. Experience</th>
                    <th>Location</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($skillAsset as $s)
                    <tr>
                        <td>{{$s->title}}</td>
                        <td>{{ $s->skill->name }}</td>
                        <td>{{ $s->user->name }}</td>
                        <td>{{ $s->profeciency_level }}</td>
                        <td>{{ $s->year_experience }} years</td>
                        <td>{{ $s->location }}</td>
                        <td>
                            <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">View</button>
                        </td>
                    </tr>
                    <div class="modal fade" id="modal-default-popout-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">{{ $s->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body pb-1">
                                <div class="col-xl-12">
                                    <!-- With Badges -->
                                    <div class="block block-rounded">
                                    
                                        <div class="block-content">
                                            {!! $s->description !!}
                                        </div>
                                        <?php $portfolio = App\Models\Portfolio::where('user_id', $s->user_id)->get(); ?>
                                        <hr>
                                        <h4>Portfolios</h4>
                                        @foreach($portfolio as $port)

                                        <div class="block-content">
                                            <h5>{{ $port->title }}</h5>
                                            <p>
                                            {!! $port->description !!}
                                            </p>
                                        </div>

                                        @endforeach
                                    </div>
                                    <!-- END With Badges -->

                                    <a href="{{ url('admin/professional/update/'.$s->id) }}" class="btn btn-danger">Deactivate</a>
                                </div>
                                
                            </div>
                            
                            <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                            {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                            </div>
                        </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
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

 <script>
    $(document).ready(function(){

        // alert('get/professional/category');

        $.ajax({
                    url: '{{ url("get/professional/category") }}',
                    type: "GET",
                    data: {
                        //  country_id: country_id,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    // context: document.body,
                    success: function(result) {
                        console.log(result);
                        $('#post-type').html('<option value="">Select Category</option>');
                        $.each(result, function(key, value) {
                            $("#post-type").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });


        
                $('#post-type').change(function(){
                    var typeID = this.value;

                    $("#post-category").html('');
                        $.ajax({
                            url: '{{ url("get/professional/sub/category") }}/' + encodeURI(typeID),
                            type: "GET",
                            data: {
                                //  country_id: country_id,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function(result) {
                                var new_result = result.sort(function(a, b) {
                                    return a.name.localeCompare(b.name);
                                });

                                console.log(result)

                                $('#post-category').html('<option value="">Select Sub Category</option>');
                                $.each(new_result, function(key, value) {
                                   
                                    $("#post-category").append('<option value="' + value.id + '">' + value.name + '</option>');  
                                });
                                // $('#city-dropdown').html('<option value="">Select Region/State First</option>');
                            }
                    });
                });

                

    });

 </script>

 @endsection
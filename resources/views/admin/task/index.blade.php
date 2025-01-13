@extends('layouts.main.master')


@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Task</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Task</li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
          </ol>
        </nav>
      </div>
    </div>
</div>

 <!-- Page Content -->
 <div class="content">
    <div class="block-header block-header-default">
        <h3 class="block-title">Create Task</h3>
    </div>

    <div class="block-content">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.store.task') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row push">
               
                <div class="col-lg-12">
                    {{-- <div class="mb-4">
                    <label class="form-label" for="example-text-input">Title</label>
                        <input type="hidden" class="form-control" value="Title" id="example-text-input" name="title" placeholder="Category">
                    </div> --}}
                    <input type="hidden" class="form-control" value="Task" id="example-text-input" name="title" placeholder="Category">
                    <div class="mb-4">
                    <label class="form-label" for="example-text-input">Description</label>
                        <textarea name="description" class="form-control" rows="10" required></textarea>
                    </div>
                    {{-- <div class="mb-4">
                    <label class="form-label" for="example-text-input">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">Select One</option>
                            <option value="TO-DO">TO DO</option>
                            <option value="IN-PROGRESS">IN PROGRESS</option>
                            <option value="COMPLETED">COMPLETED</option>
                        </select>
                    </div> --}}
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"> 
                    <div class="mb-4">
                        <button class="btn btn-primary" type="submit">Create Task</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


        <div class="block-content">
            <p>
                List of Tasks
            </p>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-vcenter">
                <thead>
                  <tr>
                    {{-- <th>Name</th> --}}
                    <th>Status</th>
                    <th>Description</th>
                    <th>When Created</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($tasks as $s)
                    <tr>
                        {{-- <td>{{$s->title}}</td> --}}
                        <td>{{ $s->status }}</td>
                        <td>{!! \Illuminate\Support\Str::words($s->description, 5) !!}</td>
                        <td> {{ $s->created_at }} </td>
                        <td>
                            {{-- <button type="button" class="btn btn-alt-primary btn-sm">View</button> --}}
                            <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $s->id }}">View</button>
                            {{-- <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-naira-{{ $s->id }}">Edit Price</button> --}}
                        </td>
                    </tr>

                    <div class="modal fade" id="modal-default-popout-{{ $s->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">{{ $s->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
    
                            <div class="modal-body pb-1">
                                <div class="col-xl-12">
                                    {!! nl2br(e($s->description)) !!}

                                    <hr>

                                    <form action="{{ route('admin.update.task') }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="form-label" for="example-text-input">Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="">Select One</option>
                                                    <option value="TO-DO">TO DO</option>
                                                    <option value="IN-PROGRESS">IN PROGRESS</option>
                                                    <option value="COMPLETED">COMPLETED</option>
                                                </select>
                                        </div>
                                            <input type="hidden" name="_id" value="{{ $s->id }}"> 
                                            <div class="mb-4">
                                                <button class="btn btn-primary" type="submit">Create Task</button>
                                            </div>
        
                                    </form>

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



       
    </div>




 </div>

@endsection

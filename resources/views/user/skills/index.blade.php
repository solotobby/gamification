@extends('layouts.main.master')

@section('style')

@endsection

@section('content')
   <!-- Hero -->
   <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
    <div class="bg-black-50">
      <div class="content content-full">
        <h1 class="fs-2 text-white my-2">
          <i class="fa fa-plus text-white-50 me-1"></i>Hire Skilled Workers
        </h1>
      </div>
    </div>
  </div>
  <!-- END Hero -->


  

  <div class="content">
      <h2 class="content-heading pt-0">Filter Search </h2>
      <div class="alert alert-info">
        Filter skilled worker based on your preferences
      </div>
        <form action="{{ url('skills') }}" method="GET">
          {{-- @csrf --}}
          <div class="row items-push">
            {{-- <div class="col-sm-6 col-xl-3">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa fa-title"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="dm-projects-search" name="dm-projects-search" placeholder="Search Projects..">
              </div>
            </div> --}}
            <div class="col-sm-6 col-xl-3">
              <select class="form-select" id="dm-projects-filter" name="skill_id">
                <option value="all">Select Skillset</option>
                @foreach ($skills as $skill)
                  <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>{{ $skill->name }} </option>
                @endforeach
              </select>
            </div>

            {{-- <div class="col-sm-6 col-xl-3">
              <select class="form-select" id="dm-projects-filter" name="profeciency_level">
                <option value="all">Experience Level</option>
                @foreach ($experience as $skill)
                  <option value="{{ $skill->id }}" {{ request('profeciency_level') == $skill->id ? 'selected' : '' }}>{{ $skill->name }} </option>
                @endforeach
              </select>
            </div> --}}

            <div class="col-sm-6 col-xl-3">
              <select class="form-select" id="dm-projects-filter" name="availability">
                    <option value="all">Availability</option>
                    <option value="remote" {{ request('availability') == 'remote' ? 'selected' : '' }}>Remote</option>
                    <option value="hybrid" {{ request('availability') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    <option value="physical" {{ request('availability') == 'physical' ? 'selected' : '' }}>Physical</option>
              </select>
            </div>

            <div class="col-sm-6 col-xl-3">
              <select class="form-select" id="dm-projects-filter" name="year_experience">
                    <option value="all">Years of Experience</option>
                    <option value="0-2" {{ request('year_experience') == '0-2' ? 'selected' : '' }}>0-2 years</option>
                    <option value="3-5" {{ request('year_experience') == '3-5' ? 'selected' : '' }}>3-5 years</option>
                    <option value="6-10" {{ request('year_experience') == '6-10' ? 'selected' : '' }}>6-10 years</option>
                    <option value="10+" {{ request('year_experience') == '10+' ? 'selected' : '' }}>10+ years</option>
              </select>
            </div>
            

            <div class="col-sm-6 col-xl-3">
              <div class="input-group">
                
                {{-- <input type="text" class="form-control border-start-0" id="dm-projects-search" name="dm-projects-search" placeholder="Search Projects.."> --}}
                {{-- <span class="input-group-text">
                  
                </span> --}}
                <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
              </div>
              
            </div>

          </div>
        </form>

         <!-- Lessons -->
         <div class="block block-rounded block-bordered">
          <div class="block-content">
            @if ($searchResult->isEmpty())
                {{-- <p>No results found.</p> --}}
            @else
            <table class="table table-striped table-borderless table-vcenter">
              <tbody>
                @foreach ($searchResult as $index => $result)
                <tr>
               

                  <td class="text-center w-25 d-none d-md-table-cell">
                    <a class="item item-circle bg-primary text-white fs-2 mx-auto" target="_blank" href="{{ url('view/skill/'.$result->id) }}">
                        <span class="fa fa-user"></span>
                    </a>
                  </td>

                  <td>
                    <div class="py-4">
                      <div class="fs-sm fw-bold text-uppercase mb-2">
                        <span class="text-muted me-3">{{ $result->user->name }}</span>
                       
                        <span class="text-primary">
                              <i class="fa fa-file"></i> {{ $result->year_experience }} years
                        </span>
                        -
                        <span class="text-primary">
                          <i class="fa fa-compass"></i> {{  $result->availability }} 
                        </span>
                      </div>
                      <a class="link-fx h4 mb-2 d-inline-block text-dark" target="_blank" href="{{ url('view/skill/'.$result->id) }}">
                        {{-- {{ $result->title }} --}}
                        {{ $result->skill->name }}
                      </a>
                      <span class="text-primary">
                          {{-- <i class="fa fa-file"></i> --}}
                          {{-- @if (!in_array($chapter->id, $reads))
                            
                          @else
                              <i class="fa fa-check"></i>
                          @endif --}}
                        </span>
                      <p class="text-muted mb-0">
                       
                          {!! \Illuminate\Support\Str::words($result->description, 35) !!}
                      </p>
                     
                    </div>
                  </td>
                </tr>
                @endforeach
                
              </tbody>
            </table>

            <div class="d-flex">
              {!! $searchResult->links('pagination::bootstrap-4') !!}
            </div>


            @endif
            

          </div>

          

        </div>
        <!-- END Lessons -->


        {{-- <div class="row items-push">
          <div class="mt-4">
            <h4>Search Results</h4>
            @if ($searchResult->isEmpty())
                <p>No results found.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> Name</th>
                            <th>Skill Name</th>
                            <th>Experience Level</th>
                            <th>Years of Experience</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($searchResult as $index => $result)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $result->user->name }}</td>
                                <td>{{ $result->skill->name }}</td>
                                <td>{{ $result->profeciencyLevel->name }}</td>
                                <td>{{ $result->year_experience }} years</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
          </div>
        </div> --}}


  </div>


@endsection

@section('script')

  <!-- Page JS Plugins -->
  <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>

  <!-- Page JS Helpers (CKEditor 5 plugins) -->
  <script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>

@endsection


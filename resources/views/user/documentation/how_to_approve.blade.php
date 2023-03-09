@extends('layouts.main.master')
@section('content')

  <!-- Main Container -->


    <!-- Hero -->
    <div class="bg-body-light">
      <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">How to Approve or Deny A Job</h1>
          <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">How to Approve/Deny Job</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
      <div class="block block-rounded">
        <div class="block-content">
          <p>
            Follow the steps below to approve/disapprove job(s) done by workers on Freebyz.
            <br>
            1. Login to dashboard <br>
            2. Select menu and select the 'Post campaign' sub menu. A drop down will be displayed.<br>
            3. Select 'View campaign' to select all the jobs you posted then click on 'View Activities' on the job you want to approve/deny<br>
            4. Once the jobs have been done by online workers, you'll see the list of people who have done the job<br>
            5. Click on 'View' button on the Right hand side of each submission and then see the submission (s) made by the workers<br>
            6. If you're satisfied, enter in your reasons and select the 'Approve' or 'Deny' button.<br>
            7. Don't forget that the system will automatically approve the jobs if you fail to approve after 5days.<br>
          </p>
        </div>
      </div>
    </div>
    <!-- END Page Content -->
 

@endsection
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
<title>Download Youtube Video</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" >
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" > --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" >

<script async src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
</script>
{{-- <script async src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js">
</script> --}}

<style>

@import url("https://fonts.googleapis.com/css2?family=Poppins:weight@100;200;300;400;500;600;700;800&display=swap");


       body{
        background-color:#eee;
        font-family: "Poppins", sans-serif;
        font-weight: 300;
       }

       .height{
        height: 100vh;
       }
       

       .search{
       position: relative;
       box-shadow: 0 0 40px rgba(51, 51, 51, .1);
         
       }

       .search input{

        height: 60px;
        text-indent: 25px;
        border: 2px solid #d6d4d4;


       }


       .search input:focus{

        box-shadow: none;
        border: 2px solid blue;


       }

       .search .fa-search{

        position: absolute;
        top: 20px;
        left: 16px;

       }

       .search button{

        position: absolute;
        top: 5px;
        right: 5px;
        height: 50px;
        width: 110px;
        background: blue;

       }
</style>

</head>

<div class="container">
    <div class="row height d-flex justify-content-center align-items-center">

        <div class="col-md-8">
        <form action="{{ route('download') }}" method="POST">
            @csrf
        <div class="search">
            <i class="fa fa-search"></i>
            <input type="url" required class="form-control" name="url" placeholder="Paste Youtube Link Url">
            <button type="submit" class="btn btn-primary">Download</button>
        </div>
        </form>
        
        </div>
        
    </div>
</div>
</html>


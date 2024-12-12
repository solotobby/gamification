@extends('layouts.main.master')
@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Overpass:400,600,800" rel="stylesheet">

<style>
* {
  margin: 0;
  padding: 0;
}



a {
  color: #34495e;
}

#counter{
  color: #36454f;
  text-align:center;
  margin-bottom: 2rem;
}
#winnings{
  color: #36454f;
  text-align:center;
  margin-top: 2rem;
}

#error{
  color: #36454f;
  text-align:center;
  margin-top: 2rem;
}

/* WRAPPER */
#wrapper {
  margin: 40px auto 0;
  width: 359px;
  position: relative;
}

#txt {
  display: none;
}

/* WHEEL */
#wheel {
  width: 343px;
  height: 343px;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
  border: 8px solid #EAE6D0;
  box-shadow: rgba(55, 51, 42, 0.2) 0px 0px 10px, rgba(55, 51, 42, 0.05) 0px 3px 0px;
  transform: rotate(0deg);
}

#wheel:before {
  content: '';
  position: absolute;
  border: 5px solid rgba(55, 51, 42, 0.1);
  width: 333px;
  height: 333px;
  border-radius: 50%;
  z-index: 1000;
}

#inner-wheel{
	width:100%;
	height:100%;
	
	-webkit-transition: all 6s cubic-bezier(0,.99,.44,.99);
	-moz-transition:    all 6 cubic-bezier(0,.99,.44,.99);
	-o-transition:      all 6s cubic-bezier(0,.99,.44,.99);
	-ms-transition:     all 6s cubic-bezier(0,.99,.44,.99);
	transition:         all 6s cubic-bezier(0,.99,.44,.99);	
}

/* This will manipulate the slice sizes... now to figure out how. Read this: https://www.smashingmagazine.com/2015/07/designing-simple-pie-charts-with-css/. You'll need to figure out the numbers for EACH pie chart depending on how many pie slices there are and how big the circle is... This is going to be a doozy. But get a foundation of how to do it first! */
#wheel div.sec{
	position: absolute;
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 200px 83px 0;
	border-color: #88C2B0 transparent;
	transform-origin: 83px 200px;
	left: 90px;
	top: -28px;
	opacity:1;
}

#wheel div.sec:nth-child(1) {
  transform: rotate(45deg);
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  border-color: #D54227 transparent;
}

#wheel div.sec:nth-child(2) {
  transform: rotate(90deg);
  -webkit-transform: rotate(90deg);
  -mos-transform: rotate(90deg);
  -o-transform: rotate(90deg);
  -ms-transform: rotate(90deg);
  border-color: #C0702B transparent;
}

#wheel div.sec:nth-child(3) {
  transform: rotate(135deg);
  -webkit-transform: rotate(135deg);
  -moz-transform: rotate(135deg);
  -o-transform: rotate(135deg);
  -ms-transform: rotate(135deg);
  border-color: #C98F2A transparent;
}

#wheel div.sec:nth-child(4) {
  transform: rotate(180deg);
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  border-color: #898E36 transparent;
}

#wheel div.sec:nth-child(5) {
  transform: rotate(225deg);
  -webkit-transform: rotate(225deg);
  -moz-transform: rotate(225deg);
  -o-transform: rotate(225deg);
  -ms-transform: rotate(255deg);
  border-color: #138D6C transparent;
}

#wheel div.sec:nth-child(6) {
  transform: rotate(270deg);
  -webkit-transform: rotate(270deg);
  -moz-transform: rotate(270deg);
  -o-transform: rotate(270deg);
  -ms-transform: rotate(270deg);
  border-color: #81B999 transparent;
}

#wheel div.sec:nth-child(7) {
  transform: rotate(315deg);
  -webkit-transform: rotate(315deg);
  -moz-transform: rotate(315deg);
  -o-transform: rotate(315deg);
  -ms-transform: rotate(315deg);
  border-color: #926C6c transparent;
}

#wheel div.sec:nth-child(8) {
  transform: rotate(360deg);
  -webkit-transform: rotate(360deg);
  -moz-transform: rotate(360deg);
  -o-transform: rotate(360deg);
  -ms-transform: rotate(360deg);
  border-color: #D67260 transparent;
}

#wheel div.sec .fas {
  margin-top: -125px;
  /*color: rgba(55, 51, 42, 0.2);*/
  color: aliceblue,
  position: relative;
  z-index: 10000000;
  display: block;
  text-align: center;
  font-size: 15px;
  margin-left: -15px;
  
  /* text-shadow: rgba(234, 230, 208, 0.1) 0px -1px 0px, rgba(55, 51, 42, 0.2) 0px 1px 0px; */
}

#spin {
  width: 68px;
  height: 68px;
  position: absolute;
  top: 50%;
  left: 50%;
  margin: -34px 0 0 -34px;
  border-radius: 50%;
  box-shadow: rgba(55, 51, 42, 0.1) 0px 3px 0px;
  z-index: 1000;
  background: #EAE6D0;
  cursor: pointer;
  font-family: 'Overpass', sans-serif;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -o-user-select: none;
  user-select: none;
}

#spin:after {
  content: "SPIN";
  font-size: 20px;
  text-align: center;
  line-height: 68px;
  color: #BFBBA6;
  text-shadow: 0 2px 0 #F2EEDD, 0 -2px 0 rgba(55, 51, 42, 0.3);
  position: relative;
  z-index: 100000;
  width: 68px;
  height: 68px;
  display: block;
}

#spin:before {
  content: "";
  position: absolute;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 0 20px 28px 20px;
  border-color: transparent transparent #F2EEDD transparent;
  top: -12px;
  left: 14px;
}

#inner-spin {
  width: 54px;
  height: 54px;
  position: absolute;
  top: 50%;
  left: 50%;
  margin: -27px 0 0 -27px;
  border-radius: 50%;
  background: red;
  z-index: 999;
  box-shadow: rgba(234, 230, 208, 1) 0px -2px 0px inset, rgba(234, 230, 208, 1) 0px 2px 0px inset, rgba(0, 0, 0, 0.4) 0px 0px 5px;
  
  background rgb(234, 230, 208) /* Old Broswers */
  background: -moz-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(211, 208, 193, 1) 100%); /* FF3.6+ */
  background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(234, 230, 208, 1)), color-stop(100%, rgba(211, 208, 193, 1))); /* Chrome, Safari 4+ */
  background: -webkit-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(211, 208, 193, 1) 100%); /* Chrome 10+, Safari 5.1+ */
  background: -o-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(211, 208, 193, 1) 100%); /* Opera 12+ */
  background: -ms-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(211, 208, 193, 1) 100%); /* IE10+ */
  background: radial-gradient(ellipse at center, rgba(234, 230, 208, 1) 0%, rgba(211, 208, 193, 1) 100%); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#EAE6D0', endColorst='#D3D0C1', GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}

#spin:active #inner-spin{
  box-shadow: rgba(55, 51, 42, 0.4) 0px 0px 5px inset;
}

#spin:active:after {
  font-size: 18px;
}

#shine {
  width: 250px;
  height: 250px;
  position: absolute;
  top: 0;
  left: 0;
  background: -moz-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(234, 230, 208, 0.99) 1%, rgba(234, 230, 208, 0.91) 9%, rgba(234, 230, 208, 0) 100%); /* FF3.6+ */
  background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(234, 230, 208, 1)), color-stop(1%, rgba(234, 230, 208, 0.99)), color-stop(9%, rgba(234, 230, 208, 0.91)), color-stop(100%, rgba(234, 230, 208, 0))); /* Chrome, Safari 4+ */
  background: -webkit-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(234, 230, 208, 0.99) 1%, rgba(234, 230, 208, 0.91) 9%, rgba(234, 230, 208, 0) 100%); /* Chrome 10+, Safari 5.1+ */
  background: -o-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(234, 230, 208, 0.99) 1%, rgba(234, 230, 208, 0.91) 9%, rgba(234, 230, 208, 0) 100%); /* Opera 12+ */
  background: -ms-radial-gradient(center, ellipse cover, rgba(234, 230, 208, 1) 0%, rgba(234, 230, 209, 0.99) 1%, rgba(234, 230, 208, 0.91) 9%, rgba(234, 230, 208, 1) 100%); /* IE10+ */
  background: radial-gradient(ellipse at center, rgba(234, 230, 208, 1) 0%, rgba(234, 230, 208, 0.99) 1%, rgba(234, 230, 208, 0.91) 9%, rgba(234, 230, 208, 0) 100%); /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#00ffffff', GradientType=1 ); /* IE 6-9 fallback on horizontal gradient */
  
  opacity: 0.1;
}

/* ANIMATION of the tab clicking to the side as the pie edges come around. When I added more pie slice, it looked glitchy, so we'll not have this for now. */
/* @-webkit-keyframes hh {
  0%, 100% {
    transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
  }
  
  50% {
    transform: rotate(7deg);
    -webkit-transform: rotate(7deg);
  }
}

@keyframes hh {
  0%, 100% {
    transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
  }
  
  50% {
    transform: rotate(7deg);
    -webkit-transform: rotate(7deg);
  }
}
*/
.spin {
  -webkit-animation: hh 0.1s; /* Chrome, Safari, Opera */
  animation: hh 0.1s;
}
.disabled {
    background-color: gray;
    cursor: not-allowed;
}





</style>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3" style="color: black">Spin to Win </h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item" style="color: black">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Spin to Win</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

    <!-- Page Content -->
    <div class="content">

        <div id="wrapper">
            {{-- <div id="counter"> </div> --}}
            <div id="wheel">
                <div id="inner-wheel">
                  <div class="sec"><span class="fas">₦50k</span></div>
                  <div class="sec"><span class="fas">₦20k</span></div>
                  <div class="sec"><span class="fas">₦1k</span></div>
                  <div class="sec"><span class="fas">₦50</span></div>
                  <div class="sec"><span class="fas">₦25</span></div>
                  <div class="sec"><span class="fas">₦20</span></div>
                  <div class="sec"><span class="fas">₦15</span></div>
                  <div class="sec"><span class="fas">₦10</span></div>
              </div>
              
              <div id="spin">
                <div id="inner-spin"></div>
              </div>
              
              <div id="shine"></div>
          </div>
          
          <div id="txt"></div>
          <div id="winnings">
          <div id="error">
        </div>

        <div id="error-modal" class="modal">
          <div class="modal-content">
            <span class="close">&times;</span>
            <p id="error-message"></p>
          </div>
        </div>

    </div>
@endsection

@section('script')
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.min.js"></script> --}}
<script>

$(document).ready(function () {
  function showErrorModal(message) {
    // Get modal and elements
    const modal = document.getElementById("error-modal");
    const errorMessage = document.getElementById("error-message");
    const closeBtn = document.querySelector(".close");

    // Set the message
    errorMessage.textContent = message;

    // Show the modal
    modal.style.display = "block";

    // Close modal on button click
    closeBtn.onclick = function () {
        modal.style.display = "none";
    };

    // Close modal on outside click
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
  }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    let attempts = 0;
    let balance = false;
    const degree = 1800;
    let clicks = 0;

    async function fetchTodaysEntries() {
        try {
            const response = await fetch('spin/attempt');
            if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
            }
            const data = await response.json();
            console.log(data); // Display today's entries

            attempts = data.count;
            balance = data.balance;
            if(!balance){
                $("#spin").css({
                    "pointer-events": "none",
                    "opacity": "0.6"
                });

                $("#winnings").text("You need a minium balance of NGN10,000 in your wallet to play this game!");
            }
           


        } catch (error) {
            console.error("Error fetching today's entries:", error);
        }
    }

    fetchTodaysEntries();





  /*WHEEL SPIN FUNCTION*/
  $("#spin").click(async function () {

    if (attempts > 0) {

        
        attempts--;
        $("#counter").text(`Attempts Remaining: ${attempts}`);

        try{
            
            // Fetch the result from the backend
            const response = await fetch("spin-wheel", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,// CSRF token for security
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ attemptsRemaining: attempts }),
            });

           // if (!response.ok) throw new Error("Failed to fetch result");

            if (!response.ok) {
                const errorData = await response.json();
                $("#winnings").text(errorData.message);
                document.querySelector("#spin").disabled = true;

                // // alert(errorData.error); // Display error message
                // if (errorData.error.includes("limit reached")) {
                //   // Disable spin button
                // }
                return;
            }


            const result = await response.json();
            const { degreeOffset, prize } = result; // Backend returns degreeOffset and prize
      
            console.log(result);

            clicks++;
            var newDegree = degree * clicks;
            var totalDegree = newDegree + degreeOffset;

            $("#inner-wheel").css({
                transform: `rotate(${totalDegree}deg)`,
            });

            setTimeout(() => {
                $("#winnings").text(prize);
            }, 6000); // Wait for the spin animation to complete

            


         } catch (error) {

          // showErrorModal("An unexpected error occurred. Please try again.");
            // console.error("Error spinning the wheel:", error);

            console.error("Error fetching spin result:", error);
            $("#winnings").text("Error, please try again!");
        }

    } else {
      
        $("#winnings").text("No attempts remaining.");
    }

  });

}); //DOCUMENT READY

</script>
@endsection

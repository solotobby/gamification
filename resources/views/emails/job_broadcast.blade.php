{{-- @extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        <tr>
            <td style="padding: 30px 30px 15px 30px;">
                <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">{{$subject}}</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 30px 20px">
                <p style="margin-bottom: 10px;">Hi <strong>{{ $name }},</strong></p>
                <p style="margin-bottom: 10px;">
                   
                   <table>
                    <thead>
                        <tr>
                            
                            <th>Campaign Amount</th>
                            <th>Post Title</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Is Completed</th>
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $job)
                            <tr>
                                <td>{{ $job['id'] }}</td>
                                <td>{{ $job['job_id']}}</td>
                                <td>{{ $job['campaign_amount'] }}</td>
                                <td>{{ $job['post_title'] }}</td>
                                <td>{{ $job['type'] }}</td>
                                <td>{{ $job['category'] }}</td>
                                
                                <td>{{ $job['currency'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                   
                <br>
            </p>


                {{-- <p style="margin-bottom: 10px;">
                    Click the button below to access more jobs... <br><br>
                    <a href="{{ url($url) }}" target="_blank" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">
                        Take More Jobs
                    </a>
                </p> --}}
                {{-- <p style="margin-bottom: 10px;">Its clean, minimal and pre-designed email template that is suitable for multiple purposes email template.</p>
                <p style="margin-bottom: 15px;">Hope you'll enjoy the experience, we're here if you have any questions, drop us a line at info@yourwebsite.com anytime. </p> -
                <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards, <br><i>Freebyz Team.</i></p>
            </td>
        </tr>
    </tbody>
</table>

@endsection --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Campaigns from Freebyz</title>
    <style>
        /* Reset styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        /* Container styles */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        /* Header styles */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #333;
        }
        /* Job details styles */
        .job-details {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .job-details h2 {
            color: #333;
            margin-bottom: 5px;
        }
        .job-details p {
            color: #666;
            margin-bottom: 3px;
        }
        /* CTA button styles */
        .cta-button {
            text-align: left;
            margin-top: 4px;
        }
        .cta-button a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        /* Footer styles */
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Latest Campaign on Freebyz</h1>
        </div>
        @foreach($campaigns as $job)
            <div class="job-details">
                <h2>{{ $job['post_title'] }}</h2>
                <p>Amount: {{ $job['currency'] }} {{ $job['campaign_amount'] }}</p>
                <p>Category: {{ $job['type'] }}</p> 
                <div class="cta-button">
                    <a href="{{  url('campaign/'.$job['job_id']) }}" target="_blank">View Campaign</a>
                </div>
            </div>
            
        @endforeach
        
        <div class="footer">
            <p>Freebyz Team</p>
        </div>
    </div>
</body>
</html>




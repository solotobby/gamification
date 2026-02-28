{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Campaigns from Freebyz</title>
    <style>
        /* Reset styles */
        body,
        html {
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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
                <a href=" https://freebyz.com/campaign/{{ $job['job_id'] }}" target="_blank">View Campaign</a>
            </div>
        </div>
        @endforeach

        <p style="margin-top: 20px; margin-bottom: 20px;">
            Get Real Time Updates on Fresh Jobs and Tasks on our channel
            <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                style="color: #25D366; font-weight: 600;">Join WhatsApp Channel</a>
        </p>
        <div class="footer">
            <p>Freebyz Team</p>
        </div>
    </div>
</body>

</html> --}}

@extends('email_template.master')

@section('content')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
            <!-- Header -->
            <tr>
                <td style="padding:30px 30px 15px 30px;">
                    <h2 style="font-size:18px;color:#6576ff;font-weight:600;margin:0;">
                        Latest Tasks on Freebyz
                    </h2>
                </td>
            </tr>

            <!-- Body -->
            <tr>
                <td style="padding:30px;">
                    <p style="margin-bottom:10px;">
                        Hi <strong>{{ $userName ?? 'there' }},</strong>
                    </p>

                    <p style="margin-bottom:20px;">
                        Fresh earning opportunities are live on
                        <a href="{{ url('/jobs') }}" style="color:#6576ff;font-weight:600;">Freebyz</a>.
                        Explore the tasks below and start earning today 💳
                    </p>

                    @foreach($campaigns as $job)
                                        <div style="
                                                background:#f8f9fa;
                                                border:1px solid #e5e9f2;
                                                border-radius:8px;
                                                padding:20px;
                                                margin-bottom:15px;
                                            ">
                                            <h4 style="margin:0 0 8px 0;color:#000;">
                                                {{ $job['post_title'] }}
                                            </h4>

                                            <p style="margin:0 0 5px 0;color:#555;">
                                                <strong>Amount:</strong> {{ $job['currency'] }} {{ number_format($job['campaign_amount']) }}
                                            </p>

                                            <p style="margin:0 0 12px 0;color:#555;">
                                                <strong>Category:</strong> {{ ucfirst($job['type']) }}
                                            </p>

                                            <a href="{{ route('campaign.public.view', $job['job_id']) }}" target="_blank" style="
                                display:inline-block;
                                padding:10px 18px;
                                background-color:#6576ff;
                                color:#ffffff;
                                text-decoration:none;
                                border-radius:5px;
                                font-weight:600;
                           ">
                                                View Task
                                            </a>

                                        </div>
                    @endforeach

                    <p style="margin-top:25px;">
                        Get real-time updates on fresh jobs and tasks:
                        <br>
                        <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                            style="color:#25D366;font-weight:600;">
                            Join WhatsApp Channel
                        </a>
                    </p>

                    <p style="margin-top:45px;margin-bottom:15px;">
                        ---- <br>
                        Regards, <br>
                        <i>{{ config('app.name') }} Team</i>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
@endsection

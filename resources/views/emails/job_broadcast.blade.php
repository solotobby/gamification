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

</html>

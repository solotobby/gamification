@component('mail::message')
# Campaign Posted

Hi {{ $poster }}, 

Your campaign has been posted successfully.<br>

    Campaign ID: {{ $job_id }}
    Campaign Name: {{ $campaign_name }}
    Campaign Type: {{ $type }}
    Campaign Category: {{ $category }}
    Amount per Job: &#8358; {{ $amount }}
    Number of Worker: {{ $number_of_staff }}
    Total Amount: &#8358; {{ $total_amount }}

@component('mail::button', ['url' =>  url('campaign/create') ])
Create More Campaign
@endcomponent

Thanks,<br>
<b>Freebyz Team</b>
@endcomponent

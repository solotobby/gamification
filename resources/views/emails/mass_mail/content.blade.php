@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        {{-- <tr>
            <td style="padding: 30px 30px 15px 30px;">
                <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">Upgrade Successful</h2>
            </td>
        </tr> --}}
        <tr>
            <td style="padding: 30px 30px 20px">
                <p style="margin-bottom: 10px;">Hi <strong>{{ $name }},</strong></p>
                <p style="margin-bottom: 10px;">
                {{-- {!! $message !!}     --}}
               
                Thank you for being a valued Freebyz customer!
                <br><br>
                We would like to let you know that, on January 15, 2025, we are going to increase verification fees for new user(s).
                <br><br>

                As you may be aware, hosting servers has been implementing yearly price increases that impacted several businesses. In recent years, we’ve absorbed these costs to keep your access to Freebyz stable. However, due to the ongoing changes, we’ve made the difficult decision to adjust our verification fees.
                <br><br>
                The new verification price(s) will be ₦3000 (for naira wallet), $6 (for dollar wallets) and their equivalents in GHS, KES, ZAR, UGX and other currencies available on Freebyz. 
                To this end, referral bonuses will also be  ₦1, 000 (for naira wallet), ($2.5 for dollar wallets) and their respective equivalents in other currencies.
                <br><br>
                Please note that minimum withdrawal will still remain  ₦2500 (for naira wallet), $10 (for dollar wallet) and their respective equivalents. 
                <br>
                This adjustment will be applied by 12.00 GMT on January 15, 2025.
                <br><br>
                Thank you for your understanding.
                <br>
                </p>
                <p style="margin-bottom: 10px;">
                    If you have any questions, please don't hesitate to contact our Customer Support team 
                     <br><br>
                    <a href="https://tawk.to/chat/6510bbe9b1aaa13b7a78ae75/1hb4ls2fd" target="_blank" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">
                        Contact Customer Care
                    </a>
                </p>
                {{-- <p style="margin-bottom: 10px;">Its clean, minimal and pre-designed email template that is suitable for multiple purposes email template.</p>
                <p style="margin-bottom: 15px;">Hope you'll enjoy the experience, we're here if you have any questions, drop us a line at info@yourwebsite.com anytime. </p> --}}
                <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards, <br><i>Freebyz Team.</i></p>
            </td>
        </tr>
    </tbody>
</table>
@endsection
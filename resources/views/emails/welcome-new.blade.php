@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        <tr>
            <td style="padding:40px 30px;">
                <p style="margin:0 0 15px 0;">Dear {{ $userName }},</p>

                <p style="margin:0 0 15px 0;">
                    You now have access to thousands of simple tasks that can earn you money daily plus EXTRA 50k to your
                    account every week.
                </p>

                <p style="margin:0 0 20px 0;">
                    Dear friend, I am super excited to welcome you to <a href="{{ url('/') }}">Freebyz</a>, Africa's trusted
                    platform for micro and remote jobs since 2022.
                    We are an 100% registered and Legit company. With your smartphone or PC, you can start earning legitimately
                    today on Freebyz
                    as your side hustle platform to pay your Bills 💳 from now on.
                </p>

                <h3 style="margin:20px 0 10px 0;">Here's what you'll find inside Freebyz:</h3>
                <ul style="margin:0 0 20px 0;padding-left:20px;">
                    <li style="margin:0 0 8px 0;"><strong>Micro Tasks:</strong> Complete easy tasks and withdraw earnings in your local currency every
                        Friday.</li>
                    <li style="margin:0 0 8px 0;"><strong>Full-time Jobs:</strong> Apply for 9am–5pm jobs or recruit full-time staff for your projects.
                    </li>
                    <li style="margin:0 0 8px 0;"><strong>Affiliate Marketing:</strong> Earn ₦1000 for every verified user you refer. Many of our users
                        have made millions simply by sharing their referral link or creating short videos about Freebyz.</li>
                    <li style="margin:0 0 8px 0;"><strong>Global Reach:</strong> Over 330,000 users worldwide trust Freebyz.</li>
                </ul>

                <p style="margin:0 0 20px 0;">
                    Over 50k Startups, creators, and founders already use Freebyz to hire workers, download apps, grow
                    audiences, and even stream music.
                </p>

                <h3 style="margin:20px 0 10px 0;">🔥 CLAIM YOUR FIRST OFFER OF 50K GIVEAWAY</h3>
                <p style="margin:0 0 15px 0;">
                    Every week until October 30, 2025, the user with the highest verified referrals will receive ₦50,000.
                    It's simple to qualify, get at least 50 verified referrals within the week.
                </p>
                <p style="margin:0 0 15px 0;">
                    To start, <a href="{{ url('/login') }}">Login</a> now to Copy your referral link now and start sharing with
                    your friends on Whatsapp, Facebook or TikTok and encourage them to verify their account with just 3000
                    naira.
                    You can also make review videos and earn 7k for each review video.
                </p>

                <p style="margin:0 0 15px 0;">
                    <a href="https://www.tiktok.com/@freebyzjobs/video/7449104740341779717">Click here</a> to see others earning
                    big on Freebyz. We can't wait to start crediting your account too.
                </p>

                <p style="margin:0 0 20px 0;">Follow our official updates <a href="https://facebook.com/remotejobsbyfreebyz">here</a></p>

                <p style="margin:20px 0 5px 0;">Regards,</p>
                <p style="margin:0;"><strong>Dr. Farohunbi Samuel</strong><br>
                    CEO, Dominahl Technologies LLC</p>
            </td>
        </tr>
    </tbody>
</table>

@endsection

@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <tbody>
        <tr>
            <td style="padding: 25px 30px; background-color: #fdf2f2; border-bottom: 2px solid #f8d7da;">
                <span style="background-color: #e53e3e; color: #ffffff; font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 4px; letter-spacing: 0.5px;">Account Notice</span>
                <h2 style="font-size: 20px; color: #9b1c1c; font-weight: 700; margin: 12px 0 0 0; line-height: 1.3;">
                    Your Freebyz Account is Scheduled for Deletion
                </h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px 30px 20px;">
                <p style="margin-bottom: 15px; font-size: 15px; color: #333333;">
                    Dear <strong>{{ $name }},</strong>
                </p>
                <p style="margin-bottom: 15px; font-size: 14px; color: #555555; line-height: 22px;">
                    We are writing to notify you that your Freebyz account associated with <strong>{{ $email }}</strong> has been deleted and queued for permanent removal.
                </p>

                <!-- Deletion Details Box -->
                <div style="background-color: #fff8f8; border: 1px solid #f5c6cb; border-radius: 6px; padding: 18px 20px; margin: 20px 0;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; color: #495057;">
                        <tr>
                            <td style="padding: 6px 0; font-weight: 600; width: 45%; color: #721c24;">Account Status:</td>
                            <td style="padding: 6px 0; color: #dc3545; font-weight: 700;">Scheduled for Deletion</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; font-weight: 600; color: #721c24;">Date Initiated:</td>
                            <td style="padding: 6px 0;">{{ $deletedAt }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; font-weight: 600; color: #721c24;">Grace Period:</td>
                            <td style="padding: 6px 0; font-weight: 700; color: #e53e3e;">60 Days</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; font-weight: 600; color: #721c24;">Permanent Purge Date:</td>
                            <td style="padding: 6px 0; font-weight: 700;">{{ $purgeDate }}</td>
                        </tr>
                    </table>
                </div>

                <p style="margin-bottom: 15px; font-size: 14px; color: #555555; line-height: 22px;">
                    <strong>What this means:</strong><br>
                    In accordance with our data retention policy, your account profile, wallet, and associated records will remain on our servers for <strong>60 days</strong> before being <strong>permanently and irreversibly wiped</strong>. During this 60-day grace period, direct login access to your account is disabled.
                </p>

                <!-- Reactivation Section -->
                <div style="background-color: #f0f4f8; border-left: 4px solid #6576ff; border-radius: 4px; padding: 15px 18px; margin: 22px 0;">
                    <h4 style="margin: 0 0 8px 0; font-size: 14px; color: #2b3a4a; font-weight: 700;">Did not request this or changed your mind?</h4>
                    <p style="margin: 0; font-size: 13px; color: #556877; line-height: 20px;">
                        If you wish to cancel this deletion and reactivate your account, you can do so at any time within the <strong>60-day grace period</strong> by contacting our support team at <a href="mailto:{{ $supportEmail }}" style="color: #6576ff; font-weight: 600; text-decoration: underline;">{{ $supportEmail }}</a> with your registered email address.
                    </p>
                </div>

                <p style="margin-top: 25px; margin-bottom: 25px; text-align: center;">
                    <a href="mailto:{{ $supportEmail }}?subject=Account%20Reactivation%20Request%20-%20{{ urlencode($email) }}&body=Hello%20Support%20Team,%0A%0AI%20am%20writing%20to%20request%20the%20reactivation%20of%20my%20Freebyz%20account%20associated%20with%20{{ urlencode($email) }}.%0A%0AThank%20you."
                       target="_blank"
                       style="background-color: #6576ff; border-radius: 4px; color: #ffffff; display: inline-block; font-size: 13px; font-weight: 600; line-height: 44px; text-align: center; text-decoration: none; padding: 0 30px; letter-spacing: 0.3px;">
                        Contact Support to Reactivate
                    </a>
                </p>

                <p style="margin-top: 35px; margin-bottom: 5px; font-size: 14px; color: #555555;">
                    Thank you for being part of Freebyz.
                </p>
                <p style="margin-top: 10px; margin-bottom: 15px; font-size: 14px; color: #555555;">
                    ---- <br> Regards, <br><strong>Freebyz Support Team</strong>
                </p>
            </td>
        </tr>
    </tbody>
</table>

@endsection

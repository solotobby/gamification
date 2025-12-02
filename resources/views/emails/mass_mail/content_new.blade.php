@extends('email_template.master')

@section('content')

<table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
    <tbody>
        <tr>
            <td style="padding: 30px 30px 20px">
                <p style="margin-bottom: 10px;">Dear <strong>{{ $name }},</strong></p>
                <div style="margin-bottom: 10px; white-space: pre-line;">
                    {!! $message !!}
                </div>
                <br>
                <img src="https://res.cloudinary.com/movic/image/upload/v1764664018/Freebyz_earn_xmpeep.jpg"
                     alt="Freebyz"
                     style="max-width: 500px; width: 100%; height: auto;">
            </td>
        </tr>
    </tbody>
</table>
@endsection

@extends('email_template.master')

@section('content')
    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
        <tbody>
            <tr>
                <td style="padding: 30px 30px 20px;">

                    {{-- Greeting --}}
                    <p style="margin-bottom: 20px; font-weight: 500;">
                        Dear {{ $name }},
                    </p>

                    {{-- Message --}}
                    <div style="margin-bottom: 10px; white-space: pre-line;">
                        {!! $message !!}
                    </div>

                    {{-- Optional Image --}}
                    @if(!empty($imagePath))
                        <br>
                        <img src="{{ displayImage($imagePath) }}" alt="Campaign Image"
                            style="max-width: 500px; width: 100%; height: auto;">
                    @endif

                    {{-- CTA / Channel Link --}}
                    <p style="margin-top: 40px; margin-bottom: 20px;">
                        Get Real Time Updates on Fresh Jobs and Tasks on our channel
                        <a href="https://whatsapp.com/channel/0029Vb7Zfnb65yDGlRg8ho1M" target="_blank"
                            style="color: #25D366; font-weight: 600;">Join WhatsApp Channel</a>
                    </p>

                </td>
            </tr>
        </tbody>
    </table>
@endsection

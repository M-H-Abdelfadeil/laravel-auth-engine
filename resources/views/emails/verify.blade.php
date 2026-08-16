<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notification' }}</title>
</head>

<body style="margin:0; padding:0; background:#f8f9fa; font-family:Arial, Helvetica, sans-serif; color:#222;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background:#f8f9fa; padding:40px 15px;">

        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width:480px; background:#ffffff;">

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 30px; text-align:center;">

                            @if(!empty($header))
                                <p style="margin:0 0 25px; font-size:16px; font-weight:600; color:#222;">
                                    {{ $header }}
                                </p>
                            @endif

                            <p style="margin:0 0 25px; font-size:14px; line-height:1.6; color:#666;">
                                {{ $message_text ?? 'Please use the code below to continue.' }}
                            </p>

                            <!-- OTP -->
                            <div style="
                                display:inline-block;
                                padding:14px 24px;
                                background:#f5f5f5;
                                border-radius:6px;
                                font-size:28px;
                                font-weight:700;
                                letter-spacing:6px;
                                color:#222;
                            ">
                                {{ $code }}
                            </div>

                            @if(!empty($action_url))
                                <p style="margin:30px 0 0;">
                                    <a href="{{ $action_url }}"
                                        style="
                                            display:inline-block;
                                            padding:11px 22px;
                                            background:#222;
                                            color:#ffffff;
                                            text-decoration:none;
                                            border-radius:5px;
                                            font-size:13px;
                                        ">
                                        {{ $action_text ?? 'Continue' }}
                                    </a>
                                </p>
                            @endif

                            <p style="
                                margin:28px 0 0;
                                font-size:12px;
                                line-height:1.6;
                                color:#999;
                            ">
                                {{ $footer_text ?? 'If you didn’t request this, you can safely ignore this email.' }}
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="
                            padding:18px 30px;
                            border-top:1px solid #eeeeee;
                            text-align:center;
                        ">
                            <p style="
                                margin:0;
                                font-size:11px;
                                color:#aaa;
                            ">
                                © {{ date('Y') }} {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>


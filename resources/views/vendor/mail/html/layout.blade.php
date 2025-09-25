<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        body { width: 100% !important; height: 100%; margin: 0; line-height: 1.4; background-color: #F2F4F6; color: #718096; -webkit-text-size-adjust: none; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; }
        .email-wrapper { width: 100%; margin: 0; padding: 0; background-color: #F2F4F6; }
        .email-content { width: 100%; margin: 0; padding: 0; }
        .email-body { width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFFFFF; }
        .email-masthead { padding: 25px 0; text-align: center; }
        .email-masthead_name { font-size: 16px; font-weight: bold; color: #A8AAAF; text-decoration: none; text-shadow: 0 1px 0 white; }
        .content-cell { padding: 35px; }
        .title { margin-top: 0; color: #333333; font-size: 22px; font-weight: bold; text-align: left; }
        .paragraph { margin-top: 0; color: #51545E; font-size: 16px; line-height: 1.5em; text-align: left; }
        .paragraph-center { text-align: center; }
        .button { background-color: #A13333; border-top: 10px solid #A13333; border-right: 18px solid #A13333; border-bottom: 10px solid #A13333; border-left: 18px solid #A13333; display: inline-block; color: #FFF; text-decoration: none; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); -webkit-text-size-adjust: none; box-sizing: border-box; }
        .email-footer { width: 570px; margin: 0 auto; padding: 35px; text-align: center; }
        .email-footer_cell { color: #A8AAAF; padding: 0; text-align: center; }
        .email-footer_text { font-size: 12px; }

        @media only screen and (max-width: 600px) {
            .email-body_inner, .email-footer { width: 100% !important; }
        }
    </style>
</head>
<body>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="email-masthead">
                            <a href="{{ config('app.url') }}" class="email-masthead_name">
                                <img src="{{ asset('images/logo-sidebar.png') }}" alt="Satoria VMS Logo" height="50" style="max-height: 50px;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell">
                                        {!! Illuminate\Mail\Markdown::parse($slot) !!}
                                        {{ $subcopy ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell" align="center">
                                        <p class="email-footer_text">
                                            &copy; {{ date('Y') }} Satoria Visit Management System. All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
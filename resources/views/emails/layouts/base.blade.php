<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('title')</title>
    <!--[if mso]>
    <style>
        * { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; }
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; word-spacing: normal; background-color: #f5f5f5;">
    <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background-color: #f5f5f5;">
        <table role="presentation" style="width: 100%; border: 0; border-spacing: 0; background-color: #f5f5f5;">
            <tr>
                <td align="center" style="padding: 20px 10px;">
                    <!--[if mso]>
                    <table role="presentation" align="center" style="width: 600px;">
                    <tr>
                    <td>
                    <![endif]-->
                    <table role="presentation" style="width: 100%; max-width: 600px; border: 0; border-spacing: 0; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        
                        <!-- Header -->
                        <tr>
                            <td align="center" style="padding: 40px 30px; background-color: #51040e; background: linear-gradient(135deg, #51040e 0%, #7d0a1b 100%);">
                                @hasSection('header_icon')
                                    <div style="font-size: 48px; margin-bottom: 12px; color: #ffffff;">@yield('header_icon')</div>
                                @endif
                                <h1 style="margin: 0; font-size: 26px; font-weight: 700; color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    @yield('header_title')
                                </h1>
                                <p style="margin: 10px 0 0; font-size: 15px; color: #f5d9dd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    @yield('header_subtitle')
                                </p>
                            </td>
                        </tr>

                        <!-- Content -->
                        <tr>
                            <td style="padding: 40px 30px; background-color: #ffffff; color: #4a5568; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; line-height: 1.6;">
                                @yield('content')
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td align="center" style="padding: 30px; background-color: #fcfbf8; border-top: 1px solid #e2e8f0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                <p style="margin: 0 0 5px 0; color: #718096; font-size: 13px; font-weight: bold;">Harita Music Academy</p>
                                <p style="margin: 0 0 15px 0; color: #718096; font-size: 13px;">Nurturing Musical Excellence Since 2026</p>
                                
                                <p style="margin: 0 0 15px 0; color: #718096; font-size: 13px;">
                                    Email: <a href="mailto:info@haritamusicacademy.com" style="color: #51040e; text-decoration: none; font-weight: bold;">info@haritamusicacademy.com</a> | 
                                    Phone: +91 87968 23332
                                </p>
                                
                                <p style="margin: 0 0 10px 0; color: #999999; font-size: 11px;">
                                    This is an automated email. Please do not reply directly to this message.
                                </p>
                                
                                <p style="margin: 0; color: #999999; font-size: 11px;">
                                    &copy; {{ date('Y') }} Harita Music Academy. All rights reserved. | 
                                    Developed by <a href="https://sitesoch.com" style="color: #667eea; text-decoration: none;">Sitesoch</a>
                                </p>
                            </td>
                        </tr>
                        
                    </table>
                    <!--[if mso]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

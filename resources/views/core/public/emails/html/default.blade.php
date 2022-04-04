<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $message->getSubject() }}</title>
    <style type="text/css">

        /* CSS RESET
        ================================================*/
        #outlook a{padding:0;}body{width:100% !important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0;padding:0;}.ExternalClass{width:100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:100%;}#backgroundTable{margin:0;padding:0;width:100% !important;line-height:100% !important;}img{outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;}a img{border:none;}.image_fix{display:block;}p{margin:1em 0;line-height:1.25;}h1,h2,h3,h4,h5,h6{color:black !important;line-height:1.25;}h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{color:blue !important;}h1 a:active,h2 a:active,h3 a:active,h4 a:active,h5 a:active,h6 a:active{color:red !important;}h1 a:visited,h2 a:visited,h3 a:visited,h4 a:visited,h5 a:visited,h6 a:visited{color:purple !important;}table td, table th{border-collapse:collapse;line-height:1.25;}table{border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;}a{color:blue;}

        /* MOBILE TARGETING
        ================================================*/
        @media only screen and(max-device-width:480px){a[href^="tel"],a[href^="sms"]{text-decoration:none;color:blue;pointer-events:none;cursor: default;}.mobile_link a[href^="tel"],.mobile_link a[href^="sms"]{text-decoration:default;color:orange !important;pointer-events:auto;cursor:default;}}
        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {a[href^="tel"],a[href^="sms"]{text-decoration:none;color:blue;pointer-events:none;cursor:default;}.mobile_link a[href^="tel"],.mobile_link a[href^="sms"]{text-decoration:default;color:orange !important;pointer-events:auto;cursor:default;}}
        @media only screen and (-webkit-min-device-pixel-ratio: 2) {
        /* Put your iPhone 4g styles in here */
        }
        /* Android targeting */
        @media only screen and (-webkit-device-pixel-ratio:.75){
            /* Put CSS for low density (ldpi) Android layouts in here */
        }
        @media only screen and (-webkit-device-pixel-ratio:1){
            /* Put CSS for medium density (mdpi) Android layouts in here */
        }
        @media only screen and (-webkit-device-pixel-ratio:1.5){
            /* Put CSS for high density (hdpi) Android layouts in here */
        }

    </style>

    <!--[if IEMobile 7]>
    <style type="text/css">

    </style>
    <![endif]-->

    <!--[if gte mso 9]>
    <style type="text/css">
        /* Target Outlook 2007 and 2010 */
    </style>
    <![endif]-->
</head>
<body>
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
        <tr>
            <td style="padding-left: 25px; padding-right: 25px; padding-bottom: 25px;" valign="top">

                @yield('content')

            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Artdevue">
    <link rel="icon" href="{{ asset('ico/favicon-32x32.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('ico/favicon-32x32.png') }}" type="image/x-icon">
    <title>{{ $title ?? '' }} - {{ config('app.name', 'Gamesgo') }}</title>
    <style type="text/css">
        body{
            width: 650px;
            background-color: #f6f7fb;
            display: block;
        }
        a{
            text-decoration: none;
        }
        span {
            font-size: 14px;
        }
        p {
            font-size: 13px;
            line-height: 1.7;
            letter-spacing: 0.7px;
            margin-top: 0;
        }
        .text-center{
            text-align: center
        }
        h6 {
            font-size: 16px;
            margin: 0 0 18px 0;
        }
    </style>
</head>
<body style="margin: 30px auto;">
<table style="width: 100%">
    <tbody>
    <tr>
        <td>
            <table style="background-color: #f6f7fb; width: 100%">
                <tbody>
                <tr>
                    <td>
                        <table style="width: 650px; margin: 0 auto; margin-bottom: 30px">
                            <tbody>
                            <tr>
                                <td><img src="{{ asset('images/logo_black.png') }}" alt="{{ config('app.name', 'Gamesgo') }}"></td>
                                {{--<td style="text-align: right; color:#999"><span>{{ $title ?? '' }}</span></td>--}}
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table style="width: 100%">
                <tbody>
                <tr>
                    <td>
                        <table style="width: 650px; margin: 0 auto; background-color: #fff; border-radius: 8px">
                            <tbody>
                            <tr>
                                <td style="padding: 30px">
                                    @yield('content')
                                    {{--<p style="margin-bottom: 0">
                                        {{ $lang['regards'] }},<br>{{ config('app.name', 'Gamesgo') }}</p>--}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            {{--<table style="width: 650px; margin: 0 auto; margin-top: 30px">
                <tbody>
                <tr style="text-align: center">
                    <td>
                        <a style="color: #999; margin-bottom: 0" href="{{ LaravelLocalization::localizeUrl('/') }}">{{ config('app.name', 'Gamesgo') }}</a>
                        <p style="color: #999; margin-bottom: 0">Powered By {{ config('app.name', 'Gamesgo') }}</p>
                    </td>
                </tr>
                </tbody>
            </table>--}}
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
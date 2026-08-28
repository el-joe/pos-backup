<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; background-color: #f9f9f9; }
        .body-content { line-height: 1.6; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 15px; }
        .footer a { color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="body-content">
            {!! $body !!}
        </div>

        <div class="footer">
            {{ config('app.name') }}
            @if ($unsubscribeUrl)
                <br>
                <a href="{{ $unsubscribeUrl }}">Unsubscribe from this newsletter</a>
            @endif
        </div>
    </div>
</body>
</html>

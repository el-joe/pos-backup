<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Us Message</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; background-color: #f9f9f9; }
        h2 { color: #007BFF; }
        p { line-height: 1.6; }
        .label { font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Contact Us Message</h2>
        <p><span class="label">Name:</span> {{ $name }}</p>
        <p><span class="label">Email:</span> {{ $email }}</p>
        <p><span class="label">Phone:</span> {{ $phone }}</p>
        <p><span class="label">Message:</span><br> {{ $messageText }}</p>
        <p><span class="label">Received at:</span> {{ $created_at }}</p>

        <div class="footer">
            This message was sent from your website contact form.<br>
            {{ config('app.name') }}
        </div>
    </div>
</body>
</html>

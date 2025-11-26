<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registration Request Received</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
    <tr>
      <td align="center">

        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 18px rgba(16,24,40,0.08);">

          <!-- Header -->
          <tr>
            <td style="padding:20px 24px;border-bottom:1px solid #eef2f6;">
              <span style="font-size:20px;color:#0f172a;font-weight:bold;">
                {{env('APP_NAME','POS System')}}
              </span>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:28px 32px;">
              <h1 style="margin:0 0 12px 0;font-size:20px;color:#0f172a;">
                Registration Request Received
              </h1>

              <p style="margin:0 0 18px 0;color:#334155;line-height:1.6;">
                Hello <strong>{{$name}}</strong>,<br>
                Thank you for your interest in joining our POS system. We have successfully received your registration request, and it is currently under review.
              </p>

              <p style="margin:0 0 12px 0;color:#475569;line-height:1.6;font-weight:bold;">
                What happens next:
              </p>

              <ul style="margin:0 0 18px 20px;color:#475569;line-height:1.6;">
                <li>Our team will review your submitted details.</li>
                <li>You will receive an email once your request is approved.</li>
                <li>After approval, you will be able to access your POS system account.</li>
              </ul>

              <p style="margin:0;color:#475569;line-height:1.6;">
                If you have any questions, feel free to contact us at
                <a href="mailto:{{env('MAIL_FROM_ADDRESS','support@example.com')}}" style="color:#2563eb;text-decoration:none;">{{env('MAIL_FROM_ADDRESS','support@example.com')}}</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:18px 32px;border-top:1px solid #eef2f6;background:#fbfdff;color:#6b7280;font-size:13px;">
              <strong style="color:#0f172a;">{{env('APP_NAME','POS System')}}</strong><br>
              Thank you for choosing our POS system.
            </td>
          </tr>

        </table>

        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="margin-top:12px;">
          <tr>
            <td style="font-size:12px;color:#94a3b8;text-align:center;">
              This is an automated message — please do not reply directly. © {{env('APP_NAME','POS System')}}
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>
</html>

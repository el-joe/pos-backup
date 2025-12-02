<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Account Approved</title>
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
                {{$name}}
              </span>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:28px 32px;">
              <h1 style="margin:0 0 12px 0;font-size:20px;color:#0f172a;">
                Your Account Has Been Approved
              </h1>

              <p style="margin:0 0 18px 0;color:#334155;line-height:1.6;">
                Hello <strong>{{$name}}</strong>,<br>
                We are pleased to inform you that your registration request for our system has been approved. You can now access your account and begin using the system.
              </p>

              <p style="margin:0 0 12px 0;color:#475569;line-height:1.6;">
                To log in to your account, please use the button below:
              </p>

              <!-- Login Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td>
                    <a href="{{'http://' . $domain}}"
                       style="display:inline-block;padding:12px 22px;border-radius:6px;background:#2563eb;color:#ffffff;text-decoration:none;font-weight:600;">
                      Login to Your Account
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 18px 0;color:#475569;line-height:1.6;">
                Or copy and paste this link into your browser:<br>
                <a href="{{'http://' . $domain}}" style="color:#2563eb;text-decoration:none;">{{'http://' . $domain}}</a>
              </p>

              <p style="margin:0;color:#475569;line-height:1.6;">
                If you need assistance, feel free to reach out at
                <a href="mailto:{{env('MAIL_FROM_ADDRESS','support@example.com')}}" style="color:#2563eb;text-decoration:none;">{{env('MAIL_FROM_ADDRESS','support@example.com')}}</a>.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:18px 32px;border-top:1px solid #eef2f6;background:#fbfdff;color:#6b7280;font-size:13px;">
              <strong style="color:#0f172a;">{{env('APP_NAME','POS System')}}</strong><br>
              We're excited to have you onboard.
            </td>
          </tr>

        </table>

        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="margin-top:12px;">
          <tr>
            <td style="font-size:12px;color:#94a3b8;text-align:center;">
              This is an automated message — please do not reply directly. © {{date('Y')}} {{env('APP_NAME','POS System')}}. All rights reserved.
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>
</html>

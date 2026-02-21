<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>New User Registration</title>
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
                {{env('APP_NAME')}} Admin
              </span>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:28px 32px;">
              <h1 style="margin:0 0 12px 0;font-size:20px;color:#0f172a;">
                New Registration Request
              </h1>

              <p style="margin:0 0 18px 0;color:#334155;line-height:1.6;">
                Hello Admin,<br>
                A new user has registered and is waiting for approval. Here are the details:
              </p>

              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 18px 0;width:100%;">
                <tr>
                  <td style="padding:6px 0;color:#475569;font-weight:bold;width:150px;">Request ID:</td>
                  <td style="padding:6px 0;color:#334155;">{{$id}}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#475569;font-weight:bold;width:150px;">Name:</td>
                  <td style="padding:6px 0;color:#334155;">{{$name}}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#475569;font-weight:bold;">Email:</td>
                  <td style="padding:6px 0;color:#334155;">{{$email}}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#475569;font-weight:bold;">Phone:</td>
                  <td style="padding:6px 0;color:#334155;">{{$phone}}</td>
                </tr>
                <tr>
                  <td style="padding:6px 0;color:#475569;font-weight:bold;">Registered at:</td>
                  <td style="padding:6px 0;color:#334155;">{{$created_at}}</td>
                </tr>
              </table>

              <p style="margin:0 0 18px 0;color:#475569;line-height:1.6;">
                You can review and approve the request from the admin panel.
              </p>


              <p style="margin:0;color:#475569;line-height:1.6;">
                Please review the request at your earliest convenience.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:18px 32px;border-top:1px solid #eef2f6;background:#fbfdff;color:#6b7280;font-size:13px;">
              <strong style="color:#0f172a;">{{env('APP_NAME')}}</strong><br>
              Automated system notification — no action required from sender.
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Account details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#f0fdf4;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;padding:32px 24px;">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(15,23,42,0.06);">

         
            <div style="height:6px;background:#16A34A;"></div>

            <div style="padding:28px 32px 20px;text-align:center;border-bottom:1px solid #f1f5f9;">
                <img src="{{ $logoCid }}" alt="OPOL Primary Healthcare Facility" width="64" height="64" style="display:block;margin:0 auto 14px;border-radius:12px;">

                <div style="font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#64748b;">
                    OPOL Primary Healthcare Facility
                </div>

                <div style="margin-top:10px;font-size:22px;font-weight:800;color:#16A34A;">
                    Your account details
                </div>

                <div style="margin-top:8px;font-size:13px;color:#475569;line-height:1.6;max-width:420px;margin-left:auto;margin-right:auto;">
                    Use these credentials to sign in. You will be asked to change your password on first login.
                </div>
            </div>

        
            <div style="padding:24px 32px;">
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:18px 20px;">
                    <div style="font-size:11px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#16A34A;margin-bottom:10px;">
                        Login credentials
                    </div>
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                        <tr>
                            <td style="padding:6px 0;color:#64748b;width:150px;">Email</td>
                            <td style="padding:6px 0;font-weight:700;color:#0f172a;">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td style="padding:6px 0;color:#64748b;">Temporary password</td>
                            <td style="padding:6px 0;font-weight:700;color:#0f172a;">{{ $plainPassword }}</td>
                        </tr>
                    </table>
                </div>

              
                <div style="margin-top:20px;text-align:center;">
                    <a href="{{ url('/webadmin-login') }}" style="display:inline-block;background:#16A34A;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:10px;font-weight:700;font-size:14px;">
                        Sign in to your account
                    </a>
                </div>

                <div style="margin-top:22px;padding-top:18px;border-top:1px solid #f1f5f9;font-size:12px;color:#64748b;line-height:1.6;">
                    If you did not expect this email, you can safely ignore it. For your security, please do not share this password with anyone.
                </div>
            </div>

         
            <div style="padding:16px 32px;background:#f8fafc;border-top:1px solid #f1f5f9;text-align:center;">
                <div style="font-size:11px;color:#94a3b8;">
                    This email was sent by the OPOL &ndash; MHO System.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
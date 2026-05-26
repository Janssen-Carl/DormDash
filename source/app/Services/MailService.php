<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Send an email using PHPMailer via configured SMTP server.
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $altBody
     * @return bool
     */
    public static function send($to, $subject, $body, $altBody = '')
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP Server Settings
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $username = env('MAIL_USERNAME', '');
            $password = env('MAIL_PASSWORD', '');
            $mail->SMTPAuth   = $username !== '' && $password !== '';
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', ''); // tls or ssl
            $mail->Port       = env('MAIL_PORT', 587);

            // SMTPOptions to prevent SSL handshake issues on some systems
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            // Recipients
            $fromAddress = env('MAIL_FROM_ADDRESS', 'official.hirehub.01@gmail.com');
            $fromName    = env('MAIL_FROM_NAME', 'DormDash');
            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error("MailService PHPMailer Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate the verification email HTML.
     */
    public static function getVerificationTemplate($username, $verifyUrl)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Verify your DormDash account</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
                .email-container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05); border: 1px border #e5e7eb; }
                .header-gradient { height: 8px; background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); }
                .content-padding { padding: 48px; }
                .logo-container { width: 56px; height: 56px; background-color: #ecfdf5; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
                .logo-icon { color: #059669; font-size: 24px; font-weight: bold; line-height: 56px; text-align: center; width: 100%; }
                h1 { color: #111827; font-size: 24px; font-weight: 800; margin-top: 0; margin-bottom: 8px; }
                .subtitle { color: #6b7280; font-size: 14px; margin-bottom: 32px; }
                p { color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
                .btn-container { text-align: center; margin-top: 32px; margin-bottom: 32px; }
                .btn { display: inline-block; background: linear-gradient(135deg, #059669 0%, #0d9488 100%); color: #ffffff !important; font-weight: 700; font-size: 16px; padding: 14px 32px; text-decoration: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(16,185,129,0.2); transition: all 0.2s ease; }
                .divider { border-top: 1px solid #f3f4f6; margin-top: 32px; margin-bottom: 24px; }
                .footer { color: #9ca3af; font-size: 12px; line-height: 1.5; }
                .footer a { color: #059669; text-decoration: none; font-weight: 600; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header-gradient"></div>
                <div class="content-padding">
                    <div class="logo-container">
                        <div class="logo-icon">D</div>
                    </div>
                    <h1>Verify Your Vendor Account</h1>
                    <div class="subtitle">Complete your registration process on DormDash</div>
                    
                    <p>Hi <strong>' . htmlspecialchars($username) . '</strong>,</p>
                    <p>Thank you for registering as a vendor on DormDash! To get your shop online and ready to receive orders, please verify your email address by clicking the button below.</p>
                    
                    <div class="btn-container">
                        <a href="' . $verifyUrl . '" class="btn">Verify Email Address</a>
                    </div>
                    
                    <p>After your email is verified, our administrative team will review your application to approve your vendor dashboard. You will receive another notification once your account becomes active.</p>
                    
                    <p style="font-size: 14px; color: #9ca3af;">If you did not create a DormDash account, you can safely ignore this email.</p>
                    
                    <div class="divider"></div>
                    <div class="footer">
                        <p>Questions? Visit our <a href="#">Merchant Help Center</a> or reply directly to this email.<br>
                        &copy; ' . date('Y') . ' DormDash. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';
    }

    /**
     * Generate the password reset email HTML.
     */
    public static function getPasswordResetTemplate($username, $resetUrl)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset your DormDash password</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
                .email-container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
                .header-gradient { height: 8px; background: linear-gradient(135deg, #ef4444 0%, #f59e0b 100%); }
                .content-padding { padding: 48px; }
                .logo-container { width: 56px; height: 56px; background-color: #fef2f2; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
                .logo-icon { color: #dc2626; font-size: 24px; font-weight: bold; line-height: 56px; text-align: center; width: 100%; }
                h1 { color: #111827; font-size: 24px; font-weight: 800; margin-top: 0; margin-bottom: 8px; }
                .subtitle { color: #6b7280; font-size: 14px; margin-bottom: 32px; }
                p { color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
                .btn-container { text-align: center; margin-top: 32px; margin-bottom: 32px; }
                .btn { display: inline-block; background: linear-gradient(135deg, #dc2626 0%, #ea580c 100%); color: #ffffff !important; font-weight: 700; font-size: 16px; padding: 14px 32px; text-decoration: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(220,38,38,0.2); transition: all 0.2s ease; }
                .warning-box { background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-bottom: 24px; }
                .warning-box p { color: #b45309; font-size: 14px; margin: 0; line-height: 1.5; }
                .divider { border-top: 1px solid #f3f4f6; margin-top: 32px; margin-bottom: 24px; }
                .footer { color: #9ca3af; font-size: 12px; line-height: 1.5; }
                .footer a { color: #dc2626; text-decoration: none; font-weight: 600; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header-gradient"></div>
                <div class="content-padding">
                    <div class="logo-container">
                        <div class="logo-icon">🔑</div>
                    </div>
                    <h1>Reset Your Password</h1>
                    <div class="subtitle">Secure account recovery request</div>
                    
                    <p>Hi <strong>' . htmlspecialchars($username) . '</strong>,</p>
                    <p>We received a request to reset the password for your DormDash account. No changes have been made yet. You can reset your password by clicking the button below:</p>
                    
                    <div class="btn-container">
                        <a href="' . $resetUrl . '" class="btn">Reset Password</a>
                    </div>
                    
                    <div class="warning-box">
                        <p><strong>Note:</strong> This link is secure and will expire in 60 minutes. It can only be used once.</p>
                    </div>
                    
                    <p style="font-size: 14px; color: #9ca3af;">If you did not request a password reset, you can safely ignore this email; your password will remain completely secure.</p>
                    
                    <div class="divider"></div>
                    <div class="footer">
                        <p>Need assistance? Contact our <a href="#">Security Team</a> or reply directly to this email.<br>
                        &copy; ' . date('Y') . ' DormDash. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';
    }
}

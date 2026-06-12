<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inheritance Report – <?php echo e($deceased_name); ?></title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a5fb4, #2d7ad6);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .body {
            background-color: white;
            padding: 30px;
            border-left: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
        }
        .share-info {
            background-color: #f0f7ff;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #1a5fb4;
        }
        .button-wrap {
            text-align: center;
            margin: 25px 0;
        }
        .button {
            background-color: #003871;
            color: white !important;
            padding: 14px 35px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
        }
        .video-section {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
        .security-notice {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
        .security-notice ul {
            margin: 10px 0 0;
            padding-left: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border: 1px solid #dddddd;
            border-top: none;
            border-radius: 0 0 10px 10px;
            font-size: 12px;
            color: #888888;
        }
        .footer p {
            margin: 0;
        }
        .footer .copy {
            margin-top: 5px;
            font-size: 11px;
            color: #aaaaaa;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>Neo Faraid</h1>
            <p>Inheritance Report</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p>Dear Valued User,</p>
            <p>
                The inheritance distribution report for <strong><?php echo e($deceased_name); ?></strong> has been approved and is now available for your secure viewing.
            </p>

            <!-- Optional share info (if applicable) -->
            <?php if(!empty($share_percentage)): ?>
            <div class="share-info">
                <p style="margin: 0;"><strong>Your Designated Share:</strong> <?php echo e($share_percentage); ?>% of the distributable estate</p>
            </div>
            <?php endif; ?>

            <p>Use the button below to access your report:</p>

            <div class="button-wrap">
                <a href="<?php echo e($report_url); ?>" class="button">View Inheritance Report</a>
            </div>

            <p>Or copy and paste the following link into your browser:</p>
            <p>
                <a href="<?php echo e($report_url); ?>" style="word-break: break-all;"><?php echo e($report_url); ?></a>
            </p>

            <!-- Will video section (if applicable) -->
            <?php if(!empty($video_url)): ?>
            <div class="video-section">
                <p style="margin: 0;"><strong>📹 Will Video Available</strong></p>
                <p style="margin: 5px 0 0;">The deceased has recorded a video will. You can view it after accessing your report.</p>
            </div>
            <?php endif; ?>

            <!-- Security Notice -->
            <div class="security-notice">
                <p style="margin: 0;"><strong>⚠️ Important Security Notes:</strong></p>
                <ul>
                    <li>This link is for your personal use only. <strong>Do not share it.</strong></li>
                    <li>The link will expire on <strong><?php echo e(now()->addDays($expiry_days)->format('d F Y')); ?></strong> (<?php echo e($expiry_days); ?> days from now).</li>
                    <li>All access to this link is logged for security purposes.</li>
                </ul>
            </div>

            <p>If you did not request this report, please ignore this email.</p>
            <p>
                Thank you,<br>
                <strong>Neo Faraid Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated message from Neo Faraid. Please do not reply to this email.</p>
            <p class="copy">&copy; <?php echo e(date('Y')); ?> Neo Faraid. All rights reserved.</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/emails/report-link.blade.php ENDPATH**/ ?>
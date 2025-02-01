<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .content {
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Our Service</h1>
    </div>
    <div class="content">
        <p>Dear <?php echo e($data['name']); ?>,</p>
        <p>Thank you for joining our platform. Here is your information:</p>
        <ul>
            <li>Email: <?php echo e($data['email']); ?></li>
            <li>Phone: <?php echo e($data['phone']); ?></li>
        </ul>
        <p>Feel free to reach out for more details.</p>
    </div>
    <div class="footer">
        <p>&copy; 2025 Your Company</p>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\pmsotech\resources\views/emails/custom_email.blade.php ENDPATH**/ ?>
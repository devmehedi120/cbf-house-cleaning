<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Booking Confirmed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e7e7e7;
        }
        .content {
            padding: 20px;
        }
        .booking-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e7e7e7;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Booking Confirmed</h2>
    </div>
    
    <div class="content">
        <p>Hello Admin,</p>
        <p>A new booking has been confirmed with the following details:</p>
        
        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Full Name:</span>
                <?php echo esc_html($booking_data['fullName']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Service:</span>
                <?php echo esc_html($booking_data['serviceType']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <?php echo esc_html($booking_data['date']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time Slot:</span>
                <?php echo esc_html($booking_data['timeSlot']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <?php echo esc_html($booking_data['phone']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <?php echo esc_html($booking_data['email']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Address:</span>
                <?php echo esc_html($booking_data['address']); ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Special Requests:</span>
                <?php echo esc_html($booking_data['specialRequests']); ?>
            </div>
        </div>
        
        <p>Please take appropriate action for this booking.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification. Please do not reply to this email.</p>
        <p>&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved.</p>
    </div>
</body>
</html>
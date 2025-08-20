<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ticket Assignment</title>
</head>

<body>
    <p>Dear <?php echo htmlspecialchars($assigneeName ?: 'Colleague'); ?>,</p>

    <p>You have been assigned to
        <strong>Ticket #<?php echo htmlspecialchars($ticket_id); ?></strong>.
    </p>

    <ul>
        <li><strong>Priority:</strong> <?php echo htmlspecialchars($priority); ?></li>
        <li><strong>Category:</strong>
            <?php echo htmlspecialchars($categoryName ?: ('Category #' . $category)); ?>
        </li>
    </ul>

    <p>Please log in to the system and review the ticket.</p>

    <p>Regards,<br><?php echo htmlspecialchars(SMTP_FROM_NAME); ?></p>
</body>

</html>
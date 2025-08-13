<?php
return "
<html>
        <body>
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px;'>
                    <h2 style='color: #007bff; margin-bottom: 20px;'>New Message on Ticket #$ticket_id</h2>
                    <p style='font-size: 16px; line-height: 1.6; color: #333;'>You have received a new message:</p>
                    <div style='background-color: white; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0;'>
                        <p style='font-style: italic; color: #666; margin: 0;'>\"$message\"</p>
                    </div>
                    <p style='font-size: 14px; color: #666;'>Reply to continue the conversation.</p>
                    <hr style='border: none; border-top: 1px solid #dee2e6; margin: 20px 0;'>
                    <p style='font-size: 12px; color: #999;'>This is an automated notification from Sadaharitha IT Helpdesk.</p>
                </div>
            </div>
        </body>
        </html>";
?>
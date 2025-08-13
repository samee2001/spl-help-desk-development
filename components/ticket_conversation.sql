-- Create table for ticket conversations
CREATE TABLE IF NOT EXISTS `tb_ticket_conversation` (
  `conv_id` int(11) NOT NULL AUTO_INCREMENT,
  `tk_id` varchar(50) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`conv_id`),
  KEY `tk_id` (`tk_id`),
  KEY `sender_email` (`sender_email`),
  KEY `receiver_email` (`receiver_email`),
  KEY `sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 
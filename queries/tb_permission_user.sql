

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table structure for table `tb_permission_user`
--

CREATE TABLE `tb_permission_user` (
  `permission_user_id` int(10) NOT NULL,
  `ur_id` int(20) NOT NULL,
  `permission_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_permission_user`
--

INSERT INTO `tb_permission_user` (`permission_user_id`, `ur_id`, `permission_id`) VALUES
(1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_permission_user`
--
ALTER TABLE `tb_permission_user`
  ADD PRIMARY KEY (`permission_user_id`),
  ADD KEY `ur_id` (`ur_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_permission_user`
--
ALTER TABLE `tb_permission_user`
  MODIFY `permission_user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_permission_user`
--
ALTER TABLE `tb_permission_user`
  ADD CONSTRAINT `tb_permission_user_ibfk_1` FOREIGN KEY (`ur_id`) REFERENCES `tb_user` (`ur_id`),
  ADD CONSTRAINT `tb_permission_user_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `tb_permissions` (`permission_id`);
COMMIT;



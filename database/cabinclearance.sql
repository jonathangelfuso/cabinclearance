-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2017 at 09:40 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cabinclearance`
--

-- --------------------------------------------------------

--
-- Table structure for table `overrides`
--

CREATE TABLE `overrides` (
  `id` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `name` varchar(65) NOT NULL,
  `period` varchar(10) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `overrides`
--

INSERT INTO `overrides` (`id`, `vendorid`, `name`, `period`, `type`) VALUES
(1, 1, 'rccl q1 vvi', 'q1', 'revenue'),
(2, 1, 'rccl q1 g2', 'q1', 'passengers'),
(3, 2, 'pcl q1 revenue', 'q1', 'revenue'),
(4, 1, 'rccl q2 vvi', 'q2', 'revenue');

-- --------------------------------------------------------

--
-- Stand-in structure for view `overridesview`
-- (See below for the actual view)
--
CREATE TABLE `overridesview` (
`overrideName` varchar(65)
,`vendorName` varchar(65)
,`level` int(11)
,`payout` decimal(20,3)
,`goal` decimal(20,2)
,`primaryKey` int(11)
,`foreignKey` int(11)
,`sold` decimal(30,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `overridesview2`
-- (See below for the actual view)
--
CREATE TABLE `overridesview2` (
`sold` decimal(30,2)
,`goal` decimal(20,2)
,`payout` decimal(20,3)
,`vendorname` varchar(65)
,`overridename` varchar(65)
,`period` varchar(10)
,`level` int(11)
,`primarykey` int(11)
,`payouttype` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `overridesview3`
-- (See below for the actual view)
--
CREATE TABLE `overridesview3` (
`paxorrev` varchar(20)
,`sold` decimal(30,2)
,`goal` decimal(20,2)
,`payout` decimal(20,3)
,`vendorname` varchar(65)
,`overridename` varchar(65)
,`period` varchar(10)
,`level` int(11)
,`primarykey` int(11)
,`payouttype` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `overridetopearned`
-- (See below for the actual view)
--
CREATE TABLE `overridetopearned` (
`overridename` varchar(65)
,`vendorname` varchar(65)
,`level` int(11)
,`payout` decimal(20,3)
,`goal` decimal(20,2)
,`sold` decimal(30,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `period` varchar(10) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `period`, `vendorid`, `amount`, `type`) VALUES
(1, 'q1', 1, '200000.00', 'revenue'),
(2, 'q1', 1, '500.00', 'passengers'),
(3, 'q1', 2, '90000.00', 'revenue');

-- --------------------------------------------------------

--
-- Table structure for table `tiers`
--

CREATE TABLE `tiers` (
  `id` int(11) NOT NULL,
  `overrideid` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `payout_type` varchar(20) NOT NULL,
  `payout_amount` decimal(20,3) NOT NULL,
  `goal_amount` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tiers`
--

INSERT INTO `tiers` (`id`, `overrideid`, `level`, `payout_type`, `payout_amount`, `goal_amount`) VALUES
(1, 1, 1, 'percentage', '0.025', '100000.00'),
(2, 1, 2, 'percentage', '0.050', '150000.00'),
(3, 1, 3, 'percentage', '0.075', '200000.00'),
(4, 1, 4, 'percentage', '0.100', '300000.00'),
(5, 2, 1, 'base', '5000.000', '100.00'),
(6, 2, 2, 'base', '7500.000', '200.00'),
(7, 2, 3, 'base', '10000.000', '200.00'),
(8, 3, 1, 'base', '4562.000', '100000.00'),
(9, 3, 2, 'base', '8000.000', '200000.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `name` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`) VALUES
(1, 'RCCL'),
(2, 'PCL'),
(3, 'fuck you'),
(4, 'hello'),
(5, 'howaboutnow'),
(6, 'andnow'),
(7, 'asdkfn;alskndf'),
(8, 'pcl'),
(9, 'pcl'),
(10, 'pcl'),
(11, 'pcl');

-- --------------------------------------------------------

--
-- Structure for view `overridesview`
--
DROP TABLE IF EXISTS `overridesview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `overridesview`  AS  select `overrides`.`name` AS `overrideName`,`vendors`.`name` AS `vendorName`,`tiers`.`level` AS `level`,`tiers`.`payout_amount` AS `payout`,`tiers`.`goal_amount` AS `goal`,`overrides`.`id` AS `primaryKey`,`tiers`.`overrideid` AS `foreignKey`,`sales`.`amount` AS `sold` from (((`tiers` join `overrides` on((`tiers`.`overrideid` = `overrides`.`id`))) join `sales` on(((`overrides`.`vendorid` = `sales`.`vendorid`) and (`overrides`.`period` = `sales`.`period`) and (`sales`.`type` = `overrides`.`type`)))) join `vendors` on((`sales`.`vendorid` = `vendors`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `overridesview2`
--
DROP TABLE IF EXISTS `overridesview2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `overridesview2`  AS  select `sales`.`amount` AS `sold`,`tiers`.`goal_amount` AS `goal`,`tiers`.`payout_amount` AS `payout`,`vendors`.`name` AS `vendorname`,`overrides`.`name` AS `overridename`,`overrides`.`period` AS `period`,`tiers`.`level` AS `level`,`overrides`.`id` AS `primarykey`,`tiers`.`payout_type` AS `payouttype` from (((`tiers` join `overrides` on((`tiers`.`overrideid` = `overrides`.`id`))) join `sales` on(((`overrides`.`vendorid` = `sales`.`vendorid`) and (`overrides`.`period` = `sales`.`period`) and (`sales`.`type` = `overrides`.`type`)))) join `vendors` on((`sales`.`vendorid` = `vendors`.`id`))) order by `overrides`.`id`,`tiers`.`level` desc ;

-- --------------------------------------------------------

--
-- Structure for view `overridesview3`
--
DROP TABLE IF EXISTS `overridesview3`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `overridesview3`  AS  select `overrides`.`type` AS `paxorrev`,`sales`.`amount` AS `sold`,`tiers`.`goal_amount` AS `goal`,`tiers`.`payout_amount` AS `payout`,`vendors`.`name` AS `vendorname`,`overrides`.`name` AS `overridename`,`overrides`.`period` AS `period`,`tiers`.`level` AS `level`,`overrides`.`id` AS `primarykey`,`tiers`.`payout_type` AS `payouttype` from (((`tiers` join `overrides` on((`tiers`.`overrideid` = `overrides`.`id`))) join `sales` on(((`overrides`.`vendorid` = `sales`.`vendorid`) and (`overrides`.`period` = `sales`.`period`) and (`sales`.`type` = `overrides`.`type`)))) join `vendors` on((`sales`.`vendorid` = `vendors`.`id`))) order by `overrides`.`id`,`tiers`.`level` desc ;

-- --------------------------------------------------------

--
-- Structure for view `overridetopearned`
--
DROP TABLE IF EXISTS `overridetopearned`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `overridetopearned`  AS  select `overridesview`.`overrideName` AS `overridename`,`overridesview`.`vendorName` AS `vendorname`,max(`overridesview`.`level`) AS `level`,`overridesview`.`payout` AS `payout`,`overridesview`.`goal` AS `goal`,`overridesview`.`sold` AS `sold` from `overridesview` where (`overridesview`.`goal` < `overridesview`.`sold`) group by `overridesview`.`overrideName` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `overrides`
--
ALTER TABLE `overrides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendorid` (`vendorid`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendorid` (`vendorid`);

--
-- Indexes for table `tiers`
--
ALTER TABLE `tiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `overrideid` (`overrideid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `overrides`
--
ALTER TABLE `overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tiers`
--
ALTER TABLE `tiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `overrides`
--
ALTER TABLE `overrides`
  ADD CONSTRAINT `overrides_ibfk_1` FOREIGN KEY (`vendorid`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`vendorid`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `tiers`
--
ALTER TABLE `tiers`
  ADD CONSTRAINT `tiers_ibfk_1` FOREIGN KEY (`overrideid`) REFERENCES `overrides` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

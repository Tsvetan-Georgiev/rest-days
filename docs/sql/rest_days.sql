-- --------------------------------------------------------

--
-- Table structure for table `rest_days`
--

CREATE TABLE IF NOT EXISTS `rest_days` (
  `day` date NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

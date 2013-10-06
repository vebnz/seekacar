-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2013 at 06:17 PM
-- Server version: 5.1.70-cll
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`) VALUES
(1, 'AceRentals'),
(2, 'Jucy'),
(3, 'Britz'),
(4, 'Omega'),
(5, 'Apex'),
(6, 'Budget'),
(7, 'Thrifty'),
(8, 'Pegasus');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyID` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=181 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `companyID`, `city`, `code`) VALUES
(1, 1, 'Auckland Airport', '16'),
(2, 1, 'Auckland', '13'),
(3, 1, 'Christchurch Airport', '26'),
(4, 1, 'Dunedin', '18'),
(5, 1, 'Dunedin Airport', '28'),
(6, 1, 'Greymouth', '19'),
(7, 1, 'Picton', '15'),
(8, 1, 'Queenstown Airport', '27'),
(9, 1, 'Wellington', '1'),
(10, 2, 'Auckland Airport', '9'),
(11, 2, 'Auckland', '1'),
(12, 2, 'Christchurch Airport', '6'),
(13, 2, 'Dunedin Airport', '17'),
(14, 2, 'Queenstown Airport', '8'),
(15, 2, 'Blenheim', '5226'),
(16, 2, 'Blenheim Airport', '5247'),
(17, 2, 'Dunedin', '5228'),
(18, 2, 'Greymouth', '5229'),
(19, 2, 'Gisborne', '5235'),
(20, 2, 'Hamilton', '6529'),
(21, 2, 'Hamilton Airport', '6504'),
(22, 2, 'Hokitika Airport', '25848'),
(23, 2, 'Invercargill', '5230'),
(24, 2, 'Kerikeri Airport', '5236'),
(25, 2, 'Napier', '5216'),
(26, 2, 'Nelson', '5231'),
(27, 2, 'New Plymouth', '5217'),
(28, 2, 'New Plymouth Airport', '5237'),
(29, 2, 'Palmerston North', '1159'),
(30, 2, 'Palmerston North Airport', '5218'),
(31, 2, 'Picton', '51092'),
(32, 2, 'Rotorua', '5220'),
(33, 2, 'Rotorua Airport', '5239'),
(34, 2, 'Tauranga', '5222'),
(35, 2, 'Tauranga Airport', '27060'),
(36, 2, 'Taupo', '5221'),
(37, 2, 'Taupo Airport', '26692'),
(38, 2, 'Timaru', '27492'),
(39, 2, 'Wanganui', '5242'),
(40, 2, 'Wellington Ferry', '1167'),
(41, 2, 'Wellington Airport', '5244'),
(42, 2, 'Westport Airport', '151900'),
(43, 2, 'Whangarei', '5225'),
(44, 2, 'Whangarei Airport', '5246'),
(45, 2, 'Whakatane', '5224'),
(46, 2, 'Whakatane Airport', '5245'),
(47, 3, 'Auckland Airport', 'AIN'),
(49, 3, 'Auckland', 'ACI'),
(50, 3, 'Blenheim Airport', 'BDO'),
(51, 3, 'Christchurch Airport', 'CDO'),
(52, 3, 'Dunedin Airport', 'DDO'),
(53, 3, 'Greymouth', 'GRA'),
(54, 3, 'Hamilton Airport', 'HDO'),
(55, 3, 'Napier Airport', 'NDA'),
(56, 3, 'Nelson Airport', 'NDO'),
(57, 3, 'Picton', 'PFE'),
(58, 3, 'Queenstown Airport', 'QDO'),
(59, 3, 'Rotorua Airport', 'RDO'),
(60, 3, 'Rotorua', 'RCI'),
(61, 3, 'Wellington Airport', 'WDO'),
(62, 3, 'Wellington Ferry', 'WFE'),
(63, 4, 'Auckland Airport', '991'),
(64, 4, 'Auckland', '2702'),
(65, 4, 'Blenheim', '974'),
(66, 4, 'Christchurch Airport', '1003'),
(67, 4, 'Christchurch', '2813'),
(68, 4, 'Nelson Airport', '1000'),
(69, 4, 'Picton', '968'),
(71, 4, 'Queenstown', '983'),
(72, 4, 'Queenstown Airport', '1005'),
(73, 4, 'Wellington Airport', '998'),
(74, 4, 'Wellington', '2814'),
(75, 4, 'Wellington Ferry', '1001'),
(76, 5, 'Auckland Airport', '991'),
(78, 5, 'Auckland', '990'),
(79, 5, 'Wellington Airport', '998'),
(80, 5, 'Wellington', '987'),
(81, 5, 'Wellington Ferry', '1001'),
(82, 5, 'Picton', '968'),
(84, 5, 'Blenheim Airport', '974'),
(85, 5, 'Nelson Airport', '1000'),
(86, 5, 'Nelson', '969'),
(87, 5, 'Christchurch Airport', '1003'),
(88, 5, 'Christchurch', '970'),
(89, 5, 'Greymouth', '989'),
(90, 5, 'Queenstown Airport', '1005'),
(91, 5, 'Queenstown', '983'),
(92, 6, 'Auckland Airport', 'AKL'),
(93, 6, 'Auckland', 'AK3'),
(97, 6, 'Blenheim Airport', 'BHE'),
(98, 6, 'Christchurch Airport', 'CHC'),
(99, 6, 'Dunedin Airport', 'DUD'),
(100, 6, 'Dunedin', 'DD1'),
(101, 6, 'Gisborne Airport', 'GIS'),
(102, 6, 'Greymouth', 'Q1A'),
(103, 6, 'Hamilton Airport', 'HLZ'),
(104, 6, 'Hamilton', 'HL1'),
(105, 6, 'Hokitika Airport', 'HKK'),
(106, 6, 'Invercargill Airport', 'IVC'),
(107, 6, 'Kerikeri Airport', 'KKE'),
(108, 6, 'Kerikeri', 'KK1'),
(109, 6, 'Napier Airport', 'NPE'),
(110, 6, 'Nelson Airport', 'NSN'),
(111, 6, 'New Plymouth Airport', 'NPL'),
(112, 6, 'New Plymouth', 'NL1'),
(113, 6, 'Palmerston North Airport', 'PMR'),
(115, 6, 'Queenstown Airport', 'ZQN'),
(116, 6, 'Queenstown', 'ZQ1'),
(117, 6, 'Rotorua Airport', 'ROT'),
(118, 6, 'Rotorua', 'RO1'),
(119, 6, 'Taupo Airport', 'TUO'),
(120, 6, 'Taupo', 'NZ8'),
(121, 6, 'Tauranga Airport', 'TRG'),
(122, 6, 'Wanganui Airport', 'WAG'),
(123, 6, 'Wanganui', 'WA1'),
(124, 6, 'Wellington Airport', 'WLG'),
(125, 6, 'Wellington', 'WL3'),
(126, 6, 'Wellington Ferry', 'WL4'),
(128, 6, 'Whakatane Airport', 'WHK'),
(129, 6, 'Whakatane', 'WH2'),
(130, 6, 'Whangarei Airport', 'WRE'),
(131, 6, 'Whangarei', 'WR1'),
(133, 7, 'Auckland Airport', 'AK3'),
(134, 7, 'Auckland', 'AK2'),
(135, 7, 'Hamilton Airport', 'HL1'),
(136, 7, 'Hamilton', 'HL2'),
(137, 7, 'Napier Airport', 'NA1'),
(138, 7, 'New Plymouth Airport', 'NP1'),
(139, 7, 'Palmerston North Airport', 'PM1'),
(140, 7, 'Rotorua Airport', 'RO1'),
(141, 7, 'Taupo Airport', 'TU1'),
(142, 7, 'Tauranga Airport', 'TG1'),
(143, 7, 'Wellington Airport', 'WL1'),
(144, 7, 'Wellington', 'WL2'),
(145, 7, 'Wellington Ferry', 'WL4'),
(146, 7, 'Blenheim Airport', 'BH1'),
(147, 7, 'Christchurch Airport', 'CH1'),
(148, 7, 'Dunedin Airport', 'DU1'),
(149, 7, 'Dunedin', 'DU2'),
(150, 7, 'Greymouth', 'GR5'),
(151, 7, 'Hokitika', 'HK1'),
(152, 7, 'Invercargill Airport', 'IV1'),
(153, 7, 'Invercargill', 'IV2'),
(154, 7, 'Nelson Airport', 'NS1'),
(156, 7, 'Queenstown Airport', 'ZQ1'),
(157, 7, 'Queenstown', 'ZQ2'),
(159, 8, 'Whangarei', '26'),
(160, 8, 'Auckland', '2'),
(161, 8, 'Auckland Airport', '1'),
(162, 8, 'Hamilton', '11'),
(163, 8, 'Tauranga', '23'),
(164, 8, 'Rotorua', '21'),
(165, 8, 'Taupo', '22'),
(166, 8, 'Napier', '13'),
(167, 8, 'New Plymouth', '15'),
(168, 8, 'Wanganui', '24'),
(169, 8, 'Palmerston North', '17'),
(170, 8, 'Wellington', '32'),
(171, 8, 'Picton', '18'),
(172, 8, 'Blenheim', '4'),
(173, 8, 'Nelson', '14'),
(174, 8, 'Christchurch', '6'),
(175, 8, 'Christchurch Airport', '5'),
(176, 8, 'Greymouth', '10'),
(177, 8, 'Queenstown', '19'),
(178, 8, 'Dunedin', '8'),
(179, 8, 'Dunedin Airport', '35'),
(180, 8, 'Invercargill', '12');

-- --------------------------------------------------------

--
-- T

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2025 at 03:26 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `investus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_investments`
--

CREATE TABLE `admin_investments` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `header_text` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `investment_type` varchar(50) NOT NULL,
  `company_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_investments`
--

INSERT INTO `admin_investments` (`id`, `company_name`, `description`, `image_url`, `header_text`, `created_at`, `investment_type`, `company_type`) VALUES
(9, 'Delta Airlines', 'Delta Air Lines is a company that provides passenger air travel solutions. It offers ticket booking, flight management, travel planning and assistance, airline maintenance, passenger and cargo transportation, and other services.', 'uploads/delta.jpg', 'Delta Airlines', '2025-03-30 14:31:53', 'stocks', 'Airlines'),
(11, 'Volkswagen', 'The Volkswagen Group, headquartered in Wolfsburg, is one of the world\'s leading manufacturers of automobiles and commercial vehicles and the largest carmaker in Europe. With our brands, business units and financial services, we are shaping the zero-emission and autonomous future of mobility.', 'uploads/automotive.jpg', 'Volkswagen', '2025-03-30 15:11:09', 'bonds', 'Automotive'),
(12, 'JPMorgan Chase', 'JPMorgan Chase & Co. is an American multinational financial services firm. It is headquartered in New York City and incorporated in Delaware. JPMorgan Chase & Co. is the largest bank in the United States and the world\'s largest bank by market capitalization as of 2023. The company offers consumer and commercial banking, investment banking, financial transaction processing, and asset management solutions through its subsidiaries. It operates through four segments: Consumer & Community Banking (CCB), Corporate & Investment Bank (CIB), Commercial Banking (CB), and Asset & Wealth Management (AWM).', 'uploads/banking.jpg', 'JPMorgan Chase', '2025-03-30 15:14:19', 'stocks', 'Banking'),
(13, 'Curaleaf', 'Curaleaf Holdings, Inc. is an American cannabis company publicly traded on the Canadian stock exchange. The company is headquartered in New York City. Founded in 2010, it produces and distributes cannabis products in North America, operating dispensaries in 19 states.', 'uploads/cannabis.jpg', 'Curaleaf', '2025-03-30 15:16:15', 'commodities', 'Cannabis'),
(15, 'Lucelec SLU', 'St. Lucia Electricity Services Limited (LUCELEC) is the only commercial generator, transmitter, distributor and seller of electrical energy in St. Lucia. We were established in 1964 and operate four Customer Service locations in Sans Souci, Rodney Bay, Vieux-Fort and Soufriere.', 'uploads/energy.jpg', 'Lucelec SLU', '2025-03-30 15:25:42', 'stocks', 'Energy'),
(16, 'Mars Chocolate', 'Mars, Incorporated is a global, family-owned company known for its diverse range of products, including confectionery, pet care, and food. Founded in 1911 by Franklin Clarence Mars, the company is headquartered in McLean, Virginia, USA. Mars is one of the largest privately held companies in the world, with annual revenue exceeding $50 billion as of 2023', 'uploads/food.jpg', 'Mars Chocolate', '2025-03-30 15:27:40', 'stocks', 'Food & Beverage'),
(17, 'UnitedHealth Group Inc.', 'At UnitedHealthcare, our mission is to help people live healthier lives and make the health system work better for everyone. We dedicate ourselves to this every day for our members by being there for what matters in moments big and small — from their earliest days, to their working years and through retirement.', 'uploads/health.jpg', 'UnitedHealth Group Inc.', '2025-03-30 15:29:36', 'stocks', 'Health Care'),
(18, 'Flow', 'We were the first to connect the Caribbean people, governments and businesses with best in class telecoms networks across broadband, fixed and mobile services. Today we remain a telecoms tour de force, unmatched in every market we serve in the Caribbean as the sole full service ‘go to’ provider.', 'uploads/flow.jpg', 'Flow', '2025-03-30 15:31:13', 'stocks', 'Internet'),
(19, 'Flow', 'We were the first to connect the Caribbean people, governments and businesses with best in class telecoms networks across broadband, fixed and mobile services. Today we remain a telecoms tour de force, unmatched in every market we serve in the Caribbean as the sole full service ‘go to’ provider.', 'uploads/flow.jpg', 'Flow', '2025-03-30 15:31:42', 'stocks', 'Telecom'),
(20, 'Facebook_Official', 'Facebook, now part of the company Meta Platforms, is a social media and social networking service. Founded in 2004 by Mark Zuckerberg and others, it connects people worldwide through its major products, including Facebook, Instagram, Oculus, Threads, Messenger, and WhatsApp', 'uploads/media.jpg', 'Facebook_Official', '2025-03-30 15:33:24', 'stocks', 'Media'),
(21, 'Worthington Industries', 'Worthington Industries, Inc., an industrial manufacturing company, focuses on value-added steel processing and manufactured metal products in North America and internationally. It operates through Steel Processing, Consumer Products, Building Products, and Sustainable Energy Solutions segments. The Steel Processing segment processes flat-rolled steel for customers primarily in the automotive, aerospace, agricultural, appliance, construction, container, hardware, heavy-truck, HVAC, lawn and garden, leisure and recreation, office furniture, and office equipment markets.', 'uploads/metals.jpg', 'Worthington Industries', '2025-03-30 15:35:29', 'stocks', 'Metals & Mining'),
(22, 'Johnson & Johnson', 'Johnson & Johnson, together with its subsidiaries, engages in the research and development, manufacture, and sale of various products in the healthcare field worldwide. It operates in two segments, Innovative Medicine and MedTech.', 'uploads/pharma.jpg', 'Johnson & Johnson', '2025-03-30 15:36:56', 'stocks', 'Pharma'),
(23, 'HomeServices of America', 'Headquartered in Edina, MN, HomeServices of America, a Berkshire Hathaway affiliate, is, through its operating companies, one of the country\'s premier providers of homeownership services, including brokerage, mortgage, franchising, title, escrow, insurance, and relocation services.', 'uploads/real.jpg', 'HomeServices of America', '2025-03-30 15:38:32', 'stocks', 'Real Estate/Construction'),
(24, 'Amazon.com Inc', 'Amazon.com, Inc. is an American multinational technology companythat focuses on e-commerce, cloud computing, online advertising, digital streaming, and artificial intelligence. The company provides a wide range of products, including apparel, electronics, grocery, music, sports goods, toys, and tools. Amazon offers personalized shopping services, Web-based credit card payment, and direct shipping to customers. The company operates through three segments: North America, International, and Amazon Web Services (AWS)', 'uploads/retail.jpg', 'Amazon.com Inc', '2025-03-30 15:39:28', 'stocks', 'Retail'),
(25, 'Adobe Inc', 'Adobe Inc., American developer of printing, publishing, and graphics software. Adobe was instrumental in the creation of the desktop publishing industry through the introduction of its PostScript printer language. Its headquarters are located in San Jose, California.', 'uploads/software2.jpg', 'Adobe Inc', '2025-03-30 15:44:20', 'stocks', 'Software'),
(26, 'Verizon Communications Inc.', 'Verizon Communications Inc., commonly known as Verizon, is a diversified multinational telecommunications conglomerate. Verizon is engaged in a range of communications segments, including 5G, wireless networks, broadband and fiber, media and technology, Internet of Things, and manager security.', 'uploads/telecom.jpg', 'Verizon Communications Inc.', '2025-03-30 15:46:31', 'stocks', 'Telecom'),
(27, 'Digicel', 'Digicel is a Caribbean-based mobile phone network and home entertainment provider operating in 25 markets worldwide. The company offers a range of services including mobile connectivity, home entertainment, business solutions, and cloud services. It has a presence in 33 countries across the Caribbean, Central America, and Pacific Islands, serving more than 8 million customers.', 'uploads/digicel5375.jpg', 'Digicel', '2025-03-30 15:47:35', 'stocks', 'Telecom'),
(28, 'Apple Inc.', 'Apple Inc. is an American multinational technology company headquartered in Cupertino, California, in Silicon Valley. It is best known for its consumer electronics, software, and services.', 'uploads/tech.jpg', 'Apple Inc.', '2025-03-30 15:48:51', 'stocks', 'Technology'),
(29, 'Moderna Inc.', 'Moderna, Inc. operates as a biotechnology company. The Company focuses on the discovery and development of messenger RNA therapeutics and vaccines. Moderna develops mRNA medicines for infectious, immuno-oncology, and cardiovascular diseases.', 'uploads/biotech.jpg', 'Moderna Inc.', '2025-03-30 15:54:37', 'stocks', 'Biotech'),
(30, 'Boeing', 'The Boeing Company is an American multinational corporation that designs, manufactures, and sells airplanes, rotorcraft, rockets, satellites, telecommunications equipment, and missiles worldwide. It is the largest aerospace company in the world. The company primarily designs, develops, and manufactures commercial and military aircraft, and also develops space, defense and security systems. The company operates through the following segments: Commercial Airplanes; Defense, Space and Security; Global Services; and Boeing Capital.', 'uploads/aero.jpg', 'Boeing', '2025-03-30 15:57:00', 'stocks', 'Aerospace & Defense'),
(33, 'Test Bonds', 'Bonds', 'uploads/digicel5375.jpg', 'Test Bonds', '2025-04-05 16:37:56', 'bonds', 'Aerospace & Defense'),
(34, 'Estate Test', 'teast estate', 'uploads/digicel5375.jpg', 'Estate Test', '2025-04-05 17:19:36', 'real_estate', 'Aerospace & Defense');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `registration_date`) VALUES
(2, 'Ajeff', '$2y$10$UzB1OUZ7gO1X3k5DBXANu.ils8ZDKBDVd7R7A6kDdMSg.96wER.XO', 'Ajeff@mail.com', '2025-02-19 16:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `bond_requests`
--

CREATE TABLE `bond_requests` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bond_requests`
--

INSERT INTO `bond_requests` (`id`, `company_name`, `file_path`, `submitted_at`) VALUES
(1, 'Digicel', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Bond_Request_1743871337.txt', '2025-04-05 16:42:17'),
(2, 'Test', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Bond_Request_1744599862.txt', '2025-04-14 03:04:22');

-- --------------------------------------------------------

--
-- Table structure for table `business_users`
--

CREATE TABLE `business_users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `business_users`
--

INSERT INTO `business_users` (`id`, `username`, `password`, `email`, `profile_picture`, `registration_date`, `status`, `created_at`) VALUES
(1, 'Digicel', '$2y$10$uRi2nfqgNR2.mbxaOKPML.I.xTkG8BhZNOqbRGgFjhD1qCZH0Xymm', 'Digicel@mail.com', 'uploads/digicel5375.jpg', '2025-03-05 12:19:27', 1, '2025-04-05 08:20:15'),
(2, 'Flow', '$2y$10$.91hxCH7mQSgYDY7ZaiDW.ZtnfsGLiycWmLTwHe6k6dQMuLJsZbyO', 'flow@mail.com', 'uploads/flow.jpg', '2025-03-22 13:32:51', 1, '2025-04-05 08:20:15'),
(3, 'Sir Author Community College', '$2y$10$uv3CN3SLRu3CAtoT6vX.ne4cdDLZ/74uBgKb7sD.U/jm4CKAoR8.S', 'SALCC@mail.com', 'uploads/salcclogo.jpg', '2025-03-29 11:08:58', 1, '2025-04-05 08:20:15'),
(4, 'Boeing', '$2y$10$6LKQzeUWJneO6ovpm8KZuuDNOMI24nA9TpwPOIs6x5uSDCkbFIWNe', 'boeing@mail.com', 'uploads/aero.jpg', '2025-03-30 12:41:48', 1, '2025-04-05 08:20:15'),
(5, 'Delta Airlines', '$2y$10$QU/ZLVPdkQBPpa.Wp5EKUeBaKJoO0KXXIXVEt5piMqM2uaPLVrI7K', 'delta@mail.com', 'uploads/delta.jpg', '2025-03-30 12:45:50', 1, '2025-04-05 08:20:15'),
(6, 'Volkswagen', '$2y$10$MSsmOjqepgmpHzsApuJh/OqzqPav0tT8gp2VsWMJGrYdgqrKgj2cm', 'volkswagen@mail.com', 'uploads/automotive.jpg', '2025-03-30 12:49:42', 1, '2025-04-05 08:20:15'),
(7, 'JPMorgan Chase', '$2y$10$/xITi1uccaKmCQLJrJok3edzzGc6/8Rz2Lm9NjHySnYn8T4TNQSD2', 'JP@mail.com', 'uploads/banking.jpg', '2025-03-30 12:54:09', 1, '2025-04-05 08:20:15'),
(8, 'Moderna Inc.', '$2y$10$F4ohobMh5eSYF1wMNzrQDupEbuME5IE8qHF9ZAq3EZKxqb/lOor46', 'moderna@mail.com', 'uploads/biotech.jpg', '2025-03-30 12:59:27', 1, '2025-04-05 08:20:15'),
(9, 'Curaleaf', '$2y$10$.PWCTyqpB3SHaO4dFF7YLOcopvlc9iVHlMQGA8vG0zF1KR1aGnHDq', 'curaleaf@mail.com', 'uploads/cannabis.jpg', '2025-03-30 13:04:09', 1, '2025-04-05 08:20:15'),
(10, 'Lucelec SLU', '$2y$10$PCkvW6oyPvPsRFhbehERK.QyncM.OEBlE3jCBwaySFAw6f70irzOu', 'lucelec@mail.com', 'uploads/energy.jpg', '2025-03-30 13:10:01', 1, '2025-04-05 08:20:15'),
(11, 'Mars Chocolate', '$2y$10$3crrEUPgOiP0bxQt35LhN.GR2LHaO4PSP0iucqn/hgPg7fx2sBtLq', 'Mars@mail.com', 'uploads/food.jpg', '2025-03-30 13:14:33', 1, '2025-04-05 08:20:15'),
(12, 'UnitedHealth Group Inc.', '$2y$10$d/FLg6QNKm6XJD/LFjns8uX6UOqD14jz/l1L/gaiQMnY.Xr71AXM6', 'unitedhealth@mail.com', 'uploads/health.jpg', '2025-03-30 13:18:57', 1, '2025-04-05 08:20:15'),
(13, 'Facebook_Official', '$2y$10$80m462A1STj3M17g3iMHyuF9.O0R1C2m55OojuH0m.CXyD3C/kSbG', 'facebook@mail.com', 'uploads/media.jpg', '2025-03-30 13:28:21', 1, '2025-04-05 08:20:15'),
(14, 'Worthington Industries', '$2y$10$67L3cEHDXimbP2NOQQc5iOTKOrh37snYy7wLJg07FDCHcOU1vrFG.', 'worthington@mail.com', 'uploads/metals.jpg', '2025-03-30 13:36:08', 1, '2025-04-05 08:20:15'),
(15, 'Johnson & Johnson', '$2y$10$jubfGBffCCCEJ8FLKib2HujrdSc1u3fgdHH7jm1FztY1t7GlnHeQq', 'johnson@mail.com', 'uploads/pharma.jpg', '2025-03-30 13:41:16', 1, '2025-04-05 08:20:15'),
(16, 'HomeServices of America', '$2y$10$SZlU2MwAwoCjp.Y9wCf8eeEyCs.xp1KkZAhnT7FRbLAwkeqCmjEEW', 'homeservices@mail.com', 'uploads/real.jpg', '2025-03-30 13:50:15', 1, '2025-04-05 08:20:15'),
(17, 'Amazon.com Inc', '$2y$10$Cm/dKsNyGAxbmzG/MX54XuAQNrri0ophhY/c/hXtgFurOxX/gdFw2', 'amazon@mail.com', 'uploads/retail.jpg', '2025-03-30 13:55:09', 1, '2025-04-05 08:20:15'),
(18, 'Adobe Inc.', '$2y$10$4Z3GnwsuPPp5UdKTD7xUe.ptra2kkwx7qdJuqmP6d4UfvQLyW3EAK', 'adobe@mail.com', 'uploads/software.webp', '2025-03-30 13:59:26', 1, '2025-04-05 08:20:15'),
(19, 'Apple Inc.', '$2y$10$7tGb/kdHXsGQJD/N8Dfs/.snyWWBbuOEA63HZiaO48032aRQt1.2.', 'apple@mail.com', 'uploads/tech.jpg', '2025-03-30 14:03:13', 1, '2025-04-05 08:20:15'),
(20, 'Verizon Communications Inc.', '$2y$10$VYFdCwoDHX8YdMH655DZv.eL0k/xzfn6vLNt5b/OG7Q5GRoVP8l36', 'verizon@mail.com', 'uploads/telecom.jpg', '2025-03-30 14:06:42', 1, '2025-04-05 08:20:15'),
(28, 'Test', '$2y$10$lGSNAjpyhvy9UUNB958Fnu7OVjqJ8h7e0ihQ0F5zePU5W9Xc1vLRS', 'kermichael1zs@gmail.com', NULL, '2025-04-07 12:53:20', 1, '2025-04-07 08:53:20'),
(29, 'KTest', '$2y$10$fB8vEo5OhtQxCrdwneX0auwWzE4wkx7mmRu/6SwbA3b7WrcD3RL7i', 'kermichaeltest@gmail.com', NULL, '2025-05-10 14:02:37', 0, '2025-05-10 10:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `content_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `comment_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `username`, `comment_text`, `created_at`) VALUES
(1, 4, 'jeff', 'Hello', '2025-03-09 11:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `commodity_requests`
--

CREATE TABLE `commodity_requests` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `commodity_requests`
--

INSERT INTO `commodity_requests` (`id`, `company_name`, `file_path`, `submitted_at`) VALUES
(3, 'Curaleaf', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Commodity_Request_1744308755.txt', '2025-04-10 18:12:35'),
(4, 'Test', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Commodity_Request_1744324003.txt', '2025-04-10 22:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `file_type` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `username`, `text`, `file_url`, `file_type`, `created_at`) VALUES
(25, 'Digicel', 'PostPaid with Digicel', 'uploads/River.png', 'png', '2025-03-13 16:35:00'),
(27, 'Flow', 'Premier League Football here at Flow!', 'uploads/flowpost.jpg', 'jpg', '2025-03-22 13:36:52'),
(28, 'Kermichael', 'Promotion!', 'uploads/image.png', 'png', '2025-03-22 14:13:54'),
(35, 'jeff', 'Clairo- junya', 'uploads/videoplayback.mp4', 'mp4', '2025-03-28 20:14:59'),
(47, 'Timmy Alexander', 'Salcc', 'uploads/salcc-sir-arthur-lewis-community-college-saint-lucia-1-1.jpg', 'jpg', '2025-03-29 11:01:52'),
(48, 'Sir Author Community College', 'Ministry of Education, Sustainable Development, Innovation, Science ...', 'uploads/screen-shot-2022-04-27-at-10.16.19.png', 'png', '2025-03-29 11:09:51'),
(51, 'Boeing', 'Throwback to our past!\r\nHow far we have come!!', 'uploads/Boeing.jpg', 'jpg', '2025-03-30 12:44:18'),
(52, 'Delta Airlines', 'Promo Codes ', 'uploads/deltapost.png', 'png', '2025-03-30 12:47:49'),
(53, 'Volkswagen', 'View our Offer!', 'uploads/volkpost.jpg', 'jpg', '2025-03-30 12:52:02'),
(54, 'JPMorgan Chase', 'Current Banking Promotions', 'uploads/jppost.jpg', 'jpg', '2025-03-30 12:56:03'),
(55, 'Moderna Inc.', 'Moderna Considers Setting $130 Price Tag on Covid-19 Vaccine ', 'uploads/biotechpost.jpg', 'jpg', '2025-03-30 13:01:44'),
(56, 'Curaleaf', 'MedWell Health and Wellness Centers', 'uploads/cannabispost.jpg', 'jpg', '2025-03-30 13:05:56'),
(57, 'Lucelec SLU', 'HUNDREDS of students from across the island this week visited the St Lucia Electricity Services Limited (LUCELEC) Cul-de-Sac power station.', 'uploads/energypost.jpg', 'jpg', '2025-03-30 13:11:08'),
(58, 'Mars Chocolate', 'Mars’ “1 in 6” promotions to return with more lines included', 'uploads/foodpost.jpg', 'jpg', '2025-03-30 13:15:49'),
(59, 'UnitedHealth Group Inc.', 'UnitedHealth Group Inc.', 'uploads/healthpost.jpg', 'jpg', '2025-03-30 13:20:15'),
(60, 'Facebook_Official', 'How to Create a Promotion on Facebook Marketplace\r\nhttps://www.youtube.com/watch?v=gP22mgQh8X4 ', 'uploads/mediapost.jpg', 'jpg', '2025-03-30 13:29:38'),
(61, 'Worthington Industries', 'Work+ featuring Worthington Industries', 'uploads/yt1z.net - Work featuring Worthington Industries (720p).mp4', 'mp4', '2025-03-30 13:37:15'),
(62, 'Johnson & Johnson', 'JOHNSON & JOHNSON’S celebrates its 125th year', 'uploads/pharmapost.jpg', 'jpg', '2025-03-30 13:43:37'),
(63, 'HomeServices of America', 'HomeServices of America is No. 1, Again!', 'uploads/realpost.jpg', 'jpg', '2025-03-30 13:52:08'),
(64, 'Amazon.com Inc', 'Promo Codes For Amazon 2024 - Brigit Claudina', 'uploads/retailpost.jpg', 'jpg', '2025-03-30 13:56:48'),
(65, 'Adobe Inc.', 'Adobe Discount Codes & Offers: Up To 60% OFF - Jan 2025', 'uploads/softwarepost.jpg', 'jpg', '2025-03-30 14:00:29'),
(66, 'Apple Inc.', 'Apple Card promo offers 5% Daily Cash back on select Apple product', 'uploads/techpost.jpg', 'jpg', '2025-03-30 14:04:24'),
(67, 'Verizon Communications Inc.', 'Switch to Verizon Unlimited and get the hottest phones FREE ', 'uploads/telecompost.jpg', 'jpg', '2025-03-30 14:07:36'),
(68, 'Anna-lisa', 'Today is our class presentation!', '', '', '2025-03-31 20:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `creators_hub_posts`
--

CREATE TABLE `creators_hub_posts` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `idea_text` text,
  `file_url` varchar(255) DEFAULT NULL,
  `file_type` enum('image','video','') DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `creators_hub_posts`
--

INSERT INTO `creators_hub_posts` (`id`, `username`, `idea_text`, `file_url`, `file_type`, `created_at`) VALUES
(2, 'jeff', 'Idea Test', 'uploads/creators_hub/67ec1e59ecdb24.93263218.jpg', 'image', '2025-04-01 17:11:53'),
(3, 'Kermichael', 'Upload #2', 'uploads/creators_hub/67ec20a36606e3.52982948.mp4', 'video', '2025-04-01 17:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `dismissed_requests`
--

CREATE TABLE `dismissed_requests` (
  `id` int NOT NULL DEFAULT '0',
  `username` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `description` text,
  `investment_type` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dismissed_requests`
--

INSERT INTO `dismissed_requests` (`id`, `username`, `company_name`, `industry`, `description`, `investment_type`, `logo_url`, `email`, `address`, `phone`, `status`, `submitted_at`, `completed_at`) VALUES
(3, 'Digicel', 'Digicel', 'technology', 'hiiiiiiii', 'stocks', 'media/digicel5375.jpg', 'Digicel@mail.com', '574 Riverdale Avenue', '7585199350', 'Completed', '2025-03-13 16:14:58', '2025-03-13 16:15:33'),
(5, 'Flow', 'Flow', 'technology', 'FLOW is a leading telecom company in Dominica, connecting the Caribbean to top-tier networks. They offer tailored services for individuals and businesses, including TV, web surfing, calls, and sports.', 'stocks', 'flow.jpg', 'flow@mail.com', '574 Riverdale Avenue', '7585199350', 'Completed', '2025-03-22 13:51:02', '2025-03-22 13:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `done_requests`
--

CREATE TABLE `done_requests` (
  `id` int NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `description` text,
  `investment_type` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `done_requests`
--

INSERT INTO `done_requests` (`id`, `username`, `company_name`, `industry`, `description`, `investment_type`, `logo_url`, `email`, `address`, `phone`, `status`, `submitted_at`, `completed_at`) VALUES
(1, 'Digicel', 'Digicel', 'technology', 'dufihdiofdj fsoijofsjf jfsojosjopf fnspofsos', 'stocks', 'media/digicel5375.jpg', 'Digicel@mail.com', '574 Riverdale Avenue', '7585199350', 'Completed', '2025-03-07 10:09:10', '2025-03-07 10:10:07'),
(4, 'Digicel', 'Digicel', 'technology', 'test', 'stocks', 'media/digicel5375.jpg', 'Digicel@mail.com', '574 Riverdale Avenue', '7585199350', 'Completed', '2025-03-22 10:48:30', '2025-03-22 13:55:52'),
(6, 'Test', 'Test', 'Aerospace & Defense', 'test', 'stocks', 'edu.png', 'jeffthekillerwon@gmail.com', '574 Riverdale Avenue', '7587234567', 'Completed', '2025-05-08 14:19:50', '2025-05-08 14:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int NOT NULL,
  `follower` varchar(255) NOT NULL,
  `following` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `follower`, `following`, `created_at`) VALUES
(2, 'jeff', 'ben', '2025-03-08 19:05:05'),
(4, 'Digicel', 'jeff', '2025-03-13 16:30:36'),
(5, 'Flow', 'jeff', '2025-03-22 13:37:14'),
(6, 'Flow', 'Digicel', '2025-03-22 13:37:21'),
(7, 'jeff', 'Flow', '2025-03-22 13:57:48'),
(8, 'Kermichael', 'Flow', '2025-03-22 14:14:26'),
(9, 'Kermichael', 'Digicel', '2025-03-22 14:14:36'),
(10, 'Kermichael', 'jeff', '2025-03-22 14:14:44'),
(15, 'jeff', 'Kermichael', '2025-03-22 15:08:05'),
(19, 'Anna-lisa', 'Sir Author Community College', '2025-03-31 20:21:00'),
(20, 'jeff', 'Anna-lisa', '2025-03-31 20:44:42'),
(21, 'jeff', 'Digicel', '2025-04-04 18:11:22');

-- --------------------------------------------------------

--
-- Table structure for table `investment_options`
--

CREATE TABLE `investment_options` (
  `id` int NOT NULL,
  `business_username` varchar(255) NOT NULL,
  `investment_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `investment_options`
--

INSERT INTO `investment_options` (`id`, `business_username`, `investment_type`) VALUES
(2, 'Boeing', 'stock'),
(5, 'Digicel', 'stock'),
(6, 'Digicel', 'bond'),
(8, 'Curaleaf', 'commodities'),
(9, 'HomeServices of America', 'real_estate'),
(14, 'Test', 'stock'),
(15, 'Test', 'bond');

-- --------------------------------------------------------

--
-- Table structure for table `library_articles`
--

CREATE TABLE `library_articles` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `library_articles`
--

INSERT INTO `library_articles` (`id`, `title`, `content`, `author`, `created_at`) VALUES
(1, 'Understanding Investments: Theories and Strategies', 'https://www.researchgate.net/publication/341922541_Understanding_Investments_Theories_and_Strategies', 'Ajeff', '2025-04-01 16:49:50');

-- --------------------------------------------------------

--
-- Table structure for table `library_images`
--

CREATE TABLE `library_images` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `uploaded_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `library_images`
--

INSERT INTO `library_images` (`id`, `title`, `image_url`, `uploaded_by`, `created_at`) VALUES
(8, '\"7 Essential Strategies for Effective Investment Management\"', 'uploads/images/67ec08c4c433f4.79780814.png', 'Ajeff', '2025-04-01 15:39:48'),
(9, 'Best Investment Stratigies', 'uploads/images/67ec09491f7238.84717907.jpg', 'Ajeff', '2025-04-01 15:42:01');

-- --------------------------------------------------------

--
-- Table structure for table `library_videos`
--

CREATE TABLE `library_videos` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(500) NOT NULL,
  `uploaded_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `library_videos`
--

INSERT INTO `library_videos` (`id`, `title`, `video_url`, `uploaded_by`, `created_at`) VALUES
(4, 'How to Invest for Beginners (Full Guide + Live Example)', 'uploads/videos/67ec08eb956405.31457754.mp4', 'Ajeff', '2025-04-01 15:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `rating` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `post_id`, `username`, `rating`) VALUES
(3, 35, 'jeff', 4),
(5, 28, 'jeff', 3),
(6, 27, 'jeff', 4),
(7, 25, 'jeff', 4),
(10, 35, 'Kermichael', 4),
(14, 68, 'jeff', 4);

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_requests`
--

CREATE TABLE `real_estate_requests` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `real_estate_requests`
--

INSERT INTO `real_estate_requests` (`id`, `company_name`, `file_path`, `submitted_at`) VALUES
(11, 'HomeServices of America', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Real_Estate_Request_1744316875.txt', '2025-04-10 20:27:56'),
(12, 'Test', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Real_Estate_Request_1744317976.txt', '2025-04-10 20:46:16'),
(13, 'Test', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Real_Estate_Request_1744318009.txt', '2025-04-10 20:46:49'),
(14, 'Test', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Real_Estate_Request_1744318363.txt', '2025-04-10 20:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `industry` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `investment_type` varchar(100) NOT NULL,
  `logo_url` varchar(500) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `username`, `company_name`, `industry`, `description`, `investment_type`, `logo_url`, `email`, `address`, `phone`, `status`, `submitted_at`) VALUES
(7, 'Digicel', 'Digicel', 'technology', 'Digicel test', 'stocks', 'delta.jpg', 'Digicel@mail.com', '574 Riverdale Avenue', '7585199350', 'Pending', '2025-04-04 18:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `stars`
--

CREATE TABLE `stars` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stars`
--

INSERT INTO `stars` (`id`, `user_id`, `business_id`) VALUES
(26, 4, NULL),
(27, NULL, 13),
(28, NULL, 2),
(29, NULL, 16);

-- --------------------------------------------------------

--
-- Table structure for table `stock_requests`
--

CREATE TABLE `stock_requests` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock_requests`
--

INSERT INTO `stock_requests` (`id`, `company_name`, `file_path`, `created_at`) VALUES
(1, 'Digicel', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Stock_Request_1743300081.txt', '2025-03-30 02:01:21'),
(4, 'Boeing', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Stock_Request_1743353036.txt', '2025-03-30 16:43:56'),
(7, 'Test', 'C:\\laragon\\www\\class-files\\class-files\\Investus_Tech_Project\\industries\\forms/../../downloads/Stock_Investment_Request_1744321740.txt', '2025-04-10 21:49:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT 'no-email@example.com',
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) DEFAULT 'default-profile.png',
  `verification_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reg_date`, `profile_picture`, `verification_code`) VALUES
(1, 'jeff', 'jeff@gmail.com', '$2y$10$x4qV/ed9MSuAwOVwFYDCee6CJQ1czX.Fd35E4fVJMsUnM5jBhNOsS', '2025-02-18 21:04:15', 'uploads/151811.jpg', NULL),
(2, 'ben', 'ben@mail.com', '$2y$10$E0qEgSl3YoBAmanFco.zOedZ8VJKo6Ns4JqFkDxyDiIee9NJwM4vm', '2025-02-22 16:28:19', 'default-profile.png', NULL),
(4, 'Kermichael', 'jeffthekillerwon@gmail.com', '$2y$10$C8K3.q2kTGUoYvpZvzhMD.hD4t8cOvMXq9FikL4rp7cGlILb0yB2m', '2025-03-22 14:07:17', 'uploads/pfp.jpg', NULL),
(7, 'Anna-lisa', 'annalisamontoute@gmail.com', '$2y$10$bIdU5JvmwPRUFpoLWXEV9.ihuWb6/uYT6nsoXV192rjrTaD4n0sFm', '2025-03-31 20:18:16', 'uploads/image.jpg', NULL),
(23, 'Kermichael_Official', 'kermichael1zs@gmail.com', '$2y$10$ULqgOLsz77xJiawMVFOb9O6B051DckKrO.ma06bWwUI9sRnC.TFF6', '2025-04-07 12:56:47', 'default-profile.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_investments`
--
ALTER TABLE `admin_investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bond_requests`
--
ALTER TABLE `bond_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_users`
--
ALTER TABLE `business_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commodity_requests`
--
ALTER TABLE `commodity_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `creators_hub_posts`
--
ALTER TABLE `creators_hub_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `done_requests`
--
ALTER TABLE `done_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investment_options`
--
ALTER TABLE `investment_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_articles`
--
ALTER TABLE `library_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_images`
--
ALTER TABLE `library_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_videos`
--
ALTER TABLE `library_videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rating` (`post_id`,`username`);

--
-- Indexes for table `real_estate_requests`
--
ALTER TABLE `real_estate_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stars`
--
ALTER TABLE `stars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_requests`
--
ALTER TABLE `stock_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_investments`
--
ALTER TABLE `admin_investments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bond_requests`
--
ALTER TABLE `bond_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `business_users`
--
ALTER TABLE `business_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `commodity_requests`
--
ALTER TABLE `commodity_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `creators_hub_posts`
--
ALTER TABLE `creators_hub_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `done_requests`
--
ALTER TABLE `done_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `investment_options`
--
ALTER TABLE `investment_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `library_articles`
--
ALTER TABLE `library_articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `library_images`
--
ALTER TABLE `library_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `library_videos`
--
ALTER TABLE `library_videos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `real_estate_requests`
--
ALTER TABLE `real_estate_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stars`
--
ALTER TABLE `stars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `stock_requests`
--
ALTER TABLE `stock_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `creators_hub_posts`
--
ALTER TABLE `creators_hub_posts`
  ADD CONSTRAINT `creators_hub_posts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

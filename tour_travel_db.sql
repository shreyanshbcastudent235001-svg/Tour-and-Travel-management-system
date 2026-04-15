-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 05:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tour_travel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `travel_date` date DEFAULT NULL,
  `travelers` int(11) DEFAULT 1,
  `message` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `travel_date`, `travelers`, `message`, `status`, `created_at`) VALUES
(1, 1, 1, '2025-11-18', 2, 'i want mor details call', 'Confirmed', '2025-11-16 13:54:00'),
(2, 1, 3, '2025-11-25', 2, '', 'Confirmed', '2025-11-17 17:00:51'),
(3, 1, 1, '2025-11-22', 1, '', 'Confirmed', '2025-11-17 17:10:52'),
(4, 2, 1, '2025-11-21', 2, '', 'Confirmed', '2025-11-17 18:37:57'),
(5, 3, 3, '2025-11-26', 2, '', 'Confirmed', '2025-11-17 19:21:59'),
(6, 4, 1, '2025-11-21', 3, '', 'Confirmed', '2025-11-17 20:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `days` int(11) DEFAULT 0,
  `nights` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `itinerary` text DEFAULT NULL,
  `includes` text DEFAULT NULL,
  `excludes` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `title`, `price`, `days`, `nights`, `description`, `itinerary`, `includes`, `excludes`, `image`, `created_at`) VALUES
(1, 'Goa Beach Holiday', 10999.00, 4, 3, 'Enjoy beaches of Goa with sightseeing and water sports.', 'Day 1: Arrival at Goa\r\nDay 2: North Goa sightseeing\r\nDay 3: Water sports activities\r\nDay 4: Leisure + market visit\r\nDay 5: Departure\r\n', 'Hotel stay (3/4/5 Star – as per package)\r\n✔ Daily breakfast\r\n✔ Airport pickup & drop\r\n✔ Sightseeing in AC vehicle\r\n✔ Local guide charges\r\n✔ Toll charges\r\n✔ Parking charges\r\n✔ Driver allowance\r\n✔ Fuel charges\r\n✔ All applicable taxes', 'Watersports charges\r\n❌ Cruise tickets\r\n❌ Alcoholic drinks', NULL, '2025-11-16 13:27:31'),
(2, 'Himachal Adventure Tour Package (5 Days / 4 Nights)', 14999.00, 5, 4, 'Experience the thrilling adventure of Himachal Pradesh with this 5 Days / 4 Nights package covering Shimla, \r\nManali, Kufri, and Solang Valley. Perfect for adventure lovers, couples, and families, this tour offers \r\nsnow-capped mountains, river rafting, paragliding, skiing, and mesmerizing hill station views.\r\n', 'Day 1: Delhi/Chandigarh → Shimla\r\n• Pickup from Delhi/Chandigarh\r\n• Beautiful drive to Shimla\r\n• Check-in at hotel\r\n• Mall Road & Ridge visit\r\n• Overnight stay in Shimla\r\n\r\nDay 2: Shimla → Kufri Adventure\r\n• Visit Kufri Snow Point\r\n• Yak ride, horse riding, skiing (optional)\r\n• Green Valley & Mini Zoo\r\n• Jakhoo Temple visit\r\n• Overnight stay in Shimla\r\n\r\nDay 3: Shimla → Manali\r\n• Scenic journey from Shimla to Manali\r\n• Sundernagar Lake\r\n• Pandoh Dam\r\n• Kullu River Rafting point\r\n• Overnight stay in Manali\r\n\r\nDay 4: Solang Valley Adventure Day\r\n• Visit Solang Valley\r\n• Adventure sports (optional): Paragliding, ATV ride, Skiing, Ropeway\r\n• Anjani Mahadev trek (optional)\r\n• Return to Manali\r\n• Overnight stay\r\n\r\nDay 5: Manali → Drop at Delhi/Chandigarh\r\n• Checkout from hotel\r\n• Visit local markets (optional)\r\n• Drop at Chandigarh/Delhi\r\n• Tour ends with beautiful memories\r\n', '• 4 Nights hotel stay (Shimla & Manali)\r\n• Daily breakfast and dinner\r\n• Private cab for all transfers and sightseeing\r\n• Kufri sightseeing\r\n• Solang Valley visit\r\n• Pick and Drop from Chandigarh/Delhi\r\n• Fuel, toll tax, parking, and driver allowance\r\n• All applicable taxes\r\n', '• Air/Train/Volvo tickets\r\n• Adventure activities charges (paragliding, rafting, skiing)\r\n• Lunch & personal expenses\r\n• Entry tickets to sights/monuments\r\n• Heater charges at hotels\r\n• Anything not mentioned in inclusions\r\n', '1763384256_hp1.jpg', '2025-11-16 13:27:31'),
(3, '4 DAYS LADAKH TOUR PACKAGE', 15000.00, 4, 3, 'A perfect 4-day Ladakh getaway covering Leh, Nubra Valley & Khardung La with scenic drives, monasteries, deserts, and unforgettable Himalayan views.\r\n', 'Day 1: Day 1: Arrival in Leh + Acclimatization - Leh Airport arrival\r\nDay 2: Leh Market & Shanti Stupa - \r\nDay 3:Leh Local Sightseeing - Hall of Fame\r\nDay 4 : Return\r\n', 'travel', 'food', '1763375755_e5e8db7e71f4537607db1f209b348867_1000x1000.jpg', '2025-11-17 16:00:26'),
(5, '4 Days Kerala Tour Package – Munnar & Alleppey', 13499.00, 4, 3, 'Explore the natural beauty of Kerala with this 4 Days / 3 Nights tour covering Munnar hill station and the \r\nfamous Alleppey backwaters. Enjoy lush green valleys, tea plantations, waterfalls, and a relaxing houseboat \r\nexperience. Perfect for couples, families, and nature lovers.\r\n', 'Day 1: Kochi to Munnar\r\n• Pickup from Kochi Airport/Railway Station\r\n• Cheeyappara & Valara Waterfalls\r\n• Tea plantations viewpoint\r\n• Hotel check-in & relaxation\r\n\r\nDay 2: Munnar Sightseeing\r\n• Eravikulam National Park (Rajamalai)\r\n• Mattupetty Dam\r\n• Echo Point\r\n• Kundala Lake\r\n• Tea Museum visit\r\n• Overnight stay at Munnar\r\n\r\nDay 3: Munnar to Alleppey Backwaters\r\n• Drive to Alleppey\r\n• Check-in to houseboat (optional)\r\n• Backwater cruise through narrow canals\r\n• Village sightseeing\r\n• Sunset experience on the lake\r\n• Overnight stay at Alleppey\r\n\r\nDay 4: Departure\r\n• Checkout from hotel/houseboat\r\n• Transfer to Kochi Airport/Railway Station\r\n• Tour ends with wonderful memories\r\n', '• Accommodation in Munnar & Alleppey\r\n• Daily breakfast\r\n• Private cab for sightseeing and transfers\r\n• Munnar local sightseeing\r\n• Alleppey backwater boat ride\r\n• Driver allowance, toll, parking, fuel charges\r\n• All applicable taxes\r\n', '• Flight/Train tickets\r\n• Lunch & dinner\r\n• Entry tickets at sightseeing points\r\n• Water activities charges\r\n• Personal expenses\r\n• Anything not mentioned in inclusions\r\n', '1763383370_Kerala-Tourism.jpg', '2025-11-17 18:12:50'),
(6, '6 Days Spiti Valley Winter Expedition', 22900.00, 6, 5, 'Experience the raw beauty of Spiti Valley in winter with this 6 Days snow expedition. \r\nWitness frozen rivers, white landscapes, snow-capped monasteries, and remote Himalayan villages \r\nlike Kaza, Hikkim, Langza, and Komic. Perfect for adventure seekers who want to explore the \r\n“Cold Desert of India” during its most magical season.\r\n', 'Day 1: Shimla/Manali → Rampur / Kalpa\r\n• Pickup from Shimla or Manali\r\n• Drive through winter roads & snow patches\r\n• Night stay at Rampur or Kalpa\r\n\r\nDay 2: Kalpa → Tabo → Kaza\r\n• Early morning departure\r\n• Visit frozen Spiti River viewpoints\r\n• Tabo Monastery (1,000+ years old)\r\n• Reach Kaza by evening\r\n• Overnight stay in Kaza\r\n\r\nDay 3: Kaza Local Sightseeing + Snow Walk\r\n• Explore Kaza Market\r\n• Kaza Monastery visit\r\n• Winter photography session\r\n• Overnight stay at Kaza\r\n\r\nDay 4: Hikkim – Komic – Langza Snow Loop\r\n• Visit the world’s highest post office (Hikkim)\r\n• Visit Komic: Highest village connected by road\r\n• Langza: Fossil village + Buddha statue\r\n• Snow fields exploration\r\n• Return to Kaza\r\n\r\nDay 5: Key Monastery + Chicham Bridge\r\n• Visit Key Monastery (Spiti’s largest)\r\n• Meet monks (optional)\r\n• Drive to Chicham Bridge (Asia’s highest bridge)\r\n• Stunning winter valley views\r\n• Return to Kaza for night stay\r\n\r\nDay 6: Kaza → Kalpa / Shimla Drop\r\n• Early morning departure\r\n• Return journey through snow patches\r\n• Drop at Shimla/Manali\r\n• Winter expedition ends with lifetime memories\r\n', '• 5 Nights stay in winter guesthouses/homestays\r\n• Breakfast & dinner included\r\n• 4x4 vehicle / Winter taxi for entire trip\r\n• Visit Key Monastery, Kaza, Hikkim, Komic, Langza\r\n• Snow drive with professional winter driver\r\n• Bonfire (where allowed)\r\n• Oxygen cylinder (on demand)\r\n• All permits & tolls\r\n', '• Volvo/Flight tickets to Shimla/Manali\r\n• Lunch & personal expenses\r\n• Snow gear rental (gumboots, jackets, gloves)\r\n• Travel insurance\r\n• Medical/rescue cost\r\n• Anything not mentioned in inclusions', '1763387659_wanderon-spiti-winter-13.jpg', '2025-11-17 19:24:19'),
(7, '4 Days Chhattisgarh Nature & Waterfalls Tour (Jagdalpur – Bastar – Chitrakote)', 10999.00, 4, 3, 'Experience the beauty of Chhattisgarh with this 4 Days / 3 Nights tour covering Jagdalpur, \r\nChitrakote Waterfall, Tirathgarh Falls, and Bastar tribal culture. This tour is perfect for \r\nnature lovers, photographers, families, and adventure seekers who want to explore India’s \r\nhidden gems and natural wonders.\r\n', 'Day 1: Jagdalpur Arrival & City Tour\r\n• Pickup from Jagdalpur Railway Station/Bus Stand\r\n• Check-in hotel\r\n• Visit Dalpat Sagar Lake\r\n• Explore local markets & Bastar culture\r\n• Overnight stay in Jagdalpur\r\n\r\nDay 2: Chitrakote Waterfall Tour\r\n• Breakfast at hotel\r\n• Drive to Chitrakote Waterfall (India’s Niagara Falls)\r\n• Enjoy boating (seasonal)\r\n• Visit Tamda Ghumar & Mendri Ghumar Falls\r\n• Return to Jagdalpur\r\n• Overnight stay\r\n\r\nDay 3: Tirathgarh Waterfall & Kanger Valley National Park\r\n• Visit Tirathgarh Waterfall (famous natural split falls)\r\n• Explore Kanger Valley National Park\r\n• Visit Kailash Caves (optional)\r\n• Wildlife & nature photography\r\n• Return to hotel & relax\r\n\r\nDay 4: Departure\r\n• Breakfast at hotel\r\n• Visit Anthropological Museum (optional)\r\n• Drop at Jagdalpur station\r\n• Tour ends with sweet memories\r\n', '• 3 Nights hotel stay in Jagdalpur\r\n• Daily breakfast\r\n• Private cab for all sightseeing\r\n• Visit to Chitrakote & Tirathgarh waterfalls\r\n• Bastar local sightseeing\r\n• All parking, toll & driver charges\r\n• All applicable taxes\r\n', '• Flight/Train tickets\r\n• Entry fees to parks & museums\r\n• Lunch & dinner\r\n• Boating and adventure activities\r\n• Personal expenses\r\n• Anything not mentioned in inclusions\r\n', '1763392192_02.jpg', '2025-11-17 20:39:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'suraj kumar', 'suraj@gmail.cm', '09878451200', '$2y$10$Rt.YCbNKliy0AHrNEL7FEe52DI/9IDHDVserTTUyjL28odVpN08oG', '2025-11-16 13:53:24'),
(2, 'Ram Kumar', 'ram@gmail.com', '08784515800', '$2y$10$ofkDLfQxFE3zYetYni4lieVwQou/kfVQmiolIr29PIT1kc4uOGZa6', '2025-11-17 18:37:21'),
(3, 'dummy user', 'demo1@gmail.com', '9999999999999', '$2y$10$FoVDsIb2LAsVRN8voCd7QeSvx8kTNH644IrKK4xnTxb3ObaXHYqCq', '2025-11-17 19:20:52'),
(4, 'dummy user', 'demo2@gmail.com', '9999999999999', '$2y$10$i6Uqf.rSUVxXbibVzjqbh.TH1OPK4rz40REeE9qX0pLN09Cqw308y', '2025-11-17 20:35:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

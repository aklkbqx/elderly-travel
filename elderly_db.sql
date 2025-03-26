-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Mar 26, 2025 at 08:54 PM
-- Server version: 10.11.11-MariaDB-ubu2204
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elderly_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` varchar(255) NOT NULL,
  `questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`questions`)),
  `additional` varchar(150) DEFAULT NULL,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessment_id`, `title`, `body`, `questions`, `additional`, `visitors`, `created_at`, `updated_at`) VALUES
(3, 'แบบประเมินความพึงพอใจ', 'ประเมินเว็บไซต์ของเรา ให้คะแนน \r\n\r\n5 มากที่สุด \r\n4 มาก \r\n3 ปานกลาง \r\n2 น้อย \r\n1 น้อยที่สุด', '[\"\\u0e01\\u0e32\\u0e23\\u0e43\\u0e0a\\u0e49\\u0e07\\u0e32\\u0e19\",\"\\u0e02\\u0e19\\u0e32\\u0e14\\u0e2a\\u0e35\\u0e41\\u0e25\\u0e30\\u0e15\\u0e31\\u0e27\\u0e2d\\u0e31\\u0e01\\u0e29\\u0e23\",\"\\u0e1f\\u0e35\\u0e40\\u0e08\\u0e2d\\u0e23\\u0e4c\\u0e15\\u0e48\\u0e32\\u0e07\\u0e46\",\"\\u0e04\\u0e27\\u0e32\\u0e21\\u0e40\\u0e2a\\u0e16\\u0e35\\u0e22\\u0e23\\u0e02\\u0e2d\\u0e07\\u0e23\\u0e30\\u0e1a\\u0e1a\"]', 'ข้อเสนอแนะเพิ่มเติม', 3, '2025-02-20 15:27:11', '2025-03-12 20:51:18'),
(5, 'การใช้งานของเว็บไซต์', 'ให้คะเเนนการใช้งานเว็บไซต์ในมุมมองของคุณ\r\nเกณฑ์คะเเนนมีดังนี้\r\n  5 มากที่สุด\r\n  4 มาก\r\n  3 ปานกลาง\r\n  2 น้อย\r\n  1 น้อยที่สุด', '[\"\\u0e01\\u0e32\\u0e23\\u0e2a\\u0e21\\u0e31\\u0e04\\u0e23\\u0e43\\u0e0a\\u0e49\\u0e07\\u0e32\\u0e19\\u0e40\\u0e27\\u0e47\\u0e1a\\u0e44\\u0e0b\\u0e15\\u0e4c\\u0e21\\u0e35\\u0e04\\u0e27\\u0e32\\u0e21\\u0e07\\u0e48\\u0e32\\u0e22\\u0e15\\u0e48\\u0e2d\\u0e01\\u0e32\\u0e23\\u0e43\\u0e0a\\u0e49\\u0e07\\u0e32\\u0e19\\u0e21\\u0e32\\u0e01\\u0e19\\u0e49\\u0e2d\\u0e22\\u0e40\\u0e40\\u0e04\\u0e48\\u0e44\\u0e2b\\u0e19?\",\"\\u0e02\\u0e48\\u0e32\\u0e27\\u0e1b\\u0e23\\u0e30\\u0e0a\\u0e32\\u0e2a\\u0e31\\u0e21\\u0e1e\\u0e31\\u0e19\\u0e18\\u0e4c\\u0e21\\u0e35\\u0e40\\u0e19\\u0e37\\u0e49\\u0e2d\\u0e2b\\u0e32\\u0e17\\u0e35\\u0e48\\u0e2d\\u0e48\\u0e32\\u0e19\\u0e07\\u0e48\\u0e32\\u0e22\\u0e21\\u0e32\\u0e01\\u0e19\\u0e49\\u0e2d\\u0e22\\u0e40\\u0e40\\u0e04\\u0e48\\u0e44\\u0e2b\\u0e19?\",\"\\u0e01\\u0e32\\u0e23\\u0e04\\u0e38\\u0e22\\u0e2a\\u0e37\\u0e48\\u0e2d\\u0e2a\\u0e32\\u0e23\\u0e01\\u0e31\\u0e1a\\u0e40\\u0e40\\u0e0a\\u0e17\\u0e1a\\u0e2d\\u0e17\\u0e21\\u0e35\\u0e04\\u0e27\\u0e32\\u0e21\\u0e2a\\u0e30\\u0e14\\u0e27\\u0e01\\u0e2a\\u0e1a\\u0e32\\u0e22\\u0e21\\u0e32\\u0e01\\u0e19\\u0e49\\u0e2d\\u0e22\\u0e40\\u0e40\\u0e04\\u0e48\\u0e44\\u0e2b\\u0e19?\",\"\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e17\\u0e35\\u0e48\\u0e17\\u0e48\\u0e2d\\u0e07\\u0e40\\u0e17\\u0e35\\u0e48\\u0e22\\u0e27\\u0e17\\u0e35\\u0e48\\u0e40\\u0e27\\u0e47\\u0e1a\\u0e44\\u0e0b\\u0e15\\u0e4c\\u0e08\\u0e31\\u0e14\\u0e02\\u0e36\\u0e49\\u0e19\\u0e21\\u0e35\\u0e04\\u0e27\\u0e32\\u0e21\\u0e19\\u0e48\\u0e32\\u0e2a\\u0e19\\u0e43\\u0e08\\u0e21\\u0e32\\u0e01\\u0e19\\u0e49\\u0e2d\\u0e22\\u0e40\\u0e40\\u0e04\\u0e48\\u0e44\\u0e2b\\u0e19?\",\"\\u0e01\\u0e32\\u0e23\\u0e08\\u0e2d\\u0e07\\u0e2a\\u0e16\\u0e32\\u0e19\\u0e17\\u0e35\\u0e48\\u0e17\\u0e48\\u0e2d\\u0e07\\u0e40\\u0e17\\u0e35\\u0e48\\u0e22\\u0e27\\u0e21\\u0e35\\u0e04\\u0e27\\u0e32\\u0e21\\u0e22\\u0e32\\u0e01\\u0e21\\u0e32\\u0e01\\u0e19\\u0e49\\u0e2d\\u0e22\\u0e40\\u0e40\\u0e04\\u0e48\\u0e44\\u0e2b\\u0e19?\"]', 'ข้อเสนอเเนะ', 0, '2025-03-16 18:35:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assessment_responses`
--

CREATE TABLE `assessment_responses` (
  `assessment_responses_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `responses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responses`)),
  `additional` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `assessment_responses`
--

INSERT INTO `assessment_responses` (`assessment_responses_id`, `assessment_id`, `user_id`, `responses`, `additional`, `created_at`, `updated_at`) VALUES
(2, 3, 4, '[\"5\",\"5\",\"5\",\"5\"]', 'test', '2025-02-20 16:02:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]',
  `people` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('PENDING','CONFIRMED','COMPLETED','CANCELED') NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bot_responses`
--

CREATE TABLE `bot_responses` (
  `response_id` int(11) NOT NULL,
  `questions` varchar(255) NOT NULL,
  `responses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responses`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(150) NOT NULL,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `title`, `body`, `image`, `visitors`, `created_at`, `updated_at`) VALUES
(8, 'ผู้สูงอายุนอนไม่หลับ สาเหตุจากอะไร แก้ไขได้หรือไม่', '          เมื่อเข้าสู่วัยผู้สูงอายุ มักจะมีอาการนอนไม่หลับ หรือนอนหลับยากและยังตื่นง่าย เมื่อตื่นแล้วก็ทำให้หลับยากขึ้นไปอีก สาเหตุของอาการนี้มาจากอะไร และส่งผลเสียต่อสุขภาพของผู้สูงวัยอย่างไรบ้าง\r\n\r\n          การนอนไม่หลับทำให้ผู้สูงอายุมีปัญหาการใช้ชีวิตประจำวัน รู้สึกอ่อนเพลีย สมองตื้อ ทำอะไรช้าลง อารมณ์เสียง่าย เสี่ยงวิตกกังวลและซึมเศร้า อาจมีปัญหาสุขภาพด้านต่างๆ ตามมา เช่น ความดันโลหิตสูง เบาหวาน ภูมิคุ้มกันทำงานลดลง อ้วน และมีโอกาสเสี่ยงสมองเสื่อม\r\n\r\nผู้สูงอายุนอนไม่หลับ มีสาเหตุจากอะไร\r\n           สาเหตุที่ทำให้ผู้สูงอายุนอนไม่หลับ มาจากหลายปัจจัยด้วยกันดังต่อไปนี้\r\n\r\n- ระดับฮอร์โมนต่างๆ ในร่างกายที่ลดต่ำลง\r\n- ความเครียด ความวิตกกังวล และความกังวลว่านอนไม่หลับขณะพยายามนอนให้หลับ\r\n- อาการปวดต่างๆ ที่เป็นอยู่ เช่น ปวดข้อ ปวดแขน ปวดขา ปวดหลัง\r\n- มีโรครบกวนการนอน เช่น เบาหวาน ต่อมลูกหมากโต ที่ต้องตื่นมาปัสสาวะตอนกลางคืน โรคหัวใจ โรคปอด โรคซึมเศร้า สมองเสื่อม นอนกรน ภาวะหยุดหายใจตอนนอนหลับ กล้ามเนื้อขากระตุก\r\n- ปัญหาระบบทางเดินอาหาร เช่น อาหารไม่ย่อย ท้องอืดแน่นท้อง กรดไหลย้อน\r\n- รับประทานยาบางชนิด เช่น ยาแก้หวัด ยารักษาหอบหืด ยาขยายหลอดลม ยาต้านซึมเศร้า หรืออาหารเสริม เช่น โสม\r\n- ดื่มเครื่องดื่มที่มีแอลกอฮอล์ และคาเฟอีน เช่น ชา กาแฟ โกโก้ ช็อกโกแลต น้ำอัดลมสีดำ เครื่องดื่มชูกำลัง และสูบบุหรี่\r\n- สิ่งแวดล้อมรบกวนการนอน มีเสียงดัง แสงสว่างกวนสายตา อุณหภูมิร้อนหรือเย็นเกินไป ที่นอนนอนแล้วไม่สบายตัว ห้องอับ\r\n- ทำกิจกรรมที่ทำให้นอนไม่หลับ เช่น ออกกำลังกายใกล้เวลานอน ดูหนังตื่นเต้นหรือน่ากลัว เล่นเกมที่ต้องลุ้น อยู่หน้าจอมือถือหรือหน้าจอต่างๆ ใกล้เวลานอน', '67d713095eadd.webp', 29, '2025-03-16 18:06:01', '2025-03-26 19:27:05'),
(9, 'ส่องอาชีพไม่มีวันเกษียณ รับมือสังคมสูงวัยยุคยิ่งแก่ยิ่งจน!', '           ในโลกที่ความแน่นอนคือความไม่แน่นอน คาดหวังอะไรไว้ก็ต้องเผื่อใจคิดแผนสำรองด้วย วันดีคืนดีอาจตกงานโดยไม่รู้ตัว หรือไม่ก็มีเหตุจำเป็นให้ต้องเออร์ลี่ก่อนเกษียณ\r\n\r\n           เกษียณยังไงไม่ให้เฉา อาจไม่ท้าทายเท่ากับเกษียณยังไงให้สามารถเลี้ยงปากเลี้ยงท้องตัวเองได้ บอกเลยว่าอย่ารอให้ถึงอายุ 60 แล้วค่อยวางแผน เพราะชีวิตจริงดราม่ากว่าที่คิดไว้เยอะ! ในวัยที่ร่างกายเริ่มถดถอยอ่อนล้า แต่สมองยังไม่โรยราไปตามวัย ต้องมองหาอาชีพที่ไม่มีวันเกษียณ เตรียมสร้างรายได้ไว้รองรับชีวิตสูงวัย ที่รันเวย์ยังยาวไกลไปอีกหลายสิบปี ลำพังแค่เบี้ยผู้สูงอายุ, เงินบำนาญชราภาพ และกองทุนสำรองเลี้ยงชีพ อาจไม่เพียงพอกับการมีอายุเกิน 80 ปี\r\n\r\n“เพาะต้นไม้ขาย” เปลี่ยนงานอดิเรกที่รักให้เกิดรายได้ แถมยังได้ออกแรงทุกวัน ทำให้สุขภาพแข็งแรงทั้งกายใจ ถือเป็นความสุขในรั้วบ้านที่เหมาะกับผู้สูงวัย\r\n\r\n“ทำขนมและอาหารขาย” เป็นอาชีพอิสระที่ไม่มีวันเกษียณ แถมสร้างรายได้อย่างต่อเนื่อง ขอเพียงแต่มีฝีมือ รับรองว่าไม่อดตาย\r\n\r\n“ปล่อยเช่าอสังหาริมทรัพย์” ในช่วงวัยทำงานหากมีกำลังทรัพย์อาจซื้อคอนโด หรือตึกแถวไว้ปล่อยเช่า กินค่าเช่าในยามแก่เฒ่า ถือเป็นการลงทุนที่สามารถเอาชนะเงินเฟ้อได้ และมีรายรับต่อเนื่องทุกเดือน\r\n\r\n“เลือกลงทุนในหุ้นปันผล” ถือเป็นช่องทางการออมเงินที่ดี และสามารถสร้างรายได้อย่างต่อเนื่องโดยไม่มีวันเกษียณ แต่ต้องลงทุนกับความรู้ควบคู่ไปด้วย อย่าลงทุนด้วยความโลภ\r\n\r\n“นักเขียน-นักแปล-ล่าม-ไกด์” การเป็นนักเขียนและนักแปลสามารถพัฒนาตัวเองได้ทั้งชีวิต และสร้างจุดเด่นจากประสบการณ์หลาก หลายที่สั่งสมมา\r\n\r\n“อาชีพในโลกออนไลน์” โลกโซเชียลมีเดียเป็นช่องทางหาเงินและเปิดโอกาสให้คนทุกเพศทุกวัย ถ้ามีไฟซะอย่าง จะเป็นอะไรก็ได้ ทั้งอินฟลูเอนเซอร์, ยูทูบเบอร์, รีวิวสินค้า, ขายของออนไลน์ หรือดาว tiktok รับรองว่าไม่เหงาแน่นอน แถมมีรายได้เป็นกอบเป็นกำ และได้แฟนคลับต่อยอดขยายธุรกิจใหม่ๆ ดูอย่างคุณยายชาวญี่ปุ่น “มาซาโกะ วากามิยะ” ลุกขึ้นเป็นนักพัฒนาแอปพลิเคชันของ Apple สร้างเกมบนมือถือสมาร์ทโฟนให้ผู้สูงอายุได้เล่นกันทั่วโลก ตอนอายุ 80 ปี และยังพัฒนาตัวเองไม่หยุดจนถึงปัจจุบันอายุเฉียดเลขเก้า\r\n\r\n“พนักงานร้านค้าปลีก และบาริสต้า” ปัจจุบันร้านค้าหลายแห่งและห้างสรรพสินค้า เปิดโอกาสให้ผู้สูงอายุได้เข้าไปเป็นพนักงาน เช่น เจ้าหน้าที่ชงกาแฟ, เจ้าหน้าที่แนะนำหนังสือ, พนักงานขาย และแคชเชียร์เก็บเงิน\r\n\r\n“ค้าขายทั่วไป” นอกจากจะทำให้ชีวิตมีคุณค่าไม่ต้องง้อลูกหลาน ยังมีรายได้เลี้ยงตัวเอง และได้ออกจากบ้านทุกวัน ไม่ต้องทนเฉาทนเหงา เพราะไม่มีอะไรทำ', '67d714c957556.webp', 3, '2025-03-16 18:13:29', '2025-03-26 19:31:03'),
(10, 'เรื่องดีๆ ปีใหม่รับสังคมสูงวัย 2568', '          “มิสเตอร์พี” ขอสวัสดีปีใหม่ 2568 อย่างเป็นทางการ และขอให้สิ่งศักดิ์สิทธิ์อวยพรให้ผู้อ่านทุกคนมีความสุข สุขภาพกายและใจแข็งแรง แข็งแกร่ง และเรื่องสุขภาพนี้ ถือเป็นเรื่องสำคัญมากของเศรษฐกิจไทย เพราะปี 2568 นี้ ถือเป็นปีที่ 2 ที่ประเทศไทยเข้าสู่การเป็น “สังคมผู้สูงอายุโดยสมบูรณ์”กล่าวคือ เป็นประเทศที่มีประชากรอายุ 60 ปีขึ้นไปเกิน 20% ประชากรอายุ 65 ปีขึ้นไป 14% ของประชากรทั้งหมด และอีกภายในไม่กี่ปีเราจะเข้าสู่ “สังคมสูงวัยระดับสุดยอด” คือมีประชากรอายุ 60 ปีเกินกว่า 28%\r\n\r\n          โดยในปี 2568 นี้คาดว่า อายุเฉลี่ยของคนไทยจะขยับมาอยู่ที่ 85 ปี แต่ทุก 1 นาที จะมีคนไทยเสียชีวิตด้วยโรคมะเร็ง โรคหัวใจ หรือเส้นเลือดในสมองแตก รวมทั้งกลายเป็นผู้ป่วยติดเตียงจำนวนมาก ทำให้ปีที่ผ่านมา ข่าวผู้ป่วยติดเตียงที่นอนรักษาตัวอยู่ที่บ้านที่เสียชีวิต เนื่องจากชำระค่าไฟฟ้าล่าช้า จนถูกเจ้าหน้าที่ตัดกระแสไฟฟ้า หรือถอดมิเตอร์ไฟฟ้า ทำให้ไม่มีไฟสำหรับอุปกรณ์รักษาพยาบาล สร้างความหดหู่ใจให้กับคนไทยอย่างมากและเรื่องดังกล่าวเป็นบทเรียนให้กับคนไทยทุกบ้านที่มีคนติดเตียง รวมทั้ง ทำให้สำนักงานคณะกรรมการกำกับกิจการพลังงาน หรือสำนักงาน กกพ. มองว่าจะต้องแก้ไขเรื่องดังกล่าวไม่ให้เกิดขึ้นอีก\r\n\r\n          โดยได้ร่วมจัดทำบันทึกความเข้าใจความร่วมมือระหว่าง 4 หน่วยงาน ได้แก่ สำนักงานคณะกรรมการกำกับกิจการพลังงาน กระทรวงสาธารณสุข การไฟฟ้านครหลวง (กฟน.) และการไฟฟ้าส่วนภูมิภาค (กฟภ.) เพื่อลดความเสี่ยงของการเสียชีวิตของผู้ป่วย ซึ่งมีความจำเป็นที่จะต้องใช้ไฟฟ้าในการเดินเครื่องมือทางการแพทย์เพื่อการรักษาพยาบาล โดยจะต้องไม่ถูกงดจ่ายไฟฟ้าทุกกรณี เพื่อให้อัตราการเสียชีวิตจากการถูกตัดไฟของผู้ป่วยกลุ่มนี้เป็นศูนย์', '67d716323cf2c.webp', 0, '2025-03-16 18:19:30', NULL),
(17, 'ฉลองเปิดตัวเว็บไซต์ แจกส่วนลด 10%', 'ฉลองเปิดตัวเว็บไซต์ แจกส่วนลด 10% เริ่มใช้งานตั้งแต่วันนี้เป็นต้นไป ถึง 31 ธันวาคม 2568', '67e46938a21e8.jpg', 0, '2025-03-26 20:53:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `comment_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `news_comments`
--

INSERT INTO `news_comments` (`comment_id`, `news_id`, `user_id`, `comment`, `created_at`) VALUES
(3, 8, 4, 'test', '2025-03-26 19:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `news_likes`
--

CREATE TABLE `news_likes` (
  `like_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `news_likes`
--

INSERT INTO `news_likes` (`like_id`, `news_id`, `user_id`, `like`, `created_at`) VALUES
(2, 6, 4, 1, '2025-02-21 11:40:21'),
(3, 9, 4, 1, '2025-03-23 05:16:55'),
(4, 8, 4, 1, '2025-03-26 19:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `status` enum('PENDING','PAID','FAIL','CANCEL') NOT NULL DEFAULT 'PENDING',
  `payment_method` enum('QRCODE_PROMPTPAY','BANK_ACCOUNT_NUMBER') DEFAULT NULL,
  `slip_image` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `place_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `health` text NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `category_id` int(11) NOT NULL,
  `visitors` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`place_id`, `name`, `address`, `health`, `price`, `images`, `category_id`, `visitors`, `created_at`, `updated_at`) VALUES
(9, 'เขาหน่อ', 'ตำบลบ้านแดน อำเภอบรรพตพิสัย จังหวัดนครสวรรค์', 'การขึ้นเขาหน่อต้องเดินขึ้นบันไดปูนและบันไดเหล็กรวมเกือบ 700 ขั้น ซึ่งบางช่วงมีความชันสูงถึงเกือบ 90 องศา ดังนั้น ผู้ที่มีปัญหาด้านสุขภาพหรือร่างกายไม่แข็งแรงควรพิจารณาความสามารถของตนก่อนขึ้น อย่างไรก็ตาม การขึ้นเขาหน่อสามารถเสริมสร้างความแข็งแรงของร่างกายและระบบหัวใจได้ หากทำด้วยความระมัดระวัง', 0.00, '[\"67e467747a66b.jpg\"]', 10, 0, '2025-03-26 20:45:40', NULL),
(10, 'บึงบอระเพ็ด​', 'ศูนย์ต้อนรับนักท่องเที่ยวบึงบอระเพ็ด​ หนองบัวลำภู  หมู่ 3 ตำบลแควใหญ่ อำเภอเมืองนครสวรรค์ จังหวัดนครสวรรค์ 60000', 'หากคุณหมายถึง \"บอระเพ็ด\" ซึ่งเป็นสมุนไพรชนิดหนึ่ง มีสรรพคุณหลายประการ เช่น ต้านอนุมูลอิสระ เสริมสร้างระบบภูมิคุ้มกัน ช่วยควบคุมระดับน้ำตาลในเลือด และลดคอเลสเตอรอล อย่างไรก็ตาม การบริโภคบอระเพ็ดเป็นเวลานานอาจส่งผลเสียต่อตับและไตได้', 0.00, '[\"67e467e38242d.jpg\"]', 10, 0, '2025-03-26 20:47:31', '2025-03-26 20:48:48'),
(11, 'อควาเรียม ณ บึงบอระเพ็ด', 'อาคารแสดงพันธุ์สัตว์น้ำบึงบอระเพ็ดเฉลิมพระเกียรติ 80 พรรษา ตั้งอยู่ที่ศูนย์บริการนักท่องเที่ยวบึงบอระเพ็ด ตำบลแควใหญ่ อำเภอเมืองนครสวรรค์ จังหวัดนครสวรรค์ 60000', 'การเยี่ยมชมอควาเรียมสามารถส่งเสริมความรู้และความเข้าใจเกี่ยวกับระบบนิเวศน้ำจืดและพันธุ์ปลาต่าง ๆ ซึ่งเป็นประโยชน์ต่อสุขภาพจิตและการเรียนรู้ของผู้เข้าชม', 49.00, '[\"67e468283da09.jpg\"]', 10, 0, '2025-03-26 20:48:40', NULL),
(12, 'วัดคีรีวงศ์', 'ตำบลปากน้ำโพ อำเภอเมืองนครสวรรค์ จังหวัดนครสวรรค์', 'การเดินขึ้นไปสักการะพระจุฬามณีเจดีย์บนยอดเขาดาวดึงส์ถือเป็นการออกกำลังกายที่ดี ช่วยเสริมสร้างความแข็งแรงของร่างกาย อย่างไรก็ตาม ควรระมัดระวังสภาพอากาศและสภาพร่างกายของตนเองด้วย', 0.00, '[\"67e46888c8070.jpg\"]', 10, 0, '2025-03-26 20:50:16', NULL),
(13, 'อุทยานสวรรค์ หรือที่รู้จักกันในชื่อ \"หนองสมบุญ\"', 'ตำบลปากน้ำโพ อำเภอเมืองนครสวรรค์ จังหวัดนครสวรรค์', 'อุทยานสวรรค์เป็นสถานที่ยอดนิยมสำหรับการออกกำลังกาย เช่น เดิน วิ่ง และปั่นจักรยาน ภายในสวนมีถนนลาดยางสำหรับกิจกรรมเหล่านี้ รวมถึงสนามกีฬาต่างๆ และเครื่องออกกำลังกายกลางแจ้ง การมาออกกำลังกายที่นี่ช่วยเสริมสร้างสุขภาพกายและใจได้อย่างดี ', 0.00, '[\"67e468d161534.jpg\"]', 10, 0, '2025-03-26 20:51:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `place_categories`
--

CREATE TABLE `place_categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `place_categories`
--

INSERT INTO `place_categories` (`category_id`, `name`, `created_at`, `updated_at`) VALUES
(10, 'ท่องเที่ยว', '2025-02-21 17:29:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `place_likes`
--

CREATE TABLE `place_likes` (
  `like_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `place_likes`
--

INSERT INTO `place_likes` (`like_id`, `place_id`, `user_id`, `like`, `created_at`, `updated_at`) VALUES
(11, 4, 4, 1, '2025-02-21 17:42:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `place_reviews`
--

CREATE TABLE `place_reviews` (
  `review_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `place_reviews`
--

INSERT INTO `place_reviews` (`review_id`, `place_id`, `user_id`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(6, 4, 4, 'ดีมากครับ', 5, '2025-03-27 02:28:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `text`, `image`, `created_at`, `updated_at`) VALUES
(12, 5, 'สวัสดีครับ ผมคือแอดมินของเว็บไซต์นี้นะครับ ใครที่อยากปรึกษาหรือสอบถามเกี่ยวกับเว็บโซต์นี้ สามารถทักมา หรือแสดงความคิดเห็นโพสต์นี้ได้เลยนะครับ', '67e45208de634.jpg', '2025-03-26 15:46:59', '2025-03-26 19:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`comment_id`, `post_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
(1, 9, 4, 'test', '2025-02-20 16:09:20', NULL),
(2, 9, 4, 'test', '2025-02-20 16:09:23', NULL),
(3, 9, 4, 'test', '2025-02-20 16:09:24', NULL),
(4, 12, 4, 'สวัสดีครับ', '2025-03-26 19:17:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`comment_id`, `post_id`, `user_id`, `like`, `created_at`, `updated_at`) VALUES
(4, 11, 4, 1, '2025-02-20 16:18:34', NULL),
(5, 10, 4, 1, '2025-02-20 16:18:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(150) NOT NULL,
  `lastname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(60) NOT NULL,
  `image` varchar(150) NOT NULL DEFAULT 'default-profile.png',
  `role` enum('user','admin','doctor') NOT NULL DEFAULT 'user',
  `active_status` enum('online','offline') NOT NULL DEFAULT 'offline',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `password`, `image`, `role`, `active_status`, `created_at`, `updated_at`) VALUES
(4, 'asd', 'asd', 'asd@gmail.com', '$2y$10$7fjDy0Y3dNb0KeoWeNcQxuBMJCvncATammDmWipieKWR4xAwY/OEC', '67b75078e5581.jpeg', 'user', 'offline', '2025-01-25 01:13:01', '2025-03-26 19:32:31'),
(5, 'admin', 'admin', 'admin@admin.com', '$2y$10$c19VUioqWilX2ukGXCtec.6cWoEDpjU9YWZV629bdit4PUWCjYFHS', '67e29ca7bd260.jpeg', 'admin', 'online', '2025-01-25 03:21:32', '2025-03-26 19:32:38'),
(13, 'som', 'sak', 'somsak@gmail.com', '$2y$10$jxbGCTup8Y73jq80uljx6ux.cKSvuvr2sFvq4F0Dv7wRrEOyXbj6m', 'default-profile.png', 'doctor', 'online', '2025-03-26 20:20:55', '2025-03-26 20:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `visitor_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`visitor_id`, `count`, `created_at`) VALUES
(1, 2002, '2025-01-24 20:59:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`);

--
-- Indexes for table `assessment_responses`
--
ALTER TABLE `assessment_responses`
  ADD PRIMARY KEY (`assessment_responses_id`),
  ADD KEY `assessment_responses_ibfk_1` (`user_id`),
  ADD KEY `assessment_responses_ibfk_2` (`assessment_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bot_responses`
--
ALTER TABLE `bot_responses`
  ADD PRIMARY KEY (`response_id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `news_id` (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news_likes`
--
ALTER TABLE `news_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `news_id` (`news_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payments_ibfk_1` (`user_id`),
  ADD KEY `payments_ibfk_2` (`booking_id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`place_id`);

--
-- Indexes for table `place_categories`
--
ALTER TABLE `place_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `place_likes`
--
ALTER TABLE `place_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `place_id` (`place_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `place_reviews`
--
ALTER TABLE `place_reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`visitor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assessment_responses`
--
ALTER TABLE `assessment_responses`
  MODIFY `assessment_responses_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `bot_responses`
--
ALTER TABLE `bot_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news_likes`
--
ALTER TABLE `news_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `place_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `place_categories`
--
ALTER TABLE `place_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `place_likes`
--
ALTER TABLE `place_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `place_reviews`
--
ALTER TABLE `place_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment_responses`
--
ALTER TABLE `assessment_responses`
  ADD CONSTRAINT `assessment_responses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assessment_responses_ibfk_2` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`assessment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

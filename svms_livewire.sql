/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.2.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: svms_livewire
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES
(1,'user','User account was created','App\\Models\\User','created',1,NULL,NULL,'{\"attributes\":{\"name\":\"Heart Ramos\",\"username\":\"ramos.hart\",\"email\":null,\"role\":\"osa\",\"assigned_gate\":null}}',NULL,'2026-03-18 17:02:11','2026-03-18 17:02:11'),
(2,'user','User account was created','App\\Models\\User','created',2,NULL,NULL,'{\"attributes\":{\"name\":\"Guard Gate 2\",\"username\":\"guard2\",\"email\":null,\"role\":\"guard\",\"assigned_gate\":\"2\"}}',NULL,'2026-03-18 17:02:11','2026-03-18 17:02:11'),
(3,'user','User account was created','App\\Models\\User','created',3,NULL,NULL,'{\"attributes\":{\"name\":\"Guard Gate 4\",\"username\":\"guard4\",\"email\":null,\"role\":\"guard\",\"assigned_gate\":\"4\"}}',NULL,'2026-03-18 17:02:11','2026-03-18 17:02:11'),
(4,'user','User account was created','App\\Models\\User','created',4,NULL,NULL,'{\"attributes\":{\"name\":\"Guard Gate 6\",\"username\":\"guard6\",\"email\":null,\"role\":\"guard\",\"assigned_gate\":\"6\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(5,'violation_type','Violation type was created','App\\Models\\ViolationType','created',1,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.1\",\"name\":\"No ID or improper display of ID\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(6,'violation_type','Violation type was created','App\\Models\\ViolationType','created',2,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.2\",\"name\":\"Disruption of classes or any academic activity or school function\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(7,'violation_type','Violation type was created','App\\Models\\ViolationType','created',3,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.3\",\"name\":\"Bringing of vape inside the campus\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(8,'violation_type','Violation type was created','App\\Models\\ViolationType','created',4,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.4\",\"name\":\"Smoking or vaping inside the school premises\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(9,'violation_type','Violation type was created','App\\Models\\ViolationType','created',5,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.5\",\"name\":\"Intoxication or being under the influence of liquor or prohibited substances\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(10,'violation_type','Violation type was created','App\\Models\\ViolationType','created',6,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.6\",\"name\":\"Possession of alcoholic beverages and e-cigarettes (vape) on campus\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(11,'violation_type','Violation type was created','App\\Models\\ViolationType','created',7,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.7\",\"name\":\"Misuse of University Facilities\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(12,'violation_type','Violation type was created','App\\Models\\ViolationType','created',8,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.8\",\"name\":\"Use of obscene or vulgar language in person, online, or in any form of communication\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(13,'violation_type','Violation type was created','App\\Models\\ViolationType','created',9,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.9\",\"name\":\"Littering (Plus P100 fine)\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(14,'violation_type','Violation type was created','App\\Models\\ViolationType','created',10,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.10\",\"name\":\"Bringing in Styrofoam (Plus P500 fine)\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(15,'violation_type','Violation type was created','App\\Models\\ViolationType','created',11,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.11\",\"name\":\"Tampering with electrical switches and other University fixtures or gadgets\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(16,'violation_type','Violation type was created','App\\Models\\ViolationType','created',12,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.12\",\"name\":\"Public display of intimacy and other such acts that offend the sensibilities of the community\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(17,'violation_type','Violation type was created','App\\Models\\ViolationType','created',13,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.13\",\"name\":\"Use of classroom and other school facilities without reservation or permission\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(18,'violation_type','Violation type was created','App\\Models\\ViolationType','created',14,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.14\",\"name\":\"Eating in the Laboratories\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(19,'violation_type','Violation type was created','App\\Models\\ViolationType','created',15,NULL,NULL,'{\"attributes\":{\"code\":\"C.1.15\",\"name\":\"Dress Code\",\"classification\":\"Minor\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(20,'violation_type','Violation type was created','App\\Models\\ViolationType','created',16,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.1\",\"name\":\"Any form of cheating or academic dishonesty on an examination\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(21,'violation_type','Violation type was created','App\\Models\\ViolationType','created',17,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.2\",\"name\":\"Fraud or use of fabricated\\/altered data or possession of leaked examination papers\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(22,'violation_type','Violation type was created','App\\Models\\ViolationType','created',18,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.3\",\"name\":\"False representation in an examination or completing assessment for another person\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(23,'violation_type','Violation type was created','App\\Models\\ViolationType','created',19,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.4\",\"name\":\"Erasing, removing, tampering with, or destroying official notices and posters\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(24,'violation_type','Violation type was created','App\\Models\\ViolationType','created',20,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.5\",\"name\":\"Disrespect to a teacher, other university personnel, or fellow student\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(25,'violation_type','Violation type was created','App\\Models\\ViolationType','created',21,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.6\",\"name\":\"Any form of vandalism (writing\\/drawing on walls, furniture, books, etc.)\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(26,'violation_type','Violation type was created','App\\Models\\ViolationType','created',22,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.7\",\"name\":\"Any form of gambling on campus or at off-campus university functions\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(27,'violation_type','Violation type was created','App\\Models\\ViolationType','created',23,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.8\",\"name\":\"IT misuse (unauthorized use, altering information, damaging data, etc.)\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(28,'violation_type','Violation type was created','App\\Models\\ViolationType','created',24,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.9\",\"name\":\"Commission of a fourth minor violation\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(29,'violation_type','Violation type was created','App\\Models\\ViolationType','created',25,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.10\",\"name\":\"Disrespect to teacher or university personnel (in person, online, or any communication)\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(30,'violation_type','Violation type was created','App\\Models\\ViolationType','created',26,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.11\",\"name\":\"Coming onto campus under the influence of alcohol\",\"classification\":\"Major - Suspension\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(31,'violation_type','Violation type was created','App\\Models\\ViolationType','created',27,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.12\",\"name\":\"Bribery or offering inducements to influence assessment or grades\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(32,'violation_type','Violation type was created','App\\Models\\ViolationType','created',28,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.13\",\"name\":\"Intentionally making false statement or fraudulent act related to University\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(33,'violation_type','Violation type was created','App\\Models\\ViolationType','created',29,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.14\",\"name\":\"Unauthorized solicitation or collection of money or instruments\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(34,'violation_type','Violation type was created','App\\Models\\ViolationType','created',30,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.15\",\"name\":\"Misuse of university\\/student funds\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(35,'violation_type','Violation type was created','App\\Models\\ViolationType','created',31,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.16\",\"name\":\"Borrowing, lending, or using another person\'s ID \\/ Tampering or use of fake ID\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(36,'violation_type','Violation type was created','App\\Models\\ViolationType','created',32,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.17\",\"name\":\"Forging or tampering with official university records or transfer forms\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(37,'violation_type','Violation type was created','App\\Models\\ViolationType','created',33,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.18\",\"name\":\"Plagiarism or using another person\'s work without acknowledgment\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(38,'violation_type','Violation type was created','App\\Models\\ViolationType','created',34,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.19\",\"name\":\"Instigating, leading, or participating in unlawful activity disrupting classes\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(39,'violation_type','Violation type was created','App\\Models\\ViolationType','created',35,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.20\",\"name\":\"Criminal act proven in court\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(40,'violation_type','Violation type was created','App\\Models\\ViolationType','created',36,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.21\",\"name\":\"Possession or use of firecrackers and other dangerous compounds on campus\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(41,'violation_type','Violation type was created','App\\Models\\ViolationType','created',37,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.22\",\"name\":\"Participation in scandalous or immoral acts causing ill-repute to University\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(42,'violation_type','Violation type was created','App\\Models\\ViolationType','created',38,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.23\",\"name\":\"Possession or distribution of pornography and related materials\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(43,'violation_type','Violation type was created','App\\Models\\ViolationType','created',39,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.24\",\"name\":\"Prostitution or involvement in sexual activity for payment\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(44,'violation_type','Violation type was created','App\\Models\\ViolationType','created',40,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.25\",\"name\":\"Misrepresentation or unauthorized use of Ateneo de Zamboanga University name\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(45,'violation_type','Violation type was created','App\\Models\\ViolationType','created',41,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.26\",\"name\":\"Theft, Pilferage, and\\/or robbery of any form\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(46,'violation_type','Violation type was created','App\\Models\\ViolationType','created',42,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.27\",\"name\":\"Data privacy violation (stealing or attempting to steal another person\'s data)\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(47,'violation_type','Violation type was created','App\\Models\\ViolationType','created',43,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.28\",\"name\":\"Physical assault\\/verbal assault\\/provocation\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(48,'violation_type','Violation type was created','App\\Models\\ViolationType','created',44,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.29\",\"name\":\"Fighting or any form of violence on campus or at university functions\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(49,'violation_type','Violation type was created','App\\Models\\ViolationType','created',45,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.30\",\"name\":\"Assault on or threats to teacher and other university personnel\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(50,'violation_type','Violation type was created','App\\Models\\ViolationType','created',46,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.31\",\"name\":\"Bullying (using any means to intimidate a student or community member)\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(51,'violation_type','Violation type was created','App\\Models\\ViolationType','created',47,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.32\",\"name\":\"Participating in any action degrading University\'s IT performance\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(52,'violation_type','Violation type was created','App\\Models\\ViolationType','created',48,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.33\",\"name\":\"Membership in subversive organizations or those inconsistent with University values\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(53,'violation_type','Violation type was created','App\\Models\\ViolationType','created',49,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.34\",\"name\":\"Membership in Greek-lettered organizations or similar societies\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(54,'violation_type','Violation type was created','App\\Models\\ViolationType','created',50,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.35\",\"name\":\"Sexual Harassment\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(55,'violation_type','Violation type was created','App\\Models\\ViolationType','created',51,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.36\",\"name\":\"Extortion\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(56,'violation_type','Violation type was created','App\\Models\\ViolationType','created',52,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.37\",\"name\":\"Cyberbullying\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(57,'violation_type','Violation type was created','App\\Models\\ViolationType','created',53,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.38\",\"name\":\"Coming onto campus under the influence of prohibited substances\",\"classification\":\"Major - Dismissal\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(58,'violation_type','Violation type was created','App\\Models\\ViolationType','created',54,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.39\",\"name\":\"Involvement in terrorism or radical extremism\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(59,'violation_type','Violation type was created','App\\Models\\ViolationType','created',55,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.40\",\"name\":\"Possession or use of deadly weapons and explosives\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(60,'violation_type','Violation type was created','App\\Models\\ViolationType','created',56,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.41\",\"name\":\"Hazing or any act of initiation rites that injures, degrades, or harms\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(61,'violation_type','Violation type was created','App\\Models\\ViolationType','created',57,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.42\",\"name\":\"Threatening someone with infliction upon person, honor, or property\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(62,'violation_type','Violation type was created','App\\Models\\ViolationType','created',58,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.43\",\"name\":\"Misuse\\/abuse of IT resources or accessing university systems without authorization\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(63,'violation_type','Violation type was created','App\\Models\\ViolationType','created',59,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.44\",\"name\":\"Engaging in scandalous\\/immoral acts causing dishonor to University\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(64,'violation_type','Violation type was created','App\\Models\\ViolationType','created',60,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.45\",\"name\":\"Engaging in subversive acts as defined by national laws\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(65,'violation_type','Violation type was created','App\\Models\\ViolationType','created',61,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.46\",\"name\":\"Possessing, distributing, or using leaked examination papers and questions\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(66,'violation_type','Violation type was created','App\\Models\\ViolationType','created',62,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.47\",\"name\":\"Engaging in hooliganism\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(67,'violation_type','Violation type was created','App\\Models\\ViolationType','created',63,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.48\",\"name\":\"Threatening\\/assaulting a teacher and other university personnel\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(68,'violation_type','Violation type was created','App\\Models\\ViolationType','created',64,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.49\",\"name\":\"Instigating, leading, or participating in unlawful activity stopping classes\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(69,'violation_type','Violation type was created','App\\Models\\ViolationType','created',65,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.50\",\"name\":\"Unlawfully preventing faculty, personnel, or students from attending classes\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(70,'violation_type','Violation type was created','App\\Models\\ViolationType','created',66,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.51\",\"name\":\"Forging\\/tampering with university records or using altered transfer forms\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(71,'violation_type','Violation type was created','App\\Models\\ViolationType','created',67,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.52\",\"name\":\"Fraud or use of fabricated\\/altered data in assessment items\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(72,'violation_type','Violation type was created','App\\Models\\ViolationType','created',68,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.53\",\"name\":\"Bribery to influence assessment outcome or subject grade\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(73,'violation_type','Violation type was created','App\\Models\\ViolationType','created',69,NULL,NULL,'{\"attributes\":{\"code\":\"C.3.54\",\"name\":\"Possession, use, or distribution of prohibited or dangerous drugs\",\"classification\":\"Major - Expulsion\"}}',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_31_224900_create_violation_types_table',1),
(5,'2026_01_31_232035_create_violations_remarks_table',1),
(6,'2026_02_05_101716_create_violation_table',1),
(7,'2026_02_19_084802_create_violation_stage_templates_table',1),
(8,'2026_02_19_084850_create_violation_stages_table',1),
(9,'2026_03_08_113456_create_violation_delete_requests_table',1),
(10,'2026_03_13_135040_create_violation_update_requests_table',1),
(11,'2026_03_13_140636_create_violation_request_reasons_table',1),
(12,'2026_03_16_120325_create_notifications_table',1),
(13,'2026_03_16_213105_create_activity_log_table',1),
(14,'2026_03_16_213106_add_event_column_to_activity_log_table',1),
(15,'2026_03_16_213107_add_batch_uuid_column_to_activity_log_table',1),
(16,'2026_03_17_011154_add_school_year_to_violations_table',1),
(17,'2026_03_17_015125_create_settings_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'school_year','2025-2026',NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('guard','osa','student') NOT NULL DEFAULT 'osa',
  `assigned_gate` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Heart Ramos','ramos.hart',NULL,NULL,'osa',NULL,'$2y$12$9skI6n350l0J1NlRCX1oou0yS1vpEChmq6oCbgkA3wR3hgO9OL.bC',NULL,'2026-03-18 17:02:11','2026-03-18 17:02:11',NULL),
(2,'Guard Gate 2','guard2',NULL,NULL,'guard','2','$2y$12$15fUY8/Dqpl/qGSrguSuB.PrD9dvlKjW6Vwde.zozij6469IPCYYK',NULL,'2026-03-18 17:02:11','2026-03-18 17:02:11',NULL),
(3,'Guard Gate 4','guard4',NULL,NULL,'guard','4','$2y$12$aXlWmlKRV6C.aLoTVx5nDuOG.Yp2AjYbegUDvEmMjAs1fliSLJxHG',NULL,'2026-03-18 17:02:11','2026-03-18 17:02:11',NULL),
(4,'Guard Gate 6','guard6',NULL,NULL,'guard','6','$2y$12$u7zPZ9cCuojeZDvUCZ06BeHS9LibbylnTDT42KOtYgusahm9jCa8a',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_delete_requests`
--

DROP TABLE IF EXISTS `violation_delete_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_delete_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_id` bigint(20) unsigned NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('pending','approved','denied') NOT NULL DEFAULT 'pending',
  `denial_reason` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_delete_requests_violation_id_foreign` (`violation_id`),
  KEY `violation_delete_requests_requested_by_foreign` (`requested_by`),
  KEY `violation_delete_requests_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `violation_delete_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  CONSTRAINT `violation_delete_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `violation_delete_requests_violation_id_foreign` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_delete_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_delete_requests` WRITE;
/*!40000 ALTER TABLE `violation_delete_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `violation_delete_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_remarks`
--

DROP TABLE IF EXISTS `violation_remarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_remarks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_type_id` bigint(20) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_remarks_violation_type_id_foreign` (`violation_type_id`),
  CONSTRAINT `violation_remarks_violation_type_id_foreign` FOREIGN KEY (`violation_type_id`) REFERENCES `violation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_remarks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_remarks` WRITE;
/*!40000 ALTER TABLE `violation_remarks` DISABLE KEYS */;
INSERT INTO `violation_remarks` VALUES
(1,1,'Improper wearing of ID','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(2,1,'ID left at home','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(3,1,'No ID presented','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(4,1,'ID not visible','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(5,2,'Talking loudly during class','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(6,2,'Unauthorized use of phone','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(7,2,'Interrupting the instructor','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(8,2,'Causing disturbance during school activity','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(9,3,'Vape found in bag','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(10,3,'Vape confiscated at gate','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(11,3,'Possession of e-cigarette','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(12,4,'Smoking in restroom','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(13,4,'Vaping in hallway','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(14,4,'Smoking in restricted area','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(15,5,'Smell of alcohol','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(16,5,'Appeared intoxicated','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(17,5,'Admitted drinking before class','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(18,6,'Alcohol found in bag','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(19,6,'Possession of liquor','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(20,6,'Possession of vape device','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(21,7,'Unauthorized use of equipment','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(22,7,'Improper use of classroom','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(23,7,'Facility damage due to misuse','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(24,8,'Verbal profanity','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(25,8,'Offensive online message','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(26,8,'Vulgar language toward student','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(27,9,'Trash left in hallway','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(28,9,'Improper disposal of waste','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(29,9,'Littering in campus grounds','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(30,10,'Styrofoam food container','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(31,10,'Styrofoam cup brought inside campus','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(32,11,'Unauthorized switch operation','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(33,11,'Tampered electrical outlet','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(34,11,'Modified classroom equipment','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(35,12,'Inappropriate physical contact','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(36,12,'Public display of affection','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(37,13,'No reservation on record','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(38,13,'Unauthorized room usage','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(39,14,'Food brought inside lab','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(40,14,'Eating during laboratory session','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(41,15,'Revealing outfit','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(42,15,'No uniform','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(43,15,'Incomplete uniform','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(44,15,'Improper uniform','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(45,15,'No school ID','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(46,15,'Improper footwear','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(47,15,'Wearing slippers','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(48,15,'Colored socks','2026-03-18 17:02:12','2026-03-18 17:02:12');
/*!40000 ALTER TABLE `violation_remarks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_request_reasons`
--

DROP TABLE IF EXISTS `violation_request_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_request_reasons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_request_reasons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_request_reasons` WRITE;
/*!40000 ALTER TABLE `violation_request_reasons` DISABLE KEYS */;
INSERT INTO `violation_request_reasons` VALUES
(1,'delete','Violation was recorded in error.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(2,'delete','Student was misidentified.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(3,'delete','Violation was already resolved.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(4,'delete','Duplicate entry.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(5,'update','Remark was entered incorrectly.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(6,'update','Remark does not match the actual incident.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(7,'update','Additional context needs to be added.','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(8,'update','Spelling or grammar correction needed.','2026-03-18 17:02:12','2026-03-18 17:02:12');
/*!40000 ALTER TABLE `violation_request_reasons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_stage_templates`
--

DROP TABLE IF EXISTS `violation_stage_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_stage_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `offense_key` varchar(255) NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_stage_templates`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_stage_templates` WRITE;
/*!40000 ALTER TABLE `violation_stage_templates` DISABLE KEYS */;
INSERT INTO `violation_stage_templates` VALUES
(1,'minor_1',1,'Oral Reprimand','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(2,'minor_2',1,'Written Reprimand','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(3,'minor_2',2,'Response Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(4,'minor_2',3,'Start Community Service','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(5,'minor_2',4,'Daily Time Record (DTR)','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(6,'minor_3',1,'Assessment','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(7,'minor_3',2,'Response Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(8,'minor_3',3,'Suspension Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(9,'minor_3',4,'Started Suspension','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(10,'minor_3',5,'Visit CGCO','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(11,'minor_3',6,'Visit OSA','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(12,'major_suspension',1,'Incident/Complaint Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(13,'major_suspension',2,'Response Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(14,'major_suspension',3,'Assessment at OSA','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(15,'major_suspension',4,'BOD Discussion','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(16,'major_suspension',5,'Decide on a Sanction','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(17,'major_dismissal',1,'Incident/Complaint Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(18,'major_dismissal',2,'Response Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(19,'major_dismissal',3,'Assessment at OSA','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(20,'major_dismissal',4,'BOD Discussion','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(21,'major_dismissal',5,'Decide on a Sanction','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(22,'major_expulsion',1,'Incident/Complaint Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(23,'major_expulsion',2,'Response Letter','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(24,'major_expulsion',3,'Assessment at OSA','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(25,'major_expulsion',4,'BOD Discussion','2026-03-18 17:02:12','2026-03-18 17:02:12'),
(26,'major_expulsion',5,'Decide on a Sanction','2026-03-18 17:02:12','2026-03-18 17:02:12');
/*!40000 ALTER TABLE `violation_stage_templates` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_stages`
--

DROP TABLE IF EXISTS `violation_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_id` bigint(20) unsigned NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `remark` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `violation_stages_violation_id_order_unique` (`violation_id`,`order`),
  CONSTRAINT `violation_stages_violation_id_foreign` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_stages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_stages` WRITE;
/*!40000 ALTER TABLE `violation_stages` DISABLE KEYS */;
/*!40000 ALTER TABLE `violation_stages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_types`
--

DROP TABLE IF EXISTS `violation_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `classification` enum('Minor','Major - Suspension','Major - Dismissal','Major - Expulsion') NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `violation_types_code_unique` (`code`),
  KEY `violation_types_classification_index` (`classification`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_types`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_types` WRITE;
/*!40000 ALTER TABLE `violation_types` DISABLE KEYS */;
INSERT INTO `violation_types` VALUES
(1,'C.1.1','No ID or improper display of ID','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(2,'C.1.2','Disruption of classes or any academic activity or school function','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(3,'C.1.3','Bringing of vape inside the campus','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(4,'C.1.4','Smoking or vaping inside the school premises','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(5,'C.1.5','Intoxication or being under the influence of liquor or prohibited substances','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(6,'C.1.6','Possession of alcoholic beverages and e-cigarettes (vape) on campus','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(7,'C.1.7','Misuse of University Facilities','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(8,'C.1.8','Use of obscene or vulgar language in person, online, or in any form of communication','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(9,'C.1.9','Littering (Plus P100 fine)','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(10,'C.1.10','Bringing in Styrofoam (Plus P500 fine)','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(11,'C.1.11','Tampering with electrical switches and other University fixtures or gadgets','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(12,'C.1.12','Public display of intimacy and other such acts that offend the sensibilities of the community','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(13,'C.1.13','Use of classroom and other school facilities without reservation or permission','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(14,'C.1.14','Eating in the Laboratories','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(15,'C.1.15','Dress Code','Minor',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(16,'C.3.1','Any form of cheating or academic dishonesty on an examination','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(17,'C.3.2','Fraud or use of fabricated/altered data or possession of leaked examination papers','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(18,'C.3.3','False representation in an examination or completing assessment for another person','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(19,'C.3.4','Erasing, removing, tampering with, or destroying official notices and posters','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(20,'C.3.5','Disrespect to a teacher, other university personnel, or fellow student','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(21,'C.3.6','Any form of vandalism (writing/drawing on walls, furniture, books, etc.)','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(22,'C.3.7','Any form of gambling on campus or at off-campus university functions','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(23,'C.3.8','IT misuse (unauthorized use, altering information, damaging data, etc.)','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(24,'C.3.9','Commission of a fourth minor violation','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(25,'C.3.10','Disrespect to teacher or university personnel (in person, online, or any communication)','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(26,'C.3.11','Coming onto campus under the influence of alcohol','Major - Suspension',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(27,'C.3.12','Bribery or offering inducements to influence assessment or grades','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(28,'C.3.13','Intentionally making false statement or fraudulent act related to University','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(29,'C.3.14','Unauthorized solicitation or collection of money or instruments','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(30,'C.3.15','Misuse of university/student funds','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(31,'C.3.16','Borrowing, lending, or using another person\'s ID / Tampering or use of fake ID','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(32,'C.3.17','Forging or tampering with official university records or transfer forms','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(33,'C.3.18','Plagiarism or using another person\'s work without acknowledgment','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(34,'C.3.19','Instigating, leading, or participating in unlawful activity disrupting classes','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(35,'C.3.20','Criminal act proven in court','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(36,'C.3.21','Possession or use of firecrackers and other dangerous compounds on campus','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(37,'C.3.22','Participation in scandalous or immoral acts causing ill-repute to University','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(38,'C.3.23','Possession or distribution of pornography and related materials','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(39,'C.3.24','Prostitution or involvement in sexual activity for payment','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(40,'C.3.25','Misrepresentation or unauthorized use of Ateneo de Zamboanga University name','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(41,'C.3.26','Theft, Pilferage, and/or robbery of any form','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(42,'C.3.27','Data privacy violation (stealing or attempting to steal another person\'s data)','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(43,'C.3.28','Physical assault/verbal assault/provocation','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(44,'C.3.29','Fighting or any form of violence on campus or at university functions','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(45,'C.3.30','Assault on or threats to teacher and other university personnel','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(46,'C.3.31','Bullying (using any means to intimidate a student or community member)','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(47,'C.3.32','Participating in any action degrading University\'s IT performance','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(48,'C.3.33','Membership in subversive organizations or those inconsistent with University values','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(49,'C.3.34','Membership in Greek-lettered organizations or similar societies','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(50,'C.3.35','Sexual Harassment','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(51,'C.3.36','Extortion','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(52,'C.3.37','Cyberbullying','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(53,'C.3.38','Coming onto campus under the influence of prohibited substances','Major - Dismissal',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(54,'C.3.39','Involvement in terrorism or radical extremism','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(55,'C.3.40','Possession or use of deadly weapons and explosives','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(56,'C.3.41','Hazing or any act of initiation rites that injures, degrades, or harms','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(57,'C.3.42','Threatening someone with infliction upon person, honor, or property','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(58,'C.3.43','Misuse/abuse of IT resources or accessing university systems without authorization','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(59,'C.3.44','Engaging in scandalous/immoral acts causing dishonor to University','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(60,'C.3.45','Engaging in subversive acts as defined by national laws','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(61,'C.3.46','Possessing, distributing, or using leaked examination papers and questions','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(62,'C.3.47','Engaging in hooliganism','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(63,'C.3.48','Threatening/assaulting a teacher and other university personnel','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(64,'C.3.49','Instigating, leading, or participating in unlawful activity stopping classes','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(65,'C.3.50','Unlawfully preventing faculty, personnel, or students from attending classes','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(66,'C.3.51','Forging/tampering with university records or using altered transfer forms','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(67,'C.3.52','Fraud or use of fabricated/altered data in assessment items','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(68,'C.3.53','Bribery to influence assessment outcome or subject grade','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12'),
(69,'C.3.54','Possession, use, or distribution of prohibited or dangerous drugs','Major - Expulsion',NULL,'2026-03-18 17:02:12','2026-03-18 17:02:12');
/*!40000 ALTER TABLE `violation_types` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violation_update_requests`
--

DROP TABLE IF EXISTS `violation_update_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violation_update_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `violation_id` bigint(20) unsigned NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `new_remark` text NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('pending','approved','denied') NOT NULL DEFAULT 'pending',
  `denial_reason` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violation_update_requests_violation_id_foreign` (`violation_id`),
  KEY `violation_update_requests_requested_by_foreign` (`requested_by`),
  KEY `violation_update_requests_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `violation_update_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `violation_update_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `violation_update_requests_violation_id_foreign` FOREIGN KEY (`violation_id`) REFERENCES `violations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violation_update_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violation_update_requests` WRITE;
/*!40000 ALTER TABLE `violation_update_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `violation_update_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `violations`
--

DROP TABLE IF EXISTS `violations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `violations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `st_first_name` varchar(255) NOT NULL,
  `st_last_name` varchar(255) NOT NULL,
  `st_mi` varchar(255) DEFAULT NULL,
  `st_program` varchar(255) NOT NULL,
  `st_year` varchar(255) NOT NULL,
  `classification` enum('Minor','Major - Suspension','Major - Dismissal','Major - Expulsion') NOT NULL,
  `type_code` varchar(255) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `is_escalated` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `school_year` varchar(9) NOT NULL DEFAULT '2025-2026',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violations_recorded_by_foreign` (`recorded_by`),
  KEY `violations_student_id_classification_index` (`student_id`,`classification`),
  KEY `violations_type_code_type_name_index` (`type_code`,`type_name`),
  KEY `violations_created_at_index` (`created_at`),
  KEY `violations_status_classification_deleted_at_index` (`status`,`classification`,`deleted_at`),
  KEY `violations_classification_student_id_created_at_id_index` (`classification`,`student_id`,`created_at`,`id`),
  KEY `violations_student_id_index` (`student_id`),
  KEY `violations_classification_index` (`classification`),
  KEY `violations_is_escalated_index` (`is_escalated`),
  KEY `violations_status_school_year_is_active_deleted_at_index` (`status`,`school_year`,`is_active`,`deleted_at`),
  CONSTRAINT `violations_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `violations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `violations` WRITE;
/*!40000 ALTER TABLE `violations` DISABLE KEYS */;
/*!40000 ALTER TABLE `violations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-03-19  1:08:28

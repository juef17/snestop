CREATE DATABASE  IF NOT EXISTS `snestop` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `snestop`;
-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: snestop
-- ------------------------------------------------------
-- Server version	5.5.31-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Playlist`
--

DROP TABLE IF EXISTS `Playlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Playlist` (
  `idPlaylist` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `nbPlays` int(11) NOT NULL,
  `public` bit(1) NOT NULL,
  `randomize` bit(1) NOT NULL,
  `loop` bit(1) NOT NULL,
  PRIMARY KEY (`idPlaylist`),
  KEY `fk_Playlist_User` (`idUser`),
  CONSTRAINT `fk_Playlist_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PlaylistItem`
--

DROP TABLE IF EXISTS `PlaylistItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PlaylistItem` (
  `idPlaylist` int(11) NOT NULL,
  `idTrack` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`idPlaylist`,`idTrack`),
  KEY `fk_PlaylistItem_Playlist` (`idPlaylist`),
  KEY `fk_PlaylistItem_Track` (`idTrack`),
  CONSTRAINT `fk_PlaylistItem_Playlist` FOREIGN KEY (`idPlaylist`) REFERENCES `Playlist` (`idPlaylist`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_PlaylistItem_Track` FOREIGN KEY (`idTrack`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ShitTrack`
--

DROP TABLE IF EXISTS `ShitTrack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ShitTrack` (
  `idTrack` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idTrack`,`idUser`),
  KEY `fk_ShitTrack_User` (`idUser`),
  KEY `fk_ShitTrack_Track` (`idTrack`),
  CONSTRAINT `fk_ShitTrack_Track` FOREIGN KEY (`idTrack`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ShitTrack_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Review`
--

DROP TABLE IF EXISTS `Review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Review` (
  `idTrack` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `text` varchar(6000) NOT NULL,
  `approved` bit(1) NOT NULL,
  PRIMARY KEY (`idTrack`,`idUser`),
  KEY `fk_Review_Track` (`idTrack`),
  KEY `fk_Review_User` (`idUser`),
  CONSTRAINT `fk_Review_Track` FOREIGN KEY (`idTrack`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Review_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DuelResult`
--

DROP TABLE IF EXISTS `DuelResult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DuelResult` (
  `idTrackWon` int(11) NOT NULL,
  `idTrackLost` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idTrackWon`,`idTrackLost`,`idUser`),
  KEY `fk_DuelResult_TrackWon` (`idTrackWon`),
  KEY `fk_DuelResult_TrackLost` (`idTrackLost`),
  KEY `fk_DuelResult_User` (`idUser`),
  CONSTRAINT `fk_DuelResult_TrackLost` FOREIGN KEY (`idTrackLost`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_DuelResult_TrackWon` FOREIGN KEY (`idTrackWon`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_DuelResult_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CommunityRequest`
--

DROP TABLE IF EXISTS `CommunityRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CommunityRequest` (
  `idCommunityRequest` int(11) NOT NULL AUTO_INCREMENT,
  `URL` varchar(255) NOT NULL,
  `name` varchar(45) NOT NULL,
  `emailRequester` varchar(90) NOT NULL,
  PRIMARY KEY (`idCommunityRequest`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PlaylistVote`
--

DROP TABLE IF EXISTS `PlaylistVote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PlaylistVote` (
  `idUser` int(11) NOT NULL,
  `idPlaylist` int(11) NOT NULL,
  `voteType` tinyint(4) NOT NULL,
  PRIMARY KEY (`idUser`,`idPlaylist`),
  KEY `fk_PlaylistVote_User` (`idUser`),
  KEY `fk_PlaylistVote_Playlist` (`idPlaylist`),
  CONSTRAINT `fk_PlaylistVote_Playlist` FOREIGN KEY (`idPlaylist`) REFERENCES `Playlist` (`idPlaylist`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_PlaylistVote_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RatingCommunity`
--

DROP TABLE IF EXISTS `RatingCommunity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RatingCommunity` (
  `idCommunity` int(11) NOT NULL,
  `idTrack` int(11) NOT NULL,
  `glicko2rating` float NOT NULL,
  `glicko2RD` float NOT NULL,
  `glicko2sigma` float NOT NULL,
  `eloRating` float NOT NULL,
  PRIMARY KEY (`idCommunity`,`idTrack`),
  KEY `fk_RatingCommunity_Community` (`idCommunity`),
  KEY `fk_RatingCommunity_Track` (`idTrack`),
  CONSTRAINT `fk_RatingCommunity_Community` FOREIGN KEY (`idCommunity`) REFERENCES `Community` (`idCommunity`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_RatingCommunity_Track` FOREIGN KEY (`idTrack`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Track`
--

DROP TABLE IF EXISTS `Track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Track` (
  `idTrack` int(11) NOT NULL AUTO_INCREMENT,
  `idGame` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `length` int(11) NOT NULL,
  `fadeLength` int(11) NOT NULL,
  `composer` varchar(45) NOT NULL,
  `turnedOffByAdmin` bit(1) NOT NULL,
  `screenshotURL` varchar(255) NOT NULL,
  `isJingle` bit(1) NOT NULL,
  `glicko2RD` float NOT NULL,
  `glicko2rating` float NOT NULL,
  `glicko2sigma` float NOT NULL,
  `eloRating` float NOT NULL,
  `spcURL` varchar(255) NOT NULL,
  `spcEncodedURL` varchar(255) NOT NULL,
  PRIMARY KEY (`idTrack`),
  KEY `fk_Track_Game` (`idGame`),
  CONSTRAINT `fk_Track_Game` FOREIGN KEY (`idGame`) REFERENCES `Game` (`idGame`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Game`
--

DROP TABLE IF EXISTS `Game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Game` (
  `idGame` int(11) NOT NULL AUTO_INCREMENT,
  `titleJap` varchar(255) NOT NULL,
  `titleEng` varchar(255) NOT NULL,
  `screenshotURL` varchar(255) NOT NULL,
  `rsnFileURL` varchar(255) NOT NULL,
  PRIMARY KEY (`idGame`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Community`
--

DROP TABLE IF EXISTS `Community`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Community` (
  `idCommunity` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `token` varchar(45) NOT NULL,
  PRIMARY KEY (`idCommunity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RatingPersonal`
--

DROP TABLE IF EXISTS `RatingPersonal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RatingPersonal` (
  `idUser` int(11) NOT NULL,
  `idTrack` int(11) NOT NULL,
  `glicko2rating` float NOT NULL,
  `glicko2RD` float NOT NULL,
  `glicko2sigma` float NOT NULL,
  `eloRating` float NOT NULL,
  PRIMARY KEY (`idUser`,`idTrack`),
  KEY `fk_RatingPersonal_User` (`idUser`),
  KEY `fk_RatingPersonal_Track` (`idTrack`),
  CONSTRAINT `fk_RatingPersonal_Track` FOREIGN KEY (`idTrack`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_RatingPersonal_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ReviewVote`
--

DROP TABLE IF EXISTS `ReviewVote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ReviewVote` (
  `idUser` int(11) NOT NULL,
  `idReviewUser` int(11) NOT NULL,
  `idReviewTrack` int(11) NOT NULL,
  `voteType` tinyint(4) NOT NULL,
  PRIMARY KEY (`idUser`,`idReviewTrack`,`idReviewUser`),
  KEY `fk_ReviewVote_User` (`idUser`),
  KEY `fk_ReviewVote_Review` (`idReviewTrack`,`idReviewUser`),
  CONSTRAINT `fk_ReviewVote_Review` FOREIGN KEY (`idReviewTrack`, `idReviewUser`) REFERENCES `Review` (`idTrack`, `idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ReviewVote_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TrackRequest`
--

DROP TABLE IF EXISTS `TrackRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TrackRequest` (
  `idTrackRequest` int(11) NOT NULL AUTO_INCREMENT,
  `idUserRequester` int(11) NOT NULL,
  `game` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `trackURL` varchar(255) NOT NULL,
  PRIMARY KEY (`idTrackRequest`),
  KEY `fk_TrackRequest_User` (`idUserRequester`),
  CONSTRAINT `fk_TrackRequest_User` FOREIGN KEY (`idUserRequester`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `idCommunity` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `language` varchar(25) NOT NULL,
  `canStreamMP3` bit(1) NOT NULL DEFAULT b'0',
  `autoplay` bit(1) NOT NULL,
  `userName` varchar(45) NOT NULL,
  `isAdmin` bit(1) NOT NULL,
  `rememberMeSnestopToken` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  KEY `fk_User_Community` (`idCommunity`),
  CONSTRAINT `fk_User_Community` FOREIGN KEY (`idCommunity`) REFERENCES `Community` (`idCommunity`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `News`
--

DROP TABLE IF EXISTS `News`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `News` (
  `idNews` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` varchar(2000) NOT NULL,
  `idUser` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`idNews`),
  KEY `fk_News_User` (`idUser`),
  CONSTRAINT `fk_News_User` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TrackScreenshotRequest`
--

DROP TABLE IF EXISTS `TrackScreenshotRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TrackScreenshotRequest` (
  `idTrack` int(11) NOT NULL,
  `idUserRequester` int(11) NOT NULL,
  `screenshotURL` varchar(255) NOT NULL,
  PRIMARY KEY (`idTrack`,`idUserRequester`),
  KEY `fk_TrackScreenshotRequest_Track` (`idTrack`),
  KEY `fk_TrackScreenshotRequest_User` (`idUserRequester`),
  CONSTRAINT `fk_TrackScreenshotRequest_Track` FOREIGN KEY (`idTrack`) REFERENCES `Track` (`idTrack`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_TrackScreenshotRequest_User` FOREIGN KEY (`idUserRequester`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GameScreenshotRequest`
--

DROP TABLE IF EXISTS `GameScreenshotRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GameScreenshotRequest` (
  `idGame` int(11) NOT NULL,
  `idUserRequester` int(11) NOT NULL,
  `screenshotURL` varchar(255) NOT NULL,
  PRIMARY KEY (`idGame`,`idUserRequester`),
  KEY `fk_GameScreenshotRequest_Game` (`idGame`),
  KEY `fk_GameScreenshotRequest_User` (`idUserRequester`),
  CONSTRAINT `fk_GameScreenshotRequest_Game` FOREIGN KEY (`idGame`) REFERENCES `Game` (`idGame`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_GameScreenshotRequest_User` FOREIGN KEY (`idUserRequester`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MistakeRequest`
--

DROP TABLE IF EXISTS `MistakeRequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MistakeRequest` (
  `idMistakeRequest` int(11) NOT NULL AUTO_INCREMENT,
  `idUserRequester` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`idMistakeRequest`),
  KEY `fk_MistakeRequest_User` (`idUserRequester`),
  CONSTRAINT `fk_MistakeRequest_User` FOREIGN KEY (`idUserRequester`) REFERENCES `User` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-11-03 18:04:41

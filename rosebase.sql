-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2016 at 03:26 PM
-- Server version: 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rosebase`
--

-- --------------------------------------------------------

--
-- Table structure for table `brendovi`
--

DROP TABLE IF EXISTS `brendovi`;
CREATE TABLE IF NOT EXISTS `brendovi` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `naziv` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `opis` text COLLATE utf8_slovenian_ci,
  `status` varchar(2) COLLATE utf8_slovenian_ci NOT NULL DEFAULT 'da',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctarife`
--

DROP TABLE IF EXISTS `ctarife`;
CREATE TABLE IF NOT EXISTS `ctarife` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `sifra` varchar(20) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'sifra carinske tarife',
  `stopa` decimal(5,2) UNSIGNED NOT NULL COMMENT 'u procentima',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `sifra` (`sifra`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='Carinske tarife';

-- --------------------------------------------------------

--
-- Table structure for table `gpartnera`
--

DROP TABLE IF EXISTS `gpartnera`;
CREATE TABLE IF NOT EXISTS `gpartnera` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'naziv grupe partnera',
  `cena` int(1) UNSIGNED NOT NULL COMMENT 'obracun cena',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='grupe partnera';

-- --------------------------------------------------------

--
-- Table structure for table `gproizvoda`
--

DROP TABLE IF EXISTS `gproizvoda`;
CREATE TABLE IF NOT EXISTS `gproizvoda` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `opis` text COLLATE utf8_slovenian_ci,
  `status` varchar(2) COLLATE utf8_slovenian_ci NOT NULL DEFAULT 'da',
  `nadgrupa` int(1) NOT NULL,
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='grupe proizvoda';

-- --------------------------------------------------------

--
-- Table structure for table `kurs`
--

DROP TABLE IF EXISTS `kurs`;
CREATE TABLE IF NOT EXISTS `kurs` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `datum` date NOT NULL,
  `kcar` decimal(14,4) NOT NULL COMMENT 'carinski kurs',
  `kbank` decimal(14,4) NOT NULL COMMENT 'bankarski kurs',
  `ksred` decimal(14,4) NOT NULL COMMENT 'srednji kurs',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='kurs eura i dinara';

-- --------------------------------------------------------

--
-- Table structure for table `msklad`
--

DROP TABLE IF EXISTS `msklad`;
CREATE TABLE IF NOT EXISTS `msklad` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idmsklad` int(10) UNSIGNED NOT NULL,
  `datum` date NOT NULL,
  `skladiz` int(3) NOT NULL,
  `skladu` int(3) NOT NULL,
  `proizvod` char(8) COLLATE utf8_slovenian_ci NOT NULL,
  `razlika` int(10) NOT NULL,
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9983 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='medjuskladistenje';

-- --------------------------------------------------------

--
-- Table structure for table `nabavka`
--

DROP TABLE IF EXISTS `nabavka`;
CREATE TABLE IF NOT EXISTS `nabavka` (
  `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ncarine` int(1) NOT NULL COMMENT 'način carinjenja',
  `datdostavnice` date NOT NULL COMMENT 'datum kreiranja dostavnice',
  `datprijemarobe` date NOT NULL COMMENT 'datum prijema robe',
  `dobavljac` int(3) NOT NULL,
  `brnarudzbenice` varchar(30) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'broj narudzbenice koja se salje nabavljacima',
  `skladiste` int(3) NOT NULL COMMENT 'u koje skladiste se dostavlja',
  `kursbanka` decimal(14,4) NOT NULL COMMENT 'eur/din u banci',
  `kurssred` decimal(14,4) NOT NULL COMMENT 'eur/rsd srednji kurs',
  `kurscarine` decimal(14,4) NOT NULL COMMENT 'eur/din na carini',
  `transport` decimal(12,2) NOT NULL COMMENT 'troskovi transporta',
  `ulaznipdv` decimal(12,2) NOT NULL,
  `neptroskoviuk` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'ukupni neposredni troskovi',
  `placeno` varchar(2) COLLATE utf8_slovenian_ci NOT NULL DEFAULT 'ne',
  `ukfnc` decimal(12,2) NOT NULL COMMENT 'Ukupna finalna cena',
  `ukpcb` decimal(12,2) NOT NULL COMMENT 'Projektovana ukupna vrednost prodate robe',
  `ukrazlika` decimal(12,2) NOT NULL COMMENT 'Razlika ukupne prodate robe i finalne cene',
  `ukmarza` decimal(5,2) NOT NULL COMMENT 'Ukupna marža uvoza',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `ID_2` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nabavkaitems`
--

DROP TABLE IF EXISTS `nabavkaitems`;
CREATE TABLE IF NOT EXISTS `nabavkaitems` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nabavka` int(10) NOT NULL,
  `idunabavci` int(3) NOT NULL,
  `proizvod` char(8) COLLATE utf8_slovenian_ci NOT NULL,
  `kolicina` int(10) NOT NULL,
  `cenaueur` decimal(14,4) NOT NULL COMMENT 'cena u eurima',
  `transportpr` decimal(5,2) NOT NULL COMMENT 'procenat troskova transporta',
  `transportiznos` decimal(12,2) NOT NULL COMMENT 'troskovi transporta',
  `cstopa` decimal(5,2) NOT NULL COMMENT 'carinska stopa',
  `neptroskovi` decimal(12,2) NOT NULL COMMENT 'neposredni troskovi',
  `nabcena` decimal(12,2) NOT NULL COMMENT 'nabavna cena',
  `razlika` decimal(12,2) NOT NULL COMMENT 'razlika nabavne cene i cene bez pdv-a',
  `mpbezpdv` decimal(12,2) NOT NULL COMMENT 'maloprodajna cena bez pdv-a',
  `marza` decimal(5,2) NOT NULL,
  `pdv` decimal(5,2) NOT NULL,
  `mpsapdv` decimal(12,2) NOT NULL COMMENT 'maloprodajna cena sa pdv-om',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2211 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='nabavka po pojedinacnim proizvodima';

-- --------------------------------------------------------

--
-- Table structure for table `partneri`
--

DROP TABLE IF EXISTS `partneri`;
CREATE TABLE IF NOT EXISTS `partneri` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gpartnera` int(2) UNSIGNED NOT NULL COMMENT 'grupa partnera',
  `ime` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `prezime` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `pol` varchar(10) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'muskarac/zena',
  `ulicaibr` varchar(50) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'ulica i broj',
  `mesto` varchar(50) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'naseljeno mesto',
  `drzava` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `firma` varchar(100) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `pib` int(20) DEFAULT NULL COMMENT 'samo za pravna lica',
  `maticni` int(30) DEFAULT NULL COMMENT 'samo za pravna lica',
  `telefon` varchar(50) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'samo za pravna lica',
  `mobilni` varchar(50) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `ID_2` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=349 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pobroj`
--

DROP TABLE IF EXISTS `pobroj`;
CREATE TABLE IF NOT EXISTS `pobroj` (
  `broj` varchar(6) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `mesto` varchar(29) COLLATE utf8_slovenian_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`mesto`),
  UNIQUE KEY `mesto` (`mesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popis`
--

DROP TABLE IF EXISTS `popis`;
CREATE TABLE IF NOT EXISTS `popis` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idpopisa` int(10) UNSIGNED NOT NULL,
  `datum` date NOT NULL,
  `proizvod` int(8) NOT NULL,
  `skladiste` int(3) UNSIGNED NOT NULL,
  `kolknjiz` int(10) NOT NULL COMMENT 'knjizena kolicina',
  `kolpopis` int(10) NOT NULL COMMENT 'popisana kolicina',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=37128 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='popis robe';

-- --------------------------------------------------------

--
-- Table structure for table `prodaja`
--

DROP TABLE IF EXISTS `prodaja`;
CREATE TABLE IF NOT EXISTS `prodaja` (
  `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kupac` int(10) NOT NULL,
  `brpracuna` varchar(30) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'broj predračuna',
  `brracuna` varchar(30) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'Broj računa',
  `brizvoda` varchar(20) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'Broj izvoda iz banke',
  `rok` date NOT NULL COMMENT 'rok plaćanja',
  `datprometa` date NOT NULL COMMENT 'datum prometa',
  `bifr` int(5) UNSIGNED DEFAULT NULL COMMENT 'broj izdatih fiskalnih računa',
  `nacdost` int(1) NOT NULL COMMENT 'način dostave',
  `brracunau` varchar(30) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'Broj računa za uplatu',
  `pozivnb` varchar(30) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'Poziv na broj',
  `skladiste` int(3) NOT NULL COMMENT 'u koje skladiste se dostavlja',
  `tisporuke` decimal(12,2) DEFAULT NULL COMMENT 'Troškovi isporuke',
  `bezpopusta` decimal(12,2) DEFAULT NULL COMMENT 'Cena bez popusta',
  `popust` decimal(12,2) DEFAULT NULL COMMENT 'Popust',
  `bezpdva` decimal(12,2) DEFAULT NULL COMMENT 'Cena bez PDVa',
  `iznospdv` decimal(12,2) DEFAULT NULL COMMENT 'Iznos PDVa',
  `zauplatu` decimal(12,2) DEFAULT NULL COMMENT 'Ukupna cena za uplatu',
  `zarada` decimal(12,2) DEFAULT NULL COMMENT 'Ukupna zarada',
  `konsignacija` int(11) DEFAULT NULL COMMENT 'vezano za konsignaciju',
  `tip` int(2) UNSIGNED NOT NULL COMMENT 'tip prodaje',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `ID_2` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1632 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodajaitems`
--

DROP TABLE IF EXISTS `prodajaitems`;
CREATE TABLE IF NOT EXISTS `prodajaitems` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `prodaja` int(10) NOT NULL,
  `iduprodaji` int(3) NOT NULL,
  `proizvod` char(8) COLLATE utf8_slovenian_ci NOT NULL,
  `kolicina` int(10) NOT NULL,
  `mpbezpdv` decimal(12,2) NOT NULL COMMENT 'maloprodajna cena bez pdv-a',
  `rabat` decimal(5,2) DEFAULT NULL COMMENT 'Rabat odnosno marža',
  `pdv` decimal(5,2) DEFAULT NULL COMMENT 'Vrednost pdva po proizvodu',
  `zarada` decimal(12,2) UNSIGNED DEFAULT NULL COMMENT 'zarada po proizvodu',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=22120 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='nabavka po pojedinacnim proizvodima';

-- --------------------------------------------------------

--
-- Table structure for table `proizvodi`
--

DROP TABLE IF EXISTS `proizvodi`;
CREATE TABLE IF NOT EXISTS `proizvodi` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sifra` char(8) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `sifrakasa` int(5) UNSIGNED DEFAULT NULL COMMENT 'Šifra u kasi',
  `barcode` int(30) DEFAULT NULL,
  `naziv` varchar(500) COLLATE utf8_slovenian_ci NOT NULL,
  `link` varchar(300) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `namgrupa` varchar(20) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'namenska grupa',
  `nadgrupa` varchar(20) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'nadredjena grupa',
  `grupa` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `brend` int(2) NOT NULL,
  `dobavljac` int(3) NOT NULL,
  `zapremina` int(5) NOT NULL,
  `tezinaneto` int(5) NOT NULL COMMENT 'tezina bez pakovanja',
  `tezinabruto` int(5) NOT NULL COMMENT 'tezina sa pakovanjem',
  `kolpak` int(3) NOT NULL COMMENT 'kolicina u pakovanju',
  `minzal` int(5) NOT NULL COMMENT 'minimalna zaliha',
  `cartar` int(2) NOT NULL COMMENT 'carinska tarifa',
  `pdv` decimal(4,2) NOT NULL COMMENT 'stopa pdv',
  `ncena` decimal(10,4) NOT NULL COMMENT 'nabavna cena u EUR',
  `pcena` decimal(8,2) NOT NULL COMMENT 'prodajna cena sa PDVom',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `sifra` (`sifra`),
  UNIQUE KEY `sifrakasa` (`sifrakasa`)
) ENGINE=InnoDB AUTO_INCREMENT=870 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skladista`
--

DROP TABLE IF EXISTS `skladista`;
CREATE TABLE IF NOT EXISTS `skladista` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8_slovenian_ci NOT NULL COMMENT 'naziv skladišta',
  `adresa` varchar(50) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `status` varchar(2) COLLATE utf8_slovenian_ci NOT NULL DEFAULT 'da' COMMENT 'aktivan/neaktivan',
  `oosoba` int(4) UNSIGNED NOT NULL COMMENT 'odgovorna osoba',
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `menjali` varchar(5000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8_slovenian_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_slovenian_ci NOT NULL,
  `salt` varchar(11) COLLATE utf8_slovenian_ci NOT NULL,
  `level` int(1) NOT NULL,
  `email` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `funkcija` int(1) DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `country` varchar(30) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `confcode` varchar(64) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `confcode2` varchar(64) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zalihe`
--

DROP TABLE IF EXISTS `zalihe`;
CREATE TABLE IF NOT EXISTS `zalihe` (
  `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `skladiste` int(3) NOT NULL,
  `proizvod` char(8) COLLATE utf8_slovenian_ci NOT NULL,
  `kolicina` int(10) NOT NULL,
  `uneo` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5794 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci COMMENT='zalihe proizvoda po skladistima';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 08 2010 г., 12:44
-- Версия сервера: 5.1.41
-- Версия PHP: 5.3.2-1ubuntu4.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `arenda`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `room_type` int(2) DEFAULT NULL,
  `districtID` int(2) NOT NULL,
  `streetID` int(2) NOT NULL,
  `group_ID` int(2) NOT NULL,
  `price` varchar(255) DEFAULT NULL,
  `fullad` text,
  `title` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `ads`
--


-- --------------------------------------------------------

--
-- Структура таблицы `districts`
--

CREATE TABLE IF NOT EXISTS `districts` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `district_name` text,
  `district_slug` text NOT NULL,
  `district_synonim` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `districts`
--


-- --------------------------------------------------------

--
-- Структура таблицы `names`
--

CREATE TABLE IF NOT EXISTS `names` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `order` int(2) DEFAULT NULL,
  `gender` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `names`
--


-- --------------------------------------------------------

--
-- Структура таблицы `phones`
--

CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `phones`
--


-- --------------------------------------------------------

--
-- Структура таблицы `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `quantity` int(8) NOT NULL,
  `section_name` text NOT NULL,
  `section_slug` text NOT NULL,
  `config` varchar(255) NOT NULL,
  `group` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `sections`
--


-- --------------------------------------------------------

--
-- Структура таблицы `streets`
--

CREATE TABLE IF NOT EXISTS `streets` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `street_name` text,
  `street_slug` text NOT NULL,
  `district_id` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `streets`
--


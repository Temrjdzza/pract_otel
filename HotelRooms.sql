-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 10 2025 г., 03:10
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mybd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `HotelRooms`
--

CREATE TABLE `HotelRooms` (
  `room_id` int(11) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `HotelRooms`
--

INSERT INTO `HotelRooms` (`room_id`, `room_type`, `price`, `capacity`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Люкс', 15000.00, 2, 'Президентский номер с видом на город', 0, '2025-06-09 17:30:00', '2025-06-09 17:30:00'),
(2, 'Стандарт', 5000.00, 2, 'Уютный номер с двуспальной кроватью', 1, '2025-06-09 17:30:00', '2025-06-09 17:30:00'),
(3, 'Полулюкс', 9000.00, 3, 'Номер повышенной комфортности с дополнительным местом', 0, '2025-06-09 17:30:00', '2025-06-09 17:35:00'),
(4, 'Апартаменты', 20000.00, 4, 'Двухкомнатные апартаменты с кухонной зоной', 1, '2025-06-09 17:30:00', '2025-06-09 17:30:00'),
(5, 'Студия', 7000.00, 2, 'Маленький уютный номер для одного человека', 0, '2025-06-09 17:30:00', '2025-06-09 17:30:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `HotelRooms`
--
ALTER TABLE `HotelRooms`
  ADD PRIMARY KEY (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

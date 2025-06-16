-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 16 2025 г., 12:02
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
-- Структура таблицы `Contacts`
--

CREATE TABLE `Contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Contacts`
--

INSERT INTO `Contacts` (`id`, `name`, `email`, `phone`) VALUES
(1, 'Иван Петров', 'ivan@example.com', '+7 912 345 67 89'),
(2, 'Мария Сидорова', 'maria@example.com', '+7 902 123 45 67'),
(3, 'Александр Иванов', 'alexander@example.com', '+7 911 890 12 34'),
(4, 'Екатерина Николаева', 'ekaterina@example.com', '+7 903 456 78 90'),
(5, 'Сергей Михайлов', 'sergey@example.com', '+7 905 234 56 78');

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
  `booked` varchar(300) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `HotelRooms`
--

INSERT INTO `HotelRooms` (`room_id`, `room_type`, `price`, `capacity`, `description`, `booked`, `created_at`, `updated_at`) VALUES
(1, 'Люкс', 15000.00, 2, 'Президентский номер с видом на город', 'Иванов Иван Иванович', '2025-06-09 17:30:00', '2025-06-12 18:24:48'),
(2, 'Стандарт', 5000.00, 2, 'Уютный номер с двуспальной кроватью', '', '2025-06-09 17:30:00', '2025-06-09 17:30:00'),
(3, 'Полулюкс', 9000.00, 3, 'Номер повышенной комфортности с дополнительным местом', '', '2025-06-09 17:30:00', '2025-06-09 17:35:00'),
(4, 'Апартаменты', 20000.00, 4, 'Двухкомнатные апартаменты с кухонной зоной', '', '2025-06-09 17:30:00', '2025-06-12 17:55:34'),
(5, 'Студия', 7000.00, 2, 'Маленький уютный номер для одного человека', '', '2025-06-09 17:30:00', '2025-06-09 17:30:00');

-- --------------------------------------------------------

--
-- Структура таблицы `hotel_reviews`
--

CREATE TABLE `hotel_reviews` (
  `id` int(11) NOT NULL,
  `fio` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` float NOT NULL,
  `publication_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Дамп данных таблицы `hotel_reviews`
--

INSERT INTO `hotel_reviews` (`id`, `fio`, `review`, `rating`, `publication_date`, `last_modified_date`) VALUES
(1, 'Петров Петр Петрович', 'Отель превосходит все ожидания! Чистые номера, вкусная еда, отличное расположение.', 5, '2025-06-14 06:50:49', '2025-06-16 10:01:48'),
(2, 'Иванова Мария Сергеевна', 'Персонал очень дружелюбный и внимательный. Номера оснащены всем необходимым.', 5, '2025-06-14 06:50:49', '2025-06-16 10:01:55'),
(3, 'Смирнов Алексей Михайлович', 'В целом неплохо, но могли бы добавить больше каналов на телевидении.', 4.5, '2025-06-14 06:50:49', '2025-06-16 10:01:14'),
(4, 'Кузнецова Елена Викторовна', 'Удобное расположение, но завтраки могли бы быть разнообразнее.', 4.5, '2025-06-14 06:50:49', '2025-06-16 10:01:37'),
(5, 'Николаева Анна Павловна', 'Были проблемы с интернетом в номере. Уборка могла бы быть чаще.', 4.5, '2025-06-14 06:50:49', '2025-06-16 10:02:12'),
(6, 'Михайлов Дмитрий Иванович', 'Задержали заселение на час. Не все приборы работали исправно.', 4, '2025-06-14 06:50:49', '2025-06-16 10:02:23'),
(7, 'Соколова Наталья Алексеевна', 'Рекомендую бронировать номера с видом на море. Спасибо за прекрасный отдых!', 4.5, '2025-06-14 06:50:49', '2025-06-16 10:02:38'),
(8, 'Васильев Сергей Николаевич', 'Хорошее соотношение цена-качество. Рекомендую всем.', 4, '2025-06-14 06:50:49', '2025-06-16 10:02:46');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Contacts`
--
ALTER TABLE `Contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `HotelRooms`
--
ALTER TABLE `HotelRooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Индексы таблицы `hotel_reviews`
--
ALTER TABLE `hotel_reviews`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Contacts`
--
ALTER TABLE `Contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `hotel_reviews`
--
ALTER TABLE `hotel_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

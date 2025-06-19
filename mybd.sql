-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 19 2025 г., 23:33
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
-- Структура таблицы `Bookings`
--

CREATE TABLE `Bookings` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `guest_name` varchar(300) NOT NULL,
  `booking_start` datetime NOT NULL,
  `booking_end` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Bookings`
--

INSERT INTO `Bookings` (`booking_id`, `room_id`, `guest_name`, `booking_start`, `booking_end`, `status`, `created_at`, `updated_at`) VALUES
(7, 1, 'Иванов Иван Иванович', '2025-06-20 16:00:00', '2025-06-22 12:00:00', 'active', '2025-06-17 08:18:23', '2025-06-17 08:18:23'),
(8, 3, 'Иванов Иван Иванович', '2025-06-20 16:00:00', '2025-06-22 12:00:00', 'active', '2025-06-18 05:48:01', '2025-06-18 05:48:01');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `HotelRooms`
--

INSERT INTO `HotelRooms` (`room_id`, `room_type`, `price`, `capacity`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Люкс', 15000.00, 2, 'Президентский номер с видом на город', '2025-06-17 07:31:01', '2025-06-19 15:28:54'),
(2, 'Стандарт', 5000.00, 2, 'Уютный номер с двуспальной кроватью', '2025-06-17 07:31:01', '2025-06-17 07:31:01'),
(3, 'Полулюкс', 9000.00, 3, 'Номер повышенной комфортности с дополнительным местом', '2025-06-17 07:31:01', '2025-06-17 07:31:01'),
(4, 'Апартаменты', 20000.00, 4, 'Двухкомнатные апартаменты с кухонной зоной', '2025-06-17 07:31:01', '2025-06-17 07:31:01'),
(5, 'Студия', 7000.00, 2, 'Маленький уютный номер для одного человека', '2025-06-17 07:31:01', '2025-06-17 07:31:01');

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

-- --------------------------------------------------------

--
-- Структура таблицы `RoomImages`
--

CREATE TABLE `RoomImages` (
  `image_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `image_url` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `RoomImages`
--

INSERT INTO `RoomImages` (`image_id`, `room_id`, `image_url`) VALUES
(1, 1, '/image/photo_lux.jpg'),
(2, 1, '/image/photo_lux1.jpg'),
(3, 3, '/image/photo_pollux.jpg'),
(4, 3, '/image/photo_pollux1.jpg'),
(5, 2, '/image/photo_standart.jpg'),
(6, 2, '/image/photo_standart1.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Bookings`
--
ALTER TABLE `Bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `room_id` (`room_id`);

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
-- Индексы таблицы `RoomImages`
--
ALTER TABLE `RoomImages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_room_id` (`room_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Bookings`
--
ALTER TABLE `Bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `Contacts`
--
ALTER TABLE `Contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `HotelRooms`
--
ALTER TABLE `HotelRooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `hotel_reviews`
--
ALTER TABLE `hotel_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `RoomImages`
--
ALTER TABLE `RoomImages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Bookings`
--
ALTER TABLE `Bookings`
  ADD CONSTRAINT `Bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `HotelRooms` (`room_id`);

--
-- Ограничения внешнего ключа таблицы `RoomImages`
--
ALTER TABLE `RoomImages`
  ADD CONSTRAINT `RoomImages_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `HotelRooms` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

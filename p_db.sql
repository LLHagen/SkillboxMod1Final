-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 25 2022 г., 14:50
-- Версия сервера: 5.7.33
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `p_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title`, `name`) VALUES
(1, 'Женщины', 'female'),
(2, 'Мужчины', 'male'),
(3, 'Дети', 'children'),
(4, 'Аксессуары', 'access');

-- --------------------------------------------------------

--
-- Структура таблицы `category_product`
--

CREATE TABLE `category_product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category_product`
--

INSERT INTO `category_product` (`product_id`, `category_id`) VALUES
(6, 1),
(9, 3),
(27, 4),
(28, 2),
(10, 2),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'Администратор', 'может заходить в административный интерфейс, видеть список заказов и управлять товарами'),
(2, 'Гости', 'Группа для незарегистрированных пользователей'),
(3, 'Оператор', 'может заходить в административный интерфейс и видеть список заказов');

-- --------------------------------------------------------

--
-- Структура таблицы `group_user`
--

CREATE TABLE `group_user` (
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `group_user`
--

INSERT INTO `group_user` (`id_user`, `id_group`) VALUES
(4, 1),
(1, 3),
(4, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int(40) NOT NULL,
  `text` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `user_sender` int(50) NOT NULL,
  `user_addressee` int(50) NOT NULL,
  `section` int(11) NOT NULL,
  `viewed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `text`, `title`, `created_at`, `user_sender`, `user_addressee`, `section`, `viewed`) VALUES
(1, 'hi bro', 're', '2020-11-10 00:00:00', 1, 3, 5, 1),
(7, 'fasdafas', 'qwrfdbgbdfmgdxfb', '2020-12-08 07:34:11', 1, 3, 7, 0),
(10, 'ases', 'eyradfhsfdgh', '2020-12-09 03:55:39', 1, 3, 6, 0),
(11, 'йцкйцееукн', 'ейифврврьщолдоюгншзрпоппгьпг', '2020-12-10 09:12:04', 1, 3, 6, 0),
(12, '124qwaf', 'sdfsd\r\ndfh', '2020-12-10 04:57:24', 1, 3, 2, 0),
(74, '', 'qwe', '2020-12-14 06:36:54', 1, 3, 1, 0),
(75, 'asd', 'asd', '2020-12-14 06:39:43', 1, 3, 1, 0),
(76, 'qwerqwe', 'wqe', '2020-12-14 07:01:18', 1, 3, 1, 0),
(77, 'фцйу', 'йцк', '2020-12-14 07:04:23', 1, 1, 1, 0),
(78, 'фцйу', 'йцк', '2020-12-14 07:05:46', 1, 1, 1, 0),
(79, 'фцйу', 'йцк', '2020-12-14 07:06:10', 1, 1, 1, 0),
(80, 'фцйу', 'йцк', '2020-12-14 07:06:30', 1, 1, 1, 0),
(81, 'ads', 'fsd', '2020-12-14 07:06:55', 1, 1, 1, 0),
(82, 'ads', 'fsd', '2020-12-14 07:07:05', 1, 1, 1, 0),
(83, 'ads', 'fsd', '2020-12-14 07:07:16', 1, 1, 1, 0),
(84, 'qrwfsd', 'abyghk,hiok;jkihgkjr8oyu,j', '2020-12-15 02:47:50', 3, 3, 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `thirdName` varchar(50) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pay` varchar(20) NOT NULL,
  `productId` int(11) NOT NULL,
  `price` float NOT NULL,
  `delivery` tinyint(1) NOT NULL,
  `city` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `home` varchar(20) NOT NULL,
  `aprt` varchar(20) NOT NULL,
  `comment` text,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `name`, `surname`, `thirdName`, `phone`, `email`, `pay`, `productId`, `price`, `delivery`, `city`, `street`, `home`, `aprt`, `comment`, `status`) VALUES
(94, 'Игорь', 'asdf', NULL, '89999999999', 'igor-smirnov-94@mail.ru', 'card', 28, 11111, 0, 'Москва', 'Тверская', '4', 'нет', NULL, 0),
(95, 'Игорь', 'asdf', NULL, '89999999999', 'igor-smirnov-94@mail.ru', 'card', 28, 11111, 0, 'Москва', 'Тверская', '4', 'нет', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `pay`
--

CREATE TABLE `pay` (
  `id` int(20) NOT NULL,
  `code` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pay`
--

INSERT INTO `pay` (`id`, `code`, `name`) VALUES
(1, 'cash', 'Наличными'),
(2, 'card', 'Банковской картой');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT '0',
  `sale` tinyint(1) NOT NULL DEFAULT '0',
  `img` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `new`, `sale`, `img`) VALUES
(2, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(4, 'Платье со складками', '2999.00', 1, 0, 'product-4.jpg'),
(5, 'Платье со складками', '1999.00', 0, 0, 'product-5.jpg'),
(6, 'Платье со складками', '2999.00', 0, 0, 'product-6.jpg'),
(8, 'Платье со складками', '2999.00', 0, 0, 'product-8.jpg'),
(9, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(10, 'Платье со складками123', '29991.00', 0, 0, 'product-9.jpg'),
(11, 'Платье со складками1236', '2999.00', 0, 0, 'product-9.jpg'),
(12, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(13, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(14, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(15, 'Платье со складками1234', '2999.00', 0, 0, 'product-9.jpg'),
(16, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(17, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(19, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(20, 'Платье со складками123', '2999.00', 0, 0, 'product-9.jpg'),
(21, 'Платье со складками', '2999.00', 1, 0, 'product-4.jpg'),
(22, 'Платье со складками', '2999.00', 1, 0, 'product-4.jpg'),
(27, 'sfggsdfhg444', '444.00', 0, 1, 'product-27.jpeg'),
(28, 'qwe111', '11111.00', 1, 0, 'product-28.jpeg'),
(33, 'Платье со складками1001', '29999.00', 0, 0, 'product-2.jpg'),
(34, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(35, 'Платье со складками1002', '29999.00', 0, 0, 'product-2.jpg'),
(36, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(37, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(38, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(39, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(40, 'Платье со складками1004', '29999.00', 0, 0, 'product-2.jpg'),
(41, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(42, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(44, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(45, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(46, 'Платье со складками1006', '29999.00', 0, 0, 'product-2.jpg'),
(47, 'Платье со складками100', '29999.00', 0, 0, 'product-2.jpg'),
(48, 'Платье со складками100', '112321.00', 0, 0, 'product-2.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `login` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `fio` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `active`, `fio`, `email`, `phone`, `password`, `notifications`) VALUES
(1, 'ivan@mail.ru', 0, 'Иванов Иван Иванович', 'ivan-00-99@mail.ru', '+79999999999', '$2y$10$5oArntFydh/p2wbNGqwMmeJrKNvZOTH9kmNqSzpYHP0EBlYXtNjwa', 1),
(2, 'qweasd', 0, 'Пупкин Василий Петрович', 'pupok@mail.ru', '+79998888888', '$2y$10$5oArntFydh/p2wbNGqwMmeJrKNvZOTH9kmNqSzpYHP0EBlYXtNjwa', 1),
(3, 'aloshka', 0, 'Петренко Алексей Олеговыч', 'aloshka@mail.ru', '+79997777777', '$2y$10$5oArntFydh/p2wbNGqwMmeJrKNvZOTH9kmNqSzpYHP0EBlYXtNjwa', 1),
(4, 'admin@admin.ru', 0, 'admin', 'admin@admin.ru', '+79999999991', '$2y$10$5oArntFydh/p2wbNGqwMmeJrKNvZOTH9kmNqSzpYHP0EBlYXtNjwa', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD PRIMARY KEY (`id_user`,`id_group`),
  ADD KEY `id_group` (`id_group`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pay`
--
ALTER TABLE `pay`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT для таблицы `pay`
--
ALTER TABLE `pay`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_product_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `id_group` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

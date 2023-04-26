-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-02-2023 a las 00:21:16
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `quarz`
--

-- --------------------------------------------------------

-- Creación de la DB
-- CREATE DATABASE quarz;

--
-- Estructura de tabla para la tabla `buys`
--

CREATE TABLE `buys` (
  `buy_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `total` float UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `payment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `buys`
--

INSERT INTO `buys` (`buy_id`, `user_id`, `provider_id`, `total`, `start_date`, `shipment_state_id`, `payment_state_id`, `description`) VALUES
(13, 1, 2, 800, '2022-11-12 07:07:25', 3, 2, ''),
(15, 1, 1, 35600, '2022-11-12 08:09:23', 4, 1, 'ítem posición 1 con descuento'),
(16, 1, 1, 149000, '2022-11-12 13:59:41', 4, 2, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `buys_items`
--

CREATE TABLE `buys_items` (
  `buy_item_id` int(10) UNSIGNED NOT NULL,
  `buy_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL,
  `total_cost` float UNSIGNED GENERATED ALWAYS AS (`cost_price` * `quantity`) VIRTUAL,
  `position` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `buys_items`
--

INSERT INTO `buys_items` (`buy_item_id`, `buy_id`, `product_id`, `cost_price`, `quantity`, `position`) VALUES
(11, 13, 9, 200, 1, 0),
(12, 13, 9, 200, 1, 1),
(13, 13, 9, 200, 1, 2),
(14, 13, 9, 200, 1, 3),
(17, 15, 2, 3800, 2, 0),
(18, 15, 2, 3500, 8, 1),
(19, 16, 1, 7450, 20, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `CUIT` bigint(11) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`client_id`, `name`, `CUIT`, `nickname`, `direction`, `email`, `phone`) VALUES
(1, 'Siemens S.A.', 30711030634, '', '', 'email@email.com', 1122335566),
(2, 'Schneider S.A.', 30710559135, 'Schneider', '', 'algo@algo.com', 1155663223),
(5, 'Plasticos París', 30731432104, 'París', 'París 570', 'pparis@gmail.com', 1122334455);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_states`
--

CREATE TABLE `payment_states` (
  `payment_state_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `payment_states`
--

INSERT INTO `payment_states` (`payment_state_id`, `title`) VALUES
(2, 'Pagado'),
(1, 'Pendiente de pago');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`permission_id`, `title`) VALUES
(1, 'Admin'),
(2, 'Normal User');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_lists`
--

CREATE TABLE `price_lists` (
  `price_list_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `standard_factor` float UNSIGNED NOT NULL DEFAULT 1.45
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `price_lists`
--

INSERT INTO `price_lists` (`price_list_id`, `title`, `standard_factor`) VALUES
(1, 'Minorista', 1.45);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `packing_unit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`product_id`, `description`, `provider_id`, `cost_price`, `packing_unit`) VALUES
(1, 'Resina estándar 1,5 kg.', 1, 7450, '1,5 kg.'),
(2, 'Resina estándar 750 gr.', 1, 3800, '750 gr.'),
(3, 'Resina estándar 300 gr.', 1, 1700, '300 gr.'),
(4, 'Resina Glass Fluent 1,5 kg.', 1, 8800, '1,5 kg.'),
(5, 'Resina Glass Fluent 3,6 kg.', 1, 20900, '3,6 kg.'),
(6, 'Resina Glass Fluent 750 gr.', 1, 4500, '750 gr.'),
(7, 'Resina Glass Fluent 300 gr.', 1, 1700, '300 gr.'),
(8, 'Resina estándar 3,6 kg.', 2, 17700, '3,6 kg.'),
(9, 'PRUEBA123', 2, 200, '165');

--
-- Disparadores `products`
--
DELIMITER $$
CREATE TRIGGER `add_default_sale_price_item` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    INSERT INTO sale_prices (product_id, price_list_id, sale_factor)
    VALUES (NEW.product_id, 1, 1.45);
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `add_default_stock_item` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    INSERT INTO stock_items (warehouse_id, product_id, quantity) VALUES (1, NEW.product_id, 0);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `providers`
--

CREATE TABLE `providers` (
  `provider_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `CUIT` bigint(11) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `providers`
--

INSERT INTO `providers` (`provider_id`, `name`, `CUIT`, `nickname`, `direction`, `email`, `phone`) VALUES
(1, 'Diamont Porcelanato Líquido', 30711030634, '', '', 'quarzepoxi@gmail.com', 0),
(2, 'Proveedor de prueba 1', 30555664434, '', '', 'algo@algo.com', 1122334455);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `total` float UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `payment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`sale_id`, `user_id`, `client_id`, `total`, `start_date`, `shipment_state_id`, `payment_state_id`, `description`) VALUES
(2, 1, 1, 272745, '2022-11-12 07:10:56', 1, 1, ''),
(3, 1, 2, 108300, '2022-11-12 08:03:35', 1, 1, 'Notas de prueba'),
(4, 1, 1, 27040, '2022-11-12 08:08:16', 1, 1, 'Item posición 1 bonificado'),
(5, 1, 1, 108025, '2022-11-12 14:02:14', 1, 1, ''),
(6, 2, 1, 113535, '2022-11-12 14:05:54', 1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_items`
--

CREATE TABLE `sales_items` (
  `sale_item_id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sale_price` float UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL,
  `total_price` float UNSIGNED GENERATED ALWAYS AS (`sale_price` * `quantity`) VIRTUAL,
  `total_cost` float UNSIGNED GENERATED ALWAYS AS (`cost_price` * `quantity`) VIRTUAL,
  `position` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sales_items`
--

INSERT INTO `sales_items` (`sale_item_id`, `sale_id`, `product_id`, `sale_price`, `cost_price`, `quantity`, `position`) VALUES
(3, 2, 5, 30305, 20900, 9, 0),
(4, 3, 1, 10800, 7500, 10, 0),
(5, 3, 9, 300, 200, 1, 1),
(6, 4, 2, 5510, 3800, 4, 0),
(7, 4, 2, 5000, 3800, 1, 1),
(8, 5, 1, 10802.5, 7450, 10, 0),
(9, 6, 1, 10802.5, 7450, 10, 0),
(10, 6, 2, 5510, 3800, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_prices`
--

CREATE TABLE `sale_prices` (
  `sale_price_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `price_list_id` int(10) UNSIGNED NOT NULL,
  `sale_factor` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sale_prices`
--

INSERT INTO `sale_prices` (`sale_price_id`, `product_id`, `price_list_id`, `sale_factor`) VALUES
(1, 1, 1, 1.45),
(2, 2, 1, 1.45),
(3, 3, 1, 1.45),
(4, 4, 1, 1.45),
(5, 5, 1, 1.45),
(6, 6, 1, 1.45),
(7, 7, 1, 1.45),
(8, 8, 1, 1.45),
(9, 9, 1, 1.45);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_states`
--

CREATE TABLE `shipment_states` (
  `shipment_state_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `shipment_states`
--

INSERT INTO `shipment_states` (`shipment_state_id`, `title`) VALUES
(3, 'Despachado'),
(1, 'Pendiente de preparar'),
(2, 'Preparado'),
(4, 'Recibido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_items`
--

CREATE TABLE `stock_items` (
  `stock_item_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `stock_items`
--

INSERT INTO `stock_items` (`stock_item_id`, `warehouse_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 5),
(2, 1, 2, 9),
(3, 1, 3, 10),
(4, 1, 4, 0),
(5, 1, 5, 5),
(6, 1, 6, 0),
(7, 1, 7, 5),
(8, 1, 8, 0),
(9, 1, 9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL DEFAULT 2,
  `name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `user`, `pass`, `permission_id`, `name`, `last_name`, `nickname`, `email`) VALUES
(1, 'admin', '8d797914d1e4d71a56a93b2770d853e49b6273f2', 1, NULL, NULL, NULL, NULL),
(2, 'user', '8d797914d1e4d71a56a93b2770d853e49b6273f2', 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_buys`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view_buys` (
`buy_id` int(10) unsigned
,`user_id` int(10) unsigned
,`user_name` varchar(255)
,`provider_id` int(10) unsigned
,`provider_name` varchar(255)
,`total` float unsigned
,`start_date` timestamp
,`ship_id` int(10) unsigned
,`ship_desc` varchar(255)
,`pay_id` int(10) unsigned
,`pay_desc` varchar(255)
,`description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_buys_items`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view_buys_items` (
`buy_item_id` int(10) unsigned
,`buy_id` int(10) unsigned
,`product_id` int(10) unsigned
,`description` varchar(255)
,`cost_price` float unsigned
,`quantity` int(20) unsigned
,`total_cost` float unsigned
,`position` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_products`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view_products` (
`product_id` int(10) unsigned
,`description` varchar(255)
,`provider_id` int(10) unsigned
,`provider_name` varchar(255)
,`cost_price` float unsigned
,`packing_unit` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_sales`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view_sales` (
`sale_id` int(10) unsigned
,`user_id` int(10) unsigned
,`user_name` varchar(255)
,`client_id` int(10) unsigned
,`client_name` varchar(255)
,`total` float unsigned
,`start_date` timestamp
,`ship_id` int(10) unsigned
,`ship_desc` varchar(255)
,`pay_id` int(10) unsigned
,`pay_desc` varchar(255)
,`description` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_sales_items`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view_sales_items` (
`sale_item_id` int(10) unsigned
,`sale_id` int(10) unsigned
,`product_id` int(10) unsigned
,`description` varchar(255)
,`sale_price` float unsigned
,`cost_price` float unsigned
,`quantity` int(20) unsigned
,`total_price` float unsigned
,`total_cost` float unsigned
,`position` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `view_stock`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `view_stock` (
`stock_id` int(10) unsigned
,`product_id` int(10) unsigned
,`product_name` varchar(255)
,`warehouse_id` int(10) unsigned
,`warehouse_name` varchar(255)
,`quantity` int(20) unsigned
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `direction` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `warehouses`
--

INSERT INTO `warehouses` (`warehouse_id`, `title`, `direction`) VALUES
(1, 'Hurlingham', 'Villa Tesei');

-- --------------------------------------------------------

--
-- Estructura para la vista `view_buys`
--
DROP TABLE IF EXISTS `view_buys`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_buys`  AS SELECT `b`.`buy_id` AS `buy_id`, `b`.`user_id` AS `user_id`, `u`.`user` AS `user_name`, `b`.`provider_id` AS `provider_id`, `p`.`name` AS `provider_name`, `b`.`total` AS `total`, `b`.`start_date` AS `start_date`, `b`.`shipment_state_id` AS `ship_id`, `ship`.`title` AS `ship_desc`, `b`.`payment_state_id` AS `pay_id`, `pay`.`title` AS `pay_desc`, `b`.`description` AS `description` FROM ((((`buys` `b` join `users` `u`) join `providers` `p`) join `shipment_states` `ship`) join `payment_states` `pay`) WHERE `u`.`user_id` = `b`.`user_id` AND `p`.`provider_id` = `b`.`provider_id` AND `ship`.`shipment_state_id` = `b`.`shipment_state_id` AND `pay`.`payment_state_id` = `b`.`payment_state_id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_buys_items`
--
DROP TABLE IF EXISTS `view_buys_items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_buys_items`  AS SELECT `bi`.`buy_item_id` AS `buy_item_id`, `bi`.`buy_id` AS `buy_id`, `bi`.`product_id` AS `product_id`, `p`.`description` AS `description`, `bi`.`cost_price` AS `cost_price`, `bi`.`quantity` AS `quantity`, `bi`.`total_cost` AS `total_cost`, `bi`.`position` AS `position` FROM (`buys_items` `bi` join `products` `p`) WHERE `bi`.`product_id` = `p`.`product_id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_products`
--
DROP TABLE IF EXISTS `view_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_products`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`description` AS `description`, `p`.`provider_id` AS `provider_id`, `prov`.`name` AS `provider_name`, `p`.`cost_price` AS `cost_price`, `p`.`packing_unit` AS `packing_unit` FROM (`products` `p` join `providers` `prov`) WHERE `p`.`provider_id` = `prov`.`provider_id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_sales`
--
DROP TABLE IF EXISTS `view_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_sales`  AS SELECT `s`.`sale_id` AS `sale_id`, `s`.`user_id` AS `user_id`, `u`.`user` AS `user_name`, `s`.`client_id` AS `client_id`, `c`.`name` AS `client_name`, `s`.`total` AS `total`, `s`.`start_date` AS `start_date`, `s`.`shipment_state_id` AS `ship_id`, `ship`.`title` AS `ship_desc`, `s`.`payment_state_id` AS `pay_id`, `pay`.`title` AS `pay_desc`, `s`.`description` AS `description` FROM ((((`sales` `s` join `users` `u`) join `clients` `c`) join `shipment_states` `ship`) join `payment_states` `pay`) WHERE `u`.`user_id` = `s`.`user_id` AND `c`.`client_id` = `s`.`client_id` AND `ship`.`shipment_state_id` = `s`.`shipment_state_id` AND `pay`.`payment_state_id` = `s`.`payment_state_id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_sales_items`
--
DROP TABLE IF EXISTS `view_sales_items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_sales_items`  AS SELECT `si`.`sale_item_id` AS `sale_item_id`, `si`.`sale_id` AS `sale_id`, `si`.`product_id` AS `product_id`, `p`.`description` AS `description`, `si`.`sale_price` AS `sale_price`, `si`.`cost_price` AS `cost_price`, `si`.`quantity` AS `quantity`, `si`.`total_price` AS `total_price`, `si`.`total_cost` AS `total_cost`, `si`.`position` AS `position` FROM (`sales_items` `si` join `products` `p`) WHERE `si`.`product_id` = `p`.`product_id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `view_stock`
--
DROP TABLE IF EXISTS `view_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_stock`  AS SELECT `s`.`stock_item_id` AS `stock_id`, `s`.`product_id` AS `product_id`, `p`.`description` AS `product_name`, `s`.`warehouse_id` AS `warehouse_id`, `w`.`title` AS `warehouse_name`, `s`.`quantity` AS `quantity` FROM ((`stock_items` `s` join `products` `p`) join `warehouses` `w`) WHERE `s`.`warehouse_id` = `w`.`warehouse_id` AND `s`.`product_id` = `p`.`product_id` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `buys`
--
ALTER TABLE `buys`
  ADD PRIMARY KEY (`buy_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `shipment_state_id` (`shipment_state_id`),
  ADD KEY `payment_state_id` (`payment_state_id`);

--
-- Indices de la tabla `buys_items`
--
ALTER TABLE `buys_items`
  ADD PRIMARY KEY (`buy_item_id`),
  ADD KEY `buy_id` (`buy_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `CUIT` (`CUIT`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `payment_states`
--
ALTER TABLE `payment_states`
  ADD PRIMARY KEY (`payment_state_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indices de la tabla `price_lists`
--
ALTER TABLE `price_lists`
  ADD PRIMARY KEY (`price_list_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `description` (`description`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indices de la tabla `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`provider_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `CUIT` (`CUIT`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `shipment_state_id` (`shipment_state_id`),
  ADD KEY `payment_state_id` (`payment_state_id`);

--
-- Indices de la tabla `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`sale_item_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD PRIMARY KEY (`sale_price_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `price_list_id` (`price_list_id`);

--
-- Indices de la tabla `shipment_states`
--
ALTER TABLE `shipment_states`
  ADD PRIMARY KEY (`shipment_state_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indices de la tabla `stock_items`
--
ALTER TABLE `stock_items`
  ADD PRIMARY KEY (`stock_item_id`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indices de la tabla `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `buys`
--
ALTER TABLE `buys`
  MODIFY `buy_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `buys_items`
--
ALTER TABLE `buys_items`
  MODIFY `buy_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `payment_states`
--
ALTER TABLE `payment_states`
  MODIFY `payment_state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `price_lists`
--
ALTER TABLE `price_lists`
  MODIFY `price_list_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `providers`
--
ALTER TABLE `providers`
  MODIFY `provider_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `sale_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sale_prices`
--
ALTER TABLE `sale_prices`
  MODIFY `sale_price_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `shipment_states`
--
ALTER TABLE `shipment_states`
  MODIFY `shipment_state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `stock_items`
--
ALTER TABLE `stock_items`
  MODIFY `stock_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `buys`
--
ALTER TABLE `buys`
  ADD CONSTRAINT `buys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `buys_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `buys_ibfk_3` FOREIGN KEY (`shipment_state_id`) REFERENCES `shipment_states` (`shipment_state_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `buys_ibfk_4` FOREIGN KEY (`payment_state_id`) REFERENCES `payment_states` (`payment_state_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `buys_items`
--
ALTER TABLE `buys_items`
  ADD CONSTRAINT `buys_items_ibfk_1` FOREIGN KEY (`buy_id`) REFERENCES `buys` (`buy_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `buys_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`shipment_state_id`) REFERENCES `shipment_states` (`shipment_state_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_ibfk_4` FOREIGN KEY (`payment_state_id`) REFERENCES `payment_states` (`payment_state_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD CONSTRAINT `sale_prices_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_prices_ibfk_2` FOREIGN KEY (`price_list_id`) REFERENCES `price_lists` (`price_list_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `stock_items`
--
ALTER TABLE `stock_items`
  ADD CONSTRAINT `stock_items_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Agregado de campo product_price a la tabla sale_prices
 SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `quarz`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_prices`
--

CREATE TABLE `sale_prices` (
  `sale_price_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `price_list_id` int(10) UNSIGNED NOT NULL,
  `sale_factor` float UNSIGNED NOT NULL,
  `product_price` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sale_prices`
--

INSERT INTO `sale_prices` (`sale_price_id`, `product_id`, `price_list_id`, `sale_factor`, `product_price`) VALUES
(1, 1, 1, 1.45, 0),
(2, 2, 1, 1.45, 0),
(3, 3, 1, 1.45, 0),
(4, 4, 1, 1.45, 0),
(5, 5, 1, 1.45, 0),
(6, 6, 1, 1.45, 0),
(7, 7, 1, 1.45, 0),
(8, 8, 1, 1.45, 0),
(9, 9, 1, 1.45, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD PRIMARY KEY (`sale_price_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `price_list_id` (`price_list_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sale_prices`
--
ALTER TABLE `sale_prices`
  MODIFY `sale_price_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sale_prices`
--
ALTER TABLE `sale_prices`
  ADD CONSTRAINT `sale_prices_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_prices_ibfk_2` FOREIGN KEY (`price_list_id`) REFERENCES `price_lists` (`price_list_id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Vista para productos con stock y precios de lista (view_products_list_stock)
CREATE OR REPLACE VIEW view_products_list_stock

AS SELECT p.product_id, p.description, p.provider_id, prov.name AS provider_name, p.cost_price, sp.product_price AS sale_price, p.packing_unit, s.quantity AS stock_quantity, s.warehouse_id, w.title AS warehouse_name, sp.price_list_id, pl.title AS price_list_name

FROM products AS p JOIN providers AS prov ON p.provider_id = prov.provider_id JOIN stock_items AS s ON p.product_id = s.product_id JOIN sale_prices AS sp ON p.product_id = sp.product_id JOIN warehouses AS w ON s.warehouse_id = w.warehouse_id JOIN price_lists AS pl ON sp.price_list_id = pl.price_list_id

-- Nuevas tablas budgets y budgets_items
CREATE TABLE `budgets` (
  `budget_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `total` float UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `budgets_items` (
  `budget_item_id` int(10) UNSIGNED NOT NULL,
  `budget_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sale_price` float UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL,
  `total_price` float UNSIGNED GENERATED ALWAYS AS (`sale_price` * `quantity`) VIRTUAL,
  `total_cost` float UNSIGNED GENERATED ALWAYS AS (`cost_price` * `quantity`) VIRTUAL,
  `position` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indices de la tabla `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`budget_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indices de la tabla `budgets_items`
--
ALTER TABLE `budgets_items`
  ADD PRIMARY KEY (`budget_item_id`),
  ADD KEY `budget_id` (`budget_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT de la tabla `budgets`
--
ALTER TABLE `budgets`
  MODIFY `budget_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `budgets_items`
--
ALTER TABLE `budgets_items`
  MODIFY `budget_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Filtros para la tabla `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `budgets_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `budgets_items`
--
ALTER TABLE `budgets_items`
  ADD CONSTRAINT `budgets_items_ibfk_1` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`budget_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `budgets_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

-- FK en 'sales' para identificar de qué 'budget' viene (si es que se genera en base a una budget)
ALTER TABLE `sales`
  ADD COLUMN budget_id INT(10) UNSIGNED DEFAULT NULL AFTER client_id;
ALTER TABLE `sales`
  ADD KEY `budget_id` (`budget_id`);
ALTER TABLE `sales`
  ADD CONSTRAINT fk_sales_budgets FOREIGN KEY (budget_id) REFERENCES budgets(budget_id) ON DELETE NO ACTION ON UPDATE CASCADE;

-- Vista view_budgets
CREATE OR REPLACE VIEW view_budgets AS
SELECT b.budget_id, b.user_id, u.user AS user_name, b.client_id, c.name AS client_name, b.total, b.start_date, b.description 
FROM budgets AS b JOIN users AS u ON b.user_id = u.user_id JOIN clients AS c ON b.client_id = c.client_id;

-- Vista view_budgets_items
CREATE OR REPLACE VIEW view_budgets_items AS
SELECT bi.budget_item_id, b.budget_id, p.product_id, p.description, bi.sale_price, bi.cost_price, bi.quantity, bi.total_price, bi.total_cost, bi.position
FROM budgets_items AS bi JOIN budgets AS b ON bi.budget_id = b.budget_id JOIN products AS p ON bi.product_id = p.product_id;

-- Cambio de tipo en tabla budgets_items
ALTER TABLE `budgets_items` CHANGE `sale_price` `sale_price` DOUBLE(10,2) UNSIGNED NOT NULL, CHANGE `cost_price` `cost_price` DOUBLE(10,2) UNSIGNED NOT NULL, CHANGE `quantity` `quantity` DOUBLE(10,2) UNSIGNED NOT NULL, CHANGE `total_price` `total_price` DOUBLE(10,2) UNSIGNED AS (`sale_price` * `quantity`) VIRTUAL, CHANGE `total_cost` `total_cost` DOUBLE(10,2) UNSIGNED AS (`cost_price` * `quantity`) VIRTUAL;
-- Cambio de tipo en tabla budgets
ALTER TABLE `budgets` CHANGE `total` `total` DOUBLE(10,2) UNSIGNED NOT NULL;
-- Cambio de tipo en tabla stock_items
ALTER TABLE `stock_items` CHANGE `quantity` `quantity` DOUBLE(10,2) UNSIGNED NOT NULL DEFAULT '0';
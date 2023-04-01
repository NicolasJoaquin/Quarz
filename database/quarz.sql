-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-11-2022 a las 02:22:26
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

--
-- Estructura de tabla para la tabla `shipment_states`
--

CREATE TABLE `shipment_states` (
  `shipment_state_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` varchar(255) UNIQUE NOT NULL,
  PRIMARY KEY (shipment_state_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `payment_states`
--

CREATE TABLE `payment_states` (
  `payment_state_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` varchar(255) UNIQUE NOT NULL,
  PRIMARY KEY (payment_state_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `client_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` varchar(255) UNIQUE NOT NULL,
  `CUIT` bigint(11) UNIQUE DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `email` varchar(255) UNIQUE DEFAULT NULL,
  `phone` int(25) DEFAULT NULL,
  PRIMARY KEY (client_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ACA
--
-- Estructura de tabla para la tabla `providers`
--

CREATE TABLE `providers` (
  `provider_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` varchar(255) UNIQUE NOT NULL,
  `CUIT` bigint(11) UNIQUE DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `email` varchar(255) UNIQUE DEFAULT NULL,
  `phone` int(25) DEFAULT NULL,
  PRIMARY KEY (provider_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` varchar(255) UNIQUE NOT NULL,
  PRIMARY KEY (permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `price_lists`
--

CREATE TABLE `price_lists` (
  `price_list_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` varchar(255) UNIQUE NOT NULL,
  `standard_factor` float UNSIGNED NOT NULL DEFAULT 1.45, 
  PRIMARY KEY (price_list_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `product_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `description` varchar(255) UNIQUE NOT NULL,
  provider_id int(10) UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `packing_unit` varchar(255) NOT NULL,
  PRIMARY KEY (product_id),
  FOREIGN KEY (provider_id) REFERENCES providers(provider_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user` varchar(255) UNIQUE NOT NULL,
  `pass` varchar(255) NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL DEFAULT 2,
  `name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `email` varchar(255) UNIQUE DEFAULT NULL,
  PRIMARY KEY (user_id),
  FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `total` float UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT NOW(),
  `shipment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `payment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  description varchar(255) DEFAULT NULL,
  PRIMARY KEY (sale_id), 
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (shipment_state_id) REFERENCES shipment_states(shipment_state_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (payment_state_id) REFERENCES payment_states(payment_state_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_items`
--

CREATE TABLE `sales_items` (
  `sale_item_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sale_price` float UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL,
  total_price float UNSIGNED as (sale_price * quantity),
  total_cost float UNSIGNED as (cost_price * quantity),
  `position` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (sale_item_id),
  FOREIGN KEY (sale_id) REFERENCES sales(sale_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_prices`
--

CREATE TABLE `sale_prices` (
  `sale_price_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `price_list_id` int(10) UNSIGNED NOT NULL,
  `sale_factor` float UNSIGNED NOT NULL,
  PRIMARY KEY (sale_price_id),
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (price_list_id) REFERENCES price_lists(price_list_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `buys`
--

CREATE TABLE `buys` (
  `buy_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `total` float UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT NOW(),
  `shipment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `payment_state_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  description varchar(255) DEFAULT NULL,
  PRIMARY KEY (buy_id), 
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (provider_id) REFERENCES providers(provider_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (shipment_state_id) REFERENCES shipment_states(shipment_state_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (payment_state_id) REFERENCES payment_states(payment_state_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `buys_items`
--

CREATE TABLE `buys_items` (
  `buy_item_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `buy_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `cost_price` float UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL,
  total_cost float UNSIGNED as (cost_price * quantity),
  `position` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (buy_item_id),
  FOREIGN KEY (buy_id) REFERENCES buys(buy_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------- 

--
-- Estructura de tabla para la tabla `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` varchar(255) UNIQUE NOT NULL,
  `direction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (warehouse_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estructura de tabla para la tabla `stock_items`
--

CREATE TABLE `stock_items` (
  `stock_item_id` int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(20) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (stock_item_id),
  FOREIGN KEY (warehouse_id) REFERENCES warehouses(warehouse_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


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
-- Estructura Stand-in para la vista `view_stock`
-- (Véase abajo para la vista actual)
--
-- CREATE TABLE `view_stock` (
-- `stock_id` int(10)
-- ,`product_id` int(10)
-- ,`product_name` varchar(250)
-- ,`warehouse_name` varchar(50)
-- ,`quantity` int(20) unsigned
-- );

-- -- --------------------------------------------------------

-- --
-- -- Estructura para la vista `view_stock`
-- --
-- DROP TABLE IF EXISTS `view_stock`;

-- CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_stock`  AS SELECT `s`.`stock_item_id` AS `stock_id`, `p`.`product_id` AS `product_id`, `p`.`description` AS `product_name`, `w`.`title` AS `warehouse_name`, `s`.`quantity` AS `quantity` FROM ((`stock_items` `s` join `products` `p`) join `warehouses` `w`) WHERE `s`.`warehouse_id` = `w`.`warehouse_id` AND `s`.`product_id` = `p`.`product_id` ;

-- VOLCADO DE DATOS (INSERTS)

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (client_id, `name`) VALUES
(1, 'Siemens S.A.');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `providers`
--

INSERT INTO `providers` (provider_id, `name`) VALUES
(1, 'Diamont Porcelanato Líquido');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `shipment_states`
--

INSERT INTO `shipment_states` (`shipment_state_id`, `title`) VALUES
(1, 'Pendiente de preparar'),
(2, 'Preparado'),
(3, 'Despachado'),
(4, 'Recibido');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `payment_states`
--

INSERT INTO `payment_states` (`payment_state_id`, `title`) VALUES
(1, 'Pendiente de pago'),
(2, 'Pagado');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`permission_id`, `title`) VALUES
(1, 'Admin'),
(2, 'Normal User');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `user`, pass, permission_id) VALUES
(1, 'admin', '8d797914d1e4d71a56a93b2770d853e49b6273f2', 1),
(2, 'user',  '8d797914d1e4d71a56a93b2770d853e49b6273f2', 2);

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `price_lists`
--

INSERT INTO `price_lists` (`price_list_id`, `title`, `standard_factor`) VALUES
(1, 'Minorista', 1.45);

-- --------------------------------------------------------



--
-- Volcado de datos para la tabla `warehouses`
--

INSERT INTO `warehouses` (`warehouse_id`, `title`, `direction`) VALUES
(1, 'Hurlingham', 'Villa Tesei');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`product_id`, `description`, `cost_price`, provider_id, `packing_unit`) VALUES
(1, 'Resina estándar 150 gr.', 950, 1, '150 gr.'),
(2, 'Resina estándar 300 gr.', 1700, 1, '300 gr.'),
(3, 'Resina estándar 750 gr.', 3800, 1, '750 gr.'),
(4, 'Resina estándar 1,5 kg.', 7450, 1, '1,5 kg.'),
(5, 'Resina Glass Fluent 300 gr.', 1990, 1, '300 gr.'),
(6, 'Resina Glass Fluent 750 gr.', 4500, 1, '750 gr.');
(7, 'Resina Glass Fluent 1,5 kg.', 8800, 1, '1,5 kg.'),
(8, 'Resina Glass Fluent 3,6 kg.', 20900, 1, '3,6 kg.'),

-- --------------------------------------------------------

--
-- Índices para tablas volcadas
--



COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

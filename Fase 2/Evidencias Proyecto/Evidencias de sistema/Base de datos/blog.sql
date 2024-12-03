-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 20:49:08
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `blog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Lácteos', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(2, 'Granos', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(3, 'Productos de Limpieza', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(4, 'Galletas', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(5, 'Bebidas', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(6, 'Panadería', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(7, 'Frutas y Verduras', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(8, 'Embutidos', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(9, 'Aseo Personal', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(10, 'Mascotas', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(11, 'Congelados', '2024-11-12 20:54:08', '2024-11-12 20:54:08'),
(12, 'Envasados', '2024-11-12 20:54:08', '2024-11-12 20:54:08'),
(13, 'Snacks', '2024-11-12 20:54:08', '2024-11-12 20:54:08'),
(14, 'Productos para el Hogar', '2024-11-12 21:06:28', '2024-11-12 21:06:28'),
(15, 'Carnes', '2024-11-12 21:06:28', '2024-11-12 21:06:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fiados`
--

CREATE TABLE `fiados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_cliente` varchar(255) NOT NULL,
  `nombre_cliente` varchar(255) NOT NULL,
  `productos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`productos`)),
  `total_precio` decimal(10,2) NOT NULL,
  `fecha_compra` datetime NOT NULL DEFAULT '2024-11-15 11:38:02',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fiados`
--

INSERT INTO `fiados` (`id`, `id_cliente`, `nombre_cliente`, `productos`, `total_precio`, `fecha_compra`, `user_id`, `created_at`, `updated_at`) VALUES
(49, '1', 'simon', '[{\"id\":\"25\",\"nombre\":\"Zanahoria\",\"cantidad\":1,\"precio_unitario\":1250,\"precio_total\":1250},{\"id\":\"26\",\"nombre\":\"Papa\",\"cantidad\":1,\"precio_unitario\":3790,\"precio_total\":3790}]', 5040.00, '2024-11-22 20:24:01', 8, '2024-11-22 23:24:01', '2024-11-22 23:24:01'),
(50, '1', 'simon', '[{\"id\":\"26\",\"nombre\":\"Papa\",\"cantidad\":1,\"precio_unitario\":3790,\"precio_total\":3790}]', 3790.00, '2024-11-24 15:40:13', 8, '2024-11-24 18:40:13', '2024-11-24 18:40:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_10_13_225956_create_productos_table', 2),
(5, '2024_10_14_212912_add_categoria_and_fecha_vencimiento_to_productos_table', 3),
(6, '2024_10_14_213202_create_categorias_table', 4),
(7, '2024_10_19_191618_create_fiados_table', 5),
(8, '2024_10_19_201540_add_producto_to_fiados_table', 6),
(9, '2024_10_19_211637_create_fiados_table', 7),
(10, '2024_11_09_203150_create_consultas_table', 8),
(11, '2024_11_09_213252_create_ventas_table', 9),
(13, '2024_11_13_125208_create_registros_reporte_table', 10),
(14, '2024_11_15_113724_create_fiados_table', 11),
(15, '2024_11_22_193244_add_profile_picture_to_users_table', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('si.carrasco@duocuc.cl', '$2y$12$U1wmlQ.FYPdX687OvKTaLOq9Kv3stOkFmVtl/nKxk.wZaafn1c0xW', '2024-11-22 23:31:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `categoria_id`, `fecha_vencimiento`, `created_at`, `updated_at`, `user_id`) VALUES
(25, 'Zanahoria', 'Multi Marca Zanahoria Bolsa, 1KG', 1250.00, 9, 7, '2025-01-01', '2024-10-19 00:01:49', '2024-11-25 00:02:45', 8),
(26, 'Papa', 'Multi Marca Papa Malla, 2 Kg', 3790.00, 9, 7, '2025-01-01', '2024-10-19 00:10:23', '2024-11-25 00:02:45', 8),
(27, 'Champiñones', 'Multi Marca Champiñones Blanco Bandeja, 200 g', 1650.00, 1, 7, '2025-10-01', '2024-10-19 00:14:00', '2024-11-24 23:41:28', 8),
(30, 'Champiñones', 'Multi Marca\r\nChampiñones Blancos Laminados Bandeja, 200 g', 1890.00, 19, 7, '2025-01-01', '2024-10-19 00:29:19', '2024-11-22 23:24:40', 8),
(31, 'Leche', 'Leche Natural Entera, 1 L', 1000.00, 17, 1, '2025-02-01', '2024-10-19 00:37:50', '2024-11-25 00:02:37', 8),
(32, 'Papel Higiénico', 'Elite Papel Higiénico Ultra Doble Hoja 50 m, 12 Un', 1190.00, 33, 9, NULL, '2024-10-19 22:45:29', '2024-11-25 00:02:50', 8),
(33, 'Papel Higiénico', 'Confort Papel Higiénico Doble Hoja 24 m, 4 Un', 1000.00, 54, 9, NULL, '2024-10-19 22:46:15', '2024-11-25 00:02:37', 8),
(35, 'Tortilla', 'Tortilla Rapidita, 1 Un', 1000.00, 8, 6, '2030-05-12', '2024-11-12 03:33:42', '2024-11-20 18:31:24', 8),
(38, 'Atún', 'Van Camp\'s Atún Al Agua, Drenado 104 g - Neto 160 g', 1850.00, 30, 12, '2025-09-10', '2024-11-13 00:00:20', '2024-11-25 00:02:48', 8),
(40, 'Lay\'s', 'Papas Fritas Corte Americano, 350 g', 3190.00, 3, 13, '2024-11-27', '2024-11-20 19:18:19', '2024-11-25 00:02:48', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_reporte`
--

CREATE TABLE `registros_reporte` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_ganancias` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registros_reporte`
--

INSERT INTO `registros_reporte` (`id`, `user_id`, `fecha_generacion`, `total_ganancias`, `created_at`, `updated_at`) VALUES
(24, 8, '2024-11-19 20:23:26', 155880.00, '2024-11-19 20:23:26', '2024-11-19 20:23:26'),
(25, 8, '2024-11-21 01:42:10', 29320.00, '2024-11-21 01:42:10', '2024-11-21 01:42:10'),
(26, 8, '2024-11-22 22:48:35', 10390.00, '2024-11-22 22:48:35', '2024-11-22 22:48:35'),
(27, 8, '2024-11-22 23:24:59', 10860.00, '2024-11-22 23:24:59', '2024-11-22 23:24:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('v9e5DKM2z4mKxUbngjXkwO1XKmu7ZTadDCSTLqOG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib3hVM25mMGxxdGN3WXE3c1VmQXlnN3VjZUIwTDFudnFZY0pkTTZXSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYWdvIjt9fQ==', 1732502698);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_picture`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(8, 'Simon', 'si.carrasco@duocuc.cl', 'profile_pictures/wPJS64iYrcJzISW758eR9oOOugT3UnU7L47N6qy8.jpg', NULL, '$2y$12$zdJRWchMKnhrbGpD.dRbfuYziSD2u.IaTCLap4Ii8QH/7lL165rmS', 'ABOnrqQ6W8GDFBrDViAFj1A1Nfpo77KykNq5MU5g6XVVOuka8yUkViPTE3PX', '2024-10-13 23:30:01', '2024-11-25 02:23:28'),
(9, 'Test User', 'test@example.com', NULL, '2024-10-15 00:38:48', '$2y$12$x1.jtmCjJCdfaZ6javfES.twfA0V/EjBw8Tiqw1AdOGLyK/vjPtUO', 'sH8eLbEPiD', '2024-10-15 00:38:48', '2024-10-15 00:38:48'),
(10, 'Jose', 'carrascogutierrezsimoneduardo@gmail.com', NULL, NULL, '$2y$12$Ku/13CDk0RyEhGaFeJKiOOCR.4h7.1SdTBeRiU/NmveyrVp/Tql9m', 'FrWiJlyFOVrSBqKC9c9RmSb3rxXigJdVlzHp5prfjxLblimPZn4BWWPWrAJn', '2024-10-21 23:18:25', '2024-11-12 05:20:42'),
(11, 'Benjamin', 'benj.munoz@duocuc.cl', NULL, NULL, '$2y$12$CiiBmC88pD9gnoQ.VpuQIeW5CRM8.k9a1mLgqSrqIoNFPtMz.M/E2', NULL, '2024-11-12 05:43:02', '2024-11-12 05:43:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_reference` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `productos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`productos`)),
  `metodo_pago` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `external_reference`, `status`, `amount`, `productos`, `metodo_pago`, `created_at`, `updated_at`, `user_id`) VALUES
(129, '674112aeca69c', 'approved', 5430.00, '[{\"id\":\"30\",\"descripcion\":\"Multi Marca\\r\\nChampi\\u00f1ones Blancos Laminados Bandeja, 200 g\",\"precio\":1890,\"cantidad\":2},{\"id\":\"27\",\"descripcion\":\"Multi Marca Champi\\u00f1ones Blanco Bandeja, 200 g\",\"precio\":1650,\"cantidad\":1}]', 'POS', '2024-11-22 23:24:30', '2024-11-22 23:24:30', 8),
(130, '674112b8043d9', 'approved', 5430.00, '[{\"id\":\"30\",\"descripcion\":\"Multi Marca\\r\\nChampi\\u00f1ones Blancos Laminados Bandeja, 200 g\",\"precio\":1890,\"cantidad\":2},{\"id\":\"27\",\"descripcion\":\"Multi Marca Champi\\u00f1ones Blanco Bandeja, 200 g\",\"precio\":1650,\"cantidad\":1}]', 'Efectivo', '2024-11-22 23:24:40', '2024-11-22 23:24:40', 8),
(131, '674113012a1ba', 'approved', 6250.00, '[{\"id\":\"25\",\"descripcion\":\"Multi Marca Zanahoria Bolsa, 1KG\",\"precio\":1250,\"cantidad\":5}]', 'POS', '2024-11-22 23:25:53', '2024-11-22 23:25:53', 8),
(132, '6743a1671e139', 'approved', 1650.00, '[{\"id\":\"27\",\"descripcion\":\"Multi Marca Champi\\u00f1ones Blanco Bandeja, 200 g\",\"precio\":1650,\"cantidad\":1}]', 'POS', '2024-11-24 21:57:59', '2024-11-24 21:57:59', 8),
(133, '6743a16aa1f90', 'approved', 1000.00, '[{\"id\":\"31\",\"descripcion\":\"Leche Natural Entera, 1 L\",\"precio\":1000,\"cantidad\":1}]', 'Efectivo', '2024-11-24 21:58:02', '2024-11-24 21:58:02', 8),
(134, '6743be9d46cca', 'approved', 3190.00, '[{\"id\":\"32\",\"descripcion\":\"Elite Papel Higi\\u00e9nico Ultra Doble Hoja 50 m, 12 Un\",\"precio\":1190,\"cantidad\":1},{\"id\":\"33\",\"descripcion\":\"Confort Papel Higi\\u00e9nico Doble Hoja 24 m, 4 Un\",\"precio\":1000,\"cantidad\":1},{\"id\":\"31\",\"descripcion\":\"Leche Natural Entera, 1 L\",\"precio\":1000,\"cantidad\":1}]', 'POS', '2024-11-25 00:02:37', '2024-11-25 00:02:37', 8),
(135, '6743bea562987', 'approved', 6230.00, '[{\"id\":\"32\",\"descripcion\":\"Elite Papel Higi\\u00e9nico Ultra Doble Hoja 50 m, 12 Un\",\"precio\":1190,\"cantidad\":1},{\"id\":\"26\",\"descripcion\":\"Multi Marca Papa Malla, 2 Kg\",\"precio\":3790,\"cantidad\":1},{\"id\":\"25\",\"descripcion\":\"Multi Marca Zanahoria Bolsa, 1KG\",\"precio\":1250,\"cantidad\":1}]', 'Efectivo', '2024-11-25 00:02:45', '2024-11-25 00:02:45', 8),
(136, '6743bea8e7818', 'approved', 5040.00, '[{\"id\":\"40\",\"descripcion\":\"Papas Fritas Corte Americano, 350 g\",\"precio\":3190,\"cantidad\":1},{\"id\":\"38\",\"descripcion\":\"Van Camp\'s At\\u00fan Al Agua, Drenado 104 g - Neto 160 g\",\"precio\":1850,\"cantidad\":1}]', 'Efectivo', '2024-11-25 00:02:48', '2024-11-25 00:02:48', 8),
(137, '6743beaac0ad4', 'approved', 1190.00, '[{\"id\":\"32\",\"descripcion\":\"Elite Papel Higi\\u00e9nico Ultra Doble Hoja 50 m, 12 Un\",\"precio\":1190,\"cantidad\":1}]', 'POS', '2024-11-25 00:02:50', '2024-11-25 00:02:50', 8);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fiados`
--
ALTER TABLE `fiados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `registros_reporte`
--
ALTER TABLE `registros_reporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_reporte_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ventas_external_reference_unique` (`external_reference`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `fiados`
--
ALTER TABLE `fiados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `registros_reporte`
--
ALTER TABLE `registros_reporte`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `registros_reporte`
--
ALTER TABLE `registros_reporte`
  ADD CONSTRAINT `registros_reporte_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

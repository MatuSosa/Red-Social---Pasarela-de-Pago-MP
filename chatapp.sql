-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2023 a las 18:26:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chatapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `unique_id` varchar(100) NOT NULL,
  `contenido` text NOT NULL,
  `comentario_padre_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `estado_id`, `user_id`, `unique_id`, `contenido`, `comentario_padre_id`, `fecha_creacion`) VALUES
(58, 216, 8, '1513570550', 'Que lindo lugar', 0, '2023-10-28 03:02:08'),
(59, 218, 8, '1513570550', 'Que lindo lugar', 0, '2023-11-14 18:27:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_notificaciones`
--

CREATE TABLE `configuracion_notificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `sonidos_notificacion` tinyint(1) DEFAULT 1,
  `cumpleanios_amigos` tinyint(1) DEFAULT 1,
  `sonido_mensajes` tinyint(1) DEFAULT 1,
  `sonido_solicitudes_amistad` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `hora` time NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `contenido` longtext DEFAULT NULL,
  `acepta_terminos` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`curso_id`, `titulo`, `descripcion`, `tipo`, `hora`, `categoria`, `precio`, `contenido`, `acepta_terminos`, `user_id`, `fecha_creacion`, `logo`) VALUES
(35, 'Curso PHP', 'Aprenderás a crear bases de datos y conectarse con ella.', 'Archivo PDF', '10:00:00', 'Informática', 5000.00, 'Manual_PHP5_Basico.pdf', 1, 8, '2023-10-17 14:51:45', 'logo_curso.png'),
(36, 'Curso PHP y Pyhton', 'Aprenderás a crear bases de datos', 'Archivo PDF', '12:00:00', 'Educacion', 4500.00, 'manual-javascript.pdf', 1, 31, '2023-10-24 21:42:40', 'logo_curso.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos_favoritos`
--

CREATE TABLE `cursos_favoritos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos_favoritos`
--

INSERT INTO `cursos_favoritos` (`id`, `user_id`, `curso_id`) VALUES
(30, 31, 35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

CREATE TABLE `denuncias` (
  `denuncia_id` int(11) NOT NULL,
  `user_id_reporter` int(11) NOT NULL,
  `user_id_seller` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `fecha_denuncia` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `motivo` text NOT NULL,
  `aprobada` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `denuncias`
--

INSERT INTO `denuncias` (`denuncia_id`, `user_id_reporter`, `user_id_seller`, `curso_id`, `fecha_denuncia`, `motivo`, `aprobada`) VALUES
(10, 31, 8, 35, '2023-10-24 21:39:21', 'El contenido del curso estaba vacio', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `media_type` enum('text','photo','video','location') NOT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) NOT NULL DEFAULT 0,
  `dislikes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `user_id`, `content`, `media_type`, `media_url`, `created_at`, `likes`, `dislikes`) VALUES
(216, 8, '\r\nEstoy en París, Francia ✈️', 'photo', '../images/pp_4.jpg', '2023-10-24 20:05:43', 0, 0),
(217, 31, '\r\nEstoy en Suiza, Paicol, Huila, Colombia ✈️', 'photo', '../images/portada.jpg', '2023-10-24 21:32:24', 0, 0),
(218, 8, '\r\nEstoy en Madrid, España ✈️', 'photo', '../images/portada.jpg', '2023-10-28 03:03:41', 0, 0),
(219, 8, '\r\nEstoy en Francia, Honduras ✈️', 'photo', '../images/pp_4.jpg', '2023-11-14 18:27:30', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `friend_id` int(11) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `friends`
--

INSERT INTO `friends` (`id`, `user_id`, `friend_id`, `status`) VALUES
(746, 8, 31, 'accepted'),
(747, 31, 8, 'accepted');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_pago`
--

CREATE TABLE `informacion_pago` (
  `informacion_pago_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cbu_cvu` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `titular_cuenta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informacion_pago`
--

INSERT INTO `informacion_pago` (`informacion_pago_id`, `user_id`, `cbu_cvu`, `alias`, `titular_cuenta`) VALUES
(7, 8, '6565465465465465465456', 'Mathias.45', 'Mathias Sosa'),
(8, 31, '6565465465465465465456', 'Jose.45', 'Jose Perez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `likes`
--

INSERT INTO `likes` (`id`, `estado_id`, `user_id`, `fecha_creacion`) VALUES
(634, 216, 8, '2023-10-28 03:01:35'),
(635, 218, 8, '2023-11-14 18:27:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `read_status`) VALUES
(168, 619311933, 1513570550, 'Hola como estás ?', 1),
(169, 1513570550, 619311933, 'bien', 1),
(170, 619311933, 1513570550, 'eeeeeee', 1),
(171, 1513570550, 619311933, 'que queres?', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `merchant_order_id` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `creator_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `payment_id`, `status`, `payment_type`, `merchant_order_id`, `fecha_registro`, `user_id`, `curso_id`, `creator_user_id`) VALUES
(18, '1318934131', 'approved', 'credit_card', '12589403416', '2023-10-17 14:55:42', 28, 35, 8),
(19, '1315643176', 'approved', 'credit_card', '12768270494', '2023-10-24 21:38:41', 31, 35, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(4000) NOT NULL,
  `img` varchar(255) NOT NULL,
  `cover_img` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `cover_img`, `status`, `is_admin`) VALUES
(8, 1513570550, 'Mathi', 'Sosa', 'matisosa55@hotmail.com', '$2y$10$yXP4XeyM1pbe5kVtlUX6qOw//.U1zx6mjdsPgLgsREhWfYto.Df2G', '6574a28c69584.jpg', '6574a2bd49a4d.jpg', 'Activo ahora', 1),
(31, 619311933, 'Josesito', 'Perez', 'jose@gmail.com', '$2y$10$68977d.1qsmiElcqVLFCSesey0HGpwPy8FRIFG3aaQf6bRKcZqg0m', '1698183023user-3.jpg', '6553bd7ce46e9.webp', 'Desconectado ahora', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_info`
--

CREATE TABLE `user_info` (
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `about_me` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `lives_in` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_info`
--

INSERT INTO `user_info` (`info_id`, `user_id`, `about_me`, `birthday`, `birthplace`, `lives_in`, `occupation`, `join_date`, `gender`, `status`, `email`, `website`, `phone_number`) VALUES
(33, 26, 'soy desarrollador', '1993-10-20', 'Argentina', 'Córdoba', 'Policia Federal', '2023-10-31', 'Masculino', 'Soltero', 'sosamathias311@gmail.com', 'asas', '02644122198'),
(34, 8, 'soy desarrollador de software', '1993-03-11', 'Argentina', 'San Juan', 'Policia Federal', '2018-02-02', 'Masculino', 'Soltero', 'sosamathias311@gmail.com', 'www.matusosa.com.ar', '02644122198');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `configuracion_notificaciones`
--
ALTER TABLE `configuracion_notificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`curso_id`);

--
-- Indices de la tabla `cursos_favoritos`
--
ALTER TABLE `cursos_favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  ADD PRIMARY KEY (`denuncia_id`),
  ADD KEY `user_id_reporter` (`user_id_reporter`),
  ADD KEY `user_id_seller` (`user_id_seller`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indices de la tabla `informacion_pago`
--
ALTER TABLE `informacion_pago`
  ADD PRIMARY KEY (`informacion_pago_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado_id` (`estado_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`info_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `configuracion_notificaciones`
--
ALTER TABLE `configuracion_notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `curso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `cursos_favoritos`
--
ALTER TABLE `cursos_favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `denuncias`
--
ALTER TABLE `denuncias`
  MODIFY `denuncia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT de la tabla `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=748;

--
-- AUTO_INCREMENT de la tabla `informacion_pago`
--
ALTER TABLE `informacion_pago`
  MODIFY `informacion_pago_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=636;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `user_info`
--
ALTER TABLE `user_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `cursos_favoritos`
--
ALTER TABLE `cursos_favoritos`
  ADD CONSTRAINT `cursos_favoritos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Filtros para la tabla `denuncias`
--
ALTER TABLE `denuncias`
  ADD CONSTRAINT `denuncias_ibfk_1` FOREIGN KEY (`user_id_reporter`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `denuncias_ibfk_2` FOREIGN KEY (`user_id_seller`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `denuncias_ibfk_3` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Filtros para la tabla `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `informacion_pago`
--
ALTER TABLE `informacion_pago`
  ADD CONSTRAINT `informacion_pago_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

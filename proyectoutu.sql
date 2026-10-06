-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-09-2026 a las 16:58:19
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
-- Base de datos: `proyectoutu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `ID_CATEGORIA` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`ID_CATEGORIA`, `NOMBRE`) VALUES
(1, 'Parques'),
(2, 'Paisajes y Espacios Naturales'),
(3, 'Patrimonio Histórico'),
(4, 'Museos y Arte'),
(5, 'Termas y Bienestar'),
(6, 'Ocio y Vida Nocturna'),
(7, 'Restaurantes'),
(8, 'Comida Rápida'),
(9, 'Heladerías'),
(10, 'Cafeterías'),
(11, 'Locales Top'),
(12, 'Recomendados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `ID_COMENTARIO` int(11) NOT NULL,
  `ID_USUARIO` int(11) NOT NULL,
  `COMENTARIO` text NOT NULL,
  `FECHA` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`ID_COMENTARIO`, `ID_USUARIO`, `COMENTARIO`, `FECHA`) VALUES
(1, 10, 'Salto es una ciudad muy linda para visitar, especialmente por sus termas.', '2026-09-14 23:49:12'),
(2, 12, 'Me encantaron los parques y los espacios verdes de la ciudad.', '2026-09-14 23:49:12'),
(3, 13, 'Hay muchos lugares interesantes para conocer y disfrutar en familia.', '2026-09-14 23:49:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `ID_EVENTO` int(11) NOT NULL,
  `ID_USUARIO` int(11) NOT NULL,
  `ID_LUGAR` int(11) DEFAULT NULL,
  `NOMBRE` varchar(150) NOT NULL,
  `DESCRIPCION` text DEFAULT NULL,
  `HORA_INI` time NOT NULL,
  `HORA_FIN` time NOT NULL,
  `DIA` int(11) NOT NULL,
  `MES` int(11) NOT NULL,
  `ANIO` smallint(6) DEFAULT NULL,
  `DIRECCION` varchar(255) DEFAULT NULL,
  `LATITUD` decimal(10,7) DEFAULT NULL,
  `LONGITUD` decimal(10,7) DEFAULT NULL,
  `IMAGEN` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`ID_EVENTO`, `ID_USUARIO`, `ID_LUGAR`, `NOMBRE`, `HORA_INI`, `HORA_FIN`, `DIA`, `MES`) VALUES
(13, 10, 5, 'Evento 1', '18:00:00', '21:00:00', 15, 9),
(14, 10, 6, 'Evento 2', '19:00:00', '22:00:00', 18, 9),
(15, 10, 7, 'Evento 3', '20:00:00', '23:00:00', 20, 9),
(16, 10, 8, 'Evento 4', '17:00:00', '20:00:00', 25, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar`
--

CREATE TABLE `lugar` (
  `ID_LUGAR` int(11) NOT NULL,
  `NOMBRE` varchar(150) NOT NULL,
  `DESCRIPCION` text DEFAULT NULL,
  `DIRECCION` varchar(200) NOT NULL,
  `IMAGEN` varchar(255) DEFAULT NULL,
  `LATITUD` decimal(10,7) DEFAULT NULL,
  `LONGITUD` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lugar`
--

INSERT INTO `lugar` (`ID_LUGAR`, `NOMBRE`, `DESCRIPCION`, `DIRECCION`, `IMAGEN`, `LATITUD`, `LONGITUD`) VALUES
(5, 'Trouville', 'Lugar gastronómico para eventos.', 'Artigas 123', 'assets/img/trouville.jpg', NULL, NULL),
(6, 'Café Central', 'Cafetería para eventos.', 'Uruguay 456', 'assets/img/cafe.jpg', NULL, NULL),
(7, 'Heladería del Centro', 'Heladería para eventos.', 'Brasil 789', 'assets/img/heladeria.jpg', NULL, NULL),
(8, 'La Terraza', 'Espacio gastronómico para eventos.', 'Rivera 321', 'assets/img/terraza.jpg', NULL, NULL),
(9, 'Parque Harriague', 'Un espacio verde ideal para relajarse, hacer ejercicio y disfrutar de la naturaleza. El parque cuenta con áreas de picnic, senderos para caminar y zonas de juegos para niños.', 'Salto', 'assets/img/Parques/parqueharriague.jpg', -31.3977508, -57.9623953),
(10, 'Parque Solari', 'Este parque es perfecto para familias y personas que buscan disfrutar de la naturaleza. El parque ofrece instalaciones para deportes y áreas de descanso.', 'Salto', 'assets/img/Parques/parquesolari.png', -31.3791252, -57.9446967),
(11, 'Parque Indigena Vaimaca Pirú', 'Un espacio verde y acogedor, perfecto para familias y personas que buscan disfrutar de la naturaleza. El parque ofrece instalaciones para deportes y áreas de descanso.', 'Salto', 'assets/img/Parques/parqueindigena.jpg', -31.3720848, -57.9797180),
(12, 'Costanera Norte', 'Un lugar ideal para disfrutar de la naturaleza, realizar caminatas y apreciar la belleza del paisaje.', 'Costanera Norte, Salto', 'assets/img/Paisajes/costaneranorte.jpeg', -31.3735000, -57.9690000),
(13, 'Cuevas de San Antonio', 'Un lugar natural y fascinante, con cuevas que albergan una gran variedad de formaciones geológicas y una rica biodiversidad.', 'San Antonio, Salto', 'assets/img/Paisajes/cuevas.png', -31.3520000, -57.9730000),
(14, 'Plaza Roosevelt', 'Un espacio público que ofrece un entorno ideal para relajarse, socializar y disfrutar de la vida urbana.', 'Plaza Roosevelt, Salto', 'assets/img/Paisajes/roosevelt.png', -31.3885000, -57.9685000),
(15, 'Teatro Larrañaga', 'Un espacio cultural emblemático que ofrece una variedad de espectáculos artísticos, desde teatro y música hasta danza y eventos comunitarios.', 'Juan Carlos Gómez, Salto', 'assets/img/Historia/teatro.jpeg', -31.3868228, -57.9682536),
(16, 'Catedral Basílica San Juan Bautista', 'Un majestuoso edificio religioso que combina arquitectura histórica y espiritualidad, siendo un punto de referencia para la comunidad y los visitantes.', 'Artigas 510, Salto', 'assets/img/Historia/catedral.jpg', -31.3887715, -57.9592628),
(17, 'Gran Hotel Concordia', 'Un edificio histórico que combina arquitectura clásica y moderna, ofreciendo a los visitantes una experiencia única de alojamiento y eventos en un entorno elegante y sofisticado.', 'Artigas 500, Salto', 'assets/img/Historia/granhotel.jpg', -31.3872378, -57.9661186),
(21, 'Museo del Hombre y la Tecnología', 'Un espacio dedicado a la preservación y exhibición del patrimonio cultural y tecnológico de la región.', 'Brasil 511, Salto', 'assets/img/Museos/mdelhombre.jpg', -31.3872000, -57.9635000),
(22, 'Museo Bellas Artes', 'El Museo Bellas Artes alberga una colección de obras de arte que abarca desde la pintura y la escultura hasta la fotografía y el diseño, ofreciendo a los visitantes una experiencia cultural enriquecedora.', 'Artigas 808, Salto', 'assets/img/Museos/gallino.jpg', -31.3855000, -57.9610000),
(23, 'Museo de Horacio Quiroga', 'Un espacio dedicado a la preservación y exhibición del patrimonio cultural y literario del escritor Horacio Quiroga.', 'Avenida Manuel Oribe, Salto', 'assets/img/Museos/horacio.jpg', -31.3965000, -57.9530000),
(24, 'Termas del Daymán', 'Uno de los principales centros termales de Salto, reconocido por sus aguas termales y sus espacios de recreación y descanso.', 'Termas del Daymán, Salto', 'assets/img/Termas/dayman.jpeg', -31.4510000, -57.9240000),
(25, 'Termas del Arapey', 'Complejo termal rodeado de naturaleza, ideal para descansar y disfrutar de las propiedades de sus aguas termales.', 'Termas del Arapey, Salto', 'assets/img/Termas/arapey.jpg', -30.9360000, -57.4550000),
(26, 'Acuamanía', 'Parque acuático termal con piscinas y diferentes atracciones para disfrutar en familia.', 'Termas del Daymán, Salto', 'assets/img/Termas/acuamania.jpg', -31.4500000, -57.9230000),
(27, 'Porco Negro', 'Un reconocido lugar de entretenimiento nocturno en Salto, ideal para disfrutar de música, baile y una noche divertida con amigos.', 'Calle Uruguay, Salto', 'assets/img/Ocio/porco.jpg', -31.3878000, -57.9625000),
(28, 'La Ferne', 'Un espacio de entretenimiento y encuentro en Salto, ideal para disfrutar de música, eventos y pasar un buen momento.', 'Calle Uruguay, Salto', 'assets/img/Ocio/ferne.jpg', -31.3882000, -57.9620000),
(29, 'Cine Sarandí', 'Un espacio dedicado al entretenimiento y al cine en Salto, donde se pueden disfrutar diferentes propuestas cinematográficas y actividades culturales.', 'Calle Sarandí, Salto', 'assets/img/Ocio/cine.jpg', -31.3888000, -57.9605000),
(30, 'El Rancho', 'Restaurante de Salto con una propuesta gastronómica variada, ideal para disfrutar de carnes, platos tradicionales y un ambiente agradable.', 'Calle Uruguay, Salto', 'assets/img/Restaurantes/elrancho.jpeg', -31.3872000, -57.9645000),
(31, 'La Trattoria', 'Restaurante especializado en gastronomía italiana, con una propuesta que incluye pastas, pizzas y otros platos tradicionales.', 'Calle Uruguay, Salto', 'assets/img/Restaurantes/trattoria.jpeg', -31.3885000, -57.9612000),
(32, 'La Caldera', 'Restaurante y parrilla tradicional de Salto, ideal para disfrutar de carnes y diferentes platos en un ambiente agradable.', 'Calle Uruguay, Salto', 'assets/img/Restaurantes/lacaldera.jpeg', -31.3860000, -57.9630000),
(33, 'Restaurant Trouville', 'Un restaurante tradicional de Salto que ofrece una variedad de comidas rápidas y platos conocidos, ideal para disfrutar de una comida informal.', 'Calle Uruguay, Salto', 'assets/img/ComidaRapida/trouville.jpeg', -31.3878000, -57.9628000),
(34, 'Burger King', 'Una reconocida cadena de comida rápida que ofrece hamburguesas, papas fritas y diferentes opciones para disfrutar de una comida rápida.', 'Salto Shopping, Av. Batlle 2260, Salto', 'assets/img/ComidaRapida/burgerking.jpeg', -31.3850000, -57.9480000),
(35, 'Subway', 'Una reconocida cadena de comida rápida especializada en sándwiches preparados al momento, con diferentes tipos de panes, ingredientes y opciones para elegir.', 'Calle Uruguay, Salto', 'assets/img/ComidaRapida/subway.jpeg', -31.3880000, -57.9615000),
(36, 'La Nevada', 'Heladería de Salto que ofrece una variedad de sabores de helado y diferentes opciones dulces para disfrutar en cualquier momento.', 'Calle Uruguay, Salto', 'assets/img/Heladerias/nevada.jpeg', -31.3875000, -57.9620000),
(37, 'Heladería Premium', 'Heladería de Salto que ofrece una variedad de sabores y productos helados, ideal para disfrutar de un momento dulce en familia o con amigos.', 'Calle Uruguay, Salto', 'assets/img/Heladerias/premium.jpeg', -31.3882000, -57.9605000),
(38, 'Heladería Alfredito', 'Heladería tradicional de Salto que ofrece diferentes sabores de helado y opciones dulces en un ambiente familiar y agradable.', 'Calle Uruguay, Salto', 'assets/img/Heladerias/alfredito.jpeg', -31.3890000, -57.9585000),
(42, 'La Don Diego', 'Esta cafetería ofrece una experiencia única con su combinación de productos de panadería y confitería, creando un ambiente acogedor para disfrutar de momentos especiales.', 'Calle Uruguay, Salto', 'assets/img/Panaderias/dondiego.jpeg', -31.3870000, -57.9630000),
(43, 'La Minerva', 'Esta panadería, aparte de ofrecer productos de panadería y confitería, también te ofrece un ambiente acogedor para disfrutar con amigos y familiares.', 'Calle Uruguay, Salto', 'assets/img/Panaderias/minerva.jpeg', -31.3895000, -57.9590000),
(44, 'La Aurora', 'Esta cafetería ofrece una experiencia única con su combinación de hamburguesas gourmet y café de alta calidad, creando un ambiente acogedor para disfrutar de momentos especiales.', 'Calle Uruguay, Salto', 'assets/img/Panaderias/aurora.jpeg', -31.3862000, -57.9655000),
(45, 'Jefris', 'Cafetería de Salto ideal para disfrutar de un buen café, acompañada de diferentes opciones dulces y saladas en un ambiente agradable.', 'Calle Uruguay, Salto', 'assets/img/Cafeterias/jefris.jpeg', -31.3870000, -57.9630000),
(46, 'La Recova', 'Cafetería de Salto que ofrece diferentes opciones para disfrutar de un café, una merienda o compartir un momento agradable.', 'Calle Uruguay, Salto', 'assets/img/Cafeterias/recova.jpeg', -31.3895000, -57.9590000),
(47, 'Basalto', 'Cafetería y espacio gastronómico ideal para disfrutar de café, comidas y diferentes propuestas en un ambiente moderno y agradable.', 'Calle Uruguay, Salto', 'assets/img/Cafeterias/basalto.jpeg', -31.3862000, -57.9655000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar_categoria`
--

CREATE TABLE `lugar_categoria` (
  `ID_LUGAR` int(11) NOT NULL,
  `ID_CATEGORIA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lugar_categoria`
--

INSERT INTO `lugar_categoria` (`ID_LUGAR`, `ID_CATEGORIA`) VALUES
(9, 1),
(10, 1),
(11, 1),
(12, 2),
(13, 2),
(14, 2),
(15, 3),
(16, 3),
(17, 3),
(21, 4),
(22, 4),
(23, 4),
(24, 5),
(25, 5),
(26, 5),
(27, 6),
(28, 6),
(29, 6),
(30, 7),
(31, 7),
(32, 7),
(33, 8),
(34, 8),
(35, 8),
(36, 9),
(37, 9),
(38, 9),
(42, 10),
(43, 10),
(44, 10),
(45, 10),
(46, 10),
(47, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `ID_ROL` int(11) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`ID_ROL`, `NOMBRE`) VALUES
(1, 'Administrador'),
(2, 'Organizador'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_USUARIO` int(11) NOT NULL,
  `ID_ROL` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `CONTRASENA` varchar(255) NOT NULL,
  `CORREO` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_USUARIO`, `ID_ROL`, `NOMBRE`, `CONTRASENA`, `CORREO`) VALUES
(10, 1, 'Facundo', 'Pollibisio2006', 'facubisio410@gmail.com'),
(11, 3, 'dasd', '231312', 'dasdas@fdasdas'),
(12, 3, 'Usuario1', '123', 'usuario1@gmail.com'),
(13, 3, 'Usuario2', '123', 'usuario2@gmal.com'),
(14, 3, 'juan', '12345678', 'juan@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_telefono`
--

CREATE TABLE `usuario_telefono` (
  `ID_USUARIO` int(11) NOT NULL,
  `TELEFONO` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_telefono`
--

INSERT INTO `usuario_telefono` (`ID_USUARIO`, `TELEFONO`) VALUES
(10, '092902842'),
(11, '3213312'),
(12, '091091091'),
(13, '092092092'),
(14, '091352873');

--
-- Estructura de tablas para reacciones y comentarios de eventos
--

CREATE TABLE `evento_reaccion` (
  `ID_EVENTO` int(11) NOT NULL,
  `ID_USUARIO` int(11) NOT NULL,
  `TIPO` tinyint(4) NOT NULL,
  `FECHA` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_EVENTO`,`ID_USUARIO`),
  KEY `FK_REACCION_USUARIO` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `evento_comentario` (
  `ID_COMENTARIO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_EVENTO` int(11) NOT NULL,
  `ID_USUARIO` int(11) NOT NULL,
  `COMENTARIO` varchar(1000) NOT NULL,
  `FECHA` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_COMENTARIO`),
  KEY `FK_COMENTARIO_EVENTO` (`ID_EVENTO`),
  KEY `FK_EVENTO_COMENTARIO_USUARIO` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ID_CATEGORIA`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`ID_COMENTARIO`),
  ADD KEY `FK_COMENTARIO_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`ID_EVENTO`),
  ADD KEY `FK_EVENTO_USUARIO` (`ID_USUARIO`),
  ADD KEY `FK_EVENTO_LUGAR` (`ID_LUGAR`);

--
-- Indices de la tabla `lugar`
--
ALTER TABLE `lugar`
  ADD PRIMARY KEY (`ID_LUGAR`);

--
-- Indices de la tabla `lugar_categoria`
--
ALTER TABLE `lugar_categoria`
  ADD PRIMARY KEY (`ID_LUGAR`,`ID_CATEGORIA`),
  ADD KEY `ID_CATEGORIA` (`ID_CATEGORIA`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`ID_ROL`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD KEY `FK_USUARIO_ROL` (`ID_ROL`);

--
-- Indices de la tabla `usuario_telefono`
--
ALTER TABLE `usuario_telefono`
  ADD PRIMARY KEY (`ID_USUARIO`,`TELEFONO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ID_CATEGORIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1114;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `ID_COMENTARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `ID_EVENTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `lugar`
--
ALTER TABLE `lugar`
  MODIFY `ID_LUGAR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_ROL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `FK_COMENTARIO_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `FK_EVENTO_LUGAR` FOREIGN KEY (`ID_LUGAR`) REFERENCES `lugar` (`ID_LUGAR`),
  ADD CONSTRAINT `FK_EVENTO_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`);

--
-- Filtros para la tabla `lugar_categoria`
--
ALTER TABLE `lugar_categoria`
  ADD CONSTRAINT `lugar_categoria_ibfk_1` FOREIGN KEY (`ID_LUGAR`) REFERENCES `lugar` (`ID_LUGAR`) ON DELETE CASCADE,
  ADD CONSTRAINT `lugar_categoria_ibfk_2` FOREIGN KEY (`ID_CATEGORIA`) REFERENCES `categoria` (`ID_CATEGORIA`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FK_USUARIO_ROL` FOREIGN KEY (`ID_ROL`) REFERENCES `rol` (`ID_ROL`);

--
-- Filtros para la tabla `usuario_telefono`
--
ALTER TABLE `usuario_telefono`
  ADD CONSTRAINT `FK_TELEFONO_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`) ON DELETE CASCADE;

--
-- Filtros para reacciones y comentarios de eventos
--
ALTER TABLE `evento_reaccion`
  ADD CONSTRAINT `FK_REACCION_EVENTO` FOREIGN KEY (`ID_EVENTO`) REFERENCES `evento` (`ID_EVENTO`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_REACCION_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`) ON DELETE CASCADE,
  ADD CONSTRAINT `CHK_REACCION_TIPO` CHECK (`TIPO` IN (-1, 1));

ALTER TABLE `evento_comentario`
  ADD CONSTRAINT `FK_COMENTARIO_EVENTO` FOREIGN KEY (`ID_EVENTO`) REFERENCES `evento` (`ID_EVENTO`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EVENTO_COMENTARIO_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

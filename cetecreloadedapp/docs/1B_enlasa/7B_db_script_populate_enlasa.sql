--
-- Volcado de datos para la tabla `days`
--

INSERT INTO `days` (`id_days`, `days_name`) VALUES
(0, 'Domingo'),
(1, 'Lunes'),
(2, 'Martes'),
(3, 'Miércoles'),
(4, 'Jueves'),
(5, 'Viernes'),
(6, 'Sábado');

--
-- Volcado de datos para la tabla `keys`
--

INSERT INTO `keys` (`id_key`, `k_name`, `k_amount`, `k_description`, `type`) VALUES
(1, 'Vacaciones', '0.00', NULL, 0),
(2, 'Llega puntual', '0.00', NULL, 0),
(3, 'Aguinaldo', '0.00', NULL, 0),
(4, 'Hace bien su trabajo', '0.00', NULL, 0),
(5, 'Retardo', '0.00', NULL, 1),
(6, 'Hace mal su trabajo', '0.00', NULL, 1);

--
-- Volcado de datos para la tabla `num`
--

INSERT INTO `num` (`i`) VALUES
(0),
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9);

--
-- Volcado de datos para la tabla `preferences`
--

INSERT INTO `preferences` (`id`, `name`, `value`) VALUES
(1, 'IVA', '16'),
(2, 'FOTO', ''),
(3, 'TUTORIAL', 'Activo'),
(4, 'ICONS', 'minimal');

--
-- Volcado de datos para la tabla `state_of_inventories`
--

INSERT INTO `state_of_inventories` (`id_state_Inventory`, `conditions`) VALUES
(1, 'Nuevo'),
(2, 'En buen estado'),
(3, 'Obsoleto'),
(4, 'Deteriorado'),
(5, 'Viejo'),
(6, 'Defectuoso'),
(7, 'No funcional');

--
-- Volcado de datos para la tabla `update_actions`
--

INSERT INTO `update_actions` (`id`, `actions`) VALUES
(0, 'updated'),
(1, 'waiting'),
(2, 'done');
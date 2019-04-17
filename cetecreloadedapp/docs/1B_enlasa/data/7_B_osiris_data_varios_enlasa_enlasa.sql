--
-- Volcado de datos para la tabla `update_actions`
--

INSERT INTO `update_actions` (`id`, `actions`) VALUES
(0, 'updated'),
(1, 'waiting'),
(2, 'done');

--
-- Volcado de datos para la tabla `company`
--

INSERT INTO `company` (`id_company`, `name_company`, `brand`, `rfc`, `website`, `company_isactive`, `name_bank`, `number_acount`, `interbank_clabe`, `sucursal_name`, `record_date`, `business`, `id_update_actions`, `progress_profile`, `cust_type`, `isprospect`, `ishost`, `interestingin`) VALUES
(1, 'Enlasa', 'Enlasa', 'enl12178g', 'enlasa.com', 1, 'Santander', '123456789', '1234567890', '001', '2014-11-26', 'Entregas Enlasa', 1, 10, 0, 0, 1, NULL);

--
-- Volcado de datos para la tabla `addresses`
--

INSERT INTO `addresses` (`id_address`, `company_id`, `street`, `postalcode`, `number`, `interior`, `neighborhood`, `state_id`, `district`, `phone`, `ext`, `url_map`) VALUES
(1, 1, 'Avenida Siempre Viva', 2000, '7', '5', 1, 1, 1, '5567894325', 3, 'https://www.google.com.mx/maps/place/Av+Siempre+Viva,+Vista+Hermosa,+45618+JAL/@20.5782338,-103.3339728,17z/data=!3m1!4b1!4m2!3m1!1s0x8428b2ca3d0ab4df:0xf736fea037b0a9b9');

--
-- Volcado de datos para la tabla `users_addresses`
--

/*INSERT INTO `users_addresses` (`id_address`, `user_id`, `street`, `postalcode`, `number`, `interior`, `state_id`, `district_id`, `neighborhood`) VALUES
(1, 1, 'Avenida Siempre Viva', 2000, '7', '5', 1, 1, 1);*/


--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id_category`, `c_name`, `c_description`) VALUES
(1, 'Transportes', NULL);

--
-- Volcado de datos para la tabla `department`
--

INSERT INTO `department` (`id_department`, `d_name`, `d_description`) VALUES
(1, 'General', NULL);

--
-- Volcado de datos para la tabla `job_users`
--

INSERT INTO `job_users` (`id`, `name_job`, `description`) VALUES
(1, 'Administrador', NULL);

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
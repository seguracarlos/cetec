--
-- Volcado de datos para la tabla `iof_role`
--

INSERT INTO `iof_role` (`rid`, `role_name`, `type_user`, `status`, `Active`) VALUES
(1, 'SuperUsuario', 0, 'Active', 1),
(2, 'Administrador', 0, 'Active', 1),
(3, 'Usuario', 0, 'Active', 1),
(4, 'Empleado', 0, 'Active', 1),
(5, 'Cliente', 1, 'Active', 1),
(6, 'Proveedor', 1, 'Active', 1),
(7, 'Vendedor', 0, 'Active', 1),
(8, 'Compras', 0, 'Active', 1),
(9, 'jslkjad', NULL, 'Active', NULL);

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

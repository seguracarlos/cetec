
--
-- Volcado de datos para la tabla `iof_resource`
--

INSERT INTO `iof_resource` (`id`, `resource_name`, `app`, `agroupName`, `menutemp`, `name`, `nameMenu`, `path`, `sub`, `isdisplayed`, `displayedoreder`, `resource`, `agroup`) VALUES
(1, 'Users\\Controller\\Index', 'Sistema', 'Sistema', 'Sistema', 'Usuarios', 'Lista de Usuarios', 'system', NULL, 1, 1, 'Users\\Controller\\Index', NULL),
(2, 'Permissions\\Controller\\Index', 'Sistema', 'Sistema', 'Sistema', 'Sistema', 'Lista de Permisos', 'system', NULL, 1, 2, 'Permissions\\Controller\\Index', NULL),
(5, 'Company\\Controller\\Index', 'Sistema', 'Sistema', 'Sistema', 'Company', 'Mi empresa', 'system', NULL, 1, 4, 'Company\\Controller\\Index', NULL),
(6, 'Customers\\Controller\\Index', 'Ingresos', 'Ingresos', 'Ingresos', 'Customers', 'Lista de Clientes', 'system', NULL, 1, 1, 'Customers\\Controller\\Index', NULL),
(7, 'In\\Controller\\Customers', 'Ingresos', 'Ingresos', 'Ingresos', 'Customers', 'Lista de cleintes modulo in', 'in', NULL, 1, 1, 'In\\Controller\\Customers', NULL);

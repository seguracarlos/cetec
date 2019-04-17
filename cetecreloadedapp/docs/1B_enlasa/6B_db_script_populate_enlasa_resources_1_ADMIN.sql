
--
-- Volcado de datos para la tabla `iof_permission`
--

INSERT INTO `iof_permission` (`id`, `permission_name`, `pathResource`, `resource_id`, `resourceName`, `name_esp`, `app`, `name_menu`, `sub_action`, `agroup`, `agroupName`, `menutemp`, `is_displayed_action`, `displayed_order_action`) VALUES
(1, 'index', 'system/users/index', 1, 'Users\\Controller\\Index', 'Inicio', 'Sistema', 'Lista de usuarios', NULL, NULL, NULL, NULL, 1, 1),
(2, 'add', 'system/users/add', 1, 'Users\\Controller\\Index', 'Agregar', 'Sistema', 'Agregar usuarios', NULL, NULL, NULL, NULL, 0, 0),
(3, 'edit', 'system/users/edit', 1, 'Users\\Controller\\Index', 'Editar', 'Sistema', 'Editar Usuario', NULL, NULL, NULL, NULL, 0, NULL),
(4, 'delete', 'system/users/delete', 1, 'Users\\Controller\\Index', 'Eliminar', 'Sistema', 'Borrar Usuario', NULL, NULL, NULL, NULL, 0, NULL),
(5, 'see', 'system/users/see', 1, 'Users\\Controller\\Index', 'Ver', 'Sistema', 'Ver usuario', NULL, NULL, NULL, NULL, 0, 0),
(6, 'index', 'system/permissions/index', 2, 'Permissions\\Controller\\Index', 'Inicio', 'Sistema', 'Lista de permisos', NULL, NULL, NULL, NULL, 1, 1),
(7, 'add', 'system/permissions/add', 2, 'Permissions\\Controller\\Index', 'Agregar', 'Sistema', 'Agregar permiso', NULL, NULL, NULL, NULL, 0, NULL),
(8, 'update', 'system/permissions/update', 2, 'Permissions\\Controller\\Index', 'Editar', 'Sistema', 'Actualizar permiso', NULL, NULL, NULL, NULL, 0, NULL),
(9, 'delete', 'system/permissionsdelete', 2, 'Permissions\\Controller\\Index', 'Eliminar', 'Sistema', 'Borrar permiso', NULL, NULL, NULL, NULL, 0, NULL),
(12, 'index', 'company/index/index', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Mi empresa', NULL, NULL, NULL, NULL, 1, 1),
(13, 'updatelogo', 'company/index/updatelogo', 5, 'Company\\Controller\\Index', 'Editar', 'Sistema', 'Actualizar logo de la empresa', NULL, NULL, NULL, NULL, 0, NULL),
(14, 'getlogo', 'company/index/getlogo', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Obtener logo de la empresa', NULL, NULL, NULL, NULL, 0, NULL),
(15, 'getinfo', 'company/index/getinfo', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Obtener información de la empresa', NULL, NULL, NULL, NULL, 0, NULL),
(16, 'index', 'customers/index/index', 6, 'Customers\\Controller\\Index', 'Inicio', 'Ingresos', 'Lista de clientes', NULL, NULL, NULL, NULL, 1, NULL),
(17, 'getdistricts', 'company/index/getdistricts', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Obtener estados', NULL, NULL, NULL, NULL, 0, NULL),
(18, 'getcolonys', 'company/index/getcolonys', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Obtener colonias', NULL, NULL, NULL, NULL, 0, NULL),
(19, 'getpostalcode', 'company/index/postalcode', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Obtener codigo postal', NULL, NULL, NULL, NULL, 0, NULL),
(20, 'updatecompany', 'company/index/updatecompany', 5, 'Company\\Controller\\Index', 'Ver', 'Sistema', 'Actualizar Compañia', NULL, NULL, NULL, NULL, 0, NULL);
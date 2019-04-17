-- INSERTS para la tabla acl_roles

INSERT INTO `acl_roles` (`role_id`, `role_name`, `type_user`, `active`) VALUES
(1, 'SuperUsuario', 0, 1),
(2, 'Administrador', 0, 1),
(3, 'Usuario', 0, 1),
(4, 'Empleado', 0, 1),
(5, 'Cliente', 1, 1),
(6, 'Proveedor', 1, 1),
(7, 'Vendedor', 0, 1),
(8, 'Compras',0,1);

-- INSERTS para la tabla job_users

INSERT INTO `job_users` (`id`, `name_job`) VALUES
(1, 'General'),
(2, 'Director'),
(3, 'Supervisor'),
(4, 'Administrador'),
(5, 'Operador'),
(6, 'Analista'),
(7, 'Ayudante'),
(8, 'Vendedor'),
(9, 'Vendedor en punto de vta'),
(10, 'Repartidor');

-- Departamentos

INSERT INTO `department` (`id_department`, `d_name`, `d_description`) VALUES 
(1, 'General', NULL),
(2, 'Dirección', NULL),
(3, 'Compras', NULL), 
(4, 'Recursos Humanos', NULL), 
(5, 'Control de Gestión', NULL), 
(6, 'Marketing', NULL), 
(7, 'Ventas', NULL), 
(8, 'Administración', NULL), 
(9, 'Finanzas', NULL);

-- Category

INSERT INTO `category` (`id_category` ,`c_name` ,`c_description`)VALUES 
('1', 'Almacen', 'Productos almacenados'),
('2', 'Materia prima', 'Materia prima'),
('3', 'Producto Terminado', 'Producto Terminado');

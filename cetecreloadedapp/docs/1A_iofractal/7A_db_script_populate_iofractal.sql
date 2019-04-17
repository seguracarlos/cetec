/*
* INSERTS para la tabla project_role
*/
INSERT INTO `project_role` (`id_project_role`, `project_role_name`) VALUES
(1, 'Product Owner'),
(2, 'Scrum Master'),
(3, 'Desarrollador'),
(4, 'Stakeholder'),
(5, 'Administrador de Proyectos'),
(6, 'Diseñador Gráfico');

/*
* INSERTS para la tabla state_of_inventories
*/
INSERT INTO `state_of_inventories` (`id_state_Inventory`, `conditions`) VALUES
(3, 'Obsoleto'),
(4, 'Deteriorado'),
(5, 'Viejo'),
(6, 'Defectuoso'),
(7, 'No funcional'),
(1, 'Nuevo'),
(2, 'En buen estado');

/*
* INSERTS para la tabla update_actions
*/
INSERT INTO `update_actions` (`id`, `actions`) VALUES
(0, 'updated'),
(1, 'waiting'),
(2, 'done');

/*
* INSERTS para la tabla tipo_tutorial
*/
INSERT INTO `tipo_tutorial` (`id_tipo`, `name_tipo`) VALUES
(1, 'Tecnológico'),
(2, 'Administrativo'),
(3, 'Educativo'),
(4, 'Documental');

/*
* INSERTS para la tabla days
*/
INSERT INTO `days` (`id_days`, `days_name`) VALUES
(1, 'Lunes'),
(2, 'Martes'),
(3, 'Miércoles'),
(4, 'Jueves'),
(5, 'Viernes'),
(6, 'Sábado'),
(0, 'Domingo');

/*
* INSERTS para la tabla types
*/
INSERT INTO `types` (`id_types`, `description`) VALUES
(1, 'Hardware'),
(2, 'Mobiliario'),
(3, 'Equipo'),
(4, 'Software'); 
/*
* INSERTS para la tabla num
*/
INSERT INTO `num` VALUES (0),(1),(2),(3),(4),(5),(6),(7),(8),(9);

/*
* INSERTS para la tabla ho_mondays
*/
insert into ho_mondays(
    select xx.w, xx.ff from
    (
    select week(x.ff) as w, dayofweek(x.ff) as f, x.ff as ff from (
        select a.Date as ff
        from (
            select date('2013-12-31') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
            from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
        ) a
        where a.Date between '2013-01-01' and '2013-12-31') x
    where dayofweek(x.ff)=2
    ) xx
order by xx.w
);

/*
* INSERTS para la tabla contract_types
*/
INSERT INTO `contract_types` VALUES (1,'Temporal'),(2,'Permanente');

/*
* INSERTS para la tabla preferences
*/
INSERT INTO `preferences` (`id`, `name` ,`value`) VALUES 
(1, 'IVA', '16'),
(2, 'FOTO', ''),
(3, 'TUTORIAL', 'Activo'),
(4, 'ICONS', 'minimal');


/*
* INSERTS para la tabla tipo_books
*/
INSERT INTO `tipo_books` (`id_type`, `type_book`) VALUES
(1, 'Científico'),
(2, 'Tecnología'),
(3, 'Educativo'),
(4, 'Instructivos'),
(5, 'Literarios'),
(6, 'Novela');

/*
* INSERTS para la tabla articles_of_inventories
*/
INSERT INTO `articles_of_inventories` (`id`, `id_types`, `name_article`) VALUES
(1, 1, 'Bocinas'),
(2, 1, 'CPU'),
(3, 1, 'Laptop'),
(4, 1, 'Monitor'),
(5, 1, 'Mouse'),
(6, 1, 'Regulador'),
(7, 1, 'Teclado'),
(8, 2, 'Escritorio'),
(9, 2, 'Silla'); 



--
-- Volcado de datos para la tabla `keys`
--

INSERT INTO `keys` (`id_key`, `k_name`, `type`) VALUES
(1, 'Vacaciones', 0),
(2, 'Llega puntual', 0),
(3, 'Aguinaldo', 0),
(4, 'Hace bien su trabajo', 0),
(5, 'Retardo', 1),
(6, 'Hace mal su trabajo', 1);

INSERT INTO `measuring_unit` (`id_measuring`, `unit`, `description`) VALUES
(1, 'Kilogramo', NULL),
(2, 'Metro', NULL),
(3, 'Litro', NULL),
(4, 'Tonelada', NULL),
(5, 'Galón', NULL),
(6, 'Metro cuadrado', NULL),
(7, 'Pieza', NULL);

--
-- Dumping data for table `account_category`
--

INSERT INTO `account_category` (`id_category`, `name`, `description`) VALUES
(100, 'CAJA', 'Cuenta para la caja'),
(200, 'BANCO', 'Cuenta para los bancos'),
(300, 'VENTAS', 'Cuenta para las ventas'),
(400, 'CXC', 'Cuenta para las cxc'),
(500, 'CXP', 'Cuenta para las cxp'),
(600, 'CLIENTES', 'Cuenta para los Clientes'),
(700, 'PROVEEDORES', 'Cuenta para los Proveedores'),
(800, 'CHEQUES', 'Cuenta para los pagos con cheques');

--
-- Volcado de datos para la tabla `account`
--

INSERT INTO `account` (`id_account`, `id_category`, `name`, `description`) VALUES
(101, 100, 'Gastos Fijos', 'Caja para los gastos fijos'),
(102, 100, 'Gasto Variables', 'Caja para los gastos variables'),
(301, 300, 'Ventas', 'Caja para pagos con tarjeta'),
(401, 400, 'Proyectos', 'cxc de proyectos'),
(601, 600, 'Jackbe', 'Cuenta del cliente Jackbe'),
(701, 700, 'Ventas-Cheque', 'Cuenta de las ventas pagadas con cheques');

--
-- tabla currentBalance
-- 

INSERT INTO `account_current_balance` (`id_current`, `id_account`, `time`, `amount`) VALUES 
(null, '101', '2014-01-24 09:13:41', '0.00'),
(null, '102', '2014-01-24 09:13:41', '0.00'),
(null, '301', '2014-01-24 09:13:41', '0.00'),
(null, '401', '2014-01-24 09:13:41', '0.00'),
(null, '601', '2014-01-24 09:13:41', '0.00'),
(null, '701', '2014-01-24 09:13:41', '0.00');

--
-- Dumping data for table `cash`
--

INSERT INTO `cash` (`id_cash`, `name_cash`, `description`) VALUES
(1, 'Gastos Fijos', ''),
(2, 'Gastos Variables', '');

--
-- Dumping data for table `cash_current_balance`
--

INSERT INTO `cash_current_balance` (`id_current`, `id_cash`, `time`, `amount`) VALUES
(1, 1, '2014-01-24 09:13:41', '0.00'),
(2, 2, '2014-01-24 09:13:52', '0.00');

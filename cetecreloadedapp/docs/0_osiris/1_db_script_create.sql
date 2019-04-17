--
-- Estructura de tabla para la tabla `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id_account` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_category`
--

CREATE TABLE IF NOT EXISTS `account_category` (
  `id_category` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_current_balance`
--

CREATE TABLE IF NOT EXISTS `account_current_balance` (
`id_current` int(11) NOT NULL,
  `id_account` int(11) NOT NULL,
  `time` datetime DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_tx`
--

CREATE TABLE IF NOT EXISTS `account_tx` (
`id_tx` int(11) NOT NULL,
  `id_account` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `withdraw` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `type_amount` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `manual` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acc_receivable`
--

CREATE TABLE IF NOT EXISTS `acc_receivable` (
`id` int(11) NOT NULL COMMENT 'Es la tabla para CxC',
  `projectid` int(11) DEFAULT NULL COMMENT 'ID del Proyecto',
  `date_exp` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `month_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Acumula las CXC se ingresa por el SP' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
`id_activities` int(4) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `log_moday_activity_date` date NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `projects_ID` int(4) NOT NULL,
  `user_id` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Registro de las actividades de los usuarios' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activitydates`
--

CREATE TABLE IF NOT EXISTS `activitydates` (
`id_dates_activity` int(11) NOT NULL,
  `id_fk_activities` int(4) NOT NULL,
  `hours` int(3) DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `id_fk_days` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
`id_address` int(4) NOT NULL,
  `company_id` int(11) NOT NULL,
  `street` varchar(45) NOT NULL,
  `postalcode` int(5) NOT NULL,
  `number` varchar(10) NOT NULL,
  `interior` varchar(10) DEFAULT NULL,
  `neighborhood` int(5) NOT NULL,
  `state_id` int(5) NOT NULL,
  `district` int(5) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `ext` int(11) DEFAULT NULL,
  `url_map` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Direcciones de los clientes, proveedores o Host' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles_of_inventories`
--

CREATE TABLE IF NOT EXISTS `articles_of_inventories` (
`id` int(11) NOT NULL,
  `name_article` varchar(80) NOT NULL,
  `id_types` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank`
--

CREATE TABLE IF NOT EXISTS `bank` (
`id_bank` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type_amount` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banks`
--

CREATE TABLE IF NOT EXISTS `banks` (
`id_bank` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `account` varchar(50) NOT NULL,
  `clabe` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL COMMENT 'Sucursal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banks_tx`
--

CREATE TABLE IF NOT EXISTS `banks_tx` (
`id_tx` int(11) NOT NULL,
  `id_bank` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `withdraw` decimal(10,2) DEFAULT NULL,
  `deposit` decimal(10,2) DEFAULT NULL,
  `type_amount` int(11) NOT NULL,
  `manual` int(1) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_current_balance`
--

CREATE TABLE IF NOT EXISTS `bank_current_balance` (
`id_current` int(11) NOT NULL,
  `id_bank` int(11) NOT NULL,
  `time` datetime DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `books`
--

CREATE TABLE IF NOT EXISTS `books` (
`idbooks` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `author` varchar(50) NOT NULL,
  `editorial` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `url` varchar(45) DEFAULT NULL,
  `pages` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `numIsbn` varchar(10) NOT NULL,
  `fisico` varchar(8) DEFAULT NULL,
  `electronico` varchar(11) DEFAULT NULL,
  `tipo_books` int(11) NOT NULL,
  `date_hour` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acl_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Inventario de libros' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash`
--

CREATE TABLE IF NOT EXISTS `cash` (
`id_cash` int(11) NOT NULL,
  `name_cash` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_current_balance`
--

CREATE TABLE IF NOT EXISTS `cash_current_balance` (
`id_current` int(11) NOT NULL,
  `id_cash` int(11) NOT NULL,
  `time` datetime DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_tx`
--

CREATE TABLE IF NOT EXISTS `cash_tx` (
`id_tx` int(11) NOT NULL,
  `id_cash` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `withdraw` decimal(10,2) DEFAULT NULL,
  `deposit` decimal(10,2) DEFAULT NULL,
  `type_amount` int(11) NOT NULL,
  `manual` int(1) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id_category` int(10) NOT NULL,
  `c_name` varchar(45) NOT NULL,
  `c_description` text
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clock`
--

CREATE TABLE IF NOT EXISTS `clock` (
  `acl_users_id` int(4) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(45) NOT NULL,
  `type` int(11) NOT NULL COMMENT 'El tipo 1 es entrada, el 2 salida\\\\\\\\n',
  `ip` varchar(15) NOT NULL,
  `clockcol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Registro del Reloj Checador';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

CREATE TABLE IF NOT EXISTS `company` (
`id_company` int(5) NOT NULL,
  `name_company` varchar(100) DEFAULT NULL,
  `brand` varchar(100) NOT NULL,
  `rfc` varchar(35) NOT NULL,
  `website` varchar(45) DEFAULT NULL,
  `company_isactive` tinyint(1) DEFAULT NULL,
  `name_bank` varchar(100) NOT NULL,
  `number_acount` varchar(100) NOT NULL,
  `interbank_clabe` varchar(100) NOT NULL,
  `sucursal_name` varchar(100) DEFAULT NULL,
  `record_date` date NOT NULL,
  `business` varchar(100) DEFAULT NULL,
  `id_update_actions` int(5) NOT NULL,
  `progress_profile` int(11) DEFAULT '0',
  `cust_type` int(1) NOT NULL DEFAULT '0',
  `isprospect` int(1) NOT NULL DEFAULT '0',
  `ishost` int(1) DEFAULT '0',
  `interestingin` varchar(500) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Entidad compania de cliente, proveedor o host' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_contact`
--

CREATE TABLE IF NOT EXISTS `company_contact` (
`id_contact` int(5) NOT NULL,
  `charge_contact` varchar(40) NOT NULL,
  `name_contact` varchar(30) NOT NULL,
  `surname_contact` varchar(30) NOT NULL,
  `lastname_contact` varchar(30) DEFAULT NULL,
  `phone_contact` varchar(20) NOT NULL,
  `ext_phone` varchar(7) DEFAULT NULL,
  `cellphone_contact` varchar(20) DEFAULT NULL,
  `mail_contact` varchar(30) DEFAULT NULL,
  `birthday_day` int(2) DEFAULT NULL,
  `birthday_month` varchar(10) DEFAULT NULL,
  `type_principal` int(5) DEFAULT NULL,
  `company_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `components`
--

CREATE TABLE IF NOT EXISTS `components` (
`component_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `type_component` varchar(10) NOT NULL,
  `acl_users_id` int(4) NOT NULL,
  `date_hour` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='IOTeca Registra los componentes de Software' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
`id_content` int(11) NOT NULL,
  `content` longblob NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_user` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contenido de los temas de encuestas o documentos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract_types`
--

CREATE TABLE IF NOT EXISTS `contract_types` (
  `id_contract` int(2) NOT NULL,
  `name_contract` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cron_token`
--

CREATE TABLE IF NOT EXISTS `cron_token` (
  `token_key` varchar(60) NOT NULL,
  `token_data` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Control para el CRON';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curses`
--

CREATE TABLE IF NOT EXISTS `curses` (
`idcurses` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `date_start` varchar(20) NOT NULL,
  `date_finish` varchar(20) NOT NULL,
  `members` int(11) NOT NULL,
  `status` varchar(8) NOT NULL,
  `description` varchar(45) NOT NULL,
  `date_hour` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acl_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Se debe llamar COURSES' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv`
--

CREATE TABLE IF NOT EXISTS `cv` (
`idcvitae` int(11) NOT NULL,
  `acl_users_id` int(4) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `objective` varchar(140) DEFAULT NULL,
  `vision` varchar(140) DEFAULT NULL,
  `current_job_position` varchar(145) DEFAULT NULL,
  `education_type` varchar(45) DEFAULT NULL,
  `school` varchar(145) DEFAULT NULL,
  `academic_degree` varchar(40) DEFAULT NULL,
  `degree_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CV de los colaboradores' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_curses`
--

CREATE TABLE IF NOT EXISTS `cv_curses` (
`id` int(11) NOT NULL,
  `name` varchar(145) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cursos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_curses_employee`
--

CREATE TABLE IF NOT EXISTS `cv_curses_employee` (
  `c_vitae_idcvitae` int(11) NOT NULL,
  `c_vitae_acl_users_id` int(4) NOT NULL,
  `cv_curses_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_jobexperience_tech`
--

CREATE TABLE IF NOT EXISTS `cv_jobexperience_tech` (
  `technologies_id_tech` int(11) NOT NULL,
  `job_experience_idjob_experience` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_job_experience`
--

CREATE TABLE IF NOT EXISTS `cv_job_experience` (
`idjob_experience` int(11) NOT NULL,
  `company_name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `job_position` varchar(45) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_job_users`
--

CREATE TABLE IF NOT EXISTS `cv_job_users` (
`id` int(4) NOT NULL,
  `name_job` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_language`
--

CREATE TABLE IF NOT EXISTS `cv_language` (
  `language_idlanguage` int(11) NOT NULL,
  `percentage` int(11) DEFAULT NULL,
  `cv_idcvitae` int(11) NOT NULL,
  `cv_acl_users_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_language_name`
--

CREATE TABLE IF NOT EXISTS `cv_language_name` (
`idlanguage` int(11) NOT NULL,
  `name` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_tech`
--

CREATE TABLE IF NOT EXISTS `cv_tech` (
  `c_vitae_idcvitae` int(11) NOT NULL,
  `c_vitae_acl_users_id` int(4) NOT NULL,
  `technologies_id_tech` int(11) NOT NULL,
  `expertis` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cv_technologies`
--

CREATE TABLE IF NOT EXISTS `cv_technologies` (
`id_tech` int(11) NOT NULL,
  `name_tech` varchar(200) NOT NULL,
  `techtype_idtechtype` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `minutes_idminutes` int(11) NOT NULL,
  `description` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `data_meeting`
--

CREATE TABLE IF NOT EXISTS `data_meeting` (
`id_meeting` int(5) NOT NULL,
  `m_date` date NOT NULL,
  `m_hour` time NOT NULL,
  `m_description` text,
  `company_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datepaymentsorder`
--

CREATE TABLE IF NOT EXISTS `datepaymentsorder` (
`id_paymentorder` int(11) NOT NULL,
  `datepayment` date NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `amountShow` decimal(10,2) DEFAULT NULL,
  `statusCxp` int(11) DEFAULT '0',
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dates_of_payments`
--

CREATE TABLE IF NOT EXISTS `dates_of_payments` (
`id_datePayment` int(4) NOT NULL,
  `datePayment` date NOT NULL,
  `projectId` int(4) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `statusCxc` int(11) DEFAULT '0',
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fechas de cobro para proyectos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `days`
--

CREATE TABLE IF NOT EXISTS `days` (
  `id_days` int(4) NOT NULL,
  `days_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Para calcular la grafica de asistencias';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `department`
--

CREATE TABLE IF NOT EXISTS `department` (
`id_department` int(11) NOT NULL,
  `d_name` varchar(100) NOT NULL,
  `d_description` text
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `district`
--

CREATE TABLE IF NOT EXISTS `district` (
  `id` int(5) NOT NULL,
  `name` varchar(60) NOT NULL,
  `state_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
`id_document` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents_fields`
--

CREATE TABLE IF NOT EXISTS `documents_fields` (
`id_field` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `label` varchar(500) NOT NULL,
  `type` varchar(500) NOT NULL,
  `position` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents_fields_content`
--

CREATE TABLE IF NOT EXISTS `documents_fields_content` (
`id_content_field` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_out_merchandize`
--

CREATE TABLE IF NOT EXISTS `entry_out_merchandize` (
`id_eop` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `eop_date` date NOT NULL,
  `type_product` int(1) NOT NULL,
  `type_storage` int(1) NOT NULL,
  `id_merchandize` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entry_out_product`
--

CREATE TABLE IF NOT EXISTS `entry_out_product` (
`id_eop` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `eop_date` date NOT NULL,
  `type_product` int(1) NOT NULL,
  `type_storage` int(1) NOT NULL,
  `id_fk_product` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
`idExpenses` int(11) NOT NULL,
  `date_of_expenses` date NOT NULL,
  `amount_of_expenses` decimal(10,2) NOT NULL,
  `description_of_expenses` varchar(400) NOT NULL,
  `reference_of_expenses` int(11) NOT NULL,
  `expenses_type` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `id_fk_user` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ho_mondays`
--

CREATE TABLE IF NOT EXISTS `ho_mondays` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Para la grafica de asistencia';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories`
--

CREATE TABLE IF NOT EXISTS `inventories` (
`id_inventories` int(4) NOT NULL,
  `types_id_types` int(11) NOT NULL,
  `id_acl_users` int(4) NOT NULL,
  `id_department` int(11) NOT NULL,
  `number_inventory` int(4) NOT NULL,
  `object` varchar(45) NOT NULL,
  `amount` int(11) NOT NULL,
  `brand` varchar(45) DEFAULT NULL,
  `serialnumber` varchar(45) DEFAULT NULL,
  `material` varchar(45) DEFAULT NULL,
  `series` varchar(45) DEFAULT NULL,
  `state` varchar(45) NOT NULL,
  `comments` varchar(500) NOT NULL,
  `registered_by` varchar(45) NOT NULL,
  `registration_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Inventarios' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
`ID` int(6) NOT NULL,
  `customer` int(4) DEFAULT NULL,
  `cxc` int(5) DEFAULT NULL,
  `date_invoice` varchar(45) DEFAULT NULL,
  `sale` int(11) DEFAULT NULL,
  `xml_invoice` varchar(10000) NOT NULL,
  `observation` text,
  `status` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Para Facturas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_permission`
--

CREATE TABLE IF NOT EXISTS `iof_permission` (
`id` int(10) unsigned NOT NULL,
  `permission_name` varchar(45) NOT NULL,
  `pathResource` varchar(100) NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  `resourceName` varchar(45) DEFAULT NULL,
  `name_esp` varchar(80) DEFAULT NULL,
  `app` varchar(100) DEFAULT NULL,
  `name_menu` varchar(45) DEFAULT NULL,
  `sub_action` varchar(45) DEFAULT NULL,
  `agroup` varchar(100) DEFAULT NULL,
  `agroupName` varchar(100) DEFAULT NULL,
  `menutemp` varchar(100) DEFAULT NULL,
  `is_displayed_action` int(11) DEFAULT NULL,
  `displayed_order_action` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_resource`
--

CREATE TABLE IF NOT EXISTS `iof_resource` (
`id` int(10) unsigned NOT NULL,
  `resource_name` varchar(50) NOT NULL,
  `app` varchar(100) DEFAULT NULL,
  `agroupName` varchar(100) DEFAULT NULL,
  `menutemp` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nameMenu` varchar(200) NOT NULL,
  `path` varchar(200) NOT NULL,
  `sub` varchar(50) DEFAULT NULL,
  `isdisplayed` tinyint(4) NOT NULL,
  `displayedoreder` int(11) NOT NULL,
  `resource` varchar(64) DEFAULT NULL,
  `agroup` varchar(100) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_role`
--

CREATE TABLE IF NOT EXISTS `iof_role` (
`rid` int(10) unsigned NOT NULL,
  `role_name` varchar(45) NOT NULL,
  `type_user` int(2) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `Active` int(1) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_role_permission`
--

CREATE TABLE IF NOT EXISTS `iof_role_permission` (
`id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `name_role` varchar(50) DEFAULT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `name_permission` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=270 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_users`
--

CREATE TABLE IF NOT EXISTS `iof_users` (
`user_id` int(4) NOT NULL,
  `privateKey` int(10) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `user_type` varchar(7) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `datebirth` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `numberEmployee` varchar(7) DEFAULT NULL,
  `rfc` varchar(35) DEFAULT NULL,
  `phone` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `password_salt` varchar(32) DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isDetailed` tinyint(1) DEFAULT NULL,
  `photofilename` longtext,
  `photofile` longtext,
  `id_job` int(4) DEFAULT NULL,
  `canlogin` int(11) DEFAULT NULL,
  `id_department` int(11) DEFAULT NULL,
  `user_principal` int(11) DEFAULT NULL,
  `avatar` longtext
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_user_role`
--

CREATE TABLE IF NOT EXISTS `iof_user_role` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(4) NOT NULL,
  `role_id` int(10) NOT NULL,
  `role_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_users`
--

CREATE TABLE IF NOT EXISTS `job_users` (
`id` int(4) NOT NULL,
  `name_job` varchar(30) NOT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `keys`
--

CREATE TABLE IF NOT EXISTS `keys` (
`id_key` int(11) NOT NULL,
  `k_name` varchar(80) NOT NULL,
  `k_amount` decimal(10,2) NOT NULL,
  `k_description` varchar(100) DEFAULT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liabilities`
--

CREATE TABLE IF NOT EXISTS `liabilities` (
`ID` int(11) NOT NULL,
  `id_cxp` int(4) DEFAULT NULL,
  `date_payment` varchar(45) NOT NULL,
  `payment` varchar(45) NOT NULL,
  `provider` varchar(45) NOT NULL,
  `type_payment` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pasivos de la empresa' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `link`
--

CREATE TABLE IF NOT EXISTS `link` (
`idlink` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL,
  `description` varchar(45) NOT NULL,
  `date_hour` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acl_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='IOTECA Para recomendar un link' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `made_payments`
--

CREATE TABLE IF NOT EXISTS `made_payments` (
`id_payment` int(10) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `account` int(200) NOT NULL,
  `reference` int(200) NOT NULL,
  `date_payment` date DEFAULT NULL,
  `project_id` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `made_payments_order`
--

CREATE TABLE IF NOT EXISTS `made_payments_order` (
`id_payment` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `account` int(200) NOT NULL,
  `reference` int(200) NOT NULL,
  `date_payment` date NOT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `measuring_unit`
--

CREATE TABLE IF NOT EXISTS `measuring_unit` (
`id_measuring` int(10) NOT NULL,
  `unit` varchar(64) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `merchandize`
--

CREATE TABLE IF NOT EXISTS `merchandize` (
`id_product` int(11) NOT NULL,
  `id_company` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_measuring` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `p_key` varchar(200) NOT NULL,
  `p_name` varchar(100) NOT NULL,
  `p_description` varchar(500) DEFAULT NULL,
  `p_record_date` date NOT NULL,
  `p_photo` longblob,
  `expiration_date` date DEFAULT NULL,
  `review_product` int(11) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `p_price` decimal(10,2) NOT NULL,
  `purchase_percentage` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabla de productos terminados' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `minutes`
--

CREATE TABLE IF NOT EXISTS `minutes` (
`idminutes` int(11) NOT NULL,
  `objective` varchar(150) DEFAULT NULL,
  `time` varchar(45) DEFAULT NULL,
  `hora` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `convened` varchar(45) DEFAULT NULL,
  `timekeeper` varchar(45) DEFAULT NULL,
  `attendees` varchar(45) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `meetingType` varchar(45) DEFAULT NULL,
  `moderator` varchar(45) DEFAULT NULL,
  `annotations` varchar(45) DEFAULT NULL,
  `read` varchar(45) DEFAULT NULL,
  `bring` varchar(45) DEFAULT NULL,
  `agendaItems` varchar(45) DEFAULT NULL,
  `newActionItem` varchar(45) DEFAULT NULL,
  `otherNotes` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='GUTEN Para minutas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `minutesbyuser`
--

CREATE TABLE IF NOT EXISTS `minutesbyuser` (
  `minutes_idminutes` int(11) NOT NULL,
  `acl_users_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `neighborhood`
--

CREATE TABLE IF NOT EXISTS `neighborhood` (
  `id` int(5) NOT NULL,
  `colony` varchar(70) NOT NULL,
  `postal_code` int(6) NOT NULL,
  `district_id` int(5) NOT NULL,
  `state_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Colonias';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `num`
--

CREATE TABLE IF NOT EXISTS `num` (
  `i` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
`id_payment` int(11) NOT NULL,
  `dropdate` varchar(45) DEFAULT NULL,
  `projected_date` varchar(45) DEFAULT NULL,
  `projects_ID` int(4) NOT NULL,
  `facturas_ID` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pay_payroll`
--

CREATE TABLE IF NOT EXISTS `pay_payroll` (
`id_paypayroll` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `type` varchar(15) NOT NULL,
  `keys` varchar(200) DEFAULT NULL,
  `id_user` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preferences`
--

CREATE TABLE IF NOT EXISTS `preferences` (
`id` int(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_products`
--

CREATE TABLE IF NOT EXISTS `price_products` (
`order_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `conditions` varchar(500) DEFAULT NULL,
  `numberofpayments` int(11) NOT NULL,
  `date_log` varchar(45) DEFAULT NULL,
  `account` varchar(200) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `id_company` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_services`
--

CREATE TABLE IF NOT EXISTS `price_services` (
`id_service` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `concept` varchar(200) DEFAULT NULL,
  `unityPrice` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `id_prospect` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE IF NOT EXISTS `products` (
`id_products` int(10) NOT NULL,
  `id_company` int(10) DEFAULT NULL,
  `id_fk_category` int(10) NOT NULL,
  `measuring_fk_id` int(11) NOT NULL,
  `acl_users_fk_id` int(4) NOT NULL,
  `p_name` varchar(45) NOT NULL,
  `p_description` text,
  `p_record_date` date DEFAULT NULL,
  `p_key` varchar(45) DEFAULT NULL,
  `p_photo` longtext,
  `expiration` date DEFAULT NULL,
  `review_product` int(11) NOT NULL,
  `p_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
`ID` int(4) NOT NULL,
  `company_ID` int(5) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `description` text,
  `discount` int(11) DEFAULT NULL,
  `percentage_duration` int(11) DEFAULT NULL,
  `numberofpayments` int(2) DEFAULT NULL,
  `profitpercent` decimal(10,2) DEFAULT NULL,
  `discountpercent` decimal(10,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `advance` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `taxpercent` float DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `treatmenttime` int(3) DEFAULT NULL,
  `costtable` varchar(1000) DEFAULT NULL,
  `type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Un proyecto es trabajo contratado a la empresa' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_contact`
--

CREATE TABLE IF NOT EXISTS `project_contact` (
  `id_project_mt` int(4) NOT NULL,
  `id_contact_mt` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_role`
--

CREATE TABLE IF NOT EXISTS `project_role` (
`id_project_role` int(5) NOT NULL,
  `project_role_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
`id_purchase` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `remaining_quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase2`
--

CREATE TABLE IF NOT EXISTS `purchase2` (
`id_purchase` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `remaining_quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_invoices`
--

CREATE TABLE IF NOT EXISTS `purchase_invoices` (
`id_purchase_invoice` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `remaining_quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_order`
--

CREATE TABLE IF NOT EXISTS `purchase_order` (
`order_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `conditions` varchar(500) DEFAULT NULL,
  `numberofpayments` int(11) NOT NULL,
  `date_log` varchar(45) DEFAULT NULL,
  `account` varchar(200) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `id_company` int(5) NOT NULL,
  `id_project` int(4) DEFAULT NULL,
  `ld_department` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saleproducts`
--

CREATE TABLE IF NOT EXISTS `saleproducts` (
`idSaleProd` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
`id_sale` int(11) NOT NULL,
  `nameClient` varchar(200) DEFAULT NULL,
  `date` date NOT NULL,
  `subtotal` double(10,2) NOT NULL,
  `tax` double(10,2) NOT NULL,
  `total` double(10,2) NOT NULL,
  `cash` double(10,2) DEFAULT NULL,
  `saleitems` varchar(2000) NOT NULL,
  `autorization` int(11) DEFAULT NULL,
  `last4number` int(4) DEFAULT NULL,
  `checkSheet` int(11) DEFAULT NULL,
  `collectionDate` date DEFAULT NULL,
  `idCountClient` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE IF NOT EXISTS `services` (
`id_service` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dateService` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `percentageGain` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shoppingcart`
--

CREATE TABLE IF NOT EXISTS `shoppingcart` (
`id_car` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `states_of_mexico`
--

CREATE TABLE IF NOT EXISTS `states_of_mexico` (
  `id` int(3) NOT NULL,
  `state` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Estados';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `state_of_inventories`
--

CREATE TABLE IF NOT EXISTS `state_of_inventories` (
  `id_state_Inventory` int(4) NOT NULL,
  `conditions` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
`id_stock` int(10) NOT NULL,
  `stock` int(10) NOT NULL,
  `min_stock` int(7) NOT NULL,
  `max_stock` int(7) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `id_fk_products` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_merchandize`
--

CREATE TABLE IF NOT EXISTS `stock_merchandize` (
`id_stock` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `max_stock` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `id_products` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `technologies`
--

CREATE TABLE IF NOT EXISTS `technologies` (
`id_tech` int(11) NOT NULL,
  `name_tech` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `techtype`
--

CREATE TABLE IF NOT EXISTS `techtype` (
`idtechtype` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
`id_theme` int(11) NOT NULL,
  `theme` varchar(45) NOT NULL,
  `selected` tinyint(1) NOT NULL DEFAULT '0',
  `id_user` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_books`
--

CREATE TABLE IF NOT EXISTS `tipo_books` (
  `id_type` int(11) NOT NULL,
  `type_book` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tipos de libros';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_tutorial`
--

CREATE TABLE IF NOT EXISTS `tipo_tutorial` (
`id_tipo` int(11) NOT NULL,
  `name_tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmp`
--

CREATE TABLE IF NOT EXISTS `tmp` (
`id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
`id_topic` int(11) NOT NULL,
  `topic_name` varchar(50) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Guarda los temas de encuestas o documentos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutorial`
--

CREATE TABLE IF NOT EXISTS `tutorial` (
`idtutorial` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `author` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `url` varchar(300) NOT NULL,
  `description` longtext,
  `acl_users_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `date_hour` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `types`
--

CREATE TABLE IF NOT EXISTS `types` (
`id_types` int(11) NOT NULL,
  `description` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tipos de ? ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `update_actions`
--

CREATE TABLE IF NOT EXISTS `update_actions` (
  `id` int(5) NOT NULL,
  `actions` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_addresses`
--

CREATE TABLE IF NOT EXISTS `users_addresses` (
`id_address` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `street` varchar(45) NOT NULL,
  `postalcode` int(5) NOT NULL,
  `number` varchar(10) NOT NULL,
  `interior` varchar(10) DEFAULT NULL,
  `state_id` int(3) NOT NULL,
  `district_id` int(5) NOT NULL,
  `neighborhood` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_projects`
--

CREATE TABLE IF NOT EXISTS `users_projects` (
`users_project_id` int(4) NOT NULL,
  `project_role` varchar(30) DEFAULT NULL,
  `projects_ID` int(4) NOT NULL,
  `acl_users_id` int(4) NOT NULL,
  `user_add_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
`id_details` int(4) NOT NULL,
  `acl_users_id` int(4) NOT NULL,
  `countBanc` int(20) DEFAULT NULL,
  `clabe` varchar(45) DEFAULT NULL,
  `branch` varchar(45) DEFAULT NULL,
  `nameBanc` varchar(45) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `period` int(5) DEFAULT NULL,
  `mannerofpayment` int(5) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `local_phone` varchar(45) DEFAULT NULL,
  `cellphone` varchar(45) DEFAULT NULL,
  `date_admission` date DEFAULT NULL,
  `contract_type` int(2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vision`
--

CREATE TABLE IF NOT EXISTS `vision` (
`idvision` int(11) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  `name_customers` varchar(100) NOT NULL,
  `name_project` varchar(100) NOT NULL,
  `problem` varchar(1000) NOT NULL,
  `affects` varchar(1000) NOT NULL,
  `impact` varchar(1000) NOT NULL,
  `solutions` varchar(1000) NOT NULL,
  `operating_environment` varchar(1000) NOT NULL,
  `user_environment` varchar(1000) NOT NULL,
  `user_profiles` varchar(1000) NOT NULL,
  `customers` varchar(100) NOT NULL,
  `corporate` varchar(100) NOT NULL,
  `distribuitor` varchar(100) NOT NULL,
  `view_project` varchar(1000) NOT NULL,
  `perspective_project` varchar(1000) NOT NULL,
  `login` varchar(500) NOT NULL,
  `activations` varchar(500) NOT NULL,
  `activate_card` varchar(500) NOT NULL,
  `assign_points` varchar(500) NOT NULL,
  `promotion` varchar(500) NOT NULL,
  `comunicator` varchar(500) NOT NULL,
  `activate_card_check` varchar(500) NOT NULL,
  `points_awarded` varchar(500) NOT NULL,
  `points_redeemed` varchar(500) NOT NULL,
  `news` varchar(500) NOT NULL,
  `personal_data` varchar(500) NOT NULL,
  `points_balance` varchar(500) NOT NULL,
  `see_promotions` varchar(500) NOT NULL,
  `the_news` varchar(500) NOT NULL,
  `promotions` varchar(500) NOT NULL,
  `store` varchar(1000) NOT NULL,
  `other_requerimnets` varchar(500) NOT NULL,
  `applicable_standard` varchar(500) NOT NULL,
  `system_requeriments` varchar(500) NOT NULL,
  `performance_requeriments` varchar(500) NOT NULL,
  `environmental_requirements` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='IOTeca Es la vision del proyecto' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `xml_invoices`
--

CREATE TABLE IF NOT EXISTS `xml_invoices` (
`id_xml` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `xml` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `account`
--
ALTER TABLE `account`
 ADD PRIMARY KEY (`id_account`);

--
-- Indices de la tabla `account_category`
--
ALTER TABLE `account_category`
 ADD PRIMARY KEY (`id_category`);

--
-- Indices de la tabla `account_current_balance`
--
ALTER TABLE `account_current_balance`
 ADD PRIMARY KEY (`id_current`);

--
-- Indices de la tabla `account_tx`
--
ALTER TABLE `account_tx`
 ADD PRIMARY KEY (`id_tx`);

--
-- Indices de la tabla `acc_receivable`
--
ALTER TABLE `acc_receivable`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `activities`
--
ALTER TABLE `activities`
 ADD PRIMARY KEY (`id_activities`), ADD KEY `proyectos` (`projects_ID`), ADD KEY `user_activities` (`user_id`);

--
-- Indices de la tabla `activitydates`
--
ALTER TABLE `activitydates`
 ADD PRIMARY KEY (`id_dates_activity`), ADD KEY `fk_activityDates_1_idx` (`id_fk_days`), ADD KEY `fk_activityDates_activities1_idx` (`id_fk_activities`);

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
 ADD PRIMARY KEY (`id_address`), ADD KEY `fk_company` (`company_id`);

--
-- Indices de la tabla `articles_of_inventories`
--
ALTER TABLE `articles_of_inventories`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_articles_of_inventories_types1` (`id_types`);

--
-- Indices de la tabla `bank`
--
ALTER TABLE `bank`
 ADD PRIMARY KEY (`id_bank`);

--
-- Indices de la tabla `banks`
--
ALTER TABLE `banks`
 ADD PRIMARY KEY (`id_bank`);

--
-- Indices de la tabla `banks_tx`
--
ALTER TABLE `banks_tx`
 ADD PRIMARY KEY (`id_tx`);

--
-- Indices de la tabla `bank_current_balance`
--
ALTER TABLE `bank_current_balance`
 ADD PRIMARY KEY (`id_current`);

--
-- Indices de la tabla `books`
--
ALTER TABLE `books`
 ADD PRIMARY KEY (`idbooks`), ADD KEY `fk_books_tipo_books1_idx` (`tipo_books`);

--
-- Indices de la tabla `cash`
--
ALTER TABLE `cash`
 ADD PRIMARY KEY (`id_cash`);

--
-- Indices de la tabla `cash_current_balance`
--
ALTER TABLE `cash_current_balance`
 ADD PRIMARY KEY (`id_current`);

--
-- Indices de la tabla `cash_tx`
--
ALTER TABLE `cash_tx`
 ADD PRIMARY KEY (`id_tx`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id_category`);

--
-- Indices de la tabla `clock`
--
ALTER TABLE `clock`
 ADD PRIMARY KEY (`acl_users_id`,`date`,`time`,`clockcol`), ADD KEY `fk_clock_acl_users1` (`acl_users_id`);

--
-- Indices de la tabla `company`
--
ALTER TABLE `company`
 ADD PRIMARY KEY (`id_company`), ADD KEY `fk_company_update_actions1` (`id_update_actions`);

--
-- Indices de la tabla `company_contact`
--
ALTER TABLE `company_contact`
 ADD PRIMARY KEY (`id_contact`), ADD KEY `fk_company_contact_1_idx` (`company_id`);

--
-- Indices de la tabla `components`
--
ALTER TABLE `components`
 ADD PRIMARY KEY (`component_id`), ADD KEY `fk_components_acl_users1` (`acl_users_id`);

--
-- Indices de la tabla `contents`
--
ALTER TABLE `contents`
 ADD PRIMARY KEY (`id_content`), ADD KEY `id_user` (`id_user`), ADD KEY `id_thematic` (`id_topic`);

--
-- Indices de la tabla `contract_types`
--
ALTER TABLE `contract_types`
 ADD PRIMARY KEY (`id_contract`);

--
-- Indices de la tabla `cron_token`
--
ALTER TABLE `cron_token`
 ADD PRIMARY KEY (`token_key`);

--
-- Indices de la tabla `curses`
--
ALTER TABLE `curses`
 ADD PRIMARY KEY (`idcurses`);

--
-- Indices de la tabla `cv`
--
ALTER TABLE `cv`
 ADD PRIMARY KEY (`idcvitae`,`acl_users_id`), ADD KEY `fk_c_vitae_acl_users1_idx` (`idcvitae`,`acl_users_id`);

--
-- Indices de la tabla `cv_curses`
--
ALTER TABLE `cv_curses`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cv_curses_employee`
--
ALTER TABLE `cv_curses_employee`
 ADD KEY `fk_cv_curses_employee_c_vitae1_idx` (`c_vitae_idcvitae`,`c_vitae_acl_users_id`), ADD KEY `fk_cv_curses_employee_cv_curses1_idxx` (`cv_curses_id`);

--
-- Indices de la tabla `cv_jobexperience_tech`
--
ALTER TABLE `cv_jobexperience_tech`
 ADD KEY `fk_jobexperience_tech_technologies1_idx` (`technologies_id_tech`), ADD KEY `fk_jobexperience_tech_job_experience1_idx` (`job_experience_idjob_experience`);

--
-- Indices de la tabla `cv_job_experience`
--
ALTER TABLE `cv_job_experience`
 ADD PRIMARY KEY (`idjob_experience`);

--
-- Indices de la tabla `cv_job_users`
--
ALTER TABLE `cv_job_users`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cv_language`
--
ALTER TABLE `cv_language`
 ADD KEY `fk_cv_language_language1_idx` (`language_idlanguage`), ADD KEY `fk_cv_language_cv1_idx` (`cv_idcvitae`,`cv_acl_users_id`);

--
-- Indices de la tabla `cv_language_name`
--
ALTER TABLE `cv_language_name`
 ADD PRIMARY KEY (`idlanguage`);

--
-- Indices de la tabla `cv_tech`
--
ALTER TABLE `cv_tech`
 ADD KEY `fk_cv_tech_c_vitae1_idx` (`c_vitae_idcvitae`,`c_vitae_acl_users_id`), ADD KEY `fk_cv_tech_technologies1_idx` (`technologies_id_tech`);

--
-- Indices de la tabla `cv_technologies`
--
ALTER TABLE `cv_technologies`
 ADD PRIMARY KEY (`id_tech`), ADD KEY `fk_technologies_techtype1_idx` (`techtype_idtechtype`);

--
-- Indices de la tabla `data`
--
ALTER TABLE `data`
 ADD PRIMARY KEY (`minutes_idminutes`);

--
-- Indices de la tabla `data_meeting`
--
ALTER TABLE `data_meeting`
 ADD PRIMARY KEY (`id_meeting`), ADD KEY `fk_company_id_data_metting_idx` (`company_id`);

--
-- Indices de la tabla `datepaymentsorder`
--
ALTER TABLE `datepaymentsorder`
 ADD PRIMARY KEY (`id_paymentorder`), ADD KEY `fk_datepaymentsorder_purchase_order1_idx` (`order_id`);

--
-- Indices de la tabla `dates_of_payments`
--
ALTER TABLE `dates_of_payments`
 ADD PRIMARY KEY (`id_datePayment`), ADD KEY `fk_dates_of_payments_projects1` (`projectId`);

--
-- Indices de la tabla `days`
--
ALTER TABLE `days`
 ADD PRIMARY KEY (`id_days`), ADD UNIQUE KEY `id_days` (`id_days`);

--
-- Indices de la tabla `department`
--
ALTER TABLE `department`
 ADD PRIMARY KEY (`id_department`);

--
-- Indices de la tabla `district`
--
ALTER TABLE `district`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `fk_district_states_of_mexico1` (`state_id`), ADD FULLTEXT KEY `name` (`name`);

--
-- Indices de la tabla `documents`
--
ALTER TABLE `documents`
 ADD PRIMARY KEY (`id_document`);

--
-- Indices de la tabla `documents_fields`
--
ALTER TABLE `documents_fields`
 ADD PRIMARY KEY (`id_field`), ADD KEY `fk_company_id_field_document_idx` (`id_document`);

--
-- Indices de la tabla `documents_fields_content`
--
ALTER TABLE `documents_fields_content`
 ADD PRIMARY KEY (`id_content_field`), ADD KEY `fk_id_content_field_field_document_idx` (`id_document`);

--
-- Indices de la tabla `entry_out_merchandize`
--
ALTER TABLE `entry_out_merchandize`
 ADD PRIMARY KEY (`id_eop`), ADD KEY `fk_product_entry_out_product_eop` (`id_merchandize`);

--
-- Indices de la tabla `entry_out_product`
--
ALTER TABLE `entry_out_product`
 ADD PRIMARY KEY (`id_eop`), ADD KEY `fk_product_entry_out_product_eop` (`id_fk_product`);

--
-- Indices de la tabla `expenses`
--
ALTER TABLE `expenses`
 ADD PRIMARY KEY (`idExpenses`), ADD KEY `fk_Expenses_acl_users1_idx` (`id_fk_user`);

--
-- Indices de la tabla `ho_mondays`
--
ALTER TABLE `ho_mondays`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventories`
--
ALTER TABLE `inventories`
 ADD PRIMARY KEY (`id_inventories`), ADD KEY `fk_inventories_acl_users1_idx` (`id_acl_users`), ADD KEY `fk_inventories_types2_idx` (`types_id_types`), ADD KEY `fk_inventories_department1_idx` (`id_department`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
 ADD PRIMARY KEY (`ID`), ADD KEY `fk_invoicesSale` (`customer`);

--
-- Indices de la tabla `iof_permission`
--
ALTER TABLE `iof_permission`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `iof_resource`
--
ALTER TABLE `iof_resource`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `iof_role`
--
ALTER TABLE `iof_role`
 ADD PRIMARY KEY (`rid`);

--
-- Indices de la tabla `iof_role_permission`
--
ALTER TABLE `iof_role_permission`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `iof_users`
--
ALTER TABLE `iof_users`
 ADD PRIMARY KEY (`user_id`), ADD KEY `id_company` (`id_company`);

--
-- Indices de la tabla `iof_user_role`
--
ALTER TABLE `iof_user_role`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `job_users`
--
ALTER TABLE `job_users`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `keys`
--
ALTER TABLE `keys`
 ADD PRIMARY KEY (`id_key`);

--
-- Indices de la tabla `liabilities`
--
ALTER TABLE `liabilities`
 ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `link`
--
ALTER TABLE `link`
 ADD PRIMARY KEY (`idlink`);

--
-- Indices de la tabla `made_payments`
--
ALTER TABLE `made_payments`
 ADD PRIMARY KEY (`id_payment`), ADD KEY `made_payment_to_projects` (`project_id`);

--
-- Indices de la tabla `made_payments_order`
--
ALTER TABLE `made_payments_order`
 ADD PRIMARY KEY (`id_payment`), ADD KEY `fk_made_payments_order_purchase_order1_idx` (`order_id`);

--
-- Indices de la tabla `measuring_unit`
--
ALTER TABLE `measuring_unit`
 ADD PRIMARY KEY (`id_measuring`);

--
-- Indices de la tabla `merchandize`
--
ALTER TABLE `merchandize`
 ADD PRIMARY KEY (`id_product`), ADD KEY `fk_products_category2_idx` (`id_category`), ADD KEY `fk_products_acl_users2_idx` (`id_user`), ADD KEY `fk_products_acl_unit3` (`id_measuring`), ADD KEY `fk_supplier_id_supplier2` (`id_company`);

--
-- Indices de la tabla `minutes`
--
ALTER TABLE `minutes`
 ADD PRIMARY KEY (`idminutes`);

--
-- Indices de la tabla `minutesbyuser`
--
ALTER TABLE `minutesbyuser`
 ADD PRIMARY KEY (`acl_users_id`,`minutes_idminutes`), ADD KEY `fk_minutesbyuser_minutes1` (`minutes_idminutes`), ADD KEY `fk_minutesbyuser_acl_users1` (`acl_users_id`);

--
-- Indices de la tabla `neighborhood`
--
ALTER TABLE `neighborhood`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `fk_neighborhood_district1` (`district_id`), ADD KEY `fk_neighborhood_states_of_mexico1` (`state_id`);

--
-- Indices de la tabla `num`
--
ALTER TABLE `num`
 ADD PRIMARY KEY (`i`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
 ADD PRIMARY KEY (`id_payment`), ADD KEY `fk_payments_projects1` (`projects_ID`), ADD KEY `fk_payments_invoices1` (`facturas_ID`);

--
-- Indices de la tabla `pay_payroll`
--
ALTER TABLE `pay_payroll`
 ADD PRIMARY KEY (`id_paypayroll`,`id_user`), ADD KEY `fk_pay_payroll_acl_users1_idx` (`id_user`);

--
-- Indices de la tabla `preferences`
--
ALTER TABLE `preferences`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `price_products`
--
ALTER TABLE `price_products`
 ADD PRIMARY KEY (`order_id`), ADD KEY `fk_price_products_company1` (`id_company`);

--
-- Indices de la tabla `price_services`
--
ALTER TABLE `price_services`
 ADD PRIMARY KEY (`id_service`), ADD KEY `fk_price_services_order1` (`id_prospect`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
 ADD PRIMARY KEY (`id_products`), ADD KEY `fk_products_category1_idx` (`id_fk_category`), ADD KEY `fk_products_acl_users1_idx` (`acl_users_fk_id`), ADD KEY `fk_products_acl_unit2` (`measuring_fk_id`), ADD KEY `fk_supplier_id_supplier` (`id_company`);

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
 ADD PRIMARY KEY (`ID`), ADD KEY `projects_to_company` (`company_ID`);

--
-- Indices de la tabla `project_contact`
--
ALTER TABLE `project_contact`
 ADD PRIMARY KEY (`id_project_mt`,`id_contact_mt`), ADD KEY `fk_project_contact_1_idx` (`id_project_mt`), ADD KEY `fk_project_contact_2_idx` (`id_contact_mt`);

--
-- Indices de la tabla `project_role`
--
ALTER TABLE `project_role`
 ADD PRIMARY KEY (`id_project_role`);

--
-- Indices de la tabla `purchase`
--
ALTER TABLE `purchase`
 ADD PRIMARY KEY (`id_purchase`), ADD KEY `fk_purchase_purchase_order1_idx` (`order_id`), ADD KEY `fk_purchase_products1_idx` (`name`);

--
-- Indices de la tabla `purchase2`
--
ALTER TABLE `purchase2`
 ADD PRIMARY KEY (`id_purchase`), ADD KEY `fk_purchase_price_order1_idx` (`order_id`), ADD KEY `fk_purchase_price_products1_idx` (`name`);

--
-- Indices de la tabla `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
 ADD PRIMARY KEY (`id_purchase_invoice`), ADD KEY `fk_purchase_purchase_order32_idx` (`invoice_id`), ADD KEY `fk_purchase_products32_idx` (`name`);

--
-- Indices de la tabla `purchase_order`
--
ALTER TABLE `purchase_order`
 ADD PRIMARY KEY (`order_id`), ADD KEY `fk_purchase_order_company1` (`id_company`), ADD KEY `fk_purchase_order_projects1` (`id_project`), ADD KEY `index_purchase_order_department1` (`ld_department`);

--
-- Indices de la tabla `saleproducts`
--
ALTER TABLE `saleproducts`
 ADD PRIMARY KEY (`idSaleProd`), ADD KEY `fk_sale_id_sale_id_fk_sale` (`id_sale`), ADD KEY `fk_sale_id_product_id_fk_product` (`id_product`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
 ADD PRIMARY KEY (`id_sale`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
 ADD PRIMARY KEY (`id_service`);

--
-- Indices de la tabla `shoppingcart`
--
ALTER TABLE `shoppingcart`
 ADD PRIMARY KEY (`id_car`), ADD KEY `fk_sale_id_sale_uder_id` (`id_user`), ADD KEY `fk_sale_id_product_id_fk_product21` (`id_product`);

--
-- Indices de la tabla `states_of_mexico`
--
ALTER TABLE `states_of_mexico`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `state_of_inventories`
--
ALTER TABLE `state_of_inventories`
 ADD PRIMARY KEY (`id_state_Inventory`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
 ADD PRIMARY KEY (`id_stock`), ADD KEY `fk_stock_products1_idx` (`id_fk_products`);

--
-- Indices de la tabla `stock_merchandize`
--
ALTER TABLE `stock_merchandize`
 ADD PRIMARY KEY (`id_stock`), ADD KEY `fk_products_id_stockx` (`id_products`);

--
-- Indices de la tabla `technologies`
--
ALTER TABLE `technologies`
 ADD PRIMARY KEY (`id_tech`);

--
-- Indices de la tabla `techtype`
--
ALTER TABLE `techtype`
 ADD PRIMARY KEY (`idtechtype`);

--
-- Indices de la tabla `themes`
--
ALTER TABLE `themes`
 ADD PRIMARY KEY (`id_theme`,`id_user`), ADD KEY `fk_themes_acl_users1_idx` (`id_user`);

--
-- Indices de la tabla `tipo_books`
--
ALTER TABLE `tipo_books`
 ADD PRIMARY KEY (`id_type`);

--
-- Indices de la tabla `tipo_tutorial`
--
ALTER TABLE `tipo_tutorial`
 ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tmp`
--
ALTER TABLE `tmp`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `topic`
--
ALTER TABLE `topic`
 ADD PRIMARY KEY (`id_topic`), ADD KEY `id_user` (`id_user`), ADD KEY `parent` (`parent`);

--
-- Indices de la tabla `tutorial`
--
ALTER TABLE `tutorial`
 ADD PRIMARY KEY (`idtutorial`), ADD KEY `fk_tutorial_tipo_tutorial1_idx` (`tipo_id`);

--
-- Indices de la tabla `types`
--
ALTER TABLE `types`
 ADD PRIMARY KEY (`id_types`);

--
-- Indices de la tabla `update_actions`
--
ALTER TABLE `update_actions`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users_addresses`
--
ALTER TABLE `users_addresses`
 ADD PRIMARY KEY (`id_address`), ADD KEY `fk_users` (`user_id`), ADD KEY `fk_users_addresses_states_of_mexico1` (`state_id`), ADD KEY `fk_users_addresses_district1` (`district_id`), ADD KEY `fk_users_addresses_neighborhood1` (`neighborhood`);

--
-- Indices de la tabla `users_projects`
--
ALTER TABLE `users_projects`
 ADD PRIMARY KEY (`users_project_id`), ADD KEY `to_project` (`projects_ID`), ADD KEY `to_users` (`acl_users_id`);

--
-- Indices de la tabla `user_details`
--
ALTER TABLE `user_details`
 ADD PRIMARY KEY (`id_details`), ADD KEY `fk_details_to_acl_users` (`acl_users_id`), ADD KEY `fk_user_details_contract_types1` (`contract_type`);

--
-- Indices de la tabla `vision`
--
ALTER TABLE `vision`
 ADD PRIMARY KEY (`idvision`);

--
-- Indices de la tabla `xml_invoices`
--
ALTER TABLE `xml_invoices`
 ADD PRIMARY KEY (`id_xml`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `account_current_balance`
--
ALTER TABLE `account_current_balance`
MODIFY `id_current` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `account_tx`
--
ALTER TABLE `account_tx`
MODIFY `id_tx` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `acc_receivable`
--
ALTER TABLE `acc_receivable`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Es la tabla para CxC';
--
-- AUTO_INCREMENT de la tabla `activities`
--
ALTER TABLE `activities`
MODIFY `id_activities` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `activitydates`
--
ALTER TABLE `activitydates`
MODIFY `id_dates_activity` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
MODIFY `id_address` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `articles_of_inventories`
--
ALTER TABLE `articles_of_inventories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `bank`
--
ALTER TABLE `bank`
MODIFY `id_bank` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `banks`
--
ALTER TABLE `banks`
MODIFY `id_bank` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `banks_tx`
--
ALTER TABLE `banks_tx`
MODIFY `id_tx` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `bank_current_balance`
--
ALTER TABLE `bank_current_balance`
MODIFY `id_current` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `books`
--
ALTER TABLE `books`
MODIFY `idbooks` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cash`
--
ALTER TABLE `cash`
MODIFY `id_cash` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cash_current_balance`
--
ALTER TABLE `cash_current_balance`
MODIFY `id_current` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cash_tx`
--
ALTER TABLE `cash_tx`
MODIFY `id_tx` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
MODIFY `id_category` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `company`
--
ALTER TABLE `company`
MODIFY `id_company` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `company_contact`
--
ALTER TABLE `company_contact`
MODIFY `id_contact` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `components`
--
ALTER TABLE `components`
MODIFY `component_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `contents`
--
ALTER TABLE `contents`
MODIFY `id_content` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `curses`
--
ALTER TABLE `curses`
MODIFY `idcurses` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cv`
--
ALTER TABLE `cv`
MODIFY `idcvitae` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cv_curses`
--
ALTER TABLE `cv_curses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cv_job_experience`
--
ALTER TABLE `cv_job_experience`
MODIFY `idjob_experience` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cv_job_users`
--
ALTER TABLE `cv_job_users`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cv_language_name`
--
ALTER TABLE `cv_language_name`
MODIFY `idlanguage` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cv_technologies`
--
ALTER TABLE `cv_technologies`
MODIFY `id_tech` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `data_meeting`
--
ALTER TABLE `data_meeting`
MODIFY `id_meeting` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `datepaymentsorder`
--
ALTER TABLE `datepaymentsorder`
MODIFY `id_paymentorder` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `dates_of_payments`
--
ALTER TABLE `dates_of_payments`
MODIFY `id_datePayment` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `department`
--
ALTER TABLE `department`
MODIFY `id_department` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `documents`
--
ALTER TABLE `documents`
MODIFY `id_document` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `documents_fields`
--
ALTER TABLE `documents_fields`
MODIFY `id_field` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `documents_fields_content`
--
ALTER TABLE `documents_fields_content`
MODIFY `id_content_field` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `entry_out_merchandize`
--
ALTER TABLE `entry_out_merchandize`
MODIFY `id_eop` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `entry_out_product`
--
ALTER TABLE `entry_out_product`
MODIFY `id_eop` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `expenses`
--
ALTER TABLE `expenses`
MODIFY `idExpenses` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `inventories`
--
ALTER TABLE `inventories`
MODIFY `id_inventories` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `iof_permission`
--
ALTER TABLE `iof_permission`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `iof_resource`
--
ALTER TABLE `iof_resource`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `iof_role`
--
ALTER TABLE `iof_role`
MODIFY `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `iof_role_permission`
--
ALTER TABLE `iof_role_permission`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=270;
--
-- AUTO_INCREMENT de la tabla `iof_users`
--
ALTER TABLE `iof_users`
MODIFY `user_id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `iof_user_role`
--
ALTER TABLE `iof_user_role`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `job_users`
--
ALTER TABLE `job_users`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `keys`
--
ALTER TABLE `keys`
MODIFY `id_key` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `liabilities`
--
ALTER TABLE `liabilities`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `link`
--
ALTER TABLE `link`
MODIFY `idlink` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `made_payments`
--
ALTER TABLE `made_payments`
MODIFY `id_payment` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `made_payments_order`
--
ALTER TABLE `made_payments_order`
MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `measuring_unit`
--
ALTER TABLE `measuring_unit`
MODIFY `id_measuring` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `merchandize`
--
ALTER TABLE `merchandize`
MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `minutes`
--
ALTER TABLE `minutes`
MODIFY `idminutes` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pay_payroll`
--
ALTER TABLE `pay_payroll`
MODIFY `id_paypayroll` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `preferences`
--
ALTER TABLE `preferences`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `price_products`
--
ALTER TABLE `price_products`
MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `price_services`
--
ALTER TABLE `price_services`
MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
MODIFY `id_products` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `projects`
--
ALTER TABLE `projects`
MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `project_role`
--
ALTER TABLE `project_role`
MODIFY `id_project_role` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `purchase`
--
ALTER TABLE `purchase`
MODIFY `id_purchase` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `purchase2`
--
ALTER TABLE `purchase2`
MODIFY `id_purchase` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
MODIFY `id_purchase_invoice` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `purchase_order`
--
ALTER TABLE `purchase_order`
MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `saleproducts`
--
ALTER TABLE `saleproducts`
MODIFY `idSaleProd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
MODIFY `id_sale` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `shoppingcart`
--
ALTER TABLE `shoppingcart`
MODIFY `id_car` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
MODIFY `id_stock` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `stock_merchandize`
--
ALTER TABLE `stock_merchandize`
MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `technologies`
--
ALTER TABLE `technologies`
MODIFY `id_tech` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `techtype`
--
ALTER TABLE `techtype`
MODIFY `idtechtype` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `themes`
--
ALTER TABLE `themes`
MODIFY `id_theme` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tipo_tutorial`
--
ALTER TABLE `tipo_tutorial`
MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tmp`
--
ALTER TABLE `tmp`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `topic`
--
ALTER TABLE `topic`
MODIFY `id_topic` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tutorial`
--
ALTER TABLE `tutorial`
MODIFY `idtutorial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `types`
--
ALTER TABLE `types`
MODIFY `id_types` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users_addresses`
--
ALTER TABLE `users_addresses`
MODIFY `id_address` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users_projects`
--
ALTER TABLE `users_projects`
MODIFY `users_project_id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `user_details`
--
ALTER TABLE `user_details`
MODIFY `id_details` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `vision`
--
ALTER TABLE `vision`
MODIFY `idvision` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `xml_invoices`
--
ALTER TABLE `xml_invoices`
MODIFY `id_xml` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activities`
--
ALTER TABLE `activities`
ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`projects_ID`) REFERENCES `projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `iof_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `activitydates`
--
ALTER TABLE `activitydates`
ADD CONSTRAINT `fk_activityDates_1` FOREIGN KEY (`id_fk_days`) REFERENCES `days` (`id_days`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_activityDates_activities1` FOREIGN KEY (`id_fk_activities`) REFERENCES `activities` (`id_activities`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `addresses`
--
ALTER TABLE `addresses`
ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `articles_of_inventories`
--
ALTER TABLE `articles_of_inventories`
ADD CONSTRAINT `fk_articles_of_inventories_types1` FOREIGN KEY (`id_types`) REFERENCES `types` (`id_types`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `books`
--
ALTER TABLE `books`
ADD CONSTRAINT `fk_books_tipo_books1` FOREIGN KEY (`tipo_books`) REFERENCES `tipo_books` (`id_type`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `clock`
--
ALTER TABLE `clock`
ADD CONSTRAINT `fk_clock_acl_users1` FOREIGN KEY (`acl_users_id`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `company`
--
ALTER TABLE `company`
ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`id_update_actions`) REFERENCES `update_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `company_contact`
--
ALTER TABLE `company_contact`
ADD CONSTRAINT `fk_company_contact_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id_company`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `components`
--
ALTER TABLE `components`
ADD CONSTRAINT `fk_components_acl_users1` FOREIGN KEY (`acl_users_id`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cv_curses_employee`
--
ALTER TABLE `cv_curses_employee`
ADD CONSTRAINT `fk_cv_curses_employee_c_vitae1` FOREIGN KEY (`c_vitae_idcvitae`, `c_vitae_acl_users_id`) REFERENCES `cv` (`idcvitae`, `acl_users_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_cv_curses_employee_cv_curses1` FOREIGN KEY (`cv_curses_id`) REFERENCES `cv_curses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cv_jobexperience_tech`
--
ALTER TABLE `cv_jobexperience_tech`
ADD CONSTRAINT `fk_jobexperience_tech_job_experience1` FOREIGN KEY (`job_experience_idjob_experience`) REFERENCES `cv_job_experience` (`idjob_experience`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_jobexperience_tech_technologies1` FOREIGN KEY (`technologies_id_tech`) REFERENCES `cv_technologies` (`id_tech`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cv_language`
--
ALTER TABLE `cv_language`
ADD CONSTRAINT `fk_cv_language_cv1` FOREIGN KEY (`cv_idcvitae`, `cv_acl_users_id`) REFERENCES `cv` (`idcvitae`, `acl_users_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_cv_language_language1` FOREIGN KEY (`language_idlanguage`) REFERENCES `cv_language_name` (`idlanguage`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cv_tech`
--
ALTER TABLE `cv_tech`
ADD CONSTRAINT `fk_cv_tech_c_vitae1` FOREIGN KEY (`c_vitae_idcvitae`, `c_vitae_acl_users_id`) REFERENCES `cv` (`idcvitae`, `acl_users_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_cv_tech_technologies1` FOREIGN KEY (`technologies_id_tech`) REFERENCES `cv_technologies` (`id_tech`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `cv_technologies`
--
ALTER TABLE `cv_technologies`
ADD CONSTRAINT `fk_technologies_techtype1` FOREIGN KEY (`techtype_idtechtype`) REFERENCES `techtype` (`idtechtype`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `data_meeting`
--
ALTER TABLE `data_meeting`
ADD CONSTRAINT `fk_company_id_data_metting1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id_company`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `datepaymentsorder`
--
ALTER TABLE `datepaymentsorder`
ADD CONSTRAINT `fk_datepaymentsorder_purchase_order1` FOREIGN KEY (`order_id`) REFERENCES `purchase_order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dates_of_payments`
--
ALTER TABLE `dates_of_payments`
ADD CONSTRAINT `fk_dates_of_payments_projects1` FOREIGN KEY (`projectId`) REFERENCES `projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `district`
--
ALTER TABLE `district`
ADD CONSTRAINT `fk_district_states_of_mexico1` FOREIGN KEY (`state_id`) REFERENCES `states_of_mexico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `documents_fields`
--
ALTER TABLE `documents_fields`
ADD CONSTRAINT `fk_company_id_document_id_document` FOREIGN KEY (`id_document`) REFERENCES `documents` (`id_document`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `documents_fields_content`
--
ALTER TABLE `documents_fields_content`
ADD CONSTRAINT `fk_id_content_field_document_id_document` FOREIGN KEY (`id_document`) REFERENCES `documents` (`id_document`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entry_out_merchandize`
--
ALTER TABLE `entry_out_merchandize`
ADD CONSTRAINT `fk_products_id_products_id_merchandizet` FOREIGN KEY (`id_merchandize`) REFERENCES `merchandize` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entry_out_product`
--
ALTER TABLE `entry_out_product`
ADD CONSTRAINT `fk_products_id_products_id_fk_product` FOREIGN KEY (`id_fk_product`) REFERENCES `products` (`id_products`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `expenses`
--
ALTER TABLE `expenses`
ADD CONSTRAINT `fk_Expenses_acl_users1` FOREIGN KEY (`id_fk_user`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inventories`
--
ALTER TABLE `inventories`
ADD CONSTRAINT `fk_inventories_acl_users1` FOREIGN KEY (`id_acl_users`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_inventories_department1` FOREIGN KEY (`id_department`) REFERENCES `department` (`id_department`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_inventories_types2` FOREIGN KEY (`types_id_types`) REFERENCES `types` (`id_types`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `iof_users`
--
ALTER TABLE `iof_users`
ADD CONSTRAINT `iof_users_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `made_payments`
--
ALTER TABLE `made_payments`
ADD CONSTRAINT `made_payment_to_projects` FOREIGN KEY (`project_id`) REFERENCES `projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `made_payments_order`
--
ALTER TABLE `made_payments_order`
ADD CONSTRAINT `fk_made_payments_order_purchase_order1` FOREIGN KEY (`order_id`) REFERENCES `purchase_order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `merchandize`
--
ALTER TABLE `merchandize`
ADD CONSTRAINT `fk_products_acl_unitx` FOREIGN KEY (`id_measuring`) REFERENCES `measuring_unit` (`id_measuring`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_products_acl_usersx` FOREIGN KEY (`id_user`) REFERENCES `iof_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_products_categoryx` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_supplier_id_supplierx` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `minutesbyuser`
--
ALTER TABLE `minutesbyuser`
ADD CONSTRAINT `fk_minutesbyuser_acl_users1` FOREIGN KEY (`acl_users_id`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_minutesbyuser_minutes1` FOREIGN KEY (`minutes_idminutes`) REFERENCES `minutes` (`idminutes`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `neighborhood`
--
ALTER TABLE `neighborhood`
ADD CONSTRAINT `fk_neighborhood_district1` FOREIGN KEY (`district_id`) REFERENCES `district` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_neighborhood_states_of_mexico1` FOREIGN KEY (`state_id`) REFERENCES `states_of_mexico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
ADD CONSTRAINT `fk_payments_invoices1` FOREIGN KEY (`facturas_ID`) REFERENCES `invoices` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_payments_projects1` FOREIGN KEY (`projects_ID`) REFERENCES `projects` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pay_payroll`
--
ALTER TABLE `pay_payroll`
ADD CONSTRAINT `fk_pay_payroll_acl_users1` FOREIGN KEY (`id_user`) REFERENCES `iof_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `price_products`
--
ALTER TABLE `price_products`
ADD CONSTRAINT `fk_price_products_company1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `price_services`
--
ALTER TABLE `price_services`
ADD CONSTRAINT `fk_price_services_order1` FOREIGN KEY (`id_prospect`) REFERENCES `projects` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
ADD CONSTRAINT `fk_products_acl_unit2` FOREIGN KEY (`measuring_fk_id`) REFERENCES `measuring_unit` (`id_measuring`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_products_acl_users1` FOREIGN KEY (`acl_users_fk_id`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_products_category1` FOREIGN KEY (`id_fk_category`) REFERENCES `category` (`id_category`) ON DELETE NO ACTION ON UPDATE CASCADE,
ADD CONSTRAINT `fk_supplier_id_supplier` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`company_ID`) REFERENCES `company` (`id_company`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `project_contact`
--
ALTER TABLE `project_contact`
ADD CONSTRAINT `fk_contact_project_1` FOREIGN KEY (`id_contact_mt`) REFERENCES `company_contact` (`id_contact`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_project_contact_1` FOREIGN KEY (`id_project_mt`) REFERENCES `projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `purchase`
--
ALTER TABLE `purchase`
ADD CONSTRAINT `fk_purchase_products1` FOREIGN KEY (`name`) REFERENCES `products` (`id_products`),
ADD CONSTRAINT `fk_purchase_purchase_order1` FOREIGN KEY (`order_id`) REFERENCES `purchase_order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `purchase2`
--
ALTER TABLE `purchase2`
ADD CONSTRAINT `fk_purchase_price_order1` FOREIGN KEY (`order_id`) REFERENCES `price_products` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_purchase_price_products1` FOREIGN KEY (`name`) REFERENCES `merchandize` (`id_product`);

--
-- Filtros para la tabla `purchase_invoices`
--
ALTER TABLE `purchase_invoices`
ADD CONSTRAINT `fk_purchase_purchase_order321` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `purchase_order`
--
ALTER TABLE `purchase_order`
ADD CONSTRAINT `fk_purchase_order_company1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_purchase_order_departments12` FOREIGN KEY (`ld_department`) REFERENCES `department` (`id_department`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_purchase_order_projects1` FOREIGN KEY (`id_project`) REFERENCES `projects` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `saleproducts`
--
ALTER TABLE `saleproducts`
ADD CONSTRAINT `fk_sale_id_product_id_fk_product` FOREIGN KEY (`id_product`) REFERENCES `merchandize` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_sale_id_sale_id_fk_sale` FOREIGN KEY (`id_sale`) REFERENCES `sales` (`id_sale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `shoppingcart`
--
ALTER TABLE `shoppingcart`
ADD CONSTRAINT `fk_sale_id_product_id_fk_product21` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_products`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_sale_id_sale_uder_id` FOREIGN KEY (`id_user`) REFERENCES `iof_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
ADD CONSTRAINT `fk_stock_products1` FOREIGN KEY (`id_fk_products`) REFERENCES `products` (`id_products`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `stock_merchandize`
--
ALTER TABLE `stock_merchandize`
ADD CONSTRAINT `fk_products_id_stockx` FOREIGN KEY (`id_products`) REFERENCES `merchandize` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `themes`
--
ALTER TABLE `themes`
ADD CONSTRAINT `fk_themes_acl_users1` FOREIGN KEY (`id_user`) REFERENCES `iof_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tutorial`
--
ALTER TABLE `tutorial`
ADD CONSTRAINT `fk_tutorial_tipo_tutorial1` FOREIGN KEY (`tipo_id`) REFERENCES `tipo_tutorial` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users_projects`
--
ALTER TABLE `users_projects`
ADD CONSTRAINT `users_projects_ibfk_1` FOREIGN KEY (`projects_ID`) REFERENCES `projects` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `users_projects_ibfk_2` FOREIGN KEY (`acl_users_id`) REFERENCES `iof_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_details`
--
ALTER TABLE `user_details`
ADD CONSTRAINT `fk_user_details_acl_users1` FOREIGN KEY (`acl_users_id`) REFERENCES `iof_users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_user_details_contract_types1` FOREIGN KEY (`contract_type`) REFERENCES `contract_types` (`id_contract`) ON DELETE NO ACTION ON UPDATE NO ACTION;

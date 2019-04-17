--
-- Volcado de datos para la tabla `company`
--

INSERT INTO `company` (`id_company`, `name_company`, `brand`, `rfc`, `website`, `company_isactive`, `name_bank`, `number_acount`, `interbank_clabe`, `sucursal_name`, `record_date`, `business`, `id_update_actions`, `progress_profile`, `cust_type`, `isprospect`, `ishost`, `interestingin`) VALUES
(1, 'Enlasa', 'enlasa', 'enl12178g', 'enlasa.com', 1, 'Santander', '3456789', '7483hdjeh372', '002', '2014-11-24', 'Transportes', 0, 0, 0, 0, 0, 'gps');

--
-- Volcado de datos para la tabla `iof_users`
--

INSERT INTO `iof_users` (`user_id`, `privateKey`, `id_company`, `user_type`, `name`, `surname`, `lastname`, `datebirth`, `email`, `numberEmployee`, `rfc`, `phone`, `user_name`, `password`, `password_salt`, `status`, `created_on`, `modified_on`, `isDetailed`, `photofilename`, `photofile`, `id_job`, `canlogin`, `id_department`, `user_principal`, `avatar`) VALUES
(1, NULL, 1, NULL, 'Erick editado', 'Garcia', 'Bravo', '0000-00-00', 'erick@example.com', '13', '', 12345678, 'er1', '317cea28a02172901a8da2711511b0eb', NULL, 'Y', '2014-10-08 17:19:37', '2014-12-05 22:50:17', NULL, NULL, NULL, 1, 1, 1, NULL, NULL),
(4, NULL, 1, NULL, 'lorena', 'martinez', 'cuapio', '0000-00-00', 'l@hotmail.com', '12', '', 0, 'lor1', '62a90ccff3fd73694bf6281bb234b09a', NULL, 'Y', '2014-10-14 22:43:51', '2014-12-05 22:50:03', NULL, NULL, NULL, 2, 1, 1, 0, NULL),
(5, NULL, 1, NULL, 'f', 'ewf', 'fw', '0000-00-00', 'wfew@frefre.com', '11', '', 0, 'ffractal', '7a08841c6ea880754bfccdae0a48cd84', NULL, 'Y', '2014-10-22 20:25:40', '2014-12-05 22:50:39', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(6, NULL, 1, NULL, 'wf', 'fw', 'frefer', '0000-00-00', 'fwfer@ghjhj.com', '10', '', 0, 'wtffractal', 'e6e9dece176d3d462cf520499f3ef3d7', NULL, 'Y', '2014-10-28 16:40:44', '2014-12-05 22:50:09', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(7, NULL, 1, NULL, 'WFERF', 'FREFRE', 'EFER', '0000-00-00', 'FEFREFE@eefr.com', '9', '', 3432423, 'werfg', '1ea752b1694cba665ecefb5a38d2422d', NULL, 'Y', '2014-10-28 16:41:01', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(8, NULL, 1, NULL, 'luis', 'martinez', 'cuapio', '0000-00-00', 'luis@hotmail.com', '8', '', 3432423, 'luisHorus', '1a79a4d60de6718e8e5b326e338ae533', NULL, 'Y', '2014-11-03 15:42:33', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(9, NULL, 1, NULL, 'dasd', 'das', 'fasf', '0000-00-00', 'user@example.com', '7', '', 4324234, 'dsda', '1a79a4d60de6718e8e5b326e338ae533', NULL, 'Y', '2014-11-03 15:45:51', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(10, NULL, 1, NULL, 'ricardo', 'Chávez', 'Fernández', '0000-00-00', 'rick@iofractal.com', '1', '', 423423, 'rick', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 06:00:00', '2014-12-05 22:48:52', NULL, NULL, NULL, 1, 1, 1, NULL, NULL),
(11, NULL, 1, NULL, 'arons', 'lala', 'peres', '1996-07-15', 'maestro@iofractal.com', '4', '', 1234567, 'arons', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 18:35:25', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, 0, NULL),
(12, NULL, 1, NULL, 'luis', 'erick', 'galvan', '1993-05-18', 'erickgarcia693@gmail.com', '3', '', 423355, 'lala', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 18:52:41', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(13, NULL, 1, NULL, 'ricardo', 'cuapios', 'valdellama', '1991-08-01', 'rick.chfz@gmail.com', '2', '', 6756756, 'rickValdeza', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 19:01:11', '2014-12-08 18:38:56', NULL, NULL, NULL, 1, 1, 1, 0, NULL),
(14, NULL, 1, NULL, 'gabo', 'lala', 'lala', '0000-00-00', 'gabo@iofractal.com', '5', '', 57565675, 'pepe', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-14 18:44:53', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(15, NULL, 1, NULL, 'alan', 'garcia', 'bellamin', '0000-00-00', 'alan@iofractal.com', '6', '', 33242342, 'jose', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-14 22:43:29', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL);

--
-- Volcado de datos para la tabla `addresses`
--

INSERT INTO `addresses` (`id_address`, `company_id`, `street`, `postalcode`, `number`, `interior`, `neighborhood`, `state_id`, `district`, `phone`, `ext`, `url_map`) VALUES
(1, 1, 'Jesus Urquia', 68219, '129', '2', 78750, 20, 1005, '557890641234', 5, 'https://www.google.com.mx/maps/place/Jes%C3%BAs+Urquiaga,+Del+Valle+Centro,+03100+Ciudad+de+M%C3%A9xico,+D.F./@19.3960008,-99.1711432,17z/data=!3m1!4b1!4m2!3m1!1s0x85d1ff72690d6b6b:0xf178d62d39b86d13');

--
-- Volcado de datos para la tabla `users_addresses`
--

INSERT INTO `users_addresses` (`id_address`, `user_id`, `street`, `postalcode`, `number`, `interior`, `state_id`, `district_id`, `neighborhood`) VALUES
(1, 1, 'Avenida Siempre Viva', 2000, '7', '5', 1, 1, 1);
CREATE DATABASE  IF NOT EXISTS `osiris` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `osiris`;
-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: osiris
-- ------------------------------------------------------
-- Server version	5.5.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `iof_role_permission`
--
INSERT INTO `iof_role_permission` (`id`, `role_id`, `name_role`, `permission_id`, `name_permission`, `status`) VALUES
(1, 1, NULL, 1, NULL, 1),
(2, 1, NULL, 2, NULL, 1),
(3, 1, NULL, 3, NULL, 1),
(4, 1, NULL, 4, NULL, 1),
(5, 1, NULL, 5, NULL, 1),
(6, 1, NULL, 6, NULL, 1),
(7, 1, NULL, 7, NULL, 1),
(8, 1, NULL, 8, NULL, 1),
(9, 1, NULL, 9, NULL, 1),
(17, 2, NULL, 1, NULL, 1),
(18, 2, NULL, 2, NULL, 0),
(19, 2, NULL, 3, NULL, 0),
(20, 2, NULL, 4, NULL, 0),
(21, 2, NULL, 5, NULL, 0),
(22, 2, NULL, 6, NULL, 0),
(23, 2, NULL, 7, NULL, 0),
(24, 2, NULL, 8, NULL, 0),
(25, 2, NULL, 9, NULL, 0),
(249, 3, NULL, 1, NULL, 1),
(250, 3, NULL, 2, NULL, 0),
(251, 3, NULL, 0, NULL, 0),
(252, 3, NULL, 4, NULL, 0),
(253, 3, NULL, 5, NULL, 1),
(254, 4, NULL, 1, NULL, 1),
(255, 4, NULL, 2, NULL, 0),
(256, 4, NULL, 3, NULL, 0),
(257, 4, NULL, 4, NULL, 0),
(258, 4, NULL, 5, NULL, 1),
(260, 1, NULL, 12, NULL, 1),
(261, 1, NULL, 13, NULL, 1),
(262, 1, NULL, 14, NULL, 1),
(263, 1, NULL, 15, NULL, 1),
(264, 1, NULL, 16, NULL, 1),
(265, 1, NULL, 17, NULL, 1),
(266, 1, NULL, 18, NULL, 1),
(267, 1, NULL, 19, NULL, 1),
(268, 1, NULL, 20, NULL, 1),
(269, 1, NULL, 21, NULL, 1);

/*INSERT INTO `iof_role_permission` 
(`id`,`role_id`,`permission_id`,`name_role`,`name_permission`,`status`)
VALUES 
(1,2,2,'/Gutenberg/proposals/add','/Gutenberg/proposals/add',1),
(2,2,15,'/System/profile/index','/System/profile/index',1),
(3,2,16,'/System/preferences/index','/System/preferences/index',1),
(4,2,17,'/Ioteca/components/update','/Ioteca/components/update',1),
(5,2,18,'/In/customers/addview','/In/customers/addview',1),
(6,2,19,'/In/customers/delete','/In/customers/delete',1),
(7,2,20,'/In/customers/update','/In/customers/update',1),
(8,2,21,'/In/customers/index','/In/customers/index',1),
(9,2,22,'/In/projects/createview','/In/projects/createview',1),
(10,2,23,'/In/projects/deleteview','/In/projects/deleteview',1),
(11,2,24,'/In/projects/updateview','/In/projects/updateview',1),
(12,2,25,'/In/projects/index','/In/projects/index',1),
(13,2,26,'/Out/expenses/add','/Out/expenses/add',1),
(14,2,27,'/Out/expenses/delete','/Out/expenses/delete',1),
(15,2,28,'/Out/expenses/update','/Out/expenses/update',1),
(16,2,29,'/Out/expenses/index','/Out/expenses/index',1),
(17,2,30,'/System/users/add','/System/users/add',1),
(18,2,31,'/System/users/delete','/System/users/delete',1),
(19,2,32,'/System/users/update','/System/users/update',1),
(20,2,33,'/System/users/index','/System/users/index',1),
(21,2,34,'/In/invoices/index','/In/invoices/index',1),
(22,2,35,'/Horus/clock/add','/Horus/clock/add',1),
(23,2,36,'/Horus/clock/delete','/Horus/clock/delete',1),
(24,2,37,'/Horus/clock/update','/Horus/clock/update',1),
(25,2,38,'/Horus/clock/index','/Horus/clock/index',1),
(26,2,41,'/Horus/activities/update','/Horus/activities/update',1),
(27,2,42,'/Horus/activities/index','/Horus/activities/index',1),
(28,2,43,'/In/cxc/add','/In/cxc/add',1),
(29,2,44,'/In/cxc/detail','/In/cxc/detail',1),
(30,2,45,'/In/cxc/update','/In/cxc/update',1),
(31,2,46,'/In/cxc/index','/In/cxc/index',1),
(32,2,47,'/Out/cxp/addpayment','/Out/cxp/addpayment',1),
(33,2,48,'/Out/cxp/getpayments','/Out/cxp/getpayments',1),
(34,2,49,'/Out/cxp/update','/Out/cxp/update',1),
(35,2,50,'/Out/cxp/index','/Out/cxp/index',1),
(36,2,51,'/Horus/inventory/add','/Horus/inventory/add',1),
(37,2,52,'/Horus/inventory/delete','/Horus/inventory/delete',1),
(38,2,53,'/Horus/inventory/update','/Horus/inventory/update',1),
(39,2,54,'/Horus/inventory/index','/Horus/inventory/index',1),
(40,2,55,'/Horus/payroll/index','/Horus/payroll/index',1),
(41,2,56,'/Horus/index/index','/Horus/index/index',1),
(42,2,57,'/welcome/index','/welcome/index',1),
(43,2,58,'/In/cxc/calendar','/In/cxc/calendar',1),
(44,2,59,'/In/cxc/fillcalendar','/In/cxc/fillcalendar',1),
(45,2,60,'/Horus/contact/getcontact','/Horus/contact/getcontact',1),
(46,2,61,'/In/projects/showteam','/In/projects/showteam',1),
(47,2,62,'/System/users/load','/System/users/load',1),
(48,2,63,'/System/users/editdetails','/System/users/editdetails',1),
(49,2,64,'/System/users/adduserdetails','/System/users/adduserdetails',1),
(50,2,65,'/System/users/getprojectsbyuser','/System/users/getprojectsbyuser',1),
(51,2,66,'/System/users/create','/System/users/create',1),
(52,2,67,'/In/customers/getearning','/In/customers/getearning',1),
(53,2,68,'/In/invoices/createview','/In/invoices/createview',1),
(54,2,71,'/Horus/contact/add','/Horus/contact/add',1),
(55,2,72,'/Horus/contact/delete','/Horus/contact/delete',1),
(56,2,73,'/Horus/contact/update','/Horus/contact/update',1),
(57,2,74,'/Horus/contact/index','/Horus/contact/index',1),
(58,2,91,'/In/customers/historycustomer','/In/customers/historycustomer',1),
(59,2,92,'/In/customers/remember','/In/customers/remember',1),
(60,2,93,'/In/customers/discard','/In/customers/discard',1),
(61,2,94,'/In/customers/enable','/In/customers/enable',1),
(62,2,95,'/In/customers/getcontract','/In/customers/getcontract',1),
(63,2,96,'/In/customers/detalleview','/In/customers/detalleview',1),
(64,2,97,'/System/users/details','/System/users/details',1),
(65,2,98,'/System/users/changepass','/System/users/changepass',1),
(66,2,99,'/System/users/deletedetails','/System/users/deletedetails',1),
(67,2,100,'/System/preferences/getcatalog','/System/preferences/getcatalog',1),
(68,2,101,'/System/preferences/update','/System/preferences/update',1),
(69,2,102,'/Horus/activities/activitiesview','/Horus/activities/activitiesview',1),
(70,2,103,'/In/invoices/updateview','/In/invoices/updateview',1),
(71,2,104,'/In/invoices/pdfview','/In/invoices/pdfview',1),
(72,2,105,'/In/customers/progressbar','/In/customers/progressbar',1),
(73,2,106,'/In/cxc/aboutproject/','/In/cxc/aboutproject/',1),
(74,2,107,'/System/users/projectbycompany','/System/users/projectbycompany',1),
(75,2,108,'/Horus/inventory/getobjectbytype','/Horus/inventory/getobjectbytype',1),
(76,2,109,'/In/cxc/invoices','/In/cxc/invoices',1),
(77,2,110,'/Out/supplier/index','/Out/supplier/index',1),
(78,2,111,'/Out/supplier/add','/Out/supplier/add',1),
(79,2,112,'/Out/supplier/update','/Out/supplier/update',1),
(80,2,113,'/Out/supplier/delete','/Out/supplier/delete',1),
(81,2,114,'/Out/supplier/detalle','/Out/supplier/detalle',1),
(82,2,115,'/Out/supplier/historysupplier','/Out/supplier/historysupplier',1),
(83,2,116,'/In/proposals/index','/In/proposals/index',1),
(84,2,117,'/In/stock/index','/In/stock/index',1),
(85,2,118,'/In/crm/index','/In/crm/index',1),
(86,2,119,'/In/cxc/getpayments','/In/cxc/getpayments',1),
(87,2,120,'/In/cxc/addpayment','/In/cxc/addpayment',1),
(88,2,121,'/In/stock/add','/In/stock/add',1),
(89,2,122,'/In/stock/delete','/In/stock/delete',1),
(90,2,123,'/In/stock/update','/In/stock/update',1),
(91,2,124,'/Out/purchaseorder/index','/Out/purchaseorder/index',1),
(92,2,125,'/Out/purchaseorder/addorder','/Out/purchaseorder/addorder',1),
(93,2,126,'/Out/purchaseorder/editorder','/Out/purchaseorder/editorder',1),
(94,2,127,'/Out/purchaseorder/deleteorder','/Out/purchaseorder/deleteorder',1),
(95,2,128,'/In/stock/reload','/In/stock/reload',1),
(96,2,129,'/In/stock/getstock','/In/stock/getstock',1),
(97,2,130,'/In/stock/setnewstock','/In/stock/setnewstock',1),
(98,2,131,'/In/crm/add','/In/crm/add',1),
(99,2,132,'/In/stock/addcategory','/In/stock/addcategory',1),
(100,2,133,'/In/crm/update','/In/crm/update',1),
(101,2,134,'/In/crm/delete','/In/crm/delete',1),
(102,2,135,'/In/prospectprojects/index','/In/prospectprojects/index',1),
(103,2,136,'/In/prospectprojects/update','/In/prospectprojects/update',1),
(104,2,137,'/In/prospectprojects/add','/In/prospectprojects/add',1),
(105,2,138,'/In/prospectprojects/delete','/In/prospectprojects/delete',1),
(106,2,139,'/Horusdashboard/topprojects','/Horusdashboard/topprojects',1),
(107,2,140,'/Horusdashboard/topcustomers','/Horusdashboard/topcustomers',1),
(108,2,141,'/category/delete','/category/delete',1),
(109,2,142,'/category/update','/category/update',1),
(110,2,144,'/Horus/activities/surfprevweek','/Horus/activities/surfprevweek',1),
(111,2,145,'/Horus/activities/surfnextweek','/Horus/activities/surfnextweek',1),
(112,2,146,'/Horus/activities/filltablebyuserid','/Horus/activities/filltablebyuserid',1),
(113,2,147,'/Horus/activities/getuserswithactivities','/Horus/activities/getuserswithactivities',1),
(114,2,148,'/In/prospectprojects/changestoproject','/In/prospectprojects/changestoproject',1),
(115,2,149,'/welcome/getgraphs','/welcome/getgraphs',1),
(116,2,150,'/Horus/preferences/choosetheme','/Horus/preferences/choosetheme',1),
(117,2,151,'/In/projects/getprojectsbycustomerid','/In/projects/getprojectsbycustomerid',1),
(118,2,152,'/Horus/employee/index','/Horus/employee/index',1),
(119,2,153,'/In/warehouse/index','/In/warehouse/index',1),
(120,2,154,'/In/warehouse/order','/In/warehouse/order',1),
(121,2,155,'/In/warehouse/inventory','/In/warehouse/inventory',1),
(122,2,156,'/In/warehouse/articleentry','/In/warehouse/articleentry',1),
(123,2,157,'/In/crm/detalle','/In/crm/detalle',1),
(124,2,158,'/Out/purchaseorder/history','/Out/purchaseorder/history',1),
(125,2,159,'/In/warehouse/entryorder','/In/warehouse/entryorder',1),
(126,2,160,'/Horus/payroll/addpayroll','/Horus/payroll/addpayroll',1),
(127,2,161,'/Horus/activities/history','/Horus/activities/history',1),
(128,2,162,'/System/permissions/index','/System/permissions/index',1),
(129,2,163,'/System/permissions/add','/System/permissions/add',1),
(130,2,164,'/System/permissions/update','/System/permissions/update',1),
(131,2,165,'/System/permissions/delete','/System/permissions/delete',1),
(132,2,166,'/Horus/inventory/reload','/Horus/inventory/reload',1),
(133,2,167,'/Horus/department/update','/Horus/department/update',1),
(134,2,168,'/Horus/department/delete','/Horus/department/delete',1),
(135,2,169,'/Horus/inventory/adddepartment','/Horus/inventory/adddepartment',1),
(136,2,170,'/In/proposals/edit','/In/proposals/edit',1),
(137,2,171,'/In/proposals/delete','/In/proposals/delete',1),
(138,2,172,'/In/proposals/export','/In/proposals/export',1),
(139,2,173,'/Horus/inventory/history','/Horus/inventory/history',1),
(140,2,174,'/In/stock/history','/In/stock/history',1),
(141,2,175,'/Out/expenses/history','/Out/expenses/history',1),
(142,2,176,'/In/projects/details','/In/projects/details',1),
(143,2,177,'/In/projects/historyprospectproject','/In/projects/historyprospectproject',1),
(144,2,178,'/In/prospectprojects/addmeetingsindex','/In/prospectprojects/addmeetingsindex',1),
(145,2,179,'/In/invoices/deleteview','/In/invoices/deleteview',1),
(146,2,180,'/In/prospectprojects/meetingsindex','/In/prospectprojects/meetingsindex',1),
(147,2,181,'/In/prospectprojects/updatemeetingsindex','/In/prospectprojects/updatemeetingsindex',1),
(148,2,182,'/Horus/documents/index','/Horus/documents/index',1),
(149,2,183,'/Horus/documents/create','/Horus/documents/create',1),
(150,2,184,'/Horus/documents/edit','/Horus/documents/edit',1),
(151,2,185,'/Horus/documents/delete','/Horus/documents/delete',1),
(152,2,186,'/In/prospectprojects/deletemeetingsindex','/In/prospectprojects/deletemeetingsindex',1),
(153,2,187,'/In/finishedproduct/index','/In/finishedproduct/index',1),
(154,2,188,'/Horus/documents/view','/Horus/documents/view',1),
(155,2,189,'/Horus/documents/creaexpor','/Horus/documents/creaexpor',1),
(156,2,190,'/Horus/documents/versions','/Horus/documents/versions',1),
(157,2,191,'/Horus/documents/deleteversion','/Horus/documents/deleteversion',1),
(158,2,192,'/Horus/documents/export','/Horus/documents/export',1),
(159,2,193,'/System/company/index','/System/company/index',1),
(160,2,194,'/System/company/update','/System/company/update',1),
(161,2,195,'/In/warehouse/history','/In/warehouse/history',1),
(162,2,196,'/Horus/payroll/listpayroll','/Horus/payroll/listpayroll',1),
(163,2,197,'/Horus/payroll/history','/Horus/payroll/history',1),
(164,2,198,'/home/index','/home/index',1),
(165,2,199,'/In/warehouse/historyproduct','/In/warehouse/historyproduct',1),
(166,2,200,'/In/invoices/details','/In/invoices/details',1),
(167,2,201,'/Horus/clock/history','/Horus/clock/history',1),
(168,2,202,'/System/users/privilegetype','/System/users/privilegetype',1),
(169,2,203,'/In/catalog/index','/In/catalog/index',1),
(170,2,204,'/Horus/contact/details','/Horus/contact/details',1),
(171,2,205,'/In/sales/add','/In/sales/add',1),
(172,2,206,'/In/sales/index','/In/sales/index',1),
(173,2,207,'/In/catalog/delete','/In/catalog/delete',1),
(174,2,208,'/In/invoices/searchcustomer','/In/invoices/searchcustomer',1),
(175,2,209,'/In/sales/searchproduct','/In/sales/searchproduct',1),
(176,2,210,'/Horus/activities/graphs','/Horus/activities/graphs',1),
(177,2,211,'/In/sales/create','/In/sales/create',1),
(178,2,212,'/Horus/job/index','/Horus/job/index',1),
(179,2,213,'/Horus/job/delete','/Horus/job/delete',1),
(180,2,214,'/Gutenberg/vision/details','/Gutenberg/vision/details',1),
(181,2,215,'/Gutenberg/minute/details','/Gutenberg/minute/details',1),
(182,2,216,'/In/warehouse/add','/In/warehouse/add',1),
(183,2,217,'/In/warehouse/update','/In/warehouse/update',1),
(184,2,218,'/In/finishedproduct/add','/In/finishedproduct/add',1),
(185,2,219,'/In/finishedproduct/update','/In/finishedproduct/update',1),
(186,2,220,'/In/finishedproduct/delete','/In/finishedproduct/delete',1),
(187,2,221,'/Horus/cash/index','/Horus/cash/index',1),
(188,2,222,'/Horus/cash/add','/Horus/cash/add',1),
(189,2,223,'/Horus/cash/update','/Horus/cash/update',1),
(190,2,224,'/Horus/cash/delete','/Horus/cash/delete',1),
(191,2,225,'/Horus/bank/index','/Horus/bank/index',1),
(192,2,226,'/Horus/bank/add','/Horus/bank/add',1),
(193,2,227,'/In/catalog/fullviewer','/In/catalog/fullviewer',1),
(194,2,228,'/Horus/accountmanager/index','/Horus/accountmanager/index',1),
(195,2,229,'/Horus/accountmanager/add','/Horus/accountmanager/add',1),
(196,2,230,'/Horus/accountmanager/edit','/Horus/accountmanager/edit',1),
(197,2,231,'/Horus/accountmanager/delete','/Horus/accountmanager/delete',1),
(198,2,232,'/Robot/horus_toy-store/index','/Robot/horus_toy-store/index',1),
(199,2,235,'/Horus/payroll/calendar','/Horus/payroll/calendar',1),
(200,2,236,'/In/proposals/price','/In/proposals/price',1),
(201,2,237,'/Horus/bank/edit','/Horus/bank/edit',1),
(202,2,238,'/Horus/bank/delete','/Horus/bank/delete',1),
(203,2,239,'/In/prospectprojects/createview','/In/prospectprojects/createview',1),
(204,2,240,'/In/prospectprojects/updateview','/In/prospectprojects/updateview',1),
(205,2,241,'/In/prospectprojects/deleteview','/In/prospectprojects/deleteview',1),
(206,2,242,'/In/prospectprojects/details','/In/prospectprojects/details',1),
(207,2,243,'/In/proposals/addorder','/In/proposals/addorder',1),
(208,2,244,'/In/proposals/history','/In/proposals/history',1),
(209,2,245,'/In/proposals/editorder','/In/proposals/editorder',1),
(210,2,246,'/In/proposals/viewArticleForm','/In/proposals/viewArticleForm',1),
(211,2,247,'/In/prospectprojects/export','/In/prospectprojects/export',1),
(212,2,248,'/In/proposals/changestoproject','/In/proposals/changestoproject',1),
(213,2,249,'/In/prospectprojects/email','/In/prospectprojects/email',1),
(214,2,254,'/In/prospectprojects/pdf','/In/prospectprojects/pdf',1),
(215,2,255,'/In/proposals/pdf','/In/proposals/pdf',1),
(216,2,256,'/In/proposals/sendmail','/In/proposals/sendmail',1),
(217,2,262,'/Horus/department/index','/Horus/department/index',1),
(218,2,263,'/Horus/department/add','/Horus/department/add',1),
(219,2,266,'/Horus/employee/update','/Horus/employee/update',1),
(220,2,267,'/System/users/avatar','/System/users/avatar',1),
(221,2,268,'/Gutenberg/documentation/index','/Gutenberg/documentation/index',1),
(222,2,269,'/Gutenberg/documentation/add','/Gutenberg/documentation/add',1),
(223,2,270,'/Gutenberg/documentation/edit','/Gutenberg/documentation/edit',1),
(224,2,271,'/Gutenberg/documentation/delete','/Gutenberg/documentation/delete',1),
(225,2,272,'/Horus/training/index','/Horus/training/index',1),
(226,2,273,'/Horus/training/add','/Horus/training/add',1),
(227,2,274,'/Horus/training/edit','/Horus/training/edit',1),
(228,2,275,'/Horus/training/delete','/Horus/training/delete',1),
(229,2,276,'/Horus/survey/index','/Horus/survey/index',1),
(230,2,277,'/Horus/survey/add','/Horus/survey/add',1),
(231,2,278,'/Horus/survey/edit','/Horus/survey/edit',1),
(232,2,279,'/Horus/survey/delete','/Horus/survey/delete',1),
(233,2,280,'/Horus/account/index','/Horus/account/index',1),
(234,2,281,'/Horus/account/add','/Horus/account/add',1),
(235,2,282,'/Horus/account/edit','/Horus/account/edit',1),
(236,2,283,'/Horus/account/delete','/Horus/account/delete',1);*/
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 12:38:23

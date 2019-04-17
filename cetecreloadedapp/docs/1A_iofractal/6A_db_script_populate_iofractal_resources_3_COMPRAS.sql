/*
 * Inserts para la tabla de acl permissions COMPRAS
 * */
INSERT INTO `acl_permissions` (`permission`, `acl_resources_uid`, `acl_roles_role_id`) VALUES
('/System/profile/index', 15, 8),
('/Out/expenses/add', 26, 8),
('/Out/expenses/delete', 27, 8),
('/Out/expenses/update', 28, 8),
('/Out/expenses/index', 29, 8),
('/Horus/index/index', 56, 8),
('/welcome/index', 57, 8),
('/Out/purchaseorder/index', 124, 8),
('/Out/purchaseorder/addorder', 125, 8),
('/Out/purchaseorder/editorder', 126, 8),
('/Out/purchaseorder/deleteorder', 127, 8),
('/In/stock/reload', 128, 8),
('/Horus/dashboard/topprojects', 139, 8),
('/Horus/dashboard/topcustomers', 140, 8),
('/welcome/getgraphs', 149, 8),
('/Out/purchaseorder/history', 158, 8),
('/Out/expenses/history', 175, 8),
('/home/index', 198, 8),
('/Out/supplier/index', 257, 8),
('/Out/stock/reload', 258, 8);
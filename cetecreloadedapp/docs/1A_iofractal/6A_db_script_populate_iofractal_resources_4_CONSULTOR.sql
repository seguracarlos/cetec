/*
* INSERTS para la tabla acl_permissions CONSULTOR
*/
INSERT INTO `acl_permissions` (`permission`, `acl_resources_uid`, `acl_roles_role_id`) VALUES
('/System/profile/index', 15, 4),
('/System/users/update', 32, 4),
('/Horus/clock/add', 35, 4),
('/Horus/clock/delete', 36, 4),
('/Horus/clock/update', 37, 4),
('/Horus/clock/index', 38, 4),
('/Horus/activities/add', 39, 4),
('/Horus/activities/delete', 40, 4),
('/Horus/activities/update', 41, 4),
('/Horus/activities/index', 42, 4),
('/welcome/index', 57, 4),
('/Horus/activities/activitiesview', 102, 4),
('/Horus/activities/addweek', 143, 4),
('/Horus/activities/surfprevweek', 144, 4),
('/Horus/activities/surfnextweek', 145, 4);
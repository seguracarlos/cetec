<?php
class Constants{
	const STATUS_ACTIVO = "ACTIVO";
	const STATUS_INACTIVO = "INACTIVO";
	const RESPONSE_CODE_OK = 200;
	const ID_PROJECT_MISSING = 100;
	const NOT_PAYMENTS_BY_PROJECT = 101;
	const WEEKLENGTH = 7;
	const ALMACEN = 1; 
	
}
class Clock{
	const ENTRADA = "Entrada";
	const SALIDA = "Salida";
}
class UserType{
	const INTERNO = "Interno";
	const EXTERNO = "Externo";
}
class ExpensesType{
	const GADMIN = 1;
	const GPROD = 2;
	const GDIV = 3;
}
class Categories {
	const DEPARTAMENTO = 0;
	const ALMACEN = 1;
}
class Status{
	const INACTIVO = 0;
	const ACTIVO = 1;
}
class ActionUpdate{
	const UPDATED = 0;
	const WAITING = 1;
	const DONE = 2;
}
class Response{
	const FAIL = 0;
	const SUCCESS = 1;
	const NOT_FOUND = 404;
	const NOT_SERVER = 500;
	const WORKING = 300;
}
class Nomina{
	const NOMINA = "NOMINA";
	const BONO = "BONO";
	const DESCUENTO = "DESCUENTO";
}
class HorusSalt{
	const SALT = "&$/&%)/HorusSalt(/$%#&#&(/)(";
}
class Host{
	const isHost = "1";
}
class Privilege{
	const SUPERUSUARIO = 1;
	const ADMINISTRADOR = 2;
	const OPERADOR = 3;
	const CONSULTOR = 4;
	const CLIENTE = 5;
	const PROVEEDOR = 6;
	//const INVALIDO = 7;
	const VISUALIZADOR = 8;
	//const SUPERADMINISTRADOR = 9;
	const SUPERVISOR = 7;
	const VENDEDOR = 11;	
	const INTERNO = 0;
	const EXTERNO = 0;
	const TYPE_INTERNO = 0;
	const CONTACTO = 10;
}
class RoleName{
	const SUPERUSUARIO = "SuperUsuario";
	const ADMINISTRADOR = "Administrador";
	const OPERADOR = "Operador";
	const CONSULTOR = "Consultor";
	const CLIENTE = "Cliente";
	const INVALIDO = "Invalido";
	const SUPERADMINISTRADOR = "Super Administrador";
}
class Required{
	const canBeNull = "canBeNull";
}
class Preferences{
	const CODE_IVA = 1;
	const CODE_LOGO = 2;
	const CODE_TUTORIAL = 3;
	const CODE_ICONS = 4;
	const ICONS = 'ICONS';
	const CODE_TUTO_ON = "activo";
	const CODE_TUTO_OFF = "inactivo";
	const STRING_IVA = 'IVA';
	const STRING_LOGO = 'LOGO';
	const STRING_FOTOS = 'FOTO';
	const TUTORIAL = 'TUTORIAL';
	const STRING_TUTO = 'TUTO';
}
class Profile{
	const PENDIENTE = "<label class='textCap'>?</label>";
	const PENDIENTEREVISION = "<label class='textCap'>Pendiente</label>";
	const PENDIENTE_STRING = "?";
}
class ProfileFields{
	const FIELDS = 31;
	const PERCENT = 100;
	const DEFAULTFIELDS = 2;
	const DEFAULTFIELDSCLIENTS = 4;
	const DEFAULTFIELDSUPLIER = 3;
}

class Clients{
	const CLIENTE = 0;
	const PROVEEDOR = 1;
	const PROSPECTOS = 2;
}
class CronToken{
	const TOKEN_BIRTHDAY_CONGRATS = "TOKEN_BIRTHDAY_CONGRATS";
	const TOKEN_PAYMENT_REMINDER = "TOKEN_PAYMENT_REMINDER";
	const TOKEN_SUMMARY_ACTIVITIES = "TOKEN_SUMMARY_ACTIVITIES";
	const TOKEN_CXC_REMINDER = "TOKEN_CXC_REMINDER";
	const TOKEN_CXP_REMINDER = "TOKEN_CXP_REMINDER";
	const TOKEN_MIN_STOCK = "TOKEN_MIN_STOCK";
	const TOKEN_EXCESS_STOCK = "TOKEN_EXCESS_STOCK";
}
class SeparatorString{
	const SEPARATOR = "_+_";
	const SEPARATOR2 = "-*-";
	const INICIALITY = "y_-";
}

class WeekNames{
	const LUNES = "Lunes";
	const MARTES = "Martes";
	const MIERCOLES = "Miercoles";
	const JUEVES = "Jueves";
	const VIERNES = "Viernes";
	const SABADO = "Sabado";
	const DOMINGO = "Domingo";
	const ACTIVIDAD = "Actividad";
}

class WeekDayId{
	const LUNES = 1;
	const MARTES = 2;
	const MIERCOLES = 3;
	const JUEVES = 4;
	const VIERNES = 5;
	const SABADO = 6;
	const DOMINGO = 0;
	const NAME = "NAME";
}

class ActionStatus{
	const OFF = false;
	const ON = true;
}
class Theme{
	const SELECTED = 1;
	const CUPERTINO = "cupertino";
	const OVERCAST = "overcast";
	const START = "start";
	const DARKHIVE = "dark-hive";
	const BLACKTIE = "black-tie";
	const HORUS = "horus-robot";
}
class StatusPurchaseOrder{
	const NOT_DELIVERED = 0;
	const DELIVERED_COMPLETE = 1; 
	const DELIVERED_INCOMPLETE = 2;
	const NOT_DELIVERED_STRING = "No entregado";
	const DELIVERED_COMPLETE_STRING = "Entrega completa";
	const DELIVERED_INCOMPLETE_STRING = "Entrega incompleta";
}
class ProductType{
	const INSUMO = 0;
	const TERMINADO = 1;
}

class TypeStorage{
	const SALIDA = 0;
	const ENTRADA = 1;
}

class ProductReview{
	const REVISION = 1;
	const REVISADO = 0;
}

class TypeAmountBank{
	const CARGO = 0;
	const ABONO = 1;
	const ACCOUNTBANKGENERAL = "100";
}

class TypeAmountAccount{
	const CARGO = 1;
	const ABONO = 0;
}

class KeysGlobalPermissions{
	
	const GUTENBERGDOCUMENTATIONINDEX = "/Gutenberg/documentation/index";
	const GUTENBERGDOCUMENTATIONADD = "/Gutenberg/documentation/add";
	const GUTENBERGDOCUMENTATIONEDIT = "/Gutenberg/documentation/edit";
	const GUTENBERGDOCUMENTATIONDELETE = "/Gutenberg/documentation/delete";
	
	const HORUSTRAININGINDEX = "/Horus/training/index";
	const HORUSTRAININGADD = "/Horus/training/add";
	const HORUSTRAININGEDIT = "/Horus/training/edit";
	const HORUSTRAININGDELETE = "/Horus/training/delete";
	
	const HORUSPOLLINDEX = "Horus/poll/index";
	const HORUSPOLLADD = "Horus/poll/add";
	const HORUSPOLLEDIT = "Horus/poll/edit";
	const HORUSPOLLDELETE = "Horus/poll/delete";
	
	const GUTENBERGPROPOSALSCRMINDEX = "/Gutenberg/proposalscrm/index";
	const GUTENBERGPROPOSALSADD = "/Gutenberg/proposals/add";
	const GUTENBERGPROPOSALADD = "/Gutenberg/proposal/add";
	const GUTENBERGPROPOSALDELETE = "/Gutenberg/proposal/delete";
	const GUTENBERGPROPOSALUPDATE = "/Gutenberg/proposal/update";
	const GUTENBERGPROPOSALINDEX = "/Gutenberg/proposal/index";
	const GUTENBERGVISIONADD = "/Gutenberg/vision/add";
	const GUTENBERGVISIONDELETE = "/Gutenberg/vision/delete";
	const GUTENBERGVISIONUPDATE = "/Gutenberg/vision/update";
	const GUTENBERGVISIONINDEX = "/Gutenberg/vision/index";
	const GUTENBERGMINUTEADD = "/Gutenberg/minute/add";
	const GUTENBERGMINUTEDELETE = "/Gutenberg/minute/delete";
	const GUTENBERGMINUTEUPDATE = "/Gutenberg/minute/update";
	const GUTENBERGMINUTEINDEX = "/Gutenberg/minute/index";
	const HOMEPROFILEINDEX = "/Gutenberg/profile/index";
	const HOMEPREFERENCESINDEX = "/Gutenberg/preferences/index";
	const HORUSCOMPONENTSUPDATE = "/Ioteca/components/update";
	const INGRESOSCUSTOMERSADDVIEW = "/In/customers/add";
	const INGRESOSCUSTOMERSDELETE = "/In/customers/delete";
	const INGRESOSCUSTOMERSUPDATEVIEW = "/In/customers/update";
	const INGRESOSCUSTOMERSINDEX = "/In/customers/index";
	const INGRESOSPROJECTSCREATEVIEW = "/In/projects/createview";
	const INGRESOSPROJECTSDELETEVIEW = "/In/projects/deleteview";
	const INGRESOSPROJECTSUPDATEVIEW = "/In/projects/updateview";
	const INGRESOSPROJECTSINDEX = "/In/projects/index";
	const EGRESOSEXPENSESADD = "/Out/expenses/add";
	const EGRESOSEXPENSESDELETE = "/Out/expenses/delete";
	const EGRESOSEXPENSESUPDATE = "/Out/expenses/update";
	const EGRESOSEXPENSESINDEX = "/Out/expenses/index";
	const EGRESOSEXPENSESHISTORY = "/Out/expenses/history";
	const HORUSUSERSADD = "/System/users/add";
	const HORUSUSERSDELETE = "/System/users/delete";
	const HORUSUSERSUPDATE = "/System/users/update";
	const HORUSUSERSINDEX = "/System/users/index";
	const INGRESOSINVOICESADD ="/In/invoices/add";
	const INGRESOSINVOICESDELETE ="/In/invoices/delete";
	const INGRESOSINVOICESUPDATE ="/In/invoices/update";
	const INGRESOSINVOICESINDEX ="/In/invoices/index";
	const HORUSCLOCKADD ="/Horus/clock/add";
	const HORUSCLOCKDELETE ="/Horus/clock/delete";
	const HORUSCLOCKUPDATE ="/Horus/clock/update";
	const HORUSCLOCKINDEX ="/Horus/clock/index";
	const HORUSACTIVITIESADD ="/Horus/activities/add";
	const HORUSACTIVITIESDELETE ="/Horus/activities/delete";
	const HORUSACTIVITIESUPDATE  ="/Horus/activities/update";
	const HORUSACTIVITIESINDEX = "/Horus/activities/index";
	const INGRESOSCXCADD  = "/In/cxc/add";
	const INGRESOSCXCDETAIL  = "/In/cxc/detail";
	const INGRESOSCXCUPDATE = "/In/cxc/update";
	const INGRESOSCXCINDEX = "/In/cxc/index";
	const EGRESOSCXPADDPAYMENT = "/Out/cxp/addpayment";
	const EGRESOSCXPGETPAYMENTS = "/Out/cxp/getpayments";
	const EGRESOSCXPUPDATE  = "/Out/cxp/update";
	const EGRESOSCXPINDEX = "/Out/cxp/index";
	const HORUSINVENTORYADDVIEW ="/Horus/inventory/add";
	const HORUSINVENTORYDELETEVIEW ="/Horus/inventory/delete";
	const HORUSINVENTORYUPDATEVIEW = "/Horus/inventory/update";
	const HORUSINVENTORYINDEX = "/Horus/inventory/index";
	const HORUSPAYROLLINDEX = "/Horus/payroll/index";
	const HORUSINDEXINDEX = "/Horus/index/index";
	const HORUSWELCOMEINDEX = "/welcome/index";
	const INGRESOSCXCCALENDAR = "/In/cxc/calendar";
	const INGRESOSCXCFILLCALENDAR = "/In/cxc/fillcalendar";
	const HORUSCONTACTGETCONTACT = "/Horus/contact/getcontact";
	const INGRESOSPROJECTSSHOWTEAM = "/In/projects/showteam";
	const HORUSUSERSLOAD = "/System/users/load";
	const HORUSUSERSEDITDETAILS = "/System/users/editdetails";
	const HORUSUSERSADDUSERDETAILS = "/System/users/adduserdetails";
	const INGRESOSUSERSGETPROJECTSBYUSER = "/In/users/getprojectsbyuser";
	const HORUSUSERSCREATE = "/System/users/create";
	const INGRESOSCUSTOMERSGETEARNING = "/In/customers/getearning";
	const INGRESOSINVOICESCREATEVIEW = "/In/invoices/createview";
	const INGRESOSINVOICESSAVE = "/In/invoices/save";
	const IOTECACOMPONENTSINDEX = "/Ioteca/components/index";
	const IOTECACOMPONENTSADD = "/Ioteca/components/add";
	const HORUSCONTACTADD = "/Horus/contact/add";
	const HORUSCONTACTDELETE = "/Horus/contact/delete";
	const HORUSCONTACTUPDATE = "/Horus/contact/update";
	const HORUSCONTACTINDEX = "/Horus/contact/index";
	const IOTECATUTORIALADD = "/Ioteca/tutorial/add";
	const IOTECATUTORIALDELETE = "/Ioteca/tutorial/delete";
	const IOTECATUTORIALUPDATE = "/Ioteca/tutorial/update";
	const IOTECATUTORIALINDEX = "/Ioteca/tutorial/index";
	const IOTECALINKADD = "/Ioteca/link/add";
	const IOTECALINKDELETE = "/Ioteca/link/delete";
	const IOTECALINKUPDATE = "/Ioteca/link/update";
	const IOTECALINKINDEX = "/Ioteca/link/index";
	const IOTECACURSESADD = "/Ioteca/curses/add";
	const IOTECACURSESDELETE = "/Ioteca/curses/delete";
	const IOTECACURSESUPDATE = "/Ioteca/curses/update";
	const IOTECACURSESINDEX = "/Ioteca/curses/index";
	const IOTECABOOKSADD = "/Ioteca/books/add";
	const IOTECABOOKSDELETE = "/Ioteca/books/delete";
	const IOTECABOOKSUPDATE = "/Ioteca/books/update";
	const IOTECABOOKSINDEX = "/Ioteca/books/index";
	const INGRESOSCUSTOMERSHISTORYCUSTOMER = "/In/customers/historycustomer";
	const INGRESOSCUSTOMERSREMEMBER = "/In/customers/remember";
	const INGRESOSCUSTOMERSDISCARD = "/In/customers/discard"; 
	const INGRESOSCUSTOMERSENABLE = "/In/customers/enable";
	const INGRESOSCUSTOMERSGETCONTRACT = "/In/customers/getcontract";
	const HORUSCUSTOMERSDETALLEVIEW = "/In/customers/detalleview";
	const HORUSUSERSDETAILS = "/System/users/details";
	const HORUSUSERSCHANGEPASS = "/System/users/changepass";
	const HORUSUSERSDELETEDETAILS = "/System/users/deletedetails";
	const HOMEPREFERENCESGETCATALOG = "/System/preferences/getcatalog";
	const HOMEPREFERENCESUPDATE = "/System/preferences/update";
	const HORUSACTIVITIESACTIVITIESVIEW = "/Horus/activities/activitiesview";
	const INGRESOSINVOICESADDVIEW = "/In/invoices/addview";
	const INGRESOSINVOICESVERVIEW = "/In/invoices/verview";
	const INGRESOSINVOICESDELETEVIEW = "/In/invoices/deleteview";
	const INGRESOSINVOICESUPDATEVIEW = "/In/invoices/updateview";
	const INGRESOSINVOICESPDFVIEW = "/In/invoices/pdfview";
	const INGRESOSCUSTOMERSPROGRESSBAR = "/In/customers/progressbar";
	const INGRESOSCXCABOUTPROJECT = "/In/ingresoscxc/aboutproject/";
	const HORUSUSERSPROJECTBYCOMPANY = "/users/projectbycompany";
	const HORUSINVENTORYGETOBJECTBYTYPE = "/Horus/inventory/getobjectbytype";
	const INGRESOSCXCINVOICES = "/In/cxc/invoices";
	const EGRESOSSUPPLIERINDEX = "/Out/supplier/index";
	const EGRESOSSUPPLIERADD = "/Out/supplier/add";
	const EGRESOSSUPPLIERUPDATE = "/Out/supplier/update";
	const EGRESOSSUPPLIERDELETE = "/Out/supplier/delete";
	const EGRESOSSUPPLIERDETALLE = "/Out/supplier/detalle";
	const EGRESOSSUPPLIERHISTORYSUPPLIER = "/Out/supplier/historysupplier";
	const INGRESOSPROPOSALSINDEX = "/In/proposals/index";
	const INGRESOSSTOCKINDEX  = "/In/stock/index";
	const INGRESOSCRMINDEX = "/In/crm/index";
	const HORUSCXCGETPAYMENTS = "/In/cxc/getpayments";
	const HORUSCXCADDPAYMENT = "/In/cxc/addpayment";
	const INGRESOSSTOCKADD = "/In/stock/add";
	const INGRESOSSTOCKDELETE = "/In/stock/delete";
	const INGRESOSSTOCKUPDATE = "/In/stock/update";
	const EGRESOSPURCHASEORDERINDEX = "/Out/purchaseorder/index";
	const EGRESOSPURCHASEORDERADDORDER = "/Out/purchaseorder/addorder";
	const EGRESOSPURCHASEORDEREDITORDER = "/Out/purchaseorder/editorder";
	const EGRESOSPURCHASEORDERDELETEORDER = "/Out/purchaseorder/deleteorder";
	const INGRESOSSTOCKRELOAD = "/In/stock/reload";
	const INGRESOSSTOCKGETSTOCK = "/In/stock/getstock";
	const INGRESOSSTOCKSETNEWSTOCK = "/In/stock/setnewstock";
	const INGRESOSCRMADD = "/In/crm/add";
	const INGRESOSSTOCKADDCATEGORY	= "/In/stock/addcategory";
	const INGRESOSCRMUPDATE = "/In/crm/update";
	const INGRESOSCRMDELETE = "/In/crm/delete";
	const INGRESOSPROSPECTPROJECTSINDEX = "/In/prospectprojects/index";
	const INGRESOSPROSPECTPROJECTSUPDATE = "/In/prospectprojects/update";
	const INGRESOSPROSPECTPROJECTSADD = "/In/prospectprojects/add";
	const INGRESOSPROSPECTPROJECTSDELETE = "/In/prospectprojects/delete";
	const HORUSDASHBOARDTOPPROJECTS = "/dashboard/topprojects";
	const HORUSDASHBOARDTOPCUSTOMERS = "/dashboard/topcustomers";
	const INGRESOSCATEGORYDELETE = "/In/category/delete";
	const INGRESOSCATEGORYUPDATE = "/In/category/update";
	const HORUSACTIVITIESADDWEEK = "/Horus/activities/addweek";
	const HORUSACTIVITIESSURFPREVWEEK = "/Horus/activities/surfprevweek";
	const HORUSACTIVITIESSURFNEXTWEEK = "/Horus/activities/surfnextweek";
	const HORUSACTIVITIESFILLTABLEBYUSERID = "/Horus/activities/filltablebyuserid";
	const HORUSACTIVITIESGETUSERSWITHACTIVITIES = "/Horus/activities/getuserswithactivities";
	const HORUSPROSPECTPROJECTSCHANGESTOPROJECT = "/Horus/prospectprojects/changestoproject";
	const HORUSWELCOMEGETGRAPHS = "/welcome/getgraphs";
	const HORUSPREFERENCESCHOOSETHEME = "/Horus/preferences/choosetheme";
	const INGRESOSPROJECTSGETPROJECTSBYCUSTOMERID = "/In/projects/getprojectsbycustomerid";
	const HORUSEMPLOYEEINDEX = "/Horus/employee/index";
	const INGRESOSWAREHOUSEINDEX = "/In/warehouse/index";
	const INGRESOSWAREHOUSEORDER = "/In/warehouse/order";
	const INGRESOSWAREHOUSEINVENTORY = "/In/warehouse/inventory";
	const INGRESOSWAREHOUSEARTICLEENTRY = "/In/warehouse/articleentry";
	const INGRESOSCRMDETALLE = "/In/crm/detalle";
	const EGRESOSPURCHASEORDERHISTORY = "/Out/purchaseorder/history";
	const INGRESOSWAREHOUSEHISTORY = "/In/warehouse/history";
	const INGRESOSCATALOGINDEX = "/In/catalog/index";
	const INGRESOSCATALOGFULLVIEWER = "/In/catalog/fullviewer";
	const INGRESOSSALESINDEX = "/In/sales/index";
	const INGRESOSPRICEHiSTORY = "/In/proposals/history";
	const INGRESOSPRICEADDORDER = "/In/proposals/addorder";
	
	const INGRESOSPRICEEDITORDER = "/In/proposals/editorder";
	const INGRESOSPRICEDELETEORDER = "/In/proposals/deleteorder";
	const INGRESOSPRICEEXPORTORDER = "/In/proposals/export";
	const INGRESOSPRICEPDFORDER = "/In/proposals/pdf";
	const INGRESOSPRICEMAILORDER = "/In/proposals/sendmail";
	
	const INGRESOSPRICECREATEPROSP = "/In/prospectprojects/createview";
	const INGRESOSPRICEUPDATEPROSP = "/In/prospectprojects/updateview";
	const INGRESOSPRICEDELETEPROSP =  "/In/prospectprojects/deleteview";
	const INGRESOSPRICECHAGEPROYECTPROSP = "/In/prospectprojects/changestoproject";
	const INGRESOSPRICEADDMEETINGPROSP = "/In/prospectprojects/meetingsindex";
	const INGRESOSPRICESENDMAILPROSP = "/In/prospectprojects/email";
	const INGRESOSPRICEPDFPROSP = "/In/prospectprojects/pdf";
	const HORUSEMPLOYEEADD = "/Horus/employee/add";
}

?>
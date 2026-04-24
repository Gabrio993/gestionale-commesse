<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['dashboard'] = 'dashboard/index';
$route['admin'] = 'admin/index';
$route['admin/utenti'] = 'admin/utenti';
$route['admin/assegna-commesse/(:num)'] = 'admin/assegna_commesse/$1';
$route['admin/salva-assegnazioni/(:num)'] = 'admin/salva_assegnazioni/$1';
$route['admin/reset-password/(:num)'] = 'admin/reset_password/$1';
$route['admin/ore/(:num)'] = 'ore/utente/$1';
$route['superadmin/ore/(:num)'] = 'ore/utente/$1';
$route['superadmin'] = 'superadmin/index';
$route['superadmin/utenti'] = 'superadmin/utenti';
$route['superadmin/ruoli'] = 'superadmin/ruoli';
$route['superadmin/cambia-ruolo'] = 'superadmin/cambia_ruolo';
$route['superadmin/reset-password/(:num)'] = 'admin/reset_password/$1';
$route['clienti'] = 'clienti/index';
$route['clienti/nuovo'] = 'clienti/nuovo';
$route['clienti/salva'] = 'clienti/salva';
$route['commesse'] = 'commesse/index';
$route['commesse/nuova'] = 'commesse/nuova';
$route['commesse/salva'] = 'commesse/salva';
$route['commesse/dettaglio/(:num)'] = 'commesse/dettaglio/$1';
$route['ore/mie/export-excel'] = 'ore/export_mie_excel';
$route['ore/mie'] = 'ore/mie';
$route['ore/utente/(:num)/export-excel'] = 'ore/export_utente_excel/$1';
$route['ore/nuova/(:num)'] = 'ore/nuova/$1';
$route['ore/modifica/(:num)'] = 'ore/modifica/$1';
$route['ore/aggiorna/(:num)'] = 'ore/aggiorna/$1';
$route['ore/elimina/(:num)'] = 'ore/elimina/$1';
$route['ore/salva'] = 'ore/salva';
$route['ore/utente/(:num)'] = 'ore/utente/$1';
$route['auth/cambia-password'] = 'auth/cambia_password';
$route['auth/salva-password'] = 'auth/salva_password';
$route['report'] = 'report/index';
$route['report/utenti'] = 'report/utenti';
$route['report/commesse'] = 'report/commesse';

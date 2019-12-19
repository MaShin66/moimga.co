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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['@(:any)'] = 'team/view/$1'; //moim/@url 로 입장
$route['@(:any)/blog'] = 'team/blog/lists';
$route['@(:any)/blog/lists'] = 'team/blog/lists';
$route['@(:any)/blog/(:num)'] = 'team/blog/view/$1';
$route['@(:any)/blog/upload'] = 'team/blog/upload'; //새로올리기
$route['@(:any)/blog/upload/(:num)'] = 'team/blog/upload'; //수정

/*team/program*/ // upload를 제외하고 정보 보는건 다 일로 온다..

$route['@(:any)/program'] = 'program/lists';
$route['@(:any)/program/lists'] = 'program/lists';
$route['@(:any)/program/lists/(:num)'] = 'program/lists/(:num)';
$route['@(:any)/program/lists/(:num)/q'] = 'program/lists/(:num)/q';
$route['@(:any)/program/(:num)'] = 'program/view/$1';
$route['@(:any)/program/upload'] = 'program/upload';
$route['@(:any)/program/upload/(:num)'] = 'program/upload'; //수정

//일반 program/lists는 상관없음.. 근데 앞에 team url이 붙는 경우에만 search query 에서 team_id찾는다.


//$route['team/(:any)'] = 'moim/info/$1';
//$route['moim/(:any)/view/(:num)'] = 'moim/view/$1/$2';
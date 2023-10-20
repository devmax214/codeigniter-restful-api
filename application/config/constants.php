<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 * ajax fix for session data
 */
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

/*
 * define Auth result code
 */
define('AUTH_NO_FOUND', '000');
define('AUTH_SUCCESS', '001');
define('AUTH_FAIL', '002');
define('AUTH_NOTACTIVE', '003');
define('AUTH_NO_FOUND_NAME', '004');

/*
 * define User status
 */
define('USER_CREATE', 'create');
define('USER_ACTIVE', 'active');
define('USER_DEACTIVE', 'deactive');
define('USER_DELETE', 'delete');

/*
 * define User roles
 */
define('USER_ROLE_COMMON', '0');
define('USER_ROLE_ADMIN', '9');

/*
 * define similar limitation
 */
define('SIMILAR_PERCENT_LIMIT', 0);

/*
 * define record limitation
 */
define('RECENT_ROWS_LIMIT', 100);

$ClothingTypeSpecs = array(
	'all' => array('height', 'weight', 'chest', 'waist', 'hip', /*'cup_size',*/ 'foot', 'neck', 'shoulder', 'arm_length', 'torso_height', 'upper_arm_size', 'belly', 'leg_length', 'thigh', 'calf'),
	'full' => array('height', 'weight', 'chest', 'waist', 'hip'),
	'lower' => array('waist', 'hip', 'leg_length', 'thigh', 'calf'),
	'upper' => array('chest', 'waist', /*'cup_size',*/ 'neck', 'shoulder', 'arm_length', 'torso_height', 'upper_arm_size', 'belly'),
	'shoes' => array('foot')
);

/*
 * define apns mode
 */
define('APNS_MODE_DEVELOPMENT', false);

/*
 * COOKIE key
 */
define('APP_COOKIE', "linkqlo_cookie_141015_");

/* End of file constants.php */
/* Location: ./application/config/constants.php */
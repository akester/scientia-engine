<?php 
/*
 * Scientia - Free Knowledge
 *
 * Copyright (C) 2013 - Andrew Kester
 *
 *    This file is part of Scientia.
 *
 *    Scientia is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    Scientia is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with Scientia.  If not, see <http://www.gnu.org/licenses/>.
 */

/* Script Version */
$version = '0.1ALPHA';

/* Turn on verbose debugging for PHP */
// ini_set('display_errors', '1');

/* 
 * We roll our own HTTP Status code function since not all versions of PHP have
 * the built in header function.
 */
function sendResponseCode($code) {
	/*
	 * Status Codes taken from
	 * http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 */
	$statusCodes = array(
			/* 1XX Informational */
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			/* 2XX Success */
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			208 => 'Already Reported',
			226 => 'IM Used',
			/* 3XX Redirection */
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => 'Switch Proxy',
			307 => 'Temporary Redirect',
			308 => 'Permanent Redirect',
			/* 4XX Client Error */
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entitiy Too Large',
			414 => 'Request URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			419 => 'Authentaction Timeout',
			420 => 'Method Failure',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			425 => 'Unordered Collection',
			426 => 'Upgrade Required',
			428 => 'Precondition Required',
			429 => 'Too Many Requests',
			431 => 'Request Header Fields Too Large',
			444 => 'No Response',
			449 => 'Retry With',
			450 => 'Blocked By Windows Parental Controls',
			451 => 'Unavailable For Legal Reasons',
			494 => 'Request Header Too Large',
			495 => 'Cert Error',
			496 => 'No Cert',
			497 => 'HTTP to HTTPS',
			499 => 'Client Closed Request',
			/* 5XX Server Error */
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			506 => 'Variant Also Negotiates',
			507 => 'Insuffcient Storage',
			508 => 'Loop Detected',
			509 => 'Bandwidth Limit Exceeded',
			510 => 'Not Extended',
			511 => 'Network Authentication Required',
			522 => 'Connection Timed Out',
			598 => 'Network Read Timeout Error',
			599 => 'Network Connect Time Error'
		);
	if (!key_exists($code, $statusCodes))
		$code = 501;
	$responseString = 'HTTP/1.1 ' . $code . ' ' . $statusCodes[$code];
	header($responseString);
}

function sendResponse($code, $data) {
	global $version;
	
	if (empty($code))
		$code = 500;
	sendResponseCode($code);
	
	if (empty($data))
		$data = array(
				'statusMessage' => 'No Reply',
				'statusDescription' => 'No Reply.'
		);
	
	/* Check to see we have at least a code and version in the data */
	if (!array_key_exists('statusCode', $data))
		$data['statusCode'] = $code;
	if (!array_key_exists('apiVersion', $data))
		$data['apiVersion'] = $version;
	
	echo json_encode($data);
	exit ();
}

/* Set up exception handling */
function scientiaExceptionHandler($e) {
	$data = array(
		'statusMessage' => 'Unhandled Exception',
		'statusDescription' => get_class($e) . 
			' --with message-- ' . $e->getMessage()
	);
	sendResponse(500,$data);
}
set_exception_handler('scientiaExceptionHandler');
/* 
 * I won't roll the parsing functions into a class since that's *all* this
 * page will do.
 */
header('Content-type: application/json; charset=utf-8');

/* Check to see we have a command */
$c = $_GET['c'];
if (empty($c))
	sendResponse(400, array(
			'statusMessage' => 'No Command',
			'statusDescription' => 'No command passed.'
	));

/* Include the autoloader */
require_once ('include/autoload.class.php');

/* Setup chars */
$chars = new scientiaSpecialChars();
$chars->storeChars();

/* This array overrides the paths of modules to allow us to use development 
 * paths.
 */
$module = new scientiaModuleCommon();
$paths = array(
		'logic' => '/home/andrew/Projects/scientia-modules/logic',
		'tic-tac-toe' => '/home/andrew/Projects/scientia-modules/tic-tac-toe',
		'meta' => '/home/andrew/Projects/scientia-modules/meta',
		'chess' => '/home/andrew/Projects/scientia-modules/chess'
);
$module->overrideModulePaths($paths);

/* Parse the command */
switch ($c) {
	case 'getModules':
		$modList = $module->getModules();
		ksort($modList);
		$data = array(
				'statusMessage' => 'OK',
				'statusDescription' => 'OK',
				'modules' => $modList
		);
		sendResponse(200, $data);
		break;
	case 'getModuleNames':
		$modList = $module->getModuleNames();
		ksort($modList);
		$data = array(
				'statusMessage' => 'OK',
				'statusDescription' => 'OK',
				'modules' => $modList
		);
		sendResponse(200, $data);
		break;
	case 'getModuleLayout':
		if (empty($_GET['module']))
			sendResponse(400, array(
				'statusMessage' => 'Missing Parameter',
				'statusDescription' => 'Module not found.'
			));
		if (empty($_GET['mode']))
			sendResponse(400, array(
					'statusMessage' => 'Missing Parameter',
					'statusDescription' => 'Mode not found.'
			));
		
		$layout = $module->getModuleLayout($_GET['module'], $_GET['mode']);
		$data = array(
				'statusMessage' => 'OK',
				'statusDescription' => 'OK',
				'layout' => $layout
		);
		sendResponse(200, $data);
		break;
	case 'getSpecialChars':
		$data = array(
				'statusMessage' => 'OK',
				'statusDescription' => 'OK',
				'chars' => $chars->getChars()
		);
		sendResponse(200, $data);
		break;
	case 'testDBConnect':
		$db = new scientiaDB();
		$data = array(
				'statusMessage' => 'OK',
				'statusDescription' => 'OK',
		);
		sendResponse(200, $data);
		break;
	case 'testDBQuery':
		$db = new scientiaDB();
		$db->query('SHOW TABLES');
		$data = array(
				'statusMessage' => 'OK',
				'statusDescription' => 'OK',
		);
		sendResponse(200, $data);
		break;
	default:
		/* Not a valid command. */
		sendResponse(400, array(
				'statusMessage' => 'Invalid Command',
				'statusDescription' => 'Command not recognized.'
		));
		break;
}
?>
<?php

define('RES_AVATAR',    1);
define('RES_TICKET',    2);
define('RES_TICKET_COMMENT', 3);
define('RES_SCREENSHOT', 4);
define('RES_PORTFOLIO',  5);
define('RES_JOB_FILE',  6);

include '../app/Helpers/base.php';

/**
* @author paulz
* @created Mar 11, 2016
*/
function g($key, $default = '')
{
	if ( isset($_REQUEST[$key]) ) 
		return $_REQUEST[$key];

	return $default;
}

class DB
{

	/**
	* Establish database connection
	*
	* @author paulz
	* @created Mar 11, 2016
	*/
	public static function connect()
	{
		$db = get_wawjob_database_setting();

		if ( !mysql_connect($db['host'], $db['username'], $db['password']) ) {
			return false;
		}

		if ( !mysql_select_db($db['database']) ) {
			return false;
		}

		return true;
	}

	public static function find($table, $value, $column = 'id')
	{
		if (is_string($value)) {
			$value = mysql_real_escape_string($value);
		}

		$query = "SELECT * FROM `$table` WHERE `$column` = '$value'";

		$r = mysql_query($query);
		if ( !$r )  {
			return false;
		}

		return mysql_fetch_assoc($r);
	}
}


function parseResource()
{
	$type = '';
	$action = g("action");
	$path = '';

	switch ($action) {
		case RES_AVATAR:  // Avatar
		case RES_PORTFOLIO:  // Portfolio
			$type = 'image';
			$name = g("name");
			$size = ltrim(g("size"), "/");

			if ( !$name ) {
				return false;
			}

			$r = DB::find('users', $name, 'username');
			if ( !$r )  {
				return false;
			}

			$user_id = $r['id'];
			$user = new stdClass;
			$user->id = $user_id;
			$user->username = $name;

			// seek default if not found
			if ($action == RES_AVATAR) {
				$is_temp = g("is_temp", 0);
				$path = avatarPath($user, $size, $is_temp, true);
			} else {
				$pt_id = g('pt_id', 0);
				$path = portfolioPath($user_id, $pt_id, $size, true);
			}

			$image_type = exif_imagetype($path);
			$mime_type = image_type_to_mime_type($image_type);
			break;

		case RES_TICKET:
		case RES_TICKET_COMMENT:
			$id = g("id");
			$name = g("name");
			if ( !$id ) {
				return false;
			}

			if ($action == RES_TICKET) {
				$type = 'ticket';
				$dir = getTicketUploadDir($id);
			} else {
				$type = 'tcomment';
			  // Retrieve ticket ID from ticket comment ID
				$r = DB::find('ticket_comments', $id);
				if ( !$r )  {
					return false;
				}

				$tid = $r['ticket_id'];

				$dir = getTicketCommentUploadDir($tid, $id);
			}

			$path = $dir."/".$name;
			$mime_type = getMimeType($path);
			break;

		case RES_SCREENSHOT:
			$cid = g("cid");
			$datetime = g("datetime");
			if (!$cid || !$datetime) {
				error_log("RES_SCREENSHOT: Invalid contract ID or datetime.");
				return false;
			}

			if (substr($datetime, -2) == "_s") {
				$type = "thumbnail";
				$datetime = rtrim($datetime, "_s");
			} else {
				$type = "full";
			}

			$path = getScreenshotPath($cid, $datetime, $type);

			if ( !file_exists($path) ) {
				return false;
			}

			$mime_type = "image/jpeg";

			break;

		case RES_JOB_FILE:
			$id = intval( g('id') );
			$hash = g('name');

			$r = DB::find('files', $id);
			if ( !$r ) {
				return false;
			}

			if ( $r['hash'] != $hash ) {
				return false;
			}

			$path = $r['path'] . $r['name'];

			if ( !file_exists($path) ) {
				return false;
			}

			$mime_type = $r['mime_type'];

			break;

		default:
			// Unknown
	}

	if (!$path) {
		return false;
	}

	if ( empty($mime_type) ) {
		echo "Invalid MIME type.";
		return false;
	}

	header('Cache-Control: max-age=86400');
	header('Content-type: '. $mime_type);
	header('Content-Length: ' . filesize($path));
	readfile($path);

	return false;
}


if ( !DB::connect() ) {
	error_log("Could not connect to the DB.");
	exit;
}

parseResource();
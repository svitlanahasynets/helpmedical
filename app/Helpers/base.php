<?php
/**
* @author paulz
* @created Mar 8, 2016
*/

if ( !defined('DS') ) {
	define('DS', DIRECTORY_SEPARATOR);
}

// freelancer connects Reset Cycle Days
if ( !defined('CONNECTS_RESET_CYCLE_DAY') ) {
	define('CONNECTS_RESET_CYCLE_DAY', 7);
}

// freelancer Reset connects numbers
if ( !defined('CONNECTS_RESET_NUMBERS') ) {
	define('CONNECTS_RESET_NUMBERS', 20);
}

// Static $50
// Earn $50 when affiliated buyer makes the first deposit
if ( !defined('AFFILIATE_BUYER_FEE') ) {
	define('AFFILIATE_BUYER_FEE', 50);
}

if ( !defined('AFFILIATE_CHILD_BUYER_FEE') ) {
	define('AFFILIATE_CHILD_BUYER_FEE', 5);
}

// 0.01%
// Earn 0.01% of the funds that affiliated freelancer gets
if ( !defined('AFFILIATE_FREELANCER_FEE') ) {
	define('AFFILIATE_FREELANCER_FEE', 0.01);
}

if ( !defined('AFFILIATE_CHILD_FREELANCER_FEE') ) {
	define('AFFILIATE_CHILD_FREELANCER_FEE', 0.001);
}

if ( !defined('SUPERADMIN_ID') ) {
	define('SUPERADMIN_ID', 1);
}

// Deduct $1 when freelancers withdraw
if ( !defined('WITHDRAW_FEE') ) {
	define('WITHDRAW_FEE', 1);
}

// Min withdraw amount
if ( !defined('MIN_WITHDRAW_AMOUNT') ) {
	define('MIN_WITHDRAW_AMOUNT', 1.1);
}

// Max withdraw amount
if ( !defined('MAX_WITHDRAW_AMOUNT') ) {
	define('MAX_WITHDRAW_AMOUNT', 10000);
}

// Max milestone amount
if ( !defined('MAX_MILESTONE_AMOUNT') ) {
	define('MAX_MILESTONE_AMOUNT', 9999999);
}

// Max fixed price
if ( !defined('MAX_FIXED_PRICE') ) {
	define('MAX_FIXED_PRICE', 9999999);
}

// Max hourly price
if ( !defined('MAX_HOURLY_PRICE') ) {
	define('MAX_HOURLY_PRICE', 999);
}

if ( !function_exists('pr') ) {
	function pr($obj) {
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
	}
}


if ( !function_exists('get_wawjob_database_setting')) {
	function get_wawjob_database_setting()
	{
		$path = dirname(dirname(dirname(__FILE__))).DS."config".DS."database.ini";

		$ini = parse_ini_file($path, true);
		$active = $ini['env']['active'];
		if ( !isset($ini[$active]) ) {
			exit;
		}

		return $ini[$active];
	}
}

if ( !function_exists('isWindows') ) {
	function isWindows()
	{
		return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
	}
}

if ( !function_exists('getRoot') ) {
	function getRoot()
	{
		$root = dirname(dirname(dirname(__FILE__)));
		return $root;
	}
}

if ( !function_exists('getMimeType') ) {
	function getMimeType($filename)
	{
		preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

		switch(strtolower($fileSuffix[1]))
		{
			case "js" :
			return "application/x-javascript";

			case "json" :
			return "application/json";

			case "jpg" :
			case "jpeg" :
			case "jpe" :
			return "image/jpg";

			case "png" :
			case "gif" :
			case "bmp" :
			case "tiff" :
			return "image/".strtolower($fileSuffix[1]);

			case "css" :
			return "text/css";

			case "xml" :
			return "application/xml";

			case "doc" :
			case "docx" :
			return "application/msword";

			case "xls" :
			case "xlsx" :
			case "xlt" :
			case "xlm" :
			case "xld" :
			case "xla" :
			case "xlc" :
			case "xlw" :
			case "xll" :
			return "application/vnd.ms-excel";

			case "ppt" :
			case "pps" :
			return "application/vnd.ms-powerpoint";

			case "rtf" :
			return "application/rtf";

			case "pdf" :
			return "application/pdf";

			case "html" :
			case "htm" :
			case "php" :
			return "text/html";

			case "txt" :
			return "text/plain";

			case "mpeg" :
			case "mpg" :
			case "mpe" :
			return "video/mpeg";

			case "mp3" :
			return "audio/mpeg3";

			case "wav" :
			return "audio/wav";

			case "aiff" :
			case "aif" :
			return "audio/aiff";

			case "avi" :
			return "video/msvideo";

			case "wmv" :
			return "video/x-ms-wmv";

			case "mov" :
			return "video/quicktime";

			case "zip" :
			return "application/zip";

			case "tar" :
			return "application/x-tar";

			case "swf" :
			return "application/x-shockwave-flash";

			default :

		}

		return "application/octet-stream";
	}
}


if ( !function_exists('getUploadPrefix') ) {
    /**
    * Generate two-level subdirectory where each level has 2000 directories to hold plengty of
    * files with quick access speed.
    *
    * 20001 => 0/10
    */
    function getUploadPrefix($idv)
    {
    	$deep1 = floor($idv / (2000 * 2000));
    	$deep2 = floor(($idv - ($deep1 * (2000 * 2000))) / 2000);

    	return $deep1 . '/' . $deep2;
    }
}


if ( !function_exists('getUploadDir')) {
  /**
  * Get full path to the upload directory for given type
  *
  */
  	function getUploadDir($schedule_id) {
	    $dir = dirname(dirname(dirname(__FILE__))) . "/uploads";
	    $dir .= "/schedule/$schedule_id/";
	    
	    return $dir;
	}
}

if ( !function_exists('getTicketUploadDir')) {
  /**
  * Get full path to the upload directory for Ticket
  *
  * Assume Ticket ID = 20001
  * Ticket path:           D:\projects\www.wawjob.com\uploads\ticket\0\2000\20001
  */
  function getTicketUploadDir($t_id)
  {
  	return getUploadDir($t_id, 'ticket');
  }
}

if ( !function_exists('getTicketCommentUploadDir')) {
  /**
  * Get full path to the upload directory for Ticket Comment
  */
  function getTicketCommentUploadDir($t_id, $tc_id)
  {
  	$root = getRoot();
  	$prefix = getUploadPrefix($t_id);

  	$dir = $root . "/uploads/ticket/" . $prefix . "/" . $t_id . "/" . $tc_id . "/";

  	return $dir;
  }
}

/* Mar 16, 2016 - paulz */
if ( !function_exists('getScreenshotPath')) {
  /**
  * Get full path to the upload directory for Work diary screenshot
  *
  * @param integer $cid: Contract ID
  * @param string $datetime: YYYYMMDDHHmm
  * @param string $type: full | thumbnail | thumbnail_path
  *       `thumbnail_path` returns thumbnail path
  *       `thumbnail` returns full path when thumbnail is not found
  *       `array` returns path, filename and thumbnamil filename
  * @return mixed
  */
  function getScreenshotPath($cid, $datetime, $type = 'full')
  {
  	$root = getRoot();
  	$prefix = getUploadPrefix($cid);

  	$date = substr($datetime, 0, 8);
  	$hm = substr($datetime, 8, 4);

  	$slug = "$root/uploads/ss/$prefix/$cid/$date/";
  	$filename = "$hm.jpg";
  	$thumb_filename = "${hm}_s.jpg";
  	$path_full = $slug . $filename;
  	$path_thumb = $slug . $thumb_filename;

  	if ($type == 'thumbnail_path') {
  		$path = $path_thumb;
  	} else if ($type == 'thumbnail') {
  		if (file_exists($path_thumb)) {
  			$path = $path_thumb;
  		} else {
  			$path = $path_full;
  		}
  	} else if ($type == 'array') {
  		$path = [
  		'path' => $slug,
  		'filename' => $filename,
  		'thumb_filename' => $thumb_filename,
  		];
  	} else {
  		$path = $path_full;
  	}

  	return $path;
  }
}

/**
 * Create directory.
 *
 * @param  string $path The path string to create directory.
 * @return boolean
 */
if ( !function_exists("createDir") ) {
	function createDir($path)
	{
		$old_umask = umask(0);
		if ( !is_dir($path) ) {
			if ( !mkdir($path, 0777, true) ) {
				return false;
			}
		}
		umask($old_umask);

		return true;
	}
}


/**
* Recursively remove a directory when it is not empty
*
* @author paulz
* @created Mar 9, 2016
*/
if ( !function_exists("rrmdir") ) {
	function rrmdir($dir) {
		if (!is_dir($dir)) {
			return false;
		}

		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}

		reset($objects);
		rmdir($dir);
	}
}

if ( !function_exists("removeDir") ) {
	function removeDir($dir) {
    //if (isWindows()) {
		return rrmdir($dir);
    //} else {
      // # remove dir by command "rm -rf [DIR]"
    //}
	}
}

/**
 * Get FontAwesome Icon Class from filename.
 *
 * @param  string $filename
 * @return string
 */
if ( !function_exists("getFileIconClass") ) {
	function getFileIconClass($filename)
	{
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$ext = strtolower($ext);

		switch ($ext) {
        // Code
			case "c": case "cpp": case "rb":
			$c = 'fa-file-code-o';
			break;

        // Word
			case "doc": case "docx":
			$c = 'fa-file-word-o';
			break;

        // Excel
			case "xls": case "xlsx":
			$c = 'fa-file-excel-o';
			break;

        // PowerPoint
			case "ppt": case "pptx":
			$c = 'fa-file-powerpoint-o';
			break;

        // PDF
			case "pdf":
			$c = 'fa-file-pdf-o';
			break;

        // Image
			case "jpg": case "png": case "bmp":
			case "jpeg": case "gif": case "psd":
			$c = 'fa-file-image-o';
			break;

        // Audio
			case "mp3": case "wma": case "wav":
			$c = 'fa-file-audio-o';
			break;

        // Video
			case "mp4": case "mpg": case "avi":
			case "vob":
            //$c = 'fa-file-video-o';
			$c = 'fa-video-camera';
			break;

        // Text
			case "txt": case "log":
			$c = 'fa-file-text-o';
			break;

        // Zip
			case "zip": case "7z": case "rar":
			$c = 'fa-file-zip-o';
			break;

			default:
			$c = 'fa-file-archive-o';
		}

		return $c;
	}
}


//////////////////////////////////////////////////////////////
if ( !function_exists('encodeChars') ) {
	function encodeChars($string) {
		return htmlentities($string);
	}
}

if ( !function_exists('validateEmail') ) {
	function validateEmail($email) {
		if ( !trim($email) ) {
			return false;
		}

		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			return false;
		}

		return true;
	}
}

if ( !function_exists('round2Decimal') ) {
	function round2Decimal($val) {
		return round((floatval($val) * 100) / 100, 2);
	}
}

/**
* Format currency like the following.
* $1000 to $1k
* @author Ro Un Nam
* @since May 21, 2017
*/
if ( !function_exists('formatEarned') ) {
	function formatEarned($val)
	{
		if ( $val < 100 ) {
			return $val;
		}
		
		return number_format($val / 1000, 1, '.', ',') . 'k';
	}
}

/* Mar 16, 2016 - Ri Chol Min */
if ( !function_exists('formatCurrency') )
{
	function formatCurrency($val)
	{
		$val = ($val * 100) / 100;
		return number_format($val, 2, '.', ',');
	}
}

/* Apr 08, 2016 - Nada */
if ( !function_exists('priceRaw') )
{
	function priceRaw($amount)
	{
		$amount = str_replace(",", "", $amount);
		$amount = floatval($amount);
		$amount = round($amount, 2);

		return $amount;
	}
}

/* Mar 2, 2016 - paulz */
if ( !function_exists("siteProtocol") ) {
	function siteProtocol()
	{
		if ( (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 || strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, 5)) == 'https' ) {
			$is_https = true;
		} else {
			$is_https = false;
		}

        //for CURL from mobile/ curl calls
		if (isset($_REQUEST['is_secure']))
		{
			$is_https = true;
		}
		return $is_https ? "https" : "http";
	}
}

/* Mar 2, 2016 - paulz */
if ( !function_exists("get_site_url") ) {
	function siteUrl($protocol = '')
	{
		if (!$protocol) {
			$protocol = siteProtocol();
		}

		return $protocol."://".$_SERVER['HTTP_HOST'];
	}
}


/**
* @author paulz
* @created Apr 2, 2016
* @param object | int $user: User object or user_id
* @param boolean $is_temp: Whether this is temp file
* @param int $size: Avatar size [optional]
* @param boolean $Is_seek_default: Return default image when not found [optional]
*
* @return Full filepath to avatar of given size
*/
if ( !function_exists('avatarPath') ) {
	function avatarPath($user, $size = '', $is_temp = false, $is_seek_default = false)
	{
		if ( !$user ) {
			return '';
		}

		if (is_object($user)) {
			$user_id = $user->id;
		} else {
			$user_id = $user;
		}

		$size = intval($size);
		if ($is_temp) {
			$dir = getUploadDir($user_id, "tmp");
			createDir($dir);
			$path = $dir . "avatar.png";
		} else {
			$dir = getUploadDir($user_id, "avatar");
			createDir($dir);
			$path = $dir . ($size ? "${user_id}_$size.png" : "${user_id}.png");
		}

		if (file_exists($path)) {
            // ok, I found the exact image what I am looking for
			return $path;
		}
		
		if ( !$is_temp && $is_seek_default) {
			if ($size) {
                // default size avatar of this user
				$path = $dir . "$user_id.png";
				if (file_exists($path) ) {
					return $path;
				}
			}
			
            // global default avatar
			$path = getRoot() . "/public/assets/images/default/avatar.png";
		}

		return $path;
	}
}

/**
* Returns URL or avatar (or temp avatar) image for given user_id and size
*
* @author paulz
* @created Mar 11, 2016
*
* @param  $is_url: TRUE = URL, FALSE = Full file path
*/
if ( !function_exists('avatarUrl') ) {
	function avatarUrl($user, $size = '', $is_temp = false)
	{
		if ( !$user ){
			return '';
		}
		$size = intval($size);

		if (is_object($user)) {
			$user_id = $user->id;
			$username = $user->username;
		} else {
			$user_id = $user["id"];
			$username = $user["username"];
		}

		$path = avatarPath($user_id, $size, $is_temp, true);
		if (!$path) {
			return '';
		}

		$url = siteUrl();
		if ($is_temp) {
			$url .= "/avatar_temp/$username";
		} else {
			$url .= "/avatar/$username";
		}

		if ($size) {
			$url .= "/$size";
		}

		$url .= "?fm=".filemtime($path);

		return $url;
	}
}


/**
* @author paulz
* @created Apr 18, 2016
* @param int $user_id: User ID
* @param int $pt_id: Portfolio ID (temp image when this = 0)
* @param int $size: Avatar size [optional]
* @param boolean $Is_seek_default: Return default image when not found [optional]
*
* @return Full filepath to portfolio screenshot of given size
*/
if ( !function_exists('portfolioPath') ) {
	function portfolioPath($user_id, $pt_id, $size = '', $is_seek_default = false)
	{
		if ( !$user_id ) {
			return '';
		}

		$size = intval($size);
		if ($pt_id == 0) {
			$dir = getUploadDir($user_id, "tmp");
			createDir($dir);
			$path = $dir . "portfolio.jpg";
		} else {
			$dir = getUploadDir($user_id, "portfolio");
			createDir($dir);
			$path = $dir . ($size ? "${pt_id}_$size.jpg" : "${pt_id}.jpg");
		}

		if ( file_exists($path) ) {
            // great! this is just want I am looking for
			return $path;
		}

		if ($pt_id > 0 && $is_seek_default) {
            // If I was looking for custom size portfolio image, then try default size portfolio image
			if ($size) {
				$path = $dir . "$pt_id.jpg";
				if ( file_exists($path) ) {
					return $path;
				}
			}
			
            // oh, I could not find image for this portfolio, just return no_background (transparent) image
			$path = getRoot() . "/public/assets/images/default/no_bg.png";
		}

		return $path;
	}
}

/**
* Returns image URL for portfolio or portfolio_temp for given user_id, pt_id and size
*
* @author paulz
* @created Apr 18, 2016
*
* e.g:
*    $user = new \stdClass;
*    $user->id = 2;
*    $user->username = 'jin';
*
*    echo portfolioUrl($user, 1);
*/
if ( !function_exists('portfolioUrl') ) {
	function portfolioUrl($user, $pt_id, $size = '')
	{
		if ( !$user ) {
			return '';
		}

		$user_id = $user->id;
		$username = $user->username;
		
		$pt_id = intval($pt_id);
		$path = portfolioPath($user_id, $pt_id, $size);
		if (!$path) {
			return '';
		}

		$url = "/portfolio/$username/$pt_id";
		if ($pt_id > 0 && $size) {
			$url .= "/$size";
		}
		if (file_exists($path)) {
			$url .= "?fm=".filemtime($path);    
		}else {
			$url = "";
		}
		

		return $url;
	}
}


/**
* Similar to avatarUrl(), returns custom resource URL
*
* @author  paulz
* @created Mar 11, 2016
* @updated Mar 16, 2016 - added screenshot
*/
if ( !function_exists('resouceUrl') ) {
	function resourceUrl()
	{
		$args = func_get_args();
		$type = $args[0];
		$url = '';

		switch ($type) {
			case "ticket":
			case "tcomment":
			$id = $args[1];
			$filename = $args[2];
			$url = "/res/$type/$id/$filename";
			break;

        // Screenshot
			case "ss":
            $cid = $args[1]; // Contract ID
            $datetime = $args[2]; // YYYYMMDDHHmm: e.g: 201603160734
            $url = "/res/ss/$cid/$datetime";
            $type = $args[3];
            if ($type == "thumbnail") {
            	$url .= '_s';
            }
            break;

            default:
        }

        return $url;
    }
}

/**
 * @author Ro Un Nam
 * @since May 17, 2017
 * Generate the file name automatically by checking duplicated name in the directory
 */
if ( !function_exists('generateFileName') ) {
	function generateFileName($dir, $original_name, $new_name = '', $index = 0) {
		$original_name = str_replace(' ', '_', $original_name);
		if ( !$new_name ) {
			$new_name = $original_name;
		}

		$fullpath = $dir . '/' . $new_name;
		if ( !file_exists( $fullpath ) ) {
			return $new_name;
		}

		$index++;
		$fileinfo = pathinfo($original_name);
		$new_name = $fileinfo['filename'] . '_' . $index . '.' . $fileinfo['extension'];

		return generateFileName($dir, $original_name, $new_name, $index);
	}
}

/**
 * @author KCG
 * @since July 13, 2017
 */
if ( !function_exists('getRedirectByRole') ) {
	function getRedirectByRole($user) {
		$redirect = 'admin.user.logout';

		if ($user->isTicket())
			$redirect = 'admin.ticket.dashboard';
		
		if ($user->isSuper())
			$redirect = 'admin.super.dashboard';

		return $redirect;
	}
}

/**
* array_concat :: in some cases, array_merge does not work as expected...
*
* @param
*   $dv: datetime value in string format
*/
if (!function_exists('array_concat')) {    
    function array_concat($arr1, $arr2) {
        $arr = [];

        foreach($arr1 AS $k => $v) {
            $arr[$k] = $v;
        }

        foreach($arr2 AS $k => $v) {
            $arr[$k] = $v;
        }

        return $arr;
    }
}

if (!function_exists('get_sanctuary_keys')) {
    function get_sanctuary_keys() {
        return ['김일성', '김정일', '김정은'];
    }
}

if (!function_exists('make_sanctuary')) {
    function make_sanctuary($str) {
        $sanctuary = get_sanctuary_keys();
        foreach($sanctuary AS $s) {
            $str = str_replace($s, '<span class="sanctuary">' . $s . '</span>', $str);
        }
        return $str;
    }
}

if (!function_exists('detect_sanctuary')) {
    function detect_sanctuary($str) {
        $sanctuary = get_sanctuary_keys();
        $offset = 0;
        $poss = [];

        foreach ($sanctuary AS $s) {
            while (!(mb_strpos($str, $s, $offset) === false)) {
                $pos = mb_strpos($str, $s, $offset);
                $dp = detect_block($str, $pos);   
                $poss[] = $dp;

                $offset = $dp[1];
            }
        }

        return $poss;
    }
}

if (!function_exists('detect_block')) {
    function detect_block($str, $pos) {

        $pos_from = 0;
        $pos_to = 0;

        $oc_pos = $pos;
        $oc_count = 0;
        while (($oc_count < 3) && ($oc_pos >= 0)) {

            if (in_array(mb_substr($str, $oc_pos, 1), [' ', ','])) {
                $oc_count++;
            }
            
            if (in_array(mb_substr($str, $oc_pos, 1), ['.', '《', '》'])) {
                $oc_count = 3;
            }

            $oc_pos--;
        }
        $pos_from = $oc_pos + 1;

        $oc_pos = $pos;
        $oc_count = 0;
        while (($oc_count < 2) && ($oc_pos <= mb_strlen($str))) {
            if (in_array(mb_substr($str, $oc_pos, 1), [' ', ','])) {
                $oc_count++;
            }
            
            if (in_array(mb_substr($str, $oc_pos, 1), ['.', '《', '》'])) {
                $oc_count = 2;
            }
            $oc_pos++;
        }
        $pos_to = $oc_pos - 1;

        return [$pos_from, $pos_to, $pos];
    }
}

if (!function_exists('get_ext')) {    
    function get_ext($file_name) {  // 수동적으로  확장자를 얻는다 !(pathinfo함수를 리용해서도 얻을수 있다!)
        $ext_array = explode(".", $file_name);
        $ext = "";
        if (count($ext_array) > 1) {
            $ext = $ext_array[count($ext_array) - 1];
        }
        return $ext;
    }
}

if ( !function_exists("root_path") ) {
    function root_path() {
        $path = dirname(dirname(dirname(__FILE__))) . '/public';
        $path = str_replace('\\', '/', $path);
        return $path;
    }
}

/**
* Make seconds readable
*
* @param
*   $sec: number of seconds
*   $cut: sec, min, hr, days, etc, until where? if it s 0, then full
*   $full: shortform or full form
*   $with_now: if >0, then shows Just Now label for those less than this, if =0, then never
*/
if (!function_exists('make_period_readable')) {    
    function make_period_readable($sec, $cut=3, $full=false, $with_now = 0) {

        if (($with_now > 0) && ($sec <= $with_now)) {
          return '조금전에';
        }

        $diffset = [
        ];

        $diffset_labels = [
          '초',
          '분',
          '시간',
          '일',
          '달',
          '년',
        ];

        $diffset_labels_full = [
          '초',
          '분',
          '시간',
          '일',
          '달',
          '년',
        ];

        $diffset_rate = [
          60,
          60,
          24,
          30,
          12,
        ];

        $a = 0;
        $b = 0;
        $ind = 0;
        do {
          $a = intval($sec / $diffset_rate[$ind]);
          $b = $sec % $diffset_rate[$ind];
          $diffset[] = $b;
          $sec = $a;
          $ind ++;
        } while (($a > 0) || ($ind < 4));
        if ($a > 0) $diffset[] = $a;

        $tks = [];
        for ($i = count($diffset) - 1; $i >= 0; $i--) {
          if ($diffset[$i] > 0) {
            $tks[] = $diffset[$i] . '' . ($full?$diffset_labels_full[$i]:$diffset_labels[$i]);
          }
        }

        $gone = '';
        if ($cut == 0) {
          $gone = implode(' ', $tks);
        } else {
          $gone = implode(' ', array_slice($tks, 0, $cut));   
        }

        return $gone. '전';
    }
}

/**
* Make date/time readable
*
* @param
*   $dv: datetime value in string format
*/
if (!function_exists('make_datetime_readable')) {    
    function make_datetime_readable($dv) {
        $now = time();
        $pc = strtotime($dv);
        $diffs = ($now - $pc);

        if ($diffs < (3600*12 + 1)) {
            return make_period_readable($diffs, 3, false, 5);
        } else {
            if (date('Y', $now) == date('Y', $pc)) {
                return date('n월 j일 H:m', $pc);
            } else {
                return date('Y년 n월 j일 H:m', $pc);
            }
        }
    }
}

if ( !function_exists("get_ext_type") ) {
    function get_ext_type($ext) {
        $ext_types = [
            'png' => 'image',
            'jpg' => 'image',
            'jpeg' => 'image',
            'gif' => 'image',
            
            'pdf' => 'pdf',

            'zip' => 'download',
            'rar' => 'download',
            '7zip' => 'download',

            'wav' => 'audio',
            'mp3' => 'audio',
            'mid' => 'audio',
            'au' => 'audio',

            'php' => 'read',
            'js' => 'read',
            'css' => 'read',

            'xlsx' => 'download',

            'ppt' => 'download',

            'avi' => 'video',
            'flv' => 'video',

            'doc' => 'download',
            'docx' => 'download',
        ];

        return array_key_exists($ext, $ext_types)?$ext_types[$ext]:'download';
    }
}

if ( !function_exists("get_ext_icons") ) {
    function get_ext_icons() {
        return [
            'png' => '<i class="fa fa-file-photo-o"></i>',
            'jpg' => '<i class="fa fa-file-photo-o"></i>',
            'jpeg' => '<i class="fa fa-file-photo-o"></i>',
            'gif' => '<i class="fa fa-file-photo-o"></i>',
            
            'pdf' => '<i class="fa fa-file-pdf-o"></i>',

            'zip' => '<i class="fa fa-file-archive-o"></i>',
            'rar' => '<i class="fa fa-file-archive-o"></i>',
            '7zip' => '<i class="fa fa-file-archive-o"></i>',

            'wav' => '<i class="fa fa-file-audio-o"></i>',
            'mp3' => '<i class="fa fa-file-audio-o"></i>',
            'mid' => '<i class="fa fa-file-audio-o"></i>',
            'au' => '<i class="fa fa-file-audio-o"></i>',

            // 'php' => '<i class="fa fa-file-code-o"></i>',
            'js' => '<i class="fa fa-file-code-o"></i>',
            'css' => '<i class="fa fa-file-code-o"></i>',

            'xlsx' => '<i class="fa fa-file-excel-o"></i>',

            'ppt' => '<i class="fa fa-file-powerpoint-o"></i>',

            'flv' => '<i class="fa fa-file-video-o"></i>',
            'avi' => '<i class="fa fa-file-video-o"></i>',

            'doc' => '<i class="fa fa-file-word-o"></i>',
            'docx' => '<i class="fa fa-file-word-o"></i>',
        ];
    }
}

if ( !function_exists("is_known_ext") ) {
    function is_known_ext($ext) {
        return in_array($ext, array_keys(get_ext_icons()));
    }
}

if ( !function_exists("get_ext_icon") ) {
    function get_ext_icon($ext) {
        $icons = get_ext_icons();
        if (is_known_ext($ext)) {
            return $icons[$ext];
        } else {
            return '<img src="' . img_url('f-ext/unknown.png') . '" />';
        }
    }
}

if ( !function_exists("js_url") ) {
    function js_url($filename = "") {
        return siteProtocol() . "://" . $_SERVER['HTTP_HOST']
            . "/assets/scripts/pages/buyer/" . $filename;
    }
}

if ( !function_exists("img_url") ) {
    function img_url($filename = "") {
        return siteProtocol() . "://" . $_SERVER['HTTP_HOST']
            . "/assets/images/pages/board/" . $filename;
    }
}

if (!function_exists('hilight')) {    
    function hilight($haystack, $needle, $span=10) {

        $haystack = trim($haystack);
        if ($needle == '') return make_sanctuary($haystack);

        $cts = '...';
        if ($span == 0) return str_replace($needle, '<i class="hilight">'.$needle.'</i>', make_sanctuary($haystack));
        // if ($span == 0) $cts = '';

        $poss = detect_sanctuary($haystack);

        $set = '';
        $offset = 0;
        
        while (!(mb_strpos($haystack, $needle, $offset) === false)) {
            
            $pos = mb_strpos($haystack, $needle, $offset);
            $prevPos = max(0, $pos-$span);
            $nextPos = min($pos + mb_strlen($needle) + $span, mb_strlen($haystack));

            // let's check if it s in santuary section
            $pos_is_found = false;
            foreach($poss AS $limit_p) {
                if (($pos >= $limit_p[0]) && ($pos <= $limit_p[1])) {
                    $prevPos = $limit_p[0];
                    $nextPos = $limit_p[1];
                    $pos_is_found = true;
                    break;
                }
            }
            if (!$pos_is_found) {
                foreach($poss AS $limit_p) {
                    if (($prevPos >= $limit_p[0]) && ($prevPos <= $limit_p[1])) {
                        $prevPos = $limit_p[1];
                        if ($prevPos > $pos) $prevPos = $limit_p[0];
                    }

                    if (($nextPos >= $limit_p[0]) && ($nextPos <= $limit_p[1])) {
                        $nextPos = $limit_p[0];
                        if ($nextPos < $pos + mb_strlen($needle)) $nextPos = $limit_p[1];
                    }
                }
            }
            


            if ($offset > $prevPos) {
                $prevPos = $offset;
            } else {
                $set .= $cts;                
            }
            $offset = $nextPos;

            $set .= mb_substr($haystack, $prevPos, $pos-$prevPos) . $needle;
            $set .= mb_substr($haystack, $pos + mb_strlen($needle), $nextPos - ($pos + mb_strlen($needle))); // . (($offset<mb_strlen($haystack))?'...':'');
        }

        if (($set != '') && ($offset < mb_strlen($haystack))) $set .= '...';

        return str_replace($needle, '<i class="hilight">'.$needle.'</i>', make_sanctuary($set));
    }
}
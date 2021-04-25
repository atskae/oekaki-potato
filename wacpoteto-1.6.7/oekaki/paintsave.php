<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-07-28
*/


function verify_edit($testnum, $hash = '') {
	// Need DB, username, password
	global $dbconn, $db_p, $db_mem, $OekakiU, $OekakiPass;

	$result = db_query("SELECT usrname, password FROM {$db_p}oekakidta WHERE PIC_ID='$testnum'");
	$row = db_fetch_array($result);
	$result2 = db_query("SELECT usrpass FROM {$db_mem}oekaki WHERE usrname='$OekakiU'");
	$row2 = db_fetch_array($result2);

	if ($OekakiU != $row['usrname'] || $OekakiPass != $row2['usrpass']) {
		// Not artist pic.  Edit w/password?
		// NOTE: Password allowed to be empty
		if (w_transmission_verify($row['password'], $hash)) {
			return $testnum;
		} else {
			return 0;
		}
	} else {
		// Artist pic
		return $testnum;
	}
}


function get_new_picnumber() {
	// Need DB
	global $dbconn, $db_p;

	// Get piccount
	$result = db_query("UPDATE {$db_p}oekakimisc SET miscvalue=(miscvalue + 1) WHERE miscname='piccount'");
	$result = db_query("SELECT miscvalue FROM {$db_p}oekakimisc WHERE miscname='piccount'");

	return db_result($result, 0);
}


function kill_post_files($resno) {
	// Need DB, names
	global $dbconn, $db_p;
	global $p_pic, $r_pic, $t_pic;

	$kpf_result = db_query("SELECT datatype, animation, ttype, ptype, rtype FROM {$db_p}oekakidta WHERE PIC_ID='{$resno}'");
	if (!$kpf_result) {
		return false;
	}
	$row = db_fetch_array($kpf_result);

	// Remove animation
	if ($row['animation'] == 1) {
		if ($row['datatype'] == 0 || $row['datatype'] == 2 || $row['datatype'] == 3) {
			file_unlock($p_pic.$resno.'.pch');
		} elseif ($row['datatype'] == 1) {
			file_unlock($p_pic.$resno.'.oeb');
		} elseif ($row['datatype'] == 4 || $row['datatype'] == 5) {
			file_unlock($p_pic.$resno.'.chi');
		}
	}

	// Remove images
	if (!empty($row['ptype'])) {
		file_unlock($p_pic.$resno.'.'.$row['ptype']);
	}
	if (!empty($row['ttype'])) {
		file_unlock($t_pic.$resno.'.'.$row['ttype']);
	}
	if (!empty($row['rtype'])) {
		file_unlock($r_pic.$resno.'.'.$row['rtype']);
	}
	return true;
}


function kill_picture_slot($resno) {
	// Need DB, names
	global $dbconn, $db_p;
	global $p_pic, $r_pic, $t_pic;

	// Remove all post files
	kill_post_files($resno);

	// Now delete the DB entries
	$result = db_query("DELETE FROM {$db_p}oekakicmt WHERE PIC_ID='{$resno}'");
	$result = db_query("DELETE FROM {$db_p}oekakidta WHERE PIC_ID='{$resno}'");

	return true;
}


function clean_picture_slots($max_slots_to_delete = 30) {
	// This function may be called any time (refresh op)

	// Need CFG, DB, names
	global $cfg, $dbconn, $db_p, $p_pic, $r_pic, $t_pic;

	// Get slots to delete
	$result = db_query("SELECT COUNT(ID_2) FROM {$db_p}oekakidta");
	if (!$result)
		return false;

	$total_slots = (int) db_result($result, 0);

	// If total rows is more than allowed, trim non-archived pics
	$trim_slots = $total_slots - $cfg['pic_store'];
	if ($trim_slots > 0) {
		// Don't blow up
		if ($trim_slots > $max_slots_to_delete) {
			$trim_slots = $max_slots_to_delte;
		}
		if ($trim_slots > 2000) {
			$trim_slots = 2000;
		}

		// Remove oldest, non-archived pictures
		$result = db_query("SELECT PIC_ID FROM {$db_p}oekakidta WHERE archive=0 ORDER BY postdate ASC LIMIT {$trim_slots}");

		while ($result && ($row = db_fetch_array($result))) {
			kill_picture_slot($row['PIC_ID']);
		}
	}

	return true;
}


function fix_thumbnail($picnumber, $new = true) {
	// Need DB, names
	global $dbconn, $db_p, $p_pic, $r_pic, $t_pic;

	// Replace thumbnail for $picnumber.  If !$new, just delete
	$result = db_query("SELECT PIC_ID, ptype, rtype, ttype FROM {$db_p}oekakidta WHERE PIC_ID={$picnumber}");
	$row = db_fetch_array($result);

	// Remove old (Win32 safe)
	if (!empty($row['ttype'])) {
		file_unlock($t_pic.$picnumber.'.'.$row['ttype']);
	}
	if (!empty($row['rtype'])) {
		file_unlock($r_pic.$picnumber.'.'.$row['rtype']);
	}

	if ($new) {
		save_thumbnail($row['PIC_ID'], $row['ptype']);
	} else {
		db_query("UPDATE {$db_p}oekakidta SET ttype='', rtype='', usethumb=0 WHERE PIC_ID='{$row['PIC_ID']}'");
	}
}


function save_thumbnail($picnumber, $p_ext, $force_thumb = false, $force_type = '') {
	// Need CFG, DB, names
	global $dbconn, $db_p;
	global $cfg;
	global $p_pic, $r_pic, $t_pic;


	// If thumbnail mode is off
	if ($cfg['use_thumb'] == 0) {
		return 1;
	}


	// Test for GDlib
	if (extension_loaded('gd')) {
		// Set up file names
		$image_source = $p_pic.$picnumber.'.'.$p_ext;
		$image_t = $t_pic.$picnumber.'.';
		$image_r = $r_pic.$picnumber.'.';


		// Get file properties: size, area, longer side
		$p_filesize = filesize($image_source);
		$p_sizes    = @GetImageSize($image_source);
		$p_area     = $p_sizes[0] * $p_sizes[1];
		if ($p_sizes[0] < $p_sizes[1]) {
			$long_mode  = 1;
			$long_side  = $p_sizes[1];
			$short_side = $p_sizes[0];
		} else {
			$long_mode  = 0;
			$long_side  = $p_sizes[0];
			$short_side = $p_sizes[1];
		}


		// Determine if thumbnails should be made
		$usethumb = 0; // Flag for filesize limit
		$make_t   = 1;
		$make_r   = 0;
		if ($long_side <= $cfg['thumb_t'] || $p_filesize < 15000) {
			// No sense in making a thumbnail if already tiny, or small filesize
			$make_t = 0;
		}
		if ($p_filesize > $cfg['thumb_bytes']) {
			// Over the size limit
			$usethumb = 1;
			$make_r = 1;
		}
		if ($long_side > 740) {
			// No matter what, it's too big for 800x600 users
			$make_r = 1;
		}

		// Force thumbnail?
		if ($force_thumb) {
			$make_r = 1;
			$usethumb = 1;
		}


		if ($make_t || $make_r) {
			/*
			OK, here's where things get ugly.  GD can't really analyze the
			source image, so we'll have to remap a few functions.  If we
			have PNG files, there may be an alpha channel, but the default
			thumbnail type is JPG, so we have to paint a background color
			(such as white) to prevent ink drawings from ending up blank.
			This can't be set globally, so we have to paint each destination
			image separately (grr).  If we're forcing PNG thumbnails, we
			need to enable alpha support to use it on the destination.
			*/

			if (function_exists('imagecreatetruecolor')) {
				$create_function = 'imagecreatetruecolor';
				$resize_function = 'imagecopyresampled';
			} else {
				$create_function = 'imagecreate';
				$resize_function = 'imagecopyresized';
			}

			// Set blending color for alpha resolution
			if (defined('ALPHA_BASE') && strpos(ALPHA_BASE, '#') !== false && strlen(ALPHA_BASE) == 7) {
				$color = ALPHA_BASE;
			} else {
				$color = '#FFFFFF';
			}
			$my_bg = sscanf ($color, '#%2x%2x%2x');


			switch ($p_ext) {
				// This is dumb
				case 'gif':
					if (!function_exists('imagecreatefromgif')) {
						// No GIF support!  Nothing we can do
						// Not an error, so return OK
						return 1;
					} else {
						$source = imagecreatefromgif($image_source);
					}
					break;
				case 'png':
					$source = imagecreatefrompng($image_source);
					break;
				case 'jpg':
					$source = imagecreatefromjpeg($image_source);
					break;
				default:
					// Not an image file [supported by GD]
					return 0;
			}

			// Did GD barf?
			if (!$source) {
				w_log(WLOG_FAIL, 'paintsave: $l_bad_upload`'.$picnumber.'`');
				return 0;
			}


			// Time to make the doughnuts
			if ($make_t) {
				// GD doesn't support scaling (!)
				$ratio  = $cfg['thumb_t'] / $long_side;
				$lesser_t = round($short_side * $ratio);

				if ($long_mode == 0) {
					$new_t = $create_function($cfg['thumb_t'], $lesser_t);
					$my_color = imagecolorallocate($new_t, $my_bg[0], $my_bg[1], $my_bg[2]);
					imagefilledrectangle($new_t, 0, 0, $cfg['thumb_t'], $lesser_t, $my_color);
					$resize_function($new_t, $source, 0, 0, 0, 0, $cfg['thumb_t'], $lesser_t, $p_sizes[0], $p_sizes[1]);
				} else {
					$new_t = $create_function($lesser_t, $cfg['thumb_t']);
					$my_color = imagecolorallocate($new_t, $my_bg[0], $my_bg[1], $my_bg[2]);
					imagefilledrectangle($new_t, 0, 0, $lesser_t, $cfg['thumb_t'], $my_color);
					$resize_function($new_t, $source, 0, 0, 0, 0, $lesser_t, $cfg['thumb_t'], $p_sizes[0], $p_sizes[1]);
				}


				// Hard-coded format for now.
				$t_format = 'jpg';
				if ($force_type == 'png') {
					$t_format = 'png';
				}

				switch ($t_format) {
					case 'png':
						file_unlock($image_t.'png');
						imagepng($new_t, $image_t.'png');
						break;
					case 'jpg':
						file_unlock($image_t.'jpg');
						if (!(imagejpeg($new_t, $image_t.'jpg', $cfg['thumb_jpg']))) {
							w_log(WLOG_FAIL, '$l_no_t`'.$picnumber.'`');
						}
						break;
				}
				imagedestroy($new_t);
			}

			if ($make_r) {
				$ratio  = $cfg['thumb_r'] / $long_side;
				$lesser_r = round($short_side * $ratio);

				if ($long_mode == 0) {
					$new_r = $create_function($cfg['thumb_r'], $lesser_r);
					$my_color = imagecolorallocate($new_r, $my_bg[0], $my_bg[1], $my_bg[2]);
					imagefilledrectangle($new_r, 0, 0, $cfg['thumb_r'], $lesser_r, $my_color);
					$resize_function($new_r, $source, 0, 0, 0, 0, $cfg['thumb_r'], $lesser_r, $p_sizes[0], $p_sizes[1]);
				} else {
					$new_r = $create_function($lesser_r, $cfg['thumb_r']);
					$my_color = imagecolorallocate($new_r, $my_bg[0], $my_bg[1], $my_bg[2]);
					imagefilledrectangle($new_r, 0, 0, $lesser_r, $cfg['thumb_r'], $my_color);
					$resize_function($new_r, $source, 0, 0, 0, 0, $lesser_r, $cfg['thumb_r'], $p_sizes[0], $p_sizes[1]);
				}


				// Hard-coded format for now
				$r_format = 'jpg';
				if ($force_type == 'png') {
					$r_format = 'png';
				}

				switch ($r_format) {
					case 'png':
						file_unlock($image_r.'png');
						$image_r .= 'png';
						imagepng($new_r, $image_r);
						break;
					case 'jpg':
						file_unlock($image_r.'jpg');
						$image_r .= 'jpg';
						if (! (imagejpeg($new_r, $image_r, $cfg['thumb_jpg']))) {
							w_log(WLOG_FAIL, '$l_no_r`'.$picnumber.'`');
						}
						break;
				}
				imagedestroy($new_r);
			}
			imagedestroy($source);


			// Test to see if we made some progress
			if (file_exists($image_r) && (filesize($image_r) > filesize($image_source) * 0.92)) {
				if (!$force_thumb) {
					// Not worth it.  People will effectively download images twice, doubling bandwidth usage.
					if (@file_unlock($image_r)) {
						$usethumb = 0;
						$make_r = 0;
					}
				}
			}

			// Update SQL
			$sql  = "UPDATE {$db_p}oekakidta SET";
			$sql2 = "WHERE PIC_ID='$picnumber'";
			if ($make_t) {
				db_query($sql." ttype='{$t_format}' ".$sql2);
			}
			if ($make_r) {
				db_query($sql." rtype='{$r_format}', usethumb='{$usethumb}' ".$sql2);
			} else {
				// Fixes issue with forced thumbnails
				db_query($sql." usethumb='0' ".$sql2);
			}
		}

		// 1=OK, 2=t, 3=r, 4=t+r
		if ($make_r && $make_t) {
			return 4;
		}
		if ($make_r && !$make_t) {
			return 3;
		}
		return 1 + $make_t;

	} else {
		return 0;
	}
}


function save_image($data, $picnumber) {
	// Need CFG, DB, names
	global $dbconn, $db_p;
	global $cfg;
	global $p_pic, $r_pic, $t_pic;

	// save_image() is a monolithic task, so any old images/anims should be wiped out
	kill_post_files($picnumber);

	$type = get_filetype($data);
	if (!$type) {
		// Corrupt data from applet?
		if (!empty($data) && strlen($data) < 1500000) {
			// Try to save for debug purposes.
			// Call it 'PNG' so servers that filter non-images will allow download.
			$image_file = $cfg['op_pics'].'/dump.png';
			file_unlock($image_file);

			if ($fp = @fopen($image_file, 'wb')) {
				// Write main file
				fwrite($fp, $data, strlen($data));
				fclose($fp);
			}

			w_log(WLOG_SECURITY, '$l_no_type`'.$picnumber.'`');
		}
		return false;
	}

	$image_file = $p_pic.$picnumber.'.'.$type;

	file_unlock($image_file);
	if ($fp = @fopen($image_file, 'wb')) {
		// Write main file
		fwrite($fp, $data, strlen($data));
		fclose($fp);
		w_group_readable($image_file);

		// I wish I didn't have to do this HERE.
		$sizes = @GetImageSize($image_file);
		if ($sizes === false) {
			// Corrupt data from applet?
			w_log(WLOG_MISC, '$l_no_dim`'.$image_file.'`');
			return false;
		}
		$result = db_query('UPDATE '.$db_p."oekakidta SET px={$sizes[0]}, py={$sizes[1]}, ptype='$type' WHERE PIC_ID='$picnumber'");

		if (!save_thumbnail($picnumber, $type)) {
			// Something wrong with GDlib?  Wrong type?
			// For now, no logging = no clogging.
		}

		return true;
	} else {
		w_log(WLOG_FAIL, '$l_no_open`'.$image_file.'`');
	}

	return false;
}


function save_anim($data, $picnumber, $extension) {
	// Need CFG, names
	global $cfg, $p_pic;

	$anim = $p_pic.$picnumber.'.'.$extension;

	file_unlock($anim); // Can't hurt

	if ($fp = @fopen($anim, 'wb')) {
		// Write main file
		fwrite($fp, $data, strlen($data));
		fclose($fp);
		w_group_readable($anim);

		return 1;
	}
	return 0;
}


function bump_post($resno) {
	// Need DB
	global $dbconn, $db_p;

	// Update postdate
	$result = db_query("UPDATE {$db_p}oekakidta SET postdate=NOW() WHERE PIC_ID='$resno'");

	if (!$result)
		// Can't read post
		return false;

	// Log
	$result = db_query("SELECT usrname FROM {$db_p}oekakidta WHERE PIC_ID='$resno'");
	if ($result) {
		w_log(WLOG_BUMP, '$l_mod_pic`'.$resno.'`', db_result($result, 0));
	}

	// Rebuild thumbnail, required for WIP
	fix_thumbnail($resno);

	// Cache latest picture
	latest_refresh();

	return true;
}
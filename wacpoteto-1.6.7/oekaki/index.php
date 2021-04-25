<?php
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-07-28
*/


// Load bootstrap
define('BOOT', 1);
require 'boot.php';


include 'header.php';


// ===================================================================
// Index sorting
//
if (!empty($artist)) {
	$sort = 6;
}
if (!empty($a_match)) {
	$sort = 7;
	$artist = '';
}
if (!empty($t_match)) {
	$sort = 9;
}
$sql_where = '';
$sql_limit = 'LIMIT '.intval($pageno * $cfg['pic_pages']).', '.$cfg['pic_pages'];

//
// SORT NOTES:
// Remember to use "d.usrname" as this is part of a LEFT JOIN with the member table.
//
switch ($sort) {
	case 1:
		$sql_where = 'WHERE postlock=1 ORDER BY lastcmt DESC';
		break;
	case 2:
		$sql_where = 'WHERE postlock=1 AND animation=1 ORDER BY postdate DESC';
		break;
	case 3:
		$sql_where = 'WHERE postlock=1 AND animation=1 ORDER BY lastcmt DESC';
		break;
	case 4:
		$sql_where = 'WHERE postlock=1 AND archive=1 ORDER BY postdate DESC';
		break;
	case 5:
		$sql_where = 'WHERE postlock=1 AND archive=1 ORDER BY lastcmt DESC';
		break;
	case 6:
		$sql_where = "WHERE d.usrname LIKE '%{$artist}%' ORDER BY postdate DESC";
		break;
	case 7:
		$sql_where = "WHERE d.usrname='{$a_match}' ORDER BY postdate DESC";
		break;
	case 8:
		$sql_where = "WHERE password='public' ORDER BY postdate DESC";
		break;
	case 9:
		if (!empty($a_match)) {
			$sql_where = "WHERE d.usrname='{$a_match}' AND postlock=1 AND title LIKE '%{$t_match}%' ORDER BY postdate DESC";
		} else {
			$sql_where = "WHERE postlock=1 AND title LIKE '%{$t_match}%' ORDER BY postdate DESC";
		}
		break;
	default:
		$sql_where = 'WHERE postlock=1 ORDER BY postdate DESC';
		break;
}

$result = db_query("SELECT ID_2 FROM {$db_p}oekakidta AS d {$sql_where}");
$pages = ceil(db_num_rows($result) / $cfg['pic_pages']);

$result = db_query("SELECT d.*, m.avatar FROM {$db_p}oekakidta AS d LEFT JOIN {$db_mem}oekaki AS m ON d.usrname=m.usrname {$sql_where} {$sql_limit}");
$rownum = db_num_rows($result);

// ===================================================================
// CGI pass-through
// This is used for links, so HTML/URL encode
//
$pic_redirect = '';
if ($pageno) {
	$pic_redirect .= '&amp;pageno='.$pageno;
}
if ($sort) {
	$pic_redirect .= '&amp;sort='.$sort;
}
if (!empty($artist)) {
	$pic_redirect .= '&amp;artist='.urlencode($artist);
}
if (!empty($a_match)) {
	$pic_redirect .= '&amp;a_match='.urlencode($a_match);
}
if (!empty($t_match)) {
	$pic_redirect .= '&amp;t_match='.urlencode($t_match);
}


// ===================================================================
// Page numbers
//
$pages_query = '';
if ($sort != 0) {
	$pages_query .= '&amp;sort='.$sort;
}
if ($artist) {
	$pages_query .= '&amp;artist='.urlencode($artist);
}
if ($a_match) {
	$pages_query .= '&amp;a_match='.urlencode($a_match);
}
if ($t_match) {
	$pages_query .= '&amp;t_match='.urlencode($t_match);
}

// $pages_link is for page numbers only!
$pages_link = 'index.php?pageno={page_link}'.$pages_query;

// Fourth parameter is how many digits on each side of the current page number will be printed
$page_numbers_formatted = quick_page_numbers(0, $pageno, $pages, 5, $pages_link)."\n";


// ===================================================================
// Main HTML
//
echo "<!-- Notice -->\n";
if (file_not_empty($cfg['res_path'].'/notice.php')) {
	echo "<div id=\"notice\">\n";
	include $cfg['res_path'].'/notice.php';
	echo "\n</div>\n"
	."<!-- /Notice -->\n\n\n"
	."<hr />\n"
	."<br />\n";
} else {
	echo "<!-- /Notice -->\n\n\n"
	."<hr />\n";
}

?>
<!-- Pages Top -->
<div id="pagestop" class="pages">
	<?php echo $page_numbers_formatted; ?>
</div>
<!-- /Pages Top -->


<hr />
<br />


<?php
/*
	MAIN LOOP
*/
?>
<!-- Content -->
<div id="contentmain">
<?php
	// Outer loop, for posts
	for ($current_post = 0; $current_post < $rownum; $current_post++) {

		// Get DB results, one row at a time
		$outerrow = db_fetch_array($result);

?>
	<!-- Pic -->
	<div id="pic<?php echo $outerrow['PIC_ID'];?>" class="postheader">
		<hr />
<?php	// Picture ID ?>
		[<?php echo $outerrow['ID_2'];?>]
<?php

		// Retouch
		if ($flags['D']) {
			if ($outerrow['usrname'] == $OekakiU || !empty($outerrow['password'])) {
				switch ($outerrow['datatype']) {
					case '0':
						$use_app = 'noteBBS.php?';
						break;
					case '1':
						$use_app = 'oekakiBBS.php?';
						break;
					case '2':
						$use_app = 'shiBBS.php?';
						break;
					case '3':
						$use_app = 'shiBBS.php?tools=pro&amp;';
						break;
					case '4':
						$use_app = 'chibipaint.php?';
						break;
					case '5':
						$use_app = 'chickenpaint.php?';
						break;
				}
				if ($outerrow['usrname'] != $OekakiU) {
					$use_app = 'retouch.php?';
				}
			
				echo "		[<a href=\"{$use_app}edit={$outerrow['PIC_ID']}\">".t('word_retouch')."</a>]\n";
			}
		}

		// Comment
		if ($outerrow['threadlock'] == '0' || $flags['mod']) {
			if ($cfg['guest_post'] == 'yes' || $OekakiU != '') {
				?>
		[<a href="comment.php?mode=add&amp;resno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('verb_comment');?></a>]
<?php
			}
		}

		// Artist & Title
		$my_title = trim($outerrow['title']);
		if ( empty($my_title) || $my_title == 'No Title') {
			$my_title = t('no_pic_title');
		}
?>
		<strong><?php tt('word_artist');?>:</strong>
		<a onclick="openWindow('profile.php?user=<?php echo urlencode($outerrow['usrname']);?>', 400, 600); return false" href="profile.php?user=<?php echo urlencode($outerrow['usrname']);?>"><?php echo w_html_chars($outerrow['usrname']);?></a>
		| <strong><?php tt('install_title2');?>:</strong> <?php echo nifty2_convert($my_title);?> 
<?php

		// Time
		if ($outerrow['edittime'] > 10 ) {
			$edit_s = ceil($outerrow['edittime'] % 60);
			$edit_m = ceil($outerrow['edittime'] / 60 % 60);
			$edit_h = ceil($outerrow['edittime'] / 3600 % 60);

			if ($edit_h)
				$edit_s = 0;

			$edit_out = '';
			if ($edit_h)
				$edit_out .= "{$edit_h}h ";
			if ($edit_m)
				$edit_out .= "{$edit_m}m ";
			if ($edit_s)
				$edit_out .= "{$edit_s}s ";
		} else {
			$edit_out = t('word_unknown');
		}
		echo "		| <strong>".t('word_time').":</strong> {$edit_out}\n";


		// Delete Picture
		if ($outerrow['usrname'] == $OekakiU) {
			// Delete
			if (!empty($a_match)) {
				$a_tag = '&amp;return=a_match';
			} else {
				$a_tag = '';
			}
			echo "		| [<a href=\"delconf.php?mode=del{$a_tag}&amp;picno={$outerrow['PIC_ID']}\">".t('word_delete')."</a>]\n";
		} elseif ($flags['admin']) {
			// Admin Delete
			echo "		| [<a href=\"delconf.php?mode=dela&amp;picno={$outerrow['PIC_ID']}\">".t('word_delete')."</a>]\n";
		}


		// Lock
		if ($flags['mod']) {
			if ($outerrow['threadlock'] != 0) {
?>
		[<a href="functions.php?mode=unlock&amp;picno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('word_unlock');?></a>]
<?php
			} else {
?>
		[<a href="functions.php?mode=lock&amp;picno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('word_lock');?></a>]
<?php
			}
		}


		// Archive
		if ($flags['admin']) {
?>
		[<a href="functions.php?picid=<?php echo $outerrow['PIC_ID'];?>&amp;mode=archive<?php echo $pic_redirect;?>"><?php
			if ($outerrow['archive'] == '0') {
				echo t('word_archive');
			} else {
				echo t('word_unarchive');
			}
			?></a>]
<?php
		}


		// Bump
		if ($flags['mod']) {
?>
		[<a href="functions.php?mode=bump&amp;resno=<?php echo $outerrow['PIC_ID'];?>"><?php tt('word_bump');?></a>]
<?php
		}


		// Block adult images
		if ($flags['mod'] || $outerrow['usrname'] == $OekakiU) {

			if ($outerrow['adult'] == 0) {
?>
		[<a href="functions.php?mode=block&amp;picno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('mark_adult', MIN_AGE_ADULT);?></a>]
<?php
			} else {
?>
		[<a href="functions.php?mode=unblock&amp;picno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('unmark_adult', MIN_AGE_ADULT);?></a>]
<?php
			}
		}


		// WIP
		if ($flags['mod']) {
?>
		[<a href="functions.php?mode=wip_pic&amp;picno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('word_WIP');?></a>]
<?php
		}


		// Thumbnail
		if ($flags['mod']) {
			if ($glob['wactest'] && $flags['owner']) {
?>
		[<a href="functions.php?mode=make_thumb&amp;picno=<?php echo $outerrow['PIC_ID'];?>&amp;type=png<?php echo $pic_redirect;?>"><?php tt('abrc_tp');?></a>
		/ <a href="functions.php?mode=make_thumb&amp;picno=<?php echo $outerrow['PIC_ID'];?>&amp;type=jpg<?php echo $pic_redirect;?>"><?php tt('abrc_tj');?></a>]
<?php
			} else {
?>
		[<a href="functions.php?mode=make_thumb&amp;picno=<?php echo $outerrow['PIC_ID'];?><?php echo $pic_redirect;?>"><?php tt('word_thumb');?></a>]
<?php
			}
		}
?>
		<hr />
	</div>
	<!-- /Postheader -->

<?php


		/*
			PICTURE RENDINGING CODE

			This is complex.

			To improve browsing visibility and reduce bandwidth, we do three things:  use thumbnails when available for large images, scale large images down if no thumbnail is available/practical, and use vertical layout for wide images so comments don't get squashed (if member has not specified a layout).

			If thumbnail support is on, a ttype (thumbnail) is always made unless the source picture is less than 15K.  ttypes are used mostly for speeding up load times on menus and when in uniformity mode, but also substitute a missing rtype if the filesize is large.

			rtypes (reduced previews) are made if a file is too large, either in filesize or dimmentions.  rtypes are *always* used if the filesize is large, and sometimes used if the layout demands it.

			When the display mode is automatic, vertical layout is only used if not using an rtype/ttype.

			VARS:

			$user['thumbview']: 0=none, 1=layout, 2=scaled, 3=uniform
			$use_thumbnail    : 0=no, 1=rtype, 2=ttype
			$use_scaling      : 0=no, >0=scale_in_pixels

			$user['picview'] : 0=auto, 1=horizontal, 2=vertical
			$this_row_display: 0=horizontal, 1=vertical

			TRUTH TABLE:

			td=$user['thumbview'] value
			h=horizontal, v=vertical
			R=rtype thumbnail, T=ttype thumbnail

			     Picture type  | td=0 | td=1 | td=2 | td=3 |
			-------------------+------+------+------+------+
			           Normal  |  Nh  |  Nh  |  Nh  |  Th  |
			   Large Filesize  |  Rh  |  Rh  |  Rh  |  Th  |
			       > Comments  |  Nv  |  Nv  |  Rh  |  Th  |
			         > Screen  |  Rh  |  Rh  |  Rh  |  Th  |
		*/

		$use_thumbnail = 0;
		$use_scaling   = 0;
		$have_r = (!empty($outerrow['rtype'])) ? 1 : 0;
		$have_t = (!empty($outerrow['ttype'])) ? 1 : 0;
		$bandwidth = (!empty($outerrow['usethumb'])) ? 1 : 0;


		// Set H/V mode to default
		$this_row_display = ($user['picview'] == 2) ? 1 : 0;

		// Choose thumbnail and layout
		if ($user['thumbview'] == 3) {
			// Uniformity mode always uses ttype, H mode
			$use_thumbnail = 2;
			$this_row_display = 0;

			// No thumbnail?
			if (!$have_t) {
				if ($have_r) {
					$use_thumbnail = 1;
				} else {
					$use_thumbnail = 0;
					$use_scaling = $cfg['thumb_t'];
				}
			}
		} else {
			if ($bandwidth && ($have_r || $have_t)) {
				// Always use thumbnail if avail
				$use_thumbnail = 1;

				if (!$have_r) {
					$use_thumbnail = 2;
				}
			} else {
				// Bandwidth OK, or we don't have a thumbnail

				// Check width of picture
				if ($outerrow['px'] > ($user['screensize'] - 60)) {
					// Width too large for screen?
					if ($have_r) {
						$use_thumbnail = 1;
					} else {
						$use_thumbnail = 0;
						$use_scaling = $cfg['thumb_r'];
					}
				} elseif ($outerrow['px'] > ($user['screensize'] - 400)) {
					// Width too large for comments?
					// For 800x600, a picture larger than 400px is too big
					if ($user['thumbview'] == 2 && $have_r) {
						// Scaled mode, use thumbnail
						$use_thumbnail = 1;
					} else {
						// Non-scaled, use V layout
						$use_thumbnail = 0;
						$this_row_display = 1;
					}
				}
			}

			// How about user overrides?
			// Layout override works with all modes but uniformity
			if ($user['picview'] > 0) {
				$this_row_display = ($user['picview'] == 2) ? 1 : 0;
			}
		}
		// At this point we know thumbnail, layout is valid


		// Put our selections together
		{
			$master_image    = $p_pic.$outerrow['PIC_ID'].'.'.$outerrow['ptype'];
			$thumbnail_image = $master_image;

			if ($use_thumbnail == 2) {
				$thumbnail_image = $t_pic.$outerrow['PIC_ID'].'.'.$outerrow['ttype'];
			} elseif ($use_thumbnail == 1) {
				$thumbnail_image = $r_pic.$outerrow['PIC_ID'].'.'.$outerrow['rtype'];
			}


			// Get longest side of picture
			if ($outerrow['py'] > $outerrow['px']) {
				$long_mode  = 1;
				$long_side  = $outerrow['py'];
				$short_side = $outerrow['px'];
			} else {
				$long_mode  = 0;
				$long_side  = $outerrow['px'];
				$short_side = $outerrow['py'];
			}

			// Set scaling
			$scale_size = '';
			if ($use_scaling && ($long_side > $use_scaling)) {
				// No thumbnail.  Scale it.
				if ($long_mode == 1) {
					// Vertical
					$scale_size = 'height="'.$use_scaling.'"';
				} else {
					// Horizontal
					$scale_size = 'width="'.$use_scaling.'"';
				}
			}
		}


		// Set CSS class for anchored image
		if ($use_thumbnail && $user['thumbview'] != 3) {
			$img_class_type = 'imgthumb';
		} else {
			$img_class_type = 'imghover';
		}


		// OK, we can render the div/table, now

?>
	<!-- Postmain -->
	<div class="postmain">
<?php
		// Watch for these!  ;)
		if ($this_row_display == 0) {
			?>
		<table cellspacing="0" cellpadding="0" border="0">
		<tr>
		<!-- Postdata -->
		<td style="vertical-align: top; text-align: center;" valign="top">
<?php
		} else {
		?>
		<div class="postdata">
<?php
		}

		// Set the adult mode
		$adult_block_mode = 0;
		if (!$flags['X'] && $outerrow['adult'] == 1) {
			if ($cfg['guest_adult'] == 'yes' || $flags['mod']) {
				// block image only (worksafe)
				$adult_block_mode = 1;
			} else {
				// block image and link
				$adult_block_mode = 2;
			}
		}

		// Paint type
		if ($outerrow['datatype'] == '0') {
			$applet_mode = t('word_paintbbs');
		}
		elseif ($outerrow['datatype'] == '1') {
			$applet_mode = t('word_oekakibbs');
		}
		elseif ($outerrow['datatype'] == '2') {
			$applet_mode = 'ShiPainter';
		}
		elseif ($outerrow['datatype'] == '3') {
			$applet_mode = 'ShiPainter Pro';
		}
		elseif ($outerrow['datatype'] == '4') {
			$applet_mode = 'Chibi Paint';
		}
		elseif ($outerrow['datatype'] == '5') {
			$applet_mode = 'Chicken Paint';
		}
		if ($outerrow['uploaded']) {
			$applet_mode = t('word_uploaded');
		}


		// Lightbox support
		$lightbox = '';
		if (!$user['no_viewer']) {
			if ($outerrow['adult'] == 1) {
				$lightbox = $user['pic_viewer_adult'];
			} else {
				$lightbox = $user['pic_viewer_norm'];
			}
		}
		if (trim($outerrow['title']) == '' || $outerrow['title'] == 'No Title') {
			$lightbox_title = 'title="Artist: '.w_html_chars($outerrow['usrname']).'"';
		} else {
			$lightbox_title = 'title="&quot;'.w_html_chars($outerrow['title']).'&quot; by '.w_html_chars($outerrow['usrname']).'"';
		}


		// Print image
		$my_picnumber   = t('picnumber', $outerrow['PIC_ID']);
		$my_clicktoview = t('clicktoview', $outerrow['PIC_ID']);
if ($adult_block_mode == 0) {
echo <<<EOF
			<a href="$master_image" {$lightbox} {$lightbox_title}><img src="$thumbnail_image" class="$img_class_type" {$scale_size} alt="{$my_picnumber}" title="{$my_picnumber}" /></a><br />

EOF;
} elseif ($adult_block_mode == 1) {
echo <<<EOF
			<a href="$master_image" {$lightbox} {$lightbox_title}><img src="{$cfg['res_path']}/{$cfg['porn_img']}" class="$img_class_type" alt="{$my_picnumber}" title="{$my_clicktoview}" /></a><br />

EOF;
} else {
echo <<<EOF
			<img src="{$cfg['res_path']}/{$cfg['porn_img']}" alt="{$my_picnumber}" title="{$my_picnumber}" /><br />

EOF;
}
		//	=	=	=	=	=	=	=	=	=	=	=	=	=	=	=

		if ($use_thumbnail && $user['thumbview'] != 3) {
			echo '			'.t('clickenlarg').'<br />'."\n";
		}
		?>
			<br />
			<?php echo $applet_mode;?> 
			<br />
<?php

		// Animation type (excludes ChibiPaint and ChickenPaint)
		if ($outerrow['animation'] == '1' && $outerrow['datatype'] < 4) {
			switch ($outerrow['datatype']) {
				case '0':
				case '2':
				case '3':
					$viewani_size = array('75', '175');
					break;

				case '1':
					$viewani_size = array('100', '185');
					break;

				default:
					break;
			}

			if ($adult_block_mode == 0 || $adult_block_mode == 1) {
				// NOTE: Use ID_2, not PIC_ID for $resno
?>
			[<a href="viewani.php?recno=<?php echo $outerrow['ID_2'];?>" onclick="openWindow('viewani.php?recno=<?php echo $outerrow['ID_2'];?>', '<?php echo $outerrow['px'] + $viewani_size[0];?>', '<?php echo $outerrow['py'] + $viewani_size[1];?>'); return false;"><?php tt('viewani_title');?></a>]<br />
<?php
			} else {
?>
			[<?php tt('word_animated');?>]<br />
<?php
			}
		}

		if ($outerrow['archive'] == '1') {
			echo "			(".t('word_archived').")<br />";
		}
		if ($outerrow['adult'] != 0 && $flags['X']) {
			echo "			(".t('word_adult').")<br />\n";
		}
		if (!empty($outerrow['password'])) {
			if ($outerrow['password'] == 'public' && $cfg['public_retouch'] == 'yes') {
				echo "			(".t('word_public').")<br />\n";
			} else {
				echo "			(".t('install_password').")<br />\n";
			}
		}
		if ($outerrow['threadlock'] != 0) {
			echo "			<br />\n			<strong>(".t('tlocked').")</strong><br />\n";
		}

		if ($this_row_display == 0) {
			?>
		</td>
<?php
		} else {
			?>
		</div>
		<br />
<?php
		}
		?>

<?php
		if ($this_row_display == 0) {
			?>
		<td style="width: 100%;" valign="top">
<?php
		}
		?>
		<div class="commentmain">
			<div class="commentheader">
				<strong>
					<a href="profile.php?user=<?php echo urlencode($outerrow['usrname']);?>" onclick="openWindow('profile.php?user=<?php echo urlencode($outerrow['usrname']);?>', 400, 500); return false"><?php echo w_html_chars($outerrow['usrname']);?></a>
				</strong>
				<span class="commentinfo">@ <?php echo date($datef['post_header'], strtotime($outerrow['postdate'])); ?></span>
				<?php
					if ($outerrow['usrname'] == $OekakiU || $flags['mod'])
						echo "<a href=\"editpic.php?picno={$outerrow['PIC_ID']}\">[".t('word_edit')."]</a>\n";
				?>
			</div>
			<div class="commentdata">
<?php
		// Avatar
		$avatar = '';
		if (!empty($outerrow['avatar'])) {
			if ($cfg['use_avatars'] == 'yes' && strtolower($OekakiU) != 'screenshot') {
				$avatar = $outerrow['avatar'];
			}
		}
		if (!empty($avatar)) {
?>
				<table>
				<tr>
				<td valign="top">
					<img src="<?php echo $cfg['avatar_folder'].'/'.$avatar;?>" alt="avatar" /><br />
				</td>
				<td valign="top" width="100%">
<?php
		}

		// Comment
		//
		// Edited, Edited By
		$edit_block = '';
		if (!empty($outerrow['edited'])) {
			$edit_block .= '<br /><br />';
			$my_date = date($datef['admin_edit'], strtotime($outerrow['edited']));

			if (!empty($innerrow['editedby'])) {
				$edit_block .= t('edited_by_admin', w_html_chars($outerrow['editedby']), $my_date);
			} else {
				$edit_block .= t('edited_on', $my_date);
			}
		}

		// Uploaded by
		if ($outerrow['uploaded'] 
			&& empty($outerrow['edited']) 
			&& !empty($outerrow['editedby'])
			&& $outerrow['editedby'] != $outerrow['usrname']
		) {
			// By referencing edited and editedby, rather than origart, we can
			// easily remove "Uploaded by" when the image is edited.
			$edit_block .= '<br /><br />'.t('uploaded_by_admin', w_html_chars($outerrow['editedby']));
		}
		if (!empty($outerrow['origart'])) {
			$edit_block .= "<br />\n<br />".t('originalby', w_html_chars($outerrow['origart']));
		}

		if (!empty($outerrow['comment'])) {
			?>
				<?php echo nifty2_convert($outerrow['comment']).$edit_block; ?> 
<?php
		} else {
			?>
				<small><?php echo t('no_comment').$edit_block;?></small>
<?php
		}
		if (!empty($avatar)) { ?>
				</td>
				</tr>
				</table>
<?php
		}
?>
			</div>

<?php
		// Comments
		$result2 = db_query("SELECT c.*, m.avatar FROM {$db_p}oekakicmt AS c LEFT JOIN {$db_mem}oekaki AS m ON c.usrname=m.usrname WHERE PIC_ID=".intval($outerrow['PIC_ID'])." ORDER BY postdate ASC");
		$rownum2 = db_num_rows($result2);

		$i2 = 0;
		while ($i2 < $rownum2) {
			// Get DB results, one row at a time.
			$innerrow = db_fetch_array($result2);

			?>
			<div class="commentheader">
<?php
			if ($innerrow['usrname'] == 'Guest'){
				// Only reg members can see e-mails
				// E-mails are set here only if a guest, so don't bother with 'email_show'
				if (!empty($OekakiU) && !empty($innerrow['email'])) {
					?>
				<b><i><a href="mailto:<?php echo email_code($innerrow['email'], true); ?>"><?php echo w_html_chars($innerrow['postname']);?></a></i></b>
<?php
				} else {
					?>
				<span class="nolink"><b><i><?php echo w_html_chars($innerrow['postname']);?></i></b></span>
<?php
				}
			} else {
				?>
				<b><a onclick="openWindow('profile.php?user=<?php echo urlencode($innerrow['usrname']);?>', 400, 500); return false" href="profile.php?user=<?php echo urlencode($innerrow['usrname']);?>"><?php echo w_html_chars($innerrow['usrname']);?></a></b>
<?php
			}
			// Print homepage only if available
			if (strlen($innerrow['url']) > 8) {
				?>
			- <a href="<?php echo w_html_chars($innerrow['url']);?>">[<?php tt('word_homepage');?>]</a>
<?php
			}
?>
				<span class="commentinfo">@ <?php echo date($datef['post_header'], strtotime($innerrow['postdate'])); ?></span>
<?php

			// Edit comment
			if ($innerrow['usrname'] == $OekakiU || $flags['mod']) {
?>
				[<a href="editcomm.php?id_3=<?php echo $innerrow['ID_3'];?>&amp;resno=<?php echo $outerrow['PIC_ID'].$pic_redirect;?>"><?php tt('word_edit');?></a>]
<?php
			}


			// Delete comment
			if ($innerrow['usrname'] == $OekakiU || $flags['admin']) {
				// Confirm
				if (defined('DISABLE_CMT_DEL_CONF') && DISABLE_CMT_DEL_CONF) {
					// No
?>
				[<a href="functions.php?mode=udelcmt&amp;cmtno=<?php echo $innerrow['ID_3'];?>"><?php tt('word_delete');?></a>]
<?php
				} else {
					// Yes
					$my_pageno = '';
					if ($pageno) {
						$my_pageno = "&amp;page={$pageno}";
					}
?>
				[<a href="delconf.php?cmtno=<?php echo $innerrow['ID_3'].$my_pageno;?>&amp;post=<?php echo $outerrow['PIC_ID'];?>"><?php tt('word_delete');?></a>]
<?php
				}
			}


			// Print Guest IP/Host
			if ($flags['mod'] && $innerrow['usrname'] == 'Guest') {
				$temp_host = '';
				if (!empty($innerrow['hostname']) && $innerrow['hostname'] != 'invalid') {
					$temp_host = $innerrow['hostname'];
				}
				?><br />
				<span class="commentinfo">(<?php echo $innerrow['IP']; if (!empty($temp_host)) { echo ' / '.$temp_host; } ?>)</span>
<?php
			}
?>
			</div>
<?php

		// Avatar
		$avatar = '';
		if (!empty($innerrow['avatar'])) {
			if ($cfg['use_c_avatars'] == 'yes' && $cfg['use_avatars'] == 'yes' && $innerrow['usrname'] != 'Guest' && strtolower($OekakiU) != 'screenshot') {
				$avatar = $innerrow['avatar'];
			}
		}
		if (!empty($avatar)) {
?>
			<div class="commentdata"<?php if (FULL_AVATARS != 1) echo ' style="padding-top: 0; padding-bottom: 0; margin-top: 0; margin-bottom: 0;"';?>>
				<table>
				<tr>
				<td valign="top">
					<img src="<?php echo $cfg['avatar_folder'].'/'.$avatar;?>" alt="avatar" /><br />
				</td>
				<td valign="top" width="100%">
<?php
		} else {
?>
				<div class="commentdata">
<?php
		}

		// Comment
		//
		// Edited, Edited By
		$edit_block = '';
		if (!empty($innerrow['edited'])) {
			$edit_block .= '<br /><br />';
			$my_date = date($datef['admin_edit'], strtotime($innerrow['edited']));

			if (!empty($innerrow['editedby'])) {
				$edit_block .= t('edited_by_admin', w_html_chars($innerrow['editedby']), $my_date);
			} else {
				$edit_block .= t('edited_on', $my_date);
			}
		}

		if (!empty($innerrow['comment'])) {
?>
				<?php echo nifty2_convert($innerrow['comment']).$edit_block; ?> 
<?php
		} else {
?>
				<small><?php echo t('no_comment').$edit_block;?></small>
<?php
		}
		if (!empty($avatar)) { ?>
				</td>
				</tr>
				</table>
<?php
		}
?>
			</div>

<?php
			$i2++;
		} // endwhile inner

		if ($this_row_display == 0) {
			// Horizontal
			?>
		</div>
		</td>
		</tr>
		</table>
<?php
		} else {
			// Vertical
			?>
		</div>
<?php
		}
		?>
		<!-- /Commentmain -->
	</div>
	<br />

<?php
	} // endwhile outer
?>

</div>
<!-- /Content -->
<?php
/*
	END MAIN LOOP
*/
?>


<hr />


<!-- Pages Bottom -->
<div id="pagesbottom" class="pages">
	<?php echo $page_numbers_formatted; ?>
</div>

<?php if (SELECT_PAGES == 1) { ?>
<!-- Jump Page Dropdown Menu -->
<div align="right">
<form name="jump">
	<?php tt('gotopg');?> 
	<select name="menu" class="txtinput">
<?php
$i4 = 0;
while ($i4 < $pages) {
	if($i4 != $pageno) {
?>
		<option value="index.php?pageno=<?php echo $i4;?><?php echo $pages_query;?>"><?php echo $i4 + 1; ?></a>
<?php
	} else {
?>
		<option selected="selected" value="index.php?pageno=<?php echo $i4;?><?php echo $pages_query;?>"><b><?php echo $i4 + 1; ?></b></option>
<?php }
	$i4++;
}
?></option>
	</select>
	<input type="button" onclick="location=document.jump.menu.options[document.jump.menu.selectedIndex].value;" value="<?php tt('word_go');?>" class="submit">&nbsp;
</form>
</div>

<!-- /Pages Bottom -->
<?php } ?>

<hr />


<?php

include 'footer.php';
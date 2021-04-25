<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
niftyToo code by Marcello Bastéa-Forte (http://marcello.cellosoft.com)

Wacintaki Poteto modifications Copyright 2004-2016 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2016-07-31
*/



//
// Smilies
// If you don't like these, edit your own in hacks.php so updates will not wipe out your changes.
if (USE_SMILE_HACK != 1 || !isset($smilies_group)) {
	// Escape all HTML entities!
	$smilies_group = array(
		':)' => 'smile.gif',
		':|' => 'neutral.gif',
		':(' => 'sad.gif',
		':D' => 'big_smile.gif',
		'&gt;:)'   => 'smirk.gif',
		'&gt;:D'   => 'evil_smile.gif',
		':sneaky:' => 'sneaky.gif',
		':o' => 'yikes.gif',
		';)' => 'wink.gif',
		':/' => 'hmm.gif',
		':P' => 'tongue.gif',
		':lol:'      => 'lol.gif',
		':mad:'      => 'mad.gif',
		':rolleyes:' => 'rolleyes.gif',
		':cool:'   => 'cool.gif',
		':duh:'    => 'duh.gif',
		':blush:'  => 'blush.gif',
		':cry:'    => 'cry.gif',
		':boohoo:' => 'boohoo.gif',
		':crazy:'  => 'crazy.gif',
		':what:'   => 'what.gif',
		':gamer:'  => 'gamer.gif',
		':barf:'   => 'barf.gif'
	);
}


function nifty2_convert($out) {
	global $cfg;
	global $user;
	global $OekakiU;

	if (isset($GLOBALS['smilies_group'])) {
		global $smilies_group;
	}

	// Required formatting
	$out = htmlspecialchars($out);
	$out = stripslashes($out);
	$out = nl2br($out);
	$out = str_replace(array("\r", "\n"), '', $out);

	// Cleanup brackets.
	$out = str_replace(array('[[', ']]'), array('&#91;', '&#93;'), $out);


	// Autolink URL
	// Regex slurps leading char, so put it back with '\1'
	if (strpos($out, '://') !== false) {
		$out = preg_replace_callback(
			// Note: make sure traling char of URL allows ";" or else entities (&amp;) will not be found.
			// Still has issues with punctuation and delimiters, but still a good compromise.
			'!([^]:,=]|^)(https?://[[:alnum:]-+\(\)\[\]&@#/%\?\$=~_\'|:,.;]+[[:alnum:]-+&@#/%=~_|;])!i',
			function ($m) {
				return $m[1].'<a href="'.nifty2_nice_url($m[2]).'">'.$m[2].'</a>';
			},
			$out
		);
	}


	// Check for BBCode tag marker.  If no marker, skip all replacements.
	if (strpos($out, '[') !== false) {

		// Tag substitutes
		$sub_in = array('[s]', '[/s]', '[strike]', '[/strike]');
		$sub_out = array('[del]', '[/del]', '[del]', '[/del]');
		$out = str_ireplace($sub_in, $sub_out, $out);

		// First handle basic BBCode tags that do not require a callback.
		nifty2_replacetag($out, 'b');
		nifty2_replacetag($out, 'u');
		nifty2_replacetag($out, 'i');
		nifty2_replacetag($out, 'del');

		if (strpos($out, '[') !== false) {
			nifty2_replacetag($out, 'big');
			nifty2_replacetag($out, 'small');
			nifty2_replacetag($out, 'code');
			nifty2_replacetag($out, 'sub');
			nifty2_replacetag($out, 'sup');

			// Tags with attributes
			nifty2_replace($out, 'color'
				, '<span style="color: \\1">\\2</span>'
				, 's'
				, '#[0-9A-Fa-f]{6}|[a-zA-Z]+'
			);
			nifty2_replace($out, 'color'
				, '<span style="color: \\1; background-color: \\2;">\\3</span>'
				, 's'
				, '#[0-9A-Fa-f]{6}|[a-zA-Z]+)\s+on\s+(#[0-9A-Fa-f]{6}|[a-zA-Z]+'
			);
		}


		// Now run the more expensive callback replacements.
		if (strpos($out, '[') !== false) {

			// Local URL redirect, return if found
			if (strpos($out, '[local') !== false) {
				// URL tag w/attribute
				nifty2_replace($out, 'local'
					, '<a href="%f1">\\2</a>'
					, 'm'
					, '[^]"]+'
					, 'nifty2_local_url'
				);
				// URL tag, no attribute
				nifty2_replace($out, 'local'
					, '<a href="%f1">\\1</a>'
					, 'm'
					, null
					, 'nifty2_local_url'
				);

				return $out;
			}


			// URL tag w/attribute
			nifty2_replace($out, 'url'
				, '<a href="%f1">\\2</a>'
				, 'm'
				, '[^]"]+'
				, 'nifty2_nice_url'
			);
			// URL tag, no attribute
			nifty2_replace($out, 'url'
				, '<a href="%f1">\\1</a>'
				, 'm'
				, null
				, 'nifty2_nice_url'
			);


			// Email tag w/attribute
			nifty2_replace($out, 'email'
				, '<a href="mailto:%f1">\\2</a>'
				, 'm'
				, '[^]"]+'
				, 'nifty2_fix_brackets'
			);
			// Email tag, no attribute
			nifty2_replace($out, 'email'
				, '<a href="mailto:%f1">\\1</a>'
				, 'm'
				, null
				, 'nifty2_fix_brackets'
			);


			// Handle quoted text after the standard tags and before any tags
			// that add layout.
			nifty2_replace($out, 'quote'
				, '<blockquote style="margin: 1em 2em 0 2em;"><em>'
					. addslashes(t('ldquo')).'%f1'.addslashes(t('rdquo'))
					. '</em></blockquote>'
				, 'm'
				, null
				, 'nifty2_fix_brackets'
			);

		}
	}


	// Smilies ($out must be HTML)
	if ($cfg['smilies'] != 'no') {
		// Show for guests, or if enabled in a user's profile
		if (empty($OekakiU) || $user['smilies_show']) {

			$flag_smilies_found = false;
			foreach ($smilies_group as $smile_in => $smile_out) {
				if (strpos($out, $smile_in) !== false) {
					$flag_smilies_found = true;
					break;
				}
			}

			if ($flag_smilies_found) {
				$out = ' '.$out.' '; // Padding for smilies test
				foreach ($smilies_group as $smile_in => $smile_out) {
					$smile_find = '/(\s|>)'.preg_quote($smile_in, '/').'(\s|<)/';
					$smile_put = '\1<img src="smilies/'.$smile_out.'" style="border: 0;" alt="'.$smile_in.'" />\2';
					$out = preg_replace($smile_find, $smile_put, $out);
				}
				$out = substr($out, 1, -1); // Remove padding
			}
		}
	}

	return $out;
}


function nifty2_replacetag(&$text, $tag) {
	nifty2_replace($text, $tag, '<'.$tag.'>\\1</'.$tag.'>');
}

function nifty2_replace(&$text, $tag, $replace, $flags = 's', $parameter = null, $filter_fn = null) {
	// Refactored preg_replace() to replace the /e modifier with a callback.

	// Regular replacements (\1, \2, ...) are handled as normal.
	// The $replace variable may contain the command '%fx' for filtered versions
	// of replacements, where 'x' is the $matches array offset.

	// Example:
	// $replace = 'Normal text: \\2, filtered version: %f2';

	$text = preg_replace_callback(
		'/\['.$tag.($parameter ? '[:=\s]\s*('.$parameter.')' : '').'\](.+?)[\n\r\f]?(?:$|\[\/'.$tag.'\])/i'.$flags,
		function ($m) use ($replace, $filter_fn) {
			$count = count($m);

			for ($i = 0; $i < $count; $i++) {
				if ($filter_fn) {
					$replace = str_replace('%f'.$i, $filter_fn($m[$i]), $replace);
				}
				$replace = str_replace('\\'.$i, $m[$i], $replace);
			}

			return $replace;
		},
		$text
	);
}

function nifty2_smiley(&$text, $smile, $name) {
	$text = str_replace("[$smile]", '<img src="/images/smilies/'.$name.'.gif" style="border: 0;" />',$text);
}

function nifty2_fix_brackets($text) {
	return stripslashes(str_replace(']', '&#93;', str_replace('[', '&#91;', $text)));
}

function nifty2_nice_url($url) {
	$url = str_replace(' ', '%20', str_replace('\'', '&apos;', nifty2_fix_brackets(stripslashes($url))));

	if (!preg_match('/(^[a-z]+):/', $url)) {
		return 'http://'.$url;
	}

	return $url;
}

function nifty2_local_url($url) {
	$url = str_replace(' ', '%20', str_replace('\'', '&apos;', nifty2_fix_brackets(stripslashes($url))));

	return $url;
}
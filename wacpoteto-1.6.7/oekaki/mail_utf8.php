<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2011-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.4 - Last modified 2015-09-26
*/


function utf8_mail($from, $to, $subject, $message, $cc = '', $bcc = '') {
	// Wrapper for mail() that encodes all characters containing a high-order
	// bit to be MIME compatible.  Also enforces line length, as per RFC 2822.
	//
	// $cc and $bcc may be arrays.
	//
	// 2nd parameter is "Return-path", which is necessary for auto-mailers
	// so bounced messages will go to the owner of the e-mail address, not
	// back to the mailing server.  This ensures oekaki admins will be
	// notified of failed registrations.  Be aware that not all servers will
	// allow a custom return path, and if they do not, behavior is undefined
	// (silent failure, not sending mail, perhaps flagging for spam, etc.)
	// Use this feature with caution, as many hosts do not like it.

	$returnpath = '';
	$force_additional = '';
	if (defined('MAIL_BOUNCE_HANDLER_ENABLE') && MAIL_BOUNCE_HANDLER_ENABLE) {
		if (defined('MAIL_BOUNCE_HANDLER_ADDRESS') && MAIL_BOUNCE_HANDLER_ADDRESS) {
			$returnpath = trim(MAIL_BOUNCE_HANDLER_ADDRESS);
		} else {
			$returnpath = $from;
		}

		if (preg_match('/<([^>|^\s]+)>?/', $returnpath, $matches)) {
			if (strpos($matches[0], '@') !== false) {
				$returnpath = trim($matches[1]);
			}
		}
		$force_additional = '-f'.$returnpath;
	}

	$params = utf8_mail_prepare($from, $returnpath, $to, $subject, $message, $cc, $bcc);

	return @mail($params['to'], $params['subject'], $params['message'], $params['headers'], $force_additional);
}


function utf8_mail_prepare($from, $returnpath, $to, $subject, $message, $cc = '', $bcc = '') {
	// ToDo: may have to redo parameters of this function with $options array.
	//
	// This function is specific to Wacintaki and will only return RFC valid
	// headers with certain input.  Using this function as-is for other
	// projects is not recommended, as it does not properly wrap lines for
	// "CC:" and "BCC:", and I'm not even sure how PHP handles wrapping for
	// "To:".  Note that PEAR::Mail v2.0a currently does not support UTF-8
	// encoding, so if you find this script useful, by all means consider
	// using it.  Just be aware of the header line length issue.
	//
	// Lampshade alert: "\n" vs "\r\n" is a problem.
	// Many UN*X servers will behave oddly with "\r\n", even though this EOL
	// is required per RFC 2822.  For the best compatibility, leave it to the
	// sendmail program to fix EOLs.  Windows requires "\r\n" as PHP's mail()
	// function sends headers directly to the e-mail daemon.  Also, I don't
	// depend on PHP_EOF.  I develop on Windows, and I know from experience
	// that PHP_EOF has been set wrong on more than one official PHP build.

	$n = PLATFORM_OS_WIN ? "\r\n" : "\n";

	if (empty($from)) {
		trigger_error('From: field empty.', E_USER_WARNING);
		return false;
	}
	if (empty($to)) {
		trigger_error('To: field empty.', E_USER_WARNING);
		return false;
	}

	$to = utf8_mail_encode($to, true);
	$subject = utf8_mail_encode($subject, false);

	// Note: Wacintaki messages should contain "\n", not "\r\n"
	$encoding = '7bit';
	if (preg_match('![\x80-\xFF]!', $message)) {
		$encoding = 'base64';
		$message = chunk_split(base64_encode($message), 76, $n);
	}

	$headers = (
		 'MIME-Version: 1.0'.$n
		.'Content-Type: text/plain; charset=utf-8; format=flowed'.$n
		.'Content-Transfer-Encoding: '.$encoding.$n
	);

	if (!empty($from)) {
		$headers .= 'From: '.utf8_mail_encode($from, true).$n;
	}
	if (!empty($returnpath)) {
		$headers .= 'Return-Path: <'.utf8_mail_encode($returnpath, true).'>'.$n;
	}
	if (!empty($cc)) {
		$headers .= 'CC: '.utf8_mail_encode($cc, true).$n;
	}
	if (!empty($bcc)) {
		$headers .= 'BCC: '.utf8_mail_encode($bcc, true).$n;
	}
	$headers .= 'X-Mailer: Wacintaki'.$n;

	return array(
		 'to'      => $to
		,'subject' => $subject
		,'message' => $message
		,'headers' => $headers
	);
}


function utf8_mail_encode($in, $is_address) {
	// Properly encoded e-mails should have the length of each line limited to
	// 76 bytes, followed by "\r\n" (for PHP's mail() function, usually only
	// "\n" works).  For Base64 encoding, the final hash is in groups of four
	// bytes.  With the 'Subject: ' on the first line, the UTF-8 marker and
	// the space prefix on each additional line, this results in a max length
	// of 73 chars per line.
	//
	// 1        10       19                                  73
	// |        |        |                                   |
	// Subject: =?UTF-8?B?        (52 encoded bytes)        ?=
	//  =?UTF-8?B?                (60 encoded bytes)        ?=
	//  |        |
	//  2        11
	//
	// To make getting the line length easier, we'll add a fake
	// 'Subject: ' to the beginning of our line and remove it later.
	// Future versions of this script may allow custom headers.

	if (PLATFORM_OS_WIN) {
		$n = "\r\n";
		$in = preg_replace('!([^\r])\n!', '!\1\r\n!', $in);
	} else {
		$n = "\n";
		$in = str_replace("\r", '', $in);
	}


	if (is_array($in)) {
		if ($is_address) {
			$in = implode(', ', $in);
		} else {
			trigger_error('Parameter 1 may be an array only when encoding e-mail addresses.', E_USER_ERROR);
			return false;
		}
	}

	if ($is_address) {
		// Due to the way spaces are handled by encoded blocks, we need to
		// make sure we leave a space at the end of each encoded block AND
		// that no two encoded blocks are adjacent.  There must be normal
		// characters between each encoded block.
		//
		// I use this method for e-mail addresses, so that as much ASCII
		// text is readable as possible.  In the future, I may do some
		// analysis to determine if quoted_printable and different blocking
		// techniques would be better for a given string format.

		// Split everything by spaces
		$matches = explode(' ', $in);

		// Now put only the words together, not the delimiters
		$count = count($matches);
		for ($i = 0; $i < $count - 1; $i++) {
			if (empty($matches[$i])) {
				unset($matches[$i]);
			} elseif (mail_utf8_find_bit_word($matches[$i])
				&& mail_utf8_find_bit_word($matches[$i + 1]))
			{
				// Two adjacent encodable words.  Join them!
				$matches[$i + 1] = $matches[$i].' '.$matches[$i + 1];
				unset($matches[$i]);
			}
		}

		foreach ($matches as $match) {
			if (preg_match('![\x80-\xFF]!', $match)) {
				$new = '=?UTF-8?B?'
					.base64_encode($match)
					.'?=';
				$in = str_replace($match, $new, $in);
			}
		}
	}

	if (!$is_address) {
		// Subject line

		if (preg_match('![\x80-\xFF]!', $in)) {
			// Break the source string into chunks of 3
			$pos_in   = 0;
			$pos_line = 19;
			$len    = strlen($in);
			$chunk  = '';
			$chunks = intval($len / 3);
			$out    = 'Subject: =?UTF-8?B?';

			while ($chunks > 0) {
				// Handle line wrap
				if ($pos_line >= 70) {
					$pos_line = 11;
					$out .= "?={$n} =?UTF-8?B?";
				}

				// Read a chunk
				$chunk = substr($in, $pos_in, 3);
				$chunks--;
				$pos_in += 3;

				$out .= base64_encode($chunk);
				$pos_line += 4;
			}

			$tail = $len % 3;
			if ($tail > 0) {
				// Last chunk
				$out .= base64_encode(substr($in, $pos_in, $tail));
			}

			$in = $out.'?=';

			// Trim 'Subject: '
			$in = substr($in, 9, strlen($in) - 9);
		}
	}

	return $in;
}


function mail_utf8_find_bit_word($in) {
	if (strpos($in, '<') !== false || strpos($in, ',') !== false) {
		return false;
	}
	if (preg_match('![\x80-\xFF]!', $in)) {
		return true;
	}
	return false;
}
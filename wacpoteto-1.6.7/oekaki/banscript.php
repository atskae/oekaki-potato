<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
OekakiPoteto Ban Script
Made for Ranmaguy's Oekaki Poteto system
	by Travis Skare (travissk@yahoo.com)

Wacintaki Poteto modifications Copyright 2004-2012 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.13 - Last modified 2012-10-06
*/


// Init
$hosts_file = $cfg['res_path'].'/hosts.txt';
$ips_file   = $cfg['res_path'].'/ips.txt';


// Parse hosts
if (!$flags['owner'] && !empty($hostname)) {

	// No need if host lookup is turned off
	if (defined('ENABLE_DNS_HOST_LOOKUP') && ENABLE_DNS_HOST_LOOKUP) {
		if (filesize($hosts_file) > 0) {
			// File for hostnames
			$fd = fopen($hosts_file, 'r');
			if (!$fd) {
				w_exit( t('file_o_warn', 'hosts.txt'));
			} else {
				// File is open and there are more names.
				while (!feof($fd)) {
					// Assign current name to test variable.
					$buffer = trim(fgets($fd, 4096));

					if (!empty($buffer)) {
						// Check for comment
						$pos = strpos($buffer, '(');
						if ($pos !== false) {
							$buffer = trim(substr($buffer, 0, $pos));
						}

						$pos = strpos($buffer, '*');
						if ($pos === false) {
							// asterisk not found.
							// use the old string compare.
							if ($buffer == $hostname)
							{
								// Just a plug for our favorite people :)
								fclose($fd);
								all_done('banned.php');
							}
						} else {
							// There was an asterisk.
							// $pos contains the first (and only) occurance
							// of an asterisk, so we can compare the rightmost
							// positions of the strings.

							// Step one: see how many characters are to the right of the *.
							// Length of string: strlen($buffer);
							// First position of asterisk: $pos
							// Number of characters to the right of the *:
							//	(strlen($buffer)-($pos+1));
							$rightmost = strlen($buffer) - ($pos + 1);
							
							// Compare rightmost parts of the two strings.
							if (substr($buffer, strlen($buffer) - $rightmost) == substr($hostname, strlen($hostname) - $rightmost)) {
								// Just a plug for our favorite people :)
								fclose($fd);
								all_done('banned.php');
							}
						}
					}
				}
				fclose($fd);
			}
		}
	}
}



// Parse IPs
if (!$flags['owner']) {
	if (filesize($ips_file) > 0) {
		$fd = fopen($ips_file, 'r');
		if (!$fd) {
			w_exit( t('file_o_warn', 'ips.txt'));
		} else {
			// File is open and there are more ips
			while (!feof($fd)) {
				// Assign current name to test variable.
				$buffer = trim(fgets($fd, 256));

				if (!empty($buffer)) {
					// Check for comment
					$pos = strpos($buffer, '(');
					if ($pos !== false) {
						$buffer = trim(substr($buffer, 0, $pos));
					}

					$pos = strpos($buffer, '*');
					if ($pos === false) {
						// asterisk not found.
						// use the old string compare.
						if ($buffer == $address) {
							// Just a plug for our favorite people :)
							fclose($fd);
							all_done('banned.php');
						}
					} else {
						// Asterisk found.
						// Position will be how many characters are to the left.
						// This makes things easy.
						if (substr($buffer, 0, $pos) == substr($address, 0, $pos)) {
							// Just a plug for our favorite people :)
							fclose($fd);
							all_done('banned.php');
						}
					}
				}
			}
			fclose($fd);
		}
	}
}
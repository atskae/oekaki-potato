<?php // Include only
if (!defined('BOOT')) exit('No bootstrap.  Exiting.');

/*
Wacintaki Poteto - Copyright 2005-2018 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.6 (1.2.0 compatible) - Last modified 2018-05-20
*/


// HACK_CHECK is defined in boot.php
if (!defined('HACK_CHECK')) {
	exit('hacks.php: HACK_CHECK is not defined.');
} elseif (HACK_CHECK > 1) {
	// Hacks out of date
	exit('You need to update your hack file (read history.txt for more information)<br />(hacks file: 1, oekaki: '.HACK_CHECK.')');
}


//
// Debug
//
{
	// Debug only
	$debug   = false;
	$wactest = false; // Beta testers only!

	$maintenance_mode = false;
}


//
// General
//
{
	// Disables banning
	// (0-1) 0=Use ban list, 1=Ignore ban list
	define('BAN_OVERRIDE', 0);

	// Enable thumbnails on comment screen
	// (0-1) 0=Use pictures, 1=Use thumbnails
	define('THUMB_COMMENT', 0);

	// Disable Java applets (.jar)
	// (0-1) 0=Allow, 1=Disable
	define('APPLET_DISABLE_JAVA', 0);

	// Disable applet-native JPEG support.
	// Does not prevent JPEG retouch (saved as PNGs) or JPEG uploads
	// (0-1) 0=Save normally, 1=Save only PNGs
	define('APPLET_NO_JPEG', 0);

	// Disable approximation of applet size, and use CSS
	// May cause some browsers (exp. Mac) to crash!
	// (0-1) 0=Use hack, 1=Disable, use CSS
	define('DISABLE_APPLET_AREA_HACK', 0);

	// Disable error reporting for server e-mail (fixes PHP limitation)
	// (0=normal, 1=disabled)
	define('DISABLE_EMAIL_CONFIRMATION', 0);

	// Force written files to be readable to all server GIDs
	// Use if server runs PHP as owner and web server as other/nobody
	// (0=normal fopen(), 1=force chmod)
	define('GROUP_READABLE', 0);

	// Disable confirmation dialog when deleting comments
	// (0=ask, 1=do_not_ask)
	define('DISABLE_CMT_DEL_CONF', 0);

	// Custom background color when turning transparrent PNGs into JPEGs, as a
	// hex color value.  This prevents images from having black backgrounds,
	// which can make line art invisible (totally black thumbnails).
	// Default color is white.  This MUST be in the form '#aabbcc'.
	// Remove the hash (#) to disable custom colors.
	//
	// define('ALPHA_BASE', 'FFFFFF'); // No hash = disabled
	// define('ALPHA_BASE', 'DDDDDD'); // Light gray = shows white lineart
	define('ALPHA_BASE', '#FFFFFF');

	// Disables GZip/ZLib compression of HTML.  Use only in extremely rare
	// cases to solve server incompatibility (some "hardened" PHP installs
	// refuse to let scripts detect whether compression is already enabled
	// on the backend or not, resulting in double compression).
	// (0=normal, 1=disabled)
	define('DISABLE_HTML_COMPRESSION', 0);
}



//
// Member settings
//
{
	// Minimum age to view adult content
	// 18=Default, 0=Disable age check
	define('MIN_AGE_ADULT', 18);

	// Disable artist killing, may be a "re-registration" issue if 1
	// (0-1) 0=Autokill removes anyone, 1=Non-artists only
	define('NO_KILL_ARTISTS', 0);

	// Time in days that a member must log in to be considered "active"
	// Default is 180 days (~6 months)
	// Must be more than 0!
	define('ACTIVE_LOGIN_TIME_DAYS', 180);
}



//
// Language
//
{
	// Allow or disable guest language feature (drop-down menu in header)
	// (0-1) 0=Allow guests to choose language, 1=Disable
	define('DISABLE_GUEST_LANG', 0);
}


//
// Security
//
{
	// Allows superadmins to add PHP to notice, banner, and rules
	// USE AT OWN RISK, as this allows sadmins to hack and get owner flags!
	// (0-1) 0=Secure, 1=Enable PHP
	define('ENABLE_PHP_RESOURCE', 0);

	// Enables DNS host traces.  Allows banning entire ISPs or countries.
	// May drasticly reduce performance on some servers or for individual
	// members.  Largely useless and not recommended except for halting
	// major spam attacks.  Will not affect attacks via proxy servers.
	// (0-1) 0=Disabled, 1=Enable host traces
	define('ENABLE_DNS_HOST_LOOKUP', 0);
}


//
// Mail
//
{
	// Attempts to set the "Return-Path" field when using the auto-mailer,
	// so the owner, not the mail server, will receive bounced mails.
	// This probably will not work unless your hosting service adds your
	// account to the trusted user list, which is unlikely on a shared hosting
	// plan.  Enabling this if you are not trusted may result in an extra
	// header being added to the mail which could interfere with deliverly,
	// and possibly result in your account being flagged for spamming.  Use
	// with caution and check with your hosting company and their FAQ/forum
	// for info on how to proplery handle bounced mail.
	// (0-1) 0=Disabled (default), 1=Enabled
	define('MAIL_BOUNCE_HANDLER_ENABLE', 0);

	// An address to use for bounced e-mails, if different than the regular
	// owner e-mail address.  Not recommended.  Use a plain address with no
	// name annotation, special characters, or spaces, as this address will
	// be sent to the system shell (via PHP's mail command)!
	// (string) Leave blank to use the default e-mail
	define('MAIL_BOUNCE_HANDLER_ADDRESS', '');
}


//
// Anti-spam
//
{
	// Max number of links allowed by guests.
	// (0-number) 2=Default, 0=Disable guest links
	define('MAX_GUEST_LINKS', 2);

	// Max comment size, in bytes.
	// Note that PHP uses bytes, not characters, so this value should be
	// larger on boards that use special characters, such as Chinese.
	// 10000=Default
	define('MAX_COMMENT_BYTES', 10000);
}


//
// Cookies
//
{
	/*
	Set this to allow multiple boards to share one cookie, as OP does not natively support multiple boards.

	LEAVE THIS BLANK IF USING A SINGLE BOARD OR IF YOU'RE NOT SURE WHAT TO PUT IN!

	If set to anything other than <blank>, the online list will show which board a member is viewing.

	This hack requires a root-based path.  '/' means the root of the server, so '/folder' is the same as 'http://www.server.com/folder'.  'folder' may do something like 'http://www.server.com/account/oekaki/folder', and won't work.

	NOTE:  Don't use 'http://' or other direct URLs -- many web browsers will flag these cookies as spyware.
	*/

	// PATH
	{
		// Failsafe, or disable (default)
		$cookie_path = '';

		// For the whole server (be careful!) or subdomain (OK).
		//$cookie_path = '/';

		// Shared server that uses accounts (not subdomains).  Folders are CaSe SeNsItIvE!
		// $cookie_path = '/AccountName/';
	}

	// DOMAIN
	{
		// Failsafe, or disable (default)
		$cookie_domain = '';

		// For every subdomain.  Keep the period at the front for maximum compatibility.
		// $cookie_domain = '.myserver.com';
	}
}


//
// index.php
//
{
	// Use drop-down menu for page numbers.
	// (0-1) 0=Off, 1=On
	define('SELECT_PAGES', 0);

	// Use full spacing between avatars (looks good on some templates)
	// (0-1) 0=Off, 1=On
	define('FULL_AVATARS', 0);
}


//
// SMILIES
//
{
	// Use default smilies, or this custom list?
	// (0-1) 0= Defaults, 1=These smilies
	define('USE_SMILE_HACK', 0);

	if (USE_SMILE_HACK == 1) {
		$smilies_temp = array(
			':)'     => 'smile.gif',
			':|'     => 'neutral.gif',
			':('     => 'sad.gif',
			':D'     => 'big_smile.gif',
			'>:)' => 'smirk.gif',
			'>:D' => 'evil_smile.gif',
			':sneaky:'   => 'sneaky.gif',
			':o'     => 'yikes.gif',
			';)'     => 'wink.gif',
			':/'     => 'hmm.gif',
			':P'     => 'tongue.gif',
			':lol:'      => 'lol.gif',
			':mad:'      => 'mad.gif',
			':rolleyes:' => 'rolleyes.gif',
			':cool:'     => 'cool.gif',
			':duh:'      => 'duh.gif',
			':blush:'    => 'blush.gif',
			':cry:'      => 'cry.gif',
			':boohoo:'   => 'boohoo.gif',
			':crazy:'    => 'crazy.gif',
			':what:'     => 'what.gif',
			':gamer:'    => 'gamer.gif',
			':barf:'     => 'barf.gif' // No comma after last entry
		);

		$smilies_group = array();
		foreach ($smilies_temp as $s_in => $s_out) {
			$smilies_group[htmlspecialchars($s_in)] = $s_out;
		}
		unset($smilies_temp);
	}

	// Maximum smilies to be shown on comment pages.
	// Set to 0 to disable smilies on comment pages.
	define('MAX_SMILIES', 200);
}
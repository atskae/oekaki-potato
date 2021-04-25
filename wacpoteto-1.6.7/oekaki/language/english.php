<?php // ÜTF-8
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastea-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.14y - Last modified 2014-11-06 (y:2015-08-23)

v1.0.6 english additions by Kevin (kevinant@gmail.com)

=====

PHP Basics:

	The most important thing to keep in mind while translating is how PHP handles quotes.

	In PHP, using double quotes as delimiters means the text will be scanned for special characters.  Double quotes ("), the dollar sign ($), backslashes, and various ASCII symbols must all be preceeded by a backslash, as follows:

		$var = " \" ' \$ \\";

		Note that the single quote is the only character not escaped.

		\n = New line, \t = tab.  These are somtimes used in e-mail message bodies.

	Using single quotes as delimiters means all text EXCEPT single quotes and backslashes will be used literally.  The drawback is that you cannot use any ASCII excape codes.

		$var = ' " \' $ \\ ';

	To help prevent confusion, using double quotes as delimiters is recommended.  If you need to use apostrophes, such as for contractions, or the dollar sign, then use single quotes for the variable delimiters.  For performance reasons, the English language file often uses single quotes.

	Translations that include HTML entities require double quotes.  Read the section "HTML Basics" below.

	When using double quotes in text, HTML entities are recommended:

		  &quot;Hello!&quot;
		&ldquot;Hello!&rdquot;

	A text editor with PHP syntax highlighting support is recommended.

	All lines must end with a semicolon.  If you are have parsing problems, check for mismatched quotes and the semicolon.

Encoding:

	UTF-8 is mandatory.

	Some messages are sent to the oekaki applets and *must* be in 8-bit ISO-8859-1 or else the applets will behave unpredictably.  Check each variable's context notes for more information.

	JavaScript messages will use the same encoding as the web browser.

	Wacintaki uses HTML entities for left and right double quotes.  Simply replace "&ldquo;" and "&rdquo;" with the proper HTML entities for your language.  Spanish/French quotes (<< >>) are "&laquo;" and "&raquo".

	E-mails may only be sent as plain text, not HTML.  UTF-8 in subject lines and message bodies is supported.

HTML Basics:

	All HTML links and entity attributes MUST use double quotes, not single quotes!

		$var = "<a href=\"{1}\">Good</a>";
		$var = '<a href="{1}">Good</a>';
		$var = "<a href='{1}'>WRONG</a>";

	Wacintaki language files must be HTML encoded.  Use symbol entities if possible:

		MANDATORY:
			      &  ->  &amp;
			< and >  ->  &lt; and &gt;

		OPTIONAL:
			<a href="#">"</a>  ->  <a href="#">&quot;</a>
			"this" or "that"   ->  &quot;this&quot; or &ldquo;that&rdquo;
			  "Wac and Tawny"  ->  &laquo;Wac y Tawny&raquo; (Spanish quote entities)
			  10 x 20 = 200    ->  10 &times; 20 = 200

Substitution:

	Variables are inserted into translations with a number in brackets.  The number refers to the parameter number used with the t() function.

		$lang['phrase'] = "{1} comments on {2} posts";

	The order of inserts may be changed:

		$lang['phrase'] = "{2} posts, {1} comments";
		$lang['phrase'] = "Posts {2} < Comments {1} < Backwards is language this";

Plural Basics:

	Wacintaki supports two plural forms with the singular zero cluase.  Whether zero is singular or plural is set with "$lang['cfg_zero_plural']" below.

	The syntax is "{p?x:y}" where p is the parameter number, x is singular, and y is plural.

		$lang['phrase'] = '{1} {1?member is:members are}' awesome.';
		$lang['phrase'] = 'There {1?is:are} {1} {1?value:values} found.';
		$lang['phrase'] = 'Set {1} {1?value:values}' on {2} {2?system:systems}';

	Embedding works, but use with caution and try to make it readable:

		$lang['something'] = 'There {1?is {1} value:are several values}.';

	Obviously, do not use curley brackets or colons within plural expressions.  Only the first question mark is used by the code, so multiple question marks are fine.

	Be clear, not clever.  {1?story:stories} is easier to read than stor{1?y:ies}.

	The language system isn't very powerful.  Please simplify sentence structure to avoid gotchas.

Gender:

	Sometimes the plural system provides the gender of a user.  Current usage is that masculine and neuter is the singular, feminine is the plural.  Check the context notes.

		// {1}=Gender
		$lang['phrase'] = "Send {1?him:her} a message.";

	If multiple people are involved, the gender will be feminine only if *all* people are female.  Some trial and error with embedding may be required to acheive the result you want:

		// {1}=Number of people, {2}=gender
		$lang['phrase'] = '{1?{2?The:The}:{2?The:The}} administrator{1?:s}';
		$lang['phrase'] = "{1?{2?L':Les }:{2?La :Las }}administrateur{1?:s}";

		Note the change to double quotes to properly handle "L'" above.

=====

*/



// Init
if (!isset($lang) || isset($_REQUEST['lang'])) {
	// Language files may be imported more than once
	$lang = array();
}

// Language version
$lang['cfg_langver'] = '1.5.7';

// Language name (native encoding, capitalized if necessary)
$lang['cfg_language'] = 'English';

// English Language name (native encoding, capitalized)
$lang['cfg_lang_eng'] = 'English';

// Name of translator(s)
$lang['cfg_translator'] = 'Theo Chakkapark, Marc Leveille';

//$lang['cfg_language'].' translation by: '.$lang['cfg_translator'];
// context = Variables not needed. Change around order as needed.
$lang['footer_translation'] = 'English translation by: '.$lang['cfg_translator'];

// Comments (native encoding)
$lang['cfg_comments'] = 'Default English language pack for Wacintaki.';

// Zero plural form.  0=singular, 1=plural
// Multiple plural forms need to be considered in next language API
$lang['cfg_zero_plural'] = 1;

// HTML charset ("Content-type")
$charset = 'utf-8';

// HTML language name ("Content-language" or "lang" tags)
$metatag_language = 'en-us';

// Date formats (http://us.php.net/manual/en/function.date.php)
// Quick ref: j=day(1-31), l=weekday(Sun-Sat), n=month(1-12), F=month(Jan-Dec)
$datef['post_header'] = 'l, F jS Y, g:i A';
$datef['admin_edit']  = 'F j, Y, g:i a';
$datef['chat']        = 'H:i';
$datef['birthdate']   = 'Y/n/j';
$datef['birthmonth']  = 'Y/n';
$datef['birthyear']   = 'Y';

// Drop-down menu in registration / edit profile.
// "Y" and "M" and "D" in any order.  All 3 letters required.
$datef['age_menu'] = 'MDY';

// Left and Right double quotes
$lang['ldquo'] = '&ldquo;';
$lang['rdquo'] = '&rdquo;';



/* Language Translation */

//Wacintaki Installation
$lang['install_title'] = 'Wacintaki Installation';

//MySQL Information
$lang['install_information'] = 'MySQL Information';

//If you do not know if your server has MySQL or how to access your MySQL account, e-mail your tech. support and ask for the following information: hostname, database name, username, and password. Without this information, you will be unable to install OP. If you need to remove the databases, look at the bottom of the page for a link to remove them. If you haven't read the readme.txt, DO IT NOW or your installation will fail! You must make sure you have the proper files and directories CHMODed before you continue.
$lang['install_disclaimer'] = "If you do not know if your server has MySQL or how to access your MySQL account, e-mail your tech. support and ask for the following information: hostname, database name, username, and password. Without this information, you will be unable to install OP. If you need to remove the databases, look at the bottom of the page for a link to remove them. If you haven't read the readme.txt, DO IT NOW or your installation will fail! You must make sure you have the proper files and directories CHMODed before you continue.";

//If your OP currently works, there is no need to change the MySQL information.
$lang['cpanel_mysqlinfo'] = 'If your OP currently works, there is no need to change the MySQL information.';

//Default Language
$lang['cpanel_deflang'] = "Default Language";

//Artist
$lang['word_artist'] = "Artist";

//Compression Settings
$lang['compress_title'] = "Compression Settings";

//Date
$lang['word_date'] = "Date";

//Time
$lang['word_time'] = "Time";

//min
$lang['word_minutes'] = "min";

//unknown
$lang['word_unknown'] = "unknown";

//Age
$lang['word_age'] = "Age";

//Gender
$lang['word_gender'] = "Gender";

//Location
$lang['word_location'] = "Location";

//Joined
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_joined'] = "Joined";

//Language
$lang['word_language'] = "Language";

//Charset
$lang['word_charset'] = "Charset";

//Help
$lang['word_help'] = "Help";

//URL
$lang['word_url'] = "URL";

//Name
$lang['word_name'] = "Name";

//Action
$lang['word_action'] = "Action";

//Disable
$lang['word_disable'] = "Disable";

//Enable
$lang['word_enable'] = "Enable";

//Translator
$lang['word_translator'] = "Translator";

//Yes
$lang['word_yes'] = "Yes";

//No
$lang['word_no'] = "No";

//Accept
$lang['word_accept'] = "Accept";

//Reject
$lang['word_reject'] = "Reject";

//Owner
$lang['word_owner'] = "Owner";

//Type
$lang['word_type'] = "Type";

//AIM
$lang['word_aolinstantmessenger'] = "AIM";

//ICQ
$lang['word_icq'] = "ICQ";

//Skype
// note: previously MSN
$lang['word_microsoftmessenger'] = "Skype";

//Yahoo
$lang['word_yahoomessenger'] = "Yahoo";

//Username
$lang['word_username'] = "Username";

//E-mail
$lang['word_email'] = "E-Mail";

//Animated
$lang['word_animated'] = "Animated";

//Normal
$lang['word_normal'] = "Normal";

//Registered
$lang['word_registered'] = "Registered";

//Guests
$lang['word_guests'] = "Guests";

//Guest
$lang['word_guest'] = "Guest";

//Refresh
$lang['word_refresh'] = "Refresh";

//Comments
$lang['word_comments'] = "Comments";

//Animations
$lang['word_animations'] = "Animations";

//Archives
$lang['word_archives'] = "Archives";

//Comment
$lang['word_comment'] = "Comment";

//Delete
$lang['word_delete'] = "Delete";

//Reason
$lang['word_reason'] = "Reason";

//Special
$lang['word_special'] = "Special";

//Archive
$lang['word_archive'] = "Archive";

//Unarchive
$lang['word_unarchive'] = "Unarchive";

//Homepage
$lang['word_homepage'] = "Homepage";

//PaintBBS
$lang['word_paintbbs'] = "PaintBBS";

//OekakiBBS
$lang['word_oekakibbs'] = "OekakiBBS";

//Archived
$lang['word_archived'] = "Archived";

//IRC server
$lang['word_ircserver'] = "IRC Server";

//days
$lang['word_days'] = "days";

//Commenting
$lang['word_commenting'] = "Commenting";

//Paletted
$lang['word_paletted'] = "Paletted";

//IRC nickname
$lang['word_ircnickname'] = "IRC Nickname";

//Template
$lang['word_template'] = "Template";

//IRC channel
$lang['word_ircchannel'] = "IRC Channel";

//Horizontal
$lang['picview_horizontal'] = "Horizontal";

//Vertical
$lang['picview_vertical'] = "Vertical";

//Male
$lang['word_male'] = "Male";

//Female
$lang['word_female'] = "Female";

//Error
$lang['word_error'] = "Error";

//Board
$lang['word_board'] = "Board";

//Ascending
$lang['word_ascending'] = "Ascending";

//Descending
$lang['word_descending'] = "Descending";

//Recover for {1}
$lang['recover_for'] = "Recover for {1}";

//Flags
$lang['word_flags'] = "Flags";

//Admin
$lang['word_admin'] = "Admin";

//Background
$lang['word_background'] = "Background";

//Font
$lang['word_font'] = "Font";

//Links
$lang['word_links'] = "Links";

//Header
$lang['word_header'] = "Header";

//View
$lang['word_view'] = "View";

//Search
$lang['word_search'] = "Search";

//FAQ
$lang['word_faq'] = "FAQ";

//Memberlist
$lang['word_memberlist'] = "Memberlist";

//News
$lang['word_news'] = "News";

//Drawings
$lang['word_drawings'] = "Drawings";

//Submenu
$lang['word_submenu'] = "Submenu";

//Retouch
$lang['word_retouch'] = "Retouch";

//Picture
$lang['word_picture'] = "Picture";



/* niftyusage.php */

//Link to Something
$lang['lnksom'] = "Link to Something";

//URLs without {1} tags will link automatically.
// {1} = "[url]"
$lang['urlswithot'] = "URLs without {1} tags will link automatically.";

//Text
$lang['nt_text'] = "Text";

//Bold text
// note: <b> tag
$lang['nt_bold'] = "Bold text";

//Italic text
// note: <i> tag
$lang['nt_italic'] = "Italic text";

//Underlined text
// note: <u> tag
$lang['nt_underline'] = "Underline text";

//Strikethrough text
// note: <del> tag
$lang['nt_strikethrough'] = "Strikethrough text";

//Big text
// note: <big> tag
$lang['nt_big'] = "Big text";

//Small text
// note: <small> tag
$lang['nt_small'] = "Small text";

//Quoted text
// note: <blockquote> tag
$lang['nt_quoted'] = "Quoted text";

//Preformatted text
// note1: <code> tag (formerly <pre>).
// note2: "Monospaced" or "Fixed Width" would also be appropriate.
$lang['nt_preformatted'] = "Preformatted text";

//Show someone how to quote
// Context = example of double brackets: "[[quote]]translation[[/quote]]"
$lang['nt_d_quote'] = "Show someone how to quote";

//These tags don't exist
// context = example of double brackets: "[[ignore]]translation[[/ignore]]"
$lang['nt_d_ignore'] = "These tags don't exist";

//ignore
// context = Example tag for double brackets: "[[ignore]]"
// translate to any word/charset if desired.
$lang['nt_ignore_tag'] = "ignore";

//Use double brackets to make Niftytoo ignore tags.
$lang['nt_use_double'] = "Use double brackets to make Niftytoo ignore tags.";

/* END niftyusage.php */



//Mailbox
$lang['word_mailbox'] = "Mailbox";

//Inbox
$lang['word_inbox'] = "Inbox";

//Outbox
$lang['word_outbox'] = "Outbox";

//Subject
$lang['word_subject'] = "Subject";

//Message
$lang['word_message'] = "Message";

//Reply
$lang['word_reply'] = "Reply";

//From
$lang['word_from'] = "From";

//Write
$lang['word_write'] = "Write";

//To
$lang['word_to'] = "To";

//Status
$lang['word_status'] = "Status";

//Edit
$lang['word_edit'] = "Edit";

//Register
$lang['word_register'] = "Register";

//Administration
$lang['word_administration'] = "Administration";

//Draw
$lang['word_draw'] = "Draw";

//Profile
$lang['word_profile'] = "Profile";

//Local
$lang['word_local'] = "Local";

//Edit Pics
$lang['header_epics'] = "Edit Pics";

//Recover Pics
$lang['header_rpics'] = "Recover Pics";

//Delete Pics
$lang['header_dpics'] = "Delete Pics";

//Delete Comments
$lang['header_dcomm'] = "Delete Comments";

//Edit Comments
$lang['header_ecomm'] = "Edit Comments";

//View Pending
$lang['header_vpending'] = "View Pending";

//Re-Touch
$lang['word_retouch'] = "Re-Touch";

//Logout
$lang['word_logoff'] = "Logout";

//Modify Flags
$lang['common_mflags'] = "Modify Flags";

//Delete User
$lang['common_delusr'] = "Delete User";

//(include the http://)
$lang['common_http'] = "(include the http://)";

//Move to page
$lang['common_moveto'] = "Move to page";

//Scroll Down
$lang['chat_scroll'] = "Scroll Down";

//Conversation
$lang['chat_conversation'] = "Conversation";

//Chat Information (required)
$lang['chat_chatinfo'] = "Chat Information (required)";

//Move to Page
$lang['common_mpage'] = "Move to Page";

//Delete Picture
$lang['common_deletepic'] = "Delete Picture";

//Picture Number
$lang['common_picno'] = "Picture Number";

//Close this Window
$lang['common_window'] = "Close this Window";

//Last Login
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['common_lastlogin'] = "Last Login";

//Picture Posts
$lang['common_picposts'] = "Picture Posts";

//Comment Posts
$lang['common_compost'] = "Comment Posts";

//Join Date
$lang['common_jdate'] = "Join Date";

//You can use the message box to leave requests/comments, or talk to Wacintaki members if they are online.<b> All comments will be deleted after a specific amount of posts.<br /></b><br /><b>*</b> - Indicates a registered member.<br /><b>#</b> - Indicates a Guest<br /><br />Although you can see the current online registered members in the chat, Guests who are still online will dissapear after a specific amount of time. Be aware that everyime a guest posts, there WILL be multiple instances of the user under Guest. <br /><br />Your IP and Hostname are tracked in case of abuse. To see a user's IP/hostname, hover your mouse over their username in the chat. The rate of refresh is 15 seconds.
$lang['chat_warning'] = "You can use the message box to leave requests/comments, or talk to Wacintaki members if they are online.<b> All comments will be deleted after a specific amount of posts.<br /></b><br /><b>*</b> - Indicates a registered member.<br /><b>#</b> - Indicates a Guest<br /><br />Although you can see the current online registered members in the chat, Guests who are still online will dissapear after a specific amount of time. Be aware that everyime a guest posts, there WILL be multiple instances of the user under Guest. <br /><br />Your IP and Hostname are tracked in case of abuse. To see a user's IP/hostname, hover your mouse over their username in the chat. The rate of refresh is 15 seconds.";

//Database Hostname
$lang['install_dbhostname'] = "Database Hostname";

//Database Name
$lang['install_dbname'] = "Database Name";

//Database Username
$lang['install_dbusername'] = "Database Username";

//Database Password
$lang['install_dbpass'] = "Database Password";

//Display - Registration (Required)
$lang['install_dispreg'] = "Display - Registration (Required)";

//URL to Wacintaki
$lang['install_opurl'] = "URL to Wacintaki";

//Registration E-Mail
$lang['install_email'] = "Registration E-Mail";

//the e-mail address to use to send out registration information; this is REQUIRED if you are using automatic registration
$lang['install_emailsub'] = "the e-mail address to use to send out registration information; this is REQUIRED if you are using automatic registration";

//General
$lang['install_general'] = "General";

//Encryption Key
$lang['install_salt'] = "Encryption Key";

//An encryption key can be any combination of characters; it is used to generate unique encryption strings for passwords. If you are using multiple boards with the same member database, make sure all boards share the same encryption key.
$lang['install_saltsub'] = "An encryption key can be any combination of characters; it is used to generate unique encryption strings for passwords. If you are using multiple boards with the same member database, make sure all boards share the same encryption key.";

//Picture Directory
$lang['install_picdir'] = "Picture Directory";

//directory where your pictures will be stored
$lang['install_picdirsub'] = "directory where your pictures will be stored";

//Number of pictures to store
$lang['install_picstore'] = "Number of pictures to store";

//the max number of pictures the OP can store at a time
$lang['install_picstoresub'] = "the max number of pictures the board can store at a time";

//Registration
$lang['install_reg'] = "Registration";

//Automatic User Delete
$lang['install_adel'] = "Automatic User Delete";

//user must login within the specified number of days before being deleted from the database
$lang['install_adelsub'] = "user must login within the specified number of days before being deleted from the database";

//days <b>(-1 disables the automatic delete)</b>
$lang['install_adelsub2'] = "days <b>(-1 disables the automatic delete)</b>";

//Allow Guests to Post?
$lang['install_gallow'] = "Allow Guests to Post?";

//if yes, guests can make comment posts on the board and chat
$lang['install_gallowsub'] = "if yes, guests can make comment posts on the board and chat";

//Require Approval? (Select no for Automatic Registration)
$lang['install_rapproval'] = "Require Approval? (Select no for Automatic Registration)";

//if yes, approval by the administrators is required to register
$lang['install_rapprovalsub'] = "if yes, approval by the administrators is required to register";

//Display - General
$lang['install_dispgen'] = "Display - General";

//Default Template
$lang['install_deftem'] = "Default Template";

//templates are stored in the templates directory
$lang['install_deftemsub'] = "templates are stored in the templates directory";

//Title
$lang['install_title2'] = "Title";

//Title for the Wacintai.  Avoid using symbols or decoration, as it will be used for e-mail headers.
$lang['install_title2sub'] = "Title for the Wacintai.  Avoid using symbols or decoration, as it will be used for e-mail headers.";

//Display - Chat
$lang['install_dispchat'] = "Display - Chat";

//Max Number of Lines to Store for Chat
$lang['install_displinesmax'] = "Max Number of Lines to Store for Chat";

//Lines of Chat Text to Display in a Page
$lang['install_displines'] = "Lines of Chat Text to Display in a Page";

//Paint Applet Settings
$lang['install_appletset'] = "Paint Applet Settings";

//Maximum Animation Filesize
$lang['install_animax'] = "Maximum Animation Filesize";

//the max filesize animation files can be in bytes; default is 500,000 bytes or 500KB
$lang['install_animaxsub'] = "the max filesize animation files can be in bytes; default is 500,000 bytes or 500KB";

//bytes (1024 bytes = 1KB)
$lang['install_bytes'] = "bytes (1024 bytes = 1KB)";

//Administrator Information
$lang['install_admininfo'] = "Administrator Information";

//Login
$lang['install_login'] = "Login"; // COMMON

//Password
$lang['install_password'] = "Password"; // COMMON

//Recover Password
$lang['header_rpass'] = "Recover Password";

//Re-Type Password
$lang['install_repassword'] = "Re-Type Password";

//TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms.
$lang['install_TOS'] = "TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms.";

//Databases Removed!
$lang['install_dbremove'] = "Databases Removed!";

//View Pending Users: Select a User
$lang['addusr_vpending'] = "View Pending Users: Select a User";

//View Pending Users: Details
$lang['addusr_vpendingdet'] = "View Pending Users: Details";

//Art URL
$lang['addusr_arturl'] = "Art URL";

//Art URL (Optional)
$lang['reg_arturl_optional'] = "Art URL (Optional)";

//Art URL (Required)
$lang['reg_arturl_required'] = "Art URL (Required)";

//Draw Access
$lang['common_drawacc'] = "Draw Access";

//Animation Access
$lang['common_aniacc'] = "Animation Access";

//Comments (will be sent to the registrant)
$lang['addusr_comment'] = "Comments (will be sent to the registrant)";

//Edit IP Ban List
$lang['banip_editiplist'] = "Edit IP Ban List";

//Use one IP per line.  Comments may be enclosed in parentheses at end of line.
$lang['banip_editiplistsub'] = 'Use one IP per line.  Comments may be enclosed in parentheses at end of line.';

//Usage Example: <strong style="text-decoration: underline">212.23.21.* (Username - banned for generic name!)</strong>
$lang['banip_editiplistsub2'] = 'Usage Example: <strong style="text-decoration: underline">212.23.21.* (Name123 - banned for generic name!)</strong>';

//Edit Host Ban List
$lang['banip_edithostlist'] = "Edit Host Ban List";

//Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!
$lang['banip_edithostlistsub'] = 'Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!';

//Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>
$lang['banip_edithostlistsub2'] = 'Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>';

//Ban List
$lang['header_banlist'] = "Ban List";

//Control Panel
$lang['header_cpanel'] = "Control Panel";

//Send OPMail Notice
$lang['header_sendall'] = "Send OPMail Notice";

//<b>You have been banned!<br /><br />Reasons:<br /></b>- A user from your ISP was banned, which banned everyone on that ISP<br />- You were banned for malicious use of the oekaki<br /><br /><em>If you feel that this message was made in error, speak to an adminstrator of the oekaki.</em>
$lang['banned'] = "<b>You have been banned!<br /><br />Reasons:<br /></b>- A user from your ISP was banned, which banned everyone on that ISP<br />- You were banned for malicious use of the oekaki<br /><br /><em>If you feel that this message was made in error, speak to an adminstrator of the oekaki.</em>";

//Retrieve Lost Password
$lang['chngpass_title'] = "Retrieve Lost Password";

//Because your password is encrypted, there is no way to retrieve it. Instead, you must specify a new password. If you receive no errors when submitting this form, that means your password has successfully changed and you can login with it once you are redirected to the index page.
$lang['chngpass_disclaimer'] = "Because your password is encrypted, there is no way to retrieve it. Instead, you must specify a new password. If you receive no errors when submitting this form, that means your password has successfully changed and you can login with it once you are redirected to the index page.";

//New Password
$lang['chngpass_newpwd'] = "New Password";

//Add Comment
$lang['comment_add'] = "Add Comment";

//Title of Picture
$lang['comment_pictitle'] = "Title of Picture";

//Adult Picture?
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['comment_adult'] = "Adult Picture?";

//Comment Database
$lang['comment_database'] = "Comment Database";

//Global Picture Database
$lang['gpicdb_title'] = "Global Picture Database";

//Delete User
$lang['deluser_title'] = "Delete User";

//will be sent to the deletee
$lang['deluser_mreason'] = "will be sent to the deletee";

//Clicking delete will remove all records associated with the user, including pictures, comments, etc. An e-mail will be sent to the user appened with your contact e-mail in the case the deletee has further questions on the removal.
$lang['deluser_disclaimer'] = "Clicking delete will remove all records associated with the user, including pictures and comments. An e-mail will be sent to the user appened with your contact e-mail in the case the deletee has further questions about the removal.";

//Animated NoteBBS
$lang['online_aninbbs'] = "Animated NoteBBS";

//Normal OekakiBBS
$lang['online_nmrlobbs'] = "Normal OekakiBBS";

//Animated OekakiBBS
$lang['online_aniobbs'] = "Animated OekakiBBS";

//Normal PaintBBS
$lang['online_npaintbbs'] = "Normal PaintBBS";

//Palette PaintBBS
$lang['online_palpaintbbs'] = "Palette PaintBBS";

//Admin Pic Recover
$lang['online_apicr'] = "Admin Pic Recover";

//Edit Notice
$lang['enotice_title'] = "Edit Notice";

//Edit Profile
$lang['eprofile_title'] = "Edit Profile";

//URL Title
$lang['eprofile_urlt'] = "URL Title";

//IRC Information
$lang['eprofile_irctitle'] = "IRC Information";

//Current Template
$lang['eprofile_curtemp'] = "Current Template";

//Current Template Details
$lang['eprofile_curtempd'] = "Current Template Details";

//Select New Template
$lang['eprofile_templsel'] = "Select New Template";

//Comments / Preferences
$lang['eprofile_compref'] = "Comments / Preferences";

//Picture View Mode
$lang['eprofile_picview'] = "Picture View Mode";

//Allow Adult Images
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adult'] = "Allow Adult Images";

//Change Password
$lang['eprofile_chngpass'] = "Change Password";

//Old Password
$lang['eprofile_oldpass'] = "Old Password";

//Retype Password
$lang['eprofile_repass'] = "Retype Password";

//You will be automatically logged out if your password successfully changes; you need to re-login when this occours.
$lang['eprofile_pdisc'] = "You will be automatically logged out if your password successfully changes; you need to re-login when this occurs.";

//Use your browser button to go back.
$lang['error_goback'] = "Use your browser button to go back.";

//Who's Online (last 15 minutes)
$lang['online_title'] = "Who's Online (last 15 minutes)";

//View Animation
$lang['viewani_title'] = "View Animation";

//file size
$lang['viewani_files'] = "File Size";

//Register New User
$lang['register_title'] = "Register New User";

//A VALID E-MAIL ADDRESS IS REQUIRED TO REGISTER!
$lang['register_sub2'] = "A VALID E-MAIL ADDRESS IS REQUIRED TO REGISTER!";

//Will be shown on your profile when registering; this comment box is limited to 80 chars for proper introduction so Admins can identify you; your IP and hostname is also tracked for security purposes.
$lang['register_sub3'] = "Will be shown on your profile when registering; this comment box is limited to 80 chars for proper introduction so Admins can identify you; your IP and hostname is also tracked for security purposes.";

//Include a URL to a picture or website that displays a piece of your work that you have done.
$lang['register_sub4'] = "Include a URL to a picture or website that displays a piece of your work that you have done.";

//THIS IS NECESSARY TO REQUEST ACCESS TO DRAW ON OEKAKI.
$lang['register_sub5'] = "THIS IS NECESSARY TO REQUEST ACCESS TO DRAW ON OEKAKI.";

//Picture Recovery
$lang['picrecover_title'] = "Picture Recovery";

//Profile for {1}
// {2} = Gender. Singular=Male/Unknown, Plural=Female
$lang['profile_title'] = "Profile for {1}";

//send a message
$lang['profile_sndmsg'] = "send a message";

//Latest Pictures
$lang['profile_latest'] = "Latest Pictures";

//Modify Applet Size
$lang['applet_size'] = "Modify Applet Size";

//Using Niftytoo
$lang['niftytoo_title'] = "Using Niftytoo";

//Nifty-markup is a universal markup system for Wacintaki. It allows for all the basic formatting you could want in your messages, profiles, and text.
$lang['niftytoo_titlesub'] = "Nifty-markup is a universal markup system for Wacintaki. It allows for all the basic formatting you could want in your messages, profiles, and text.";

//Linking/URLs
$lang['niftytoo_linking'] = "Linking/URLs";

//To have a url automatically link, just type it in, beginning with http://
$lang['niftytoo_autolink'] = "To have a URL automatically link, just type it in, beginning with http://";

//Basic Formatting
$lang['niftytoo_basicfor'] = "Basic Formatting";

//Change a font's color to the specified <em>colorcode</em>.
$lang['niftytoo_textcol'] = "Change a font's color to the specified <em>colorcode</em>.";

//will produce
$lang['niftytoo_produce'] = "will produce";

//Intermediate Formatting
$lang['niftytoo_intermform'] = "Intermediate Formatting";

//Modify Permissions
$lang['niftytoo_permissions'] = "Modify Permissions";

//Recover Any Pic
$lang['header_rapic'] = "Recover Any Pic";

//Super Administrator
$lang['type_sadmin'] = "Super Administrator";

//Owner
$lang['type_owner'] = "Owner";

//Administrator
$lang['type_admin'] = "Administrator";

//Draw Access
$lang['type_daccess'] = "Draw Access";

//Animation Access
$lang['type_aaccess'] = "Animation Access";

//Immunity
$lang['type_immunity'] = "Immunity";

//Adult Viewing
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['type_adultview'] = "Adult Viewing";

//General User
$lang['type_guser'] = "General User";

//A Super Administrator has the ability to add administrators as well as the abilities that Administrators have.
$lang['type_sabil'] = "A Super Administrator has the ability to add administrators as well as the abilities that Administrators have.";

//Removing this permission will suspend their account.
$lang['type_general'] = "Removing this permission will suspend their account.";

//Gives the user access to draw.
$lang['type_gdaccess'] = "Gives the user access to draw.";

//Gives the user access to animate.
$lang['type_gaaccess'] = "Gives the user access to animate.";

//Prevents a user from being deleted if the Kill Date is set.
$lang['type_userkill'] = "Prevents a user from being deleted if the Kill Date is set.";

//Member List
$lang['mlist_title'] = "Member List";

//Pending Member
$lang['mlist_pending'] = "Pending Member";

//Send Mass Message
$lang['massm_smassm'] = "Send Mass Message";

//The message subject
$lang['mail_subdesc'] = "The message subject";

//The body of the message
$lang['mail_bodydesc'] = "The body of the message";

//Send Message
$lang['sendm_title'] = "Send Message";

//The recipient of the message
$lang['sendm_recip'] = "The recipient of the message";

//Read Message
$lang['readm_title'] = "Read Message";

//Retrieve Lost Password
$lang['lostpwd_title'] = "Retrieve Lost Password";

//An e-mail will be sent to the e-mail address you have in your profile. If you did not specify an e-mail address when you registered, you will have to re-register for a new account. The e-mail will contain a URL to where you can specify a new password, as well as the IP and hostname of the computer used to request the password for security purposes.
$lang['lostpwd_directions'] = "An e-mail will be sent to the e-mail address you have in your profile. If you did not specify an e-mail address when you registered, you will have to re-register for a new account. The e-mail will contain a URL to where you can specify a new password, as well as the IP and hostname of the computer used to request the password for security purposes.";

//Local Comment Database
$lang['lcommdb_title'] = "Local Comment Database";

//Language Settings
$lang['eprofile_langset'] = "Language Settings";



/* functions.php */

//A subject is required to send a message.
$lang['functions_err1'] = "A subject is required to send a message.";

//You cannot use mass mailing.
$lang['functions_err2'] = "You cannot use mass mailing.";

//Access Denied. You do not have permissions to modify archives.
$lang['functions_err3'] = "Access Denied. You do not have permissions to modify archives.";

//The username you are trying to retrieve to does not exist. Please check your spelling and try again.
$lang['functions_err4'] = "The username you are trying to retrieve to does not exist. Please check your spelling and try again.";

//Your new and retyped passwords do not match. Please go back and try again.
$lang['functions_err5'] = "Your new and retyped passwords do not match. Please go back and try again.";

//Invalid retrival codes. This message will only appear if you have attempted to tamper with the password retrieval system.
$lang['functions_err6'] = "Invalid retrieval codes. This message will only appear if you have attempted to tamper with the password retrieval system.";

//The username you are trying to send to does not exist. Please check your spelling and try again.
$lang['functions_err9'] = "The username you are trying to send to does not exist. Please check your spelling and try again.";

//You need to be logged in to send messages.
$lang['functions_err10'] = "You need to be logged in to send messages.";

//You cannot access messages in the mailbox that do not belong to you.
$lang['functions_err11'] = "You cannot access messages in the mailbox that do not belong to you.";

//Access Denied. You do not have permissions to delete users.
$lang['functions_err12'] = "Access Denied. You do not have permissions to delete users.";

//Access Denied: Your password is invalid, or you are still a pending member.
$lang['functions_err13'] = "Access Denied: Your password is invalid, or you are still a pending member.";

//Invalid verification code.
$lang['functions_err14'] = "Invalid verification code.";

//The e-mail address specified in registration already exists in the database. Please re-register with a different address.
$lang['functions_err15'] = "The e-mail address specified in registration already exists in the database. Please re-register with a different address.";

//You do not have the credentials to add or remove users.
$lang['functions_err17'] = "You do not have the credentials to add or remove users.";

//You cannot claim a picture that is not yours.
$lang['functions_err18'] = "You cannot claim a picture that is not yours.";

//You cannot delete a comment that does not belong to you if you are not an Administrator.
$lang['functions_err19'] = "You cannot delete a comment that does not belong to you if you are not an Administrator.";

//You cannot delete a picture that does not belong to you if you are not an Administrator.
$lang['functions_err20'] = "You cannot delete a picture that does not belong to you if you are not an Administrator.";

//You cannot edit a comment that does not belong to you.
$lang['functions_err21'] = "You cannot edit a comment that does not belong to you.";

//{1} Password Recovery
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['precover_title'] = '{1} Password Recovery';

//Dear {1},\n\nYou or someone with the IP/hostname [{6}] has requested for a password retrieve on {2 @ {3}. To retrieve your password, please copy and paste or click on this link into your browser:\n\n{4}\n\nYou will then be asked to specify a new password. If you did not request this, you may discard this message.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
// {6} = IP address
$lang['pw_recover_email_message'] = "Dear {1},\n\nYou or someone with the IP/hostname [{6}] has requested for a password retrieve on {2} @ {3}. To retrieve your password, please copy and paste or click on this link into your browser:\n\n{4}\n\nYou will then be asked to specify a new password. If you did not request this, you may discard this message.";

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['mandel_title'] = '{1} Deletion Notice';

//Dear {1},\n\nYour account has been deleted from {2} @ {3}. If you have any questions, please e-mail the administrator that removed your account..\n\nDeleted by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['act_delete_email_message'] = "Dear {1},\n\nYour account has been deleted from {2} @ {3}. If you have any questions, please e-mail the administrator that removed your account.\n\nDeleted by: {4} ({5})\nComments: {6}";

//{1} Registration Details
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['autoreg_title'] = '{1} Registration Details';

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{4}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: Automated Registration
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = permissions
$lang['auto_accept_email_message'] = "Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{4}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: Automated Registration";

//{1} Verification Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['verreg_title'] = '{1} Verification Notice';

//Dear {1},\n\nYou have registered for {2} @ {3}. To complete your registration, please copy and paste or click on this link into your browser:\n\n{4}\n\nThis will verify your account so you can login into the oekaki.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
$lang['ver_email_message'] = "Dear {1},\n\nYou have registered for {2} @ {3}. To complete your registration, please copy and paste or click on this link into your browser:\n\n{4}\n\nThis will verify your account so you can login into the oekaki.";

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{7}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = comments
// {7} = permissions
$lang['admin_accept_email_message'] = "Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{7}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: {4} ({5})\nComments: {6}";

//Dear {1},\n\nYour registration at {2} @ {3}, has been rejected. Please e-mail the {2} administrator who rejected you for more details. DO NOT reply to this e-mail address.\n\nRejected by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['reg_reject_email_message'] = "Dear {1},\n\nYour registration at {2} @ {3}, has been rejected. Please e-mail the {2} administrator who rejected you for more details. DO NOT reply to this e-mail address.\n\nRejected by: {4} ({5})\nComments: {6}";

//Your picture has been removed
// NOTE: mailbox subject.  BBCode only.  No newlines.
$lang['picdel_title'] = 'Your picture has been removed';

//Hello,\n\nYour picture ({1}) has been removed from the database by {2} for the following reason:\n\n{3}\n\nIf you have any questions/comments regarding the action, you may reply to this message.
// NOTE: mailbox message.  BBCode only, and use \n rather than <br />.
// {1} = url
// {2} = admin name
// {3} = reason
$lang['picdel_admin_note'] = "Hello,\n\nYour picture ({1}) has been removed from the database by {2} for the following reason:\n\n{3}\n\nIf you have any questions/comments regarding the action, you may reply to this message.";

//(No reason specified)
$lang['picdel_admin_noreason'] = '(No reason specified)';

//Safety save
$lang['to_wip_admin_title'] = 'Safety save';

//One of your pictures has been turned into a safety save by {1}. To finish it, go to the draw screen. It must be finished within {2} days.
$lang['to_wip_admin_note'] = 'One of your pictures has been turned into a safety save by {1}. To finish it, go to the draw screen. It must be finished within {2} days.';

/* END functions.php */



/* maint.php */

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['kill_title'] = "{1} Deletion Notice";

//Dear {1},\n\nThis is an automated message from the {2} automatic deletion system. Your account has been deleted because you have not logged into the oekaki within the last {3} days. If you want to re-register, please visit {4}\n\nBecause the account has been deleted, all post, comment, and other records associated with your username has been removed, and cannot be re-claimed. To avoid further deletions upon re-registration, be sure to log into your account within the specified amount of days in the FAQ.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['kill_email_message'] = "Dear {1},\n\nThis is an automated message from the {2} automatic deletion system. Your account has been deleted because you have not logged into the oekaki within the last {3} days. If you want to re-register, please visit {4}\n\nBecause the account has been deleted, all post, comment, and other records associated with your username has been removed, and cannot be re-claimed. To avoid further deletions upon re-registration, be sure to log into your account within the specified amount of days in the FAQ.\n\nSincerely,\n{2}";

//{1} Registration Expired
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['regexpir'] = "{1} Registration Expired";

//Dear {1},\n\nYour registration at {2} has expired becuase you did not activate your account within {3} days. To submit a new registration, please visit {4}\n\nIf you did not receive a link to activate your account in a seperate e-mail, try using a different address or check the anti-spam settings used for your e-mail.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['reg_expire_email_message'] = "Dear {1},\n\nYour registration at {2} has expired becuase you did not activate your account within {3} days. To submit a new registration, please visit {4}\n\nIf you did not receive a link to activate your account in a seperate e-mail, try using a different address or check the anti-spam settings used for your e-mail.\n\nSincerely,\n{2}";

/* END maint.php */



/* FAQ */

//Frequently Asked Questions
$lang['faq_title'] = 'Frequently Asked Questions';

//<strong>Current Wacintaki version: {1}</strong>
$lang['faq_curver'] = '<strong>Current Wacintaki version: {1}</strong>';

//<strong>This oekaki deletes inactive accounts after {1} days.  Log in regularly to keep your account active.</strong>
$lang['faq_autoset'] = '<strong>This oekaki deletes inactive accounts after {1} days.  Log in regularly to keep your account active.</strong>';

//<strong>No automatic deletion is set.</strong>
$lang['faq_noset'] = '<strong>No automatic deletion is set.</strong>';

//Get the latest Java for running oekaki applets.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_java'] = 'Get the latest Java for running oekaki applets.';

//JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_jtablet'] = 'JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.';

//Table of Contents
$lang['faq_toc'] = 'Table of Contents';

// -----

//What is an &ldquo;oekaki?&rdquo;
$lang['faq_question'][0] = 'What is an &ldquo;oekaki?&rdquo;';

$lang['faq_answer'][0] = '<p>Oekaki is a Japanese term that roughly translates to &ldquo;doodle&rdquo; or &ldquo;scribble.&rdquo;  On the Internet, oekaki boards are bulletin boards or forums dedicated to drawing pictures that are drawn using a paint program that runs in a web browser.  Traditionally, early oekakis were simple pictures drawn with few colors on a canvas measuring 300 &times; 300 pixels, and they were posted sequentially, rather than in a threaded gallery format.  Oekaki boards started showing up on Japanese web sites around 1998, were only available in Japanese, and were often dedicated to anime and video game fan art.</p>

<p>Today, the drawing programs are much more sophisticated and allow more complex drawings of different sizes and in multiple layers, but the oekaki bulletin board format usually remains the same.  Oekakis are largely dedicated to original work you have drawn yourself.  Photographs or work you did not create yourself are discouraged, and in most cases are not allowed.  Some oekaki boards allow you to upload pictures from your computer, but most do not.</p>

<p>The term &ldquo;oekaki&rdquo; may refer to both oekaki drawings and an oekaki board.  When someone draws an oekaki, they are drawing a picture.  When someone draws on an Oekaki, or looks at oekakis, they are usually referring to participating on an oekaki board.</p>';


//What is Wacintaki, and why is it sometimes called Wacintaki Poteto?
$lang['faq_question'][1] = 'What is Wacintaki, and why is it sometimes called Wacintaki Poteto?';

$lang['faq_answer'][1] = '<p>Wacintaki is an oekaki board that may be installed on a personal web site.  It is a very traditional oekaki board, with the exception that it requires people to register as members before they may draw.  Wacintaki was forked from a previous open-source oekaki known as OekakiPoteto, written by Theo Chakkapark and Marcello Bastéa-Forte.  Old versions of the fork were known as Wacintaki Poteto, but now the board is simply named Wacintaki.</p>

<p>If you are interested in installing Wacintkai on your web site, visit the Wacintaki home page or the NineChime software support forum for more information.</p>';


$lang['faq_question'][2] = 'How do I start drawing?';

$lang['faq_answer'][2] = '<p>Wacintaki requires registration before you may draw.  If you are registered, you may begin drawing by clicking on &ldquo;Draw&rdquo; on the menu bar.  Note that administrators may revoke your ability to draw, revoke your registration, or even ban you at any time if you misbehave.</p>';


$lang['faq_question'][3] = 'What is the difference between drawing and uploading?';

$lang['faq_answer'][3] = '<p>Oekaki boards allow you to draw pictures with a custom paint program that runs in your web browser.  Some oekaki boards will also allow you to upload an image from your computer, but this is at the discression of the oekaki owner.  Some oekaki boards will allow all members to upload files, however, most boards will only allow specific members to upload.  Please check the board rules before asking for the ability to upload images.</p>';


$lang['faq_question'][4] = 'How do I start animating?';

$lang['faq_answer'][4] = '<p>Some paint programs allow you to record your actions as you draw, so other members may see how you work.  After clicking &ldquo;Draw&rdquo; on the menu bar, you will be given an option to enable animations.  Note that some paint programs do not support animations, but they may require you to click the animation option if you want to draw your picture in multiple layers.</p>

<p>Animations will only show your actions while drawing.  You cannot create cartoons or videos, or edit the animations.  If you click &ldquo;undo&rdquo; in a paint program, your last action will not show up in the animation.</p>';


$lang['faq_question'][5] = 'I can\'t see or start the paint program.';

$lang['faq_answer'][5] = '<p>Most of the paint programs are Java applets.  If your web browser says it does not have the correct plug-in, try downloading Java from <a href="http://www.java.com">www.Java.com</a>.  Java is a software platform created by Sun Microsystems and is now distributed by Oracle.</p>

<p>Many PCs already have Java installed.  Java on the Macintosh is provided by Apple, and is known to have problems running the oekaki applets.</p>';


$lang['faq_question'][6] = 'I can\'t retouch my pictures.  The canvas is blank!';

$lang['faq_answer'][6] = '<p>Older versions of Java have problems importing pictures, so make sure you are using a recent version of Java.  You may also have to enable animation support to retouch pictures.  If you are retouching a complex picture with a long animation, you may have to wait a minute before the picture will show up in the canvas as the paint program loads the animation.</p>';


$lang['faq_question'][7] = 'I lost my password.';

$lang['faq_answer'][7] = '<p>You may recover your password <a href="lostpass.php">here</a>.</p>

<p>If you can\'t get your password back with password recovery, tell the owner of the board, and he or she can give you a new password.</p>';


$lang['faq_question'][8] = 'I can\'t logout.';

$lang['faq_answer'][8] = '<p>This is likely a web browser problem.  You should empty your browser cookies.  A cookie is a small tag of information that lets a server know who you are.  Most browsers have an option to clear cookies from the &ldquo;Tools->Options&rdquo; menu.</p>';


$lang['faq_question'][9] = 'My picture didn\'t post, or I wasn\'t able to comment, and lost my picture!';

$lang['faq_answer'][9] = '<p>Your picture may not be lost.  If the picture finished uploading, but you were unable to comment, go to &ldquo;Administration->Recover Pics&rdquo; to recover it.  Your information has still saved, including the time it took for you to draw it.  If you have trouble, the administrator may recover pictures for you, or upload a screen capture you took using the &ldquo;Print Scrn&rdquo; key.</p>

<p><strong>NOTE</strong>:  Pictures saved using a screen capture may be very large and in a format not compatible with Wacintaki.  Please tell an administrator about your lost picture <strong>before</strong> sending a huge picture file to an admin\'s e-mail address.</p>';


$lang['faq_question'][10] = 'Why can\'t I see any e-mail addresses?';

$lang['faq_answer'][10] = '<p>To help protect e-mail addresses from spam, you must be logged into your account to see other peoples\' addresses in their profile or the memberlist.</p>

<p>You will always be able to see admin e-mails, but you will have to substitute the anti-spam character with the &ldquo;@&rdquo; symbol.</p>';


$lang['faq_question'][11] = 'What happened to the chat / mailbox?';

$lang['faq_answer'][11] = '<p>Wacintaki allows administrators to disable the chat and mailbox systems normally found in OekakiPoteto.  The chat system uses a lot of bandwidth and some servers may not be able to use it.  If the mailbox has been disabled, you may send e-mails to people directly by viewing that person\'s profile or the memberlist.  Note that you must be a member and logged in to see e-mail addresses.</p>';


$lang['faq_question'][12] = 'How may I send a mailbox message to someone?';

$lang['faq_answer'][12] = '<p>Click on member names to open up their profiles, and click on &ldquo;send a message&rdquo; at the top of that page.  You must be logged into your account to send messages.</p>';


$lang['faq_question'][13] = 'What is &ldquo;automatic user delete?&rdquo;';

$lang['faq_answer'][13] = '<p>If it is set, at a specific date, all users who haven\'t logged in within the specified time will automatically be removed from the board. To prevent this from happening, ask an administrator to give you an Immunity flag, or log into the board on a regular basis.</p>';


$lang['faq_question'][14] = 'What is considered an adult picture?';

$lang['faq_answer'][14] = '<p>As oekaki is an international passtime, the definition of an adult picture varies.  Usually this means any material of a mature nature, suitable only for people at least 18 years of age.  This material includes aggressive violence, nudity, or anything of a sexual or explicit nature.  Some oekaki boards do not allow adult content, so read the rules to see if any additional or alternative conditions have been specified by the administrators.</p>

<p>If an oekaki board has been rated for adults only, you must submit an age statement before you may register.  If you cannot view adult pictures and would like to see them, you must select &ldquo;Edit Profile,&rdquo; and make sure the &ldquo;Allow Adult Images&rdquo; checkbox is selected.</p>';


$lang['faq_question'][15] = 'Why are some pictures shrunken into a thumbnail, while others are not?';

$lang['faq_answer'][15] = '<p>Images become thumbnails based on a number of factors, including the filesize of your image, and what thumbnail mode the administrator has selected for the board.  You may change thumbnail and layout modes in <a href="editprofile.php">&ldquo;Edit Profile&rdquo;</a> if the administrator allows members to choose their own view modes.</p>';

// -----

//Who {1?is the owner:are the owners} of this oekaki?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionA'] = 'Who {1?is:are} the owner{1?:s} of this oekaki?';

//Who {1?is the administrator:are the administrators}?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionB'] = 'Who {1?is:are} the administrator{1?:s}?';

//Who {1?is the moderator:are the moderators}?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionC'] = 'Who {1?is:are} the moderator{1?:s}?';

/* End FAQ */



$lang['word_new'] = "New";

$lang['word_unread'] = "Unread";

$lang['word_read'] = "Read";

$lang['word_replied'] = "Replied";

$lang['register_sub8'] = "After you have registered, check your e-mail for the link to activate your account.";

//Upload
$lang['word_upload'] = "Upload";

//Upload Picture
$lang['upload_title'] = "Upload Picture";

//File to upload
$lang['upload_file'] = "File to upload";

//ShiPainter
$lang['word_shipainter'] = "ShiPainter";

//ShiPainter Pro
$lang['word_shipainterpro'] = "ShiPainter Pro";

//Edit Banner
$lang['header_ebanner'] = "Edit Banner";

//Reset All Templates
$lang['install_resettemplate'] = "Reset All Templates";

//If yes, all members will have their template reset to the default
$lang['install_resettemplatesub'] = "If yes, all members will have their template reset to the default";

//N/A
$lang['word_na'] = "N/A";

//You do not have draw access. Ask an administrator on details for receiving access.
$lang['draw_noaccess'] = "You do not have draw access. Ask an administrator on details for receiving access.";

//Upload Access
$lang['type_uaccess'] = 'Upload Access';

//Print &ldquo;Uploaded by&rdquo;
$lang['admin_uploaded_by'] = 'Print &ldquo;Uploaded by&rdquo;';

//Gives the user access to the picture upload feature.
$lang['type_guaccess'] = 'Gives the user access to the picture upload feature.';

//Delete database
$lang['delete_dbase'] = "Delete database";

//Database Uninstall
$lang['uninstall_prompt'] = "Database Uninstall";

//Are you sure you want to remove the database?  This will remove information for the board
$lang['sure_remove_dbase'] = "Are you sure you want to remove the database?  This will remove information for the board";

//Images, templates, and all other files in the OP directory must be deleted manually.
$lang['all_delete'] = "Images, templates, and all other files in the OP directory must be deleted manually.";

//If you have only one board, you may delete both databases below.
$lang['delete_oneboard'] = "If you have only one board, you may delete both databases below.";

//If you are sharing a database with more than one board, be sure to delete <em>only</em> the database for posts and comments.  If you delete the database for member profiles, all your boards will cease to function!
$lang['sharing_dbase'] = "If you are sharing a database with more than one board, be sure to delete <em>only</em> the database for posts and comments.  If you delete the database for member profiles, all your boards will cease to function!";

//Each board must be removed with its respective installer.
$lang['remove_board'] = "Each board must be removed with its respective installer.";

//Delete posts and comments.
$lang['delepostcomm'] = "Delete posts and comments.";

//Delete member profiles, chat, and mailboxes.
$lang['delememp'] = "Delete member profiles, chat, and mailboxes.";

//Uninstall error
$lang['uninserror'] = "Uninstall error";

//Valid database and config files were not found.  The board must be properly installed before any database entries can be removed.  If problems persist, let your sysadmin delete the databases by name.
$lang['uninsmsg'] = "Valid database and config files were not found.  The board must be properly installed before any database entries can be removed.  If problems persist, let your sysadmin delete the databases by name.";

//Uninstall Status
$lang['unistatus'] = "Uninstall Status";

//NOTE:  No databases changed
$lang['notedbchange'] = "NOTE:  No databases changed";

//Return to the installer
$lang['returninst'] = "Return to the installer";

//Wacintaki Installation
$lang['wacinstall'] = "Wacintaki Installation";

//Installation Progress
$lang['instalprog'] = "Installation Progress";

//ERROR:  Your database settings are invalid.
$lang['err_dbs'] = "ERROR:  Your database settings are invalid.";

//NOTE:  Database password is blank (not an error).
$lang['note_pwd'] = "NOTE:  Database password is blank (not an error).";

//ERROR:  The administrator login name is missing.
$lang['err_adminname'] = "ERROR:  The administrator login name is missing.";

//ERROR:  The administrator password is missing.
$lang['err_adminpwd'] = "ERROR:  The administrator password is missing.";

//ERROR:  The administrator passwords do not match.
$lang['err_admpwsmtch'] = "ERROR:  The administrator passwords do not match.";

//Could not connect to the MySQL database.
$lang['err_mysqlconnect'] = "Could not connect to the MySQL database.";

//Wrote database config file.
$lang['msg_dbsefile'] = "Wrote database config file.";

//ERROR:  Could not open database config file for writing.  Check your server permissions
$lang['err_permis'] = "ERROR:  Could not open database config file for writing.  Check your server permissions";

//Wrote config file.
$lang['wrconfig'] = "Wrote config file.";

//ERROR:  Could not open config file for writing.  Check your server permissions.
$lang['err_wrconfig'] = "ERROR:  Could not open config file for writing.  Check your server permissions.";

//ERROR:  Could not create folder &ldquo;{1}&rdquo;
$lang['err_cfolder'] = "ERROR:  Could not create folder &ldquo;{1}&rdquo;";

//ERROR:  Folder &ldquo;{1}&rdquo; is locked.  You may have to create this folder manually.
$lang['err_folder'] = "ERROR:  Folder &ldquo;{1}&rdquo; is locked.  You may have to create this folder manually.";

//One or more base files could not be created.  Try again or manually create the listed files with zero length.
$lang['err_fcreate'] = "One or more base files could not be created.  Try again or manually create the listed files with zero length.";

//'Wrote base &ldquo;resource&rdquo; folder files.'
$lang['write_basefile'] = "Wrote base &ldquo;resource&rdquo; folder files.";

//Starting to set up database.
$lang['startsetdb'] = "Starting to set up database.";

//Finished setting up database.
$lang['finishsetdb'] = "Finished setting up database.";

//If you did not receive any errors, the databases have been installed.
$lang['noanyerrors'] = "If you did not receive any errors, the databases have been installed.";

//If you are installing another board and your primary board is functioning properly, ignore any database errors.
$lang['anotherboarderr'] = "If you are installing another board and your primary board is functioning properly, ignore any database errors.";

//Click the button below to finalize the installation.  This will clean up the installer files and prevent security problems.  You will have to copy <em>install.php</em> into the Wacintaki folder if you need to uninstall the database.  All other maintenance can be done with the control panel.
$lang['clickbuttonfinal'] = "Click the button below to finalize the installation.  This will clean up the installer files and prevent security problems.  You will have to copy <em>install.php</em> into the Wacintaki folder if you need to uninstall the database.  All other maintenance can be done with the control panel.";

//Secure installer and go to the BBS
$lang['secinst'] = "Secure installer and go to the BBS";

//Installation Error
$lang['err_install'] = "Installation Error";

//&ldquo;templates&rdquo; and &ldquo;resource&rdquo; folders are not writable!  Be sure to CHMOD these folders to their correct permissions before running the installer.
$lang['err_temp_resource'] = "&ldquo;templates&rdquo; and &ldquo;resource&rdquo; folders are not writable!  Be sure to CHMOD these folders to their correct permissions before running the installer.";

//Wacintaki Installation
$lang['wac_inst'] = "Wacintaki Installation";

//Installation Notes
$lang['inst_note'] = "Installation Notes";

//One MySQL database is required to install Wacintaki.  If you do not know how to access your MySQL account, e-mail your sysadmin, or log into your control panel and look for a database tool such as phpMyAdmin.  On most servers, &ldquo;localhost&rdquo; will work for the hostname, though web hosts with a dedicated MySQL server may require something such as &ldquo;mysql.server.com&rdquo;.  Be aware that some database tools, such as CPanel or phpMyAdmin, may automatically add a prefix to your database name or username, so if you create a database called &ldquo;oekaki&rdquo;, the result may end up being &ldquo;accountname_oekaki&rdquo;.  The database table prefixes (default &ldquo;op_&rdquo;) are only significant if you wish to install more than one oekaki.  Consult the manual for more information on installing multiple oekakis with one database.
$lang['mysqldb_wact'] = "One MySQL database is required to install Wacintaki.  If you do not know how to access your MySQL account, e-mail your sysadmin, or log into your control panel and look for a database tool such as phpMyAdmin.  On most servers, &ldquo;localhost&rdquo; will work for the hostname, though web hosts with a dedicated MySQL server may require something such as &ldquo;mysql.server.com&rdquo;.  Be aware that some database tools, such as CPanel or phpMyAdmin, may automatically add a prefix to your database name or username, so if you create a database called &ldquo;oekaki&rdquo;, the result may end up being &ldquo;accountname_oekaki&rdquo;.  The database table prefixes (default &ldquo;op_&rdquo;) are only significant if you wish to install more than one oekaki.  Consult the manual for more information on installing multiple oekakis with one database.";

//Database Table Prefix
$lang['dbtablepref'] = "Database Table Prefix";

//If installing mutiple boards on one database, each board must have its own, unique table prefix.
$lang['multiboardpref'] = "If installing mutiple boards on one database, each board must have its own, unique table prefix.";

//Member Table Prefix
$lang['memberpref'] = "Member Table Prefix";

//If installing multiple boards on one database, and you want all members to access each board without seperate registrations, make sure each board shares the same table prefix.  To force sperate registrations for each board, make this prefix unique for each installation.
$lang['instalmulti'] = "If installing multiple boards on one database, and you want all members to access each board without seperate registrations, make sure each board shares the same table prefix.  To force sperate registrations for each board, make this prefix unique for each installation.";

//<a href="{1}">Click here to uninstall an existing database.</a>  Confirmation will be requested.
$lang['uninstexist'] = '<a href="{1}">Click here to uninstall an existing database.</a>  Confirmation will be requested.';

//This is a guess.  Make sure it is correct, or registration will not work correctly.
$lang['guessregis'] = "This is a guess.  Make sure it is correct, or registration will not work correctly.";

//Picture Name Prefix
$lang['picpref'] = "Picture Name Prefix";

//This prefix will appear on every picture and animation saved by the BBS.  Example: &ldquo;OP_50.png&rdquo;
$lang['picprefexp'] = "This prefix will appear on every picture and animation saved by the BBS.  Example: &ldquo;OP_50.png&rdquo;";

//Allow Public Pictures
$lang['allowppicture'] = "Allow Public Pictures";

//Public pictures may be retouched by any member with draw permissions. No passwords are used, and retouched images are submitted as new posts. <strong>NOTE</strong>: May result in floods without strict rules and administration.
$lang['ppmsgrtouch'] = "Public pictures may be retouched by any member with draw permissions. No passwords are used, and retouched images are submitted as new posts. <strong>NOTE</strong>: May result in floods without strict rules and administration.";

//Allow Safety Saves
$lang['allowsafesave'] = "Allow Safety Saves";

//Safety saves do not show up on the board while they are in progress.  Only one safety save is allowed per member, and they are automatically deleted after a certain number of days
$lang['safesaveexp'] = "Safety saves do not show up on the board while they are in progress.  Only one safety save is allowed per member, and they are automatically deleted after a certain number of days";

//Safety Save Storage
$lang['savestorage'] = "Safety Save Storage";

//Number of days safety saves are stored before they are removed.  Default is 30.
$lang['safetydays'] = "Number of days safety saves are stored before they are removed.  Default is 30.";

//Auto Immunity for Artists
$lang['autoimune'] = "Auto Immunity for Artists";

//If yes, people who draw pictures will automatically receive the immunity flag from auto user delete.
$lang['autoimune_exp'] = "If yes, people who draw pictures will automatically receive the immunity flag from auto user delete.";

//Show Rules Before Registration
$lang['showrulereg'] = "Show Rules Before Registration";

//If yes, people will be shown the rules before they can submit a new registration.  Use &ldquo;Edit Rules&rdquo; in the admin menu to set rules.
$lang['showruleregexp'] = "If yes, people will be shown the rules before they can submit a new registration.  Use &ldquo;Edit Rules&rdquo; in the admin menu to set rules.";

//Require Art Submission
$lang['requireartsub'] = "Require Art Submission";

//If yes, new users are instructed to provide a link to a piece of art for the administrator to view.
$lang['requireartsubyes'] = "If yes, new users are instructed to provide a link to a piece of art for the administrator to view.";

//If no, new users are told the URL field is optional.
$lang['requireartsubno'] = "If no, new users are told the URL field is optional.";

//No (forced)
$lang['forceactivate'] = "No (forced)";

//If yes, approval by the administrators is required to register.
$lang['activateyes'] = "If yes, approval by the administrators is required to register.";

//If no, users will receive an activation code in their e-mail.
$lang['activeno'] = "If no, users will receive an activation code in their e-mail.";

//Use &ldquo;forced&rdquo; ONLY if your server cannot send e-mails, and you want automatic approval.
$lang['activateforced'] = "Use &ldquo;forced&rdquo; ONLY if your server cannot send e-mails, and you want automatic approval.";

//Default Permissions for Approved Registrations
$lang['defaultpermis'] = "Default Permissions for Approved Registrations";

//Members may bump own pictures on retouch?
$lang['bumpretouch'] = "Members may bump own pictures on retouch?";

//Author Name
$lang['authorname'] = "Author Name";

//Name of the BBS owner.  This is displayed in the copyright and page metadata.
$lang['bbsowner'] = "Name of the BBS owner.  This is displayed in the copyright and page metadata.";

//Adult rated BBS
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbs'] = "Adult rated BBS";

//Select Yes to declare your BBS for adults only.  Users are required to state their age to register.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsdesc'] = "Select Yes to declare your BBS for adults only.  Users are required to state their age to register.";

//NOTE:  Does <strong>not</strong> make every picture adult by default.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsnote'] = "NOTE:  Does <strong>not</strong> make every picture adult by default.";

//Allow guests access to pr0n
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpron'] = "Allow guests access to pr0n";

//If yes, adult images are blocked and may be viewed by clicking the pr0n placeholder.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronyes'] = "If yes, adult images are blocked and may be viewed by clicking the pr0n placeholder.";

//If no, the link is disabled and all access is blocked.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronno'] = "If no, the link is disabled and all access is blocked.";

//Number of Pics on Index
$lang['maxpiconind'] = "Number of Pics on Index";

//Avatars
$lang['word_avatars'] = "Avatars";

//Enable Avatars
$lang['enableavata'] = "Enable Avatars";

//Allow Avatars On Comments
$lang['allowavatar'] = "Allow Avatars On Comments";

//Avatar Storage
$lang['AvatarStore'] = "Avatar Storage";

//Change <strong>only</strong> if installing multiple boards.  Read the manual.
$lang['changemulti'] = "Change <strong>only</strong> if installing multiple boards.  Read the manual.";

//Basically, use one folder for all boards.  Example:  use &ldquo;avatars&rdquo; for board 1, &ldquo;../board1/avatars&rdquo; for board 2, etc.
$lang['changemultidesc'] = "Basically, use one folder for all boards.  Example:  use &ldquo;avatars&rdquo; for board 1, &ldquo;../board1/avatars&rdquo; for board 2, etc.";

//Maximum Avatar Size
$lang['maxavatar'] = "Maximum Avatar Size";

//Default is 50 &times; 50 pixels.  Larger than 60 &times; 60 not recommended
$lang['maxavatardesc'] = "Default is 50 &times; 50 pixels.  Larger than 60 &times; 60 not recommended";

//Default Canvas Size
$lang['cavasize'] = "Default Canvas Size";

//The default canvas size.  Typical value is 300 &times; 300 pixels
$lang['defcanvasize'] = "The default canvas size.  Typical value is 300 &times; 300 pixels";

//Minimum Canvas Size
$lang['mincanvasize'] = "Minimum Canvas Size";

//The minimum canvas size.  Recommended minimum is 50 &times; 50 pixels
$lang['mincanvasizedesc'] = "The minimum canvas size.  Recommended minimum is 50 &times; 50 pixels";

//Maximum Canvas Size
$lang['maxcanvasize'] = "Maximum Canvas Size";

//Maximum canvas.  Recommended maximum is 500 &times; 500 pixels
$lang['maxcanvasizedesc'] = "Maximum canvas.  Recommended maximum is 500 &times; 500 pixels";

//Be aware that a small increase in dimentions results in a large increase in surface area, and thus filesize and bandwidth.  1000 &times; 1000 uses <strong>four times</strong> as much bandwidth as 500 &times; 500
$lang['maxcanvasizedesc2'] = "Be aware that a small increase in dimentions results in a large increase in surface area, and thus filesize and bandwidth.  1000 &times; 1000 uses <strong>four times</strong> as much bandwidth as 500 &times; 500";

//Number of pictures to display per page of the BBS
$lang['maxpicinddesc'] = "Number of pictures to display per page of the BBS";

//Number of Entries on Menus
$lang['menuentries'] = "Number of Entries on Menus";

//Number of entries (such as user names) to display per page of the menus and admin controls
$lang['menuentriesdesc'] = "Number of entries (such as user names) to display per page of the menus and admin controls";

//Use Smilies in Comments?
$lang['usesmilies'] = "Use Smilies in Comments?";

//Smilies are configurable by editing the &ldquo;hacks.php&rdquo; file
$lang['usesmiliedesc'] = "Smilies are configurable by editing the &ldquo;hacks.php&rdquo; file";

//Maximum Upload Filesize
$lang['maxfilesize'] = "Maximum Upload Filesize";

//The max filesize uploaded pictures can be, in bytes.  Default is 500,000 bytes or 500KB.
$lang['maxupfileexp'] = "The max filesize uploaded pictures can be, in bytes.  Default is 500,000 bytes or 500KB.";

//The maximum value allowed by most servers varies from 2 to 8MB.
$lang['maxupfileexp2'] = "The maximum value allowed by most servers varies from 2 to 8MB.";

//Canvas size preview
$lang['canvasprev'] = "Canvas size preview";

//Image for canvas size preview on Draw screen.  Square picture recommended.
$lang['canvasprevexp'] = "Image for canvas size preview on Draw screen.  Square picture recommended.";

//Preview Title
$lang['pviewtitle'] = "Preview Title";

//Title of preview image (Text only &amp; do not use double quotes).
$lang['titleprevwi'] = "Title of preview image (Text only &amp; do not use double quotes).";

//&ldquo;Pr0n&rdquo; placeholder image
$lang['pron'] = "&ldquo;Pr0n&rdquo; placeholder image";

//Image for substitution of pr0n.  Square picture recommended.  Default &ldquo;pr0n.png&rdquo;
$lang['prondesc'] = "Image for substitution of pr0n.  Square picture recommended.  Default &ldquo;pr0n.png&rdquo;";

//Enable Chat
$lang['enablechat'] = "Enable Chat";

//Note:  chat uses a lot of bandwidth
$lang['chatnote'] = "Note:  chat uses a lot of bandwidth";

//Your server does not have the graphics system &ldquo;GDlib&rdquo; installed, therefore you cannot enable thumbnail support.  However, you may still select a default thumbnail mode which will conserve screenspace by shrinking pictures.
$lang['err_nogdlib'] = "Your server does not have the graphics system &ldquo;GDlib&rdquo; installed, therefore you cannot enable thumbnail support.  However, you may still select a default thumbnail mode which will conserve screenspace by shrinking pictures.";

//Four thumbnail modes are available.  None, Layout, Scaled, and Uniformity.  If you're confused which mode to use, try Scaled first.
$lang['thumbmodes'] = "Four thumbnail modes are available.  None, Layout, Scaled, and Uniformity.  If you're confused which mode to use, try Scaled first.";

//If you choose &ldquo;None&rdquo;, thumbnail support will always be off for all members unless you enable it later in the control panel.
$lang['thumbmodesexp2'] = "If you choose &ldquo;None&rdquo;, thumbnail support will always be off for all members unless you enable it later in the control panel.";

//Default thumbnail mode
$lang['defthumbmode'] = "Default thumbnail mode";

//None
$lang['word_none'] = "None";

//Layout
$lang['word_layout'] = "Layout";

//Scaled (default)
$lang['word_defscale'] = "Scaled (default)";

//Uniformity
$lang['word_uniformity'] = "Uniformity";

//Tip:  Options are ordered in terms of bandwidth.  Uniformity uses the least bandwidth.  Scaled Layout is recommended.
$lang['optiontip'] = "Tip:  Options are ordered in terms of bandwidth.  Uniformity uses the least bandwidth.  Scaled Layout is recommended.";

//Force default thumbnails
$lang['forcedefthumb'] = "Force default thumbnails";

//If yes, users may only use the default mode (recommended for servers with little bandwidth). If no, users may select any thumbnail mode they wish.
$lang['forcethumbdesc'] = "If yes, users may only use the default mode (recommended for servers with little bandwidth). If no, users may select any thumbnail mode they wish.";

//Small thumbnail size
$lang['smallthumb'] = "Small thumbnail size";

//Size of small (uniformity) thumbnails in pixels.  Small thumbnails are generated often.  Default 120.
$lang['smallthumbdesc'] = "Size of small (uniformity) thumbnails in pixels.  Small thumbnails are generated often.  Default 120.";

//Large thumbnail size
$lang['largethumb'] = "Large thumbnail size";

//Size of large (layout) thumbnails.  Large thumbnails are made only occasionally for layout or scaled thumbnail modes.  Default 250.
$lang['largethumbdesc'] = "Size of large (layout) thumbnails.  Large thumbnails are made only occasionally for layout or scaled thumbnail modes.  Default 250.";

//Filesize for large thumbnail generation
$lang['thumbnailfilesize'] = "Filesize for large thumbnail generation";

//If a picture's filesize is greater than this value, a large thumbnail will be generated in addition to the small one.  Default is 100,000 bytes.  If using uniformity mode only, set to zero to disable and save server space.
$lang['thumbsizedesc'] = "If a picture's filesize is greater than this value, a large thumbnail will be generated in addition to the small one.  Default is 100,000 bytes.  If using uniformity mode only, set to zero to disable and save server space.";

//Your E-mail Address (leave blank to use registration e-mail)
$lang['emaildesc'] = "Your E-mail (leave blank to use registration e-mail)";

//Submit Information for Install
$lang['finalinstal'] = "Submit Information for Install";

//---addusr

//You do not have the credentials to add users.
$lang['nocredeu'] = "You do not have the credentials to add users.";

//Note to admins:  Automatic approval is enabled, so users are expected to enable their own accounts.  Contact the board owner if you have questions about approving or rejecting members manually.
$lang['admnote'] = "Note to admins:  Automatic approval is enabled, so users are expected to enable their own accounts.  Contact the board owner if you have questions about approving or rejecting members manually.";

//INVALID
$lang['word_invalid'] = "INVALID";

//--banlist

//You do not have the credentials to ban users.
$lang['credibandu'] = "You do not have the credentials to ban users.";

//&ldquo;{1}&rdquo; is locked!  View Readme.txt for help.
// {1} = filename
$lang['fislockvred'] = "&ldquo;{1}&rdquo; is locked!  View Readme.txt for help.";

//Submit Changes
$lang['submitchange'] = "Submit Changes";

//You do not have access as a registered member to use the chat.
$lang['memaccesschat'] = "You do not have access as a registered member to use the chat.";

//The chat room has been disabled.
$lang['charommdisable'] = "The chat room has been disabled.";

//Sorry, an IFrame capable browser is required to participate in the chat room.
$lang['iframechat'] = "Sorry, an IFrame capable browser is required to participate in the chat room.";

//Invalid user name.
$lang['invuname'] = "Invalid user name.";

//Invalid verification code.
$lang['invercode'] = "Invalid verification code.";

//Safety Save
$lang['safetysave'] = "Safety Save";

//Return to the BBS
$lang['returnbbs'] = "Return to the BBS";

//Error looking for a recent picture.
$lang['err_lookrecpic'] = "Error looking for a recent picture.";

//NOTE:  Refresh may be required to see retouched image
$lang['refreshnote'] = "NOTE:  Refresh may be required to see retouched image";

//Picture properties
$lang['picprop'] = "Picture properties";

//No, post picture now
$lang['safesaveopt1'] = "No, post picture now";

//Yes, save for later
$lang['safesaveopt2'] = "Yes, save for later";

//Bump picture
$lang['bumppic'] = "Bump picture";

//You may bump your edited picture to the first page.
$lang['bumppicexp'] = "You may bump your edited picture to the first page.";

//Share picture
$lang['sharepic'] = "Share picture";

//Password protect
$lang['pwdprotect'] = "Password protect";

//Public (to all members)
$lang['picpublic'] = "Public (to all members)";

//Submit
$lang['word_submit'] = "Submit";

//Thanks for logging in!
$lang['common_login'] = "Thanks for logging in!";

//You have sucessfully logged out.
$lang['common_logout'] = "You have sucessfully logged out.";

//Your login has been updated.
$lang['common_loginupd'] = "Your login has been updated.";

//An error occured.  Please try again.
$lang['common_error'] = "An error occured.  Please try again.";

//&lt;&lt;PREV
$lang['page_prev'] = '&lt;&lt;PREV';

//NEXT&gt;&gt;
$lang['page_next'] = 'NEXT&gt;&gt;';

//&middot;
// bullet.  Separator between <<PREV|NEXT>> and page numbers
$lang['page_middot'] = '&middot;';

//&hellip;
// "...", or range of omitted numbers
$lang['page_ellipsis'] = '&hellip;';

//You do not have the credentials to access the control panel.
$lang['noaccesscp'] = "You do not have the credentials to access the control panel.";

//Storage
$lang['word_storage'] = "Storage";

//300 or more recommended.  If reduced, excess pictures are deleted immediately.  Check disk space usage on the <a href=\"testinfo.php\">diagnostics page</a>.
$lang['cpmsg1'] = "300 or more recommended.  If reduced, excess pictures are deleted immediately.  Check disk space usage on the <a href=\"testinfo.php\">diagnostics page</a>.";

//Use &ldquo;avatars/&rdquo; for master board, &ldquo;../board1/avatars/&rdquo; for all other boards.
$lang['cpmsg2'] = "Use &ldquo;avatars/&rdquo; for master board, &ldquo;../board1/avatars/&rdquo; for all other boards.";

//Image for canvas size preview on Draw screen.  Square picture recommended.  Default &ldquo;preview.png&rdquo;
$lang['cpmsg3'] = "Image for canvas size preview on Draw screen.  Square picture recommended.  Default &ldquo;preview.png&rdquo;";

//Rebuild thumbnails
$lang['rebuthumb'] = "Rebuild thumbnails";

//Page one
$lang['pgone'] = "Page one";

//Archived pictures only
$lang['archipon'] = "Archived pictures only";

//All thumbnails (very slow!)
$lang['allthumb'] = "All thumbnails (very slow!)";

//If thumbnail settings are changed, these thumbnails will be rebuilt.
$lang['rebuthumbnote'] = "If thumbnail settings are changed, these thumbnails will be rebuilt.";

//You do not have the credentials to delete comments
$lang['errdelecomm'] = "You do not have the credentials to delete comments";

//Send reason to mailbox
$lang['sreasonmail'] = "Send reason to mailbox";

//You do not have the credentials to edit the rules.
$lang['erreditrul'] = "You do not have the credentials to edit the rules.";

//Edit Rules
$lang['editrul'] = "Edit Rules";

//HTML and PHP are allowed.
$lang['htmlphpallow'] = "HTML and PHP are allowed.";

//You do not have the credentials to delete pictures.
$lang['errdelpic'] = "You do not have the credentials to delete pictures.";

//You do not have the credentials to delete users.
$lang['errdelusr'] = "You do not have the credentials to delete users.";

//Pictures folder is locked!  View Readme.txt for help.
$lang['picfolocked'] = "Pictures folder is locked!  View Readme.txt for help.";

//Unfinished Pictures
$lang['icomppic'] = "Unfinished Pictures";

//Click here to recover pictures
$lang['clickrecoverpic'] = "Click here to recover pictures.";

//Applet
$lang['word_applet'] = "Applet";

//, with palette
$lang['withpalet'] = ", with palette";

//Canvas
$lang['word_canvas'] = "Canvas";

//Min
$lang['word_min'] = "Min";

//Max
$lang['word_max'] = "Max";

//NOTE:  You must check &ldquo;animation&rdquo; to save your layers.
$lang['note_layers'] = "NOTE:  You must check &ldquo;animation&rdquo; to save your layers.";

//Avatars are disabled on this board.
$lang['avatardisable'] = "Avatars are disabled on this board.";

//You must login to access this feature.
$lang['loginerr'] = "You must login to access this feature.";

//File did not upload properly.  Try again.
$lang['err_fileupl'] = "File did not upload properly.  Try again.";

//Picture is an unsupported filetype.
$lang['unsuppic'] = "Picture is an unsupported filetype.";

//Filesize is too large.  Max size is {1} bytes.
$lang['filetoolar'] = "Filesize is too large.  Max size is {1} bytes.";

//Image size cannot be read.  File may be corrupt.
$lang['err_imagesize'] = "Image size cannot be read.  File may be corrupt.";

//Avatar upload
$lang['avatarupl'] = "Avatar upload";

//Avatar updated!
$lang['avatarupdate'] = "Avatar updated!";

//Your avatar may be a PNG, JPEG, or GIF.
$lang['avatarform'] = "Your avatar may be a PNG, JPEG, or GIF.";

//Avatars will only show on picture posts (not comments).
$lang['avatarshpi'] = "Avatars will only show on picture posts (not comments).";

//Change Avatar
$lang['chgavatar'] = "Change Avatar";

//Delete avatar
$lang['delavatar'] = "Delete avatar";

//Missing comment number.
$lang['err_comment'] = "Missing comment number.";

//You cannot edit a comment that does not belong to you.
$lang['err_ecomment'] = "You cannot edit a comment that does not belong to you.";

//You do not have the credentials to edit news.
$lang['err_editnew'] = "You do not have the credentials to edit news.";

//The banner is optional and displays at the very top of the webpage.
$lang['bannermsg'] = "The banner is optional and displays at the very top of the webpage.";

//The notice is optional and displays just above the page numbers on <em>every</em> page.
$lang['noticemsg'] = "The notice is optional and displays just above the page numbers on <em>every</em> page.";

//Erase
$lang['word_erase'] = "Erase";

//Centered Box
$lang['centrebox'] = "Centered Box";

//Scroll Box
$lang['scrollbox'] = "Scroll Box";

//Quick Draw
$lang['quickdraw'] = "Quick Draw";

//You cannot edit a picture that does not belong to you.
$lang['err_editpic'] = "You cannot edit a picture that does not belong to you.";

//Type &ldquo;public&rdquo; to share with everyone
$lang['editpicmsg'] = "Type &ldquo;public&rdquo; to share with everyone.";

//You cannot use the profile editor.
$lang['err_edprof'] = "You cannot use the profile editor.";

//Real Name (Optional)
$lang['realnameopt'] = "Real Name (Optional)";

//This is not your username.  This is your real name and will only show up in your profile.
$lang['realname'] = "This is not your username.  This is your real name and will only show up in your profile.";

//Birthday
$lang['word_birthday'] = "Birthday";

//M
// Month
$lang['abbr_month'] = "M";

//D
// Day
$lang['abbr_day'] = "D";

//Y
// Year
$lang['abbr_year'] = "Y";

//January
$lang['month_jan'] = "January";

//February
$lang['month_feb'] = "February";

//March
$lang['month_mar'] = "March";

//April
$lang['month_apr'] = "April";

//May
$lang['month_may'] = "May";

//June
$lang['month_jun'] = "June";

//July
$lang['month_jul'] = "July";

//August
$lang['month_aug'] = "August";

//September
$lang['month_sep'] = "September";

//October
$lang['month_oct'] = "October";

//November
$lang['month_nov'] = "November";

//December
$lang['month_dec'] = "December";

//Year is required for birthday to be saved.  Day and month are optional.
$lang['bdaysavmg'] = "Year is required for birthday to be saved.  Day and month are optional.";

//Website
$lang['word_website'] = "Website";

//Website title
$lang['websitetitle'] = "Website title";

//You can also type a message here and leave the URL blank
$lang['editprofmsg2'] = "You can also type a message here and leave the URL blank";

//Avatar
$lang['word_avatar'] = "Avatar";

//Current Avatar
$lang['curavatar'] = "Current Avatar";

//Online Presence
$lang['onlineprese'] = "Online Presence";

//(Automatic)
// context = Used as label in drop-down menu
$lang['picview_automatic'] = "(Automatic)";

//Automatic is the default format and will layout comments to wrap around the picture. Horizontal is good for very high-res screens and displays comments to the right of the picture.  Vertical is recommended for very small, low-res screens.
$lang['msg_automatic'] = "Automatic is the default format and will layout comments to wrap around the picture. Horizontal is good for very high-res screens and displays comments to the right of the picture.  Vertical is recommended for very small, low-res screens.";

//Thumbnail mode
$lang['thumbmode'] = "Thumbnail mode";

//Default
$lang['word_default'] = "Default";

//Scaled
$lang['word_scaled'] = "Scaled";

//Default is recommended.  Layout will disable most thumbnails.  Scaled is like layout but will shrink big pictures.  Uniformity will make all thumbnails the same size.
$lang['msgdefrec'] = "Default is recommended.  Layout will disable most thumbnails.  Scaled is like layout but will shrink big pictures.  Uniformity will make all thumbnails the same size.";

//(Cannot be changed on this board)
$lang['msg_cantchange'] = "(Cannot be changed on this board)";

//Screen size
$lang['screensize'] = "Screen size";

//{1} or higher
// {1} = screen resolution ("1280&times;1024")
$lang['orhigher'] = "{1} or higher";

//Your screensize, which helps determine the best layout.  Default is 800 &times; 600.
$lang['screensizemsg'] = "Your screensize, which helps determine the best layout.  Default is 800 &times; 600.";

// No image data was received by the server.\nPlease try again, or take a screenshot of your picture.
$lang['err_nodata'] = "No image data was received by the server.\nTake a screenshot of your picture (to be safe), and try submitting it again.";

//Login could not be verified!  Take a screenshot of your picture.
$lang['err_loginvs'] = "Login could not be verified!  Take a screenshot of your picture.";

//Unable to allocate a new picture slot!\nTake a screenshot of your picture and tell the admin.
$lang['err_picts'] = "Unable to allocate a new picture slot!\nTake a screenshot of your picture and tell the admin.";

//Unable to save image.\nPlease try again, or take a screenshot of your picture.
$lang['err_saveimg'] = "Unable to save image.\nPlease try again, or take a screenshot of your picture.";

//Rules
$lang['word_rules'] = "Rules";

//Public Images
$lang['publicimg'] = "Public Images";

//Drawings by Comment
$lang['drawbycomm'] = "Drawings by Comment";

//Animations by Comment
$lang['animbycomm'] = "Animations by Comment";

//Archives by Commen
$lang['archbycomm'] = "Archives by Comment";

//Go
// context = Used as button
$lang['word_go'] = "Go";

//My Oekaki
$lang['myoekaki'] = "My Oekaki";

//Reset Password
$lang['respwd'] = "Reset Password";

//Unlock
$lang['word_unlock'] = "Unlock";

//Lock
$lang['word_lock'] = "Lock";

//Bump
$lang['word_bump'] = "Bump";

//WIP
$lang['word_WIP'] = "WIP";

//TP
// context = "[T]humbnail [P]NG"
$lang['abrc_tp'] = "TP";

//TJ
// context = "[T]humbnail [J]PEG"
$lang['abrc_tj'] = "TJ";

//Thumb
$lang['word_thumb'] = "Thumb";

//Pic #{1}
$lang['picnumber'] = 'Pic #{1}';

//Pic #{1} (click to view)
$lang['clicktoview'] = 'Pic #{1} (click to view)';

//(Click to enlarge)
$lang['clickenlarg'] = '(Click to enlarge)';

//Adult
$lang['word_adult'] = "Adult";

//Public
$lang['word_public'] = "Public";

//Thread Locked
$lang['tlocked'] = "Thread Locked";

//The mailbox has been disabled.
$lang['mailerrmsg1'] = "The mailbox has been disabled.";

//You cannot access the mailbox.
$lang['mailerrmsg2'] = "You cannot access the mailbox.";

//You need to login to access the mailbox.
$lang['mailerrmsg3'] = "You need to login to access the mailbox.";

// You cannot access messages in the mailbox that do not belong to you.
$lang['mailerrmsg4'] = "You cannot access messages in the mailbox that do not belong to you.";

//You cannot access the mass send.
$lang['mailerrmsg5'] = "You cannot access the mass send.";

//Reverse Selection
$lang['revselect'] = "Reverse Selection";

//Delete Selected
$lang['delselect'] = "Delete Selected";

//(Yourself)
// context = Placeholder in table list
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_yourself'] = "(Yourself)";

//Original Message
$lang['orgmessage'] = "Original Message";

//Send
$lang['word_send'] = "Send";

//You can use this to send a global notice to a group of members via the OPmailbox.
$lang['massmailmsg1'] = "You can use this to send a global notice to a group of members via the OPmailbox.";

//Be careful when sending a mass mail to &ldquo;Everyone&rdquo; as this will result in LOTS of messages in your outbox.  Use this only if you really have to!
$lang['massmailmsg2'] = "Be careful when sending a mass mail to &ldquo;Everyone&rdquo; as this will result in LOTS of messages in your outbox.  Use this only if you really have to!";

//Everyone
$lang['word_everyone'] = "Everyone";

//Administrators
$lang['word_administrators'] = "Administrators";

//Pictures
$lang['word_pictures'] = "Pictures";

//Sort by
$lang['sortby'] = "Sort by";

//Order
$lang['word_order'] = "Order";

//Per page
$lang['perpage'] = "Per page";

//Keywords
$lang['word_keywords'] = "Keywords";

//(please login)
// context = Placeholder. Substitute for an e-mail address
$lang['plzlogin'] = "(please login)";

//Pending
$lang['word_pending'] = "Pending";

//You do not have the credentials to access flag modification.
$lang['err_modflags'] = "You do not have the credentials to access flag modification.";

//Warning:  be careful not to downgrade your own rank
$lang['warn_modflags'] = "Warning:  be careful not to downgrade your own rank!";

//Admin rank
$lang['adminrnk'] = "Admin rank";

//current
$lang['word_current'] = "current";

//You do not have the credentials to access the password reset.
$lang['retpwderr'] = "You do not have the credentials to access the password reset.";

//Only use this feature if a member cannot change their password in the profile editor, and they cannot use the password recovery feature because their recorded e-mail is not working.
$lang['newpassmsg1'] = "Only use this feature if a member cannot change their password in the profile editor, and they cannot use the password recovery feature because their recorded e-mail is not working.";

//Valid password in database
$lang['validpwdb'] = "Valid password in database";

//You do not have draw access. Login to draw, or ask an administrator for details about receiving access.
$lang['err_drawaccess'] = "You do not have draw access. Login to draw, or ask an administrator for details about receiving access.";

//Public retouch disabled.
$lang['pubretouchdis'] = "Public retouch disabled.";

//Incorrect password to retouch!
$lang['errrtpwd'] = "Incorrect password to retouch!";

//You have too many unfinished pictures!  Use Recover Pics menu to finish one.
$lang['munfinishpic'] = "You have too many unfinished pictures!  Use the Recover Pics menu to finish one.";

//You have an unfinished picture!  Use Recover Pics menu to finish it.
$lang['aunfinishpic'] = "You have an unfinished picture!  Use Recover Pics menu to finish it.";

//Resize PaintBBS to fit window
$lang['resizeapplet'] = "Resize applet to fit window";

//Hadairo
$lang['pallette1'] = "Hadairo";

//Red
$lang['pallette2'] = "Red";

//Yellow
$lang['pallette3'] = "Yellow";

//Green
$lang['pallette4'] = "Green";

//Blue
$lang['pallette5'] = "Blue";

//Purple
$lang['pallette6'] = "Purple";

//Brown
$lang['pallette7'] = "Brown";

//Character
$lang['pallette8'] = "Character";

//Pastel
$lang['pallette9'] = "Pastel";

//Sougen
$lang['pallette10'] = "Sougen";

//Moe
$lang['pallette11'] = "Moe";

//Grayscale
$lang['pallette12'] = "Grayscale";

//Main
$lang['pallette13'] = "Main";

//Wac!
$lang['pallette14'] = "Wac!";

//Save Palette
$lang['savpallette'] = "Save Palette";

//Save Color Changes
$lang['savcolorcng'] = "Save Color Changes";

//Palette
$lang['word_Palette'] = "Palette";

//Brighten
$lang['word_Brighten'] = "Brighten";

//Darken
$lang['word_Darken'] = "Darken";

//Invert
$lang['word_invert'] = "Invert";

//Replace all palettes
$lang['paletteopt1'] = "Replace all palettes";

//Replace active palette
$lang['paletteopt2'] = "Replace active palette";

//Append palette
$lang['apppalette'] = "Append palette";

//Set As Default Palette
$lang['setdefpalette'] = "Set As Default Palette";

//Palette Manipulate/Create
$lang['palletemini'] = "Palette Manipulate/Create";

//Gradation
$lang['word_Gradation'] = "Gradation";

//Applet Controls
$lang['appcontrol'] = "Applet Controls";

//Please note:
// context = help tips on for applets
$lang['plznote'] = "Please note:";

//Any canvas size change will destroy your current picture!
$lang['canvchgdest'] = "Any canvas size change will destroy your current picture!";

//You cannot resize your canvas when retouching an older picture.
$lang['noresizeretou'] = "You cannot resize your canvas when retouching an older picture.";

//You may need to refresh the window to start retouching.
$lang['refreshbret'] = "You may need to refresh the window to start retouching.";

//Click the &ldquo;Float&rdquo; button in the upper left corner to go fullscreen.
$lang['float'] = "Click the &ldquo;Float&rdquo; button in the upper left corner to go fullscreen.";

//X (width)
$lang['canvasx'] = "X";

//Y (height)
$lang['canvasy'] = "Y";

//Modify
$lang['word_modify'] = "Modify";

//Java Information
$lang['javaimfo'] = "Java Information";

//If you get an &ldquo;Image Transfer Error&rdquo;, you will have to use Microsoft VM instead of Sun Java.
$lang['oekakihlp1'] = "If you get an &ldquo;Image Transfer Error&rdquo;, you will have to use Microsoft VM instead of Sun Java.";

//If retouching an animated picture and the canvas is blank, play the animation first.
$lang['oekakihlp2'] = "If retouching an animated picture and the canvas is blank, play the animation first.";

//Recent IP / host
$lang['reciphost'] = "Recent IP / host";

//Send mailbox message
$lang['sendmailbox'] = "Send mailbox message";

//Browse all posts ({1} total)
$lang['browseallpost'] = "Browse all posts ({1} total)";

//(Broken image)
// context = Placholder for missing image
$lang['brokenimage'] = "(Broken image)";

//(No animation)
// context = Placholder for missing animation
$lang['noanim'] = "(No animation)";

//{1} seconds
$lang['recover_sec'] = "{1} seconds";

//{1} {1?minute:minutes}
$lang['recover_min'] = "{1} {1?minute:minutes}";

//Post now
$lang['postnow'] = "Post now";

//Please read the rules before submitting a registration.
$lang['plzreadrulereg'] = "Please read the rules before submitting a registration.";

//If you agree to these rules
$lang['agreerulz'] = "If you agree to these rules";

//click here to register
$lang['clickheregister'] = "click here to register";

//Registration Submitted
$lang['regisubmit'] = "Registration Submitted";

//Your registration for &ldquo;{1}&rdquo; is being processed.
// {1} = Oekaki title
$lang['urgistra'] = "Your registration for &ldquo;{1}&rdquo; is being processed.";

//Your registration for &ldquo;{1}&rdquo; has been approved!<br /><br />You may now configure your membership profile.<br /><br /><a href=\"editprofile.php\">Click here to edit your profile.</a>
// {1} = Oekaki title
$lang['urgistra_approved'] = "Your registration for &ldquo;{1}&rdquo; has been approved!<br /><br />You may now configure your membership profile.<br /><br /><a href=\"editprofile.php\">Click here to edit your profile.</a>";

//Before you may login, an administrator must approve your registration.  You should receive an e-mail shortly to let you know if your account has been approved.<br /><br />Once approved, you may update your member profile via the &ldquo;My Oekaki&rdquo; menu.
$lang['aprovemsgyes'] = "Before you may login, an administrator must approve your registration.  You should receive an e-mail shortly to let you know if your account has been approved.<br /><br />Once approved, you may update your member profile via the &ldquo;My Oekaki&rdquo; menu.";

//Please check your e-mail soon for the link to activate your account.<br /><br />Once your e-mail has been verified, you will be automatically logged in as a new member, and will be able to add information to your profile.
$lang['aprovemsgno'] = "Please check your e-mail soon for the link to activate your account.<br /><br />Once your e-mail has been verified, you will be automatically logged in as a new member, and will be able to add information to your profile.";

//Notes About Registering
$lang['nbregister'] = "Notes About Registering";

//DO NOT REGISTER TWICE.
$lang['registertwice'] = "DO NOT REGISTER TWICE.";

//You can check if you're in the pending list by viewing the member list and searching for your username.
$lang['regmsg1'] = "You can check if you're in the pending list by viewing the member list and searching for your username.";

//Use only alphanumeric characters for names and passwords.  Do not use quotes or apostrophes.  Passwords are case-sensitive.
$lang['regmsg2'] = "Use only alphanumeric characters for names and passwords.  Do not use quotes or apostrophes.  Passwords are case-sensitive.";

//You may change anything in your profile except your name once your registration is accepted.
$lang['regmsg3'] = "You may change anything in your profile except your name once your registration is accepted.";

//You must wait for an administrator to approve your registration on this board.  Your registration approval may take awhile if no one at the moment has time to maintain the pending list.  Please be patient; you will receive an e-mail notifying you of your approval.
$lang['regmsg4'] = "You must wait for an administrator to approve your registration on this board.  Your registration approval may take awhile if no one at the moment has time to maintain the pending list.  Please be patient; you will receive an e-mail notifying you of your approval.";

//If you don't receive an e-mail with a verification code, or if you cannot activate your account via e-mail, contact an administrator for help.  Administrators may manually approve your account in these cases.
$lang['regmsg5'] = "If you don't receive an e-mail with a verification code, or if you cannot activate your account via e-mail, contact an administrator for help.  Administrators may manually approve your account in these cases.";

//Your password can be mailed to you if you forget it.  <strong>Your e-mail will only be visible to other registered members.</strong>  You can remove or edit your e-mail after registration.  Ask the board owner about other potential privacy concerns.
$lang['regmsg6'] = "Your password can be mailed to you if you forget it.  <strong>Your e-mail will only be visible to other registered members.</strong>  You can remove or edit your e-mail after registration.  Ask the board owner about other potential privacy concerns.";

//{1}+ Age Statement
// {1} = minimum age. Implies {1} age or older
$lang['agestatement'] = "{1}+ Age Statement";

//<strong>This oekaki is for adults only.</strong>  You are required to declare your birth year to register.  Year is required, month and day are optional and may be left blank.
$lang['adultonlymsg'] = "<strong>This oekaki is for adults only.</strong>  You are required to declare your birth year to register.  Year is required, month and day are optional and may be left blank.";

//A link to your webpage, or a direct link to a sample of your artwork.  Not required for registration on this board.
$lang['nbwebpage'] = "A link to your webpage, or a direct link to a sample of your artwork.  Not required for registration on this board.";

//Submit Registration
$lang['subregist'] = "Submit Registration";

//Could not fetch information about picture
$lang['coulntfetipic'] = "Could not fetch information about picture";

//No edit number specified
$lang['noeditno'] = "No edit number specified";

//This picture is available to all board members.
$lang['picavailab'] = "This picture is available to all board members.";

//The edited version of this image will be posted as a new picture.
$lang['retouchmsg2'] = "The edited version of this image will be posted as a new picture.";

//The original artist will be credited automatically.
$lang['retouchmsg3'] = "The original artist will be credited automatically.";

//A password is required to retouch this picture.
$lang['retouchmsg4'] = "A password is required to retouch this picture.";

//The retouched picture will overwrite the original
$lang['retouchmsg5'] = "The retouched picture will overwrite the original";

//Continue
$lang['word_continue'] = "Continue";



/* sqltest.php */

//SQL direct call
// context = Can use the SQL tool
$lang['st_sql_header'] = 'SQL direct call';

//Original:
$lang['st_orig_query'] = 'Original:';

//Evaluated:
// context = "Processed" or "Computed"
$lang['st_eval_query'] = 'Evaluated:';

//Query okay.
$lang['st_query_ok'] = 'Query okay.';

//{1} {1?row:rows} affected.
$lang['st_rows_aff'] = '{1} {1?row:rows} affected.';

//First result: &ldquo;{1}&rdquo;
$lang['st_first_res'] = 'First result: &ldquo;{1}&rdquo;';

//Query failed!
$lang['st_query_fail'] = 'Query failed!';

//Database type:
// context = Which brand of database (MySQL, PostgreSQL, etc.)
$lang['st_db_type'] = 'Database type:';

//&nbsp;USE THIS TOOL WITH EXTREME CAUTION!  Detailed SQL knowledge required!&nbsp;
// context = This is a BIG warning with a large font.
$lang['st_big_warn'] = '&nbsp;USE THIS TOOL WITH EXTREME CAUTION!  Detailed SQL knowledge required!&nbsp;';

//Type a raw SQL query with no ending semicolon.  PHP strings will be evaluated.  Confirmation will be requested.
$lang['st_directions'] = 'Type a raw SQL query with no ending semicolon.  PHP strings will be evaluated.  Confirmation will be requested.';

//Version
$lang['st_ver_btn'] = 'Version';

/* END sqltest.php */



/* testinfo.php */

//Diagnostics page available only to owner.
$lang['testvar1'] = 'Diagnostics page available only to owner.';

//<strong>Folder empty</strong>
$lang['d_folder_empty'] = '<strong>Folder empty</strong>';

//DB info
$lang['dbinfo'] = 'DB info';

// Database version:
$lang['d_db_version'] = 'Database version:';

//Total pictures:
$lang['d_total_pics'] = 'Total pictures:';

//{1} (out of {2})
// {1} = existing pictures, {2} = maximum
$lang['d_pics_vs_max'] = '{1} (out of {2})';

//Archives:
$lang['d_archives'] = 'Archives:';

//WIPs and recovery:
$lang['d_wip_recov'] = 'WIPs and recovery:';

//Current picture number:
$lang['d_cur_picno'] = 'Current picture number:';

//<strong>Cannot read folder</strong>
$lang['d_no_read_dir'] = '<strong>Cannot read folder</strong>';

//SQL direct calls:
// context = Can use the SQL tool
$lang['d_sql_direct'] = 'SQL direct calls:';

//Available <a href="{1}">(click here)</a>
$lang['d_sql_avail'] = 'Available <a href="{1}">(click here)</a>';

//Config
$lang['d_word_config'] = 'Config';

//PHP Information:
$lang['d_php_info'] = 'PHP Information:';

//{1} <a href="{2}">(click for more details)</a>
// {1} = PHP version number
$lang['d_php_ver_num'] = '{1} <a href="{2}">(click for more details)</a>';

//Config version:
$lang['configver'] = 'Config version:';

//Contact:
$lang['word_contact'] = 'Contact:';

//Path to OP:
$lang['pathtoop'] = 'Path to OP:';

//Cookie path:
$lang['cookiepath'] = 'Cookie path:';

//Cookie domain:
// context = domain: tech term used for web addresses
$lang['cookie_domain'] = 'Cookie domain:';

//Cookie life:
// context = how long a browser cookie lasts
$lang['cookielife'] = 'Cookie life:';

//(empty)
// context = placeholder if no path/domain set for cookie
$lang['cookie_empty'] = '(empty)';

//{1} seconds (approximately {2} {2?day:days})
$lang['seconds_approx_days'] = '{1} seconds (approximately {2} {2?day:days})';

//Public images: // 'publicimg'
$lang['d_pub_images'] = 'Public images:';

//Safety saves:
$lang['safetysaves'] = 'Safety saves:';

//Yes ({1} days)
// {1} always > 1
$lang['d_yes_days'] = 'Yes ({1} days)';

//No ({1} days)
$lang['d_no_days'] = 'No ({1} days)';

//Pictures folder
$lang['d_pics_folder'] = 'Pictures folder';

//Notice:
$lang['d_notice'] = 'Notice:';

//Folder:
$lang['d_folder'] = 'Folder:';

//Total files:
$lang['d_total_files'] = 'Total files:';

//Total space used:
$lang['d_space_used'] = 'Total space used:';

//Average file size:
$lang['d_avg_filesize'] = 'Average file size:';

//Images:
$lang['d_images_label'] = 'Images:';

//{1} ({2}%)
// {1} = images, {2} = percentage of folder
$lang['d_img_and_percent'] = '{1} ({2}%)';

//Animations:
$lang['d_anim_label'] = 'Animations:';

//{1} ({2}%)
// {1} = animations, {2} = percentage of folder
$lang['d_anim_and_percent'] = '{1} ({2}%)';

//Other filetypes:
$lang['d_other_types'] = 'Other filetypes:';

//Locks
$lang['word_locks'] = 'Locks';

// Okay
// context = file is "writable" or "good".
$lang['word_okay'] = 'Okay';

//<strong>Locked</strong>
// context = "Unwritable" or "Unavailable" rather than broken or secure
$lang['word_locked'] = '<strong>Locked</strong>';

//<strong>Missing</strong>
$lang['word_missing'] = '<strong>Missing</strong>';

/* END testinfo.php */



//You do not have the credentials to upload pictures.
$lang['err_upload'] = "You do not have the credentials to upload pictures.";

//Picture to upload
$lang['pictoupload'] = "Picture to upload";

//Valid filetypes are PNG, JPEG, and GIF.
$lang['upldvalidtyp'] = "Valid filetypes are PNG, JPEG, and GIF.";

//Animation to upload
$lang['animatoupd'] = "Animation to upload";

//Matching picture and applet type required.
$lang['uploadmsg1'] = "Matching picture and applet type required.";

//Valid filetypes are PCH, SPCH, and OEB.
$lang['uploadmsg2'] = "Valid filetypes are PCH, SPCH, CHI, and OEB.";

//Valid filetypes are PCH and SPCH.
$lang['uploadmsg3'] = "Valid filetypes are PCH, SPCH, and CHI.";

//Applet type
$lang['appletype'] = "Applet type";

//Time invested (in minutes)
$lang['timeinvest'] = "Time invested (in minutes)";

//Use &ldquo;0&rdquo; or leave blank if unknown
$lang['uploadmsg4'] = "Use &ldquo;0&rdquo; or leave blank if unknown";

//Download
$lang['word_download'] = "Download";

//This window refreshes every {1} seconds.
$lang['onlinelistmsg'] = "This window refreshes every {1} seconds.";

//Go to page
$lang['gotopg'] = "Go to page";

//Netiquette applies.  Ask the admin if you have any questions.
// context = Default rules
$lang['defrulz'] = "Netiquette applies.  Ask the admin if you have any questions.";

//Send reason
$lang['sendreason'] = "Send reason";

//&ldquo;avatar&rdquo; field does not exist in database.
$lang['err_favatar'] = "&ldquo;avatar&rdquo; field does not exist in database.";

//Get
$lang['pallette_get'] = " Get ";

//Set
$lang['pallette_set'] = " Set ";

// Diagnostics
$lang['header_diag'] = 'Diagnostics';

// Humanity test for guest posts?
$lang['cpanel_humanity_infoask'] = "Humanity test for guest posts?";

// If yes, guests are required to pass a humanity test before posting comments.  The test must be passed only once.
$lang['cpanel_humanity_sub'] = "If yes, guests are required to pass a humanity test before posting comments.  The test must be passed only once.";

// And now, for the humanity test.
$lang['humanity_notify_sub'] = "And now, for the humanity test.";

// If canvas is blank or broken, <a href=\"{1}\">click here to import canvas, not animation</a>.
$lang['shi_canvas_only'] = "If canvas is blank or broken, <a href=\"{1}\">click here to import canvas, not animation</a>.";

//For help with installation, read the &ldquo;readme.html&rdquo; file that came with your Wacintaki distribution.  Make sure you have CHMOD all files appropriately before continuing with installation.  For technical assistance, please visit the <a href="http://www.NineChime.com/forum/">NineChime Software Forum</a>.
$lang['assist_install'] = "For help with installation, read the &ldquo;readme.html&rdquo; file that came with your Wacintaki distribution.  Make sure you have CHMOD all files appropriately before continuing with installation.  For technical assistance, please visit the <a href=\"http://www.NineChime.com/forum/\">NineChime Software Forum</a>.";

//The installer only sets mandatory information.  Once the board has been installed, use the Control Panel to fully configure the board.
$lang['assist_install2'] = "The installer only sets mandatory information.  Once the board has been installed, use the Control Panel to fully configure the board.";

//<strong>None</strong> will disable thumbnails, and uses a lot of bandwidth.  <strong>Layout</strong> will keep most pictures their original dimensions, and usually uses a vertical layout for wide pictures to keep comments readable.  <strong>Scaled</strong> will use thumbnails for wide pictures, and favor horizontal layout.  <strong>Uniformity</strong> makes all the pictures the same size with a small thumbnail.
$lang['thumbmodesexp'] = "<strong>None</strong> will disable thumbnails, and uses a lot of bandwidth.  <strong>Layout</strong> will keep most pictures their original dimensions, and usually uses a vertical layout for wide pictures to keep comments readable.  <strong>Scaled</strong> will use thumbnails for wide pictures, and favor horizontal layout.  <strong>Uniformity</strong> makes all the pictures the same size with a small thumbnail.";

//Resize to this:
$lang['resize_to_this'] = 'Resize to this:';

//Show e-mail to members
$lang['email_show'] = 'Show e-mail to members';

//Show smilies
$lang['smilies_show'] = 'Show smilies';

//Host lookup disabled in &ldquo;hacks.php&rdquo; file.
$lang['hosts_disabled'] = 'Host lookup disabled in &ldquo;hacks.php&rdquo; file.';

//Reminder
$lang['word_reminder'] = 'Reminder';

//Anti-spam
$lang['anti_spam'] = 'Anti-spam';

//(Delete without sending e-mail)
$lang['anti_spam_delete'] = '(Delete without sending e-mail)';

//Log
$lang['word_log'] = 'Log';

//You must be an administrator to access the log.
$lang['no_log_access'] = 'You must be an administrator to access the log.';

//{1} entries
$lang['log_entries'] = '{1} entries';

//Category
$lang['word_category'] = 'Category';

//Peer (affected)
$lang['log_peer'] = 'Peer (affected)';

//(Self)
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['log_self'] = '(Self)';

//Required.  Guests cannot see e-mails, and it may be hidden from other members.
$lang['msg_regmail'] = "Required.  Guests cannot see e-mails, and it may be hidden from other members.";

//Normal Chibi Paint
$lang['online_npaintbbs'] = 'Normal Chibi Paint';

//No Animation
$lang['draw_no_anim'] = '(Layers only; no animation)';

//Purge All Registrations
$lang['purge_all_regs'] = "Purge All Registrations";

//Are you sure you want to delete all {1} registrations?
$lang['sure_purge_regs'] = "Are you sure you want to delete all {1} registrations?";

//Animations/Layers
$lang['draw_anims_layers'] = 'Animations/Layers';

//Most applets combine layers with animation data.
$lang['draw_combine_layers'] = 'Most applets combine layers with animation data.';

//Helps prevent accidental loss of pictures.
$lang['draw_help_loss_pics'] = 'Helps prevent accidental loss of pictures.';

//Window close confirmation
$lang['draw_close_confirm'] = 'Window close confirmation';

//Remember draw settings
$lang['draw_remember_settings'] = 'Remember draw settings';

//Any reduction to the number of pictures stored requires confirmation via the checkbox.
$lang['pic_store_change_confirm'] = 'Any reduction to the number of pictures stored requires confirmation via the checkbox.';

//Check this box to confirm any changes
$lang['cpanel_check_box_confirm'] = 'Check this box to confirm any changes';

//Use Lytebox viewer
$lang['cpanel_use_lightbox'] = 'Use Lytebox viewer';

//Enables support for the zooming Lytebox/Lightbox viewer for picture posts.
$lang['cpanel_lightbox_sub'] = 'Enables support for the zooming Lytebox/Lightbox viewer for picture posts.';

//An Administrator has all the basic moderation functions, including editing the notice and banner, banning, changing member flags, and recovering pictures.
$lang['type_aabil'] = 'An Administrator has all the basic moderation functions, including editing the notice and banner, banning, changing member flags, and recovering pictures.';

//Moderator
$lang['word_moderator'] = 'Moderator';

//Moderators have the ability to edit and delete comments, upload, as well as edit post properties (lock, bump, adult, WIP).
$lang['type_mabil'] = 'Moderators have the ability to edit and delete comments, upload, as well as edit post properties (lock, bump, adult, WIP).';

//Use Lightbox pop-up viewer
$lang['profile_use_lightbox'] = 'Use pop-up image viewer';

//Enables pop-up 
$lang['profile_lightbox_sub'] = 'Enables Lightbox/Lytebox pop-up viewer for images instead of a new browser tab or window.';

//{1} {1?day:days} remaining
$lang['recovery_days_remaining'] = "{1} {1?day:days} remaining";

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_notice'] = 'You have {1} unfinished {1?picture:pictures}.';

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_warning'] = 'You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.';

//(Default only)
// context = Placeholder if only one template available
$lang['default_only'] = '(Default only)';

//Private Oekaki
$lang['header_private_oekaki'] = 'Private Oekaki';

//You are not logged in.  Please log in or register to view this oekaki.
$lang['private_please_login'] = 'You are not logged in.  Please log in or register to view this oekaki.';

//Requires members to register or log in to view art and comments.  Guests are completelly blocked.
$lang['private_oekaki_sub'] = 'Requires members to register or log in to view art and comments.  Guests are completelly blocked.';

//Server is able to send e-mail?
$lang['able_send_email'] = 'Server is able to send e-mail?';

//Safety Save Limit
$lang['header_safety_max'] = 'Safety Save Limit';

//Maximum number of unfinished pictures a member may have at a time.  Default is {1}.
$lang['safety_max_sub'] = 'Maximum number of unfinished pictures a member may have at a time.  Default is {1}.';

//You need Java&trade; to use the paint program.  <a href="{1}">Click here to download Java</a>.
$lang['paint_need_java_link'] = 'You need Java&trade; to use the paint program.  <a href="{1}">Click here to download Java</a>.';

//Don't know / I Don't know
// context = drop-down menu option
$lang['option_dont_know'] = "Don't know";

//{1} folder is locked!  Folder must be CHMOD to 755 (preferred) or 777.  View Readme.txt for help.
// NOTE: no HTML.  {1} = folder, {2} = manual
$lang['boot_folder_locked'] = '{1} folder is locked!  Folder must be CHMOD to 755 (preferred) or 777.  View {2} for help.';

//Installer removed.
$lang['boot_inst_rm'] = 'Installer removed.';

//Installer removal failed!
$lang['boot_inst_rm_fail'] = 'Installer removal failed!';

//Updater removed.
$lang['boot_update_rm'] = 'Updater removed.';

//Updater removal failed!
$lang['boot_update_rm_fail'] = 'Updater removal failed!';

//Proceed to index page
$lang['boot_goto_index'] = 'Proceed to index page';

//Remove the files manually via FTP
$lang['boot_remove_ftp'] = 'Remove the files manually via FTP';

//&ldquo;install.php&rdquo; and/or &ldquo;update.php&rdquo; still exists!
$lang['boot_still_exist_sub1'] = '&ldquo;install.php&rdquo; and/or &ldquo;update.php&rdquo; still exists!';

//If you get this error again, delete the files manually via FTP.
$lang['boot_still_exist_sub3'] = 'If you get this error again, delete the files manually via FTP.';

//Password verification failed. Make sure the new password is typed properly in both fields
$lang['pass_ver_failed'] = 'Password verification failed.  Make sure the new password is typed properly in both fields.';

//Password contains invalid characters: {1}
$lang['pass_invalid_chars'] = 'Password contains invalid characters: {1}';

//Password is empty.
$lang['pass_emtpy'] = 'Password is empty.';

//Username contains invalid characters: {1}
$lang['name_invalid_chars'] = 'Username contains invalid characters: {1}';

//Humanity test failed.
$lang['humanity_test_failed'] = 'Humanity test failed.';

//You must submit a valid age declaration (birth year).
$lang['submit_valid_age'] = 'You must submit a valid age declaration (birth year).';

//Your age declaration could not be accepted.
$lang['age_not_accepted'] = 'Your age declaration could not be accepted.';

//A valid URL is required to register on this BBS
$lang['valid_url_req'] = 'A valid URL is required to register on this BBS.';

//You must declare your age to register on this BBS
$lang['must_declare_age'] = 'You must declare your age to register on this BBS.';

//Sorry, the BBS e-mailer isn't working. You'll have to wait for your application to be approved.
$lang['email_wait_approval'] = "Sorry, the BBS e-mailer isn't working. You'll have to wait for your application to be approved.";

//Database error. Please try again.
$lang['db_err'] = 'Database error. Please try again.';

//Database error. Try using {1}picture recovery{2}.
// {1}=BBCode start tag, {2}=BBCode end tag
$lang['db_err_pic_recovery'] = 'Database error. Try using {1}picture recovery{2}.';

//You cannot post a comment because the thread is locked
$lang['no_post_locked'] = 'You cannot post a comment because the thread is locked.';

//HTML links unsupported.  Use NiftyToo/BBCode instead.
$lang['no_html_alt'] = 'HTML links unsupported.  Use NiftyToo/BBCode instead.';

//Guests may only post {1} {1?link:links} per comment on this board.
$lang['guest_num_links'] = 'Guests may only post {1} {1?link:links} per comment on this board.';

//You must be a moderator to mark pictures other than yours as adult.
$lang['mod_change_adult'] = 'You must be a moderator to mark pictures other than yours as adult.';

//Only moderators may change safety save status.
$lang['mod_change_wip'] = 'Only moderators may change safety save status.';

//Only moderators may use this function.
$lang['mod_only_func'] = 'Only moderators may use this function.';

//No mode!  Some security policies or advertisements on shared servers may interfere with comments and picture data.  This is a technical problem.  Ask your admin for help.
$lang['func_no_mode'] = 'No mode!  Some security policies or advertisements on shared servers may interfere with comments and picture data.  This is a technical problem.  Ask your admin for help.';

/* End Version 1.5.5 */



/* Version 1.5.6 */

//Registed
// context = Date on which a member "Submit registration" or "Signed up"
// {1} = count of pending registrations
$lang['registered_on'] = 'Registered';

//Modify Canvas Size (max is {1} &times; {2})
$lang['applet_modify'] = "Modify Canvas Size (max is {1} &times; {2})";

//Canvas (min: {1}&times;{2}, max: {3}&times;{4})
$lang['draw_canvas_min_max'] = 'Canvas (min: {1}&times;{2}, max: {3}&times;{4})';

//If you're having trouble with the applet, try downloading the latest version of Java from {1}.
$lang['javahlp'] = "If you're having trouble with the applet, try downloading the latest version of Java from {1}.";

//If you do not need them anymore, <a href="{1}">click here to remove them</a>.
$lang['boot_still_exist_sub2'] = 'If you do not need them anymore, <a href="{1}">click here to remove them</a>.';

//Delete Palette
$lang['delete_palette'] = 'Delete Palette';

//You may have {1} safety {1?save:saves} at a time.  Remember to finish a safety save soon or it will be automatically deleted within {2} {2?day:days}.
$lang['safesavemsg2'] = "You may have {1} safety {1?save:saves} at a time.  Remember to finish a safety save soon or it will be automatically deleted within {2} {2?day:days}.";

//Safety save was successful!  To resume a safety save, click &ldquo;Draw&rdquo;, or use the &ldquo;Recover Pics&rdquo; menu.
$lang['safesavemsg3'] = "Safety save was successful!  To resume a safety save, click &ldquo;Draw&rdquo;, or use the &ldquo;Recover Pics&rdquo; menu.";

//Every time you retouch your safety save, the delete timer will be reset to {1} {1?day:days}.
$lang['safesavemsg5'] = "Every time you retouch your safety save, the delete timer will be reset to {1} {1?day:days}.";

//Error reading picture #{1}.
$lang['err_readpic'] = "Error reading picture #{1}.";

//What is {1} {2} {3}?
//What is  8   +   6 ?
$lang['humanity_question_3_part'] = "What is {1} {2} {3}?";

//Safety saves are stored for {1} {1?day:days}.
$lang['sagesaveopt3'] = "Safety saves are stored for {1} {1?day:days}.";

//Comments (<a href="{1}">NiftyToo Usage</a>)
$lang['header_comments_niftytoo'] = 'Comments (<a href="{1}">NiftyToo Usage</a>)';

//Edit Comment (<a href="{1}">NiftyToo Usage</a>)
$lang['ecomm_title'] = 'Edit Comment (<a href="{1}">NiftyToo Usage</a>)';

//Edit Picture Info (<a href="{1}">NiftyToo Usage</a>)
$lang['erpic_title'] = 'Edit Picture Info (<a href="{1}">NiftyToo Usage</a>)';

//Message Box (<a href="{1}">NiftyToo Usage</a>)
$lang['chat_msgbox'] = 'Message Box (<a href="{1}">NiftyToo Usage</a>)';

//(<a href="{1}">NiftyToo Usage</a>)
$lang['common_niftytoo'] = '(<a href="{1}">NiftyToo Usage</a>)';

//(Original by <strong>{1}</strong>)
// {1} = member name
$lang['originalby'] = "(Original by <strong>{1}</strong>)";

//If you are not redirected in {1} seconds, click here.
// context = clickable, {1} defaults to "3" or "three"
$lang['common_redirect'] = 'If you are not redirected in {1} seconds, click here.';

//Could not write config file.  Check your server permissions.
$lang['cpanel_cfg_err'] = 'Could not write config file.  Check your server permissions.';

//Enable Mailbox
$lang['enable_mailbox'] = "Enable Mailbox";

//Unable to read picture #{1}.
$lang['delconf_pic_err'] = 'Unable to read picture #{1}.';

//Image too large!  Size limit is {1} &times; {2} pixels.
$lang['err_imagelar'] = "Image too large!  Size limit is {1} &times; {2} pixels.";

//It must not be larger than {1} &times; {2} pixels.
$lang['notlarg'] = "It must not be larger than {1} &times; {2} pixels.";

//(No avatar)
$lang['noavatar'] = "(No avatar)";

//Print &ldquo;Edited on (date)&rdquo;
// context = (date) is a literal.  Actual date not printed.
$lang['print_edited_on'] = "Print &ldquo;Edited on (date)&rdquo;";

//(Edited on {1})
// {1} = current date
$lang['edited_on'] = "(Edited on {1})";

//Print &ldquo;Edited by {1}&rdquo;
// {1} = admin name
$lang['print_edited_by_admin'] = "Print &ldquo;Edited by {1}&rdquo;";

//(Edited by <strong>{1}</strong> on {2})
// {1} = admin name, {2} = current date
$lang['edited_by_admin'] = "(Edited by <strong>{1}</strong> on {2})";

//You may check this if you are an adult (at least {1} years old).
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adultsub'] = "You may check this if you are an adult (at least {1} years old).";

//Load time {1} seconds
$lang['footer_load_time'] = 'Load time {1} seconds';

//Links disabled for guests
// context = HTML-formatted links in comments on pictures
$lang['no_guest_links'] = 'Links disabled for guests.';

//{1}+
// context = Marks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['mark_adult'] = '{1}+';

//Un {1}+
// context = Unmarks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['unmark_adult'] = 'Un {1}+';

//Mailbox:
$lang['mailbox_label'] = 'Mailbox:';

//{1} {1?message:messages}, {2} unread
$lang['mail_count'] = '{1} {1?message:messages}, {2} unread';

//From:
$lang['from_label'] = 'From:';

//Re:
// context = may be used inconsistently for technical reasons
$lang['reply_label'] = 'Re:';

//Subject:
$lang['subject_label'] = 'Subject:';

//Send To:
$lang['send_to_label'] = 'Send To:';

//Message:
$lang['message_label'] = 'Message:';

//<a href="{1}">{2}</a> @ {3}
// context = "{2=username} sent you this mailbox message at/on {3=datetime}"
$lang['mail_sender_datetime'] = '<a href="{1}">{2}</a> @ {3}';

//Registered: {1} {1?member:members} and {2} {2?admin:admins}.
$lang['mmail_reg_list'] = 'Registered: {1} {1?member:members} and {2} {2?admin:admins}.';

//{1} {1?member:members} active within the last {2} days.
$lang['mmail_active_list'] = '{1} {1?member:members} active within last {2} days.';

//Everyone ({1})
$lang['mmail_to_everyone'] = 'Everyone ({1})';

//Active members ({1})
$lang['mmail_to_active'] = 'Active members ({1})';

//All admins/mods ({1})
// context = admins and moderators
$lang['mmail_to_admins_mods'] = 'All admins/mods ({1})';

//Super-admins only
$lang['mmail_to_superadmins'] = 'Super-admins only';

//Flags: FLAG DESCRIPTION
// context = "Can Draw", or "Drawing ability", etc.
$lang['mmail_to_draw_flag']   = 'Flags: Draw';
$lang['mmail_to_upload_flag'] = 'Flags: Upload';
$lang['mmail_to_adult_flag']  = 'Flags: Adult viewing';
$lang['mmail_to_immune_flag'] = 'Flags: Immunity';

//<a href="{1}">Online</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_online'] = '<a href="{1}">Online</a> ({2})';

//<a href="{1}">Chat</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_chat'] = '<a href="{1}">Chat</a> ({2})';

//<strong>Rules</strong>
// context = normally in bold text
$lang['header_rules'] = '<strong>Rules</strong>';

//<strong>Draw</strong>
// context = normally in bold text
$lang['header_draw'] = '<strong>Draw</strong>';

//<a href="{1}">Mailbox</a> ({2})
// context = Used as link. {2} = count of messages
$lang['header_mailbox'] = '<a href="{1}">Mailbox</a> ({2})';

//Online
// context = Used as label. No HTML link. {1} = count of people (if desired)
$lang['chat_online'] = 'Online';

//{1} {1?member:members} {1?matches:match} your search.
$lang['match_search'] = '{1} {1?member:members} {1?matches:match} your search.';

//{1} {1?member:members}, {2} active within {3} days.
$lang['member_stats'] = '{1} {1?member:members}, {2} active within {3} days.';

//(None)
// context = placeholder if no avatar available
$lang['avatar_none'] = '(None)';

//No rank
// or "None". context = administrator rank
$lang['rank_none'] = 'No rank';

//No Thumbnails
$lang['cp_no_thumbs'] = 'No thumbnails';

//No pictures
$lang['no_pictures'] = 'No pictures';

//{1} (last login)
// {1} = IP address.
$lang['ip_by_login'] = '{1} (last login)';

//{1} (last comment)
$lang['ip_by_comment'] = '{1} (last comment)';

//{1} (last picture)
$lang['ip_by_picture'] = '{1} (last picture)';

//(None)
// context = placeholder if no url to web site
$lang['url_none'] = '(None)';

//(Web site)
// context = placeholder if there is a url, but no title (no space to print whole url)
$lang['url_substitute'] = '(Web site)';

//(Default)
// context = placeholder if default template is chosen
$lang['template_default'] = '(Default)';

//(Default)
// context = placeholder if default language is chosen
$lang['language_default'] = '(Default)';

//Default
$lang['palette_default'] = 'Default';

//No Title
$lang['no_pic_title'] = 'No Title';

//Send E-mails
$lang['install_send_emails'] = 'Send E-mails';

//Adjusts how many e-mails are sent by your server.  Default is &ldquo;Yes&rdquo;, and is highly recommended.
$lang['adjust_emails_sent_sub1'] = 'Adjusts how many e-mails are sent by your server.  Default is &ldquo;Yes&rdquo;, and is highly recommended.';

//Minimal
// or "Minimum"
$lang['cpanel_emails_minimal'] = 'Minimal';

//&ldquo;Minimal&rdquo; will reduce e-mails by approximately {1}.  Choose &ldquo;No&rdquo; if your server cannot send e-mail.
// {1} = percentage or fraction
$lang['adjust_emails_sent_sub2'] = '&ldquo;Minimal&rdquo; will reduce e-mails by approximately {1}.  Choose &ldquo;No&rdquo; if your server cannot send e-mail.';

//No (recommended)
$lang['chat_no_set_reccom'] = 'No (recommended)';

//Display - Mailbox
$lang['install_mailbox'] = 'Display - Mailbox';

//Sorry, the BBS e-mailer isn't working. Ask an admin to reset your password.
$lang['no_email_pass_reset'] = "Sorry, the BBS e-mailer isn't working. Ask an admin to reset your password.";

//User deleted, but e-mailer isn't working. Notify user manually at {1}.
$lang['no_email_kill_notify'] = "User deleted, but e-mailer isn't working. Notify user manually at {1}.";

//<strong>Maintenance</strong>
// context = Highly visible notification if board is in maintenacne mode. Disables "Logout"
$lang['header_maint'] = '<strong>Maintenance</strong>';

//The oekaki is down for maintenance
// context = <h2> on plain page
$lang['boot_down_maint'] = 'The oekaki is down for maintenance';

//&ldquo;{1}&rdquo; should be back online shortly.  Send all questions to {2}.
// {1} = oekaki title, {2} = admin e-mail
$lang['boot_maint_exp'] = '&ldquo;{1}&rdquo; should be back online shortly.  Send all questions to {2}.';

//Member name already exists!
$lang['func_reg_name_exists'] = 'Member name already exists!';

//You cannot access flag modification
$lang['func_no_flag_access'] = 'You cannot access flag modification.';

//Account updated, but member could not be e-mailed.
$lang['func_update_no_mail'] = 'Account updated, but member could not be e-mailed.';

//Account rejected, but could not be e-mailed. Notify applicant manually at {1}'
// {1} = e-mail address
$lang['func_reject_no_mail'] = 'Account rejected, but could not be e-mailed. Notify applicant manually at {1}';

//Your age declaration could not be accepted.
$lang['func_bad_age'] = 'Your age declaration could not be accepted.';

//Image too large!  Size limit is {1} bytes.
$lang['err_imagelar_bytes'] = 'Image too large!  Size limit is {1} bytes.';

//No picture data was received. Try again.
$lang['func_no_img_data'] = 'No picture data was received. Try again.';

//An error occured while uploading. Try again.
$lang['func_up_err'] = 'An error occured while uploading. Try again.';



/* whosonline.php */

// context = nouns, not verbs
$lang['o_unknown']     = 'Unknown';
$lang['o_addusr']      = 'Pending List';
$lang['o_banlist']     = 'Banlist';
$lang['o_chatbox']     = 'Chat';
$lang['o_chibipaint']  = 'Chibi Paint';
$lang['o_chngpass']    = 'Change Password';
$lang['o_comment']     = 'Comment';
$lang['o_cpanel']      = 'Control Panel';
$lang['o_delcomments'] = 'Delete Comments';
$lang['o_delpics']     = 'Delete Picture';
$lang['o_delusr']      = 'Delete Users';
$lang['o_draw']        = 'Draw Screen';
$lang['o_edit_avatar'] = 'Edit Avatar';
$lang['o_editcomm']    = 'Edit Comment';
$lang['o_editnotice']  = 'Edit Notice/Banner';
$lang['o_editpic']     = 'Edit Picture';
$lang['o_editprofile'] = 'Edit Profile';
$lang['o_editrules']   = 'Edit Rules';
$lang['o_faq']         = 'FAQ';
$lang['o_index']       = 'View';
$lang['o_index_match'] = 'View by Artist';
$lang['o_lcommentdel'] = 'Delete Comment';
$lang['o_log']         = 'Log';
$lang['o_lostpass']    = 'Password Recovery';
$lang['o_mail']        = 'Mailbox';
$lang['o_massmail']    = 'Mass Mailbox';
$lang['o_memberlist']  = 'Memberlist';
$lang['o_modflags']    = 'Member Permissions';
$lang['o_newpass']     = 'Change Password';
$lang['o_niftyusage']  = 'Using Niftytoo';
$lang['o_notebbs']     = 'NoteBBS';
$lang['o_oekakibbs']   = 'OekakiBBS';
$lang['o_paintbbs']    = 'PaintBBS';
$lang['o_profile']     = 'Profile Viewer';
$lang['o_recover']     = 'Picture Recovery';
$lang['o_retouch']     = 'Retouch';
$lang['o_shibbs']      = 'ShiPainter';
$lang['o_showrules']   = 'Rules';
$lang['o_sqltest']     = 'Diagnostics';
$lang['o_testinfo']    = 'Diagnostics';
$lang['o_upload']      = 'Upload';
$lang['o_viewani']     = 'Anim Viewer';
$lang['o_whosonline']  = 'Online List';

/* END whosonline.php */



//Submit for review
$lang['submit_review'] = 'Submit for review';

//<a href="http://www.NineChime.com/products/" title="Get your own free BBS!">{1}</a> by {2} / Based on <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> by <a href="http://suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
// {1} = "Wacintaki" + link
// {2} = "Waccoon" (may change)
$lang['f_bbs_credits'] = '<a href="http://www.NineChime.com/products/" title="Get your own free BBS!">{1}</a> by {2} / Based on <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> by <a href="http://suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>';

//PaintBBS and Shi-Painter by <a href="http://shichan.jp/">Shi-chan</a> / ChibiPaint by <a href="http://www.chibipaint.com">Mark Schefer</a>
$lang['f_applet_credits'] = 'PaintBBS and Shi-Painter by <a href="http://shichan.jp">Shi-chan</a> / ChibiPaint by <a href="http://www.chibipaint.com">Mark Schefer</a>';

//Administrator Account
// context = default comment for admin account
$lang['install_admin_account'] = 'Administrator Account';

//If you are submitting a picture or just closing the window, click OK."
// context = JavaScript alert.  Browser/version specific, and may be troublesome.
$lang['js_noclose'] = 'If you are submitting a picture or just closing the window, click OK.';

//Comment
// context = verb form; label for making posts on pictures
$lang['verb_comment'] = 'Comment';

//&hellip;
// context = Placeholder if a comment is empty
$lang['no_comment'] = '&hellip;';

//OekakiPoteto 5.x by <a href="http://www.suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
$lang['install_byline'] = 'OekakiPoteto 5.x by <a href="http://www.suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>';

//Wacintaki 1.x modifications by <a href="http://www.NineChime.com/products/">Waccoon</a>
// {1} = "Waccoon" (may change)
$lang['install_byline2'] = 'Wacintaki 1.x modifications by <a href="http://www.NineChime.com/products/">{1}</a>';

//Wacintaki Oekaki: draw pictures online
// context = HTML head->meta name
$lang['meta_desc'] = 'Wacintaki Oekaki: draw pictures online';

//(Link)
// context = Placeholder, link to a web page on the memberlist
$lang['ml_web_link'] = '(Link)';

//-
// context = Placeholder, no web page available on the memberlist
$lang['ml_no_link'] = '-';

//-
// context = Placeholder, no email available on the memberlist
$lang['ml_no_email'] = '-';

//&ldquo;{1}&rdquo; cannot be opened.
// {1} = filename
$lang['file_o_warn'] = '&ldquo;{1}&rdquo; cannot be opened.';



/* log.php */

// The log is a diagnostic tool.  Retain formatting and colons.
// Some filenames (lowercase, without '.php') are not translatable.

// Generic warning
$lang['l_'.WLOG_MISC] = 'Misc';

// Generic error/failure
$lang['l_'.WLOG_FAIL] = 'Failure';

// SQL error
$lang['l_'.WLOG_SQL_FAIL] = 'SQL failure';

// Regular maintenance
$lang['l_'.WLOG_MAINT] = 'Maintenance';

// Input from client, hacking
$lang['l_'.WLOG_SECURITY] = 'Security';

// Updates and events
$lang['l_'.WLOG_BANNER] = 'Banner';
$lang['l_'.WLOG_RULES]  = 'Rules';
$lang['l_'.WLOG_NOTICE] = 'Notice';
$lang['l_'.WLOG_CPANEL] = 'CPanel';
$lang['l_'.WLOG_THUMB_OVERRIDE] = 'Thumbnail Change';
$lang['l_'.WLOG_THUMB_REBUILD]  = 'Thumbnail Rebuild';
$lang['l_'.WLOG_MASS_MAIL]    = 'Massmail';
$lang['l_'.WLOG_BAN]          = 'Ban';
$lang['l_'.WLOG_DELETE_USER]  = 'User Delete';
$lang['l_'.WLOG_REG]          = 'Registration';
$lang['l_'.WLOG_APPROVE]      = 'Approve';
$lang['l_'.WLOG_EDIT_PROFILE] = 'Profile Edit';
$lang['l_'.WLOG_FLAGS]        = 'Flag Change';
$lang['l_'.WLOG_PASS_RECOVER] = 'Password Recover';
$lang['l_'.WLOG_PASS_RESET]   = 'Password Reset';
$lang['l_'.WLOG_ARCHIVE]      = 'Archive';
$lang['l_'.WLOG_BUMP]         = 'Bump';
$lang['l_'.WLOG_RECOVER]      = 'Recovery';
$lang['l_'.WLOG_DELETE]       = 'Delete';
$lang['l_'.WLOG_LOCK_THREAD]  = 'Thread Lock';
$lang['l_'.WLOG_ADULT]        = 'Adult';
$lang['l_'.WLOG_ADMIN_WIP]    = 'WIP (by admin)';
$lang['l_'.WLOG_EDIT_PIC]     = 'Picture Edit';
$lang['l_'.WLOG_EDIT_COMM]    = 'Comment Edit';

//cpanel: Updated
$lang['l_c_update'] = 'cpanel: Updated';

//No RAW POST data
// context = "RAW POST" is a programming term for HTTP data
$lang['l_no_post'] = 'No RAW POST data';

//Cannot allocate new PIC_ID
$lang['l_no_picid'] = 'Cannot allocate new PIC_ID';

//Cannot insert image #{1}
$lang['l_app_no_insert'] = 'Cannot insert image #{1}';

//Cannot save image #{1}
$lang['l_app_no_save'] = 'Cannot save image #{1}';

//paintsave: Bad upload for #{1}, cannot make thumbnail
$lang['l_bad_upload'] = 'paintsave: Bad upload for #{1}, cannot make thumbnail';

//paintsave: Cannot make &ldquot&rdquo; thumbnail for image #{1}
$lang['l_no_t'] = 'paintsave: Cannot make &ldquot&rdquo; thumbnail for image #{1}';

//paintsave: Cannot make &ldquo;r&rdquo; thumbnail for image #{1}
$lang['l_no_r'] = 'paintsave: Cannot make &ldquo;r&rdquo; thumbnail for image #{1}';

//paintsave: Bad datatype for image #{1} (saved as &ldquo;dump.png&rdquo;)
$lang['l_no_type'] = 'paintsave: Bad datatype for image #{1} (saved as &ldquo;dump.png&rdquo;)';

//paintsave: Corrupt image dimentions for #{1}
$lang['l_no_dim'] = 'paintsave: Corrupt image dimentions for #{1}';

//paintsave: cannot write image &ldquo;{1}&rdquo;
$lang['l_no_open'] = 'paintsave: cannot write image &ldquo;{1}&rdquo;';

//Picture: #{1}
// context = "Picture #{1} affected"
$lang['l_mod_pic'] = 'Picture: #{1}';

//Comment: #{1}
// context = "Comment #{1} affected"
$lang['l_mod_comm'] = 'Comment: #{1}';

//WIP: #{1}
// context = "WIP or Safety save #{1} affected"
$lang['l_mod_wip'] = 'WIP: #{1}';

//Active
// context: Active members.  Displayed under "Peer (affected)"
$lang['type_active'] = 'Active'; 

//Sent to:
// context = Password recovery.  {1} = E-mail address.
$lang['l_sent_to'] = 'Sent to: {1}';

//Reset by: Admin
// context = Password reset
$lang['l_reset_admin'] = 'Reset by: Admin';

//Reset by: Member
// context = Password reset
$lang['l_reset_mem'] = 'Reset by: Member';

//Reason sent: Yes or No
// context = Reason for being deleted.  Yes or No only.
$lang['l_reason_yes'] = 'Reason sent: Yes';
$lang['l_reason_no'] = 'Reason sent: No';

//Accepted with the following flags: {1}
// {1} ~ 'GDU' or some other combination of letters
$lang['l_accept_f'] = 'Accepted with the following flags: {1}';

//Profile: Updated
$lang['l_prof_up'] = 'Profile: Updated';

//Banlist: Updated
$lang['l_ban_up'] = 'Banlist: Updated';

//Banner and notice: Updated
$lang['l_banner_notice_up'] = 'Banner and notice: Updated';

//Rules: Updated
$lang['l_rules_up'] = 'Rules: Updated';

//Upload: Cannot insert image &ldquo;{1}&rdquo;
$lang['l_f_no_insert'] = 'Upload: Cannot insert image &ldquo;{1}&rdquo;';

//Upload: Cannot save image &ldquo;{1}&rdquo;
$lang['l_f_no_save'] = 'Upload: Cannot save image &ldquo;{1}&rdquo;';

//Upload: Cannot save image #{1}
$lang['l_f_no_anim'] = 'Upload: Cannot save image #{1}';

//Retouch: #{1}
// context = Bump by retouching picture.
$lang['l_f_bump_retouch'] = 'Retouch: #{1}';

//Locked: #{1}
// context = Thread #{1} locked or unlocked
$lang['l_f_lock']   = 'Locked: #{1}';
$lang['l_f_unlock'] = 'Unlocked: #{1}';

//Marked as adult: #{1}
// context = [Un]marked #{1} as an adults-only picture
$lang['l_f_adult']   = 'Marked as adult: #{1}';
$lang['l_f_unadult'] = 'Unmarked as adult: #{1}';

/* END log.php */



//Member &ldquo;{1}&rdquo; could not be found.
$lang['mem_not_found'] = 'Member &ldquo;{1}&rdquo; could not be found.';

//Profile for &ldquo;{1}&rdquo; not retrievable!  Check the log.
$lang['prof_not_ret'] = 'Profile for &ldquo;{1}&rdquo; not retrievable!  Check the log.';

//Cannot allocate new PIC_ID for upload.
$lang['f_no_picid'] = 'Cannot allocate new PIC_ID for upload.';

//You must be a moderator to lock or unlock threads.
$lang['functions_err22'] = 'You must be a moderator to lock or unlock threads.';

//Please run the <a href="{1}">updater</a>.
$lang['please_update'] = 'Please run the <a href="{1}">updater</a>.';

//Current version: {1}
$lang['please_update_cur'] = 'Current version: {1}';

//New version: {1}
// context = version after update has completed
$lang['please_update_new'] = 'New version: {1}';



/* update.php */

//Starting update from {1} to {2}.
// {1} = from name+version, {2} = to name+version
$lang['up_from_to'] = 'Starting update from {1} to {2}.';

//Finished update to Wacintaki {1}.
// {1} = to version number
$lang['up_fin_to'] = 'Finished update to Wacintaki {1}.';

//Update to Wacintaki {1} failed.
// {1} = to version number
$lang['up_to_fail'] = 'Update to Wacintaki {1} failed.';

//Verifictaion of Wacintaki {1} failed.
// {1} = to version number
$lang['up_ver_fail'] = 'Verification of Wacintaki {1} failed.';

//STOP: Cannot read file &ldquo;{1}&drquo; for database connection.
// {1} = filename
$lang['up_cant_read_for_db'] = 'STOP: Cannot read file &ldquo;{1}&drquo; for database connection.';

//STOP: Cannot write file &ldquo;{1}&rdquo;
// {1} = filename
$lang['up_cant_write_file'] = 'STOP: Cannot write file &ldquo;{1}&rdquo;';

//STOP: &ldquo;{1}&rdquo; file is not writable.
// {1} = filename
$lang['up_file_locked'] = 'STOP: &ldquo;{1}&rdquo; file is not writable.';

//WARNING: missing image &ldquo;{1}&rdquo; for post {2}.
// {1} = picture filename, {2} = number (ID_2)
$lang['up_missing_img'] = 'WARNING: missing image &ldquo;{1}&rdquo; for post {2}.';

//WARNING:  Folder &ldquo;{1}&rdquo; is not writable.  CHMOD the folder to 775 or 777 after the updater has finished.
// {1} = folder name
$lang['up_folder_locked'] = 'WARNING:  Folder &ldquo;{1}&rdquo; is not writable.  CHMOD the folder to 775 or 777 after the updater has finished.';

//STOP: Unable to add admin ranks to database (SQL: {1})
// {1} db_error()
$lang['up_no_add_rank'] = 'STOP: Unable to add admin ranks to database (SQL: {1})';

//STOP:  Could not set admin rank for {1} (SQL: {2})
// {1} = username
// {2} = db_error()
$lang['up_no_set_rank'] = 'STOP:  Could not set admin rank for {1} (SQL: {2})';

//STOP: Could not create folder &ldquo;{1}&rdquo;.
// {1} = filename
$lang['up_cant_make_folder'] = 'STOP: Could not create folder &ldquo;{1}&rdquo;.';

//STOP: Could not update piccount (current picture number) for new sorting system (SQL: {1})
// {1} = db_error()
$lang['up_no_piccount'] = 'STOP: Could not update piccount (current picture number) for new sorting system (SQL: {1})';

//STOP: Wax Poteto database not at required version (1.3.0).  Run the Wax Poteto 5.6.x updater first.
$lang['up_wac_no_130'] = 'STOP: Wax Poteto database not at required version (1.3.0).  Run the Wax Poteto 5.6.x updater first.';

//STOP: Could not verify database version marker (SQL: {1})
// {1} = db_error()
$lang['up_no_set_db_utf'] = 'STOP: Could not verify database version marker (SQL: {1})';

//NOTE: Remember to copy your resource files (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) into the Wacintaki &ldquo;resource&rdquo; folder and CHMOD them so they are writable.
$lang['up_move_res'] = 'NOTE: Remember to copy your resource files (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) into the Wacintaki &ldquo;resource&rdquo; folder and CHMOD them so they are writable.';

//Wacintaki 1.5.6 requires significant changes to the database to support international letters.
$lang['up_change_sum'] = 'Wacintaki 1.5.6 requires significant changes to the database to support international letters.';

//Click here to start the database conversion.
$lang['up_click_start_conv'] = 'Click here to start the database conversion.';

//STOP: Cannot read UTF-8 marker from database.
$lang['up_no_dbutf_marker'] = 'STOP: Cannot read UTF-8 marker from database.';

//Cleaned up {1} orphaned files.
$lang['up_cleaned_sum'] = 'Cleaned up {1} orphaned {1?file:files}.';

//Unsupported update type &ldquo;From {1} to {2}&rdquo;.
// {1} from version, {2} = to version
$lang['up_no_up_num'] = 'Unsupported update type &ldquo;From {1} to {2}&rdquo;.';

//Board config version:  &ldquo;{1}&rdquo;,  database version:  &ldquo;{2}&rdquo;
// {1}+{2} = numbers
$lang['up_no_up_sum'] = 'Board config version:  &ldquo;{1}&rdquo;,  database version:  &ldquo;{2}&rdquo;';

//Update cannot continue.
$lang['up_no_cont'] = 'Update cannot continue.';

//If problems persist, visit the <a href="{1}">NineChime.com Forum</a>.
// {1} = url
$lang['up_nc_short'] = 'If problems persist, visit the <a href="{1}">NineChime.com Forum</a>.';

//Wacintaki Update {1}
// {1} = number
$lang['up_header_title'] = 'Wacintaki Update {1}';

//If you have multiple boards, make sure each board is at the current version.
$lang['up_mult_warn'] = 'If you have multiple boards, make sure each board is at the current version.';

//Click the button below to finalize the update.  This will delete the updater files and prevent security problems.
$lang['up_click_final'] = 'Click the button below to finalize the update.  This will delete the updater files and prevent security problems.';

//Secure updater and go to the BBS
// context = clickable button
$lang['up_secure_button'] = 'Secure updater and go to the BBS';

//Some warnings were returned during the update.  Please note these messages and run the updater again to ensure everything is set properly.  You may run the updater multiple times if needed.
$lang['up_warn_rerun'] = 'Some warnings were returned during the update.  Please note these messages and run the updater again to ensure everything is set properly.  You may run the updater multiple times if needed.';

//Errors occured during the update!  Check your server and database permissions and try again.  The update will not function properly until all errors are resolved.
$lang['up_stop_sum'] = 'Errors occured during the update!  Check your server and database permissions and try again.  The update will not function properly until all errors are resolved.';

//NOTE: Make sure you\'ve deleted your old OekakiPoteto v5.x template and language files before running this updater.  Your old OekakiPoteto templates and language files will not work with Wacintaki.
$lang['up_no_op_tpl'] = 'NOTE: Make sure you\'ve deleted your old OekakiPoteto v5.x template and language files before running this updater.  Your old OekakiPoteto templates and language files will not work with Wacintaki.';

//Click Next to start the update.
$lang['up_next_start'] = 'Click Next to start the update.';

//Next
// context = clickable button
$lang['up_word_next'] = 'Next';

//{1} detected.
// {1} = version
$lang['up_v_detected'] = '{1} detected.';

//You appear to be running the latest version of Wacintaki already.  You may proceed to verify that the last update completed correctly.
$lang['up_latest_ver'] = 'You appear to be running the latest version of Wacintaki already.  You may proceed to verify that the last update completed correctly.';

//Click Next to verify the update.
$lang['up_next_ver'] = 'Click Next to verify the update.';

//Unknown version.
$lang['up_unknown_v'] = 'Unknown version.';

//Config: {1}, Database: {2}
// {1}+{2} = numbers
$lang['up_unknown_v_sum'] = 'Config: {1}, Database: {2}';

//This updater only supports Wacintaki versions less than or equal to {1}.
// {1} = number
$lang['up_v_spread_sum'] = 'This updater only supports Wacintaki versions less than or equal to {1}.';

/* END update.php */



/* update_rc.php */

//Database has already been updated to UTF-8.
$lang['upr_already_utf'] = 'Database has already been updated to UTF-8.';

//Click here to run the updater.
$lang['upr_click_run'] = 'Click here to run the updater.';

//PHP extension &ldquo;iconv&rdquo; not available.  Cannot recode from Big5 to UTF-8!
// context = iconv is all lower case (shell program).
$lang['upr_iconv_mia'] = 'PHP extension &ldquo;iconv&rdquo; not available.  Cannot recode from Big5 to UTF-8!';

//Please visit the <a href="{1}">NineChime Forum</a> for help.
// {1} = url
$lang['upr_nc_shortest'] = 'Please visit the <a href="{1}">NineChime Forum</a> for help.';

//This tool will convert an existing Wacintaki database to support international letters and text (such as &laquo;&ntilde;&raquo; and &bdquo;&#223;&ldquo;).
$lang['upr_conv_w_8bit'] = 'This tool will convert an existing Wacintaki database to support international letters and text (such as &laquo;&ntilde;&raquo; and &bdquo;&#223;&ldquo;).';

//If you have multiple Wacintaki boards installed on your web site, you only need to run this tool once, but you will still need to run the updater on each board.
// context = update_rc.php runs only once, update.php must run on each board.
$lang['upr_xself_mult_warn'] = 'If you have multiple Wacintaki boards installed on your web site, you only need to run this tool once, but you will still need to run the updater on each board.';

//Using this tool more than once will not cause any damage.
$lang['upr_no_damage'] = 'Using this tool more than once will not cause any damage.';

//NOTE:  {1} encoding detected.  Conversion will be from {1} to UTF-8.
// {1} = iconv charset ("iso-8859-1", "big5", "utf-8", etc.)
$lang['upr_char_det_conv'] = 'NOTE:  {1} encoding detected.  Conversion will be from {1} to UTF-8.';

//If you used international letters in your password, you will need to recover your password after the update.
$lang['upr_utf_rec_pass'] = 'If you used international letters in your password, you will need to recover your password after the update.';

//Click here to begin step {1} of {2}.
// {1}+{2} = numbers
$lang['upr_click_steps'] = 'Click here to begin step {1} of {2}.';

//If you have problems converting the database, try visiting the <a href="{1}">NineChime Forum</a>, or you may <a href="{2}">bypass the conversion.</a>  If you bypass the conversion, existing comments with international letters will be corrupt, but new comments will post fine.
// {1} = url, {2} = local url
$lang['upr_nc_visit_bypass'] = 'If you have problems converting the database, try visiting the <a href="{1}">NineChime Forum</a>, or you may <a href="{2}">bypass the conversion.</a>  If you bypass the conversion, existing comments with international letters will be corrupt, but new comments will post fine.';

//STOP: Cannot create one or more temp files for database conversion.  Check the permissions of the main oekaki folder.
$lang['upr_no_make_temp'] = 'STOP: Cannot create one or more temp files for database conversion.  Check the permissions of the main oekaki folder.';

//Done!  Database has been updated to UTF-8.
$lang['upr_done_up'] = 'Done!  Database has been updated to UTF-8.';

//Found {1} tables in the database.
$lang['upr_found_tbls'] = 'Found {1} tables in the database.';

//{1} {1?row:rows} need to be converted.
$lang['upr_found_rows'] = '{1} {1?row:rows} need to be converted.';

//<strong>Please wait...</strong> it may take a minute for the next page to show.
$lang['upr_plz_wait'] = '<strong>Please wait...</strong> it may take a minute for the next page to show.';

//STOP: Double-click or unexpected reload detected.  Please wait another {1} seconds.
$lang['upr_dbl_click'] = 'STOP: Double-click or unexpected reload detected.  Please wait another {1} seconds.';

//Building resource files.  Please wait...
$lang['upr_build_res_wait'] = 'Building resource files.  Please wait...';

//Step {1} of database update finished.  Ready to start step {2}.
$lang['upr_step_ready_num'] = 'Step {1} of database update finished.  Ready to start step {2}.';

//If there are any errors printed above, it\'s strongly recommended that you <a href="{1}">visit NineChime forum</a> for help.  The oekaki should still function properly if all members use only English letters.
// {1} = url
$lang['upr_if_err_nc'] = 'If there are any errors printed above, it\'s strongly recommended that you <a href="{1}">visit the NineChime Forum</a> for help.  The oekaki should still function properly if all members use only English letters.';

//Wacintaki UTF-8 Update
$lang['upr_header_title'] = 'Wacintaki UTF-8 Update';

//TIMEOUT: database partially exported.  This is normal if your database is very large.
$lang['upr_time_partial'] = 'TIMEOUT: database partially exported.  This is normal if your database is very large.';

//{1} {1?row:rows} updated before timeout.
$lang['upr_rows_partial'] = '{1} {1?row:rows} updated before timeout.';

//Click here to resume.
$lang['upr_click_resume'] = 'Click here to resume.';

//TIMEOUT_IMPORT: database partially imported.  This is normal if your database is very large.
$lang['upr_time_norm'] = 'TIMEOUT_IMPORT: database partially imported.  This is normal if your database is very large.';

//STOP:{1} Cannot get tables: (SQL: {2})
// {1} = placeholder, so maintain spacing.  {2} = db_error()
$lang['upr_sql_bad_tbls'] = 'STOP:{1} Cannot get tables: (SQL: {2})';

//STOP:{1} No SQL tables found!
// {1} = placeholder, so maintain spacing.
$lang['upr_sql_no_tbls'] = 'STOP:{1} No SQL tables found!';

//STOP: Error reading column &ldquo;{1}&rdquo;: (SQL: {2})
// {1} = column name, {2} = db_error()
$lang['upr_bad_col'] = 'STOP: Error reading column &ldquo;{1}&rdquo;: (SQL: {2})';

//STOP: No SQL columns found in table &ldquo;{1}&rdquo;
// {1} = table name
$lang['upr_no_cols'] = 'STOP: No SQL columns found in table &ldquo;{1}&rdquo;';

//{1} {1?row:rows} collected.  Total time for export: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_exp_time'] = '{1} {1?row:rows} collected.  Total time for export: {2} {2?second:seconds}.';

//{1} {1?row:rows} updated.  Total time for import: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_imp_time'] = '{1} {1?row:rows} updated.  Total time for import: {2} {2?second:seconds}.';

//STOP: set_db_utf8_misc_marker({1}): Cannot insert db_utf8 marker (SQL: {2})
// {1} = debug argument (ignore), {2} = db_error()
$lang['upr_utf_no_ins'] = 'STOP: set_db_utf8_misc_marker({1}): Cannot insert db_utf8 marker (SQL: {2})';

/* END update_rc.php */



//Uploaded
$lang['word_uploaded'] = 'Uploaded';

//(Uploaded by <strong>{1}</strong>)
$lang['uploaded_by_admin'] = '(Uploaded by <strong>{1}</strong>)';

/* End Version 1.5.6 */



/* Version 1.5.9 */

//Edit Notice
$lang['header_enotice'] = 'Edit Notice';

//Add Banner Image
$lang['btn_add_banner'] = 'Add Banner Image';

/* End Version 1.5.9 */



/* Version 1.5.13 */

//You do not have the permissions to rename users.
$lang['err_no_rename_usr'] = 'You do not have the permissions to rename users.';

//<strong>WARNING: old username does not exist!
$lang['rename_old_nonexist'] = '<strong>WARNING: old username does not exist!</strong>';

//<strong>WARNING: new username already exists!
$lang['rename_new_exists'] = '<strong>WARNING: new username already exists!</strong>';

//<strong>WARNING: you cannot change names of people with the same or greater rank than yourself.
$lang['rename_no_rank'] = '<strong>WARNING: you cannot change names of people with the same or greater rank than yourself.</strong>';

//Username changed
// context = log file
$lang['l_renamed_mem'] = 'Username changed';

//Rename User
$lang['rename_user_title'] = 'Rename User';

//Please note that the rename tool will only work on a single oekaki board.  If you have multiple boards sharing one memberlist, pictures and comments on other boards will not be associated with the new name.
$lang['rename_user_disclaimer'] = 'Please note that the rename tool will only work on a single oekaki board.  If you have multiple boards sharing one memberlist, pictures and comments on other boards will not be associated with the new name.';

//Rename
// context = submit button
$lang['word_rename'] = 'Rename';

//Old Username
$lang['old_username'] = 'Old Username';

//New Username
$lang['new_username'] = 'New Username';

//Ready to rename the user.  Please make sure spelling and capitalization are correct before proceeding.
$lang['confirm_rename_ok'] = 'Ready to rename the user.  Please make sure spelling and capitalization are correct before proceeding.';

//Rename User
// context = admin menu
$lang['header_rename'] = 'Rename User';

//Rename Users
// context = whosonline.php
$lang['o_renameusr']  = 'Rename Users';

/* End Version 1.5.13 */



/* Version 1.5.14 */

//{1} Registration Review
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['reg_rev_title'] = '{1} Registration Review';

//A new user named '{1}' has submit a registration application to the oekaki {2}.  You may reivew the application in the 'View Pending' menu or by visiting this link:\n\n{3}
// {1} = username
// {2} = oekaki title
// {3} = script URL
$lang['email_new_user_application'] = "A new user named '{1}' has submit a registration application to the oekaki {2}.  You may reivew the application in the 'View Pending' menu or by visiting this link:\n\n{3}";

//A valid e-mail address is required.
$lang['email_req_err'] = 'A valid e-mail address is required.';

/* End Version 1.5.14 */

?>
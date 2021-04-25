<?php // ÜTF-8
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastea-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.14x - Last modified 2014-11-06 (x:2015-08-23)

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
$lang['cfg_language'] = "Deutsch";

// English Language name (native encoding, capitalized)
$lang['cfg_lang_eng'] = "German";

// Name of translator(s)
$lang['cfg_translator'] = "Trunksi - Nadin Unbehau";

//$lang['cfg_language'].' translation by: '.$lang['cfg_translator'];
// context = Variables not needed. Change around order as needed.
$lang['footer_translation'] = $lang['cfg_language'].' Übersetzt von: '.$lang['cfg_translator'];

// Comments (native encoding)
$lang['cfg_comments'] = "Das deutsche Sprachpaket für Wacintaki. Fehler bitte im Ninechime-Forum (Oekaki Tech Support vom OekakiPoteto) melden. ";

// Zero plural form.  0=singular, 1=plural
// Multiple plural forms need to be considered in next language API
$lang['cfg_zero_plural'] = 1;

// HTML charset ("Content-type")
$charset = "utf-8";

// HTML language name ("Content-language" or "lang" tags)
$metatag_language = "de";

// Date formats (http://us.php.net/manual/en/function.date.php)
// Quick ref: j=day(1-31), l=weekday(Sun-Sat), n=month(1-12), F=month(Jan-Dec)
$datef['post_header'] = 'l, F jS Y, G:i';
$datef['admin_edit']  = 'F j, Y, G:i';
$datef['chat']        = 'H:i';
$datef['birthdate']   = 'Y/n/j';
$datef['birthmonth']  = 'Y/n';
$datef['birthyear']   = 'Y';

// Drop-down menu in registration / edit profile.
// "Y" and "M" and "D" in any order.  All 3 letters required.
$datef['age_menu'] = 'DMY';

// Left and Right double quotes
$lang['ldquo'] = '&bdquo;';
$lang['rdquo'] = '&ldquo;';



/* Language Translation */

//Wacintaki Installation
$lang['install_title'] = "Wacintaki Installation";

//MySQL Information
$lang['install_information'] = "MySQL Information";

//If you do not know if your server has MySQL or how to access your MySQL account, e-mail your tech. support and ask for the following information: hostname, database name, username, and password. Without this information, you will be unable to install Wacintaki. If you need to remove the databases, look at the bottom of the page for a link to remove them. If you haven't read the readme.txt, DO IT NOW or your installation will fail! You must make sure you have the proper files and directories CHMODed before you continue.
$lang['install_disclaimer'] = "Falls Sie nicht wissen, ob Ihr Server MySQL unterstützt und wie man darauf zugreift, kontaktieren Sie bitte zunächst Ihren technischen Support und fragen nach folgenden Informationen: Datenbankserver (hostname), Datenbanknamen, Datenbank-Benutzername und Passwort der Datenbank. Ohne diese genannten Informationen ist es nicht möglich, das Wacintaki zu installieren. Falls Sie die Datenbank entfernen müssen, lesen Sie diesbezüglich am Ende der Seite nach. Es wird außerdem empfohlen, dass Sie vorher die readme.txt lesen, da sonst die Installation fehlschlagen könnte. Sie müssen sicher gehen, dass alle CHMOD-Rechte an die entsprechenden Dateien und Verzeichnisse korrekt vergeben wurden, bevor Sie fortfahren.";

//If your OP currently works, there is no need to change the MySQL information.
$lang['cpanel_mysqlinfo'] = "Sollte Ihr OP momentan problemlos funktionieren, besteht kein Grund die MySQL-Einstellungen zu ändern.";

//Default Language
$lang['cpanel_deflang'] = "Standardsprache";

//Artist
$lang['word_artist'] = "Zeichner";

//Compression Settings
$lang['compress_title'] = "Kompressionseinstellungen";

//Date
$lang['word_date'] = "Datum";

//Time
$lang['word_time'] = "Zeit";

//min
$lang['word_minutes'] = "Min";

//unknown
$lang['word_unknown'] = "Unbekannt";

//Age
$lang['word_age'] = "Alter";

//Gender
$lang['word_gender'] = "Geschlecht";

//Location
$lang['word_location'] = "Ort";

//Joined
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_joined'] = "Angemeldet seit";

//Language
$lang['word_language'] = "Sprache";

//Charset
$lang['word_charset'] = "Zeichenkodierung";

//Help
$lang['word_help'] = "Hilfe";

//URL
$lang['word_url'] = "URL";

//Name
$lang['word_name'] = "Name";

//Action
$lang['word_action'] = "Aktion";

//Disable
$lang['word_disable'] = "Deaktivieren";

//Enable
$lang['word_enable'] = "Aktivieren";

//Translator
$lang['word_translator'] = "Übersetzer";

//Yes
$lang['word_yes'] = "Ja";

//No
$lang['word_no'] = "Nein";

//Accept
$lang['word_accept'] = "Akzeptieren";

//Reject
$lang['word_reject'] = "Ablehnen";

//Owner
$lang['word_owner'] = "Besitzer";

//Type
$lang['word_type'] = "Typ";

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
$lang['word_username'] = "Benutzername";

//E-mail
$lang['word_email'] = "E-Mail";

//Animated
$lang['word_animated'] = "Animiert";

//Normal
$lang['word_normal'] = "Normal";

//Registered
$lang['word_registered'] = "Registriert";

//Guests
$lang['word_guests'] = "Gäste";

//Guest
$lang['word_guest'] = "Gast";

//Refresh
$lang['word_refresh'] = "Aktualisieren";

//Comments
$lang['word_comments'] = "Kommentare";

//Animations
$lang['word_animations'] = "Animationen";

//Archives
$lang['word_archives'] = "Archiv";

//Comment
$lang['word_comment'] = "Kommentar";

//Delete
$lang['word_delete'] = "Löschen";

//Reason
$lang['word_reason'] = "Begründung";

//Special
$lang['word_special'] = "Informationen &amp; Rechte";

//Archive
$lang['word_archive'] = "Archivieren";

//Unarchive
$lang['word_unarchive'] = "Dearchivieren";

//Homepage
$lang['word_homepage'] = "Homepage";

//PaintBBS
$lang['word_paintbbs'] = "PaintBBS";

//OekakiBBS
$lang['word_oekakibbs'] = "OekakiBBS";

//Archived
$lang['word_archived'] = "Archiviert";

//IRC server
$lang['word_ircserver'] = "IRC Server";

//days
$lang['word_days'] = "Tage";

//Commenting
$lang['word_commenting'] = "Kommentiert";

//Paletted
$lang['word_paletted'] = "Palette";

//IRC nickname
$lang['word_ircnickname'] = "IRC Benutzername";

//Template
$lang['word_template'] = "Template";

//IRC channel
$lang['word_ircchannel'] = "IRC Channel";

//Horizontal
$lang['picview_horizontal'] = "Horizontal";

//Vertical
$lang['picview_vertical'] = "Vertikal";

//Male
$lang['word_male'] = "Männlich";

//Female
$lang['word_female'] = "Weiblich";

//Error
$lang['word_error'] = "Fehler";

//Board
$lang['word_board'] = "Board";

//Ascending
$lang['word_ascending'] = "Aufsteigend";

//Descending
$lang['word_descending'] = "Absteigend";

//Recover for {1}
$lang['recover_for'] = "Wiederherstellen für {1}";

//Flags
$lang['word_flags'] = "Rechte";

//Admin
$lang['word_admin'] = "Admin";

//Background
$lang['word_background'] = "Hintergrund";

//Font
$lang['word_font'] = "Schriftart";

//Links
$lang['word_links'] = "Links";

//Header
$lang['word_header'] = "Header";

//View
$lang['word_view'] = "Anzeigen";

//Search
$lang['word_search'] = "Suche";

//FAQ
$lang['word_faq'] = "FAQ";

//Memberlist
$lang['word_memberlist'] = "Mitgliederliste";

//News
$lang['word_news'] = "News";

//Drawings
$lang['word_drawings'] = "Bilder";

//Submenu
$lang['word_submenu'] = "Untermenü";

//Retouch
$lang['word_retouch'] = "Retusche";

//Picture
$lang['word_picture'] = "Bild";



/* niftyusage.php */

//Link to Something
$lang['lnksom'] = "Ein Link zu etwas";

//URLs without {1} tags will link automatically.
// {1} = "[url]"
$lang['urlswithot'] = "URLs ohne {1}-Tags werden automatisch in Links umgewandelt.";

//Text
$lang['nt_text'] = "Text";

//Bold text
// note: <b> tag
$lang['nt_bold'] = "Fetter Text";

//Italic text
// note: <i> tag
$lang['nt_italic'] = "Kursiver Text";

//Underlined text
// note: <u> tag
$lang['nt_underline'] = "Unterstrichener Text";

//Strikethrough text
// note: <del> tag
$lang['nt_strikethrough'] = "Durchgestrichener Text";

//Big text
// note: <big> tag
$lang['nt_big'] = "Großer Text";

//Small text
// note: <small> tag
$lang['nt_small'] = "Kleiner Text";

//Quoted text
// note: <blockquote> tag
$lang['nt_quoted'] = "Zitierter Text";

//Preformatted text
// note1: <code> tag (formerly <pre>).
// note2: "Monospaced" or "Fixed Width" would also be appropriate.
$lang['nt_preformatted'] = "Vorformatierter Text";

//Show someone how to quote
// Context = example of double brackets: "[[quote]]translation[[/quote]]"
$lang['nt_d_quote'] = "So zeigen Sie, wie man einen Text zitieren kann.";

//These tags don't exist
// context = example of double brackets: "[[ignore]]translation[[/ignore]]"
$lang['nt_d_ignore'] = "Diese Tags existieren nicht.";

//ignore
// context = Example tag for double brackets: "[[ignore]]"
// translate to any word/charset if desired.
$lang['nt_ignore_tag'] = "Ignorieren";

//Use double brackets to make Niftytoo ignore tags.
$lang['nt_use_double'] = "Benutzen Sie doppelte Klammern (eckig) damit Niftytoo-Tags nicht umgewandelt werden und diese somit zu ignorieren.";

/* END niftyusage.php */



//Mailbox
$lang['word_mailbox'] = "Mailbox";

//Inbox
$lang['word_inbox'] = "Posteingang";

//Outbox
$lang['word_outbox'] = "Postausgang";

//Subject
$lang['word_subject'] = "Betreff";

//Message
$lang['word_message'] = "Nachricht";

//Reply
$lang['word_reply'] = "Antworten";

//From
$lang['word_from'] = "Von";

//Write
$lang['word_write'] = "Verfassen";

//To
$lang['word_to'] = "An";

//Status
$lang['word_status'] = "Status";

//Edit
$lang['word_edit'] = "Bearbeiten";

//Register
$lang['word_register'] = "Registrieren";

//Administration
$lang['word_administration'] = "Administration";

//Draw
$lang['word_draw'] = "Zeichnen";

//Profile
$lang['word_profile'] = "Profil";

//Local
$lang['word_local'] = "Ort";

//Edit Pics
$lang['header_epics'] = "Bilder verwalten";

//Recover Pics
$lang['header_rpics'] = "Bilder wiederherstellen";

//Delete Pics
$lang['header_dpics'] = "Bilder löschen";

//Delete Comments
$lang['header_dcomm'] = "Kommentare löschen";

//Edit Comments
$lang['header_ecomm'] = "Kommentare bearbeiten";

//View Pending
$lang['header_vpending'] = "Benutzer freischalten";

//Re-Touch
$lang['word_retouch'] = "Überarbeiten";

//Logout
$lang['word_logoff'] = "Abmelden";

//Modify Flags
$lang['common_mflags'] = "Rechte verwalten";

//Delete User
$lang['common_delusr'] = "Benutzer löschen";

//(include the http://)
$lang['common_http'] = "(inklusive des http://)";

//Move to page
$lang['common_moveto'] = "Gehe zu Seite";

//Scroll Down
$lang['chat_scroll'] = "Herunterscrollen";

//Conversation
$lang['chat_conversation'] = "Unterhaltung";

//Chat Information (required)
$lang['chat_chatinfo'] = "Chatinformation (Benötigt)";

//Move to Page
$lang['common_mpage'] = "Gehe zu Seite";

//Delete Picture
$lang['common_deletepic'] = "Bild löschen";

//Picture Number
$lang['common_picno'] = "Bildnummer";

//Close this Window
$lang['common_window'] = "Dieses Fenster schließen";

//Last Login
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['common_lastlogin'] = "Letzte Anmeldung";

//Picture Posts
$lang['common_picposts'] = "Veröffentlichte Bilder";

//Comment Posts
$lang['common_compost'] = "Geschriebene Kommentare";

//Join Date
$lang['common_jdate'] = "Beitrittsdatum";

//You can use the message box to leave requests/comments, or talk to Wacintaki members if they are online.<b> All comments will be deleted after a specific amount of posts.<br /></b><br /><b>*</b> - Indicates a registered member.<br /><b>#</b> - Indicates a Guest<br /><br />Although you can see the current online registered members in the chat, Guests who are still online will dissapear after a specific amount of time. Be aware that everyime a guest posts, there WILL be multiple instances of the user under Guest. <br /><br />Your IP and Hostname are tracked in case of abuse. To see a user's IP/hostname, hover your mouse over their username in the chat. The rate of refresh is 15 seconds.
$lang['chat_warning'] = "Sie können Anfragen &amp Kommentare hinterlassen, oder sich mit anderen Mitgliedern unterhalten, wenn diese online sind. <b> Alle Kommentare werden nach Erreichen einer bestimmten Anzahl gelöscht. <br /></b> <br /> <b>*</b> - Kennzeichnet registrierte Mitglieder. <br /> <b>#</b> - Kennzeichnet Gäste.<br /><br /> Alle aktuell registrierten Mitglieder werden im Chat angezeigt. Dagegen werden Gäste, welche gerade online sind, nach einiger Zeit ausgeblendet. Bedenken Sie also, sollte ein Gast etwas schreiben, so WERDEN mehrere Gäste als GAST dargestellt. <br /><br /> Ihre IP und Hostname werden aus Sicherheitsgründen, für den Fall eines Missbrauchs, gespeichert. Um Informationen eines Benutzers anzuzeigen, fahren Sie mit der Maus über dessen Benutzernamen im Chat. Die Aktualisierungsrate liegt bei 15 Sekunden.";

//Database Hostname
$lang['install_dbhostname'] = "Datenbankserver (Hostname)";

//Database Name
$lang['install_dbname'] = "Datenbankname";

//Database Username
$lang['install_dbusername'] = "Datenbank-Benutzername";

//Database Password
$lang['install_dbpass'] = "Datenbank-Passwort";

//Display - Registration (Required)
$lang['install_dispreg'] = "Anzeigen - Registration (Benötigt)";

//URL to Wacintaki
$lang['install_opurl'] = "URL zum Wacintaki";

//Registration E-Mail
$lang['install_email'] = "E-Mail zur Registration";

//the e-mail address to use to send out registration information; this is REQUIRED if you are using automatic registration
$lang['install_emailsub'] = "Die E-Mail-Adresse wird benötigt, um Informationen der Anmeldung zu verschicken; wird benötigt, wenn eine automatische Registration erfolgen soll und der Benutzer sich freischalten muss.";

//General
$lang['install_general'] = "Allgemein";

//Encryption Key
$lang['install_salt'] = "Schlüssel";

//An encryption key can be any combination of characters; it is used to generate unique encryption strings for passwords. If you are using multiple boards with the same member database, make sure all boards share the same encryption key.
$lang['install_saltsub'] = "Der Schlüssel (Encryption Key) kann aus einer beliebigen Kombination von Zeichen bestehen; er wird benutzt, um eine eindeutige Verschlüsselungen für Passwörter zu generieren. Sollten Sie mehrere Boards nutzen, welche sich die selbe Benutzerdatenbank teilen, so achten Sie darauf, dass der selbe Schlüssel (Encryption Key) benutzt wird.";

//Picture Directory
$lang['install_picdir'] = "Bilderverzeichnis";

//directory where your pictures will be stored
$lang['install_picdirsub'] = "Verzeichnis/Ordner in dem die Bilder gespeichert werden";

//Number of pictures to store
$lang['install_picstore'] = "Anzahl der zu speichernden Bilder (maximal)";

//the max number of pictures the OP can store at a time
$lang['install_picstoresub'] = "die maximale Anzahl an Bildern die gleichzeitig gespeichert werden können";

//Registration
$lang['install_reg'] = "Registration";

//Automatic User Delete
$lang['install_adel'] = "Automatische Benutzerlöschung";

//user must login within the specified number of days before being deleted from the database
$lang['install_adelsub'] = "Der Benutzer muss sich innerhalb eines bestimmten Zeitraumes (Angabe in Tagen) einloggen, oder er wird automatisch aus der Datenbank gelöscht.";

//days <b>(-1 disables the automatic delete)</b>
$lang['install_adelsub2'] = "Tage <b>(-1 deaktiviert die Automatische Benutzerlöschung)</b>";

//Allow Guests to Post?
$lang['install_gallow'] = "Dürfen Gäste kommentieren?";

//if yes, guests can make comment posts on the board and chat
$lang['install_gallowsub'] = "Wurde JA gewählt, so können Gäste auf dem Board und im Chat Kommentare abgeben";

//Require Approval? (Select no for Automatic Registration)
$lang['install_rapproval'] = "Bestätigung benötigt? (Wählen Sie NEIN für eine Automatische Registration)";

//if yes, approval by the administrators is required to register
$lang['install_rapprovalsub'] = "Wurde JA gewählt, so muss ein Administrator den Benutzer nach der Registration freischalten";

//Display - General
$lang['install_dispgen'] = "Allgemeine Einstellungen";

//Default Template
$lang['install_deftem'] = "Standard-Template";

//templates are stored in the templates directory
$lang['install_deftemsub'] = "Templates werden im Template-Verzeichnis gespeichert";

//Title
$lang['install_title2'] = "Titel";

//Title for the Wacintai.  Avoid using symbols or decoration, as it will be used for e-mail headers.
$lang['install_title2sub'] = "Wie soll Ihr Wacintaki heißen? Wird in der Navigationsleiste angezeigt.";

//Display - Chat
$lang['install_dispchat'] = "Chat-Einstellungen";

//Max Number of Lines to Store for Chat
$lang['install_displinesmax'] = "Maximale Anzahl an Zeilen, die für den Chat gespeichert werden.";

//Lines of Chat Text to Display in a Page
$lang['install_displines'] = "Anzahl der Zeilen, welche pro Seite angezeigt werden.";

//Paint Applet Settings
$lang['install_appletset'] = "Einstellungen der Zeichenanwendungen";

//Maximum Animation Filesize
$lang['install_animax'] = "Maximale Datengröße von Animationen";

//the max filesize animation files can be in bytes; default is 500,000 bytes or 500KB
$lang['install_animaxsub'] = "Die maximale Datengröße für Animationsdateien in Bytes; Standard ist 500.000 Bytes oder 500KB.";

//bytes (1024 bytes = 1KB)
$lang['install_bytes'] = "Bytes (1024 Bytes = 1KB)";

//Administrator Information
$lang['install_admininfo'] = "Administrator - Informationen";

//Login
$lang['install_login'] = "Login"; // COMMON

//Password
$lang['install_password'] = "Passwort"; // COMMON

//Recover Password
$lang['header_rpass'] = "Passwort vergessen";

//Re-Type Password
$lang['install_repassword'] = "Passwort wiederholen";

//TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms.
$lang['install_TOS'] = "NUTZUNGSBEDINGUNGEN: OekakiPoteto ist Freeware (kostenlos). Sie dürfen unendlich viele Kopien des OekakiPoteto auf Ihrer Website installieren. Sie dürfen den Code modifizieren und Ihre eigenen Skripte erstellen, solange genaue Quellenangaben zu RanmaGuy und Marcello am Schluss der Seiten des OekakiPoteto vergeben werden; zusammen mit einer Verlinkung zu suteki.nu. Falls Sie diese Regeln missachten, so sind die Ersteller dazu berechtigt, Ihr OekakiPoteto schließen zu lassen. Das OekakiPoteto (Software) darf NICHT zum Verkauf angeboten werden. Sollten Sie es gekauft haben, so wurden Sie betrogen, denn dies ist eine kostenlose Software. Bei Benutzung des OekakiPotetos, modifiziert oder unmodifiziert, erklären Sie sich dieser Bestimmungen einverstanden.";

//Databases Removed!
$lang['install_dbremove'] = "Datenbanken entfernt!";

//View Pending Users: Select a User
$lang['addusr_vpending'] = "Schwebende Benutzer anzeigen: Benutzer auswählen";

//View Pending Users: Details
$lang['addusr_vpendingdet'] = "Schwebende Benutzer anzeigen: Details";

//Art URL
$lang['addusr_arturl'] = "Link zu eigenen Bildern/Arbeiten";

//Art URL (Optional)
$lang['reg_arturl_optional'] = "Link zu eigenen Bildern/Arbeiten (Optional)";

//Art URL (Required)
$lang['reg_arturl_required'] = "Link zu eigenen Bildern/Arbeiten (Benötigt)";

//Draw Access
$lang['common_drawacc'] = "Zeichnen erlaubt";

//Animation Access
$lang['common_aniacc'] = "Animationen erlaubt";

//Comments (will be sent to the registrant)
$lang['addusr_comment'] = "Kommentare (werden an den registrierten Benutzer gesendet)";

//Edit IP Ban List
$lang['banip_editiplist'] = "IP-Bannliste bearbeiten";

//Use one IP per line.  Comments may be enclosed in parentheses at end of line.
$lang['banip_editiplistsub'] = 'Benutzen Sie pro Zeile eine IP. Kommentare können am Ende der Zeile in Klammern beigefügt werden.';

//Usage Example: <strong style="text-decoration: underline">212.23.21.* (Username - banned for generic name!)</strong>
$lang['banip_editiplistsub2'] = 'Beispiel: <strong style="text-decoration: underline">212.23.21.* (Benutzername - gebannt aufgrund -Grund einfügen-!)</strong>';

//Edit Host Ban List
$lang['banip_edithostlist'] = "Host-Bannliste bearbeiten";

//Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!
$lang['banip_edithostlistsub'] = 'Gleicht dem Muster der Bannung von IPs. Diese Maßnahme bannt gesamte ISPs und möglicherweise eine <em>große</em> Anzahl an Leuten, deshalb nutzen Sie diese Funktion mit Bedacht!';

//Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>
$lang['banip_edithostlistsub2'] = 'Beispiel: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs wechseln zu oft)</strong>';

//Ban List
$lang['header_banlist'] = "Bannliste";

//Control Panel
$lang['header_cpanel'] = "Kontrollzentrum";

//Send OPMail Notice
$lang['header_sendall'] = "OPMail versenden ";

//<b>You have been banned!<br /><br />Reasons:<br /></b>- A user from your ISP was banned, which banned everyone on that ISP<br />- You were banned for malicious use of the OekakiPoteto<br /><br /><em>If you feel that this message was made in error, speak to an adminstrator of the OekakiPoteto.</em>
$lang['banned'] = "<b>Sie wurden gebannt!<br /><br />Gründe:<br /></b>- Ein Benutzer Ihrer ISP wurde gebannt, was zur Folge hat, dass alle Benutzer mit dieser ISP gebannt wurden.<br />- Sie wurden gebannt aufgrund böswilliger Benutzung des OekakiPotetos.<br /><br /><em>Sind Sie der Meinung, dass diese Nachricht auf einen Irrtum basiert, kontaktieren Sie einen Administrator des OekakiPotetos.</em>";

//Retrieve Lost Password
$lang['chngpass_title'] = "Vergessenes Passwort zusenden";

//Because your password is encrypted, there is no way to retrieve it. Instead, you must specify a new password. If you recieve no errors when submitting this form, that means your password has successfully changed and you can login with it once you are redirected to the index page.
$lang['chngpass_disclaimer'] = "Da Ihr Passwort verschlüsselt ist, kann es nicht wiederhergestellt werden. Stattdessen müssen Sie ein neues Passwort festlegen. Sollten Sie keine Fehlermeldung beim Absenden des Formulars erhalten, bedeutet das, dass Ihr Passwort erfolgreich geändert wurde und Sie sich nun mit dem neuen Passwort anmelden können, sobald Sie auf die Startseite weitergeleitet wurden.";

//New Password
$lang['chngpass_newpwd'] = "Neues Passwort";

//Add Comment
$lang['comment_add'] = "Kommentar hinzufügen";

//Title of Picture
$lang['comment_pictitle'] = "Titel des Bildes";

//Adult Picture?
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['comment_adult'] = "Gesperrtes Bild/{1}+?";

//Comment Database
$lang['comment_database'] = "Kommentardatenbank";

//Global Picture Database
$lang['gpicdb_title'] = "Allgemeine Bilddatenbank";

//Delete User
$lang['deluser_title'] = "Benutzer löschen";

//will be sent to the deletee
$lang['deluser_mreason'] = "Wird dem zu löschenden Benutzer zugesandt.";

//Clicking delete will remove all records associated with the user, including pictures, comments, etc. An e-mail will be sent to the user appened with your contact e-mail in the case the deletee has further questions on the removal.
$lang['deluser_disclaimer'] = "Mit Bestätigung der Löschung werden alle Aufzeichnungen/Einträge (inklusive der Bilder, Kommentare, etc.) bezüglich dieses Benutzers entfernt. Eine E-Mail wird an die vom Benutzer angegebene Kontakt-E-Mail-Adresse gesendet, für den Fall, dass dieser weitere Fragen zu seiner Löschung hat.";

//Animated NoteBBS
$lang['online_aninbbs'] = "NoteBBS - Animiert";

//Normal OekakiBBS
$lang['online_nmrlobbs'] = "OekakiBBS - Normal";

//Animated OekakiBBS
$lang['online_aniobbs'] = "OekakiBBS - Animiert";

//Normal PaintBBS
$lang['online_npaintbbs'] = "PaintBBS - Normal";

//Palette PaintBBS
$lang['online_palpaintbbs'] = "PaintBBS - Palette";

//Admin Pic Recover
$lang['online_apicr'] = "Bildwiederherstellung - Admin";

//Edit Notice
$lang['enotice_title'] = "Ankündigungen bearbeiten";

//Edit Profile
$lang['eprofile_title'] = "Profil bearbeiten";

//URL Title
$lang['eprofile_urlt'] = "URL-Titel";

//IRC Information
$lang['eprofile_irctitle'] = "IRC-Information";

//Current Template
$lang['eprofile_curtemp'] = "Aktuelles Template";

//Current Template Details
$lang['eprofile_curtempd'] = "Aktuelles Template - Details";

//Select New Template
$lang['eprofile_templsel'] = "Neues Template wählen";

//Comments / Preferences
$lang['eprofile_compref'] = "Kommentare / Interessen";

//Picture View Mode
$lang['eprofile_picview'] = "Anzeigemodus für Bilder";

//Allow Adult Images
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adult'] = "Bilder für Erwachsene/gesperrte Bilder ({1}+) betrachten";

//Change Password
$lang['eprofile_chngpass'] = "Passwort ändern";

//Old Password
$lang['eprofile_oldpass'] = "Altes Passwort";

//Retype Password
$lang['eprofile_repass'] = "Passwort wiederholen";

//You will be automatically logged out if your password successfully changes; you need to re-login when this occours.
$lang['eprofile_pdisc'] = "Sie werden automatisch abgemeldet, wenn Ihr Passwort erfolgreich geändert wurde; Sie müssen sich danach neu anmelden.";

//Use your browser button to go back.
$lang['error_goback'] = "Benutzen Sie Ihren Browser um zurückzukehren.";

//Who's Online (last 15 minutes)
$lang['online_title'] = "Wer war in den letzten 15 Minuten online";

//View Animation
$lang['viewani_title'] = "Animation anzeigen";

//file size
$lang['viewani_files'] = "Dateigröße";

//Register New User
$lang['register_title'] = "Neuen Benutzer anlegen";

//A VALID E-MAIL ADDRESS IS REQUIRED TO REGISTER!
$lang['register_sub2'] = "EINE GÜLTIGE E-MAIL-ADRESSE WIRD BENÖTIGT, UM SICH ZU REGISTRIEREN!";

//Will be shown on your profile when registering; this comment box is limited to 80 chars for proper introduction so Admins can identify you; your IP and hostname is also tracked for security purposes.
$lang['register_sub3'] = "Wird auf Ihrem Profil bei der Anmeldung angezeigt. Sie können ein Kommentar (bis zu 80 Zeichen) verfassen, um sich kurz vorzustellen. Ihre IP und Ihr Anbieter werden ebenfalls, aus Sicherheitsgründen, gespeichert.";

//Include a URL to a picture or website that displays a piece of your work that you have done.
$lang['register_sub4'] = "Geben Sie die URL zu einem Bild oder einer Webseite mit Ihren Werken an, um zu zeigen, was Sie bisher gezeichnet/gemalt haben.";

//THIS IS NECESSARY TO REQUEST ACCESS TO DRAW ON OEKAKI
$lang['register_sub5'] = "DIES WIRD BENÖTIGT, UM ZUGRIFF AUF DIE MALRECHTE AUF DEM OEKAKI ZU BEKOMMEN";

//Picture Recovery
$lang['picrecover_title'] = "Wiederherstellung von Bildern";

//Profile for {1}
// {2} = Gender. Singular=Male/Unknown, Plural=Female
$lang['profile_title'] = "Profil von {1}";

//send a message
$lang['profile_sndmsg'] = "Nachricht senden";

//Latest Pictures
$lang['profile_latest'] = "Letzte Bilder";

//Modify Applet Size
$lang['applet_size'] = "Die Größe der Arbeitsfläche bearbeiten";

//Using Niftytoo
$lang['niftytoo_title'] = "Niftytoo verwenden";

//Nifty-markup is a universal markup system for OekakiPoteto. It allows for all the basic formatting you could want in your messages, profiles, and text.
$lang['niftytoo_titlesub'] = "Nifty-markup ist ein universelles &bdquo;markup system&ldquo; für OekakiPoteto. Es erlaubt Ihnen den Text einfach in Ihren Nachrichten, Profilen und sonstigen Texten zu formatieren.";

//Linking/URLs
$lang['niftytoo_linking'] = "Verlinkung/URLs";

//To have a url automatically link, just type it in, beginning with http://
$lang['niftytoo_autolink'] = "Soll die URL automatisch verlinkt werden, so setzen Sie am Anfang (vor dem WWW) das &bdquo;http://&ldquo;";

//Basic Formatting
$lang['niftytoo_basicfor'] = "Einfache Formatierung";

//Change a font's color to the specified <em>colorcode</em>.
$lang['niftytoo_textcol'] = "Die Schriftfarbe zu einen bestimmten <em>Farbcode</em> ändern";

//will produce
$lang['niftytoo_produce'] = "bewirkt";

//Intermediate Formatting
$lang['niftytoo_intermform'] = "Fortgeschrittene Formatierung";

//Modify Permissions
$lang['niftytoo_permissions'] = "Rechte verwalten";

//Recover Any Pic
$lang['header_rapic'] = "Ein Bild wiederherstellen";

//Super Administrator
$lang['type_sadmin'] = "Super Administrator";

//Owner
$lang['type_owner'] = "Besitzer";

//Administrator
$lang['type_admin'] = "Administrator";

//Draw Access
$lang['type_daccess'] = "Zeichnen erlaubt";

//Animation Access
$lang['type_aaccess'] = "Verwendung von Animationen erlaubt";

//Immunity
$lang['type_immunity'] = "Immunität";

//Adult Viewing
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['type_adultview'] = "Darf Bilder für Erwachsene/gesperrte Bilder ({1}+) betrachten";

//General User
$lang['type_guser'] = "Benutzer";

//A Super Administrator has the ability to add administrators as well as the abilities that Administrators have.
$lang['type_sabil'] = "Ein Super Administrator kann andere Administratoren hinzufügen und deren Funktionen/Zugriffsrechte festlegen.";

//Removing this permission will suspend their account.
$lang['type_general'] = "Bei Entfernung dieser Genehmigung wird der Account geschlossen/gelöscht.";

//Gives the user access to draw.
$lang['type_gdaccess'] = "Ermöglicht dem Benutzer zu Malen/Zeichnen.";

//Gives the user access to animate.
$lang['type_gaaccess'] = "Der Benutzer darf so Animationen benutzen.";

//Prevents a user from being deleted if the Kill Date is set.
$lang['type_userkill'] = "Der Benutzer ist vor der Automatischen Löschung geschützt.";

//Member List
$lang['mlist_title'] = "Mitgliederliste";

//Pending Member
$lang['mlist_pending'] = "Schwebende Benutzer";

//Send Mass Message
$lang['massm_smassm'] = "Rundmail verfassen";

//The message subject
$lang['mail_subdesc'] = "Betreff der Nachricht";

//The body of the message
$lang['mail_bodydesc'] = "Inhalt der Nachricht";

//Send Message
$lang['sendm_title'] = "Nachricht senden";

//The recipient of the message
$lang['sendm_recip'] = "Der Empfänger der Nachricht";

//Read Message
$lang['readm_title'] = "Nachricht lesen";

//Retrieve Lost Password
$lang['lostpwd_title'] = "Passwort wiederherstellen";

//An e-mail will be sent to the e-mail address you have in your profile. If you did not specify an e-mail address when you registered, you will have to re-register for a new account. The e-mail will contain a URL to where you can specify a new password, as well as the IP and hostname of the computer used to request the password for security purposes.
$lang['lostpwd_directions'] = "Eine E-Mail wird an die im Profil angegebene E-Mail-Adresse gesendet. (Wenn Sie keine E-Mail-Adresse bei Ihrer Registration angegeben haben, müssen Sie einen neuen Account erstellen.) Die zugesendete E-Mail enthält einen Link, wo Sie dann ein neues Passwort festlegen können. (Ihre IP und Hostname des Computers werden aus Sicherheitsgründen genutzt, um ein neues Passwort anzufordern.)";

//Local Comment Database
$lang['lcommdb_title'] = "Lokale Kommentardatenbank";

//Language Settings
$lang['eprofile_langset'] = "Spracheinstellungen";



/* functions.php */

//A subject is required to send a message.
$lang['functions_err1'] = "Sie müssen einen Betreff eingeben, um diese Nachricht zu versenden.";

//You cannot use mass mailing.
$lang['functions_err2'] = "Sie können keine Rundmails verfassen.";

//Access Denied. You do not have permissions to modify archives.
$lang['functions_err3'] = "Zugriff verweigert. Sie besitzen nicht die nötigen Rechte um Archive zu bearbeiten.";

//The username you are trying to retrieve to does not exist. Please check your spelling and try again.
$lang['functions_err4'] = "Der Benutzername nach dem Sie suchen, konnte nicht gefunden werden oder existiert nicht. Bitte überprüfen Sie Ihre Eingabe und versuchen es erneut.";

//Your new and retyped passwords do not match. Please go back and try again.
$lang['functions_err5'] = "Ihr neues und wiederholtes Passwort stimmen nicht überein. Bitte versuchen Sie es erneut.";

//Invalid retrival codes. This message will only appear if you have attempted to tamper with the password retrieval system.
$lang['functions_err6'] = "Ungültige Codeabfrage. Diese Nachricht erscheint nur, wenn Sie versucht haben das Passwort-Wiederherstellungssystem zu manipulieren.";

//The username you are trying to send to does not exist. Please check your spelling and try again.
$lang['functions_err9'] = "Der Benutzername an den Sie diese Nachricht senden möchten, konnte nicht gefunden werden oder existiert nicht. Bitte überprüfen Sie Ihre Eingabe und versuchen es anschließend erneut.";

//You need to be logged in to send messages.
$lang['functions_err10'] = "Sie müssen eingeloggt sein, um Nachrichten senden zu können.";

//You cannot access messages in the mailbox that do not belong to you.
$lang['functions_err11'] = "Sie können auf keine Nachrichten zugreifen, die Ihnen nicht gehören.";

//Access Denied. You do not have permissions to delete users.
$lang['functions_err12'] = "Zugriff verweigert. Sie besitzen keine Rechte Benutzer zu löschen.";

//Access Denied: Your password is invalid, or you are still a pending member.
$lang['functions_err13'] = "Zugriff verweigert: Ihr Passwort ist ungültig oder Sie wurden noch nicht als Benutzer freigeschaltet.";

//Invalid verification code.
$lang['functions_err14'] = "Ungültiger Verifizierungs-Code.";

//The e-mail address specified in registration already exists in the database. Please re-register with a different address.
$lang['functions_err15'] = "Diese E-Mail-Adresse ist bereits vorhanden. Bitte registrieren Sie sich mit einer anderen E-Mail-Adresse.";

//You do not have the credentials to add or remove users.
$lang['functions_err17'] = "Sie dürfen keine Benutzer löschen oder hinzufügen.";

//You cannot claim a picture that is not yours.
$lang['functions_err18'] = "Sie haben keinen Anspruch auf Bilder, welche Ihnen nicht gehören.";

//You cannot delete a comment that does not belong to you if you are not an Administrator.
$lang['functions_err19'] = "Sie können keine Kommentare anderer Benutzer löschen, wenn Sie kein Administrator sind.";

//You cannot delete a picture that does not belong to you if you are not an Administrator.
$lang['functions_err20'] = "Sie können keine Bilder anderer Benutzer löschen, wenn Sie kein Administrator sind.";

//You cannot edit a comment that does not belong to you.
$lang['functions_err21'] = "Sie können keine Kommentare anderer Benutzer bearbeiten.";

//{1} Password Recovery
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['precover_title'] = '{1} - Passwort vergessen/wiederherstellen';

//Dear {1},\n\nYou or someone with the IP/hostname [{6}] has requested for a password retrieve on {2 @ {3}. To retrieve your password, please copy and paste or click on this link into your browser:\n\n{4}\n\nYou will then be asked to specify a new password. If you did not request this, you may discard this message.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
// {6} = IP address
$lang['pw_recover_email_message'] = "Sehr geehrte/geehrter {1},\n\nSie oder jemand mit dieser IP/hostname [{6}] hatte eine Anfrage gestellt, ein neues Passwort auf {2} @ {3} zu erhalten. Um dieses Passwort zu erhalten, kopieren und fügen Sie den Link in Ihren Browser ein oder klicken auf diesen:\n\n{4}\n\nSie werden gebeten ein neues Passwort festzulegen. Falls Sie diese Anfrage nicht abgeschickt haben, so ignorieren Sie diese Nachricht.";

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['mandel_title'] = '{1} - Nachricht der Löschung';

//Dear {1},\n\nYour account has been deleted from {2} @ {3}. If you have any questions, please e-mail the administrator that removed your account.\n\nDeleted by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['act_delete_email_message'] = "Sehr geehrte/geehrter {1},\n\nIhr Account wurde auf {2} @ {3} entfernt. Falls Sie Fragen diesbezüglich haben, kontaktieren Sie bitte den Administrator, der Ihren Account gelöscht hat.\n\nGelöscht von: {4} ({5})\nKommentare: {6}";

//{1} Registration Details
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['autoreg_title'] = '{1} - Registrations-Details';

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{4}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: Automated Registration
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = permissions
$lang['auto_accept_email_message'] = "Sehr geehrte/geehrter {1},\n\nIhre Registration wurde akzeptiert! Sie können sich nun auf {2} @ {3} mit dem Benutzernamen und dem Passwort, welche Sie während des Registrationsvorganges angegeben haben,  einloggen. Nach erfolgreichem Login, können Sie Ihr eigenes Profil bearbeiten und Ihre IP und Hostnamen entfernen (für Administratoren sind dieses jedoch weiterhin sichtbar), welche während des Vorganges gespeichert wurden.\n\nIhnen wurden folgende Rechte zugeteilt:\n{4}\nSollten Sie keine Zeichen-/Animationsrechte besitzen und diese benötigen, schicken Sie eine E-Mail an einen Administrator, um weitere Informationen zu erhalten.\n\n Außerdem wird empfohlen in der FAQ bezüglich des Zeitraumes für die Automatische Benutzerlöschung nachzulesen. Wenn diese aktiviert wurde, müssen Sie sich innerhalb des angegebenen Zeitraums einloggen, um zu verhindern, dass Ihr Account aufgrund Inaktivität gelöscht wird.\n\nBestätigt durch: Automatisierter Registration";

//{1} Verification Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['verreg_title'] = '{1} - Kontrollnachricht';

//Dear {1},\n\nYou have registered for {2} @ {3}. To complete your registration, please copy and paste or click on this link into your browser:\n\n{4}\n\nThis will verify your account so you can login into the OekakiPoteto.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
$lang['ver_email_message'] = "Sehr geehrte/geehrter {1},\n\nSie haben sich auf {2} @ {3} registriert. Um die Registrierung abzuschließen, klicken Sie bitte auf den Link. Sollte dies nicht möglich sein, kopieren und fügen Sie diesen in Ihren Browser ein:\n\n{4}\n\nDieser Vorgang wird Ihren Account bestätigen und Sie können sich anschließend auf dem OekakiPoteto einloggen.";

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{7}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = comments
// {7} = permissions
$lang['admin_accept_email_message'] = "Sehr geehrte/geehrter {1},\n\nIhre Registration wurde akzeptiert! Sie können sich nun auf {2} @ {3} mit dem Benutzernamen und dem Passwort, welche Sie während des Registrationsvorganges angegeben haben, einloggen. Nach erfolgreichem Login, können Sie Ihr eigenes Profil bearbeiten und Ihre IP und Hostnamen entfernen (für Administratoren sind dieses jedoch weiterhin sichtbar), welche während des Vorganges gespeichert wurden.\n\nIhnen wurden folgende Rechte zugeteilt:\n{7}\nSollten Sie keine Zeichen-/Animationsrechte besitzen und diese benötigen, schicken Sie eine E-Mail an einen Administrator, um weitere Informationen zu erhalten.\n\n Außerdem wird empfohlen in der FAQ bezüglich des Zeitraumes für die Automatische Benutzerlöschung nachzulesen. Wenn diese aktiviert wurde, müssen Sie sich innerhalb des angegebenen Zeitraums einloggen, um zu verhindern, dass Ihr Account aufgrund Inaktivität gelöscht wird.\n\nBestätigt von: {4} ({5})\nKommentare: {6}";

//Dear {1},\n\nYour registration at {2} @ {3}, has been rejected. Please e-mail the {2} administrator who rejected you for more details. DO NOT reply to this e-mail address.\n\nRejected by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['reg_reject_email_message'] = "Sehr geehrte/geehrter {1},\n\nIhre Registration auf {2} @ {3}, wurde abgelehnt. Bitte kontaktieren Sie den Administrator von {2}, welcher Sie abgelehnt hat, um weitere Informationen zu erhalten. ANTWORTEN SIE NICHT auf diese E-Mail.\n\nAbgelehnt von: {4} ({5})\nKommentare: {6}";

//Your picture has been removed
// NOTE: mailbox subject.  BBCode only.  No newlines.
$lang['picdel_title'] = 'Ihr Bild wurde gelöscht';

//Hello,\n\nYour picture ({1}) has been removed from the database by {2} for the following reason:\n\n{3}\n\nIf you have any questions/comments regarding the action, you may reply to this message.
// NOTE: mailbox message.  BBCode only, and use \n rather than <br />.
// {1} = url
// {2} = admin name
// {3} = reason
$lang['picdel_admin_note'] = "Hallo,\n\nIhr Bild ({1}) wurde aus der Datenbank von folgenden Administrator gelöscht {2}; aus dem folgenden Grund:\n\n{3}\n\nFür Fragen und Kommentare bezüglich dieser Aktion, können Sie auf diese Nachricht antworten.";

//(No reason specified)
$lang['picdel_admin_noreason'] = '(Es wurde kein Grund angegeben)';

//Safety save
$lang['to_wip_admin_title'] = 'Zwischenspeicherung/Safety save';

//One of your pictures has been turned into a safety save by {1}. To finish it, go to the draw screen. It must be finished within {2} days.
$lang['to_wip_admin_note'] = 'Eines Ihrer Bilder wurde in den Zwischenspeicher/Safety save von {1} getan. Um dieses zu beenden, gehen Sie zum Zeichen-Bildschirm. Es muss innerhalb von {2} Tagen beendet werden.';

/* END functions.php */



/* maint.php */

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['kill_title'] = "{1} - Nachricht über Löschung";

//Dear {1},\n\nThis is an automated message from the {2} automatic deletion system. Your account has been deleted because you have not logged into the oekaki within the last {3} days. If you want to re-register, please visit {4}\n\nBecause the account has been deleted, all post, comment, and other records associated with your username has been removed, and cannot be re-claimed. To avoid further deletions upon re-registration, be sure to log into your account within the specified amount of days in the FAQ.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['kill_email_message'] = "Sehr geehrte/geehrter {1},\n\nDies ist eine automatische Nachricht des Automatischen Löschungssystem des Wacintakis - {2}. Ihr Account wurde aufgrund seiner Inaktivität gelöscht, weil Sie sich nicht innerhalb der letzten {3} Tage eingeloggt haben. Wenn Sie sich neu registrieren wollen, dann gehen Sie zu {4}.\n\nWeil Ihr Account gelöscht wurde, wurde alles (Bilder, Kommentare und andere Einträge) bezüglich Ihres Benutzernamens entfernt und kann nicht wiederhergestellt werden. Um weitere Löschungen, bei einer erneuten Registration, zu vermeiden achten Sie darauf, sich regelmäßig einzuloggen. Die genaue Angabe der Tage können Sie in der FAQ finden.\n\nSie können außerdem Immunitätsrechte bei einem Administrator anfordern, was Sie vor einer automatischen Löschung bewahren würde. Bei Fragen kontaktieren Sie bitte den Besitzer der Seite.\n\nMit freundlichen Grüßen,\nAutomatisierte Löschung";

//{1} Registration Expired
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['regexpir'] = "{1} - Registration abgelaufen";

//Dear {1},\n\nYour registration at {2} has expired becuase you did not activate your account within {3} days. To submit a new registration, please visit {4}\n\nIf you did not receive a link to activate your account in a seperate e-mail, try using a different address or check the anti-spam settings used for your e-mail.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['reg_expire_email_message'] = "Sehr geehrte/geehrter {1},\n\nIhre Registration bei {2} @ {4} ist abgelaufen, weil Sie Ihren Account nicht innerhalb der {3} Tage aktiviert haben. Bitte registrieren Sie sich erneut.\n\n Falls Sie keine Mail mit einen Aktivierungslink erhalten haben, dann versuchen Sie eine andere E-Mail-Adresse oder überprüfen Sie die Anti-Spam-Einstellungen Ihres E-Mail-Anbieters.\n\n Mit freundlichen Grüßen,\nAutomatisierte Registration";

/* END maint.php */



/* FAQ */

//Frequently Asked Questions
$lang['faq_title'] = 'Frequently Asked Questions - Häufig Gestellte Fragen';

//<strong>Current Wacintaki version: {1}</strong>
$lang['faq_curver'] = '<strong>Aktuelle Wacintaki Version: {1}</strong>';

//<strong>This oekaki deletes inactive accounts after {1} days.  Log in regularly to keep your account active.</strong>
$lang['faq_autoset'] = '<strong>Dieses Oekaki löscht inaktive Accounts nach {1} Tagen.  Loggen Sie sich regelmäßig ein, damit Ihr Account nicht gelöscht wird.</strong>';

//<strong>No automatic deletion is set.</strong>
$lang['faq_noset'] = '<strong>Es wurde keine Automatische Benutzerlöschung aktiviert.</strong>';

//Get the latest Java for running oekaki applets.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_java'] = 'Laden Sie sich die neueste Java-Version herunter, um die Oekaki-Anwendungen nutzen zu können.';

//JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_jtablet'] = 'JTablet fügt Java eine Druckempfindlichkeit hinzu. Unterstützt: PC, Mac und Linux.';

//Table of Contents
$lang['faq_toc'] = 'Inhaltsverzeichnis';

// -----

//What is an &ldquo;oekaki?&rdquo;
$lang['faq_question'][0] = 'Was ist ein &bdquo;Oekaki&ldquo;?';

$lang['faq_answer'][0] = '<p>Oekaki ist japanisch und bedeutet grob übersetzt &bdquo;Kritzelei&ldquo; oder &bdquo;kritzeln&ldquo;. Im Internet sind Oekaki Boards im Grunde wie Foren aufgebaut, welche speziell für das das Malen von Bildern im Internet entwickelt wurden. So können Benutzer mit den eingebauten Malanwendungen Bilder im Webbrowser malen. Ursprünglich waren Oekakis nur einfache Bilder mit einer Größe von 300 &times; 300 Pixeln und unter Verwendung weniger Farben. Oekakis wurden dann nachfolgend aufgelistet, anstelle des heutigen Formates, was an eine Art Mischung aus Galerie und Forum erinnert. Oekaki Boards tauchten erstmals auf japanischen Websites um 1998 auf. Diese waren damals nur in Japan verfügbar und wurden oft für Anime- oder Videospiel-Fanarts genutzt.</p>

<p>Heute sind die Zeichenanwendungen anspruchsvoller gestaltet und aufwendigere Bilder - in verschiedenen Größen und mit mehreren Ebenen - werden nun unterstützt. Dennoch bleibt das Oekaki-Foren-Format für gewöhnlich das selbe. Oekakis sind größtenteils dafür eingerichtet worden, dass eigene Arbeiten/Zeichnungen von einem selbst erstellt werden können. Fotos und Bilder die von einer anderen Person erstellt wurden, sollten nicht hochgeladen werden und ist sogar in den meisten Fällen gänzlich verboten. Einige Oekaki Boards erlauben es, dass man Bilder vom Computer hochladen kann, aber die meisten unterstützen oder erlauben diese Funktion nicht.</p>

<p>Der Begriff &bdquo;Oekaki&ldquo; kann sowohl die Bilder als auch das Board selbst bezeichnen. Wenn jemand ein Oekaki malt, dann malt er ein Bild. Wenn jemand auf einem Oekaki malt, oder nach Oekakis schaut, dann meint man generell, dass dieser auf einem Oekaki Board unterwegs ist.</p>';


//What is Wacintaki, and why is it sometimes called Wacintaki Poteto?
$lang['faq_question'][1] = 'Was ist das Wacintaki und warum wird es manchmal Wacintaki Poteto genannt?';

$lang['faq_answer'][1] = '<p>Das Wacintaki ist ein Oekaki Board, was auf einer eigenen (persönlichen) Website installiert werden kann. Es ist ein übliches Oekaki Board, mit der Ausnahme, dass Leute sich registrieren müssen bevor sie malen können. Wacintaki entstand aus einen vorherigen Open-Source Oekaki, welches unter OekakiPoteto bekannt war und von Theo Chakkapark und Marcello Bastéa-Forte geschrieben wurde. Ältere Version dieses Boardes waren unter dem Namen Wacintaki Poteto bekannt. Heute trägt es nur noch den Namen Wacintaki.</p>

<p>Wenn Sie Interesse haben, ein eigenes Wacintaki auf Ihrer Website zu installieren, besuchen Sie die Wacintaki-Homepage oder den NineChime Software Support, um weitere Informationen diesbezüglich zu erhalten.</p>';


$lang['faq_question'][2] = 'Wie kann man anfangen zu zeichnen/malen?';

$lang['faq_answer'][2] = '<p>Bevor Sie mit dem Zeichnen (od. Malen) anfangen können, müssen Sie sich zunächst registrieren. Nachdem die Registration abgeschlossen ist, können Sie mit dem Zeichnen beginnen; indem Sie auf &bdquo;Zeichnen&ldquo; in der Menüleiste klicken. Beachten Sie, dass Administratoren Ihnen die Zeichenrechte und Ihre Registration widerrufen können und Sie sogar jederzeit bannen können, wenn Sie sich auf dem Board falsch verhalten.</p>';


$lang['faq_question'][3] = 'Worin besteht der Unterschied zwischen zeichnen und hochladen?';

$lang['faq_answer'][3] = '<p>Die Oekaki Boards ermöglichen es, dass der Benutzer mit einer eingebauten Zeichenanwendung Bilder im Browser selbst zeichnen kann. Einige Oekaki Boards erlauben es, eigene Bilder vom Computer hochzuladen, wenn der Besitzer diesem zustimmt. Andere Boards erlauben es allen Benutzern ihre Bilder vom Computer hochzuladen,  aber die meisten bestimmen lieber selbst, welchen Mitgliedern  sie diese Rechte vergeben. Bitte überprüfen Sie vorher die Regeln, bevor Sie bei einen Administrator nach diesen genannten Rechten fragen.</p>';


$lang['faq_question'][4] = 'Wie kann man das Bild als Animation aufzeichnen lassen?';

$lang['faq_answer'][4] = '<p>Einige Zeichenandwendungen erlauben es, Aktionen, die während des Zeichnens ausgeführt werden, aufzunehmen. Das ermöglicht anderen Mitgliedern zu zeigen, wie Sie selbst zeichnen. Um die Funktion zu aktivieren klicken Sie auf &bdquo;Zeichnen&ldquo; (in der Menüleiste) und haken Sie anschließend das Kästchen an, welches die Animation aktiviert (&bdquo;Animationen/Ebenen&ldquo;). Beachten Sie, dass nicht alle Anwendungen diese Funktion unterstützen, benötigen aber trotzdem eine Aktivierung der Funktion, um mehrere Ebenen zu speichern.</p>

<p>Animationen zeigen nur Ihre Aktionen während des Zeichnens. Sie können keine Cartoons oder Videos erstellen und die Animationen auch nicht bearbeiten. Wenn Sie in einer Anwendung auf &bdquo;Undo&ldquo; klicken, löscht das Ihren letzten Arbeitsschritt und wird auch nicht in der Animation angezeigt.</p>';


$lang['faq_question'][5] = 'Das Programm lässt sich nicht starten bzw. kann man nichts sehen.';

$lang['faq_answer'][5] = '<p>Die meisten der Anwendungen basieren auf Java-Anwendungen. Falls Ihr Browser angibt, dass das benötigte Plugin fehlt, müssen Sie es zunächst von <a href="http://www.java.com">www.Java.com</a> herunterladen und installieren. Java ist eine Software-Plattform, welche von Sun Microsystems erstellt wurde und nun von Oracle vertrieben wird.</p>

<p>Viele PCs haben Java bereits vorinstalliert. Java auf dem Macintosh wird von Apple angeboten und ist dafür bekannt, dass es Probleme mit den Zeichenanwendungen hat.</p>';


$lang['faq_question'][6] = 'Bilder lassen sich nicht überarbeiten (Re-Touch) &mdash; die Fläche bleibt leer!';

$lang['faq_answer'][6] = '<p>Ältere Versionen des Java haben Probleme Bilder zu importieren. Gehen Sie also sicher, dass Sie die neueste Java-Version besitzen. Es kann außerdem sein, dass Sie zunächst die Funktion &bdquo;Animationen/Ebenen&ldquo; aktivieren müssen, um Bilder überarbeiten zu können. Sollten Sie ein umfangreiches Bild mit langer Animation weiterzeichnen wollen, so kann es sein, dass Sie eventuell bis zu einer Minute warten müssen, bis das Bild erscheint, da die Anwendung im Hintergrund die Animation laden muss.</p>';


$lang['faq_question'][7] = 'Was kann man tun, wenn man das Passwort vergessen hat?';

$lang['faq_answer'][7] = '<p>Sie können sich <a href="lostpass.php">HIER</a> ein neues Passwort zusenden lassen.</p>

<p>Falls Sie Ihr Passwort nicht wiederherstellen können, kontaktieren Sie den Besitzer des Boardes. Dieser kann Ihnen ein neues Passwort einstellen. (Es wird empfohlen, anschließend das Passwort erneut zu ändern, damit auch wirklich nur SIE das Passwort kennen.)</p>';


$lang['faq_question'][8] = 'Man kann sich nicht ausloggen!';

$lang['faq_answer'][8] = '<p>Das ist vermutlich ein Problem des Browsers. Versuchen Sie Ihre Cookies (die des Browsers) zu löschen. Ein Cookie beinhaltet Informationen, welche den Server wissen lassen, wer Sie sind und speichert somit unter Anderem, dass Sie sich eingeloggt haben. Viele Browser haben die Möglichkeit Cookies zu löschen unter &bdquo;Einstellungen -> Optionen&ldquo;.</p>';


$lang['faq_question'][9] = 'Das Bild wurde nicht veröffentlicht; man konnte kein Kommentar verfassen; das Bild ging verloren.';

$lang['faq_answer'][9] = '<p>Ihr Bild ist möglicherweise noch nicht verloren. Wurde das Bild erfolgreich hochgeladen, aber Sie konnten kein Kommentar verfassen, gehen Sie zu &bdquo;Administration -> Bilder wiederherstellen&ldquo;, um es wiederherzustellen. Ihre Informationen wurden gespeichert, inklusive der Zeit die Sie für das Bild benötigt haben. Falls Sie dabei Probleme haben, kann auch ein Administrator das Bild für Sie wiederherstellen, oder einen Screenshot(Bildschirmfoto) hochladen, wenn Sie einen via &bdquo;Print Scrn&ldquo; (bzw. Druck-Taste) erstellt haben.</p>';


$lang['faq_question'][10] = 'Warum werden einige E-Mail-Adressen nicht angezeigt?';

$lang['faq_answer'][10] = '<p>Um E-Mail-Adressen vor Spam zu bewahren, müssen Sie eingeloggt sein. So werden dann die E-Mail-Adressen der Benutzer im Profil und in der Mitgliederliste angezeigt.</p>
<p>Sie werden die E-Mail-Adressen der Admins immer sehen können, allerdings wurden die &bdquo;@&ldquo;-Symbole ausgetauscht, um Spam zu vermeiden.</p>';


$lang['faq_question'][11] = 'Was ist mit dem Chat/ der Mailbox passiert?';

$lang['faq_answer'][11] = '<p>Wacintaki erlaubt Administratoren den Chat und die Mailbox zu deaktivieren. Das Chat-System benötigt eine Menge Traffic (Bandbreite) und einige Server können das möglicherweise nicht unterstützen. Im Falle einer deaktivierten Mailbox haben Sie die Möglichkeit E-Mails direkt an den entsprechenden Benutzer zu schicken, in dem Sie die E-Mail-Adresse aus seinem Profil oder der Mitgliederliste entnehmen. Beachten Sie, dass Sie eingeloggt sein müssen, um die E-Mail-Adressen anderer Benutzer sehen zu können.</p>';


$lang['faq_question'][12] = 'Wie kann man jemanden eine Nachricht via Mailbox schicken?';

$lang['faq_answer'][12] = '<p>Klicken Sie auf einen Benutzernamen, um sein Profil aufzurufen und klicken Sie dort auf &bdquo;Nachricht senden&ldquo;, am Anfang der Seite. Info: Sie müssen eingeloggt sein, um Nachrichten zu senden.</p>';


$lang['faq_question'][13] = 'Was bedeutet &bdquo;Automatische Benutzerlöschung&ldquo;?';

$lang['faq_answer'][13] = '<p>Wenn diese auf einen bestimmten Zeitraum gesetzt wurde, bedeutet es, dass alle Benutzer die sich nicht in den angegebenen Zeitraum eingeloggt haben, gelöscht werden. Um das zu verhindern, fragen Sie einen Administrator Ihnen Immunitätsrechte zu geben oder loggen Sie sich regelmäßig auf dem Board ein.</p>';


$lang['faq_question'][14] = 'Welche Bilder werden als gesperrte (Bilder für Erwachsene/18+) Bilder angesehen?';

$lang['faq_answer'][14] = '<p>Da das Zeichnen von Oekakis ein internationaler Zeitvertreib ist, variiert die Definition von 18+ Bildern (Alter kann in anderen Ländern variieren). Gewöhnlich bezeichnen diese Bilder jegliche Materie reiferer Natur, die nur für Personen gedacht sind, die wenigstens 18 Jahre alt sind. Dieses Material beinhaltet aggressive Gewalt, Nacktheit, Szenen sexueller Art oder explizite Abbildung des menschlichen Körpers. Einige Oekaki Plattformen gestatten keinen 18+ Inhalt auf ihrer Seite. Lesen Sie hierzu die Regeln, um zu sehen, ob zusätzliche oder veränderte Bedingungen vom Administrator eingestellt worden.</p>

<p>Wurde ein Oekaki Board als Board für Erwachsene eingestuft, so müssen Sie während der Registration eine Altersangabe tätigen. Sollten keine Bilder, die als &bdquo;gesperrte Bilder&ldquo; eingestuft wurden, für Sie angezeigt werden, so müssen Sie über &bdquo;Profil bearbeiten&ldquo; die Option &bdquo;Bilder für Erwachsene/gesperrte Bilder betrachten&ldquo; aktivieren.</p>';


$lang['faq_question'][15] = 'Warum werden einige Bilder als Thumbnails dargestellt und andere wiederum nicht?';

$lang['faq_answer'][15] = '<p>Bilder werden verkleinert und werden unter bestimmten Bedingungen zu Thumbnails, z.B. der Datengröße des Bildes und welchen Thumbnail-Modus der Administrator eingestellt hat. Sie können Einstellungen unter <a href="editprofile.php">&bdquo;Profil bearbeiten&ldquo;</a>vornehmen, wenn der Administrator erlaubt, eigene Ansichten einzustellen.</p>';

// -----

//Who {1?is the owner:are the owners} of this oekaki?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionA'] = 'Wer {1?ist der Besitzer:sind die Besitzer} dieses Oekakis?';

//Who {1?is the administrator:are the administrators}?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionB'] = 'Wer {1?ist Administrator:sind die Administratoren}?';

//Who {1?is the moderator:are the moderators}?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionC'] = 'Wer {1?ist Moderator:sind die Moderatoren}?';

/* End FAQ */



$lang['word_new'] = "Neu";

$lang['word_unread'] = "Ungelesen";

$lang['word_read'] = "Gelesen";

$lang['word_replied'] = "Geantwortet";

$lang['register_sub8'] = "Nachdem Sie sich registriert haben, kontrollieren Sie Ihr E-Mail-Postfach nach einer Aktivierungs-Mail.";

//Upload
$lang['word_upload'] = "Upload";

//Upload Picture
$lang['upload_title'] = "Bilder hochladen";

//File to upload
$lang['upload_file'] = "Datei zum Hochladen";

//ShiPainter
$lang['word_shipainter'] = "ShiPainter";

//ShiPainter Pro
$lang['word_shipainterpro'] = "ShiPainter Pro";

//Edit Banner
$lang['header_ebanner'] = "Banner bearbeiten";

//Reset All Templates
$lang['install_resettemplate'] = "Alle Templates zurücksetzen";

//If yes, all members will have their template reset to the default
$lang['install_resettemplatesub'] = "Wurde JA gewählt, werden alle Templates der Benutzer auf das Standard-Template zurückgesetzt";

//N/A
$lang['word_na'] = "Keine Antwort";

//You do not have draw access. Ask an administrator on details for recieving access.
$lang['draw_noaccess'] = "Sie besitzen keine Zeichenrechte. Fragen Sie einen Administrator nach Informationen, wie Sie welche bekommen können.";

//Upload Access
$lang['type_uaccess'] = 'Uploadrechte';

//Print &ldquo;Uploaded by&rdquo;
$lang['admin_uploaded_by'] = '&bdquo;Hochgeladen von&ldquo; anzeigen?';

//Gives the user access to the picture upload feature.
$lang['type_guaccess'] = 'Ermöglicht dem Benutzer Bilder hochladen zu dürfen.';

//Delete database
$lang['delete_dbase'] = "Datenbank löschen";

//Database Uninstall
$lang['uninstall_prompt'] = "Datenbank deinstallieren";

//Are you sure you want to remove the database?  This will remove information for the board
$lang['sure_remove_dbase'] = "Sind Sie sicher, dass Sie die Datenbank löschen wollen? Das wird alle Informationen für das folgende Board löschen";

//Images, templates, and all other files in the OP directory must be deleted manually.
$lang['all_delete'] = "Bilder, Templates und alle anderen Dateien im OP-Verzeichnis müssen manuell gelöscht werden.";

//If you have only one board, you may delete both databases below.
$lang['delete_oneboard'] = "Wenn Sie nur ein Board besitzen, brauchen Sie nur folgende beide Datenbanken zu löschen.";

//If you are sharing a database with more than one board, be sure to delete <em>only</em> the database for posts and comments.  If you delete the database for member profiles, all your boards will cease to function!
$lang['sharing_dbase'] = "Wenn Sie die Datenbank mit mehr als einem Board teilen, achten Sie darauf, dass Sie <em>nur</em> die Datenbank für Einträge und Kommentare löschen. Falls Sie die Datenbank für Mitglieder-Profile löschen, werden alle Boards nicht mehr funktionieren!";

//Each board must be removed with its respective installer.
$lang['remove_board'] = "Jedes Board muss mit seinem eigenen Installer gelöscht werden.";

//Delete posts and comments.
$lang['delepostcomm'] = "Einträge und Kommentare löschen.";

//Delete member profiles, chat, and mailboxes.
$lang['delememp'] = "Lösche Mitglieder-Profile, Chats und Mailboxen.";

//Uninstall error
$lang['uninserror'] = "Fehler bei der Deinstallation";

//Valid database and config files were not found.  The board must be properly installed before any database entries can be removed.  If problems persist, let your sysadmin delete the databases by name.
$lang['uninsmsg'] = "Gültige Datenbank- und Konfigurationsdateien wurden nicht gefunden. Das Board muss richtig installiert werden, bevor jegliche Datenbankeinträge gelöscht werden können. Wenn Probleme bestehen, lassen Sie Ihren System-Admin die Datenbank manuell löschen.";

//Uninstall Status
$lang['unistatus'] = "Status der Deinstallation";

//NOTE:  No databases changed
$lang['notedbchange'] = "ANMERKUNG: Datenbank wurde nicht geändert";

//Return to the installer
$lang['returninst'] = "Kehren Sie zum Installer zurück";

//Wacintaki Installation
$lang['wacinstall'] = "Wacintaki Installation";

//Installation Progress
$lang['instalprog'] = "Installationsfortschritt";

//ERROR:  Your database settings are invalid.
$lang['err_dbs'] = "FEHLER: Ihre Datenbankeinstellungen sind fehlerhaft.";

//NOTE:  Database password is blank (not an error).
$lang['note_pwd'] = "ANMERKUNG: Datenbankpasswort ist leer (kein Fehler).";

//ERROR:  The administrator login name is missing.
$lang['err_adminname'] = "FEHLER:  Der Login-Name des Administrators fehlt.";

//ERROR:  The administrator password is missing.
$lang['err_adminpwd'] = "FEHLER:  Das Passwort des Administrators fehlt.";

//ERROR:  The administrator passwords do not match.
$lang['err_admpwsmtch'] = "FEHLER:  Die Passwörter des Administrators stimmen nicht überein.";

//Could not connect to the MySQL database.
$lang['err_mysqlconnect'] = "Konnte nicht auf die MySQL Datenbank zugreifen.";

//Wrote database config file...
$lang['msg_dbsefile'] = "Schrieb Datenbank-Konfigurationsdatei...";

//ERROR:  Could not open database config file for writing.  Check your server permissions
$lang['err_permis'] = "FEHLER:  Konnte die Datenbank-Konfigurationsdatei zum Schreiben nicht öffnen. Überprüfen Sie die Server-Zugriffsrechte";

//Wrote config file...
$lang['wrconfig'] = "Schrieb Konfigurationsdatei...";

//ERROR:  Could not open config file for writing.  Check your server permissions.
$lang['err_wrconfig'] = "FEHLER:  Konnte die Konfigurationsdatei zum Schreiben nicht öffnen. Überprüfen Sie die Server-Zugriffsrechte";

//ERROR:  Could not create folder
$lang['err_cfolder'] = "FEHLER:  Konnte kein Verzeichnis erstellen";

//ERROR:  Folder &ldquo;{1}&rdquo; is locked.  You may have to create this folder manually.
$lang['err_folder'] = "FEHLER: Verzeichnis &bdquo;{1}&ldquo; ist gesperrt.  Sie müssen das Verzeichnis manuell erstellen.";

//One or more base files could not be created.  Try again or manually create the listed files with zero length.
$lang['err_fcreate'] = "Eine oder mehr Basis-Dateien konnten nicht erstellt werden. Versuchen Sie es erneut oder erstellen Sie die Dateien manuell (mit den 0 Größen).";

//'Wrote base &ldquo;resource&rdquo; folder files...'
$lang['write_basefile'] = "Erstellte die Basis &bdquo;resource&ldquo;-Verzeichnis-Dateien...";

//Starting to set up database...
$lang['startsetdb'] = "Beginnt Erstellung der Datenbank...";

//Finished setting up database...
$lang['finishsetdb'] = "Beendet Erstellung der Datenbank...";

//If you did not receive any errors, the databases have been installed.
$lang['noanyerrors'] = "Sollten Sie keine Fehlermeldungen erhalten haben, wurden die Datenbanken installiert.";

//If you are installing another board and your primary board is functioning properly, ignore any database errors.
$lang['anotherboarderr'] = "Wenn Sie ein weiteres Board erstellen und Ihr Hauptboard funktioniert richtig, ignorieren Sie die Datenbankfehler.";

//Click the button below to finalize the installation.  This will clean up the installer files and prevent security problems.  You will have to copy <em>install.php</em> into the Wacintaki folder if you need to uninstall the database.  All other maintenance can be done with the control panel.
$lang['clickbuttonfinal'] = "Klicken Sie auf den Button unterhalb, um die Installation abzuschließen. Dieser Vorgang wird die Installationsdateien säubern und vor Sicherheitsproblemen schützen. Sie müssen die <em>install.php</em> in das Hauptverzeichnis des Wacintaki kopieren, wenn Sie die Datenbanken deinstallieren wollen. Die restliche Wartung kann im Control Panel durchgeführt werden.";

//Secure installer and go to the BBS
$lang['secinst'] = "Installer absichern und zum BBS gehen";

//Installation Error
$lang['err_install'] = "Installationsfehler";

//&ldquo;templates&rdquo; and &ldquo;resource&rdquo; folders are not writable!  Be sure to CHMOD these folders to their correct permissions before running the installer.
$lang['err_temp_resource'] = "&bdquo;templates&ldquo;- und &bdquo;resource&ldquo;-Verzeichnisse sind nicht schreibbar! Überprüfen Sie, ob Sie korrekte CHMOD-Einstellungen für diese Verzeichnisse vergeben haben, bevor Sie den Installer starten.";

//Wacintaki Installation
$lang['wac_inst'] = "Wacintaki Installation";

//Installation Notes
$lang['inst_note'] = "Installationsnotizen";

//One MySQL database is required to install Wacintaki.  If you do not know how to access your MySQL account, e-mail your sysadmin, or log into your control panel and look for a database tool such as phpMyAdmin.  On most servers, &ldquo;localhost&rdquo; will work for the hostname, though web hosts with a dedicated MySQL server may require something such as &ldquo;mysql.server.com&rdquo;.  Be aware that some database tools, such as CPanel or phpMyAdmin, may automatically add a prefix to your database name or username, so if you create a database called &ldquo;oekaki&rdquo;, the result may end up being &ldquo;accountname_oekaki&rdquo;.  The database table prefixes (default &ldquo;op_&rdquo;) are only significant if you wish to install more than one oekaki.  Consult the manual for more information on installing multiple oekakis with one database.
$lang['mysqldb_wact'] = "Eine MySQL-Datenbank wird benötigt, um Wacintaki zu installieren. Wenn Sie nicht wissen, wie Sie Ihren MySQL-Account erreichen können, kontaktieren Sie Ihren System-Administrator oder loggen sich in Ihr Control Panel ein und suchen Sie nach einen Datenbank-Tool (z.B. phpMyAdmin). Auf den meisten Servern wird &bdquo;localhost&ldquo; als Hostname verwendet, obwohl es auch unter Umständen bei WebHostern mit zugehörigem MySQL-Servern sein kann, dass diese etwas wie &bdquo;mysql.server.com&ldquo; besitzen. Denken Sie daran, dass einige Datenbank-Tools (wie CPanel oder phpMyAdmin) automatisch Präfixe an den Datenbanknamen oder -Benutzernamen hängen können. Sollten Sie also in diesem Fall eine Datenbank mit dem Namen &bdquo;Oekaki&ldquo; erstellen, kann es sein, dass das Ergebnis am Ende &bdquo;accountname_oekaki&ldquo; lautet. Die Datenbank-Tabellen-Vorsilben (standardmäßig &bdquo;op_&ldquo;) sind lediglich bedeutend, wenn Sie mehr als ein Oekaki installieren wollen. Benutzen Sie das Handbuch, um weitere Informationen bezüglich der Installation mehrerer Oekaki Board mit nur einer Datenbank zu erhalten.";

//Database Table Prefix
$lang['dbtablepref'] = "Datenbank-Tabellen-Vorsilbe";

//If installing mutiple boards on one database, each board must have its own, unique table prefix.
$lang['multiboardpref'] = "Wenn mehrere Boards auf einer Datenbank installiert werden, muss jedes Board seine eigene, individuelle Tabellen-Vorsilbe besitzen.";

//Member Table Prefix
$lang['memberpref'] = "Mitglieder-Tabellen-Vorsilbe";

//If installing multiple boards on one database, and you want all members to access each board without seperate registrations, make sure each board shares the same table prefix.  To force sperate registrations for each board, make this prefix unique for each installation.
$lang['instalmulti'] = "Wenn mehrere Board auf einer Datenbank installiert werden und Sie möchten die Mitglieder auf allen Boards teilen (ohne separate Registrationen), dann müssen diese Boards alle die selben Tabellen-Vorsilben besitzen. Für eigenständige Boards (jedes Board hat eine eigene Registration) achten Sie darauf, dass jedes Board eine andere Vorsilbe besitzt.";

//<a href="{1}">Click here to uninstall an existing database.</a>  Confirmation will be requested.
$lang['uninstexist'] = '<a href="{1}">Klicken Sie hier, um eine existierende Datenbank zu deinstallieren.</a> Bestätigung wird angefordert.';

//This is a guess.  Make sure it is correct, or registration will not work correctly.
$lang['guessregis'] = "Dies ist eine Schätzung. Achten Sie darauf, dass sie korrekt ist, ansonsten wird die Registration nicht richtig funktionieren.";

//Picture Name Prefix
$lang['picpref'] = "Vorsilbe für Bildernamen";

//This prefix will appear on every picture and animation saved by the BBS.  Example: &ldquo;OP_50.png&rdquo;
$lang['picprefexp'] = "Diese Vorsilbe erscheint bei jedem Bild und bei jeder Animation, welche vom BBS gespeichert werden. Beispiel:&bdquo;OP_50.png&ldquo;";

//Allow Public Pictures
$lang['allowppicture'] = "Öffentliche Bilder erlauben";

//Public pictures may be retouched by any member with draw permissions. No passwords are used, and retouched images are submitted as new posts. <strong>NOTE</strong>: May result in floods without strict rules and administration.
$lang['ppmsgrtouch'] = "Öffentliche Bilder können von jedem Benutzer mit Zeichenrechten weitergemalt werden. Es wird kein Passwort benutzt und die weitergemalten Bilder werden als neue gespeichert. <strong>ANMERKUNG</strong>: Kann in Spam ausarten ohne konkrete Regeln und Leitung.";

//Allow Safety Saves
$lang['allowsafesave'] = "Erlaubt Zwischenspeicherungen/Safety saves";

//Safety saves do not show up on the board while they are in progress.  Only one safety save is allowed per member, and they are automatically deleted after a certain number of days
$lang['safesaveexp'] = "Zwischenspeicherungen (oder auch Safety saves) werden auf dem Board nicht angezeigt, solange an ihnen gearbeitet wird. Nur ein Zwischenspeicher ist pro Benutzer empfohlen und wird automatisch nach einen bestimmten Zeitraum gelöscht, wenn dieser nicht fertiggestellt wird.";

//Safety Save Storage
$lang['savestorage'] = "Zeitspanne für Zwischenspeicherungen/Safety saves";

//Number of days safety saves are stored before they are removed.  Default is 30.
$lang['safetydays'] = "Anzahl an Tagen an denen ein Safety save unangetastet im Speicher verweilen kann, ohne dabei gelöscht zu werden. Der Standardwert liegt bei 30 Tagen.";

//Auto Immunity for Artists
$lang['autoimune'] = "Auto-Immunität für Zeichner";

//If yes, people who draw pictures will automatically receive the immunity flag from auto user delete.
$lang['autoimune_exp'] = "Wurde JA gewählt, so werden Benutzer die zeichnen automatisch Immunitätsrechte erhalten und sind somit vor der automatischen Löschung geschützt.";

//Show Rules Before Registration
$lang['showrulereg'] = "Regeln vor der Registration anzeigen";

//If yes, people will be shown the rules before they can submit a new registration.  Use &ldquo;Edit Rules&rdquo; in the admin menu to set rules.
$lang['showruleregexp'] = "Wurde JA gewählt, so werden den registrierenden Benutzer zunächst die Regeln angezeigt, bevor diese sich registrieren können. Benutzen Sie &bdquo;Regeln bearbeiten&ldquo; im Admin-Menü, um Regeln festzulegen.";

//Require Art Submission
$lang['requireartsub'] = "Einreichen von Arbeiten/Werken verlangen";

//If yes, new users are instructed to provide a link to a piece of art for the administrator to view.
$lang['requireartsubyes'] = "Wurde JA gewählt, werden die neuen Benutzer gebeten, einen Link mit bisheren Arbeiten/Bildern für den Administrator einzutragen.";

//If no, new users are told the URL field is optional.
$lang['requireartsubno'] = "Bei NEIN, ist dieses Feld für den Benutzer optional.";

//No (forced)
$lang['forceactivate'] = "Nein (Erzwingen)";

//If yes, approval by the administrators is required to register.
$lang['activateyes'] = "Wurde JA gewählt, muss eine Bestätigung durch den Administrator erfolgen.";

//If no, users will recieve an activation code in their e-mail.
$lang['activeno'] = "Bei NEIN, erhalten Benutzer einen Aktivierungscode via E-Mail.";

//Use &ldquo;forced&rdquo; ONLY if your server cannot send e-mails, and you want automatic approval.
$lang['activateforced'] = "Benutzen Sie &bdquo;Erzwingen&ldquo; NUR, wenn Ihr Server keine E-Mails schicken kann und Sie somit eine automatische Bestätigung geben möchten.";

//Default Permissions for Approved Registrations
$lang['defaultpermis'] = "Vorgegebene Rechte für bestätigte Registrationen";

//Members may bump own pictures on retouch?
$lang['bumpretouch'] = "Dürfen Mitglieder ihr eigenes Bild bumpen?";

//Author Name
$lang['authorname'] = "Autorenname";

//Name of the BBS owner.  This is displayed in the copyright and page metadata.
$lang['bbsowner'] = "Name des BBS-Besitzers. Diese Information wird im Copyright und den Seiten-Metadaten vermerkt.";

//Adult rated BBS
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbs'] = "Als {1}+/Board für Erwachsene eingestuftes BBS";

//Select Yes to declare your BBS for adults only.  Users are required to state their age to register.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsdesc'] = "Wählen Sie Ja, um das BBS für {1}+ zu kennzeichnen. Benutzer müssen ihr Alter angeben, um sich zu registrieren.";

//NOTE:  Does <strong>not</strong> make every picture adult by default.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsnote'] = "ANMERKUNG: Markiert Bilder standardmäßig <strong>nicht</strong> als {1}+.";

//Allow guests access to pr0n
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpron'] = "Gästen erlauben auf Pr0n zuzugreifen";

//If yes, adult images are blocked and may be viewed by clicking the pr0n placeholder.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronyes'] = "Wurde JA gewählt, werden {1}+-Bilder blockiert und können betrachtet werden, wenn man auf den Platzhalter klickt.";

//If no, the link is disabled and all access is blocked.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronno'] = "Bei NEIN, wird der Link deaktiviert und jeglicher Zugriff wird blockiert.";

//Number of Pics on Index
$lang['maxpiconind'] = "Anzahl der Bilder pro Seite";

//Avatars
$lang['word_avatars'] = "Avatare";

//Enable Avatars
$lang['enableavata'] = "Avatare aktivieren";

//Allow Avatars On Comments
$lang['allowavatar'] = "Erlaubt das Anzeigen von Avataren in Kommentaren";

//Avatar Storage
$lang['AvatarStore'] = "Avatar-Verzeichnis";

//Change <strong>only</strong> if installing multiple boards.  Read the manual.
$lang['changemulti'] = "Änderung <strong>nur</strong> bei Installation mehrerer Boards.  Lesen Sie dazu die Anleitung.";

//Basically, use one folder for all boards.  Example:  use &ldquo;avatars&rdquo; for board 1, &ldquo;../board1/avatars&rdquo; for board 2, etc.
$lang['changemultidesc'] = "Standardmäßig gilt, das gleiche Verzeichnis für alle Boards. Beispiel: Benutzen Sie &bdquo;avatars&ldquo; für Board 1, &bdquo;../board1/avatars&ldquo; für Board 2, etc.";

//Maximum Avatar Size
$lang['maxavatar'] = "Maximale Avatargröße";

//Default is 50 &times; 50 pixels.  Larger than 60 &times; 60 not recommended
$lang['maxavatardesc'] = "Standard ist 50 &times; 50 Pixel.  Größer als 60 &times; 60 wird nicht empfohlen";

//Default Canvas Size
$lang['cavasize'] = "Voreingestellte Größe der Zeichenfläche";

//The default canvas size.  Typical value is 300 &times; 300 pixels
$lang['defcanvasize'] = "Die voreingestellte Größe der Zeichenfläche. Typisch ist hierfür 300 &times; 300 Pixel";

//Minimum Canvas Size
$lang['mincanvasize'] = "Mindestgröße der Zeichenfläche";

//The minimum canvas size.  Recommended minimum is 50 &times; 50 pixels
$lang['mincanvasizedesc'] = "Die kleinste Größe der Zeichenfläche.  Empfohlener Mindestwert liegt bei 50 &times; 50 Pixel";

//Maximum Canvas Size
$lang['maxcanvasize'] = "Maximalgröße der Zeichenfläche";

//Maximum canvas.  Recommended maximum is 500 &times; 500 pixels
$lang['maxcanvasizedesc'] = "Die größtmögliche Grenze für die Zeichenfläche.  Empfohlenes Maximum liegt bei 500 &times; 500 Pixel";

//Be aware that a small increase in dimentions results in a large increase in surface area, and thus filesize and bandwidth.  1000 &times; 1000 uses <strong>four times</strong> as much bandwidth as 500 &times; 500
$lang['maxcanvasizedesc2'] = "Bedenken Sie, dass eine kleine Änderung im Größenmaß eine große Änderung im Flächenmaß zur Folge hat und dadurch mehr Datenspeicher und Bandbreite benötigt wird. 1000 &times; 1000 benötigt <strong>viermal</strong> so viel Bandbreite wie 500 &times; 500";

//Number of pictures to display per page of the BBS
$lang['maxpicinddesc'] = "Anzahl an Bildern die pro Seite im BBS angezeigt werden sollen";

//Number of Entries on Menus
$lang['menuentries'] = "Anzahl an Einträgen in Menüs";

//Number of entries (such as user names) to display per page of the menus and admin controls
$lang['menuentriesdesc'] = "Anzahl an Einträgen (wie Benutzernamen) die pro Seite von Menüs und bei der Admin-Bedienung angezeigt werden";

//Use Smilies in Comments?
$lang['usesmilies'] = "Smileys in Kommentaren benutzen?";

//Smilies are configurable by editing the &ldquo;hacks.php&rdquo; file
$lang['usesmiliedesc'] = "Smileys können in der &bdquo;hacks.php&ldquo;-Datei angepasst werden";

//Maximum Upload Filesize
$lang['maxfilesize'] = "Maximale Upload-Datengröße";

//The max filesize uploaded pictures can be, in bytes.  Default is 500,000 bytes or 500KB.
$lang['maxupfileexp'] = "Die maximale Datengröße, die hochgeladen werden kann.  Standardmäßig liegt sie bei 500.000 Bytes oder 500KB.";

//The maximum value allowed by most servers varies from 2 to 8MB.
$lang['maxupfileexp2'] = "Das Maximum liegt bei den meisten Servern bei 2 bis 8MB.";

//Canvas size preview
$lang['canvasprev'] = "Vorschau der Zeichenfläche";

//Image for canvas size preview on Draw screen.  Square picture recommended.
$lang['canvasprevexp'] = "Das Bild für die Vorschau der Zeichenfläche bei den Zeichenoptionen. Ein quadratisches Bild wird hierfür empfohlen.";

//Preview Title
$lang['pviewtitle'] = "Vorschautitel";

//Title of preview image (Text only &amp; do not use double quotes).
$lang['titleprevwi'] = "Titel des Vorschaubildes (nur Text &amp; keine doppelten Anführungszeichen benutzen).";

//&ldquo;Pr0n&rdquo; placeholder image
$lang['pron'] = "&bdquo;Pr0n&ldquo;-Platzhalter";

//Image for substitution of pr0n.  Square picture recommended.  Default &ldquo;pr0n.png&rdquo;
$lang['prondesc'] = "Das Bild als Platzhalter für gesperrte Bilder. Ein quadratisches Bild wird empfohlen. Standard: &bdquo;pr0n.png&ldquo;";

//Enable Chat
$lang['enablechat'] = "Chat aktivieren";

//Note:  chat uses a lot of bandwidth
$lang['chatnote'] = "Anmerkung:  Der Chat verbraucht viel Bandbreite";

//Your server does not have the graphics system &ldquo;GDlib&rdquo; installed, therefore you cannot enable thumbnail support.  However, you may still select a default thumbnail mode which will conserve screenspace by shrinking pictures.
$lang['err_nogdlib'] = "Auf Ihrem Server wurde nicht das Grafik-System &bdquo;GDlib&ldquo; installiert, aus diesem Grund kann keine Unterstützung für Thumbnails aktiviert werden. Sie können immer noch den standardmäßigen Thumbnail-Modus wählen, in dem die Bilder verkleinert werden.";

//Four thumbnail modes are available.  None, Layout, Scaled, and Uniformity.  If you're confused which mode to use, try Scaled first.
$lang['thumbmodes'] = "Vier Thumbnail-Modi sind verfügbar. Keine, Layout, Skaliert und Einheitlich. Wenn Sie nicht wissen, welchen Sie wählen sollen, versuchen Sie es zunächst mit Skaliert.";

//If you choose &ldquo;None&rdquo;, thumbnail support will always be off for all members unless you enable it later in the control panel.
$lang['thumbmodesexp2'] = "Wählen Sie &bdquo;Keine&ldquo;, ist die Unterstützung für die Thumbnails immer AUS (für alle Benutzer), außer es wird später im Kontrollzentrum geändert.";

//Default thumbnail mode
$lang['defthumbmode'] = "Voreingestellter Thumbnail-Modus";

//None
$lang['word_none'] = "Keine";

//Layout
$lang['word_layout'] = "Layout";

//Scaled (default)
$lang['word_defscale'] = "Skaliert (Standard)";

//Uniformity
$lang['word_uniformity'] = "Einheitlich";

//Tip:  Options are ordered in terms of bandwidth.  Uniformity uses the least bandwidth.  Scaled Layout is recommended.
$lang['optiontip'] = "Tipp:  Optionen sind nach Beanspruchung der Bandbreite angeordnet. Einheitlich benötigt am wenigsten. Skaliert wird empfohlen.";

//Force default thumbnails
$lang['forcedefthumb'] = "Erzwingen der voreingestellten Thumbnails";

//If yes, users may only use the default mode (recommended for servers with little bandwidth). If no, users may select any thumbnail mode they wish.
$lang['forcethumbdesc'] = "Wurde JA gewählt, so können Benutzer lediglich den voreingestellten Modus benutzen (Empfohlen für Server mit geringer Bandbreite). Bei NEIN können Benutzer selber festlegen, welchen Modus sie wählen möchten.";

//Small thumbnail size
$lang['smallthumb'] = "Kleine Thumbnailgröße";

//Size of small (uniformity) thumbnails in pixels.  Small thumbnails are generated often.  Default 120.
$lang['smallthumbdesc'] = "Größe der kleinen (Einheitlich) Thumbnails in Pixeln.  Kleine Thumbnails werden oft generiert. Standard ist 120";

//Large thumbnail size
$lang['largethumb'] = "Große Thumbnailgröße";

//Size of large (layout) thumbnails.  Large thumbnails are made only occasionally for layout or scaled thumbnail modes.  Default 250.
$lang['largethumbdesc'] = "Größe der großen (Layout) Thumbnails.  Große Thumbnails werden gelegentlich für den Layout oder Skalierten Modus generiert. Standard 250.";

//Filesize for large thumbnail generation
$lang['thumbnailfilesize'] = "Datengröße bei Generation von großen Thumbnails";

//If a picture's filesize is greater than this value, a large thumbnail will be generated in addition to the small one.  Default is 100,000 bytes.  If using uniformity mode only, set to zero to disable and save server space.
$lang['thumbsizedesc'] = "Sollte die Datengröße eines Bildes diesen Wert überschreiten, wird ein großes Thumbnail generiert, zusätzlich zu dem kleinen. Standard liegt bei 100.000 Bytes. Falls ausschließlich der Einheitlich-Modus genutzt wird, setzen Sie den Wert auf Null, um die Funktion zu deaktivieren und Speicherplatz zu sparen.";

//Your E-mail Address (leave blank to use registration e-mail)
$lang['emaildesc'] = "Ihre E-Mail-Adresse (lassen Sie das Feld offen, wenn diese mit der Registrations-E-Mail übereinstimmt)";

//Submit Information for Install
$lang['finalinstal'] = "Informationen für die Installation übertragen";

//---addusr

//You do not have the credentials to add users.
$lang['nocredeu'] = "Sie dürfen keine Benutzer hinzufügen.";

//Note to admins:  Automatic approval is enabled, so users are expected to enable their own accounts.  Contact the board owner if you have questions about approving or rejecting members manually.
$lang['admnote'] = "Anmerkung an die Administratoren:  Automatische Bestätigung ist aktiviert, Benutzer müssen so ihren eigenen Account freischalten. Kontaktieren Sie den Board-Besitzer falls Fragen bezüglich der manuellen Genehmigung und Ablehnung von Benutzern besteht.";

//INVALID
$lang['word_invalid'] = "UNGÜLTIG";

//--banlist

//You do not have the credentials to ban users.
$lang['credibandu'] = "Sie dürfen keine Benutzer bannen.";

//&ldquo;{1}&rdquo; is locked!  View &ldquo;Readme.txt&rdquo; for help.
// {1} = filename
$lang['fislockvred'] = "&bdquo;{1}&ldquo; ist gesperrt!  Lesen Sie die &bdquo;Readme.txt&ldquo;, um weitere Hilfe zu erhalten.";

//Submit Changes
$lang['submitchange'] = "Änderungen senden";

//You do not have access as a registered member to use the chat.
$lang['memaccesschat'] = "Sie haben als registrierter Benutzer keinen Zugriff auf den Chat.";

//The chat room has been disabled.
$lang['charommdisable'] = "Der Chatraum wurde deaktiviert.";

//Sorry, an IFrame capable browser is required to participate in the chat room.
$lang['iframechat'] = "Entschuldigung, Ihr Browser muss IFrames unterstützen, um am Chat teilzunehmen.";

//Invalid user name.
$lang['invuname'] = "Ungültiger Benutzername.";

//Invalid verification code.
$lang['invercode'] = "Ungültiger Aktivierungslink.";

//Safety Save
$lang['safetysave'] = "Safety Save";

//Return to the BBS
$lang['returnbbs'] = "Kehren Sie zum BBS zurück";

//Error looking for a recent picture.
$lang['err_lookrecpic'] = "Fehler beim Suchen des letzten Bildes.";

//NOTE:  Refresh may be required to see retouched image
$lang['refreshnote'] = "ANMERKUNG:  Sie müssen eventuell die Seite aktualisieren, bevor Sie die Fortschritte sehen können.";

//Picture properties
$lang['picprop'] = "Bildeigenschaften";

//No, post picture now
$lang['safesaveopt1'] = "Nein, Bild jetzt veröffentlichen";

//Yes, save for later
$lang['safesaveopt2'] = "Ja, für später sichern";

//Bump picture
$lang['bumppic'] = "Bump";

//You may bump your edited picture to the first page.
$lang['bumppicexp'] = "Sie dürfen das Bild bumpen, damit es wieder ganz oben steht.";

//Share picture
$lang['sharepic'] = "Bild teilen";

//Password protect
$lang['pwdprotect'] = "Passwortgesichert";

//Public (to all members)
$lang['picpublic'] = "Öffentlich (für alle Benutzer)";

//Submit
$lang['word_submit'] = "Abschicken";

//Thanks for logging in!
$lang['common_login'] = "Willkommen zurück!";

//You have sucessfully logged out.
$lang['common_logout'] = "Sie haben sich erfolgreich ausgeloggt.";

//Your login has been updated.
$lang['common_loginupd'] = "Ihr Login wurde aktualisiert.";

//An error occured.  Please try again.
$lang['common_error'] = "Ein Fehler trat auf. Bitte versuchen Sie es erneut.";

//&lt;&lt;PREV
$lang['page_prev'] = '&lt;&lt;ZURÜCK';

//NEXT&gt;&gt;
$lang['page_next'] = 'NÄCHSTE&gt;&gt;';

//&middot;
// bullet.  Separator between <<PREV|NEXT>> and page numbers
$lang['page_middot'] = '&middot;';

//&hellip;
// "...", or range of omitted numbers
$lang['page_ellipsis'] = '&hellip;';

//You do not have the credentials to access the control panel.
$lang['noaccesscp'] = "Sie dürfen nicht auf das Kontrollzentrum zugreifen.";

//Storage
$lang['word_storage'] = "Speicher";

//300 or more recommended.  If reduced, excess pictures are deleted immediately.  Check disk space usage on the <a href=\"testinfo.php\">diagnostics page</a>.
$lang['cpmsg1'] = "300 oder mehr wird empfohlen. Bei Reduktion werden alle darüberliegenden Bilder sofort gelöscht. Überprüfen Sie die Ausnutzung des Speicherplatzes auf der <a href=\"testinfo.php\">Diagnostikseite</a>.";

//Use &ldquo;avatars/&rdquo; for master board, &ldquo;../board1/avatars/&rdquo; for all other boards.
$lang['cpmsg2'] = "Benutzen Sie &bdquo;avatars/&ldquo; für das Hauptboard, &bdquo;../board1/avatars/&ldquo; für alle anderen Boards.";

//Image for canvas size preview on Draw screen.  Square picture recommended.  Default &ldquo;preview.png&rdquo;
$lang['cpmsg3'] = "Das Bild für die Vorschau der Zeichenfläche bei den Zeichenoptionsbildschirm. Ein quadratisches Bild wird empfohlen. Standard &bdquo;preview.png&ldquo;";

//Rebuild thumbnails
$lang['rebuthumb'] = "Thumbnails erneuern";

//Page one
$lang['pgone'] = "Seite 1";

//Archived pictures only
$lang['archipon'] = "Nur archivierte Bilder";

//All thumbnails (very slow!)
$lang['allthumb'] = "Alle Thumbnails (sehr langsam!)";

//If thumbnail settings are changed, these thumbnails will be rebuilt.
$lang['rebuthumbnote'] = "Werden Thumbnail-Einstellungen geändert, so werden diese Thumbnails erneuert.";

//You do not have the credentials to delete comments
$lang['errdelecomm'] = "Sie dürfen keine Kommentare löschen.";

//Send reason to mailbox
$lang['sreasonmail'] = "Begründung an die Mailbox schicken";

//You do not have the credentials to edit the rules.
$lang['erreditrul'] = "Sie dürfen keine Regeln bearbeiten.";

//Edit Rules
$lang['editrul'] = "Regeln bearbeiten";

//HTML and PHP are allowed.
$lang['htmlphpallow'] = "HTML und PHP sind erlaubt.";

//You do not have the credentials to delete pictures.
$lang['errdelpic'] = "Sie dürfen keine Bilder löschen.";

//You do not have the credentials to delete users.
$lang['errdelusr'] = "Sie dürfen keine Benutzer löschen.";

//Pictures folder is locked!  View Readme.txt for help.
$lang['picfolocked'] = "Das Bilderverzeichnis ist gesperrt! Lesen Sie die Readme.txt für weitere Hilfe.";

//Unfinished Pictures
$lang['icomppic'] = "Unfertige Bilder";

//Click here to recover pictures
$lang['clickrecoverpic'] = "Klicken Sie hier, um Bilder wiederherzustellen";

//Applet
$lang['word_applet'] = "Anwendung";

//, with palette
$lang['withpalet'] = ", mit Palette";

//Canvas
$lang['word_canvas'] = "Fläche";

//Min
$lang['word_min'] = "Min";

//Max
$lang['word_max'] = "Max";

//NOTE:  You must check &ldquo;animation&rdquo; to save your layers.
$lang['note_layers'] = "ANMERKUNG: Sie müssen &bdquo;Animation&ldquo; anhaken, damit Ihre Ebenen gespeichert werden.";

//Avatars are disabled on this board.
$lang['avatardisable'] = "Avatare wurden auf diesem Board deaktiviert.";

//You must login to access this feature.
$lang['loginerr'] = "Sie müssen eingeloggt sein, um diese Funktion nutzen zu können.";

//File did not upload properly.  Try again.
$lang['err_fileupl'] = "Die Datei wurde nicht ordnungsgemäß hochgeladen. Bitte versuchen Sie es erneut.";

//Picture is an unsupported filetype.
$lang['unsuppic'] = "Der Dateityp des Bildes wird nicht unterstützt.";

//Filesize is too large.  Max size is {1} bytes.
$lang['filetoolar'] = "Die Dateigröße ist zu groß. Die maximale Größe liegt bei {1} Bytes.";

//Image size cannot be read.  File may be corrupt.
$lang['err_imagesize'] = "Die Bildgröße kann nicht gelesen werden.  Datei ist vermutlich beschädigt.";

//Avatar upload
$lang['avatarupl'] = "Avatar hochladen";

//Avatar updated!
$lang['avatarupdate'] = "Avatar wurde geändert!";

//Your avatar may be a PNG, JPEG, or GIF.
$lang['avatarform'] = "Ihr Avatar kann im PNG-, JPEG,- oder GIF-Format sein.";

//Avatars will only show on picture posts (not comments).
$lang['avatarshpi'] = "Avatare werden nur bei Bildern angezeigt (nicht bei Kommentaren).";

//Change Avatar
$lang['chgavatar'] = "Avatar ändern";

//Delete avatar
$lang['delavatar'] = "Avatar löschen";

//Missing comment number.
$lang['err_comment'] = "Fehlende Kommentarnummer.";

//You cannot edit a comment that does not belong to you.
$lang['err_ecomment'] = "Sie dürfen keine Kommentare anderer Benutzer bearbeiten.";

//You do not have the credentials to edit news.
$lang['err_editnew'] = "Sie dürfen keine News bearbeiten.";

//The banner is optional and displays at the very top of the webpage.
$lang['bannermsg'] = "Der Banner ist optional und wird am Anfang der Webseite angezeigt.";

//The notice is optional and displays just above the page numbers on <em>every</em> page.
$lang['noticemsg'] = "Die Ankündigung ist optional und wird direkt über den Seitenzahlen auf <em>jeder</em> Seite angezeigt.";

//Erase
$lang['word_erase'] = "Löschen";

//Centered Box
$lang['centrebox'] = "Zentrierter Kasten";

//Scroll Box
$lang['scrollbox'] = "Scrollbarer Kasten";

//Quick Draw
$lang['quickdraw'] = "Schnelles Zeichnen";

//You cannot edit a picture that does not belong to you.
$lang['err_editpic'] = "Sie dürfen keine Bilder anderer Benutzer bearbeiten.";

//Type &ldquo;public&rdquo; to share with everyone
$lang['editpicmsg'] = "Tippen Sie &bdquo;public&ldquo; ein, um das Bild mit anderen Benutzern zu teilen.";

//You cannot use the profile editor.
$lang['err_edprof'] = "Sie können den Profil-Editor nicht benutzen.";

//Real Name (Optional)
$lang['realnameopt'] = "Realer Name (Optional)";

//This is not your username.  This is your real name and will only show up in your profile.
$lang['realname'] = "Das ist nicht Ihr Benutzername.  Das ist Ihr wirklicher Name, welcher nur im Profil angezeigt wird.";

//Birthday
$lang['word_birthday'] = "Geburtstag";

//M
// Month
$lang['abbr_month'] = "M";

//D
// Day
$lang['abbr_day'] = "T";

//Y
// Year
$lang['abbr_year'] = "J";

//January
$lang['month_jan'] = "Januar";

//February
$lang['month_feb'] = "Februar";

//March
$lang['month_mar'] = "März";

//April
$lang['month_apr'] = "April";

//May
$lang['month_may'] = "Mai";

//June
$lang['month_jun'] = "Juni";

//July
$lang['month_jul'] = "Juli";

//August
$lang['month_aug'] = "August";

//September
$lang['month_sep'] = "September";

//October
$lang['month_oct'] = "Oktober";

//November
$lang['month_nov'] = "November";

//December
$lang['month_dec'] = "Dezember";

//Year is required for birthday to be saved.  Day and month are optional.
$lang['bdaysavmg'] = "Das Jahr wird benötigt, um Ihr Geburtsjahr zu speichern. Tag und Monat sind dabei optional.";

//Website
$lang['word_website'] = "Website";

//Website title
$lang['websitetitle'] = "Titel der Website";

//You can also type a message here and leave the URL blank
$lang['editprofmsg2'] = "Sie können eine Nachricht hinterlassen oder das Feld leer lassen.";

//Avatar
$lang['word_avatar'] = "Avatar";

//Current Avatar
$lang['curavatar'] = "Aktuelles Avatar";

//Online Presence
$lang['onlineprese'] = "Weitere Identitäten";

//(Automatic)
// context = Used as label in drop-down menu
$lang['picview_automatic'] = "(Automatisch)";

//Automatic is the default format and will layout comments to wrap around the picture. Horizontal is good for very high-res screens and displays comments to the right of the picture.  Vertical is recommended for very small, low-res screens.
$lang['msg_automatic'] = "Automatisch ist das Standardformat und wird die Kommentare um das Bild herum anpassen. Horizontal ist gut für sehr hochaufgelöste Bildschirme und zeigt Kommentare auf der rechten Seite des Bildes an. Vertikal ist empfohlen für sehr kleine, niedrigaufgelöste Bildschirme.";

//Thumbnail mode
$lang['thumbmode'] = "Thumbnail-Modus";

//Default
$lang['word_default'] = "Voreingestellt";

//Scaled
$lang['word_scaled'] = "Skaliert";

//Default is recommended.  Layout will disable most thumbnails.  Scaled is like layout but will shrink big pictures.  Uniformity will make all thumbnails the same size.
$lang['msgdefrec'] = "Voreingestellt wird empfohlen. Layout wird die meisten Thumbnails deaktivieren. Skaliert ist wie Layout, nur schrumpft es große Bilder. Einheitlich erstellt gleichgroße Thumbnails.";

//(Cannot be changed on this board)
$lang['msg_cantchange'] = "(Kann auf diesem Board nicht geändert werden)";

//Screen size
$lang['screensize'] = "Bildschirmgröße";

//{1} or higher
// {1} = screen resolution ("1280&times;1024")
$lang['orhigher'] = "{1} oder größer";

//Your screensize, which helps determine the best layout.  Default is 800 &times; 600.
$lang['screensizemsg'] = "Ihre Bildschirmgröße, welche hilft das beste Layout zu bestimmen. Standard liegt bei 800 &times; 600.";

// No image data was received by the server.\nPlease try again, or take a screenshot of your picture.
$lang['err_nodata'] = "Es konnten keine Bilddaten vom Server empfangen werden.\n Versuchen Sie es erneut oder erstellen Sie einen Screenshot (Bildschirmfoto) von Ihrem Bild (um sicher zu gehen).";

//Login could not be verified!  Take a screenshot of your picture.
$lang['err_loginvs'] = "Login konnte nicht bestätigt werden! Erstellen Sie ein Screenshot (Bildschirmfoto) von Ihrem Bild.";

//Unable to allocate a new picture slot!\nTake a screenshot of your picture and tell the admin.
$lang['err_picts'] = "Konnte keinen neuen Bildplatz zuteilen!\n Erstellen Sie einen Screenshot (Bildschirmfoto) von Ihrem Bild und melden es einem Administrator.";

//Unable to save image.\nPlease try again, or take a screenshot of your picture.
$lang['err_saveimg'] = "Bild konnte nicht gespeichert werden.\n Bitte versuchen Sie es erneut, oder erstellen Sie ein Screenshot (Bildschirmfoto) von Ihrem Bild.";

//Rules
$lang['word_rules'] = "Regeln";

//Public Images
$lang['publicimg'] = "Öffentliche Bilder";

//Drawings by Comment
$lang['drawbycomm'] = "Bilder nach Kommentaren";

//Animations by Comment
$lang['animbycomm'] = "Animationen nach Kommentaren";

//Archives by Commen
$lang['archbycomm'] = "Archive nach Kommentaren";

//Go
// context = Used as button
$lang['word_go'] = "Senden";

//My Oekaki
$lang['myoekaki'] = "Mein Oekaki";

//Reset Password
$lang['respwd'] = "Passwort zurücksetzen";

//Unlock
$lang['word_unlock'] = "Entsperren";

//Lock
$lang['word_lock'] = "Sperren";

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
$lang['picnumber'] = 'Bild #{1}';

//Pic #{1} (click to view)
$lang['clicktoview'] = 'Bild #{1} (Klicke zum betrachten)';

//(Click to enlarge)
$lang['clickenlarg'] = "Klicke zum vergrößern";

//Adult
$lang['word_adult'] = "Gesperrt";

//Public
$lang['word_public'] = "Öffentlich";

//Thread Locked
$lang['tlocked'] = "Thema gesperrt";

//The mailbox has been disabled.
$lang['mailerrmsg1'] = "Die Mailbox wurde deaktiviert.";

//You cannot access the mailbox.
$lang['mailerrmsg2'] = "Sie können nicht auf die Mailbox zugreifen.";

//You need to login to access the mailbox.
$lang['mailerrmsg3'] = "Sie müssen eingeloggt sein, um auf die Mailbox zugreifen zu können.";

// You cannot access messages in the mailbox that do not belong to you.
$lang['mailerrmsg4'] = "Sie können nicht auf Nachrichten anderer Benutzer zugreifen.";

//You cannot access the mass send.
$lang['mailerrmsg5'] = "Sie können keine Massenmail senden.";

//Reverse Selection
$lang['revselect'] = "Auswahl umkehren";

//Delete Selected
$lang['delselect'] = "Markierte löschen";

//(Yourself)
// context = Placeholder in table list
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_yourself'] = "(Sie selbst)";

//Original Message
$lang['orgmessage'] = "Originalnachricht";

//Send
$lang['word_send'] = "Senden";

//You can use this to send a global notice to a group of members via the OPmailbox.
$lang['massmailmsg1'] = "Sie können diese Funktion nutzen, um eine umfassende Nachricht an eine Gruppe von Benutzern via OPmailbox zu schicken.";

//Be careful when sending a mass mail to &ldquo;Everyone&rdquo; as this will result in LOTS of messages in your outbox.  Use this only if you really have to!
$lang['massmailmsg2'] = "Vorsicht beim Senden von Massenmails an &bdquo;Alle&ldquo;. Dies hat zur Folge, dass viele Nachrichten im Postausgang gespeichert werden. Nutzen Sie diese Funktion nur, wenn Sie wirklich müssen!";

//Everyone
$lang['word_everyone'] = "Alle";

//Administrators
$lang['word_administrators'] = "Administratoren";

//Pictures
$lang['word_pictures'] = "Bilder";

//Sort by
$lang['sortby'] = "Ordnen nach";

//Order
$lang['word_order'] = "Reihenfolge";

//Per page
$lang['perpage'] = "Pro Seite";

//Keywords
$lang['word_keywords'] = "Schlüsselwörter";

//(please login)
// context = Placeholder. Substitute for an e-mail address
$lang['plzlogin'] = "(Nur für angemeldete Benutzer sichtbar)";

//Pending
$lang['word_pending'] = "Schwebend";

//You do not have the credentials to access flag modification.
$lang['err_modflags'] = "Sie dürfen keine Benutzerrechte bearbeiten.";

//Warning:  be careful not to downgrade your own rank
$lang['warn_modflags'] = "Warnung:  Vorsicht, verringern Sie nicht Ihren eigenen Rang!";

//Admin rank
$lang['adminrnk'] = "Admin-Rang";

//current
$lang['word_current'] = "Aktuell";

//You do not have the credentials to access the password reset.
$lang['retpwderr'] = "Sie dürfen keine Passwörter zurücksetzen.";

//Only use this feature if a member cannot change their password in the profile editor, and they cannot use the password recovery feature because their recorded e-mail is not working.
$lang['newpassmsg1'] = "Benutzen Sie diese Funktion nur, wenn ein Benutzer sein Passwort nicht via Profil ändern kann und die Passwort-Wiederherstellung ebenfalls aufgrund der eingetragenen E-Mail nicht hilft.";

//Valid password in database
$lang['validpwdb'] = "Gültiges Passwort in der Datenbank";

//You do not have draw access. Login to draw, or ask an administrator for details about receiving access.
$lang['err_drawaccess'] = "Sie besitzen keine Zeichenrechte. Loggen Sie  sich zum Zeichnen ein oder fragen einen Administrator nach Informationen diese Rechte zu bekommen.";

//Public retouch disabled.
$lang['pubretouchdis'] = "Öffentliches weitermalen ist deaktiviert.";

//Incorrect password to retouch!
$lang['errrtpwd'] = "Falsches Passwort!";

//You have too many unfinished pictures!  Use Recover Pics menu to finish one.
$lang['munfinishpic'] = "Sie besitzen zu viele unfertige Bilder!  Benutzen Sie das Bilder wiederherstellen-Menü, um die anderen zu beenden.";

//You have an unfinished picture!  Use Recover Pics menu to finish it.
$lang['aunfinishpic'] = "Sie besitzen ein unfertiges Bild!  Benutzen Sie &bdquo;Bilder wiederherstellen&ldquo;, um es zu beenden.";

//Resize PaintBBS to fit window
$lang['resizeapplet'] = "Größe der Programmfläche an das Fenster anpassen";

//Hadairo
$lang['pallette1'] = "Hautfarben";

//Red
$lang['pallette2'] = "Rot";

//Yellow
$lang['pallette3'] = "Gelb";

//Green
$lang['pallette4'] = "Grün";

//Blue
$lang['pallette5'] = "Blau";

//Purple
$lang['pallette6'] = "Violett";

//Brown
$lang['pallette7'] = "Braun";

//Character
$lang['pallette8'] = "Charakter";

//Pastel
$lang['pallette9'] = "Pastell";

//Sougen
$lang['pallette10'] = "Sougen";

//Moe
$lang['pallette11'] = "Moe";

//Grayscale
$lang['pallette12'] = "Graustufen";

//Main
$lang['pallette13'] = "Main";

//Wac!
$lang['pallette14'] = "Wac!";

//Save Palette
$lang['savpallette'] = "Palette speichern";

//Save Color Changes
$lang['savcolorcng'] = "Farbänderungen speichern";

//Palette
$lang['word_Palette'] = "Palette";

//Brighten
$lang['word_Brighten'] = "Aufhellen";

//Darken
$lang['word_Darken'] = "Abdunkeln";

//Invert
$lang['word_invert'] = "Invertieren";

//Replace all palettes
$lang['paletteopt1'] = "Alle Paletten ersetzen";

//Replace active palette
$lang['paletteopt2'] = "Aktive Palette ersetzen";

//Append palette
$lang['apppalette'] = "Palette hinzufügen";

//Set As Default Palette
$lang['setdefpalette'] = "Als Standard-Palette einstellen";

//Palette Manipulate/Create
$lang['palletemini'] = "Palette bearbeiten/erstellen";

//Gradation
$lang['word_Gradation'] = "Verlauf";

//Applet Controls
$lang['appcontrol'] = "Programmsteuerung";

//Please note:
// context = help tips on for applets
$lang['plznote'] = "Bitte beachten Sie:";

//Any canvas size change will destroy your current picture
$lang['canvchgdest'] = "Jegliche Art von Änderungen an der Zeichenfläche, wird Ihr aktuelles Bild löschen";

//You cannot resize your canvas when retouching an older picture.
$lang['noresizeretou'] = "Sie können die Größe der Zeichenfläche nicht beim überarbeiten eines Bildes ändern.";

//You may need to refresh the window to start retouching.
$lang['refreshbret'] = "Sie müssen eventuell das Fenster aktualisieren, um Ihr Bild weitermalen zu können.";

//Click the &ldquo;Float&rdquo; button in the upper left corner to go fullscreen.
$lang['float'] = "Klicken Sie den &bdquo;Float&ldquo;-Button in der oberen linken Ecke, um zu Vollbild zu wechseln.";

//X (width)
$lang['canvasx'] = "X";

//Y (height)
$lang['canvasy'] = "Y";

//Modify
$lang['word_modify'] = "Ändern";

//Java Information
$lang['javaimfo'] = "Java Information";

//If you get an &ldquo;Image Transfer Error&rdquo;, you will have to use Microsoft VM instead of Sun Java.
$lang['oekakihlp1'] = "Wenn Sie ein &bdquo;Bildübertragungsfehler&ldquo; erhalten, müssen Sie die Microsoft VM anstelle von Sun Java verwenden.";

//If retouching an animated picture and the canvas is blank, play the animation first.
$lang['oekakihlp2'] = "Sollte bei dem Überarbeiten eines Bildes mit Animation der Bildschirm leer bleiben, versuchen Sie zunächst die Animation abzuspielen.";

//Recent IP / host
$lang['reciphost'] = "Letzte(r) IP / Host";

//Send mailbox message
$lang['sendmailbox'] = "Eine Nachricht senden";

//Browse all posts ({1} total)
$lang['browseallpost'] = "Durchsuche alle Einträge ({1} insgesamt)";

//(Broken image)
// context = Placholder for missing image
$lang['brokenimage'] = "(Bild defekt)";

//(No animation)
// context = Placholder for missing animation
$lang['noanim'] = "(Keine Animation)";

//{1} seconds
$lang['recover_sec'] = "{1} Sekunden";

//{1} {1?minute:minutes}
$lang['recover_min'] = "{1} {1?Minute:Minuten}";

//Post now
$lang['postnow'] = "Jetzt veröffentlichen";

//Please read the rules before submitting a registration.
$lang['plzreadrulereg'] = "Bitte lesen Sie zunächst die Regeln, bevor Sie sich registrieren.";

//If you agree to these rules
$lang['agreerulz'] = "Wenn Sie diesen Regeln zustimmen";

//click here to register
$lang['clickheregister'] = "klicken Sie hier, um sich zu registrieren";

//Registration Submitted
$lang['regisubmit'] = "Registration abgeschickt";

//Your registration for &ldquo;{1}&rdquo; is being processed.
// {1} = Oekaki title
$lang['urgistra'] = "Ihre Registration bei &bdquo;{1}&ldquo; wird bearbeitet.";

//Your registration for &ldquo;{1}&rdquo; has been approved!<br /><br />You may now configure your membership profile.<br /><br /><a href=\"editprofile.php\">Click here to edit your profile.</a>
// {1} = Oekaki title
$lang['urgistra_approved'] = "Ihre Registration bei &bdquo;{1}&ldquo; wurde bestätigt!<br /><br />Sie dürfen nun Ihr Benutzerprofil bearbeiten.<br /><br /><a href=\"editprofile.php\">Klicken Sie hier, um Ihr Profil zu bearbeiten</a>";

//Before you may login, an administrator must approve your registration.  You should receive an e-mail shortly to let you know if your account has been approved.<br /><br />Once approved, you may update your member profile via the &ldquo;My Oekaki&rdquo; menu.
$lang['aprovemsgyes'] = "Bevor Sie sich einloggen können, muss ein Administrator Ihre Registration bestätigen. Sie sollten in Kürze eine E-Mail erhalten, in der geschrieben steht, ob Ihr Account freigeschaltet wurde.<br /><br /> Sobald dieser bestätigt wurde, können Sie Ihr Benutzerprofil unter &bdquo;Mein Oekaki &ldquo; bearbeiten.";

//Please check your e-mail soon for the link to activate your account.<br /><br />Once your e-mail has been verified, you will be automatically logged in as a new member, and will be able to add information to your profile.
$lang['aprovemsgno'] = "Bitte überprüfen Sie Ihr E-Mail-Postfach (ggf. auch den Spam-Ordner), ob Sie eine Aktivierungsmail inkl. des Aktivierungslinks erhalten haben.<br /><br />Sobald Ihre E-Mail bestätigt wurde, werden Sie automatisch als neues Mitglied angemeldet sein und können weitere Informationen zu Ihrem Profil hinzufügen.";

//Notes About Registering
$lang['nbregister'] = "Anmerkung über die Registration";

//DO NOT REGISTER TWICE.
$lang['registertwice'] = "REGISTRIEREN SIE SICH NICHT ZWEIMAL.";

//You can check if you're in the pending list by viewing the member list and searching for your username.
$lang['regmsg1'] = "Sie können überprüfen, ob Sie sich in der Liste für Schwebende Benutzer befinden, indem Sie in der Mitgliederliste nach Ihrem Benutzernamen suchen.";

//Use only alphanumeric characters for names and passwords.  Do not use quotes or apostrophes.  Passwords are case-sensitive.
$lang['regmsg2'] = "Benutzen Sie für Ihr Passwort nur Buchstaben- und Zahlenkombinationen. Benutzen Sie keine Sonderzeichen wie Anführungszeichen und Apostrophe. Bei Passwörtern ist die Groß-Kleinschreibung wichtig!";

//You may change anything in your profile except your name once your registration is accepted.
$lang['regmsg3'] = "Sie können alles in Ihrem Profil ändern, mit Ausnahme des Benutzernamens, sobald Ihre Registration akzeptiert wurde.";

//You must wait for an administrator to approve your registration on this board.  Your registration approval may take awhile if no one at the moment has time to maintain the pending list.  Please be patient; you will receive an e-mail notifying you of your approval.
$lang['regmsg4'] = "Sie müssen warten, bis ein Administrator Sie freigeschalten hat. Die Bestätigung Ihrer Registration kann eine Weile dauern, wenn zu diesem Zeitpunkt kein Administrator auf dem Board unterwegs ist. Bitte seien Sie geduldig; Sie werden eine E-Mail erhalten, die Sie über eine Änderung des Status Ihrer Registration informiert.";

//If you don't receive an e-mail with a verification code, or if you cannot activate your account via e-mail, contact an administrator for help.  Administrators may manually approve your account in these cases.
$lang['regmsg5'] = "Falls Sie keine E-Mail mit einen Aktivierungslink erhalten haben oder Sie Ihren Account nicht via E-Mail aktivieren können, dann kontaktieren Sie einen Administrator und bitten diesen um Hilfe. Administratoren können ggf. Benutzer manuell freischalten.";

//Your password can be mailed to you if you forget it.  <strong>Your e-mail will only be visible to other registered members.</strong>  You can remove or edit your e-mail after registration.  Ask the board owner about other potential privacy concerns.
$lang['regmsg6'] = "Ihr Passwort kann Ihnen zugeschickt werden, falls Sie es vergessen haben. <strong> Ihre E-Mail-Adresse ist nur für andere Benutzer sichtbar.</strong>  Sie können aber bei Bedarf Ihre E-Mail-Adresse nach der Registration bearbeiten und entfernen.";

//{1}+ Age Statement
// {1} = minimum age. Implies {1} age or older
$lang['agestatement'] = "{1}+ Altersangabe";

//<strong>This oekaki is for adults only.</strong>  You are required to declare your birth year to register.  Year is required, month and day are optional and may be left blank.
$lang['adultonlymsg'] = "<strong>Dieses Oekaki ist nur für Erwachsene.</strong>  Sie müssen Ihr Geburtsjahr zunächst angeben, um sich zu registrieren. Das Jahr wird benötigt, Monate und Tage sind optional und können leer gelassen werden.";

//A link to your webpage, or a direct link to a sample of your artwork.  Not required for registration on this board.
$lang['nbwebpage'] = "Ein Link zu Ihrer Website oder ein direkter Link mit Beispielen Ihrer Zeichnungen/Bilder/Werke. Wird nicht für die Registration benötigt.";

//Submit Registration
$lang['subregist'] = "Registration abschicken";

//Could not fetch information about picture
$lang['coulntfetipic'] = "Konnte keine Informationen über das Bild erhalten";

//No edit number specified
$lang['noeditno'] = "Keine Bearbeitungsnummer festgelegt";

//This picture is available to all board members.
$lang['picavailab'] = "Dieses Bild dürfen alle Mitglieder des Boardes benutzen.";

//The edited version of this image will be posted as a new picture.
$lang['retouchmsg2'] = "Die bearbeitete Version des Bildes wird als neues gespeichert.";

//The original artist will be credited automatically.
$lang['retouchmsg3'] = "Der Zeichner des Bildes wird automatisch angegeben.";

//A password is required to retouch this picture.
$lang['retouchmsg4'] = "Ein Passwort wird benötigt, um dieses Bild fortzusetzen.";

//The retouched picture will overwrite the original
$lang['retouchmsg5'] = "Das bearbeitete Bild wird das Original ersetzen.";

//Continue
$lang['word_continue'] = "Fortsetzen";



/* sqltest.php */

//SQL direct call
// context = Can use the SQL tool
$lang['st_sql_header'] = 'SQL direkte Anfrage';

//Original:
$lang['st_orig_query'] = 'Original:';

//Evaluated:
// context = "Processed" or "Computed"
$lang['st_eval_query'] = 'Einschätzung:';

//Query okay.
$lang['st_query_ok'] = 'Anfrage okay.';

//{1} {1?row:rows} affected.
$lang['st_rows_aff'] = '{1} {1?Row:Rows} betroffen.';

//First result: &ldquo;{1}&rdquo;
$lang['st_first_res'] = 'Erstes Ergebnis: &bdquo;{1}&ldquo;';

//Query failed!
$lang['st_query_fail'] = 'Anfrage fehlgeschlagen!';

//Database type:
// context = Which brand of database (MySQL, PostgreSQL, etc.)
$lang['st_db_type'] = 'Datenbanktyp:';

//&nbsp;USE THIS TOOL WITH EXTREME CAUTION!  Detailed SQL knowledge required!&nbsp;
// context = This is a BIG warning with a large font.
$lang['st_big_warn'] = '&nbsp;BENUTZEN SIE DIESE FUNKTION MIT HÖCHSTER VORSICHT! Genaue SQL-Kenntnisse sind erforderlich!&nbsp;';

//Type a raw SQL query with no ending semicolon.  PHP strings will be evaluated.  Confirmation will be requested.
$lang['st_directions'] = 'Geben Sie eine Raw SQL-Anfrage ohne Semikolon am Ende ein.  PHP Strings werden ausgewertet. Bestätigung wird angefordert.';

//Version
$lang['st_ver_btn'] = 'Version';

/* END sqltest.php */



/* testinfo.php */

//Diagnostics page available only to owner.
$lang['testvar1'] = 'Die Diagnoseseite kann nur vom Besitzer eingesehen werden.';

//<strong>Folder empty</strong>
$lang['d_folder_empty'] = '<strong>Ordner leer</strong>';

//DB info
$lang['dbinfo'] = 'DB-Info';

// Database version:
$lang['d_db_version'] = 'Datenbankversion:';

//Total pictures:
$lang['d_total_pics'] = 'Bilder insgesamt:';

//{1} (out of {2})
// {1} = existing pictures, {2} = maximum
$lang['d_pics_vs_max'] = '{1} (von {2})';

//Archives:
$lang['d_archives'] = 'Archive:';

//WIPs and recovery:
$lang['d_wip_recov'] = 'WIPs und Safety saves:';

//Current picture number:
$lang['d_cur_picno'] = 'Momentane Anzahl an Bildern:';

//<strong>Cannot read folder</strong>
$lang['d_no_read_dir'] = '<strong>Verzeichnis nicht lesbar</strong>';

//SQL direct calls:
// context = Can use the SQL tool
$lang['d_sql_direct'] = 'SQL direkte Anfragen:';

//Available <a href="{1}">(click here)</a>
$lang['d_sql_avail'] = 'Vorhanden <a href="{1}">(Klicken Sie hier)</a>';

//Config
$lang['d_word_config'] = 'Konfigurieren';

//PHP Information:
$lang['d_php_info'] = 'PHP Information:';

//{1} <a href="{2}">(click for more details)</a>
// {1} = PHP version number
$lang['d_php_ver_num'] = '{1} <a href="{2}">(Klicken Sie hier, für weitere Details)</a>';

//Config version:
$lang['configver'] = "Version konfigurieren:";

//Contact:
$lang['word_contact'] = 'Kontakt:';

//Path to OP:
$lang['pathtoop'] = 'Pfad zum OP:';

//Cookie path:
$lang['cookiepath'] = 'Cookie-Pfad:';

//Cookie domain:
// context = domain: tech term used for web addresses
$lang['cookie_domain'] = 'Cookie Domain:';

//Cookie life:
// context = how long a browser cookie lasts
$lang['cookielife'] = 'Wie lange werden Cookies gespeichert:';

//(empty)
// context = placeholder if no path/domain set for cookie
$lang['cookie_empty'] = '(leer)';

//{1} seconds (approximately {2} {2?day:days})
$lang['seconds_approx_days'] = '{1} Sekunden (etwa {2} {2?Tag:Tage})';

//Public images: // 'publicimg'
$lang['d_pub_images'] = 'Öffentliche Bilder:';

//Safety saves:
$lang['safetysaves'] = 'Safety saves:';

//Yes ({1} days)
// {1} always > 1
$lang['d_yes_days'] = 'Ja ({1} Tage)';

//No ({1} days)
$lang['d_no_days'] = 'Nein ({1} Tage)';

//Pictures folder
$lang['d_pics_folder'] = 'Bilderverzeichnis';

//Notice:
$lang['d_notice'] = 'Anmerkung:';

//Folder:
$lang['d_folder'] = 'Verzeichnis:';

//Total files:
$lang['d_total_files'] = 'Dateien insgesamt:';

//Total space used:
$lang['d_space_used'] = 'Belegter Datenspeicher:';

//Average file size:
$lang['d_avg_filesize'] = 'Durchschnittliche Datengröße:';

//Images:
$lang['d_images_label'] = 'Bilder:';

//{1} ({2}%)
// {1} = images, {2} = percentage of folder
$lang['d_img_and_percent'] = '{1} ({2}%)';

//Animations:
$lang['d_anim_label'] = 'Animationen:';

//{1} ({2}%)
// {1} = animations, {2} = percentage of folder
$lang['d_anim_and_percent'] = '{1} ({2}%)';

//Other filetypes:
$lang['d_other_types'] = 'Andere Dateitypen:';

//Locks
$lang['word_locks'] = 'Sperrungen/Freigaben';

// Okay
// context = file is "writable" or "good".
$lang['word_okay'] = 'Okay';

//<strong>Locked</strong>
// context = "Unwritable" or "Unavailable" rather than broken or secure
$lang['word_locked'] = '<strong>Gesperrt</strong>';

//<strong>Missing</strong>
$lang['word_missing'] = '<strong>Nicht vorhanden</strong>';

/* END testinfo.php */



//You do not have the credentials to upload pictures.
$lang['err_upload'] = "Sie dürfen keine Bilder hochladen.";

//Picture to upload
$lang['pictoupload'] = "Pfad zum Bild";

//Valid filetypes are PNG, JPEG, and GIF.
$lang['upldvalidtyp'] = "Akzeptierte Dateitypen sind: PNG, JPEG, und GIF.";

//Animation to upload
$lang['animatoupd'] = "Pfad zur Animations-/Ebenendatei";

//Matching picture and applet type required.
$lang['uploadmsg1'] = "Passender Bild- und Anwendungstyp wird benötigt.";

//Valid filetypes are PCH, SPCH, and OEB.
$lang['uploadmsg2'] = "Akzeptierte Dateitypen: PCH, SPCH, CHI und OEB.";

//Valid filetypes are PCH and SPCH.
$lang['uploadmsg3'] = "Akzeptierte Dateitypen sind: PCH, SPCH und CHI.";

//Applet type
$lang['appletype'] = "Anwendungstyp";

//Time invested (in minutes)
$lang['timeinvest'] = "Zeit investiert (in Minuten)";

//Use &ldquo;0&rdquo; or leave blank if unknown
$lang['uploadmsg4'] = "Benutzen Sie &bdquo;0&ldquo; oder lassen das Feld frei, falls unbekannt";

//Download
$lang['word_download'] = "Download";

//This window refreshes every {1} seconds.
$lang['onlinelistmsg'] = "Dieses Fenster aktualisiert sich alle {1} Sekunden.";

//Go to page
$lang['gotopg'] = "Gehe zur Seite";

//Netiquette applies.  Ask the admin if you have any questions.
// context = Default rules
$lang['defrulz'] = "Es gilt die Netiquette. Fragen Sie einen Administrator, wenn Sie Fragen haben.";

//Send reason
$lang['sendreason'] = "Grund senden";

//&ldquo;avatar&rdquo; field does not exist in database.
$lang['err_favatar'] = "&bdquo;avatar&ldquo;-Feld existiert nicht in der Datenbank.";

//Get
$lang['pallette_get'] = "Erhalte";

//Set
$lang['pallette_set'] = "Setze";

// Diagnostics
$lang['header_diag'] = 'Diagnosen';

// Humanity test for guest posts?
$lang['cpanel_humanity_infoask'] = "Spamschutz für schreibende Gäste aktivieren?";

// If yes, guests are required to pass a humanity test before posting comments.  The test must be passed only once.
$lang['cpanel_humanity_sub'] = "Wurde JA gewählt, dann müssen Gäste eine Aufgabe lösen, bevor diese Kommentare absenden können. Diese Aufgabe muss nur einmal gelöst werden.";

// And now, for the humanity test.
$lang['humanity_notify_sub'] = "Spamschutz";

// If canvas is blank or broken, <a href=\"{1}\">click here to import canvas, not animation</a>.
$lang['shi_canvas_only'] = "Falls die Fläche leer oder kaputt ist, <a href=\"{1}\">Klicken Sie hier, um das Bild zu importieren (OHNE Animation)</a>.";

//For help with installation, read the &ldquo;readme.html&rdquo; file that came with your Wacintaki distribution.  Make sure you have CHMOD all files appropriately before continuing with installation.  For technical assistance, please visit the <a href="http://www.NineChime.com/forum/">NineChime Software Forum</a>.
$lang['assist_install'] = "Für Hilfe bei der Installation, lesen Sie die &bdquo;readme.html&ldquo;-Datei die beim Wacintaki beigefügt ist. Versichern Sie sich, dass alle CHMOD-Rechte richtig vergeben wurden, bevor Sie mit der Installation fortfahren. Für technische Unterstützung besuchen Sie das <a href=\"http://www.NineChime.com/forum/\">NineChime Software Forum</a>.";

//The installer only sets mandatory information.  Once the board has been installed, use the Control Panel to fully configure the board.
$lang['assist_install2'] = "Der Installer setzt nur verbindliche Informationen. Sobald das Board installiert ist, benutzen Sie das Kontrollzentrum (Control Panel), um Ihr Board komplett zu konfigurieren.";

//<strong>None</strong> will disable thumbnails, and uses a lot of bandwidth.  <strong>Layout</strong> will keep most pictures their original dimmentions, and usually uses a vertical layout for wide pictures to keep comments readable.  <strong>Scaled</strong> will use thumbnails for wide pictures, and favor horizontal layout.  <strong>Uniformity</strong> makes all the pictures the same size with a small thumbnail.
$lang['thumbmodesexp'] = "<strong>Keine</strong> deaktiviert Thumbnails und benötigt eine Menge Bandbreite.  <strong>Layout</strong> wird die meisten Bilder in ihrer Originalgröße beibehalten und benutzt meistens das vertikale Layout für breite Bilder, um Kommentare lesbar zu belassen. <strong>Skaliert</strong> wird Thumbnails aus breiten Bildern erstellen und bevorzugt das horizontale Layout. <strong>Einheitlich</strong> macht aus allen Bildern eine Größe bei den Thumbnails.";

//Resize to this:
$lang['resize_to_this'] = 'Ändert die Größe zu dieser:';

//Show e-mail to members
$lang['email_show'] = 'E-Mail-Adresse anderen Benutzern anzeigen';

//Show smilies
$lang['smilies_show'] = 'Smileys anzeigen';

//Host lookup disabled in &ldquo;hacks.php&rdquo; file.
$lang['hosts_disabled'] = 'Host lookup deaktiviert in &bdquo;hacks.php&ldquo;.';

//Reminder
$lang['word_reminder'] = 'Erinnerung';

//Anti-spam
$lang['anti_spam'] = 'Anti-Spam';

//(Delete without sending e-mail)
$lang['anti_spam_delete'] = '(Löschen ohne eine E-Mail zu senden)';

//Log
$lang['word_log'] = 'Log';

//You must be an administrator to access the log.
$lang['no_log_access'] = 'Sie müssen ein Administrator sein, um den Log einsehen zu können.';

//{1} entries
$lang['log_entries'] = '{1} Einträge';

//Category
$lang['word_category'] = 'Kategorie';

//Peer (affected)
$lang['log_peer'] = 'Peer (Betroffen)';

//(Self)
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['log_self'] = '(Sie Selbst)';

//Required.  Guests cannot see e-mails, and it may be hidden from other members.
$lang['msg_regmail'] = "Erforderlich.  Gäste können keine E-Mail-Adressen sehen und zusätzlich können diese vor anderen Benutzern verborgen werden.";

//Normal Chibi Paint
$lang['online_npaintbbs'] = 'Chibi Paint - Normal';

//No Animation
$lang['draw_no_anim'] = '(Nur Ebenen; keine Animation)';

//Purge All Registrations
$lang['purge_all_regs'] = "Alle Registrationen bereinigen";

//Are you sure you want to delete all {1} registrations?
$lang['sure_purge_regs'] = "Sind Sie sicher, dass Sie alle {1} Registrationen löschen wollen?";

//Animations/Layers
$lang['draw_anims_layers'] = 'Animationen/Ebenen';

//Most applets combine layers with animation data.
$lang['draw_combine_layers'] = 'Die meisten Programme kombinieren die Ebenen mit der Animationsdatei.';

//Helps prevent accidental loss of pictures.
$lang['draw_help_loss_pics'] = 'Hilft vor unbeabsichtigten Verlierens des Bildes.';

//Window close confirmation
$lang['draw_close_confirm'] = 'Bestätigung der Schließung des Fensters';

//Remember draw settings
$lang['draw_remember_settings'] = 'Zeicheneinstellungen merken';

//Any reduction to the number of pictures stored requires confirmation via the checkbox.
$lang['pic_store_change_confirm'] = 'Jegliche Reduktion der Anzahl an Bildern, die gespeichert werden, muss mittels Kontrollkästchen bestätigt werden.';

//Check this box to confirm any changes
$lang['cpanel_check_box_confirm'] = 'Markieren Sie dieses Kästchen, um Änderungen zu bestätigen';

//Use Lytebox viewer
$lang['cpanel_use_lightbox'] = 'Den Lytebox Viewer aktivieren';

//Enables support for the zooming Lytebox/Lightbox viewer for picture posts.
$lang['cpanel_lightbox_sub'] = 'Aktiviert die Unterstützung für den zoomenden Lytebox/Lightbox Viewer für Bilder.';

//An Administrator has all the basic moderation functions, including editing the notice and banner, banning, changing member flags, and recovering pictures.
$lang['type_aabil'] = 'Ein Administrator hat alle einfachen Moderations-Funktionen, inklusive das Bearbeiten der Ankündigungen und Banner, Bannung, Benutzerrechte verwalten und Bilder wiederherzustellen.';

//Moderator
$lang['word_moderator'] = 'Moderator';

//Moderators have the ability to edit and delte comments, upload, as well as edit post properties (lock, bump, adult, WIP).
$lang['type_mabil'] = 'Moderatoren haben die Fähigkeit Kommentare zu bearbeiten und zu löschen, Bilder hochzuladen und Bilder zu bearbeiten (Sperren, Bump, 18+, WIP).';

//Use Lightbox pop-up viewer
$lang['profile_use_lightbox'] = 'Den Pop-Up Viewer aktivieren';

//Enables pop-up 
$lang['profile_lightbox_sub'] = 'Aktiviert den Lightbox/Lytebox Pop-Up Viewer für Bilder, anstelle das Öffnen des Bildes in einem neuen Tab oder Fenster.';

//{1} {1?day:days} remaining
$lang['recovery_days_remaining'] = "{1} {1?Tag:Tage} verbleibend";

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_notice'] = 'Sie haben {1} {1?unfertiges Bild:unfertige Bilder}. Bitte beenden Sie {1?es:mindestens eins}, bevor Sie ein neues Bild anfangen.';

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_warning'] = 'Sie haben {1} {1?unfertiges Bild:unfertige Bilder}.  Bitte beenden Sie {1?es:mindestens eins}, bevor Sie ein neues Bild anfangen.';

//(Default only)
// context = Placeholder if only one template available
$lang['default_only'] = '(Nur Voreinstellungen)';

//Private Oekaki
$lang['header_private_oekaki'] = 'Privates Oekaki';

//You are not logged in.  Please log in or register to view this oekaki.
$lang['private_please_login'] = 'Sie sind nicht eingeloggt. Melden Sie sich an oder registrieren Sie sich, um dieses Oekaki-Board betrachten zu können.';

//Requires members to register or log in to view art and comments.  Guests are completelly blocked.
$lang['private_oekaki_sub'] = 'Verlangt, dass Mitglieder sich registrieren oder einloggen, um Bilder betrachten und Kommentare lesen zu können. Gäste werden komplett geblockt.';

//Server is able to send e-mail?
$lang['able_send_email'] = 'Kann der Server E-Mails versenden?';

//Safety Save Limit
$lang['header_safety_max'] = 'Safety Save Limit';

//Maximum number of unfinished pictures a member may have at a time.  Default is {1}.
$lang['safety_max_sub'] = 'Die maximale Anzahl an unfertigen Bildern, die ein Mitglied zur gleichen Zeit besitzen darf.  Standard ist {1}.';

//You need Java&trade; to use the paint program.  <a href="{1}">Click here to download Java</a>.
$lang['paint_need_java_link'] = 'Sie benötigen Java&trade; um die Zeichenanwendungen benutzen zu können.  <a href="{1}">Klicken Sie hier, um Java herunterzuladen</a>.';

//Don't know / I Don't know
// context = drop-down menu option
$lang['option_dont_know'] = "Nicht bekannt";

//{1} folder is locked!  Folder must be CHMOD to 755 (preferred) or 777.  View Readme.txt for help.
// NOTE: no HTML.  {1} = folder, {2} = manual
$lang['boot_folder_locked'] = '{1}-Verzeichnis ist gesperrt! Verzeichnisse müssen die CHMOD-Rechte 755 (empfohlen) oder 777 bekommen.  Lesen Sie {2}, um weitere Hilfe zu erhalten.';

//Installer removed
$lang['boot_inst_rm'] = 'Installer entfernt';

//Installer removal failed!
$lang['boot_inst_rm_fail'] = 'Entfernung des Installers fehlgeschlagen!';

//Updater removed
$lang['boot_update_rm'] = 'Updater entfernt';

//Updater removal failed!
$lang['boot_update_rm_fail'] = 'Entfernung des Updaters fehlgeschlagen!';

//Proceed to index page
$lang['boot_goto_index'] = 'Weiter zur Startseite';

//Remove the files manually via FTP
$lang['boot_remove_ftp'] = 'Entfernen Sie die Dateien manuell per FTP';

//&ldquo;install.php&rdquo; and/or &ldquo;update.php&rdquo; still exists!
$lang['boot_still_exist_sub1'] = '&bdquo;install.php&ldquo; und/oder &bdquo;update.php&ldquo; sind noch vorhanden!';

//If you get this error again, delete the files manually via FTP.
$lang['boot_still_exist_sub3'] = 'Sollten Sie diesen Fehler weiterhin erhalten, entfernen Sie die Dateien manuell per FTP.';

//Password verification failed. Make sure the new password is typed properly in both fields
$lang['pass_ver_failed'] = 'Bestätigung des Passworts fehlgeschlagen. Gehen Sie sicher, dass in beiden Feldern, das gleiche Passwort eingetragen wurde.';

//Password contains invalid characters: {1}
$lang['pass_invalid_chars'] = 'Passwort enthält ungültige Zeichen: {1}';

//Password is empty.
$lang['pass_emtpy'] = 'Das Passwort ist leer.';

//Username contains invalid characters: {1}
$lang['name_invalid_chars'] = 'Der Benutzername enthält ungültige Zeichen: {1}';

//Humanity test failed.
$lang['humanity_test_failed'] = 'Menschlichkeitstest (für den Spamschutz) fehlgeschlagen.';

//You must submit a valid age declaration (birth year).
$lang['submit_valid_age'] = 'Sie müssen ein gültiges Alter angeben (Geburtsjahr).';

//Your age declaration could not be accepted.
$lang['age_not_accepted'] = 'Ihre Altersangabe wurde nicht akzeptiert.';

//A valid URL is required to register on this BBS
$lang['valid_url_req'] = 'Eine gültige Adresse (URL) wird benötigt, um sich auf diesem BBS zu registrieren.';

//You must declare your age to register on this BBS
$lang['must_declare_age'] = 'Sie müssen eine gültige Altersangabe machen, um sich auf diesem BBS zu registrieren.';

//Sorry, the BBS e-mailer isn't working. You'll have to wait for your application to be approved.
$lang['email_wait_approval'] = "Entschuldigung, der BBS-E-Mailer funktioniert nicht. Sie müssen warten, bis Ihre Anfrage bestätigt wird.";

//Database error. Please try again.
$lang['db_err'] = 'Datenbankfehler. Bitte versuchen Sie es erneut.';

//Database error. Try using {1}picture recovery{2}.
// {1}=BBCode start tag, {2}=BBCode end tag
$lang['db_err_pic_recovery'] = 'Datenbankfehler. Versuchen Sie die {1}Bilderwiederherstellung{2}.';

//You cannot post a comment because the thread is locked
$lang['no_post_locked'] = 'Sie können hier nicht kommentieren, da das Thema (der Thread) gesperrt wurde.';

//HTML links unsupported.  Use NiftyToo/BBCode instead.
$lang['no_html_alt'] = 'HTML-Links werden nicht unterstützt. Benutzen Sie stattdessen NiftyToo/BBCode.';

//Guests may only post {1} {1?link:links} per comment on this board.
$lang['guest_num_links'] = 'Gäste dürfen nur {1} {1?Link:Links} pro Kommentar auf diesem Board posten.';

//You must be a moderator to mark pictures other than yours as adult.
$lang['mod_change_adult'] = 'Sie müssen ein Moderator sein, um diese Bilder als Gesperrte Bilder (Bilder für Erwachsene) zu markieren.';

//Only moderators may change safety save status.
$lang['mod_change_wip'] = 'Nur Moderatoren können die Safety Save-Status ändern.';

//Only moderators may use this function.
$lang['mod_only_func'] = 'Nur Moderatoren können diese Funktion nutzen.';

//No mode!  Some security policies or advertisements on shared servers may interfere with comments and picture data.  This is a technical problem.  Ask your admin for help.
$lang['func_no_mode'] = 'Kein Modus! Einige Sicherheitseinstellungen oder Werbeanzeigen auf geteilten Servern können die Bild- und Kommentardaten stören. Dies ist ein technisches Problem. Fragen Sie bei Ihren Administrator um Hilfe.';

/* End Version 1.5.5 */



/* Version 1.5.6 */

//Registed
// context = Date on which a member "Submit registration" or "Signed up"
// {1} = count of pending registrations
$lang['registered_on'] = 'Registriert';

//Modify Canvas Size (max is {1} &times; {2})
$lang['applet_modify'] = "Die Größe des Bildes bearbeiten (Maximaler Wert: {1} &times; {2})";

//Canvas (min: {1}&times;{2}, max: {3}&times;{4})
$lang['draw_canvas_min_max'] = 'Zeichenfläche (min: {1}&times;{2}, max: {3}&times;{4})';

//If you're having trouble with the applet, try downloading the latest version of Java from {1}.
$lang['javahlp'] = "Wenn Sie Probleme mit dem Programm haben, versuchen Sie die neueste Version von Java zu installieren von {1}.";

//If you do not need them anymore, <a href="{1}">click here to remove them</a>.
$lang['boot_still_exist_sub2'] = 'Falls Sie diese nicht mehr benötigen, <a href="{1}">klicken Sie hier, um sie zu entfernen</a>.';

//Delete Palette
$lang['delete_palette'] = 'Palette löschen';

//You may have {1} safety {1?save:saves} at a time.  Remember to finish a safety save soon or it will be automatically deleted within {2} {2?day:days}.
$lang['safesavemsg2'] = "Sie dürfen {1} Safety {1?save:saves} gleichzeitig besitzen. Bedenken Sie, dass Sie ihr Bild beenden müssen oder es wird automatisch in {2} {2?Tag:Tagen} gelöscht.";

//Safety save was successful!  To resume a safety save, click &ldquo;Draw&rdquo;, or use the &ldquo;Recover Pics&rdquo; menu.
$lang['safesavemsg3'] = "Der Safety save war erfolgreich! Um Ihr Bild weiter zu malen gehen Sie zu &bdquo;Zeichnen&ldquo; oder benutzen Sie die &bdquo;Bilder wiederherstellen&ldquo;-Option.";

//Every time you retouch your safety save, the delete timer will be reset to {1} {1?day:days}.
$lang['safesavemsg5'] = "Jedes Mal, wenn Sie Ihr Safety Save weitermalen, wird der Zeitzähler zurückgesetzt; auf {1} {1?Tag:Tage}.";

//Error reading picture #{1}.
$lang['err_readpic'] = "Fehler beim Lesen des Bildes #{1}.";

//What is {1} {2} {3}?
//What is  8   +   6 ?
$lang['humanity_question_3_part'] = "Was ergibt {1} {2} {3}?";

//Safety saves are stored for {1} {1?day:days}.
$lang['sagesaveopt3'] = "Safety saves werden für {1} {1?Tag:Tage} gespeichert.";

//Comments (<a href="{1}">NiftyToo Usage</a>)
$lang['header_comments_niftytoo'] = 'Kommentare (<a href="{1}">NiftyToo-Anwendung</a>)';

//Edit Comment (<a href="{1}">NiftyToo Usage</a>)
$lang['ecomm_title'] = 'Kommentar bearbeiten (<a href="{1}">NiftyToo-Anwendung</a>)';

//Edit Picture Info (<a href="{1}">NiftyToo Usage</a>)
$lang['erpic_title'] = 'Bildinformationen bearbeiten (<a href="{1}">NiftyToo-Anwendung</a>)';

//Message Box (<a href="{1}">NiftyToo Usage</a>)
$lang['chat_msgbox'] = 'Nachrichten (<a href="{1}">NiftyToo-Anwendung</a>)';

//(<a href="{1}">NiftyToo Usage</a>)
$lang['common_niftytoo'] = '(<a href="{1}">NiftyToo-Anwendung</a>)';

//(Original by <strong>{1}</strong>)
// {1} = member name
$lang['originalby'] = "(Original von <strong>{1}</strong>)";

//If you are not redirected in {1} seconds, click here.
// context = clickable, {1} defaults to "3" or "three"
$lang['common_redirect'] = 'Sollten Sie nicht binnen {1} Sekunden weitergeleitet werden, klicken Sie hier.';

//Could not write config file.  Check your server permissions.
$lang['cpanel_cfg_err'] = 'Konnte Konfigurationsdatei nicht schreiben. Überprüfen Sie die Zugriffsrechte des Servers.';

//Enable Mailbox
$lang['enable_mailbox'] = "Mailbox aktivieren";

//Unable to read picture #{1}.
$lang['delconf_pic_err'] = 'Konnte Bild #{1} nicht lesen.';

//Image too large!  Size limit is {1} &times; {2} pixels.
$lang['err_imagelar'] = "Bild zu groß! Das Größenlimit liegt bei {1} &times; {2} Pixeln.";

//It must not be larger than {1} &times; {2} pixels.
$lang['notlarg'] = "Es darf nicht größer als {1} &times; {2} Pixel sein.";

//(No avatar)
$lang['noavatar'] = "(Kein Avatar)";

//Print &ldquo;Edited on (date)&rdquo;
// context = (date) is a literal.  Actual date not printed.
$lang['print_edited_on'] = "Anzeigen &bdquo;Bearbeitet am (Datum)&ldquo;";

//(Edited on {1})
// {1} = current date
$lang['edited_on'] = "(Bearbeitet am {1})";

//Print &ldquo;Edited by {1}&rdquo;
// {1} = admin name
$lang['print_edited_by_admin'] = "Anzeigen &bdquo;Bearbeitet von {1}&ldquo;";

//(Edited by <strong>{1}</strong> on {2})
// {1} = admin name, {2} = current date
$lang['edited_by_admin'] = "(Bearbeitet von <strong>{1}</strong> am {2})";

//You may check this if you are an adult (at least {1} years old).
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adultsub'] = "Sie können diese Option anhaken, wenn Sie erwachsen sind (mindestens {1} Jahre alt).";

//Load time {1} seconds
$lang['footer_load_time'] = 'Ladezeit: {1} Sekunden';

//Links disabled for guests
// context = HTML-formatted links in comments on pictures
$lang['no_guest_links'] = 'Links sind für Gäste deaktiviert.';

//{1}+
// context = Marks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['mark_adult'] = '{1}+';

//Un {1}+
// context = Unmarks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['unmark_adult'] = 'De-{1}+';

//Mailbox:
$lang['mailbox_label'] = 'Mailbox:';

//{1} {1?message:messages}, {2} unread
$lang['mail_count'] = '{1} {1?Nachricht:Nachrichten}, {2} ungelesen';

//From:
$lang['from_label'] = 'Von:';

//Re:
// context = may be used inconsistently for technical reasons
$lang['reply_label'] = 'Re:';

//Subject:
$lang['subject_label'] = 'Betreff:';

//Send To:
$lang['send_to_label'] = 'Senden an:';

//Message:
$lang['message_label'] = 'Nachricht:';

//<a href="{1}">{2}</a> @ {3}
// context = "{2=username} sent you this mailbox message at/on {3=datetime}"
$lang['mail_sender_datetime'] = '<a href="{1}">{2}</a> @ {3}';

//Registered: {1} {1?member:members} and {2} {2?admin:admins}.
$lang['mmail_reg_list'] = 'Registriert: {1} {1?Mitglied:Mitglieder} und {2} {2?Admin:Admins}.';

//{1} {1?member:members} active within the last {2} days.
$lang['mmail_active_list'] = '{1} {1?Mitglied:Mitglieder} aktiv innerhalb der letzten {2} Tage.';

//Everyone ({1})
$lang['mmail_to_everyone'] = 'Alle ({1})';

//Active members ({1})
$lang['mmail_to_active'] = 'Aktive Mitglieder ({1})';

//All admins/mods ({1})
// context = admins and moderators
$lang['mmail_to_admins_mods'] = 'Alle Administratoren/Moderatoren ({1})';

//Super-admins only
$lang['mmail_to_superadmins'] = 'Nur Super Administratoren';

//Flags: FLAG DESCRIPTION
// context = "Can Draw", or "Drawing ability", etc.
$lang['mmail_to_draw_flag']   = 'Rechte: Zeichnen';
$lang['mmail_to_upload_flag'] = 'Rechte: Hochladen';
$lang['mmail_to_adult_flag']  = 'Rechte: Gesperrte Bilder betrachten';
$lang['mmail_to_immune_flag'] = 'Rechte: Immunität';

//<a href="{1}">Online</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_online'] = '<a href="{1}">Online</a> ({2})';

//<a href="{1}">Chat</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_chat'] = '<a href="{1}">Chat</a> ({2})';

//<strong>Rules</strong>
// context = normally in bold text
$lang['header_rules'] = '<strong>Regeln</strong>';

//<strong>Draw</strong>
// context = normally in bold text
$lang['header_draw'] = '<strong>Zeichnen</strong>';

//<a href="{1}">Mailbox</a> ({2})
// context = Used as link. {2} = count of messages
$lang['header_mailbox'] = '<a href="{1}">Mailbox</a> ({2})';

//Online
// context = Used as label. No HTML link. {1} = count of people (if desired)
$lang['chat_online'] = 'Online';

//{1} {1?member:members} match your search.
$lang['match_search'] = '{1} {1?Mitglied:Mitglieder} treffen auf Ihre Suchanfrage zu.';

//{1} {1?member:members}, {2} active within {3} days.
$lang['member_stats'] = '{1} {1?Mitglied:Mitglieder}, {2} aktiv innerhalb von {3} Tagen.';

//(None)
// context = placeholder if no avatar available
$lang['avatar_none'] = '(Nicht verfügbar)';

//No rank
// or "None". context = administrator rank
$lang['rank_none'] = 'Kein Rang';

//No Thumbnails
$lang['cp_no_thumbs'] = 'Keine Thumbnails';

//No pictures
$lang['no_pictures'] = 'Keine Bilder';

//{1} (last login)
// {1} = IP address.
$lang['ip_by_login'] = '{1} (letzte Anmeldung)';

//{1} (last comment)
$lang['ip_by_comment'] = '{1} (letztes Kommentar)';

//{1} (last picture)
$lang['ip_by_picture'] = '{1} (letztes Bild)';

//(None)
// context = placeholder if no url to web site
$lang['url_none'] = '(Nicht verfügbar)';

//(Web site)
// context = placeholder if there is a url, but no title (no space to print whole url)
$lang['url_substitute'] = '(Website)';

//(Default)
// context = placeholder if default template is chosen
$lang['template_default'] = '(Voreinstellung)';

//(Default)
// context = placeholder if default language is chosen
$lang['language_default'] = '(Voreinstellung)';

//Default
$lang['palette_default'] = 'Voreinstellung';

//No Title
$lang['no_pic_title'] = 'Kein Titel';

//Send E-mails
$lang['install_send_emails'] = 'E-Mails senden';

//Adjusts how many e-mails are sent by your server.  Default is &ldquo;Yes&rdquo;, and is highly recommended.
$lang['adjust_emails_sent_sub1'] = 'Passen Sie an, wie viele E-Mails von Ihrem Server versendet werden. Voreingestellt ist &bdquo;JA&ldquo; und wird auch empfohlen.';

//Minimal
// or "Minimum"
$lang['cpanel_emails_minimal'] = 'Minimum';

//&ldquo;Minimal&rdquo; will reduce e-mails by approximately {1}.  Choose &ldquo;No&rdquo; if your server cannot send e-mail.
// {1} = percentage or fraction
$lang['adjust_emails_sent_sub2'] = '&bdquo;Minimum&ldquo; wird die E-Mails um etwa {1} reduzieren. Wählen Sie &bdquo;NEIN&ldquo;, wenn Ihr Server keine E-Mails versenden kann.';

//No (recommended)
$lang['chat_no_set_reccom'] = 'NEIN (empfohlen)';

//Display - Mailbox
$lang['install_mailbox'] = 'Anzeigen - Mailbox';

//Sorry, the BBS e-mailer isn't working. Ask an admin to reset your password.
$lang['no_email_pass_reset'] = "Entschuldigung, der BBS-E-Mailer funktioniert nicht. Fragen Sie einen Admin, ob dieser Ihr Passwort zurücksetzen kann.";

//User deleted, but e-mailer isn't working. Notify user manually at {1}.
$lang['no_email_kill_notify'] = "Benutzer gelöscht, aber der E-Mailer funktioniert nicht. Benachrichtigen Sie den Benutzer manuell via {1}.";

//<strong>Maintenance</strong>
// context = Highly visible notification if board is in maintenacne mode. Disables "Logout"
$lang['header_maint'] = '<strong>Wartung/Maintenance</strong>';

//The oekaki is down for maintenance
// context = <h2> on plain page
$lang['boot_down_maint'] = 'Das Oekaki ist zurzeit wegen Wartungsarbeiten offline.';

//&ldquo;{1}&rdquo; should be back online shortly.  Send all quesions to {2}.
// {1} = oekaki title, {2} = admin e-mail
$lang['boot_maint_exp'] = '&bdquo;{1}&ldquo; sollte in Kürze wieder verfügbar sein. Schicken Sie alle Fragen an {2}.';

//Member name already exists!
$lang['func_reg_name_exists'] = 'Benutzername existiert bereits!';

//You cannot access flag modification
$lang['func_no_flag_access'] = 'Sie können keine Rechte bearbeiten.';

//Account updated, but member could not be e-mailed.
$lang['func_update_no_mail'] = 'Account bearbeitet; dem Benutzer konnte keine E-Mail gesendet werden.';

//Account rejected, but could not be e-mailed. Notify applicant manually at {1}'
// {1} = e-mail address
$lang['func_reject_no_mail'] = 'Account abgelehnt; dem Benutzer konnte keine E-Mail gesendet werden. Benachrichtigen Sie diesen unter {1}';

//Your age declaration could not be accepted.
$lang['func_bad_age'] = 'Ihre Altersangabe wurde nicht akzeptiert.';

//Image too large!  Size limit is {1} bytes.
$lang['err_imagelar_bytes'] = 'Bild zu groß! Das Dateilimit liegt bei {1} Bytes.';

//No picture data was received. Try again.
$lang['func_no_img_data'] = 'Bilddaten konnten nicht empfangen werden. Versuchen Sie es erneut.';

//An error occured while uploading. Try again.
$lang['func_up_err'] = 'Ein Fehler trat während des Hochladens auf. Versuchen Sie es erneut.';



/* whosonline.php */

// context = nouns, not verbs
$lang['o_unknown']     = 'Unbekannt';
$lang['o_addusr']      = 'Schwebende Benutzer';
$lang['o_banlist']     = 'Bannliste';
$lang['o_chatbox']     = 'Chat';
$lang['o_chibipaint']  = 'Chibi Paint';
$lang['o_chngpass']    = 'Ändert Passwort';
$lang['o_comment']     = 'Kommentiert';
$lang['o_cpanel']      = 'Control Panel';
$lang['o_delcomments'] = 'Löscht Kommentare';
$lang['o_delpics']     = 'Löscht Bilder';
$lang['o_delusr']      = 'Löscht Benutzer';
$lang['o_draw']        = 'Zeichenbildschirm';
$lang['o_edit_avatar'] = 'Bearbeitet Avatar';
$lang['o_editcomm']    = 'Bearbeitet Kommentar';
$lang['o_editnotice']  = 'Bearbeitet Ankündigung/Banner';
$lang['o_editpic']     = 'Bearbeitet Bilder';
$lang['o_editprofile'] = 'Bearbeitet Profil';
$lang['o_editrules']   = 'Bearbeitet Regeln';
$lang['o_faq']         = 'FAQ';
$lang['o_index']       = 'Betrachtet';
$lang['o_index_match'] = 'Betrachtet (sortiert nach Zeichner)';
$lang['o_lcommentdel'] = 'Löscht Kommentar';
$lang['o_log']         = 'Log';
$lang['o_lostpass']    = 'Passwort-Wiederherstellung';
$lang['o_mail']        = 'Mailbox';
$lang['o_massmail']    = 'Rundmail';
$lang['o_memberlist']  = 'Mitgliederliste';
$lang['o_modflags']    = 'Zugriffsrechte der Mitglieder';
$lang['o_newpass']     = 'Ändert Passwort';
$lang['o_niftyusage']  = 'Benutzt Niftytoo';
$lang['o_notebbs']     = 'NoteBBS';
$lang['o_oekakibbs']   = 'OekakiBBS';
$lang['o_paintbbs']    = 'PaintBBS';
$lang['o_profile']     = 'Betrachtet ein Profil';
$lang['o_recover']     = 'Bildwiederherstellung';
$lang['o_retouch']     = 'Überarbeitet ein Bild';
$lang['o_shibbs']      = 'ShiPainter';
$lang['o_showrules']   = 'Regeln';
$lang['o_sqltest']     = 'Diagnosen';
$lang['o_testinfo']    = 'Diagnosen';
$lang['o_upload']      = 'Hochladen';
$lang['o_viewani']     = 'Betrachtet eine Animation';
$lang['o_whosonline']  = 'Wer ist Online';

/* END whosonline.php */



//Submit for review
$lang['submit_review'] = 'Zur Durchsicht einreichen';

//<a href="http://www.NineChime.com/products/" title="Get your own free BBS!">{1}</a> by {2} / Based on <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> by <a href="http://suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
// {1} = "Wacintaki" + link
// {2} = "Waccoon" (may change)
$lang['f_bbs_credits'] = '<a href="http://www.NineChime.com/products/" title="Holen Sie Ihr eigenes kostenloses BBS!">{1}</a> von {2} / Basierend auf <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> von <a href="http://suteki.nu">RanmaGuy</a> und <a href="http://www.cellosoft.com">Marcello</a>';

//PaintBBS and Shi-Painter by <a href="http://shichan.jp/">Shi-chan</a> / ChibiPaint by <a href="http://www.chibipaint.com">Mark Schefer</a>
$lang['f_applet_credits'] = 'PaintBBS und Shi-Painter von <a href="http://shichan.jp">Shi-chan</a> / ChibiPaint von <a href="http://www.chibipaint.com">Mark Schefer</a>';

//Administrator Account
// context = default comment for admin account
$lang['install_admin_account'] = 'Administratoren-Account';

//If you are submitting a picture or just closing the window, click OK."
// context = JavaScript alert.  Browser/version specific, and may be troublesome.
$lang['js_noclose'] = 'Wenn Sie ein Bild abschicken oder das Fenster schließen wollen, klicken Sie auf OK.';

//Comment
// context = verb form; label for making posts on pictures
$lang['verb_comment'] = 'Kommentar';

//&hellip;
// context = Placeholder if a comment is empty
$lang['no_comment'] = '&hellip;';

//OekakiPoteto 5.x by <a href="http://www.suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
$lang['install_byline'] = 'OekakiPoteto 5.x von <a href="http://www.suteki.nu">RanmaGuy</a> und <a href="http://www.cellosoft.com">Marcello</a>';

//Wacintaki 1.x modifications by <a href="http://www.NineChime.com/products/">Waccoon</a>
// {1} = "Waccoon" (may change)
$lang['install_byline2'] = 'Wacintaki 1.x Modifikationen von <a href="http://www.NineChime.com/products/">{1}</a>';

//Wacintaki Oekaki: draw pictures online
// context = HTML head->meta name
$lang['meta_desc'] = 'Wacintaki Oekaki: Zeichnen Sie Ihre Bilder online';

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
$lang['file_o_warn'] = '&bdquo;{1}&ldquo; kann nicht geöffnet werden.';



/* log.php */

// The log is a diagnostic tool.  Retain formatting and colons.
// Some filenames (lowercase, without '.php') are not translatable.

// Generic warning
$lang['l_'.WLOG_MISC] = 'Sonstiges';

// Generic error/failure
$lang['l_'.WLOG_FAIL] = 'Fehler';

// SQL error
$lang['l_'.WLOG_SQL_FAIL] = 'SQL Fehler';

// Regular maintenance
$lang['l_'.WLOG_MAINT] = 'Wartung - Maintenance';

// Input from client, hacking
$lang['l_'.WLOG_SECURITY] = 'Sicherheit';

// Updates and events
$lang['l_'.WLOG_BANNER] = 'Banner';
$lang['l_'.WLOG_RULES]  = 'Regeln';
$lang['l_'.WLOG_NOTICE] = 'Ankündigung';
$lang['l_'.WLOG_CPANEL] = 'Kontrollzentrum';
$lang['l_'.WLOG_THUMB_OVERRIDE] = 'Thumbnail bearbeitet';
$lang['l_'.WLOG_THUMB_REBUILD]  = 'Thumbnail Neuaufgebaut';
$lang['l_'.WLOG_MASS_MAIL]    = 'Rundmail';
$lang['l_'.WLOG_BAN]          = 'Bann';
$lang['l_'.WLOG_DELETE_USER]  = 'Benutzer gelöscht';
$lang['l_'.WLOG_REG]          = 'Registration';
$lang['l_'.WLOG_APPROVE]      = 'Akzeptiert';
$lang['l_'.WLOG_EDIT_PROFILE] = 'Profil bearbeitet';
$lang['l_'.WLOG_FLAGS]        = 'Rechte geändert';
$lang['l_'.WLOG_PASS_RECOVER] = 'Passwort wiederhergestellt';
$lang['l_'.WLOG_PASS_RESET]   = 'Passwort zurückgesetzt';
$lang['l_'.WLOG_ARCHIVE]      = 'Archiv';
$lang['l_'.WLOG_BUMP]         = 'Bump';
$lang['l_'.WLOG_RECOVER]      = 'Wiederherstellung';
$lang['l_'.WLOG_DELETE]       = 'Löschen';
$lang['l_'.WLOG_LOCK_THREAD]  = 'Thread sperren';
$lang['l_'.WLOG_ADULT]        = 'Erwachsen';
$lang['l_'.WLOG_ADMIN_WIP]    = 'WIP (von Admin)';
$lang['l_'.WLOG_EDIT_PIC]     = 'Bild bearbeitet';
$lang['l_'.WLOG_EDIT_COMM]    = 'Kommentar bearbeitet';

//cpanel: Updated
$lang['l_c_update'] = 'Kontrollzentrum: Aktualisiert';

//No RAW POST data
// context = "RAW POST" is a programming term for HTTP data
$lang['l_no_post'] = 'Keine RAW POST-Daten';

//Cannot allocate new PIC_ID
$lang['l_no_picid'] = 'Kann keine neue PIC_ID zuweisen';

//Cannot insert image #{1}
$lang['l_app_no_insert'] = 'Kann Bild #{1} nicht einfügen';

//Cannot save image #{1}
$lang['l_app_no_save'] = 'Kann Bild #{1} nicht speichern';

//paintsave: Bad upload for #{1}, cannot make thumbnail
$lang['l_bad_upload'] = 'paintsave: Fehlerhafter Upload für #{1}, es kann kein Thumbnail erstellt werden';

//paintsave: Cannot make &ldquot&rdquo; thumbnail for image #{1}
$lang['l_no_t'] = 'paintsave: Kann kein &bdquo;&ldquo; Thumbnail für Bild #{1} erstellen';

//paintsave: Cannot make &ldquo;r&rdquo; thumbnail for image #{1}
$lang['l_no_r'] = 'paintsave: Kann kein &bdquo;r&ldquo; Thumbnail für Bild #{1} erstellen';

//paintsave: Bad datatype for image #{1} (saved as &ldquo;dump.png&rdquo;)
$lang['l_no_type'] = 'paintsave: fehlerhafter Dateityp für Bild #{1} (wurde gespeichert als &bdquo;dump.png&ldquo;)';

//paintsave: Corrupt image dimentions for #{1}
$lang['l_no_dim'] = 'paintsave: fehlerhafte Bildmaße für #{1}';

//paintsave: cannot write image &ldquo;{1}&rdquo;
$lang['l_no_open'] = 'paintsave: kann Bild &bdquo;{1}&ldquo; nicht schreiben';

//Picture: #{1}
// context = "Picture #{1} affected"
$lang['l_mod_pic'] = 'Bild: #{1}';

//Comment: #{1}
// context = "Comment #{1} affected"
$lang['l_mod_comm'] = 'Kommentar: #{1}';

//WIP: #{1}
// context = "WIP or Safety save #{1} affected"
$lang['l_mod_wip'] = 'WIP: #{1}';

//Active
// context: Active members.  Displayed under "Peer (affected)"
$lang['type_active'] = 'Aktiv'; 

//Sent to:
// context = Password recovery.  {1} = E-mail address.
$lang['l_sent_to'] = 'Gesendet an: {1}';

//Reset by: Admin
// context = Password reset
$lang['l_reset_admin'] = 'Zurückgesetzt von: Admin';

//Reset by: Member
// context = Password reset
$lang['l_reset_mem'] = 'Zurückgesetzt von: Benutzer';

//Reason sent: Yes or No
// context = Reason for being deleted.  Yes or No only.
$lang['l_reason_yes'] = 'Begründung gesendet: Ja';
$lang['l_reason_no'] = 'Begründung gesendet: Nein';

//Accepted with the following flags: {1}
// {1} ~ 'GDU' or some other combination of letters
$lang['l_accept_f'] = 'Akzeptiert, mit folgenden Rechten: {1}';

//Profile: Updated
$lang['l_prof_up'] = 'Profil: Aktualisiert';

//Banlist: Updated
$lang['l_ban_up'] = 'Bannliste: Aktualisiert';

//Banner and notice: Updated
$lang['l_banner_notice_up'] = 'Banner und Ankündigung: Aktualisiert';

//Rules: Updated
$lang['l_rules_up'] = 'Regeln: Aktualisiert';

//Upload: Cannot insert image &ldquo;{1}&rdquo;
$lang['l_f_no_insert'] = 'Upload: Kann Bild &bdquo;{1}&ldquo; nicht einfügen';

//Upload: Cannot save image &ldquo;{1}&rdquo;
$lang['l_f_no_save'] = 'Upload: Kann Bild &bdquo;{1}&ldquo; nicht speichern';

//Upload: Cannot save image #{1}
$lang['l_f_no_anim'] = 'Upload: Kann Bild #{1} nicht speichern';

//Retouch: #{1}
// context = Bump by retouching picture.
$lang['l_f_bump_retouch'] = 'Überarbeitet: #{1}';

//Locked: #{1}
// context = Thread #{1} locked or unlocked
$lang['l_f_lock']   = 'Gesperrt: #{1}';
$lang['l_f_unlock'] = 'Entsperrt: #{1}';

//Marked as adult: #{1}
// context = [Un]marked #{1} as an adults-only picture
$lang['l_f_adult']   = 'Als Bild für Erwachsene markiert: #{1}';
$lang['l_f_unadult'] = 'Markierung als Bild für Erwachsene entfernt: #{1}';

/* END log.php */



//Member &ldquo;{1}&rdquo; could not be found.
$lang['mem_not_found'] = 'Benutzer &bdquo;{1}&ldquo; konnte nicht gefunden werden.';

//Profile for &ldquo;{1}&rdquo; not retrievable!  Check the log.
$lang['prof_not_ret'] = 'Profil für &bdquo;{1}&ldquo; konnte nicht abgerufen werden! Überprüfen Sie den Log.';

//Cannot allocate new PIC_ID for upload.
$lang['f_no_picid'] = 'Kann keine neue PIC_ID für den Upload zuweisen.';

//You must be a moderator to lock or unlock threads.
$lang['functions_err22'] = 'Sie müssen Moderator sein, um Threads (Bilder) sperren und entsperren zu können.';

//Please run the <a href="{1}">updater</a>.
$lang['please_update'] = 'Bitte starten Sie den <a href="{1}">Updater</a>.';

//Current version: {1}
$lang['please_update_cur'] = 'Aktuelle Version: {1}';

//New version: {1}
// context = version after update has completed
$lang['please_update_new'] = 'Neue Version: {1}';



/* update.php */

//Starting update from {1} to {2}.
// {1} = from name+version, {2} = to name+version
$lang['up_from_to'] = 'Startet Update von {1} zu {2}.';

//Finished update to Wacintaki {1}.
// {1} = to version number
$lang['up_fin_to'] = 'Update zu Wacintaki {1} abgeschlossen.';

//Update to Wacintaki {1} failed.
// {1} = to version number
$lang['up_to_fail'] = 'Update zu Wacintaki {1} ist fehlgeschlagen.';

//Verifictaion of Wacintaki {1} failed.
// {1} = to version number
$lang['up_ver_fail'] = 'Bestätigung, dass Wacintaki {1} fehlgeschlagen ist.';

//STOP: Cannot read file &ldquo;{1}&drquo; for database connection.
// {1} = filename
$lang['up_cant_read_for_db'] = 'STOPP: Kann Datei &ldquo;{1}&drquo; nicht lesen, welche für die Datenbankverbindung benötigt wird.';

//STOP: Cannot write file &ldquo;{1}&rdquo;
// {1} = filename
$lang['up_cant_write_file'] = 'STOPP: Kann Datei &ldquo;{1}&rdquo; nicht schreiben.';

//STOP: &ldquo;{1}&rdquo; file is not writable.
// {1} = filename
$lang['up_file_locked'] = 'STOPP: &bdquo;{1}&ldquo;-Datei ist nicht schreibbar.';

//WARNING: missing image &ldquo;{1}&rdquo; for post {2}.
// {1} = picture filename, {2} = number (ID_2)
$lang['up_missing_img'] = 'WARNUNG: Bild &bdquo;{1}&ldquo; kann nicht unter {2} gespeichert werden, da nicht vorhanden ist.';

//WARNING:  Folder &ldquo;{1}&rdquo; is not writable.  CHMOD the folder to 775 or 777 after the updater has finished.
// {1} = folder name
$lang['up_folder_locked'] = 'WARNUNG:  Verzeichnis &bdquo;{1}&ldquo; ist nicht schreibbar. Ändern Sie die Dateiberechtigungen (CHMOD) des Verzeichnisses zu 775 oder 777 nachdem das Update abgeschlossen ist.';

//STOP: Unable to add admin ranks to database (SQL: {1})
// {1} db_error()
$lang['up_no_add_rank'] = 'STOPP: Konnte die Ränge der Administratoren der Datenbank nicht hinzufügen (SQL: {1})';

//STOP:  Could not set admin rank for {1} (SQL: {2})
// {1} = username
// {2} = db_error()
$lang['up_no_set_rank'] = 'STOPP: Konnte den Admin-Rang für {1} festlegen (SQL: {2})';

//STOP: Could not create folder &ldquo;{1}&rdquo;.
// {1} = filename
$lang['up_cant_make_folder'] = 'STOPP: Konnte das Verzeichnis &bdquo;{1}&ldquo; nicht erstellen.';

//STOP: Could not update piccount (current picture number) for new sorting system (SQL: {1})
// {1} = db_error()
$lang['up_no_piccount'] = 'STOPP: Konnte die Bilderanzahl (momentane Anzahl der Bilder auf dem Board) für eine Neusortierung nicht aktualisieren (SQL: {1})';

//STOP: Wax Poteto database not at required version (1.3.0).  Run the Wax Poteto 5.6.x updater first.
$lang['up_wac_no_130'] = 'STOPP: Wax Poteto-Datenbank ist nicht auf der dafür erforderlichen Version (1.3.0).  Starten Sie zunächst den Wax Poteto 5.6.x Updater.';

//STOP: Could not verify database version marker (SQL: {1})
// {1} = db_error()
$lang['up_no_set_db_utf'] = 'STOPP: Konnte die Datenbankversion nicht nachprüfen (SQL: {1})';

//NOTE: Remember to copy your resource files (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) into the Wacintaki &ldquo;resource&rdquo; folder and CHMOD them so they are writable.
$lang['up_move_res'] = 'ANMERKUNG: Denken Sie daran, dass die Dateien aus dem Verzeichnis &bdquo;resource&ldquo; (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) in diesem Verzeichnis liegen und schreibbar sein müssen (ggf. CHMODs anpassen).';

//Wacintaki 1.5.6 requires significant changes to the database to support international letters.
$lang['up_change_sum'] = 'Bei Wacintaki 1.5.6 werden größere Änderungen in der Datenbank vorgenommen, damit auch Sonderzeichen (internationale Zeichen) unterstützt werden können.';

//Click here to start the database conversion.
$lang['up_click_start_conv'] = 'Klicken Sie hier, um die Datenbank-Konvertierung zu starten.';

//STOP: Cannot read UTF-8 marker from database.
$lang['up_no_dbutf_marker'] = 'STOPP: Die Kennzeichnung des UTF-8 der Datenbank kann nicht gelesen werden.';

//Cleaned up {1} orphaned files.
$lang['up_cleaned_sum'] = 'Aufgeräumt: {1} verwaiste {1?Datei:Dateien}.';

//Unsupported update type &ldquo;From {1} to {2}&rdquo;.
// {1} from version, {2} = to version
$lang['up_no_up_num'] = 'Nicht unterstützter Updatetyp &bdquo;Von {1} nach {2}&ldquo;.';

//Board config version:  &ldquo;{1}&rdquo;,  database version:  &ldquo;{2}&rdquo;
// {1}+{2} = numbers
$lang['up_no_up_sum'] = 'Boardkonfigurationsversion:  &bdquo;{1}&ldquo;,  Datenbankversion:  &bdquo;{2}&ldquo;';

//Update cannot continue.
$lang['up_no_cont'] = 'Update kann nicht fortgesetzt werden.';

//If problems persist, visit the <a href="{1}">NineChime.com Forum</a>.
// {1} = url
$lang['up_nc_short'] = 'Sollten die Probleme weiterhin auftreten, besuchen Sie das <a href="{1}">NineChime.com Forum</a>.';

//Wacintaki Update {1}
// {1} = number
$lang['up_header_title'] = 'Wacintaki Update {1}';

//If you have multiple boards, make sure each board is at the current version.
$lang['up_mult_warn'] = 'Sollten Sie mehrere Boards besitzen, gehen Sie sicher, dass jedes Board die aktuelle (und selbe) Version besitzt.';

//Click the button below to finalize the update.  This will delete the updater files and prevent security problems.
$lang['up_click_final'] = 'Klicken Sie auf den darunterstehenden Button, um das Update abzuschließen. Dieser Vorgang wird die Updater-Dateien löschen und vor Sicherheitsprobleme schützen.';

//Secure updater and go to the BBS
// context = clickable button
$lang['up_secure_button'] = 'Updater entfernen und zurück zum BBS';

//Some warnings were returned during the update.  Please note these messages and run the updater again to ensure everything is set properly.  You may run the updater multiple times if needed.
$lang['up_warn_rerun'] = 'Einige Warnungen sind während des Updates aufgetreten. Bitte beachten Sie die Hinweise und starten den Updater noch einmal, um sicher zu gehen, dass alles richtig eingestellt wurde. Sie können den Updater, wenn nötig, mehrmals durchlaufen lassen.';

//Errors occured during the update!  Check your server and database permissions and try again.  The update will not function properly until all errors are resolved.
$lang['up_stop_sum'] = 'Fehler sind während des Updates aufgetreten! Überprüfen Sie die Server- und Datenbank-Zugriffsrechte und versuchen Sie es erneut. Dieser Updater wird nicht richtig funktionieren, bis alle Fehler behoben wurden.';

//NOTE: Make sure you\'ve deleted your old OekakiPoteto v5.x template and language files before running this updater.  Your old OekakiPoteto templates and language files will not work with Wacintaki.
$lang['up_no_op_tpl'] = 'ANMERKUNG: Gehen Sie sicher, dass Sie alle OekakiPoteto v5.x Templates und Sprachpakete entfernt haben, bevor Sie den Updater starten. Ihre alten OekakiPoteto Templates und Sprachpakete sind nicht kompatibel zum Wacintaki.';

//Click Next to start the update.
$lang['up_next_start'] = 'Klicken Sie auf Weiter, um das Update zu starten.';

//Next
// context = clickable button
$lang['up_word_next'] = 'Weiter';

//{1} detected.
// {1} = version
$lang['up_v_detected'] = '{1} erkannt.';

//You appear to be running the latest version of Wacintaki already.  You may proceed to verify that the last update completed correctly.
$lang['up_latest_ver'] = 'Es scheint, als hätten Sie bereits die neueste Version des Wacintaki installiert. Sie können fortfahren um nachzuprüfen, ob das letzte Update erfolgreich abgeschlossen wurde.';

//Click Next to verify the update.
$lang['up_next_ver'] = 'Klicken Sie auf Weiter, um das Update zu überprüfen.';

//Unknown version.
$lang['up_unknown_v'] = 'Unbekannte Version.';

//Config: {1}, Database: {2}
// {1}+{2} = numbers
$lang['up_unknown_v_sum'] = 'Konfiguration: {1}, Datenbank: {2}';

//This updater only supports Wacintaki versions less than or equal to {1}.
// {1} = number
$lang['up_v_spread_sum'] = 'Dieser Updater unterstützt lediglich Wacintaki Versionen niedriger oder gleich der Version {1}.';

/* END update.php */



/* update_rc.php */

//Database has already been updated to UTF-8.
$lang['upr_already_utf'] = 'Datenbank wurde bereits nach UTF-8 aktualisiert.';

//Click here to run the updater.
$lang['upr_click_run'] = 'Klicken Sie hier, um den Updater zu starten.';

//PHP extension &ldquo;iconv&rdquo; not available.  Cannot recode from Big5 to UTF-8!
// context = iconv is all lower case (shell program).
$lang['upr_iconv_mia'] = 'PHP Erweiterungen &bdquo;iconv&ldquo; nicht verfügbar.  Kann nicht von Big5 zu UTF-8 konvertieren!';

//Please visit the <a href="{1}">NineChime Forum</a> for help.
// {1} = url
$lang['upr_nc_shortest'] = 'Bitte besuchen Sie das <a href="{1}">NineChime Forum</a>, um weitere Hilfe zu erhalten.';

//This tool will convert an existing Wacintaki database to support international letters and text (such as &laquo;&ntilde;&raquo; and &bdquo;&#223;&ldquo;).
$lang['upr_conv_w_8bit'] = 'Diese Funktion wird eine bereits vorhandene Wacintaki Datenbank konvertieren damit diese Sonderzeichen und Texte (wie &laquo;&ntilde;&raquo; und &bdquo;&#223;&ldquo;) unterstützen kann.';

//If you have multiple Wacintaki boards installed on your web site, you only need to run this tool once, but you will still need to run the updater on each board.
// context = update_rc.php runs only once, update.php must run on each board.
$lang['upr_xself_mult_warn'] = 'Sollten Sie mehrere Wacintaki Boards auf Ihrer Website besitzen, brauchen Sie dieses Tool nur einmal zu starten. Jedoch müssen Sie weiterhin den normalen Updater auf jeden Board ausführen.';

//Using this tool more than once will not cause any damage.
$lang['upr_no_damage'] = 'Es wird keinen Schaden entstehen, sollten Sie dieses Tool mehr als einmal ausführen.';

//NOTE:  {1} encoding detected.  Conversion will be from {1} to UTF-8.
// {1} = iconv charset ("iso-8859-1", "big5", "utf-8", etc.)
$lang['upr_char_det_conv'] = 'Anmerkung:  {1} Codierung entdeckt. Konvertierung von {1} zu UTF-8.';

//If you used international letters in your password, you will need to recover your password after the update.
$lang['upr_utf_rec_pass'] = 'Haben Sie Sonderzeichen in Ihrem Passwort benutzt, so müssen Sie nach dem Update ein neues Passwort festlegen (z.B. über das Profil oder &bdquo;Passwort vergessen&ldquo;).';

//Click here to begin step {1} of {2}.
// {1}+{2} = numbers
$lang['upr_click_steps'] = 'Klicken Sie hier, um Schritt {1} von {2} zu starten.';

//If you have problems converting the database, try visiting the <a href="{1}">NineChime Forum</a>, or you may <a href="{2}">bypass the conversion.</a>  If you bypass the conversion, existing comments with international letters will be corrupt, but new comments will post fine.
// {1} = url, {2} = local url
$lang['upr_nc_visit_bypass'] = 'Sollten Sie Probleme bei der Konvertierung der Datenbank haben, versuchen Sie das <a href="{1}">NineChime Forum</a> zu besuchen, oder <a href="{2}">überspringen Sie die Konvertierung.</a> Wenn Sie die Konvertierung überspringen, werden alle bereits vorhandenen Kommentare mit Sonderzeichen beschädigt. Neue Kommentare werden aber anschließend richtig dargestellt.';

//STOP: Cannot create one or more temp files for database conversion.  Check the permissions of the main oekaki folder.
$lang['upr_no_make_temp'] = 'STOPP: Konnte keine neuen temporäre Dateien für die Datenbank-Konvertierung erstellen. Überprüfen Sie die Zugriffsrechte des Hauptverzeichnisses Ihres Oekakis.';

//Done!  Database has been updated to UTF-8.
$lang['upr_done_up'] = 'Abgeschlossen!  Datenbank wurde nach UTF-8 aktualisiert.';

//Found {1} tables in the database.
$lang['upr_found_tbls'] = 'Fand {1} Tabellen in der Datenbank.';

//{1} {1?row:rows} need to be converted.
$lang['upr_found_rows'] = '{1} {1?Reihe:Reihen} müssen konvertiert werden.';

//<strong>Please wait...</strong> it may take a minute for the next page to show.
$lang['upr_plz_wait'] = '<strong>Bitte warten...</strong> es kann bis zu einer Minute dauern, bis die nächste Seite angezeigt wird.';

//STOP: Double-click or unexpected reload detected.  Please wait another {1} seconds.
$lang['upr_dbl_click'] = 'STOPP: Doppelklick oder unerwartetes Neuladen entdeckt.  Bitte warten Sie {1} Sekunden.';

//Building resource files.  Please wait...
$lang['upr_build_res_wait'] = 'Erstellt Ressourcen-Dateien. Bitte warten...';

//Step {1} of database update finished.  Ready to start step {2}.
$lang['upr_step_ready_num'] = 'Schritt {1} des Datenbank-Updates abgeschlossen. Bereit Schritt {2} zu starten.';

//If there are any errors printed above, it\'s strongly recommended that you <a href="{1}">visit NineChime forum</a> for help.  The oekaki should still function properly if all members use only English letters.
// {1} = url
$lang['upr_if_err_nc'] = 'Sollten hier Fehler angezeigt werden, wird ausdrücklich empfohlen, dass Sie das <a href="{1}">NineChime Forum</a> besuchen, um weitere Hilfe zu erhalten. Das Oekaki sollte weiterhin funktionieren, wenn alle Mitglieder nur englische Buchstaben und Zeichen verwenden.';

//Wacintaki UTF-8 Update
$lang['upr_header_title'] = 'Wacintaki UTF-8 Update';

//TIMEOUT: database partially exported.  This is normal if your database is very large.
$lang['upr_time_partial'] = 'ZEITÜBERSCHREITUNG: Datenbank wurde nur teilweise übertragen. Dies ist normal, wenn Ihre Datenbank sehr groß ist.';

//{1} {1?row:rows} updated before timeout.
$lang['upr_rows_partial'] = '{1} {1?Reihe:Reihen} vor der Zeitüberschreitung aktualisiert.';

//Click here to resume.
$lang['upr_click_resume'] = 'Klicken Sie hier, um es fortzusetzen.';

//TIMEOUT_IMPORT: database partially imported.  This is normal if your database is very large.
$lang['upr_time_norm'] = 'ZEITÜBERSCHREITUNG_IMPORT: Datenbank wurde nur teilweise importiert. Dies ist normal, wenn Ihre Datenbank sehr groß ist.';

//STOP:{1} Cannot get tables: (SQL: {2})
// {1} = placeholder, so maintain spacing.  {2} = db_error()
$lang['upr_sql_bad_tbls'] = 'STOPP:{1} Kann Tabellen nicht erhalten: (SQL: {2})';

//STOP:{1} No SQL tables found!
// {1} = placeholder, so maintain spacing.
$lang['upr_sql_no_tbls'] = 'STOPP:{1} Keine SQL-Tabellen gefunden!';

//STOP: Error reading column &ldquo;{1}&rdquo;: (SQL: {2})
// {1} = column name, {2} = db_error()
$lang['upr_bad_col'] = 'STOPP: Fehler beim Lesen der Spalte &bdquo;{1}&rldquo;: (SQL: {2})';

//STOP: No SQL columns found in table &ldquo;{1}&rdquo;
// {1} = table name
$lang['upr_no_cols'] = 'STOPP: Keine SQL-Spalten in der Tabelle &bdquo;{1}&ldquo; gefunden.';

//{1} {1?row:rows} collected.  Total time for export: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_exp_time'] = '{1} {1?Reihe:Reihen} gesammelt. Gesamte Zeit für den Export: {2} {2?Sekunde:Sekunden}.';

//{1} {1?row:rows} updated.  Total time for import: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_imp_time'] = '{1} {1?Reihe:Reihen} aktualisiert. Gesamte Zeit für den Import: {2} {2?Sekunde:Sekunden}.';

//STOP: set_db_utf8_misc_marker({1}): Cannot insert db_utf8 marker (SQL: {2})
// {1} = debug argument (ignore), {2} = db_error()
$lang['upr_utf_no_ins'] = 'STOPP: set_db_utf8_misc_marker({1}): Kann die db_utf8-Kennzeichnung nicht einfügen (SQL: {2})';

/* END update_rc.php */



//Uploaded
$lang['word_uploaded'] = 'Hochgeladen';

//(Uploaded by <strong>{1}</strong>)
$lang['uploaded_by_admin'] = '(Hochgeladen von <strong>{1}</strong>)';

/* End Version 1.5.6 */



/* REMOVED */

//Color
$lang['word_color'] = "Farbe";

//Colorcode
$lang['word_colorcode'] = "Farbcode";

/* END */



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
<?php // ÜTF-8
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastea-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.14x - Last modified 2014-11-06 (x:2015-08-23)

v1.0.6 english and chinese additions by Kevin (kevinant@gmail.com)

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
$lang['cfg_language'] = "繁體中文";

// English Language name (native encoding, capitalized)
$lang['cfg_lang_eng'] = "Chinese Traditional";

// Name of translator(s)
$lang['cfg_translator'] = "<a href=\"mailto:kevinant@gmail.com\">Kevin</a>";

//$lang['cfg_language'].' translation by: '.$lang['cfg_translator'];
// context = Variables not needed. Change around order as needed.
$lang['footer_translation'] = $lang['cfg_language'].' translation by: '.$lang['cfg_translator'];

// Comments (native encoding)
$lang['cfg_comments'] = "中文版的 Wacintaki";

// Zero plural form.  0=singular, 1=plural
// Multiple plural forms need to be considered in next language API
//$lang['cfg_zero_plural'] = 1;

// HTML charset ("Content-type")
$charset = "utf-8";

// HTML language name ("Content-language" or "lang" tags)
// Waccoon: Should this be zh-tw?
$metatag_language = "zh-tw";

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
$lang['install_title'] = "安裝Wacintaki MySQL繪圖流言版系統";

//MySQL Information
$lang['install_information'] = "MySQL資料庫 資料";

//If you do not know if your server has MySQL or how to access your MySQL account, e-mail your tech. support and ask for the following information: hostname, database name, username, and password. Without this information, you will be unable to install OP. If you need to remove the databases, look at the bottom of the page for a link to remove them. If you haven't read the readme.txt, DO IT NOW or your installation will fail! You must make sure you have the proper files and directories CHMODed before you continue.
// UPDATE: Remove CHMOD number "775", as different numbers may be required.
$lang['install_disclaimer'] = "如果你不知道你的伺服器支不支援MySQL或是你不知道你的MySQL資訊,請向你的空間提供著查詢以下訊息:<br />MySQL資料庫伺服器位置, MySQL資料庫名稱, MySQL使用著名稱, 及 MySQL使用著密碼. <br />若沒有這些隻訓,你將無法安裝OekakiPotato. 如果你需要移除MySQL資料庫,請按本頁最下方的連結. 安裝錢請先閱讀readme.txt! 請確定你資料夾和檔案都Chmod到775.";

//If your OP currently works, there is no need to change the MySQL information.
$lang['cpanel_mysqlinfo'] = "如果你的Oekaki Poetato已經安裝了,就不用更改MySQL資料庫的設定.";

//Default Language
$lang['cpanel_deflang'] = "預設語言";

//Artist
$lang['word_artist'] = "畫家";

//Compression Settings
$lang['compress_title'] = "壓縮設定";

//Date
$lang['word_date'] = "日期";

//Time
$lang['word_time'] = "時間";

//min
$lang['word_minutes'] = "分鐘";

//unknown
$lang['word_unknown'] = "不明";

//Age
$lang['word_age'] = "年齡";

//Gender
$lang['word_gender'] = "性別";

//Location
$lang['word_location'] = "地區";

//Joined
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_joined'] = "加入";

//Language
$lang['word_language'] = "語言";

//Charset
$lang['word_charset'] = "字集";

//Help
$lang['word_help'] = "說明";

//URL
$lang['word_url'] = "URL";

//Name
$lang['word_name'] = "名字";

//Action
$lang['word_action'] = "動作";

//Disable
$lang['word_disable'] = "不啟用";

//Enable
$lang['word_enable'] = "啟用";

//Translator
$lang['word_translator'] = "翻譯著";

//Yes
$lang['word_yes'] = "是";

//No
$lang['word_no'] = "否";

//Accept
$lang['word_accept'] = "接受";

//Reject
$lang['word_reject'] = "拒絕";

//Owner
$lang['word_owner'] = "作著";

//Type
$lang['word_type'] = "種類";

//AIM
$lang['word_aolinstantmessenger'] = "AIM";

//ICQ
$lang['word_icq'] = "ICQ";

//Skype
// note: previously MSN
$lang['word_microsoftmessenger'] = "Skype";

//Yahoo
$lang['word_yahoomessenger'] = "Yahoo即時通";

//Username
$lang['word_username'] = "會員名稱";

//E-mail
$lang['word_email'] = "E-Mail";

//Animated
$lang['word_animated'] = "動畫";

//Normal
$lang['word_normal'] = "正常";

//Registered
$lang['word_registered'] = "已註冊";

//Guests
$lang['word_guests'] = "客人";

//Guest
$lang['word_guest'] = "客人";

//Refresh
$lang['word_refresh'] = "重新整理";

//Comments
$lang['word_comments'] = "留言";

//Animations
$lang['word_animations'] = "動畫";

//Archives
$lang['word_archives'] = "歷史圖片集";

//Comment
$lang['word_comment'] = "回覆";

//Delete
$lang['word_delete'] = "刪除";

//Reason
$lang['word_reason'] = "理由";

//Special
$lang['word_special'] = "特別";

//Archive
$lang['word_archive'] = "歷史圖片集";

//Unarchive
$lang['word_unarchive'] = "從歷史圖片集拿出";

//Homepage
$lang['word_homepage'] = "首頁";

//PaintBBS
$lang['word_paintbbs'] = "PaintBBS";

//OekakiBBS
$lang['word_oekakibbs'] = "OekakiBBS";

//Archived
$lang['word_archived'] = "歷史圖片";

//IRC server
$lang['word_ircserver'] = "IRC 伺服器";

//days
$lang['word_days'] = "天";

//Commenting
$lang['word_commenting'] = "回覆";

//Paletted
$lang['word_paletted'] = "著色盤";

//IRC nickname
$lang['word_ircnickname'] = "IRC 暱稱";

//Template
$lang['word_template'] = "風格";

//IRC channel
$lang['word_ircchannel'] = "頻道";

//Horizontal
$lang['picview_horizontal'] = "水平";

//Vertical
$lang['picview_vertical'] = "垂直";

//Male
$lang['word_male'] = "男性";

//Female
$lang['word_female'] = "女性";

//Error
$lang['word_error'] = "錯誤";

//Board
$lang['word_board'] = "版面";

//Ascending
$lang['word_ascending'] = "從最早的開始排序";

//Descending
$lang['word_descending'] = "從最新的圖開始排序";

//Recover for {1}
$lang['recover_for'] = "恢復圖 {1}";

//Flags
$lang['word_flags'] = "記號";

//Admin
$lang['word_admin'] = "管理";

//Background
$lang['word_background'] = "背景";

//Font
$lang['word_font'] = "字型";

//Links
$lang['word_links'] = "連結";

//Header
$lang['word_header'] = "頁首";

//View
$lang['word_view'] = "觀看";

//Search
$lang['word_search'] = "搜尋";

//FAQ
$lang['word_faq'] = "問題和解答";

//Memberlist
$lang['word_memberlist'] = "會員名單";

//News
$lang['word_news'] = "最新消息";

//Drawings
$lang['word_drawings'] = "圖畫";

//Submenu
$lang['word_submenu'] = "次選單";

//Retouch
$lang['word_retouch'] = "續繪";

//Picture
$lang['word_picture'] = "圖片";



/* niftyusage.php */
// UPDATE

//Link to Something
$lang['lnksom'] = "連結到一些東西";

//URLs without {1} tags will link automatically.
// {1} = "[url]"
$lang['urlswithot'] = "URL中沒有 {1} 標示會自動變成連結.";

//Text
$lang['nt_text'] = "文字";

//Bold text
// note: <b> tag
$lang['nt_bold'] = "粗體文字";

//Italic text
// note: <i> tag
$lang['nt_italic'] = "斜體文字";

//Underlined text
// note: <u> tag
$lang['nt_underline'] = "底線文字";

//Strikethrough text
// note: <del> tag
$lang['nt_strikethrough'] = "一條拫線?文字";

//Big text
// note: <big> tag
$lang['nt_big'] = "大字體";

//Small text
// note: <small> tag
$lang['nt_small'] = "小字體";

//Quoted text
// note: <blockquote> tag
$lang['nt_quoted'] = "引用字體";

//Preformatted text
// note1: <code> tag (formerly <pre>).
// note2: "Monospaced" or "Fixed Width" would also be appropriate.
$lang['nt_preformatted'] = "已格式過的文字";

//Show someone how to quote
// Context = example of double brackets: "[[quote]]translation[[/quote]]"
$lang['nt_d_quote'] = "展示引用方式";

//These tags don't exist
// context = example of double brackets: "[[ignore]]translation[[/ignore]]"
$lang['nt_d_ignore'] = "這些標記不存在";

//ignore
// context = Example tag for double brackets: "[[ignore]]"
// translate to any word/charset if desired.
$lang['nt_ignore_tag'] = "忽略";

//Use double brackets to make Niftytoo ignore tags.
$lang['nt_use_double'] = "使用雙括號讓Niftytoo系統忽略標記.";

/* END niftyusage.php */



//Mailbox
$lang['word_mailbox'] = "信箱";

//Inbox
$lang['word_inbox'] = "收件夾";

//Outbox
$lang['word_outbox'] = "寄件夾";

//Subject
$lang['word_subject'] = "主題";

//Message
$lang['word_message'] = "訊息";

//Reply
$lang['word_reply'] = "回覆";

//From
$lang['word_from'] = "從";

//Write
$lang['word_write'] = "寫";

//To
$lang['word_to'] = "給";

//Status
$lang['word_status'] = "狀態";

//Edit
$lang['word_edit'] = "編輯";

//Register
$lang['word_register'] = "註冊";

//Administration
$lang['word_administration'] = "管理塗鴉版";

//Draw
$lang['word_draw'] = "畫圖";

//Profile
$lang['word_profile'] = "個人資料";

//Local
$lang['word_local'] = "本區";

//Edit Pics
$lang['header_epics'] = "編輯圖片";

//Recover Pics
$lang['header_rpics'] = "恢復圖片";

//Delete Pics
$lang['header_dpics'] = "刪除圖片";

//Delete Comments
$lang['header_dcomm'] = "刪除回覆";

//Edit Comments
$lang['header_ecomm'] = "編輯回覆";

//View Pending
$lang['header_vpending'] = "查看未批准的使用著";

//Re-Touch
$lang['word_retouch'] = "續繪";

//Logout
$lang['word_logoff'] = "登出";

//Modify Flags
$lang['common_mflags'] = "編輯記號";

//Delete User
$lang['common_delusr'] = "刪除會員";

//(include the http://)
$lang['common_http'] = "(包括 http://)";

//Move to page
$lang['common_moveto'] = "移到頁";

//Scroll Down
$lang['chat_scroll'] = "向下捲動";

//Conversation
$lang['chat_conversation'] = "喊話內容";

//Chat Information (required)
$lang['chat_chatinfo'] = " 喊話資料(需要的)";

//Move to Page
$lang['common_mpage'] = "移到  頁";

//Delete Picture
$lang['common_deletepic'] = "刪除圖片";

//Picture Number
$lang['common_picno'] = "圖片編號";

//Close this Window
$lang['common_window'] = "關閉此視窗";

//Last Login
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['common_lastlogin'] = "上次登入";

//Picture Posts
$lang['common_picposts'] = "圖片留言";

//Comment Posts
$lang['common_compost'] = "回覆留言";

//Join Date
$lang['common_jdate'] = "加入日期";

//You can use the message box to leave requests/comments, or talk to Wacintaki members if they are online.<b> All comments will be deleted after a specific amount of posts.<br /></b><br /><b>*</b> - Indicates a registered member.<br /><b>#</b> - Indicates a Guest<br /><br />Although you can see the current online registered members in the chat, Guests who are still online will dissapear after a specific amount of time. Be aware that everyime a guest posts, there WILL be multiple instances of the user under Guest. <br /><br />Your IP and Hostname are tracked in case of abuse. To see a user's IP/hostname, hover your mouse over their username in the chat. The rate of refresh is 15 seconds.
$lang['chat_warning'] = "你可以使用訊息盒以留下要求,或和上線的會員說話.<b>所有的留言將於一定的時間後刪除<br /></b><b><br />*</b> -表示已註冊之會員.<br /><b>#</b> - 表示客人 <br /><br />雖然你可以看到現在上線的人在喊話盒裡, 還在上線的客人將於一定時間消失, 注意每次可人留言時, 那裡一定有會員在其下面. <br /><br />你的IP已經被記錄了,因為怕被洗版.";

//Database Hostname
$lang['install_dbhostname'] = "資料庫伺服器名稱";

//Database Name
$lang['install_dbname'] = "資料庫名稱";

//Database Username
$lang['install_dbusername'] = "資料庫使用著";

//Database Password
$lang['install_dbpass'] = "資料庫密碼";

//Display - Registration (Required)
$lang['install_dispreg'] = "顯示-註冊 (一定要的)";

//URL to Wacintaki
$lang['install_opurl'] = "Wacintaki的路徑";

//Registration E-Mail
$lang['install_email'] = "管理著的 E-Mail";

//the e-mail address to use to send out registration information; this is REQUIRED if you are using automatic registration
$lang['install_emailsub'] = "如果E-mail地址是用來寄出確認信的話,這是一的需要的欄位";

//General
$lang['install_general'] = "一般設定";

//Encryption Key
$lang['install_salt'] = "加密密碼";

//An encryption key can be any combination of characters; it is used to generate unique encryption strings for passwords. If you are using multiple boards with the same member database, make sure all boards share the same encryption key.
$lang['install_saltsub'] = "一個加密密碼可以是全部的符號.這是用來製造唯一的String.如果你安裝超過一個版版的話,請確定每個版版都是同樣的加密密碼";

//Picture Directory
$lang['install_picdir'] = "圖片資料夾";

//directory where your pictures will be stored
$lang['install_picdirsub'] = "將要存放圖片的資料夾";

//Number of pictures to store
$lang['install_picstore'] = "要存放多少圖片?";

//the max number of pictures the OP can store at a time
$lang['install_picstoresub'] = "最多要存放的圖片";

//Registration
$lang['install_reg'] = "註冊";

//Automatic User Delete
$lang['install_adel'] = "自動刪除久未來的會員";

//user must login within the specified number of days before being deleted from the database
$lang['install_adelsub'] = "會員必須再一定的時間內至少登入一次,否則帳號會被刪除";

//days <b>(-1 disables the automatic delete)</b>
$lang['install_adelsub2'] = "天 <b>(-1 表示不刪除久未來的會員)</b>";

//Allow Guests to Post?
$lang['install_gallow'] = "讓沒註冊會員的訪客留言?(最好不要)";

//if yes, guests can make comment posts on the board and chat
$lang['install_gallowsub'] = "如果是的話,沒註冊的會員還能回覆和在喊話盒回覆";

//Require Approval? (Select no for Automatic Registration)
$lang['install_rapproval'] = "需要管理員批准? (若要自動註冊的話請選不)";

//if yes, approval by the administrators is required to register
$lang['install_rapprovalsub'] = "如果是,會員註冊後要經過管理員批准才行";

//Display - General
$lang['install_dispgen'] = "一般顯示";

//Default Template
$lang['install_deftem'] = "預設風格";

//templates are stored in the templates directory
$lang['install_deftemsub'] = "風格是存在templates 資料夾";

//Title
$lang['install_title2'] = "主題";

//Title for the Wacintai.  Avoid using symbols or decoration, as it will be used for e-mail headers.
$lang['install_title2sub'] = "你的塗鴉版的名稱";

//Display - Chat
$lang['install_dispchat'] = "顯示喊話盒";

//Max Number of Lines to Store for Chat
$lang['install_displinesmax'] = "有多少行要想是在喊話盒?";

//Lines of Chat Text to Display in a Page
$lang['install_displines'] = "一頁最多能想是多少行?";

//Paint Applet Settings
$lang['install_appletset'] = "畫圖版程式設定";

//Maximum Animation Filesize
$lang['install_animax'] = "最大動畫的檔案大小";

//the max filesize animation files can be in bytes; default is 500,000 bytes or 500KB
$lang['install_animaxsub'] = "最大的動畫檔案大小,預設為500KB或是 500000位元組";

//bytes (1024 bytes = 1KB)
$lang['install_bytes'] = "位元組(1024 Bytes = 1KB)";

//Administrator Information
$lang['install_admininfo'] = "管理員資料";

//Login
$lang['install_login'] = "登入";

//Password
$lang['install_password'] = "密碼";

//Recover Password
$lang['header_rpass'] = "忘記密碼";

//Re-Type Password
$lang['install_repassword'] = "重新鍵入新密碼";

//TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms. 
$lang['install_TOS'] = "請注意GNU的的規定";

//Databases Removed!
$lang['install_dbremove'] = "資料庫以移除";

//View Pending Users: Select a User
$lang['addusr_vpending'] = "觀看尚未被批准的使用著:選一個使用著";

//View Pending Users: Details
$lang['addusr_vpendingdet'] = "觀看尚未被批准的使用著: 內容";

//Art URL
$lang['addusr_arturl'] = "畫作 URL";

//Art URL (Optional)
$lang['reg_arturl_optional'] = "畫作 URL (不一定需要)";

//Art URL (Required)
$lang['reg_arturl_required'] = "畫作 URL (需要的)";

//Draw Access
$lang['common_drawacc'] = "畫圖權";

//Animation Access
$lang['common_aniacc'] = "畫動畫權";

//Comments (will be sent to the registrant)
$lang['addusr_comment'] = "意見 (將送給此會員)";

//Edit IP Ban List
$lang['banip_editiplist'] = "編輯ip封鎖名單";

//Use one IP per line.  Comments may be enclosed in parentheses at end of line.
$lang['banip_editiplistsub'] = "每一行打一個IP,在後面可以用括號把封鎖理由包起來.";

//Usage Example: <strong style="text-decoration: underline">212.23.21.* (Username - banned for generic name!)</strong>
$lang['banip_editiplistsub2'] = '例如: <strong style="text-decoration: underline">212.23.21.* (某甲 - 使用禁用名稱!)</strong>';

//Edit Host Ban List
$lang['banip_edithostlist'] = "編輯封鎖ISP名單";

//Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!
$lang['banip_edithostlistsub'] = '跟封鎖IP名單同樣的用法.  這會把整個ISP的IP和 <em>一大堆</em> 人封鎖,所以請小心使用.!';

//Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>
$lang['banip_edithostlistsub2'] = '例如: <strong style="text-decoration: underline">*.dsl.lamernet.net (假的ISP伺服器,IP換的太快)</strong>';

//Ban List
$lang['header_banlist'] = "封鎖名單";

//Control Panel
$lang['header_cpanel'] = "控制台";

//Send OPMail Notice
$lang['header_sendall'] = "寄出信件通知所有會員";

//<b>You have been banned!<br /><br />Reasons:<br /></b>- A user from your ISP was banned, which banned everyone on that ISP<br />- You were banned for malicious use of the oekaki<br /><br /><em>If you feel that this message was made in error, speak to an adminstrator of the oekaki.</em>
$lang['banned'] = "<b>你已被封鎖!<br /><br />原因:<br /></b>- 一個會員從你的ISP已經被封鎖,所以這樣就封鎖了整的ISP的IP.<br />-或著是你犯規被罰<br /><br /><em>請與管理員聯絡以得知詳情</em>";

//Retrieve Lost Password
$lang['chngpass_title'] = "重設命碼";

//Because your password is encrypted, there is no way to retrieve it. Instead, you must specify a new password. If you receive no errors when submitting this form, that means your password has successfully changed and you can login with it once you are redirected to the index page.
$lang['chngpass_disclaimer'] = "由於密碼已被加密,所以無法取得密碼,但是你可以重設密碼.請輸入你的新密碼.如果你傳送新密碼時沒問題的畫,表示你以成功重設密碼.然後等到你被Redirect到首頁後.你就可以用你的新密碼登入了.";

//New Password
$lang['chngpass_newpwd'] = "新密碼";

//Add Comment
$lang['comment_add'] = "增加回覆";

//Title of Picture
$lang['comment_pictitle'] = "畫作的主題";

//Adult Picture?
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['comment_adult'] = "成人圖片?";

//Comment Database
$lang['comment_database'] = "回覆資料庫";

//Global Picture Database
$lang['gpicdb_title'] = "全部的圖片資料庫";

//Delete User
$lang['deluser_title'] = "刪除使用著";

//will be sent to the deletee
$lang['deluser_mreason'] = "將會送到被刪除的會員的信箱";

//Clicking delete will remove all records associated with the user, including pictures, comments, etc. An e-mail will be sent to the user appened with your contact e-mail in the case the deletee has further questions on the removal.
$lang['deluser_disclaimer'] = "按下善除後將會刪除此會員所有的東西,包括其創作及回覆留言.一封E-mail將寄給他,如過他有問題的話他能回信.";

//Animated NoteBBS
$lang['online_aninbbs'] = "動畫的NoteBBS";

//Normal OekakiBBS
$lang['online_nmrlobbs'] = "正常的OekakiBBS";

//Animated OekakiBBS
$lang['online_aniobbs'] = "動畫的 OekakiBBS";

//Normal PaintBBS
$lang['online_npaintbbs'] = "正常的 PaintBBS";

//Palette PaintBBS
$lang['online_palpaintbbs'] = "著色的 PaintBBS";

//Admin Pic Recover
$lang['online_apicr'] = "管理員圖片恢復";

//Edit Notice
$lang['enotice_title'] = "編輯公告";

//Edit Profile
$lang['eprofile_title'] = "編輯個人資料";

//URL Title
$lang['eprofile_urlt'] = "URL 主題";

//IRC Information
$lang['eprofile_irctitle'] = "IRC 資訊";

//Current Template
$lang['eprofile_curtemp'] = "現在的風格";

//Current Template Details
$lang['eprofile_curtempd'] = "現在的風格的資料";

//Select New Template
$lang['eprofile_templsel'] = "選一個新風格";

//Comments / Preferences
$lang['eprofile_compref'] = "意見 / 選項";

//Picture View Mode
$lang['eprofile_picview'] = "觀賞圖片模式";

//Allow Adult Images
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adult'] = "允許成人圖片";

//Change Password
$lang['eprofile_chngpass'] = "更換密碼";

//Old Password
$lang['eprofile_oldpass'] = "舊密碼";

//Retype Password
$lang['eprofile_repass'] = "重新輸入密碼";

//You will be automatically logged out if your password successfully changes; you need to re-login when this occours.
$lang['eprofile_pdisc'] = "若你想更改密碼的話,你將會自動登出,若此發生時,請在登入一次,謝謝.";

//Use your browser button to go back.
$lang['error_goback'] = "用你的瀏覽器的上一頁按紐以回到上一頁.";

//Who's Online (last 15 minutes)
$lang['online_title'] = "誰在線上 (前15分鐘)";

//View Animation
$lang['viewani_title'] = "觀賞動畫";

//file size
$lang['viewani_files'] = "檔案大小";

//Register New User
$lang['register_title'] = "註冊新使用著";

//A VALID E-MAIL ADDRESS IS REQUIRED TO REGISTER!
$lang['register_sub2'] = "需要一個正確的E-amil註冊!!";

//Will be shown on your profile when registering; this comment box is limited to 80 chars for proper introduction so Admins can identify you; your IP and hostname is also tracked for security purposes.
$lang['register_sub3'] = "當你在註冊時,會顯示在你的個人資料上,這個意見盒只能輸入80個字,這樣管理員才能知道你,由於防小白的因素 的IP和ISP被紀錄. 不能用 HTML 或 image 語法.";

//Include a URL to a picture or website that displays a piece of your work that you have done.
$lang['register_sub4'] = "包括一個URL到一個圖片,他將是你的頭像.";

//THIS IS NECESSARY TO REQUEST ACCESS TO DRAW ON OEKAKI.
$lang['register_sub5'] = "這是必要的,所以才能有畫圖權.";

//Picture Recovery
$lang['picrecover_title'] = "恢復圖片";

//Profile for {1}
// {2} = Gender. Singular=Male/Unknown, Plural=Female
$lang['profile_title'] = "個人資料 {1}";

//send a message
$lang['profile_sndmsg'] = "送個訊息";

//Latest Pictures
$lang['profile_latest'] = "最新的圖片";

//Modify Applet Size
$lang['applet_size'] = "修改Applet的大小(長寬度)";

//Using Niftytoo
$lang['niftytoo_title'] = "用 Niftytoo";

//Nifty-markup is a universal markup system for Wacintaki. It allows for all the basic formatting you could want in your messages, profiles, and text.
$lang['niftytoo_titlesub'] = "Nifty-markup 是一個Wacintaki的語法系統 .這能允許在你的回覆和文字使用一些語法.";

//Linking/URLs
$lang['niftytoo_linking'] = "連結/路徑";

//To have a url automatically link, just type it in, beginning with http://
$lang['niftytoo_autolink'] = "要有一個正常的連結,請輸入URL,地址開頭要有 http://";

//Basic Formatting
$lang['niftytoo_basicfor'] = "基本格式";

//Change a font's color to the specified <em>colorcode</em>.
$lang['niftytoo_textcol'] = "更換一個字型的顏色到指示的<em>顏色碼</em>.";

//will produce
$lang['niftytoo_produce'] = "將會生產";

//Intermediate Formatting
$lang['niftytoo_intermform'] = "中等格式";

//Modify Permissions
$lang['niftytoo_permissions'] = "更改權限";

//Recover Any Pic
$lang['header_rapic'] = "恢復任何圖片";

//Super Administrator
$lang['type_sadmin'] = "超級管理員";

//Owner
$lang['type_owner'] = "擁有著";

//Administrator
$lang['type_admin'] = "管理員";

//Draw Access
$lang['type_daccess'] = "畫圖權限";

//Animation Access
$lang['type_aaccess'] = "動畫權限";

//Immunity
$lang['type_immunity'] = "防禦權限";

//Adult Viewing
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['type_adultview'] = "成人觀賞權限";

//General User
$lang['type_guser'] = "一般會員";

//A Super Administrator has the ability to add administrators as well as the abilities that Administrators have.
$lang['type_sabil'] = "一個超級管理員不但能管理,還能增加一些管理員.";

//Removing this permission will suspend their account.
$lang['type_general'] = "警告:移除此權限會停止他們的帳號.";

//Gives the user access to draw.
$lang['type_gdaccess'] = "讓此會員能畫圖";

//Gives the user access to animate.
$lang['type_gaaccess'] = "讓此會員能作動畫";

//Prevents a user from being deleted if the Kill Date is set.
$lang['type_userkill'] = "若自動刪除會員啟用時,選擇此項能防止此會員被刪.";

//Member List
$lang['mlist_title'] = "會員列表";

//Pending Member
$lang['mlist_pending'] = "尚未批准的會員";

//Send Mass Message
$lang['massm_smassm'] = "傳送大量私人訊息";

//The message subject
$lang['mail_subdesc'] = "訊息主題";

//The body of the message
$lang['mail_bodydesc'] = "訊息內容";

//Send Message
$lang['sendm_title'] = "傳送訊息";

//The recipient of the message
$lang['sendm_recip'] = "此訊息的收件著";

//Read Message
$lang['readm_title'] = "閱讀訊息";

//Retrieve Lost Password
$lang['lostpwd_title'] = "恢復遺失的密碼";

//An e-mail will be sent to the e-mail address you have in your profile. If you did not specify an e-mail address when you registered, you will have to re-register for a new account. The e-mail will contain a URL to where you can specify a new password, as well as the IP and hostname of the computer used to request the password for security purposes.
$lang['lostpwd_directions'] = "一封E-mail將送給到你註冊時填寫的E-mail,若你在註冊時沒有填寫 或 亂填你的E-mail地址,你就要重新註冊. 這封E-mail會給你一個URL,在那裡你可以重設你帳號的密碼., 也可以設定能取得密碼的電腦IP和名稱.";

//Local Comment Database
$lang['lcommdb_title'] = "當地的評語資料庫";

//Language Settings
$lang['eprofile_langset'] = "語言設定";



/* functions.php */

//A subject is required to send a message.
$lang['functions_err1'] = "送出私人訊息需要填主旨.";

//You cannot use mass mailing.
$lang['functions_err2'] = "你不可以用大量發送郵件的功能.";

//Access Denied. You do not have permissions to modify archives.
$lang['functions_err3'] = "拒絕進入. 你沒有權利更改圖片的歷史集.";

//The username you are trying to retrieve to does not exist. Please check your spelling and try again.
$lang['functions_err4'] = "你要的使用著名稱不存在,請檢查你有沒有拼錯.";

//Your new and retyped passwords do not match. Please go back and try again.
$lang['functions_err5'] = "你新的和重新輸入的密碼不一樣,請再試一次.";

//Invalid retrival codes. This message will only appear if you have attempted to tamper with the password retrieval system.
$lang['functions_err6'] = "無效的還原馬. 你是不試想厄搞阿?.";

//The username you are trying to send to does not exist. Please check your spelling and try again.
$lang['functions_err9'] = "你想把訊息送給的會員不存在,請檢查後再試一次.";

//You need to be logged in to send messages.
$lang['functions_err10'] = "你需要登入才能送私人訊息.";

//You cannot access messages in the mailbox that do not belong to you.
$lang['functions_err11'] = "你無法進入不屬於你的私人訊息盒.";

//Access Denied. You do not have permissions to delete users.
$lang['functions_err12'] = "拒絕進入. 你沒有刪除會員的權限.";

//Access Denied: Your password is invalid, or you are still a pending member.
$lang['functions_err13'] = "拒絕進入:你的密碼是無效的或你的帳號尚未啟用.";

//Invalid verification code.
$lang['functions_err14'] = "無效的確認碼.";

//The e-mail address specified in registration already exists in the database. Please re-register with a different address.
$lang['functions_err15'] = "此E-mail地此已經被人註冊了,請以其他的E-mail地址註冊.";

//You do not have the credentials to add or remove users.
$lang['functions_err17'] = "你沒有刪除會增加會員的權限.";

//You cannot claim a picture that is not yours.
$lang['functions_err18'] = "你無法說這圖片是你的因為這圖片根本不是你的.";

//You cannot delete a comment that does not belong to you if you are not an Administrator.
$lang['functions_err19'] = "你無法刪除一個不屬於你的留言,除非你是管理員或版主.";

//You cannot delete a picture that does not belong to you if you are not an Administrator.
$lang['functions_err20'] = "你無法刪除一個不屬於你的圖片.除非你是管理員或版主.";

//You cannot edit a comment that does not belong to you.
$lang['functions_err21'] = "你無法編輯一個不屬於你的流言.";

//{1} Password Recovery
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['precover_title'] = '{1} 密碼恢復';

//Dear {1},\n\nYou or someone with the IP/hostname [{6}] has requested for a password retrieve on {2 @ {3}. To retrieve your password, please copy and paste or click on this link into your browser:\n\n{4}\n\nYou will then be asked to specify a new password. If you did not request this, you may discard this message.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
// {6} = IP address
$lang['pw_recover_email_message'] = "親愛的 {1},\n\n你或其他人的: IP/電腦名稱 [{6}] 要求要恢復密碼 {2} @ {3}. 請把此連結複製到你的瀏覽器上以恢復你的密碼:\n\n{4}\n\n你將會被要求重設一個新密碼.如果你並沒有要求恢復密碼,請不用理會此訊息.";

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['mandel_title'] = '{1} 刪除通知';

//Dear {1},\n\nYour account has been deleted from {2} @ {3}. If you have any questions, please e-mail the administrator that removed your account..\n\nDeleted by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['act_delete_email_message'] = "親愛的 {1},\n\n你的帳號已刪除從: {2} @ {3}. 如果你有任何問題,請聯繫刪除你帳號的管理員.\n\n刪除: {4} ({5})\n留言: {6}";

//{1} Registration Details
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['autoreg_title'] = '{1} 註冊資訊';

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{4}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: Automated Registration
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = permissions
$lang['auto_accept_email_message'] = "親愛的 {1},\n\n你以經註冊完成,你現在可以登入 {2} @ {3} 用你註冊時帳號登入,當你登入後,你可能想到個人控制台以選擇你喜歡的選項,和清除IP和電腦名稱的註冊紀錄.\n\n你被給予以下的權限:\n{4}\n如果你沒有畫圖和動畫的從取權限,請寄信給管理員.\n\n並看看FAQ看看有沒有定期刪會員,如果有 ,你必須要常常登入你的帳號,避免你的帳號被移除.\n\n批准人: 自動註冊系統";

//{1} Verification Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['verreg_title'] = '{1} 確認通知';

//Dear {1},\n\nYou have registered for {2} @ {3}. To complete your registration, please copy and paste or click on this link into your browser:\n\n{4}\n\nThis will verify your account so you can login into the oekaki.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
$lang['ver_email_message'] = "親愛的 {1},\n\n你註冊了此塗鴉流言版 {2} @ {3}. 若要完成註冊程序,請複製以下連結到你的瀏覽器:\n\n{4}\n\n這可以確認你的帳號,然後你就可以登入.";

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{7}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = comments
// {7} = permissions
$lang['admin_accept_email_message'] = "親愛的 {1},\n\n你以經註冊完成,你現在可以登入 {2} @ {3} 用你註冊時帳號登入,當你登入後,你可能想到個人控制台以選擇你喜歡的選項,和清除IP和電腦名稱的註冊紀錄.\n\n你被給予以下的權限:\n{7}\n如果你沒有畫圖和動畫的從取權限,請寄信給管理員.\n\n並看看FAQ看看有沒有定期刪會員,如果有 ,你必須要常常登入你的帳號,避免你的帳號被移除.\n\n被批准: {4} ({5})\n留言: {6}";

//Dear {1},\n\nYour registration at {2} @ {3}, has been rejected. Please e-mail the {2} administrator who rejected you for more details. DO NOT reply to this e-mail address.\n\nRejected by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['reg_reject_email_message'] = "親愛的 {1},\n\n你的申請在 {2} @ {3}, 被拒絕了,請聯絡 {2} 拒絕你的管理員,請勿回覆此封郵件.\n\n被拒絕: {4} ({5})\n留言: {6}";

//Your picture has been removed
// NOTE: mailbox subject.  BBCode only.  No newlines.
$lang['picdel_title'] = '你的圖片已被移除';

//Hello,\n\nYour picture ({1}) has been removed from the database by {2} for the following reason:\n\n{3}\n\nIf you have any questions/comments regarding the action, you may reply to this message.
// NOTE: mailbox message.  BBCode only, and use \n rather than <br />.
// {1} = url
// {2} = admin name
// {3} = reason
$lang['picdel_admin_note'] = "你好,\n\n你的圖片 ({1}) 已從資料庫被管理員移除 {2} 移除原因為下:\n\n{3}\n\n如果你有任何問題,你可以回覆此封郵件洽詢管理員.";

//(No reason specified)
$lang['picdel_admin_noreason'] = '(沒有特別的理由)';

//Safety save
$lang['to_wip_admin_title'] = '安全儲存';

//One of your pictures has been turned into a safety save by {1}. To finish it, go to the draw screen. It must be finished within {2} days.
$lang['to_wip_admin_note'] = '你的其中一個圖片已經被 {1} 轉成安全儲存,如要完成此圖畫,請到畫圖的螢幕,這必須要在兩天內完成.';

/* END functions.php */



/* maint.php */

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['kill_title'] = "{1} 刪除通知";

//Dear {1},\n\nThis is an automated message from the {2} automatic deletion system. Your account has been deleted because you have not logged into the oekaki within the last {3} days. If you want to re-register, please visit {4}\n\nBecause the account has been deleted, all post, comment, and other records associated with your username has been removed, and cannot be re-claimed. To avoid further deletions upon re-registration, be sure to log into your account within the specified amount of days in the FAQ.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['kill_email_message'] = "親愛的 {1},\n\n這個自動訊息是從本繪圖流言版刪除系統產生的. 你的帳號被刪除,因為你很久都沒登入 {2} 再上一次 {3} 天. 如果你希望重新註冊,到 {4}\n\n因為你的帳號已被刪除,所有關於你的文章及圖片都被刪除了. 為了讓以後此是不會再發生,請閱讀FAQ裡的日期並常常登入..\n\n敬上,\n自動刪除";

//{1} Registration Expired
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['regexpir'] = "{1} 註冊過期";

//Dear {1},\n\nYour registration at {2} has expired becuase you did not activate your account within {3} days. To submit a new registration, please visit {4}\n\nIf you did not receive a link to activate your account in a seperate e-mail, try using a different address or check the anti-spam settings used for your e-mail.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['reg_expire_email_message'] = "親愛的 {1},\n\n你的申請在 {2} @ {4} 過期了因為你沒有啟動帳號在 {3} 天. 請再重新註冊一次. {4}\n\n如果你在你的e-mai沒收到啟動帳號的連結,請嘗試用另一個地址或檢查你信箱的反垃圾信設定.\n\n自動註冊系統\n敬上.\n\n敬上,\n自動註冊系統";

/* END maint.php */



/* FAQ */

//Frequently Asked Questions
$lang['faq_title'] = "常見問題";

//<strong>Current Wacintaki version: {1}</strong>
$lang['faq_curver'] = "現在裝的 Wacintaki 繪圖留言系統版本: {1}";

//<strong>This oekaki deletes inactive accounts after {1} days.  Log in regularly to keep your account active.</strong>
//UPDATE
$lang['faq_autoset'] = "自動偵測設定為 {1} 天";

//<strong>No automatic deletion is set.</strong>
$lang['faq_noset'] = "目前尚未設定自動偵測.";

//Get the latest Java for running oekaki applets.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_java'] = '請安裝最新版本的JAVA才可以使用此塗鴉板.';

//JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_jtablet'] = 'JTablet可以使Java增加壓力感應功能,支援PC, Mac, 和 Linux.';

//Table of Contents
$lang['faq_toc'] = "FAQ目錄";

// -----

//What is an &ldquo;oekaki?&rdquo;
$lang['faq_question'][0] = '甚麼是 &ldquo;oekaki?&rdquo;';

$lang['faq_answer'][0] = '<p>Oekaki是塗鴉的日文, Oekaki版在網路上就是可以利用塗鴉程式和瀏覽器的塗鴉版. 最剛開始的網路塗鴉板只有幾種顏色畫在300x300像素的圖片上,圖片是一列一列的顯示,不像現在的塗鴉板是依照一幅畫與留言的並列顯示. 塗鴉板在1998年開始在日本各大遊戲電玩網站出現.</p>

<p>今天的塗鴉程式比以前豐富許多,並且可以畫出不同大小多圖層的圖,但是塗鴉板的版面還是差不多一樣. 塗鴉板大多都是個人創作,相片和不是自己的作品通常都不被允許的,除了少數的塗鴉板有圖片上傳功能外.</p>

<p>&ldquo;oekaki&rdquo;這個詞可以表示塗鴉作品和塗鴉板. 當一個人再畫一個oekaki表示他們再畫一張圖.當一個人在oekaki上面作畫,或者是看oekaki,這時oekaki就是塗鴉板.</p>';


//What is Wacintaki, and why is it sometimes called Wacintaki Poteto?
$lang['faq_question'][1] = '甚麼是 Wacintaki, 為甚麼它有時候叫做 Wacintaki Poteto?';

$lang['faq_answer'][1] = '<p>Wacintaki是一個可以安裝在個人網站的塗鴉板系統. 跟傳統的塗鴉板不同的是,使用者必須註冊才能夠塗鴉.  Wacintaki 是從之前Theo Chakkapark 和 Marcello Bast嶧-Fortewas做的OekakiPoteto 所改寫的. 剛改寫的程式叫做Wacintaki Poteto,但是現在已經改名為Wacintaki.</p>

<p>如果你想在你網站上安裝Wacintkai, 請去Wacintaki的網頁或者是去NineChime 軟體支援論壇.</p>';


$lang['faq_question'][2] = '我要怎麼開始畫圖?';

$lang['faq_answer'][2] = '<p>Wacintaki需要你先註冊成為使用者才能開始塗鴉. 如果你已經註冊了,你可以開始按工具列上的畫圖選項開始畫圖. 注意如果你違反規定系統管理員可以隨時把你的畫圖權限給拿走,把你帳號刪除乃至封鎖你的帳號.</p>';


$lang['faq_question'][3] = '畫圖和上傳哪裡不同?';

$lang['faq_answer'][3] = '<p>Oekaki塗鴉板讓你使用你想要的塗鴉程式畫圖. 如果管理著允許,一些塗鴉板也能夠讓你上傳圖片. 但是很多版面只有設定特定人士可以上傳,在詢問圖片上傳權限前,請先檢查版面規定.</p>';


$lang['faq_question'][4] = '要如何開始製造動畫?';

$lang['faq_answer'][4] = '<p>一些塗鴉程式允許你記錄你畫圖的動作讓其他人知道你畫圖的順序. 在按下選單中的 &ldquo;畫圖&rdquo; 之後,你會看到動畫的選項.  有些塗鴉程式無法紀錄動畫,但是須要你選擇動畫選項才能啟動多圖層作畫.</p>

<p>動畫只會顯示你畫圖的過程. 你無法製作卡通,電影或者是編輯動畫.  如果你在塗鴉程式中按下了 &ldquo;復原&rdquo; 鈕, 你的上一個動作將不會紀錄在動畫中.</p>';


$lang['faq_question'][5] = '我無法打開塗鴉板程式.';

$lang['faq_answer'][5] = '<p>大部分塗鴉程式均為 Java applets. 如果你的瀏覽器說你沒有對的外掛,請到 <a href="http://www.java.com">www.Java.com</a> 下載Java. Java是昇陽公司創造的一個軟體平台,現在由甲骨文公司管理.</p>

<p>大部分的電腦已經安裝了Java.  Java 在Mac電腦上已經被頻果電腦安裝了,但是在執行塗鴉程式的時候會出問題.</p>';


$lang['faq_question'][6] = '我沒辦法編輯我的圖片,整個畫布都是空白的!';

$lang['faq_answer'][6] = '<p>舊版的Java沒辦法匯入舊圖到程式裡面, 所以請確認你有最新版的Java程式. 在編輯圖片的時候也可以啟用動畫繼續記錄你畫圖的過程,如果你是編輯一個很複雜的圖片,動畫載入將會花一段時間.</p>';


$lang['faq_question'][7] = '我遺失了密碼.';

$lang['faq_answer'][7] = '<p>你可以在<a href=\"lostpass.php\">這裡</a>恢復你的密碼.</p>

<p>如果你沒辦法使用密碼恢復程式取得你的密碼, 請聯絡管理員,他/她將會給你一個新密碼.</p>';


$lang['faq_question'][8] = '我無法登出.';

$lang['faq_answer'][8] = '<p>這很有可能是瀏覽器問題.  你應該清空你瀏覽器的快取. 快取是網站存在電腦上的資訊,使網站可以知道你是誰. 大部分的瀏覽器可以在選單裡面找 &ldquo;工具->選項&rdquo; 的選單來清除Cookies.</p>';


$lang['faq_question'][9] = '我的圖片沒有送出,而且我的文章也沒送出,我的圖片會不會遺失?';

$lang['faq_answer'][9] = '<p>你的圖片不一定被遺失. 如果你的圖片已經上傳,但是你無法寫文章, 請按 &ldquo;管理->找回失圖&rdquo; 找回你的圖片. 你的資訊仍然已經儲存了,包括你的繪圖時間. 如過你還有問題的話,管理員可能可以恢復你的圖片,或者是你在畫圖完前先按 Print Screen Sys Rq 鍵備份你的圖片,以供出問題的時候可以上傳圖片.</p>

<p><strong>注意</strong>:  用Print System SysRq儲存的檔案大小可能變大而且格式可能跟 Wacintaki 無法相容. 在你想要你或管理員上傳你備份的圖片之前,請先通知管理員試試看他有沒有辦法從系統中找到圖片.</p>';


$lang['faq_question'][10] = '為甚麼我沒辦法看到任何電子郵箱地址?';

$lang['faq_answer'][10] = '<p>位了防止郵件信箱成為垃圾郵件的目標,你需要登入你的帳號才能在會員列表上看到其他會員的資料.</p>

<p>你可以看到管理員的郵件信箱地址,但是你必須輸入防機器人辨識文字,兩邊用 &ldquo;@&rdquo; 包含起來.</p>';


$lang['faq_question'][11] = '聊天和信箱系統怎麼了?';

$lang['faq_answer'][11] = '<p>Wacintaki 允許管理員關掉在OekakiPoteto裡面有的聊天室和信箱系統. 聊天室系統會使用有些伺服器無法承受的頻寬. 如果信箱系統已經被停用了,如果你是註冊會員的話,你可以去會員列表直接發到會員的電子郵箱中.</p>';


$lang['faq_question'][12] = '我要如何送信箱訊息給一個人?';

$lang['faq_answer'][12] = '<p>請到他們的個人資料中,然後按下頁面上方的 &ldquo;傳送訊息&rdquo;按鈕. 你必須登入你的帳號才能傳送訊息.</p>';


$lang['faq_question'][13] = '甚麼是 &ldquo;自動刪除會員?&rdquo;';

$lang['faq_answer'][13] = '<p>如果這個被設定的話,在一定的時間所有沒有登入的使用者將會被刪除. 如果你不希望被自動刪除的話,請與管理員要球一個防禦標記, 或者是定期登入塗鴉板.</p>';


$lang['faq_question'][14] = '甚麼是成人圖片?';

$lang['faq_answer'][14] = '<p>塗鴉板是世界性的,世界各地對成人圖片的定義也不一樣. 通常成人圖片是只有十八歲以上才能看的. 這些圖片包含暴力與色情的圖片. 一些塗鴉板不允許成人內容,所以請先閱讀管理員的版規在作畫.</p>

<p>如果一個塗鴉板被設為成人才可以進去的話,你必須在你註冊之前先傳送一個年齡聲明才可註冊. 如果你想要看成人圖片的話,你必須在你的個人資料中選擇允許成人圖片.</p>';


$lang['faq_question'][15] = '為甚麼有些圖片有縮圖,其他的圖片卻沒有?';

$lang['faq_answer'][15] = '<p>會不會縮圖取決於多項因素,包含圖片的檔案大小與圖片的大小和管理員設定的縮圖模式. 如果管理員允許,你可以在 <a href="editprofile.php">&ldquo;編輯個人資料中&rdquo;</a> 變更縮圖和版面的設定.</p>';

// -----

//Who {1?is the owner:are the owners} of this oekaki?
$lang['faq_questionA'] = "誰是這個OekakiPoteto的擁有者?";

//Who {1?is the adminitrator:are the adminitrators}?
$lang['faq_questionB'] = "誰是這個OekakiPoteto的管理員?";

//Who {1?is the moderator:are the moderators}?
$lang['faq_questionC'] = '誰是版主?';

/* End FAQ */



$lang['word_new'] = "新的";

$lang['word_unread'] = "尚未閱讀";

$lang['word_read'] = "已閱讀";

$lang['word_replied'] = "已回覆";

$lang['register_sub8'] = "當你註冊後,查看你的E-mail找能夠啟動(確認)你的帳號的連結,以啟用帳號.";

//Upload
$lang['word_upload'] = "上傳";

//Upload Picture
$lang['upload_title'] = "上傳圖片";

//File to upload
$lang['upload_file'] = "想要上傳的檔案";

//ShiPainter
$lang['word_shipainter'] = "ShiPainter";

//ShiPainter Pro
$lang['word_shipainterpro'] = "ShiPainter Pro";

//Edit Banner
$lang['header_ebanner'] = "編輯橫副";

//Reset All Templates
$lang['install_resettemplate'] = "重設全部的面板";

//N/A
$lang['word_na'] = "沒有";

//You do not have draw access. Ask an administrator on details for receiving access.
$lang['draw_noaccess'] = "你沒有畫圖的權限,請洽詢管理員.";

//Upload Access
$lang['type_uaccess'] = '上傳存取';

//Print &ldquo;Uploaded by&rdquo;
$lang['admin_uploaded_by'] = '顯示&ldquo;上傳者&rdquo;';

//Gives the user access to the picture upload feature.
$lang['type_guaccess'] = '給那位使用著上傳圖片的權限.';

//Delete database
$lang['delete_dbase'] = "刪除資料庫";

//Database Uninstall
$lang['uninstall_prompt'] = "解除安裝OekakiPoteto資料庫";

//Are you sure you want to remove the database?  This will remove information for the board
$lang['sure_remove_dbase'] = "你確定你要移除資料庫嗎?這會把所有版面的資料庫清空";

//Images, templates, and all other files in the OP directory must be deleted manually.
$lang['all_delete'] = "圖片,面板和全部其他在OP資料夾的檔案必須手動刪除.";

//If you have only one board, you may delete both databases below.
$lang['delete_oneboard'] = "如果你只有一個塗鴉版的話,你可以同時刪除以下兩個資料庫.";

//If you are sharing a database with more than one board, be sure to delete <em>only</em> the database for posts and comments.  If you delete the database for member profiles, all your boards will cease to function!
$lang['sharing_dbase'] = "如果你有多於一個版共用此資料庫,記得<em>只</em>刪除圖片和文章. 如果你刪除整個資料庫(包含會員資料),其他的版面都會出問題!";

//Each board must be removed with its respective installer.
$lang['remove_board'] = "每個版面需要用他自己的解安裝程式移除.";

//Delete posts and comments.
$lang['delepostcomm'] = "刪除圖片和文章.";

//Delete member profiles, chat, and mailboxes.
$lang['delememp'] = "刪除會員信件,聊天和信箱裡的所有東西.";

//Uninstall error
$lang['uninserror'] = "解除安裝錯誤";

//Valid database and config files were not found.  The board must be properly installed before any database entries can be removed.  If problems persist, let your sysadmin delete the databases by name.
$lang['uninsmsg'] = "無法讀取系統資料庫和設定檔. 塗鴉版必須正確安裝過才能解除安裝.  如果問題還是持續的話, 叫你的空間管理員移除那些資料庫,或用Phpmyadmin自行刪除.";

//Uninstall Status
$lang['unistatus'] = "解除安裝進度";

//NOTE:  No databases changed
$lang['notedbchange'] = "注意: 沒有資料庫被改過";

//Return to the installer
$lang['returninst'] = "回到安裝程式";

//Wacintaki Installation
$lang['wacinstall'] = "Wacintaki 塗鴉版安裝程式";

//Installation Progress
$lang['instalprog'] = "安裝進度";

//ERROR:  Your database settings are invalid.
$lang['err_dbs'] = "錯誤: 你的資料庫設定無效.";

//NOTE:  Database password is blank (not an error).
$lang['note_pwd'] = "注意: 資料庫密碼是空白 (不是個問題).";

//ERROR:  The administrator login name is missing.
$lang['err_adminname'] = "錯誤: 沒有管理員登入帳號.";

//ERROR:  The administrator password is missing.
$lang['err_adminpwd'] = "錯誤: 沒有管理員登入密碼.";

//ERROR:  The administrator passwords do not match.
$lang['err_admpwsmtch'] = "錯誤: 兩個管理員登入密碼不相符.";

//Could not connect to the MySQL database.
$lang['err_mysqlconnect'] = "無法連線到MySQL資料庫.";

//Wrote database config file.
$lang['msg_dbsefile'] = "寫到資料庫設定檔中.";

//ERROR:  Could not open database config file for writing.  Check your server permissions
$lang['err_permis'] = "錯誤: 無法寫入資料庫設定檔. 確認你伺服器的設定";

//Wrote config file.
$lang['wrconfig'] = "寫入設定檔.";

//ERROR:  Could not open config file for writing.  Check your server permissions.
$lang['err_wrconfig'] = "錯誤: 無法寫入設定檔,  確認你伺服器的設定";

//ERROR:  Could not create folder &ldquo;{1}&rdquo;
$lang['err_cfolder'] = "錯誤: 無法建立資料夾 &ldquo;{1}&rdquo;";

//ERROR:  Folder &ldquo;{1}&rdquo; is locked.  You may have to create this folder manually.
$lang['err_folder'] = "錯誤: 資料夾 &ldquo;{1}&rdquo; 是鎖定的. 你可能需要手動創建此資料夾.";

//One or more base files could not be created.  Try again or manually create the listed files with zero length.
$lang['err_fcreate'] = "無法建立一或更多個基本檔案.  請在試一次或手動建立以下長度為0位元組的檔案.";

//'Wrote base &ldquo;resource&rdquo; folder files.'
$lang['write_basefile'] = "寫入基本 &ldquo;資源&rdquo; 資料夾的檔案.";

//Starting to set up database.
$lang['startsetdb'] = "開始安裝資料庫.";

//Finished setting up database.
$lang['finishsetdb'] = "成功安裝資料庫.";

//If you did not receive any errors, the databases have been installed.
$lang['noanyerrors'] = "如果你沒收到任何錯誤, 塗鴉版的資料庫已經成功安裝了.";

//If you are installing another board and your primary board is functioning properly, ignore any database errors.
$lang['anotherboarderr'] = "如果你是在安裝另一個塗鴉版,然而第一個版面是正常的話,請略過任何資料庫錯誤.";

//Click the button below to finalize the installation.  This will clean up the installer files and prevent security problems.  You will have to copy <em>install.php</em> into the Wacintaki folder if you need to uninstall the database.  All other maintenance can be done with the control panel.
$lang['clickbuttonfinal'] = "按以下按鈕以結束安裝程式. 這會刪除案裝程式的檔案以防止安全問題.  如果你要解除安裝資料庫的話,你必須抄一份 <em>install.php</em> 到 Wacintaki 塗鴉版的資料夾. 所有其他的修改可以在系統管理控制台完成.";

//Secure installer and go to the BBS
$lang['secinst'] = "刪除安裝程式和到新安裝的塗鴉版";

//Installation Error
$lang['err_install'] = "安裝錯誤";

//&ldquo;templates&rdquo; and &ldquo;resource&rdquo; folders are not writable!  Be sure to CHMOD these folders to their correct permissions before running the installer.
$lang['err_temp_resource'] = "無法寫入&ldquo;面板&rdquo; 和 &ldquo;資源&rdquo; 資料夾! 在安裝之前請確認這些資料夾有正確的 CHMOD 到正確的權限.";

//Wacintaki Installation
$lang['wac_inst'] = "Wacintaki 塗鴉版安裝程式";

//Installation Notes
$lang['inst_note'] = "安裝注意事項";

//One MySQL database is required to install Wacintaki.  If you do not know how to access your MySQL account, e-mail your sysadmin, or log into your control panel and look for a database tool such as phpMyAdmin.  On most servers, &ldquo;localhost&rdquo; will work for the hostname, though web hosts with a dedicated MySQL server may require something such as &ldquo;mysql.server.com&rdquo;.  Be aware that some database tools, such as CPanel or phpMyAdmin, may automatically add a prefix to your database name or username, so if you create a database called &ldquo;oekaki&rdquo;, the result may end up being &ldquo;accountname_oekaki&rdquo;.  The database table prefixes (default &ldquo;op_&rdquo;) are only significant if you wish to install more than one oekaki.  Consult the manual for more information on installing multiple oekakis with one database.
$lang['mysqldb_wact'] = "需要一個MySQL資料庫安裝Wacintaki塗鴉版. 如果你不知道如何進入你的MySQL帳號, 請e-mail 給你的空間管理員, 或登入到你的空間控制台尋找資料庫管理程式(如phpMyAdmin). 再大部分的伺服器上,資料庫伺服器名稱是&ldquo;localhost&rdquo;, 但是有些伺服器使用專用的MySQL伺服器可能會有其他資料庫伺服器名稱,如 &ldquo;mysql.server.com&rdquo;. 請注意有些資料庫管理程式,如CPanel或phpMyAdmin, 可能會自動在你資料庫名稱或使用者情稱加上某些字首, 所以如果你建立了一個資料庫名子為 &ldquo;oekaki&rdquo;, 那個資料庫的名子可能變成 &ldquo;accountname_oekaki&rdquo;.  資料庫表格的字首 (預設為 &ldquo;op_&rdquo;) 需要注意如果你安裝不只一個塗鴉版. 如要在一個資料庫裝多重塗鴉版.請閱讀安裝說明.";

//Database Table Prefix
$lang['dbtablepref'] = "資料庫表格字首";

//If installing mutiple boards on one database, each board must have its own, unique table prefix.
$lang['multiboardpref'] = "如果安裝不只一個塗鴉版在一個資料庫的話,每個塗鴉版必須要有獨特的資料庫字首.";

//Member Table Prefix
$lang['memberpref'] = "會員資料表字首";

//If installing multiple boards on one database, and you want all members to access each board without seperate registrations, make sure each board shares the same table prefix.  To force sperate registrations for each board, make this prefix unique for each installation.
$lang['instalmulti'] = "如果安裝多重塗鴉版在一個資料庫上, 然後你希望每個會員不需在每個版註冊而能夠登入到每個版, 請確認每個版的會員資料表的字首是一樣的. 如要強迫會員在每個版註冊, 請讓此字首和其他塗鴉版的字首不一樣.";

//<a href="{1}">Click here to uninstall an existing database.</a>  Confirmation will be requested.
$lang['uninstexist'] = '<a href="{1}">按這裡以解除安裝所有存在的資料庫</a> 將需要確認';

//This is a guess.  Make sure it is correct, or registration will not work correctly.
$lang['guessregis'] = "這是一個猜測. 請確認這是對的,否將無法註冊.";

//Picture Name Prefix
$lang['picpref'] = "圖片名稱字首";

//This prefix will appear on every picture and animation saved by the BBS.  Example: &ldquo;OP_50.png&rdquo;
$lang['picprefexp'] = "這字首將會出現在被此塗鴉板儲存的圖片檔名上.  例如: &ldquo;OP_50.png&rdquo;";

//Allow Public Pictures
$lang['allowppicture'] = "允許公用圖片";

//Public pictures may be retouched by any member with draw permissions. No passwords are used, and retouched images are submitted as new posts. <strong>NOTE</strong>: May result in floods without strict rules and administration.
$lang['ppmsgrtouch'] = "公用圖片可以被人何擁有畫圖權限的人編輯. 沒有密碼保護, 這些編輯過的圖片會另存新圖. <strong>注意</strong>: 如果沒有嚴厲的管理和版規的話,可能到致嚴重灌水.";

//Allow Safety Saves
$lang['allowsafesave'] = "允許安全儲存";

//Safety saves do not show up on the board while they are in progress.  Only one safety save is allowed per member, and they are automatically deleted after a certain number of days
$lang['safesaveexp'] = "安全儲存是在圖片還沒完成前,不會把那個圖片公開在板上. 每個會員只能有一個安全儲存並且他們會在幾天內自動被系統刪除";

//Safety Save Storage
$lang['savestorage'] = "保存安全儲存的圖片";

//Number of days safety saves are stored before they are removed.  Default is 30
// UPDATE: Maximum number of days is no longer 60.
$lang['safetydays'] = "在安全儲存一個東西後過多少天會被系統自動刪除. 預設是30, 最多60";

//Auto Immunity for Artists
$lang['autoimune'] = "自動防禦畫家";

//If yes, people who draw pictures will automatically receive the immunity flag from auto user delete.
$lang['autoimune_exp'] = "如果是, 如果使用者有畫過圖的話,他們就不會遭到系統自動刪除.";

//Show Rules Before Registration
$lang['showrulereg'] = "在會員註冊前顯示規定";

//If yes, people will be shown the rules before they can submit a new registration.  Use &ldquo;Edit Rules&rdquo; in the admin menu to set rules.
$lang['showruleregexp'] = "如果是, 每個人在註冊前會看到規定. 用在管理表單中的&ldquo;編輯規定&rdquo;設定規定.";

//Require Art Submission
$lang['requireartsub'] = "需要之前的畫作驗證";

//If yes, new users are instructed to provide a link to a piece of art for the administrator to view.
$lang['requireartsubyes'] = "如果是,新註冊的使用者者必須提供一個連結到他之前的畫作,以讓管理員批准.";

//If no, new users are told the URL field is optional.
$lang['requireartsubno'] = "如果不,新註冊的使用者不需要提供一個連結到他之前的畫作.";

//No (forced)
$lang['forceactivate'] = "不(強迫啟用)";

//If yes, approval by the administrators is required to register.
$lang['activateyes'] = "如果是,新註冊的會員需要管理員的批准.";

//If no, users will receive an activation code in their e-mail.
$lang['activeno'] = "如果不,新註冊的會員將會收到e-mail以啟用他的帳號.";

//Use &ldquo;forced&rdquo; ONLY if your server cannot send e-mails, and you want automatic approval.
$lang['activateforced'] = "如果你的伺服器無法發E-mail然後你想要自動啟用會員的帳號,那就選 &ldquo;強迫啟用&rdquo;.";

//Default Permissions for Approved Registrations
$lang['defaultpermis'] = "當一個新會員驗證過後,他的預設權限:";

//Members may bump own pictures on retouch?
$lang['bumpretouch'] = "會員可以在編輯圖片中順便頂他們的圖片嗎?";

//Author Name
$lang['authorname'] = "塗鴉板擁有者名稱";

//Name of the BBS owner.  This is displayed in the copyright and page metadata.
$lang['bbsowner'] = "塗鴉板擁有者名稱. 這會在頁尾和註解中顯示.";

//Adult rated BBS
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbs'] = "成人塗鴉板";

//Select Yes to declare your BBS for adults only.  Users are required to state their age to register.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsdesc'] = "選是來表示你的塗鴉板只給成人用的. 新註冊的會員必須提供他的生日才能註冊.";

//NOTE:  Does <strong>not</strong> make every picture adult by default.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsnote'] = "注意: 這<strong>不會</strong>把每個圖片預設成成人圖片.";

//Allow guests access to pr0n
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpron'] = "允許訪客觀看成人圖片";

//If yes, adult images are blocked and may be viewed by clicking the pr0n placeholder.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronyes'] = "如果是,成人圖片會先被隱藏,但是訪客可以按一個宣告按鈕以觀看那些圖片.";

//If no, the link is disabled and all access is blocked.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronno'] = "如果不,客人將不能看到成人圖片.";

//Number of Pics on Index
$lang['maxpiconind'] = "每一頁的圖片數目";

//Avatars
$lang['word_avatars'] = "頭像";

//Enable Avatars
$lang['enableavata'] = "啟用頭像";

//Allow Avatars On Comments
$lang['allowavatar'] = "允許頭像在文章上";

//Avatar Storage
$lang['AvatarStore'] = "頭像保存資料夾";

//Change <strong>only</strong> if installing multiple boards.  Read the manual.
$lang['changemulti'] = "<strong>只有</strong>當你安裝不只一個塗鴉板時需要更改. 若不懂請查詢安裝手冊.";

//Basically, use one folder for all boards.  Example:  use &ldquo;avatars&rdquo; for board 1, &ldquo;../board1/avatars&rdquo; for board 2, etc.
$lang['changemultidesc'] = "基本上,每個塗鴉板只用一個頭像資料夾. 例如: 用&ldquo;avatars&rdquo;當作塗鴉板1地頭像資料夾,&ldquo;../board1/avatars&rdquo;當作塗鴉板2地頭像資料夾,以此類推.";

//Maximum Avatar Size
$lang['maxavatar'] = "最大的頭像大小";

//Default is 50 &times; 50 pixels.  Larger than 60 &times; 60 not recommended
$lang['maxavatardesc'] = "預設是50&times;50像素. 不建議大於60&times;60大小的頭像(會破壞版面)";

//Default Canvas Size
$lang['cavasize'] = "預設的畫布大小";

//The default canvas size.  Typical value is 300 &times; 300 pixels
$lang['defcanvasize'] = "預設的畫布大小. 正常的是300&times;300像素";

//Minimum Canvas Size
$lang['mincanvasize'] = "最小畫布大小";

//The minimum canvas size.  Recommended minimum is 50 &times; 50 pixels
$lang['mincanvasizedesc'] = "最小畫布大小. 建議是50&times;50像素";

//Maximum Canvas Size
$lang['maxcanvasize'] = "最大畫布大小";

//Maximum canvas.  Recommended maximum is 500 &times; 500 pixels
$lang['maxcanvasizedesc'] = "最大畫布大小. 建議是500&times;500像素";

//Be aware that a small increase in dimentions results in a large increase in surface area, and thus filesize and bandwidth.  1000 &times; 1000 uses <strong>four times</strong> as much bandwidth as 500 &times; 500
$lang['maxcanvasizedesc2'] = "注意,增加一點點會大大增加畫布的表面面積,檔案大小和頻寬. 1000&times;1000像素的畫布用的頻寬是500&times;500的<strong>四倍</strong>";

//Number of pictures to display per page of the BBS
$lang['maxpicinddesc'] = "在塗鴉板每頁所顯示的圖片數量";

//Number of Entries on Menus
$lang['menuentries'] = "每個表單的每一頁顯示多少項目";

//Number of entries (such as user names) to display per page of the menus and admin controls
$lang['menuentriesdesc'] = "每一頁顯示多少項目(像使用者名稱)顯示在表單上和系統控制台上";

//Use Smilies in Comments?
$lang['usesmilies'] = "允許在評語中使用表情?";

//Smilies are configurable by editing the &ldquo;hacks.php&rdquo; file
$lang['usesmiliedesc'] = "表情可以在&ldquo;hacks.php&rdquo;中編輯";

//Maximum Upload Filesize
$lang['maxfilesize'] = "最大上傳檔案大小";

//The max filesize uploaded pictures can be, in bytes.  Default is 500,000 bytes or 500KB.
$lang['maxupfileexp'] = "最大上傳檔案大小(單位:位元組). 預設是500,000位元組或500KB.";

//The maximum value allowed by most servers varies from 2 to 8MB.
$lang['maxupfileexp2'] = "最大是2,000,000個位元組或2MB,這是在大部分伺服器的限制.";

//Canvas size preview
$lang['canvasprev'] = "畫布大小預覽";

//Image for canvas size preview on Draw screen.  Square picture recommended.
$lang['canvasprevexp'] = "在畫之前畫布大小預覽用的圖片. 建議是方形圖片.";

//Preview Title
$lang['pviewtitle'] = "預覽圖片主題";

//Title of preview image (Text only &amp; do not use double quotes).
$lang['titleprevwi'] = "預覽圖片的主題(純文字&amp;請勿用雙引號).";

//&ldquo;Pr0n&rdquo; placeholder image
$lang['pron'] = "&ldquo;Pr0n&rdquo;代替圖片";

//Image for substitution of pr0n.  Square picture recommended.  Default &ldquo;pr0n.png&rdquo;
$lang['prondesc'] = "代替成人圖片的圖片. 建議是方形圖片. 預設是&ldquo;pr0n.png&rdquo;";

//Enable Chat
$lang['enablechat'] = "啟用聊天室";

//Note:  chat uses a lot of bandwidth
$lang['chatnote'] = "注意:聊天室使用很大的頻寬";

//Your server does not have the graphics system &ldquo;GDlib&rdquo; installed, therefore you cannot enable thumbnail support.  However, you may still select a default thumbnail mode which will conserve screenspace by shrinking pictures.
$lang['err_nogdlib'] = "你的伺服器沒有安裝圖片系統&ldquo;GDlib&rdquo;, 所以你無法啟用縮圖. 但是,你還是可以選一個縮圖模式,這樣可以縮小圖片以節省顯示空間.";

//Four thumbnail modes are available.  None, Layout, Scaled, and Uniformity.  If you're confused which mode to use, try Scaled first.
$lang['thumbmodes'] = "有四種縮圖模式供選擇. 無,表面,比例和一致. 如果你不知道要用哪個,先選比例模式.";

//If you choose &ldquo;None&rdquo;, thumbnail support will always be off for all members unless you enable it later in the control panel.
$lang['thumbmodesexp2'] = "如果你選擇&ldquo;無&rdquo;,縮圖不會啟用,除非管理員以後在控制台啟用.";

//Default thumbnail mode
$lang['defthumbmode'] = "預設的縮圖模式";

//None
$lang['word_none'] = "無";

//Layout
$lang['word_layout'] = "表面";

//Scaled (default)
$lang['word_defscale'] = "比例(預設)";

//Uniformity
$lang['word_uniformity'] = "一致";

//Tip:  Options are ordered in terms of bandwidth.  Uniformity uses the least bandwidth.  Scaled Layout is recommended.
$lang['optiontip'] = "秘訣:選項是依照使用寬頻來排序的. 一致使用最少頻寬. 建議比例模式.";

//Force default thumbnails
$lang['forcedefthumb'] = "強迫預設縮圖";

//If yes, users may only use the default mode (recommended for servers with little bandwidth). If no, users may select any thumbnail mode they wish.
$lang['forcethumbdesc'] = "如果是,會員只能用預設的縮圖模式(建議使用在只有少量寬頻的伺服器). 如果不,使用者可以自由選擇他要的縮圖模式.";

//Small thumbnail size
$lang['smallthumb'] = "小縮圖的大小";

//Size of small (uniformity) thumbnails in pixels.  Small thumbnails are generated often.  Default 120.
$lang['smallthumbdesc'] = "小縮圖(一致模式)的大小(單位:像素). 常常會產生小縮圖. 預設是120.";

//Large thumbnail size
$lang['largethumb'] = "大縮圖的大小";

//Size of large (layout) thumbnails.  Large thumbnails are made only occasionally for layout or scaled thumbnail modes.  Default 250.
$lang['largethumbdesc'] = "大縮圖(表面模式)的大小(單位:像素). 如果用表面或比例模式時,大縮圖會在一些時候產生. 預設是250.";

//Filesize for large thumbnail generation
$lang['thumbnailfilesize'] = "大縮圖產生的檔案大小";

//If a picture's filesize is greater than this value, a large thumbnail will be generated in addition to the small one.  Default is 100,000 bytes.  If using uniformity mode only, set to zero to disable and save server space.
$lang['thumbsizedesc'] = "如果一個圖片的檔案大小大於這數字, 一個大縮圖和小縮圖會產生. 預設是100,000個位元組. 如果是用一致模式,把它設為 0 以節省伺服器空間.";

//Your E-mail Address (leave blank to use registration e-mail)
$lang['emaildesc'] = "你的E-mail地址(如果要用註冊地址請留空)";

//Submit Information for Install
$lang['finalinstal'] = "送出資料以安裝";

//---addusr

//You do not have the credentials to add users.
$lang['nocredeu'] = "你沒有增加會員的權限.";

//Note to admins:  Automatic approval is enabled, so users are expected to enable their own accounts.  Contact the board owner if you have questions about approving or rejecting members manually.
$lang['admnote'] = "管理員注意: 自動啟用帳號是啟用的, 所以會員應該會自己啟用帳號.  如果你對手動開啟會員帳號有疑問的話,請聯絡本塗鴉板的擁有人.";

//INVALID
$lang['word_invalid'] = "無效";

//--banlist

//You do not have the credentials to ban users.
$lang['credibandu'] = "你沒有鎖使用著的權限.";

//&ldquo;{1}&rdquo; is locked!  View Readme.txt for help.
// {1} = filename
$lang['fislockvred'] = "&ldquo;{1}&rdquo; 被鎖住了! 如要更多說明,請閱讀Readme.txt .";

//Submit Changes
$lang['submitchange'] = "送出變更";

//You do not have access as a registered member to use the chat.
$lang['memaccesschat'] = "你不是註冊的會員,所以沒有進聊天室的權限.";

//The chat room has been disabled.
$lang['charommdisable'] = "聊天室目前尚未啟用.";

//Sorry, an IFrame capable browser is required to participate in the chat room.
$lang['iframechat'] = "抱歉, 必須要一個可以執行IFrame的瀏覽器才能進去聊天室.";

//Invalid user name.
$lang['invuname'] = "無效的使用者名稱.";

//Invalid verification code.
$lang['invercode'] = "無效的驗證碼.";

//Safety Save
$lang['safetysave'] = "安全儲存";

//Return to the BBS
$lang['returnbbs'] = "回到塗鴉板";

//Error looking for a recent picture.
$lang['err_lookrecpic'] = "錯誤:無法找到最近的圖片.";

//NOTE:  Refresh may be required to see retouched image
$lang['refreshnote'] = "注意:要看到編輯過的圖片可能需要按重新整理";

//Picture properties
$lang['picprop'] = "圖片屬性";

//No, post picture now
$lang['safesaveopt1'] = "不,現在就貼圖";

//Yes, save for later
$lang['safesaveopt2'] = "是,儲存圖片留者以後編輯";

//Bump picture
$lang['bumppic'] = "頂圖";

//You may bump your edited picture to the first page.
$lang['bumppicexp'] = "你可以把你編輯過的圖頂到第一頁.";

//Share picture
$lang['sharepic'] = "分享圖片";

//Password protect
$lang['pwdprotect'] = "密碼保護";

//Public (to all members)
$lang['picpublic'] = "公用圖片(所有會員都可編輯)";

//Submit
$lang['word_submit'] = "送出";

//Thanks for logging in!
$lang['common_login'] = "感謝你登入!";

//You have sucessfully logged out.
$lang['common_logout'] = "你已經成功了登出.";

//Your login has been updated.
$lang['common_loginupd'] = "你的登入狀態已更新.";

//An error occured.  Please try again.
$lang['common_error'] = "錯誤發生.請再試一次.";

//&lt;&lt;PREV
$lang['page_prev'] = '&lt;&lt;上一頁';

//NEXT&gt;&gt;
$lang['page_next'] = '下一頁&gt;&gt;';

//&middot;
// bullet.  Separator between <<PREV|NEXT>> and page numbers
$lang['page_middot'] = '&middot;';

//&hellip;
// "...", or range of omitted numbers
$lang['page_ellipsis'] = '&hellip; &hellip;';

//You do not have the credentials to access the control panel.
$lang['noaccesscp'] = "你沒有進入系統管理控制台的權限.";

//Storage
$lang['word_storage'] = "儲存";

//300 or more recommended.  If reduced, excess pictures are deleted immediately.  Check disk space usage on the <a href=\"testinfo.php\">diagnostics page</a>.
$lang['cpmsg1'] = "最多同時儲存的圖片數. 建議大於300個(差不多25MB).可以在任何時間更改,但是超過此數字的舊圖片會立刻被刪除";

//Use &ldquo;avatars/&rdquo; for master board, &ldquo;../board1/avatars/&rdquo; for all other boards.
$lang['cpmsg2'] = "給主要塗鴉板用&ldquo;avatars/&rdquo;,其他塗鴉板用&ldquo;../board1/avatars/&rdquo;.";

//Image for canvas size preview on Draw screen.  Square picture recommended.  Default &ldquo;preview.png&rdquo;
$lang['cpmsg3'] = "在畫之前畫布大小預覽用的圖片. 建議是方形圖片. 預設的圖片是&ldquo;preview.png&rdquo;";

//Rebuild thumbnails
$lang['rebuthumb'] = "重建縮圖";

//Page one
$lang['pgone'] = "第一頁";

//Archived pictures only
$lang['archipon'] = "只有歷史圖片";

//All thumbnails (very slow!)
$lang['allthumb'] = "全部的圖片(會花很久時間!)";

//If thumbnail settings are changed, these thumbnails will be rebuilt.
$lang['rebuthumbnote'] = "如果縮圖的設定被改變的話,這些縮圖將重建.";

//You do not have the credentials to delete comments
$lang['errdelecomm'] = "你沒有刪除留言的權利";

//Send reason to mailbox
$lang['sreasonmail'] = "把理由送到信像";

//You do not have the credentials to edit the rules.
$lang['erreditrul'] = "你沒有編輯規定的權利.";

//Edit Rules
$lang['editrul'] = "編輯規定";

//HTML and PHP are allowed.
$lang['htmlphpallow'] = "允許HTML和PHP語法";

//You do not have the credentials to delete pictures.
$lang['errdelpic'] = "你沒有刪除圖片的權限.";

//You do not have the credentials to delete users.
$lang['errdelusr'] = "你沒有刪除會員的權限.";

//Pictures folder is locked!  View Readme.txt for help.
$lang['picfolocked'] = "圖片資料夾被鎖住了! 如要說明請讀Readme.txt.";

//Unfinished Pictures
$lang['icomppic'] = "尚未完成的圖片";

//Click here to recover pictures
$lang['clickrecoverpic'] = "按這裡恢復圖片";

//Applet
$lang['word_applet'] = "繪圖程式";

//, with palette
$lang['withpalet'] = ",加上調色盤";

//Canvas
$lang['word_canvas'] = "畫布";

//Min
$lang['word_min'] = "最小";

//Max
$lang['word_max'] = "最大";

//NOTE:  You must check &ldquo;animation&rdquo; to save your layers.
$lang['note_layers'] = "注意:你必須勾選&ldquo;動畫&rdquo;才能儲存圖層.";

//Avatars are disabled on this board.
$lang['avatardisable'] = "在這塗鴉板頭像功能尚未被啟用.";

//You must login to access this feature.
$lang['loginerr'] = "你必須登入才能使用此功能.";

//File did not upload properly.  Try again.
$lang['err_fileupl'] = "檔案沒有正常的上傳. 請再試一次.";

//Picture is an unsupported filetype.
$lang['unsuppic'] = "這圖片是一個不支援的格式.";

//Filesize is too large.  Max size is {1} bytes.
$lang['filetoolar'] = "圖片檔案太大. 最大的大小是{1}個位元組.";

//Image size cannot be read.  File may be corrupt.
$lang['err_imagesize'] = "無法讀取檔案大小. 檔案可能已毀損.";

//Avatar upload
$lang['avatarupl'] = "頭像上傳";

//Avatar updated!
$lang['avatarupdate'] = "頭像已成功上傳!";

//Your avatar may be a PNG, JPEG, or GIF.
$lang['avatarform'] = "你的頭像可以是PNG,JPEG,或GIF格式.";

//Avatars will only show on picture posts (not comments).
$lang['avatarshpi'] = "頭像只會在貼圖上顯示 (不會在留言上顯示).";

//Change Avatar
$lang['chgavatar'] = "更改頭像";

//Delete avatar
$lang['delavatar'] = "刪除頭像";

//Missing comment number.
$lang['err_comment'] = "沒有留言的編號.";

//You cannot edit a comment that does not belong to you.
$lang['err_ecomment'] = "你無法編輯一個不屬於你的留言.";

//You do not have the credentials to edit news.
$lang['err_editnew'] = "你沒有編輯公告的權限.";

//The banner is optional and displays at the very top of the webpage.
$lang['bannermsg'] = "橫幅式不一定需要,他將在頁首中顯示.";

//The notice is optional and displays just above the page numbers on <em>every</em> page.
$lang['noticemsg'] = "公告不一定需要,他會在<em>每頁</em>的頁數上顯示.";

//Erase
$lang['word_erase'] = "抹除";

//Centered Box
$lang['centrebox'] = "中心的盒子";

//Scroll Box
$lang['scrollbox'] = "捲軸";

//Quick Draw
$lang['quickdraw'] = "快速畫圖";

//You cannot edit a picture that does not belong to you.
$lang['err_editpic'] = "你無法編輯一個不屬於你的圖.";

//Type &ldquo;public&rdquo; to share with everyone
$lang['editpicmsg'] = "&ldquo;公用&rdquo;種類會把這圖和大家分享.";

//You cannot use the profile editor.
$lang['err_edprof'] = "你無法使用個人資料編輯器.";

//Real Name (Optional)
$lang['realnameopt'] = "真實名字(可不填)";

//This is not your username.  This is your real name and will only show up in your profile.
$lang['realname'] = "這不是你的使用者名稱. 這是你個人名字,只會在個人資料中顯示.";

//Birthday
$lang['word_birthday'] = "生日";

//M
// Month
$lang['abbr_month'] = "月";

//D
// Day
$lang['abbr_day'] = "日";

//Y
// Year
$lang['abbr_year'] = "年";

//January
$lang['month_jan'] = "一月";

//February
$lang['month_feb'] = "二月";

//March
$lang['month_mar'] = "三月";

//April
$lang['month_apr'] = "四月";

//May
$lang['month_may'] = "五月";

//June
$lang['month_jun'] = "六月";

//July
$lang['month_jul'] = "七月";

//August
$lang['month_aug'] = "八月";

//September
$lang['month_sep'] = "九月";

//October
$lang['month_oct'] = "十月";

//November
$lang['month_nov'] = "十一月";

//December
$lang['month_dec'] = "十二月";

//Year is required for birthday to be saved.  Day and month are optional.
$lang['bdaysavmg'] = "必須提供出生年才能儲存生日.月和日可不填.";

//Website
$lang['word_website'] = "網站";

//Website title
$lang['websitetitle'] = "網站名字";

//You can also type a message here and leave the URL blank
$lang['editprofmsg2'] = "你也可以把URL欄留空然後留一個你的訊息";

//Avatar
$lang['word_avatar'] = "頭像";

//Current Avatar
$lang['curavatar'] = "現在的頭像";

//Online Presence
$lang['onlineprese'] = "線上資料";

//(Automatic)
// context = Used as label in drop-down menu
$lang['picview_automatic'] = "自動";

//Automatic is the default format and will layout comments to wrap around the picture. Horizontal is good for very high-res screens and displays comments to the right of the picture.  Vertical is recommended for very small, low-res screens.
$lang['msg_automatic'] = "自動式預設的格式,這會把圖片和留言好好的排列. 高解析度的圖片最好用水平,這會使留言在圖片的右邊顯示. 垂直是給小和低解析度的螢幕.";

//Thumbnail mode
$lang['thumbmode'] = "縮圖模式";

//Default
$lang['word_default'] = "預設";

//Scaled
$lang['word_scaled'] = "比例";

//Default is recommended.  Layout will disable most thumbnails.  Scaled is like layout but will shrink big pictures.  Uniformity will make all thumbnails the same size.
$lang['msgdefrec'] = "建議預設模式. 表面不會為大部分圖片建立縮圖. 比例會把大圖片縮小. 一致會把所有的縮圖都弄成同樣大小.";

//(Cannot be changed on this board)
$lang['msg_cantchange'] = "無法在這塗鴉板上更改";

//Screen size
$lang['screensize'] = "螢幕大小";

//{1} or higher
// {1} = screen resolution ("1280&times;1024")
$lang['orhigher'] = "{1} 或更高";

//Your screensize, which helps determine the best layout.  Default is 800 &times; 600.
$lang['screensizemsg'] = "你的螢幕大小, 這可以幫助系統決定最好的排序方式. 預設是800&times;600像素.";

// No image data was received by the server.\nPlease try again, or take a screenshot of your picture.
$lang['err_nodata'] = "沒有接收到任何圖片的資料.\n 為了安全起見,請把螢幕的圖片擷取下來,然後再嘗試送出一次.";

//Login could not be verified!  Take a screenshot of your picture.
$lang['err_loginvs'] = "無法確認登入! 請把螢幕的圖片擷取下來.";

//Unable to allocate a new picture slot!\nTake a screenshot of your picture and tell the admin.
$lang['err_picts'] = "無法找到新的圖片編號!\n請把螢幕的圖片擷取下來並告知系統管理員.";

//Unable to save image.\nPlease try again, or take a screenshot of your picture.
$lang['err_saveimg'] = "無法儲存圖片.\n請再試一次,請把螢幕的圖片擷取下來並告知系統管理員.";

//Rules
$lang['word_rules'] = "規定";

//Public Images
$lang['publicimg'] = "公用圖片";

//Drawings by Comment
$lang['drawbycomm'] = "依照留言排列圖片";

//Animations by Comment
$lang['animbycomm'] = "依照留言排列動畫";

//Archives by Commen
$lang['archbycomm'] = "A依照留言排列歷史圖片";

//Go
// context = Used as button
$lang['word_go'] = "到";

//My Oekaki
$lang['myoekaki'] = "我的塗鴉";

//Reset Password
$lang['respwd'] = "重設密碼";

//Unlock
$lang['word_unlock'] = "反鎖";

//Lock
$lang['word_lock'] = "鎖";

//Bump
$lang['word_bump'] = "頂";

//WIP
$lang['word_WIP'] = "WIP";

//TP
// context = "[T]humbnail [P]NG"
$lang['abrc_tp'] = "縮Png";

//TJ
// context = "[T]humbnail [J]PEG"
$lang['abrc_tj'] = "縮Jpg";

//Thumb
$lang['word_thumb'] = "縮圖";

//Pic #{1}
$lang['picnumber'] = '#{1}';

//Pic #{1} (click to view)
$lang['clicktoview'] = "#{1} 按這裡檢視圖片";

//(Click to enlarge)
$lang['clickenlarg'] = "按這裡放大圖片";

//Adult
$lang['word_adult'] = "成人圖片";

//Public
$lang['word_public'] = "公用圖片";

//Thread Locked
$lang['tlocked'] = "主題被鎖了";

//The mailbox has been disabled.
$lang['mailerrmsg1'] = "信箱尚未被啟用.";

//You cannot access the mailbox.
$lang['mailerrmsg2'] = "你無法進入信箱.";

//You need to login to access the mailbox.
$lang['mailerrmsg3'] = "你必須登入才能進入個人信箱.";

// You cannot access messages in the mailbox that do not belong to you.
$lang['mailerrmsg4'] = "你無法閱讀不屬於你的私人訊息.";

//You cannot access the mass send.
$lang['mailerrmsg5'] = "你無法使用大規模送信.";

//Reverse Selection
$lang['revselect'] = "反選擇";

//Delete Selected
$lang['delselect'] = "刪除選擇的";

//(Yourself)
// context = Placeholder in table list
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_yourself'] = "你自己";

//Original Message
$lang['orgmessage'] = "原始訊息";

//Send
$lang['word_send'] = "送出";

//You can use this to send a global notice to a group of members via the OPmailbox.
$lang['massmailmsg1'] = "你可以使用這個功能傳送訓息給所有的會員.";

//Be careful when sending a mass mail to &ldquo;Everyone&rdquo; as this will result in LOTS of messages in your outbox.  Use this only if you really have to!
$lang['massmailmsg2'] = "請注意當你發送大量信件給&ldquo;大家&rdquo;這會使你的寄件夾有很多信件. 除非有必要否則請勿用此功能!";

//Everyone
$lang['word_everyone'] = "大家";

//Administrators
$lang['word_administrators'] = "管理員";

//Pictures
$lang['word_pictures'] = "圖片";

//Sort by
$lang['sortby'] = "排列:";

//Order
$lang['word_order'] = "順序";

//Per page
$lang['perpage'] = "每頁";

//Keywords
$lang['word_keywords'] = "關鍵字";

//(please login)
// context = Placeholder. Substitute for an e-mail address
$lang['plzlogin'] = "請登入";

//Pending
$lang['word_pending'] = "尚未啟用";

//You do not have the credentials to access flag modification.
$lang['err_modflags'] = "你沒有權限修改其他會員的權限.";

//Warning:  be careful not to downgrade your own rank
$lang['warn_modflags'] = "警告:請勿把你自己的權限降級";

//Admin rank
$lang['adminrnk'] = "管理員權限";

//current
$lang['word_current'] = "現在";

//You do not have the credentials to access the password reset.
$lang['retpwderr'] = "你沒有足夠權限境入密碼重設.";

//Only use this feature if a member cannot change their password in the profile editor, and they cannot use the password recovery feature because their recorded e-mail is not working.
$lang['newpassmsg1'] = "用此功能如果會員無法在會員資料編輯器改變它的密碼,然後他們無法使用密碼恢復功能因為他們的E-mail信箱壞掉了.";

//Valid password in database
$lang['validpwdb'] = "在資料庫中的有效密碼";

//You do not have draw access. Login to draw, or ask an administrator for details about receiving access.
$lang['err_drawaccess'] = "你沒有畫圖的權限. 請登入後畫圖,或詢問管理員要如何取得畫圖權限.";

//Public retouch disabled.
$lang['pubretouchdis'] = "公用編輯尚未啟用.";

//Incorrect password to retouch!
$lang['errrtpwd'] = "不正確的編輯密碼!";

//You have too many unfinished pictures!  Use Recover Pics menu to finish one.
$lang['munfinishpic'] = "你有太多尚未完成的圖片了! 用恢復圖片表單以完成它們.";

//You have an unfinished picture!  Use Recover Pics menu to finish it.
$lang['aunfinishpic'] = "你有一個尚未完成的圖片! 用恢復圖片表單以完成它.";

//Resize PaintBBS to fit window
$lang['resizeapplet'] = "調整PaintBBS的大小,讓他跟視窗一樣大";

//Hadairo
$lang['pallette1'] = "肌色系";

//Red
$lang['pallette2'] = "赤色系";

//Yellow
$lang['pallette3'] = "黃橙色系";

//Green
$lang['pallette4'] = "綠色系";

//Blue
$lang['pallette5'] = "青色系";

//Purple
$lang['pallette6'] = "紫色系";

//Brown
$lang['pallette7'] = "褐色系";

//Character
$lang['pallette8'] = "人物";

//Pastel
$lang['pallette9'] = "粉色系";

//Sougen
$lang['pallette10'] = "草原大地";

//Moe
$lang['pallette11'] = "萌色系";

//Grayscale
$lang['pallette12'] = "灰階";

//Main
$lang['pallette13'] = "主要色系";

//Wac!
$lang['pallette14'] = "Wac色系!";

//Save Palette
$lang['savpallette'] = "儲存調色盤";

//Save Color Changes
$lang['savcolorcng'] = "儲存顏色改變";

//Palette
$lang['word_Palette'] = "調色盤";

//Brighten
$lang['word_Brighten'] = "調亮";

//Darken
$lang['word_Darken'] = "調暗";

//Invert
$lang['word_invert'] = "反轉(對調)";

//Replace all palettes
$lang['paletteopt1'] = "取代所有的調色盤";

//Replace active palette
$lang['paletteopt2'] = "取代所有活耀的調色盤";

//Append palette
$lang['apppalette'] = "附加調色盤";

//Set As Default Palette
$lang['setdefpalette'] = "設為預設調色盤";

//Palette Manipulate/Create
$lang['palletemini'] = "改變/建立調色盤";

//Gradation
$lang['word_Gradation'] = "漸層";

//Applet Controls
$lang['appcontrol'] = "塗鴉程式設定";

//Please note:
// context = help tips on for applets
$lang['plznote'] = "請注意:";

//Any canvas size change will destroy your current picture!
$lang['canvchgdest'] = "任何改變畫布的大小將會毀掉你現在的圖片";

//You cannot resize your canvas when retouching an older picture.
$lang['noresizeretou'] = "你無法在編輯舊圖片中調整你畫布的大小.";

//You may need to refresh the window to start retouching.
$lang['refreshbret'] = "在開始編輯前,你可能需要重新整理視窗.";

//Click the &ldquo;Float&rdquo; button in the upper left corner to go fullscreen.
$lang['float'] = "按下在左上角的&ldquo;浮動&rdquo;按鈕以靶塗鴉程式的視窗放到最大.";

//X (width)
$lang['canvasx'] = "寬度";

//Y (height)
$lang['canvasy'] = "高度";

//Modify
$lang['word_modify'] = "變更";

//Java Information
$lang['javaimfo'] = "Java資料";

//If you get an &ldquo;Image Transfer Error&rdquo;, you will have to use Microsoft VM instead of Sun Java.
$lang['oekakihlp1'] = "如果你得到&ldquo;Image Transfer Error(圖片傳送錯誤)&rdquo;的訊息, 你可能需要用Microsoft VM來代替Sun Java.";

//If retouching an animated picture and the canvas is blank, play the animation first.
$lang['oekakihlp2'] = "如果你再編輯一個圖片但是畫布是空白的,請先播放一下動畫.";

//Recent IP / host
$lang['reciphost'] = "最近的IP/伺服器";

//Send mailbox message
$lang['sendmailbox'] = "送出私人訊息";

//Browse all posts ({1} total)
$lang['browseallpost'] = "瀏覽所有的文章 ({1} 總共)";

//(Broken image)
// context = Placholder for missing image
$lang['brokenimage'] = "毀損圖片";

//(No animation)
// context = Placholder for missing animation
$lang['noanim'] = "沒有動畫";

//{1} seconds
$lang['recover_sec'] = "{1} 秒";

//{1} {1?minute:minutes}
$lang['recover_min'] = "{1} 分";

//Post now
$lang['postnow'] = "現在送出";

//Please read the rules before submitting a registration.
$lang['plzreadrulereg'] = "在送出註冊表單前,請先仔細閱讀規定.";

//If you agree to these rules
$lang['agreerulz'] = "如果你同依這些規定";

//click here to register
$lang['clickheregister'] = "按這裡註冊";

//Registration Submitted
$lang['regisubmit'] = "註冊內容成功送出";

//Your registration for &ldquo;{1}&rdquo; is being processed.
// {1} = Oekaki title
$lang['urgistra'] = "你在 &ldquo;{1}&rdquo; 的註冊正在處理中";

//Your registration for &ldquo;{1}&rdquo; has been approved!<br /><br />You may now configure your membership profile.<br /><br /><a href=\"editprofile.php\">Click here to edit your profile.</a>
// {1} = Oekaki title
$lang['urgistra_approved'] = "你在 &ldquo;{1}&rdquo; 已經被批准了!<br /><br />你現在可以以新會員的身分登入,你可以在&ldquo;我的塗鴉&rdquo;表單中更新你的會員資料.";

//Before you may login, an administrator must approve your registration.  You should receive an e-mail shortly to let you know if your account has been approved.<br /><br />Once approved, you may update your member profile via the &ldquo;My Oekaki&rdquo; menu.
$lang['aprovemsgyes'] = "在你可以登入之前,管理員必須批准你的註冊. 如果你的帳號已被開通,系統會盡快的E-mail通知你.<br /><br />在批准之後,你可以在&ldquo;我的塗鴉&rdquo;表單中更新你的會員資料.";

//Please check your e-mail soon for the link to activate your account.<br /><br />Once your e-mail has been verified, you will be automatically logged in as a new member, and will be able to add information to your profile.
$lang['aprovemsgno'] = "請盡快到你的e-mail信箱收信已啟用你的帳號.<br /><br />當你的E-mail驗證過後,你將能夠以新會員的身分登入,然後你就可以立刻更新你的資料.";

//Notes About Registering
$lang['nbregister'] = "註冊注意事項";

//DO NOT REGISTER TWICE.
$lang['registertwice'] = "請勿註冊兩次以上.";

//You can check if you're in the pending list by viewing the member list and searching for your username.
$lang['regmsg1'] = "你可以到會員列表中尋找你的會員名以確認你是不是待批准的會員.";

//Use only alphanumeric characters for names and passwords.  Do not use quotes or apostrophes.  Passwords are case-sensitive.
$lang['regmsg2'] = "只能用英文字母和數字的使用者名稱和密碼. 請勿用引號或雙影號. 密碼會分大小寫.";

//You may change anything in your profile except your name once your registration is accepted.
$lang['regmsg3'] = "當你的會員資格被批准後,你可以改變任何你的個人資料,除了你的使用者名稱以外.";

//You must wait for an administrator to approve your registration on this board.  Your registration approval may take awhile if no one at the moment has time to maintain the pending list.  Please be patient; you will receive an e-mail notifying you of your approval.
$lang['regmsg4'] = "你必須等待管理員批准你的註冊. 你可能要等一些時間讓管理員審核和批准你的註冊,請有耐心的等待. 當你被批准時,系統將會E-mail通知.";

//If you don't receive an e-mail with a verification code, or if you cannot activate your account via e-mail, contact an administrator for help.  Administrators may manually approve your account in these cases.
$lang['regmsg5'] = "如果你沒收到任何驗證信的,或你沒辦法用E-mail啟用你的帳號,請聯絡管理員. 管理員可以手動批准你的帳號.";

//Your password can be mailed to you if you forget it.  <strong>Your e-mail will only be visible to other registered members.</strong>  You can remove or edit your e-mail after registration.  Ask the board owner about other potential privacy concerns.
$lang['regmsg6'] = "如果你忘記你的密碼,系統可以寄給你. <strong>只有註冊的會員看的到你的E-mail地址.</strong> 你的會員資格被批准以後,你可以移除或編輯你的E-mail地址. 如果你對隱私權有疑問時,請洽詢本塗鴉板的擁有者.";

//{1}+ Age Statement
// {1} = minimum age. Implies {1} age or older
$lang['agestatement'] = "{1}+ 年齡聲明";

//<strong>This oekaki is for adults only.</strong>  You are required to declare your birth year to register.  Year is required, month and day are optional and may be left blank.
$lang['adultonlymsg'] = "<strong>這是一個成人的塗鴉板.</strong> 你必須聲明你的年齡才可註冊. 出生年份是需要的,日和月可填可不填.";

//A link to your webpage, or a direct link to a sample of your artwork.  Not required for registration on this board.
$lang['nbwebpage'] = "你網站的連結,或是一個到你塗鴨作品的連結. 在此塗鴉板的注測程序並不需要.";

//Submit Registration
$lang['subregist'] = "送出註冊";

//Could not fetch information about picture
$lang['coulntfetipic'] = "無法取得圖片資訊";

//No edit number specified
$lang['noeditno'] = "沒有編輯的次數";

//This picture is available to all board members.
$lang['picavailab'] = "這圖片可以給全部的會員編輯.";

//The edited version of this image will be posted as a new picture.
$lang['retouchmsg2'] = "此圖片的編輯版本將被另存成新圖片.";

//The original artist will be credited automatically.
$lang['retouchmsg3'] = "原作者將主動的被感謝.";

//A password is required to retouch this picture.
$lang['retouchmsg4'] = "需要編輯密碼才能編輯此圖片.";

//The retouched picture will overwrite the original
$lang['retouchmsg5'] = "被編輯的圖片將取代原版圖片";

//Continue
$lang['word_continue'] = "繼續";



/* sqltest.php */

//SQL direct call
// context = Can use the SQL tool
$lang['st_sql_header'] = '直接呼叫SQL語法';

//Original:
$lang['st_orig_query'] = '原始語法:';

//Evaluated:
// context = "Processed" or "Computed"
$lang['st_eval_query'] = '執行過的語法:';

//Query okay.
$lang['st_query_ok'] = '查詢沒問題.';

//{1} {1?row:rows} affected.
$lang['st_rows_aff'] = '{1} 列資料被影響.';

//First result: &ldquo;{1}&rdquo;
$lang['st_first_res'] = '第一個結果: &ldquo;{1}&rdquo;';

//Query failed!
$lang['st_query_fail'] = '查詢失敗!';

//Database type:
// context = Which brand of database (MySQL, PostgreSQL, etc.)
$lang['st_db_type'] = '資料庫種類:';

//&nbsp;USE THIS TOOL WITH EXTREME CAUTION!  Detailed SQL knowledge required!&nbsp;
// context = This is a BIG warning with a large font.
$lang['st_big_warn'] = '&nbsp;請非常小心使用此功能! 必須要深熟SQL資料庫系統及語法!&nbsp;';

//Type a raw SQL query with no ending semicolon.  PHP strings will be evaluated.  Confirmation will be requested.
$lang['st_directions'] = '請輸入一個SQL語法或查詢,不要在後面加上;. PHP 語法將會被執行. 執行前將會經過確認.';

//Version
$lang['st_ver_btn'] = '版本';

/* END sqltest.php */



/* testinfo.php */

//Diagnostics page available only to owner.
$lang['testvar1'] = '只有此塗鴉板的擁有者才能使用錯誤診斷頁面';

//<strong>Folder empty</strong>
$lang['d_folder_empty'] = '<strong>空白資料夾</strong>';

//DB info
$lang['dbinfo'] = '資料庫資料';

// Database version:
$lang['d_db_version'] = '資料庫版本:';

//Total pictures:
$lang['d_total_pics'] = '圖片總數:';

//{1} (out of {2})
// {1} = existing pictures, {2} = maximum
$lang['d_pics_vs_max'] = '{1} (在 {2} 個圖片中的)';

//Archives:
$lang['d_archives'] = '資料庫:';

//WIPs and recovery:
$lang['d_wip_recov'] = 'WIPs 與恢復圖片:';

//Current picture number:
$lang['d_cur_picno'] = '現在圖片編號:';

//<strong>Cannot read folder</strong>
$lang['d_no_read_dir'] = '<strong>無法讀取資料夾</strong>';

//SQL direct calls:
// context = Can use the SQL tool
$lang['d_sql_direct'] = 'SQL 直接呼叫語法:';

//Available <a href="{1}">(click here)</a>
$lang['d_sql_avail'] = '可以 <a href="{1}">(請按這)</a>';

//Config
$lang['d_word_config'] = '設定';

//PHP Information:
$lang['d_php_info'] = 'PHP 資訊:';

//{1} <a href="{2}">(click for more details)</a>
// {1} = PHP version number
$lang['d_php_ver_num'] = '{1} <a href="{2}">(請按此取得更多資訊)</a>';

//Config version:
$lang['configver'] = '設定版本:';

//Contact:
$lang['word_contact'] = '連絡:';

//Path to OP:
$lang['pathtoop'] = '塗鴉板路徑:';

//Cookie path:
$lang['cookiepath'] = 'Cookie路徑:';

//Cookie domain:
// context = domain: tech term used for web addresses
$lang['cookie_domain'] = 'Cookie domain:';

//Cookie life:
// context = how long a browser cookie lasts
$lang['cookielife'] = 'Cookie壽命:';

//(empty)
// context = placeholder if no path/domain set for cookie
$lang['cookie_empty'] = '(空白)';

//{1} seconds (approximately {2} {2?day:days})
$lang['seconds_approx_days'] = '{1} 秒 (大約為 {2} 天)';

//Public images: // 'publicimg'
$lang['d_pub_images'] = '公用圖片:';

//Safety saves:
$lang['safetysaves'] = '安全儲存:';

//Yes ({1} days)
// {1} always > 1
$lang['d_yes_days'] = '是 ({1} 天)';

//No ({1} days)
$lang['d_no_days'] = '否 ({1} 天)';

//Pictures folder
$lang['d_pics_folder'] = '圖片資料夾';

//Notice:
$lang['d_notice'] = '公告:';

//Folder:
$lang['d_folder'] = '資料夾:';

//Total files:
$lang['d_total_files'] = '全部的檔案:';

//Total space used:
$lang['d_space_used'] = '全部已使用的空間:';

//Average file size:
$lang['d_avg_filesize'] = '平均檔案大小:';

//Images:
$lang['d_images_label'] = '圖片:';

//{1} ({2}%)
// {1} = images, {2} = percentage of folder
$lang['d_img_and_percent'] = '{1} ({2}%)';

//Animations:
$lang['d_anim_label'] = '動畫:';

//{1} ({2}%)
// {1} = animations, {2} = percentage of folder
$lang['d_anim_and_percent'] = '{1} ({2}%)';

//Other filetypes:
$lang['d_other_types'] = '其他檔案類型:';

//Locks
$lang['word_locks'] = '鎖定';

// Okay
// context = file is "writable" or "good".
$lang['word_okay'] = 'OK';

//<strong>Locked</strong>
// context = "Unwritable" or "Unavailable" rather than broken or secure
$lang['word_locked'] = '被鎖定';

//<strong>Missing</strong>
$lang['word_missing'] = '少掉';

/* END testinfo.php */



//You do not have the credentials to upload pictures.
$lang['err_upload'] = "你沒有上傳圖片的權限.";

//Picture to upload
$lang['pictoupload'] = "要上傳的圖片";

//Valid filetypes are PNG, JPEG, and GIF.
$lang['upldvalidtyp'] = "有效的圖片格式是PNG,JPEG,和GIF.";

//Animation to upload
$lang['animatoupd'] = "要上傳的動畫";

//Matching picture and applet type required.
$lang['uploadmsg1'] = "動畫必須符合圖片和所用的塗鴉程式";

//Valid filetypes are PCH, SPCH, and OEB.
$lang['uploadmsg2'] = "有效的動畫格式是PCH,SPCH,和OEB.";

//Valid filetypes are PCH and SPCH.
$lang['uploadmsg3'] = "有效的檔案格式是PCH和SPCH.";

//Applet type
$lang['appletype'] = "塗鴉程式";

//Time invested (in minutes)
$lang['timeinvest'] = "畫圖時間(分鐘)";

//Use &ldquo;0&rdquo; or leave blank if unknown
$lang['uploadmsg4'] = "如果不知道,請填&ldquo;0&rdquo;或留空";

//Download
$lang['word_download'] = "下載";

//This window refreshes every {1} seconds.
$lang['onlinelistmsg'] = "此視窗在每{1}秒內重新整理.";

//Go to page
$lang['gotopg'] = "到那頁";

//Netiquette applies.  Ask the admin if you have any questions.
// context = Default rules
$lang['defrulz'] = "需要遵守基本規定. 有任何問題請洽詢管理員.";

//Send reason
$lang['sendreason'] = "送出理由";

//&ldquo;avatar&rdquo; field does not exist in database.
$lang['err_favatar'] = "資料庫沒有&ldquo;頭像&rdquo;一欄.";

//Get
$lang['pallette_get'] = "取得";

//Set
$lang['pallette_set'] = "設為";

// Diagnostics
$lang['header_diag'] = '疑難排解(只有管理員可以使用)';

// Humanity test for guest posts?
$lang['cpanel_humanity_infoask'] = "客人貼圖時的人性化測試?";

// If yes, guests are required to pass a humanity test before posting comments.  The test must be passed only once.
$lang['cpanel_humanity_sub'] = "如果是,客人在張貼文章前需要通過一次人性化測試.";

// And now, for the humanity test.
$lang['humanity_notify_sub'] = "這人性化測試.";

// If canvas is blank or broken, <a href=\"{1}\">click here to import canvas, not animation</a>.
$lang['shi_canvas_only'] = "如果畫板是空白的或者是壞掉的<a href=\"{1}\">請按這裡匯入圖片,將不匯入動畫</a>.";

//For help with installation, read the &ldquo;readme.html&rdquo; file that came with your Wacintaki distribution.  Make sure you have CHMOD all files appropriately before continuing with installation.  For technical assistance, please visit the <a href="http://www.NineChime.com/forum/">NineChime Software Forum</a>.
$lang['assist_install'] = "如需安裝上的協助,請閱讀在Wacintaki裡面的 &ldquo;readme.html&rdquo; 檔案. 在安裝前請先確定所有檔案CHMOD權限設定都是對的,如需技術上的協助,請到 <a href=\"http://www.NineChime.com/forum/\">NineChime 軟體論壇</a>.";

//The installer only sets mandatory information.  Once the board has been installed, use the Control Panel to fully configure the board.
$lang['assist_install2'] = "安裝程式只會設定必須要的資訊.當安裝程式設定好之後,請使用控制台設定塗鴉板.";

//<strong>None</strong> will disable thumbnails, and uses a lot of bandwidth.  <strong>Layout</strong> will keep most pictures their original dimensions, and usually uses a vertical layout for wide pictures to keep comments readable.  <strong>Scaled</strong> will use thumbnails for wide pictures, and favor horizontal layout.  <strong>Uniformity</strong> makes all the pictures the same size with a small thumbnail.
$lang['thumbmodesexp'] = "<stron>無</strong> 將會取消縮圖功能從而耗費大頻寬  <strong>版面</strong> 將會使的大多圖片維持它的長寬比例,而且寬圖將會使用垂直版面使回文可以更容易看見.  <strong>縮放</strong> 將會對寬圖使用縮圖及水平排版.  <strong>一致性</strong> 將會使所有同樣大小的圖片使用同樣大小的縮圖.";

//Resize to this:
$lang['resize_to_this'] = '縮放到這尺寸:';

//Show e-mail to members
$lang['email_show'] = '顯示 e-mail 地址給會員';

//Show smilies
$lang['smilies_show'] = '顯示表情符號';

//Host lookup disabled in &ldquo;hacks.php&rdquo; file.
$lang['hosts_disabled'] = '伺服器查詢在 &ldquo;hacks.php&rdquo; 中不被啟用.';

//Reminder
$lang['word_reminder'] = '提醒';

//Anti-spam
$lang['anti_spam'] = '反垃圾郵件';

//(Delete without sending e-mail)
$lang['anti_spam_delete'] = '(刪除不用E-mail通知)';

//Log
$lang['word_log'] = '紀錄';

//You must be an administrator to access the log.
$lang['no_log_access'] = '你必須是管理員才可以看到紀錄.';

//{1} entries
$lang['log_entries'] = '{1} 條紀錄';

//Category
$lang['word_category'] = '種類';

//Peer (affected)
$lang['log_peer'] = '週遭 (被影響的)';

//(Self)
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['log_self'] = '(自己)';

//Required.  Guests cannot see e-mails, and it may be hidden from other members.
$lang['msg_regmail'] = "必須. 訪客無法看見e-mail地址,而且E-mail地址某些會員也看不到.";

//Normal Chibi Paint
$lang['online_npaintbbs'] = '一般的 Chibi Paint';

//No Animation
$lang['draw_no_anim'] = '(只有圖層沒有動畫)';

//Purge All Registrations
$lang['purge_all_regs'] = "刪除所有註冊";

//Are you sure you want to delete all {1} registrations?
$lang['sure_purge_regs'] = "你確定你要刪除 {1} 個註冊會員麻?";

//Animations/Layers
$lang['draw_anims_layers'] = '動畫/圖層';

//Most applets combine layers with animation data.
$lang['draw_combine_layers'] = '大多塗鴉程式與圖層和動畫結合.';

//Helps prevent accidental loss of pictures.
$lang['draw_help_loss_pics'] = '可以防止意外性遺失圖片.';

//Window close confirmation
$lang['draw_close_confirm'] = '視窗關閉確認';

//Remember draw settings
$lang['draw_remember_settings'] = '記得畫圖設定';

//Any reduction to the number of pictures stored requires confirmation via the checkbox.
$lang['pic_store_change_confirm'] = '任何減少儲存圖面數的動作需要經過勾選鈕才可以.';

//Check this box to confirm any changes
$lang['cpanel_check_box_confirm'] = '請在這邊打勾以確認變更';

//Use Lytebox viewer
$lang['cpanel_use_lightbox'] = '使用Lytebox觀看器';

//Enables support for the zooming Lytebox/Lightbox viewer for picture posts.
$lang['cpanel_lightbox_sub'] = '啟用 Lytebox/Lightbox 放大圖片觀看器.';

//An Administrator has all the basic moderation functions, including editing the notice and banner, banning, changing member flags, and recovering pictures.
$lang['type_aabil'] = '一個管理員可以設定整個版面,包含變更公佈和網站橫幅,封鎖與變更會員,和恢復遺失在系統中的圖片.';

//Moderator
$lang['word_moderator'] = '版主';

//Moderators have the ability to edit and delete comments, upload, as well as edit post properties (lock, bump, adult, WIP).
$lang['type_mabil'] = '版主可以編輯或者是刪除文章及圖片,也可以變更文章屬性 (鎖定, 置頂, 成人, WIP).';

//Who are the Moderators?
$lang['faq_who_mods'] = '誰是版主?';

//Use Lightbox pop-up viewer
$lang['profile_use_lightbox'] = '使用跳出式圖片觀看器';

//Enables pop-up 
$lang['profile_lightbox_sub'] = '啟用跳出式 Lightbox/Lytebox 圖片觀看器來代替開新視窗的圖片.';

//{1} {1?day:days} remaining
$lang['recovery_days_remaining'] = "剩下{1}天";

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_notice'] = "你有 {1} 幅尚未完成的圖片 請在畫新圖片之前先完成此圖.";

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_warning'] = "你有 {1} 幅尚未完成的圖片 請在畫新圖片之前先完成此圖.";

//(Default only)
// context = Placeholder if only one template available
$lang['default_only'] = '(預設面板)';

//Private Oekaki
$lang['header_private_oekaki'] = '私人塗鴉';

//You are not logged in.  Please log in or register to view this oekaki.
$lang['private_please_login'] = '你尚未登入,請先登入或註冊再瀏覽此塗鴉.';

//Requires members to register or log in to view art and comments.  Guests are completelly blocked.
$lang['private_oekaki_sub'] = '需要會員登入才能看到畫作和文章.訪客將都看不到任何成品.';

//Server is able to send e-mail?
$lang['able_send_email'] = '伺服器可以發送任何電子郵件??';

//Safety Save Limit
$lang['header_safety_max'] = '安全儲存容量';

//Maximum number of unfinished pictures a member may have at a time.  Default is {1}.
$lang['safety_max_sub'] = '一個會員最多可已儲存多少尚未完成的圖畫,預設為 {1}.';

//You need Java&trade; to use the paint program.  <a href="{1}">Click here to download Java</a>.
$lang['paint_need_java_link'] = '你需要安裝 Java&trade; 才可以使用塗鴉程式  <a href="{1}">請按這裡下載 Java</a>.';

//Don't know / I Don't know
// context = drop-down menu option
$lang['option_dont_know'] = "我不知道";

//{1} folder is locked!  Folder must be CHMOD to 755 (preferred) or 777.  View Readme.txt for help.
// NOTE: no HTML.  {1} = folder, {2} = manual
$lang['boot_folder_locked'] = '{1}  資料夾被鎖定了! 資料夾至少需要被 CHMOD 為 755 (比較好) 或者是 777. 請看 {2} 取得幫助.';

//Installer removed.
$lang['boot_inst_rm'] = '安裝程式已經成功的移除.';

//Installer removal failed!
$lang['boot_inst_rm_fail'] = '安裝程式移除失敗!';

//Updater removed.
$lang['boot_update_rm'] = '更新程式移除成功.';

//Updater removal failed!
$lang['boot_update_rm_fail'] = '更新程式移除失敗!';

//Proceed to index page
$lang['boot_goto_index'] = '到首頁';

//Remove the files manually via FTP
$lang['boot_remove_ftp'] = '使用FTP手動移除檔案';

//&ldquo;install.php&rdquo; and/or &ldquo;update.php&rdquo; still exists!
$lang['boot_still_exist_sub1'] = '&ldquo;install.php&rdquo; 和/或 &ldquo;update.php&rdquo; 還存在!';

//If you get this error again, delete the files manually via FTP.
$lang['boot_still_exist_sub3'] = '如果你還看到這些錯誤,請用FTP手動刪除這些檔案.';

//Password verification failed. Make sure the new password is typed properly in both fields
$lang['pass_ver_failed'] = '密碼確認錯誤. 請確定同樣的新密碼在兩欄都有打出來.';

//Password contains invalid characters: {1}
$lang['pass_invalid_chars'] = '密碼包含不合法的符號: {1}';

//Password is empty.
$lang['pass_emtpy'] = '密碼是空白的.';

//Username contains invalid characters: {1}
$lang['name_invalid_chars'] = '使用者名稱包含不合法符號: {1}';

//Humanity test failed.
$lang['humanity_test_failed'] = '沒通過人性化測試.';

//You must submit a valid age declaration (birth year).
$lang['submit_valid_age'] = '你需要一個年齡的宣告 (出生年).';

//Your age declaration could not be accepted.
$lang['age_not_accepted'] = '你的出生年宣告不無法被接受.';

//A valid URL is required to register on this BBS
$lang['valid_url_req'] = '請輸入合法的網址.';

//You must declare your age to register on this BBS
$lang['must_declare_age'] = '你必須宣告你的年齡才可以在此討論版註冊.';

//Sorry, the BBS e-mailer isn't working. You'll have to wait for your application to be approved.
$lang['email_wait_approval'] = "抱歉,討論版的e-mail程式有錯誤,你必須要等到你的申請被批准.";

//Database error. Please try again.
$lang['db_err'] = '資料庫錯誤,請再試一次.';

//Database error. Try using {1}picture recovery{2}.
// {1}=BBCode start tag, {2}=BBCode end tag
$lang['db_err_pic_recovery'] = '資料庫錯誤. 請嘗試使用 {1}圖片恢復{2}.';

//You cannot post a comment because the thread is locked
$lang['no_post_locked'] = '你無法在此貼文,因為此討論串已經被鎖定.';

//HTML links unsupported.  Use NiftyToo/BBCode instead.
$lang['no_html_alt'] = '不支援HTML超連結.  請用 NiftyToo/BBCode 代碼.';

//Guests may only post {1} {1?link:links} per comment on this board.
$lang['guest_num_links'] = '訪客只能在每篇回應中貼上 {1} 個超連結.';

//You must be a moderator to mark pictures other than yours as adult.
$lang['mod_change_adult'] = '你必須是版主才能把不是你的圖片標示為成人圖片.';

//Only moderators may change safety save status.
$lang['mod_change_wip'] = '只有版主才可以改變安全儲存的狀態.';

//Only moderators may use this function.
$lang['mod_only_func'] = '版主才可以使用此功能.';

//No mode!  Some security policies or advertisements on shared servers may interfere with comments and picture data.  This is a technical problem.  Ask your admin for help.
$lang['func_no_mode'] = '沒有模式! 這是一個技術上的問題,某些共享網路伺服器的安全設定和廣告會影響畫作和回應的排版,請洽詢管理員已取得幫助.';

/* End Version 1.5.5 */



/* Version 1.5.6 */

//Registed
// context = Date on which a member "Submit registration" or "Signed up"
// {1} = count of pending registrations
$lang['registered_on'] = '已註冊';

//Modify Canvas Size (max is {1} &times; {2})
$lang['applet_modify'] = "修改圖片的長寬度 (最大的是 {1}&times;{2})";

//Canvas (min: {1}&times;{2}, max: {3}&times;{4})
$lang['draw_canvas_min_max'] = '畫布 (最小: {1}&times;{2}, 最大: {3}&times;{4})';

//If you're having trouble with the applet, try downloading the latest version of Java from {1}.
$lang['javahlp'] = "如果你用此程式發生一些問題,請嘗試下載新版在 {1}.";

//If you do not need them anymore, <a href="{1}">click here to remove them</a>.
$lang['boot_still_exist_sub2'] = '如果你再需要這個東西, <a href="{1}">請按此移除</a>.';

//Delete Palette
$lang['delete_palette'] = '刪除調色盤';

//You may have {1} safety {1?save:saves} at a time.  Remember to finish a safety save soon or it will be automatically deleted within {2} {2?day:days}.
$lang['safesavemsg2'] = "你可以同時有 {1} 個安全儲存.記得要趕快完成這個圖片,否則會被系統自動刪除 {2} 天";

//Safety save was successful!  To resume a safety save, click &ldquo;Draw&rdquo;, or use the &ldquo;Recover Pics&rdquo; menu.
$lang['safesavemsg3'] = "成功執行安全儲存! 要編輯一個安全儲存的圖片,請用在選單上的&ldquo;畫&rdquo;,或&ldquo;恢復圖片&rdquo;選單";

//Every time you retouch your safety save, the delete timer will be reset to {1} {1?day:days}.
$lang['safesavemsg5'] = "每次你編輯你在安全儲存的圖片,自動刪除時間會重新計算 {1} 天";

//Error reading picture #{1}.
$lang['err_readpic'] = "錯誤讀取圖片 {1}";

//What is {1} {2} {3}?
//What is  8   +   6 ?
$lang['humanity_question_3_part'] = "{1} {2} {3} 等於多少?";

//Safety saves are stored for {1} {1?day:days}.
$lang['sagesaveopt3'] = "成功安全儲存給 {1} 天";

//Comments (<a href="{1}">NiftyToo Usage</a>)
$lang['header_comments_niftytoo'] = '留言 (<a href="{1}">NiftyToo用法</a>)';

//Edit Comment (<a href="{1}">NiftyToo Usage</a>)
$lang['ecomm_title'] = '編輯回覆 (<a href="{1}">NiftyToo用法</a>)';

//Edit Picture Info (<a href="{1}">NiftyToo Usage</a>)
$lang['erpic_title'] = '編輯 / 恢復圖片資料 (<a href="{1}">NiftyToo用法</a>)';

//Message Box (<a href="{1}">NiftyToo Usage</a>)
$lang['chat_msgbox'] = '訊息盒 (<a href="{1}">NiftyToo用法</a>)';

//(<a href="{1}">NiftyToo Usage</a>)
$lang['common_niftytoo'] = '(<a href="{1}">NiftyToo用法</a>)';

//(Original by <strong>{1}</strong>)
// {1} = member name
$lang['originalby'] = "(作者: <strong>{1}</strong>)";

//If you are not redirected in {1} seconds, click here.
// context = clickable, {1} defaults to "3" or "three"
$lang['common_redirect'] = '如果你在三秒內沒被轉頁,請按這裡';

//Could not write config file.  Check your server permissions.
$lang['cpanel_cfg_err'] = "無法開啟設定檔config.php以讀寫. 請確認你伺服器的設定";

//Enable Mailbox
$lang['enable_mailbox'] = "啟用信箱";

//Unable to read picture #{1}.
$lang['delconf_pic_err'] = '無法讀取圖片 #{1}.';

//Image too large!  Size limit is {1} &times; {2} pixels.
$lang['err_imagelar'] = "圖片太大! 最大圖片大小是 {1}&times;{2} 像素";

//It must not be larger than {1} &times; {2} pixels.
$lang['notlarg'] = "這一定要大於 {1}&times;{2} 像素";

//(No avatar)
$lang['noavatar'] = "不要頭像";

//Print &ldquo;Edited on (date)&rdquo;
// context = (date) is a literal.  Actual date not printed.
$lang['print_edited_on'] = "顯示被編輯 (date)";

//(Edited on {1})
// {1} = current date
$lang['edited_on'] = "被編輯 {1}";

//Print &ldquo;Edited by {1}&rdquo;
// {1} = admin name
$lang['print_edited_by_admin'] = "顯示編輯 {1}";

//(Edited by <strong>{1}</strong> on {2})
// {1} = admin name, {2} = current date
$lang['edited_by_admin'] = "顯示編輯 {1} on {2}";

//You may check this if you are an adult (at least {1} years old).
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adultsub'] = "你如果是 {1}+ 才能勾選此項";

//Load time {1} seconds
$lang['footer_load_time'] = '載入時間 {1} 秒';

//Links disabled for guests
// context = HTML-formatted links in comments on pictures
$lang['no_guest_links'] = '訪客不能使用超連結功能.';

//{1}+
// context = Marks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['mark_adult'] = '{1}+';

//Un {1}+
// context = Unmarks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['unmark_adult'] = '解除 {1}+ 標示';

//Mailbox:
$lang['mailbox_label'] = '信箱:';

//{1} {1?message:messages}, {2} unread
$lang['mail_count'] = '{1} 訊息, {2} 尚未閱讀';

//From:
$lang['from_label'] = '從:';

//Re:
// context = may be used inconsistently for technical reasons
$lang['reply_label'] = '回覆:';

//Subject:
$lang['subject_label'] = '主題:';

//Send To:
$lang['send_to_label'] = '傳送給:';

//Message:
$lang['message_label'] = '訊息:';

//<a href="{1}">{2}</a> @ {3}
// context = "{2=username} sent you this mailbox message at/on {3=datetime}"
$lang['mail_sender_datetime'] = '<a href="{1}">{2}</a> @ {3}';

//Registered: {1} {1?member:members} and {2} {2?admin:admins}
$lang['mmail_reg_list'] = '已註冊: {1} 會員, {2} 管理員';

//{1} {1?member:members} active within the last {2} days.
$lang['mmail_active_list'] = '在過去兩天中共有{1} 活動的會員.';

//Everyone ({1})
$lang['mmail_to_everyone'] = '大家 ({1})';

//Active members ({1})
$lang['mmail_to_active'] = '活動中的會員: ({1})';

//All admins/mods ({1})
// context = admins and moderators
$lang['mmail_to_admins_mods'] = '全部管理員 ({1})';

//Super-admins only
$lang['mmail_to_superadmins'] = '只有超級管理員';

//Flags: FLAG DESCRIPTION
// context = "Can Draw", or "Drawing ability", etc.
$lang['mmail_to_draw_flag']   = '記號: 圖畫';
$lang['mmail_to_upload_flag'] = '記號: 上傳';
$lang['mmail_to_adult_flag']  = '記號: 成人觀賞權限';
$lang['mmail_to_immune_flag'] = '記號: 防禦權限';

//<a href="{1}">Online</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_online'] = '<a href="{1}">上線的人</a> ({2})';

//<a href="{1}">Chat</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_chat'] = '<a href="{1}">聊天</a> ({2})';

//<strong>Rules</strong>
// context = normally in bold text
$lang['header_rules'] = '規定';

//<strong>Draw</strong>
// context = normally in bold text
$lang['header_draw'] = '圖畫';

//<a href="{1}">Mailbox</a> ({2})
// context = Used as link. {2} = count of messages
$lang['header_mailbox'] = '<a href="{1}">信箱</a> ({2})';

//Online
// context = Used as label. No HTML link. {1} = count of people (if desired)
$lang['chat_online'] = '上線的人';

//{1} {1?member:members} match your search.
$lang['match_search'] = '{1} 個會員符合你的搜尋';

//{1} {1?member:members}, {2} active within {3} days.
$lang['member_stats'] = '{1} 個會員中的 {2}個會員在過去  {3}天內有在塗鴉版內活動.';

//(None)
// context = placeholder if no avatar available
$lang['avatar_none'] = '(無)';

//No rank
// or "None". context = administrator rank
$lang['rank_none'] = '無';

//No Thumbnails
$lang['cp_no_thumbs'] = '無';

//No pictures
$lang['no_pictures'] = '沒有圖片';

//{1} (last login)
// {1} = IP address.
$lang['ip_by_login'] = '{1} (上次登入)';

//{1} (last comment)
$lang['ip_by_comment'] = '{1} (上次回復)';

//{1} (last picture)
$lang['ip_by_picture'] = '{1} (上次畫圖)';

//(None)
// context = placeholder if no url to web site
$lang['url_none'] = '無';

//(Web site)
// context = placeholder if there is a url, but no title (no space to print whole url)
$lang['url_substitute'] = '(網站)';

//(Default)
// context = placeholder if default template is chosen
$lang['template_default'] = '預設';

//(Default)
// context = placeholder if default language is chosen
$lang['language_default'] = '預設';

//Default
$lang['palette_default'] = '預設';

//No Title
$lang['no_pic_title'] = '沒有主題';

//Send E-mails
$lang['install_send_emails'] = '傳送電子郵件';

//Adjusts how many e-mails are sent by your server.  Default is &ldquo;Yes&rdquo;, and is highly recommended.
$lang['adjust_emails_sent_sub1'] = '調整伺服器傳送電子郵件的數量. 預設為&ldquo;是&rdquo;, 建議啟用此功能.';

//Minimal
// or "Minimum"
$lang['cpanel_emails_minimal'] = '最小的';

//&ldquo;Minimal&rdquo; will reduce e-mails by approximately {1}.  Choose &ldquo;No&rdquo; if your server cannot send e-mail.
// {1} = percentage or fraction
$lang['adjust_emails_sent_sub2'] = '&ldquo;最低&rdquo; 會大約減少{1}封電子郵件.  請選 &ldquo;不&rdquo; 如果你的伺服器無法傳送電子郵件.';

//No (recommended)
$lang['chat_no_set_reccom'] = '不 (建議)';

//Display - Mailbox
$lang['install_mailbox'] = '顯示-信箱';

//Sorry, the BBS e-mailer isn't working. Ask an admin to reset your password.
$lang['no_email_pass_reset'] = "抱歉, 塗鴉板的電子郵件發信功能現在無法發信, 請聯絡管理員重設你的密碼.";

//User deleted, but e-mailer isn't working. Notify user manually at {1}.
$lang['no_email_kill_notify'] = "使用者已經被刪除,但是電子郵件發信功能無法送信, 手動通知使用者 {1}.";

//<strong>Maintenance</strong>
// context = Highly visible notification if board is in maintenacne mode. Disables "Logout"
$lang['header_maint'] = '<strong>維護中</strong>';

//The oekaki is down for maintenance
// context = <h2> on plain page
$lang['boot_down_maint'] = '塗鴉板正在維護中';

//&ldquo;{1}&rdquo; should be back online shortly.  Send all quesions to {2}.
// {1} = oekaki title, {2} = admin e-mail
$lang['boot_maint_exp'] = '&ldquo;{1}&rdquo; 正在維護中,塗鴉板應該很快的就會在被啟用. 請把所有的問題送給 {2}.';

//Member name already exists!
$lang['func_reg_name_exists'] = '會員名稱已經存在!';

//You cannot access flag modification
$lang['func_no_flag_access'] = '你無法使用改變標記功能.';

//Account updated, but member could not be e-mailed.
$lang['func_update_no_mail'] = '帳號已被更新, 但是無法傳送電子郵件通知給會員.';

//Account rejected, but could not be e-mailed. Notify applicant manually at {1}'
// {1} = e-mail address
$lang['func_reject_no_mail'] = '帳號已被拒絕, 但是無法傳送電子郵件通知. 請到 {1} 手動通知申請者';

//Your age declaration could not be accepted.
$lang['func_bad_age'] = '你的年齡宣告無法被接受.';

//Image too large!  Size limit is {1} bytes.
$lang['err_imagelar_bytes'] = '圖片太大! 最大圖片大小是 {1} 位元組.';

//No picture data was received. Try again.
$lang['func_no_img_data'] = '沒有收到圖片資料,請再試一次.';

//An error occured while uploading. Try again.
$lang['func_up_err'] = '上傳圖片時發生錯誤,請再試一次.';



/* whosonline.php */

// context = nouns, not verbs
$lang['o_unknown']     = '未知';
$lang['o_addusr']      = '會員等待批准名單';
$lang['o_banlist']     = '封鎖名單';
$lang['o_chatbox']     = '聊天';
$lang['o_chibipaint']  = 'Chibi Paint';
$lang['o_chngpass']    = '變更密碼';
$lang['o_comment']     = '留言';
$lang['o_cpanel']      = '控制台';
$lang['o_delcomments'] = '刪除留言';
$lang['o_delpics']     = '刪除圖片';
$lang['o_delusr']      = '刪除會員';
$lang['o_draw']        = '畫的螢幕區域';
$lang['o_edit_avatar'] = '編輯頭像';
$lang['o_editcomm']    = '編輯留言';
$lang['o_editnotice']  = '編輯公告欄/橫幅';
$lang['o_editpic']     = '編輯圖片';
$lang['o_editprofile'] = '編輯會員個人資料';
$lang['o_editrules']   = '編輯版規';
$lang['o_faq']         = '答與問';
$lang['o_index']       = '觀賞';
$lang['o_index_match'] = '畫家觀賞次數';
$lang['o_lcommentdel'] = '刪除留言';
$lang['o_log']         = '紀錄';
$lang['o_lostpass']    = '恢復密碼';
$lang['o_mail']        = '信箱';
$lang['o_massmail']    = '大宗郵件信箱';
$lang['o_memberlist']  = '會員列表';
$lang['o_modflags']    = '會員權限';
$lang['o_newpass']     = '變更密碼';
$lang['o_niftyusage']  = '使用 Niftytoo';
$lang['o_notebbs']     = 'NoteBBS';
$lang['o_oekakibbs']   = 'OekakiBBS';
$lang['o_paintbbs']    = 'PaintBBS';
$lang['o_profile']     = '觀看個人資料';
$lang['o_recover']     = '圖片恢復';
$lang['o_retouch']     = '續繪';
$lang['o_shibbs']      = 'ShiPainter';
$lang['o_showrules']   = '版規';
$lang['o_sqltest']     = 'SQL疑難排解';
$lang['o_testinfo']    = '疑難排解';
$lang['o_upload']      = '上傳';
$lang['o_viewani']     = '動畫觀賞器';
$lang['o_whosonline']  = '上線名單';

/* END whosonline.php */



//Submit for review
$lang['submit_review'] = '送出给評價';

//<a href="http://www.NineChime.com/products/" title="Get your own free BBS!">{1}</a> by {2} / Based on <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> by <a href="http://suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
// {1} = "Wacintaki" + link
// {2} = "Waccoon" (may change)
$lang['f_bbs_credits'] = '<a href="http://www.NineChime.com/products/" title="取得你自己免費的塗鴉板!">{1}</a>  {2} 寫的 / 從 <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> 改編, <a href="http://suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a> 寫的';

//PaintBBS and Shi-Painter by <a href="http://shichan.jp/">Shi-chan</a> / ChibiPaint by <a href="http://www.chibipaint.com">Mark Schefer</a>
$lang['f_applet_credits'] = 'PaintBBS 和 Shi-Painter 作者: <a href="http://shichan.jp">Shi-chan</a> / ChibiPaint 作者: <a href="http://www.chibipaint.com">Mark Schefer</a>';

//Administrator Account
// context = default comment for admin account
$lang['install_admin_account'] = '管理員帳戶';

//If you are submitting a picture or just closing the window, click OK."
// context = JavaScript alert.  Browser/version specific, and may be troublesome.
$lang['js_noclose'] = '如果你再傳送圖片或者只是要關閉視窗,請按OK.';

//Comment
// context = verb form; label for making posts on pictures
$lang['verb_comment'] = '回應';

//&hellip;
// context = Placeholder if a comment is empty
$lang['no_comment'] = '&hellip;';

//OekakiPoteto 5.x by <a href="http://www.suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
$lang['install_byline'] = 'OekakiPoteto 5.x 作者: <a href="http://www.suteki.nu">RanmaGuy</a> 與 <a href="http://www.cellosoft.com">Marcello</a>';

//Wacintaki 1.x modifications by <a href="http://www.NineChime.com/products/">Waccoon</a>
// {1} = "Waccoon" (may change)
$lang['install_byline2'] = 'Wacintaki 1.x 改進 作者: <a href="http://www.NineChime.com/products/">{1}</a>';

//Wacintaki Oekaki: draw pictures online
// context = HTML head->meta name
$lang['meta_desc'] = 'Wacintaki Oekaki: 線上畫圖';

//(Link)
// context = Placeholder, link to a web page on the memberlist
$lang['ml_web_link'] = '(連結)';

//-
// context = Placeholder, no web page available on the memberlist
$lang['ml_no_link'] = '-';

//-
// context = Placeholder, no email available on the memberlist
$lang['ml_no_email'] = '-';

//&ldquo;{1}&rdquo; cannot be opened.
// {1} = filename
$lang['file_o_warn'] = '&ldquo;{1}&rdquo; 無法開啟.';



/* log.php */

// The log is a diagnostic tool.  Retain formatting and colons.
// Some filenames (lowercase, without '.php') are not translatable.

// Generic warning
$lang['l_'.WLOG_MISC] = '其他';

// Generic error/failure
$lang['l_'.WLOG_FAIL] = '失敗';

// SQL error
$lang['l_'.WLOG_SQL_FAIL] = 'SQL 執行失敗';

// Regular maintenance
$lang['l_'.WLOG_MAINT] = '維護';

// Input from client, hacking
$lang['l_'.WLOG_SECURITY] = '安全';

// Updates and events
$lang['l_'.WLOG_BANNER] = '橫幅';
$lang['l_'.WLOG_RULES]  = '版規';
$lang['l_'.WLOG_NOTICE] = '公告';
$lang['l_'.WLOG_CPANEL] = '控制台';
$lang['l_'.WLOG_THUMB_OVERRIDE] = '縮圖更改';
$lang['l_'.WLOG_THUMB_REBUILD]  = '縮圖重建';
$lang['l_'.WLOG_MASS_MAIL]    = '大宗郵件';
$lang['l_'.WLOG_BAN]          = '封鎖';
$lang['l_'.WLOG_DELETE_USER]  = '刪除使用者';
$lang['l_'.WLOG_REG]          = '註冊';
$lang['l_'.WLOG_APPROVE]      = '批准';
$lang['l_'.WLOG_EDIT_PROFILE] = '資料更改';
$lang['l_'.WLOG_FLAGS]        = '標示更改';
$lang['l_'.WLOG_PASS_RECOVER] = '密碼恢復';
$lang['l_'.WLOG_PASS_RESET]   = '密碼重設';
$lang['l_'.WLOG_ARCHIVE]      = '存舊';
$lang['l_'.WLOG_BUMP]         = '置頂';
$lang['l_'.WLOG_RECOVER]      = '恢復';
$lang['l_'.WLOG_DELETE]       = '刪除';
$lang['l_'.WLOG_LOCK_THREAD]  = '文章鎖定';
$lang['l_'.WLOG_ADULT]        = '成人';
$lang['l_'.WLOG_ADMIN_WIP]    = 'WIP (管理員)';
$lang['l_'.WLOG_EDIT_PIC]     = '圖片編輯';
$lang['l_'.WLOG_EDIT_COMM]    = '留言編輯';

//cpanel: Updated
$lang['l_c_update'] = '控制台:已更新';

//No RAW POST data
// context = "RAW POST" is a programming term for HTTP data
$lang['l_no_post'] = '沒有 RAW POST 資料';

//Cannot allocate new PIC_ID
$lang['l_no_picid'] = '無法發配新的PIC_ID(圖片編號)';

//Cannot insert image #{1}
$lang['l_app_no_insert'] = '無法插入圖片 #{1}';

//Cannot save image #{1}
$lang['l_app_no_save'] = '無法儲存圖片 #{1}';

//paintsave: Bad upload for #{1}, cannot make thumbnail
$lang['l_bad_upload'] = '畫圖儲存: 無效的 #{1} 上傳, 無法製作縮圖';

//paintsave: Cannot make &ldquot&rdquo; thumbnail for image #{1}
$lang['l_no_t'] = '畫圖儲存: 無法為  #{1} 製作 &ldquot&rdquo; 縮圖';

//paintsave: Cannot make &ldquo;r&rdquo; thumbnail for image #{1}
$lang['l_no_r'] = '畫圖儲存: 無法為  #{1} 製作 &ldquo;r&rdquo; 縮圖';

//paintsave: Bad datatype for image #{1} (saved as &ldquo;dump.png&rdquo;)
$lang['l_no_type'] = '畫圖儲存:  #{1} 的圖片格式無效 (儲存為 &ldquo;dump.png&rdquo;)';

//paintsave: Corrupt image dimentions for #{1}
$lang['l_no_dim'] = '畫圖儲存:  #{1} 的長寬維度無效';

//paintsave: cannot write image &ldquo;{1}&rdquo;
$lang['l_no_open'] = '畫圖儲存: 無法寫入圖片 &ldquo;{1}&rdquo;';

//Picture: #{1}
// context = "Picture #{1} affected"
$lang['l_mod_pic'] = '圖片: #{1}';

//Comment: #{1}
// context = "Comment #{1} affected"
$lang['l_mod_comm'] = '留言: #{1}';

//WIP: #{1}
// context = "WIP or Safety save #{1} affected"
$lang['l_mod_wip'] = 'WIP: #{1}';

//Active
// context: Active members.  Displayed under "Peer (affected)"
$lang['type_active'] = '活躍'; 

//Sent to:
// context = Password recovery.  {1} = E-mail address.
$lang['l_sent_to'] = '傳送給: {1}';

//Reset by: Admin
// context = Password reset
$lang['l_reset_admin'] = '密碼重設: 管理員';

//Reset by: Member
// context = Password reset
$lang['l_reset_mem'] = '密碼重設: 會員';

//Reason sent: Yes or No
// context = Reason for being deleted.  Yes or No only.
$lang['l_reason_yes'] = '理由送出: 是';
$lang['l_reason_no'] = '理由送出: 無';

//Accepted with the following flags: {1}
// {1} ~ 'GDU' or some other combination of letters
$lang['l_accept_f'] = '有這些標記的圖片已被接受: {1}';

//Profile: Updated
$lang['l_prof_up'] = '個人資料: 已更新';

//Banlist: Updated
$lang['l_ban_up'] = '封鎖名單: 已更新';

//Banner and notice: Updated
$lang['l_banner_notice_up'] = '橫幅與注意事項: 已更新';

//Rules: Updated
$lang['l_rules_up'] = '版規: 已更新';

//Upload: Cannot insert image &ldquo;{1}&rdquo;
$lang['l_f_no_insert'] = '上傳: 無法插入圖片 &ldquo;{1}&rdquo;';

//Upload: Cannot save image &ldquo;{1}&rdquo;
$lang['l_f_no_save'] = '上傳: 無法儲存圖片 &ldquo;{1}&rdquo;';

//Upload: Cannot save image #{1}
$lang['l_f_no_anim'] = '上傳: 無法儲存圖片 #{1}';

//Retouch: #{1}
// context = Bump by retouching picture.
$lang['l_f_bump_retouch'] = '續繪: #{1}';

//Locked: #{1}
// context = Thread #{1} locked or unlocked
$lang['l_f_lock']   = '鎖定: #{1}';
$lang['l_f_unlock'] = '反鎖定: #{1}';

//Marked as adult: #{1}
// context = [Un]marked #{1} as an adults-only picture
$lang['l_f_adult']   = '標記為成人: #{1}';
$lang['l_f_unadult'] = '移除成人標記: #{1}';

/* END log.php */



//Member &ldquo;{1}&rdquo; could not be found.
$lang['mem_not_found'] = '會員 &ldquo;{1}&rdquo; 無法找到.';

//Profile for &ldquo;{1}&rdquo; not retrievable!  Check the log.
$lang['prof_not_ret'] = '無法找到 &ldquo;{1}&rdquo; 的會員資料,請見日誌.';

//Cannot allocate new PIC_ID for upload.
$lang['f_no_picid'] = '無法給上傳的圖片一個PIC_ID(圖片編號).';

//You must be a moderator to lock or unlock threads.
$lang['functions_err22'] = '你必須是版主才能封鎖或者是解鎖圖片及留言.';

//Please run the <a href="{1}">updater</a>.
$lang['please_update'] = '請執行 <a href="{1}">更新程式</a>.';

//Current version: {1}
$lang['please_update_cur'] = '現在版本: {1}';

//New version: {1}
// context = version after update has completed
$lang['please_update_new'] = '新版本: {1}';



/* update.php */

//Starting update from {1} to {2}.
// {1} = from name+version, {2} = to name+version
$lang['up_from_to'] = '從 {1} 更新到 {2}.';

//Finished update to Wacintaki {1}.
// {1} = to version number
$lang['up_fin_to'] = '已經完成更新到 Wacintaki {1}.';

//Update to Wacintaki {1} failed.
// {1} = to version number
$lang['up_to_fail'] = '更新到 Wacintaki {1} 失敗.';

//Verifictaion of Wacintaki {1} failed.
// {1} = to version number
$lang['up_ver_fail'] = '確認 Wacintaki {1} 版本失敗.';

//STOP: Cannot read file &ldquo;{1}&drquo; for database connection.
// {1} = filename
$lang['up_cant_read_for_db'] = '停止: 無法讀取檔案 &ldquo;{1}&drquo; 取得資料庫連線.';

//STOP: Cannot write file &ldquo;{1}&rdquo;
// {1} = filename
$lang['up_cant_write_file'] = '停止: 無法寫入檔案 &ldquo;{1}&rdquo;';

//STOP: &ldquo;{1}&rdquo; file is not writable.
// {1} = filename
$lang['up_file_locked'] = '停止: &ldquo;{1}&rdquo; 是無法被寫入的.';

//WARNING: missing image &ldquo;{1}&rdquo; for post {2}.
// {1} = picture filename, {2} = number (ID_2)
$lang['up_missing_img'] = '警告: 文章 {2} 中的圖片 &ldquo;{1}&rdquo; 遺失.';

//WARNING:  Folder &ldquo;{1}&rdquo; is not writable.  CHMOD the folder to 775 or 777 after the updater has finished.
// {1} = folder name
$lang['up_folder_locked'] = '警告:  資料夾 &ldquo;{1}&rdquo; 無法寫入.  請在更新程式結束後設此資料夾CHMOD到775或777.';

//STOP: Unable to add admin ranks to database (SQL: {1})
// {1} db_error()
$lang['up_no_add_rank'] = '停止: 無法在資料庫中加入管理員資料 (SQL: {1})';

//STOP:  Could not set admin rank for {1} (SQL: {2})
// {1} = username
// {2} = db_error()
$lang['up_no_set_rank'] = '停止:  無法在資料庫中設定管理員權限 {1} (SQL: {2})';

//STOP: Could not create folder &ldquo;{1}&rdquo;.
// {1} = filename
$lang['up_cant_make_folder'] = '停止: 無法建立資料夾 &ldquo;{1}&rdquo;.';

//STOP: Could not update piccount (current picture number) for new sorting system (SQL: {1})
// {1} = db_error()
$lang['up_no_piccount'] = '停止: 無法為新的圖片編碼系統更新圖片計算次數  (SQL: {1})';

//STOP: Wax Poteto database not at required version (1.3.0).  Run the Wax Poteto 5.6.x updater first.
$lang['up_wac_no_130'] = '停止: Wax Poteto 資料庫的版本不是 (1.3.0). 請先執行Wax Poteto 5.6.x 更新程式.';

//STOP: Could not verify database version marker (SQL: {1})
// {1} = db_error()
$lang['up_no_set_db_utf'] = '停止: 無法確認資料庫版本 (SQL: {1})';

//NOTE: Remember to copy your resource files (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) into the Wacintaki &ldquo;resource&rdquo; folder and CHMOD them so they are writable.
$lang['up_move_res'] = '注意: 請把你的資源檔 (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) 複製到 Wacintaki &ldquo;resource&rdquo; 資料夾,並設 CHMOD 到它們有寫入權限(write).';

//Wacintaki 1.5.6 requires significant changes to the database to support international letters.
$lang['up_change_sum'] = 'Wacintaki 1.5.6 需要對資料庫進行大改變才能支援多國語言文字.';

//Click here to start the database conversion.
$lang['up_click_start_conv'] = '請按這裡開始執行資料庫轉換.';

//STOP: Cannot read UTF-8 marker from database.
$lang['up_no_dbutf_marker'] = '停止: 無法從資料庫讀取 UTF-8 標記.';

//Cleaned up {1} orphaned files.
$lang['up_cleaned_sum'] = '已清理 {1} 個孤獨檔案.';

//Unsupported update type &ldquo;From {1} to {2}&rdquo;.
// {1} from version, {2} = to version
$lang['up_no_up_num'] = '未支持的更新版本類型 &ldquo;從 {1} 到 {2}&rdquo;.';

//Board config version:  &ldquo;{1}&rdquo;,  database version:  &ldquo;{2}&rdquo;
// {1}+{2} = numbers
$lang['up_no_up_sum'] = '塗鴉板設定版本:  &ldquo;{1}&rdquo;,  資料庫版本:  &ldquo;{2}&rdquo;';

//Update cannot continue.
$lang['up_no_cont'] = '更新無法繼續.';

//If problems persist, visit the <a href="{1}">NineChime.com Forum</a>.
// {1} = url
$lang['up_nc_short'] = '如果問題仍然持續,請到 <a href="{1}">NineChime.com 討論區</a>詢問.';

//Wacintaki Update {1}
// {1} = number
$lang['up_header_title'] = 'Wacintaki 更新 {1}';

//If you have multiple boards, make sure each board is at the current version.
$lang['up_mult_warn'] = '如果你有多重塗鴉板,請確定他們是現在的版本.';

//Click the button below to finalize the update.  This will delete the updater files and prevent security problems.
$lang['up_click_final'] = '請按以下按鈕完成更新.  這將會刪除更新程式檔案以確保安全.';

//Secure updater and go to the BBS
// context = clickable button
$lang['up_secure_button'] = '安全更新程式並且到塗鴉板';

//Some warnings were returned during the update.  Please note these messages and run the updater again to ensure everything is set properly.  You may run the updater multiple times if needed.
$lang['up_warn_rerun'] = '更新程式產生了一些警告. 請注意這些訊息在執行更新程式一次以確保沒有問題.  如有需要你可以執行更新程式數次.';

//Errors occured during the update!  Check your server and database permissions and try again.  The update will not function properly until all errors are resolved.
$lang['up_stop_sum'] = '更新中出現了錯誤! 請確定你的伺服器與資料庫權限後再試一次 ,在所有錯誤排除之前更新程式無法正常執行.';

//NOTE: Make sure you\'ve deleted your old OekakiPoteto v5.x template and language files before running this updater.  Your old OekakiPoteto templates and language files will not work with Wacintaki.
$lang['up_no_op_tpl'] = '注意: 請確定你已在執行此更新程式前已經刪除舊的 OekakiPoteto v5.x 面板和語言檔案. 你的舊的OekakiPoteto面板及語言檔案將不會在Wacintaki上有效.';

//Click Next to start the update.
$lang['up_next_start'] = '按下一步開始更新.';

//Next
// context = clickable button
$lang['up_word_next'] = '下一步';

//{1} detected.
// {1} = version
$lang['up_v_detected'] = '偵測到版本{1}.';

//You appear to be running the latest version of Wacintaki already.  You may proceed to verify that the last update completed correctly.
$lang['up_latest_ver'] = '你已經在執行 Wacintaki 的最新版本. 你可以直接跳到確認上次的更新完正正確的步驟.';

//Click Next to verify the update.
$lang['up_next_ver'] = '請按下一步確認更新.';

//Unknown version.
$lang['up_unknown_v'] = '未知的版本.';

//Config: {1}, Database: {2}
// {1}+{2} = numbers
$lang['up_unknown_v_sum'] = '設定: {1}, 資料庫: {2}';

//This updater only supports Wacintaki versions less than or equal to {1}.
// {1} = number
$lang['up_v_spread_sum'] = '此更新程式只支援 Wacintaki 版本 {1} 或者是更早的版本.';

/* END update.php */



/* update_rc.php */

//Database has already been updated to UTF-8.
$lang['upr_already_utf'] = '資料庫已經更新到 UTF-8.';

//Click here to run the updater.
$lang['upr_click_run'] = '請按此執行更新程式.';

//PHP extension &ldquo;iconv&rdquo; not available.  Cannot recode from Big5 to UTF-8!
// context = iconv is all lower case (shell program).
$lang['upr_iconv_mia'] = 'PHP 外掛 &ldquo;iconv&rdquo; 不存在. 無法從 Big5 碼轉到 UTF-8碼!';

//Please visit the <a href="{1}">NineChime Forum</a> for help.
// {1} = url
$lang['upr_nc_shortest'] = '請到 <a href="{1}">NineChime 討論區</a> 尋求幫助.';

//This tool will convert an existing Wacintaki database to support international letters and text (such as &laquo;&ntilde;&raquo; and &bdquo;&#223;&ldquo;).
$lang['upr_conv_w_8bit'] = '這工具將會把 Wacintaki資料庫轉換成為支援各國的文字和字母 (譬如 &laquo;&ntilde;&raquo; 和 &bdquo;&#223;&ldquo;).';

//If you have multiple Wacintaki boards installed on your web site, you only need to run this tool once, but you will still need to run the updater on each board.
// context = update_rc.php runs only once, update.php must run on each board.
$lang['upr_xself_mult_warn'] = '如果你有安裝多個Wacintaki塗鴉板你只需要執行此程式一次, 但是你需要再各塗鴉板執行一次塗鴉板更新程式.';

//Using this tool more than once will not cause any damage.
$lang['upr_no_damage'] = '使用此工具超過一次不會導致任何損失.';

//NOTE:  {1} encoding detected.  Conversion will be from {1} to UTF-8.
// {1} = iconv charset ("iso-8859-1", "big5", "utf-8", etc.)
$lang['upr_char_det_conv'] = '注意: 偵測到 {1} 碼.  將從 {1} 碼轉到UTF-8碼.';

//If you used international letters in your password, you will need to recover your password after the update.
$lang['upr_utf_rec_pass'] = '如果你在你的密碼使用了英語外的符號及文字,你需要在此程式執行後恢復或者是重設密碼.';

//Click here to begin step {1} of {2}.
// {1}+{2} = numbers
$lang['upr_click_steps'] = '請按此開始 {2} 部中的第 {1} 步.';

//If you have problems converting the database, try visiting the <a href="{1}">NineChime Forum</a>, or you may <a href="{2}">bypass the conversion.</a>  If you bypass the conversion, existing comments with international letters will be corrupt, but new comments will post fine.
// {1} = url, {2} = local url
$lang['upr_nc_visit_bypass'] = '如果你有轉換資料庫的問題, 請訪問 <a href="{1}">NineChime 討論區</a>尋求協助, 或者你可以 <a href="{2}">跳過轉換.</a>如果你跳過轉換,使用非英語的文字的留言將會損壞,但是新的留言不會有事.';

//STOP: Cannot create one or more temp files for database conversion.  Check the permissions of the main oekaki folder.
$lang['upr_no_make_temp'] = '停止: 無法製作一個或多個暫時資料夾或者是資料庫轉換. 請檢查塗鴉板資料夾的權限.';

//Done!  Database has been updated to UTF-8.
$lang['upr_done_up'] = '完成! 資料庫轉換到 UTF-8碼.';

//Found {1} tables in the database.
$lang['upr_found_tbls'] = '在資料庫找到 {1}個資料表.';

//{1} {1?row:rows} need to be converted.
$lang['upr_found_rows'] = '{1} 列資料需要被轉換.';

//<strong>Please wait...</strong> it may take a minute for the next page to show.
$lang['upr_plz_wait'] = '<strong>請等待...</strong> 此步驟需要等待一段時間,之後下一頁將會顯示.';

//STOP: Double-click or unexpected reload detected.  Please wait another {1} seconds.
$lang['upr_dbl_click'] = '停止: 雙重滑鼠按鍵或者是不該出現的重新整理被偵測到. 請在等待 {1} 秒.';

//Building resource files.  Please wait...
$lang['upr_build_res_wait'] = '建立資源檔中. 請等待...';

//Step {1} of database update finished.  Ready to start step {2}.
$lang['upr_step_ready_num'] = '步驟 {1} 的資料庫更新已經完成. 準備開始步驟 {2}.';

//If there are any errors printed above, it\'s strongly recommended that you <a href="{1}">visit NineChime forum</a> for help.  The oekaki should still function properly if all members use only English letters.
// {1} = url
$lang['upr_if_err_nc'] = '如果上面有顯示任何錯誤, 非常建議你訪問 <a href="{1}">visit the NineChime 論壇</a> 尋求協助. 如果全部會員都只使用英語文字的話塗鴉板仍然還會正常運作.';

//Wacintaki UTF-8 Update
$lang['upr_header_title'] = 'Wacintaki UTF-8 更新';

//TIMEOUT: database partially exported.  This is normal if your database is very large.
$lang['upr_time_partial'] = '逾時: 資料庫部分匯出完成. 如果你資料庫很大的話這是正常現象.';

//{1} {1?row:rows} updated before timeout.
$lang['upr_rows_partial'] = '{1} 列在逾時前更新.';

//Click here to resume.
$lang['upr_click_resume'] = '請按此繼續.';

//TIMEOUT_IMPORT: database partially imported.  This is normal if your database is very large.
$lang['upr_time_norm'] = '逾時匯入: 資料庫部份匯入完成. 如果你資料庫很大的話這是正常現象.';

//STOP:{1} Cannot get tables: (SQL: {2})
// {1} = placeholder, so maintain spacing.  {2} = db_error()
$lang['upr_sql_bad_tbls'] = '停止:{1} 無法取得資料表: (SQL: {2})';

//STOP:{1} No SQL tables found!
// {1} = placeholder, so maintain spacing.
$lang['upr_sql_no_tbls'] = '停止:{1} 找不到 SQL 資料表!';

//STOP: Error reading column &ldquo;{1}&rdquo;: (SQL: {2})
// {1} = column name, {2} = db_error()
$lang['upr_bad_col'] = '停止: 讀取 &ldquo;{1}&rdquo; 欄位錯誤: (SQL: {2})';

//STOP: No SQL columns found in table &ldquo;{1}&rdquo;
// {1} = table name
$lang['upr_no_cols'] = '停止: 在資料表中沒有找到SQL欄位 &ldquo;{1}&rdquo;';

//{1} {1?row:rows} collected.  Total time for export: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_exp_time'] = '已取得{1}資料列. 匯出時間總計: {2} 秒.';

//{1} {1?row:rows} updated.  Total time for import: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_imp_time'] = '{1} 列資料已更新. 匯入時間總計: {2} 秒.';

//STOP: set_db_utf8_misc_marker({1}): Cannot insert db_utf8 marker (SQL: {2})
// {1} = debug argument (ignore), {2} = db_error()
$lang['upr_utf_no_ins'] = '停止: set_db_utf8_misc_marker({1}): 無法插入 db_utf8 標記 (SQL: {2})';

/* END update_rc.php */



//Uploaded
$lang['word_uploaded'] = '已更新';

//(Uploaded by <strong>{1}</strong>)
$lang['uploaded_by_admin'] = '(<strong>{1}</strong>上傳)';

/* End Version 1.5.6 */



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
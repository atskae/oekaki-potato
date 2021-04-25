<?php // ÜTF-8
/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastea-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.5.14x - Last modified 2014-11-06 (x:2015-08-23)

v1.0.6 & 1.1.3 english and chinese additions by Kevin (kevinant@gmail.com)

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
$lang['cfg_language'] = "简体中文";

// English Language name (native encoding, capitalized)
$lang['cfg_lang_eng'] = "Chinese Simplified";

// Name of translator(s)
$lang['cfg_translator'] = "<a href=\"mailto:kevinant@gmail.com\">Kevin</a>. 简体版: <a href=\"http://3eye.ws/blog/\">巫鹰</a>";

//$lang['cfg_language'].' translation by: '.$lang['cfg_translator'];
// context = Variables not needed. Change around order as needed.
$lang['footer_translation'] = $lang['cfg_language'].' translation by: '.$lang['cfg_translator'];

// Comments (native encoding)
$lang['cfg_comments'] = "Wacintaki 简体中文版";

// Zero plural form.  0=singular, 1=plural
// Multiple plural forms need to be considered in next language API
//$lang['cfg_zero_plural'] = 1;

// HTML charset ("Content-type")
$charset = "utf-8";

// HTML language name ("Content-language" or "lang" tags)
// Waccoon: Should this be zh-cn?
$metatag_language = "zh-Hans";

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
$lang['install_title'] = "安装 Wacintaki MySQL 绘图评论板系统";

//MySQL Information
$lang['install_information'] = "MySQL 数据库信息";

//If you do not know if your server has MySQL or how to access your MySQL account, e-mail your tech. support and ask for the following information: hostname, database name, username, and password. Without this information, you will be unable to install OP. If you need to remove the databases, look at the bottom of the page for a link to remove them. If you haven't read the readme.txt, DO IT NOW or your installation will fail! You must make sure you have the proper files and directories CHMODed before you continue.
// UPDATE: Remove CHMOD number "775", as different nubmers may be required.
$lang['install_disclaimer'] = "如果你清楚你的服务器支不支持 MySQL 或是你不知道你的 MySQL 信息, 请向你的空间提供着查询以下信息:<br />MySQL 数据库服务器位置, MySQL 数据库名称, MySQL 账号名及密码. <br />若没有这些信息, 你将无法安装 OekakiPoetato. 如果你需要删除 MySQL 数据库, 请按本页最下方的链接. 安装前请先阅读 readme.txt! 请确定你文件夹和档案都 Chmod 到 775.";

//If your OP currently works, there is no need to change the MySQL information.
$lang['cpanel_mysqlinfo'] = "如果你的 Oekaki Poetato 当前运行正常, 就不用更改 MySQL 数据库的设定.";

//Default Language
$lang['cpanel_deflang'] = "默认语言";

//Artist
$lang['word_artist'] = "作者";

//Compression Settings
$lang['compress_title'] = "压缩设定";

//Date
$lang['word_date'] = "日期";

//Time
$lang['word_time'] = "时间";

//min
$lang['word_minutes'] = "分钟";

//unknown
$lang['word_unknown'] = "unknown";

//Age
$lang['word_age'] = "年龄";

//Gender
$lang['word_gender'] = "性别";

//Location
$lang['word_location'] = "来自";

//Joined
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_joined'] = "加入";

//Language
$lang['word_language'] = "语言";

//Charset
$lang['word_charset'] = "编码";

//Help
$lang['word_help'] = "帮助";

//URL
$lang['word_url'] = "URL";

//Name
$lang['word_name'] = "名字";

//Action
$lang['word_action'] = "动作";

//Disable
$lang['word_disable'] = "不启用";

//Enable
$lang['word_enable'] = "启用";

//Translator
$lang['word_translator'] = "翻译者";

//Yes
$lang['word_yes'] = "是";

//No
$lang['word_no'] = "否";

//Accept
$lang['word_accept'] = "接受";

//Reject
$lang['word_reject'] = "拒绝";

//Owner
$lang['word_owner'] = "拥有者";

//Type
$lang['word_type'] = "类别";

//AIM
$lang['word_aolinstantmessenger'] = "AIM";

//ICQ
$lang['word_icq'] = "ICQ";

//Skype
// note: previously MSN
$lang['word_microsoftmessenger'] = "Skype";

//Yahoo
$lang['word_yahoomessenger'] = "Yahoo实时通";

//Username
$lang['word_username'] = "会员名";

//E-mail
$lang['word_email'] = "电邮";

//Animated
$lang['word_animated'] = "动画";

//Normal
$lang['word_normal'] = "正常";

//Registered
$lang['word_registered'] = "已注册";

//Guests
$lang['word_guests'] = "访客";

//Guest
$lang['word_guest'] = "访客";

//Refresh
$lang['word_refresh'] = "刷新";

//Comments
$lang['word_comments'] = "说明";

//Animations
$lang['word_animations'] = "动画";

//Archives
$lang['word_archives'] = "图片存档";

//Comment
$lang['word_comment'] = "评论";

//Delete
$lang['word_delete'] = "删除";

//Reason
$lang['word_reason'] = "理由";

//Special
$lang['word_special'] = "特别";

//Archive
$lang['word_archive'] = "归档";

//Unarchive
$lang['word_unarchive'] = "取消归档";

//Homepage
$lang['word_homepage'] = "首页";

//PaintBBS
$lang['word_paintbbs'] = "PaintBBS";

//OekakiBBS
$lang['word_oekakibbs'] = "OekakiBBS";

//Archived
$lang['word_archived'] = "已存档";

//IRC server
$lang['word_ircserver'] = "IRC 服务器";

//days
$lang['word_days'] = "天";

//Commenting
$lang['word_commenting'] = "评论";

//Paletted
$lang['word_paletted'] = "调色盘";

//IRC nickname
$lang['word_ircnickname'] = "IRC 昵称";

//Template
$lang['word_template'] = "模板";

//IRC channel
$lang['word_ircchannel'] = "频道";

//Horizontal
$lang['picview_horizontal'] = "水平";

//Vertical
$lang['picview_vertical'] = "垂直";

//Male
$lang['word_male'] = "男性";

//Female
$lang['word_female'] = "女性";

//Error
$lang['word_error'] = "错误";

//Board
$lang['word_board'] = "版面";

//Ascending
$lang['word_ascending'] = "从最早的开始排序";

//Descending
$lang['word_descending'] = "从最新的开始排序";

//Recover for {1}
$lang['recover_for'] = "恢复 为 {1}";

//Flags
$lang['word_flags'] = "权限";

//Admin
$lang['word_admin'] = "管理";

//Background
$lang['word_background'] = "背景";

//Font
$lang['word_font'] = "字体";

//Links
$lang['word_links'] = "链接";

//Header
$lang['word_header'] = "页眉";

//View
$lang['word_view'] = "查看";

//Search
$lang['word_search'] = "搜索";

//FAQ
$lang['word_faq'] = "常见问题";

//Memberlist
$lang['word_memberlist'] = "会员名单";

//News
$lang['word_news'] = "最新消息";

//Drawings
$lang['word_drawings'] = "图画";

//Submenu
$lang['word_submenu'] = "子菜单";

//Retouch
$lang['word_retouch'] = "续绘";

//Picture
$lang['word_picture'] = "图片";



/* niftyusage.php */
// UPDATE

//Link to Something
$lang['lnksom'] = "链接到一些东西";

//URLs without {1} tags will link automatically.
// {1} = "[url]"
$lang['urlswithot'] = "URL 中没有 {1} 标签会自动变成链接.";

//Text
$lang['nt_text'] = "文字";

//Bold text
// note: <b> tag
$lang['nt_bold'] = "粗体文字";

//Italic text
// note: <i> tag
$lang['nt_italic'] = "斜体文字";

//Underlined text
// note: <u> tag
$lang['nt_underline'] = "下划线文字";

//Strikethrough text
// note: <del> tag
$lang['nt_strikethrough'] = "删除线文字";

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
$lang['nt_preformatted'] = "预先格式的文字";

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
$lang['word_mailbox'] = "信箱";

//Inbox
$lang['word_inbox'] = "收件夹";

//Outbox
$lang['word_outbox'] = "寄件夹";

//Subject
$lang['word_subject'] = "主题";

//Message
$lang['word_message'] = "信息";

//Reply
$lang['word_reply'] = "评论";

//From
$lang['word_from'] = "发自";

//Write
$lang['word_write'] = "撰写";

//To
$lang['word_to'] = "发给";

//Status
$lang['word_status'] = "状态";

//Edit
$lang['word_edit'] = "编辑";

//Register
$lang['word_register'] = "注册";

//Administration
$lang['word_administration'] = "管理涂鸦板";

//Draw
$lang['word_draw'] = "开始画图";

//Profile
$lang['word_profile'] = "个人资料";

//Local
$lang['word_local'] = "本区";

//Edit Pics
$lang['header_epics'] = "编辑图片";

//Recover Pics
$lang['header_rpics'] = "恢复图片";

//Delete Pics
$lang['header_dpics'] = "删除图片";

//Delete Comments
$lang['header_dcomm'] = "删除评论";

//Edit Comments
$lang['header_ecomm'] = "编辑评论";

//View Pending
$lang['header_vpending'] = "查看未批准的会员";

//Re-Touch
$lang['word_retouch'] = "续绘";

//Logout
$lang['word_logoff'] = "注销";

//Modify Flags
$lang['common_mflags'] = "修改权限";

//Delete User
$lang['common_delusr'] = "删除会员";

//(include the http://)
$lang['common_http'] = "(包括 http://)";

//Move to page
$lang['common_moveto'] = "移到页";

//Scroll Down
$lang['chat_scroll'] = "向下滚屏";

//Conversation
$lang['chat_conversation'] = "交谈";

//Chat Information (required)
$lang['chat_chatinfo'] = " 聊天信息 (必填)";

//Move to Page
$lang['common_mpage'] = "移到 页";

//Delete Picture
$lang['common_deletepic'] = "删除图片";

//Picture Number
$lang['common_picno'] = "图片编号";

//Close this Window
$lang['common_window'] = "关闭此窗口";

//Last Login
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['common_lastlogin'] = "上次登入";

//Picture Posts
$lang['common_picposts'] = "图片贴";

//Comment Posts
$lang['common_compost'] = "评论贴";

//Join Date
$lang['common_jdate'] = "加入日期";

//You can use the message box to leave requests/comments, or talk to Wacintaki members if they are online.<b> All comments will be deleted after a specific amount of posts.<br /></b><br /><b>*</b> - Indicates a registered member.<br /><b>#</b> - Indicates a Guest<br /><br />Although you can see the current online registered members in the chat, Guests who are still online will dissapear after a specific amount of time. Be aware that everyime a guest posts, there WILL be multiple instances of the user under Guest. <br /><br />Your IP and Hostname are tracked in case of abuse. To see a user's IP/hostname, hover your mouse over their username in the chat. The rate of refresh is 15 seconds.
$lang['chat_warning'] = "你可以使用收件箱以留下要求, 或和在线的会员说话. <b>所有的评论将于一定的时间后删除</b><br /><br /><b>*</b> - 表示已注册之会员.<br /><b>#</b> - 表示访客 <br /><br />虽然你可以看到当前在线的人在聊天室里, 但在线的访客将于一定时间消失. 注意每次访客评论时, 会存在多个访客账号的实例. <br /><br />因管理需要您的 IP 已被记录. 要查看会员的 IP, 将鼠标指针悬停在聊天室中的会员名上. 刷新频率为15秒.";

//Database Hostname
$lang['install_dbhostname'] = "数据库服务器名称";

//Database Name
$lang['install_dbname'] = "数据库名称";

//Database Username
$lang['install_dbusername'] = "数据库用户名";

//Database Password
$lang['install_dbpass'] = "数据库密码";

//Display - Registration (Required)
$lang['install_dispreg'] = "显示-注册 (必填)";

//URL to Wacintaki
$lang['install_opurl'] = "Wacintaki 的存放 URL";

//Registration E-Mail
$lang['install_email'] = "管理者的 E-Mail";

//the e-mail address to use to send out registration information; this is REQUIRED if you are using automatic registration
$lang['install_emailsub'] = "用来寄出确认信的邮件地址, 如果你使用自动注册此字段必填";

//General
$lang['install_general'] = "一般设定";

//Encryption Key
$lang['install_salt'] = "加密密码";

//An encryption key can be any combination of characters; it is used to generate unique encryption strings for passwords. If you are using multiple boards with the same member database, make sure all boards share the same encryption key.
$lang['install_saltsub'] = "加密密码可以是任意的字符. 这是用来生成唯一的编码字串. 如果你安装超过一个涂鸦板的话, 请确定每个版都是同样的加密密码";

//Picture Directory
$lang['install_picdir'] = "图片文件夹";

//directory where your pictures will be stored
$lang['install_picdirsub'] = "将要存放图片的文件夹";

//Number of pictures to store
$lang['install_picstore'] = "要储存图片数";

//the max number of pictures the OP can store at a time
$lang['install_picstoresub'] = "最多能存放的图片数量";

//Registration
$lang['install_reg'] = "注册";

//Automatic User Delete
$lang['install_adel'] = "自动删除会员";

//user must login within the specified number of days before being deleted from the database
$lang['install_adelsub'] = "会员必须在一定的时间内至少登入一次, 否则账号会被删除";

//days <b>(-1 disables the automatic delete)</b>
$lang['install_adelsub2'] = "天 <b>(-1 表示不自动删除会员)</b>";

//Allow Guests to Post?
$lang['install_gallow'] = "允许访客评论? (最好不要)";

//if yes, guests can make comment posts on the board and chat
$lang['install_gallowsub'] = "如果是的话, 访客就可以发表评论和聊天";

//Require Approval? (Select no for Automatic Registration)
$lang['install_rapproval'] = "需要管理员批准? (若要自动注册的话请选否)";

//if yes, approval by the administrators is required to register
$lang['install_rapprovalsub'] = "如果是, 会员注册后要经过管理员批准";

//Display - General
$lang['install_dispgen'] = "一般显示";

//Default Template
$lang['install_deftem'] = "默认模板";

//templates are stored in the templates directory
$lang['install_deftemsub'] = "模板保存在 templates 文件夹";

//Title
$lang['install_title2'] = "标题";

//Title for the Wacintai.  Avoid using symbols or decoration, as it will be used for e-mail headers.
$lang['install_title2sub'] = "你的涂鸦板的名称，显示在导航条上";

//Display - Chat
$lang['install_dispchat'] = "显示聊天室";

//Max Number of Lines to Store for Chat
$lang['install_displinesmax'] = "聊天室中保存的最大行数";

//Lines of Chat Text to Display in a Page
$lang['install_displines'] = "聊天室中一页显示的行数";

//Paint Applet Settings
$lang['install_appletset'] = "涂鸦板程序设定";

//Maximum Animation Filesize
$lang['install_animax'] = "最大的动画文件大小";

//the max filesize animation files can be in bytes; default is 500,000 bytes or 500KB
$lang['install_animaxsub'] = "允许的最大动画文件大小, 默认为 500KB 或是 500000字节";

//bytes (1024 bytes = 1KB)
$lang['install_bytes'] = "字节 (1024 Bytes = 1KB)";

//Administrator Information
$lang['install_admininfo'] = "管理员信息";

//Login
$lang['install_login'] = "登入";

//Password
$lang['install_password'] = "密码";

//Recover Password
$lang['header_rpass'] = "忘记密码";

//Re-Type Password
$lang['install_repassword'] = "重新键入密码";

//TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms. 
$lang['install_TOS'] = "TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms.";

//Databases Removed!
$lang['install_dbremove'] = "数据库已删除";

//View Pending Users: Select a User
$lang['addusr_vpending'] = "查看尚未被批准的会员: 选择一个会员";

//View Pending Users: Details
$lang['addusr_vpendingdet'] = "查看尚未被批准的会员: 详细内容";

//Art URL
$lang['addusr_arturl'] = "画作 URL";

//Art URL (Optional)
$lang['reg_arturl_optional'] = "画作 URL (可选)";

//Art URL (Required)
$lang['reg_arturl_required'] = "画作 URL (必填)";

//Draw Access
$lang['common_drawacc'] = "画图权限";

//Animation Access
$lang['common_aniacc'] = "发动画权限";

//Comments (will be sent to the registrant)
$lang['addusr_comment'] = "意见 (将送给此会员)";

//Edit IP Ban List
$lang['banip_editiplist'] = "编辑 IP 封锁名单";

//Use one IP per line.  Comments may be enclosed in parentheses at end of line.
$lang['banip_editiplistsub'] = 'Use one IP per line.  Comments may be enclosed in parentheses at end of line.';

//Usage Example: <strong style="text-decoration: underline">212.23.21.* (Username - banned for generic name!)</strong>
$lang['banip_editiplistsub2'] = 'Usage Example: <strong style="text-decoration: underline">212.23.21.* (Name123 - banned for generic name!)</strong>';

//Edit Host Ban List
$lang['banip_edithostlist'] = "编辑封锁主机名单";

//Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!
$lang['banip_edithostlistsub'] = 'Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!';

//Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>
$lang['banip_edithostlistsub2'] = 'Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>';

//Ban List
$lang['header_banlist'] = "封锁名单";

//Control Panel
$lang['header_cpanel'] = "控制台";

//Send OPMail Notice
$lang['header_sendall'] = "寄出信件通知所有会员";

//<b>You have been banned!<br /><br />Reasons:<br /></b>- A user from your ISP was banned, which banned everyone on that ISP<br />- You were banned for malicious use of the oekaki<br /><br /><em>If you feel that this message was made in error, speak to an adminstrator of the oekaki.</em>
$lang['banned'] = "<b>你已被封锁!<br /><br />可能原因:<br /></b>- 一个来自和你相同 ISP 的会员被封锁了, 所以这样就封锁了整个 ISP 的 IP. <br />- 或者你因为恶意使用本涂鸦板而被封锁<br /><br /><em>如果你认为此信息有误, 请与管理员联系</em>";

//Retrieve Lost Password
$lang['chngpass_title'] = "重设密码";

//Because your password is encrypted, there is no way to retrieve it. Instead, you must specify a new password. If you receive no errors when submitting this form, that means your password has successfully changed and you can login with it once you are redirected to the index page.
$lang['chngpass_disclaimer'] = "由于密码已被加密, 所以无法取回密码, 但是你可以重设密码. 请输入你的新密码. 如果你提交此表单没有遇到任何问题, 表示你已成功重设密码. 然后回到首页后你就可以用你的新密码登入了.";

//New Password
$lang['chngpass_newpwd'] = "新密码";

//Add Comment
$lang['comment_add'] = "发表评论";

//Title of Picture
$lang['comment_pictitle'] = "图片的主题";

//Adult Picture?
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['comment_adult'] = "成人图片?";

//Comment Database
$lang['comment_database'] = "评论数据库";

//Global Picture Database
$lang['gpicdb_title'] = "全部图片数据库";

//Delete User
$lang['deluser_title'] = "删除会员";

//will be sent to the deletee
$lang['deluser_mreason'] = "将会送到被删除会员的信箱";

//Clicking delete will remove all records associated with the user, including pictures, comments, etc. An e-mail will be sent to the user appened with your contact e-mail in the case the deletee has further questions on the removal.
$lang['deluser_disclaimer'] = "按下删除后将会删除此会员所有的东西, 包括其创作及评论. 一封 E-mail 将寄给他.";

//Animated NoteBBS
$lang['online_aninbbs'] = "动画的 NoteBBS";

//Normal OekakiBBS
$lang['online_nmrlobbs'] = "正常的 OekakiBBS";

//Animated OekakiBBS
$lang['online_aniobbs'] = "动画的 OekakiBBS";

//Normal PaintBBS
$lang['online_npaintbbs'] = "正常的 PaintBBS";

//Palette PaintBBS
$lang['online_palpaintbbs'] = "带调色盘的 PaintBBS";

//Admin Pic Recover
$lang['online_apicr'] = "管理员图片恢复";

//Edit Notice
$lang['enotice_title'] = "编辑公告";

//Edit Profile
$lang['eprofile_title'] = "编辑个人资料";

//URL Title
$lang['eprofile_urlt'] = "URL 主题";

//IRC Information
$lang['eprofile_irctitle'] = "IRC 信息";

//Current Template
$lang['eprofile_curtemp'] = "现在的模板";

//Current Template Details
$lang['eprofile_curtempd'] = "现在的模板的细节";

//Select New Template
$lang['eprofile_templsel'] = "选一个新模板";

//Comments / Preferences
$lang['eprofile_compref'] = "评论 / 选项";

//Picture View Mode
$lang['eprofile_picview'] = "图片查看模式";

//Allow Adult Images
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adult'] = "允许成人图片";

//Change Password
$lang['eprofile_chngpass'] = "更改密码";

//Old Password
$lang['eprofile_oldpass'] = "旧密码";

//Retype Password
$lang['eprofile_repass'] = "重新输入密码";

//You will be automatically logged out if your password successfully changes; you need to re-login when this occours.
$lang['eprofile_pdisc'] = "成功修改密码后你将自动被注销, 到时重新登录即可.";

//Use your browser button to go back.
$lang['error_goback'] = "用你的浏览器的上一页按纽以回到上页.";

//Who's Online (last 15 minutes)
$lang['online_title'] = "谁在线 (前15分钟)";

//View Animation
$lang['viewani_title'] = "观赏动画";

//file size
$lang['viewani_files'] = "文件大小";

//Register New User
$lang['register_title'] = "注册新会员";

//A VALID E-MAIL ADDRESS IS REQUIRED TO REGISTER!
$lang['register_sub2'] = "需要一个正确的 E-amil 才可注册!!";

//Will be shown on your profile when registering; this comment box is limited to 80 chars for proper introduction so Admins can identify you; your IP and hostname is also tracked for security purposes.
$lang['register_sub3'] = "当你在注册时会显示在你的个人资料上, 最多输入80个字, 这样可以帮助管理员识别你的身份. 为保护网络安全你的 IP 也会被记录.";

//Include a URL to a picture or website that displays a piece of your work that you have done.
$lang['register_sub4'] = "输入你的作品或者包含你作品的网站的网址.";

//THIS IS NECESSARY TO REQUEST ACCESS TO DRAW ON OEKAKI.
$lang['register_sub5'] = "这是申请画图权限所必须的.";

//Picture Recovery
$lang['picrecover_title'] = "恢复图片";

//Profile for {1}
// {2} = Gender. Singular=Male/Unknown, Plural=Female
$lang['profile_title'] = "个人资料 {1}";

//send a message
$lang['profile_sndmsg'] = "发送信息";

//Latest Pictures
$lang['profile_latest'] = "最新的图片";

//Modify Applet Size
$lang['applet_size'] = "修改 Applet 的尺寸";

//Using Niftytoo
$lang['niftytoo_title'] = "使用 Niftytoo";

//Nifty-markup is a universal markup system for Wacintaki. It allows for all the basic formatting you could want in your messages, profiles, and text.
$lang['niftytoo_titlesub'] = "Nifty-markup 是 Wacintaki 使用的语法标记系统. 这能允许在你的评论和文字中使文字格式化 (修改字号, 颜色等).";

//Linking/URLs
$lang['niftytoo_linking'] = "链接/网址";

//To have a url automatically link, just type it in, beginning with http://
$lang['niftytoo_autolink'] = "http:// 开头的网址会自动转换成链接";

//Basic Formatting
$lang['niftytoo_basicfor'] = "基本格式";

//Change a font's color to the specified <em>colorcode</em>.
$lang['niftytoo_textcol'] = "更换一个字体的颜色到指示的<em>颜色码</em>.";

//will produce
$lang['niftytoo_produce'] = "将会产生";

//Intermediate Formatting
$lang['niftytoo_intermform'] = "中等格式";

//Modify Permissions
$lang['niftytoo_permissions'] = "更改权限";

//Recover Any Pic
$lang['header_rapic'] = "恢复任何图片";

//Super Administrator
$lang['type_sadmin'] = "超级管理员";

//Owner
$lang['type_owner'] = "拥有者";

//Administrator
$lang['type_admin'] = "管理员";

//Draw Access
$lang['type_daccess'] = "画图权限";

//Animation Access
$lang['type_aaccess'] = "动画权限";

//Immunity
$lang['type_immunity'] = "免疫权限";

//Adult Viewing
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['type_adultview'] = "成人观赏权限";

//General User
$lang['type_guser'] = "一般会员";

//A Super Administrator has the ability to add administrators as well as the abilities that Administrators have.
$lang['type_sabil'] = "一个超级管理员不仅具有普通管理员的权限, 还能添加新管理员.";

//Removing this permission will suspend their account.
$lang['type_general'] = "警告: 删除此权限会暂停他们的账号.";

//Gives the user access to draw.
$lang['type_gdaccess'] = "让此会员能画图";

//Gives the user access to animate.
$lang['type_gaaccess'] = "让此会员能作动画";

//Prevents a user from being deleted if the Kill Date is set.
$lang['type_userkill'] = "若自动删除会员启用时, 选择此项能防止此会员被删.";

//Member List
$lang['mlist_title'] = "会员列表";

//Pending Member
$lang['mlist_pending'] = "尚未批准的会员";

//Send Mass Message
$lang['massm_smassm'] = "批量发送信息";

//The message subject
$lang['mail_subdesc'] = "信息主题";

//The body of the message
$lang['mail_bodydesc'] = "信息内容";

//Send Message
$lang['sendm_title'] = "传送信息";

//The recipient of the message
$lang['sendm_recip'] = "此信息的收件者";

//Read Message
$lang['readm_title'] = "阅读信息";

//Retrieve Lost Password
$lang['lostpwd_title'] = "恢复遗失的密码";

//An e-mail will be sent to the e-mail address you have in your profile. If you did not specify an e-mail address when you registered, you will have to re-register for a new account. The e-mail will contain a URL to where you can specify a new password, as well as the IP and hostname of the computer used to request the password for security purposes.
$lang['lostpwd_directions'] = "一封邮件将送给到你注册时填写的邮件地址, 若你在注册时没有填写或乱填你的邮件地址, 你就要重新注册. 这封邮件会给你一个网址, 访问该网址可以重设你账号的密码, 提出修改密码申请的机器 IP 也会一同发送给你.";

//Local Comment Database
$lang['lcommdb_title'] = "本地评论数据库";

//Language Settings
$lang['eprofile_langset'] = "语言设定";



/* functions.php */

//A subject is required to send a message.
$lang['functions_err1'] = "提交的信息需要主题.";

//You cannot use mass mailing.
$lang['functions_err2'] = "你不可以用大量发送邮件的功能.";

//Access Denied. You do not have permissions to modify archives.
$lang['functions_err3'] = "拒绝进入. 你没有权限更改图片存档.";

//The username you are trying to retrieve to does not exist. Please check your spelling and try again.
$lang['functions_err4'] = "你要访问的会员名不存在, 请检查你有没有拼错.";

//Your new and retyped passwords do not match. Please go back and try again.
$lang['functions_err5'] = "你的新密码和重新输入的密码不一样, 请再试一次.";

//Invalid retrival codes. This message will only appear if you have attempted to tamper with the password retrieval system.
$lang['functions_err6'] = "无效的还原码.";

//The username you are trying to send to does not exist. Please check your spelling and try again.
$lang['functions_err9'] = "你想把信息送给的会员不存在, 请检查后再试一次.";

//You need to be logged in to send messages.
$lang['functions_err10'] = "你需要登入才能发信.";

//You cannot access messages in the mailbox that do not belong to you.
$lang['functions_err11'] = "你无法进入不属于你的收件箱.";

//Access Denied. You do not have permissions to delete users.
$lang['functions_err12'] = "拒绝进入. 你没有删除会员的权限.";

//Access Denied: Your password is invalid, or you are still a pending member.
$lang['functions_err13'] = "拒绝进入. 你的密码是无效的或你的账号尚未启用.";

//Invalid verification code.
$lang['functions_err14'] = "无效的确认码.";

//The e-mail address specified in registration already exists in the database. Please re-register with a different address.
$lang['functions_err15'] = "此邮件地此已经被人注册了,请以其他的地址注册.";

//You do not have the credentials to add or remove users.
$lang['functions_err17'] = "你没有删除和增加会员的权限.";

//You cannot claim a picture that is not yours.
$lang['functions_err18'] = "你无法声称这图片是你的因为这图片根本不是你的.";

//You cannot delete a comment that does not belong to you if you are not an Administrator.
$lang['functions_err19'] = "你无法删除一个不属于你的评论, 除非你是管理员或版主.";

//You cannot delete a picture that does not belong to you if you are not an Administrator.
$lang['functions_err20'] = "你无法删除一个不属于你的图片. 除非你是管理员或版主.";

//You cannot edit a comment that does not belong to you.
$lang['functions_err21'] = "你无法编辑一个不属于你的评论.";

//{1} Password Recovery
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['precover_title'] = '{1} 密码恢复';

//Dear {1},\n\nYou or someone with the IP/hostname [{6}] has requested for a password retrieve on {2 @ {3}. To retrieve your password, please copy and paste or click on this link into your browser:\n\n{4}\n\nYou will then be asked to specify a new password. If you did not request this, you may discard this message.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
// {6} = IP address
$lang['pw_recover_email_message'] = "亲爱的 {1},\n\n你或其他人使用相同 IP 的人 [{6}] 要求恢复密码 {2} @ {3}. 请把此链接复制到你的浏览器上以恢复你的密码:\n\n{4}\n\n之后你会被要求设置一个新密码. 如果你并没有要求恢复密码, 请不用理会此信息.";

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['mandel_title'] = '{1} 删除通知';

//Dear {1},\n\nYour account has been deleted from {2} @ {3}. If you have any questions, please e-mail the administrator that removed your account..\n\nDeleted by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['act_delete_email_message'] = "亲爱的 {1},\n\n你的账号已删除自: {2} @ {3}. 如果你有任何问题, 请联系删除你账号的管理员.\n\n删除: {4} ({5})\n说明: {6}";

//{1} Registration Details
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['autoreg_title'] = '{1} 注册信息';

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{4}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: Automated Registration
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = permissions
$lang['auto_accept_email_message'] = "亲爱的 {1},\n\n注册完成, 你现在可以登入了 {2} @ {3} 用你注册时的账号登入, 当你登入后, 你可能想到个人控制台以填写个性化信息, 或者清除注册是记录的 IP 信息.\n\n你被赋予以下的权限:\n{4}\n如果你没有画图或动画的权限, 并希望申请该权限, 请寄信给管理员咨询.\n\n并阅读常见问题解答查看是否有设定自动删除会员的日期, 如果有, 你必须要常常登入你的账号, 避免你的账号被删除.\n\n批准人: 自动注册系统";

//{1} Verification Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['verreg_title'] = '{1} 确认通知';

//Dear {1},\n\nYou have registered for {2} @ {3}. To complete your registration, please copy and paste or click on this link into your browser:\n\n{4}\n\nThis will verify your account so you can login into the oekaki.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
$lang['ver_email_message'] = "亲爱的 {1},\n\n你注册了此涂鸦板 {2} @ {3}. 若要完成注册程序, 请复制以下链接到你的浏览器:\n\n{4}\n\n这可以确认你的账号, 然后你就可以登入涂鸦板了.";

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{7}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = comments
// {7} = permissions
$lang['admin_accept_email_message'] = "亲爱的 {1},\n\n注册完成, 你现在可以登入了 {2} @ {3} 用你注册时的账号登入, 当你登入后, 你可能想到个人控制台以填写个性化信息, 或者清除注册是记录的 IP 信息.\n\n你被赋予以下的权限:\n{7}\n如果你没有画图或动画的权限, 并希望申请该权限, 请寄信给管理员咨询.\n\n并阅读常见问题解答查看是否有设定自动删除会员的日期, 如果有, 你必须要常常登入你的账号, 避免你的账号被删除.\n\n批准人: {4} ({5})\n说明: {6}";

//Dear {1},\n\nYour registration at {2} @ {3}, has been rejected. Please e-mail the {2} administrator who rejected you for more details. DO NOT reply to this e-mail address.\n\nRejected by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['reg_reject_email_message'] = "亲爱的 {1},\n\n你的申请在 {2} @ {3}, 被拒绝了, 请联络 {2} 拒绝你的管理员, 请勿直接回复此邮件.\n\n拒绝人: {4} ({5})\n说明: {6}";

//Your picture has been removed
// NOTE: mailbox subject.  BBCode only.  No newlines.
$lang['picdel_title'] = '你的图片已被删除';

//Hello,\n\nYour picture ({1}) has been removed from the database by {2} for the following reason:\n\n{3}\n\nIf you have any questions/comments regarding the action, you may reply to this message.
// NOTE: mailbox message.  BBCode only, and use \n rather than <br />.
// {1} = url
// {2} = admin name
// {3} = reason
$lang['picdel_admin_note'] = "你好,\n\n你的图片 ({1}) 已被管理员从数据库删除 {2} 删除原因为下:\n\n{3}\n\n如果有任何疑问, 你可以回复此封邮件洽询管理员.";

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
$lang['kill_title'] = "{1} 删除通知";

//Dear {1},\n\nThis is an automated message from the {2} automatic deletion system. Your account has been deleted because you have not logged into the oekaki within the last {3} days. If you want to re-register, please visit {4}\n\nBecause the account has been deleted, all post, comment, and other records associated with your username has been removed, and cannot be re-claimed. To avoid further deletions upon re-registration, be sure to log into your account within the specified amount of days in the FAQ.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['kill_email_message'] = "亲爱的 {1},\n\n这个自动信息是从本涂鸦板删除系统产生的. 你的账号被删除, 因为你很久都没登入 {2} 最后一次 {3} 天. 如果你希望重新注册, 到 {4}\n\n因为你的账号已被删除, 有关于你的文章及图片都被删除了. 为了让以后此是不会再发生, 请阅读 FAQ 里的日期并常常登入.\n\n敬上,\n自动删除";

//{1} Registration Expired
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['regexpir'] = "{1} 注册过期";

//Dear {1},\n\nYour registration at {2} has expired becuase you did not activate your account within {3} days. To submit a new registration, please visit {4}\n\nIf you did not receive a link to activate your account in a seperate e-mail, try using a different address or check the anti-spam settings used for your e-mail.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['reg_expire_email_message'] = "亲爱的 {1},\n\n你的申请在 {2} @ {4} 过期了因为你没有启动账号在 {3} 天. 请再重新注册一次. {4}\n\n如果你没有收到启动账号的 e-mail, 请尝试用另一个信箱地址或检查你信箱的反垃圾设定.\n\n自动注册系统\n敬上";

/* END maint.php */



/* FAQ */

//Frequently Asked Questions
$lang['faq_title'] = "常见问题";

//<strong>Current Wacintaki version: {1}</strong>
$lang['faq_curver'] = "当前安装的 Wacintaki 涂鸦板版本: {1}";

//<strong>This oekaki deletes inactive accounts after {1} days.  Log in regularly to keep your account active.</strong>
//UPDATE
$lang['faq_autoset'] = "自动删除设定为 {1} 天";

//<strong>No automatic deletion is set.</strong>
$lang['faq_noset'] = "目前尚未设定自动删除.";

//Get the latest Java for running oekaki applets.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_java'] = 'Get the latest Java for running oekaki applets.';

//JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_jtablet'] = 'JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.';

//Table of Contents
$lang['faq_toc'] = "目录";

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


$lang['faq_question'][7] = '我遗失了密码.';

$lang['faq_answer'][7] = '<p>你可以到<a href=\"lostpass.php\">这里</a>恢复你的密码.</p>

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

<p>If an oekaki board has been rated for adults only, you must submit an age statement before you may register.  If you cannot view adult pictures and would like to see them, you must select &ldquo;Edit Profile&rdquo;, and make sure the &ldquo;Allow Adult Images&rdquo; checkbox is selected.</p>';


$lang['faq_question'][15] = 'Why are some pictures shrunken into a thumbnail, while others are not?';

$lang['faq_answer'][15] = '<p>Images become thumbnails based on a number of factors, including the filesize of your image, and what thumbnail mode the administrator has selected for the board.  You may change thumbnail and layout modes in <a href="editprofile.php">&ldquo;Edit Profile&rdquo;</a> if the administrator allows members to choose their own view modes.</p>';

// -----

//Who {1?is the owner:are the owners} of this oekaki?
$lang['faq_questionA'] = "谁是这个 OekakiPoteto 的拥有者?";

//Who {1?is the adminitrator:are the adminitrators}?
$lang['faq_questionB'] = "谁是这个 OekakiPoteto 的管理员?";

//Who {1?is the moderator:are the moderators}?
$lang['faq_questionC'] = 'Who {1?is:are} the moderator{1?:s}?';

/* End FAQ */



$lang['word_new'] = "新的";

$lang['word_unread'] = "尚未阅读";

$lang['word_read'] = "已阅读";

$lang['word_replied'] = "已回复";

$lang['register_sub8'] = "注册完成后, 请查收你的 E-mail 中的激活链接以启用账号.";

//Upload
$lang['word_upload'] = "上传";

//Upload Picture
$lang['upload_title'] = "上传图片";

//File to upload
$lang['upload_file'] = "想要上传的文件";

//ShiPainter
$lang['word_shipainter'] = "ShiPainter";

//ShiPainter Pro
$lang['word_shipainterpro'] = "ShiPainter Pro";

//Edit Banner
$lang['header_ebanner'] = "编辑横幅";

//Reset All Templates
$lang['install_resettemplate'] = "重设全部的模板";

//N/A
$lang['word_na'] = "未指定";

//You do not have draw access. Ask an administrator on details for receiving access.
$lang['draw_noaccess'] = "你没有画图的权限, 请洽询管理员.";

//Upload Access
$lang['type_uaccess'] = '上传权限';

//Print &ldquo;Uploaded by&rdquo;
$lang['admin_uploaded_by'] = '显示 &ldquo;上传者&rdquo;';

//Gives the user access to the picture upload feature.
$lang['type_guaccess'] = '给该会员上传图片的权限.';

//Delete database
$lang['delete_dbase'] = "删除数据库";

//Database Uninstall
$lang['uninstall_prompt'] = "卸载 OekakiPoteto 数据库?";

//Are you sure you want to remove the database?  This will remove information for the board
$lang['sure_remove_dbase'] = "你确定要删除数据库吗? 这会把所有版面的数据库清空";

//Images, templates, and all other files in the OP directory must be deleted manually.
$lang['all_delete'] = "涂鸦板所在文件夹中的图片, 模板和全部其他的文件必须手动删除.";

//If you have only one board, you may delete both databases below.
$lang['delete_oneboard'] = "如果你只有一个涂鸦板的话, 你可以同时删除以下两个数据库.";

//If you are sharing a database with more than one board, be sure to delete <em>only</em> the database for posts and comments.  If you delete the database for member profiles, all your boards will cease to function!
$lang['sharing_dbase'] = "如果你有多于一个涂鸦板共享此数据库, 记得<em>只</em>删除图片和文章使用的数据库. 如果你删除了保存会员信息的数据库, 所有涂鸦板都会停止工作!";

//Each board must be removed with its respective installer.
$lang['remove_board'] = "每个涂鸦板需要用他自己的卸载程序删除.";

//Delete posts and comments.
$lang['delepostcomm'] = "删除图片和文章.";

//Delete member profiles, chat, and mailboxes.
$lang['delememp'] = "删除会员信息, 聊天和信箱里的所有东西.";

//Uninstall error
$lang['uninserror'] = "卸载错误";

//Valid database and config files were not found.  The board must be properly installed before any database entries can be removed.  If problems persist, let your sysadmin delete the databases by name.
$lang['uninsmsg'] = "无法读取系统数据库和配置文件. 涂鸦板必须正确安装过才能卸载. 如果问题依然存在, 请你的空间管理员删除那些数据库, 或用 phpMyAdmin 自行删除.";

//Uninstall Status
$lang['unistatus'] = "卸载进度";

//NOTE:  No databases changed
$lang['notedbchange'] = "注意: 数据库未被更改";

//Return to the installer
$lang['returninst'] = "回到安装程序";

//Wacintaki Installation
$lang['wacinstall'] = "Wacintaki 涂鸦板安装程序";

//Installation Progress
$lang['instalprog'] = "安装进度";

//ERROR:  Your database settings are invalid.
$lang['err_dbs'] = "错误: 你的数据库设定无效.";

//NOTE:  Database password is blank (not an error).
$lang['note_pwd'] = "注意: 数据库密码是空白 (不是错误).";

//ERROR:  The administrator login name is missing.
$lang['err_adminname'] = "错误: 没有管理员登入账号.";

//ERROR:  The administrator password is missing.
$lang['err_adminpwd'] = "错误: 没有管理员登入密码.";

//ERROR:  The administrator passwords do not match.
$lang['err_admpwsmtch'] = "错误: 两次管理员登入密码输入不相符.";

//Could not connect to the MySQL database.
$lang['err_mysqlconnect'] = "无法连接到 MySQL 数据库.";

//Wrote database config file.
$lang['msg_dbsefile'] = "写到数据库配置文件中.";

//ERROR:  Could not open database config file for writing.  Check your server permissions
$lang['err_permis'] = "错误: 无法写入数据库配置文件. 请确认你的服务器权限设定";

//Wrote config file.
$lang['wrconfig'] = "写入配置文件.";

//ERROR:  Could not open config file for writing.  Check your server permissions.
$lang['err_wrconfig'] = "错误: 无法写入配置文件. 请确认你的服务器权限设定";

//ERROR:  Could not create folder &ldquo;{1}&rdquo;
$lang['err_cfolder'] = "错误: 无法建立文件夹 &ldquo;{1}&rdquo;";

//ERROR:  Folder &ldquo;{1}&rdquo; is locked.  You may have to create this folder manually.
$lang['err_folder'] = "错误: 文件夹 &ldquo;{1}&rdquo; 是锁定的. 你可能需要手动创建此文件夹.";

//One or more base files could not be created.  Try again or manually create the listed files with zero length.
$lang['err_fcreate'] = "无法建立一或更多个基本文件. 请再试一次, 或手动建立以下长度为0字节的文件.";

//'Wrote base &ldquo;resource&rdquo; folder files.'
$lang['write_basefile'] = "写入基本 &ldquo;resource&rdquo; 文件夹的文件.";

//Starting to set up database.
$lang['startsetdb'] = "开始安装数据库.";

//Finished setting up database.
$lang['finishsetdb'] = "成功安装数据库.";

//If you did not receive any errors, the databases have been installed.
$lang['noanyerrors'] = "如果你没收到任何错误, 涂鸦板的数据库已经成功安装了.";

//If you are installing another board and your primary board is functioning properly, ignore any database errors.
$lang['anotherboarderr'] = "如果你是在安装另一个涂鸦板, 然而第一个版面是正常的话, 请略过任何数据库错误信息.";

//Click the button below to finalize the installation.  This will clean up the installer files and prevent security problems.  You will have to copy <em>install.php</em> into the Wacintaki folder if you need to uninstall the database.  All other maintenance can be done with the control panel.
$lang['clickbuttonfinal'] = "点击以下按钮结束安装程序. 这会删除安装程序文件来避免安全问题. 如果你要卸载数据库的话, 你必须把 <em>install.php</em> 上传到 Wacintaki 涂鸦板的文件夹中. 所有其他的配置可以在系统控制面板中完成.";

//Secure installer and go to the BBS
$lang['secinst'] = "删除安装程序并进入涂鸦板";

//Installation Error
$lang['err_install'] = "安装错误";

//&ldquo;templates&rdquo; and &ldquo;resource&rdquo; folders are not writable!  Be sure to CHMOD these folders to their correct permissions before running the installer.
$lang['err_temp_resource'] = "无法写入到 &ldquo;templates&rdquo; 和 &ldquo;resource&rdquo; 文件夹! 在安装之前请确认这些文件夹拥有正确的正确的权限.";

//Wacintaki Installation
$lang['wac_inst'] = "Wacintaki 涂鸦板安装程序";

//Installation Notes
$lang['inst_note'] = "安装注意事项";

//One MySQL database is required to install Wacintaki.  If you do not know how to access your MySQL account, e-mail your sysadmin, or log into your control panel and look for a database tool such as phpMyAdmin.  On most servers, &ldquo;localhost&rdquo; will work for the hostname, though web hosts with a dedicated MySQL server may require something such as &ldquo;mysql.server.com&rdquo;.  Be aware that some database tools, such as CPanel or phpMyAdmin, may automatically add a prefix to your database name or username, so if you create a database called &ldquo;oekaki&rdquo;, the result may end up being &ldquo;accountname_oekaki&rdquo;.  The database table prefixes (default &ldquo;op_&rdquo;) are only significant if you wish to install more than one oekaki.  Consult the manual for more information on installing multiple oekakis with one database.
$lang['mysqldb_wact'] = "需要一个 MySQL 数据库安装 Wacintaki 涂鸦板. 如果你不知道如何进入你的 MySQL 账号, 请 e-mail 给你的空间管理员, 或登入到你的空间控制面板寻找数据库管理程序 (如 phpMyAdmin). 在大部分的服务器上, 数据库服务器名称是 localhost, 但是有些服务器使用专用的 MySQL 服务器, 可能会有其他数据库服务器名称, 如 mysql.server.com. 请注意有些数据库管理程序, 如 CPanel 或 phpMyAdmin, 可能会自动在你数据库名称或账号前加上某些前缀, 所以如果你建立了一个数据库名子为 oekaki, 那个数据库的名子可能变成 accountname_oekaki. 数据表的前缀 (默认为 op_) 只有在安装多个涂鸦板时才需要更改它. 如要在一个数据库装多重涂鸦板. 请阅读安装说明.";

//Database Table Prefix
$lang['dbtablepref'] = "数据表前缀";

//If installing mutiple boards on one database, each board must have its own, unique table prefix.
$lang['multiboardpref'] = "如果在一个数据库中安装多个涂鸦板的话, 每个涂鸦板必须要有不同的数据库前缀.";

//Member Table Prefix
$lang['memberpref'] = "会员数据表前缀";

//If installing multiple boards on one database, and you want all members to access each board without seperate registrations, make sure each board shares the same table prefix.  To force sperate registrations for each board, make this prefix unique for each installation.
$lang['instalmulti'] = "如果在一个数据库中安装多个涂鸦板, 并且希望每个会员不需在每个涂鸦板注册而能够登入到每个板, 请确认每个板的会员数据表的前缀是一样的. 如要强迫会员在每个板注册, 请让此前缀和其他涂鸦板的前缀不同.";

//<a href="{1}">Click here to uninstall an existing database.</a>  Confirmation will be requested.
$lang['uninstexist'] = '<a href="{1}">点击这里以卸载所有存在的数据库</a> 将需要确认';

//This is a guess.  Make sure it is correct, or registration will not work correctly.
$lang['guessregis'] = "这是一个猜测. 请确认这是正确的, 否则将无法注册.";

//Picture Name Prefix
$lang['picpref'] = "图片名称前缀";

//This prefix will appear on every picture and animation saved by the BBS.  Example: &ldquo;OP_50.png&rdquo;
$lang['picprefexp'] = "这段前缀将会出现在涂鸦板保存的每个图片文件名上. 例如: OP_50.png";

//Allow Public Pictures
$lang['allowppicture'] = "允许公用图片";

//Public pictures may be retouched by any member with draw permissions. No passwords are used, and retouched images are submitted as new posts. <strong>NOTE</strong>: May result in floods without strict rules and administration.
$lang['ppmsgrtouch'] = "公用图片可以被任何拥有画图权限的会员编辑, 没有密码保护. 这些编辑过的图片会另存为新图. <strong>注意</strong>: 如果没有严格的管理和版规的话, 可能到致严重灌水.";

//Allow Safety Saves
$lang['allowsafesave'] = "允许安全储存";

//Safety saves do not show up on the board while they are in progress.  Only one safety save is allowed per member, and they are automatically deleted after a certain number of days
$lang['safesaveexp'] = "安全储存是在图片完成前, 不把图片公开在板上. 每个会员只能有一个安全储存, 并且它们会在一定天数内自动被系统删除";

//Safety Save Storage
$lang['savestorage'] = "安全储存时间";

//Number of days safety saves are stored before they are removed.  Default is 30.
$lang['safetydays'] = "一个安全储存会在多少天后会被系统自动删除. 默认是30";

//Auto Immunity for Artists
$lang['autoimune'] = "画家自动免疫";

//If yes, people who draw pictures will automatically receive the immunity flag from auto user delete.
$lang['autoimune_exp'] = "如果是, 画过图的会员会自动获得免疫权限而不会遭到系统自动删除.";

//Show Rules Before Registration
$lang['showrulereg'] = "在会员注册前显示板规";

//If yes, people will be shown the rules before they can submit a new registration.  Use &ldquo;Edit Rules&rdquo; in the admin menu to set rules.
$lang['showruleregexp'] = "如果是, 每个人在注册前会看到板规. 使用管理功能中的 &ldquo;编辑板规&rdquo; 设定板规.";

//Require Art Submission
$lang['requireartsub'] = "需要之前的画作验证";

//If yes, new users are instructed to provide a link to a piece of art for the administrator to view.
$lang['requireartsubyes'] = "如果是, 新注册的会员必须提供一个到他之前的画作的链接, 让管理员查看.";

//If no, new users are told the URL field is optional.
$lang['requireartsubno'] = "如果不, 新注册的会员不需要提供一个到他之前的画作的链接.";

//No (forced)
$lang['forceactivate'] = "否 (强迫启用)";

//If yes, approval by the administrators is required to register.
$lang['activateyes'] = "如果是, 新注册的会员需要管理员的批准.";

//If no, users will receive an activation code in their e-mail.
$lang['activeno'] = "如果不, 新注册的会员将会收到 e-mail 以启用他的账号.";

//Use &ldquo;forced&rdquo; ONLY if your server cannot send e-mails, and you want automatic approval.
$lang['activateforced'] = "如果你的服务器无法发送 E-mail, 并且你想要自动启用会员的账号, 那么请选 &ldquo;强迫启用&rdquo;.";

//Default Permissions for Approved Registrations
$lang['defaultpermis'] = "当一个新会员通过验证后, 他的默认权限:";

//Members may bump own pictures on retouch?
$lang['bumpretouch'] = "会员可以在续绘时顺便顶他们的图片吗?";

//Author Name
$lang['authorname'] = "拥有者名称";

//Name of the BBS owner.  This is displayed in the copyright and page metadata.
$lang['bbsowner'] = "涂鸦板拥有者名称. 这会在页脚和批注中显示.";

//Adult rated BBS
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbs'] = "成人涂鸦板";

//Select Yes to declare your BBS for adults only.  Users are required to state their age to register.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsdesc'] = "选是来表示你的涂鸦板只给成人用的. 新注册的会员必须提供他的生日才能注册.";

//NOTE:  Does <strong>not</strong> make every picture adult by default.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsnote'] = "注意: 这<strong>不会</strong>把每个图片自动设为成人图片.";

//Allow guests access to pr0n
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpron'] = "允许访客观看成人图片";

//If yes, adult images are blocked and may be viewed by clicking the pr0n placeholder.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronyes'] = "如果是, 成人图片会先被隐藏, 但是访客可以点击成人图占位图片来观看那些图片.";

//If no, the link is disabled and all access is blocked.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronno'] = "如果不, 访客将不能看到成人图片.";

//Number of Pics on Index
$lang['maxpiconind'] = "每一页的图片数目";

//Avatars
$lang['word_avatars'] = "头像";

//Enable Avatars
$lang['enableavata'] = "启用头像";

//Allow Avatars On Comments
$lang['allowavatar'] = "允许在评论前显示头像";

//Avatar Storage
$lang['AvatarStore'] = "头像保存文件夹";

//Change <strong>only</strong> if installing multiple boards.  Read the manual.
$lang['changemulti'] = "<strong>只有</strong>当你安装多个涂鸦板时需要更改. 若不懂请查询安装手册.";

//Basically, use one folder for all boards.  Example:  use &ldquo;avatars&rdquo; for board 1, &ldquo;../board1/avatars&rdquo; for board 2, etc.
$lang['changemultidesc'] = "基本上, 每个涂鸦板使用相同的头像文件夹. 例如: 用 &ldquo;avatars&rdquo; 当作涂鸦板1的头像文件夹, &ldquo;../board1/avatars&rdquo; 当作涂鸦板2地头像文件夹, 以此类推.";

//Maximum Avatar Size
$lang['maxavatar'] = "最大的头像大小";

//Default is 50 &times; 50 pixels.  Larger than 60 &times; 60 not recommended
$lang['maxavatardesc'] = "默认是50&times;50像素. 不建议大于60&times;60大小的头像 (会破坏版面)";

//Default Canvas Size
$lang['cavasize'] = "默认的画布大小";

//The default canvas size.  Typical value is 300 &times; 300 pixels
$lang['defcanvasize'] = "默认的画布大小. 正常的是300&times;300像素";

//Minimum Canvas Size
$lang['mincanvasize'] = "最小画布大小";

//The minimum canvas size.  Recommended minimum is 50 &times; 50 pixels
$lang['mincanvasizedesc'] = "最小画布大小. 建议是50&times;50像素";

//Maximum Canvas Size
$lang['maxcanvasize'] = "最大画布大小";

//Maximum canvas.  Recommended maximum is 500 &times; 500 pixels
$lang['maxcanvasizedesc'] = "最大画布大小. 建议是500&times;500像素";

//Be aware that a small increase in dimentions results in a large increase in surface area, and thus filesize and bandwidth.  1000 &times; 1000 uses <strong>four times</strong> as much bandwidth as 500 &times; 500
$lang['maxcanvasizedesc2'] = "注意, 增加一点点会大大增加画布的表面面积, 文件大小和带宽. 1000&times;1000像素的画布占用的带宽是500&times;500的<strong>四倍</strong>";

//Number of pictures to display per page of the BBS
$lang['maxpicinddesc'] = "在涂鸦板每页所显示的图片数量";

//Number of Entries on Menus
$lang['menuentries'] = "菜单上显示项目的数量";

//Number of entries (such as user names) to display per page of the menus and admin controls
$lang['menuentriesdesc'] = "窗体和系统控制面板上每一页显示多少项目 (如会员名称)";

//Use Smilies in Comments?
$lang['usesmilies'] = "允许在评论中使用表情?";

//Smilies are configurable by editing the &ldquo;hacks.php&rdquo; file
$lang['usesmiliedesc'] = "表情可以在 &ldquo;hacks.php&rdquo; 中编辑";

//Maximum Upload Filesize
$lang['maxfilesize'] = "最大上传文件大小";

//The max filesize uploaded pictures can be, in bytes.  Default is 500,000 bytes or 500KB.
$lang['maxupfileexp'] = "最大上传文件的大小 (单位: 字节). 默认是500,000字节或500KB.";

//The maximum value allowed by most servers varies from 2 to 8MB.
$lang['maxupfileexp2'] = "最大是2,000,000个字节或2MB, 这是大部分服务器的限制.";

//Canvas size preview
$lang['canvasprev'] = "画布大小预览";

//Image for canvas size preview on Draw screen.  Square picture recommended.
$lang['canvasprevexp'] = "在开始画之前预览画布大小用的图片. 建议是方形图片.";

//Preview Title
$lang['pviewtitle'] = "预览图片标题";

//Title of preview image (Text only &amp; do not use double quotes).
$lang['titleprevwi'] = "预览图片的标题 (纯文本, 请勿用双引号).";

//&ldquo;Pr0n&rdquo; placeholder image
$lang['pron'] = "成人图占位图片";

//Image for substitution of pr0n.  Square picture recommended.  Default &ldquo;pr0n.png&rdquo;
$lang['prondesc'] = "代替成人图片的图片. 建议是方形图片. 默认是 &ldquo;pr0n.png&rdquo;";

//Enable Chat
$lang['enablechat'] = "启用聊天室";

//Note:  chat uses a lot of bandwidth
$lang['chatnote'] = "注意: 聊天室使用很大的带宽";

//Your server does not have the graphics system &ldquo;GDlib&rdquo; installed, therefore you cannot enable thumbnail support.  However, you may still select a default thumbnail mode which will conserve screenspace by shrinking pictures.
$lang['err_nogdlib'] = "你的服务器没有安装图片系统 &ldquo;GDlib&rdquo;, 所以你无法启用缩略图. 但是, 你还是可以选一个缩略图模式, 这样可以缩小图片以节省显示空间.";

//Four thumbnail modes are available.  None, Layout, Scaled, and Uniformity.  If you're confused which mode to use, try Scaled first.
$lang['thumbmodes'] = "有四种缩略图模式供选择. 无, 自动布局, 自动缩放和统一化. 如果你不知道要选择哪个, 请选择自动缩放.";

//If you choose &ldquo;None&rdquo;, thumbnail support will always be off for all members unless you enable it later in the control panel.
$lang['thumbmodesexp2'] = "如果你选择 &ldquo;无&rdquo;, 缩略图功能将对所有会员关闭, 除非你以后在控制台启用.";

//Default thumbnail mode
$lang['defthumbmode'] = "默认的缩略图模式";

//None
$lang['word_none'] = "无";

//Layout
$lang['word_layout'] = "自动布局";

//Scaled (default)
$lang['word_defscale'] = "自动缩放 (默认)";

//Uniformity
$lang['word_uniformity'] = "统一化";

//Tip:  Options are ordered in terms of bandwidth.  Uniformity uses the least bandwidth.  Scaled Layout is recommended.
$lang['optiontip'] = "提示: 选项是依照使用的宽带来排序的. 统一化模式使用最少带宽. 推荐使用自动缩放模式.";

//Force default thumbnails
$lang['forcedefthumb'] = "强迫默认缩略图";

//If yes, users may only use the default mode (recommended for servers with little bandwidth). If no, users may select any thumbnail mode they wish.
$lang['forcethumbdesc'] = "如果是, 会员将只能选用默认的缩略图模式 (建议在带宽很少的服务器上使用). 如果不, 会员可以自由选择他要的缩略图模式.";

//Small thumbnail size
$lang['smallthumb'] = "小缩略图的大小";

//Size of small (uniformity) thumbnails in pixels.  Small thumbnails are generated often.  Default 120.
$lang['smallthumbdesc'] = "小缩略图 (统一化模式) 的大小 (单位:像素). 小缩略图会频繁地生成. 默认是120.";

//Large thumbnail size
$lang['largethumb'] = "大缩略图的大小";

//Size of large (layout) thumbnails.  Large thumbnails are made only occasionally for layout or scaled thumbnail modes.  Default 250.
$lang['largethumbdesc'] = "大缩略图 (自动布局模式) 的大小 (单位:像素). 使用自动布局或自动缩放模式时, 大缩略图只会偶尔产生. 默认是250.";

//Filesize for large thumbnail generation
$lang['thumbnailfilesize'] = "大缩略图产生的文件大小";

//If a picture's filesize is greater than this value, a large thumbnail will be generated in addition to the small one.  Default is 100,000 bytes.  If using uniformity mode only, set to zero to disable and save server space.
$lang['thumbsizedesc'] = "如果一个图片的文件大小大于这个数字, 会生成一个大缩略图和一个小缩略图. 默认是100,000个字节. 如果使用统一化模式, 把它设为0以节省服务器空间.";

//Your E-mail Address (leave blank to use registration e-mail)
$lang['emaildesc'] = "你的 E-mail 地址 (如果要和注册地址相同请留空)";

//Submit Information for Install
$lang['finalinstal'] = "提交数据以安装";

//---addusr

//You do not have the credentials to add users.
$lang['nocredeu'] = "你没有添加会员的权限.";

//Note to admins:  Automatic approval is enabled, so users are expected to enable their own accounts.  Contact the board owner if you have questions about approving or rejecting members manually.
$lang['admnote'] = "管理员注意: 自动批准账号功能是启用的, 所以会员应该会自己启用账号. 如果你对手动开启会员账号有疑问的话, 请联络本涂鸦板的拥有人.";

//INVALID
$lang['word_invalid'] = "无效";

//--banlist

//You do not have the credentials to ban users.
$lang['credibandu'] = "你没有封锁会员的权限.";

//&ldquo;{1}&rdquo; is locked!  View Readme.txt for help.
// {1} = filename
$lang['fislockvred'] = "&ldquo;{1}&rdquo; 被锁住了! 如要更多说明, 请阅读 Readme.txt.";

//Submit Changes
$lang['submitchange'] = "提交变更";

//You do not have access as a registered member to use the chat.
$lang['memaccesschat'] = "你不是注册的会员, 所以没有进聊天室的权限.";

//The chat room has been disabled.
$lang['charommdisable'] = "聊天室目前尚未启用.";

//Sorry, an IFrame capable browser is required to participate in the chat room.
$lang['iframechat'] = "抱歉, 必须要一个支持 IFrame 的浏览器才能进去聊天室.";

//Invalid user name.
$lang['invuname'] = "无效的会员名.";

//Invalid verification code.
$lang['invercode'] = "无效的验证码.";

//Safety Save
$lang['safetysave'] = "安全储存";

//Return to the BBS
$lang['returnbbs'] = "回到涂鸦板";

//Error looking for a recent picture.
$lang['err_lookrecpic'] = "错误: 无法找到最近的图片.";

//NOTE:  Refresh may be required to see retouched image
$lang['refreshnote'] = "注意: 要看到编辑过的图片可能需要刷新页面";

//Picture properties
$lang['picprop'] = "图片属性";

//No, post picture now
$lang['safesaveopt1'] = "不, 现在就发布";

//Yes, save for later
$lang['safesaveopt2'] = "是, 储存图片留着以后编辑";

//Bump picture
$lang['bumppic'] = "顶图";

//You may bump your edited picture to the first page.
$lang['bumppicexp'] = "你可以把你编辑过的图顶到第一页.";

//Share picture
$lang['sharepic'] = "分享图片";

//Password protect
$lang['pwdprotect'] = "密码保护";

//Public (to all members)
$lang['picpublic'] = "公用图片 (所有会员都可编辑)";

//Submit
$lang['word_submit'] = "提交";

//Thanks for logging in!
$lang['common_login'] = "感谢你登入!";

//You have sucessfully logged out.
$lang['common_logout'] = "你已成功注销.";

//Your login has been updated.
$lang['common_loginupd'] = "你的登入状态已更新.";

//An error occured.  Please try again.
$lang['common_error'] = "发生错误， 请再试一次.";

//&lt;&lt;PREV
$lang['page_prev'] = '&lt;&lt;上一页';

//NEXT&gt;&gt;
$lang['page_next'] = '下一页&gt;&gt;';

//&middot;
// bullet.  Separator between <<PREV|NEXT>> and page numbers
$lang['page_middot'] = '&middot;';

//&hellip;
// "...", or range of omitted numbers
$lang['page_ellipsis'] = '&hellip; &hellip;';

//You do not have the credentials to access the control panel.
$lang['noaccesscp'] = "你没有进入系统管理控制面板的权限.";

//Storage
$lang['word_storage'] = "储存";

//300 or more recommended.  If reduced, excess pictures are deleted immediately.  Check disk space usage on the <a href=\"testinfo.php\">diagnostics page</a>.
$lang['cpmsg1'] = "最多同时储存的图片数. 建议大于300个 (差不多25MB). 可以在任何时间更改, 但是超过此数字的旧图片会立刻被删除";

//Use &ldquo;avatars/&rdquo; for master board, &ldquo;../board1/avatars/&rdquo; for all other boards.
$lang['cpmsg2'] = "给主要涂鸦板使用 &ldquo;avatars/&rdquo;, 其他涂鸦板用 &ldquo;../board1/avatars/&rdquo;.";

//Image for canvas size preview on Draw screen.  Square picture recommended.  Default &ldquo;preview.png&rdquo;
$lang['cpmsg3'] = "在开始绘画之前预览画布大小用的图片. 建议是方形图片. 默认的图片是 &ldquo;preview.png&rdquo;";

//Rebuild thumbnails
$lang['rebuthumb'] = "重新生成缩略图";

//Page one
$lang['pgone'] = "第一页";

//Archived pictures only
$lang['archipon'] = "只对存档图片";

//All thumbnails (very slow!)
$lang['allthumb'] = "全部的图片 (会花很久时间!)";

//If thumbnail settings are changed, these thumbnails will be rebuilt.
$lang['rebuthumbnote'] = "如果缩略图的设定被改变的话, 这些缩略图将重新生成.";

//You do not have the credentials to delete comments
$lang['errdelecomm'] = "你没有删除评论的权限";

//Send reason to mailbox
$lang['sreasonmail'] = "把理由发至信箱";

//You do not have the credentials to edit the rules.
$lang['erreditrul'] = "你没有编辑板规的权限.";

//Edit Rules
$lang['editrul'] = "编辑板规";

//HTML and PHP are allowed.
$lang['htmlphpallow'] = "允许 HTML 和 PHP 语法";

//You do not have the credentials to delete pictures.
$lang['errdelpic'] = "你没有删除图片的权限.";

//You do not have the credentials to delete users.
$lang['errdelusr'] = "你没有删除会员的权限.";

//Pictures folder is locked!  View Readme.txt for help.
$lang['picfolocked'] = "图片文件夹被锁住了! 如要说明请读 Readme.txt.";

//Unfinished Pictures
$lang['icomppic'] = "尚未完成的图片";

//Click here to recover pictures
$lang['clickrecoverpic'] = "点击这里恢复图片";

//Applet
$lang['word_applet'] = "绘图程序";

//, with palette
$lang['withpalet'] = ", 包括调色盘";

//Canvas
$lang['word_canvas'] = "画布";

//Min
$lang['word_min'] = "最小";

//Max
$lang['word_max'] = "最大";

//NOTE:  You must check &ldquo;animation&rdquo; to save your layers.
$lang['note_layers'] = "注意: 你必须勾选 \"动画\" 才能储存图层.";

//Avatars are disabled on this board.
$lang['avatardisable'] = "此涂鸦板头像功能已禁用.";

//You must login to access this feature.
$lang['loginerr'] = "你必须登入才能使用此功能.";

//File did not upload properly.  Try again.
$lang['err_fileupl'] = "文件没有正常地上传. 请再试一次.";

//Picture is an unsupported filetype.
$lang['unsuppic'] = "此图片格式不支持.";

//Filesize is too large.  Max size is {1} bytes.
$lang['filetoolar'] = "图片文件太大. 最大的大小是{1}个字节.";

//Image size cannot be read.  File may be corrupt.
$lang['err_imagesize'] = "无法读取文件大小. 文件可能已毁损.";

//Avatar upload
$lang['avatarupl'] = "头像上传";

//Avatar updated!
$lang['avatarupdate'] = "头像已成功上传!";

//Your avatar may be a PNG, JPEG, or GIF.
$lang['avatarform'] = "你的头像可以是 PNG, JPEG 或 GIF 格式.";

//Avatars will only show on picture posts (not comments).
$lang['avatarshpi'] = "头像只会在贴图上显示 (不会在评论上显示).";

//Change Avatar
$lang['chgavatar'] = "更改头像";

//Delete avatar
$lang['delavatar'] = "删除头像";

//Missing comment number.
$lang['err_comment'] = "没有评论的编号.";

//You cannot edit a comment that does not belong to you.
$lang['err_ecomment'] = "你无法编辑一个不属于你的评论.";

//You do not have the credentials to edit news.
$lang['err_editnew'] = "你没有编辑公告的权限.";

//The banner is optional and displays at the very top of the webpage.
$lang['bannermsg'] = "横幅不是必须的, 它将显示在页面的最顶端.";

//The notice is optional and displays just above the page numbers on <em>every</em> page.
$lang['noticemsg'] = "公告不是必须的, 它将显示在<em>每页</em>的页数上方.";

//Erase
$lang['word_erase'] = "清除";

//Centered Box
$lang['centrebox'] = "居中的区块";

//Scroll Box
$lang['scrollbox'] = "滚动条";

//Quick Draw
$lang['quickdraw'] = "快速画图";

//You cannot edit a picture that does not belong to you.
$lang['err_editpic'] = "你无法编辑一个不属于你的图片.";

//Type &ldquo;public&rdquo; to share with everyone
$lang['editpicmsg'] = "输入 public 会把此图和大家分享.";

//You cannot use the profile editor.
$lang['err_edprof'] = "你无法使用个人资料编辑器.";

//Real Name (Optional)
$lang['realnameopt'] = "真实姓名 (可不填)";

//This is not your username.  This is your real name and will only show up in your profile.
$lang['realname'] = "这不是你的会员名. 这是你的真实姓名, 只会在个人资料中显示.";

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
$lang['bdaysavmg'] = "必须提供出生年才能储存生日. 月和日可不填.";

//Website
$lang['word_website'] = "网站";

//Website title
$lang['websitetitle'] = "网站名字";

//You can also type a message here and leave the URL blank
$lang['editprofmsg2'] = "你也可以把 URL 栏留空然后留一条你的信息";

//Avatar
$lang['word_avatar'] = "头像";

//Current Avatar
$lang['curavatar'] = "现在的头像";

//Online Presence
$lang['onlineprese'] = "联系信息";

//(Automatic)
// context = Used as label in drop-down menu
$lang['picview_automatic'] = "自动";

//Automatic is the default format and will layout comments to wrap around the picture. Horizontal is good for very high-res screens and displays comments to the right of the picture.  Vertical is recommended for very small, low-res screens.
$lang['msg_automatic'] = "自动是默认的格式, 这会把图片和评论好好的排列. 高分辨率的屏幕最好用水平模式, 这会使评论在图片的右边显示. 垂直模式适合低分辨率的屏幕.";

//Thumbnail mode
$lang['thumbmode'] = "缩略图模式";

//Default
$lang['word_default'] = "默认";

//Scaled
$lang['word_scaled'] = "比例";

//Default is recommended.  Layout will disable most thumbnails.  Scaled is like layout but will shrink big pictures.  Uniformity will make all thumbnails the same size.
$lang['msgdefrec'] = "建议使用默认设置. 自动布局模式不会为大部分图片建立缩略图. 自动缩放模式会把大图片缩小. 统一化模式会把所有的缩略图都弄成同样大小.";

//(Cannot be changed on this board)
$lang['msg_cantchange'] = "无法在此涂鸦板上更改";

//Screen size
$lang['screensize'] = "屏幕大小";

//{1} or higher
// {1} = screen resolution ("1280&times;1024")
$lang['orhigher'] = "{1} 或更高";

//Your screensize, which helps determine the best layout.  Default is 800 &times; 600.
$lang['screensizemsg'] = "你的屏幕大小, 这可以帮助系统决定最好的布局方式. 默认是800&times;600像素.";

// No image data was received by the server.\nPlease try again, or take a screenshot of your picture.
$lang['err_nodata'] = "没有接收到任何图片的数据.\n 为了安全起见, 请把屏幕的图片截取下来, 然后再尝试提交一次.";

//Login could not be verified!  Take a screenshot of your picture.
$lang['err_loginvs'] = "无法确认登入! 请把屏幕的图片截取下来.";

//Unable to allocate a new picture slot!\nTake a screenshot of your picture and tell the admin.
$lang['err_picts'] = "无法找到新的图片编号!\n请把屏幕的图片截取下来并告知系统管理员.";

//Unable to save image.\nPlease try again, or take a screenshot of your picture.
$lang['err_saveimg'] = "无法储存图片.\n请再试一次, 或把屏幕的图片截取下来并告知系统管理员.";

//Rules
$lang['word_rules'] = "板规";

//Public Images
$lang['publicimg'] = "公用图片";

//Drawings by Comment
$lang['drawbycomm'] = "依照评论排列图片";

//Animations by Comment
$lang['animbycomm'] = "依照评论排列动画";

//Archives by Commen
$lang['archbycomm'] = "依照评论排列图片存档";

//Go
// context = Used as button
$lang['word_go'] = "GO";

//My Oekaki
$lang['myoekaki'] = "我的涂鸦";

//Reset Password
$lang['respwd'] = "重设密码";

//Unlock
$lang['word_unlock'] = "解锁";

//Lock
$lang['word_lock'] = "锁";

//Bump
$lang['word_bump'] = "顶";

//WIP
$lang['word_WIP'] = "安储";

//TP
// context = "[T]humbnail [P]NG"
$lang['abrc_tp'] = "缩PNG";

//TJ
// context = "[T]humbnail [J]PEG"
$lang['abrc_tj'] = "缩JPG";

//Thumb
$lang['word_thumb'] = "缩略图";

//Pic #{1}
$lang['picnumber'] = '#{1}';

//Pic #{1} (click to view)
$lang['clicktoview'] = "#{1} 点击这里查看图片";

//(Click to enlarge)
$lang['clickenlarg'] = "点击这里放大图片";

//Adult
$lang['word_adult'] = "成人图片";

//Public
$lang['word_public'] = "公用图片";

//Thread Locked
$lang['tlocked'] = "主题已锁定";

//The mailbox has been disabled.
$lang['mailerrmsg1'] = "信箱被禁用.";

//You cannot access the mailbox.
$lang['mailerrmsg2'] = "你无法进入信箱.";

//You need to login to access the mailbox.
$lang['mailerrmsg3'] = "你必须登入才能进入个人信箱.";

// You cannot access messages in the mailbox that do not belong to you.
$lang['mailerrmsg4'] = "你无法阅读不属于你的私人信息.";

//You cannot access the mass send.
$lang['mailerrmsg5'] = "你无法使用批量发信.";

//Reverse Selection
$lang['revselect'] = "反选";

//Delete Selected
$lang['delselect'] = "删除选定的";

//(Yourself)
// context = Placeholder in table list
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_yourself'] = "你自己";

//Original Message
$lang['orgmessage'] = "原始信息";

//Send
$lang['word_send'] = "发送";

//You can use this to send a global notice to a group of members via the OPmailbox.
$lang['massmailmsg1'] = "你可以使用这个功能批量发信给一组会员.";

//Be careful when sending a mass mail to &ldquo;Everyone&rdquo; as this will result in LOTS of messages in your outbox.  Use this only if you really have to!
$lang['massmailmsg2'] = "请注意当你发送大量信件给 &ldquo;每个人&rdquo; 会使你的寄件夹中塞满信件. 除非有必要否则请勿用此功能!";

//Everyone
$lang['word_everyone'] = "每个人";

//Administrators
$lang['word_administrators'] = "管理员";

//Pictures
$lang['word_pictures'] = "图片";

//Sort by
$lang['sortby'] = "排序";

//Order
$lang['word_order'] = "顺序";

//Per page
$lang['perpage'] = "每页";

//Keywords
$lang['word_keywords'] = "关键词";

//(please login)
// context = Placeholder. Substitute for an e-mail address
$lang['plzlogin'] = "请登入";

//Pending
$lang['word_pending'] = "尚未启用";

//You do not have the credentials to access flag modification.
$lang['err_modflags'] = "你没有权限修改其他会员的权限.";

//Warning:  be careful not to downgrade your own rank
$lang['warn_modflags'] = "警告: 请勿把你自己的权限降级";

//Admin rank
$lang['adminrnk'] = "管理员等级";

//current
$lang['word_current'] = "当前";

//You do not have the credentials to access the password reset.
$lang['retpwderr'] = "你没有重设密码的权限.";

//Only use this feature if a member cannot change their password in the profile editor, and they cannot use the password recovery feature because their recorded e-mail is not working.
$lang['newpassmsg1'] = "仅用于一个会员在个人信息中无法修改密码, 或者因为他的注册邮箱坏掉而无法使用密码恢复功能时.";

//Valid password in database
$lang['validpwdb'] = "在数据库中的有效密码";

//You do not have draw access. Login to draw, or ask an administrator for details about receiving access.
$lang['err_drawaccess'] = "你没有画图的权限. 请登入后画图, 或询问管理员要如何取得画图权限.";

//Public retouch disabled.
$lang['pubretouchdis'] = "公用图片被禁用.";

//Incorrect password to retouch!
$lang['errrtpwd'] = "不正确的续绘密码!";

//You have too many unfinished pictures!  Use Recover Pics menu to finish one.
$lang['munfinishpic'] = "你有太多尚未完成的图片了! 点击菜单上的恢复图片链接完成它们.";

//You have an unfinished picture!  Use Recover Pics menu to finish it.
$lang['aunfinishpic'] = "你有一个尚未完成的图片! 点击菜单上的恢复图片链接完成它.";

//Resize PaintBBS to fit window
$lang['resizeapplet'] = "调整绘图板让它适合窗口的大小";

//Hadairo
$lang['pallette1'] = "肌色系";

//Red
$lang['pallette2'] = "赤色系";

//Yellow
$lang['pallette3'] = "黄橙色系";

//Green
$lang['pallette4'] = "绿色系";

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
$lang['pallette12'] = "灰阶";

//Main
$lang['pallette13'] = "主要色系";

//Wac!
$lang['pallette14'] = "Wac 色系!";

//Save Palette
$lang['savpallette'] = "保存调色盘";

//Save Color Changes
$lang['savcolorcng'] = "保存颜色更改";

//Palette
$lang['word_Palette'] = "调色盘";

//Brighten
$lang['word_Brighten'] = "调亮";

//Darken
$lang['word_Darken'] = "调暗";

//Invert
$lang['word_invert'] = "反转 (对调)";

//Replace all palettes
$lang['paletteopt1'] = "替换所有的调色盘";

//Replace active palette
$lang['paletteopt2'] = "替换所有活跃的调色盘";

//Append palette
$lang['apppalette'] = "附加调色盘";

//Set As Default Palette
$lang['setdefpalette'] = "设为默认调色盘";

//Palette Manipulate/Create
$lang['palletemini'] = "改变/建立调色盘";

//Gradation
$lang['word_Gradation'] = "渐变色";

//Applet Controls
$lang['appcontrol'] = "涂鸦程序设定";

//Please note:
// context = help tips on for applets
$lang['plznote'] = "请注意:";

//Any canvas size change will destroy your current picture!
$lang['canvchgdest'] = "改变画布的大小将会毁掉你现在的图片";

//You cannot resize your canvas when retouching an older picture.
$lang['noresizeretou'] = "你不能在续绘时修改画布的大小.";

//You may need to refresh the window to start retouching.
$lang['refreshbret'] = "在开始续绘前, 你可能需要刷新窗口.";

//Click the &ldquo;Float&rdquo; button in the upper left corner to go fullscreen.
$lang['float'] = "点击左上角的 &ldquo;浮动&rdquo; 按钮可以把涂鸦程序的窗口放到全屏.";

//X (width)
$lang['canvasx'] = "宽度";

//Y (height)
$lang['canvasy'] = "高度";

//Modify
$lang['word_modify'] = "修改";

//Java Information
$lang['javaimfo'] = "Java 信息";

//If you get an &ldquo;Image Transfer Error&rdquo;, you will have to use Microsoft VM instead of Sun Java.
$lang['oekakihlp1'] = "如果你收到 &ldquo;Image Transfer Error (图片传送错误)&rdquo; 的信息, 你可能需要用 Microsoft VM 来代替 Sun Java.";

//If retouching an animated picture and the canvas is blank, play the animation first.
$lang['oekakihlp2'] = "如果你编辑一个动画图片但是画布是空白的, 请先播放动画.";

//Recent IP / host
$lang['reciphost'] = "最近的 IP / 主机";

//Send mailbox message
$lang['sendmailbox'] = "发信";

//Browse all posts ({1} total)
$lang['browseallpost'] = "浏览所有的作品 ({1} 总共)";

//(Broken image)
// context = Placholder for missing image
$lang['brokenimage'] = "毁损图片";

//(No animation)
// context = Placholder for missing animation
$lang['noanim'] = "没有动画";

//{1} seconds
$lang['recover_sec'] = "{1} 秒";

//{1} {1?minute:minutes}
$lang['recover_min'] = "{1} 分";

//Post now
$lang['postnow'] = "现在发布";

//Please read the rules before submitting a registration.
$lang['plzreadrulereg'] = "在提交注册申请前, 请先仔细阅读板规.";

//If you agree to these rules
$lang['agreerulz'] = "如果你同意这些板规";

//click here to register
$lang['clickheregister'] = "点击这里注册";

//Registration Submitted
$lang['regisubmit'] = "注册内容成功提交";

//Your registration for &ldquo;{1}&rdquo; is being processed.
// {1} = Oekaki title
$lang['urgistra'] = "你在 &ldquo;{1}&rdquo; 的注册正在处理中.";

//Your registration for &ldquo;{1}&rdquo; has been approved!<br /><br />You may now configure your membership profile.<br /><br /><a href=\"editprofile.php\">Click here to edit your profile.</a>
// {1} = Oekaki title
$lang['urgistra_approved'] = "你在 &ldquo;{1}&rdquo; 已经被批准了!<br /><br /><a href=\"editprofile.php\">你现在可以以新会员的身份登入, 你可以点击 &ldquo;编辑个人资料&rdquo; 来更新你的会员信息.</a>";

//Before you may login, an administrator must approve your registration.  You should receive an e-mail shortly to let you know if your account has been approved.<br /><br />Once approved, you may update your member profile via the &ldquo;My Oekaki&rdquo; menu.
$lang['aprovemsgyes'] = "在你可以登入之前, 管理员必须批准你的注册申请. 如果你的账号已被开通, 系统会尽快以 E-mail 通知你.<br /><br />在批准之后, 你可以点击 &ldquo;编辑个人资料&rdquo; 来更新你的会员信息.";

//Please check your e-mail soon for the link to activate your account.<br /><br />Once your e-mail has been verified, you will be automatically logged in as a new member, and will be able to add information to your profile.
$lang['aprovemsgno'] = "请尽快到你的 e-mail 信箱收信来启用你的账号.<br /><br />当你的 E-mail 验证通过后, 你将能够以新会员的身份自动登入, 然后你就可以立刻更新你的个人信息了.";

//Notes About Registering
$lang['nbregister'] = "注册注意事项";

//DO NOT REGISTER TWICE.
$lang['registertwice'] = "请勿注册两次以上.";

//You can check if you're in the pending list by viewing the member list and searching for your username.
$lang['regmsg1'] = "你可以到会员列表中寻找你的会员名以确认你是不是待批准的会员.";

//Use only alphanumeric characters for names and passwords.  Do not use quotes or apostrophes.  Passwords are case-sensitive.
$lang['regmsg2'] = "只能用英文字母和数字作会员名称和密码. 请勿用引号或双引号. 密码区分大小写.";

//You may change anything in your profile except your name once your registration is accepted.
$lang['regmsg3'] = "当你的会员资格被批准后, 你可以修改个人资料, 除了你的会员名以外.";

//You must wait for an administrator to approve your registration on this board.  Your registration approval may take awhile if no one at the moment has time to maintain the pending list.  Please be patient; you will receive an e-mail notifying you of your approval.
$lang['regmsg4'] = "你必须等待管理员批准你的注册申请. 你可能要等一些时间让管理员来审核和处理. 请耐心等待, 当你被批准后, 系统将会发 E-mail 通知你.";

//If you don't receive an e-mail with a verification code, or if you cannot activate your account via e-mail, contact an administrator for help.  Administrators may manually approve your account in these cases.
$lang['regmsg5'] = "如果你没收到任何验证邮件, 或你没办法用 E-mail 启用你的账号, 请联络管理员. 管理员可以手动批准你的账号.";

//Your password can be mailed to you if you forget it.  <strong>Your e-mail will only be visible to other registered members.</strong>  You can remove or edit your e-mail after registration.  Ask the board owner about other potential privacy concerns.
$lang['regmsg6'] = "如果你忘记你的密码, 系统可以寄给你. <strong>只有注册会员能看到你的 E-mail 地址.</strong> 你的会员资格被批准以后, 你可以删除或编辑你的 E-mail 地址. 如果你对隐私权有疑问时, 请洽询本涂鸦板的拥有者.";

//{1}+ Age Statement
// {1} = minimum age. Implies {1} age or older
$lang['agestatement'] = "{1}+ 年龄声明";

//<strong>This oekaki is for adults only.</strong>  You are required to declare your birth year to register.  Year is required, month and day are optional and may be left blank.
$lang['adultonlymsg'] = "<strong>这是一个成人的涂鸦板.</strong> 你必须声明你的年龄才可注册. 出生年份是必填的, 日和月可填可不填.";

//A link to your webpage, or a direct link to a sample of your artwork.  Not required for registration on this board.
$lang['nbwebpage'] = "你网站的链接, 或是一个指向你绘画作品的链接. 在此涂鸦板的注册程序中并不是必须的.";

//Submit Registration
$lang['subregist'] = "提交注册";

//Could not fetch information about picture
$lang['coulntfetipic'] = "无法取得图片信息";

//No edit number specified
$lang['noeditno'] = "没有指定编辑的次数";

//This picture is available to all board members.
$lang['picavailab'] = "此图片允许所有的会员续绘.";

//The edited version of this image will be posted as a new picture.
$lang['retouchmsg2'] = "此图片的编辑版本将被另存成新图片.";

//The original artist will be credited automatically.
$lang['retouchmsg3'] = "将会自动注明原作者.";

//A password is required to retouch this picture.
$lang['retouchmsg4'] = "需要编辑密码才能续绘此图片.";

//The retouched picture will overwrite the original
$lang['retouchmsg5'] = "被续绘的图片将覆盖原版图片";

//Continue
$lang['word_continue'] = "继续";



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
$lang['testvar1'] = '只有此涂鸦板的拥有者才能使用错误诊断页面';

//<strong>Folder empty</strong>
$lang['d_folder_empty'] = '<strong>Folder empty</strong>';

//DB info
$lang['dbinfo'] = '数据库信息';

// Database version:
$lang['d_db_version'] = 'Database version:';

//Total pictures:
$lang['d_total_pics'] = 'Total pictures:';

//{1} (out of {2})
// {1} = existing pictures, {2} = maximum
$lang['d_pics_vs_max'] = '{1} (out of {2})';

//Archives:
$lang['d_archives'] = '图片存档:';

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
$lang['d_word_config'] = '设定';

//PHP Information:
$lang['d_php_info'] = 'PHP Information:';

//{1} <a href="{2}">(click for more details)</a>
// {1} = PHP version number
$lang['d_php_ver_num'] = '{1} <a href="{2}">(click for more details)</a>';

//Config version:
$lang['configver'] = '设置版本:';

//Contact:
$lang['word_contact'] = '联系:';

//Path to OP:
$lang['pathtoop'] = '涂鸦板路径:';

//Cookie path:
$lang['cookiepath'] = 'Cookie 路径:';

//Cookie domain:
// context = domain: tech term used for web addresses
$lang['cookie_domain'] = 'Cookie domain:';

//Cookie life:
// context = how long a browser cookie lasts
$lang['cookielife'] = 'Cookie 寿命:';

//(empty)
// context = placeholder if no path/domain set for cookie
$lang['cookie_empty'] = '(empty)';

//{1} seconds (approximately {2} {2?day:days})
$lang['seconds_approx_days'] = '{1} 秒 (approximately {2} 天)';

//Public images: // 'publicimg'
$lang['d_pub_images'] = '公用图片:';

//Safety saves:
$lang['safetysaves'] = '安全储存:';

//Yes ({1} days)
// {1} always > 1
$lang['d_yes_days'] = '是 ({1} 天)';

//No ({1} days)
$lang['d_no_days'] = '否 ({1} 天)';

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
$lang['d_images_label'] = '图片:';

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
$lang['word_locks'] = '锁定';

// Okay
// context = file is "writable" or "good".
$lang['word_okay'] = 'OK';

//<strong>Locked</strong>
// context = "Unwritable" or "Unavailable" rather than broken or secure
$lang['word_locked'] = '被锁定';

//<strong>Missing</strong>
$lang['word_missing'] = '丢失';

/* END testinfo.php */



//You do not have the credentials to upload pictures.
$lang['err_upload'] = "你没有上传图片的权限.";

//Picture to upload
$lang['pictoupload'] = "要上传的图片";

//Valid filetypes are PNG, JPEG, and GIF.
$lang['upldvalidtyp'] = "允许的图片格式有 PNG, JPEG 和 GIF.";

//Animation to upload
$lang['animatoupd'] = "要上传的动画";

//Matching picture and applet type required.
$lang['uploadmsg1'] = "动画必须符合图片和所用的涂鸦程序.";

//Valid filetypes are PCH, SPCH, and OEB.
$lang['uploadmsg2'] = "允许的动画格式有 PCH, SPCH 和 OEB.";

//Valid filetypes are PCH and SPCH.
$lang['uploadmsg3'] = "有效的文件格式有 PCH 和 SPCH.";

//Applet type
$lang['appletype'] = "涂鸦程序";

//Time invested (in minutes)
$lang['timeinvest'] = "花费时间 (分钟)";

//Use &ldquo;0&rdquo; or leave blank if unknown
$lang['uploadmsg4'] = "如果不知道, 请填写0或留空";

//Download
$lang['word_download'] = "下载";

//This window refreshes every {1} seconds.
$lang['onlinelistmsg'] = "此窗口每{1}秒自动刷新.";

//Go to page
$lang['gotopg'] = "跳至页";

//Netiquette applies.  Ask the admin if you have any questions.
// context = Default rules
$lang['defrulz'] = "需要遵守基本的网络礼节. 有任何问题请洽询管理员.";

//Send reason
$lang['sendreason'] = "发送理由";

//&ldquo;avatar&rdquo; field does not exist in database.
$lang['err_favatar'] = "数据库中没有 &ldquo;avatar&rdquo; 字段.";

//Get
$lang['pallette_get'] = "获得";

//Set
$lang['pallette_set'] = "设为";

// Diagnostics
$lang['header_diag'] = '诊断';

// Humanity test for guest posts?
$lang['cpanel_humanity_infoask'] = "访客评论使用验证码?";

// If yes, guests are required to pass a humanity test before posting comments.  The test must be passed only once.
$lang['cpanel_humanity_sub'] = "如果是, 访客在发表评论前必须正确填写验证码. 验证码只需填写一次.";

// And now, for the humanity test.
$lang['humanity_notify_sub'] = "接下来请填写正确的验证码.";

// If canvas is blank or broken, <a href=\"{1}\">click here to import canvas, not animation</a>.
$lang['shi_canvas_only'] = "如果画布为空白或破碎, <a href=\"{1}\">请点击这里导入画布 (非动画)</a>.";

//For help with installation, read the &ldquo;readme.html&rdquo; file that came with your Wacintaki distribution.  Make sure you have CHMOD all files appropriately before continuing with installation.  For technical assistance, please visit the <a href="http://www.NineChime.com/forum/">NineChime Software Forum</a>.
$lang['assist_install'] = "要获得安装的帮助, 请阅读安装包中附带的 readme.html 文件. 并确认在你在开始安装前, 已将所有文件的权限设置正确. 有任何技术问题请访问 <a href=\"http://www.NineChime.com/forum/\">NineChime Software 的论坛</a>.";

//The installer only sets mandatory information.  Once the board has been installed, use the Control Panel to fully configure the board.
$lang['assist_install2'] = "安装程序将只设定基本的信息, 当涂鸦板安装完成后, 请使用控制面板来进行全面设置.";

//<strong>None</strong> will disable thumbnails, and uses a lot of bandwidth.  <strong>Layout</strong> will keep most pictures their original dimensions, and usually uses a vertical layout for wide pictures to keep comments readable.  <strong>Scaled</strong> will use thumbnails for wide pictures, and favor horizontal layout.  <strong>Uniformity</strong> makes all the pictures the same size with a small thumbnail.
$lang['thumbmodesexp'] = "选择 <strong>无</strong> 将禁用缩略图功能, 这样会使用很多带宽. 选择 <strong>自动布局</strong> 将保持图片的原始大小, 并在图片较宽时使用垂直布局, 以使评论文字清晰易读. 选择 <strong>自动缩放</strong> 将为较宽的图片生成缩略图, 并使用水平布局. 选择 <strong>统一化</strong> 将强制为所有的图片生成尺寸相同的缩略图.";

//Resize to this:
$lang['resize_to_this'] = '将大小调整为:';

//Show e-mail to members
$lang['email_show'] = '对会员显示 e-mail';

//Show smilies
$lang['smilies_show'] = '使用表情';

//Host lookup disabled in &ldquo;hacks.php&rdquo; file.
$lang['hosts_disabled'] = '主机查询功能已在 hacks.php 中禁用.';

//Reminder
$lang['word_reminder'] = '提醒';

//Anti-spam
$lang['anti_spam'] = '反垃圾';

//(Delete without sending e-mail)
$lang['anti_spam_delete'] = '(删除并不发送邮件)';

//Log
$lang['word_log'] = '日志';

//You must be an administrator to access the log.
$lang['no_log_access'] = '你必须是管理员才可以访问日志.';

//{1} entries
$lang['log_entries'] = '{1} 条目';

//Category
$lang['word_category'] = '分类';

//Peer (affected)
$lang['log_peer'] = '对象 (受影响的)';

//(Self)
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['log_self'] = '自己';

//Required.  Guests cannot see e-mails, and it may be hidden from other members.
$lang['msg_regmail'] = "必填.  访客无法看到你的邮件地址, 你也可以之后隐藏它.";

//Normal Chibi Paint
$lang['online_npaintbbs'] = '标准的 Chibi Paint';

//No Animation
$lang['draw_no_anim'] = '(无动画)';

//Purge All Registrations
$lang['purge_all_regs'] = "清除所有的注册信息";

//Are you sure you want to delete all {1} registrations?
$lang['sure_purge_regs'] = "你确认要删除 {1} 个注册信息吗?";

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
$lang['registered_on'] = '已注册';

//Modify Canvas Size (max is {1} &times; {2})
$lang['applet_modify'] = "修改画布尺寸 (最大为 {1}&times;{2})";

//Canvas (min: {1}&times;{2}, max: {3}&times;{4})
$lang['draw_canvas_min_max'] = '画布 (最小: {1}&times;{2}, 最大: {3}&times;{4})';

//If you're having trouble with the applet, try downloading the latest version of Java from {1}.
$lang['javahlp'] = "如果你在使用此程序时发生一些问题, 请尝试下载新版在 {1}.";

//If you do not need them anymore, <a href="{1}">click here to remove them</a>.
$lang['boot_still_exist_sub2'] = 'If you do not need them anymore, <a href="{1}">click here to remove them</a>.';

//Delete Palette
$lang['delete_palette'] = 'Delete Palette';

//You may have {1} safety {1?save:saves} at a time.  Remember to finish a safety save soon or it will be automatically deleted within {2} {2?day:days}.
$lang['safesavemsg2'] = "你可以同时有 {1} 个安全储存. 记得要赶快完成这个图片, 否则会被系统自动删除 {2} 天";

//Safety save was successful!  To resume a safety save, click &ldquo;Draw&rdquo;, or use the &ldquo;Recover Pics&rdquo; menu.
$lang['safesavemsg3'] = "成功执行安全储存! 要继续编辑一个安全储存的图片, 请点击菜单上的 &ldquo;开始画图&rdquo;, 或 &ldquo;恢复图片&rdquo; 链接";

//Every time you retouch your safety save, the delete timer will be reset to {1} {1?day:days}.
$lang['safesavemsg5'] = "每次编辑你安全储存的图片, 自动删除时间会重新计算 {1} 天";

//Error reading picture #{1}.
$lang['err_readpic'] = "读取图片错误 {1}";

//What is {1} {2} {3}?
//What is  8   +   6 ?
$lang['humanity_question_3_part'] = "请计算 {1} {2} {3}?";

//Safety saves are stored for {1} {1?day:days}.
$lang['sagesaveopt3'] = "安全储存的图片会保存 {1} 天";

//Comments (<a href="{1}">NiftyToo Usage</a>)
$lang['header_comments_niftytoo'] = '说明 (<a href="{1}">NiftyToo 用法</a>)';

//Edit Comment (<a href="{1}">NiftyToo Usage</a>)
$lang['ecomm_title'] = '编辑评论 (<a href="{1}">NiftyToo 用法</a>)';

//Edit Picture Info (<a href="{1}">NiftyToo Usage</a>)
$lang['erpic_title'] = '编辑 / 恢复图片数据 (<a href="{1}">NiftyToo 用法</a>)';

//Message Box (<a href="{1}">NiftyToo Usage</a>)
$lang['chat_msgbox'] = '收件箱 (<a href="{1}">NiftyToo 用法</a>)';

//(<a href="{1}">NiftyToo Usage</a>)
$lang['common_niftytoo'] = '(<a href="{1}">NiftyToo 用法</a>)';

//(Original by <strong>{1}</strong>)
// {1} = member name
$lang['originalby'] = "(原作者: <strong>{1}</strong>)";

//If you are not redirected in {1} seconds, click here.
// context = clickable, {1} defaults to "3" or "three"
$lang['common_redirect'] = '如果在三秒内没有自动跳转页面, 请点击这里';

//Could not write config file.  Check your server permissions.
$lang['cpanel_cfg_err'] = "无法开启配置文件config.php写入. 请确认你服务器的权限设置";

//Enable Mailbox
$lang['enable_mailbox'] = "启用信箱";

//Unable to read picture #{1}.
$lang['delconf_pic_err'] = 'Unable to read picture #{1}.';

//Image too large!  Size limit is {1} &times; {2} pixels.
$lang['err_imagelar'] = "图片太大! 最大图片大小是 {1}&times;{2} 像素";

//It must not be larger than {1} &times; {2} pixels.
$lang['notlarg'] = "尺寸不能大于 {1}&times;{2} 像素";

//(No avatar)
$lang['noavatar'] = "不要头像";

//Print &ldquo;Edited on (date)&rdquo;
// context = (date) is a literal.  Actual date not printed.
$lang['print_edited_on'] = "注明被编辑 (date)";

//(Edited on {1})
// {1} = current date
$lang['edited_on'] = "被编辑 {1}";

//Print &ldquo;Edited by {1}&rdquo;
// {1} = admin name
$lang['print_edited_by_admin'] = "注明编辑 {1}";

//(Edited by <strong>{1}</strong> on {2})
// {1} = admin name, {2} = current date
$lang['edited_by_admin'] = "注明编辑 {1} on {2}";

//You may check this if you are an adult (at least {1} years old).
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adultsub'] = "如果你大于 {1} 岁请勾选此项";

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
$lang['unmark_adult'] = '反 {1}+';

//Mailbox:
$lang['mailbox_label'] = '信箱:';

//{1} {1?message:messages}, {2} unread
$lang['mail_count'] = '{1} 信息, {2} 尚未阅读';

//From:
$lang['from_label'] = '发自:';

//Re:
// context = may be used inconsistently for technical reasons
$lang['reply_label'] = 'Re:';

//Subject:
$lang['subject_label'] = '主题:';

//Send To:
$lang['send_to_label'] = '传送给:';

//Message:
$lang['message_label'] = '信息:';

//<a href="{1}">{2}</a> @ {3}
// context = "{2=username} sent you this mailbox message at/on {3=datetime}"
$lang['mail_sender_datetime'] = '<a href="{1}">{2}</a> @ {3}';

//Registered: {1} {1?member:members} and {2} {2?admin:admins}
$lang['mmail_reg_list'] = '已注册: {1} 会员, {2} 管理员';

//{1} {1?member:members} active within the last {2} days.
$lang['mmail_active_list'] = '{1} {1?member:members} active within the last {2} days.';

//Everyone ({1})
$lang['mmail_to_everyone'] = '每个人 ({1})';

//Active members ({1})
$lang['mmail_to_active'] = 'Active members ({1})';

//All admins/mods ({1})
// context = admins and moderators
$lang['mmail_to_admins_mods'] = '全部管理员 ({1})';

//Super-admins only
$lang['mmail_to_superadmins'] = '只有超级管理员';

//Flags: FLAG DESCRIPTION
// context = "Can Draw", or "Drawing ability", etc.
$lang['mmail_to_draw_flag']   = '权限: 开始画图';
$lang['mmail_to_upload_flag'] = '权限: 上传';
$lang['mmail_to_adult_flag']  = '权限: 成人观赏权限';
$lang['mmail_to_immune_flag'] = '权限: 免疫权限';

//<a href="{1}">Online</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_online'] = '<a href="{1}">在线会员</a> ({2})';

//<a href="{1}">Chat</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_chat'] = '<a href="{1}">聊天</a> ({2})';

//<strong>Rules</strong>
// context = normally in bold text
$lang['header_rules'] = '板规';

//<strong>Draw</strong>
// context = normally in bold text
$lang['header_draw'] = '图画';

//<a href="{1}">Mailbox</a> ({2})
// context = Used as link. {2} = count of messages
$lang['header_mailbox'] = '<a href="{1}">信箱</a> ({2})';

//Online
// context = Used as label. No HTML link. {1} = count of people (if desired)
$lang['chat_online'] = '在线会员';

//{1} {1?member:members} match your search.
$lang['match_search'] = '{1} {1?member:members} 符合你的搜索';

//{1} {1?member:members}, {2} active within {3} days.
$lang['member_stats'] = '{1} {1?member:members}, {2} active within {3} days.';

//(None)
// context = placeholder if no avatar available
$lang['avatar_none'] = '(None)';

//No rank
// or "None". context = administrator rank
$lang['rank_none'] = '无';

//No Thumbnails
$lang['cp_no_thumbs'] = '无';

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
$lang['url_none'] = '无';

//(Web site)
// context = placeholder if there is a url, but no title (no space to print whole url)
$lang['url_substitute'] = '(Web site)';

//(Default)
// context = placeholder if default template is chosen
$lang['template_default'] = '默认';

//(Default)
// context = placeholder if default language is chosen
$lang['language_default'] = '默认';

//Default
$lang['palette_default'] = '默认';

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
$lang['install_mailbox'] = '显示-信箱';

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

//&ldquo;{1}&rdquo; should be back online shortly.  Send all quesions to {2}.
// {1} = oekaki title, {2} = admin e-mail
$lang['boot_maint_exp'] = '&ldquo;{1}&rdquo; should be back online shortly.  Send all quesions to {2}.';

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
$lang['err_imagelar_bytes'] = '图片太大! 最大图片大小是 {1} 字节';

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
$lang['o_niftyusage']  = '使用 Niftytoo';
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
$lang['file_o_warn'] = '&ldquo;{1}&rdquo; 无法开启.';



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
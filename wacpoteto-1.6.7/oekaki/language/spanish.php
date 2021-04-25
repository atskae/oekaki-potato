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
$lang['cfg_language'] = "Español";

// English Language name (native encoding, capitalized)
$lang['cfg_lang_eng'] = "Spanish";

// Name of translator(s)
$lang['cfg_translator'] = "Federico Arboleda";

//$lang['cfg_language'].' translation by: '.$lang['cfg_translator'];
// context = Variables not needed. Change around order as needed.
$lang['footer_translation'] = 'Traducido al español por: '.$lang['cfg_translator'];

// Comments (native encoding)
$lang['cfg_comments'] = "Cadenas en español para Wacintaki.";

// Zero plural form.  0=singular, 1=plural
// Multiple plural forms need to be considered in next language API
$lang['cfg_zero_plural'] = 1;

// HTML charset ("Content-type")
$charset = "utf-8";

// HTML language name ("Content-language" or "lang" tags)
$metatag_language = "es-co";

// Date formats (http://us.php.net/manual/en/function.date.php)
// Quick ref: j=day(1-31), l=weekday(Sun-Sat), n=month(1-12), F=month(Jan-Dec)
$datef['post_header'] = 'l, j \\d\\e F \\d\\e Y, g:i A';
$datef['admin_edit']  = 'j \\d\\e F \\d\\e Y, g:i a';
$datef['chat']        = 'H:i';
$datef['birthdate']   = 'j/n/Y';
$datef['birthmonth']  = 'n/Y';
$datef['birthyear']   = 'Y';

// Drop-down menu in registration / edit profile.
// "Y" and "M" and "D" in any order.  All 3 letters required.
$datef['age_menu'] = 'DMY';

// Left and Right double quotes
$lang['ldquo'] = '&laquo;';
$lang['rdquo'] = '&raquo;';



/* Language Translation */

//Wacintaki Installation
$lang['install_title'] = "Instalación de Wacintaki";

//MySQL Information
$lang['install_information'] = "Información de MySQL";

//If you do not know if your server has MySQL or how to access your MySQL account, e-mail your tech. support and ask for the following information: hostname, database name, username, and password. Without this information, you will be unable to install OP. If you need to remove the databases, look at the bottom of the page for a link to remove them. If you haven't read the readme.txt, DO IT NOW or your installation will fail! You must make sure you have the proper files and directories CHMODed before you continue.
$lang['install_disclaimer'] = "Si no sabe si su servidor tiene MySQL, o cómo acceder a su cuenta de MySQL, pida la siguiente información en asistencia técnica: nombre del servidor, nombre de la base de datos, nombre de usuario, y contraseña. Sin esta información no podrá instalar OP. Si tiene que quitar las bases de datos, busque al final de la página un enlace para ello. Si no ha leído el archivo readme.txt, ¡<span style=\"strong\">HÁGALO AHORA</span> o su instalación podrá fallar! Debe estar seguro de tener los permisos sobre los archivos y directorios apropiados antes de continuar.";

//If your OP currently works, there is no need to change the MySQL information.
$lang['cpanel_mysqlinfo'] = "Si su copia de OP funciona, no hay necesidad de cambiar la información de MySQL.";

//Default Language
$lang['cpanel_deflang'] = "Idioma por defecto";

//Artist
$lang['word_artist'] = "Artista";

//Compression Settings
$lang['compress_title'] = "Opciones de compresión";

//Date
$lang['word_date'] = "Fecha";

//Time
$lang['word_time'] = "Tiempo";

//min
$lang['word_minutes'] = "min";

//unknown
$lang['word_unknown'] = "desconocido";

//Age
$lang['word_age'] = "Edad";

//Gender
$lang['word_gender'] = "Sexo";

//Location
$lang['word_location'] = "Ubicación";

//Joined
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_joined'] = "Inscrit{1?o:a} en";

//Language
$lang['word_language'] = "Idioma";

//Charset
$lang['word_charset'] = "Juego de caracteres";

//Help
$lang['word_help'] = "Ayuda";

//URL
$lang['word_url'] = "URL";

//Name
$lang['word_name'] = "Nombre";

//Action
$lang['word_action'] = "Acción";

//Disable
$lang['word_disable'] = "Desactivar";

//Enable
$lang['word_enable'] = "Activar";

//Translator
$lang['word_translator'] = "Traductor";

//Yes
$lang['word_yes'] = "Sí";

//No
$lang['word_no'] = "No";

//Accept
$lang['word_accept'] = "Aceptar";

//Reject
$lang['word_reject'] = "Rechazar";

//Owner
$lang['word_owner'] = "Dueño";

//Type
$lang['word_type'] = "Tipo";

//AIM
$lang['word_aolinstantmessenger'] = "AIM";

//ICQ
$lang['word_icq'] = "ICQ";

//Skype
// note: previously MSN
$lang['word_microsoftmessenger'] = "Skype";

//Yahoo
$lang['word_yahoomessenger'] = "Yahoo!";

//Username
$lang['word_username'] = "Nombre de usuario";

//E-mail
$lang['word_email'] = "Correo electrónico";

//Animated
$lang['word_animated'] = "Animado";

//Normal
$lang['word_normal'] = "Normal";

//Registered
$lang['word_registered'] = "Inscrito";

//Guests
$lang['word_guests'] = "Invitados";

//Guest
$lang['word_guest'] = "Invitado";

//Refresh
$lang['word_refresh'] = "Recargar";

//Comments
$lang['word_comments'] = "Comentarios";

//Animations
$lang['word_animations'] = "Animaciones";

//Archives
$lang['word_archives'] = "Archivos";

//Comment
$lang['word_comment'] = "Comentario";

//Delete
$lang['word_delete'] = "Eliminar";

//Reason
$lang['word_reason'] = "Razón";

//Special
$lang['word_special'] = "Especial";

//Archive
$lang['word_archive'] = "Archivar";

//Unarchive
$lang['word_unarchive'] = "Desarchivar";

//Homepage
$lang['word_homepage'] = "Página de inicio";

//PaintBBS
$lang['word_paintbbs'] = "PaintBBS";

//OekakiBBS
$lang['word_oekakibbs'] = "OekakiBBS";

//Archived
$lang['word_archived'] = "Archivado";

//IRC server
$lang['word_ircserver'] = "Servidor IRC";

//days
$lang['word_days'] = "días";

//Commenting
$lang['word_commenting'] = "Comentando";

//Paletted
$lang['word_paletted'] = "Con paleta";

//IRC nickname
$lang['word_ircnickname'] = "Apodo en IRC";

//Template
$lang['word_template'] = "Plantilla";

//IRC channel
$lang['word_ircchannel'] = "Canal IRC";

//Horizontal
$lang['picview_horizontal'] = "Horizontal";

//Vertical
$lang['picview_vertical'] = "Vertical";

//Male
$lang['word_male'] = "Masculino";

//Female
$lang['word_female'] = "Femenino";

//Error
$lang['word_error'] = "Error";

//Board
$lang['word_board'] = "Tablón";

//Ascending
$lang['word_ascending'] = "Ascendente";

//Descending
$lang['word_descending'] = "Descendente";

//Recover for {1}
$lang['recover_for'] = "Recuperar para {1}";

//Flags
$lang['word_flags'] = "Marcas";

//Admin
$lang['word_admin'] = "Admin";

//Background
$lang['word_background'] = "Fondo";

//Font
$lang['word_font'] = "Tipo de letra";

//Links
$lang['word_links'] = "Enlaces";

//Header
$lang['word_header'] = "Encabezado";

//View
$lang['word_view'] = "Ver";

//Search
$lang['word_search'] = "Buscar";

//FAQ
$lang['word_faq'] = "Preguntas frecuentes";

//Memberlist
$lang['word_memberlist'] = "Lista de miembros";

//News
$lang['word_news'] = "Noticias";

//Drawings
$lang['word_drawings'] = "Dibujos";

//Submenu
$lang['word_submenu'] = "Submenú";

//Retouch
$lang['word_retouch'] = "Retocar";

//Picture
$lang['word_picture'] = "Imagen";



/* niftyusage.php */

//Link to Something
$lang['lnksom'] = "Enlace a algún lado";

//URLs without {1} tags will link automatically.
// {1} = "[url]"
$lang['urlswithot'] = "Los URL sin etiqueta {1} se enlazan automáticamente.";

//Text
$lang['nt_text'] = "Texto";

//Bold text
// note: <b> tag
$lang['nt_bold'] = "Texto en negrilla";

//Italic text
// note: <i> tag
$lang['nt_italic'] = "Texto en cursiva";

//Underlined text
// note: <u> tag
$lang['nt_underline'] = "Texto subrayado";

//Strikethrough text
// note: <del> tag
$lang['nt_strikethrough'] = "Texto tachado";

//Big text
// note: <big> tag
$lang['nt_big'] = "Texto grande";

//Small text
// note: <small> tag
$lang['nt_small'] = "Texto pequeño";

//Quoted text
// note: <blockquote> tag
$lang['nt_quoted'] = "Texto citado";

//Preformatted text
// note1: <code> tag (formerly <pre>).
// note2: "Monospaced" or "Fixed Width" would also be appropriate.
$lang['nt_preformatted'] = "Texto preformateado";

//Show someone how to quote
// Context = example of double brackets: "[[quote]]translation[[/quote]]"
$lang['nt_d_quote'] = "Mostrarle a alguien cómo citar";

//These tags don't exist
// context = example of double brackets: "[[ignore]]translation[[/ignore]]"
$lang['nt_d_ignore'] = "Estas etiquetas no existen";

//ignore
// context = Example tag for double brackets: "[[ignore]]"
// translate to any word/charset if desired.
$lang['nt_ignore_tag'] = "ignorar";

//Use double brackets to make Niftytoo ignore tags.
$lang['nt_use_double'] = "Use dos corchetes para que Niftytoo ignore las etiquetas.";

/* END niftyusage.php */



//Mailbox
$lang['word_mailbox'] = "Casilla de correo";

//Inbox
$lang['word_inbox'] = "Buzón de entrada";

//Outbox
$lang['word_outbox'] = "Buzón de salida";

//Subject
$lang['word_subject'] = "Asunto";

//Message
$lang['word_message'] = "Mensaje";

//Reply
$lang['word_reply'] = "Responder";

//From
$lang['word_from'] = "De";

//Write
$lang['word_write'] = "Escribir";

//To
$lang['word_to'] = "Para";

//Status
$lang['word_status'] = "Estado";

//Edit
$lang['word_edit'] = "Editar";

//Register
$lang['word_register'] = "Inscribirse";

//Administration
$lang['word_administration'] = "Administración";

//Draw
$lang['word_draw'] = "Dibujar";

//Profile
$lang['word_profile'] = "Perfil";

//Local
$lang['word_local'] = "Local";

//Edit Pics
$lang['header_epics'] = "Editar imágenes";

//Recover Pics
$lang['header_rpics'] = "Recuperar imágenes";

//Delete Pics
$lang['header_dpics'] = "Eliminar imágenes";

//Delete Comments
$lang['header_dcomm'] = "Eliminar comentarios";

//Edit Comments
$lang['header_ecomm'] = "Editar comentarios";

//View Pending
$lang['header_vpending'] = "Ver pendientes";

//Re-Touch
$lang['word_retouch'] = "Retocar";

//Logout
$lang['word_logoff'] = "Salir";

//Modify Flags
$lang['common_mflags'] = "Cambiar marcas";

//Delete User
$lang['common_delusr'] = "Eliminar usuario";

//(include the http://)
$lang['common_http'] = "(incluir el http://)";

//Move to page
$lang['common_moveto'] = "Mover a";

//Scroll Down
$lang['chat_scroll'] = "Desplazarse hacia abajo";

//Conversation
$lang['chat_conversation'] = "Conversación";

//Chat Information (required)
$lang['chat_chatinfo'] = "Información de chat (obligatoria)";

//Move to Page
$lang['common_mpage'] = "Mover a la página";

//Delete Picture
$lang['common_deletepic'] = "Eliminar imagen";

//Picture Number
$lang['common_picno'] = "Número de imagen";

//Close this Window
$lang['common_window'] = "Cerrar esta ventana";

//Last Login
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['common_lastlogin'] = "Último ingreso en";

//Picture Posts
$lang['common_picposts'] = "Publicaciones de imágenes";

//Comment Posts
$lang['common_compost'] = "Publicaciones de comentarios";

//Join Date
$lang['common_jdate'] = "Fecha de ingreso";

//You can use the message box to leave requests/comments, or talk to Wacintaki members if they are online.<b> All comments will be deleted after a specific amount of posts.<br /></b><br /><b>*</b> - Indicates a registered member.<br /><b>#</b> - Indicates a Guest<br /><br />Although you can see the current online registered members in the chat, Guests who are still online will dissapear after a specific amount of time. Be aware that everyime a guest posts, there WILL be multiple instances of the user under Guest. <br /><br />Your IP and Hostname are tracked in case of abuse. To see a user's IP/hostname, hover your mouse over their username in the chat. The rate of refresh is 15 seconds.
$lang['chat_warning'] = "Puede usar el buzón de mensajes para dejar peticiones o comentarios, o hablarle a los usuarios de Wacintaki si están conectados.<b> Todos los comentarios se borrarán luego de una cantidad específica de publicaciones.<br /></b><br /><b>*</b> - Indica un miembro inscrito.<br /><b>#</b> - Indica un invitado.<br /><br />Aunque se pueden ver los miembros inscritos actualmente en el chat, los invitados que estén aún conectados desaparecerán después de cierto tiempo. Note que cada vez que un invitado publique, habrá múltiples instancias del usuario bajo Invitado. <br /><br />Su IP y nombre de servidor serán rastreados en caso de abuso. Para ver los de otro usuario, ponga el puntero sobre su nombre en el chat. La recarga ocurre cada quince segundos.";

//Database Hostname
$lang['install_dbhostname'] = "Servidor de la base de datos";

//Database Name
$lang['install_dbname'] = "Nombre de la base de datos";

//Database Username
$lang['install_dbusername'] = "Nombre de usuario para la base de datos";

//Database Password
$lang['install_dbpass'] = "Contraseña para la base de datos";

//Display - Registration (Required)
$lang['install_dispreg'] = "Vista: Inscripción (obligatorio)";

//URL to Wacintaki
$lang['install_opurl'] = "URL de Wacintaki";

//Registration E-Mail
$lang['install_email'] = "Correo electrónico de inscripción";

//the e-mail address to use to send out registration information; this is REQUIRED if you are using automatic registration
$lang['install_emailsub'] = "La dirección electrónica que se usará para enviar información de inscripción; ésta es OBLIGATORIA si se está usando inscripción automática.";

//General
$lang['install_general'] = "General";

//Encryption Key
$lang['install_salt'] = "Clave de cifrado";

//An encryption key can be any combination of characters; it is used to generate unique encryption strings for passwords. If you are using multiple boards with the same member database, make sure all boards share the same encryption key.
$lang['install_saltsub'] = "Una clave de cifrado puede ser cualquier combinación de caracteres; se usa para generar cadenas cifradas únicas para las contraseñas. Si está usando varios tablones con la misma base de datos de miembros, asegúrese de usar la misma clave en todos.";

//Picture Directory
$lang['install_picdir'] = "Directorio de imágenes";

//directory where your pictures will be stored
$lang['install_picdirsub'] = "Directorio donde se guardarán sus imágenes.";

//Number of pictures to store
$lang['install_picstore'] = "Número de imágenes a guardar";

//the max number of pictures the OP can store at a time
$lang['install_picstoresub'] = "Máximo número de imágenes que el tablón puede almacenar simultáneamente.";

//Registration
$lang['install_reg'] = "Inscripción";

//Automatic User Delete
$lang['install_adel'] = "Eliminación automática de usuarios";

//user must login within the specified number of days before being deleted from the database
$lang['install_adelsub'] = "el usuario debe conectarse dentro del tiempo especificado, antes de ser eliminado de la base de datos";

//days <b>(-1 disables the automatic delete)</b>
$lang['install_adelsub2'] = "días <b>(-1 desactiva la eliminación automática)</b>";

//Allow Guests to Post?
$lang['install_gallow'] = "¿Permitir a los invitados publicar?";

//if yes, guests can make comment posts on the board and chat
$lang['install_gallowsub'] = "Si sí, los invitados podrán publicar comentarios en el tablón y usar el chat.";

//Require Approval? (Select no for Automatic Registration)
$lang['install_rapproval'] = "¿Aprobación obligatoria? (Elija no para inscripciones automáticas)";

//if yes, approval by the administrators is required to register
$lang['install_rapprovalsub'] = "Si sí, se necesitará la aprobación de los administradores para inscribirse";

//Display - General
$lang['install_dispgen'] = "Vista: General";

//Default Template
$lang['install_deftem'] = "Plantilla predeterminada";

//templates are stored in the templates directory
$lang['install_deftemsub'] = "Las plantillas se guardan en el directorio templates.";

//Title
$lang['install_title2'] = "Título";

//Title for the Wacintai.  Avoid using symbols or decoration, as it will be used for e-mail headers.
$lang['install_title2sub'] = "como quiera llamar la instalación de Wacintaki; se muestra en la barra de navegación";

//Display - Chat
$lang['install_dispchat'] = "Vista: Chat";

//Max Number of Lines to Store for Chat
$lang['install_displinesmax'] = "Máximo número de líneas a guardar por conversación";

//Lines of Chat Text to Display in a Page
$lang['install_displines'] = "Líneas de texto que se mostrarán en una página";

//Paint Applet Settings
$lang['install_appletset'] = "Opciones de la aplicación de dibujo";

//Maximum Animation Filesize
$lang['install_animax'] = "Máximo tamaño de una animación";

//the max filesize animation files can be in bytes; default is 500,000 bytes or 500KB
$lang['install_animaxsub'] = "El máximo tamaño que los archivos de animación pueden tener, en bytes; por defecto son 512&nbsp;000 bytes (500 kB)."; //FIXME: How many bytes in a kilobyte?

//bytes (1024 bytes = 1KB)
$lang['install_bytes'] = "bytes (1024 bytes = 1 kB)";

//Administrator Information
$lang['install_admininfo'] = "Información del administrador";

//Login
$lang['install_login'] = "Conectarse"; // COMMON

//Password
$lang['install_password'] = "Contraseña"; // COMMON

//Recover Password
$lang['header_rpass'] = "Recuperar contraseña";

//Re-Type Password
$lang['install_repassword'] = "Reescriba la contraseña";

//TERMS OF USE: OekakiPoteto is freeware. You are allowed to install unlimited copies of OekakiPoteto on your site. You may modify the code and create your own supporting scripts for it as long as you properly credit RanmaGuy and Marcello at the bottom of the OekakiPoteto pages, along with a link back to suteki.nu. If you fail to give us proper credit, your board can be disabled by us. You may NOT sell OekakiPoteto to anyone! If you were sold OekakiPoteto, you got ripped off for a free product. By using OekakiPoteto, modified, or unmodified, you agree to these terms.
$lang['install_TOS'] = "TÉRMINOS DE USO: OekakiPoteto es freeware. Se le permite instalar cualquier cantidad de copias en su sitio. Puede modificar el código y crear sus propios scripts para éste, siempre y cuando les reconozca autoría a RanmaGuy y Marcello al final de las páginas de OekakiPoteto, junto con un enlace a suteki.nu. Si no lo hace, podemos deshabilitar su tablón. ¡NO PUEDE venderle OekakiPoteto a nadie! Si le vendieron OekakiPoteto, lo estafaron. Al usar OekakiPoteto, modificado o sin modificar, está aceptando estos términos.";

//Databases Removed!
$lang['install_dbremove'] = "Bases de datos eliminadas";

//View Pending Users: Select a User
$lang['addusr_vpending'] = "Ver usuarios pendientes: elija un usuario";

//View Pending Users: Details
$lang['addusr_vpendingdet'] = "Ver usuarios pendientes: detalles";

//Art URL
$lang['addusr_arturl'] = "URL de arte";

//Art URL (Optional)
$lang['reg_arturl_optional'] = "URL de arte (Opcional)";

//Art URL (Required)
$lang['reg_arturl_required'] = "URL de arte (Necesario)";

//Draw Access
$lang['common_drawacc'] = "Acceso a dibujo";

//Animation Access
$lang['common_aniacc'] = "Acceso a animación";

//Comments (will be sent to the registrant)
$lang['addusr_comment'] = "Comentarios (se enviarán al solicitante)";

//Edit IP Ban List
$lang['banip_editiplist'] = "Editar lista de IP vetadas";

//Use one IP per line.  Comments may be enclosed in parentheses at end of line.
$lang['banip_editiplistsub'] = 'Ponga una IP por línea. Se pueden añadir comentarios entre paréntesis al final de la línea.';

//Usage Example: <strong style="text-decoration: underline">212.23.21.* (Username - banned for generic name!)</strong>
$lang['banip_editiplistsub2'] = 'Ejemplo de uso: <strong style="text-decoration: underline">212.23.21.* (Nombre123: vetado por nombre genérico)</strong>';

//Edit Host Ban List
$lang['banip_edithostlist'] = "Editar lista de servidores vetados";

//Same usage as for IPs.  This bans entire ISPs and possibly <em>large</em> numbers of people, so use with caution!
$lang['banip_edithostlistsub'] = 'Mismo uso que con las IP. Sirve para vetar ISP enteros y probablemente <em>mucha</em> gente; úselo con precaución.';

//Usage Example: <strong style="text-decoration: underline">*.dsl.lamernet.net (Proxy ISP, IPs rotate too often)</strong>
$lang['banip_edithostlistsub2'] = 'Ejemplo de uso: <strong style="text-decoration: underline">*.dsl.lamernet.net (ISP de proxy, las IP cambian frecuentemente)</strong>';

//Ban List
$lang['header_banlist'] = "Lista de vetos";

//Control Panel
$lang['header_cpanel'] = "Panel de control";

//Send OPMail Notice
$lang['header_sendall'] = "Enviar notificación por correo de OP";

//<b>You have been banned!<br /><br />Reasons:<br /></b>- A user from your ISP was banned, which banned everyone on that ISP<br />- You were banned for malicious use of the OekakiPoteto<br /><br /><em>If you feel that this message was made in error, speak to an administrator of the OekakiPoteto.</em>
$lang['banned'] = "<b>¡Usted ha sido vetado!<br /><br />Razones:<br /></b>- Un usuario de su ISP fue vetado, lo que vetó a todo el mundo en dicho ISP<br />- Usted fue vetado por abusar de OekakiPoteto<br /><br /><em>Si cree que este mensaje es erróneo, hable con un administrador de OekakiPoteto.</em>";

//Retrieve Lost Password
$lang['chngpass_title'] = "Recuperar contraseña perdida";

//Because your password is encrypted, there is no way to retrieve it. Instead, you must specify a new password. If you receive no errors when submitting this form, that means your password has successfully changed and you can login with it once you are redirected to the index page.
$lang['chngpass_disclaimer'] = "Como su contraseña está cifrada, no hay manera de recuperarla. Deberá en cambio crear una nueva. Si no recibe errores al enviar este formulario, significa que su contraseña ha sido cambiada con éxito y podrá ingresar con ella cuando sea redirigido a la página de inicio.";

//New Password
$lang['chngpass_newpwd'] = "Nueva contraseña";

//Add Comment
$lang['comment_add'] = "Añadir comentario";

//Title of Picture
$lang['comment_pictitle'] = "Título de la imagen";

//Adult Picture?
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['comment_adult'] = "¿Imagen para adultos?";

//Comment Database
$lang['comment_database'] = "Base de datos de comentarios";

//Global Picture Database
$lang['gpicdb_title'] = "Base de datos global de imágenes";

//Delete User
$lang['deluser_title'] = "Eliminar usuario";

//will be sent to the deletee
$lang['deluser_mreason'] = "se enviará al eliminado";

//Clicking delete will remove all records associated with the user, including pictures, comments, etc. An e-mail will be sent to the user appened with your contact e-mail in the case the deletee has further questions on the removal.
$lang['deluser_disclaimer'] = "Hacer clic en eliminar quitará todos los registros asociados con el usuario, incluyendo imágenes y comentarios. Se enviará un correo al usuario junto con su dirección electrónica, por si aquél tiene preguntas sobre la eliminación.";

//Animated NoteBBS
$lang['online_aninbbs'] = "NoteBBS animado";

//Normal OekakiBBS
$lang['online_nmrlobbs'] = "OekakiBBS normal";

//Animated OekakiBBS
$lang['online_aniobbs'] = "OekakiBBS animado";

//Normal PaintBBS
$lang['online_npaintbbs'] = "PaintBBS normal";

//Palette PaintBBS
$lang['online_palpaintbbs'] = "PaintBBS con paleta";

//Admin Pic Recover
$lang['online_apicr'] = "Recuperar imagen de administrador";

//Edit Notice
$lang['enotice_title'] = "Editar notificación";

//Edit Profile
$lang['eprofile_title'] = "Editar perfil";

//URL Title
$lang['eprofile_urlt'] = "Título de URL";

//IRC Information
$lang['eprofile_irctitle'] = "Información de IRC";

//Current Template
$lang['eprofile_curtemp'] = "Plantilla actual";

//Current Template Details
$lang['eprofile_curtempd'] = "Detalles de la plantilla actual";

//Select New Template
$lang['eprofile_templsel'] = "Elegir nueva plantilla";

//Comments / Preferences
$lang['eprofile_compref'] = "Commentarios y preferencias";

//Picture View Mode
$lang['eprofile_picview'] = "Modo de vista de imágenes";

//Allow Adult Images
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adult'] = "Permitir imágenes para adultos";

//Change Password
$lang['eprofile_chngpass'] = "Cambiar contraseña";

//Old Password
$lang['eprofile_oldpass'] = "Contraseña anterior";

//Retype Password
$lang['eprofile_repass'] = "Repita la contraseña";

//You will be automatically logged out if your password successfully changes; you need to re-login when this occours.
$lang['eprofile_pdisc'] = "Será desconectado automáticamente si su contraseña se cambia con éxito; deberá volver a conectarse cuando esto ocurra.";

//Use your browser button to go back.
$lang['error_goback'] = "Use el botón del navegador para regresar";

//Who's Online
$lang['online_title'] = "Quiénes están conectados (últimos quince minutos)";

//View Animation
$lang['viewani_title'] = "Ver animación";

//file size
$lang['viewani_files'] = "Tamaño de archivo";

//Register New User
$lang['register_title'] = "Inscribir nuevo usuario";

//A VALID E-MAIL ADDRESS IS REQUIRED TO REGISTER!
$lang['register_sub2'] = "SE NECESITA UNA DIRECCIÓN ELECTRÓNICA VÁLIDA PARA INSCRIBIRSE";

//Will be shown on your profile when registering; this comment box is limited to 80 chars for proper introduction so Admins can identify you; your IP and hostname is also tracked for security purposes.
$lang['register_sub3'] = "Se mostrará en su perfil cuando se inscriba; este espacio de comentarios está limitado a 80 caracteres para presentarse, de modo que los administradores lo puedan identificar; su dirección IP y nombre de servidor se registrarán por seguridad.";

//Include a URL to a picture or website that displays a piece of your work that you have done.
$lang['register_sub4'] = "Incluya un URL a una imagen o sitio web donde se muestre algo que usted haya hecho.";

//THIS IS NECESSARY TO REQUEST ACCESS TO DRAW ON OEKAKI.
$lang['register_sub5'] = "ESTO ES NECESARIO PARA PEDIR ACCESO A DIBUJAR EN EL OEKAKI.";

//Picture Recovery
$lang['picrecover_title'] = "Recuperación de imágenes";

//Profile for {1}
// {2} = Gender. Singular=Male/Unknown, Plural=Female
$lang['profile_title'] = "Perfil para {1}";

//send a message
$lang['profile_sndmsg'] = "enviar un mensaje";

//Latest Pictures
$lang['profile_latest'] = "Últimas imágenes";

//Modify Applet Size
$lang['applet_size'] = "Cambiar tamaño de la aplicación";

//Using Niftytoo
$lang['niftytoo_title'] = "Usando NiftyToo";

//Nifty-markup is a universal markup system for OekakiPoteto. It allows for all the basic formatting you could want in your messages, profiles, and text.
$lang['niftytoo_titlesub'] = "Las etiquetas Nifty son un lenguaje universal para OekakiPoteto. Permiten todo el formato básico que pueda necesitar en sus mensajes, perfiles, y texto.";

//Linking/URLs
$lang['niftytoo_linking'] = "Enlaces y URL";

//To have a url automatically link, just type it in, beginning with http://
$lang['niftytoo_autolink'] = "Para enlazar automáticamente con un URL, escríbalo precedido de http://";

//Basic Formatting
$lang['niftytoo_basicfor'] = "Formato básico";

//Change a font's color to the specified <em>colorcode</em>.
$lang['niftytoo_textcol'] = "Cambiar el color de la letra al <em>código de color</em> especificado (en inglés).";

//will produce
$lang['niftytoo_produce'] = "producirá";

//Intermediate Formatting
$lang['niftytoo_intermform'] = "Formato intermedio";

//Modify Permissions
$lang['niftytoo_permissions'] = "Cambiar permisos";

//Recover Any Pic
$lang['header_rapic'] = "Recuperar cualquier imagen";

//Super Administrator
$lang['type_sadmin'] = "Superadministrador";

//Owner
$lang['type_owner'] = "Dueño";

//Administrator
$lang['type_admin'] = "Administrador";

//Draw Access
$lang['type_daccess'] = "Acceso a dibujo";

//Animation Access
$lang['type_aaccess'] = "Acceso a animación";

//Immunity
$lang['type_immunity'] = "Inmunidad";

//Adult Viewing
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['type_adultview'] = "Vista para adultos";

//General User
$lang['type_guser'] = "Usuario general";

//A Super Administrator has the ability to add administrators as well as the abilities that Administrators have.
$lang['type_sabil'] = "Un superadministrador puede añadir administradores, y hacer todo lo que hace un administrador.";

//Removing this permission will suspend their account.
$lang['type_general'] = "Quitar este permiso suspenderá la cuenta.";

//Gives the user access to draw.
$lang['type_gdaccess'] = "Le da al usuario acceso para dibujar";

//Gives the user access to animate.
$lang['type_gaaccess'] = "Le da al usuario acceso para animar.";

//Prevents a user from being deleted if the Kill Date is set.
$lang['type_userkill'] = "Evita el borrado de un usuario si se establece la fecha de eliminación";

//Member List
$lang['mlist_title'] = "Lista de miembros";

//Pending Member
$lang['mlist_pending'] = "Miembro pendiente";

//Send Mass Message
$lang['massm_smassm'] = "Enviar mensaje masivo";

//The message subject
$lang['mail_subdesc'] = "Asunto del mensaje";

//The body of the message
$lang['mail_bodydesc'] = "Texto del mensaje";

//Send Message
$lang['sendm_title'] = "Enviar mensaje";

//The recipient of the message
$lang['sendm_recip'] = "Receptor del mensaje";

//Read Message
$lang['readm_title'] = "Leer mensaje";

//Retrieve Lost Password
$lang['lostpwd_title'] = "Recuperar contraseña perdida";

//An e-mail will be sent to the e-mail address you have in your profile. If you did not specify an e-mail address when you registered, you will have to re-register for a new account. The e-mail will contain a URL to where you can specify a new password, as well as the IP and hostname of the computer used to request the password for security purposes.
$lang['lostpwd_directions'] = "Se enviará un correo a la dirección que tiene en su perfil. Si no especificó una dirección electrónica, deberá inscribirse en una nueva cuenta. Dicho correo tendrá un URL para establecer una nueva contraseña, junto con el IP y nombre de servidor del computador del que provino la petición, por seguridad.";

//Local Comment Database
$lang['lcommdb_title'] = "Base de datos local de comentarios";

//Language Settings
$lang['eprofile_langset'] = "Opciones de idioma";



/* functions.php */

//A subject is required to send a message.
$lang['functions_err1'] = "Se necesita un asunto para enviar un mensaje.";

//You cannot use mass mailing.
$lang['functions_err2'] = "No puede enviar correos masivos.";

//Access Denied. You do not have permissions to modify archives.
$lang['functions_err3'] = "Acceso denegado. No tiene permiso para modificar archivos.";

//The username you are trying to retrieve to does not exist. Please check your spelling and try again.
$lang['functions_err4'] = "El nombre de usuario al que intenta recuperar no existe. Revise que esté bien escrito e intente de nuevo.";

//Your new and retyped passwords do not match. Please go back and try again.
$lang['functions_err5'] = "Las dos contraseñas no coinciden. Por favor retroceda e intente de nuevo.";

//Invalid retrival codes. This message will only appear if you have attempted to tamper with the password retrieval system.
$lang['functions_err6'] = "Códigos de recuperación inválidos. Este mensaje aparecerá solamente si intentó violar el sistema de recuperación de contraseñas.";

//The username you are trying to send to does not exist. Please check your spelling and try again.
$lang['functions_err9'] = "El nombre de usuario al que intenta enviar no existe. Revise que esté bien escrito e intente de nuevo.";

//You need to be logged in to send messages.
$lang['functions_err10'] = "Debe estar conectado para enviar mensajes.";

//You cannot access messages in the mailbox that do not belong to you.
$lang['functions_err11'] = "No puede ver mensajes en un buzón que no sea suyo.";

//Access Denied. You do not have permissions to delete users.
$lang['functions_err12'] = "Acceso denegado. No tiene permiso de eliminar usuarios.";

//Access Denied: Your password is invalid, or you are still a pending member.
$lang['functions_err13'] = "Acceso denegado. Su contraseña es errónea o su membrecía aún está pendiente.";

//Invalid verification code.
$lang['functions_err14'] = "Código de verificación inválido.";

//The e-mail address specified in registration already exists in the database. Please re-register with a different address.
$lang['functions_err15'] = "La dirección utilizada en la solicitud existe ya en la base de datos. Inscríbase de nuevo con otra dirección.";

//You do not have the credentials to add or remove users.
$lang['functions_err17'] = "No tiene las credenciales para añadir o quitar usuarios.";

//You cannot claim a picture that is not yours.
$lang['functions_err18'] = "No puede reclamar una imagen que no sea suya.";

//You cannot delete a comment that does not belong to you if you are not an Administrator.
$lang['functions_err19'] = "No puede eliminar un comentario que no sea suyo, salvo que sea usted un administrador.";

//You cannot delete a picture that does not belong to you if you are not an Administrator.
$lang['functions_err20'] = "No puede eliminar una imagen que no sea suya, salvo que sea usted un administrador.";

//You cannot edit a comment that does not belong to you.
$lang['functions_err21'] = "No puede editar un comentario que no sea suyo.";

//{1} Password Recovery
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['precover_title'] = 'Recuperación de contraseña de «{1}»';

//Dear {1},\n\nYou or someone with the IP/hostname [{6}] has requested for a password retrieve on {2 @ {3}. To retrieve your password, please copy and paste or click on this link into your browser:\n\n{4}\n\nYou will then be asked to specify a new password. If you did not request this, you may discard this message.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
// {6} = IP address
$lang['pw_recover_email_message'] = "Estimado/a {1}:\n\nUsted, o alguien desde la IP/servidor [{6}] pidió recuperar la contraseña de «{2}» ({3}). Para recuperar su contraseña, copie y pegue este enlace en su navegador, o haga clic en él:\n\n{4}\n\nSe le pedirá entonces una nueva contraseña. Si no pidió esto, puede ignorar este mensaje.";

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['mandel_title'] = 'Anuncio de eliminación de «{1}»';

//Dear {1},\n\nYour account has been deleted from {2} @ {3}. If you have any questions, please e-mail the administrator that removed your account..\n\nDeleted by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['act_delete_email_message'] = "Estimado/a {1}:\n\nSu cuenta de «{2}» ({3}) ha sido eliminada. Si tiene más preguntas, escríbale al administrador que eliminó su cuenta.\n\nEliminada por: {4} ({5})\nComentarios: {6}";

//{1} Registration Details
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['autoreg_title'] = 'Detalles de inscripción a «{1}»';

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{4}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: Automated Registration
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = permissions
$lang['auto_accept_email_message'] = "Estimado/a {1}:\n\nSe ha aceptado su inscripción. Puede conectarse a «{2}» ({3}) con el nombre y la contraseña con los que se inscribió. Al conectarse, puede ir a la administración a personalizar su perfil, y borrar el IP y servidor que se guardaron durante la inscripción.\n\nHa recibido los siguientes permisos:\n{4}\nSi no tiene acceso a dibujo o a animación, y lo quiere, escríbale a un administrador para más detalles.\n\nRevise también las preguntas frecuentes en el sitio para ver si hay una fecha de eliminación automática. De ser así, debe conectarse al sitio antes de tal fecha para evitar que su cuenta sea eliminada por inactividad.\n\nAprobado por: Inscripción automática";

//{1} Verification Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
// {1} = Oekaki title
$lang['verreg_title'] = 'Anuncio de verificación de «{1}»';

//Dear {1},\n\nYou have registered for {2} @ {3}. To complete your registration, please copy and paste or click on this link into your browser:\n\n{4}\n\nThis will verify your account so you can login into the OekakiPoteto.
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = script URL
$lang['ver_email_message'] = "Estimado/a {1}:\n\nSe ha inscrito a «{2}» ({3}). Para completar su inscripción, copie y pegue este enlace en su navegador, o haga clic en él:\n\n{4}\n\nEsto verificará su cuenta para poder conectarse a OekakiPoteto.";

//Dear {1},\n\nYour registration has been accepted! You may begin to login into {2} @ {3} using the username and password you supplied during registration. Upon login, you may want to go to Administration to customize your profile, and erase your IP and hostname that was recorded during registration.\n\nYou have been granted the following permissions:\n{7}\nIf you do not have Draw/Animation access, and want it, e-mail an Administrator for more details.\n\nAlso, check the FAQ of the site to see if an automatic deletion date is set. If it has been set, you must login to your account before the specified automatic deletion date to prevent your account from being automatically removed due to inactivity.\n\nApproved by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = comments
// {7} = permissions
$lang['admin_accept_email_message'] = "Estimado/a {1}:\n\nSe ha aceptado su inscripción. Puede conectarse a «{2}» en la dirección {3}, con el nombre y la contraseña con los que se inscribió. Al conectarse, puede ir a la administración a personalizar su perfil, y borrar el IP y servidor que se guardaron durante la inscripción.\n\nHa recibido los siguientes permisos:\n{7}\nSi no tiene acceso a dibujo o a animación, y lo quiere, escríbale a un administrador para más detalles.\n\nRevise también las preguntas frecuentes en el sitio para ver si hay una fecha de eliminación automática. De ser así, debe conectarse al sitio antes de tal fecha para evitar que ésta sea eliminada por inactividad.\n\nAprobada por: {4} ({5})\nComentarios: {6}";

//Dear {1},\n\nYour registration at {2} @ {3}, has been rejected. Please e-mail the {2} administrator who rejected you for more details. DO NOT reply to this e-mail address.\n\nRejected by: {4} ({5})\nComments: {6}
// {1} = username
// {2} = oekaki title
// {3} = oekaki URL
// {4} = admin name
// {5} = admin email
// {6} = reason
$lang['reg_reject_email_message'] = "Estimado/a {1}:\n\nSu inscripción a «{2}» ({3}) ha sido rechazada. Por favor escríbale al administrador de {2} que lo rechazó para más detalles. NO responda a este mensaje.\n\nRechazada por: {4} ({5})\nComentarios: {6}";

//Your picture has been removed
// NOTE: mailbox subject.  BBCode only.  No newlines.
$lang['picdel_title'] = 'Su imagen ha sido eliminada';

//Hello,\n\nYour picture ({1}) has been removed from the database by {2} for the following reason:\n\n{3}\n\nIf you have any questions/comments regarding the action, you may reply to this message.
// NOTE: mailbox message.  BBCode only, and use \n rather than <br />.
// {1} = url
// {2} = admin name
// {3} = reason
$lang['picdel_admin_note'] = 'Hola.\n\nSu imagen ({1}) ha sido eliminada de la base de datos por {2}, por la siguiente razón:\n\n{3}\n\nSi tiene preguntas o comentarios al respecto, responda a este mensaje.';

//(No reason specified)
$lang['picdel_admin_noreason'] = '(Razón no especificada)';

//Safety save
$lang['to_wip_admin_title'] = 'Copia de seguridad';

//One of your pictures has been turned into a safety save by {1}. To finish it, go to the draw screen. It must be finished within {2} days.
$lang['to_wip_admin_note'] = 'Una de sus imágenes fue hecha copia de seguridad por {1}. Para terminarla vaya a la página de dibujo. Debe terminarse en {2} días.';

/* END functions.php */



/* maint.php */

//{1} Deletion Notice
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['kill_title'] = "Anuncio de eliminación de «{1}»";

//Dear {1},\n\nThis is an automated message from the {2} automatic deletion system. Your account has been deleted because you have not logged into the oekaki within the last {3} days. If you want to re-register, please visit {4}\n\nBecause the account has been deleted, all post, comment, and other records associated with your username has been removed, and cannot be re-claimed. To avoid further deletions upon re-registration, be sure to log into your account within the specified amount of days in the FAQ.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['kill_email_message'] = "Estimado/a {1}:\n\nÉste es un mensaje del sistema de eliminación automática de Wacintaki. Su cuenta fue eliminada porque no se ha conectado a «{2}» en los últimos {3} días. Si quiere inscribirse de nuevo, vaya a {4}\n\nComo la cuenta fue eliminada, todos los comentarios, publicaciones, y otros registros asociados con su nombre de usuario se borraron, y no pueden recuperarse. Para evitar más eliminaciones al inscribirse de nuevo, asegúrese de conectarse dentro del tiempo especificado en las preguntas frecuentes.\n\nAtentamente,\nEliminación automática";

//{1} Registration Expired
// NOTE: e-mail subject.  No HTML entities or newlines.
$lang['regexpir'] = "Inscripción a «{1}» vencida";

//Dear {1},\n\nYour registration at {2} has expired becuase you did not activate your account within {3} days. To submit a new registration, please visit {4}\n\nIf you did not receive a link to activate your account in a seperate e-mail, try using a different address or check the anti-spam settings used for your e-mail.\n\nSincerely,\n{2}
// context = sent as e-mail.  Use plain text formatting.
// {1} = username
// {2} = oekaki title
// {3} = timeout, in days
// {4} = oekaki URL
$lang['reg_expire_email_message'] = "Estimado/a {1}:\n\nSu inscripción a «{2}» venció porque no activó su cuenta a los {3} días. Postule nuevamente para inscribirse.\n\nSi no recibió un enlace para activar su cuenta en un correo separado, intente usar una dirección distinta, o revise las opciones de spam en su correo electrónico.\n\nAtentamente,\nInscripción automática";

/* END maint.php */



/* FAQ */

//Frequently Asked Questions
$lang['faq_title'] = 'Preguntas frecuentes';

//<strong>Current Wacintaki version: {1}</strong>
$lang['faq_curver'] = '<strong>Versión actual de Wacintaki: {1}</strong>';

//<strong>This oekaki deletes inactive accounts after {1} days.  Log in regularly to keep your account active.</strong>
$lang['faq_autoset'] = '<strong>Este oekaki borra cuentas inactivas después de {1} días. Conéctese regularmente para mantener su cuenta activa.</strong>';

//<strong>No automatic deletion is set.</strong>
$lang['faq_noset'] = '<strong>No hay eliminación automática.</strong>';

//Get the latest Java for running oekaki applets.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_java'] = 'Descargue la última versión de Java para ejecutar aplicaciones de oekaki.';

//JTablet adds pressure sensitivity to Java. Supports PC, Mac, and Linux.
// context = Title for <img>.  Try to keep reasonably short.
$lang['faq_dl_jtablet'] = 'JTablet permite sensibilidad de presión en Java. Compatible con PC, Mac, y GNU/Linux.';

//Table of Contents
$lang['faq_toc'] = 'Tabla de contenido';

// -----

//What is an &ldquo;oekaki?&rdquo;
$lang['faq_question'][0] = '¿Qué es un &laquo;oekaki&raquo;?';

$lang['faq_answer'][0] = '<p>Oekaki es un término japonés que se podría traducir como &laquo;garabato&raquo;. En el Internet, los tablones oekaki son carteleras o foros dedicados a dibujar imágenes usando un programa que se ejecuta en el navegador. Los primeros oekakis consistían en imágenes simples, con unos pocos colores, dibujadas en un lienzo de 300 &times; 300 píxeles, que se publicaban en sucesión, en lugar de en un formato de galería con hilos. Los tablones oekaki empezaron a aparecer en los sitios web japoneses alrededor de 1998, estaban sólo en japonés, y con frecuencia se dedicaban a fanart de anime y videojuegos.</p>

<p>Hoy en día los programas de dibujo son mucho más sofisticados y permiten creaciones más complejas, de distintos tamaños y en varias capas, pero el formato de cartelera del oekaki es con frecuencia el mismo. Mayormente los oekakis son para imágenes que el propio usuario dibuje. Se desaconsejan, y casi siempre se prohíben, las fotos y el trabajo de otros autores. En algunos se le permitirá subir imágenes de su computador, pero en la mayoría no será así.</p>

<p>El término &laquo;oekaki&raquo; se puede referir tanto a los dibujos como al tablón de oekaki: cuando alguien dibuja un oekaki, se refiere a una imagen; cuando alguien dibuja en un oekaki, o mira oekakis, se refiere a participar en un tablón oekaki.</p>';


//What is Wacintaki, and why is it sometimes called Wacintaki Poteto?
$lang['faq_question'][1] = '¿Qué es Wacintaki y por qué a veces se lo llama Wacintaki Poteto?';

$lang['faq_answer'][1] = '<p>Wacintaki es un tablón de oekaki que se puede instalar en un sitio web personal. Es un tablón muy tradicional, con la excepción de que requiere que una persona se inscriba como miembro antes de dibujar. Wacintaki se bifurcó de un oekaki anterior de código abierto, llamado OekakiPoteto, creado por Theo Chakkapark y Marcello Bastéa-Forte. Las versiones anteriores del programa se conocían como Wacintaki Poteto,  pero ahora el tablón se llama Wacintaki a secas.</p>

<p>Si está interesado en instalar Wacintaki en su sitio web, visite la página web de Wacintaki o los foros de asistencia de NineChime para más información.</p>';


$lang['faq_question'][2] = '¿Cómo empiezo a dibujar?';

$lang['faq_answer'][2] = '<p>Wacintaki necesita una inscripción antes de dibujar. Si está inscrito, puede empezar a dibujar haciendo clic en &laquo;<a href="draw.php">Dibujar</a>&raquo;, en el menú. Los administradores pueden revocar su permiso para dibujar, revocar su inscripción, o incluso vetarlo si no sigue las reglas.</p>';


$lang['faq_question'][3] = '¿Cuál es la diferencia entre dibujar y subir imágenes?';

$lang['faq_answer'][3] = '<p>Los tablones Oekaki permiten dibujar con un programa personalizado que se ejecuta en su navegador. Algunos le permitirán también, a discreción del dueño, subir imágenes de su computador. En algunos, todos los miembros podrán subir archivos; sin embargo, en la mayoría sólo unos pocos podrán hacerlo. Revise las reglas del tablón antes de pedir el permiso de subir imágenes.</p>';


$lang['faq_question'][4] = '¿Cómo empiezo a animar?';

$lang['faq_answer'][4] = '<p>Algunos programas de dibujo permiten registrar sus acciones mientras dibuja, para que los otros puedan ver cómo trabaja. Después de hacer clic en &laquo;Dibujar&raquo; en el menú, se le dará la opción de activar animaciones. Algunos programas no permiten animaciones, pero requerirán que usted active esta opción para dibujar en varias capas.</p>

<p>Las animaciones sólo mostrarán sus acciones al dibujar. No se pueden crear dibujos animados, videos, ni editar las animaciones. Si hace clic en  &laquo;undo&raquo; (deshacer) en un programa de dibujo, su última acción no se verá en la animación.</p>';


$lang['faq_question'][5] = 'No veo o no puedo iniciar el programa de dibujo.';

$lang['faq_answer'][5] = '<p>La mayoría de los programas de dibujo son aplicaciones de Java. Si su navegador dice no tener el complemento correcto, intente descargar Java de <a href="http://www.java.com">www.Java.com</a>. Java es una plataforma de software creada por Sun Microsystems y distribuida actualmente por Oracle.</p>

<p>Muchos computadores tienen Java ya instalado. Apple provee Java para los Macintosh, que se sabe que tiene problemas al ejecutar las aplicaciones oekaki.</p>';


$lang['faq_question'][6] = 'No puedo retocar mis imágenes, el lienzo está vacío.';

$lang['faq_answer'][6] = '<p>Las versiones más viejas de Java tienen problemas al importar imágenes; asegúrese de estar usando una versión reciente. Podría también tener que activar animaciones para retocar imágenes. Si está retocando una imagen compleja con una animación larga, tal vez deba esperar un momento mientras el programa de dibujo carga la animación, antes que la imagen aparezca en el lienzo.</p>';


$lang['faq_question'][7] = 'Perdí mi contraseña.';

$lang['faq_answer'][7] = '<p>Puede recuperarla <a href="lostpass.php">aquí</a>.</p>

<p>Si no puede recuperarla con este método, avísele al dueño del tablón, que le dará una nueva contraseña.</p>';


$lang['faq_question'][8] = 'No puedo salir.';

$lang['faq_answer'][8] = '<p>Esto es probablemente un problema del navegador; debe borrar las <i>cookies</i>. Una <i>cookies</i> es un dato que le permite al servidor saber quién es usted. La mayoría de los navegadores permiten borrarlas desde el menú &laquo;Herramientas->Opciones&raquo;.</p>';


$lang['faq_question'][9] = 'Mi imagen no se publicó, o no pude comentar y perdí la imagen.';

$lang['faq_answer'][9] = '<p>Su imagen tal vez no se perdió. Si terminó de subirla pero no pudo comentar, vaya a &laquo;Administración->Recuperar imágenes&raquo; para recuperarla. Su información se guardó en todo caso, incluyendo el tiempo que tomó hacer el dibujo. Si tiene problemas, un administrador puede recuperar imágenes por usted, o subir una captura que haya tomado usted de la pantalla.</p>

<p><strong>NOTA</strong>: Las imágenes guardadas de esta última manera podrían ser muy grandes o estar en un formato incompatible con Wacintaki. Dígale al administrador sobre su imagen perdida <strong>antes</strong> de mandarle un archivo inmenso al correo electrónico.</p>';


$lang['faq_question'][10] = '¿Por qué no puedo ver direcciones electrónicas?';

$lang['faq_answer'][10] = '<p>Para evitar el spam en dichas direcciones, debe usted estar conectado para poder verlas en la lista de miembros o en un perfil.</p>
<p>Siempre podrá ver las de los administradores, pero deberá poner la &laquo;@&raquo; en lugar del símbolo usado para evitar el spam.</p>';


$lang['faq_question'][11] = '¿Qué pasó con el chat o el buzón?';

$lang['faq_answer'][11] = '<p>Wacintaki permite que los administradores desactiven los sistemas de chat y de mensajería encontrados usualmente en OekakiPoteto. El sistema de chat usa mucho ancho de banda y algunos servidores no pueden usarlo. Si se desactivaron los mensajes puede enviarle correos electrónicos a los usuarios directamente; las direcciones están en la lista de miembros y en los perfiles. Usted debe estar inscrito y conectado para verlas.</p>';


$lang['faq_question'][12] = '¿Cómo le mando un mensaje a alguien?';

$lang['faq_answer'][12] = '<p>Haga clic en el nombre de un usuario para ver su perfil, y luego en &laquo;enviar un mensaje&raquo; en dicha página. Debe estar conectado para poder hacerlo.</p>';


$lang['faq_question'][13] = '¿Qué es la eliminación automática de usuarios?';

$lang['faq_answer'][13] = '<p>Si ésta está activada en cierta fecha, todos los usuarios que no se hayan conectado para ese momento serán eliminados del tablón. Para evitarlo pídale a un administrador inmunidad, o conéctese con frecuencia al sitio.</p>';


$lang['faq_question'][14] = '¿Qué es una imagen para adultos?';

$lang['faq_answer'][14] = '<p>Como el oekaki es un pasatiempo internacional, la definición de imágenes para adultos varía. Usualmente esto significa cualquier material explícito, no apto para menores de 18; esto incluye violencia fuerte, desnudos, y todo lo de naturaleza sexual. Algunos tablones oekaki no permiten contenido para adultos, así que lea las reglas para ver si los administradores especificaron alguna condición adicional o alternativa.</p>

<p>Si un oekaki está marcado como sólo para adultos, debe estipular su edad antes de inscribirse. Si quiere ver imágenes para adultos y no puede, vaya a &laquo;Editar perfil&raquo; y active la casilla &laquo;Permitir imágenes para adultos&raquo;.</p>';


$lang['faq_question'][15] = '¿Por qué unas imágenes aparecen en miniatura y otras no?';

$lang['faq_answer'][15] = '<p>Las imágenes se vuelven miniaturas según varios factores, incluyendo el tamaño del archivo, y qué modalidad de miniaturas estableció el administrador. Puede cambiar las modalidades en &laquo;<a href="editprofile.php">Editar perfil</a>&raquo; si el administrador se lo permite a los usuarios.</p>';

// -----

//Who {1?is the owner:are the owners} of this oekaki?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionA'] = '¿Quién{1? es el dueño:es son los dueños} de este oekaki?';

//Who {1?is the administrator:are the administrators}?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionB'] = '¿Quién{1? es el administrador:es son los administradores}?';

//Who {1?is the moderator:are the moderators}?
// {1} = number, {2} = gender(s) (plural if all females)
$lang['faq_questionC'] = '¿Quién{1? es el moderador:es son los moderadores}?';

/* End FAQ */



$lang['word_new'] = "Nuevo";

$lang['word_unread'] = "Sin leer";

$lang['word_read'] = "Leído";

$lang['word_replied'] = "Respondido";

$lang['register_sub8'] = "Después de inscribirse, busque en su correo electrónico el enlace para activar su cuenta.";

//Upload
$lang['word_upload'] = "Subir";

//Upload Picture
$lang['upload_title'] = "Subir imagen";

//File to upload
$lang['upload_file'] = "Archivo a subir";

//ShiPainter
$lang['word_shipainter'] = "ShiPainter";

//ShiPainter Pro
$lang['word_shipainterpro'] = "ShiPainter Pro";

//Edit Banner
$lang['header_ebanner'] = "Editar pancarta";

//Reset All Templates
$lang['install_resettemplate'] = "Restablecer todas las plantillas";

//If yes, all members will have their template reset to the default
$lang['install_resettemplatesub'] = "Si sí, se restablecerán las plantillas de todos a las predeterminadas";

//N/A
$lang['word_na'] = "N. D.";

//You do not have draw access. Ask an administrator on details for receiving access.
$lang['draw_noaccess'] = "No tiene acceso a dibujar. Pregúntele a un administrador los detalles para recibirlo.";

//Upload Access
$lang['type_uaccess'] = 'Acceso a carga';

//Print &ldquo;Uploaded by&rdquo;
$lang['admin_uploaded_by'] = 'Imprimir &laquo;subido por&raquo;';

//Gives the user access to the picture upload feature.
$lang['type_guaccess'] = 'Le da al usuario acceso a la función de subir imágenes.';

//Delete database
$lang['delete_dbase'] = "Eliminar base de datos";

//Database Uninstall
$lang['uninstall_prompt'] = "Desinstalar base de datos";

//Are you sure you want to remove the database?  This will remove information for the board
$lang['sure_remove_dbase'] = "¿Está seguro de querer quitar la base de datos? Esto eliminará información del tablón";

//Images, templates, and all other files in the OP directory must be deleted manually.
$lang['all_delete'] = "Las imágenes, plantillas, y otros archivos en el directorio de OP deben borrarse a mano.";

//If you have only one board, you may delete both databases below.
$lang['delete_oneboard'] = "Si tiene un solo tablón, puede eliminar las dos bases de datos abajo.";

//If you are sharing a database with more than one board, be sure to delete <em>only</em> the database for posts and comments.  If you delete the database for member profiles, all your boards will cease to function!
$lang['sharing_dbase'] = "Si está compartiendo una base de datos con más tablones, asegúrese de eliminar <em>sólo</em> la base de datos de publicaciones y comentarios. Si elimina la de perfiles de miembros, todos sus tablones dejarán de funcionar.";

//Each board must be removed with its respective installer.
$lang['remove_board'] = "Debe eliminarse cada tablón con su instalador respectivo.";

//Delete posts and comments.
$lang['delepostcomm'] = "Borrar publicaciones y comentarios.";

//Delete member profiles, chat, and mailboxes.
$lang['delememp'] = "Eliminar perfiles de miembros, chat, y buzones.";

//Uninstall error
$lang['uninserror'] = "Error de desinstalación";

//Valid database and config files were not found.  The board must be properly installed before any database entries can be removed.  If problems persist, let your sysadmin delete the databases by name.
$lang['uninsmsg'] = "No se encontraron archivos válidos de base de datos o de configuración. El tablón debe estar bien instalado antes de poder eliminar cualquier entrada en la base de datos. Si los problemas persisten, pídale al administrador eliminar las bases por nombre.";

//Uninstall Status
$lang['unistatus'] = "Estado de desinstalación";

//NOTE:  No databases changed
$lang['notedbchange'] = "NOTA: No cambiaron las bases de datos";

//Return to the installer
$lang['returninst'] = "Volver al desinstalador";

//Wacintaki Installation
$lang['wacinstall'] = "Instalación de Wacintaki";

//Installation Progress
$lang['instalprog'] = "Progreso de la instalación";

//ERROR:  Your database settings are invalid.
$lang['err_dbs'] = "ERROR: Sus opciones de base de datos no son válidas.";

//NOTE:  Database password is blank (not an error).
$lang['note_pwd'] = "NOTA: La contraseña de la base de datos está vacía (no es un error).";

//ERROR:  The administrator login name is missing.
$lang['err_adminname'] = "ERROR: Falta el nombre del administrador.";

//ERROR:  The administrator password is missing.
$lang['err_adminpwd'] = "ERROR: Falta la contraseña del administrador.";

//ERROR:  The administrator passwords do not match.
$lang['err_admpwsmtch'] = "ERROR: No coinciden las contraseñas del administrador.";

//Could not connect to the MySQL database.
$lang['err_mysqlconnect'] = "No se pudo conectar a la base de datos MySQL.";

//Wrote database config file...
$lang['msg_dbsefile'] = "Se escribió el archivo de configuración de la base de datos...";

//ERROR:  Could not open database config file for writing.  Check your server permissions
$lang['err_permis'] = "ERROR: No se pudo abrir para escritura el archivo de configuración de la base de datos. Revise sus permisos en el servidor.";

//Wrote config file...
$lang['wrconfig'] = "Se escribió el archivo de configuración...";

//ERROR:  Could not open config file for writing.  Check your server permissions.
$lang['err_wrconfig'] = "ERROR: No se pudo abrir para escritura el archivo de configuración. Revise sus permisos en el servidor.";

//ERROR:  Could not create folder &ldquo;{1}&rdquo;
$lang['err_cfolder'] = "ERROR: No se pudo crear el directorio &laquo;{1}&raquo;";

//ERROR:  Folder &ldquo;{1}&rdquo; is locked.  You may have to create this folder manually.
$lang['err_folder'] = "ERROR: Directorio &laquo;{1}&raquo; está bloqueado. Debe crear dicho directorio a mano.";

//One or more base files could not be created.  Try again or manually create the listed files with zero length.
$lang['err_fcreate'] = "Uno o más archivos de base no se pudieron crear. Intente de nuevo o cree manualmente los archivos listados, vacíos.";

//'Wrote base &ldquo;resource&rdquo; folder files...'
$lang['write_basefile'] = "Escritos los archivos base del directorio &laquo;resource&raquo;...";

//Starting to set up database...
$lang['startsetdb'] = "Comenzando a configurar la base de datos...";

//Finished setting up database...
$lang['finishsetdb'] = "Se terminó de configurar la base de datos...";

//If you did not receive any errors, the databases have been installed.
$lang['noanyerrors'] = "Si no recibió errores, se instalaron las bases de datos.";

//If you are installing another board and your primary board is functioning properly, ignore any database errors.
$lang['anotherboarderr'] = "Si está instalando otro tablón y el principal funciona bien, ignore los errores de bases de datos.";

//Click the button below to finalize the installation.  This will clean up the installer files and prevent security problems.  You will have to copy <em>install.php</em> into the Wacintaki folder if you need to uninstall the database.  All other maintenance can be done with the control panel.
$lang['clickbuttonfinal'] = "Haga clic en el botón de abajo para terminar la instalación. Esto limpiará los archivos del instalador y evitará problemas de seguridad. Tendrá que copiar <em>install.php</em> al directorio de Wacintaki si necesita desinstalar la base de datos. El resto del mantenimiento se puede hacer con el panel de control.";

//Secure installer and go to the BBS
$lang['secinst'] = "Asegurar el instalador e ir al BBS";

//Installation Error
$lang['err_install'] = "Error de instalación";

//&ldquo;templates&rdquo; and &ldquo;resource&rdquo; folders are not writable!  Be sure to CHMOD these folders to their correct permissions before running the installer.
$lang['err_temp_resource'] = "Los directorios &laquo;templates&raquo; y &laquo;resource&raquo; están protegidos. Establezca los permisos necesarios con CHMOD antes de ejecutar el instalador.";

//Wacintaki Installation
$lang['wac_inst'] = "Instalación de Wacintaki";

//Installation Notes
$lang['inst_note'] = "Notas de instalación";

// $lang['assist_install'] = REVISED v1.9

//One MySQL database is required to install Wacintaki.  If you do not know how to access your MySQL account, e-mail your sysadmin, or log into your control panel and look for a database tool such as phpMyAdmin.  On most servers, &ldquo;localhost&rdquo; will work for the hostname, though web hosts with a dedicated MySQL server may require something such as &ldquo;mysql.server.com&rdquo;.  Be aware that some database tools, such as CPanel or phpMyAdmin, may automatically add a prefix to your database name or username, so if you create a database called &ldquo;oekaki&rdquo;, the result may end up being &ldquo;accountname_oekaki&rdquo;.  The database table prefixes (default &ldquo;op_&rdquo;) are only significant if you wish to install more than one oekaki.  Consult the manual for more information on installing multiple oekakis with one database.
$lang['mysqldb_wact'] = "Hace falta una base de datos MySQL para instalar Wacintaki. Si no sabe cómo acceder a su cuenta de MySQL, escríbale al administrador del sistema, o entre a su panel de control y busque una herramienta para bases de datos como phpMyAdmin. &laquo;localhost&raquo; sirve como nombre de servidor en la mayoría de ellos, aunque los servicios de alojamiento con un servidor MySQL dedicado usualmente necesitan algo como &laquo;mysql.server.com&raquo;. Algunas de estas herramientas, como CPanel o phpMyAdmin, podrían añadir automáticamente un prefijo al nombre de usuario o de base de datos; así que si crea una base llamada &laquo;oekaki&raquo;, el resultado final podría ser &laquo;nombredecuenta_oekaki&raquo;. Los prefijos de las tablas en la base de datos (&laquo;op_&raquo; es el predeterminado) sólo son significativos si quiere instalar más de un oekaki. Consulte el manual para más información sobre instalar varios oekakis con una base de datos.";

//Database Table Prefix
$lang['dbtablepref'] = "Prefijo de las tablas de la base de datos";

//If installing mutiple boards on one database, each board must have its own, unique table prefix.
$lang['multiboardpref'] = "Si instala varios tablones con la misma base de datos, cada uno debe tener su propio y único prefijo de tablas.";

//Member Table Prefix
$lang['memberpref'] = "Prefijo de la tabla de miembros";

//If installing multiple boards on one database, and you want all members to access each board without seperate registrations, make sure each board shares the same table prefix.  To force sperate registrations for each board, make this prefix unique for each installation.
$lang['instalmulti'] = "Si instala varios tablones con la misma base de datos, y quiere que los usuarios puedan acceder a todos ellos sin inscripciones separadas, todos ellos deben tener el mismo prefijo de tabla. Para obligar a que se inscriban por separado a cada tablón, haga los prefijos distintos.";

//<a href="{1}">Click here to uninstall an existing database.</a>  Confirmation will be requested.
$lang['uninstexist'] = '<a href="{1}">Clic aquí para desinstalar una base de datos existente.</a>  Se le pedirá confirmación.';

//This is a guess.  Make sure it is correct, or registration will not work correctly.
$lang['guessregis'] = "Esto es una conjetura. Verifique que sea correcta, o la inscripción no funcionará bien.";

//Picture Name Prefix
$lang['picpref'] = "Prefijo de los nombres de imagen";

//This prefix will appear on every picture and animation saved by the BBS.  Example: &ldquo;OP_50.png&rdquo;
$lang['picprefexp'] = "Este prefijo aparecerá en todas las imágenes y animaciones guardadas por el BBS. P. ej.: &laquo;OP_50.png&raquo;.";

//Allow Public Pictures
$lang['allowppicture'] = "Permitir imágenes públicas";

//Public pictures may be retouched by any member with draw permissions. No passwords are used, and retouched images are submitted as new posts. <strong>NOTE</strong>: May result in floods without strict rules and administration.
$lang['ppmsgrtouch'] = "Las imágenes públicas pueden ser retocadas por cualquier usuario con permiso de dibujar. No se usan contraseñas, y los retoques se envían como publicaciones nuevas. <strong>NOTA</strong>: Puede resultar en una avalancha de contenido a falta de reglas o administración estrictas.";

//Allow Safety Saves
$lang['allowsafesave'] = "Permitir copias de seguridad";

//Safety saves do not show up on the board while they are in progress.  Only one safety save is allowed per member, and they are automatically deleted after a certain number of days
$lang['safesaveexp'] = "Las copias de seguridad no se muestran en el tablón mientras estén en progreso. Solamente se permite una por miembro, y se borran automáticamente tras cierto número de días.";

//Safety Save Storage
$lang['savestorage'] = "Almacenamiento de copias de seguridad";

//Number of days safety saves are stored before they are removed.  Default is 30.
$lang['safetydays'] = "Número de días por el que se guardarán las copias de seguridad antes de borrarse. 30 por defecto.";

//Auto Immunity for Artists
$lang['autoimune'] = "Autoinmunidad para los artistas";

//If yes, people who draw pictures will automatically receive the immunity flag from auto user delete.
$lang['autoimune_exp'] = "Si sí, los usuarios que dibujen recibirán automáticamente inmunidad contra la eliminación automática de usuarios.";

//Show Rules Before Registration
$lang['showrulereg'] = "Ver reglas antes de la inscripción";

//If yes, people will be shown the rules before they can submit a new registration.  Use &ldquo;Edit Rules&rdquo; in the admin menu to set rules.
$lang['showruleregexp'] = "Si sí, se les mostrarán las reglas a los usuarios antes de que puedan inscribirse. Use &laquo;Editar reglas&raquo; en el menú de administración para establecerlas.";

//Require Art Submission
$lang['requireartsub'] = "Exigir muestra de arte";

//If yes, new users are instructed to provide a link to a piece of art for the administrator to view.
$lang['requireartsubyes'] = "Si sí, los nuevos usuarios deberán dar un enlace a una obra suya para que los administradores la vean.";

//If no, new users are told the URL field is optional.
$lang['requireartsubno'] = "Si no, se les dirá que el campo URL es opcional.";

//No (forced)
$lang['forceactivate'] = "No (forzar)";

//If yes, approval by the administrators is required to register.
$lang['activateyes'] = "Si sí, se necesita la aprobación de los administradores para inscribirse.";

//If no, users will receive an activation code in their e-mail.
$lang['activeno'] = "Si no, los usuarios recibirán un código de activación en su correo electrónico.";

//Use &ldquo;forced&rdquo; ONLY if your server cannot send e-mails, and you want automatic approval.
$lang['activateforced'] = "Use &laquo;forzar&raquo; SÓLO si su servidor no puede mandar correos y quiere aprobación automática.";

//Default Permissions for Approved Registrations
$lang['defaultpermis'] = "Permisos predeterminados para inscripciones aprobadas";

//Members may bump own pictures on retouch?
$lang['bumpretouch'] = "¿Los miembros pueden hacer subir sus propias imágenes al retocarlas?";

//Author Name
$lang['authorname'] = "Nombre del autor";

//Name of the BBS owner.  This is displayed in the copyright and page metadata.
$lang['bbsowner'] = "Nombre del dueño del BBS. Se mostrará en los metadatos de copyright y de la página.";

//Adult rated BBS
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbs'] = "BBS para adultos";

//Select Yes to declare your BBS for adults only.  Users are required to state their age to register.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsdesc'] = "Elija &laquo;Sí&raquo; para declarar que su BBS es sólo para adultos. Se les preguntará a los usuarios la edad al inscribirse.";

//NOTE:  Does <strong>not</strong> make every picture adult by default.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['adultrbbsnote'] = "NOTA: <strong>No hace</strong> que todas las imágenes se marquen por defecto como para adultos.";

//Allow guests access to pr0n
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpron'] = "Permitirle a los invitados acceso al porno";

//If yes, adult images are blocked and may be viewed by clicking the pr0n placeholder.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronyes'] = "Si sí, se bloquean las imágenes para adultos pero se pueden ver haciendo clic en el marcador de porno.";

//If no, the link is disabled and all access is blocked.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['allowpronno'] = "Si no, se deshabilita el enlace y se bloquea todo el acceso.";

//Number of Pics on Index
$lang['maxpiconind'] = "Número de imágenes en el índice";

//Avatars
$lang['word_avatars'] = "Avatares";

//Enable Avatars
$lang['enableavata'] = "Activar avatares";

//Allow Avatars On Comments
$lang['allowavatar'] = "Permitir avatares en los comentarios";

//Avatar Storage
$lang['AvatarStore'] = "Almacenamiento de avatares";

//Change <strong>only</strong> if installing multiple boards.  Read the manual.
$lang['changemulti'] = "Cambie <strong>sólo</strong> si está instalando varios tablones. Lea el manual.";

//Basically, use one folder for all boards.  Example:  use &ldquo;avatars&rdquo; for board 1, &ldquo;../board1/avatars&rdquo; for board 2, etc.
$lang['changemultidesc'] = "Básicamente usar un directorio para todos los tablones. Ejemplo: usar &laquo;avatars&raquo; para el primero, &laquo;../board1/avatars&raquo; para el segundo, etc.";

//Maximum Avatar Size
$lang['maxavatar'] = "Máximo tamaño de un avatar";

//Default is 50 &times; 50 pixels.  Larger than 60 &times; 60 not recommended
$lang['maxavatardesc'] = "Por defecto, 50 &times; 50 píxeles. No se recomienda poner más de 60 &times; 60.";

//Default Canvas Size
$lang['cavasize'] = "Tamaño predeterminado del lienzo";

//The default canvas size.  Typical value is 300 &times; 300 pixels
$lang['defcanvasize'] = "El tamaño predeterminado del lienzo; lo usual es de 300 &times; 300 píxeles.";

//Minimum Canvas Size
$lang['mincanvasize'] = "Tamaño mínimo del lienzo";

//The minimum canvas size.  Recommended minimum is 50 &times; 50 pixels
$lang['mincanvasizedesc'] = "El tamaño mínimo del lienzo. Se recomienda usar 50 &times; 50 píxeles.";

//Maximum Canvas Size
$lang['maxcanvasize'] = "Tamaño máximo del lienzo";

//Maximum canvas.  Recommended maximum is 500 &times; 500 pixels
$lang['maxcanvasizedesc'] = "El lienzo más grande. Se recomienda usar 500 &times; 500 píxeles.";

//Be aware that a small increase in dimentions results in a large increase in surface area, and thus filesize and bandwidth.  1000 &times; 1000 uses <strong>four times</strong> as much bandwidth as 500 &times; 500
$lang['maxcanvasizedesc2'] = "Tenga en cuenta que un pequeño incremento en las dimensiones causa un gran incremento en el área superficial, y por ende en el tamaño de archivo y ancho de banda. 1000 &times; 1000 usa el <strong>cuádruple</strong> del ancho de banda que 500 &times; 500.";

//Number of pictures to display per page of the BBS
$lang['maxpicinddesc'] = "Número de imágenes a mostrar por página en el BBS";

//Number of Entries on Menus
$lang['menuentries'] = "Número de entradas en los menús";

//Number of entries (such as user names) to display per page of the menus and admin controls
$lang['menuentriesdesc'] = "Número de entradas (e. g. nombres) a mostrar por página de menús y controles de administrador";

//Use Smilies in Comments?
$lang['usesmilies'] = "¿Usar emotíconos en los comentarios?";

//Smilies are configurable by editing the &ldquo;hacks.php&rdquo; file
$lang['usesmiliedesc'] = "Se pueden configurar los emotíconos editando el archivo &laquo;hacks.php&raquo;.";

//Maximum Upload Filesize
$lang['maxfilesize'] = "Límite de tamaño para cargas";

//The max filesize uploaded pictures can be, in bytes.  Default is 500,000 bytes or 500KB.
$lang['maxupfileexp'] = "El máximo tamaño de archivo que pueden tener las imágenes subidas, en bytes. El predeterminado es de 500&nbsp;000 bytes o 500 kB."; //FIXME: How many bytes in a kilobyte?

//The maximum value allowed by most servers varies from 2 to 8MB.
$lang['maxupfileexp2'] = "El máximo permitido por la mayoría de los servidores varía de 2 a 8 MB.";

//Canvas size preview
$lang['canvasprev'] = "(Vista previa del lienzo)";

//Image for canvas size preview on Draw screen.  Square picture recommended.
$lang['canvasprevexp'] = "Imagen para la vista previa del tamaño del lienzo en la pantalla de dibujo. Se recomienda una imagen cuadrada.";

//Preview Title
$lang['pviewtitle'] = "Título de vista previa";

//Title of preview image (Text only &amp; do not use double quotes).
$lang['titleprevwi'] = "Título de la imagen de vista previa (sólo texto; no use comillas dobles).";

//&ldquo;Pr0n&rdquo; placeholder image
$lang['pron'] = "Marcador &laquo;Pr0n&raquo; (porno)";

//Image for substitution of pr0n.  Square picture recommended.  Default &ldquo;pr0n.png&rdquo;
$lang['prondesc'] = "Imagen para sustituir el porno. Se recomienda una cuadrada. La predeterminada es &laquo;pr0n.png&raquo;.";

//Enable Chat
$lang['enablechat'] = "activar chat";

//Note:  chat uses a lot of bandwidth
$lang['chatnote'] = "Nota: el chat usa mucho ancho de banda";

//Your server does not have the graphics system &ldquo;GDlib&rdquo; installed, therefore you cannot enable thumbnail support.  However, you may still select a default thumbnail mode which will conserve screenspace by shrinking pictures.
$lang['err_nogdlib'] = "Su servidor no tiene el sistema gráfico &laquo;GDlib&raquo; instalado, por ende no puede activar miniaturas. Puede sin embargo elegir un modo de miniaturas predeterminado, que ahorrará espacio en pantalla encogiendo las imágenes.";

//Four thumbnail modes are available.  None, Layout, Scaled, and Uniformity.  If you're confused which mode to use, try Scaled first.
$lang['thumbmodes'] = "Hay cuatro modos de miniaturas disponibles: Ninguna, Distribuidas, A Escala, y Uniformes. Si no está seguro sobre cuál elegir, pruebe primero A Escala.";

//If you choose &ldquo;None&rdquo;, thumbnail support will always be off for all members unless you enable it later in the control panel.
$lang['thumbmodesexp2'] = "Si elige &laquo;Ninguna&raquo;, ningún miembro podrá tener miniaturas salvo que usted lo active luego en el panel de control.";

//Default thumbnail mode
$lang['defthumbmode'] = "Modo de miniaturas predeterminado";

//None
$lang['word_none'] = "Ninguna";

//Layout
$lang['word_layout'] = "Distribuidas";

//Scaled (default)
$lang['word_defscale'] = "A Escala (por defecto)";

//Uniformity
$lang['word_uniformity'] = "Uniformes";

//Tip:  Options are ordered in terms of bandwidth.  Uniformity uses the least bandwidth.  Scaled Layout is recommended.
$lang['optiontip'] = "Nota: Las opciones van en orden de ancho de banda. Uniformes usa el mínimo. Se recomienda la distribución a escala.";

//Force default thumbnails
$lang['forcedefthumb'] = "Forzar miniaturas predeterminadas";

//If yes, users may only use the default mode (recommended for servers with little bandwidth). If no, users may select any thumbnail mode they wish.
$lang['forcethumbdesc'] = "Si sí, sólo se podrá usar el modo predeterminado (se recomienda para servidores con poco ancho de banda). Si no, los usuarios podrán elegir el modo que les plazca.";

//Small thumbnail size
$lang['smallthumb'] = "Tamaño de las miniaturas pequeñas";

//Size of small (uniformity) thumbnails in pixels.  Small thumbnails are generated often.  Default 120.
$lang['smallthumbdesc'] = "Tamaño de las miniaturas pequeñas (uniformes) en píxeles. Éstas se generan frecuentemente. 120 por defecto.";

//Large thumbnail size
$lang['largethumb'] = "Tamaño de las miniaturas grandes";

//Size of large (layout) thumbnails.  Large thumbnails are made only occasionally for layout or scaled thumbnail modes.  Default 250.
$lang['largethumbdesc'] = "Tamaño de las miniaturas grandes (distribuidas). Éstas se hacen sólo ocasionalmente en miniaturas distribuidas y a escala. 250 por defecto.";

//Filesize for large thumbnail generation
$lang['thumbnailfilesize'] = "Tamaño de archivo para hacer miniaturas grandes";

//If a picture's filesize is greater than this value, a large thumbnail will be generated in addition to the small one.  Default is 100,000 bytes.  If using uniformity mode only, set to zero to disable and save server space.
$lang['thumbsizedesc'] = "Si el tamaño de una imagen es mayor que este valor, se generará una miniatura grande además de la pequeña. Por defecto son 100&nbsp;000 bytes. Si se usan sólo miniaturas uniformes, hágalo cero para deshabilitarlo y ahorrar espacio en el servidor.";

//Your E-mail Address (leave blank to use registration e-mail)
$lang['emaildesc'] = "Su dirección electrónica (déjelo en blanco para usar el correo de inscripción)";

//Submit Information for Install
$lang['finalinstal'] = "Enviar información para la instalación";

//---addusr

//You do not have the credentials to add users.
$lang['nocredeu'] = "No tiene las credenciales para añadir usuarios.";

//Note to admins:  Automatic approval is enabled, so users are expected to enable their own accounts.  Contact the board owner if you have questions about approving or rejecting members manually.
$lang['admnote'] = "Nota a los administradores: está activada la aprobación automática; se espera que los usuarios activen sus propias cuentas. Contacte al dueño del tablón si tiene preguntas sobre aprobar o rechazar miembros manualmente.";

//INVALID
$lang['word_invalid'] = "INVÁLIDO";

//--banlist

//You do not have the credentials to ban users.
$lang['credibandu'] = "No tiene las credenciales para vetar usuarios.";

//&ldquo;{1}&rdquo; is locked!  View Readme.txt for help.
// {1} = filename
$lang['fislockvred'] = "&laquo;{1}&raquo; está bloqueado. Para ayuda, vea Readme.txt.";

//Submit Changes
$lang['submitchange'] = "Enviar cambios";

//You do not have access as a registered member to use the chat.
$lang['memaccesschat'] = "No tiene acceso al chat como usuario inscrito.";

//The chat room has been disabled.
$lang['charommdisable'] = "La sala de chat se deshabilitó.";

//Sorry, an IFrame capable browser is required to participate in the chat room.
$lang['iframechat'] = "Hace falta un navegador que admita iframes para participar en el chat.";

//Invalid user name.
$lang['invuname'] = "Nombre de usuario inválido.";

//Invalid verification code.
$lang['invercode'] = "Código de verificación inválido.";

//Safety Save
$lang['safetysave'] = "Copia de seguridad";

//Return to the BBS
$lang['returnbbs'] = "Volver al BBS";

//Error looking for a recent picture.
$lang['err_lookrecpic'] = "Error buscando una imagen reciente.";

//NOTE:  Refresh may be required to see retouched image
$lang['refreshnote'] = "NOTA: Puede ser necesario recargar para ver la imagen retocada";

//Picture properties
$lang['picprop'] = "Propiedades de imagen";

//No, post picture now
$lang['safesaveopt1'] = "No, publicar imagen ya";

//Yes, save for later
$lang['safesaveopt2'] = "Sí, guardar para después";

//Bump picture
$lang['bumppic'] = "Hacer subir imagen";

//You may bump your edited picture to the first page.
$lang['bumppicexp'] = "Puede mover su imagen editada a la primera página.";

//Share picture
$lang['sharepic'] = "Compartir imagen";

//Password protect
$lang['pwdprotect'] = "Proteger con contraseña";

//Public (to all members)
$lang['picpublic'] = "Pública (para todos)";

//Submit
$lang['word_submit'] = "Enviar";

//Thanks for logging in!
$lang['common_login'] = "Gracias por conectarse.";

//You have sucessfully logged out.
$lang['common_logout'] = "Ha salido exitosamente del sistema.";

//Your login has been updated.
$lang['common_loginupd'] = "Su nombre de usuario se actualizó.";

//An error occured.  Please try again.
$lang['common_error'] = "Ocurrió un error. Intente de nuevo.";

//&lt;&lt;PREV
$lang['page_prev'] = '&lt;&lt;ANTERIOR';

//NEXT&gt;&gt;
$lang['page_next'] = 'SIGUIENTE&gt;&gt;';

//&middot;
// bullet.  Separator between <<PREV|NEXT>> and page numbers
$lang['page_middot'] = '&middot;';

//&hellip;
// "...", or range of omitted numbers
$lang['page_ellipsis'] = '&hellip;';

//You do not have the credentials to access the control panel.
$lang['noaccesscp'] = "No tiene las credenciales para acceder al panel de control.";

//Storage
$lang['word_storage'] = "Almacenamiento";

//300 or more recommended.  If reduced, excess pictures are deleted immediately.  Check disk space usage on the <a href=\"testinfo.php\">diagnostics page</a>.
$lang['cpmsg1'] = "Se recomiendan 300 o más. Si se reduce se borrarán inmediatamente las imágenes de más. Revise el uso de espacio en la <a href=\"testinfo.php\">página de diagnósticos</a>.";

//Use &ldquo;avatars/&rdquo; for master board, &ldquo;../board1/avatars/&rdquo; for all other boards.
$lang['cpmsg2'] = "Usar &laquo;avatars/&raquo; para el tablón principal, &laquo;../board1/avatars/&raquo; para el resto.";

//Image for canvas size preview on Draw screen.  Square picture recommended.  Default &ldquo;preview.png&rdquo;
$lang['cpmsg3'] = "Imagen de vista previa del tamaño del lienzo en la pantalla de dibujo. Se recomienda una imagen cuadrada. La predeterminada es &laquo;preview.png&raquo;.";

//Rebuild thumbnails
$lang['rebuthumb'] = "Reconstruir miniaturas";

//Page one
$lang['pgone'] = "Primera página";

//Archived pictures only
$lang['archipon'] = "Sólo imágenes archivadas";

//All thumbnails (very slow!)
$lang['allthumb'] = "Todas las miniaturas (muy lento)";

//If thumbnail settings are changed, these thumbnails will be rebuilt.
$lang['rebuthumbnote'] = "Si se cambian las opciones de miniaturas, dichas miniaturas se reconstruirán.";

//You do not have the credentials to delete comments
$lang['errdelecomm'] = "No tiene las credenciales para borrar comentarios.";

//Send reason to mailbox
$lang['sreasonmail'] = "Enviar razón al buzón";

//You do not have the credentials to edit the rules.
$lang['erreditrul'] = "No tiene las credenciales para editar las reglas.";

//Edit Rules
$lang['editrul'] = "Editar reglas";

//HTML and PHP are allowed.
$lang['htmlphpallow'] = "Se permiten HTML y PHP.";

//You do not have the credentials to delete pictures.
$lang['errdelpic'] = "No tiene las credenciales para eliminar imágenes.";

//You do not have the credentials to delete users.
$lang['errdelusr'] = "No tiene las credenciales para eliminar usuarios.";

//Pictures folder is locked!  View Readme.txt for help.
$lang['picfolocked'] = "El directorio de las imágenes está bloqueado. Para ayuda, vea Readme.txt.";

//Unfinished Pictures
$lang['icomppic'] = "Imágenes sin terminar";

//Click here to recover pictures
$lang['clickrecoverpic'] = "Haga clic aquí para recuperar imágenes.";

//Applet
$lang['word_applet'] = "Aplicación";

//, with palette
$lang['withpalet'] = ", con paleta";

//Canvas
$lang['word_canvas'] = "Lienzo";

//Min
$lang['word_min'] = "Mín";

//Max
$lang['word_max'] = "Máx";

//NOTE:  You must check &ldquo;animation&rdquo; to save your layers.
$lang['note_layers'] = "NOTA: Debe marcar &laquo;animación&raquo; para guardar las capas.";

//Avatars are disabled on this board.
$lang['avatardisable'] = "En este tablón están desactivados los avatares.";

//You must login to access this feature.
$lang['loginerr'] = "Debe conectarse para acceder a esta funcionalidad.";

//File did not upload properly.  Try again.
$lang['err_fileupl'] = "El archivo no se cargó correctamente. Intente de nuevo.";

//Picture is an unsupported filetype.
$lang['unsuppic'] = "La imagen es de un tipo incompatible.";

//Filesize is too large.  Max size is {1} bytes.
$lang['filetoolar'] = "El archivo es muy grande. El máximo es de {1} bytes.";

//Image size cannot be read.  File may be corrupt.
$lang['err_imagesize'] = "No se pudo leer el tamaño de la imagen. El archivo puede estar dañado.";

//Avatar upload
$lang['avatarupl'] = "Subir avatar";

//Avatar updated!
$lang['avatarupdate'] = "Avatar actualizado.";

//Your avatar may be a PNG, JPEG, or GIF.
$lang['avatarform'] = "Su avatar puede estar en formato PNG, JPEG, o GIF.";

//Avatars will only show on picture posts (not comments).
$lang['avatarshpi'] = "Los avatares sólo se mostrarán en las publicaciones de imágenes (no en los comentarios).";

//Change Avatar
$lang['chgavatar'] = "Cambiar avatar";

//Delete avatar
$lang['delavatar'] = "Eliminar avatar";

//Missing comment number.
$lang['err_comment'] = "Falta el número de comentario.";

//You cannot edit a comment that does not belong to you.
$lang['err_ecomment'] = "No puede editar un comentario que no le pertenezca.";

//You do not have the credentials to edit news.
$lang['err_editnew'] = "No tiene las credenciales para editar noticias.";

//The banner is optional and displays at the very top of the webpage.
$lang['bannermsg'] = "La pancarta es opcional y se muestra al principio de la página web.";

//The notice is optional and displays just above the page numbers on <em>every</em> page.
$lang['noticemsg'] = "La notificación es opcional y se ve justo sobre los números de página en <em>todas</em> las páginas.";

//Erase
$lang['word_erase'] = "Borrar";

//Centered Box
$lang['centrebox'] = "Caja centrada";

//Scroll Box
$lang['scrollbox'] = "Caja de desplazamiento";

//Quick Draw
$lang['quickdraw'] = "Dibujo rápido";

//You cannot edit a picture that does not belong to you.
$lang['err_editpic'] = "No puede editar una imagen que no le pertenezca.";

//Type &ldquo;public&rdquo; to share with everyone
$lang['editpicmsg'] = "Escriba &laquo;public&raquo; para compartirla con todo el mundo.";

//You cannot use the profile editor.
$lang['err_edprof'] = "No puede usar el editor de perfiles.";

//Real Name (Optional)
$lang['realnameopt'] = "Nombre real (opcional)";

//This is not your username.  This is your real name and will only show up in your profile.
$lang['realname'] = "Este no es su nombre de usuario, es su nombre real y sólo aparecerá en su perfil.";

//Birthday
$lang['word_birthday'] = "Fecha de nacimiento";

//M
// Month
$lang['abbr_month'] = "M";

//D
// Day
$lang['abbr_day'] = "D";

//Y
// Year
$lang['abbr_year'] = "A";

//January
$lang['month_jan'] = "enero";

//February
$lang['month_feb'] = "febrero";

//March
$lang['month_mar'] = "marzo";

//April
$lang['month_apr'] = "abril";

//May
$lang['month_may'] = "mayo";

//June
$lang['month_jun'] = "junio";

//July
$lang['month_jul'] = "julio";

//August
$lang['month_aug'] = "agosto";

//September
$lang['month_sep'] = "septiembre";

//October
$lang['month_oct'] = "octubre";

//November
$lang['month_nov'] = "noviembre";

//December
$lang['month_dec'] = "diciembre";

//Year is required for birthday to be saved.  Day and month are optional.
$lang['bdaysavmg'] = "Se necesita el año para guardar la fecha de nacimiento. El día y el mes son opcionales.";

//Website
$lang['word_website'] = "Sitio web";

//Website title
$lang['websitetitle'] = "Título del sitio";

//You can also type a message here and leave the URL blank
$lang['editprofmsg2'] = "Puede también escribir aquí un mensaje y dejar el URL en blanco";

//Avatar
$lang['word_avatar'] = "Avatar";

//Current Avatar
$lang['curavatar'] = "Avatar actual";

//Online Presence
$lang['onlineprese'] = "Presencia en línea";

//(Automatic)
// context = Used as label in drop-down menu
$lang['picview_automatic'] = "(Automático)";

//Automatic is the default format and will layout comments to wrap around the picture. Horizontal is good for very high-res screens and displays comments to the right of the picture.  Vertical is recommended for very small, low-res screens.
$lang['msg_automatic'] = "Automático es el formato predeterminado, y organizará los comentarios para que rodeen la imagen. Horizontal es adecuado para pantallas de muy alta resolución y muestra los comentarios a la derecha de la imagen. Vertical se recomienda para pantallas pequeñas y de baja resolución.";

//Thumbnail mode
$lang['thumbmode'] = "Modo de miniaturas";

//Default
$lang['word_default'] = "Predeterminado";

//Scaled
$lang['word_scaled'] = "A Escala";

//Default is recommended.  Layout will disable most thumbnails.  Scaled is like layout but will shrink big pictures.  Uniformity will make all thumbnails the same size.
$lang['msgdefrec'] = "Se recomienda el predeterminado. Distribuidas desactivará casi todas las miniaturas. A Escala son como las Distribuidas pero encogiendo las imágenes grandes. Uniformes hará todas las miniaturas del mismo tamaño.";

//(Cannot be changed on this board)
$lang['msg_cantchange'] = "(No se puede cambiar en este tablón)";

//Screen size
$lang['screensize'] = "Tamaño de la pantalla";

//{1} or higher
// {1} = screen resolution ("1280&times;1024")
$lang['orhigher'] = "{1} o más";

//Your screensize, which helps determine the best layout.  Default is 800 &times; 600.
$lang['screensizemsg'] = "El tamaño de su pantalla, que ayudará a determinar la mejor distribución. El predeterminado es 800 &times; 600.";

// No image data was received by the server.\nPlease try again, or take a screenshot of your picture.
$lang['err_nodata'] = "El servidor no recibió datos de imagen.\nTome un pantallazo de su imagen (por si acaso) e intente reenviarla.";

//Login could not be verified!  Take a screenshot of your picture.
$lang['err_loginvs'] = "No se pudo verificar su identidad. Tome un pantallazo de su imagen.";

//Unable to allocate a new picture slot!\nTake a screenshot of your picture and tell the admin.
$lang['err_picts'] = "No se pudo asignar un nuevo espacio de imagen.\nTome un pantallazo de la imagen y dígale al administrador.";

//Unable to save image.\nPlease try again, or take a screenshot of your picture.
$lang['err_saveimg'] = "No se pudo guardar la imagen.\nPor favor intente de nuevo, o tome un pantallazo de su imagen.";

//Rules
$lang['word_rules'] = "Reglas";

//Public Images
$lang['publicimg'] = "Imágenes públicas";

//Drawings by Comment
$lang['drawbycomm'] = "Dibujos por comentario";

//Animations by Comment
$lang['animbycomm'] = "Animaciones por comentario";

//Archives by Commen
$lang['archbycomm'] = "Archivos por comentario";

//Go
// context = Used as button
$lang['word_go'] = "Ir";

//My Oekaki
$lang['myoekaki'] = "Mi oekaki";

//Reset Password
$lang['respwd'] = "Restablecer contraseña";

//Unlock
$lang['word_unlock'] = "Desbloquear";

//Lock
$lang['word_lock'] = "Bloquear";

//Bump
$lang['word_bump'] = "Hacer subir";

//WIP
$lang['word_WIP'] = "Inacabado";

//TP
// context = "[T]humbnail [P]NG"
$lang['abrc_tp'] = "TP";

//TJ
// context = "[T]humbnail [J]PEG"
$lang['abrc_tj'] = "TJ";

//Thumb
$lang['word_thumb'] = "Miniatura";

//Pic #{1}
$lang['picnumber'] = 'Imagen número {1}';

//Pic #{1} (click to view)
$lang['clicktoview'] = "Imagen número {1}, clic para ver";

//(Click to enlarge)
$lang['clickenlarg'] = "(clic para agrandar)";

//Adult
$lang['word_adult'] = "Para adultos";

//Public
$lang['word_public'] = "Público";

//Thread Locked
$lang['tlocked'] = "Hilo cerrado";

//The mailbox has been disabled.
$lang['mailerrmsg1'] = "El buzón ha sido desactivado.";

//You cannot access the mailbox.
$lang['mailerrmsg2'] = "No puede acceder al buzón.";

//You need to login to access the mailbox.
$lang['mailerrmsg3'] = "Necesita conectarse para acceder al buzón.";

// You cannot access messages in the mailbox that do not belong to you.
$lang['mailerrmsg4'] = "No puede acceder a mensajes del buzón que no le pertenezcan.";

//You cannot access the mass send.
$lang['mailerrmsg5'] = "No puede acceder al envío masivo.";

//Reverse Selection
$lang['revselect'] = "Invertir selección";

//Delete Selected
$lang['delselect'] = "Eliminar seleccionados";

//(Yourself)
// context = Placeholder in table list
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['word_yourself'] = "(Usted)";

//Original Message
$lang['orgmessage'] = "Mensaje original";

//Send
$lang['word_send'] = "Enviar";

//You can use this to send a global notice to a group of members via the OPmailbox.
$lang['massmailmsg1'] = "Puede usar esto para enviarle una notificación global a un grupo de miembros por medio del buzón OP.";

//Be careful when sending a mass mail to &ldquo;Everyone&rdquo; as this will result in LOTS of messages in your outbox.  Use this only if you really have to!
$lang['massmailmsg2'] = "Tenga cuidado al enviarle un correo masivo a &laquo;Todos&raquo; pues esto generará un MONTÓN de mensajes en su bandeja de salida. Úselo sólo cuando sea necesario.";

//Everyone
$lang['word_everyone'] = "Todos";

//Administrators
$lang['word_administrators'] = "Administradores";

//Pictures
$lang['word_pictures'] = "Imágenes";

//Sort by
$lang['sortby'] = "Ordenar por";

//Order
$lang['word_order'] = "Orden";

//Per page
$lang['perpage'] = "Por página";

//Keywords
$lang['word_keywords'] = "Palabras clave";

//(please login)
// context = Placeholder. Substitute for an e-mail address
$lang['plzlogin'] = "(por favor inicie sesión)";

//Pending
$lang['word_pending'] = "Pendiente";

//You do not have the credentials to access flag modification.
$lang['err_modflags'] = "No tiene las credenciales para modificar las marcas.";

//Warning:  be careful not to downgrade your own rank!
$lang['warn_modflags'] = "¡Cuidado! No se baje usted mismo de categoría.";

//Admin rank
$lang['adminrnk'] = "Rango de administrador";

//current
$lang['word_current'] = "actual";

//You do not have the credentials to access the password reset.
$lang['retpwderr'] = "No tiene las credenciales para acceder al restablecimiento de contraseñas.";

//Only use this feature if a member cannot change their password in the profile editor, and they cannot use the password recovery feature because their recorded e-mail is not working.
$lang['newpassmsg1'] = "Use esta funcionalidad solamente si un miembro no puede cambiar su contraseña en el editor de perfil ni recuperar la contraseña porque su dirección electrónica no funciona.";

//Valid password in database
$lang['validpwdb'] = "Contraseña válida en la base de datos";

//You do not have draw access. Login to draw, or ask an administrator for details about receiving access.
$lang['err_drawaccess'] = "No tiene acceso a dibujar. Inicie sesión para dibujar, o pídale a un administrador detalles para recibir acceso.";

//Public retouch disabled.
$lang['pubretouchdis'] = "Retoque público desactivado.";

//Incorrect password to retouch!
$lang['errrtpwd'] = "Contraseña para retocar incorrecta.";

//You have too many unfinished pictures!  Use Recover Pics menu to finish one.
$lang['munfinishpic'] = "Tiene muchas imágenes sin terminar. Use el menú de recuperar imágenes para terminar alguna.";

//You have an unfinished picture!  Use Recover Pics menu to finish it.
$lang['aunfinishpic'] = "Tiene una imagen sin terminar. Use el menú de recuperar imágenes para terminarla.";

//Resize PaintBBS to fit window
$lang['resizeapplet'] = "Redimensionar aplicación a la ventana";

//Hadairo
$lang['pallette1'] = "Piel";

//Red
$lang['pallette2'] = "Rojo";

//Yellow
$lang['pallette3'] = "Amarillo";

//Green
$lang['pallette4'] = "Verde";

//Blue
$lang['pallette5'] = "Azul";

//Purple
$lang['pallette6'] = "Morado";

//Brown
$lang['pallette7'] = "Café";

//Character
$lang['pallette8'] = "Personaje";

//Pastel
$lang['pallette9'] = "Pastel";

//Sougen
$lang['pallette10'] = "Pradera";

//Moe
$lang['pallette11'] = "Moe"; //sin traducir

//Grayscale
$lang['pallette12'] = "Grises";

//Main
$lang['pallette13'] = "Principal";

//Wac!
$lang['pallette14'] = "Wac!";

//Save Palette
$lang['savpallette'] = "Guardar paleta";

//Save Color Changes
$lang['savcolorcng'] = "Guardar cambios de color";

//Palette
$lang['word_Palette'] = "Paleta";

//Brighten
$lang['word_Brighten'] = "Iluminar";

//Darken
$lang['word_Darken'] = "Oscurecer";

//Invert
$lang['word_invert'] = "Invertir";

//Replace all palettes
$lang['paletteopt1'] = "Reemplazar todas las paletas";

//Replace active palette
$lang['paletteopt2'] = "Reemplazar paleta activa";

//Append palette
$lang['apppalette'] = "Agregar paleta";

//Set As Default Palette
$lang['setdefpalette'] = "Volver paleta predeterminada";

//Palette Manipulate/Create
$lang['palletemini'] = "Manipular o crear paleta";

//Gradation
$lang['word_Gradation'] = "Gradación";

//Applet Controls
$lang['appcontrol'] = "Controles de aplicación";

//Please note:
// context = help tips on for applets
$lang['plznote'] = "Nótese:";

//Any canvas size change will destroy your current picture!
$lang['canvchgdest'] = "¡Cualquier cambio al tamaño del lienzo destruirá su imagen actual!";

//You cannot resize your canvas when retouching an older picture.
$lang['noresizeretou'] = "No puede redimensionar el lienzo al retocar una imagen vieja.";

//You may need to refresh the window to start retouching.
$lang['refreshbret'] = "Puede necesitar recargar la ventana para empezar a retocar.";

//Click the &ldquo;Float&rdquo; button in the upper left corner to go fullscreen.
$lang['float'] = "Haga clic en el botón &laquo;Float&raquo; de la esquina superior izquierda para ir a pantalla completa.";

//X (width)
$lang['canvasx'] = "X";

//Y (height)
$lang['canvasy'] = "Y";

//Modify
$lang['word_modify'] = "Modificar";

//Java Information
$lang['javaimfo'] = "información de Java";

//If you get an &ldquo;Image Transfer Error&rdquo;, you will have to use Microsoft VM instead of Sun Java.
$lang['oekakihlp1'] = "Si recibe un &laquo;error de transferencia de imagen&raquo;, tendrá que usar la MV de Microsoft en lugar de Sun Java.";

//If retouching an animated picture and the canvas is blank, play the animation first.
$lang['oekakihlp2'] = "Si está retocando una imagen animada y el lienzo está vacío, reproduzca la animación primero.";

//Recent IP / host
$lang['reciphost'] = "IP y servidor recientes";

//Send mailbox message
$lang['sendmailbox'] = "Enviar mensaje al buzón";

//Browse all posts ({1} total)
$lang['browseallpost'] = "Ver todas las publicaciones ({1} en total)";

//(Broken image)
// context = Placholder for missing image
$lang['brokenimage'] = "(Imagen dañada)";

//(No animation)
// context = Placholder for missing animation
$lang['noanim'] = "(Sin animación)";

//{1} seconds
$lang['recover_sec'] = "{1} seg";

//{1} {1?minute:minutes}
$lang['recover_min'] = "{1} min";

//Post now
$lang['postnow'] = "Publicar ahora";

//Please read the rules before submitting a registration.
$lang['plzreadrulereg'] = "Lea las reglas antes de enviar la inscripción.";

//If you agree to these rules
$lang['agreerulz'] = "Si acepta estas reglas";

//click here to register
$lang['clickheregister'] = "haga clic aquí para inscribirse";

//Registration Submitted
$lang['regisubmit'] = "Inscripción enviada";

//Your registration for &ldquo;{1}&rdquo; is being processed.
// {1} = Oekaki title
$lang['urgistra'] = "Su inscripción a &laquo;{1}&raquo; se está procesando.";

//Your registration for &ldquo;{1}&rdquo; has been approved!<br /><br />You may now configure your membership profile.<br /><br /><a href=\"editprofile.php\">Click here to edit your profile.</a>
// {1} = Oekaki title
$lang['urgistra_approved'] = "Su inscripción a &laquo;{1}&raquo; se ha aprobado.<br /><br />Puede configurar su perfil de miembro.<br /><br /><a href=\"editprofile.php\">Haga clic aquí para editar su perfil.</a>";

//Before you may login, an administrator must approve your registration.  You should receive an e-mail shortly to let you know if your account has been approved.<br /><br />Once approved, you may update your member profile via the &ldquo;My Oekaki&rdquo; menu.
$lang['aprovemsgyes'] = "Antes de poder iniciar sesión, un administrador debe aprobar su inscripción. En breve recibirá un correo electrónico indicándole si su cienta fue aprobada.<br /><br />Una vez aprobada, podrá actualizar su perfil de miembro con el menú &laquo;Mi oekaki&raquo;.";

//Please check your e-mail soon for the link to activate your account.<br /><br />Once your e-mail has been verified, you will be automatically logged in as a new member, and will be able to add information to your profile.
$lang['aprovemsgno'] = "Busque pronto en su correo electrónico el enlace para activar su cuenta.<br /><br />Una vez se haya verificado su dirección, se le iniciará automáticamente sesión como un nuevo miembro, y podrá añadir información a su perfil.";

//Notes About Registering
$lang['nbregister'] = "Notas sobre la inscripción";

//DO NOT REGISTER TWICE
$lang['registertwice'] = "NO SE INSCRIBA DOS VECES.";

//You can check if you're in the pending list by viewing the member list and searching for your username.
$lang['regmsg1'] = "Puede revisar si está en la lista de pendientes buscando su nombre en la lista de miembros.";

//Use only alphanumeric characters for names and passwords.  Do not use quotes or apostrophes.  Passwords are case-sensitive.
$lang['regmsg2'] = "Use sólo caracteres alfanuméricos para nombres y contraseñas. No use comillas ni apóstrofos. Las mayúsculas se diferencian.";

//You may change anything in your profile except your name once your registration is accepted.
$lang['regmsg3'] = "Puede cambiar todo en su perfil excepto el nombre cuando se acepte su inscripción.";

//You must wait for an administrator to approve your registration on this board.  Your registration approval may take awhile if no one at the moment has time to maintain the pending list.  Please be patient; you will receive an e-mail notifying you of your approval.
$lang['regmsg4'] = "Debe esperar a que un administrador apruebe su inscripción a este tablón. La aprobación puede tomar un rato si no hay nadie en el momento que revise la lista de pendientes. Sea paciente; recibirá un correo notificándole de la aprobación.";

//If you don't receive an e-mail with a verification code, or if you cannot activate your account via e-mail, contact an administrator for help.  Administrators may manually approve your account in these cases.
$lang['regmsg5'] = "Si no recibe un correo con un código de verificación, o no puede activar su cuenta por correo electrónico, pídale ayuda a un administrador; ellos pueden aprobar manualmente su cuenta en esos casos.";

//Your password can be mailed to you if you forget it.  <strong>Your e-mail will only be visible to other registered members.</strong>  You can remove or edit your e-mail after registration.  Ask the board owner about other potential privacy concerns.
$lang['regmsg6'] = "Se le puede enviar por correo su contraseña si la olvida. <strong>Su correo electrónico sólo será visible a otros miembros inscritos.</strong> Puede quitarlo o modificarlo después de inscribirse. Pregúntele al dueño del tablón si tiene otras dudas sobre la privacidad.";

//{1}+ Age Statement
// {1} = minimum age. Implies {1} age or older
$lang['agestatement'] = "Declaración de edad de {1} o más";

//<strong>This oekaki is for adults only.</strong>  You are required to declare your birth year to register.  Year is required, month and day are optional and may be left blank.
$lang['adultonlymsg'] = "<strong>Este oekaki es sólo para adultos.</strong> Necesita dar su año de nacimiento para inscribirse. El mes y el día son opcionales y se pueden dejar en blanco.";

//A link to your webpage, or a direct link to a sample of your artwork.  Not required for registration on this board.
$lang['nbwebpage'] = "Un enlace a su página web, o a una muestra de su trabajo. No se necesita para inscribirse aquí.";

//Submit Registration
$lang['subregist'] = "Enviar inscripción";

//Could not fetch information about picture
$lang['coulntfetipic'] = "No se pudo buscar información sobre la imagen";

//No edit number specified
$lang['noeditno'] = "Número de edición no especificado";

//This picture is available to all board members.
$lang['picavailab'] = "Esta imagen está disponible a todos los miembros del tablón.";

//The edited version of this image will be posted as a new picture.
$lang['retouchmsg2'] = "La versión editada de esta imagen se publicará como una imagen nueva.";

//The original artist will be credited automatically.
$lang['retouchmsg3'] = "Se le reconocerá autoría al artista original automáticamente.";

//A password is required to retouch this picture.
$lang['retouchmsg4'] = "Se necesita una contraseña para retocar esta imagen.";

//The retouched picture will overwrite the original
$lang['retouchmsg5'] = "La imagen retocada reemplazará la original";

//Continue
$lang['word_continue'] = "Continuar";



/* sqltest.php */

//SQL direct call
// context = Can use the SQL tool
$lang['st_sql_header'] = 'Llamada directa a SQL';

//Original:
$lang['st_orig_query'] = 'Original:';

//Evaluated:
// context = "Processed" or "Computed"
$lang['st_eval_query'] = 'Evaluada:';

//Query okay.
$lang['st_query_ok'] = 'Consulta realizada.';

//{1} {1?row:rows} affected.
$lang['st_rows_aff'] = '{1} {1?fila afectada:filas afectadas}.';

//First result: &ldquo;{1}&rdquo;
$lang['st_first_res'] = 'Primer resultado: &laquo;{1}&raquo;';

//Query failed!
$lang['st_query_fail'] = 'La consulta falló.';

//Database type:
// context = Which brand of database (MySQL, PostgreSQL, etc.)
$lang['st_db_type'] = 'Tipo de base de datos:';

//&nbsp;USE THIS TOOL WITH EXTREME CAUTION!  Detailed SQL knowledge required!&nbsp;
// context = This is a BIG warning with a large font.
$lang['st_big_warn'] = '&nbsp;¡USE ESTA HERRAMIENTA CON MUCHO CUIDADO! Se necesita conocimiento detallado de SQL.&nbsp;';

//Type a raw SQL query with no ending semicolon.  PHP strings will be evaluated.  Confirmation will be requested.
$lang['st_directions'] = 'Escriba una consulta SQL en bruto sin punto y coma al final. Las cadenas PHP se evaluarán. Se pedirá confirmación.';

//Version
$lang['st_ver_btn'] = 'Versión';

/* END sqltest.php */



/* testinfo.php */

//Diagnostics page available only to owner.
$lang['testvar1'] = 'La página de diagnósticos sólo está disponible para el dueño.';

//<strong>Folder empty</strong>
$lang['d_folder_empty'] = '<strong>Directorio vacío</strong>';

//DB info
$lang['dbinfo'] = 'información de base de datos';

// Database version:
$lang['d_db_version'] = 'Versión de base de datos:';

//Total pictures:
$lang['d_total_pics'] = 'Total de imágenes:';

//{1} (out of {2})
// {1} = existing pictures, {2} = maximum
$lang['d_pics_vs_max'] = '{1} (de {2})';

//Archives:
$lang['d_archives'] = 'Archivadas:';

//WIPs and recovery:
$lang['d_wip_recov'] = 'Inacabadas y recuperables:';

//Current picture number:
$lang['d_cur_picno'] = 'Número de imagen actual:';

//<strong>Cannot read folder</strong>
$lang['d_no_read_dir'] = '<strong>No se puede leer el directorio</strong>';

//SQL direct calls:
// context = Can use the SQL tool
$lang['d_sql_direct'] = 'Llamadas directas a SQL:';

//Available <a href="{1}">(click here)</a>
$lang['d_sql_avail'] = 'Disponibles <a href="{1}">(clic aquí)</a>';

//Config
$lang['d_word_config'] = 'Configuración';

//PHP Information:
$lang['d_php_info'] = 'Información de PHP:';

//{1} <a href="{2}">(click for more details)</a>
// {1} = PHP version number
$lang['d_php_ver_num'] = '{1} <a href="{2}">(clic para más detalles)</a>';

//Config version:
$lang['configver'] = 'Versión de la configuración:';

//Contact:
$lang['word_contact'] = 'Contacto:';

//Path to OP:
$lang['pathtoop'] = 'Ruta a OP:';

//Cookie path:
$lang['cookiepath'] = 'Ruta a las cookies:';

//Cookie domain:
// context = domain: tech term used for web addresses
$lang['cookie_domain'] = 'Dominio de las cookies:';

//Cookie life:
// context = how long a browser cookie lasts
$lang['cookielife'] = 'Vida de las cookies:';

//(empty)
// context = placeholder if no path/domain set for cookie
$lang['cookie_empty'] = '(vacío)';

//{1} seconds (approximately {2} {2?day:days})
$lang['seconds_approx_days'] = '{1} segundos (cerca de {2} {2?día:días})';

//Public images: // 'publicimg'
$lang['d_pub_images'] = 'Imágenes públicas:';

//Safety saves:
$lang['safetysaves'] = 'Copias de seguridad:';

//Yes ({1} days)
// {1} always > 1
$lang['d_yes_days'] = 'Sí ({1} días)';

//No ({1} days)
$lang['d_no_days'] = 'No ({1} días)';

//Pictures folder
$lang['d_pics_folder'] = 'Directorio de imágenes';

//Notice:
$lang['d_notice'] = 'Notificación:';

//Folder:
$lang['d_folder'] = 'Directorio:';

//Total files:
$lang['d_total_files'] = 'Archivos en total:';

//Total space used:
$lang['d_space_used'] = 'Espacio usado en total:';

//Average file size:
$lang['d_avg_filesize'] = 'Tamaño de archivo en promedio:';

//Images:
$lang['d_images_label'] = 'Imágenes:';

//{1} ({2}%)
// {1} = images, {2} = percentage of folder
$lang['d_img_and_percent'] = '{1} ({2}%)';

//Animations:
$lang['d_anim_label'] = 'Animaciones:';

//{1} ({2}%)
// {1} = animations, {2} = percentage of folder
$lang['d_anim_and_percent'] = '{1} ({2}%)';

//Other filetypes:
$lang['d_other_types'] = 'Otros tipos de archivo:';

//Locks
$lang['word_locks'] = 'Bloqueos';

// Okay
// context = file is "writable" or "good".
$lang['word_okay'] = 'Bien';

//<strong>Locked</strong>
// context = "Unwritable" or "Unavailable" rather than broken or secure
$lang['word_locked'] = '<strong>Bloqueado</strong>';

//<strong>Missing</strong>
$lang['word_missing'] = '<strong>Faltante</strong>';

/* END testinfo.php */



//You do not have the credentials to upload pictures.
$lang['err_upload'] = "No tiene las credenciales para subir imágenes.";

//Picture to upload
$lang['pictoupload'] = "Imagen a subir";

//Valid filetypes are PNG, JPEG, and GIF.
$lang['upldvalidtyp'] = "Se aceptan los formatos PNG, JPEG, y GIF.";

//Animation to upload
$lang['animatoupd'] = "Animación a subir";

//Matching picture and applet type required.
$lang['uploadmsg1'] = "Se necesita que coincidan los tipos de aplicación e imagen.";

//Valid filetypes are PCH, SPCH, and OEB.
$lang['uploadmsg2'] = "Se aceptan los formatos PCH, SPCH, CHI, y OEB.";

//Valid filetypes are PCH and SPCH.
$lang['uploadmsg3'] = "Se aceptan los formatos PCH, SPCH, y CHI.";

//Applet type
$lang['appletype'] = "Tipo de aplicación";

//Time invested (in minutes)
$lang['timeinvest'] = "Tiempo invertido (en minutos)";

//Use &ldquo;0&rdquo; or leave blank if unknown
$lang['uploadmsg4'] = "Use &laquo;0&raquo; o déjelo vacío si no sabe";

//Download
$lang['word_download'] = "Descargar";

//This window refreshes every {1} seconds.
$lang['onlinelistmsg'] = "Esta ventana se recarga cada {1} segundos.";

//Go to page
$lang['gotopg'] = "Ir a página";

//Netiquette applies.  Ask the admin if you have any questions.
// context = Default rules
$lang['defrulz'] = "Se aplica la <em>netiqueta</em>. Pregúntele al administrador si tiene dudas.";

//Send reason
$lang['sendreason'] = "Enviar razón";

//&ldquo;avatar&rdquo; field does not exist in database.
$lang['err_favatar'] = "No existe el campo &laquo;avatar&raquo; en la base de datos.";

//Get
$lang['pallette_get'] = " Tomar ";

//Set
$lang['pallette_set'] = " Establecer ";

// Diagnostics
$lang['header_diag'] = 'Diagnósticos';

// Humanity test for guest posts?
$lang['cpanel_humanity_infoask'] = "¿Prueba de humanidad para publicaciones de invitados?";

// If yes, guests are required to pass a humanity test before posting comments.  The test must be passed only once.
$lang['cpanel_humanity_sub'] = "Si sí, los invitados deberán pasar una prueba de humanidad antes de publicar comentarios; ésta sólo se realizará una vez.";

// And now, for the humanity test.
$lang['humanity_notify_sub'] = "Y ahora, la prueba de humanidad.";

// If canvas is blank or broken, <a href=\"{1}\">click here to import canvas, not animation</a>.
$lang['shi_canvas_only'] = "Si el lienzo está vacío o dañado, <a href=\"{1}\">clic aquí para importar el lienzo, no la animación</a>.";

//For help with installation, read the &ldquo;readme.html&rdquo; file that came with your Wacintaki distribution.  Make sure you have CHMOD all files appropriately before continuing with installation.  For technical assistance, please visit the <a href="http://www.NineChime.com/forum/">NineChime Software Forum</a>.
$lang['assist_install'] = "Para ayuda con la instalación, lea el archivo &laquo;readme.html&raquo; incluido en su distribución de Wacintaki. Asegúrese de hacer CHMOD correctamente sobre todos los archivos antes de seguir con la instalación. Para asistencia técnica visite el <a href=\"http://www.NineChime.com/forum/\">foro de NineChime Software</a>.";

//The installer only sets mandatory information.  Once the board has been installed, use the Control Panel to fully configure the board.
$lang['assist_install2'] = "El instalador sólo establece la información obligatoria. Después de instalarlo use el panel de control para configurar del todo el tablón.";

//<strong>None</strong> will disable thumbnails, and uses a lot of bandwidth.  <strong>Layout</strong> will keep most pictures their original dimensions, and usually uses a vertical layout for wide pictures to keep comments readable.  <strong>Scaled</strong> will use thumbnails for wide pictures, and favor horizontal layout.  <strong>Uniformity</strong> makes all the pictures the same size with a small thumbnail.
$lang['thumbmodesexp'] = "<strong>Ninguna</strong> desactivará las miniaturas, y consume mucho ancho de banda. <strong>Distribuidas</strong> dejará la mayoría de las imágenes de su tamaño original, y distribuirá verticalmente las más anchas para que se puedan leer los comentarios. <strong>A Escala</strong> usará miniaturas para las figuras grandes, y favorecerá una distribución horizontal. <strong>Uniformes</strong> hará todas las imágenes del mismo tamaño con una miniatura pequeña.";

//Resize to this:
$lang['resize_to_this'] = 'Redimensionar a:';

//Show e-mail to members
$lang['email_show'] = 'Mostrar correo a los miembros';

//Show smilies
$lang['smilies_show'] = 'Mostrar emotíconos';

//Host lookup disabled in &ldquo;hacks.php&rdquo; file.
$lang['hosts_disabled'] = 'Búsqueda de servidor desactivada en el archivo &laquo;hacks.php&raquo;.';

//Reminder
$lang['word_reminder'] = 'Recordatorio';

//Anti-spam
$lang['anti_spam'] = 'Antispam';

//(Delete without sending e-mail)
$lang['anti_spam_delete'] = '(eliminar sin enviar mensaje)';

//Log
$lang['word_log'] = 'Registro';

//You must be an administrator to access the log.
$lang['no_log_access'] = 'Debe ser un administrador para acceder al registro.';

//{1} entries
$lang['log_entries'] = '{1} entradas';

//Category
$lang['word_category'] = 'Categoría';

//Peer (affected)
$lang['log_peer'] = 'Objetivo';

//(Self)
// {1} = Gender. Singular=Male/Unknown, Plural=Female
$lang['log_self'] = "Sí {1?mismo:misma}";

//Required.  Guests cannot see e-mails, and it may be hidden from other members.
$lang['msg_regmail'] = "Necesario. Los invitados no podrán ver la dirección de correo electrónico y ésta se puede esconder de los demás miembros.";

//Normal Chibi Paint
$lang['online_npaintbbs'] = 'Chibi Paint normal';

//No Animation
$lang['draw_no_anim'] = '(sólo capas, sin animación)';

//Purge All Registrations
$lang['purge_all_regs'] = "borrar todas las inscripciones";

//Are you sure you want to delete all {1} registrations?
$lang['sure_purge_regs'] = "¿Seguro de que quiere borrar {1} inscripciones?";

//Animations/Layers
$lang['draw_anims_layers'] = 'Animaciones o capas';

//Most applets combine layers with animation data.
$lang['draw_combine_layers'] = 'La mayoría de las aplicaciones combinan las capas con datos de animación.';

//Helps prevent accidental loss of pictures.
$lang['draw_help_loss_pics'] = 'Ayuda a evitar la pérdida accidental de imágenes.';

//Window close confirmation
$lang['draw_close_confirm'] = 'Confirmación de cierre de ventana';

//Remember draw settings
$lang['draw_remember_settings'] = 'Recordar opciones de dibujo';

//Any reduction to the number of pictures stored requires confirmation via the checkbox.
$lang['pic_store_change_confirm'] = 'Cualquier reducción al número de imágenes guardadas necesita confirmación marcando la caja.';

//Check this box to confirm any changes
$lang['cpanel_check_box_confirm'] = 'Marque esta caja para confirmar los cambios';

//Use Lytebox viewer
$lang['cpanel_use_lightbox'] = 'Usar el visor Lytebox';

//Enables support for the zooming Lytebox/Lightbox viewer for picture posts.
$lang['cpanel_lightbox_sub'] = 'Activa el visor de aumento Lytebox/Lightbox para publicaciones de imagen.';

//An Administrator has all the basic moderation functions, including editing the notice and banner, banning, changing member flags, and recovering pictures.
$lang['type_aabil'] = 'Un administrador tiene todos los poderes básicos de moderación, como editar la notificación y pancarta, vetar, cambiar marcas de usuarios, y recuperar imágenes.';

//Moderator
$lang['word_moderator'] = 'Moderador';

//Moderators have the ability to edit and delete comments, upload, as well as edit post properties (lock, bump, adult, WIP).
$lang['type_mabil'] = 'Los moderadores tienen el poder de editar y borrar comentarios, subir, y editar propiedades de las publicaciones (bloquear, hacer subir, marcar como para adultos o incabada).';

//Use Lightbox pop-up viewer
$lang['profile_use_lightbox'] = 'Usar visor en ventana emergente';

//Enables pop-up 
$lang['profile_lightbox_sub'] = 'Activa el visor Lightbox/Lytebox en una ventana emergente en lugar de una nueva pestaña o ventana del navegador.';

//{1} {1?day:days} remaining
$lang['recovery_days_remaining'] = "{1} {1?día restante:días restantes}.";

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_notice'] = 'Tiene {1} {1?imagen:imágenes} sin terminar.';

//You have {1} unfinished {1?picture:pictures}.  Please finish {1?it:at least one} before starting a new picture.
$lang['unfinished_warning'] = 'Tiene {1} {1?imagen:imágenes} sin terminar. Por favor {1?termínela:termine al menos una} antes de comenzar una nueva imagen.';

//(Default only)
// context = Placeholder if only one template available
$lang['default_only'] = '(sólo predeterminado)';

//Private Oekaki
$lang['header_private_oekaki'] = 'Oekaki privado';

//You are not logged in.  Please log in or register to view this oekaki.
$lang['private_please_login'] = 'No está conectado. Por favor conéctese o inscríbase para ver este oekaki.';

//Requires members to register or log in to view art and comments.  Guests are completelly blocked.
$lang['private_oekaki_sub'] = 'Requiere que los miembros se inscriban o conecten para ver arte y comentarios. Los invitados no se permiten.';

//Server is able to send e-mail?
$lang['able_send_email'] = '¿El servidor puede mandar correo electrónico?';

//Safety Save Limit
$lang['header_safety_max'] = 'Límite de copias de seguridad';

//Maximum number of unfinished pictures a member may have at a time.  Default is {1}.
$lang['safety_max_sub'] = 'Número máximo de imágenes sin terminar que puede tener al mismo tiempo un miembro. {1} por defecto.';

//You need Java&trade; to use the paint program.  <a href="{1}">Click here to download Java</a>.
$lang['paint_need_java_link'] = 'Necesita Java&trade; para usar el programa de dibujo. <a href="{1}">Clic aquí para descargar Java</a>.';

//Don't know / I Don't know
// context = drop-down menu option
$lang['option_dont_know'] = "No sé";

//{1} folder is locked!  Folder must be CHMOD to 755 (preferred) or 777.  View Readme.txt for help.
// NOTE: no HTML.  {1} = folder, {2} = manual
$lang['boot_folder_locked'] = 'El directorio {1} está bloqueado. Debe darle permisos 755 (preferiblemente) o 777. Para ayuda, véase {2}.';

//Installer removed.
$lang['boot_inst_rm'] = 'Instalador quitado.';

//Installer removal failed!
$lang['boot_inst_rm_fail'] = 'No se pudo quitar el instalador';

//Updater removed.
$lang['boot_update_rm'] = 'Actualizador quitado.';

//Updater removal failed!
$lang['boot_update_rm_fail'] = 'No se pudo quitar el actualizador';

//Proceed to index page
$lang['boot_goto_index'] = 'Seguir a la página del índice';

//Remove the files manually via FTP
$lang['boot_remove_ftp'] = 'Quitar los archivos manualmente por FTP';

//&ldquo;install.php&rdquo; and/or &ldquo;update.php&rdquo; still exists!
$lang['boot_still_exist_sub1'] = 'Aún existe &laquo;install.php&raquo; o &laquo;update.php&raquo;.';

//If you get this error again, delete the files manually via FTP.
$lang['boot_still_exist_sub3'] = 'Si recibe de nuevo este error, borre a mano los archivos por FTP.';

//Password verification failed. Make sure the new password is typed properly in both fields
$lang['pass_ver_failed'] = 'Falló la verificación de contraseña. Asegúrese de que esté bien escrita en los dos campos.';

//Password contains invalid characters: {1}
$lang['pass_invalid_chars'] = 'La contraseña tiene caracteres inválidos: {1}';

//Password is empty.
$lang['pass_emtpy'] = 'La contraseña está vacía.';

//Username contains invalid characters: {1}
$lang['name_invalid_chars'] = 'El nombre tiene caracteres inválidos: {1}';

//Humanity test failed.
$lang['humanity_test_failed'] = 'Falló la prueba de humanidad.';

//You must submit a valid age declaration (birth year).
$lang['submit_valid_age'] = 'Debe enviar una declaración de edad válida (año de nacimiento).';

//Your age declaration could not be accepted.
$lang['age_not_accepted'] = 'No se pudo aceptar su declaración de edad.';

//A valid URL is required to register on this BBS
$lang['valid_url_req'] = 'Hace falta un URL válido para inscribirse en este BBS.';

//You must declare your age to register on this BBS
$lang['must_declare_age'] = 'Debe declarar su edad para inscribirse en este BBS.';

//Sorry, the BBS e-mailer isn't working. You'll have to wait for your application to be approved.
$lang['email_wait_approval'] = "El gestor de correo del BBS no funciona. Debe esperar a que se apruebe su solicitud.";

//Database error. Please try again.
$lang['db_err'] = 'Error de base de datos. Intente de nuevo.';

//Database error. Try using {1}picture recovery{2}.
// {1}=BBCode start tag, {2}=BBCode end tag
$lang['db_err_pic_recovery'] = 'Error de base de datos. Intente usar {1}recuperación de imágenes{2}.';

//You cannot post a comment because the thread is locked
$lang['no_post_locked'] = 'No puede publicar comentarios porque el hilo está bloqueado.';

//HTML links unsupported.  Use NiftyToo/BBCode instead.
$lang['no_html_alt'] = 'No se admiten enlaces HTML. Use NiftyToo o BBCode.';

//Guests may only post {1} {1?link:links} per comment on this board.
$lang['guest_num_links'] = 'Los invitados sólo pueden publicar {1} {1?enlace:enlaces} por comentario en este tablón.';

//You must be a moderator to mark pictures other than yours as adult.
$lang['mod_change_adult'] = 'Debe ser un moderador para marcar como para adultos imágenes que no sean suyas.';

//Only moderators may change safety save status.
$lang['mod_change_wip'] = 'Sólo los moderadores pueden cambiar el estado de las copias de seguridad.';

//Only moderators may use this function.
$lang['mod_only_func'] = 'Sólo los moderadores pueden usar esta función.';

//No mode!  Some security policies or advertisements on shared servers may interfere with comments and picture data.  This is a technical problem.  Ask your admin for help.
$lang['func_no_mode'] = 'Sin modo. Algunas políticas de seguridad o publicidades en servidores compartidos pueden interferir con datos de comentarios y de imágenes. Es un problema técnico. Pídale ayuda a su administrador.';

/* End Version 1.5.5 */



/* Version 1.5.6 */

//Registered
// context = Date on which a member "Submit registration" or "Signed up"
// {1} = count of pending registrations
$lang['registered_on'] = 'Fecha de inscripción';

//Modify Canvas Size (max is {1} &times; {2})
$lang['applet_modify'] = "Cambiar tamaño del lienzo (máximo {1} &times; {2})";

//Canvas (min: {1}&times;{2}, max: {3}&times;{4})
$lang['draw_canvas_min_max'] = 'Lienzo (mín.: {1}&times;{2}, máx.: {3}&times;{4})';

//If you're having trouble with the applet, try downloading the latest version of Java from {1}.
$lang['javahlp'] = "Si tiene problemas con la aplicación, intente bajar la última versión de Java de {1}.";

//If you do not need them anymore, <a href="{1}">click here to remove them</a>.
$lang['boot_still_exist_sub2'] = 'Si no los necesita más, <a href="{1}">haga clic aquí para quitarlos</a>.';

//Delete Palette
$lang['delete_palette'] = 'Borrar paleta';

//You may have {1} safety {1?save:saves} at a time.  Remember to finish a safety save soon or it will be automatically deleted within {2} {2?day:days}.
$lang['safesavemsg2'] = "Puede tener {1} {1?copia:copias} de seguridad a la vez. Recuerde {1?terminarla:terminarlas} pronto, o {1?será borrada:serán borradas} automáticamente en {2} {2?día:días}.";

//Safety save was successful!  To resume a safety save, click &ldquo;Draw&rdquo;, or use the &ldquo;Recover Pics&rdquo; menu.
$lang['safesavemsg3'] = "Copia de seguridad guardada con éxito. Para retomar una de éstas, haga clic en &laquo;Dibujar&raquo; o use el menú &laquo;Recuperar imágenes&raquo;.";

//Every time you retouch your safety save, the delete timer will be reset to {1} {1?day:days}.
$lang['safesavemsg5'] = "Siempre que retoque su copia de seguridad, se reiniciará el conteo de eliminación a {1} {1?día:días}.";

//Error reading picture #{1}.
$lang['err_readpic'] = "Error leyendo la imagen {1}.";

//What is {1} {2} {3}?
//What is  8   +   6 ?
$lang['humanity_question_3_part'] = "¿Cuánto es {1} {2} {3}?";

//Safety saves are stored for {1} {1?day:days}.
$lang['sagesaveopt3'] = "Las copias de seguridad se guardan por {1} {1?día:días}.";

//Comments (<a href="{1}">NiftyToo Usage</a>)
$lang['header_comments_niftytoo'] = 'Comentarios (<a href="{1}">Uso de NiftyToo</a>)';

//Edit Comment (<a href="{1}">NiftyToo Usage</a>)
$lang['ecomm_title'] = 'Editar comentario (<a href="{1}">Uso de NiftyToo</a>)';

//Edit Picture Info (<a href="{1}">NiftyToo Usage</a>)
$lang['erpic_title'] = 'Editar información de imagen (<a href="{1}">Uso de NiftyToo</a>)';

//Message Box (<a href="{1}">NiftyToo Usage</a>)
$lang['chat_msgbox'] = 'Buzón de mensajes (<a href="{1}">Uso de NiftyToo</a>)';

//(<a href="{1}">NiftyToo Usage</a>)
$lang['common_niftytoo'] = '(<a href="{1}">Uso de NiftyToo</a>)';

//(Original by <strong>{1}</strong>)
// {1} = member name
$lang['originalby'] = "(Original por <strong>{1}</strong>)";

//If you are not redirected in {1} seconds, click here.
// context = clickable, {1} defaults to "3" or "three"
$lang['common_redirect'] = 'Si no se lo redirige en tres segundos, haga clic aquí.';

//Could not write config file.  Check your server permissions.
$lang['cpanel_cfg_err'] = "No se pudo abrir el archivo de configuración config.php para escritura. Revise sus permisos en el servidor.";

//Enable Mailbox
$lang['enable_mailbox'] = "Activar casilla de correo";

//Unable to read picture #{1}.
$lang['delconf_pic_err'] = 'No se pudo leer la imagen número {1}.';

//Image too large!  Size limit is {1} &times; {2} pixels.
$lang['err_imagelar'] = "¡La imagen es muy grande! El máximo tamaño es de {1} &times; {2} píxeles.";

//It must not be larger than {1} &times; {2} pixels.
$lang['notlarg'] = "No puede tener más de {1} &times; {2} píxeles.";

//(No avatar)
$lang['noavatar'] = "(Sin avatar)";

//Print &ldquo;Edited on (date)&rdquo;
// context = (date) is a literal.  Actual date not printed.
$lang['print_edited_on'] = "Imprimir &laquo;Editado el (fecha)&raquo;";

//(Edited on {1})
// {1} = current date
$lang['edited_on'] = "(Editado el {1})";

//Print &ldquo;Edited by {1}&rdquo;
// {1} = admin name
$lang['print_edited_by_admin'] = "Imprimir &laquo;Editado por {1}&raquo;";

//(Edited by <strong>{1}</strong> on {2})
// {1} = admin name, {2} = current date
$lang['edited_by_admin'] = "(Editado por <strong>{1}</strong> el {2})";

//You may check this if you are an adult (at least {1} years old).
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['eprofile_adultsub'] = "Puede marcar esto si es adulto (mínimo {1} años).";

//Load time {1} seconds
$lang['footer_load_time'] = 'Tiempo de carga: {1} segundos';

//Links disabled for guests
// context = HTML-formatted links in comments on pictures
$lang['no_guest_links'] = 'Enlaces deshabilitados para invitados.';

//{1}+
// context = Marks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['mark_adult'] = '>{1}';

//Un {1}+
// context = Unmarks picture as adult content.
// {1} = MIN_AGE_ADULT, default 18, does not have to be used.
$lang['unmark_adult'] = 'Quitar >{1}';

//Mailbox:
$lang['mailbox_label'] = 'Casilla de correo:';

//{1} {1?message:messages}, {2} unread
$lang['mail_count'] = '{1} {1?mensaje:mensajes}, {2} sin leer';

//From:
$lang['from_label'] = 'De:';

//Re:
// context = may be used inconsistently for technical reasons
$lang['reply_label'] = 'Re:';

//Subject:
$lang['subject_label'] = 'Asunto:';

//Send To:
$lang['send_to_label'] = 'Enviar a:';

//Message:
$lang['message_label'] = 'Mensaje:';

//<a href="{1}">{2}</a> @ {3}
// context = "{2=username} sent you this mailbox message at/on {3=datetime}"
$lang['mail_sender_datetime'] = '<a href="{1}">{2}</a> el {3}';

//Registered: {1} {1?member:members} and {2} {2?admin:admins}
$lang['mmail_reg_list'] = 'Inscritos: {1} {1?miembro:miembros} y {2} {2?administrador:administradores}';

//{1} {1?member:members} active within the last {2} days.
$lang['mmail_active_list'] = '{1} {1?miembro activo:miembros activos} en los últimos {2} {2?día:días}.';

//Everyone ({1})
$lang['mmail_to_everyone'] = 'Todos ({1})';

//Active members ({1})
$lang['mmail_to_active'] = 'Miembros activos ({1})';

//All admins/mods ({1})
// context = admins and moderators
$lang['mmail_to_admins_mods'] = 'Todos los administradores ({1})';

//Super-admins only
$lang['mmail_to_superadmins'] = 'Superadministradores solamente';

//Flags: FLAG DESCRIPTION
// context = "Can Draw", or "Drawing ability", etc.
$lang['mmail_to_draw_flag']   = 'Con acceso a dibujar';
$lang['mmail_to_upload_flag'] = 'Con acceso a subir';
$lang['mmail_to_adult_flag']  = 'Con acceso a vista para adultos';
$lang['mmail_to_immune_flag'] = 'Con inmunidad';

//<a href="{1}">Online</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_online'] = '<a href="{1}">{2?Conectado:Conectados}</a> ({2})';

//<a href="{1}">Chat</a> ({2})
// context = Used as link. {2} = count of people
$lang['header_chat'] = '<a href="{1}">Chat</a> ({2})';

//<strong>Rules</strong>
// context = normally in bold text
$lang['header_rules'] = '<strong>Reglas</strong>';

//<strong>Draw</strong>
// context = normally in bold text
$lang['header_draw'] = '<strong>Dibujar</strong>';

//<a href="{1}">Mailbox</a> ({2})
// context = Used as link. {2} = count of messages
$lang['header_mailbox'] = '<a href="{1}">Casilla de correo</a> ({2})';

//Online
// context = Used as label. No HTML link. {1} = count of people (if desired)
$lang['chat_online'] = '{1?Conectado:Conectados}';

//{1} {1?member:members} match your search.
$lang['match_search'] = '{1} {1?miembro:miembros} coinciden con su búsqueda.';

//{1} {1?member:members}, {2} active within {3} days.
$lang['member_stats'] = '{1} {1?miembro:miembros}, {2} {2?activo:activos} en {3} {3?día:días}.';

//(None)
// context = placeholder if no avatar available
$lang['avatar_none'] = '(Ninguno)';

//No rank
// or "None". context = administrator rank
$lang['rank_none'] = 'Ningún rango';

//No Thumbnails
$lang['cp_no_thumbs'] = 'Sin miniaturas';

//No pictures
$lang['no_pictures'] = 'Sin imágenes';

//{1} (last login)
// {1} = IP address.
$lang['ip_by_login'] = '{1} (última conexión)';

//{1} (last comment)
$lang['ip_by_comment'] = '{1} (último comentario)';

//{1} (last picture)
$lang['ip_by_picture'] = '{1} (última imagen)';

//(None)
// context = placeholder if no url to web site
$lang['url_none'] = '(Ninguno)';

//(Web site)
// context = placeholder if there is a url, but no title (no space to print whole url)
$lang['url_substitute'] = '(Sitio web)';

//(Default)
// context = placeholder if default template is chosen
$lang['template_default'] = '(Predeterminado)';

//(Default)
// context = placeholder if default language is chosen
$lang['language_default'] = '(Predeterminado)';

//Default
$lang['palette_default'] = 'Predeterminado';

//No Title
$lang['no_pic_title'] = 'Sin título';

//Send E-mails
$lang['install_send_emails'] = 'Enviar correos';

//Adjusts how many e-mails are sent by your server.  Default is &ldquo;Yes&rdquo;, and is highly recommended.
$lang['adjust_emails_sent_sub1'] = 'Ajusta cuántos correos manda su servidor. El predeterminado es &laquo;Sí&raquo;, y se recomienda.';

//Minimal
// or "Minimum"
$lang['cpanel_emails_minimal'] = 'Mínimo';

//&ldquo;Minimal&rdquo; will reduce e-mails by approximately {1}.  Choose &ldquo;No&rdquo; if your server cannot send e-mail.
// {1} = percentage or fraction
$lang['adjust_emails_sent_sub2'] = '&laquo;Mínimo&raquo; reducirá los correos en {1} approximadamente. Elija &laquo;No&raquo; si su servidor no puede mandar correos.';

//No (recommended)
$lang['chat_no_set_reccom'] = 'No (recomendado)';

//Display - Mailbox
$lang['install_mailbox'] = 'Vista: Casilla de correo';

//Sorry, the BBS e-mailer isn't working. Ask an admin to reset your password.
$lang['no_email_pass_reset'] = "El gestor de correo del BBS no funciona. Pídale a un administrador que restablezca su contraseña.";

//User deleted, but e-mailer isn't working. Notify user manually at {1}.
$lang['no_email_kill_notify'] = "Se eliminó el usuario, pero el gestor de correo no funciona. Notifíquele manualmente al usuario en {1}.";

//<strong>Maintenance</strong>
// context = Highly visible notification if board is in maintenance mode. Disables "Logout"
$lang['header_maint'] = '<strong>Mantenimiento</strong>';

//The oekaki is down for maintenance
// context = <h2> on plain page
$lang['boot_down_maint'] = 'El oekaki está cerrado por mantenimiento';

//&ldquo;{1}&rdquo; should be back online shortly.  Send all quesions to {2}.
// {1} = oekaki title, {2} = admin e-mail
$lang['boot_maint_exp'] = '&laquo;{1}&raquo; volverá pronto. Si tiene preguntas escriba a {2}.';

//Member name already exists!
$lang['func_reg_name_exists'] = 'Ya existe el nombre de usuario.';

//You cannot access flag modification
$lang['func_no_flag_access'] = 'No puede acceder a la modificación de permisos.';

//Account updated, but member could not be e-mailed.
$lang['func_update_no_mail'] = 'Se actualizó la cuenta, pero no se pudo enviar correo al miembro.';

//Account rejected, but could not be e-mailed. Notify applicant manually at {1}'
// {1} = e-mail address
$lang['func_reject_no_mail'] = 'Se rechazó la cuenta, pero no se pudo enviar correo. Notifique al postulante en {1}';

//Your age declaration could not be accepted.
$lang['func_bad_age'] = 'No se pudo aceptar su declaración de edad.';

//Image too large!  Size limit is {1} bytes.
$lang['err_imagelar_bytes'] = '¡La imagen es muy grande! El máximo tamaño es de {1} bytes.';

//No picture data was received. Try again.
$lang['func_no_img_data'] = 'No se recibieron datos de imagen. Intente de nuevo.';

//An error occured while uploading. Try again.
$lang['func_up_err'] = 'Ocurrió un error durante la carga. Intente de nuevo.';



/* whosonline.php */

// context = nouns, not verbs
$lang['o_unknown']     = 'Desconocida';
$lang['o_addusr']      = 'Lista de pendientes';
$lang['o_banlist']     = 'Lista de vetos';
$lang['o_chatbox']     = 'Chat';
$lang['o_chibipaint']  = 'Chibi Paint';
$lang['o_chngpass']    = 'Cambio de contraseña';
$lang['o_comment']     = 'Comentarios';
$lang['o_cpanel']      = 'Panel de control';
$lang['o_delcomments'] = 'Borrado de comentarios';
$lang['o_delpics']     = 'Borrado de imágenes';
$lang['o_delusr']      = 'Borrado de usuarios';
$lang['o_draw']        = 'Pantalla de dibujo';
$lang['o_edit_avatar'] = 'Edición de avatar';
$lang['o_editcomm']    = 'Edición de comentarios';
$lang['o_editnotice']  = 'Edición de anuncio o pancarta';
$lang['o_editpic']     = 'Edición de imagen';
$lang['o_editprofile'] = 'Edición de perfil';
$lang['o_editrules']   = 'Edición de reglas';
$lang['o_faq']         = 'Preguntas frecuentes';
$lang['o_index']       = 'Vista';
$lang['o_index_match'] = 'Vista por artista';
$lang['o_lcommentdel'] = 'Borrado de comentarios';
$lang['o_log']         = 'Registro';
$lang['o_lostpass']    = 'Recuperación de contraseña';
$lang['o_mail']        = 'Buzón';
$lang['o_massmail']    = 'Correo masivo';
$lang['o_memberlist']  = 'Lista de miembros';
$lang['o_modflags']    = 'Permisos de miembros';
$lang['o_newpass']     = 'Cambio de contraseña';
$lang['o_niftyusage']  = 'Usando NiftyToo';
$lang['o_notebbs']     = 'NoteBBS';
$lang['o_oekakibbs']   = 'OekakiBBS';
$lang['o_paintbbs']    = 'PaintBBS';
$lang['o_profile']     = 'Visor de perfiles';
$lang['o_recover']     = 'Recuperación de imágenes';
$lang['o_retouch']     = 'Retoque';
$lang['o_shibbs']      = 'ShiPainter';
$lang['o_showrules']   = 'Reglas';
$lang['o_sqltest']     = 'Diagnósticos';
$lang['o_testinfo']    = 'Diagnósticos';
$lang['o_upload']      = 'Carga de archivos';
$lang['o_viewani']     = 'Visor de animaciones';
$lang['o_whosonline']  = 'Lista de conectados';

/* END whosonline.php */



//Submit for review
$lang['submit_review'] = 'Enviar a revisión';

//<a href="http://www.NineChime.com/products/" title="Get your own free BBS!">{1}</a> by {2} / Based on <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a> by <a href="http://suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
// {1} = "Wacintaki" + link
// {2} = "Waccoon" (may change)
$lang['f_bbs_credits'] = '<a href="http://www.NineChime.com/products/" title="Consiga su propio BBS gratis">{1}</a> por {2}. Basado en <a href="http://www.suteki.nu/community/">OekakiPoteto v5.x</a>, de <a href="http://suteki.nu">RanmaGuy</a> y <a href="http://www.cellosoft.com">Marcello</a>.';

//PaintBBS and Shi-Painter by <a href="http://shichan.jp/">Shi-chan</a> / ChibiPaint by <a href="http://www.chibipaint.com">Mark Schefer</a>
$lang['f_applet_credits'] = 'PaintBBS y Shi-Painter por <a href="http://shichan.jp">Shi-chan</a>. ChibiPaint por <a href="http://www.chibipaint.com">Mark Schefer</a>';

//Administrator Account
// context = default comment for admin account
$lang['install_admin_account'] = 'Cuenta de administrador';

//If you are submitting a picture or just closing the window, click OK."
// context = JavaScript alert.  Browser/version specific, and may be troublesome.
$lang['js_noclose'] = 'Si está enviando una imagen o cerrando la ventana, haga clic en Aceptar.';

//Comment
// context = verb form; label for making posts on pictures
$lang['verb_comment'] = 'Comentar';

//&hellip;
// context = Placeholder if a comment is empty
$lang['no_comment'] = '&hellip;';

//OekakiPoteto 5.x by <a href="http://www.suteki.nu">RanmaGuy</a> and <a href="http://www.cellosoft.com">Marcello</a>
$lang['install_byline'] = 'OekakiPoteto 5.x por <a href="http://www.suteki.nu">RanmaGuy</a> y <a href="http://www.cellosoft.com">Marcello</a>';

//Wacintaki 1.x modifications by <a href="http://www.NineChime.com/products/">Waccoon</a>
// {1} = "Waccoon" (may change)
$lang['install_byline2'] = 'Modificaciones Wacintaki 1.x por <a href="http://www.NineChime.com/products/">{1}</a>';

//Wacintaki Oekaki: draw pictures online
// context = HTML head->meta name
$lang['meta_desc'] = 'Wacintaki Oekaki: dibuje en internet';

//(Link)
// context = Placeholder, link to a web page on the memberlist
$lang['ml_web_link'] = '(Enlace)';

//-
// context = Placeholder, no web page available on the memberlist
$lang['ml_no_link'] = '-';

//-
// context = Placeholder, no email available on the memberlist
$lang['ml_no_email'] = '-';

//&ldquo;{1}&rdquo; cannot be opened.
// {1} = filename
$lang['file_o_warn'] = 'No se puede abrir &laquo;{1}&raquo;.';



/* log.php */

// The log is a diagnostic tool.  Retain formatting and colons.
// Some filenames (lowercase, without '.php') are not translatable.

// Generic warning
$lang['l_'.WLOG_MISC] = 'Misceláneo';

// Generic error/failure
$lang['l_'.WLOG_FAIL] = 'Falla';

// SQL error
$lang['l_'.WLOG_SQL_FAIL] = 'Falla de SQL';

// Regular maintenance
$lang['l_'.WLOG_MAINT] = 'Mantenimiento';

// Input from client, hacking
$lang['l_'.WLOG_SECURITY] = 'Seguridad';

// Updates and events
$lang['l_'.WLOG_BANNER] = 'Pancarta';
$lang['l_'.WLOG_RULES]  = 'Reglas';
$lang['l_'.WLOG_NOTICE] = 'Anuncio';
$lang['l_'.WLOG_CPANEL] = 'CPanel';
$lang['l_'.WLOG_THUMB_OVERRIDE] = 'Cambio de miniatura';
$lang['l_'.WLOG_THUMB_REBUILD]  = 'Reconstrucción de miniaturas';
$lang['l_'.WLOG_MASS_MAIL]    = 'Correo masivo';
$lang['l_'.WLOG_BAN]          = 'Veto';
$lang['l_'.WLOG_DELETE_USER]  = 'Eliminación de usuario';
$lang['l_'.WLOG_REG]          = 'Inscripción';
$lang['l_'.WLOG_APPROVE]      = 'Aprobación';
$lang['l_'.WLOG_EDIT_PROFILE] = 'Edición de perfil';
$lang['l_'.WLOG_FLAGS]        = 'Cambio de marcas';
$lang['l_'.WLOG_PASS_RECOVER] = 'Recuperación de contraseña';
$lang['l_'.WLOG_PASS_RESET]   = 'Restablecimiento de contraseña';
$lang['l_'.WLOG_ARCHIVE]      = 'Archivo';
$lang['l_'.WLOG_BUMP]         = 'Subida';
$lang['l_'.WLOG_RECOVER]      = 'Recuperación';
$lang['l_'.WLOG_DELETE]       = 'Borrado';
$lang['l_'.WLOG_LOCK_THREAD]  = 'Bloqueo de hilo';
$lang['l_'.WLOG_ADULT]        = 'Para adultos';
$lang['l_'.WLOG_ADMIN_WIP]    = 'Inacabado (por administrador)';
$lang['l_'.WLOG_EDIT_PIC]     = 'Edición de imagen';
$lang['l_'.WLOG_EDIT_COMM]    = 'Edición de comentario';

//cpanel: Updated
$lang['l_c_update'] = 'cpanel: Actualizado';

//No RAW POST data
// context = "RAW POST" is a programming term for HTTP data
$lang['l_no_post'] = 'Sin datos RAW POST';

//Cannot allocate new PIC_ID
$lang['l_no_picid'] = 'No se pudo asignar un nuevo PIC_ID';

//Cannot insert image #{1}
$lang['l_app_no_insert'] = 'No se pudo insertar la imagen número {1}';

//Cannot save image #{1}
$lang['l_app_no_save'] = 'No se pudo guardar la imagen número {1}';

//paintsave: Bad upload for #{1}, cannot make thumbnail
$lang['l_bad_upload'] = 'paintsave: Carga mal hecha en la número {1}, no se puede hacer la miniatura';

//paintsave: Cannot make &ldquot&rdquo; thumbnail for image #{1}
$lang['l_no_t'] = 'paintsave: No se puede hacer la miniatura &laquo;t&raquo; para la imagen número {1}';

//paintsave: Cannot make &ldquo;r&rdquo; thumbnail for image #{1}
$lang['l_no_r'] = 'paintsave: No se puede hacer la miniatura &laquo;r&raquo; para la imagen número {1}';

//paintsave: Bad datatype for image #{1} (saved as &ldquo;dump.png&rdquo;)
$lang['l_no_type'] = 'paintsave: Tipo de datos erróneo en la imagen número {1} (guardada como &laquo;dump.png&raquo;)';

//paintsave: Corrupt image dimentions for #{1}
$lang['l_no_dim'] = 'paintsave: Dimensiones de imagen número {1} corruptas';

//paintsave: cannot write image &ldquo;{1}&rdquo;
$lang['l_no_open'] = 'paintsave: no se puede escribir la imagen &ldquo;{1}&rdquo;';

//Picture: #{1}
// context = "Picture #{1} affected"
$lang['l_mod_pic'] = 'Imagen: número {1}';

//Comment: #{1}
// context = "Comment #{1} affected"
$lang['l_mod_comm'] = 'Comentario: número {1}';

//WIP: #{1}
// context = "WIP or Safety save #{1} affected"
$lang['l_mod_wip'] = 'Inacabada: número {1}';

//Active
// context: Active members.  Displayed under "Peer (affected)"
$lang['type_active'] = 'Activos'; 

//Sent to:
// context = Password recovery.  {1} = E-mail address.
$lang['l_sent_to'] = 'Enviado a: {1}';

//Reset by: Admin
// context = Password reset
$lang['l_reset_admin'] = 'Restablecido por: administrador';

//Reset by: Member
// context = Password reset
$lang['l_reset_mem'] = 'Restablecido por: miembro';

//Reason sent: Yes or No
// context = Reason for being deleted.  Yes or No only.
$lang['l_reason_yes'] = 'Razón enviada: sí';
$lang['l_reason_no'] = 'Razón enviada: no';

//Accepted with the following flags: {1}
// {1} ~ 'GDU' or some other combination of letters
$lang['l_accept_f'] = 'Aceptado con las siguientes marcas: {1}';

//Profile: Updated
$lang['l_prof_up'] = 'Perfil: actualizado';

//Banlist: Updated
$lang['l_ban_up'] = 'Lista de vetos: actualizada';

//Banner and notice: Updated
$lang['l_banner_notice_up'] = 'Pancarta y anuncio: actualizados';

//Rules: Updated
$lang['l_rules_up'] = 'Reglamento: actualizado';

//Upload: Cannot insert image &ldquo;{1}&rdquo;
$lang['l_f_no_insert'] = 'Carga: no se puede insertar la imagen &laquo;{1}&raquo;';

//Upload: Cannot save image &ldquo;{1}&rdquo;
$lang['l_f_no_save'] = 'Carga: no se puede guardar la imagen &laquo;{1}&raquo;';

//Upload: Cannot save image #{1}
$lang['l_f_no_anim'] = 'Carga: no se puede guardar la imagen número {1}';

//Retouch: #{1}
// context = Bump by retouching picture.
$lang['l_f_bump_retouch'] = 'Retoque: número {1}';

//Locked: #{1}
// context = Thread #{1} locked or unlocked
$lang['l_f_lock']   = 'Bloqueado: número {1}';
$lang['l_f_unlock'] = 'Desbloqueado: número {1}';

//Marked as adult: #{1}
// context = [Un]marked #{1} as an adults-only picture
$lang['l_f_adult']   = 'Marcada para adultos: número {1}';
$lang['l_f_unadult'] = 'Desmarcada para adultos: número {1}';

/* END log.php */



//Member &ldquo;{1}&rdquo; could not be found.
$lang['mem_not_found'] = 'No se pudo encontrar el miembro &laquo;{1}&raquo;.';

//Profile for &ldquo;{1}&rdquo; not retrievable!  Check the log.
$lang['prof_not_ret'] = 'No se pudo acceder al perfil de &laquo;{1}&raquo;. Revise el registro.';

//Cannot allocate new PIC_ID for upload.
$lang['f_no_picid'] = 'No se pudo asignar un nuevo PIC_ID para la carga.';

//You must be a moderator to lock or unlock threads.
$lang['functions_err22'] = 'Debe ser un moderador para bloquear o desbloquear hilos.';

//Please run the <a href="{1}">updater</a>.
$lang['please_update'] = 'Por favor ejecute el <a href="{1}">actualizador</a>.';

//Current version: {1}
$lang['please_update_cur'] = 'Versión actual: {1}';

//New version: {1}
// context = version after update has completed
$lang['please_update_new'] = 'Nueva versión: {1}';



/* update.php */

//Starting update from {1} to {2}.
// {1} = from name+version, {2} = to name+version
$lang['up_from_to'] = 'Iniciando actualización de {1} a {2}.';

//Finished update to Wacintaki {1}.
// {1} = to version number
$lang['up_fin_to'] = 'Se completó la actualización a Wacintaki {1}.';

//Update to Wacintaki {1} failed.
// {1} = to version number
$lang['up_to_fail'] = 'Falló la actualización a Wacintaki {1}.';

//Verifictaion of Wacintaki {1} failed.
// {1} = to version number
$lang['up_ver_fail'] = 'Falló la verificación de Wacintaki {1}.';

//STOP: Cannot read file &ldquo;{1}&rdquo; for database connection.
// {1} = filename
$lang['up_cant_read_for_db'] = 'ALTO: No se puede leer el archivo &laquo;{1}&raquo; para la conexión a la base de datos.';

//STOP: Cannot write file &ldquo;{1}&rdquo;
// {1} = filename
$lang['up_cant_write_file'] = 'ALTO: No se puede escribir el archivo &laquo;{1}&raquo;';

//STOP: &ldquo;{1}&rdquo; file is not writable.
// {1} = filename
$lang['up_file_locked'] = 'ALTO: El archivo &laquo;{1}&raquo; no es escribible.';

//WARNING: missing image &ldquo;{1}&rdquo; for post {2}.
// {1} = picture filename, {2} = number (ID_2)
$lang['up_missing_img'] = 'ADVERTENCIA: falta la imagen &laquo;{1}&raquo; para la publicación {2}.';

//WARNING:  Folder &ldquo;{1}&rdquo; is not writable.  CHMOD the folder to 775 or 777 after the updater has finished.
// {1} = folder name
$lang['up_folder_locked'] = 'ADVERTENCIA: No se puede escribir al directorio &laquo;{1}&raquo;. Cámbiele los permisos a 775 o 777 cuando el actualizador haya terminado.';

//STOP: Unable to add admin ranks to database (SQL: {1})
// {1} db_error()
$lang['up_no_add_rank'] = 'ALTO: No se pueden añadir los rangos de adminsitrador a la base de datos (SQL: {1})';

//STOP:  Could not set admin rank for {1} (SQL: {2})
// {1} = username
// {2} = db_error()
$lang['up_no_set_rank'] = 'ALTO:  No se pudo añadir el rango de administrador de {1} (SQL: {2})';

//STOP: Could not create folder &ldquo;{1}&rdquo;.
// {1} = filename
$lang['up_cant_make_folder'] = 'ALTO: No se pudo crear el directorio &laquo;{1}&raquo;.';

//STOP: Could not update piccount (current picture number) for new sorting system (SQL: {1})
// {1} = db_error()
$lang['up_no_piccount'] = 'ALTO: No se pudo actualizar el conteo de imágenes para el nuevo sistema de ordenamiento (SQL: {1})';

//STOP: Wax Poteto database not at required version (1.3.0).  Run the Wax Poteto 5.6.x updater first.
$lang['up_wac_no_130'] = 'ALTO: La base de datos Wax Poteto no está en la versión requerida (1.3.0). Ejecute primero el actualizador de Wax Poteto 5.6.x.';

//STOP: Could not verify database version marker (SQL: {1})
// {1} = db_error()
$lang['up_no_set_db_utf'] = 'ALTO: No se pudo verificar el marcador de versión de base de datos (SQL: {1})';

//NOTE: Remember to copy your resource files (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) into the Wacintaki &ldquo;resource&rdquo; folder and CHMOD them so they are writable.
$lang['up_move_res'] = 'NOTA: Recuerde copiar sus archivos de recursos (banner.php, hosts.txt, ips.txt, pr0n.png, preview.png) al directorio &laquo;resource&raquo; de Wacintaki y ejecutar CHMOD de modo que se puedan escribir.';

//Wacintaki 1.5.6 requires significant changes to the database to support international letters.
$lang['up_change_sum'] = 'Wacintaki 1.5.6 requiere cambios significativos a la base de datos para permitir letras internacionales.';

//Click here to start the database conversion.
$lang['up_click_start_conv'] = 'Haga clic aquí para comenzar la conversión de la base de datos.';

//STOP: Cannot read UTF-8 marker from database.
$lang['up_no_dbutf_marker'] = 'ALTO: No se pudo leer el marcador UTF-8 de la base de datos.';

//Cleaned up {1} orphaned files.
$lang['up_cleaned_sum'] = 'Se {1?limpió {1} archivo huérfano:limpiaron {1} archivos huérfanos}.';

//Unsupported update type &ldquo;From {1} to {2}&rdquo;.
// {1} from version, {2} = to version
$lang['up_no_up_num'] = 'Tipo de actualización no permitido &laquo;De {1} a {2}&raquo;.';

//Board config version:  &ldquo;{1}&rdquo;,  database version:  &ldquo;{2}&rdquo;
// {1}+{2} = numbers
$lang['up_no_up_sum'] = 'Versión de configuración del tablón: &laquo;{1}&raquo;, versión de base de datos: &laquo;{2}&raquo;';

//Update cannot continue.
$lang['up_no_cont'] = 'No puede continuar la actualización.';

//If problems persist, visit the <a href="{1}">NineChime.com Forum</a>.
// {1} = url
$lang['up_nc_short'] = 'Si los problemas persisten, visite el <a href="{1}">foro de NineChime.com</a>.';

//Wacintaki Update {1}
// {1} = number
$lang['up_header_title'] = 'Actualización {1} de Wacintaki';

//If you have multiple boards, make sure each board is at the current version.
$lang['up_mult_warn'] = 'Si tiene varios tablones, asegúrese de que cada uno esté en la versión actual.';

//Click the button below to finalize the update.  This will delete the updater files and prevent security problems.
$lang['up_click_final'] = 'Haga clic en el botón de abajo para terminar la actualización. Esto eliminará los archivos del actualizador y evitará problemas de seguridad.';

//Secure updater and go to the BBS
// context = clickable button
$lang['up_secure_button'] = 'Asegurar el actualizador e ir al BBS';

//Some warnings were returned during the update.  Please note these messages and run the updater again to ensure everything is set properly.  You may run the updater multiple times if needed.
$lang['up_warn_rerun'] = 'Se recibieron algunas advertencias durante la actualización. Por favor tome nota de los mensajes y ejecute de nuevo el actualizador, asegurándose de que todo esté en orden. Puede ejecutarlo varias veces si necesita.';

//Errors occured during the update!  Check your server and database permissions and try again.  The update will not function properly until all errors are resolved.
$lang['up_stop_sum'] = 'Ocurrieron errores durante la actualización. Revise sus permisos de servidor y base de datos, y reintente. La actualización no funcionará hasta que todos los errores se arreglen.';

//NOTE: Make sure you\'ve deleted your old OekakiPoteto v5.x template and language files before running this updater.  Your old OekakiPoteto templates and language files will not work with Wacintaki.
$lang['up_no_op_tpl'] = 'NOTA: Asegúrese de haber borrado sus archivos viejos de plantillas e idioma de OekakiPoteto 5.x antes de ejecutar el actualizador. Sus archivos viejos no funcionarán con Wacintaki.';

//Click Next to start the update.
$lang['up_next_start'] = 'Haga clic en Siguiente para empezar la actualización.';

//Next
// context = clickable button
$lang['up_word_next'] = 'Siguiente';

//{1} detected.
// {1} = version
$lang['up_v_detected'] = 'Se detectó {1}.';

//You appear to be running the latest version of Wacintaki already.  You may proceed to verify that the last update completed correctly.
$lang['up_latest_ver'] = 'Parece estar ya ejecutando la última versión de Wacintaki. Puede verificar que la última actualización se haya completado correctamente.';

//Click Next to verify the update.
$lang['up_next_ver'] = 'Haga clic en Siguiente para verificar la actualización.';

//Unknown version.
$lang['up_unknown_v'] = 'Versión desconocida.';

//Config: {1}, Database: {2}
// {1}+{2} = numbers
$lang['up_unknown_v_sum'] = 'Configuración: {1}, base de datos: {2}';

//This updater only supports Wacintaki versions less than or equal to {1}.
// {1} = number
$lang['up_v_spread_sum'] = 'Este actualizador sólo permite versiones de Wacintaki menores o iguales que {1}.';

/* END update.php */



/* update_rc.php */

//Database has already been updated to UTF-8.
$lang['upr_already_utf'] = 'La base de datos ya se actualizó a UTF-8.';

//Click here to run the updater.
$lang['upr_click_run'] = 'Clic aquí para ejecutar el actualizador.';

//PHP extension &ldquo;iconv&rdquo; not available.  Cannot recode from Big5 to UTF-8!
// context = iconv is all lower case (shell program).
$lang['upr_iconv_mia'] = 'La extensión &laquo;iconv&raquo; de PHP no está disponible. No se puede recodificar de Big5 a UTF-8.';

//Please visit the <a href="{1}">NineChime Forum</a> for help.
// {1} = url
$lang['upr_nc_shortest'] = 'Para ayuda, visite el <a href="{1}">foro de NineChime</a>.';

//This tool will convert an existing Wacintaki database to support international letters and text (such as &laquo;&ntilde;&raquo; and &bdquo;&szlig;&ldquo;).
$lang['upr_conv_w_8bit'] = 'Esta herramienta convertirá una base de datos Wacintaki existente para permitir letras y texto internacionales (como &laquo;&ntilde;&raquo; y &bdquo;&szlig;&ldquo;).';

//If you have multiple Wacintaki boards installed on your web site, you only need to run this tool once, but you will still need to run the updater on each board.
// context = update_rc.php runs only once, update.php must run on each board.
$lang['upr_xself_mult_warn'] = 'Si tiene varios tablones Wacintaki instalados en su sitio web, sólo necesita ejecutar esta herramienta una vez; el actualizador se debe ejecutar por separado en cada tablón.';

//Using this tool more than once will not cause any damage.
$lang['upr_no_damage'] = 'Usar más de una vez esta herramienta no causará daños.';

//NOTE:  {1} encoding detected.  Conversion will be from {1} to UTF-8.
// {1} = iconv charset ("iso-8859-1", "big5", "utf-8", etc.)
$lang['upr_char_det_conv'] = 'NOTA: se detectó la codificación {1}. Se hará la conversión de {1} a UTF-8.';

//If you used international letters in your password, you will need to recover your password after the update.
$lang['upr_utf_rec_pass'] = 'Si usó letras internacionales en su contraseña, deberá recuperarla después de actualizar.';

//Click here to begin step {1} of {2}.
// {1}+{2} = numbers
$lang['upr_click_steps'] = 'Clic aquí para comenzar el paso {1} de {2}.';

//If you have problems converting the database, try visiting the <a href="{1}">NineChime Forum</a>, or you may <a href="{2}">bypass the conversion.</a>  If you bypass the conversion, existing comments with international letters will be corrupt, but new comments will post fine.
// {1} = url, {2} = local url
$lang['upr_nc_visit_bypass'] = 'Si tiene problemas convirtiendo la base de datos, puede visitar el <a href="{1}">foro de NineChime</a>, o bien <a href="{2}">omitir la conversión</a>. Si la omite, los comentarios existentes con letras internacionales se corromperán, pero los nuevos aparecerán bien.';

//STOP: Cannot create one or more temp files for database conversion.  Check the permissions of the main oekaki folder.
$lang['upr_no_make_temp'] = 'STOP: No se pudieron crear uno o más archivos temporales para la conversión de la base de datos. Revise los permisos del directorio principal del oekaki.';

//Done!  Database has been updated to UTF-8.
$lang['upr_done_up'] = 'Hecho. La base de datos ha sido actualizada a UTF-8.';

//Found {1} tables in the database.
$lang['upr_found_tbls'] = 'Se encontraron {1} tablas en la base de datos.';

//{1} {1?row:rows} need to be converted.
$lang['upr_found_rows'] = 'Hay que convertir {1} {1?fila:filas}.';

//<strong>Please wait...</strong> it may take a minute for the next page to show.
$lang['upr_plz_wait'] = '<strong>Por favor espere...</strong> la siguiente página puede tomar un minuto en aparecer.';

//STOP: Double-click or unexpected reload detected.  Please wait another {1} seconds.
$lang['upr_dbl_click'] = 'ALTO: Se detectó un doble clic o una recarga inesperada. Espere {1} segundos más.';

//Building resource files.  Please wait...
$lang['upr_build_res_wait'] = 'Construyendo archivos de recursos. Espere por favor.';

//Step {1} of database update finished.  Ready to start step {2}.
$lang['upr_step_ready_num'] = 'Terminó el paso {1} de la actualización a la base de datos. Listo para comenzar el paso {2}.';

//If there are any errors printed above, it\'s strongly recommended that you <a href="{1}">visit NineChime forum</a> for help.  The oekaki should still function properly if all members use only English letters.
// {1} = url
$lang['upr_if_err_nc'] = 'Si aparecen errores arriba, se recomienda que <a href="{1}">visite el foro de NineChime</a> para recibir ayuda. El oekaki seguirá funcionando si todos los miembros usan sólo letras inglesas.';

//Wacintaki UTF-8 Update
$lang['upr_header_title'] = 'Actualización a UTF-8 de Wacintaki';

//TIMEOUT: database partially exported.  This is normal if your database is very large.
$lang['upr_time_partial'] = 'TIEMPO AGOTADO: base de datos exportada parcialmente. Esto es normal si su base de datos es muy grande.';

//{1} {1?row:rows} updated before timeout.
$lang['upr_rows_partial'] = 'Se {1?actualizó {1} fila:actualizaron {1} filas} antes de agotarse el tiempo de espera.';

//Click here to resume.
$lang['upr_click_resume'] = 'Clic aquí para retomar.';

//TIMEOUT_IMPORT: database partially imported.  This is normal if your database is very large.
$lang['upr_time_norm'] = 'TIEMPO DE IMPORTACIÓN AGOTADO: base de datos importada parcialmente. Esto es normal si su base de datos es muy grande.';

//STOP:{1} Cannot get tables: (SQL: {2})
// {1} = placeholder, so maintain spacing.  {2} = db_error()
$lang['upr_sql_bad_tbls'] = 'ALTO:{1} No se pueden obtener las tablas: (SQL: {2})';

//STOP:{1} No SQL tables found!
// {1} = placeholder, so maintain spacing.
$lang['upr_sql_no_tbls'] = 'ALTO:{1} No se encontraron tablas SQL.';

//STOP: Error reading column &ldquo;{1}&rdquo;: (SQL: {2})
// {1} = column name, {2} = db_error()
$lang['upr_bad_col'] = 'ALTO: Error leyendo la columna &laquo;{1}&raquo;: (SQL: {2})';

//STOP: No SQL columns found in table &ldquo;{1}&rdquo;
// {1} = table name
$lang['upr_no_cols'] = 'ALTO: No se encontraron columnas SQL en la tabla &laquo;{1}&raquo;';

//{1} {1?row:rows} collected.  Total time for export: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_exp_time'] = '{1} {1?fila recogida:filas recogidas}. Tiempo total para la exportación: {2} {2?segundo:segundos}.';

//{1} {1?row:rows} updated.  Total time for import: {2} {2?second:seconds}.
// {2} is a decimal number (can't convert decimal notation yet)
$lang['upr_rows_imp_time'] = '{1} {1?fila actualizada:filas actuaizadas}. Tiempo total para la importación: {2} {2?segundo:segundos}.';

//STOP: set_db_utf8_misc_marker({1}): Cannot insert db_utf8 marker (SQL: {2})
// {1} = debug argument (ignore), {2} = db_error()
$lang['upr_utf_no_ins'] = 'ALTO: set_db_utf8_misc_marker({1}): No se puede insertar el marcador db_utf8 (SQL: {2})';

/* END update_rc.php */



//Uploaded
$lang['word_uploaded'] = 'Subido';

//(Uploaded by <strong>{1}</strong>)
$lang['uploaded_by_admin'] = '(subido por <strong>{1}</strong>)';

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
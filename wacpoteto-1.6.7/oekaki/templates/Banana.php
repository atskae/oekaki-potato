<?php
// Wacintaki Poteto 1.3.1
// Template format, 1.0.2

$tversion = 'wac-1.0.2';

// Control variable.  Values are 'build' or 'parse'
// 'build' will return entire template, 'parse' will define variables only
if (!isset($tcontrol)) {
	$tcontrol = 'build';
}



// Main vars
$t_name    = 'Banana';
$t_author  = 'Marc "Waccoon" Leveille';
$t_email   = 'Waccoon@NineChime.com';
$t_url     = 'http://NineChime.com/Wacintosh.htm';
$t_comment = 'Default template for Wacintaki Poteto';

$tnotes = <<<EOF
Template: $t_name
Author: $t_author
WWW: $t_url
E-Mail: $t_email
Comments: $t_comment
EOF;



// General
$bgColor   = '#F7DEC2';
$bgImage   = 'Banana/Background.png';
$fontSize  = '10pt';
$fontFace  = '"Arial", "Helvetica", sans-serif';
$textColor = 'black';
$link      = '#800000';
$aLink     = '#A00000';
$vLink     = '#805050';
$dStamp    = $textColor;

// Menus
$mBackgroundColor = '#EECA7D';
$mFontSize        = '11pt';
$mFontSmallSize   = '9pt';
$mBorderSize      = '1px';
$mBorderColor     = 'black';
$mBorderStyle     = 'solid';

// Page numbers
$pFontSize        = '9pt';
$pMargin          = '5%';
$pBackgroundColor = '';

// Headers
$hTextColor       = 'black';
$hBackgroundColor = '#FFE92A';
$hFontSize        = '10pt';
$hBorderSize      = '1px';
$hBorderColor     = 'black';
$hBorderStyle     = 'solid';

// Containers
$dMargin          = '5%';
$dPadding         = '11px';
$dBackgroundColor = 'white';
$dBorderSize      = '1px';
$dBorderColor     = 'black';
$dBorderStyle     = 'solid';

// Match post borders? (if same style and colors)
$match_borders = 'yes';

// Active page number
$apFontSize        = '14pt';
$apColor           = 'black';

// Comment Headers
$cStripColor  = '#FFFAC6';

// Comments
$cFontSize    = '10pt';
$cPostbgColor = 'white';
$cPostColor   = 'black';
$cBorderSize  = '0';
$cBorderColor = 'black';
$cBorderStyle = 'solid';

// Pop-ups
$popFontSize = '10pt';

// Mutilines (Text Input)
$tInputHeight          = '21px';
$tInputFontSize        = '10pt';
$tInputFontColor       = 'black';
$tInputBackgroundColor = 'white';

// Buttons
$sButtonHeight          = '20px';
$sButtonFontSize        = '9pt';
$sButtonFontColor       = 'white';
$sButtonBackgroundColor = '#DC7846';
$sButtonBorderSize      = '1px';
$sButtonBorderColor     = 'black';
$sButtonBorderStyle     = 'solid';

// Scollbars (IE only)
$useScrollbars   = 'no';
$scrollBase      = '';
$scrollArrow     = '';
$scrollFace      = '';
$scrollHighlight = '';
$scrollShadow    = '';
$scroll3D        = '';

// Chat
$ChatPostFont     = '"Courier New", "Courier", monospace';
$ChatPostFontSize = '9pt';
$ChatHeaderColor  = '#E0E0E0';



//
// Modes
//
if ($tcontrol == 'build') {

	// Fix borders for post headers
	if ($match_borders == 'yes') {
		$hBorderFixed = "$hBorderSize $hBorderSize 0 $hBorderSize";
	} else {
		$hBorderFixed = $hBorderSize;
	}

echo <<<EOF
/*
$tnotes
*/


/* Links */
a:link {
	text-decoration: none;
	color: $link;
}
a:visited {
	text-decoration: none;
	color: $link;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}
span.nolink {
	color: $link;
}
.imghover {
	padding: 1px;
	border-width: 1px;
	border-color: $dBackgroundColor;
	border-style: solid;
}
.imghover:hover {
	padding: 1px;
	border-width: 1px;
	border-color: $link;
	border-style: solid;
}
.imgthumb {
	padding: 1px;
	border-width: 1px;
	border-color: $link;
	border-style: solid;
}
.imgthumb:hover {
	padding: 1px;
	border-width: 1px;
	border-color: $dBackgroundColor;
	border-style: solid;
}


/* Tags */
html {
	margin: 0;
	padding: 0;
	color: $textColor;
	font-family: $fontFace;
	font-size: $fontSize;
}

body {
	margin: 0;
	padding: 0;
	background-color: $bgColor;
	background-image: url("$bgImage");
EOF;

if ($useScrollbars == 'yes') {
echo <<<EOF
	scrollbar-arrow-color: $scrollArrow;
	scrollbar-base-color: $scrollBase;
	scrollbar-face-color: $scrollFace;
	scrollbar-highlight-color: $scrollHighlight;
	scrollbar-shadow-color: $scrollShadow;
	scrollbar-3d-light-color: $scroll3D;
EOF;
}

echo <<<EOF
}
hr {
	/* Used only for non-CSS browsers */
	display: none;
}
form {
	margin: 0;
	padding: 0;
}
p.indent {
	text-indent: 2em;
	padding: 0;
	font-size: $fontSize;
}
p.subtext {
	text-indent: 0;
	margin: 0 0 0 1em;
	font-size: $fontSize;
	font-style: italic;
}


/* Header */
#banner {
	background-color: $bgColor;
	background-image: url("Banana/BannerBack.png");
	border-width: 0 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}
#menubar {
	width: 100%;
	padding: 2px 10px 2px 10px;
	background-color: $mBackgroundColor;
	background-image: url("Banana/MenuBack.png");
	font-size: $mFontSize;
	border-width: 0 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}
#options {
	width: 100%;
	padding: 7px 10px 7px 10px;
	background-color: $hBackgroundColor;
	background-image: url("Banana/HeaderBack.png");
	font-size: $mFontSize;
	border-width: 0 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}
span.newmail {
	/* New messages! */
	font-weight: bold;
}
#adminbar {
	/* FIX: Leftover admin stuff */
	padding: 3px 10px 3px 10px;
	background-color: $mBackgroundColor;
	background-image: url("Banana/MenuBack.png");
	font-size: $mFontSmallSize;
	border-width: 0 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}
#notice {
	/* Board-specific drabble */
	padding: 0 10px 0 10px;
}


/* Content */
.pages {
	/* Styles for page navigators */
	margin: 0 $pMargin 0 $pMargin;
	text-align: center;
	font-size: $pFontSize;
	font-weight: bold;
}
.activepage {
	/* Current page number in page navigators */
	color: $apColor;
	font-size: $apFontSize;
	font-weight: normal;
}
#contentmain {
	/* Container for all content on all pages except pop-ups */
	margin: 0 $dMargin 0 $dMargin;
}
.postheader {
	/* Header for each post */
	padding: 5px;
	text-align: left;
	color: $hTextColor;
	background-color: $hBackgroundColor;
	background-image: url("Banana/HeaderBack.png");
	font-size: $hFontSize;
	border-width: $hBorderFixed;
	border-color: $hBorderColor;
	border-style: $hBorderStyle;
}
.postmain {
	/* Container for each post */
	vertical-align: top;
	padding: $dPadding;
	background-color: $dBackgroundColor;
	border-width: $dBorderSize;
	border-color: $dBorderColor;
	border-style: $dBorderStyle;
}
.postdata {
	/* Picture for each post */
	text-align: center;
	background-color: $cPostbgColor;
}
.commentmain {
	/* Container for each comment */
	margin: 0 0 0 10px;
}
.commentheader {
	/* Header for each comment */
	padding: 1px 0 2px 10px;
	text-align: left;
	background-color: $cStripColor;
	font-size: 13px;
	border-width: $cBorderSize;
	border-color: $cBorderColor;
	border-style: $cBorderStyle;
}
.commentinfo {
	/* Inherits from commentheader */
	color: $dStamp;
	font-size: 11px;
}
.commentdata {
	/* The actual comments */
	margin-bottom: 8px;
	padding: 3px 10px 3px 10px;
	text-align: left;
	vertical-align: top;
	color: $cPostColor;
	background-color: $cPostbgColor;
	font-size: $cFontSize;
	border-width: 0 $cBorderSize $cBorderSize $cBorderSize;
	border-color: $cBorderColor;
	border-style: $cBorderStyle;
}
#footer {
	/* Copyright */
	padding: 8px;
	text-align: right;
	background-color: $mBackgroundColor;
	background-image: url("Banana/MenuBack.png");
	font-size: 10px;
	border-width: $mBorderSize 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}


/* Menus and Config */
.header {
	/* General header (should follow PostHeader styles) */
	text-align: center;
	margin: 0;
	padding: 3px 0 3px 0;
	color: $hTextColor;
	background-color: $hBackgroundColor;
	background-image: url("Banana/HeaderBack.png");
	font-weight: normal;
	font-size: $hFontSize;
	border-width: $hBorderFixed;
	border-color: $hBorderColor;
	border-style: $hBorderStyle;
}
.infotable {
	/* Simple formatting for generic tables */
	padding: 1% 5% 1% 5%;
	color: $hTextColor;
	background-color: $cPostbgColor;
	font-size: $hFontSize;
	border-width: $dBorderSize;
	border-color: $dBorderColor;
	border-style: $dBorderStyle;
}
.infomain {
	/* Style for admin panel tables */
	width: 100%;
}
p.infonote {
	/* General information at the top of infotables */
	padding: 1em;
	text-indent: 30pt;
	text-align: justify;
	border-width: $hBorderSize;
	border-color: $hBorderColor;
	border-style: $hBorderStyle;
}
.infoask {
	/* Admin panel options */
	/* NOTE: IE doesn't like percentages! */
	width: 200px;
	text-align: right;
	vertical-align: top;
	padding-right: 20px;
}
.infoenter {
	/* Admin panel form data */
	/* NOTE: IE doesn't like percentages! */
	text-align: left;
	vertical-align: top;
}
.txtinput {
	color: $tInputFontColor;
	font-size: $tInputFontSize;
	background-color: $tInputBackgroundColor;
}
.submit {
	color: $sButtonFontColor;
	background: $sButtonBackgroundColor;
	font-size: $sButtonFontSize;
	border-color: $sButtonBorderColor;
	border-style: $sButtonBorderStyle;
	border-width: $sButtonBorderSize;
}
.multiline {
	color: $tInputFontColor;
	font-size: $tInputFontSize;
	background-color: $tInputBackgroundColor;
}


/* Popup stuff, may be smaller than normal pages */

/* Tags */
.pheader {
	/* General header (should follow PostHeader styles) */
	text-align: center;
	padding: 4px;
	color: $hTextColor;
	background-color: $hBackgroundColor;
	background-image: url("Banana/HeaderBack.png");
	font-size: $popFontSize;
	border-width: $hBorderFixed;
	border-color: $hBorderColor;
	border-style: $hBorderStyle;
}
.pinfotable {
	/* Simple formatting for generic tables */
	padding: 1% 5% 1% 5%;
	color: $hTextColor;
	background-color: $cPostbgColor;
	font-size: $popFontSize;
	border-width: $dBorderSize;
	border-color: $dBorderColor;
	border-style: $dBorderStyle;
}
.pinfoask {
	/* Admin panel options */
	text-align: right;
	vertical-align: top;
	padding-right: 10px;
	font-weight: bold;
}
.pinfoenter {
	/* Admin panel form data */
	text-align: left;
	vertical-align: top;
}
.ptxtinput {
	height: $tInputHeight;
	color: $tInputFontColor;
	font-size: $tInputFontSize;
	background-color: $tInputBackgroundColor;
}
.chatinfo {
	text-align: center;
	background-color: $ChatHeaderColor;
}
.pchatdialog {
	font-family: $ChatPostFont;
	font-size: $ChatPostFontSize;
}
EOF;

} ?>
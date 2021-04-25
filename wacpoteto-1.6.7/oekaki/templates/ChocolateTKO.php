<?php
// Wacintaki Poteto template format, v1.0.2
// Original template written for Oekaki Poteto 5.x by Tokio-K

$tversion = 'wac-1.0.2';

// Control variable.  Values are 'build' or 'parse'
// 'build' will return entire template, 'parse' will define variables only
if (!isset($tcontrol)) {
	$tcontrol = 'build';
}



// Main vars
$t_name    = 'Chocolate TKO';
$t_author  = 'Tokio-K';
$t_email   = 'ecchi_tokio@yahoo.com';
$t_url     = 'http://tokio.iscute.com';
$t_comment = 'One of the color schemes used on the Suteki.nu message boards.';

$tnotes = <<<EOF
Template: $t_name
Author: $t_author
WWW: $t_url
E-Mail: $t_email
Comments: $t_comment
EOF;



// General
$bgColor   = '#A68064';
$bgImage   = '';
$fontSize  = '10pt';
$fontFace  = '"Arial", sans-serif';
$textColor = 'white';
$link      = '#EAADEA';
$aLink     = '#EAADEA';
$vLink     = '#EAADEA';
$dStamp    = '#545454';

// Menus
$mBackgroundColor = '#97694F';
$mFontSize        = '11pt';
$mFontSmallSize   = '9pt';
$mBorderSize      = '1px';
$mBorderColor     = '#A68064';
$mBorderStyle     = 'groove';

// Page numbers
$pFontSize        = '9pt';
$pMargin          = '5%';
$pBackgroundColor = '';

// Headers
$hTextColor       = 'white';
$hBackgroundColor = '#6B4226';
$hFontSize        = '10pt';
$hBorderSize      = '0';
$hBorderColor     = '#A68064';
$hBorderStyle     = 'groove';

// Containers
$dMargin          = '5%';
$dPadding         = '3px';
$dBackgroundColor = $bgColor;
$dBorderSize      = '0';
$dBorderColor     = '#A68064';
$dBorderStyle     = 'groove';

// Match post borders? (if same style and colors)
$match_borders = 'no';

// Active page number
$apFontSize        = '11pt';
$apColor           = 'white';

// Comment Headers
$cStripColor  = $bgColor;

// Comments
$cFontSize    = '10pt';
$cPostbgColor = $bgColor;
$cPostColor   = 'white';
$cBorderSize  = '0';
$cBorderColor = '#A68064';
$cBorderStyle = 'groove';

// Pop-ups
$popFontSize = '10pt';

// Mutilines (Text Input)
$tInputHeight          = '21px';
$tInputFontSize        = '10pt';
$tInputFontColor       = 'crimson';
$tInputBackgroundColor = 'white';

// Buttons
$sButtonHeight          = '20px';
$sButtonFontSize        = '9pt';
$sButtonFontColor       = 'black';
$sButtonBackgroundColor = 'white';
$sButtonBorderSize      = '1px';
$sButtonBorderColor     = 'black';
$sButtonBorderStyle     = 'dotted';

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
$ChatHeaderColor  = $hBackgroundColor;



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
	border-color: $bgColor;
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
	color: white;
	background-color: $bgColor;
	border-width: 0 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}
#menubar {
	width: 100%;
	padding: 2px 10px 2px 10px;
	background-color: $mBackgroundColor;
	font-size: $mFontSize;
	border-width: 0 0 $mBorderSize 0;
	border-color: $mBorderColor;
	border-style: $mBorderStyle;
}
#options {
	width: 100%;
	padding: 7px 10px 7px 10px;
	background-color: $bgColor;
	font-size: $mFontSize;
	border-width: 0;
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
	background-color: $bgColor;
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
	background-color: $pBackgroundColor;
	font-size: $pFontSize;
	font-weight: bold;
	border-width: $pButtonBorderSize;
	border-color: $pBorderColor;
	border-style: $pBorderStyle;
}
.activepage {
	/* Current page number in page navigators */
	color: $apColor;
	font-size: $apFontSize;
	font-weight: bold;
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
	padding: 0 10px 0 15px;
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
	font-weight: normal;
	font-size: $hFontSize;
	border-width: $hBorderFixed;
	border-color: $hBorderColor;
	border-style: $hBorderStyle;
}
.infotable {
	/* Simple formatting for generic tables */
	padding: 1% 5% 1% 5%;
	background-color: $bgColor;
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
	font-size: $popFontSize;
	border-width: $hBorderFixed;
	border-color: $hBorderColor;
	border-style: $hBorderStyle;
}
.pinfotable {
	/* Simple formatting for generic tables */
	padding: 1% 5% 1% 5%;
	color: $textColor;
	background-color: $bgColor;
	font-size: $popFontSize;
	border-width: $pBorderSize;
	border-color: $pBorderColor;
	border-style: $pBorderStyle;
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
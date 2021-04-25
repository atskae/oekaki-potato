/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

Wacintaki Poteto modifications Copyright 2004-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.2 - Last modified 2015-06-21
*/

"use strict";

// noClose() handler, in case future browsers have issues with the feature.
var W_USE_NOCLOSE = true;


function openWindow(url, w, h) {
	// Bypass Firefox 3.5 bug
	// Firefox on WinXP seems to want a minimum window size or else there will be no scrollbars.
	// Minimum size appears to differ between 3.5.x branches or depends on a particular desktop aspect ratio.  Weird.
	if (navigator.appCodeName && navigator.appCodeName == 'Mozilla') {
		if (w < 700) {
			w = 700;
		}
	}

	var options = "width=" + w + ",height=" + h + ",";
	options += "resizable=yes,scrollbars=yes,status=yes,";
	options += "menubar=yes,toolbar=yes,location=yes,directories=no";
	var newWin = window.open(url, 'newWin', options);
	newWin.focus();
}

function MM_findObj(n, d) {
	// v4.01
	var p, i, x;
	if (!d) d = document;
	if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
		d = parent.frames[n.substring(p + 1)].document;
		n = n.substring(0, p);
	}
	if (!(x = d[n]) && d.all) {
		x = d.all[n];
	}
	for (i = 0; !x && i < d.forms.length; i++) {
		x = d.forms[i][n];
	}
	for (i = 0; !x && d.layers && i < d.layers.length; i++) {
		x = MM_findObj(n, d.layers[i].document);
	}
	if (!x && d.getElementById) {
		x = d.getElementById(n);
	}
	return x;
}

// chatbox.php (Wac/Wax)
function MM_setTextOfTextfield(objName, x, newText) {
	// v3.0
	var obj = MM_findObj(objName);
	if (obj) {
		obj.value = newText;
	}
}

// Wac/Wax (editprofile.php, register.php)
function MM_validateForm() {
	// v4.0
	var i, p, q, nm, test, num, min, max;
	var errors = '';
	var args = MM_validateForm.arguments;

	for (i = 0; i < (args.length - 2); i += 3) {
		test = args[i + 2];
		val = MM_findObj(args[i]);
		if (val) {
			nm = val.name;
			if ((val = val.value) != "") {
				if (test.indexOf('isEmail') != -1) {
					p = val.indexOf('@');
					if (p < 1 || p == (val.length-1)) {
						errors += '- ' + nm + ' must contain an e-mail address.\n';
					}
				} else if (test!='R') {
					num = parseFloat(val);
					if (isNaN(val)) {
						errors += '- ' + nm + ' must contain a number.\n';
					}
					if (test.indexOf('inRange') != -1) {
						p = test.indexOf(':');
						min = test.substring(8, p);
						max = test.substring(p + 1);
						if (num < min || max < num) {
							errors += '- ' + nm + ' must contain a number between ' + min + ' and ' + max + '.\n';
						}
					}
				}
			} else if (test.charAt(0) == 'R') {
				errors += '- ' + nm + ' is required.\n';
			}
		}
	}
	if (errors) alert('The following error(s) occurred:\n' + errors);
	document.MM_returnValue = (errors == '');
}

function insertText(id, newtext) {
	// Works for IE and Mozilla, but not many others
	var box = document.getElementById(id);
	box.focus();

	if (box.createTextRange) {
		// IE (use this instead of caretPos)
		document.selection.createRange().text += newtext;
	} else if (box.setSelectionRange) {
		// Mozilla, some others
		var len = box.selectionEnd;
		box.value = box.value.substr(0, len) + newtext + box.value.substr(len);
		box.setSelectionRange(len + newtext.length, len + newtext.length);
	} else {
		// DOM
		box.value += newtext;
	}
}

function display_block_when(change, check, value) {
	// Show change if check == value, otherwise hide
	var set_change = document.getElementById(change);
	var set_check = document.getElementById(check);

	if (set_check.value == value) {
		set_change.style.display = 'block';
	} else {
		set_change.style.display = 'none';
	}
}

function badChars(test) {
	var badsymbols = ['\'', '\"', '$', '#', '+', '&', '=','*', ':', ';', '/', '\\', '<', '>'];

	for (var i = 0; i < badsymbols.length; i++) {
		if (test.indexOf(badsymbols[i]) != -1) {
			return badsymbols[i];
		}
	}
	return 0;
}

function checkName(name) {
	var n1 = document.getElementById(name);

	var result = badChars(n1.value);
	if (result == '\'' || result == '\"') {
		alert('Do not use double quotes or apostrophes in your name');
		return 0;
	}
	if (result) {
		alert('Do not use the following characters in your name: \' \" $ # & = * + : ; / \\ < >');
		return 0;
	}

	return 1;
}

function checkURL(line) {
	var url = document.getElementById(line);
	var result = url.value;

	// Fitler space and upper case
	result = result.replace(/^\s*(.*)/, "$1");
	result = result.replace(/(.*?)\s*$/, "$1");
	result = result.toLowerCase();

	if (result == '' || result.length < 8) {
		alert('A valid URL is required to register on this BBS.');
		return 0;
	}

	return 1;
}

function checkPass(pass1, pass2) {
	var p1 = document.getElementById(pass1);
	var p2 = document.getElementById(pass2);

	var result = badChars(p1.value);
	if (result == '\'' || result == '\"') {
		alert('Do not use double quotes or apostrophes in your password');
		return 0;
	}
	if (result) {
		alert('Do not use the following characters in your password: \' \" $ # & = * + : ; / \\');
		return 0;
	}

	if (p1.value != '' && p2.value != '') {
		if (p1.value != p2.value) {
			alert('Passwords do not match');
			return 0;
		}
	}
	return 1;
}

function maximize_app(apptype) {
	var appchange = document.getElementById(apptype);

	if (!appchange) {
		alert("Can't maximize '" + escapeHtml(apptype) + "'!\n\nEither your browser is too old, there is an error in the HTML, or Java can't load it.");
		return;
	}

	var winwidth = get_width();
	var winheight = get_height();

	if (!winwidth || !winheight) {
		alert("Sorry, I can't determine your browser's window size.\n\nThis feature won't work with your brand of web browser.");
		return;
	}

	if (winwidth > 600) {
		appchange.style.width = "100%";
		appchange.width = "100%";
	} else {
		appchange.style.width = "600px";
		appchange.width = "600";
	}

	if (winheight > 550 ) {
		appchange.style.height = winheight + "px";
		appchange.height = winheight;
	} else {
		appchange.style.height = "550px";
		appchange.height = "550";
	}
}

function size_app(apptype) {
	var appchange = document.getElementById(apptype);
	var button_x = document.getElementById("button_x");
	var button_y = document.getElementById("button_y");
	var new_x;
	var new_y;

	if (!appchange) {
		alert("Can't maximize '" + escapeHtml(apptype) + "'!\n\nEither your browser is either too old, there is an error in the HTML, or Java can't load it.");
		return false;
	}
	if (button_x && button_x.value > 0 && button_y && button_y.value > 0) {
		new_x = button_x.value;
		new_y = button_y.value;
	} else {
		return false;
	}

	if (new_x > 500 && new_x < 10000) {
		appchange.style.width = new_x + "px";
		appchange.width = new_x;
	} else {
		appchange.style.width = "500px";
		appchange.width = "500";
	}

	if (new_y > 530 && new_y < 10000) {
		appchange.style.height = new_y + "px";
		appchange.height = new_y;
	} else {
		appchange.style.height = "530px";
		appchange.height = "530";
	}

	return true;
}

function resize_chat(percentage) {
	var newheight;
	var frameid = document.getElementById("chatarea");

	if (!frameid) {
		alert("DOM error with resize_chat(): No ElementID for 'chatarea'!\n\nPlease notify the administrator. A DOM error is usually a problem with the HTML.");
		return;
	}

	if (percentage < 10 || percentage > 100) {
		alert("Value for resize_chat() is invalid. 10 to 100 expected. Do not include the % symbol.\n\nPlease notify the administrator. This is a problem with the HTML.");
		return;
	}

	newheight = get_height();
	if (newheight) {
		newheight = Math.round( (get_height() * percentage) / 100);
		frameid.style.height = newheight + "px";
	}
}

function get_width() {
	// Based on code taken from http://www.quirksmode.org
	if (self.innerWidth) {
		// All except Explorer
		return self.innerWidth;
	}
	else if (document.documentElement && document.documentElement.clientWidth) {
		// Explorer 6 Strict Mode
		return document.documentElement.clientWidth;
	}
	else if (document.body) {
		// Other browsers
		return document.body.clientWidth;
	}
	// End code credit

	return false;
}

function get_height() {
	// Based on code taken from http://www.quirksmode.org
	if (self.innerHeight) {
		// all except Explorer
		return self.innerHeight;
	}
	if (document.documentElement && document.documentElement.clientHeight) {
		// Explorer 6 Strict Mode
		return document.documentElement.clientHeight;
	}
	if (document.body) {
		// other browsers
		return document.body.clientHeight;
	}
	// End code credit

	return false;
}

function updatePreview(xMin, yMin, xMax, yMax){
	var image = document.getElementById("previewImage");
	var newx  = document.getElementById("previewWidth");
	var newy  = document.getElementById("previewHeight");

	if (!image || !newx || !newy) {
		alert("updatePreview(): previewImage, previewWidth, or previewHeight is missing!\n\nCheck the HTML for missing 'id' attributes.");
		return;
	}

	// Get input
	var testx = newx.value;
	var testy = newy.value;

	// Validate input
	if (testx < xMin) testx = xMin;
	if (testx > xMax) testx = xMax;
	if (testy < yMin) testy = yMin;
	if (testy > yMax) testy = yMax;

	image.width = testx;
	image.height = testy;
	newx.value = testx;
	newy.value = testy;
}

function toggle_checks(check_name, my_form) {
	var el;

	for (i = 0; i < my_form.elements.length; i++) {
		el = my_form.elements[i];
		if (el.name == check_name) {
			if (el.disabled == false) {
				if (el.checked == true) {
					el.checked = false;
				} else {
					el.checked = true;
				}
			}
		}
	}
}


/*
string hcp()
	: HTML character preprocessor.  Masks illegal HTML chars in comments, as HTML does not support the XML 'CDATA' tag.  Also subs symbols snooped by spambots.
	Related: hcprint()

	Input:
		{{  -> "<"
		}}  -> ">"
		{e} -> "mailto:"
		{a} -> "@"
		{d} -> "."
		{s} -> "?subject="

	Ex:
		myvar = hcp('{{a href="{e}name{a}server{d}com{s}mysubject"}}Contact{{/a}}');
*/
function hcp(work) {
	var map = {
		'{e}': "mailto:",
		'{a}': "&#064;",
		'{d}': ".",
		'{s}': "?subject=",
		'{{': "<",
		'}}': ">",
	};

	for (var key in map) {
		if (map.hasOwnProperty(key)) {
			work = work.replace(new RegExp(key, "g"), map[key]);
		}
	}

	return work;
}


/*
void hcprint()
	: writeln() wrapper for hcp()
	Dependency: hcp()

	Ex:
		hcprint('{{tag}}content{{/tag}}');
*/
function hcprint(input) {
	document.writeln(hcp(input));
}


function escapeHtml(str) {
	var div = document.createElement('div');
	div.appendChild(document.createTextNode(str));
	return div.innerHTML;
}


/*
void noBadClose()
	: Use in <script> tag to prevent accidental closing of window.
	Requires: W_USE_NOCLOSE
*/
function noBadClose() {
	if (W_USE_NOCLOSE == true) {
		window.onbeforeunload = noClose;
	}
}


/*
string/void noClose()
	: Uses DOM2 or IE DHTML to prevent accidental closing of applet window while drawing.
	Related: noBadClose()
*/
function noClose(e) {
	if (!e) e = window.event;
	var ncl_message = "If you are submitting a picture or just closing the window, click OK.";

	// DOM 2 or IE (Opera 9 not supported)
	if (e.stopPropagation) {
		// stopPropagation kills the bubbling process.
		// preventDefault negates return value (custom message).
		e.stopPropagation();
		e.preventDefault();
		return ncl_message;
	} else {
		// cancelBubble is supported by IE - this will kill the bubbling process.
		// IE requires a return value to properly cancel the bubble.
		e.cancelBubble = true;
		e.returnValue = ncl_message;
	}
}
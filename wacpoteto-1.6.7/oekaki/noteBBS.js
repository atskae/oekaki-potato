/*
OekakiPoteto 5.x Copyright 2000-2002 RanmaGuy (Theo Chakkapark, http://suteki.nu) and Marcello Bastéa-Forte (http://marcello.cellosoft.com). Modification to the files are permitted as long as my name remains on the files.

NoteBBS Copyright WonderCat Studio (http://www.wondercatstudio.com/)

Wacintaki Poteto modifications Copyright 2006-2015 Marc "Waccoon" Leveille
http://www.NineChime.com/products/
Version 1.6.2 - Last modified 2015-07-07
*/

"use strict";

//
// Globals
//
var cutomP = 0; // Static for PaletteNew()

var Palettes = new Array();
//Default
Palettes[0] = "#000000\n#FFFFFF\n#B47575\n#888888\n#FA9696\n#C096C0\n#FFB6FF\n#8080FF\n#25C7C9\n#E7E58D\n#E7962D\n#99CB7B\n#FCECE2\n#F9DDCF";
//Hadairo
Palettes[1] = "#FFF0DC\n#52443C\n#FFE7D0\n#5E3920\n#FFD6C0\n#B06A54\n#FFCBB3\n#C07A64\n#FFC0A3\n#DEA197\n#FFB7A2\n#ECA385\n#000000\n#FFFFFF";
//Red
Palettes[2] = "#FFEEF7\n#FFE6E6\n#FFCAE4\n#FFC4C4\n#FF9DCE\n#FF7D7D\n#FF6AB5\n#FF5151\n#FF2894\n#FF0000\n#CF1874\n#BF0000\n#851B53\n#800000";
//Yellow
Palettes[3] = "#FFE3D7\n#FFFFDD\n#FFCBB3\n#FFFFA2\n#FFA275\n#FFFF00\n#FF8040\n#D9D900\n#FF5F11\n#AAAA00\n#DB4700\n#7D7D00\n#BD3000\n#606000";
//Green
Palettes[4] = "#C6FDD9\n#E8FACD\n#8EF09F\n#B9E97E\n#62D99D\n#9ADC65\n#1DB67C\n#65B933\n#1A8C5F\n#4F8729\n#136246\n#2B6824\n#0F3E2B\n#004000";
//Blue
Palettes[5] = "#DFF4FF\n#C1FFFF\n#80C6FF\n#6DEEFC\n#60A8FF\n#44D0EE\n#1D56DC\n#209CCC\n#273D8F\n#2C769A\n#1C2260\n#295270\n#000040\n#003146";
//Purple
Palettes[6] = "#E9D2FF\n#E1E1FF\n#DAB5FF\n#C1C1FF\n#CE9DFF\n#8080FF\n#B366FF\n#6262FF\n#9428FF\n#3D44C9\n#6900D2\n#33309E\n#3F007D\n#252D6B";
//Brown
Palettes[7] = "#ECD3BD\n#F7E2BD\n#E4C098\n#DBC7AC\n#C8A07D\n#D9B571\n#896952\n#C09450\n#825444\n#AE7B3E\n#5E4435\n#8E5C2F\n#493830\n#5F492C";
//Character
Palettes[8] = "#FFEADD\n#DED8F5\n#FFCAAB\n#9C89C4\n#F19D71\n#CF434A\n#52443C\n#F09450\n#5BADFF\n#FDF666\n#0077D9\n#4AA683\n#000000\n#FFFFFF";
//Pastel
Palettes[9] = "#F6CD8A\n#FFF99D\n#89CA9D\n#C7E19E\n#8DCFF4\n#8CCCCA\n#9595C6\n#94AAD6\n#AE88B8\n#9681B7\n#F49F9B\n#F4A0BD\n#8C6636\n#FFFFFF";
//Sougen
Palettes[10] = "#C7E19E\n#D1E1FF\n#A8D59D\n#8DCFE0\n#7DC622\n#00A49E\n#528413\n#CBB99C\n#00B03B\n#766455\n#007524\n#5B3714\n#0F0F0F\n#FFFFFF";
//Moe
Palettes[11] = "#FFFF80\n#F4C1D4\n#EE9C00\n#F4BDB0\n#C45914\n#ED6B9E\n#FEE7DB\n#E76568\n#FFC89D\n#BD3131\n#ECA385\n#AE687E\n#0F0F0F\n#FFFFFF";
//Grayscale
Palettes[12] = "#FFFFFF\n#808080\n#EDEDED\n#6D6D6D\n#DBDBDB\n#5B5B5B\n#C9C9C9\n#494949\n#B6B6B6\n#363636\n#A4A4A4\n#121212\n#929292\n#000000";
//Main
Palettes[13] = "#000000\n#FFFFFF\n#B47575\n#888888\n#FA9696\n#C096C0\n#FFB6FF\n#8080FF\n#25C7C9\n#E7E58D\n#E7962D\n#99CB7B\n#FCECE2\n#F9DDCF";
//Wac!
Palettes[14] = "#A610A1\n#000000\n#A67BB0\n#595959\n#CC8FCA\n#999999\n#F8D2E6\n#E0E0E0\n#FAE6F1\n#00B5D9\n#AE005F\n#00CD43\n#E60087\n#FFCCCC";



/*
 * PaintBBS callback handler (isJs)
 *
 * PaintBBS uses a method call() which allows the applet to interact with the
 * HTML via the DOM.  Wacintaki/Wax does not use some of the features of
 * PaintBBS, but the applet will throw "undefined function" errors if the
 * callback is not satisfied.  So, we must return NULL for each call().
 */
function paintBBSCallback(value) {
	if (value == "start") {
		// Initializer.  No return required for PaintBBS, but some JS engines
		// require a consistent return value.
		PaletteSave();
		return null;
	}
	if (value == "check") {
		// If return value is true ('yes', 'true', '1'), this bypasses the
		// confirmation dialog box when saving images.  This allows for the
		// saving of images by clicking a form button, rather than a GUI event
		// within the applet.
		return null;
	}
	if (value == "header") {
		// HTML form probe.  Return null, since the form values are invalid.
		return null;
	}
}


//
// Palette Load/Save
//
function PaletteSave() {
	var d = document;
	var paintapp = d.getElementById("paintbbs");
	Palettes[0] = String(paintapp.getColors());
}


function PaletteNew() {
	var d = document;
	var paintapp = d.getElementById("paintbbs");
	var p = String(paintapp.getColors());
	var s = d.Palette.select;

	Palettes[s.length] = p;
	cutomP++;
	var str = prompt("Save Current Palette?", "Custom " + cutomP);
	if (str == null || str == "") {
		cutomP--;
		return;
	}
	s.options[s.length] = new Option(str);
	if (s.length < 30) {
		s.size = s.length;
	}
}


function PaletteRenew() {
	var d = document;
	var paintapp = d.getElementById("paintbbs");
	Palettes[d.Palette.select.selectedIndex] = String(paintapp.getColors());
}


function PaletteDel() {
	var p = Palettes.length;
	var s = document.Palette.select;
	var i = s.selectedIndex;
	if (i == -1) {
		return;
	}

	flag = confirm(s.options[i].text + ": Delete This Palette?");
	if (!flag) {
		return;
	}
	s.options[i] = null;
	while (i < p) {
		Palettes[i] = Palettes[i + 1];
		i++;
	}
	if (s.length < 30) {
		s.size = s.length;
	}
}


function PaletteMatrixGet() {
	var paintapp = document.getElementById("paintbbs");
	var d = document.Palette;
	var p = Palettes.length;
	var s = d.select;
	var m = d.m_m.selectedIndex;
	var t = d.setr;

	t.value = "!Palette\n" + String(paintapp.getColors());
	switch (m) {
		case 0:
		case 2:
		default:
			var n = 0;
			var c = 0;
			while (n < p) {
				if (s.options[n] != null) {
					t.value = t.value + "\n!" + s.options[n].text + "\n" + Palettes[n];
					c++;
				}
				n++;
			}
			alert(c + " palettes retrieved.\n\nCopy the palette data into a text file to save it.");
			break;
		case 1:
			alert("Current palette retrieved.\n\nCopy the palette data into a text file to save it.");
			break;
	}
	t.value = t.value + "\n!Matrix";
}


function PalleteMatrixSet() {
	var m = document.Palette.m_m.selectedIndex;
	var str = "Click OK to import the palette. Make sure you have chosen the desired import option.\n\n";
	switch (m) {
		case 0:
		default:
			flag = confirm(str + "Replace all palettes: kills and replaces the existing palettes with the new ones.");
			break;
		case 1:
			flag = confirm(str + "Replace active palette: replaces the currently selected palette only.");
			break;
		case 2:
			flag = confirm(str + "Append palette: adds the new palettes without changing the existing ones.");
			break;
	}
	if (!flag) {
		return;
	}

	PaletteSet();

	var s = document.Palette.select;
	if (s.length < 30) {
		s.size = s.length;
	} else {
		s.size = 30;
	}
}


function PaletteSet() {
	var paintapp = document.getElementById("paintbbs");
	var d = document.Palette;
	var se = d.setr.value;
	var s = d.select;
	var m = d.m_m.selectedIndex;
	var l = se.length;

	if (l < 1) {
		alert("Oops. Palette field is empty.");
		return;
	}

	var n = 0;
	var o = 0;
	var e = 0;

	switch (m) {
		case 0:
		default:
			n = s.length;
			while (n > 0) {
				n--;
				s.options[n] = null;
			}
		case 2:
			i = s.options.length;
			n = se.indexOf("!", 0) + 1;
			if (n == 0) {
				return;
			}
			while (n < l) {
				e = se.indexOf("\n#", n);
				if (e == -1) {
					return;
				}

				pn = se.substring(n, e - 1);
				o = se.indexOf("!", e);
				if (o == -1) {
					return;
				}
				pa = se.substring(e + 1, o - 2);
				if (pn != "Palette") {
					if (i >= 0) {
						s.options[i] = new Option(pn);
					}
					Palettes[i] = pa;
					i++;
				} else {
					document.paintbbs.setColors(pa);
				}
				n = o + 1;
			}
			break;
		case 1:
			n = se.indexOf("!", 0) + 1;
			if (n == 0) {
				return;
			}
			e = se.indexOf("\n#", n);
			o = se.indexOf("!", e);
			if (e >= 0) {
				pa = se.substring(e + 1, o - 2);
			}
			document.paintbbs.setColors(pa);
	}
}


//
// Palette manipulation
//
function P_Effect(v) {
	v = parseInt(v, 10);
	var x = 1;
	if (v == 255) {
		x =- 1;
	}

	var d = document.getElementById("paintbbs");
	var p = String(d.getColors()).split("\n");
	var l = p.length;
	var s = "";
	var R, G, B;

	for (var n = 0; n < l; n++) {
		R = v + (parseInt(p[n].substr(1,2), 16) * x); // Hex
		G = v + (parseInt(p[n].substr(3,2), 16) * x); // Hex
		B = v + (parseInt(p[n].substr(5,2), 16) * x); // Hex

		if (R > 255) {
			R = 255;
		} else if (R < 0) {
			R = 0;
		}

		if (G > 255) {
			G = 255;
		} else if (G < 0) {
			G = 0;
		}

		if (B > 255) {
			B = 255;
		} else if (B < 0) {
			B = 0;
		}
		s += "#" + Hex(R) + Hex(G) + Hex(B) + "\n";
	}
	d.setColors(s);
}


//
// Applet palette communication
//
function setPalette() {
	var d = document;
	var paintapp = d.getElementById("paintbbs");

	// Sets palette to applet from the list
	paintapp.setColors(Palettes[d.Palette.select.selectedIndex]);
	GetPalette();
}


function GetPalette() {
	var d = document;
	var paintapp = d.getElementById("paintbbs");
	var p = String(paintapp.getColors());

	if (p == "null" || p == "") {
		return;
	}

	var ps = p.split("\n");
	var st = d.grad.p_st.selectedIndex;
	var ed = d.grad.p_ed.selectedIndex;
	d.grad.pst.value = ps[st].substr(1, 6);
	d.grad.ped.value = ps[ed].substr(1, 6);

	setBgColor('p_st_box', d.grad.pst.value);
	setBgColor('p_ed_box', d.grad.ped.value);

	GradSelC(ps);
}


function GradSelC(ps) {
	var grad_start = document.getElementById("grad_start");
	var grad_end = document.getElementById("grad_end");

	var d = document.grad;
	var l = ps.length;
	var pe = "";
	var R, G, B;

	for (var n = 0; n < l; n++) {
		R = 255 + (parseInt(ps[n].substr(1, 2), 16) * -1); // Hex
		G = 255 + (parseInt(ps[n].substr(3, 2), 16) * -1); // Hex
		B = 255 + (parseInt(ps[n].substr(5, 2), 16) * -1); // Hex

		if (R > 255) {
			R = 255;
		} else if (R < 0) {
			R = 0;
		}

		if (G > 255) {
			G = 255;
		} else if (G < 0) {
			G = 0;
		}

		if (B > 255) {
			B = 255;
		} else if (B < 0) {
			B = 0;
		}
		pe += "#" + Hex(R) + Hex(G) + Hex(B) + "\n";
	}
	pe = pe.split("\n");

	for (n = 0; n < l; n++) {
		grad_start.options[n].style.background = ps[n];
		grad_start.options[n].style.color = pe[n];
		grad_end.options[n].style.background = ps[n];
		grad_end.options[n].style.color = pe[n];
	}
}


function ChengeGrad() {
	var d = document;
	var paintapp = d.getElementById("paintbbs");
	var st = d.grad.pst.value; // Start value text box
	var ed = d.grad.ped.value; // End value text box

	// Get start values
	var s_R = convertPal(st, 0);
	var s_G = convertPal(st, 1);
	var s_B = convertPal(st, 2);

	// Get difference / 14
	var d_R = (convertPal(ed, 0) - s_R) / 13.0;
	var d_G = (convertPal(ed, 1) - s_G) / 13.0;
	var d_B = (convertPal(ed, 2) - s_B) / 13.0;
	var p = "";

	for (var cnt = 0; cnt < 14; cnt++) {
		p += "#" + Hex(s_R) + Hex(s_G) + Hex(s_B) + "\n";
		s_R += d_R;
		s_G += d_G;
		s_B += d_B;
	}
	paintapp.setColors(p);

	d.grad.p_st.selectedIndex = 0;
	d.grad.p_ed.selectedIndex = 13;

	GetPalette();
}

function setBgColor(target, color) {
	color = parseInt(color, 16); // Hex
	if (isNaN(color)) {
		return;
	}

	var hex_st = color.toString(16);
	while (hex_st.length < 6) {
		hex_st = "0" + hex_st;
	}
	document.getElementById(target).style.background = "#" + hex_st;
}


//
// Worker functions
//
function Hex(n) {
	n = parseInt(n, 10);
	if (n < 0) {
		n *= -1;
	}
	var hex = "";
	var m;
	var k;

	while (n > 16) {
		m = n;
		if (n > 16) {
			n = parseInt(n / 16, 10);
			m -= (n * 16);
		}
		k = hexString(m);
		hex = k + hex;
	}
	k = hexString(n);
	hex = k + hex;

	while (hex.length < 2) {
		hex = "0" + hex;
	}
	return hex;
}


function hexString(n) {
	if (! isNaN(n)) {
		if (n == 10) {
			n = "A";
		} else if (n == 11) {
			n = "B";
		} else if (n == 12) {
			n ="C";
		} else if (n == 13) {
			n = "D";
		} else if (n == 14) {
			n = "E";
		} else if (n == 15) {
			n = "F";
		}
	} else {
		n = "";
	}
	return n;
}


function convertPal(col, offset) {
	var newcol = parseInt(col.substr(offset * 2, 2), 16); // Hex

	if (newcol > 255) {
		newcol = 255;
	}

	if (newcol < 0) {
		newcol = 0;
	}

	return newcol;
}
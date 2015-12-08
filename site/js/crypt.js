/* 
* Adapted from Project Nayuki, used with permission.
* (http://www.nayuki.io/page/caesar-cipher-javascript)
*/
"use strict";
/*
 * Handles the HTML input/output for Caesar cipher encryption/decryption.
 * This is the one and only entry point function called from the HTML code.
 */
function doCrypt(isDecrypt,elementID) {
	//5 iterations because why not?
	//console.log("doCrypt: Executing...")
	var shiftText = 5;

	if (!/^-?\d+$/.test(shiftText)) {
		alert("Shift is not an integer");
		return;
	}

	var key = parseInt(shiftText, 10);
	if (key < 0 || key >= 26) {
		alert("Shift is out of range");
		return;
	}

	if (isDecrypt)
		key = (26 - key) % 26;
	var preCrypt = document.getElementById(elementID).href;
	document.getElementById(elementID).href = crypt(preCrypt, key);
}

/*
 * Does the heavy lifting. No validation done, so doCrypt better do it.
 */
function crypt(input, key) {
	var output = "";
		for (var i = 0; i < input.length; i++) {
		var c = input.charCodeAt(i);
		if      (c >= 65 && c <=  90) output += String.fromCharCode((c - 65 + key) % 26 + 65);  // Uppercase
		else if (c >= 97 && c <= 122) output += String.fromCharCode((c - 97 + key) % 26 + 97);  // Lowercase
		else                          output += input.charAt(i);  // Copy
	}
	return output;
}
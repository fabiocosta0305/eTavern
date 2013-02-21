/***********************************************************************
 * scripts.js
 *
 * Computer Science 50
 * Problem Set 7
 *
 * Global JavaScript, if any.
 **********************************************************************/

function popitup(url) {
	newwindow=window.open(url,'name','titlebar=no,height=300,width=800');
	if (window.focus) {newwindow.focus()}
	return false;
}

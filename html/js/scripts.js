/***********************************************************************
 * scripts.js
 *
 * Computer Science 50
 * Problem Set 7
 *
 * Global JavaScript, if any.
 **********************************************************************/

function popitup(url) {
	newwindow=window.open(url,'name','titlebar=no,scrollbars=yes,location=0,height=300,width=800');
	if (window.focus) {newwindow.focus()}
	return false;
}

function userdata(url) {
	newwindow=window.open(url,'name','titlebar=no,scrollbars=yes,location=0,height=300,width=800');
	if (window.focus) {newwindow.focus()}
	return false;
}

function clonechar(charid) {
    window.opener.location.reload(true);window.opener.location.href='clonechar.php?charid=' + charid;window.close();
}

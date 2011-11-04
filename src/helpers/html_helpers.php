<?php

function _s($str) {
	return htmlspecialchars($str);
}

function _f($number) {
	return number_format($number, 2, ',', '');
}

function _c($number) {
	return number_format((double)$number, 2, ',', ' ') . ' &#8364;';
}

function echoHtml($value, $default='&#160;', $useDefault=false)
{
	echo ($value != '' && !$useDefault) ? htmlspecialchars($value) : $default;
}

function echoInput($name, $value, $size, $length, $id=null, $class=null)
{
	$ret =  '<input type="text" name="'.$name.'"';
	if ($id !== null) {
		$ret .= ' id="' . $id . '"';
	}
	if ($class !== null) {
		$ret .= ' class="' . $class . '"';
	}
	$ret .=	' size="'.$size.'" maxlength="'.$length.
		'" value="'.htmlspecialchars($value).'" />';
	echo $ret;
}

function _d($date)
{
	if ($date === null) {
		return "";
	}
	if (!$date instanceof DateTime) {
		throw new Exception("Argument must be of type DateTime or null");
	}
	return $date->format('j.n.Y');
}

function _t($time)
{
	if ($time === null) {
		return "";
	}
	if (!$time instanceof DateTime) {
		throw new Exception("Argument must be of type DateTime or null");
	}
	return $time->format('H:i');
}

function flashObject($movie, $width, $height, $flashVersion=6, $background="#FFFFFF")
{
	// load flashobject.js in <head>
	// SWFObject v1.5.1: Flash Player detection and embed - http://blog.deconcept.com/swfobject/

	$basename = basename($movie, '.swf');
	$contentId = 'flash-container-' . $basename;
	$movieId = 'flash-movie-' . $basename;

	//content div
	$result = sprintf('<div id="%s" style="width: %dpx; height: %dpx;" >You need flash plugin to show this content.</div>',
		$contentId, $width, $height);

	//fläsö-object
	$result .= sprintf('<script type="text/javascript">
	var d = Date();
	var fo = new FlashObject("%s", "%s", "100%%", "100%%", "%d", "%s");
	fo.addParam("allowScriptAccess", "sameDomain");
	fo.addParam("quality", "best");
	fo.addParam("scale", "noscale");
	fo.addParam("salign", "lt");
	fo.addParam("wmode", "window");
	fo.write("%s");
</script>', $movie, $movieId, $flashVersion, $background, $contentId);

	return $result;
}

?>
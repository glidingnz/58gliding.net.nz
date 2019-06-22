<?php
//make any string a simple string that is safe for variable names, filenames
function simple_string($origString, $dash_character='-', $extra='') {
	
	// strip out any single quotemarks for words like tim's
	$newString = str_replace("'", '', $origString);
	
	// replaces any non alphanumeric characters with dashes
	$newString = preg_replace("#[^A-Za-z0-9{$extra}]#", "{$dash_character}", $newString);
	
	// strips any exta consecutive dashes
	$newString = preg_replace("#$dash_character+#", "$dash_character", $newString);
	
	// remove trailing and first character underscores if they exist
	if (substr($newString, -1)=="$dash_character") $newString=substr($newString, 0, -1);
	if (substr($newString, 0, 1)=="$dash_character") $newString=substr($newString, 1);
	
	return $newString;
}

function safe_filename($string)
{
	// replaces any non alphanumeric characters with dashes
	$string = preg_replace("#[^A-Za-z0-9\.]#", "-", $string);
	return $string;
}


function randomkeys($length) {
    $pattern = "123890abhijklmnopqrscdefgt4567uvwxyz";
    $key  = $pattern{rand(0,35)};
    for($i=1;$i<$length;$i++)
    {
        $key .= $pattern{rand(0,35)};
    }
    return $key;
}
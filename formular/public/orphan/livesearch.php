<?php
//########################
// OBSOLETE AS OF NOW####
//#######################

$xmlDoc = new DOMDocument();
$xmlDoc->load("/var/www/html/utils/usernames.xml");

$usernames = $xmlDoc->getElementsByTagName('username');
$u_len = $usernames->length;
$q = $_GET["q"]; // query parameter
$hint = "";



if (strlen($q) > 0) {
    $hint = ""; // clear previous hint
    for ($i = 0; $i < $u_len; $i++) {
        $username = $usernames->item($i)->nodeValue;

        if (stripos($username, $q) !== false) {
            $hint .= $username . "<br>";
        }
    }
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint == "") {
    $response = "no suggestion";
} else {
    $response = $hint;
}

//output the response
echo $response;

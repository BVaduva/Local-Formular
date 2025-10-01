<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Formular\QueryHandler;

$queryHandler = new QueryHandler();

$usernames = $queryHandler->getAllUsernames();

if (empty($usernames)) {
    die('No usernames found.');
}

// Create an XML document
$xml = new \DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

// Create root element
$root = $xml->createElement('users');
$xml->appendChild($root);

// Append each username to the XML
foreach ($usernames as $user) {
    $usernameElement = $xml->createElement('username', htmlspecialchars($user['username']));
    $root->appendChild($usernameElement);
}

// Save the XML document to a file
$xmlFilePath = __DIR__ . '/usernames.xml';

if ($xml->save($xmlFilePath) === false) {
    die("Error saving XML file.");
}


echo "Usernames successfully written to XML file: " . $xmlFilePath;

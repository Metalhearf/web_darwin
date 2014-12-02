<?PHP
require_once 'php-on-couch/lib/couch.php';
require_once 'php-on-couch/lib/couchClient.php';
require_once 'php-on-couch/lib/couchDocument.php';
session_start();
date_default_timezone_set('Europe/Paris');

$client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', $_GET['dayName']);

try {$doc = $client->getDoc($_GET['docName']);}
catch (Exception $e)
{
	echo "Something weird happened: ".$e->getMessage()."\n";
}

$doc->finalComment = '<i class="glyphicon glyphicon-user"></i><strong> ' . $_SESSION['user'] . ' </strong>(' . date('j/m/y h:i:s') . ") : \n" . $_POST['comment'];

try {
	$response = $client->storeDoc($doc);
}
catch (Exception $e)
{
	echo "Something weird happened: ".$e->getMessage()."\n";
	return(0);
}
echo "The document is update.\n";
header('Location: ../info_candidat.php?id=' . $_GET['docName'] . '&name=' . $_GET["dayName"] . '&fullname=' . $_GET["fullname"] . '');
?>
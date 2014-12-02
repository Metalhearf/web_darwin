<?PHP
require_once 'php-on-couch/lib/couch.php';
require_once 'php-on-couch/lib/couchClient.php';
require_once 'php-on-couch/lib/couchDocument.php';
/*function add_user($userID, $userPSW, $userRoles, $userMail, $userName)*/
session_start();

$client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', '_users');

preg_match_all("/([\wéè-]+)/",  $_POST['roles'], $roles);

$doc = new stdClass();
$doc->_id = "org.couchdb.user:" .  strtolower($_POST['login']);
$doc->name = $_POST['login'];
$doc->password = $_POST['password'];
$doc->mail = $_POST['mail'];
$doc->fullname = $_POST['fullname'];
$doc->roles = $roles[1];
$doc->type = "user";

try {$docBis = $client->getDoc($doc->_id);}
catch (Exception $e)
{
	try {$response = $client->storeDoc($doc);}
	catch (Exception $e)
	{
		echo "Something weird happened: ".$e->getMessage()."\n";
		return(0);
	}
	echo "The document is stored.\n";
	header('Location: ../admin_panel.php');
}
$doc->_rev = $docBis->_rev;
try {
	$response = $client->storeDoc($doc);
}
catch (Exception $e)
{
	echo "Something weird happened: ".$e->getMessage()."\n";
	return(0);
}
echo "The document is update.\n";
header('Location: ../admin_panel.php');
?>
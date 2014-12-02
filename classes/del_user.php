<?PHP
require_once 'php-on-couch/lib/couch.php';
require_once 'php-on-couch/lib/couchClient.php';
require_once 'php-on-couch/lib/couchDocument.php';
/*function del_user($userID)*/
session_start();

$client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', '_users');
try
{
	$doc = $client->getDoc('org.couchdb.user:' . $_POST['del_user']);
}
catch (Exception $e)
{
	echo "File not found.\n";
	return (0);
}

try
{
	$result = $client->deleteDoc($doc);
}
catch (Exception $e)
{
	echo "Something weird happened: ".$e->getMessage()."\n";
	return(0);
}
header('Location: ../admin_panel.php');
?>
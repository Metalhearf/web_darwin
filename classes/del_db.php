<?PHP
	session_start();
	require_once 'php-on-couch/lib/couch.php';
	require_once 'php-on-couch/lib/couchClient.php';
	require_once 'php-on-couch/lib/couchDocument.php';
	/*function del_user($userID)*/
	$client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', 'day-' . strtolower($_POST['dbName']));

	try
	{
		$dbInfo = $client->getDatabaseInfos();
	}
	catch (Exception $e)
	{
		echo "DB not found.\n";
		return (0);
	}

	try
	{
		$result = $client->deleteDatabase();
	}
	catch (Exception $e)
	{
		echo "Something weird happened: ".$e->getMessage()."\n";
		return(0);
	}
	echo "DB deleted.\n";
	header('location: ../accueil.php');
?>
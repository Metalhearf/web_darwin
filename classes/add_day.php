<?PHP
session_start();

if (!isset($_FILES) || !isset($_FILES['csvName']) || empty($_FILES['csvName']) || !isset($_FILES['csvName']['tmp_name']) || empty($_FILES['csvName']['tmp_name']))
{
	$_SESSION['error'] = '<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Attention !</strong> Vous devez spécifier un fichier .CSV valide et non vide.
</div>';
header('Location: ../accueil.php');
return true;
}
$file_parts = pathinfo($_FILES['csvName']['name']);
if (!isset($file_parts['extension']) || empty($file_parts['extension']) || $file_parts['extension'] != 'csv')
{
	$_SESSION['error'] = '<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Attention !</strong> Vous devez spécifier un fichier d\'extension .CSV valide.
</div>';
header('Location: ../accueil.php');
return true;
}

if (!isset($_POST) || !isset($_POST['dayDate']) || empty($_POST['dayDate']) || false == preg_match( '`^\d{4}-\d{1,2}-\d{1,2}$`' , $_POST['dayDate']))
{
	$_SESSION['error'] = '<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Attention !</strong> Vous devez spécifer une date valide.
</div>';

header('Location: ../accueil.php');
return true;
}
if(!isset($_POST['dayName']) || empty($_POST['dayName']))
{
	$_SESSION['error'] = '<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Attention !</strong> Vous devez spécifier un nom de journée valide.
</div>';

header('Location: ../accueil.php');
return true;
}
if (!isset($_POST['daySize']) || empty($_POST['daySize']) || !is_numeric($_POST['daySize']))
{
	$_SESSION['error'] = '<div class="alert alert-warning alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<strong>Attention !</strong> Vous devez spécifier un effectif de candidats.
</div>';

header('Location: ../accueil.php');
return true;
}

require_once 'php-on-couch/lib/couch.php';
require_once 'php-on-couch/lib/couchClient.php';
require_once 'php-on-couch/lib/couchDocument.php';

/*function add_day($dayName, $dayDate)*/
$client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', 'day-' . strtolower($_POST['dayName']));

echo "#### Creating database ".$client->getDatabaseUri().': $result = $client->createDatabase();'."\n";
try {
	$result = $client->createDatabase();
}
catch (Exception $e)
{
	echo "It seems that something wrong happened.\n";
	exit (0);
}
echo "Database successfully created. CouchDB sent the response :".print_r($result,true)."\n";

$doc = new stdClass();
$doc->_id = "info";
$doc->date = $_POST['dayDate'];
$doc->description = $_POST['dayName'];
$doc->size = $_POST['daySize'];

$stored =0;
try {$docBis = $client->getDoc('info');}
catch (Exception $e)
{
	echo "Something weird happened: ".$e->getMessage()."\n";
	try {$response = $client->storeDoc($doc);}
	catch (Exception $e)
	{
		echo "Something weird happened: ".$e->getMessage()."\n";
		return(0);
	}
	$stored =1;
}
if ($stored == 0)
{
	$doc->_rev = $docBis->_rev;
	try {
		$response = $client->storeDoc($doc);
	}
	catch (Exception $e)
	{
		echo "Something weird happened: ".$e->getMessage()."\n";
		return(0);
	}
}



$filename = $_FILES['csvName']['tmp_name'];
$filename = substr($filename, 0, -3) . 'csv';
rename($_FILES['csvName']['tmp_name'], $filename);
$handle = fopen($filename, 'r');
$data = fgetcsv($handle, 0, ";");
$result = array();
for ($j = 0, $i = 0; isset($data[$i]); $j++)
{
	for ($k = 0; $k < 50 && isset($data[$i]); $i++, $k++)
	{
		$result[$j][] = $data[$i];
	}
}
unset($result[$j-1]);

for ($i=1; isset($result[$i]); $i++)
{
	$stored = 0;
	$doc = new stdClass();
	$doc->_id = $result[$i][20];
	$doc->mail = $result[$i][13];
	$doc->fullname = $result[$i][19] . ' ' . mb_strtoupper($result[$i][31]);
	$doc->comments = array();
	$doc->finalComment = "";

	try
	{
		$docBis = $client->getDoc($doc->_id);
	}
	catch (Exception $e)
	{
		try
		{
			$response = $client->storeDoc($doc);
		}
		catch (Exception $e)
		{
			echo "Something weird happened: ".$e->getMessage()."\n";
			return(0);
		}

		$stored = 1;
	}
	if ($stored == 0)
	{
		$doc->_rev = $docBis->_rev;
		try
		{
			$response = $client->storeDoc($doc);
		}
		catch (Exception $e)
		{
			echo "Something weird happened: ".$e->getMessage()."\n";
			return(0);
		}

	}
}

fclose($handle);
header('Location: ../accueil.php');
?>
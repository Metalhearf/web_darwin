<?PHP
echo $_POST['csvName'] . '\n';
echo $_POST['dayName'] . '\n';
$filename = $_POST['csvName'];
$handle = fopen($filename, 'r');
$data = fgetcsv($handle, 0, ";");
$result = array();
for ($j = 0, $i = 0; isset($data[$i]); $j++)
{
	for ($k = 0; $k < 50 ; $i++, $k++)
	{
		$result[$j][] = $data[$i];
	}
}
unset($result[$j-1]);


$client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . 'darwin@adrien.no-ip.org:5984','day-' . strtolower($_POST['dayName']));

for ($i=1; isset($result[$i]); $i++)
{
	$stored = 0;
	$doc = new stdClass();
	$doc->_id = $result[$i][20];
	$doc->mail = $result[$i][13];
	$doc->fullname = $result[$i][19] . ' ' . mb_strtoupper($result[$i][31]);

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
		echo "The document is stored.\n";
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
		echo "The document is update.\n";
	}
}
?>
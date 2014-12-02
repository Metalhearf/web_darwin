<?PHP
require_once 'php-on-couch/lib/couch.php';
require_once 'php-on-couch/lib/couchClient.php';
require_once 'php-on-couch/lib/couchDocument.php';

session_start();
$_SESSION['user'] = '';
$_SESSION['psw'] = '';

		$client = new couchClient ('http://' . $_POST['loginname'] . ':' . $_POST['password'] . '@adrien.no-ip.org:5984','_users');

		try {
			$databases = $client->getDatabaseInfos();
		} catch ( Exception $e) {
			/*echo "Some error happened during the request. This is certainly because your Name or Password is incorrect.\n";*/
			$_SESSION['error'] = '<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert"></button>
			<strong>Attention !</strong> Désolé, mais ce compte d\'utilisateur ou mot de passe est incorrect.
			</div>';
			header('Location: ../index.php');
			exit;
		}
		$_SESSION['user'] = $_POST['loginname'];
		$_SESSION['psw'] = $_POST['password'];

		header('Location: ../accueil.php');

?>
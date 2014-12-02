<?php
/**
 * @Author: Adrien
 * @Date:   2014-11-27 09:40:27
 * @Last Modified by:   Adrien
 * @Last Modified time: 2014-12-01 09:57:41
 */

session_start();

// Si l'utilisateur n'a aucune session active (vidage cache + tentative d'aller dans des pages necessitant la connexion) on l'envoie sur l'index
if ($_SESSION['user'] == null)
{
	header("Location: index.php");
}

require_once 'classes/php-on-couch/lib/couch.php';
require_once 'classes/php-on-couch/lib/couchClient.php';
require_once 'classes/php-on-couch/lib/couchDocument.php';

$user = $_SESSION['user'];
$psw = $_SESSION['psw'];

$client = new couchClient ('http://' . $user . ':' . $psw . '@adrien.no-ip.org:5984', '_users');
try {
	$doc = $client->getDoc('org.couchdb.user:' . $user);
}
catch (Exception $e)
{
	echo "Something weird happened: ".$e->getMessage()."\n";
	exit(0);
}

// Récupération des variables nécessaires dans la page.
$fullname = $doc->fullname;
$mail = $doc->mail;
$roles = $doc->roles;
?>


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Darwin : Admin Panel</title>
	<link rel="icon" type="img/favicon.ico" href="img/favicon.ico">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/styles.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/table_filter.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<nav class="navbar navbar-admin" role="navigation">
					<div class="navbar-header">
						<a class="navbar-brand" href="admin_panel.php">
							<img alt="logo_darwin" src="img/favicon.ico">
						</a>
						<a class="navbar-brand" href="accueil.php"><i class="glyphicon glyphicon-home"></i> Accueil</a>
					</div>

					<div class="navbar-default">
						<a class="navbar-brand" href="admin_panel.php"><i class="glyphicon glyphicon-eye-open"></i> Admin Panel</a>
					</div>

					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<span class="glyphicon glyphicon-user"></span>
									<strong><?php echo $_SESSION['user']; ?></strong>
									<span class="glyphicon glyphicon-chevron-down"></span>
								</a>

								<ul class="dropdown-menu">
									<li>
										<div class="navbar-login">
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<p class="text-center">
														<span class="glyphicon glyphicon-user icon-size"></span>
													</p>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<p class="text-left">
														<strong>
															<i class="glyphicon glyphicon-user"></i>
															<?php echo $fullname; ?>
														</strong>
													</p>
													<p class="text-left">
														<i class="glyphicon glyphicon-envelope"></i>
														<?php echo $mail; ?>
													</p>
													<p class="text-left">
														<i class="glyphicon glyphicon-eye-open"></i>
														<?php
														foreach($roles as $element)
														{
															echo $element . ' ';
														}
														?>
													</p>
												</div>
											</div>
										</div>
									</li>

									<li>
										<div class="navbar-login navbar-login-session">
											<div class="row">
												<div class="col-lg-12">
													<p>
														<a href="deconnexion_admin.php" class="btn btn-danger btn-block">Déconnexion</a>
													</p>
												</div>
											</div>
										</div>
									</li>
								</ul>

							</li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
		<!-- </div> -->

		<div class="row">
			<div class="page-header col-xs-12">
				<h2>Panneau d'Administration</h2>
				<p>Bonjour, <strong><?php echo $fullname; ?></strong> et bienvenue sur l'interface de gestion de Darwin.</p>
				<p>Via ce panneau d'administration, vous pourrez ajouter ou supprimer des utilisateurs de confiance et leur attribuer un rôle.</p>
				<div class="alert alert-warning alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					Le rôle "<strong>asset</strong>", permet d'accéder aux pages des étudiants, leur attribuer des commentaires et une photo.
				</div>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
					Le rôle "<strong>admin</strong>", permet de manager les utilisateurs de Darwin, et les Journées. Il est le seul capable d'inscrire le commentaire final de chaque étudiant.
				</div>
			</div>
		</div>

		<div id="wrap" class="text-right">
			<button class="btn btn-success" data-toggle="modal" data-target="#myModal">
				<i class="glyphicon glyphicon-plus"></i>
				Ajouter un utilisateur
			</button>
			<br />
		</div>

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 text-center cadre">
						<form method="post" action="classes/add_user.php" role="form">
							<h4>Login à créer</h4>
							<input type="text" class="form-control" placeholder="Username" name="login">

							<h4>Mot de passe</h4>
							<input type="password" class="form-control" name="password" placeholder="********">

							<h4>Nom complet</h4>
							<input type="text" class="form-control" placeholder="Charles DARWIN" name="fullname">

							<h4>Mail</h4>
							<input type="mail" class="form-control" placeholder="darwin@darwin.dar" name="mail">

							<h4>Rôles</h4>
							<input type="text" class="form-control" placeholder="asset" name="roles">

							<hr>
							<button type="submit" class="btn btn-success" data-toggle="modal" data-target="#myModal">
								<i class="glyphicon glyphicon-ok"></i>
								Ajouter l'utilisateur !
							</button>
							<button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
								<i class="glyphicon glyphicon-remove"></i>
								Annuler
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!-- TAB -->
		<div class="tab">
			<div class="col-md-offset-3 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Listing des Utilisateurs</h3>
						<div class="pull-right"> <span class="clickable filter" data-toggle="tooltip" title="Recherche avancée" data-container="body">
							<i class="glyphicon glyphicon-filter"></i>
						</span>
					</div>
				</div>
				<div class="panel-body">
					<input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Recherche..." />
				</div>
				<table class="table table-hover" id="dev-table">
					<thead>
						<tr>
							<th>Login</th>
							<th>Rôle Principal</th>
							<th>Nom</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$all_docs = $client->getAllDocs();

						foreach ($all_docs->rows as $row)
						{
							if (strncmp($row->id, "org.couchdb.user:", 17) == 0)
							{
								$test = new couchClient ('http://' . $user . ':' . $psw . '@adrien.no-ip.org:5984', "_users");
								$content = $test->getDoc($row->id);
								echo '<form name="form" method="post" action="classes/del_user.php">
								<tr>
									<td>' . $content->name . '</td>
									<td>' . $content->roles[0]. '</td>
									<td>' . $content->fullname . '</td>
									<td>
										<button class="btn btn-danger btn" name="del_user" value="' . $content->name. '">
											<i class="glyphicon glyphicon-trash"></i>  Supprimer
										</button>
									</td>
								</tr>
							</form>';
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

</div>
</div>
</body>
</html>
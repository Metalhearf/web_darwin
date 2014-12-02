<?php
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
  <title>Darwin's Theory : Infos <?php echo $_GET['name']; ?></title>
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
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <nav class="navbar navbar-classic" role="navigation">

          <div class="navbar-header">
            <a class="navbar-brand" href="accueil.php">
              <img alt="logo_darwin" src="img/favicon.ico">
            </a>
            <a class="navbar-brand" href="accueil.php"> <i class="glyphicon glyphicon-home"></i> Accueil</a>
          </div>

          <?php
          $compte = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984','_users');
          $compte = $compte->getDoc("org.couchdb.user:" . $_SESSION['user']);
          if (strcmp("admin", $compte->roles[0]) == 0)
          {
           echo '<div class="navbar-default">
                <a class="navbar-brand" href="admin_login.php"><i class="glyphicon glyphicon-eye-open"></i> Admin Panel</a>
          </div>';
          }
          ?>

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
                        <p><a href="deconnexion.php" class="btn btn-danger btn-block">Déconnexion</a></p>
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

    <div class="row">
      <div class="page-header col-lg-12 col-md-12 col-sm-12">
        <h1><?php echo $_GET['name']; ?></h1>
      </div>
    </div>

    <!-- TAB -->
    <div class="tab">
      <div class="col-md-offset-3 col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Informations de DAYNAME</h3>
            <div class="pull-right"> <span class="clickable filter" data-toggle="tooltip" title="Recherche avancée" data-container="body"> <i class="glyphicon glyphicon-filter"></i> </span> </div>
          </div>
          <div class="panel-body">
            <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Recherche..." />
          </div>
          <table class="table table-hover zebra-striped" id="dev-table">
            <thead>
              <tr>
                <th>Photo</th>
                <th>id</th>
                <th>Nom Prénom</th>
                <th>Mail</th>
              </tr>
            </thead>
            <tbody>

              <?php
              $client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', $_GET['name']);
              $doc = $client->getAllDocs();
              foreach($doc->rows as $row){
               if (strncmp($row->id, "info", 4) != 0){
                try{$candidat = $client->getDoc($row->id);}
                catch(Exception $e)
                {
                 echo "Something weird happened: ".$e->getMessage()."\n";
                 exit(1);
               }
               echo ' <tr>
               <td>
                <img src="img/favicon.png" alt="photo" class="img-circle" height="50" width="50">
              </td>
              <td>' . $candidat->_id . '</td>
              <td><a href="info_candidat.php?id=' . $candidat->_id . '&name=' . $_GET["name"] .'&fullname=' . $candidat->fullname . '">' . $candidat->fullname . '</a></td>
              <td><a href="mailto:' . $candidat->mail . '">' . $candidat->mail . '</a></td>
            </tr>';
          }
        }
        ?>


      </tbody>
    </table>
  </div>
</div>
</div>
</div>
</div>
</body>
</html>

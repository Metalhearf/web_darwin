<?php
session_start();

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

$fullname = $doc->fullname;
$mail = $doc->mail;
$roles = $doc->roles;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Darwin's Theory : Accueil</title>
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
  <?php
  $client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984','user');
  $doc = $client->listDatabases();
  ?>
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
                        <p class="text-center"> <span class="glyphicon glyphicon-user icon-size"></span> </p>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="text-left">  <i class="glyphicon glyphicon-user"></i><strong><?php echo $fullname; ?></strong></p>
                        <p class="text-left"> <i class="glyphicon glyphicon-envelope"></i> <?php echo $mail; ?> </p>
                        <p class="text-left"> <i class="glyphicon glyphicon-eye-open"></i>
                          <?php foreach($roles as $element) { echo $element . ' '; } ?>
                        </p>
                      </div>
                    </div>
                  </div>
                </li>
                <li>
                  <div class="navbar-login navbar-login-session">
                    <div class="row">
                      <div class="col-lg-12">
                        <p> <a href="deconnexion.php" class="btn btn-danger btn-block">Déconnexion</a> </p>
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
  <div class="row">
    <div class="page-header col-lg-12 col-md-12 col-sm-12">
      <h1>Accueil Darwin</h1>
    </div>
  </div>

  <?php
  if (isset($_SESSION['error']))
  {
    echo $_SESSION['error'];
    unset($_SESSION['error']);
  }
  ?>

  <div id="wrap" class="text-right">
    <?php
    $compte = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984','_users');
    $compte = $compte->getDoc("org.couchdb.user:" . $_SESSION['user']);
    if (strcmp("admin", $compte->roles[0]) == 0)
    {
      echo '<button class="btn btn-success" data-toggle="modal" data-target="#myModal">
      <i class="glyphicon glyphicon-plus"></i> Créer une journée
    </button>';
  }
  ?>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3 text-center cadre">
        <form method="post" action="classes/add_day.php" role="form" enctype="multipart/form-data">
          <label><h4>Nom de la journée</h4></label>
          <input type="text" class="form-control" placeholder="Jour04" name="dayName">
          <label><h4>Date de la journée</h4></label>
          <label><p class="small">(Format : AAAA-MM-JJ)</p></label>
          <input type="date" class="form-control" name="dayDate">
          <label><h4>Effectif</h4></label>
          <input type="number" class="form-control" placeholder="155" name="daySize" min="0">
          <label><h4>Indiquez le fichier .CSV à importer :</h4></label>
          <input type="file" name="csvName" title="Search for a file to add">
          <hr>

          <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#myModal">
            <i class="glyphicon glyphicon-ok"></i>
            Ajouter la journée !
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

<div class="tab">
  <div class="col-md-offset-3 col-md-6">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Listing des Journées</h3>
        <div class="pull-right"> <span class="clickable filter" data-toggle="tooltip" title="Recherche avancée" data-container="body"> <i class="glyphicon glyphicon-filter"></i> </span> </div>
      </div>
      <div class="panel-body">
        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Recherche..." />
      </div>
      <table class="table table-hover" id="dev-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Nom</th>
            <th>Effectif</th>

            <?php
            $admin = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984','_users');
            $admin = $admin->getDoc("org.couchdb.user:" . $_SESSION['user']);
            if (strcmp("admin", $admin->roles[0]) == 0)
            {
              echo '<th>Gérer</th>';
            }
            ?>
            <th>Informations</th>
          </tr>
        </thead>
        <tbody>
          <?php
          for ($i = 0; isset($doc[$i]); $i++)
          {
            if (strncmp($doc[$i], "day", 3) == 0 )
            {
              $client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984',$doc[$i]);
              $content = $client->getDoc("info");
              echo '<tr>
              <form name="form" method="post" action="classes/del_db.php">
                <td>' . $content->date . '</td>
                <td>' . $content->description . '</td>
                <td>' . $content->size . '</td>
                ';
                if (strcmp("admin", $admin->roles[0]) == 0)
                {
                  echo	'<td><button type="buttom" class="btn btn-danger" name="dbName" value="' . $content->description . '">
                  <i class="glyphicon glyphicon-trash"></i>
                </button></td>';
              }
              echo '</form> <td>
              <a href="info_jour.php?name=' . $doc[$i] . '">
                <button type="button" name="info" class="btn btn-info">
                  <i class="glyphicon glyphicon-share-alt"></i> Voir ce jour
                </button>
              </a>
            </td>
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
</body>
</html>

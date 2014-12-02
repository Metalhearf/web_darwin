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
<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Darwin's Theory : Infos<?php echo $_GET['name']; ?></title>
  <link rel="icon" type="img/favicon.ico" href="img/favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/styles.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/table_filter.js"></script>
  <script>
    $(function(){
      $("#sub").keydown(function(event) {
        if (event.which == 13) {
          event.preventDefault();
          $("#form1").submit();
        }
      });
    });
    $(function(){
      $("#sub2").keydown(function(event) {
        if (event.which == 13) {
          event.preventDefault();
          $("#form2").submit();
        }
      });
    });
  </script>
</head>
<body>
  <div class="container">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <nav class="navbar navbar-classic" role="navigation">
        <div class="navbar-header">
          <a class="navbar-brand" href="accueil.php">
            <img alt="logo_darwin" src="img/favicon.ico">
          </a>
          <a class="navbar-brand" href="accueil.php"><i class="glyphicon glyphicon-home"></i> Accueil</a>
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
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-user"></span> <strong><?php echo $_SESSION['user']; ?></strong> <span class="glyphicon glyphicon-chevron-down"></span> </a>
          <ul class="dropdown-menu">
            <li>
              <div class="navbar-login">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center"> <span class="glyphicon glyphicon-user icon-size"></span> </p>
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p class="text-left"> <strong> <i class="glyphicon glyphicon-user"></i> <?php echo $fullname; ?> </strong> </p>
                    <p class="text-left"> <i class="glyphicon glyphicon-envelope"></i> <?php echo $mail; ?> </p>
                    <p class="text-left"> <i class="glyphicon glyphicon-eye-open"></i>
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
    <div class="col-xs-6">
      <h1><?php $client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', $_GET['name']);
        $doc = $client->getDoc($_GET['id']);
        echo '<i class="glyphicon glyphicon-user"></i> ' . $_GET['fullname']; ?>
      </h1>
      <h5>
        <?php echo ' <i class="glyphicon glyphicon-barcode"></i> <strong>ID</strong> : ' . $doc->_id . '<br />';
        echo '<i class="glyphicon glyphicon-envelope"></i> <strong>Mail</strong> : ' . $doc->mail;
        ?>
      </h5>
    </div>
    <div class="col-xs-6 text-right">
      <h2>
        <?php echo '<i class="glyphicon glyphicon-calendar"></i> : ' . $_GET['name'];  ?>
      </h2>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- COMMENTAIRE FINAL -->
    <div class="col-md-offset-3 col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Commentaire Final</h3>
          <div class="pull-right">  </div>
        </div>
        <div class="panel-body"> </div>
        <table class="table table-hover" id="dev-table">
          <tbody>
            <?php
            $client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', $_GET['name']);
            $doc = $client->getDoc($_GET['id']);

            echo ' <tr>
            <td>' . $doc->finalComment . '</td>
          </tr>';
          ?>
        </tbody>
      </table>
      <?php
      $compte = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984','_users');
      $compte = $compte->getDoc("org.couchdb.user:" . $_SESSION['user']);
      if (strcmp("admin", $compte->roles[0]) == 0)
      {
       echo '
       <div class="panel-footer">

         <form id="form1" method="POST" action="classes/add_fcomment.php?docName=' . $_GET['id'] . '&dayName=' . $_GET['name'] . '&fullname=' . $_GET['fullname'] . '" role="form">
           <textarea id="sub" class="form-control" rows="3" name="comment" maxlength=500 placeholder="Saisir un commentaire ici."></textarea>
           <hr>
           <button class="btn btn-success" type="submit">
            <i class="glyphicon glyphicon-ok"></i>
            Envoyer mon commentaire final !
          </button>
          <button class="btn btn-danger" type="reset">
            <i class="glyphicon glyphicon-remove"></i>
            Annuler mon commentaire final
          </button>
        </form>

      </div>';
    }
    ?>
  </div>
  <!-- DEBUT TAB -->
  <div class="tab commentaire">
    <div class="col-md-12 col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Commentaires</h3>
          <div class="pull-right"> <span class="clickable filter" data-toggle="tooltip" title="Recherche avancée" data-container="body"> <i class="glyphicon glyphicon-filter"></i> </span> </div>
        </div>
        <div class="panel-body">
          <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Recherche..." />
        </div>
        <table class="table table-hover" id="dev-table">
          <tbody>
            <?php
            $client = new couchClient ('http://' . $_SESSION['user'] . ':' . $_SESSION['psw'] . '@adrien.no-ip.org:5984', $_GET['name']);
            $doc = $client->getDoc($_GET['id']);
            foreach($doc->comments as $key=>$value)
            {
              echo ' <tr>
              <td>' . $value . '</td>
            </tr>';
          }
          ?>
        </tbody>
      </table>
      <div class="panel-footer">
        <?php
        echo '<form id="form2" method="POST" action="classes/add_comments.php?docName=' . $_GET['id'] . '&dayName=' . $_GET['name'] . '&fullname=' . $_GET['fullname'] . '" role="form">
        <textarea id="sub2" class="form-control" rows="3" name="comments" maxlength=500 placeholder="Saisir un commentaire ici."></textarea>
        <hr>
        <button class="btn btn-success" type="submit">
          <i class="glyphicon glyphicon-ok"></i>
          Envoyer mon commentaire !
        </button>
        <button class="btn btn-danger" type="reset">
          <i class="glyphicon glyphicon-remove"></i>
          Annuler mon commentaire
        </button>
      </form>';
      ?>
    </div>
  </div>
</div>
</div>
</div>
<!-- FIN TAB -->

</div>
</div>
</div>
</body>
</html>
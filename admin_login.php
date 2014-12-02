<!DOCTYPE html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Darwin's Theory : Administration</title>
  <link rel="shortcut icon" type="img/favicon.ico" href="img/favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/styles.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/errors_index.js"></script>
  <script type="text/javascript" src="js/bootstrapValidator.js"></script>
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
        <nav class="navbar navbar-admin" role="navigation">
         <div class="navbar-header">
            <a class="navbar-brand" href="admin_panel.php">
              <img alt="logo_darwin" src="img/favicon.ico">
            </a>
            <a class="navbar-brand" href="accueil.php"><i class="glyphicon glyphicon-home"></i> Accueil</a>
          </div>

          <div class="navbar-default">
            <a class="navbar-brand" href="admin_login.php"><i class="glyphicon glyphicon-eye-open"></i> Admin Panel</a>
          </div>
        </nav>
      </div>
    </div>
    <form method="POST" action="./classes/check_login_admin.php" id="contactForm" class="form-horizontal">
      <fieldset>
        <div class="row">
          <div class="col-lg-offset-4 col-lg-4 col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-user"></i>
                </span>
                <input class="form-control" type="text" autofocus name="loginname" placeholder="Identifiants">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-lock"></i>
                </span>
                <input class="form-control" type="password" name="password" placeholder="Mot de Passe">
              </div>
            </div>
            <div class="form-group">
              <input class="btn btn-lg btn-primary btn-block" type="submit" value="Connexion">
            </div>
          </div>
        </div>
      </fieldset>
    </form>

    <?php
    session_start();
    if (isset($_SESSION['error']))
    {
      echo $_SESSION['error'];
      unset($_SESSION['error']);
    }
    ?>

    <div class="form-group">
      <div class="col-sm-9 text-center">
        <div id="messages"></div>
      </div>
    </div>

  </div>
</body>
</html>
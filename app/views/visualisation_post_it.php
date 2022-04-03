<!doctype html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Visualisation Post it</title>
  <link rel="stylesheet" href=".././assets/css/visualisation_post_it.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <header>
    <nav>
      <h1>
        <i class="fa fa-eye"></i>
              VISUALISATION POST IT
      </h1>
      <ul class="nav-list">
        <li>
          <form method="get" action="./index.php">
            <button type="submit" id="link" name="home" value="true">  Accueil
            </button>
          </form>
        </li>
        <li>
          <form method="get" action="./index.php">
            <button type="submit" id="logout" name="logout" value="true">Deconnexion
            </button>
          </form>
        </li>
      </ul>
    </nav>
  </header>
  <div class="container">
      <div class="tableau">
        <div class="content">
            <h2>Titre Post it : <span id="titre">
              <?php 
                echo htmlspecialchars($postit['title']);
              ?>
            </span></h2>
            <p>Propriétaire Post it : 
                <span id="Propriétaire">
                  <?php echo htmlspecialchars($user_name); ?>
                </span>
            </p>
            <p>Contenu du Post it : 
            </p>
            <p id="post-it-content">
              <?php echo htmlspecialchars($postit['content']); ?>
            </p>
            <?php
              if($edition) {
                echo '<div id="edit-post-it">
                <p hidden>'.$_POST['postit_id'].'</p><button id="edit-post">
                Editer</button><button id="delete-post">Supprimer</button></div>';
              }
            ?>
        </div>
        <div class="aside-content">
            <p>Utilisateur partagé : </p>
            <ul id="user-share">
              <?php 
                if (isset($shared_array)) {
                  foreach ($shared_array as $shared_user) {
                    echo "<li>".htmlspecialchars($shared_user['email'])."</li>";
                  }
                }
              ?>
            </ul>
        </div>
    </div>
</div>
    <!-- script js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src=".././assets/scripts/visualisation_post_it.js"></script>
</body>
</html>
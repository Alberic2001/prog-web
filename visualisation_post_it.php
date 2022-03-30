<?php
    include('./database/controller.php');
    include('./database/manager/PostitManager.php');
    include('./database/manager/SharedManager.php');
    include('./database/manager/UserManager.php');

    session_start();
    if(!isset($_SESSION['user_id'])) {
        logout();
    }
    $postit = read_one_postit($_POST['postit_id']);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Visualisation Post it</title>
  <link rel="stylesheet" href="css/visualisation_post_it.css">
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
          <a href="./accueil.php" id="link">Accueil</a>
        </li>
        <li>
          <form method="get" action="./database/index.php">
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
                echo $postit['title'];
              ?>
            </span></h2>
            <p>Propriétaire Post it : 
                <span id="Propriétaire">
                  <?php 
                    if($postit['user_id']== $_SESSION['user_id']){
                      echo $_SESSION['user_name'];
                    } else {
                      $user_name_postit = get_username_for_postit($postit['user_id']);
                      echo $user_name_postit[1];
                    }
                  ?>
                </span>
            </p>
            <p>Contenu du Post it : 
            </p>
            <p id="post-it-content">
            <?php 
                echo $postit['content'];
              ?>
            </p>
            <?php
              if(isset($_POST['edition']) && $_POST['edition'] == "true"){
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
              $user_shared = read_all_postits($_POST['postit_id']);
              foreach ($user_shared as $shared) {
                $user = get_email_for_userId($shared["user_id"]);
                echo "<li>".$user."</li>";
              }
            ?>
            </ul>
        </div>
    </div>
</div>
    <!-- script js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/visualisation_post_it.js"></script>
</body>
</html>
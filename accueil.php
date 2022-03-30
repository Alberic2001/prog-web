<?php
    include('./database/controller.php');
    include('./database/manager/PostitManager.php');
    include('./database/manager/SharedManager.php');

    session_start();
    if(!isset($_SESSION['user_id'])) {
        logout();
    }
?>
<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/accueil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Reenie+Beanie&display=swap" rel="stylesheet">
</head>

<body>
  <header>
        <nav>
            <h1>
                <i class="fa fa-home">
                </i>
                POST IT
            </h1>
            <ul class="nav-list">
                <li>
                    <a href="./ajout_post_it.php" id="link" >Ajouter Post It</a>
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
    <main>
        <section id="post-it-perso">
            <h2><i class="fa fa-user"></i>
                Mes Post-it
            </h2>
                        <div class="post-it">
                            
                            <ul id="list-perso">
                            <?php
                              $user_postit = read_all_for_one_user_postit($_SESSION['user_id']);
                              foreach ($user_postit as $postit) {
                                echo "<li><h3 class='titre'>".$postit["title"]."</h3>";
                                echo "<p hidden>".$postit["id"]."</p>";
                                echo "<p class='date'>Date de création : <br><i>".$postit["date"]."</i></p>";
                                echo "<span class='btn-group'><button class='modif-postit'>Modifier</button>
                                      <button class='suppr-postit'>Supprimer</button></span></li>";
                              }
                            ?>
                          </ul>
                       </div>
        </section>
        <section id="post-it-partage">
            <h2><i class="fa fa-share"></i>
                Post-it partagés
            </h2>
            <div class="post-it">
                <ul id="list-partage">
                    <?php
                        $user_shared = read_all_for_one_user_shared($_SESSION['user_id']);
                        foreach ($user_shared as $shared) {
                          $postit = read_one_postit($shared["postit_id"]);
                          echo '<br>';
                          echo "<li><h3 class='titre'>".$postit["title"]."</h3>";
                          echo "<p hidden>".$postit["id"]."</p>";
                          echo "<p class='date'>Date de création : <i>".$postit["date"]."</i></p></li>";
                        }
                    ?>
                </ul>
            </div>
        </section>
    </main>
    <!-- script js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/accueil.js"></script>
</body>
</html>
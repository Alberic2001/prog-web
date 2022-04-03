<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Accueil</title>
    <link rel="stylesheet" href=".././assets/css/accueil.css">
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
                    <form method="post" action="./index.php">
                        <button type="submit" id="link" name="addPostitPage" value="create">
                            Ajouter Post It
                        </button>
                    </form>
                </li>
                <li>
                    <form method="get" action="./index.php">
                        <button type="submit" id="logout" name="logout" value="true">
                            Deconnexion
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
                        if(isset($user_postit) && $user_postit['success']){
                            unset($user_postit["success"]);
                            foreach ($user_postit as $postit) {
                                echo "<li><h3 class='titre'>".htmlspecialchars($postit["title"])."</h3>";
                                echo "<p hidden>". htmlspecialchars($postit["id"]) ."</p>";
                                echo "<p class='date'>Date de création : <br><i>". htmlspecialchars(date('Y-m-d', strtotime($postit["date"])))."</i></p>";
                                echo "<span class='btn-group'><button class='modif-postit'>Modifier</button>
                                    <button class='suppr-postit'>Supprimer</button></span></li>";
                            }
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
                        if(isset($postit_shared)){
                            foreach ($postit_shared as $shared_post) {
                                echo '<br>';
                                echo "<li><h3 class='titre'>". htmlspecialchars($shared_post["title"]) ."</h3>";
                                echo "<p hidden>". htmlspecialchars($shared_post["id"]) ."</p>";
                                echo "<p class='date'>Date de création : <i>". htmlspecialchars(date('Y-m-d', strtotime($shared_post["date"]))) ."</i></p></li>";
                            }
                        }
                    ?>
                </ul>
            </div>
        </section>
    </main>
    <!-- script js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src=".././assets/scripts/accueil.js"></script>
</body>
</html>
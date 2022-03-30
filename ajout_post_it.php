<?php
    include('./database/controller.php');
    include('./database/manager/PostitManager.php');
    include('./database/manager/SharedManager.php');
    include('./database/manager/UserManager.php');

    session_start();
    if(!isset($_SESSION['user_id'])) {
        logout();
    }
    if(isset($_POST['edition']) && $_POST['edition'] == "true"){
        $postit = read_one_postit($_POST['postit_id']);
    }
?>
<!doctype html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Ajout Post it</title>
    <link rel="stylesheet" href="css/ajout_post_it.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <header>
        <nav>
            <h1>
                <i class="fa fa-plus"></i> 
                <?php
                if(isset($_POST['edition']) && $_POST['edition'] == "true"){
                    echo 'MODIFIER POST IT';
                } else {
                   echo 'AJOUT POST IT';
                }
                ?>
            </h1>
            <ul class="nav-list">
                <li>
                    <a href="./accueil.php" id="link">Accueil</a>
                </li>
                <li>
                    <form method="get" action="./database/index.php">
                        <button type="submit" id="logout" name="logout" value="true">  Deconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <form method="post" id="form_post">
            <div class="form-content base">
                <h2>
                    <i class="fa fa-edit"></i> 
                    <?php
                    if(isset($_POST['edition']) && $_POST['edition'] == "true"){
                        echo 'MODIFIER POST IT';
                    } else {
                        echo 'AJOUT POST IT';
                    }
                    ?>
                </h2>
                <div class="edit">
                    <div>
                        <label for="title">Titre du post it :</label>
                        <br>
                        <?php
                            if(isset($_POST['edition']) && $_POST['edition'] == "true"){
                                echo "<input type='text' id='title' placeholder='Ecrire le titre de mon Post it ...' name='title' value='".$postit["title"]."'/>";          
                            } else {
                                echo "<input type='text' id='title' placeholder='Ecrire le titre de mon Post it ...' name='title'/>";
                            }
                        ?>
                    </div>
                    <div class="infos">
                        <p id="owner">
                            <i class="fa fa-user"></i> 
                            Propriétaire : 
                            <?php
                                echo $_SESSION['user_name'];
                            ?>
                        </p>
                        <p id="date">
                            <i class="fa fa-calendar"></i> 
                            Date <span class="edition">d'ajout</span> : 
                            <?php 
                                if(!isset($_POST['edition'])) {
                                    date_default_timezone_set('UTC');
                                    echo  date("y/m/d");
                                } else {
                                    echo  $_POST['date'];    
                                }
                            ?>
                        </p>
                    </div>
                    <div>
                        <label for="content"><i class="fa fa-pencil"></i> Contenu :</label>
                        <br>
                        <?php
                            if(isset($_POST['edition']) && $_POST['edition'] == "true"){
                                echo "<textarea id='content' name='content' placeholder='Ecrire mon Post it ...' rows='5' cols='33' >".$postit["content"]."</textarea>";
                            } else {
                                echo "<textarea id='content' name='content' placeholder='Ecrire mon Post it ...' rows='5' cols='33'></textarea>";
                            }
                        ?>
                    </div>
                    <div class="btn-action">
                        <button type="submit" class="btn btn-first" id="btn_submit">Créer</button>
                        <br>
                        <button type="reset" class="btn btn-secondary" id="btn_reset">Réinitialiser</button>
                    </div>
                </div>
            </div>
            <div class="form-content aside">
                <div class="partage">
                    <p> <i class="fa fa-users"></i> Liste de partage </p>
                    <p>-- Selectionner les utilisateurs --</p>
                    <ul>
                        <?php
                            if(isset($_POST['edition']) && $_POST['edition'] == "true"){
                                //echo
                                $user_shared = read_all_postits($_POST['postit_id']);
                                //var_dump($user_shared);

                                /*foreach ($user_shared as $shared) {
                                  $user = get_email_for_userId($shared["user_id"]);
                                  echo "<li>".$user."</li>";
                                }*/
                                $arrayUser = read_all();
                                foreach($arrayUser as $user) {
                                    if($user["id"] != $_SESSION['user_id']){
                                        if($user["id"] != $_SESSION['user_id'] && in_array($user["email"], $user_shared)) {
                                            echo '<li>';
                                            echo "<input type='hidden' id='".$user["id"]."' value='".$user["email"]."' />";
                                            echo "<input type='checkbox' name='user_id[]' value='".$user["email"]."' checked />";
                                            echo $user["email"]."</li>";
                                        } else {
                                            echo '<li>';
                                            echo "<input type='hidden' id='".$user["id"]."' value='".$user["email"]."' />";
                                            echo "<input type='checkbox' name='user_id[]' value='".$user["email"]."' />";
                                            echo $user["email"]."</li>";
                                        }
                                    }
                                }
                            }
                        ?>
                    </ul>
                    <br>
                </div>
            </div>
        </form>
    </div>
    <!-- script js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/ajout_post_it.js"></script>
</body>

</html>
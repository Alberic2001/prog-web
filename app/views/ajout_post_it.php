<!doctype html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Ajout Post it</title>
    <link rel="stylesheet" href=".././public/css/ajout_post_it.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <header>
        <nav>
            <h1>
                <i class="fa fa-plus"></i> 
                <?php
                    if($create){
                        echo 'AJOUT POST IT';
                    } else {
                        echo 'MODIFIER POST IT';
                    }
                ?>
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
                        <button type="submit" id="logout" name="logout" value="true">  Deconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <form method="post" id="form_post">
        <?php 
            if (!$create) { 
                echo "<input type='hidden' value='". htmlspecialchars($_POST['postit_id']) ."' id='postit_id' />";
            }
        ?>
            <div class="form-content base">
                <h2>
                    <i class="fa fa-edit"></i> 
                    <?php
                        if($create){
                            echo 'AJOUT POST IT';
                        } else {
                            echo 'MODIFIER POST IT';
                        }
                    ?>
                </h2>
                <div class="edit">
                    <div>
                        <label for="title">Titre du post it :</label>
                        <br>
                        <?php
                            if($create){
                                echo "<input type='text' id='title' placeholder='Ecrire le titre de mon Post it ...' name='title'/>";
                            } else {
                                echo "<input type='text' id='title' placeholder='Ecrire le titre de mon Post it ...' name='title' value='". htmlspecialchars($postit["title"]) ."'/>";          
                            }
                        ?>
                    </div>
                    <div class="infos">
                        <p id="owner">
                            <i class="fa fa-user"></i> 
                            Propriétaire : 
                            <?= $_SESSION['user_name'] ?>
                        </p>
                        <p id="date">
                            <i class="fa fa-calendar"></i> 
                            Date <span class="edition">d'ajout</span> : 
                            <?php
                                if($create){
                                    echo date('Y-m-d');
                                } else {
                                    echo htmlspecialchars(date('Y-m-d', strtotime($postit["date"])));
                                }
                            ?>
                        </p>
                    </div>
                    <div>
                        <label for="content"><i class="fa fa-pencil"></i> Contenu :</label>
                        <br>
                        <?php
                            if ($create) {
                                echo "<textarea id='content' name='content' placeholder='Ecrire mon Post it ...' rows='5' cols='33'></textarea>";
                            } else {
                                echo "<textarea id='content' name='content' placeholder='Ecrire mon Post it ...' rows='5' cols='33' >". htmlspecialchars($postit["content"]) ."</textarea>";  
                            }
                        ?>
                    </div>
                    <div class="btn-action">
                        <button type="submit" class="btn btn-first" id="btn_submit">       
                            <?php
                                if($create){
                                    echo "Créer";
                                } else {
                                    echo "Modifier";          
                                }
                            ?>
                        </button>
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
                            if(isset($shared_array)){
                                foreach($shared_array as $shared) {
                                    echo '<li>';
                                    echo "<input type='hidden' id='".$shared["id"]."' value='". htmlspecialchars($shared["email"]) ."' />";
                                    echo "<input type='checkbox' name='user_id[]' value='". htmlspecialchars($shared["email"]) ."' ".$shared["checked"] ." class='checkbox_user' />";
                                    echo htmlspecialchars($shared["email"])."</li>";
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
    <script type="text/javascript" src=".././public/scripts/ajout_post_it.js"></script>
</body>

</html>
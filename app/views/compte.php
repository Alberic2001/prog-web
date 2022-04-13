<!doctype html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Profil</title>
    <link rel="stylesheet" href=".././assets/css/compte.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <header>
        <nav>
            <h1>
                <i class="fa fa-user"></i> Mon Profil
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
    <!--
    <div class="grid-container">
        <div class="user-content">
            <div class="user-img">
                <img src=".././assets/img/user-default.svg" alt=""/>
            </div> 
            <h2></h2>
            <div class="user-infos">
                <p>Nom</p>
                <p></p>
                <p>Prenom</p>
                <p></p>
                <p>Age</p>
                <p></p>
                <p>Email</p>
                <p></p>
                <p>Pays</p>
                <p></p>
            </div>
        </div>
        <div class="user-activity">test</div> 
        <div class="user-postit-perso">test</div>

        <div class="user-classement">test</div>
        <div class="user-change-password">
            <p>Mot de passe</p>
            <form action="./index.php" value="change-user-password">
                <p>Changer le mot de passe</p>
                <div class="btn-action">
                    <button type="submit" class="btn btn-first" id="btn_submit">       
                    </button>
                </div>
            </form>
        </div> 

        <div class="user-postit-partage">test</div>
        <div class="user-postit-favoris">test</div>
        <div class="user-top-postit">test</div>
    </div>
    <!-- script js 
    -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src=".././assets/scripts/compte.js"></script>
</body>
</html>
<?php
require('../manager/UserManager.php');
require('../models/User.php');

echo '<br />';
$createUser = new UserManager();

$user = new User('email7@gmail.com', 'password', 'prenom', 'nom', date('Y-m-d', strtotime($_POST['birthdate'])), 3);
if($user){
    echo 'user ok text : '.$user->getBirth_date() . '   <br>';
} else {
    echo 'user empty';
}

echo 44;
/*
print_r($createUser->sign_in('prenom@gmail.com', 'password'));
echo 'ok signin';*/
if(($createUser->sign_up($user))) {
    echo 'ok created';
} else {
    echo 'not created';
}
?>
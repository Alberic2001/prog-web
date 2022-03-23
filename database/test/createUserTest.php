<?php
require('../manager/UserManager.php');
// require('../models/User.php');

echo '<br />';
// $createUser = new UserManager();

$user = user_builder('email7@gmail.com', 'password','prenom', 'nom', date('Y-m-d', strtotime('2001/07/09')), 3);
if($user){
    echo 'user ok text : '.$user['birth_date'] . '   <br>';
} else {
    echo 'user empty';
}

echo 44;
/*
print_r($createUser->sign_in('prenom@gmail.com', 'password'));
echo 'ok signin';*/
$log = sign_up($user);
echo '<br />';
echo $log;
?>
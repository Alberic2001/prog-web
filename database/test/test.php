<?php

// include_once('../manager/PostitManager.php');
// include_once('../manager/UserManager.php');
include_once('../manager/SharedManager.php');
// include_once('../manager/SharedManager.php');
// include_once('../manager/UserManager.php');
// $date = new DateTime();
// $postit = postit_builder('First title', 'kadasdv agsesv asgdasveaf', $date->format('Y-m-d H:i:s'), 1);
// $log = create($postit);
// echo '<br />';

// $postit_array = read_all();
// print_r($postit_array);
// echo '<br />';

// $postit2 = read_one(3);
// print_r($postit2);
// echo '<br />';


// $postit['content'] = 'I dont know how to give name to things !!!';
// print_r(update($postit));
// echo '<br />';

// print_r($postit);

// $userConnected = sign_up(user_builder('bla@gmail.com', 'passer1234', 'bla', 'BLA', '2002/12/30'));
// print_r($userConnected);

// $userConnected = sign_in('bla@gmail.com', 'passer1234');
// print_r($userConnected);
// echo '<br />';
// $users = read_all();
// print_r($users);

// echo '<br />';
// create(shared_builder(1, 1));
// create(shared_builder(4, 3));
// create(shared_builder(5, 1));
// create(shared_builder(6, 3));

echo '<br />';
$shared = read_all();
print_r($shared);

echo '<br />';
print_r(read_all_for_one_user(3));
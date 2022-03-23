<?php

include_once('../manager/PostitManager.php');
// include_once('../manager/SharedManager.php');
// include_once('../manager/UserManager.php');
$date = new DateTime();
$postit = postit_builder('First title', 'kadasdv agsesv asgdasveaf', $date->format('Y-m-d H:i:s'), 1);
$log = create($postit);
echo '<br />';

$postit_array = read_all();
print_r($postit_array);
echo '<br />';

$postit2 = read_one(3);
print_r($postit2);
echo '<br />';


$postit['content'] = 'I dont know how to give name to things !!!';
print_r(update($postit));
echo '<br />';

print_r($postit);

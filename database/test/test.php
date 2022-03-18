<?php

include_once('../manager/PostitManager.php');
include_once('../models/Postit.php');



$db = new PostitManager();

$postit = new Postit('And another title', 'Yes we go on and on !', new DateTime(), 3);
echo '<br />';
print_r($db->create($postit));
echo '<br />';

print_r($db->read_all());
echo '<br />';

print_r($db->read_one($postit->getId()));
echo '<br />';
$postit->setContent('I dont know how to give name to things !!!')
       ->setTitle($postit->getTitle() . ' !!!');
print_r($db->update($postit));
echo '<br />';

print_r($postit);

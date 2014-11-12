<?php
include_once("includes/setup.php");
include_once("includes/authorize.php");

$wrapper = file_get_contents("templates/google/google.php");

# Get upcoming adventures
$favImg = "<img src='assets/smiley-tiny.png' width='12' height='12' "
    . "title='This adventure matches your interests' "
    . "alt='This adventure matches your interests'>";
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/select-top-upcoming.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$cmd->addParameter("number", 10);
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $wrapper = Template::block($wrapper, "UPCOMING",
        $row + array("img" => ($row['fav'] > 0 ? $favImg : "")));
}

$res['content'] = $obj['user']->insertIntoTemplate($wrapper);
?>

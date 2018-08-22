<?php
/*
 * This file is part of SocialClub (http://socialclub.sourceforge.net)
 * Copyright (C) 2004 Baron Schwartz <baron at sequent dot org>
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307  USA
 * 
 * $Id: edit_questions.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 */

include_once("question.php");

# Create and validate the form.
$form = new XMLForm("forms/adventure/edit_questions.xml");
$form->snatch();
$form->validate();

$template = file_get_contents("templates/adventure/edit_questions.php");
$template = $object->insertIntoTemplate($template);

if ($object->getStatus() == $cfg['status_id']['active']) {
    $template= Template::unhide($template, "NOTICE");
}

if ($form->isValid()) {
    # Create the question
    $question = new question();
    $question->setAdventure($cfg['object']);
    $question->setType($form->getValue("type"));
    $question->setText($form->getValue("text"));
    $question->insert();
    # Plug stuff into the page about the question that just got added
    $template = Template::replace($template, array(
        "C_QUESTION_TITLE" => $question->getText()));
    $template = Template::unhide($template, "SUCCESS");
}

# Add the most popular questions into the page for instant copying
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/question/select-most-popular.sql");
$cmd->addParameter("adventure", $cfg['object']);
$cmd->addParameter("limit", 15);
$result = $cmd->executeReader();
if (!$result->numRows()) {
    # There were no questions for this type of adventure.  Get the most
    # popular overall
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/question/select-most-popular-overall.sql");
    $cmd->addParameter("limit", 15);
    $result = $cmd->executeReader();
}
while ($row = $result->fetchRow()) {
    $template = Template::block($template, "POPULAR", array(
        "A" => $cfg['object'],
        "Q" => $row['c_uid'],
        "TEXT" => $row['c_text']));
}

# Add existing questions into the page for reference
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/question/select-by-adventure.sql");
$cmd->addParameter("adventure", $cfg['object']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $template = Template::block($template, "EXISTING", array(
        "Q" => $row['c_uid'],
        "TYPE" => $row['c_type'],
        "TEXT" => $row['c_text']));
}

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));
$res['title'] = "Edit Adventure Questions";

?>

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
 * $Id: comment.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 *
 * Allows an attendee to comment on the adventure
 */

include_once("JoinAdventure.php");

# Make sure that the user has joined the adventure and has not already commented
$allowed = (JoinAdventure::checkIfMemberIsAttending($object, $obj['user'])
    && !JoinAdventure::checkIfMemberCommented($object, $obj['user']));

$template = file_get_contents("templates/adventure/comment.php");

if ($allowed) {

    # Create the form.  Generate a list of options:
    $formTemplate = file_get_contents("forms/adventure/comment.xml");
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/generic-select.sql");
    $cmd->addParameter("table", "[_]rating");
    $result = $cmd->executeReader();
    while ($row = $result->fetchRow()) {
        $formTemplate = Template::block($formTemplate, "OPTION",
            array_change_key_case($row, 1));
    }

    $form = new XmlForm(Template::finalize($formTemplate), true);
    $form->snatch();
    $form->validate();

    if ($form->isValid()) {
        $ac = new adventure_comment();
        $ac->setText($form->getValue("comment"));
        $ac->setSubject($form->getValue("subject"));
        $ac->setRating($form->getValue("rating"));
        $ac->setAnonymous($form->getValue("anonymous"));
        $ac->setAdventure($cfg['object']);
        $ac->insert();
        $template = Template::unhide($template, "THANKS");
    }
    else {
        $template = Template::replace($template, array(
            "FORM" => $form->toString()));
    }
}
else {
    $template = Template::unhide($template, "NOTALLOWED");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Comment on Adventure";

?>

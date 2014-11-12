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
 * $Id: join.php,v 1.2 2005/08/02 02:54:11 bps7j Exp $
 *
 * Purpose: the main join page.
 */

$cfg['login_mode'] = "none";
include("includes/authorize.php");

include_once("JoinClub.php");
include_once("member.php");
include_once("address.php");
include_once("phone_number.php");
include_once("chat.php");

$wrapper = file_get_contents("templates/join/join.php");

# Create the template for the form
$formTemplate = file_get_contents("forms/join/join.xml");

# Plug in some things from the database, such as IM types
$result = $obj['conn']->query("select * from [_]chat_type");
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "chat", $row);
}
$result = $obj['conn']->query("select * from [_]phone_number_type");
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "phone_types", $row);
}

# Plug membership-type choices into the form template.
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership_type/select-current-options.sql");
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "plan", $row);
}

$form =& new XMLForm(Template::finalize($formTemplate), true);

$form->snatch();
$form->validate();

if ($form->isValid()) {
    if (JoinClub::checkIfMemberExists($form->getValue('emailAddress'))) {
        $wrapper = Template::unhide($wrapper, "ERROR");
    }
    else {
        # The form is valid, now create some objects and put them into the
        # database.
        JoinClub::createAndStoreObjects($form);

        # The page has exited already, execution doesn't continue
        exit;
    }
}

$res['content'] = Template::replace($wrapper, array(
    "FORM" => $form->toString()));


?>

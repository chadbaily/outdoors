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
 * $Id: announce.php,v 1.4 2009/03/12 03:15:58 pctainto Exp $
 */

include_once("MassEmail.php");
include_once("location.php");

# Create templates
$template = file_get_contents("templates/adventure/announce.php");
$template = $object->insertIntoTemplate($template);

$error = false;

# Ensure that the adventure's start date is in the future
if (date("Y-m-d H:i:s") >= $object->getStartDate()) {
    $template = Template::unhide($template, "START");
    $error = true;
}

# Ensure that the adventure is active.
if ($object->getStatus() != $cfg['status_id']['active']) {
    $template = Template::unhide($template, "ACTIVE");
    $error = true;
}

if (!$error && getval('continue')) {
    # Create some variables that we need
    $leader = new member();
    $leader->select($object->getOwner());
    $departure = new location();
    $departure->select($object->getDeparture());
    $destination = new location();
    $destination->select($object->getDestination());

    # Find the main category of the adventure, and use that as the email's
    # category for opt-out purposes.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/adventure/select-main-category.sql");
    $cmd->addParameter("adventure", $cfg['object']);
    $category = $cmd->executeScalar();
    if (!$category) {
        $category = 1;
    }

    # Create a template for the body of the email
    $emailBody = file_get_contents("templates/emails/adventure-announce.txt");
    $subject = "New Adventure: " . $object->getTitle();
    $emailBody = $object->insertIntoTemplate($emailBody);
    $emailBody = Template::replace($emailBody, array(
        "LEADER_NAME" => $leader->getFullName(),
        "LEADER_EMAIL" => $leader->getEmail(),
        "DEPARTURE" => $departure->getTitle(),
        "DESTINATION" => $destination->getTitle(),
        "BASEURL" => $cfg['site_url'] . $cfg['base_url']));
    MassEmail::sendMassEmail($obj['user'], $subject, $emailBody, $category);
    $template = Template::unhide($template, "SUCCESS");

}
elseif (!$error) {
    $template = Template::unhide($template, "CONFIRM");
}

$res['content'] = $template;
$res['title'] = "Announce Adventure";

?>

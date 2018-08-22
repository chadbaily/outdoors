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
 * $Id: read.php,v 1.2 2005/08/02 03:05:24 bps7j Exp $
 */

$template = file_get_contents("templates/member/read.php");

# Decide what to show, based on who's logged in.  This is in addition to the
# normal privileges that apply.  The variables are named after what they will
# allow: $showAll will allow everything to be seen (not to be confused with 
# "show this to all people").
$self = $obj['user']->equals($object);
$showAll = ($self || $obj['user']->isRootUser() 
    || $obj['user']->isInGroup("root"));
$showMost = $showAll
    || $obj['user']->isInGroup("leader")
    || $obj['user']->isInGroup("officer");

# The ultimate decision: whether to show anything at all
if ($showMost || !$object->getHidden()) {
    $template = Template::unhide($template, "ALL");

    # Insert the member's information into the template
    $template = Template::block($template, "MEMBER",
        $object->getVarArray(), false);

    # Insert the member's address into the template
    $addr = $object->getPrimaryAddress();
    if ($addr && ($showMost || !$addr->getHidden())) {
        $template = Template::block($template, "ADDRESS",
            $addr->getVarArray(), false);
        $template = Template::unhide($template, array("ADDRESS"));
        if ($self && count($object->getChildren("address", "c_owner")) > 1) {
            $template = Template::unhide($template, array("ADDR_ALL"));
        }
    }

    # Insert the member's phone numbers into the template
    $showPhones = false;
    foreach ($object->getChildren("phone_number", "c_owner") as $num) {
        if ($showMost || !$num->getHidden()) {
            $template = Template::block($template, "PHONE",
                $num->getVarArray());
            $showPhones = true;
        }
    }
    if ($showPhones) {
        $template = Template::unhide($template, array(
            "PHONES"));
        if ($self && count($object->getChildren("phone_number", "c_owner")) > 1) {
            $template = Template::unhide($template, array(
                "PHONE_ALL"));
        }
    }

    # Get a list of adventures the member participated in
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/adventure/select-by-member.sql");
    $cmd->addParameter("member", $cfg['object']);
    $cmd->addParameter("status", $cfg['status_id']['default']);
    $cmd->addParameter("end", date('Y-m-d', time()));
    $result = $cmd->executeReader();
    $count = 0;
    if ($result->numRows()) {
        while ($row = $result->fetchRow()) {
            $template = Template::block($template, "ROW",
                array_change_key_case($row, 1)
                + array("CLASS" => (($count++ % 2) ? "odd" : "even")));
        }
        $template = Template::unhide($template, "ADV");
    }

    # Insert the member's chat identities into the template
    $showChats = false;
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/chat/select.sql");
    $cmd->addParameter("member", $cfg['object']);
    $result = $cmd->executeReader();
    while ($row = $result->fetchRow()) {
        if ($showMost || !$row["c_private"]) {
            $template = Template::block($template, "CHAT", $row);
            $showChats = true;
        }
    }
    if ($showChats) {
        $template = Template::unhide($template, "IDENTITIES");
        if ($self && count($object->getChildren("chat", "c_owner")) > 1) {
            $template = Template::unhide($template, array(
                "CHAT_ALL"));
        }
    }

    # Unhide parts of the template
    if ($showMost) {
        $template = Template::unhide($template, array(
            "GENDER", "BIRTHDATE"));
    }
    if ($showAll) {
        $template = Template::unhide($template, array(
            "PASSWORD"));
    }
    if ($showAll || !$object->getEmailHidden()) {
        $template = Template::unhide($template, array(
            "EMAIL"));
    }

}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Member Details: " . $object->getFullName();

?>

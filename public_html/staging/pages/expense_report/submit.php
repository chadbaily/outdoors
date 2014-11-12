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
 * $Id: submit.php,v 1.5 2009/03/12 04:29:17 pctainto Exp $
 */

require_once("MassEmail.php");

# The lifecycle of an expense report is in its statuses.  It goes from default
# to pending to paid.  At each step a note is added.  When it goes from default
# to pending, its owner is set to root so the original owner can no longer edit
# it (and the expenses themselves are chowned root).

$object->setStatus($cfg['status_id']['pending']);
$object->setOwner($cfg['root_uid']);
$object->addNote();
$object->update();

# Chown root all the expenses
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/expense_report/accept-to-pending.sql");
$cmd->addParameter("report", $cfg['object']);
$cmd->addParameter("owner", $cfg['root_uid']);
$cmd->executeNonQuery();

# Send an email to the treasurers!
MassEmail::sendMassEmail(
    $obj['user'],
    "New expense report", 
    $obj['user']->getFullName(). " has entered a new expense "
        . "report for you to review and accept at "
        . "$cfg[site_url]$cfg[base_url]"
        . "/members/expense_report/read/$cfg[object]",
    0,
    $cfg['group_id']['treasurer']);

redirect("$cfg[base_url]/members/expense_report/read/$cfg[object]");

?>

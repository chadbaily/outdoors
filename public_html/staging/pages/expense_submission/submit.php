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
 * $Id: submit.php,v 1.2 2005/08/02 03:05:23 bps7j Exp $
 */

$template = file_get_contents("templates/expense_submission/submit.php");

if (isset($_POST['submitted'])) {
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/expense_submission/submit.sql");
    $cmd->addParameter("submission", $cfg['object']);
    $cmd->addParameter("submitted", $cfg['status_id']['submitted']);
    $cmd->executeNonQuery();
}

if ($object->getStatus() != $cfg['status_id']['default']
    || isset($_POST['submitted']))
{
    redirect("$cfg[base_url]/members/expense_submission/read/$cfg[object]");
}

$res['title'] = "Submit Expenses";
$res['content'] = $template;

?>

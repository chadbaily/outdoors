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
 * $Id: read.php,v 1.2 2005/08/02 03:05:23 bps7j Exp $
 */

$template = file_get_contents("templates/expense_submission/read.php");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/expense_submission/select-expenses.sql");
$cmd->addParameter("submission", $cfg['object']);
$result = $cmd->executeReader();
if ($result->numRows()) {
    $template = Template::unhide($template, "some");
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "expense", $row);
    }
    # Add total
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/expense_submission/select-total.sql");
    $cmd->addParameter("submission", $cfg['object']);
    $total = $cmd->executeScalar();
    $template = Template::replace($template, array("total" => $total));
}
else {
    $template = Template::unhide($template, "none");
}

$cfg['status_title'] = array_flip($cfg['status_id']);
$template = Template::replace($template, array(
    "c_created_date" => $object->getCreatedDate(),
    "status" => $cfg['status_title'][$object->getStatus()]));

$res['content'] = $template;
$res['title'] = "View Submission";

?>

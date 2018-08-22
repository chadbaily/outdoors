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
 * $Id: read.php,v 1.2 2005/08/02 03:05:22 bps7j Exp $
 */

$template = file_get_contents("templates/expense_report/read.php");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/expense_report/select-expenses.sql");
$cmd->addParameter("report", $cfg['object']);
$result = $cmd->executeReader();
if ($result->numRows()) {
    $template = Template::unhide($template, "some");
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "expense", $row);
    }
    # Add total
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/expense_report/select-total.sql");
    $cmd->addParameter("report", $cfg['object']);
    $total = $cmd->executeScalar();
    $template = Template::replace($template, array("total" => $total));
}
else {
    $template = Template::unhide($template, "none");
}

$member =& new member();
$member->select($object->getMember());
$cfg['status_title'] = array_flip($cfg['status_id']);
$template = Template::replace($template, array(
    "member" => $member->getFullName(),
    "c_created_date" => $object->getCreatedDate(),
    "status" => $cfg['status_title'][$object->getStatus()]));

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));

$res['title'] = "View Expense Report";

?>

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
 * $Id: list_owned_by.php,v 1.1 2009/11/14 22:53:30 pctainto Exp $
 */

# Create a template 
$template = file_get_contents("templates/application/list_owned_by.php");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/application/list_owned_by.sql");
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "report", $row);
}

if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = Template::replace($template, array("FORM" => $form->toString()));
$res['title'] = "Your Officer Applications";

?>

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
 * $Id: list_all.php,v 1.3 2005/08/02 02:47:31 bps7j Exp $
 *
 * This page expects some criteria for what to display: past, joined.
 * This variable comes from $_GET[criteria].
 */

include_once("location.php");

$template = file_get_contents("templates/adventure/list_all.php");

# Create a SqlCommand
$cmd = $obj['conn']->createCommand();

# Choose which query to run:
$dateFormat = "D M j, g:i A";
switch (getval('criteria')) {
    case "past":
        $cmd->loadQuery("sql/adventure/list_all-past.sql");
        $cmd->addParameter("active", $cfg['status_id']['active']);
        if (isset($_GET['all'])) {
            $cmd->addParameter("start", "1900-01-01");
        }
        $dateFormat = "M j, Y";
        $res['title'] = "Past Adventures";
        break;
    case "joined":
        $cmd->loadQuery("sql/adventure/list_all-joined.sql");
        $cmd->addParameter("member", $cfg['user']);
        $dateFormat = "M j, Y";
        $res['title'] = "Adventures You've Joined";
        break;
    case "owned":
        $cmd->loadQuery("sql/adventure/list_all-owned.sql");
        $cmd->addParameter("owner", $cfg['user']);
        $dateFormat = "M j, Y g:i A";
        $res['title'] = "Adventures You Are Leading";
        break;
    case "inactive":
        $cmd->loadQuery("sql/adventure/list_all-inactive.sql");
        $cmd->addParameter("inactive", $cfg['status_id']['inactive']);
        $res['title'] = "Inactive Adventures";
        break;
    default:
        $cmd->loadQuery("sql/adventure/list_all.sql");
        $cmd->addParameter("active", $cfg['status_id']['active']);
        $res['title'] = "All Adventures";
        $dateFormat = "M j, Y";
        # Special case (OK, it's actually the general case): allow
        # filtering a la searching.
        $form =& new XmlForm("forms/adventure/list_all.xml");
        $form->snatch();
        $template = Template::replace($template, array(
            "form" => $form->toString()));
        $critTitle = $form->getValue("title");
        $critLoc = $form->getValue("location");
        $critLeader = $form->getValue("leader");
        $critStart = $form->getValue("start");
        $critEnd = $form->getValue("end");
        if ($critTitle != "" && $critTitle != "[title]") {
            $cmd->addParameter("title", "%$critTitle%");
        }
        if ($critLoc != "" && $critLoc != "[location]") {
            $cmd->addParameter("location", "%$critLoc%");
        }
        if ($critLeader != "" && $critLeader != "[leader]") {
            $cmd->addParameter("leader", "%$critLeader%");
        }
        if ($critStart != "") {
            $cmd->addParameter("start", date("Y-m-d", strtotime($critStart)));
        }
        if ($critEnd != "") {
            $cmd->addParameter("end", date("Y-m-d", strtotime($critEnd)));
        }
        break;
}

# Format the date display differently depending on the query
$template = Template::replace($template, array("FORMAT" => $dateFormat));

# Choose which text to un-hide:
$template = Template::unhide($template,
    getval('criteria') 
        ? strtoupper(getval('criteria'))
        : "ALL");

$result = $cmd->executeReader();

if ($result->numRows()) {
    while ($row = $result->fetchRow()) {
        # Plug adventures into the template.
        $template = Template::block($template, "ROW", 
            array_change_key_case($row, 1));
    }
    $template = Template::unhide($template, "SOME");
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = $template;

?>

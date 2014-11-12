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
 * $Id: configuration.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 */

# Check that the user has correct permissions
if (!$obj['user']->isInGroup('root')) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$template = file_get_contents("templates/admin/configuration.php");
$formT = file_get_contents("forms/main/configuration.xml");

# ------------------------------------------------------------------------------
# Get the configuration variables from the database.
# ------------------------------------------------------------------------------
$result = $obj['conn']->query("select * from [_]configuration");
while ($row = $result->fetchRow()) {
    if (isset($_GET['edit']) && $_GET['edit'] == $row['c_name']) {
        # Build an edit form for the variable, inline.
        $formT = Template::replace($formT, array("type" => $row['c_type']));
        if ($row['c_type'] != "string") {
            $formT = Template::unhide($formT, "typed");
        }
        $form = new XmlForm(Template::finalize($formT), true);
        $form->setValue("val", $row['c_value']);
        $form->snatch();
        $form->validate();
        $template = Template::block($template, "row", array(
            "c_name" => $row['c_name'],
            "c_value" => $form->toString(),
            "c_type" => $row['c_type'],
            "c_description" => $row['c_description']));
        if ($form->isValid()) {
            $obj['conn']->query("update [_]configuration "
                . "set c_value = {val,char} where c_name = {name,char}",
                array ("val" => $form->getValue("val"),
                    "name" => $_GET['edit']));
        }
    }
    else {
        $template = Template::block($template, "row", array(
            "c_name" => $row['c_name'],
            "c_value" => htmlspecialchars($row['c_value']),
            "c_type" => $row['c_type'],
            "c_description" => $row['c_description']));
    }
}

$res['title'] = "Configuration";
$res['content'] = $template;

?>

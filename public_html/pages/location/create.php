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
 * $Id: create.php,v 1.2 2005/08/02 03:05:24 bps7j Exp $
 */

include_once("location.php");
include_once("location_activity.php");

$template = file_get_contents("templates/location/create.php");

# Create and validate the form.

$formTemplate = file_get_contents("forms/location/create.xml");

# Create a list of activities that this location could be associated with.
# These go into the form, not the page template
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]activity");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();

# Print the activites out in columnar format -- NOT all in one column.  How
# many columns?
$cols = 2;
$rowTemplate = Template::extract($formTemplate, "ACTIVITY_ROW");
for ($i = 0; $i < $result->numRows();) {
    $thisRow = $rowTemplate;
    for ($j = 0; $j < $cols && $row = $result->fetchRow(); ++$j, ++$i) {
        $thisRow = Template::block($thisRow, "ACTIVITY",
            array_change_key_case($row, 1)
            + array("WIDTH" => (int) (100 / $cols)));
    }
    $formTemplate = Template::replace($formTemplate,
        array("ACTIVITY_ROW" => $thisRow), true);
}

$form =& new XmlForm(Template::finalize($formTemplate), true);
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Create the location
    $object =& new location();
    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setZipCode($form->getValue("zip-code"));
    $object->insert();

    # Create location/activity associations
    foreach($form->getValue("activity") as $uid => $checked) {
        if ($checked) {
            $locAct =& new location_activity();
            $locAct->setLocation($object->getUID());
            $locAct->setActivity($uid);
            $locAct->insert();
        }
    }

    redirect("$cfg[base_url]/members/location/read/$object->c_uid");
}

# Create a list of locations and print it out for the user's handy reference.
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]location");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "LOCATIONS",
        array_change_key_case($row, 1));
}

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));
$res['title'] = "Create a Location";

?>

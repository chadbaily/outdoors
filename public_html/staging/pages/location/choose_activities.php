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
 * $Id: choose_activities.php,v 1.4 2009/03/12 03:15:58 pctainto Exp $
 */

$template = file_get_contents("templates/location/choose_activities.php");

# Get a list of all activities
$activities = array();
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]activity");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $activities[$row['c_uid']] = array_change_key_case($row, 1);
}

# Get a list of the location's activities and re-key them by ACTIVITY not by the
# c_uid
$locActs = array();
foreach ($object->getChildren("location_activity") as $key => $act) {
    # Don't assign by reference -- subtle chaos ensues.
    $locActs[$act->getActivity()] = $act;
}

# Get an array of checkboxes that the user checked
$checkboxes = postval('activities');
$posted = postval('posted');

if ($posted && !is_array($checkboxes)) {
    $checkboxes = array();
}

# Whether any modifications were made
$dirty = false;

foreach (array_keys($activities) as $key) {
    # We'll check the checkbox if the locAct exists...
    $checked = array_key_exists($key, $locActs);
    # If the location is already in this category, and the form is submitted but
    # the checkbox isn't checked, delete the locAct
    if ($checked && $posted && !in_array($key, $checkboxes)) {
        $locActs[$key]->delete(TRUE);
        $dirty = TRUE;
        $checked = false;
    }
    # If the location isn't already in the category, and the form is submitted
    # and the  checkbox is checked, add the locAct
    elseif (!array_key_exists($key, $locActs)
        && $posted
        && in_array($key, $checkboxes))
    {
        $locAct = new location_activity();
        $locAct->setLocation($cfg['object']);
        $locAct->setActivity($key);
        $locAct->insert();
        $dirty = TRUE;
        $checked = true;
    }
    # Plug the info into the template row...
    $template = Template::block($template, "ACTIVITY",
        $activities[$key]
        + array("CHECKED" => ($checked ? "checked" : "")));
}

if ($dirty) {
    # Say that the categories were updated.
    $template = Template::unhide($template, "SUCCESS");
}

# Plug it all into the templates
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Choose Activities";


?>

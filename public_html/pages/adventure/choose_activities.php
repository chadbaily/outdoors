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
 * $Id: choose_activities.php,v 1.3 2005/08/05 20:27:10 bps7j Exp $
 */

include_once("adventure_activity.php");

$template = file_get_contents("templates/adventure/choose_activities.php");

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

# Get a list of the adventure's activities and re-key it by ACTIVITY, not by
# c_uid
$advActivities = array();
foreach ($object->getChildren("adventure_activity") as $uid => $activity) {
    # Don't assign by reference.  Objects are already references.
    $advActivities[$activity->c_activity] = $activity;
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
    # We'll check the checkbox if the advActivity exists...
    $checked = array_key_exists($key, $advActivities);
    # If the advActivity already exists, and the form is submitted but the
    # checkbox isn't checked, delete the advActivity
    if ($checked && $posted && !in_array($key, $checkboxes)) {
        $advActivities[$key]->delete(TRUE);
        $dirty = TRUE;
        $checked = false;
    }
    # If the advActivity doesn't already exist, and the form is submitted and
    # the checkbox is checked, add the advActivity
    elseif (!array_key_exists($key, $advActivities)
        && $posted
        && in_array($key, $checkboxes))
    {
        $advAct =& new adventure_activity();
        $advAct->setAdventure($cfg['object']);
        $advAct->setActivity($key);
        $advAct->insert();
        $dirty = TRUE;
        $checked = true;
    }
    # Plug the info into the template row...
    $template = Template::block($template, "ACTIVITY",
        $activities[$key]
        + array("CHECKED" => ($checked ? "checked" : "")));
}

if ($dirty) {
    # Say that the advActivities were updated.
    $template = Template::unhide($template, "SUCCESS");
}

# Plug it all into the templates
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Choose Adventure Activities";

?>

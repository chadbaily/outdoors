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
 * $Id: optout.php,v 1.3 2005/08/04 21:24:16 bps7j Exp $
 */

$template = file_get_contents("templates/member/optout.php");

# Get a list of all activity categories
$cats = array();
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]activity_category ");
$cmd->addParameter("orderby", "c_uid");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $cats[$row['c_uid']] = $row;
}

# Get a list of the opts and re-key them by CATEGORY not by the c_uid
$opts = array();
foreach ($object->getChildren("optout", "c_member") as $key => $opt) {
    # Don't assign by reference -- subtle chaos ensues.
    $opts[$opt->getCategory()] = $opt;
}

# Get an array of checkboxes that the user checked
$checkboxes = postval('cats');
$posted = postval('posted');

if ($posted && !is_array($checkboxes)) {
    $checkboxes = array();
}

# Whether any modifications were made
$dirty = false;

foreach (array_keys($cats) as $key) {
    # We'll check the checkbox if the optout does not exist (the member wants to
    # get emails).
    $exists = array_key_exists($key, $opts);
    # If the member is already opted out, and the form is submitted and the
    # checkbox is checked, delete the optout
    if ($exists && $posted && in_array($key, $checkboxes)) {
        $opts[$key]->delete(TRUE);
        $dirty = TRUE;
        $exists = false;
    }
    # If the member isn't already opted out, and the form is submitted and
    # the  checkbox is unchecked, add the optout 
    elseif (!$exists
        && $posted
        && !in_array($key, $checkboxes))
    {
        $opt =& new optout();
        $opt->setMember($cfg['object']);
        $opt->setCategory($key);
        $opt->insert();
        $dirty = TRUE;
        $exists = true;
    }
    # Plug the info into the template row...
    $template = Template::block($template, "optout",
        $cats[$key]
        + array("CHECKED" => (!$exists ? "checked" : "")));
}

if ($dirty) {
    # Say that the opts were updated.
    $template = Template::unhide($template, "SUCCESS");
}

# Plug it all into the templates
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Opt Out of Emails";


?>

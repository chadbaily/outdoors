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
 * $Id: write.php,v 1.5 2009/03/12 03:16:00 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/checkout/write.php");

if ($object->getStatus() == $cfg['status_id']['default']) {
    $template = Template::unhide($template, "good");

    # Display information about the current checkout
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/checkout/select-items.sql");
    $cmd->addParameter("checkout", $cfg['object']);
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::unhide($template, "someitems");
        while ($row = $result->fetchRow()) {
            $template = Template::block($template, "item", $row);
        }
    }
    else {
        $template = Template::unhide($template, "noitems");
    }

    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/checkout/select-gear.sql");
    $cmd->addParameter("checkout", $cfg['object']);
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::unhide($template, "somegear");
        while ($row = $result->fetchRow()) {
            $template = Template::block($template, "gear", $row);
        }
    }
    else {
        $template = Template::unhide($template, "nogear");
    }

    # Add a menu of activity categories for selecting commonly checked
    # out gear
    $activityMenu = file_get_contents("forms/checkout/select-activity.xml");
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/generic-select.sql");
    $cmd->addParameter("table", "[_]activity_category");
    $cmd->addParameter("orderby", "c_title");
    $result = $cmd->executeReader();
    while ($row = $result->fetchRow()) {
        $activityMenu = Template::block($activityMenu, "option", $row);
    }
    $activityMenu = Template::replace($activityMenu, array(
        "OBJECT" => $cfg['object']));
    $activityForm = new XmlForm(Template::finalize($activityMenu), true);
    $activityForm->setValue("activity", $object->getActivity());
    $activityForm->snatch();

    # Add the commonly checked-out gear to the page
    $multiTemplate = file_get_contents("forms/checkout_gear/create-multiple.xml");
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/checkout/select-common-gear.sql");
    $cmd->addParameter("activity", $activityForm->getValue("activity"));
    $cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
    $cmd->addParameter("missing", $cfg['status_id']['missing']);
    $result = $cmd->executeReader();
    $someFreq = false;
    while ($row = $result->fetchRow()) {
        if ($row['available'] > 0) {
            $someFreq = true;
            $multiTemplate = Template::block($multiTemplate,
                array("common", "config"), $row);
        }
    }
    if ($someFreq) {
        $template = Template::unhide($template, "someFreq");
    }
    else {
        $template = Template::unhide($template, "noFreq");
    }
    $multiForm = new XmlForm(Template::finalize($multiTemplate), true);
    $multiForm->setValue("checkout", $cfg['object']);
    $multiForm->setValue("activity", $activityForm->getValue("activity"));

    # Add the one-gear-at-a-time form to the page
    $formTemplate = file_get_contents("forms/checkout_gear/create.xml");
    $cmd = $obj['conn']->createCommand();
    $cmd->addParameter("missing", $cfg['status_id']['missing']);
    $cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
    $cmd->loadQuery("sql/item_type/select-with-available.sql");
    $result = $cmd->executeReader();
    $thisCat = "";
    # Extract two templates from this template, and use them instead.
    $groupTemplate = Template::extract($formTemplate, "group");
    $gearTemplate = Template::delete($formTemplate, "group");
    $thisGroup = "";
    while ($row = $result->fetchRow()) {
        if ($thisCat != $row['cat_title']) {
            $thisCat = $row['cat_title'];
            $gearTemplate = Template::replace($gearTemplate, array(
                "types" => $thisGroup), 1);
            $thisGroup = Template::replace($groupTemplate, array(
                "cat_title" => $row['cat_title']));
        }
        $thisGroup = Template::block($thisGroup, "type", $row);
    }
    $gearTemplate = Template::replace($gearTemplate, array(
        "types" => $thisGroup), 1);

    $gearForm = new XMLForm(Template::finalize($gearTemplate), true);
    $gearForm->setValue("checkout", $cfg['object']);

    $itemForm = new XmlForm("forms/checkout_item/create.xml");
    $itemForm->setValue("checkout", $cfg['object']);

    $member = new member();
    $member->select($object->getMember());

    $template = Template::replace($template, array(
        "itemForm" => $itemForm->toString(),
        "activityForm" => $activityForm->toString(),
        "gearForm" => $gearForm->toString(),
        "multiForm" => $multiForm->toString(),
        "name" => $member->getFullName()));
}
else {
    $template = Template::unhide($template, "bad");
}

$res['content'] = $template;
$res['title'] = "Add Items to Check Out";

?>

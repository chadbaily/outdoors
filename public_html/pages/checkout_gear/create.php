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
 * $Id: create.php,v 1.4 2005/09/01 02:18:45 bps7j Exp $
 */

# Create templates
$template = file_get_contents("templates/checkout_gear/create.php");
$formTemplate = file_get_contents("forms/checkout_gear/create.xml");

if (isset($_POST['multiple'])) {

    # Add the commonly checked-out gear form, and fill it with the form
    # submission
    $multiTemplate = file_get_contents("forms/checkout_gear/create-multiple.xml");
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/checkout/select-common-gear.sql");
    $cmd->addParameter("activity", $_POST['activity']);
    $cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
    $cmd->addParameter("missing", $cfg['status_id']['missing']);
    $result = $cmd->executeReader();
    while ($row = $result->fetchRow()) {
        $multiTemplate = Template::block($multiTemplate, array("common", "config"), $row);
    }
    $multiForm =& new XmlForm(Template::finalize($multiTemplate), true);

    $multiForm->snatch();
    $multiForm->validate();

    if ($multiForm->isValid()) {
        foreach ($_POST['gear'] as $gear) {
            if ($multiForm->getValue("gear{$gear}")) {
                # Check out the quantity of the specified category of gear, with the
                # specified description.  Status is checked_out by default,
                # since there is no direct relationship to an item.
                $object =& new checkout_gear();
                $object->setCheckout($multiForm->getValue("checkout"));
                $object->setType($gear);
                $object->setStatus($cfg['status_id']['checked_out']);
                $object->setDescription($multiForm->getValue("gear{$gear}description"));
                $object->setQty($multiForm->getValue("gear{$gear}qty"));
                $object->setCheckinMember($cfg['user']);
                $object->insert();
            }
        }
        # Redirect back to the checkout itself
        redirect("$cfg[base_url]/members/checkout/write/" . $multiForm->getValue("checkout"));
    }
    else {
        $res['content'] = Template::replace($template, array(
            "form" => $multiForm->toString()));
    }
}
else {
    $avail = array();
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item_type/select-with-available.sql");
    $cmd->addParameter("missing", $cfg['status_id']['missing']);
    $cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
    $result = $cmd->executeReader();
    $thisCat = "";
    $groupTemplate = Template::extract($formTemplate, "group");
    $formTemplate = Template::delete($formTemplate, "group");
    $thisGroup = "";
    while ($row = $result->fetchRow()) {
        # Save the qty available for later
        $avail[$row['c_uid']] = $row['available'];
        if ($thisCat != $row['cat_title']) {
            $thisCat = $row['cat_title'];
            $formTemplate = Template::replace($formTemplate, array(
                "types" => $thisGroup), 1);
            $thisGroup = Template::replace($groupTemplate, array(
                "cat_title" => $row['cat_title']));
        }
        $thisGroup = Template::block($thisGroup, "type", $row);
    }
    $formTemplate = Template::replace($formTemplate, array(
        "types" => $thisGroup), 1);

    $form =& new XMLForm(Template::finalize($formTemplate), true);

    $form->snatch();
    $form->validate();

    if ($form->isValid() && $form->getValue("qty") <= $avail[$form->getValue("category")]) {
        # Add the new checkout_gear to the checkout, then redirect back to the checkout
        $object =& new checkout_gear();
        $object->setCheckout($form->getValue("checkout"));
        $object->setType($form->getValue("category"));
        $object->setDescription($form->getValue("description"));
        $object->setQty($form->getValue("qty"));
        $object->setStatus($cfg['status_id']['checked_out']);
        $object->insert();
        redirect("$cfg[base_url]/members/checkout/write/" . $form->getValue("checkout"));
    }
    elseif ($form->isValid() && $form->getValue("qty") > $avail[$form->getValue("category")]) {
        $template = Template::unhide($template, "tooMuch");
    }

    $res['content'] = Template::replace($template, array(
        "form" => $form->toString()));
}

$res['title'] = "Add Gear to Check Out";

?>

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
 * $Id: create.php,v 1.6 2009/03/12 03:16:01 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/checkout_item/create.php");

$form = new XMLForm("forms/checkout_item/create.xml");
$form->snatch();
$form->validate();

$status = 0;
$already = 0;

if ($form->isValid()) {
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item/check-can-checkout.sql");
    $cmd->addParameter("item", $form->getValue("item"));
    $cmd->addParameter("checkout", $form->getValue("checkout"));
    $cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $row = $result->fetchRow();
        $status = $row['c_status'];
        $already = $row['already'];
        if ($row['c_checkout'] != $form->getValue("checkout")
            && $row['already'] < 0
            && $status == $cfg['status_id']['checked_out'])
        {
            # The item is checked out on other checkout sheet(s), so obviously
            # they are wrong.  We'll forcibly check it in and add a note.
            do {
                # These are the same queries that are used in /checkout/check_in
                $cmd = $obj['conn']->createCommand();
                $cmd->loadQuery("sql/checkout_item/check_in.sql");
                $cmd->addParameter("checkout_item", $row['c_uid']);
                $cmd->addParameter("status", $cfg['status_id']['checked_in']);
                $cmd->executeNonQuery();
                $cmd = $obj['conn']->createCommand();
                $cmd->loadQuery("sql/checkout_item/add-note.sql");
                $cmd->addParameter("member", $cfg['user']);
                $cmd->addParameter("checkout_item", $row['c_uid']);
                $cmd->addParameter("note", "Forced checkin for checkout "
                    . $form->getValue("checkout"));
                $cmd->executeNonQuery();
                $cmd = $obj['conn']->createCommand();
                $cmd->loadQuery("sql/checkout/check_in.sql");
                $cmd->addParameter("checkout", $row['c_checkout']);
                $cmd->addParameter("checked_out",
                    $cfg['status_id']['checked_out']);
                $cmd->addParameter("checked_in",
                    $cfg['status_id']['checked_in']);
                $cmd->executeNonQuery();
            } while ($row = $result->fetchRow());
            $status = $cfg['status_id']['checked_in'];
        }
        if ($row['c_checkout'] != $form->getValue("checkout")
            && $row['already'] < 0
            && $status == $cfg['status_id']['checked_in'])
        {
            # Add the new checkout_item to the checkout, then redirect back
            # to the checkout
            $object = new checkout_item();
            $object->setCheckout($form->getValue("checkout"));
            $object->setItem($form->getValue("item"));
            $object->insert();
            redirect("$cfg[base_url]/members/checkout/write/"
                . $form->getValue("checkout"));
        }
    }
}
# Display the form and force the user to fix the mistake.
if (!$status) {
    $template = Template::unhide($template, "notfound");
}

if ($already > 0) {
    $template = Template::unhide($template, "already");
}

$res['content'] = Template::replace($template, array(
    "form" => $form->toString()));

$res['title'] = "Add an Item to Check Out";

?>

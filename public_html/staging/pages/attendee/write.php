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
 * $Id: write.php,v 1.2 2009/03/12 03:15:58 pctainto Exp $
 *
 * Allows a leader to edit an attendee record.  Really the only thing to edit is
 * the amount the attendee paid.
 */

$template = file_get_contents("templates/attendee/write.php");
$template = $object->insertIntoTemplate($template);

# Create the form.
$form = new XMLForm("forms/attendee/write.xml");

# Populate the form from the object
$form->setValue("amount-paid", $object->getAmountPaid());
$form->setValue("joined", $object->getJoinedDate());

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $object->setAmountPaid($form->getValue("amount-paid"));
    $object->setJoinedDate($form->getValue("joined"));
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

$res['content'] = Template::replace($template, array("FORM" => $form->toString()));
$res['title'] = "Edit Attendee Details";

?>

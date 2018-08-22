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
 * $Id: write.php,v 1.2 2005/08/02 02:59:47 bps7j Exp $
 */

$template = file_get_contents("templates/membership_type/write.php");

$form =& new XmlForm("forms/membership_type/write.xml");
$form->setValue("title", $object->getTitle());
$form->setValue("description", $object->getDescription());
$form->setValue("begin-date", $object->getBeginDate());
$form->setValue("expiration-date", $object->getExpirationDate());
$form->setValue("show-date", $object->getShowDate());
$form->setValue("hide-date", $object->getHideDate());
$form->setValue("units-granted", $object->getUnitsGranted());
$form->setValue("unit", $object->getUnit());
$form->setValue("unit-cost", $object->getUnitCost());
$form->setValue("total-cost", $object->getTotalCost());
$form->setValue("private", $object->getHidden());
$form->setValue("flexible", $object->getFlexible());

# Now overwrite it with any data the user submitted.
$form->snatch();
$form->validate();

if ($form->isValid()) {
    
    # Update the object
    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setHidden(intval($form->getValue("private")));
    $object->setFlexible(intval($form->getValue("flexible")));
    $object->setBeginDate($form->getValue("begin-date"));
    $object->setExpirationDate($form->getValue("expiration-date"));
    $object->setShowDate($form->getValue("show-date"));
    $object->setHideDate($form->getValue("hide-date"));
    $object->setUnitsGranted($form->getValue("units-granted"));
    $object->setUnit($form->getValue("unit"));
    $object->setUnitCost($form->getValue("unit-cost"));
    $object->setTotalCost($form->getValue("total-cost"));
    $object->update();

    # Plug in a success message
    $template = Template::unhide($template, "SUCCESS");
}

$res['content'] = Template::replace($template,
    array("FORM" => $form->toString()));
$res['title'] = "Edit Membership Type";

?>

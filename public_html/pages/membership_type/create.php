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
 * $Id: create.php,v 1.2 2005/08/02 02:59:31 bps7j Exp $
 */

$template = file_get_contents("templates/membership_type/create.php");

$form =& new XmlForm("forms/membership_type/create.xml");
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $object =& new membership_type();
    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setHidden(intval($form->getValue("private")));
    $object->setFlexible(intval($form->getValue("flexible")));
    if (!$object->getFlexible()) {
        $object->setBeginDate($form->getValue("begin-date"));
        $object->setExpirationDate($form->getValue("expiration-date"));
    }
    $object->setShowDate($form->getValue("show-date"));
    $object->setHideDate($form->getValue("hide-date"));
    $object->setUnitsGranted($form->getValue("units-granted"));
    $object->setUnit($form->getValue("unit"));
    $object->setUnitCost($form->getValue("unit-cost"));
    $object->setTotalCost($form->getValue("total-cost"));
    $object->insert();
    redirect("$cfg[base_url]/members/membership_type/read/$object->c_uid");
}

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));
$res['title'] = "Create Membership Type";


?>

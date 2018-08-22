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
 * $Id: renew.php,v 1.3 2005/08/31 00:40:05 bps7j Exp $
 *
 * Purpose: allows a current member (who may be expired) to renew his/her
 * membership.
 */

include_once("membership_type.php");
include_once("member.php");
include_once("membership.php");
include_once("Email.php");

# Find out who the user is
$cfg['login_mode'] = "partial";
include_once("includes/authorize.php");

# Create templates
$wrapper = file_get_contents("templates/join/renew.php");
$formTemplate = file_get_contents("forms/join/renew.xml");

# Plug membership-type choices into the form template.
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership_type/select-for-renewal.sql");
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "plan", $row);
}

$form =& new XMLForm(Template::finalize($formTemplate), true);

# Plug the member's phone and address information into the form, then overwrite
# it with whatever the member submits:
$address = $obj['user']->getPrimaryAddress();
$phone = $obj['user']->getPrimaryPhoneNumber();
if (is_object($address)) {
    $form->setValue("street", $address->getStreet());
    $form->setValue("city", $address->getCity());
    $form->setValue("state", $address->getState());
    $form->setValue("zip", $address->getZIP());
}
if (is_object($phone)) {
    $form->setValue("areaCode", $phone->getAreaCode());
    $form->setValue("exchange", $phone->getExchange());
    $form->setValue("number", $phone->getNumber());
}

$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Create a new membership
    $type =& new membership_type();
    $type->select($form->getValue("membership-plan"));

    $mem =& new membership();
    $mem->setOwner($cfg['root_uid']);
    $mem->setType($type->getUID());
    $mem->setBeginDate($type->getBeginDate());
    $mem->setExpirationDate($type->getExpirationDate());
    $mem->setTotalCost($type->getTotalCost());
    $mem->setUnitsGranted($type->getUnitsGranted());
    $mem->setUnit($type->getUnit());
    $mem->setMember($cfg['user']);
    $mem->insert();

    # Insert or update the address and phone numbers
    if (is_object($phone)) {
        $phone->setAreaCode($form->getValue('areaCode'));
        $phone->setExchange($form->getValue('exchange'));
        $phone->setNumber($form->getValue('number'));
        $phone->update();
    }
    else {
        $phone =& new phone_number();
        $phone->setTitle("Phone Number");
        $phone->setPrimary(1);
        $phone->setAreaCode($form->getValue('areaCode'));
        $phone->setExchange($form->getValue('exchange'));
        $phone->setNumber($form->getValue('number'));
        $phone->insert();
    }
    if (is_object($address)) {
        $address->setStreet($form->getValue('street'));
        $address->setCity($form->getValue('city'));
        $address->setState($form->getValue('state'));
        $address->setZIP($form->getValue('zip'));
        $address->update();
    }
    else {
        $address =& new address();
        $address->setTitle("Main Address");
        $address->setStreet($form->getValue('street'));
        $address->setCity($form->getValue('city'));
        $address->setState($form->getValue('state'));
        $address->setZIP($form->getValue('zip'));
        $address->setCountry('US');
        $address->setPrimary(1);
        $address->insert();
    }

    # Send an email to the member
    $body = file_get_contents("templates/emails/final-instructions.txt");
    $email =& new Email();
    $email->setFrom($cfg['club_admin_email_name']);
    $email->addHeader("Return-Path", $cfg['club_admin_email']);
    $email->setSubject("Your new $cfg[club_name] membership");
    $email->addTo($obj['user']->getEmail());
    $body = Template::replace($body, array(
        "C_FULL_NAME" => $obj['user']->getFullName(),
        "MEMBERSHIP" => $type->getTitle(),
        "CLUB_NAME" => $cfg['club_name'],
        "FEE" => $type->getTotalCost()));
    $email->setBody(Template::finalize($body));
    $email->loadFooter("templates/emails/footer.txt");
    $email->send();

    redirect("$cfg[base_url]/members/join/final-instructions");
}

$res['content'] = Template::replace($wrapper, array(
    "FORM" => $form->toString()));

$res['title'] = "Renew Your Membership";

?>

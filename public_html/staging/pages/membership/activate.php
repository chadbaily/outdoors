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
 * $Id: activate.php,v 1.4 2009/03/12 03:15:58 pctainto Exp $
 */

require_once("membership_type.php");
include_once("Email.php");
require_once("transaction.php");
require_once("DateTime.php");

$template = file_get_contents("templates/membership/activate.php");

# Retrieve some objects we need
$type = new membership_type();
$type->select($object->getType());
$member = new member();
$member->select($object->getMember());

if ($type->getFlexible()) {
    $template = Template::unhide($template, "FLEX");
}
else {
    $template = Template::unhide($template, "INFLEX");
}

# Determine the age of the member
$now = getdate();
$born = getdate(strtotime($member->getBirthDate()));
$months = ($now['mon'] - $born['mon'] + 12) % 12;
$years = $now['year'] - $born['year'];
if ($now['mon'] < $born['mon']) {
    $years -= 1;
}
$template = Template::replace($template, array(
    "AGE" => "$years years and $months months")); 

# Get the max expiration date of the user's active memberships from the
# database
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership/select-max-expiration-date.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$cmd->addParameter("member", $object->getMember());
$maxExpire = new DateTimeSC($cmd->executeScalar());

# Figure out when the suggested end date should be.  It depends on whether
# the membership type is flexible or not.
$begin = new DateTimeSC();
$expire = new DateTimeSC();
if ($type->getFlexible()) {
    if ($begin->compareTo($maxExpire) < 0) {
        $begin = $maxExpire;
    }
    switch ($type->getUnit()) {
    case "year":
        $expire = $begin->addYears($object->getUnitsGranted());
        break;
    case "month":
        $expire = $begin->addMonths($object->getUnitsGranted());
        break;
    case "day":
        $expire = $begin->addDays($object->getUnitsGranted());
        break;
    }
}
else {
    # Just use the values in the membership itself; these were set when the
    # membership was created.
    $begin = new DateTimeSC($object->getBeginDate());
    $expire = new DateTimeSC($object->getExpirationDate());
}

# Create the form and initialize it from the results of the date
# calculations above.
$form = new XmlForm("forms/membership/activate.xml");
$form->setValue("begin", $begin->toString("Y-m-d"));
$form->setValue("expire", $expire->toString("Y-m-d"));
$form->setValue("totalcost", $object->getTotalCost());

# Overwrite the values in the form with what the user may have submitted
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $object->setStatus($cfg['status_id']['active']);
    $object->setAmountPaid($form->getValue("totalcost"));
    $object->setBeginDate($form->getValue("begin"));
    $object->setExpirationDate($form->getValue("expire"));
    $object->update();

    # Add a note to the member to say who activated the membership
    $member->addNote("$cfg[user] activated membership $cfg[object]");

    $msg = new Email();
    $msgBody = file_get_contents("templates/emails/activation-notice.txt");
    $msgBody = Template::replace($msgBody, array(
        "CLUB_NAME" => $cfg['club_name'],
        "TITLE" => $type->getTitle(),
        "C_FULL_NAME" => $member->getFullName(),
        "BASEURL" => $cfg['site_url'] . $cfg['base_url'])
        + $object->getVarArray());
    $msg->setBody($msgBody);
    $msg->addTo($member->getEmail());
    $msg->setFrom($cfg['club_admin_email_name']);
    $msg->addHeader("Return-Path", $cfg['club_admin_email']);
    $msg->loadFooter("templates/emails/footer.txt");
    $msg->setSubject("Welcome to $cfg[club_name]!");
    $msg->send();

    # Record this transaction
    $cmd = $obj['conn']->createCommand();
    $cmd->setCommandText("select c_uid from [_]expense_category "
        . "where c_title = 'Membership Dues'");
    $cat = $cmd->executeScalar();

    # Record this transaction
    $tran = new transaction();
    $tran->setAmount(floatval($form->getValue("totalcost")));
    $tran->setCategory($cat);
    $tran->setDescription($member->getFullName()
        . "'s dues for membership $cfg[object]");
    $tran->setFrom($cfg['object']);
    $tran->setTo($cfg['root_uid']);
    $tran->insert();

    # Update the page
    $template = Template::unhide($template, "SUCCESS");
}
else {
    $template = Template::unhide($template, "inactive");
    $template = Template::replace($template, array("FORM" => $form->toString()));
}

$res['content'] = $member->insertIntoTemplate(
    $type->insertIntoTemplate(
    $object->insertIntoTemplate($template)));
$res['title'] = "Activate Membership";

?>

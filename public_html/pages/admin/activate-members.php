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
 * $Id: activate-members.php,v 1.3 2005/08/02 02:41:45 bps7j Exp $
 */

include_once("membership.php");
include_once("membership_type.php");
include_once("Email.php");
require_once("transaction.php");

# Check that the user has correct permissions
if (!$obj['user']->isInGroup('root') && !$obj['user']->isInGroup('activator')) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$wrapper = file_get_contents("templates/admin/activate-members.php");

# Get a list of memberships that are a) not flexible b) inactive c) the
# membership type hasn't expired already (so activating the membership would do
# some good).

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership/select-for-activation.sql");
$cmd->addParameter("inactive", $cfg['status_id']['inactive']);
$cmd->addParameter("active", $cfg['status_id']['active']);
$result = $cmd->executeReader();

if (isset($_POST['submitted']) && isset($_POST['membership'])) {

    # Get the correct category for the transactions
    $cmd = $obj['conn']->createCommand();
    $cmd->setCommandText("select c_uid from [_]expense_category "
        . "where c_title = 'Membership Dues'");
    $cat = $cmd->executeScalar();

    $aggregate = array(); // Array of results we'll print out later
    while ($row = $result->fetchRow()) {
        # Check that the current row got submitted with the form
        if (in_array($row['membership_uid'], $_POST['membership'])) {
            # Create the objects we need
            $membership =& new membership();
            $membership->select($row['membership_uid']);
            $member =& new member();
            $member->select($row['c_uid']);
            $membership->setStatus($cfg['status_id']['active']);
            $membership->setBeginDate($row['c_begin_date']);
            $membership->setExpirationDate($row['c_expiration_date']);
            $membership->setAmountPaid($membership->getTotalCost());
            $membership->update();

            # Add a note to the member to say who activated the membership
            $member->addNote("$cfg[user] activated membership "
                .  $membership->getUID());

            $msg =& new Email();
            $msgBody = file_get_contents("templates/emails/activation-notice.txt");
            $msgBody = Template::replace($msgBody, array(
                "CLUB_NAME" => $cfg['club_name'],
                "TITLE" => $row['c_title'],
                "C_FULL_NAME" => $member->getFullName(),
                "BASEURL" => $cfg['site_url'] . $cfg['base_url'])
                + $membership->getVarArray());
            $msg->setBody($msgBody);
            $msg->addTo($member->getEmail());
            $msg->setFrom($cfg['club_admin_email_name']);
            $msg->addHeader("Return-Path", $cfg['club_admin_email']);
            $msg->loadFooter("templates/emails/footer.txt");
            $msg->setSubject("Welcome to $cfg[club_name]!");
            $msg->send();

            # Record this transaction
            $tran =& new transaction();
            $tran->setAmount(floatval($membership->getAmountPaid()));
            $tran->setCategory($cat);
            $tran->setDescription($member->getFullName()
                . "'s dues for membership " . $membership->getUID());
            $tran->setFrom($row['c_uid']);
            $tran->setTo($cfg['root_uid']);
            $tran->insert();

            # Record the results
            if (!isset($aggregate[$row['c_type']])) {
                $aggregate[$row['c_type']] = 1;
            }
            else {
                $aggregate[$row['c_type']] += 1;
            }
        }
    }
    if (count($aggregate)) {
        $wrapper = Template::unhide($wrapper, "SUCCESS");
        foreach ($aggregate as $type => $num) {
            $mt =& new membership_type();
            $mt->select($type);
            $wrapper = Template::block($wrapper, "RESULTS", array(
                "MEMBERSHIP_TITLE" => $mt->getTitle(),
                "NUM" => $num));
        }
    }
}
else {
    # No post data; display the list.
    $wrapper = Template::unhide($wrapper, "SOME");
    while ($row = $result->fetchRow()) {
        $wrapper = Template::block($wrapper, "row", $row + array(
            "underage" => ($row['c_underage'] ? " class='underage'" : "")));
    }
}

$res['title'] = "Activate Memberships";
$res['content'] = $wrapper;

?>

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
 * $Id: default.php,v 1.5 2005/08/05 00:46:27 bps7j Exp $
 */

require_once("chat.php");
require_once("address.php");
require_once("phone_number.php");

$wrapper = file_get_contents("templates/profile/default.php");

# Update the member's choice of primary address
if (isset($_GET['primaryAddress'])) {
    $newAddr =& new address();
    $newAddr->select($_GET['primaryAddress']);
    if ($newAddr->getOwner() == $cfg['user']) {
        $newAddr->makePrimary();
    }
}

# Update the member's privacy preferences on addresses
if (isset($_GET['privateAddress'])) {
    $privAddr =& new address();
    $privAddr->select($_GET['privateAddress']);
    if ($privAddr->getOwner() == $cfg['user']) {
        $privAddr->setHidden(!$privAddr->getHidden());
        $privAddr->update();
    }
}

# Update the member's choice of primary chat
if (isset($_GET['primaryChat'])) {
    $newChat =& new chat();
    $newChat->select($_GET['primaryChat']);
    if ($newChat->getOwner() == $cfg['user']) {
        $newChat->makePrimary();
    }
}

# Update the member's privacy preferences on chats
if (isset($_GET['privateChat'])) {
    $privChat =& new chat();
    $privChat->select($_GET['privateChat']);
    if ($privChat->getOwner() == $cfg['user']) {
        $privChat->setHidden(!$privChat->getHidden());
        $privChat->update();
    }
}

# Update the member's choice of primary phone
if (isset($_GET['primaryPhone'])) {
    $newPhone =& new phone_number();
    $newPhone->select($_GET['primaryPhone']);
    if ($newPhone->getOwner() == $cfg['user']) {
        $newPhone->makePrimary();
    }
}

# Update the member's privacy preferences on phones
if (isset($_GET['privatePhone'])) {
    $privPhone =& new phone_number();
    $privPhone->select($_GET['privatePhone']);
    if ($privPhone->getOwner() == $cfg['user']) {
        $privPhone->setHidden(!$privPhone->getHidden());
        $privPhone->update();
    }
}

# Update the member's own privacy settings and add them to the page
if (isset($_GET['meHidden'])) {
    $obj['user']->setHidden($_GET['meHidden']);
    $obj['user']->update();
}
if (isset($_GET['hideEmail'])) {
    $obj['user']->setEmailHidden($_GET['hideEmail']);
    $obj['user']->update();
}
if (!$obj['user']->getHidden()) {
    $wrapper = Template::replace($wrapper, array("HIDDEN" => "NOT"));
}
$wrapper = Template::replace($wrapper, array(
    "EMAIL_PRIVATE" => $obj['user']->getEmailHidden() ? "NO" : "YES"));


# Add objects to the page.
$addresses = $obj['user']->getChildren("address", "c_owner");
$address = $obj['user']->getPrimaryAddress();
$phones = $obj['user']->getChildren("phone_number", "c_owner");
$phone = $obj['user']->getPrimaryPhoneNumber();
$chats = $obj['user']->getChildren("chat", "c_owner");
$chat = $obj['user']->getPrimaryChat();

foreach ($addresses as $key => $addr) {
    $wrapper = Template::block($wrapper, "ADDRESS",
        $addr->getVarArray()
        + array(
            "PRIMARY" => (is_object($address) && $key == $address->getUID()) ? "Yes" : "No",
            "PRIVATE" => $addr->getHidden() ? "Yes" : "No"
        ));
}

foreach ($phones as $key => $pho) {
    $wrapper = Template::block($wrapper, "PHONE",
        $pho->getVarArray()
        + array(
            "PRIMARY" => (is_object($phone) && $key == $phone->getUID()) ? "Yes" : "No",
            "PRIVATE" => $pho->getHidden() ? "Yes" : "No"
        ));
}

foreach ($chats as $key => $cha) {
    $wrapper = Template::block($wrapper, "CHAT",
        $cha->getVarArray()
        + array(
            "PRIMARY" => (is_object($chat) && $key == $chat->getUID()) ? "Yes" : "No",
            "PRIVATE" => $cha->getHidden() ? "Yes" : "No"
        ));
}

$res['content'] = Template::replace($wrapper,
    array("OBJECT" => $cfg['user']));
$res['title'] = "Manage Your Profile";

?>

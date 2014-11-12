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
 * $Id: retrieve-password.php,v 1.2 2009/03/12 03:15:59 pctainto Exp $
 *
 * Purpose: allows a user to enter his/her email address.  The system will email
 * the password to that address.
 */

include_once("RetrievePassword.php");
include_once("member.php");

$user = new member();

$form = new XMLForm("forms/main/retrieve-password.xml");
$form->snatch();
$form->validate();

$wrapper = file_get_contents("templates/main/retrieve-password.php");

if ($form->isValid()) {
    # We got an email address, see if it's in the database...
    if (RetrievePassword::checkForExistence($form->getValue('emailAddress'))) {
        # It's in the database
        $user->selectFromEmail($form->getValue('emailAddress'));
        RetrievePassword::sendPassword($user);
        $wrapper = Template::unhide($wrapper, "SUCCESS");
    }
    else {
        # It's not
        $wrapper = Template::unhide($wrapper, "ERROR");
    }
}

$res['content'] = Template::replace($wrapper, array(
    "FORM" => $form->toString()));
$res['title'] = "Retrieve Password";
$res['usetab'] = true;
$res['tabfile'] = "main.php";
$res['tab'] = "";

?>

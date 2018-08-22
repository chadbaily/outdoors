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
 * $Id: feedback.php,v 1.2 2009/03/12 03:15:59 pctainto Exp $
 *
 * Purpose: allow the user to send anonymous feedback to the officers list
 */

include_once("Email.php");

$wrapper = file_get_contents("templates/main/feedback.php");
$form = new XmlForm("forms/main/feedback.xml");

$form->snatch();
$form->validate();

if ($form->isValid()) {
    $email = new Email();
    $email->addTo($cfg['club_admin_email']);
    $email->setFrom($cfg['club_admin_email']);
    $email->setSubject($form->getValue("subject"));
    $email->setBody($form->getValue("message") 
    . "\r\n\r\nThis user visited this page from " . $_SERVER["HTTP_REFERER"]);
    $email->send();
    $wrapper = Template::unhide($wrapper, "SUCCESS");
}
else {
    $wrapper = Template::replace($wrapper, array("FORM" => $form->toString()));
}

$res['content'] = $wrapper;
$res['title'] = "Send Feedback";
$res['usetab'] = true;
$res['tabfile'] = "main.php";
$res['tab'] = "Home";
$res['subtab'] = "Contact Us";

?>

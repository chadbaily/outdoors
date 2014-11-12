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
 * $Id: create.php,v 1.2 2009/03/12 03:16:00 pctainto Exp $
 */

$template = file_get_contents("templates/address/create.php");

# Create the form
$form = new XMLForm("forms/address/create.xml");

# Validate and plug the form into the wrapper template
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $object = new address();
    $object->setTitle($form->getValue("title"));
    $object->setStreet($form->getValue("street"));
    $object->setCity($form->getValue("city"));
    $object->setState($form->getValue("state"));
    $object->setZIP($form->getValue("zip"));
    $object->setCountry($form->getValue("country"));
    $object->insert();
    redirect("$cfg[base_url]/members/address/read/$object->c_uid");
}
else {
    $template = Template::replace($template, array(
        "FORM" => $form->toString()));
}

$res['content'] = $template;
$res['title'] = "Create an Address";

?>

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
 */

$template = file_get_contents("templates/location/write.php");
$template = $object->insertIntoTemplate($template);

# Create and populate the form.
$form = new XmlForm("forms/location/write.xml");
$form->setValue("title", $object->getTitle());
$form->setValue("description", $object->getDescription());
$form->setValue("zip-code", $object->getZipCode());

# Now overwrite it with any data the user submitted.
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the location with the new values
    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setZipCode($form->getValue("zip-code"));

    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}
$template = Template::replace($template, array("FORM" => $form->toString()));

$res['content'] = $template;
$res['title'] = "Edit Location";

?>

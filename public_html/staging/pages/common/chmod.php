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
 * $Id: chmod.php,v 1.2 2009/03/12 03:15:59 pctainto Exp $
 */

# Create and populate the form.
$form = new XMLForm("forms/common/chmod.xml");

# Set the values in the form
$perms = $object->getUnixperms();
$form->setValue("owner_read", $object->getPerm("owner_read"));
$form->setValue("group_read", $object->getPerm("group_read"));
$form->setValue("other_read", $object->getPerm("other_read"));
$form->setValue("owner_write", $object->getPerm("owner_write"));
$form->setValue("group_write", $object->getPerm("group_write"));
$form->setValue("other_write", $object->getPerm("other_write"));
$form->setValue("owner_delete", $object->getPerm("owner_delete"));
$form->setValue("group_delete", $object->getPerm("group_delete"));
$form->setValue("other_delete", $object->getPerm("other_delete"));

# Now overwrite it with any data the user submitted.
$form->snatch();
$form->validate();

# Create a template that we can use to put instructions into the page
$template = file_get_contents("templates/common/chmod.php");

if ($form->isValid()) {
    # Update the object with the new values
    
    $object->setPerm("owner_read", $form->getValue("owner_read"));
    $object->setPerm("group_read", $form->getValue("group_read"));
    $object->setPerm("other_read", $form->getValue("other_read"));
    $object->setPerm("owner_write", $form->getValue("owner_write"));
    $object->setPerm("group_write", $form->getValue("group_write"));
    $object->setPerm("other_write", $form->getValue("other_write"));
    $object->setPerm("owner_delete", $form->getValue("owner_delete"));
    $object->setPerm("group_delete", $form->getValue("group_delete"));
    $object->setPerm("other_delete", $form->getValue("other_delete"));
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

# Plug it all into the template
$res['content'] = Template::replace($template, array(
    "TABLE" => get_class($object),
    "FORM" => $form->toString()));
$res['title'] = "Change Object Permissions";

?>

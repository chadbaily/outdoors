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
 * $Id: chmeta.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 */

# Create and populate the form.  Create the form as a template.  Create a
# template for the page.
$formTemplate = file_get_contents("forms/common/chmeta.xml");
$template = file_get_contents("templates/common/chmeta.php");

# Fill in choices for the creator
$result = $obj['conn']->query("select c_uid, c_first_name, c_last_name from [_]member"
    . " order by c_last_name");
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate,
        "CREATOR", array_change_key_case($row, 1));
}

# Fill in choices for the status
foreach ($cfg['status_id'] as $key => $val) {
    $formTemplate = Template::block($formTemplate, "STATUS",
        array("C_UID" => $val, "C_TITLE" => $key));
}

$formTemplate = Template::finalize($formTemplate);

$form = new XmlForm($formTemplate, true);

# Populate with the current values
$form->setValue("created", $object->getCreatedDate());
$form->setValue("creator", $object->getCreator());
$form->setValue("status", $object->getStatus());

# Now overwrite it with any data the user submitted.
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the object with the new values
    $object->setCreator($form->getValue("creator"));
    $object->setStatus($form->getValue("status"));
    $object->setCreatedDate($form->getValue("created"));
    $object->update();
    # Plug in a success message
    $template = Template::unhide($template, "SUCCESS");
}

# Plug it all into the template
$res['content'] = Template::replace($template, array(
    "TABLE" => get_class($object),
    "FORM" => $form->toString()));
$res['title'] = "Edit Object Properties";

?>

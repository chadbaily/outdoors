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
 * $Id: add_privilege.php,v 1.4 2005/08/03 01:29:11 bps7j Exp $
 */

include_once("privilege.php");

# Create templates
$template = file_get_contents("templates/common/add_privilege.php");
$formTemplate = file_get_contents("forms/common/add_privilege.xml");

# Add actions and tables to the form template
foreach ($cfg['actions'] as $key => $val) {
    $formTemplate = Template::block($formTemplate, "ACTION", array(
        "C_UID" => $key,
        "C_SUMMARY" => $val['c_summary']));
}
$result = $obj['conn']->query("select * from [_]table");
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "TABLE",
        array_change_key_case($row, 1));
}

$form =& new XmlForm(Template::finalize($formTemplate), true);
$form->setValue("related_uid", $cfg['object']);
$form->setValue("related_table", $cfg['table_prefix'] . get_class($object));
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $priv =& new privilege();
    $priv->setWhatGrantedTo($form->getValue("what_granted_to"));
    $priv->setWhoGrantedTo($form->getValue("who_granted_to"));
    $priv->setAction($form->getValue("action"));
    $priv->setWhatRelatesTo($form->getValue("what_relates_to"));
    $priv->setRelatedTable($form->getValue("related_table"));
    $priv->setRelatedUID($form->getValue("related_uid"));
    $priv->insert();
    $template = Template::unhide($template, "SUCCESS");
}
else {
    $template = Template::replace($template, array("FORM" => $form->toString()));
}

# Plug it all into the templates.
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Add Privilege";

?>

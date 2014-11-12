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
 * $Id: email.php,v 1.4 2009/03/12 03:15:59 pctainto Exp $
 */

include_once("MassEmail.php");
include_once("includes/authorize.php");

$template = file_get_contents("templates/main/email.php");

# Create and validate the form.
$formTemplate = file_get_contents("forms/main/email.xml");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]activity_category");
$cmd->addParameter("orderby", "c_uid");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "option", $row);
}
$invalid = array("wheel", "root", "guest", "activator");
foreach ($cfg['group_id'] as $key => $val) {
    if (!in_array($key, $invalid)) {
        $formTemplate = Template::block($formTemplate, "group",
            array("group_id" => $val, "group_name" => $key));
    }
}
if ($obj['user']->isRootUser()) {
    $formTemplate = Template::unhide($formTemplate, "FORCE");
}

$form = new XmlForm(Template::finalize($formTemplate), true);
$form->snatch();
$form->validate();
if (!$form->getValue("group")) {
    $form->setValue("group", $cfg['group_id']['member']);
}

if ($form->isValid()) {
    MassEmail::sendMassEmail(
        $obj['user'],
        $form->getValue("subject"),
        $form->getValue("message"),
        $form->getValue("category"),
        $form->getValue("group"),
        $obj['user']->isRootUser() ? $form->getValue("force") : 0
    );
    $template = Template::unhide($template, "success");
}
else {
    # Plug the form into the template
    $template = Template::replace($template, array(
        "form" => $form->toString()));
    $template = Template::unhide($template, "initial");
}

$res['content'] = $template;
$res['title'] = "Email Club Members";
$res['navbar'] = "Member's Area";

?>

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
 * $Id: create.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 */

$template = file_get_contents("templates/report/create.php");

# Create and validate the form.
$form = new XmlForm("forms/report/create.xml");
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $object = new report();
    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setQuery($form->getValue("query"));
    # Make sure the report can't modify the database.
    $badWords = $object->checkForAlter();
    if (count($badWords)) {
        foreach ($badWords as $key => $word) {
            $template = Template::block($template, "ITEM", array(
                "WORD" => $word));
        }
        $template = Template::unhide($template, "BAD");
        $template = Template::replace($template, array(
            "FORM" => $form->toString()));
    }
    else {
        $object->insert();
        redirect("$cfg[base_url]/members/report/read/$object->c_uid");
    }
}
else {
    $template = Template::replace($template, array(
        "FORM" => $form->toString()));
}

$res['content'] = $template;
$res['title'] = "Create a New Report";

?>

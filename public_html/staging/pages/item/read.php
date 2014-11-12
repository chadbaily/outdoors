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
 * $Id: read.php,v 1.4 2009/03/12 03:16:01 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/item/read.php");

# Add all the features to the template
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/select-features.sql");
$cmd->addParameter("item", $cfg['object']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $template = Template::block($template, "ATTR", array(
        "C_NAME" => $row['c_name'],
        "C_VALUE" => ($row['c_value'] ? $row['c_value'] : "")));
}

# Get the next and last items in the list
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/next-by-type.sql");
$cmd->addParameter("type", $object->getType());
$cmd->addParameter("item", $cfg['object']);
$next = $cmd->executeScalar();
if ($next) {
    $template = Template::unhide($template, "NEXT");
    $template = Template::replace($template,
        array ("NEXT_ID" => $next));
}
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/last-by-type.sql");
$cmd->addParameter("type", $object->getType());
$cmd->addParameter("item", $cfg['object']);
$last = $cmd->executeScalar();
if ($last) {
    $template = Template::unhide($template, "LAST");
    $template = Template::replace($template,
        array ("LAST_ID" => $last));
}

# Auto-link to items when they are in the format "item XYZ"
$replacement = "<a href=\"members/item/read/\\1\">\\0</a>";
$template = preg_replace("/item (\d+)/", $replacement, $template);

# Add information about the type and condition and status
$type = new item_type();
$type->select($object->getType());
$template = Template::replace($template, array(
    "TYPE_TITLE" => $type->getTitle()));
$cond = new condition();
$cond->select($object->getCondition());
$cfg['status_title'] = array_flip($cfg['status_id']);
$template = Template::replace($template, array(
    "CONDITION_TITLE" => $cond->getTitle()));
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Item: " . $object->getUID();

?>

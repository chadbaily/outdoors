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
 * $Id: read.php,v 1.2 2009/03/12 03:15:59 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/item_type/read.php");

# Get info on the category
$cat = new item_category();
$cat->select($object->getCategory());
$template = Template::replace($template, array(
    "CAT_TITLE" => $cat->getTitle()));

# Add all the attributes to the template
foreach ($object->getChildren("item_type_feature") as $attr) {
    $template = Template::block($template, "ATTR",
        array("C_NAME" => $attr->getName()));
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Item Type: " . $object->getTitle();

?>

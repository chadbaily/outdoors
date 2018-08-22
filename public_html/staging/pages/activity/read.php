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
 * $Id: read.php,v 1.2 2009/03/12 03:16:02 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/activity/read.php");

$cat = new activity_category();
$cat->select($object->getCategory());
$template = Template::replace($template, array("cat_title" => $cat->getTitle()));

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Activity: " . $object->getTitle();

?>

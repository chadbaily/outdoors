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
 * $Id: item.php,v 1.1.1.1 2005/03/27 19:53:08 bps7j Exp $
 */

include_once("includes/authorize.php");
include_once("item.php");
include_once("item_type.php");
include_once("item_feature.php");
include_once("condition.php");

$res['title'] = "Items";

include_once("pages/common/include-file.php");

# Prepare things for the main page to process
$res['navbar'] = "Member's Area/Inventory";

?>

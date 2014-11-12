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
 * $Id: inventory.php,v 1.3 2005/08/02 03:05:24 bps7j Exp $
 */

include_once("includes/authorize.php");
include_once("item.php");
include_once("item_type.php");
include_once("item_feature.php");
include_once("condition.php");
include_once("checkout.php");

$res['title'] = "Inventory";
$res['navbar'] = "Member's Area/Inventory";

$template = file_get_contents("templates/main/inventory.php");

# Plug in allowed actions.
$item =& new table("$cfg[table_prefix]item");
$checkout =& new table("$cfg[table_prefix]checkout");

if ($item->permits('list_all')) {
    $template = Template::unhide($template, "item_list_all");
}

if ($item->permits('create')) {
    $template = Template::unhide($template, "item_create");
}

if ($checkout->permits('create')) {
    $template = Template::unhide($template, "checkout_create");
}

if ($checkout->permits('list_all')) {
    $template = Template::unhide($template, "checkout_list_all");
}

if ($obj['user']->isInGroup("quartermaster")
    || $obj['user']->isRootUser())
{
    $template = Template::unhide($template, "manage");
}

# Display an inventory report.
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/summarize.sql");
$cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
$cmd->addParameter("missing", $cfg['status_id']['missing']);
$reader = $cmd->executeReader();
while ($row = $reader->fetchRow()) {
    $template = Template::block($template, "row", $row);
}

$res['content'] = $template;

?>

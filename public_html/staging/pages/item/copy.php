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
 * $Id: copy.php,v 1.3 2005/08/02 03:05:23 bps7j Exp $
 */

# Make a copy of the object and redirect to the copy's edit page.
$item = $object->copy();
$item->insert();

# Copy the item's features, too
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/copy-features.sql");
$cmd->addParameter("from", $cfg['object']);
$cmd->addParameter("to", $item->getUID());
$cmd->addParameter("owner", $cfg['user']);
$cmd->executeNonQuery();

if (isset($_SERVER['HTTP_REFERER'])
        && strpos($_SERVER['HTTP_REFERER'], "edit_features"))
{
    redirect("$cfg[base_url]/members/item/edit_features/$item->c_uid");
}
else {
    redirect("$cfg[base_url]/members/item/write/$item->c_uid?mode=new");
}

?>

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
 * $Id: accept.php,v 1.2 2005/08/02 03:05:05 bps7j Exp $
 */

# Accepting a checkout means that you're satisfied with it.  It sets the status
# of the checkout, checkout_item, and item records to checked_out and adds a
# note to each item.
if ($object->getStatus() == $cfg['status_id']['default']) {
    $object->setStatus($cfg['status_id']['checked_out']);
    $object->update();
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/checkout/accept.sql");
    $cmd->addParameter("checkout", $cfg['object']);
    $cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
    $cmd->executeNonQuery();
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/checkout/add-item-notes.sql");
    $cmd->addParameter("checkout", $cfg['object']);
    $cmd->executeNonQuery();
}

redirect("$cfg[base_url]/members/checkout/read/$cfg[object]");

?>

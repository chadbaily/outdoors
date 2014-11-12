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
 * $Id: tabbed-box.php,v 1.4 2009/03/12 03:16:02 pctainto Exp $
 *
 * This page looks for an object called $cfg['object'] that it can use to check
 * permissions and so forth.  It then generates a row of tabs, one for each
 * action that's permitted on the object except for the 'generic' tabs, and then
 * includes a file that can override these choices, based on the type of object.
 * So if it's an adventure, and the adventure file exists, that file will have a
 * chance to veto the appearance of a tab.
 */

include_once("TabbedBox.php");
$obj['tabbed_box'] = new TabbedBox();

if (isset($object)) {
    # Init the object's allowed actions, if needed
    $actions = $object->getAllowedActions();
    # Check if the current action is 'generic' (as defined in the DB)
    if ($actions[$cfg['action']]['c_generic']) {
        # Add all the 'generic' actions that are allowed, as well as the
        # 'Details' action
        foreach ($actions as $row) {
            if ($row['c_generic'] || $row['c_title'] == 'read') {
                $obj['tabbed_box']->addTab($row['c_label'],
                    $row['c_row'],
                    "members/{PAGE}/$row[c_title]/{OBJECT}");
                if ($row['c_title'] == $cfg['action']) {
                    $obj['tabbed_box']->setActiveTab($row['c_label']);
                }
            }
        }
    }
    else {
        # Add all the 'specific' actions that are allowed and implemented
        foreach ($actions as $row) {
            if (!$row['c_generic']
                && file_exists("pages/$cfg[page]/$row[c_title].php"))
            {
                $obj['tabbed_box']->addTab($row['c_label'],
                    # Ignore the row for this.  The override file may override this
                    0,
                    "members/{PAGE}/$row[c_title]/{OBJECT}");
                if ($row['c_title'] == $cfg['action']) {
                    $obj['tabbed_box']->setActiveTab($row['c_label']);
                }
            }
        }
    }
    # Include an override file if it exists
    if (file_exists("nav/" . get_class($object) . ".php")) {
        include_once("nav/" . get_class($object) . ".php");
    }
}

$res['tabs'] = $obj['tabbed_box']->toString();

?>

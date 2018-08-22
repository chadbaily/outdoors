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
 * $Id: chgrp_secondary.php,v 1.2 2005/06/05 18:03:09 bps7j Exp $
 */

$template = file_get_contents("templates/member/chgrp_secondary.php");

# Get an array of checkboxes that the user checked
$checkboxes = postval('groups');
$posted = postval('posted');

if ($posted && !is_array($checkboxes)){
    $checkboxes = array();
}

$dirty = false;

foreach ($cfg['group_id'] as $groupName => $group) {
    # There are two "dangerous" groups, which could cause a privilege escalation
    # if the user is given them.  So, only all-powerful users will have the
    # ability to let people belong to that group (otherwise, an ordinary user
    # could make himself a root user or something).
    if ($obj['user']->isInGroup('root')
        || ($groupName != 'root' && $groupName != 'wheel'))
    {

        # We'll check the checkbox if the member is in the group...
        $inGroup = $object->isInGroup($group);
        # If the member is already in the group, and the form is submitted but the
        # checkbox isn't checked, delete the member from that group.
        if ($inGroup && $posted && !in_array($group, $checkboxes)) {
            $object->setInGroup($group, 0);
            $dirty = TRUE;
            $inGroup = false;
        }
        # If the member isn't already in the group, and the form is submitted and
        # the checkbox is checked, add the member to the group.
        elseif (!$inGroup && $posted && in_array($group, $checkboxes))
        {
            $object->setInGroup($group, 1);
            $dirty = TRUE;
            $inGroup = true;
        }
        # Plug the info into the template row...
        $template = Template::block($template, "GROUP", array(
            "CHECKED" => ($inGroup ? "checked" : ""),
            "c_uid" => $group,
            "c_title" => $groupName));
    }
}

if ($dirty) {
    $object->update();
    # Say that the groups were updated.
    $template = Template::unhide($template, "SUCCESS");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Change Group Memberships";
$res['help'] = "HelpOnGroupMemberships";

?>

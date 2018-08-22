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
 * $Id: read.php,v 1.4 2006/03/27 03:46:25 bps7j Exp $
 */

include_once("location.php");

$template = file_get_contents("templates/adventure/read.php");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/read.sql");
$cmd->addParameter("adventure", $cfg['object']);
$cmd->addParameter("member", $cfg['user']);
$cmd->addParameter("default", $cfg['status_id']['default']);
$result = $cmd->executeReader();
$advRow = $result->fetchRow();

# If the member is attending the adventure, show a link to edit/view answers to
# questions
if ($advRow['at_uid'] && $advRow['before_deadline']) {
    $template = Template::unhide($template, "view_answers");
}

# If the adventure is cancelled, display a message on the page
if ($object->getStatus() == $cfg['status_id']['cancelled']) {
    $template = Template::unhide($template, "cancelled");
}

# If the member is allowed to comment, display links to do so
if (!$advRow['co_uid'] && $advRow['trip_over']) {
    $template = Template::unhide($template, "comment_link");
}

# Plug in activity types for the adventure:
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/select-activities.sql");
$cmd->addParameter("adventure", $cfg['object']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $template = Template::block($template, "cat", $row);
}

# Plug in attendees if the adventure is in the past:
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/select-attendee-info.sql");
$cmd->addParameter("adventure", $cfg['object']);
$cmd->addParameter("default", $cfg['status_id']['default']);
$result = $cmd->executeReader();
if ($result->numRows()) {
    $template = Template::unhide($template, "attendees");
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "attendee", $row);
    }
}

# If the adventure has any comments, display them
$comment = Template::extract($template, "comment");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/comment-select-for-read.sql");
$cmd->addParameter("adventure", $cfg['object']);
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $thisComment = Template::replace($comment, $row);
    if ($row['c_anonymous']) {
        $thisComment = Template::unhide($thisComment, "hide_name");
    }
    else {
        $thisComment = Template::unhide($thisComment, "show_name");
    }
    $template = Template::replace($template, array(
        "comment" => $thisComment), true);
}
if ($result->numRows()) {
    $template = Template::unhide($template, "some");
}

if ($advRow['num_atts'] >= $advRow['c_max_attendees']) {
    $template = Template::unhide($template, "full");
}
$template = Template::replace($template, $advRow);

# Show a link to the weather reports if the destination has a zip code
if ($advRow['c_zip_code']) {
    $template = Template::unhide($template, "weather");
}

$res['content'] = $template;
$res['title'] = "Adventure : "
    . substr($object->getTitle(), 0, 45)
    . (strlen($object->getTitle()) > 45 ? "..." : "");

?>

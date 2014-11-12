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
 * $Id: read.php,v 1.1 2009/11/14 22:53:30 pctainto Exp $
 */

$template = file_get_contents("templates/application/read.php");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/application/read.sql");
$cmd->addParameter("application", $cfg['object']);
$result = $cmd->executeReader();
$advRow = $result->fetchRow();

$member = new member();
$member->select($object->getMember());
$current_user = new member();
$current_user->select($cfg['user']);

if ($current_user->isInGroup($cfg['group_id']['officer'])) {
    $template = Template::unhide($template, "HEADERS");
    $cmd->loadQuery("sql/application/select-trips-led.sql");
    $cmd->addParameter("application", $cfg['object']);
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::replace($template, array('num_led'=>$result->numRows()));
        $template = Template::unhide($template, "tripsled");
        $count = 0;
        while ($row = $result->fetchRow()) {
            # Plug adventures into the template.
            $template = Template::block($template, "tripled",
                $row
                + array("CLASS" => (($count++ %2) ? "" : " class='odd'")));
        }
    } else {
        $template = Template::unhide($template, "NONELED");
    }
    
    $cmd->loadQuery("sql/application/select-trips-attended.sql");
    $cmd->addParameter("application", $cfg['object']);
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::replace($template, array('num_att'=>$result->numRows()));
        $template = Template::unhide($template, "tripsatt");
       
        $count = 0;
        while ($row = $result->fetchRow()) {
            # Plug adventures into the template.
            $template = Template::block($template, "tripatt",
                $row
                + array("CLASS" => (($count++ %2) ? "" : " class='odd'")));
        }
    } else {
        $template = Template::unhide($template, "NONEATTENDED");
    }
}

$template = Template::replace($template, $advRow);

$res['content'] = $template;
$res['title'] = "Officer Application : " . $member->getFullName();

?>

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
 * $Id: view_report.php,v 1.5 2009/03/12 03:15:58 pctainto Exp $
 */
include_once("attendee.php");

$template = file_get_contents("templates/adventure/view_report/report.php");
$form = new XmlForm("forms/adventure/view_report.xml");
$form->snatch();

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/view_report-summary.sql");
$cmd->addParameter("adventure", $cfg['object']);
$result = $cmd->executeReader();
$row = $result->fetchRow();

$template = Template::replace($template, array(
                "NUM_JOINED" => $row['joined'],
                    "NUM_WAITLISTED" => $row['waitlisted']));

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/view_report.sql");
$cmd->addParameter("adventure", $cfg['object']);
$cmd->addParameter("waitlisted", $cfg['status_id']['waitlisted']);
if ($form->getValue("status")) {
    $cmd->addParameter("status",
        $cfg['status_id'][$form->getValue("status")]);
}
$result = $cmd->executeReader();

if ($result->numRows()) {

    $template = Template::unhide($template, "SOME");

    # Prepare to get the member's phone numbers
    $cmd2 = $obj['conn']->createCommand();
    $cmd2->loadQuery("sql/phone_number/select-by-owner.sql");

    while ($row = $result->fetchRow()) {

        # Get an array of the member's phone numbers
        $cmd2->addParameter("owner", $row['c_member']);
        $result2 = $cmd2->executeReader();
        $phones = array();
        while ($row2 = $result2->fetchRow()) {
            $phones[] = $row2['c_phone_number']
                . ($row2['c_abbreviation'] ? $row2['c_abbreviation'] : "");
        }

        $template = Template::block($template, "ROW",
            array_change_key_case($row, 1)
                + array("NUMBERS" => implode("<br>", $phones)));
    }

    $template = $template = Template::replace($template, array(
        "LINK" => "members/adventure/view_report/{$cfg['object']}?&status={$form->getValue('status')}&form-name=1&piece=questions",
        "DIV_PREFIX" => "{$form->getValue('status')}_"
        ));
    $template = Template::unhide($template, "SHOW_QUESTIONS");

}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = $template;

$template = Template::replace($template, array(
    "CONTENTS" => $res['content'],
    "FORM" => $form->toString()));
$res['content'] = $object->insertIntoTemplate($template);

?>

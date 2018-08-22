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

$template = file_get_contents("templates/adventure/view_report/questions.php");
$form = new XmlForm("forms/adventure/view_report.xml");
$form->snatch();

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

    if ($form->getValue("question")) {
        $template = Template::unhide($template, "QUESTIONS");
        # Decide on header style
        $headerStyle = "HEADER" . $form->getValue("question");
        foreach ($object->getChildren("question") as $key => $question) {
            $template = Template::block($template, $headerStyle,
                array("C_TEXT" => $question->getText()));
        }
        $rowTemplate = Template::extract($template, "QUESTION");
        $template = Template::delete($template, "QUESTION");
        $result->seekRow(0);
        while ($row = $result->fetchRow()) {
            $thisRow = $rowTemplate;
            $attendee = new attendee();
            $attendee->select($row['c_uid']);
            $answers = $attendee->getChildren("answer");
            # Re-key the answers by question ID
            $a = array();
            foreach ($answers as $answer) {
                $a[$answer->getQuestion()] = $answer;
            }
            $answers = $a;
            foreach ($object->getChildren("question") as $key => $question) {
                if (isset($answers[$key])) {
                    if ($question->getType() == "bool") {
                        $thisRow = Template::block($thisRow,
                                "ANSWER", array(
                                    "C_ANSWER_TEXT" => ($answers[$key]->getAnswerText() == "1"
                                                ? "Yes"
                                                : "No"))
                                                + $answers[$key]->getVarArray());
                    }
                    else {
                        $thisRow = Template::block($thisRow,
                                "ANSWER", $answers[$key]->getVarArray());
                    }
                }
                else {
                    $thisRow = Template::block($thisRow, "ANSWER",
                    array("C_ANSWER_TEXT" => "--"));
                }
            }
            $thisRow = Template::replace($thisRow, array("C_FULL_NAME" => $row['c_full_name']));
            $template = Template::replace($template, array("ROWS" => $thisRow), 1);
        }
    }
    else {
        $template = Template::replace($template, array(
                    "LINK" => "members/adventure/view_report/{$cfg['object']}?&status={$form->getValue('status')}&form-name=1&piece=questions"
                    ));
        $template = Template::unhide($template, "SHOW_QUESTIONS");
        $template = Template::delete($template, "QUESTIONS");
    }

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

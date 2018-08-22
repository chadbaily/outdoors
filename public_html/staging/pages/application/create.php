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
 * $Id: create.php,v 1.1 2009/11/14 22:53:30 pctainto Exp $
 */

# Create the form and the template
$formTemplate = file_get_contents("forms/application/create.xml");
$template = file_get_contents("templates/application/create.php");

#Get the member's info
$member = new member();
$member->select($cfg['user']);

#Add in the info for this user
$formTemplate = Template::replace($formTemplate, array("C_FULL_NAME"=>$member->getFullName(), "C_EMAIL"=>$member->getEmail())); 

# Turn the form template into a form and XML-parse it
$form = new XMLForm(Template::finalize($formTemplate), true);
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Save the adventure in the database
    $object = new application();
    $object->setTitle($form->getValue("title"));
    $object->setYearsLeft($form->getValue("yearsleft"));
    $object->setYearInSchool($form->getValue("yearinschool"));
    $object->setClubExperience($form->getValue("club"));
    $object->setLeadershipExperience($form->getValue("leadership"));
    if ($form->getValue("climbing") && $form->getValue("climbing") != "") {
        $object->setClimbingExperience($form->getValue("climbing"));
    }
    if ($form->getValue("kayaking") && $form->getValue("kayaking") != "") {
        $object->setKayakingExperience($form->getValue("kayaking"));
    }
    if ($form->getValue("biking") && $form->getValue("biking") != "") {
        $object->setBikingExperience($form->getValue("biking"));
    }
    if ($form->getValue("hiking") && $form->getValue("hiking") != "") {
        $object->setHikingExperience($form->getValue("hiking"));
    }
    if ($form->getValue("caving") && $form->getValue("caving") != "") {
        $object->setCavingExperience($form->getValue("caving"));
    }
    if ($form->getValue("snowsports") && $form->getValue("snowsports") != "") {
        $object->setSnowsportsExperience($form->getValue("snowsports"));
    }
    if ($form->getValue("other") && $form->getValue("other") != "") {
        $object->setOtherExperience($form->getValue("other"));
    }
    $object->setWhyOfficer($form->getValue("whyofficer"));
    if ($form->getValue("purchasing")) {
        $object->setPurchasingInterest(1);
    }
    if ($form->getValue("treasurer")) {
        $object->setTreasurerInterest(1);
    }
    if ($form->getValue("quartermaster")) {
        $object->setQuartermasterInterest(1);
    }
    if ($form->getValue("advisor")) {
        $object->setAdvisorInterest(1);
    }
    $object->setMember($cfg['user']);
    $object->insert();

    # Display instructions & links for the next step.
    redirect("$cfg[base_url]/members/main/member-home");
}
else {
    $res['content'] = Template::replace($template, array(
        "FORM" => $form->toString()));
}

$res['title'] = "Create An Officer Applicatio";

?>

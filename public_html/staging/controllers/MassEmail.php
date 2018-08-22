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
 * $Id: MassEmail.php,v 1.6 2009/03/12 04:46:44 pctainto Exp $
 */

include_once("Email.php");

class MassEmail {

    function sendMassEmail($user, $subject, $message, $category, $group = 0, $force = 0) {
        global $obj;
        global $cfg;

        $email = new Email();
        $email->setFrom($user->getFullName() . " <" . $user->getEmail() . ">");
        $email->addTo($user->getFullName() . " <" . $user->getEmail() . ">");
        if (strpos($subject, "$cfg[club_name]: ") == FALSE) {
            if ($cfg['group_id']['leader'] == $group){
                 $subject = "$cfg[club_name] Leaders: $subject";
             }
             else if ($cfg['group_id']['officer'] == $group){
                 $subject = "$cfg[club_name] Officers: $subject";
             }
             else if ($cfg['group_id']['treasurer'] == $group){
                 $subject = "$cfg[club_name] Treasurers: $subject";
             }
             else if ($cfg['group_id']['quartermaster'] == $group){
                 $subject = "$cfg[club_name] Quartermasters: $subject";
             }
             else{
                 $subject = "$cfg[club_name]: $subject";
             }
        }
        $email->setSubject($subject);
        $email->setBody($message);
        $email->addHeader("X-$cfg[club_name]-Category", $category);
        $email->addHeader("X-$cfg[club_name]-Email", "true");
        $email->loadFooter("templates/emails/main-footer.txt");
        $email->setWordWrap(false);

        // Insert the email into the DB
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/email/insert.sql");
        $cmd->addParameter("subject", $subject);
        $cmd->addParameter("message", $message);
        $cmd->addParameter("user", $user->getUID());
        $res = $cmd->executeReader();
        $id = $res->identity();

        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/email/insert-recipients.sql");
        $cmd->addParameter("category", $category);
        $cmd->addParameter("active", $cfg['status_id']['active']);
        $cmd->addParameter("email", $id);
        if ($group) {
            $cmd->addParameter("group", $group);
        }
      	$cmd->addParameter("force", $force);

        $cmd->executeNonQuery();
    }

}

?>

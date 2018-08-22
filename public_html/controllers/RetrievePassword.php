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
 * $Id: RetrievePassword.php,v 1.2 2005/08/02 02:34:36 bps7j Exp $
 */
// {{{require declarations
include_once("Email.php");
// }}}

class RetrievePassword {
    // {{{declarations
    var $emailAddress;
    var $password;
    //}}}
    
    /* {{{checkForExistence
     *
     */
    function checkForExistence($email) {
        global $obj;
        $result = $obj['conn']->query(
            "select c_password from [_]member where c_email={email,char,30} "
                . "and c_deleted <> 1",
                array('email' => $email));
        if ($result->numRows() > 0) {
            $row = $result->fetchRow();
            $this->emailAddress = $email;
            $this->password = $row['c_password'];
            return 1;
        }
        return 0;
    } //}}}

    /* {{{sendPassword
     *
     */
    function sendPassword(&$member) {
        global $cfg;
        $email =& new Email();
        $email->loadFooter("templates/emails/footer.txt");
        $body = file_get_contents("templates/emails/retrieve-password.txt");
        $body = Template::replace($body, array(
            "BASEURL" => $cfg['site_url'] . $cfg['base_url']));
        $body = $member->insertIntoTemplate($body);
        $email->setBody($body);
        $email->setWordWrap(true);
        $email->addTo($member->getEmail());
        $email->setFrom($cfg['club_admin_email']);
        $email->setSubject("Your Club Password");
        $email->send();
    } //}}}
}
?>

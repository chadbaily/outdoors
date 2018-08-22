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
 * $Id: authorize-full.php,v 1.5 2009/11/14 22:27:45 pctainto Exp $
 */

# If the browser sent auth, we can authenticate against the database.  The user
# may have already been authenticated on a previous request, and if so the browser
# will re-send the auth every time.  Auth is trivially encoded to make it
# non-obvious.

if ($cfg['auth']['user'] && $cfg['auth']['pass']) {
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/misc/login-full.sql");
    $cmd->addParameter("active", $cfg['status_id']['active']);
    $cmd->addParameter("inactive", $cfg['status_id']['inactive']);
    $cmd->addParameter("paid", $cfg['status_id']['paid']);
    $cmd->addParameter("email", $cfg['auth']['user']);
    $cmd->addParameter("password", $cfg['auth']['pass']);
    $result = $cmd->executeReader();

    # If there are any rows, the email address exists in the database.
    if ($result->numRows()) {

        $row = $result->fetchRow();
        if ($row['password_correct']) {
        	# We now know for sure what the user's ID is!
            $cfg['user'] = $row['c_uid'];

            # Set the auth cookie.  It may get unset later
            # but that's ok.  We need it if we're going to do any redirects.
            setcookie("auth", base64_encode(serialize($cfg['auth'])), null, "/");

            # We need to check that the user has an active membership, and if
            # not the user can't access this page. We check for various special
            # cases and redirect as needed.
            #
            # a) The user has no memberships and needs to go to the
            #    renew page.
            if (!$row['total']) {
                redirect("$cfg[base_url]/members/join/renew");
            }
            # b) The user has no valid memberships (memberships that are
            #    active, the begin date is before now and the end date is
            #    after now)
            if (!$row['valid']) {
                # There are two cases: the user has expired memberships and
                # needs to renew, or the member has an inactive, pending
                # membership and needs to wait for activation.
                if ($row['pending']) {
                    redirect("$cfg[base_url]/members/join/inactive");
                }
                else {
                    redirect("$cfg[base_url]/members/join/renew");
                }
            }
        }
        else {
            $cfg['login_password'] = false;
        }
        $cfg['db_server_time'] = $row['current_timestamp'];
    }
    else {
        $cfg['login_exists'] = false;
    }
}

# If there's still no $cfg['user'], we need to get the user to enter an email
# address and password.
if (!$cfg['user']) {
    $cfg['login_status'] = false;
}
# If we got here, we have identified the user's c_uid.
else {
    $obj['user'] = new Member();
    $obj['user']->select($cfg['user']);
    $cfg['login_status'] = true;
}

?>

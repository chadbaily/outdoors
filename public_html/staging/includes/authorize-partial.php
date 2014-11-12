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
 * $Id: authorize-partial.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 */

# If the 'user' cookie exists, we can use that.  Test to see if this user is in
# the database, but only if there is NOT any auth data:
if (isset($_COOKIE['user']) && $_COOKIE['user']
    && !$cfg['auth']['user']
    && !$cfg['auth']['pass'])
{
    $result = $obj['conn']->query("select current_timestamp, [_]member.* "
        . "from [_]member where c_uid = {uid,int}",
        array('uid' => $_COOKIE['user']));
    if ($result->numRows()) {
        # We're good to go.
        $obj['user'] = new Member();
        $obj['user']->initFromRow($result->fetchRow());
        $cfg['user'] = $obj['user']->getUID();
        $cfg['db_server_time'] = $row['current_timestamp'];
        return;
    }
}

# If the browser sent auth, we can authenticate against the database.

if ($cfg['auth']['user'] && $cfg['auth']['pass']) {
    $result = $obj['conn']->query("select * from [_]member "
        . "where c_password = {password,char,30} and c_email = {username,char,60}",
        array('username' => $cfg['auth']['user'],
            'password' => $cfg['auth']['pass']));
    # If there are any rows, the email address exists in the database and the
    # user knows the right password.
    if ($result->numRows()) {
        $obj['user'] = new Member();
        $obj['user']->initFromRow($result->fetchRow());
        $cfg['user'] = $obj['user']->getUID();
        return;
    }
}

# If there's still no $cfg['user'], we need to get the user to enter an email
# address and password.
if (!$cfg['user']) {
    $cfg['login_status'] = false;
}

?>

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
 * $Id: authorize-none.php,v 1.1.1.1 2005/03/27 19:54:21 bps7j Exp $
 *
 * Purpose: ensures that the system doesn't have any login information on the
 * user.
 */

$cfg['auth'] = array("user" => "", "pass" => "");
setcookie("auth", "", time() - 3600, "/");
setcookie("user", "", time() - 3600, "/");
$cfg['login_status'] = true;

?>

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
 * $Id: not-permitted.php,v 1.2 2005/06/05 17:12:44 bps7j Exp $
 *
 */

trigger_error("Permission denied: $_SERVER[REQUEST_URI]", E_USER_NOTICE);

$res['content'] = "<h1>Permission Denied</h1><p>Sorry, you are not "
    . "permitted to view this page.</p>";
$res['title'] = "Permission Denied";

?>

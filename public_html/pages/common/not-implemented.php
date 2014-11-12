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
 * $Id: not-implemented.php,v 1.2 2005/06/05 17:10:17 bps7j Exp $
 *
 * This file is included when there's a request for an 'action' file (a file in
 * the appropriate directory that's named the same as the database action) but
 * that 'action' file can't be found.  This is where the "action not
 * implemented" page comes from.
 *
 */

trigger_error("Action not implemented: $_SERVER[REQUEST_URI]", E_USER_ERROR);

$res['content'] = "<h1>Error: Action Not Implemented</h1><p>The action <b>"
    . $cfg['actions'][$cfg['action']]['c_summary']
    . "</b> does not appear to be implemented for this type of object.</p>";
$res['title'] = "Action Not Implemented";

?>

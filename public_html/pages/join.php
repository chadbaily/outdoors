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
 * $Id: join.php,v 1.1.1.1 2005/03/27 19:53:08 bps7j Exp $
 */

$res['title'] = "Join the Club"; 

if (file_exists("$cfg[page_path]/$cfg[action].php")) {
    include_once("$cfg[page_path]/$cfg[action].php");
} else {
    include_once("$cfg[page_path]/join.php");
}

# Prepare things for the main page to process
$res['navbar'] = "Join {CLUB_NAME}";

?>

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
 * $Id: calendar.php,v 1.2 2009/03/12 03:15:58 pctainto Exp $
 * Purpose: this is the website's front page.
 */

include_once("includes/authorize.php");
include_once("EventCalendar.php");

$start = new DateTimeSC(date("Y-m") . "-01");
$end = new DateTimeSC(date("Y-m") . date("-t"));

# If there's query parameters to show a different date, use it
if (isset($_GET['date']) 
    && preg_match('/^\d\d\d\d-\d\d-\d\d$/', $_GET['date']))
{
    $start = new DateTimeSC(date("Y-m", strtotime($_GET['date'])) . "-01");
    $end = new DateTimeSC($start->toString("Y-m") . $start->toString("-t"));
}

$res['title'] = "Calendar of Events for " . $start->toString("F Y");
$res['content'] = Template::replace(
    file_get_contents("templates/calendar/default.php"), array(
        "CALENDAR" => EventCalendar::generateMonthView(
            $start, 75, true, false, true)));

$res['navbar'] = "Member's Area/Member Home Page";

?>

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
 * $Id: EventCalendar.php,v 1.3 2009/03/12 03:13:36 pctainto Exp $
 */

class EventCalendar {

    /* {{{getEvents
     * Returns an array of arrays for the given period's data.  The array looks
     * like
     * array (
     *   array( // One per day in the range
     *     "date" => DateTimeSC,
     *     "events" => array(
     *       array ("title" => string, "uid" => int)
     *       ...
     *     )
     *   ),
     *   ...
     * )
     * $fill means whether to expand the "selection" to fill out partial
     * beginning and ending weeks.
     */
    function getEvents(/*DateTimeSC*/ $start, /*DateTimeSC*/ $end, $fill = false) {
        global $obj;
        global $cfg;
        $cal = array();

        # Fill out partial weeks
        if ($fill) {
            $start = $start->addDays(-1 * $start->getDayOfWeek());
            $end = $end->addDays(6 - $end->getDayOfWeek());
        }

        # Generate an array that's empty for the entire range of days, keyed on
        # the ISO representation of the date
        $temp = $start;
        while ($temp->compareTo($end) <= 0) {
            $cal[$temp->toString("Y-m-d")] = array(
                "date" => $temp,
                "events" => array());
            $temp = $temp->addDays(1);
        }

        # Query the DB to see what's in it for the given date range
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/calendar.sql");
        $cmd->addParameter("active", $cfg['status_id']['active']);
        $cmd->addParameter("start", $start->toString("Y-m-d"));
        $cmd->addParameter("end", $end->toString("Y-m-d"));
        $result = $cmd->executeReader();

        # Add the adventures in the result set to the array to return, making
        # certain not to add adventures to elements in the array that are
        # outside the bounds of the dates we want to show.
        while ($row = $result->fetchRow()) {
            $st = new DateTimeSC(substr($row['c_start_date'], 0, 10));
            if ($st->compareTo($start) < 0) {
                $st = new DateTimeSC($start->toString("Y-m-d"));
            }
            $et = new DateTimeSC(substr($row['c_end_date'], 0, 10));
            while ($st->compareTo($et) <= 0 and $st->compareTo($end) <= 0) {
                $cal[$st->toString("Y-m-d")]['events'][] = array (
                    "title" => $row['c_title'],
                    "description" => $row['c_description'],
                    "start" => $row['c_start_date'],
                    "end" => $row['c_end_date'],
                    "uid" => $row['c_uid']);
                $st = $st->addDays(1);
            }
        }

        return $cal;
    }

    function generateMonthView($start, $size,
        $showEvents = true, $abbrev = false, $fill = false)
    {
        $end = new DateTimeSC($start->toString("Y-m-t"));
        # figure out what the next and last months should be
        $next = $start->addMonths(1);
        $last = $start->addMonths(-1);

        # Create text to decorate the calendar
        $title = $start->toString("F Y");
        $nextLink = $abbrev ? "&raquo;" : "&raquo; next";
        $lastLink = $abbrev ? "&laquo;" : "last &laquo;";
        $nextVal = $next->toString("Y-m-d");
        $lastVal = $last->toString("Y-m-d");
        $days = $abbrev
            ? array("S", "M", "T", "W", "R", "F", "S")
            : array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");

        # Create the top of the table
        $result = "<table class='calendar'><tr>"
            . "<th><a href='members/calendar?date=$lastVal'>$lastLink</a></th>"
            . "<th colspan='5'>$title</th>"
            . "<th><a href='members/calendar?date=$nextVal'>$nextLink</a></th></tr><tr>";
        foreach ($days as $day) {
            $result .= "<th width='$size'>$day</th>";
        }
        $result .= "</tr>";

        $events = EventCalendar::getEvents($start, $end, $fill);
        $keys = array_keys($events);

        # Print out the blank days leading up to the first day in the array
        $result .= "<tr height=$size>";
        $startDay = intval($events[$keys[0]]['date']->getDayOfWeek());
        for ($i = 0; $i < $startDay; ++$i) {
            $result .= "<td>&nbsp;</td>";
        }

        # Print out each day
        $started = false;
        $past = true;
        foreach ($events as $key => $arr) {
            if ($arr['date']->getDayOfWeek() % 7 == 0 && $started) {
                $result .= "</tr><tr height=$size>";
            }
            if ($arr['date']->compareTo(new DateTimeSC()) >= 0) {
                $past = false;
            }
            $started = true;
            if ($showEvents) {
                $result .= "<td style='width:{$size}px; height:{$size}px; overflow:hidden' class='" 
                    . $arr['date']->toString("M") . "'>" . $arr['date']->getDay();
                foreach ($arr['events'] as $ev) {
                    $result .= "<div style='width:{$size}px; font-size:75%'><a href='members/adventure"
                        . "/read/$ev[uid]' title='"
                        . str_replace("'", "", $ev['title']) 
                        . " (" . date("g A D", strtotime($ev['start']))
                        . " - " . date("g A D", strtotime($ev['end'])) . ")'"
                        . ($past ? " class='past'" : "")
                        . ">$ev[title]</a></div>";
                }
            }
            else {
                $result .= "<td class='" . $arr['date']->toString("M");
                if (count($arr['events'])) {
                    $result .= " hasEvent";
                }
                $result .= "'>" . $arr['date']->getDay();
            }
            $result .= "</td>";
        }

        # Print out the rest of the last row
        $lastDay = $events[$keys[count($keys) - 1]]['date']->getDayOfWeek();
        for ($i = $lastDay; $i < 6; ++$i) {
            $result .= "<td>&nbsp;</td>";
        }

        $result .= "</tr></table>";
        return $result;
    }

}

?>

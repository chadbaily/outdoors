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
 * $Id: adventure.php,v 1.5 2009/03/12 03:16:00 pctainto Exp $
 */

include_once("database_object.php");

class adventure extends database_object {
    // {{{declarations
    var $c_title = null;
    var $c_description = null;
    var $c_destination = null;
    var $c_departure = null;
    var $c_start_date = null;
    var $c_end_date = null;
    var $c_signup_date = null;
    var $c_fee = null;
    var $c_max_attendees = null;
    var $c_average_rating = null;
    var $c_num_ratings = null;
    var $c_waitlist_only = null;
    // }}}

    /* {{{constructor
     *
     */
    function adventure() {
        $this->database_object();
    } //}}}

    /* {{{isFull
     * Returns true if the adventure already has as many attendees as it can
     * take.
     */
    function isFull() {
        return (count($this->getChildren("attendee", "c_adventure")) >= $this->getMaxAttendees());
    } //}}}

    /* {{{getTitle
     *
     */
    function getTitle() {
        return $this->c_title;
    } //}}}

    /* {{{setTitle
     *
     */
    function setTitle($value) {
        $this->c_title = $value;
    } //}}}

    /* {{{getDescription
     *
     */
    function getDescription() {
        return $this->c_description;
    } //}}}

    /* {{{setDescription
     *
     */
    function setDescription($value) {
        $this->c_description = $value;
    } //}}}

    /* {{{getDestination
     *
     */
    function getDestination() {
        return $this->c_destination;
    } //}}}

    /* {{{setDestination
     *
     */
    function setDestination($value) {
        $this->c_destination = $value;
    } //}}}

    /* {{{getDeparture
     *
     */
    function getDeparture() {
        return $this->c_departure;
    } //}}}

    /* {{{setDeparture
     *
     */
    function setDeparture($value) {
        $this->c_departure = $value;
    } //}}}

    /* {{{getStartDate
     *
     */
    function getStartDate() {
        return $this->c_start_date;
    } //}}}

    /* {{{setStartDate
     *
     */
    function setStartDate($value) {
        $this->c_start_date = date("Y-m-d H:i:s", strtotime($value));
    } //}}}

    /* {{{getEndDate
     *
     */
    function getEndDate() {
        return $this->c_end_date;
    } //}}}

    /* {{{setEndDate
     *
     */
    function setEndDate($value) {
        $this->c_end_date = date("Y-m-d H:i:s", strtotime($value));
    } //}}}

    /* {{{getSignupDate
     *
     */
    function getSignupDate() {
        return $this->c_signup_date;
    } //}}}

    /* {{{setSignupDate
     *
     */
    function setSignupDate($value) {
        $this->c_signup_date = date("Y-m-d H:i:s", strtotime($value));
    } //}}}

    /* {{{getFee
     *
     */
    function getFee() {
        return $this->c_fee;
    } //}}}

    /* {{{setFee
     *
     */
    function setFee($value) {
        $this->c_fee = $value;
    } //}}}

    /* {{{getWaitlistOnly
     *
     */
    function getWaitlistOnly() {
        return $this->c_waitlist_only;
    } //}}}

    /* {{{setWaitlistOnly
     *
     */
    function setWaitlistOnly($value) {
        $this->c_waitlist_only = $value;
    } //}}}

    /* {{{getMaxAttendees
     *
     */
    function getMaxAttendees() {
        return $this->c_max_attendees;
    } //}}}

    /* {{{setMaxAttendees
     *
     */
    function setMaxAttendees($value) {
        $this->c_max_attendees = $value;
    } //}}}

    /* {{{setAverageRating
     * Don't use this.  It should be automatically done when you insert a new
     * AdventureComment.
     */
    function setAverageRating($value) {
        $this->c_average_rating = $value;
    } //}}}

    /* {{{getAverageRating
     */
    function getAverageRating() {
        return $this->c_average_rating;
    } //}}}

    /* {{{setNumRatings
     * Don't use this.  It should be automatically done when you insert a new
     * AdventureComment.
     */
    function setNumRatings($value) {
        $this->c_num_ratings = $value;
    } //}}}

    /* {{{getNumRatings
     */
    function getNumRatings() {
        return $this->c_num_ratings;
    } //}}}

    function getAttendees(/*string*/ $status) {
        global $obj;
        global $cfg;
        $res = array();
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/adventure/select-attendees.sql");
        $cmd->addParameter("adventure", $this->c_uid);
        $cmd->addParameter("status", $cfg['status_id'][$status]);
        $result = $cmd->executeReader();
        while ($row = $result->fetchRow()) {
            $res[$row['c_uid']] = new Attendee();
            $res[$row['c_uid']]->initFromRow($row);
        }
        return $res;
    }

}

?>

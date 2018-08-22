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
 * $Id: application.php,v 1.1 2009/11/14 22:50:50 pctainto Exp $
 */

include_once("database_object.php");

class application extends database_object {
    // {{{declarations
    var $c_title = null;
    var $c_yearsleft = null;
    var $c_yearinschool = null;
    var $c_club = null;
    var $c_leadership = null;
    var $c_climbing = null;
    var $c_kayaking = null;
    var $c_biking = null;
    var $c_hiking = null;
    var $c_caving = null;
    var $c_snowsports = null;
    var $c_other = null;
    var $c_whyofficer = null;
    var $c_purchasing = null;
    var $c_treasurer = null;
    var $c_quartermaster = null;
    var $c_advisor = null;
    var $c_member = null;
    // }}}

    /* {{{constructor
     *
     */
    function application() {
        $this->database_object();
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

    /* {{{getYearInSchool
     *
     */
    function getYearInSchool() {
        return $this->c_yearinschool;
    } //}}}

    /* {{{setYearInSchool
     *
     */
    function setYearInSchool($value) {
        $this->c_yearinschool = $value;
    } //}}}

    /* {{{getYearsLeft
     *
     */
    function getYearsLeft() {
        return $this->c_yearsleft;
    } //}}}

    /* {{{setYearsLeft
     *
     */
    function setYearsLeft($value) {
        $this->c_yearsleft = $value;
    } //}}}

    /* {{{getClubExperience
     */
    function getClubExperience() {
        return $this->c_club;
    } //}}}

    /* {{{setClubExperience
     */
    function setClubExperience($value) {
        $this->c_club = $value;
    } //}}}

    /* {{{getLeadershipExperience
     */
    function getLeadershipExperience() {
        return $this->c_leadership;
    } //}}}

    /* {{{setLeadershipExperience
     */
    function setLeadershipExperience($value) {
        $this->c_leadership = $value;
    } //}}}

    /* {{{getClimbingExperience
     */
    function getClimbingExperience() {
        return $this->c_climbing;
    } //}}}

    /* {{{setClimbingExperience
     */
    function setClimbingExperience($value) {
        $this->c_climbing = $value;
    } //}}}

    /* {{{getKayakingExperience
     */
    function getKayakingExperience() {
        return $this->c_kayaking;
    } //}}}

    /* {{{setKaykingExperience
     */
    function setKayakingExperience($value) {
        $this->c_kayaking = $value;
    } //}}}

    /* {{{getBikingExperience
     */
    function getBikingExperience() {
        return $this->c_biking;
    } //}}}

    /* {{{setBikingExperience
     */
    function setBikingExperience($value) {
        $this->c_biking = $value;
    } //}}}

    /* {{{getHikingExperience
     */
    function getHikingExperience() {
        return $this->c_hiking;
    } //}}}

    /* {{{setHikingExperience
     */
    function setHikingExperience($value) {
        $this->c_hiking = $value;
    } //}}}

    /* {{{getCavingExperience
     */
    function getCavingExperience() {
        return $this->c_caving;
    } //}}}

    /* {{{setCavingExperience
     */
    function setCavingExperience($value) {
        $this->c_caving = $value;
    } //}}}

    /* {{{getSnowsportsExperience
     */
    function getSnowsportsExperience() {
        return $this->c_snowsports;
    } //}}}

    /* {{{setSnowsportsExperience
     */
    function setSnowsportsExperience($value) {
        $this->c_snowsports = $value;
    } //}}}

    /* {{{getOtherExperience
     */
    function getOtherExperience() {
        return $this->c_other;
    } //}}}

    /* {{{setOtherExperience
     */
    function setOtherExperience($value) {
        $this->c_other = $value;
    } //}}}

    /* {{{getWhyOfficer
     */
    function getWhyOfficer() {
        return $this->c_whyofficer;
    } //}}}

    /* {{{setWhyOfficer
     */
    function setWhyOfficer($value) {
        $this->c_whyofficer = $value;
    } //}}}

    /* {{{getPurchasingInterest
     */
    function getPurchasingInterest() {
        return $this->c_purchasing;
    } //}}}

    /* {{{setPurchasingInterest
     */
    function setPurchasingInterest($value) {
        $this->c_purchasing = $value;
    } //}}}

    /* {{{getTreasurerInterest
     */
    function getTreasurerInterest() {
        return $this->c_treasurer;
    } //}}}

    /* {{{setTreasurerInterest
     */
    function setTreasurerInterest($value) {
        $this->c_treasurer = $value;
    } //}}}

    /* {{{getQuartermasterInterest
     */
    function getQuartermasterInterest() {
        return $this->c_quartermaster;
    } //}}}

    /* {{{setQuartermasterInterest
     */
    function setQuartermasterInterest($value) {
        $this->c_quartermaster = $value;
    } //}}}

    /* {{{getAdvisorInterest
     */
    function getAdvisorInterest() {
        return $this->c_advisor;
    } //}}}

    /* {{{setAdvisorInterest
     */
    function setAdvisorInterest($value) {
        $this->c_advisor = $value;
    } //}}}

    /* {{{getMember
     */
    function getMember() {
        return $this->c_member;
    } //}}}

    /* {{{setMember
     */
    function setMember($value) {
        $this->c_member = $value;
    } //}}}


}
?>

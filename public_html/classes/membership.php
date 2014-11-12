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
 * $Id: membership.php,v 1.1.1.1 2005/03/27 19:54:28 bps7j Exp $
 */

include_once("database_object.php");

class membership extends database_object {
    // {{{declarations
    var $c_member = null;
    var $c_type = null;
    var $c_begin_date = null;
    var $c_expiration_date = null;
    var $c_units_granted = null;
    var $c_unit = null;
    var $c_total_cost = null;
    var $c_amount_paid = null;
    // }}}

    /* {{{constructor
     *
     */
    function membership() {
        $this->database_object();
    } //}}}

    /* {{{getMember
     *
     */
    function getMember() {
        return $this->c_member;
    } //}}}

    /* {{{setMember
     *
     */
    function setMember($value) {
        $this->c_member = $value;
    } //}}}

    /* {{{getType
     *
     */
    function getType() {
        return $this->c_type;
    } //}}}                             

    /* {{{setType
     *
     */
    function setType($value) {
        $this->c_type = $value;
    } //}}}

    /* {{{getBeginDate
     *
     */
    function getBeginDate() {
        return $this->c_begin_date;
    } //}}}

    /* {{{setBeginDate
     *
     */
    function setBeginDate($value) {
        $this->c_begin_date = is_null($value) ? null : date("Y-m-d", strtotime($value));
    } //}}}

    /* {{{getExpirationDate
     *
     */
    function getExpirationDate() {
        return $this->c_expiration_date;
    } //}}}

    /* {{{setExpirationDate
     *
     */
    function setExpirationDate($value) {
        $this->c_expiration_date = is_null($value) ? null : date("Y-m-d", strtotime($value));
    } //}}}

    /* {{{getUnitsGranted
     *
     */
    function getUnitsGranted() {
        return $this->c_units_granted;
    } //}}}                             

    /* {{{setUnitsGranted
     *
     */
    function setUnitsGranted($value) {
        $this->c_units_granted = $value;
    } //}}}

    /* {{{getUnit
     *
     */
    function getUnit() {
        return $this->c_unit;
    } //}}}                             

    /* {{{setUnit
     *
     */
    function setUnit($value) {
        $this->c_unit = $value;
    } //}}}

    /* {{{getTotalCost
     *
     */
    function getTotalCost() {
        return $this->c_total_cost;
    } //}}}                             

    /* {{{setTotalCost
     *
     */
    function setTotalCost($value) {
        $this->c_total_cost = $value;
    } //}}}

    /* {{{getAmountPaid
     *
     */
    function getAmountPaid() {
        return $this->c_amount_paid;
    } //}}}                             

    /* {{{setAmountPaid
     *
     */
    function setAmountPaid($value) {
        $this->c_amount_paid = $value;
    } //}}}

}
?>

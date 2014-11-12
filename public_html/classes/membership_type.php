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
 * $Id: membership_type.php,v 1.2 2005/08/02 02:32:55 bps7j Exp $
 */

include_once("database_object.php");

class membership_type extends database_object {
    // {{{declarations
    var $c_title = null;
    var $c_description = null;
    var $c_begin_date = null;
    var $c_expiration_date = null;
    var $c_show_date = null;
    var $c_hide_date = null;
    var $c_units_granted = null;
    var $c_unit = null;
    var $c_unit_cost = null;
    var $c_total_cost = null;
    var $c_flexible = null;
    var $c_hidden = null;
    // }}}

    /* {{{constructor
     *
     */
    function membership_type() {
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

    /* {{{getShowDate
     *
     */
    function getShowDate() {
        return $this->c_show_date;
    } //}}}

    /* {{{setShowDate
     *
     */
    function setShowDate($value) {
        $this->c_show_date = date("Y-m-d", strtotime($value));
    } //}}}

    /* {{{getHideDate
     *
     */
    function getHideDate() {
        return $this->c_hide_date;
    } //}}}

    /* {{{setHideDate
     *
     */
    function setHideDate($value) {
        $this->c_hide_date = date("Y-m-d", strtotime($value));
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

    /* {{{getUnitCost
     *
     */
    function getUnitCost() {
        return $this->c_unit_cost;
    } //}}}                             

    /* {{{setUnitCost
     *
     */
    function setUnitCost($value) {
        $this->c_unit_cost = $value;
    } //}}}

    /* {{{getFlexible
     *
     */
    function getFlexible() {
        return $this->c_flexible;
    } //}}}                             

    /* {{{setFlexible
     *
     */
    function setFlexible($value) {
        $this->c_flexible = $value;
    } //}}}

    /* {{{getHidden
     *
     */
    function getHidden() {
        return $this->c_hidden;
    } //}}}                             

    /* {{{setHidden
     *
     */
    function setHidden($value) {
        $this->c_hidden = $value;
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

}
?>

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
 * $Id: member.php,v 1.4 2009/03/12 03:16:00 pctainto Exp $
 */

include_once("member_note.php");
include_once("database_object.php");

class member extends database_object {
    // {{{declarations
    var $c_first_name = null;
    var $c_last_name = null;
    var $c_full_name = null;
    var $c_gender = null;
    var $c_email = null;
    var $c_password = null;
    var $c_birth_date = null;
    var $c_group_memberships = null;
    var $c_receive_email = null;
    var $c_is_student = null;
    var $c_hidden = null;
    var $c_email_hidden = null;
    // }}}

    /* {{{constructor
     *
     */
    function member() {
        $this->database_object();
    } //}}}

    /* {{{insert
     *
     */
    function insert() {
        // Make sure this is valid for the DB
        $this->setGender($this->getGender());
        return $this->doInsert();
    } //}}}

    /* {{{update
     *
     */
    function update() {
        // Do some setup... make sure this is valid for the DB
        $this->setGender($this->getGender());
        $this->doUpdate();
    } //}}}

    /* {{{addNote
     * Adds a MemberNote to the member
     */
    function addNote($noteText) {
        global $cfg;
        $note = new member_note();
        if (!isset($cfg['user'])) {
            $note->setOwner($cfg['root_uid']);
            $note->setCreator($cfg['root_uid']);
        }
        $note->setMember($this->c_uid);
        $note->setNote($noteText);
        $note->insert();
    } //}}}
    
    /* {{{selectFromEmail
     * Selects the member based on email address rather than primary key.
     */
    function selectFromEmail($email) {
        global $obj;
        $result = $obj['conn']->query("select * from $this->table where c_email='$email'");
        if ($result->numRows() > 0) {
            $row = $result->fetchRow();
            $this->initFromRow($row);
            return $this->c_uid;
        }
        return 0;
    } //}}}
    
    /* {{{getFullName
     */
    function getFullName() {
        return $this->c_full_name;
    } //}}}

    /* {{{prepFullName
     * This is "private" in that it should only be called from within this
     * class.  When someone updates the first or last name, this ought to get
     * updated too.
     */
    function prepFullName() {
        $this->c_full_name = "$this->c_first_name $this->c_last_name";
    } //}}}

    /* {{{getFirstName
     *
     */
    function getFirstName() {
        return $this->c_first_name;
    } //}}}
    
    /* {{{setFirstName
     *
     */
    function setFirstName($value) {
        $this->c_first_name = $value;
        $this->prepFullName();
    } //}}}

    /* {{{getLastName
     *
     */
    function getLastName() {
        return $this->c_last_name;
    } //}}}

    /* {{{setLastName
     *
     */
    function setLastName($value) {
        $this->c_last_name = $value;
        $this->prepFullName();
    } //}}}

    /* {{{getGender
     *
     */
    function getGender() {
        return ($this->c_gender == 'm' ? 'm' : 'f');
    } //}}}

    /* {{{setGender
     *
     */
    function setGender($value) {
        $this->c_gender = ( strcasecmp($value, 'm') < 0 ? 'f' : 'm');
    } //}}}

    /* {{{getEmail
     *
     */
    function getEmail() {
        return $this->c_email;
    } //}}}
    
    /* {{{setEmail
     *
     */
    function setEmail($value) {
        $this->c_email = $value;
    } //}}}

    /* {{{getReceiveEmail
     *
     */
    function getReceiveEmail() {
        return $this->c_receive_email;
    } //}}}

    /* {{{setReceiveEmail
     *
     */
    function setReceiveEmail($value) {
        $this->c_receive_email = $value;
    } //}}}

    /* {{{getIsStudent
     *
     */
    function getIsStudent() {
        return $this->c_is_student;
    } //}}}

    /* {{{setIsStudent
     *
     */
    function setIsStudent($value) {
        $this->c_is_student = $value;
    } //}}}

    /* {{{getPassword
     *
     */
    function getPassword() {
        return $this->c_password;
    } //}}}

    /* {{{setPassword
     *
     */
    function setPassword($value) {
        $this->c_password = $value;
    } //}}}

    /* {{{getBirthDate
     *
     */
    function getBirthDate() {
        return $this->c_birth_date;
    } //}}}

    /* {{{setBirthDate
     *
     */
    function setBirthDate($value) {
        $this->c_birth_date = date("Y-m-d", strtotime($value));
    } //}}}

    /* {{{getPrimaryAddress
     * May return null if there is no primary address.
     */
    function getPrimaryAddress() {
        require_once("address.php");
        global $obj;
        global $cfg;
        $result = $obj['conn']->query("select * from [_]address "
            . "where c_owner = $this->c_uid and c_deleted <> 1 "
            . "and c_primary = 1");
        if ($result->numRows()) {
            $address = new address();
            $address->initFromRow($result->fetchRow());
            return $address;
        }
		return null;
    } //}}}

    /* {{{getPrimaryPhoneNumber
     * May return null if there is no primary phone number.
     */
    function getPrimaryPhoneNumber() {
        require_once("phone_number.php");
        global $obj;
        global $cfg;
        $result = $obj['conn']->query("select * from [_]phone_number "
            . "where c_owner = $this->c_uid and c_deleted <> 1 "
            . "and c_primary = 1");
        if ($result->numRows()) {
            $num = new phone_number();
            $num->initFromRow($result->fetchRow());
            return $num;
        }
		return null;
    } //}}}

    /* {{{getPrimaryChat
     * May return null if there is no primary chat
     */
    function getPrimaryChat() {
        require_once("chat.php");
        global $obj;
        global $cfg;
        $result = $obj['conn']->query("select * from [_]chat "
            . "where c_owner = $this->c_uid and c_deleted <> 1 "
            . "and c_primary = 1");
        if ($result->numRows()) {
            $chat = new chat();
            $chat->initFromRow($result->fetchRow());
            return $chat;
        }
		return null;
    } //}}}

    /* {{{isRootUser
     * Returns true if the member is a root user.
     */
    function isRootUser() {
        global $cfg;
        return ($this->c_uid == $cfg['root_uid'] 
            || $this->isInGroup("root"));
    } //}}}

    /* {{{isInGroup
     * Returns true if the member is in $group.  $group can be either the
     * integer c_uid or the string c_title of the group.
     */
    function isInGroup($group) {
        global $cfg;
        if (!is_numeric($group)) {
            $group = $cfg['group_id'][$group];
        }

        return (($group & $this->c_group_memberships) != 0) ? 1 : 0;
    } //}}}

    /* {{{setInGroup
     * Sets the bitmask that specifies which groups the user belongs to.
     */
    function setInGroup($group, $value) {
        global $cfg;
        if (!is_numeric($group)) {
            $group = $cfg['group_id'][$group];
        }

        # Watch out for bitwise operations on null values... nothing happens
        if (!$this->c_group_memberships) {
            $this->c_group_memberships = 0;
        }

        $this->c_group_memberships = $value
            ? $this->c_group_memberships | $group
            : $this->c_group_memberships & (~ $group);
    } //}}}

    /* {{{getEmailHidden
     *
     */
    function getEmailHidden() {
        return $this->c_email_hidden;
    } //}}}                             

    /* {{{setEmailHidden
     *
     */
    function setEmailHidden($value) {
        $this->c_email_hidden = $value;
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

}
?>

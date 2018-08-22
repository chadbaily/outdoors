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
 * $Id: email_list.php,v 1.3 2009/03/12 03:16:00 pctainto Exp $
 *
 * Mailman lists are really stupid with the requirement to have a password.  We
 * auto-generate a random password at subscribe time, then remember it in the DB
 * for unsubscription.  That way the user never needs to know about it.
 */

include_once("database_object.php");
include_once("subscription.php");
include_once("Email.php");

class email_list extends database_object {
    // {{{declarations
    var $c_title = null;
    var $c_description = null;
    var $c_name = null;
    var $c_password = null;
    var $c_owner_address = null;
    var $c_mgmt_address = null;
    var $c_list_address = null;
    var $c_type = null;
    var $c_subject_prefix = null;
    var $subscriptionRequests;
    var $unsubscriptionRequests;
    // }}}

    /* {{{constructor
     *
     */
    function email_list() {
        $this->database_object();
        $this->subscriptionRequests = array();
        $this->unsubscriptionRequests = array();
    } //}}}

    /* {{{isSubscribed
     */
    function isSubscribed(&$member) {
        global $obj;
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/email_list/check-subscribed.sql");
        $cmd->addParameter("owner", $member->getUID());
        $cmd->addParameter("list", $this->c_uid);
        $cmd->addParameter("email", $member->getEmail());
        return (bool) $cmd->executeScalar();
    } //}}}

    /* {{{subscribe
     */
    function subscribe(&$member) {
        $this->subscriptionRequests[] = $member;
    }

    /* {{{unsubscribe
     */
    function unsubscribe(&$subscription) {
        $this->unsubscriptionRequests[] = $subscription;
    } //}}}

    /* {{{processRequests
     * You need to call this after calling subscribe() or unsubscribe().  This
     * method will take action on those requests, which have been saved.
     */
    function processRequests() {
        $sub = "";
        $unsub = "";
        $body = "";

        switch ($this->c_type) {
        case "MailmanList":
            $unsub = "unsubscribe {C_PASSWORD} address={C_EMAIL}\r\n";
            $sub = "subscribe {C_PASSWORD} address={C_EMAIL}\r\n";
            break;
        default:
            trigger_error("I don't know what to do with "
                . "a $this->c_type mailing list", E_USER_ERROR);
            break;
        }

        # Add the subscription and unsubscription requests
        $email = new Email();
        foreach ($this->unsubscriptionRequests as $subscription) {
            $body .= $subscription->insertIntoTemplate($unsub);
            $subscription->delete(true);
        }
        foreach ($this->subscriptionRequests as $member) {
            if (!$this->isSubscribed($member)) {
                $password = getRandomString(8);
                $subscription = new Subscription();
                $subscription->setOwner($member->getUID());
                $subscription->setList($this->c_uid);
                $subscription->setEmail($member->getEmail());
                $subscription->setPassword($password);
                $subscription->insert();
                $body .= $subscription->insertIntoTemplate($sub);
            }
        }

        $email->setBody($body);
        $email->setFrom($this->c_owner_address);
        $email->setSubject($this->getTitle() . " management request");
        $email->addTo($this->c_mgmt_address);
        $email->addBCC($this->c_owner_address);
        $email->addHeader("Reply-To", $this->c_owner_address);
        $email->addHeader("Return-Path", $this->c_owner_address);
        $email->send();
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

    /* {{{getName
     *
     */
    function getName() {
        return $this->c_name;
    } //}}}

    /* {{{setName
     *
     */
    function setName($value) {
        $this->c_name = $value;
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

    /* {{{getOwnerAddress
     *
     */
    function getOwnerAddress() {
        return $this->c_owner_address;
    } //}}}

    /* {{{setOwnerAddress
     *
     */
    function setOwnerAddress($value) {
        $this->c_owner_address = $value;
    } //}}}

    /* {{{getMgmtAddress
     *
     */
    function getMgmtAddress() {
        return $this->c_mgmt_address;
    } //}}}

    /* {{{setMgmtAddress
     *
     */
    function setMgmtAddress($value) {
        $this->c_mgmt_address = $value;
    } //}}}

    /* {{{getListAddress
     *
     */
    function getListAddress() {
        return $this->c_list_address;
    } //}}}

    /* {{{setListAddress
     *
     */
    function setListAddress($value) {
        $this->c_list_address = $value;
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

    /* {{{getSubjectPrefix
     *
     */
    function getSubjectPrefix() {
        return $this->c_subject_prefix;
    } //}}}

    /* {{{setSubjectPrefix
     *
     */
    function setSubjectPrefix($value) {
        $this->c_subject_prefix = $value;
    } //}}}

}
?>

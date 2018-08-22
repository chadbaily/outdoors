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
 * $Id: payment.php,v 1.2 2009/11/14 22:13:10 pctainto Exp $
 */

include_once("database_object.php");

class payment extends database_object {
    // {{{declarations
	var $c_payer_id = null;
	var $c_payment_date = null;
	var $c_txn_id = null;
	var $c_first_name = null;
	var $c_last_name = null;
	var $c_payer_email = null;
	var $c_payer_status = null;
	var $c_payment_type = null;
	var $c_memo = null;
	var $c_item_name = null;
	var $c_item_number = null;
	var $c_quantity = null;
	var $c_mc_gross = null;
	var $c_mc_currency = null;
	var $c_address_name = null;
	var $c_address_street = null;
	var $c_address_city = null;
	var $c_address_state = null;
	var $c_address_zip = null;
	var $c_address_country = null;
	var $c_address_status = null;
	var $c_payer_business_name = null;
	var $c_payment_status = null;
	var $c_pending_reason = null;
	var $c_reason_code = null;
	var $c_txn_type = null;
    // }}}

    /* {{{constructor
     *
     */
    function payment() {
        $this->database_object();
    } //}}}
    
    function initialize($vars) {
    	$this->setPayerId($vars['payer_id']);
    	$this->setPaymentDate($vars['payment_date']);
    	$this->setTxnId($vars['txn_id']);
    	$this->setFirstName($vars['first_name']);
    	$this->setLastName($vars['last_name']);
    	$this->setPayerEmail($vars['payer_email']);
    	$this->setPayerStatus($vars['payer_status']);
    	$this->setPaymentType($vars['payment_type']);
    	$this->setMemo($vars['memo']);
    	$this->setItemName($vars['item_name1']);
    	$this->setItemNumber($vars['item_number1']);
    	$this->setQuantity($vars['quantity1']);
    	$this->setMcGross($vars['mc_gross_1']);
    	$this->setMcCurrency($vars['mc_currency']);
    	$this->setAddressName($vars['address_name']);
    	$this->setAddressStreet($vars['address_street']);
    	$this->setAddressCity($vars['address_city']);
    	$this->setAddressState($vars['address_state']);
    	$this->setAddressZip($vars['address_zip']);
    	$this->setAddressCountry($vars['address_country']);
    	$this->setPayerBusinessName($vars['payer_business_name']);
    	$this->setPaymentStatus($vars['payment_status']);
    	$this->setPendingReason($vars['pending_reason']);
    	$this->setReasonCode($vars['reason_code']);
    	$this->setTxnType($vars['txn_type']);
    }
    
    function getPayerId() {
    	return $this->c_payer_id;
    }
    
    function setPayerId($value) {
    	$this->c_payer_id = $value;
    }
    
    function getPaymentDate() {
    	return $this->c_payment_date;
    }
    
    function setPaymentDate($value) {
    	$this->c_payment_date = $value;
    }
    
    function getTxnId() {
    	return $c_txn_id;
    }
    
    function setTxnId($value) {
    	$this->c_txn_id = $value;
    }
    
    function getFirstName() {
    	return $this->c_first_name;
    }
    
    function setFirstName($value) {
    	$this->c_first_name = $value;
    }
    
    function getLastName() {
    	return $this->c_last_name;
    }
    
    function setLastName($value) {
    	$this->c_last_name = $value;
    }
    
    function getPayerEmail() {
    	return $this->c_payer_email;
    }
    
    function setPayerEmail($value) {
    	$this->c_payer_email = $value;
    }
    
    function getPayerStatus() {
    	return $this->c_payer_status;
    }
    
    function setPayerStatus($value) {
    	$this->c_payer_status = $value;
    }
    
    function getPaymentType() {
    	return $this->c_payment_type;
    }
    
    function setPaymentType($value) {
    	$this->c_payment_type = $value;
    }
    
    function getMemo() {
    	return $this->c_memo;
    }
    
    function setMemo($value) {
    	$this->c_memo = $value;
    }
    
    function getItemName() {
    	return $this->c_item_name;
    }
    
    function setItemName($value) {
    	$this->c_item_name = $value;
    }
    
    function getItemNumber() {
    	return $this->c_item_number;
    }
    
    function setItemNumber($value) {
    	$this->c_item_number = $value;
    }

    function getQuantity() {
    	return $this->c_quantity;
    }
    
    function setQuantity($value) {
    	$this->c_quantity = $value;
    }
    
    function getMcGross() {
    	return $this->c_mc_gross;
    }
    
    function setMcGross($value) {
    	$this->c_mc_gross = $value;
    }
    
    function getMcCurrency() {
    	return $this->c_mc_currency;
    }
    
    function setMcCurrency($value) {
    	$this->c_mc_currency = $value;
    }
    
    function getAddressName() {
    	return $this->c_address_name;
    }
    
    function setAddressName($value) {
    	$this->c_address_name = $value;
    }
    
    function getAddressStreet() {
    	return $this->c_address_street;
    }
    
    function setAddressStreet($value) {
    	$this->c_address_street = $value;
    }
    
    function getAddressCity() {
    	return $this->c_address_city;
    }
    
    function setAddressCity($value) {
    	$this->c_address_city = $value;
    }
    
    function getAddressState() {
    	return $this->address_state;
    }
    
    function setAddressState($value) {
    	$this->c_address_state = $value;
    }
    
    function getAddressZip() {
    	return $this->c_address_zip;
    }
    
    function setAddressZip($value) {
    	$this->c_address_zip = $value;
    }
    
    function getAddressCountry() {
    	return $this->c_address_country;
    }
    
    function setAddressCountry($value) {
    	$this->c_address_country = $value;
    }
    
    function getPayerBusinessName() {
    	return $this->c_payer_business_name;
    }
    
    function setPayerBusinessName($value) {
    	$this->c_payer_business_name = $value;
    }
    
    function getPaymentStatus() {
    	return $this->c_payment_status;
    }
    
    function setPaymentStatus($value) {
    	$this->c_payment_status = $value;
    }
    
    function getPendingReason() {
    	return $this->c_pending_reason;
    }
    
    function setPendingReason($value) {
    	$this->c_pending_reason = $value;
    }
    
    function getReasonCode() {
    	return $this->c_reason_code;
    }
    
    function setReasonCode($value) {
    	$this->c_reason_code = $value;
    }
    
    function getTxnType() {
    	return $this->c_txn_type;
    }
    
    function setTxnType($value) {
    	$this->c_txn_type = $value;
    }

}
?>

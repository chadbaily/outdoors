<?php
/*
 * Creates XmlFormDocument objects and manipulates them through the DOM.
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
 * $Id: XmlFormDocument.php,v 1.5 2009/03/12 03:13:36 pctainto Exp $
 */
/* 
 * Purpose:  Represents an HTML form object that is derived from a DOM tree that
 * probably comes from an XML file.
 */

include_once("Document.php");

class XMLFormDocument extends Document {

    var $isValid = true;

    // {{{XMLFormDocument
    function XMLFormDocument() {
        $this->Document();
    } //}}}

    // {{{setValue
    function setValue($name, $value) {
        // Stringify the input
        if (is_scalar($value)) {
            $value = strval($value);
        }
        elseif (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[strval($key)] = strval($val);
            }
        }
        elseif ($value) {
            trigger_error("Invalid object type " . gettype($value), E_USER_ERROR);
        }
        else {
            $value = "";
        }
        @$configElements = $this->getElementByID("config");
        $els = $configElements->selectElements(array("name" => $name), "element");
        if (!count($els)) {
            // Could not find any config element with the attribute "name" equal
            // to $name
            return null;
        }
        // Grab the first element found and use it as the configuration data for
        // the particular form control we're going to set
        $configEl = $els[0];
        // There are two types of form elements: arrays and scalars.  In
        // PHP, it is possible to make anything into an array of values by
        // naming it with a trailing pair of brackets [], so these form
        // elements are actually treated differently by the code (because
        // even though their name attribute has [], the way you get the
        // value from PHP is without them).
        if ($configEl->getAttribute("type") == "array") {
            // {{{array
            // An array means that there could be multiple values submitted
            // for this element.  Form elements that are arrays by nature
            // are
            // * CheckBox
            // * SelectMultiple
            if ($configEl->getAttribute("tag-name") == "input") {
                // It's a CheckBox array.  Unselect everything there's no data
                // for, select everything there is.
                $elements = $this->selectElements(array(
                        "name" => $configEl->getAttribute("name") . "[]",
                        "type" => "checkbox"), "input");
                if (!is_array($value)) {
                    $value = array();
                }
                foreach (array_keys($elements) as $key) {
                    $option = $elements[$key];
                    if (in_array($option->getAttribute("value"), array_values($value), TRUE)) {
                        $option->setAttribute("checked", "1");
                    }
                    else {
                        $option->removeAttribute("checked");
                    }
                }
            }
            elseif ($configEl->getAttribute("tag-name") == "select") {
                // It's a Select element with "multiple" = "1".  For this
                // element, we need to get all child elements of type
                // <option> and set the "selected" option on those that there's
                // data for.
                @$el = $this->getElementByID($configEl->getAttribute("element-id"));
                if (is_array($value)) {
                    foreach (array_keys($el->childNodes) as $key) {
                        $option = $el->childNodes[$key];
                        if ($option->nodeType == DOM_ELEMENT_NODE
                                && $option->tagName == "option") {
                            if (in_array($option->getAttribute("value"), array_values($value), TRUE)) {
                                $option->setAttribute("selected", "1");
                            }
                            else {
                                $option->removeAttribute("selected");
                            }
                        }
                    }
                }
            }
            else {
                trigger_error("There is an error in the <config> element "
                    . "of the form's XML file.  Unrecognized tag name "
                    .  $configEl->getAttribute("tag-name"), E_USER_ERROR);
            }
            // }}}
        }
        else { // 'scalar' is assumed.
            // {{{scalar
            // A scalar means that the browser submits a single value for
            // the element.  Form elements that are scalar by nature are
            // * Radio
            // * TextBox
            // * Password
            // * Hidden
            // * Button
            // * Submit
            // * Reset
            // * SelectOne
            // * TextArea
            // * A CheckBox can also be defined as scalar.

            // If there's a tag-name attribute, the element needs to be
            // identified by tag name (there might be multiples, as in a radio
            // array).  Otherwise, it needs to be identified by element-id.
            if ($configEl->getAttribute("tag-name")) {
                // We assume it's a radio button array.
                if ($configEl->getAttribute("tag-name") == "input") {
                    // Unselect everything there's no data for, select everything there is.
                    $radios = $this->selectElements(array(
                            "name" => $configEl->getAttribute("name")), "input");
                    foreach (array_keys($radios) as $key) {
                        $radio = $radios[$key];
                        if (!is_null($value) && $radio->getAttribute("value") == $value) {
                            $radio->setAttribute("checked", "1");
                        }
                        else {
                            $radio->removeAttribute("checked");
                        }
                    }
                }
                else {
                    trigger_error("I was expecting the element's tag-name to be "
                        . "'input' (don't confuse *tag-name* with *input type*!)");
                }
            }
            else {
                // Need to identify the element by the element-id
                @$el = $this->getElementByID($configEl->getAttribute("element-id"));
                if (!$el) {
                    trigger_error("Element with ID '" . $configEl->getAttribute("element-id")
                        . "' not found in the XML document.", E_USER_ERROR);
                }

                // There are several kinds of elements in the scalar category:
                // those whose value is contained in the "value" attribute,
                // those whose value is in a Text node, and those who are one of
                // several elements that must have their "selected" or "checked"
                // attribute set to true to indicate which of them is actually
                // the active element.
                if ($el->tagName == "input") {

                    $type = $el->getAttribute("type");

                    if ($type == "text"
                        || $type == "password"
                        || $type == "hidden"
                        || $type == "button"
                        || $type == "submit"
                        || $type == "reset"
                        ) {
                        // TextBox, Password, Hidden, Button, Submit, Reset
                        $el->setAttribute("value", trim($value));
                    }
                    elseif ($type == "checkbox") {
                        // CheckBox that's not an array
                        if (!is_null($value) && $value !== "") {
                            $el->setAttribute("checked", "1");
                        }
                        else {
                            $el->removeAttribute("checked");
                        }
                    }
                    else {
                        trigger_error("Invalid attribute value '$type' for "
                            . "attribute 'name' (or missing tag-name attribute "
                            . "in definition) for <input>.", E_USER_ERROR);
                    }
                }
                elseif ($el->tagName == "select") {
                    // Select
                    // Find the correct child node of the element with a value
                    // of whatever value it is, and set its "selected" attribute
                    // to "1".
                    @$options = $el->getElementsByTagName("option");
                    foreach (array_keys($options) as $key) {
                        if ($options[$key]->getAttribute("value") === $value) {
                            $options[$key]->setAttribute("selected", "1");
                        }
                        else {
                            $options[$key]->removeAttribute("selected");
                        }
                    }
                }
                elseif ($el->tagName == "textarea") {
                    // TextArea
                    $el->firstChild->data = trim($value);
                }
                // }}}
                else {
                    trigger_error("Element '$el->tagName' is not recognized or found "
                        . "while trying to perform setValue('$name', '$value').", E_USER_ERROR);
                }
            }
        }
    } //}}}

    // {{{populateFromBrowser
    function populateFromBrowser() {
        // Get the element that describes the form itself, and from this get the
        // form element and the sanity-checking input element.
        $config = $this->getElementByID("config");
        $form = $this->getElementByID($config->getAttribute("form-id"));
        if (is_null($form)) {
            trigger_error("There is no element with id "
                . $config->getAttribute("form-id") . ".  You probably "
                . "forgot to put the attribute on the form element.",
                E_USER_ERROR);
            $this->isValid = false;
            return;
        }
        $method = $form->getAttribute("method");
        @$sanityCheck = $this->getElementByID(
            $config->getAttribute("sanity-check"));
        if (is_null($form) || is_null($sanityCheck)) {
            trigger_error("You have not specified form elements correctly in "
                . "the <config> element of the XML.", E_USER_ERROR);
        }
        // See if the browser posted any form data.  If not, then there is no
        // point trying to set element values; just exit.
        if ($this->getBrowserData($method, $sanityCheck->getAttribute("name"))
                !== $sanityCheck->getAttribute("value")) {
            return;
        }
        $this->preProcess();
        // For each element described in the config element, get the element or
        // list of elements in the form, and populate them with the browser's
        // data.
        foreach (array_keys($config->childNodes) as $key) {
            // Skip whitespace nodes
            if ($config->childNodes[$key]->nodeType == DOM_ELEMENT_NODE) {
                $data = $this->getBrowserData($method,
                    $config->childNodes[$key]->getAttribute("name"));
                $this->setValue($config->childNodes[$key]->getAttribute("name"), $data);
            }
        }
    } //}}}

    // {{{preProcess
    function preProcess() {
        // Get the config element...
        @$config = $this->getElementByID("config");
        @$form = $this->getElementByID($config->getAttribute("form-id"));
        $method = $form->getAttribute("method");
        @$sanityCheck = $this->getElementByID(
            $config->getAttribute("sanity-check"));
        if (is_null($form) || is_null($sanityCheck)) {
            trigger_error("You have not specified form elements correctly in "
                . "the <config> element of the XML.", E_USER_ERROR);
        }
        // Do any pre-processing as defined in the config
        foreach (array_keys($config->childNodes) as $key) {
            // Skip whitespace nodes
            if ($config->childNodes[$key]->nodeType == DOM_ELEMENT_NODE) {
                // Call functions as defined in the "pre-processing" attribute.
                $str = $config->childNodes[$key]->getAttribute("pre-processing");
                @$el = $this->getElementByID($config->childNodes[$key]->getAttribute("element-id"));
                if (!is_null($el) && $str) {
                    foreach (explode(",", $str) as $func) {
                        if (function_exists($func)) {
                            // Really, we can only do this on <input type="text"> and <textarea>
                            if ($el->tagName == "input") {
                                $el->setAttribute("value", $func($el->getAttribute("value")));
                            }
                            elseif ($el->tagName == "textarea") {
                                $el->firstChild->data = $func($el->firstChild->data);
                            }
                        }
                        else {
                            trigger_error("Function $func doesn't exist!", E_USER_ERROR);
                        }
                    }
                }
            }
        }
    } //}}}

    // {{{checkConfig
    // TODO
    function checkConfig() {
    } //}}}

    // {{{validate
    function validate() {
        // Get the configuration information for the form
        @$config = $this->getElementByID("config");
        // The form is not valid unless it has been submitted.
        @$formEl = $this->getElementByID(
            $config->getAttribute("form-id"));
        if (is_null($formEl)) {
            trigger_error("There is no element with id "
                . $config->getAttribute("form-id") . ".  You probably "
                . "forgot to put the attribute on the form element.",
                E_USER_ERROR);
            $this->isValid = false;
            return;
        }
        $sanityCheck = $config->getAttribute("sanity-check");
        if (!$this->getBrowserData(
                $formEl->getAttribute("method"), $sanityCheck)) {
            $this->isValid = false;
            return false;
        }
        $this->preProcess();
        // Check each element referenced by the config element
        foreach (array_keys($config->childNodes) as $key) {
            // $el is the configuration information element
            $el = $config->childNodes[$key];
            if ($el->nodeType != DOM_ELEMENT_NODE) {
                continue;
            }
            if ($el->getAttribute("required") && !$this->validateRequired($el)) {
                $this->isValid = false;
                $el->setAttribute("failed-required", "1");
                continue;
            }
            if ($el->getAttribute("required-unless")
                    && !$this->getBrowserData($formEl->getAttribute("method"), $el->getAttribute("required-unless"))
                    && !$this->validateRequired($el)) {
                $this->isValid = false;
                $el->setAttribute("failed-required", "1");
                continue;
            }
            if ($el->getAttribute("required-if")
                    && $this->getBrowserData($formEl->getAttribute("method"), $el->getAttribute("required-if"))
                    && !$this->validateRequired($el)) {
                $this->isValid = false;
                $el->setAttribute("failed-required", "1");
                continue;
            }
            if ($el->getAttribute("data-type") && !$this->validateDataType($el)) {
                $this->isValid = false;
                $el->setAttribute("failed-data-type", "1");
                continue;
            }
            if ($el->getAttribute("compare-to-id") && !$this->validateComparison($el)) {
                $this->isValid = false;
                $el->setAttribute("failed-comparison", "1");
            }
        }
        return $this->isValid;
    } //}}}

    // {{{validateRequired
    function validateRequired(&$config) {
        // First, discover whether it's an array or a scalar.  If a value is
        // required, a scalar must have a value; an array must have a value for
        // at least one of its elements.
        if ($config->getAttribute("type") == "array") {
            if ($config->getAttribute("element-id")) {
                // It's a SelectMultiple.  Requires that the element be identified by ID.
                @$el = $this->getElementByID($config->getAttribute("element-id"));
                foreach (array_keys($el->childNodes) as $key) {
                    $option = $el->childNodes[$key];
                    if ($option->nodeType == DOM_ELEMENT_NODE
                            && $option->tagName == "option"
                            && $option->getAttribute("selected") == "1"
                            && $option->getAttribute("value") !== "") {
                        return true;
                    }
                }
                return false;
            }
            else {
                // It's a CheckBox array.  Get an array of elements and check
                // that at least one has the "checked" attribute.  Find elements
                // by getting all elements <input type="checkbox" name="{name}">
                // where {name} comes from the "name" attr of the <config>
                // element.
                $options = $this->selectElements(array(
                        "name" => $config->getAttribute("name") . "[]",
                        "type" => "checkbox"), "input");
                foreach (array_keys($options) as $key) {
                    if ($options[$key]->getAttribute("checked") === "1") {
                        return true;
                    }
                }
                return false;
            }
        }

        else {
            // The type is scalar (this is the default).  There are 3 kinds of
            // scalar elements: <input>, <textarea> and <select>
            if ($config->getAttribute("element-id")) {
                @$el = $this->getElementByID($config->getAttribute("element-id"));
                if ($el->tagName == "input") {
                    // There are two kinds of <input> elements: checkbox and
                    // everything else.  There may be multiple HTML elements
                    // that we need to go through for a radio, but checkbox and
                    // others are going to be single and are identified by ID.
                    // Radio buttons are special: they are in an array of
                    // elements not identified by element-id (see below)
                    if ($el->getAttribute("type") == "checkbox") {
                        if ($el->getAttribute("checked") === "1") {
                            return true;
                        }
                        return false;
                    }
                    else { // text, password, hidden, button, submit, reset
                        // Only validate text, password; the user has no control
                        // over the others.
                        if ($el->getAttribute("type") == "text"
                                || $el->getAttribute("type") == "password") {
                            if (trim($el->getAttribute("value")) === "") {
                                return false;
                            }
                            return true;
                        }
                    }
                }
                elseif ($el->tagName == "select") {
                    // At least one of the element's childNodes needs to be
                    // selected.  Elements with an empty value count as not
                    // selected.
                    @$options = $el->getElementsByTagName("option");
                    foreach (array_keys($options) as $key) {
                        if ($options[$key]->getAttribute("selected") == "1"
                            && $options[$key]->getAttribute("value") !== "")
                        {
                            return true;
                        }
                    }
                    return false;
                }
                else { // <textarea>
                    if (trim($el->firstChild->data) === "") {
                        return false;
                    }
                    return true;
                }

            }
            else {
                // It's an <input type="radio"> and we look up its elements by
                // name and type, not by ID
                $options = $this->selectElements(array(
                    "type" => "radio",
                    "name" => $config->getAttribute("name")), "input");
                foreach (array_keys($options) as $key) {
                    if ($options[$key]->getAttribute("checked") === "1") {
                        return true;
                    }
                }
                return false;
            }
        }
        return true;
    } //}}}

    // {{{validateDataType
    function validateDataType(&$config) {
        @$el = $this->getElementByID($config->getAttribute("element-id"));
        $val = "";
        switch($el->tagName) {
            case "input": // password, text
                $val = $el->getAttribute("value");
                break;
            case "textarea":
                $val = $el->childNodes[0]->data;
                break;
        }
        if (trim($val) === "") {
            return true;
        }
        switch($config->getAttribute("data-type")) {
            case "number":
                return is_numeric($val);
                break;
            case "regexp":
                return preg_match($config->getAttribute("expression"), $val);
                break;
            case "date":
                return (
                    preg_match("/^\d\d\d\d-\d\d-\d\d$/", $val)
                    && checkdate(
                        substr($val, 5, 2),
                        substr($val, 8, 2),
                        substr($val, 0, 4))
                    );
                break;
            case "datetime":
                return (preg_match("/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/", $val)
                    && DateTimeSC::parseDate($val));
                break;
            case "timestamp":
                return preg_match("/^\d{14}$/", $val);
                break;
            case "email":
                return preg_match("/^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/", $val);
                break;
            case "integer":
                return preg_match('/^\d+$/', $val);
                break;
            case "words":
                return preg_match('/^[\w\d\t ]+$/', $val);
                break;
            case "bool":
                return true;
                break;
            default:
                trigger_error("Unknown value for data-type attribute: '"
                    . $config->getAttribute("data-type") . "'", E_USER_WARNING);
                break;
        }
    } //}}}

    // {{{validateComparison
    function validateComparison(&$config) {
        // As usual, advanced validation can only be done on textbox, password,
        // and textarea
        @$el1 = $this->getElementByID($config->getAttribute("element-id"));
        $el2 = $this->getElementByID($config->getAttribute("compare-to-id"));
        $datatype = $config->getAttribute("data-type");
        // Fetch the data from both elements
        $thisValue = "";
        $otherValue = "";
        if ($el1->tagName == "input") { // textbox, password
            $thisValue = $el1->getAttribute("value");
        }
        else { // textarea
            $thisValue = $el1->childNodes[0]->data;
        }
        if ($el2->tagName == "input") {
            $otherValue = $el2->getAttribute("value");
        }
        else {
            $otherValue = $el2->childNodes[0]->data;
        }
        // There might be different types of data, such as numbers, strings, and
        // dates, that have to be compared.  These need to be converted into
        // something that can compare.
        switch ($datatype) {
            case "date":
                $thisValue = strtotime($thisValue);
                $otherValue = strtotime($otherValue);
                break;
            case "datetime":
            case "precise-datetime":
                $thisValue = strtotime($thisValue);
                $otherValue = strtotime($otherValue);
                break;
        }
        switch ($config->getAttribute("compare-type")) {
            case "less":
                return ($thisValue < $otherValue);
            case "equal":
                return ($thisValue === $otherValue);
            case "greater":
                return ($thisValue > $otherValue);
            case "lessequal":
                return ($thisValue <= $otherValue);
            case "greaterequal":
                return ($thisValue >= $otherValue);
        }
        return true;
    } //}}}

    // {{{isValid
    function isValid() {
        return $this->isValid;
    } //}}}

    // {{{enableErrorMessages
    function enableErrorMessages() {
        if ($this->isValid) {
            return;
        }
        // Get the configuration information for the form
        @$config = $this->getElementByID("config");
        foreach (array_keys($config->childNodes) as $key) {
            // $el is the configuration information element
            $el = $config->childNodes[$key];
            if ($el->nodeType != DOM_ELEMENT_NODE) {
                continue;
            }
            // First see if the config element specifies an error element to
            // unhide
            if ($el->getAttribute("error-element")) {
                $default = $el->getAttribute("error-element");
            }
            else {
                unset($default);
            }
            // Then check if the element failed any validation checks; if so,
            // get the name of the element to unhide
            $failed = false;
            if ($el->getAttribute("failed-required")) {
                $errorElement = $el->getAttribute("name") . "-error";
                $failed = true;
            }
            elseif ($el->getAttribute("failed-data-type")) {
                $errorElement = $el->getAttribute("name") . "-data-error";
                $failed = true;
            }
            elseif ($el->getAttribute("failed-comparison")) {
                $errorElement = $el->getAttribute("name") . "-comparison-error";
                $failed = true;
            }

            if ($failed) {
                unset($node);
                // Try to find the error element to unhide
                if (isset($errorElement)
                        && $this->getElementByID($errorElement)) {
                    @$node = $this->getElementByID($errorElement);
                }
                if (!isset($node) && isset($default) && $default) {
                    // Try to find the explicitly specified default error
                    // element
                    @$node = $this->getElementByID($default);
                }
                if (!isset($node)) {
                    @$node = $this->getElementByID($el->getAttribute("name") . "-error");
                }
                if (isset($node)) {
                    // If we found an error element, unhide it
                    $node->removeAttribute("hidden");
                }
            }
        }
        // Look for an error element for the *whole form* and unhide that if it
        // exists.
        @$overall = $this->getElementByID(
            $config->getAttribute("error-element"));
        if (!is_null($overall)) {
            $overall->removeAttribute("hidden");
        }
    } //}}}

    // {{{disableErrorMessages
    function disableErrorMessages() {
        // Get the configuration information for the form
        @$config = $this->getElementByID("config");
        foreach (array_keys($config->childNodes) as $key) {
            // $el is the configuration information element
            $el = $config->childNodes[$key];
            if ($el->nodeType != DOM_ELEMENT_NODE) {
                continue;
            }
            if ($el->getAttribute("failed-required")) {
                $errorElement = $el->getAttribute("element-id" . "-error");
                @$node = $this->getElementByID($errorElement);
                $node->setAttribute("hidden", "1");
            }
            if ($el->getAttribute("failed-data-type")) {
                $errorElement = $el->getAttribute("element-id" . "-data-error");
                @$node = $this->getElementByID($errorElement);
                $node->setAttribute("hidden", "1");
            }
            if ($el->getAttribute("failed-comparison")) {
                $errorElement = $el->getAttribute("element-id" . "-comparison-error");
                @$node = $this->getElementByID($errorElement);
                $node->setAttribute("hidden", "1");
            }
        }
    } //}}}

    // {{{getElementByNameAndValue
    function getElementByNameAndValue($name, $value) {
        $array = $this->getElementsByAttributeValue("name", $name);
        foreach (array_keys($array) as $key) {
            if ($array[$key]->getAttribute("value") === $value) {
                return $array[$key];
            }
        }
        return null;
    } //}}}

    // {{{getBrowserData
    // $method must be either GET or POST.  Case matters!
    function getBrowserData($method, $name) {
        eval("@\$source = \$_$method;");
        if (!isset($source) || !isset($source[$name])) {
            return null;
        }
        if (is_array($source[$name])) {
            // build a new array and return it, sanitized
            $toReturn = array();
            foreach ($source[$name] as $key => $val) {
                if (get_magic_quotes_gpc()) {
                    $toReturn[$key] = stripslashes($val);
                }
                else {
                    $toReturn[$key] = stripslashes($val);
                }
            }
            return $toReturn;
        }
        return stripslashes($source[$name]);
    } //}}}

}

?>

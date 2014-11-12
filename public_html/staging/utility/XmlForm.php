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
 * $Id: XmlForm.php,v 1.3 2009/03/12 03:13:36 pctainto Exp $
 */
include_once("XmlFormParser.php");
include_once("XmlSerializer.php");

class XMLForm {

    var $form;

    // {{{XMLForm
    function XMLForm($data, $text = FALSE) {
        # $text being TRUE means the $data is actually a parsable string;
        # otherwise $data is a filename to read from disk and parse
        if ($text) {
            $file = $data;
        }
        else {
            $file = file_get_contents($data);
        }
        # Check for empty form data
        if (!trim($file)) {
            trigger_error("The file is empty", E_USER_ERROR);
        }
        $parser = new XMLFormParser();
        @$this->form = $parser->parse($file);
    } //}}}

    // {{{getValue
    function getValue($name) {
        // Get the config for the form
        $config = $this->form->getElementByID("config");
        if (is_null($config)) {
            trigger_error("Invalid <config> specification in the form", E_USER_ERROR);
        }
        // Try to find the config element for the element
        $els = $config->selectElements(array("name" => $name), "element");
        if (!count($els)) {
            return null;
        }
        else {
            $el = $els[0];
        }
        // $el is now the config element that tells us things about the element
        // whose value we want.  Get the element's value
        if ($el->getAttribute("type") == "array") {
            // {{{array
            $result = array();
            if ($el->getAttribute("tag-name") == "input") { // It's a CheckBox
                $elements = $this->form->selectElements(array(
                    "name" => $el->getAttribute("name") . "[]",
                    "type" => "checkbox"), "input");
                foreach (array_keys($elements) as $key) {
                    $option = $elements[$key];
                    if ($option->getAttribute("checked")) {
                        $result[$option->getAttribute("value")] = "1";
                    }
                    else {
                        $result[$option->getAttribute("value")] = "0";
                    }
                }
            }
            elseif ($el->getAttribute("tag-name") == "select") {
                $select = $this->form->getElementByID($el->getAttribute("element-id"));
                foreach (array_keys($select->childNodes) as $key) {
                    $option = $select->childNodes[$key];
                    if ($option->nodeType == DOM_ELEMENT_NODE
                            && $option->tagName == "option") {
                        if ($option->getAttribute("selected")) {
                            $result[$option->getAttribute("value")] = "1";
                        }
                        else {
                            $result[$option->getAttribute("value")] = "0";
                        }
                    }
                }
            }
            return $result;
            // }}}
        }
        elseif ($el->getAttribute("element-id")) {
            // {{{scalar that's not a radio button or checkbox array
            $element = $this->form->getElementByID($el->getAttribute("element-id"));
            if ($element->tagName == "select") {
                // Select
                // Find the correct child node of the element and get its value
                @$options = $element->getElementsByTagName("option");
                foreach (array_keys($options) as $key) {
                    if ($options[$key]->getAttribute("selected")) {
                        return $options[$key]->getAttribute("value");
                    }
                }
                return null;
            }
            elseif ($element->tagName == "input") {
                // TextBox, Password, Hidden, Button, Submit, Reset
                if ($element->getAttribute("type") == "checkbox") {
                    if (!$element->getAttribute("checked")) {
                        return;
                    }
                }
                return $element->getAttribute("value");
            }
            else {
                // TextArea
                return $element->childNodes[0]->data;
            }
            // }}}
        }
        else {
            // Scalar that's a radio button array or checkbox (scalar) array
            $elems = $this->form->selectElements(array("name" =>
                $el->getAttribute("name")), "input");
            foreach (array_keys($elems) as $key) {
                if ($elems[$key]->getAttribute("checked")) {
                    return $elems[$key]->getAttribute("value");
                }
            }
        }
    } //}}}

    // {{{setValue
    function setValue($name, $value) {
        return $this->form->setValue($name, $value);
    } //}}}

    // {{{toString
    function toString() {
        // Set the form's action, by default, to post back to itself.
        @$config = $this->form->getElementByID("config");
        if (is_null($config)) {
            trigger_error("Invalid <config> specification in the form", E_USER_ERROR);
        }
        @$form = $this->form->getElementByID($config->getAttribute("form-id"));
        if (is_null($form)) {
            trigger_error("Invalid <config> specification in the form", E_USER_ERROR);
            return;
        }
        if (!$form->getAttribute("method")) {
            $form->setAttribute("method", "POST");
        }
        if (!$form->getAttribute("action")) {
            $form->setAttribute("action", $_SERVER['REQUEST_URI']);
        }
        $serializer = new XMLSerializer("HTML");
        return $serializer->serializeNode($this->form, "");
    } //}}}

    // {{{snatch
    function snatch() {
        return $this->form->populateFromBrowser();
    } //}}}

    // {{{validate
    function validate() {
        $result = $this->form->validate();
        // Only enable error messages if the form has been submitted
        @$config = $this->form->getElementByID("config");
        @$formEl = $this->form->getElementByID(
            $config->getAttribute("form-id"));
        if (is_null($formEl)) {
            trigger_error("There is no element with id "
                . $config->getAttribute("form-id") . ".  You probably "
                . "forgot to put the attribute on the form element.",
                E_USER_ERROR);
            return false;
        }
        $sanityCheck = $config->getAttribute("sanity-check");
        if ($this->form->getBrowserData(
                $formEl->getAttribute("method"), $sanityCheck)) {
            $this->form->enableErrorMessages();
        }
    } //}}}

    // {{{suppressErrors
    function suppressErrors() {
        return $this->form->disableErrorMessages();
    } //}}}

    // {{{isValid
    function isValid() {
        return $this->form->isValid();
    } //}}}

}

?>

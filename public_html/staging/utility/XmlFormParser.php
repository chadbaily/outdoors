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
 * $Id: XmlFormParser.php,v 1.2 2009/03/12 03:13:36 pctainto Exp $
 */
/* 
 * Purpose:  Represents a specialized XML parser that builds an XMLFormDocument
 * instead of a regular Document.
 */

include_once("XmlParser.php");
include_once("XmlFormDocument.php");

class XMLFormParser extends XMLParser {

    function XMLFormParser() {
        $this->XMLParser();
    }

    function parse($data) { 
        $this->document = new XMLFormDocument();
        $this->currentNode = $this->document;
        if (!xml_parse($this->parser, $data)) {
            $line = xml_get_current_line_number($this->parser);
            $col = xml_get_current_column_number($this->parser);
            # Get the current line, and up to 30 characters before and after the
            # column number where the error occurred
            $lines = explode("\n", $data);
            $chars = "\r\n" . substr($lines[$line - 1], max(0, $col - 30), 60);
            # Add a "pointer" below it
            $chars .= "\r\n" . str_repeat("-", min(30, $col)) . "^\r\n";
            trigger_error("XML Parsing error at line $line, column $col:"
                . xml_error_string(xml_get_error_code($this->parser)) . ": $chars",
                E_USER_ERROR);
        }
        return $this->document;
    }

}

?>

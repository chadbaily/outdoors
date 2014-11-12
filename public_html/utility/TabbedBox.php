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
 * $Id: TabbedBox.php,v 1.1.1.1 2005/03/27 19:54:19 bps7j Exp $
 *
 * Purpose: A box with multiple tabs.  Each tab has some text in it,
 * which could be a link as well.  There can be multiple rows too.
 */

class TabbedBox {
    // {{{declarations
    var $rows = array();        # An array of rows, which are arrays of tabs
    var $activeTab  = "";       # The currently active tab, which is highlighted
    // }}}

    /* {{{constructor
     *
     */
    function TabbedBox() {
    } //}}}

    /* {{{setActiveTab
     * Sets the active tab to the element that is keyed on $activeTab
     */
    function setActiveTab($value) {
        $this->activeTab = str_replace("&", "", $value);
    } //}}}

    /* {{{addTab
     */
    function addTab($text, $row, $link) {
        $name = str_replace("&", "", $text);
        if (!isset($this->rows[intval($row)])) {
            $this->rows[intval($row)] = array();
        }
        $this->rows[intval($row)][$name] = array(
            'label' => $text,
            'link' => $link,
            'row' => $row);
    } //}}}

    /* deleteTab
     * Deletes the tab with the specified label.
     */
    function deleteTab($label) {
        foreach ($this->rows as $key => $row) {
            unset($this->rows[$key][$label]);
        }
    } #}}}

    /* {{{toString
     * Returns an HTML string representation of the whole thing.
     */
    function toString() {
        $result = "";
        $tabs = array(""); # An array of the HTML strings to make the tabs
        $activeRow = 0;

        foreach ($this->rows as $key => $row) {
            $tabs[$key] = "";
            foreach ($this->rows[$key] as $name => $tab) {
                # Find out if it's the active tab
                if ($name == $this->activeTab) {
                    $class = " class='active'";
                    $activeRow = $key;
                } else {
                    $class = "";
                }
                $accesskey = (strpos($tab['label'], '&') !== false)
                    ? substr($tab['label'], strpos($tab['label'], '&') + 1, 1)
                    : "";
                $label = preg_replace("/&(.)/", "<u>$1</u>", $tab['label']);

                # Add the tab to the row
                $tabs[$key] .= "\r\n  <td$class><a "
                    . ($accesskey ? "accesskey='$accesskey' " : "")
                    . "href='$tab[link]'>$label</a></td>";
            }
        }

        # Add the active row to the output first, then delete it from the array
        $tabHTML = "\r\n<tr><td><table cellspacing=0 border=0><tr>"
            . $tabs[$activeRow] .  "\r\n</tr></table></td></tr>";
        unset($tabs[$activeRow]);

        # Prepend the rest of the rows, so they'll go "on top" of the active row
        return "\r\n<tr><td><table cellspacing=0 border=0><tr>"
            . join("\r\n</tr></table></td></tr>"
                . "\r\n<tr><td><table cellspacing=0 border=0><tr>", $tabs)
            . "\r\n</tr></table></td></tr>$tabHTML";
    } //}}}

}

?>

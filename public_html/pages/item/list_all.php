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
 * $Id: list_all.php,v 1.4 2005/08/02 03:05:23 bps7j Exp $
 */

$res['title'] = "List All Items";
$template = file_get_contents("templates/item/list_all.php");

# #############################################################################
# These parameters come from the GET variables, but they are used to set values
# in the form later, which is how they persist across pages.
# #############################################################################
$crit = array(
    "count" => 30,
    "category" => 0,
    "type" => 0,
    "status" => 0,
    "sort" => "ID",
    # This is never assigned to.
    "defaultSort" => "ID",
    "numRows" => 1,
    "numPages" => 1,
    "thisPage" => 1);

if (isset($_GET['count']) && $_GET['count']) {
    $crit['count'] = max(10, min(100, intval($_GET['count'])));
}
if (isset($_GET['category']) && $_GET['category']) {
    $crit['category'] = intval($_GET['category']);
}
if (isset($_GET['status']) && $_GET['status']) {
    $crit['status'] = intval($_GET['status']);
}
if (isset($_GET['type']) && $_GET['type']) {
    $crit['type'] = intval($_GET['type']);
}
if (isset($_GET['offset']) && $_GET['offset']) {
    $crit['thisPage'] = max(1, intval($_GET["offset"]));
}
if (isset($_GET['sort']) && $_GET['sort']) {
    $crit['sort'] = $_GET["sort"];
}
# These sort columns are common to both types of display
$otherSortCols = array("ID", "qty", "condition", "status");

# #############################################################################
# Create the form.
# #############################################################################
# Create the filter for category, type, and status
$formTemplate = file_get_contents("forms/item/list_all.xml");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]item_category");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "CAT",
        array_change_key_case($row, 1));
}

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item_type/select-by-category.sql");
if (isset($_GET['category']) && $_GET['category']) {
    $cmd->addParameter("cat", intval($_GET['category']));
}
$result = $cmd->executeReader();
$thisCat = "";
$groupTemplate = Template::extract($formTemplate, "GROUP");
$formTemplate = Template::delete($formTemplate, "GROUP");
$thisGroup = "";
while ($row = $result->fetchRow()) {
    if ($thisCat != $row['cat_title']) {
        $thisCat = $row['cat_title'];
        $formTemplate = Template::replace($formTemplate, array(
            "TYPES" => $thisGroup), 1);
        $thisGroup = Template::replace($groupTemplate, array(
            "CAT_TITLE" => $row['cat_title']));
    }
    $thisGroup = Template::block($thisGroup, "TYPE",
        array_change_key_case($row, 1));
}
$formTemplate = Template::replace($formTemplate, array(
    "TYPES" => $thisGroup), 1);

# ############################################################################
# Leave the rest of the form for later.  Right now, query for items.
# ############################################################################
if (!$crit["type"]) {

    # Add the appropriate sort columns to the menu.
    $sortCols = array("type", "details1", "details2");
    foreach ($sortCols as $col) {
        $formTemplate = Template::block($formTemplate, "sort",
            array("title" => $col));
    }
    if (!in_array($crit['sort'], $sortCols) &&
            !in_array($crit['sort'], $otherSortCols))
    {
        $crit['sort'] = $crit['defaultSort'];
    }

    # Figure out how many results there are total.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item/count.sql");
    if ($crit['category']) {
        $cmd->addParameter("cat", $crit['category']);
    }
    if ($crit['status']) {
        $cmd->addParameter("status", $crit['status']);
    }
    $crit['numRows'] = $cmd->executeScalar();
    if ($crit['numRows'] <= ($crit['thisPage'] - 1) * $crit['count']) {
        $crit['thisPage'] = 1;
    }

    # Retrieve the results that we're going to display.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item/list_all.sql");
    $cmd->addParameter("offset", ($crit['thisPage'] - 1) * $crit['count']);
    $cmd->addParameter("limit", $crit['count']);
    $cmd->addParameter("orderby", $crit['sort']);
    if ($crit['category']) {
        $cmd->addParameter("cat", $crit['category']);
    }
    if ($crit['status']) {
        $cmd->addParameter("status", $crit['status']);
    }
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::unhide($template, "GENERIC");
        $template = Template::delete($template, "BY_TYPE");
    }

}
else {

    # Figure out how many results there are total.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item/count.sql");
    $cmd->addParameter("type", $crit['type']);
    if ($crit['status']) {
        $cmd->addParameter("status", $crit['status']);
    }
    $crit['numRows'] = $cmd->executeScalar();
    if ($crit['numRows'] <= ($crit['thisPage'] - 1) * $crit['count']) {
        $crit['thisPage'] = 1;
    }

    # The list should be filtered by type.  This requires building a custom
    # query and template.  Get the attributes that belong to the item type as a
    # guide for this process.
    $queryTemplate = file_get_contents("sql/item/list_all-by-type.sql");
    $type =& new item_type();
    $type->select($crit['type']);
    $res['title'] = "List All Items: " . $type->getTitle();
    $sortExists = false;
    foreach ($type->getChildren("item_type_feature") as $key => $iat) {
        $colName = $iat->getName();
        # Plug into the query template
        $queryTemplate = Template::block($queryTemplate,
            array("join", "select"),
            array("c_name" => $colName));
        # Plug into the page template
        $template = Template::block($template,
            array("header", "bodyrow"),
            array("c_name" => $colName));
        # Plug into the sort options
        $formTemplate = Template::block($formTemplate, "sort",
            array("col" => $colName, "title" => $colName));
        if ($crit['sort'] == $colName) {
            $sortExists = true;
        }
    }
    $queryTemplate = Template::delete($queryTemplate, "join");
    $queryTemplate = Template::delete($queryTemplate, "select");

    if (!$sortExists && !in_array($crit['sort'], $otherSortCols)) {
        $crit['sort'] = $crit['defaultSort'];
    }

    # Exec the query.
    $cmd = $obj['conn']->createCommand();
    $cmd->setCommandText($queryTemplate);
    $cmd->addParameter("itemtype", $crit['type']);
    $cmd->addParameter("orderby", $crit['sort'] . "_table");
    $cmd->addParameter("offset", ($crit['thisPage'] - 1) * $crit['count']);
    $cmd->addParameter("limit", $crit['count']);
    if ($crit['status']) {
        $cmd->addParameter("status", $crit['status']);
    }
    $result = $cmd->executeReader();

    if ($result->numRows()) {
        $template = Template::unhide($template, "BY_TYPE");
    }
    $template = Template::delete($template, "GENERIC");
}

# ########################################################################
# Plug the results into the page.
# ########################################################################

if ($result->numRows()) {
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "item", $row);
    }
}
else {
    $template = Template::unhide($template, "NONE");
}

# ########################################################################
# Finish building the form.
# ########################################################################

# Add some sort columns to the form
foreach ($otherSortCols as $col) {
    $formTemplate = Template::block($formTemplate, "sort",
        array("title" => $col));
}

# Create the "page X of Y" menu
$crit['numPages'] = ceil($crit['numRows'] / $crit['count']);
for ($i = 1; $i <= $crit['numPages']; ++$i) {
    $formTemplate = Template::block($formTemplate, "offset", array(
        "page" => $i, "pages" => $crit['numPages']));
}

# Insert statuses into the status menu
foreach (array("checked_out", "checked_in", "missing") as $stat) {
    $formTemplate = Template::block($formTemplate, "status",
        array("c_title" => $stat, "c_uid" => $cfg['status_id'][$stat]));
}

# Create the form from the template
$form =& new XmlForm(Template::finalize($formTemplate), true);
$form->setValue("status", $cfg['status_id']['checked_in']);
$form->setValue("sort", $crit['sort']);
$form->snatch();
if (!is_numeric($form->getValue("count"))) {
    $form->setValue("count", $crit['count']);
}

$template = Template::replace($template, array(
    "FORM" => $form->toString(),
    "SORT_COL" => $crit['sort']));

# Auto-link to items when they are in the format "item XYZ"
$replacement = "<a href=\"members/item/read/\\1\">\\0</a>";
$template = preg_replace("/item (\d+)/", $replacement, $template);

$res['content'] = Template::replace($template, array("NUM_ROWS" => $crit['numRows']));

?>

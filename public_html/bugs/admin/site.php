<?php

// site.php - Interface to the site table
// ------------------------------------------------------------------------
// Copyright (c) 2001, 2002 The phpBugTracker Group
// ------------------------------------------------------------------------
// This file is part of phpBugTracker
//
// phpBugTracker is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// phpBugTracker is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with phpBugTracker; if not, write to the Free Software Foundation,
// Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
// ------------------------------------------------------------------------
// $Id: site.php,v 1.2 2002/08/26 18:11:13 bcurtis Exp $

chdir('..');
define('TEMPLATE_PATH', 'admin');
include 'include.php';

function del_item($siteid = 0) {
	global $db, $me;
	
	if ($siteid) {
		// Make sure we are going after a valid record
		$itemexists = $db->getOne('select count(*) from '.TBL_SITE.
			" where site_id = $siteid");
		// Are there any bugs tied to this one?
		$bugcount = $db->getOne('select count(*) from '.TBL_BUG.
			" where site_id = $siteid");
		if ($itemexists and !$bugcount) {
			$db->query('delete from '.TBL_SITE." where site_id = $siteid");
		}
	}
	header("Location: $me?");
}

function do_form($siteid = 0) {
	global $db, $me, $_pv, $STRING, $t;

	extract($_pv);
	$error = '';
	// Validation
	if (!$site_name = trim($site_name))
		$error = $STRING['givename'];
	if ($error) { show_form($siteid, $error); return; }

	if (empty($sort_order)) $sort_order = 0;
	if (!$siteid) {
		$db->query('insert into '.TBL_SITE.' (site_id, site_name, sort_order) '.
			'values ('.$db->nextId(TBL_SITE).', '.$db->quote(stripslashes($site_name)).
			', '.$sort_order.')');
	} else {
		$db->query('update '.TBL_SITE.' set site_name = '.
			$db->quote(stripslashes($site_name)).', sort_order = '.
			$sort_order.' where site_id = '.$site_id);
	}
	if ($use_js) {
		$t->display('admin/edit-submit.html');
	} else {
		header("Location: $me?");
	}
}

function show_form($siteid = 0, $error = '') {
	global $db, $me, $t, $_pv, $STRING;

	if ($siteid && !$error) {
		$t->assign($db->getRow("select * from ".TBL_SITE.
			" where site_id = '$siteid'"));
	} else {
 		$t->assign($_pv);
	}
	$t->assign('error', $error);
	$t->wrap('admin/site-edit.html', ($siteid ? 'editsite' : 'addsite'));
}

function list_items($siteid = 0, $error = '') {
	global $me, $db, $t, $_gv, $STRING, $TITLE, $QUERY;

	if (empty($_gv['order'])) { 
		$order = 'sort_order'; 
		$sort = 'asc'; 
	} else {
		$order = $_gv['order']; 
		$sort = $_gv['sort']; 
	}
	
	$page = isset($_gv['page']) ? $_gv['page'] : 0;
	
	$nr = $db->getOne("select count(*) from ".TBL_SITE);

	list($selrange, $llimit) = multipages($nr, $page, "order=$order&sort=$sort");

	$t->assign('sites', $db->getAll($db->modifyLimitQuery(
		sprintf($QUERY['admin-list-sites'], $order, $sort), $llimit, $selrange)));

	$headers = array(
		'siteid' => 'site_id',
		'name' => 'site_name',
		'sortorder' => 'sort_order');

	sorting_headers($me, $headers, $order, $sort);

	$t->wrap('admin/sitelist.html', 'site');
}

$perm->check('Admin');

if (isset($_gv['op'])) {
    switch($_gv['op']) {
	case 'add':
	    list_items();
	break;
	case 'edit':
	    show_form($_gv['site_id']);
	break;
	case 'del':
	    del_item($_gv['site_id']);
	break;
    }
} elseif(isset($_pv['submit'])) {
    do_form($_pv['site_id']);
} else {
    list_items();
}

?>

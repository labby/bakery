<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker, adapted for LEPTON CMS (https://lepton-cms.org)
  Copyright (C) 2007 - 2018, Christoph Marti, Aldus, erpe

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


require('../../config.php');

// Get id
if (!isset($_GET['img_id']) OR !is_numeric($_GET['img_id']) OR !isset($_GET['item_id']) OR !is_numeric($_GET['item_id'])) {
	header("Location: index.php");
} else {
	$id       = $_GET['img_id'];
	$item_id  = $_GET['item_id'];
	$id_field = 'img_id';
	$table    = TABLE_PREFIX.'mod_bakery_images';
}

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

// Include the ordering class
require(LEPTON_PATH.'/framework/class.order.php');

// Create new order object and reorder
$order = new order($table, 'position', $id_field, 'item_id');
if ($order->move_down($id)) {
	$admin->print_success($TEXT['SUCCESS'], LEPTON_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$item_id.'#images');
} else {
	$admin->print_error($TEXT['ERROR'], LEPTON_URL.'/modules/bakery/modify_item.php?page_id='.$page_id.'&section_id='.$section_id.'&item_id='.$item_id.'#images');
}

// Print admin footer
$admin->print_footer();

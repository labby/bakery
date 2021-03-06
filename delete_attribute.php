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
if (!isset($_GET['attribute_id']) OR !is_numeric($_GET['attribute_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$attribute_id = $_GET['attribute_id'];
}

// Include admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(LEPTON_PATH.'/modules/admin.php');


// Delete attribute
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_attributes WHERE attribute_id = '$attribute_id'");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_bakery_item_attributes WHERE attribute_id = '$attribute_id'");


// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], LEPTON_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

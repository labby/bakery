<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker, adapted for LEPTON CMS (https://lepton-cms.org)
  Copyright (C) 2007 - 2018, Christoph Marti, Aldus, erpe

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License  - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


require('../../config.php');

// Include admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(LEPTON_PATH.'/modules/admin.php');


if (!empty($_POST['option_name'])) {
	// Insert new option name into db
	if (empty($_POST['option_id'])) {
		$option_name = addslashes(strip_tags($_POST['option_name']));
		$database->query("INSERT INTO ".TABLE_PREFIX."mod_bakery_options (option_name) VALUES ('$option_name')");
	}
	// Update option name
	else {
		$option_id = addslashes(strip_tags($_POST['option_id']));
		$option_name = addslashes(strip_tags($_POST['option_name']));
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_options SET option_name = '$option_name' WHERE option_id = '$option_id'");
	}
} else {
	$admin->print_error($MESSAGE['MEDIA']['BLANK_NAME'], LEPTON_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
}


// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], LEPTON_URL.'/modules/bakery/modify_options.php?page_id='.$page_id);
}


// Print admin footer
$admin->print_footer();

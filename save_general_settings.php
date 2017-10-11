<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org)
  Copyright (C) 2007 - 2017, Christoph Marti

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

// Include admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(LEPTON_PATH.'/modules/admin.php');
// Include functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Remove any tags and add slashes
$reload = $_POST['reload'] == 'true' ? true : false;

$shop_name       = addslashes(strip_tags($_POST['shop_name']));
$shop_email      = addslashes(strip_tags($_POST['shop_email']));
$pages_directory = addslashes(strip_tags($_POST['pages_directory']));
$tac_url         = addslashes(strip_tags($_POST['tac_url']));
$shop_country    = addslashes(strip_tags($_POST['shop_country']));
$shop_state      = isset($_POST['shop_state']) ? addslashes(strip_tags($_POST['shop_state'])) : '';
$shipping_form   = addslashes(strip_tags($_POST['shipping_form']));

$company_field = isset($_POST['company_field']) ? 'show'    : 'hide';
$tax_no_field  = isset($_POST['tax_no_field'])  ? 'show'    : 'hide';
$state_field   = isset($_POST['state_field'])   ? 'show'    : 'hide';
$zip_location  = isset($_POST['zip_location'])  ? 'end'     : 'inside';
$no_revocation = isset($_POST['no_revocation']) ? 'e-goods' : 'none';
$hide_country  = isset($_POST['hide_country'])  ? 'hide'    : 'show';
$cust_msg      = isset($_POST['cust_msg'])      ? 'show'    : 'hide';

$display_settings    = isset($_POST['display_settings'])    ? 1 : 0;
$skip_cart           = isset($_POST['skip_cart'])           ? 'yes' : 'no';
$out_of_stock_orders = isset($_POST['out_of_stock_orders']) ? 1 : 0;
$use_captcha         = isset($_POST['use_captcha'])         ? 'yes' : 'no';

$definable_field_0 = addslashes(strip_tags($_POST['definable_field_0']));
$definable_field_1 = addslashes(strip_tags($_POST['definable_field_1']));
$definable_field_2 = addslashes(strip_tags($_POST['definable_field_2']));
$stock_mode        = addslashes(strip_tags($_POST['stock_mode']));
$stock_limit       = addslashes(strip_tags($_POST['stock_limit']));

$shop_currency = addslashes(strip_tags($_POST['shop_currency']));
$dec_point     = addslashes(strip_tags($_POST['dec_point']));
$thousands_sep = addslashes(strip_tags($_POST['thousands_sep']));
$tax_rate      = addslashes(strip_tags($_POST['tax_rate']));
$tax_rate1     = addslashes(strip_tags($_POST['tax_rate1']));
$tax_rate2     = addslashes(strip_tags($_POST['tax_rate2']));
$tax_group     = addslashes(strip_tags($_POST['tax_group']));
$tax_included  = isset($_POST['tax_included']) ? 'included' : 'excluded';
$tax_by        = addslashes(strip_tags($_POST['tax_by']));

$tax_rate_shipping = addslashes(strip_tags($_POST['tax_rate_shipping']));
$free_shipping     = addslashes(strip_tags($_POST['free_shipping']));
$free_shipping_msg = isset($_POST['free_shipping_msg']) ? 'show' : 'hide';
$shipping_method   = addslashes(strip_tags($_POST['shipping_method']));
$shipping_domestic = addslashes(strip_tags($_POST['shipping_domestic']));
$shipping_abroad   = addslashes(strip_tags($_POST['shipping_abroad']));
$shipping_zone     = addslashes(strip_tags($_POST['shipping_zone']));
$zone_countries    = isset($_POST['zone_countries']) ? implode(',', $_POST['zone_countries']) : '';


// Clean out protocol names if added to the shop name
// to prevent problems with the php mail() function
$shop_name = str_replace('http://',  '', $shop_name);
$shop_name = str_replace('https://', '', $shop_name);


// If no state file exists for the selected country...
if (!file_exists(LEPTON_PATH.'/modules/bakery/languages/states/'.$shop_country.'.php')) {
	// ...set shop state to blank
	$shop_state = '';
	// ...change tax by from state to country
	if ($tax_by == 'state') $tax_by = 'country';
}


// Get current general settings
$query_general_settings = $database->query("SELECT pages_directory, tax_rate, tax_rate1, tax_rate2 FROM ".TABLE_PREFIX."mod_bakery_general_settings");
$general_settings = $query_general_settings->fetchRow();


// If a tax rate setting has changed => update items tax rate 
if ($general_settings['tax_rate'] != $tax_rate) {
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET tax_rate = $tax_rate WHERE tax_rate = {$general_settings['tax_rate']}");
}
if ($general_settings['tax_rate1'] != $tax_rate1) {
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET tax_rate = $tax_rate1 WHERE tax_rate = {$general_settings['tax_rate1']}");
}
if ($general_settings['tax_rate2'] != $tax_rate2) {
	$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET tax_rate = $tax_rate2 WHERE tax_rate = {$general_settings['tax_rate2']}");
}


// Rename Bakery pages directory

// Old and new directory pathes
$old_pages_dir = LEPTON_PATH.PAGES_DIRECTORY.'/'.$general_settings['pages_directory'].'/';
$new_pages_dir = LEPTON_PATH.PAGES_DIRECTORY.'/'.$pages_directory.'/';

// Make sure the old directory exists
make_dir($old_pages_dir);

// Rename if the pages directory has changed
if ($general_settings['pages_directory'] != $pages_directory) {
	// Check if the pages directory name does not exist yet
	if (is_dir($new_pages_dir)) {
		$admin->print_error($MESSAGE['MEDIA']['DIR_EXISTS'], LEPTON_URL.'/modules/bakery/modify_general_settings.php?page_id='.$page_id.'&section_id='.$section_id);
	}
	// Rename directory
	if (rename($old_pages_dir, $new_pages_dir)) {
		// Update item links
		$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_items SET link = REPLACE(link, '/{$general_settings['pages_directory']}/', '/$pages_directory/')");
	}
	else {
		$admin->print_error($MESSAGE['MEDIA']['CANNOT_RENAME'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}	
}


// Update general settings
$database->query("UPDATE ".TABLE_PREFIX."mod_bakery_general_settings SET shop_name = '$shop_name', shop_email = '$shop_email', pages_directory = '$pages_directory', tac_url = '$tac_url', shop_country = '$shop_country', shop_state = '$shop_state', shipping_form = '$shipping_form', company_field = '$company_field', state_field = '$state_field', tax_no_field = '$tax_no_field', tax_group = '$tax_group', zip_location = '$zip_location', no_revocation = '$no_revocation', hide_country = '$hide_country', cust_msg = '$cust_msg',  display_settings = '$display_settings', skip_cart = '$skip_cart', out_of_stock_orders = '$out_of_stock_orders', use_captcha = '$use_captcha', definable_field_0 = '$definable_field_0', definable_field_1 = '$definable_field_1', definable_field_2 = '$definable_field_2', stock_mode = '$stock_mode', stock_limit = '$stock_limit', shop_currency = '$shop_currency', dec_point = '$dec_point', thousands_sep = '$thousands_sep', tax_rate = '$tax_rate', tax_rate1 = '$tax_rate1', tax_rate2 = '$tax_rate2', tax_included = '$tax_included', tax_by = '$tax_by', tax_rate_shipping = '$tax_rate_shipping', free_shipping = '$free_shipping', free_shipping_msg = '$free_shipping_msg', shipping_method = '$shipping_method', shipping_domestic = '$shipping_domestic', shipping_abroad = '$shipping_abroad', shipping_zone = '$shipping_zone', zone_countries = '$zone_countries'");

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	// If a country has been selected go back to the general settings page
	if ($reload) {
		$admin->print_success($TEXT['SUCCESS'], LEPTON_URL.'/modules/bakery/modify_general_settings.php?page_id='.$page_id.'&section_id='.$section_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
}

// Print admin footer
$admin->print_footer();

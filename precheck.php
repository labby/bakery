<?php

/*
  Module developed for the Open Source Content Management System WebsiteBaker (http://websitebaker.org), adapted for LEPTON CMS
  Copyright (C) 2007 - 2017, Christoph Marti, Aldus, erpe

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


// prevent this file from being accessed directly
if (!defined('LEPTON_PATH')) die(header('Location: ../../index.php'));

$PRECHECK = array();


/**
 * Specify required WebsiteBaker version
 * You need to provide at least the Version number (Default operator:= >=)
 */
$PRECHECK['VERSION'] = array('VERSION' => '3.0.0', 'OPERATOR' => '>=');


/**
 * Specify required PHP extension
 * Provide a simple array with the extension required by the module
 */
$PRECHECK['PHP_EXTENSIONS'] = array('gd');


/**
 * Specify required PHP INI settings
 * Provide an array with the setting and the expected value
 */
$PRECHECK['PHP_SETTINGS'] = array('safe_mode' => '0');

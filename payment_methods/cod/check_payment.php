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


// Prevent this file from being accessed directly
if (defined('LEPTON_PATH') == false) {
	exit("Cannot access this file directly"); 
}

// Set payment status tu success
$payment_status = 'success';
include(LEPTON_PATH.'/modules/bakery/view_confirmation.php');
return;

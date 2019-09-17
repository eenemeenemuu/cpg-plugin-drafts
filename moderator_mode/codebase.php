<?php
/**************************************************
  Coppermine 1.6.x Plugin - moderator_mode
  *************************************************
  Copyright (c) 2019 eenemeenemuu
  *************************************************
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.
  **************************************************/

if (!defined('IN_COPPERMINE')) die('Not in Coppermine...');

$thisplugin->add_action('gallery_admin_mode', 'moderator_mode');

function moderator_mode() {
    // Configuration here:
    $moderator_user_ids = array('3');
    $moderator_allowed_sites = array(
        'displayimage.php', // needed for edit one pic (display button)
        'edit_one_pic.php', // needed for edit one pic
        'delete.php', // needed for edit one pic
        'index.php', // needed for edit several pics (display button)
        'editpics.php', // needed for edit several pics
    );
    /*
     * You need to apply one modification for this plugin. Open include/init.inc.php, find
     * 
     * define('GALLERY_ADMIN_MODE', USER_IS_ADMIN && $USER['am']);
     * 
     * and replace with
     * 
     * define('GALLERY_ADMIN_MODE', USER_IS_ADMIN && $USER['am'] || CPGPluginAPI::action('gallery_admin_mode', null));
    */

    global $CPG_PHP_SELF;

    if (in_array(USER_ID, $moderator_user_ids) && in_array($CPG_PHP_SELF, $moderator_allowed_sites)) {
        return true;
    }
}

//EOF
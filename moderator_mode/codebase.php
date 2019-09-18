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
        'index.php', // needed for edit several pics (display button in category/album view)
        'thumbnails.php', // needed for edit several pics (display button in thumbnail view)
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

    if (in_array(USER_ID, $moderator_user_ids)) {
        define('USER_IS_MODERATOR');
        if (in_array($CPG_PHP_SELF, $moderator_allowed_sites)) {
            return true;
        }
    }
}

$thisplugin->add_filter('admin_menu', 'moderator_mode_admin_menu');

function moderator_mode_admin_menu($html) {
    if (!USER_IS_ADMIN && USER_IS_MODERATOR) {
        global $template_user_admin_menu, $lang_user_admin_menu, $lang_gallery_admin_menu;

        $param = array('{ALBMGR_TITLE}' => $lang_user_admin_menu['albmgr_title'],
            '{ALBMGR_LNK}' => $lang_user_admin_menu['albmgr_lnk'],
            '{ALBUMS_ICO}' => cpg_fetch_icon('alb_mgr', 1),
            '{MODIFYALB_TITLE}' => $lang_user_admin_menu['modifyalb_title'],
            '{MODIFYALB_LNK}' => $lang_user_admin_menu['modifyalb_lnk'],
            '{MODIFYALB_ICO}' => cpg_fetch_icon('modifyalb', 1),
            '{MY_PROF_TITLE}' => $lang_user_admin_menu['my_prof_title'],
            '{MY_PROF_LNK}' => $lang_user_admin_menu['my_prof_lnk'],
            '{MY_PROF_ICO}' => cpg_fetch_icon('my_profile', 1),
            '{PICTURES_TITLE}' => $lang_gallery_admin_menu['pictures_title'],
            '{PICTURES_LNK}' => $lang_gallery_admin_menu['pictures_lnk'],
            '{PICTURES_ICO}' => cpg_fetch_icon('picture_sort', 1),
            );

        $html = template_eval($template_user_admin_menu, $param);
    }
    return $html;
}

//EOF
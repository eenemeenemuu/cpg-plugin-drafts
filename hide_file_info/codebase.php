<?php

if (!defined('IN_COPPERMINE')) die('Not in Coppermine...');

$thisplugin->add_filter('file_info','hide_file_info');

function hide_file_info($info) {
    global $lang_picinfo, $lang_common;

    unset($info[$lang_picinfo['Album name']]);
    unset($info[$lang_common['filesize']]);
    unset($info[$lang_picinfo['Dimensions']]);
    unset($info[$lang_picinfo['Displayed']]);
    unset($info[$lang_picinfo['URL']]);
    unset($info[$lang_picinfo['addFavPhrase']]);

    return $info;
}

//EOF
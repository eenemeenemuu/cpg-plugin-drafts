<?php

if (!defined('IN_COPPERMINE')) die('Not in Coppermine...');

$thisplugin->add_action('plugin_install', 'registration_field_install');
$thisplugin->add_filter('register_form_create', 'registration_field_register_form_create');
$thisplugin->add_filter('register_form_validate', 'registration_field_register_form_validate');
$thisplugin->add_action('register_form_submit', 'registration_field_register_form_submit');
if (defined('USERMGR_PHP') && GALLERY_ADMIN_MODE) {
    $thisplugin->add_filter('page_html', 'registration_field_page_html');
}

function registration_field_install() {
    global $CONFIG;
    cpg_db_query("ALTER TABLE {$CONFIG['TABLE_USERS']} ADD user_info varchar(255) default ''");
    return true;
}

function registration_field_register_form_create($form_data) {
    global $lang_register_php;
    $form_data_new = array();
    foreach ($form_data as $data) {
        $form_data_new[] = $data;
        if ($data[1] == 'email') {
            $icon = '<img src="images/icons/memberlist.png" border="0" alt="" width="16" height="16" class="icon">';
            $text = 'Angaben zur Person (Zu dieser Galerie erhält nur ein eingeschränkter Personenkreis Zutritt. Beschreibe bitte wer du bist, woher man dich kennt bzw. über wen du diese Galerie kennst. Unbekannte Personen erhalten keinen Zutritt zur Galerie!)';
            $form_data_new[] = array('textarea', 'user_info', $icon.$text, 255);
        }
    }
    return $form_data_new;
}

function registration_field_register_form_validate($error) {
    $superCage = Inspekt::makeSuperCage();
    if (!$superCage->post->keyExists('user_info') || strlen($superCage->post->getRaw('user_info')) < 3) {
        $error = '<li style="list-style-image:url(images/icons/stop.png)">"Angaben zur Person" überprüfen</li>';
    }
    return $error;
}

function registration_field_register_form_submit($user_data) {
    global $CONFIG;
    $superCage = Inspekt::makeSuperCage();
    $user_info = $superCage->post->getEscaped('user_info');
    cpg_db_query("UPDATE {$CONFIG['TABLE_USERS']} SET user_info = '$user_info' WHERE user_id = '{$user_data['user_id']}'");
    return;
}

function registration_field_page_html($html) {
    global $CONFIG;
    $superCage = Inspekt::makeSuperCage();
    if ($superCage->get->keyExists('user_id')) {
        $user = mysql_fetch_assoc(cpg_db_query("SELECT user_active, user_info FROM {$CONFIG['TABLE_USERS']} WHERE user_id = ".$superCage->get->getInt('user_id')));
        if ($user['user_active'] == "NO" && $user['user_info']) {
            $row = <<<EOT
                <tr>
                    <td class="tableb red" valign="top">
                        <img src="images/icons/memberlist.png" border="0" alt="" width="16" height="16" class="icon" />Angaben zur Person
                    </td>
                    <td class="tableb red" valign="top">
                        {$user['user_info']}
                    </td>
                </tr>
EOT;
            $html = preg_replace('/name="user_active".*<\/tr>/Us', '\\0'.$row, $html);
        }
    }
    return $html;
}

?>
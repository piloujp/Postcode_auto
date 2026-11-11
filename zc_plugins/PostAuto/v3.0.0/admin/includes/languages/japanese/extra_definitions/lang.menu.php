<?php

/**
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: piloujp 2026 Oct 14 Modified in v3.3.0-dev $
*/

$pluginForumUrl = 'https://www.zen-cart.com/threads/205577';
$pluginGithubUrl = 'https://github.com/piloujp/Postcode_auto';
$pluginVersion = basename(dirname(__DIR__, 5));

$ButtonGap = '4px';
$ButtonStyle = ' target="_blank" rel="noopener noreferrer" class="btn btn-primary" role="button" style="margin:0 ' . $ButtonGap . ' 0 0;padding-left:8px;padding-right:8px"';

$ButtonsLinks =
    '<div style="margin:10px 0 0;padding:0 0 0 ' . $ButtonGap . '">'
    . '<a href="' . (defined('DIR_WS_CATALOG') ? DIR_WS_CATALOG : '/') . 'zc_plugins/PostAuto/' . $pluginVersion . '/readme.html"' . $ButtonStyle . '>お読みください</a>'
    . '<a href="' . $pluginForumUrl . '"' . $ButtonStyle . '>フォーラム</a>'
    . '<a href="' . $pluginGithubUrl . '"' . $ButtonStyle . '>GitHub</a>'
    . '</div>';

$define = [
    'ADMIN_PLUGIN_MANAGER_NAME_FOR_POSTAUTO' => '郵便番号で自動入力',
    'ADMIN_PLUGIN_MANAGER_DESCRIPTION_FOR_POSTAUTO' => 'このプラグインは、郵便番号が入力されると、フォームのフィールドに都道府県、市区町村、および（データが利用可能な場合）番地を自動入力します。'
    . $ButtonsLinks
];

return $define;

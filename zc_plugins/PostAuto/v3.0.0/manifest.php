<?php

$pluginForumUrl = 'https://www.zen-cart.com/threads/205577';
$pluginGithubUrl = 'https://github.com/piloujp/Postcode_auto';
$pluginVersion = basename(__DIR__);

$ButtonGap = '4px';
$ButtonStyle = ' target="_blank" rel="noopener noreferrer" class="btn btn-primary" role="button" style="margin:0 ' . $ButtonGap . ' 0 0;padding-left:8px;padding-right:8px"';

$ButtonsLinks =
    '<div style="margin:10px 0 0;padding:0 0 0 ' . $ButtonGap . '">'
    . '<a href="' . (defined('DIR_WS_CATALOG') ? DIR_WS_CATALOG : '/') . 'zc_plugins/PostAuto/' . $pluginVersion . '/readme.html"' . $ButtonStyle . '>ReadMe</a>'
    . '<a href="' . $pluginForumUrl . '"' . $ButtonStyle . '>Forum Thread</a>'
    . '<a href="' . $pluginGithubUrl . '"' . $ButtonStyle . '>GitHub</a>'
    . '</div>';

return [
    'pluginVersion' => 'v3.0.0',
    'pluginName' => 'Postcode Auto Fill',
    'pluginDescription' => 'Fills in the state, city and (where the data has it) street address as soon as a postal/zip code is typed, on the account, address book and checkout forms, One-Page Checkout included.' . $ButtonsLinks,
    'pluginAuthor' => 'My Zen Cart Host (dbltoe), piloujp',
    'pluginId' => 2370, // ID from Zen Cart forum
    'zcVersions' => ['v1.5.8', 'v1.5.8a', 'v2.0.0', 'v2.0.1', 'v2.1.0', 'v2.2.0', 'v2.2.1', 'v2.2.2', 'v2.3.0', 'v3.0.0'],
    'changelog' => 'https://github.com/piloujp/Postcode_auto/releases/tag/v3.0.0', // online URL (eg github release tag page, or changelog file there) or local filename only, ie: changelog.txt (in same dir as this manifest file)
    'github_repo' => $pluginGithubUrl, // url
    'pluginGroups' => [],
];

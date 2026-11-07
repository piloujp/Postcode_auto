<?php

$sql = "SET @coid = (SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_3 = 'JPN' ORDER BY countries_id DESC LIMIT 1);";
$this->executeInstallerSql($sql);

// Delete file number: 2610

// Add file number: 2610
$sql = "
REPLACE INTO " . TABLE_ZONES_TO_POST_CODE_JP . " (zone_country_id, zone_id, post_code, zone_name, zone_city_name, zone_street_name, zone_name_romaji, zone_city_name_romaji, zone_street_name_romaji) VALUES
(@coid, IFNULL((SELECT zone_id FROM zones WHERE zone_name = 'Ibaraki' AND zone_country_id = @coid), 0),'3130065','茨城県','常陸太田市','まいづる町','Ibaraki','Hitachiota Shi', 'Maizurucho')
";
$this->executeInstallerSql($sql);

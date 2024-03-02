<?php
/**
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: pilou2 2024 Feb 14 Modified in v2.0.0-alpha1 $
*/
class zcAjaxPostcodeQuery extends base
{
	public function postcodequery()
    {
		$zone = array();
		if (isset($_POST['postcode']) and isset($_POST['country'])) { // uses postcode to define zone, limited to country where postcode zones have been put in database
			$conn = new PDO("mysql:host=" . constant('DB_SERVER') . ";dbname=" . constant('DB_DATABASE') . "", DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$reqcountcode = $conn->prepare("SELECT countries_iso_code_3 FROM " . DB_PREFIX . "countries WHERE countries_id = ?;");
			$reqcountcode->execute([$_POST['country']]);
			if (!empty($reqcountcode)) {
				$codeiso = $reqcountcode->fetchAll()[0]['countries_iso_code_3'];
			} else {
				return;
			}
			
			switch ($codeiso) {
				case 'JPN':
					if ($_SESSION['language'] == 'japanese') {
						$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name, zone_street_name FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE post_code = ?;");
					} else {
						$request = $conn->prepare("SELECT zone_id, zone_name_romaji AS zone_name, zone_city_name_romaji AS zone_city_name, zone_street_name_romaji AS zone_street_name FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE post_code = ?;");
					}
					break;
				case 'FRA':
				case 'GUF':
				case 'PYF':
				case 'ATF':
				case 'GLP':
				case 'MTQ':
				case 'MYT':
				case 'MCO':
				case 'REU':
				case 'WLF':
				case 'SPM':
						$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name, zone_street_name FROM " . DB_PREFIX . "zones_to_post_code_fr WHERE zone_country_id = " . $_POST['country'] . " AND post_code = ?;");
					break;
				case 'PLW':
				case 'USA':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_us WHERE zone_country_id = " . $_POST['country'] . " AND post_code = ?;");
					break;
				case 'ESP':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_es WHERE post_code = ?;");
					break;
				case 'DEU':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_de WHERE post_code = ?;");
					break;
				case 'ITA':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_it WHERE post_code = ?;");
					break;
				case 'LIE':
				case 'CHE':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_ch WHERE zone_country_id = " . $_POST['country'] . " AND post_code = ?;");
					break;
				case 'AUS':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_au WHERE post_code = ?;");
					break;
				case 'AUT':
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_at WHERE post_code = ?;");
					break;
				default:
					$request = null;
					break;
			}

			if (!empty($request)){
				try {
					$request->execute([$_POST['postcode']]);
					foreach($request as $k=>$v) {
						$state = (!empty($v['zone_id']) && ACCOUNT_STATE_DRAW_INITIAL_DROPDOWN === 'true') ? $v['zone_id'] : $v['zone_name'];
						$suburb_street = (empty($v['zone_street_name'])) ? '' : $v['zone_street_name'];
						$zone[] = array('zone_id' => $state, 'city' => $v['zone_city_name'], 'street_name' => $suburb_street);
					}
					return $zone;
				} catch(PDOException $e) {
					return "Connection failed: " . $e->getMessage();
				}
			}
			$conn = null;
		}
	}
}
?>
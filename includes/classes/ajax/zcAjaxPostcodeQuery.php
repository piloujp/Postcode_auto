<?php
/**
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: pilou2 2023 Apr 11 Modified in v1.5.8a $
*/
class zcAjaxPostcodeQuery extends base
{
	public function postcodequery()
    {
		$zone = array();
		if (isset($_POST['postcode']) and isset($_POST['country'])) { // uses postcode to define zone, limited to country where postcode zones have been put in database
			$conn = new PDO("mysql:host=" . constant('DB_SERVER') . ";dbname=" . constant('DB_DATABASE') . "", DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			switch ($_POST['country']) {
				case 107:
					if ($_SESSION['language'] == 'japanese') {
						$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name, zone_street_name FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE post_code = ?;");
					} else {
						$request = $conn->prepare("SELECT zone_id_romaji AS zone_id, zone_name_romaji AS zone_name, zone_city_name_romaji AS zone_city_name, zone_street_name_romaji AS zone_street_name FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE post_code = ?;");
					}
					break;
				case 233:
				case 174:
				case 141:
				case 137:
				case 134:
				case 87:
				case 75:
				case 76:
				case 77:
				case 73:
						$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name, zone_street_name FROM " . DB_PREFIX . "zones_to_post_code_fr WHERE zone_country_id = " . $_POST['country'] . " AND post_code = ?;");
					break;
				case 223:
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_us WHERE post_code = ?;");
					break;
				case 195:
					$request = $conn->prepare("SELECT zone_id, zone_name, zone_city_name FROM " . DB_PREFIX . "zones_to_post_code_es WHERE post_code = ?;");
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
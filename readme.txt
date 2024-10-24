Postcode auto fill is a plugin that autofill state, city and if available street address fields in a form when you enter postal/zip code. An ajax request and JavaScript (jQuery) make this possible on the fly without submitting the form.
It uses extra tables with postal codes data for one country at a time.
An external jQuery plugin is used to display multiple possible answers in a JavaScript popup tool window:
PowerTip  from Steven Benner at https://stevenbenner.github.io/jquery-powertip/.

'Postcode auto fill' is provided with ten countries data/tables: United States, Spain, France and its territories, Germany, Italy, Swiss, Liechtenstein (included with Swiss), Austria, Australia and Japan. If you want to add a country, you must build a new table with necessary data and add some php code (in SWITCH loop) to the ajax query function.
Instructions for that are at the end of this files.

INSTALL:
--------
--------
- Run SQL query from files (zones_to_post_code_2letterscountrycode.sql) in SQL folder for each country you want to use in Zen Cart SQL tool or from PhpMyAdmin. If your country is not available, you will have to build the table. See how at the end of this file.
Be aware that it could take some time for country like Japan where you have more than 120 000 entries. If you added some prefix to your Zen cart database, you have to modify these queries before running them.
IMPORTANT notes:
	1- For Zen Cart v1.58a and below, some data in zones table are outdated or have errors. Run queries in files '../sql/V1.5.8a-and-lower/zones_*2letterscountrycode*_update.sql' FIRST to correct them.
	2- Japanese table uses Japanese zones installed with Japanese language pack. If they are not installed in your cart, use 'zones_to_post_code_jp_zone0.sql' which has all zones ids set to zero. It will be faster like this as this table is pretty big.
	
- Copy following files to your cart respecting folder tree:
includes/classes/ajax/zcAjaxPostcodeQuery.php
includes/modules/pages/address_book_process/jscript_postcode.js ** NEEDS TO BE MODIFIED TO FIT YOUR TEMPLATE **
includes/modules/pages/checkout_shipping_address/jscript_postcode.js ** NEEDS TO BE MODIFIED TO FIT YOUR TEMPLATE **
includes/modules/pages/login/jscript_postcode.js ** NEEDS TO BE MODIFIED TO FIT YOUR TEMPLATE **
includes/templates/YOUR_TEMPLATE/css/stylesheet_jquery.powertip.min.css
includes/templates/YOUR_TEMPLATE/jscript/jquery.powertip.min.js
To do so, first RENAME folder 'YOUR_TEMPLATE' to the template name you use, then COPY 'includes' folder content to your cart 'includes' folder.
In 'jscript_postcode.js' files there are lines like this:
    $.getScript( "includes/templates/responsive_classic/jscript/jquery.powertip.min.js" ),
Where path needs to be changed to fit your template name if it is not 'responsive_classic':
    $.getScript( "includes/templates/YOUR_ACTUAL_TEMPLATE_NAME/jscript/jquery.powertip.min.js" ),

Bonus:
 If you want to use database postal codes data in shipping estimator merge the provided file (in bonus folder) but there is no ajax here, you must click on submit button to update form:
 includes/modules/shipping_estimator.php
 
 Or add this code around line 48 or 53 depending on ZC version after --- '$selectedState = (isset($_POST['state']) ? zen_output_string_protected($_POST['state']) : ''); ---:
 
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	if (!empty($_POST['postcode'])) { // uses postcode to define zone if postcode zones have been put in database.
	// To display zone/state only for shop's country, comment precedent line and uncomment next one, and reverse.
	//if (!empty($_POST['postcode']) and $_POST['zone_country_id'] == STORE_COUNTRY) { // uses postcode to define zone, limited to store country and if postcode zones have been put in database.
		$_POST['postcode'] = preg_replace('/[-―ー\s]/u','',$_POST['postcode']);
		
		$reqcountcodeiso = 'SELECT countries_iso_code_3 FROM ' . DB_PREFIX . 'countries WHERE countries_id = :zonecountryid';
		$reqcountcodeiso = $db->bindVars($reqcountcodeiso, ':zonecountryid', $_POST['zone_country_id'], 'integer');
		$contryresult = $db->execute($reqcountcodeiso, 1);
		if (!empty($contryresult)) {
			$codeiso3 = $contryresult->fields['countries_iso_code_3'];
		} else {
			$codeiso3 = '';
		}
		
		switch ($codeiso3) {
			case 'JPN':
				if ($_SESSION['language'] == 'japanese') {
					$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				} else {
					$sql = "SELECT zone_name_romaji AS zone_name, zone_id_romaji AS zone_id FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
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
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_fr WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'PLW':
			case 'USA':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_us WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'ESP':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_es WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'DEU':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_de WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'ITA':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_it WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'LIE':
			case 'CHE':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_ch WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'AUS':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_au WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
			case 'AUT':
				$sql = "SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_at WHERE zone_country_id = :zonecountryid AND post_code = :postcode";
				break;
		}
		$sql = $db->bindVars($sql, ':zonecountryid', $_POST['zone_country_id'], 'integer');
		$sql = $db->bindVars($sql, ':postcode', $_POST['postcode'], 'string');
		$result = $db->Execute($sql, 1);
		if (isset($result) and !empty($result->fields)) {
			$selectedState = $result->fields['zone_name'];
			$state_zone_id = (int)$result->fields['zone_id'];
		} else {
			$selectedState = '';
			$state_zone_id = 0;
		}
	}
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

UNINSTALL:
----------
----------
- Run these SQL queries to remove database tables:
DROP TABLE zones_to_post_code_us;
DROP TABLE zones_to_post_code_fr;
DROP TABLE zones_to_post_code_jp;
DROP TABLE zones_to_post_code_es;
DROP TABLE zones_to_post_code_de;
DROP TABLE zones_to_post_code_it;
DROP TABLE zones_to_post_code_ch;
DROP TABLE zones_to_post_code_au;
DROP TABLE zones_to_post_code_at;

- Remove files by following install steps but instead of copy, delete files or added code (shipping estimator only).

BUILDING A NEW COUNTRY TABLE DATA:
----------------------------------
----------------------------------
- First you need to download data. First place to start is your country's national Post Office site. Be careful that there are lots of services providing postal code data, but they are not often free to distribute or even use.
- You then must format these data to build a MySQL database table with a name following this format 'zones_to_post_code_(2 LETTERS COUNTRYCODE)' and with these fields at minimum:
'zone_country_id' for the country id  number used in Zen Cart. Set for the country you downloaded data.
'zone_id' for this country zones id numbers in Zen Cart if they exist. First setting it to zero is easier and then when table is created (and zones exist), update it with a query like this:

(example for United States):
	UPDATE zones_to_post_code_us INNER JOIN zones ON zones_to_post_code_us.zone_name = zones.zone_name AND zen_zones.zone_country_id = 223 SET zones_to_post_code_us.zone_id = zones.zone_id;
(example for France and other countries that includes territories or other small coutries):
	UPDATE zones_to_post_code_fr INNER JOIN zones ON zones_to_post_code_fr.zone_name = zones.zone_name SET zones_to_post_code_fr.zone_id = zones.zone_id;
(example for Japan):
	UPDATE zones_to_post_code_jp INNER JOIN zen_zones ON zones_to_post_code_jp.zone_name = zen_zones.zone_name AND zen_zones.zone_country_id = 107 SET zones_to_post_code_jp.zone_id = zen_zones.zone_id;
	UPDATE zones_to_post_code_jp INNER JOIN zen_zones ON zones_to_post_code_jp.zone_name_romaji = zen_zones.zone_name AND zen_zones.zone_country_id = 107 SET zones_to_post_code_jp.zone_id_romaji = zen_zones.zone_id;

'post_code' obviously for postal/zip code. This plugin is programed for a minimum of five characters long.
'zone_name' for state, district, prefecture or whatever it is called in the country.
'zone_city_name' for city name corresponding to the postal code.
'zone_street_name' for area, county, or any street level information. This can be omitted like in US table, but it must be taken in account in PHP code in AJAX function.
Then I add a 'post_zone_id' field as an auto_increment index. Have a look at provided SQL data to get an idea of the structure. All this can be done using different tools, like Excel and/or a good text editor like notepad++ to finally build an SQL file to import in Zen Cart.
With countries using non-alphabet writting you might want to double fields (adding an aplhabet written field), but it will make sql requests and PHP loops more complicated. have a look at Japanese version.

- Finally, 'zcAjaxPostcodeQuery.php' (and eventually 'shipping_estimator.php') file must be modified. In the 'switch' loop you need to add a 'case' for the new country. You can copy an existing one and modify country code and SQL request especially if you have street data or not.

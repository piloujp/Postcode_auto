Postcode auto fill is a plugin that autofill state, city and if available street address fields in a form when you enter postal/zip code. An ajax request and JavaScript make this possible on the fly without submitting the form.
It uses extra tables with postal codes data for one country at a time.
An external jQuery plugin is used to display multiple possible answers in a JavaScript popup tool window:
PowerTip  from Steven Benner at https://stevenbenner.github.io/jquery-powertip/.

'Postcode auto fill' is provided with three countries data/tables: United States, Japan and France. If you want to add a country, you must build a new table with necessary data and add some php code (in SWITCH loop) to the ajax query function.
Instructions for that are at the end of this files.

INSTALL:
--------
--------
- Run SQL query from SQL folder for each country you want to use in Zen Cart SQL tool or from PhpMyAdmin. If your country is not available, you will have to build the table. See how at the end of this file.
Be aware that it could take some time for country like Japan where you have more than 120 000 entries. If you added some prefix to your Zen cart database, you have to modify these queries before running them.
Important notes:
	1- Japanese table uses Japanese zones installed with Japanese language pack. If they are not installed in your cart, you can just set all zone_id fields to 0 with this query 'UPDATE zones_to_post_code_jp SET zone_id=0, zone_id_romaji=0 WHERE 1;'.
	2- If you have added country zones for other countries before adding Japanese ones then zone ids are different than those in the SQL query. In this case use SQL queries provided at the end of this file in section 'BUILDING A NEW COUNTRY TABLE DATA' to update them.

- Copy following files to your cart respecting folder tree:
includes/classes/ajax/zcAjaxPostcodeQuery.php
includes/modules/pages/address_book_process/jscript_postcode.js
includes/modules/pages/checkout_shipping_address/jscript_postcode.js
includes/modules/pages/login/jscript_postcode.js
includes/templates/YOUR_TEMPLATE/css/stylesheet_jquery.powertip.min.css
includes/templates/YOUR_TEMPLATE/jscript/jquery.powertip.min.js
To do so, first RENAME folder 'YOUR_TEMPLATE' to the template name you use, then COPY 'includes' folder content to your cart 'includes' folder.

Bonus:
 If you want to use database postal codes data in shipping estimator merge the provided file (in bonus folder) but there is no ajax here, you must click on form button to update:
 includes/modules/shipping_estimator.php
 
 Or add this code around line 41:
 
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	if (!empty($_POST['postcode']) and $_POST['zone_country_id'] == STORE_COUNTRY) { // uses postcode to define zone, limited to store country and if postcode zones have been put in database.
		switch ($_POST['zone_country_id']) {
			case 107:
				if ($_SESSION['language'] == 'japanese') {
				$result = $db->Execute("SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE zone_country_id = " . $_POST['zone_country_id'] . " AND post_code = '" . $_POST['postcode'] . "' LIMIT 1;");
				} else {
				$result = $db->Execute("SELECT zone_name_romaji AS zone_name, zone_id_romaji AS zone_id FROM " . DB_PREFIX . "zones_to_post_code_jp WHERE zone_country_id = " . $_POST['zone_country_id'] . " AND post_code = '" . $_POST['postcode'] . "' LIMIT 1;");
				}
				if (isset($result)) {
					$selectedState = $result->fields['zone_name'];
					$state_zone_id = $result->fields['zone_id'];
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
				$result = $db->Execute("SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_fr WHERE zone_country_id = " . $_POST['zone_country_id'] . " AND post_code = '" . $_POST['postcode'] . "' LIMIT 1;");
				if (isset($result)) {
					$selectedState = $result->fields['zone_name'];
					$state_zone_id = $result->fields['zone_id'];
				}
				break;
			case 223:
				$result = $db->Execute("SELECT zone_name, zone_id FROM " . DB_PREFIX . "zones_to_post_code_us WHERE zone_country_id = " . $_POST['zone_country_id'] . " AND post_code = '" . $_POST['postcode'] . "' LIMIT 1;");
				if (isset($result)) {
					$selectedState = $result->fields['zone_name'];
					$state_zone_id = $result->fields['zone_id'];
				}
				break;
		}
	}
 -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

UNINSTALL:
----------
----------
- Run these SQL query to remove database tables:
DROP TABLE `zen_zones_to_post_code_us`;
DROP TABLE `zen_zones_to_post_code_fr`;
DROP TABLE `zen_zones_to_post_code_jp`;

- Remove files by following install steps but instead of copy, delete files or added code (shipping estimator only).

BUILDING A NEW COUNTRY TABLE DATA:
----------------------------------
----------------------------------
- First you need to download data. First place to start is your country's national Post Office site. Be careful that there are lots of services providing postal code data, but they are not often free to distribute or even use.
- You then must format these data to build a MySQL database with a name following this format 'zones_to_post_code_(2 LETTERS COUNTRYCODE)' and with these fields at minimum:
'zone_country_id' for the country id  number used in Zen Cart. Set for the country you downloaded data.
'zone_id' for this country zones id numbers in Zen Cart if they exist. First setting it to zero is easier and then when table is created (and zones exist), update it with a query like this:

(example for United States):
	UPDATE zones_to_post_code_us INNER JOIN zones ON zones_to_post_code_us.zone_name = zones.zone_name SET zones_to_post_code_us.zone_id = zones.zone_id;
(example for Japan):
	UPDATE zones_to_post_code_jp INNER JOIN zen_zones ON zones_to_post_code_jp.zone_name = zen_zones.zone_name SET zones_to_post_code_jp.zone_id = zen_zones.zone_id;
	UPDATE zones_to_post_code_jp INNER JOIN zen_zones ON zones_to_post_code_jp.zone_name_romaji = zen_zones.zone_name SET zones_to_post_code_jp.zone_id_romaji = zen_zones.zone_id;

'post_code' obviously for postal/zip code. This plugin is programed for a minimum of five characters long.
'zone_name' for state, district, prefecture or whatever it is called in the country.
'zone_city_name' for city name corresponding to the postal code.
'zone_street_name' for area, county, or any street level information. This can be omitted like in US table, but it must be taken in account in PHP code in AJAX function.
Then I add a 'post_zone_id' field as an auto_increment index. Have a look at provided SQL data to get an idea of the structure. All this can be done using different tools, like Excel and/or a good text editor like notepad++ to finally build an SQL file to import in Zen Cart.
With countries using non-alphabet writting you might want to double fields, but it will make sql requests and PHP loops more complicated. have a look at Japanese version.

- Finally, 'zcAjaxPostcodeQuery.php' (and eventually 'shipping_estimator.php') file must be modified. In the 'switch' loop you need to add a 'case' for the new country. You can copy an existing one and modify country code and SQL request especially if you have street data or not.

EXTRAS:
-------
-------
In Powertip plugin files and folders you can find a readable version of 'jquery.powertip.min.js' and some css files with different designs (for popup menu) you can try.

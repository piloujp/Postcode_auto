UPDATE zones SET zone_name='Valle D\'Aosta' WHERE zone_country_id=105 AND zone_name='Aosta';
UPDATE zones SET zone_name='Barletta-Andria-Trani' WHERE zone_country_id=105 AND zone_name='Barletta Andria Trani';
UPDATE zones SET zone_name='Forlì-Cesena' WHERE zone_country_id=105 AND zone_name='Forlì Cesena';
UPDATE zones SET zone_name='L\'Aquila' WHERE zone_country_id=105 AND zone_name='Aquila';
UPDATE zones SET zone_name='Massa-Carrara' WHERE zone_country_id=105 AND zone_name='Massa Carrara';
UPDATE zones SET zone_name='Pesaro E Urbino' WHERE zone_country_id=105 AND zone_name='Pesaro Urbino';
UPDATE zones SET zone_name='Verbano-Cusio-Ossola' WHERE zone_country_id=105 AND zone_name='Verbania';

UPDATE zones SET zone_code='SU',zone_name='Sud Sardegna' WHERE zone_country_id=105 AND zone_name='Carbonia-Iglesias'; -- zone id=211 zone_code='CI'

DELETE FROM zones WHERE zone_country_id=105 AND zone_name='Medio Campidano';
DELETE FROM zones WHERE zone_country_id=105 AND zone_name='Ogliastra';
DELETE FROM zones WHERE zone_country_id=105 AND zone_name='Olbia-Tempio';

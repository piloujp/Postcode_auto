SELECT @coidch := countries_id From countries WHERE countries_iso_code_3 = 'CHE';

UPDATE zones SET zone_name='Fribourg' WHERE zone_country_id=@coidch AND zone_name='Freiburg';
UPDATE zones SET zone_name='Genève' WHERE zone_country_id=@coidch AND zone_name='Genf';
UPDATE zones SET zone_code='GR', zone_name='Graubünden' WHERE zone_country_id=@coidch AND zone_name='Graubnden';
UPDATE zones SET zone_name='Neuchâtel' WHERE zone_country_id=@coidch AND zone_name='Neuenburg';
UPDATE zones SET zone_name='Ticino' WHERE zone_country_id=@coidch AND zone_name='Tessin';
UPDATE zones SET zone_name='Vaud' WHERE zone_country_id=@coidch AND zone_name='Waadt';
UPDATE zones SET zone_name='Valais' WHERE zone_country_id=@coidch AND zone_name='Wallis';

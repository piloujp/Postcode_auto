Set @japan_id = (Select countries_id from countries where countries_iso_code_2 = 'JP' LIMIT 1);

ALTER TABLE zones_to_post_code_jp DROP COLUMN zone_id_romaji;

UPDATE zones_to_post_code_jp pst INNER JOIN zones z ON pst.zone_name = z.zone_code AND z.zone_country_id = @japan_id SET pst.zone_id = z.zone_id;

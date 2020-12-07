/*
    Filter countries: China, Panama, India, Afghanistan
*/

SELECT
    `ip_cidr`, `ip_dec` AS 'ip_dec_min',
    1 AS 'enabled',
    (`ip_dec`+`address_count`) AS 'ip_dec_max'
FROM
    `pw_geoip`
WHERE
    `pw_geoip`.`country_code` IN ('CN', 'PA', 'IN', 'AF')
ORDER BY
    `ip_dec_min` ASC;
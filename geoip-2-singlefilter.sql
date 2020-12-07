/*
    Use this template for searching and blocking a single subnet
    Note that the IP address must be provided in its Decimal Format
*/
SELECT
    `ip_cidr`, `ip_dec` AS 'ip_dec_min',
    1 AS 'enabled',
    (`ip_dec`+`address_count`) AS 'ip_dec_max'
FROM
    `pw_geoip`
WHERE
    ('1566795953' >= `ip_dec` AND '1566795953' <= (`ip_dec` + `address_count`));
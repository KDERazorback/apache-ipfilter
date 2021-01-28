INSERT INTO
    `{{TABLE}}`
    (
        `ip_cidr`,
        `enabled`,
        `ip_dec_min`,
        `ip_dec_max`
    )
VALUES
    (
        '{{ip_cidr}}',
        b'{{enabled}}',
        '{{ip_dec_min}}',
        '{{ip_dec_max}}'
    )
;
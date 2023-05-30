SELECT *
FROM (
    SELECT name, invoice_id, created_at, img_confirmation_date, NULL AS given_date, 'donate' AS source
    FROM `ympb2020_dja_donate`
    WHERE `ympb2020_dja_donate`.`whatsapp` = '081260335454'
    UNION ALL
    SELECT NULL AS name, NULL AS invoice_id, NULL AS created_at, NULL AS img_confirmation_date, given_date, 'slip' AS source
    FROM `ympb2020_josh_slip`
    WHERE `ympb2020_josh_slip`.`whatsapp` = '081260335454'
) AS combined
WHERE COALESCE(created_at, given_date) BETWEEN '2023-03-01' AND '2023-03-25'
ORDER BY COALESCE(created_at, given_date, img_confirmation_date)
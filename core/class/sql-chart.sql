SELECT DATE_FORMAT(given_date, '%M') AS month, COUNT(*) AS donate, SUM(nominal) AS volume
FROM `ympb2020_josh_slip`
WHERE (given_date BETWEEN DATE_FORMAT(NOW() - INTERVAL 3 MONTH, '%Y-%m-01') AND LAST_DAY(NOW() - INTERVAL 1 MONTH))
GROUP BY DATE_FORMAT(given_date, '%Y-%m')
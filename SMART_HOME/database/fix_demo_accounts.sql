USE smart_home_db;

-- Cap nhat mat khau tai khoan mau, khong xoa du lieu khac.
UPDATE users SET password = '$2y$12$JQczz/kb5rddiory2zxA8.N8p9IzIrL/4K6rH1OA5gxHdQ.Q.jY96', status = 'active' WHERE email = 'admin@smarthome.local';
UPDATE users SET password = '$2y$12$eUyt151jRs1Kg0i6663I6.ywAwLnRNiN.Xyt/9zUHPRHckroS3P52', status = 'active' WHERE email = 'staff@smarthome.local';

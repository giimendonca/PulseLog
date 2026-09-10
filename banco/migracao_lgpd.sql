USE pulse_log;

ALTER TABLE usuarios
    ADD COLUMN aceite_lgpd BOOLEAN NOT NULL DEFAULT FALSE AFTER senha,
    ADD COLUMN aceite_lgpd_em DATETIME NULL AFTER aceite_lgpd;
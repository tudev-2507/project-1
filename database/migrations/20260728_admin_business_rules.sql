SET @previous_sql_mode = @@SESSION.sql_mode;
SET SESSION sql_mode = CONCAT_WS(',', NULLIF(@@SESSION.sql_mode, ''), 'NO_AUTO_VALUE_ON_ZERO');

ALTER TABLE `tai_khoan`
    ADD COLUMN `trang_thai` TINYINT(1) NOT NULL DEFAULT 1 AFTER `role`,
    MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `khuyen_mai`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `mau_sac`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `kich_co`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT;

SET SESSION sql_mode = @previous_sql_mode;

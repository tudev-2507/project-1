-- Fix product creation failing with:
-- SQLSTATE[23000]: 1062 Duplicate entry '0' for key 'PRIMARY'
-- Keep the existing legacy product with id=0 unchanged while altering the column.
SET @previous_sql_mode = @@SESSION.sql_mode;
SET SESSION sql_mode = CONCAT_WS(
    ',',
    NULLIF(@@SESSION.sql_mode, ''),
    'NO_AUTO_VALUE_ON_ZERO'
);

ALTER TABLE `san_pham`
    MODIFY `id` INT NOT NULL AUTO_INCREMENT;

SET SESSION sql_mode = @previous_sql_mode;

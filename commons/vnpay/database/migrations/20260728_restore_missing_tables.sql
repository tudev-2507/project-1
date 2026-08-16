-- Restore tables omitted from the partial da1n1 database import.
CREATE TABLE IF NOT EXISTS `pt_thanh_toan` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(225) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pt_thanh_toan` (`id`, `name`) VALUES
    (1, 'Thanh toán khi nhận hàng (COD)'),
    (3, 'Ví điện tử (VNPay)')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

CREATE TABLE IF NOT EXISTS `lien_he` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `ten` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `so_dien_thoai` INT NOT NULL,
    `noi_dung` TEXT NOT NULL,
    `chu_de` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

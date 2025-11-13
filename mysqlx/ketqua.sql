CREATE TABLE `ketqua` (
    `mabn` INT NOT NULL,
    `kqua` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
    `cdoan` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
    `kthuoc` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
    `hdon` DECIMAL(10,0) NOT NULL,
    `gchux` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
    PRIMARY KEY (`mabn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

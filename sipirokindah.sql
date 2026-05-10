-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2025 at 01:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tubesmsbd`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_archive_old_data` (IN `p_months_old` INT)   BEGIN
    DECLARE archive_date DATE;
    DECLARE rows_affected INT DEFAULT 0;
    
    -- Set tanggal cutoff
    SET archive_date = DATE_SUB(CURDATE(), INTERVAL p_months_old MONTH);
    
    -- Log start
    CALL sp_log_system_event('ARCHIVE_STARTED', 
        CONCAT('Mulai archive data sebelum ', archive_date));
    
    -- 1. Archive attendances
    CREATE TABLE IF NOT EXISTS attendances_archive LIKE attendances;
    
    INSERT INTO attendances_archive
    SELECT * FROM attendances 
    WHERE date < archive_date;
    
    SET rows_affected = ROW_COUNT();
    DELETE FROM attendances WHERE date < archive_date;
    
    CALL sp_log_system_event('ARCHIVE_ATTENDANCES', 
        CONCAT(rows_affected, ' rows attendance di-archive'));
    
    -- 2. Archive catatan_panen
    CREATE TABLE IF NOT EXISTS catatan_panen_archive LIKE catatan_panen;
    
    INSERT INTO catatan_panen_archive
    SELECT * FROM catatan_panen 
    WHERE tanggal < archive_date;
    
    SET rows_affected = ROW_COUNT();
    DELETE FROM catatan_panen WHERE tanggal < archive_date;
    
    CALL sp_log_system_event('ARCHIVE_PANEN', 
        CONCAT(rows_affected, ' rows panen di-archive'));
    
    -- 3. Archive old logs (simpan 6 bulan terakhir saja)
    CREATE TABLE IF NOT EXISTS log_aktivitas_user_archive LIKE log_aktivitas_user;
    
    INSERT INTO log_aktivitas_user_archive
    SELECT * FROM log_aktivitas_user 
    WHERE waktu < DATE_SUB(CURDATE(), INTERVAL 6 MONTH);
    
    SET rows_affected = ROW_COUNT();
    DELETE FROM log_aktivitas_user 
    WHERE waktu < DATE_SUB(CURDATE(), INTERVAL 6 MONTH);
    
    CALL sp_log_system_event('ARCHIVE_LOGS', 
        CONCAT(rows_affected, ' rows logs di-archive'));
    
    -- 4. Optimize tables setelah delete
    OPTIMIZE TABLE attendances, catatan_panen, log_aktivitas_user;
    
    -- Update summary statistics
    INSERT INTO archive_history (
        archive_date, 
        months_old, 
        attendances_archived, 
        panen_archived, 
        logs_archived,
        total_space_freed_mb
    ) VALUES (
        CURDATE(),
        p_months_old,
        (SELECT COUNT(*) FROM attendances_archive WHERE date < archive_date),
        (SELECT COUNT(*) FROM catatan_panen_archive WHERE tanggal < archive_date),
        (SELECT COUNT(*) FROM log_aktivitas_user_archive WHERE waktu < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)),
        ROUND(
            (SELECT data_length + index_length FROM information_schema.tables 
             WHERE table_schema = 'tubesmsbd' AND table_name = 'attendances_archive') / 1024 / 1024, 2
        )
    );
    
    CALL sp_log_system_event('ARCHIVE_COMPLETED', 
        CONCAT('Archive selesai. Data sebelum ', archive_date, ' telah dipindahkan'));
    
    SELECT 
        'Archive completed successfully!' as message,
        archive_date as data_cutoff_date,
        (SELECT COUNT(*) FROM attendances_archive WHERE date < archive_date) as attendances_archived,
        (SELECT COUNT(*) FROM catatan_panen_archive WHERE tanggal < archive_date) as panen_archived,
        (SELECT COUNT(*) FROM log_aktivitas_user_archive WHERE waktu < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)) as logs_archived;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_monthly_report` (IN `p_month` INT, IN `p_year` INT)   BEGIN
    DECLARE start_date DATE;
    DECLARE end_date DATE;
    DECLARE report_period VARCHAR(50);
    
    -- Set tanggal periode
    SET start_date = STR_TO_DATE(CONCAT(p_year, '-', LPAD(p_month, 2, '0'), '-01'), '%Y-%m-%d');
    SET end_date = LAST_DAY(start_date);
    SET report_period = CONCAT(DATE_FORMAT(start_date, '%M %Y'));
    
    -- Buat tabel laporan bulanan jika belum ada
    CREATE TABLE IF NOT EXISTS monthly_reports (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        report_period VARCHAR(50) NOT NULL,
        month INT NOT NULL,
        year INT NOT NULL,
        total_employees INT DEFAULT 0,
        total_attendance INT DEFAULT 0,
        total_late INT DEFAULT 0,
        total_panen DECIMAL(12,2) DEFAULT 0,
        avg_panen_per_employee DECIMAL(10,2) DEFAULT 0,
        top_performer_id BIGINT,
        top_performer_name VARCHAR(255),
        top_performer_panen DECIMAL(10,2),
        generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_period (month, year)
    );
    
    -- Insert atau update laporan
    INSERT INTO monthly_reports (
        report_period, month, year, 
        total_employees, total_attendance, total_late,
        total_panen, avg_panen_per_employee,
        top_performer_id, top_performer_name, top_performer_panen
    )
    SELECT 
        report_period,
        p_month,
        p_year,
        -- Total pegawai aktif
        (SELECT COUNT(*) FROM users WHERE role IN ('user', 'security', 'cleaning')),
        -- Total kehadiran
        (SELECT COUNT(*) FROM attendances 
         WHERE MONTH(date) = p_month AND YEAR(date) = p_year
           AND status IN ('tepat waktu', 'terlambat')),
        -- Total terlambat
        (SELECT COUNT(*) FROM attendances 
         WHERE MONTH(date) = p_month AND YEAR(date) = p_year
           AND status = 'terlambat'),
        -- Total panen
        COALESCE((SELECT SUM(berat_kg) FROM catatan_panen 
                  WHERE MONTH(tanggal) = p_month AND YEAR(tanggal) = p_year), 0),
        -- Rata-rata panen per pegawai
        COALESCE((SELECT AVG(berat_kg) FROM (
            SELECT id_pegawai, SUM(berat_kg) as berat_kg
            FROM catatan_panen 
            WHERE MONTH(tanggal) = p_month AND YEAR(tanggal) = p_year
            GROUP BY id_pegawai
        ) t), 0),
        -- Top performer
        top_performer.id,
        top_performer.name,
        top_performer.total_panen
    FROM (
        SELECT 
            u.id,
            u.name,
            COALESCE(SUM(cp.berat_kg), 0) as total_panen
        FROM users u
        LEFT JOIN catatan_panen cp ON u.id = cp.id_pegawai 
            AND MONTH(cp.tanggal) = p_month 
            AND YEAR(cp.tanggal) = p_year
        WHERE u.role IN ('user', 'security', 'cleaning')
        GROUP BY u.id, u.name
        ORDER BY total_panen DESC
        LIMIT 1
    ) top_performer
    
    ON DUPLICATE KEY UPDATE
        total_employees = VALUES(total_employees),
        total_attendance = VALUES(total_attendance),
        total_late = VALUES(total_late),
        total_panen = VALUES(total_panen),
        avg_panen_per_employee = VALUES(avg_panen_per_employee),
        top_performer_id = VALUES(top_performer_id),
        top_performer_name = VALUES(top_performer_name),
        top_performer_panen = VALUES(top_performer_panen),
        generated_at = CURRENT_TIMESTAMP;
    
    -- Log aktivitas
    CALL sp_log_system_event('MONTHLY_REPORT_GENERATED', 
        CONCAT('Laporan bulanan ', report_period, ' telah dibuat'));
    
    SELECT CONCAT('Laporan bulanan ', report_period, ' berhasil digenerate!') as result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_log_aktivitas_user` (IN `p_user_id` BIGINT, IN `p_aksi` VARCHAR(100), IN `p_deskripsi` TEXT)   BEGIN
    INSERT INTO log_aktivitas_user (user_id, aksi, deskripsi) VALUES (p_user_id, p_aksi, p_deskripsi);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_log_error_event` (IN `p_modul` VARCHAR(100), IN `p_pesan` TEXT)   BEGIN
    INSERT INTO log_error_event (modul, pesan_error) VALUES (p_modul, p_pesan);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_log_system_event` (IN `p_event_name` VARCHAR(100), IN `p_deskripsi` TEXT)   BEGIN
    INSERT INTO log_system_event (event_name, deskripsi) VALUES (p_event_name, p_deskripsi);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_transaksi_absensi_atomic` (IN `p_user_id` BIGINT, IN `p_date` DATE, IN `p_check_in` TIME, IN `p_check_out` TIME, IN `p_status` VARCHAR(100), IN `p_palm_weight` DECIMAL(9,2))   BEGIN
    DECLARE v_tx_id BIGINT DEFAULT NULL;
    DECLARE exit_reason TEXT DEFAULT NULL;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        IF v_tx_id IS NOT NULL THEN
            UPDATE log_transaksi
            SET status = 'FAILED', waktu_selesai = NOW(), pesan = COALESCE(exit_reason, 'SQLEXCEPTION')
            WHERE id_transaksi = v_tx_id;
        END IF;
        CALL sp_log_error_event('sp_transaksi_absensi_atomic', COALESCE(exit_reason, 'SQLEXCEPTION'));
    END;

    START TRANSACTION;

    INSERT INTO log_transaksi (nama_proses, waktu_mulai, status) 
    VALUES ('sp_transaksi_absensi_atomic', NOW(), 'RUNNING');
    SET v_tx_id = LAST_INSERT_ID();

    SAVEPOINT before_absensi_insert;

    -- Optional check: prevent duplicate attendance for same user & date
    -- Lock any existing row for update to avoid race condition
    SELECT id INTO @existing_att_id
    FROM attendances
    WHERE user_id = p_user_id AND date = p_date
    FOR UPDATE;

    IF @existing_att_id IS NULL THEN
        INSERT INTO attendances (user_id, date, check_in, check_out, status, palm_weight, created_at, updated_at)
        VALUES (p_user_id, p_date, p_check_in, p_check_out, p_status, p_palm_weight, NOW(), NOW());
    ELSE
        -- If already exists, we can decide to update instead of insert
        UPDATE attendances
        SET check_in = p_check_in, check_out = p_check_out, status = p_status, palm_weight = p_palm_weight, updated_at = NOW()
        WHERE id = @existing_att_id;
    END IF;

    -- Log activities
    CALL sp_log_aktivitas_user(p_user_id, 'Absensi', CONCAT('Absensi pada ', p_date, ' status: ', p_status));
    CALL sp_log_system_event('ABSENSI_INSERT_OR_UPDATE', CONCAT('User ', p_user_id, ' attendance for ', p_date));

    INSERT INTO log_absensi_activity (user_id, tanggal, status, jam_checkin) VALUES (p_user_id, p_date, p_status, p_check_in);

    -- Commit everything
    COMMIT;

    UPDATE log_transaksi
    SET status = 'SUCCESS', waktu_selesai = NOW(), pesan = CONCAT('Absensi processed for user ', p_user_id)
    WHERE id_transaksi = v_tx_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_transaksi_panen_atomic` (IN `p_user_id` BIGINT, IN `p_tanggal` DATE, IN `p_berat` DECIMAL(9,2), IN `p_area_id` BIGINT)   BEGIN
    -- variable declarations must be before handlers/statements
    DECLARE v_tx_id BIGINT DEFAULT NULL;
    DECLARE v_periode VARCHAR(64);
    DECLARE exit_reason TEXT DEFAULT NULL;

    -- error handler: on any SQL exception, rollback and record error
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- record failure
        ROLLBACK;
        IF v_tx_id IS NOT NULL THEN
            UPDATE log_transaksi
            SET status = 'FAILED', waktu_selesai = NOW(), pesan = COALESCE(exit_reason, 'SQL Exception')
            WHERE id_transaksi = v_tx_id;
        END IF;
        -- log error event for auditing
        CALL sp_log_error_event('sp_transaksi_panen_atomic', COALESCE(exit_reason, 'SQLEXCEPTION'));
    END;

    START TRANSACTION;

    -- create a log_transaksi row to track this transaction
    INSERT INTO log_transaksi (nama_proses, waktu_mulai, status) 
    VALUES ('sp_transaksi_panen_atomic', NOW(), 'RUNNING');
    SET v_tx_id = LAST_INSERT_ID();

    -- savepoint before critical operations
    SAVEPOINT before_insert_panen;

    -- Insert panen record
    INSERT INTO catatan_panen (id_pegawai, tanggal, id_area_kerja, jumlah_tandan, berat_kg, created_at, updated_at)
    VALUES (p_user_id, p_tanggal, p_area_id, 0, p_berat, NOW(), NOW());

    -- write activity logs (user-level and panen-specific)
    CALL sp_log_aktivitas_user(p_user_id, 'Tambah Panen', CONCAT('Tambah panen ', p_berat, ' kg pada ', p_tanggal));
    CALL sp_log_system_event('PANEN_INSERT', CONCAT('Panen oleh user ', p_user_id, ' tanggal ', p_tanggal));

    INSERT INTO log_panen_activity (user_id, tanggal, berat_kg, area_id) VALUES (p_user_id, p_tanggal, p_berat, p_area_id);

    -- Savepoint after insert so we can rollback to this if later steps fail
    SAVEPOINT after_insert_panen;

    -- Update rapot for this user for the day (call the atomic rapot updater or the existing sp_update_rapot_periode)
    -- We'll call sp_update_rapot_periode for the single-day period
    -- prepare periode label (used inside sp_update_rapot_periode it formats again but we keep consistent)
    SET v_periode = CONCAT(DATE_FORMAT(p_tanggal,'%b-%Y'), ' - ', DATE_FORMAT(p_tanggal,'%b-%Y'));
    CALL sp_update_rapot_periode(p_tanggal, p_tanggal);

    -- if reach here, commit
    COMMIT;

    -- update transaction log to success
    UPDATE log_transaksi
    SET status = 'SUCCESS', waktu_selesai = NOW(), pesan = CONCAT('Inserted panen and updated rapot for user ', p_user_id)
    WHERE id_transaksi = v_tx_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_rapot_periode` (IN `p_start_date` DATE, IN `p_end_date` DATE)   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_user_id BIGINT;
    DECLARE total_panen DECIMAL(9,2);
    DECLARE total_hadir INT;
    DECLARE v_periode VARCHAR(50);

    DECLARE cur CURSOR FOR SELECT id FROM users;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    SET v_periode = CONCAT(DATE_FORMAT(p_start_date, '%b-%Y'), ' - ', DATE_FORMAT(p_end_date, '%b-%Y'));

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_user_id;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Hitung performa user berdasarkan function yang sudah ada
        SET total_panen = fn_total_panen_user(v_user_id, p_start_date, p_end_date);
        SET total_hadir = fn_jumlah_kehadiran_user(v_user_id, p_start_date, p_end_date);

        IF EXISTS (SELECT 1 FROM rapot WHERE id_user = v_user_id AND periode = v_periode) THEN
            UPDATE rapot
            SET nilai = total_panen + total_hadir,
                updated_at = NOW()
            WHERE id_user = v_user_id AND periode = v_periode;
        ELSE
            INSERT INTO rapot (id_user, periode, nilai, created_at, updated_at)
            VALUES (v_user_id, v_periode, total_panen + total_hadir, NOW(), NOW());
        END IF;
    END LOOP;
    CLOSE cur;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_get_employee_rank` (`p_user_id` BIGINT, `p_month` INT, `p_year` INT) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE employee_rank INT;
    
    WITH ranking_cte AS (
        SELECT 
            u.id,
            u.name,
            COALESCE(SUM(cp.berat_kg), 0) as total_panen,
            COUNT(DISTINCT a.date) as hari_kerja,
            ROW_NUMBER() OVER (ORDER BY COALESCE(SUM(cp.berat_kg), 0) DESC) as ranking
        FROM users u
        LEFT JOIN catatan_panen cp ON u.id = cp.id_pegawai 
            AND MONTH(cp.tanggal) = p_month 
            AND YEAR(cp.tanggal) = p_year
        LEFT JOIN attendances a ON u.id = a.user_id 
            AND MONTH(a.date) = p_month 
            AND YEAR(a.date) = p_year
            AND a.status != 'alpha'
        WHERE u.role IN ('user', 'security', 'cleaning')
        GROUP BY u.id, u.name
    )
    SELECT ranking INTO employee_rank
    FROM ranking_cte 
    WHERE id = p_user_id;
    
    RETURN COALESCE(employee_rank, 0);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fn_jumlah_kehadiran_user` (`p_user_id` BIGINT, `p_start_date` DATE, `p_end_date` DATE) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE total_hadir INT;
    SELECT COUNT(*) INTO total_hadir
    FROM attendances
    WHERE user_id = p_user_id AND date BETWEEN p_start_date AND p_end_date;
    RETURN total_hadir;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fn_total_panen_user` (`p_user_id` BIGINT, `p_start_date` DATE, `p_end_date` DATE) RETURNS DECIMAL(9,2) DETERMINISTIC BEGIN
    DECLARE total_kg DECIMAL(9,2);
    SELECT IFNULL(SUM(berat_kg),0) INTO total_kg
    FROM catatan_panen
    WHERE id_pegawai = p_user_id AND tanggal BETWEEN p_start_date AND p_end_date;
    RETURN total_kg;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fn_total_user_role` (`p_role` VARCHAR(255)) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE jumlah INT;
    SELECT COUNT(*) INTO jumlah
    FROM users
    WHERE role = p_role;
    RETURN jumlah;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `judul`, `isi`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 'Evaluasi Bulanan', 'Evaluasi performa bulanan akan dilakukan pada awal Desember.', 2, '2025-11-30 06:41:27', '2025-11-30 06:41:27'),
(4, 'Keselamatan Kerja', 'Gunakan alat pelindung diri saat bekerja di area panen.', 2, '2025-11-30 06:41:27', '2025-11-30 06:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `archive_history`
--

CREATE TABLE `archive_history` (
  `id` bigint(20) NOT NULL,
  `archive_date` date NOT NULL,
  `months_old` int(11) NOT NULL,
  `attendances_archived` int(11) DEFAULT 0,
  `panen_archived` int(11) DEFAULT 0,
  `logs_archived` int(11) DEFAULT 0,
  `total_space_freed_mb` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area_kerja`
--

CREATE TABLE `area_kerja` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `afdeling` varchar(60) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area_kerja`
--

INSERT INTO `area_kerja` (`id`, `kode`, `nama`, `afdeling`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'A001', 'Blok A1', 'Afdeling Utara', 'Area sawit produktif dengan kondisi baik', '2025-11-30 06:41:07', '2025-11-30 06:41:07'),
(2, 'A002', 'Blok B2', 'Afdeling Selatan', 'Area dengan tanah lembab', '2025-11-30 06:41:07', '2025-11-30 06:41:07'),
(3, 'A003', 'Blok C3', 'Afdeling Timur', 'Area baru diremajakan', '2025-11-30 06:41:07', '2025-11-30 06:41:07'),
(4, 'A004', 'Blok D4', 'Afdeling Barat', 'Area dekat sungai', '2025-11-30 06:41:07', '2025-11-30 06:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `palm_weight` decimal(8,2) DEFAULT NULL COMMENT 'Berat sawit dalam kg',
  `checkout_photo_path` varchar(255) DEFAULT NULL COMMENT 'Foto saat checkout',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `date`, `check_in`, `check_out`, `status`, `photo_path`, `palm_weight`, `checkout_photo_path`, `note`, `created_at`, `updated_at`) VALUES
(5, 1, '2025-11-22', '08:10:00', '16:20:00', 'terlambat', NULL, NULL, 'checkout_photos/ykSVuvkPm8VOrk5NTJohelZ7siB6wZXK1d99hygP.png', 'suhci', '2025-11-21 18:10:00', '2025-11-22 02:20:00'),
(7, 3, '2025-11-24', '08:25:00', NULL, 'terlambat', NULL, NULL, NULL, NULL, '2025-11-23 18:25:00', '2025-11-23 18:25:00'),
(13, 3, '2025-12-02', '07:00:00', '15:05:00', 'tepat waktu', NULL, NULL, NULL, 'dfv', '2025-12-01 16:00:00', '2025-12-02 00:05:00'),
(14, 7, '2025-12-02', '08:11:00', '16:10:00', 'terlambat', NULL, NULL, NULL, 'iejsdnf', '2025-12-01 18:11:00', '2025-12-02 02:10:00'),
(16, 1, '2025-12-03', '07:30:00', NULL, 'tepat waktu', NULL, NULL, NULL, NULL, '2025-12-02 17:30:00', '2025-12-02 17:30:00'),
(17, 7, '2025-12-08', '08:00:00', '16:38:00', 'terlambat', NULL, NULL, NULL, 'lagi kerja', '2025-12-07 18:00:00', '2025-12-08 02:38:00'),
(18, 3, '2025-12-09', '07:00:00', '15:00:00', 'tepat waktu', NULL, NULL, NULL, 'hadir', '2025-12-08 17:00:00', '2025-12-09 01:00:00'),
(19, 7, '2025-12-09', '07:15:00', '15:20:00', 'tepat waktu', NULL, NULL, NULL, 'hadir', '2025-12-08 17:15:00', '2025-12-09 01:20:00'),
(20, 1, '2025-12-09', '08:20:00', '17:00:00', 'terlambat', NULL, NULL, NULL, 'hadir 123', '2025-12-08 18:20:00', '2025-12-09 03:00:00'),
(21, 6, '2025-12-09', '08:30:00', '16:30:00', 'terlambat', NULL, NULL, NULL, '123', '2025-12-08 18:30:00', '2025-12-09 02:30:00'),
(22, 3, '2025-12-15', '07:05:00', '15:10:00', 'tepat waktu', NULL, NULL, NULL, '123', '2025-12-14 17:05:00', '2025-12-15 01:10:00'),
(23, 1, '2025-12-15', '08:45:00', NULL, 'terlambat', NULL, NULL, NULL, NULL, '2025-12-14 18:45:00', '2025-12-14 18:45:00'),
(34, 4, '2025-12-03', '08:05:00', '16:15:00', 'terlambat', NULL, NULL, NULL, 'hadir', '2025-12-02 18:05:00', '2025-12-03 02:15:00'),
(35, 5, '2025-12-03', '07:20:00', '15:50:00', 'tepat waktu', NULL, NULL, NULL, 'hadir', '2025-12-02 17:20:00', '2025-12-03 01:50:00'),
(37, 4, '2025-12-08', '08:10:00', '16:20:00', 'terlambat', NULL, NULL, NULL, 'hadir', '2025-12-07 18:10:00', '2025-12-08 02:20:00'),
(38, 5, '2025-12-08', '07:25:00', '15:55:00', 'tepat waktu', NULL, NULL, NULL, 'hadir', '2025-12-07 17:25:00', '2025-12-08 01:55:00'),
(40, 4, '2025-12-16', '08:20:00', '16:25:00', 'terlambat', NULL, NULL, NULL, 'hadir', '2025-12-15 18:20:00', '2025-12-16 02:25:00'),
(41, 5, '2025-12-16', '07:10:00', '15:50:00', 'tepat waktu', NULL, NULL, NULL, 'hadir', '2025-12-15 17:10:00', '2025-12-16 01:50:00'),
(42, 5, '2025-12-18', '13:31:21', '13:32:28', 'terlambat', NULL, NULL, NULL, 'hadir samuel', '2025-12-18 06:31:21', '2025-12-18 06:32:30'),
(43, 1, '2025-12-18', '13:34:34', '13:35:20', 'terlambat', NULL, NULL, NULL, 'mufti samuel', '2025-12-18 06:34:34', '2025-12-18 06:35:20'),
(44, 7, '2025-12-18', '19:00:06', '19:08:28', 'terlambat', NULL, NULL, NULL, 'hasbi hadir', '2025-12-18 12:00:06', '2025-12-18 12:08:28'),
(45, 3, '2025-12-18', '19:08:46', '19:09:04', 'terlambat', NULL, NULL, NULL, 'rio hadir', '2025-12-18 12:08:46', '2025-12-18 12:09:04'),
(46, 7, '2025-12-19', '23:42:26', '23:42:55', 'terlambat', NULL, NULL, NULL, 'hasbi hadir', '2025-12-19 16:42:26', '2025-12-19 16:42:58'),
(47, 1, '2025-12-19', '23:44:07', '23:44:41', 'terlambat', NULL, NULL, NULL, 'muft ihadir', '2025-12-19 16:44:07', '2025-12-19 16:44:41'),
(48, 6, '2025-12-19', '23:45:20', '23:45:32', 'terlambat', NULL, NULL, NULL, 'yosua hasdir', '2025-12-19 16:45:20', '2025-12-19 16:45:32'),
(49, 1, '2025-12-20', '23:22:25', NULL, 'terlambat', NULL, NULL, NULL, NULL, '2025-12-20 16:22:25', '2025-12-20 16:22:25'),
(50, 7, '2025-12-21', '00:24:36', '00:24:54', 'tepat waktu', NULL, NULL, NULL, 'hadir', '2025-12-20 17:24:36', '2025-12-20 17:24:56'),
(51, 3, '2025-12-21', '00:45:39', '01:28:16', 'tepat waktu', NULL, NULL, 'checkout_photos/KAAD0LvxsYxBHh45UY3njtjL6nBI6xhVyC6WXGlz.png', '321', '2025-12-20 17:45:39', '2025-12-20 18:28:16'),
(52, 6, '2025-12-21', '01:34:20', '01:34:29', 'tepat waktu', NULL, NULL, 'checkout_photos/hdEFtpuFeaULo8PJiO5Khuu7pNJcWRNMGoVsZYHv.png', 'hadir', '2025-12-20 18:34:20', '2025-12-20 18:34:29'),
(54, 1, '2025-12-21', '01:57:53', NULL, 'tepat waktu', NULL, NULL, NULL, 'Test dari tinker', '2025-12-20 18:57:53', '2025-12-20 18:57:53'),
(55, 7, '2025-12-22', '20:40:41', '20:40:53', 'terlambat', NULL, NULL, 'checkout_photos/R3En930oac8keQeQn8T2OPunUMlKJaX5y4pW0gnP.jpg', NULL, '2025-12-22 13:40:41', '2025-12-22 13:40:53'),
(56, 1, '2025-12-23', '01:12:13', '01:12:50', 'tepat waktu', NULL, NULL, 'checkout_photos/6F99agEshDaJlawTfj2AhPZd1Li0Om9Qr8yKokKM.jpg', 'hadir', '2025-12-22 18:12:13', '2025-12-22 18:12:50'),
(57, 5, '2025-12-23', '01:29:12', '01:31:57', 'tepat waktu', NULL, NULL, 'checkout_photos/VyNXpb1tpTmK042bFtaJKMdoBgMibxdWYNvndMDR.jpg', 'hafir', '2025-12-22 18:29:12', '2025-12-22 18:31:57'),
(61, 7, '2025-12-23', '02:12:18', '02:18:22', 'tepat waktu', NULL, NULL, 'checkout_photos/u3CTcLjib7GwKVZFbm6ANUweszCChEVwWSm8uIoA.jpg', 'hasbong hadir', '2025-12-22 19:12:18', '2025-12-22 19:18:22'),
(62, 6, '2025-12-23', '17:49:36', '18:21:27', 'terlambat', NULL, NULL, 'checkout_photos/XFVUV6XpDoFAh29keKSTD9iX1qUGxPWINAR4tH9q.jpg', 'yosua hadir', '2025-12-23 10:49:36', '2025-12-23 11:21:28'),
(64, 1, '2025-12-24', '17:53:04', '17:55:03', 'terlambat', NULL, NULL, 'checkout_photos/H34BZAhp9Kez1aGiRWBGRBrAXu0k9LeYBrhIxUbp.jpg', 'hadir mufti blok 1a', '2025-12-24 10:53:04', '2025-12-24 10:55:03'),
(65, 7, '2025-12-25', '19:28:53', '19:34:19', 'terlambat', NULL, NULL, 'checkout_photos/rCECQJik2J3XrUooX8bSDlLl5rkpEsfGGFuJrdAY.jpg', 'hadir hasbi', '2025-12-25 12:28:53', '2025-12-25 12:34:19'),
(66, 3, '2025-12-25', '19:29:06', '19:34:49', 'terlambat', NULL, NULL, 'checkout_photos/xjc1XgdZpCCtuO7RDtbr5nsLy4WhhWnuXbyEU7cu.jpg', 'hadir rio', '2025-12-25 12:29:06', '2025-12-25 12:34:49'),
(67, 5, '2025-12-25', '19:29:27', '19:36:16', 'terlambat', NULL, NULL, 'checkout_photos/Arx55oE49yKnPt3enR9r5GfQRd9nBwdWsWrBm1tI.png', 'hadir samuel', '2025-12-25 12:29:27', '2025-12-25 12:36:16'),
(68, 6, '2025-12-25', '19:29:41', '19:35:50', 'terlambat', NULL, NULL, 'checkout_photos/tU5gS6n0IeCVnWkimC6VEa4iTdf1zqGHO8prMt1W.jpg', 'hadir yosua', '2025-12-25 12:29:41', '2025-12-25 12:35:50'),
(69, 1, '2025-12-25', '19:32:38', '19:33:19', 'terlambat', NULL, NULL, 'checkout_photos/YVXXOohfhXenT2eHmVJlV3Zy9CbQebqvidJaC4jq.jpg', 'hadir mufti', '2025-12-25 12:32:38', '2025-12-25 12:33:20'),
(70, 10, '2025-12-25', '19:35:10', NULL, 'terlambat', NULL, NULL, NULL, NULL, '2025-12-25 12:35:10', '2025-12-25 12:35:10');

--
-- Triggers `attendances`
--
DELIMITER $$
CREATE TRIGGER `tr_auto_log_absensi` AFTER INSERT ON `attendances` FOR EACH ROW BEGIN
    CALL sp_log_aktivitas_user(NEW.user_id, 'Absensi', CONCAT('User ', NEW.user_id, ' melakukan absensi pada ', NEW.date));
    CALL sp_log_system_event('ABSENSI_INSERT', CONCAT('Data absensi baru ditambahkan untuk user ', NEW.user_id));
    INSERT INTO log_absensi_activity (user_id, tanggal, status, jam_checkin) VALUES (NEW.user_id, NEW.date, NEW.status, NEW.check_in);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `attendances_archive`
--

CREATE TABLE `attendances_archive` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `palm_weight` decimal(8,2) DEFAULT NULL COMMENT 'Berat sawit dalam kg',
  `checkout_photo_path` varchar(255) DEFAULT NULL COMMENT 'Foto saat checkout',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catatan_panen`
--

CREATE TABLE `catatan_panen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pegawai` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `id_area_kerja` bigint(20) UNSIGNED DEFAULT NULL,
  `jumlah_tandan` int(10) UNSIGNED DEFAULT 0,
  `berat_kg` decimal(9,2) DEFAULT 0.00,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catatan_panen`
--

INSERT INTO `catatan_panen` (`id`, `id_pegawai`, `tanggal`, `id_area_kerja`, `jumlah_tandan`, `berat_kg`, `catatan`, `created_at`, `updated_at`) VALUES
(45, 3, '2025-12-02', NULL, 0, 244.00, 'dfv', '2025-12-02 08:00:00', '2025-12-02 08:00:00'),
(46, 7, '2025-12-02', NULL, 0, 234.00, 'iejsdnf', '2025-12-02 09:00:00', '2025-12-02 09:00:00'),
(47, 3, '2025-12-03', 1, 0, 150.50, NULL, '2025-12-03 08:30:00', '2025-12-03 08:30:00'),
(48, 7, '2025-12-08', NULL, 0, 30.00, 'lagi kerja', '2025-12-08 09:30:00', '2025-12-08 09:30:00'),
(49, 3, '2025-12-09', NULL, 0, 30.00, 'hadir', '2025-12-09 08:00:00', '2025-12-09 08:00:00'),
(50, 3, '2025-12-09', NULL, 0, 30.00, 'hadir', '2025-12-09 08:10:00', '2025-12-09 08:10:00'),
(51, 7, '2025-12-09', NULL, 0, 100.00, 'hadir', '2025-12-09 09:00:00', '2025-12-09 09:00:00'),
(52, 3, '2025-12-15', NULL, 0, 123.00, '123', '2025-12-15 08:30:00', '2025-12-15 08:30:00'),
(63, 7, '2025-12-18', NULL, 0, 200.00, 'hasbi hadir', '2025-12-18 12:08:28', '2025-12-18 12:08:28'),
(64, 3, '2025-12-18', NULL, 0, 423.00, 'rio hadir', '2025-12-18 12:09:04', '2025-12-18 12:09:04'),
(65, 7, '2025-12-19', NULL, 0, 60.00, 'hasbi hadir', '2025-12-19 16:42:58', '2025-12-19 16:42:58'),
(66, 7, '2025-12-21', NULL, 0, 123.00, 'hadir', '2025-12-20 17:24:56', '2025-12-20 17:24:56'),
(67, 3, '2025-12-21', NULL, 0, 123.00, '321', '2025-12-20 18:28:16', '2025-12-20 18:28:16'),
(68, 7, '2025-12-22', NULL, 0, 70.00, NULL, '2025-12-22 13:40:53', '2025-12-22 13:40:53'),
(69, 3, '2025-12-23', NULL, 0, 1000.00, 'rio hadir jam 01.30', '2025-12-22 18:38:24', '2025-12-22 18:38:24'),
(70, 7, '2025-12-23', NULL, 0, 855.00, 'hadir hasbong', '2025-12-22 18:40:12', '2025-12-22 18:40:12'),
(71, 7, '2025-12-23', NULL, 0, 67.00, 'hasbong hadir', '2025-12-22 19:18:22', '2025-12-22 19:18:22'),
(72, 7, '2025-12-25', NULL, 0, 120.00, 'hadir hasbi', '2025-12-25 12:34:19', '2025-12-25 12:34:19'),
(73, 3, '2025-12-25', NULL, 0, 150.00, 'hadir rio', '2025-12-25 12:34:49', '2025-12-25 12:34:49');

--
-- Triggers `catatan_panen`
--
DELIMITER $$
CREATE TRIGGER `tr_update_panen_rapot` AFTER INSERT ON `catatan_panen` FOR EACH ROW BEGIN
    CALL sp_log_aktivitas_user(NEW.id_pegawai, 'Tambah Panen', CONCAT('Panen ditambahkan sebanyak ', NEW.berat_kg, ' kg.'));
    CALL sp_log_system_event('PANEN_INSERT', CONCAT('Panen baru dimasukkan oleh user ID ', NEW.id_pegawai));
    INSERT INTO log_panen_activity (user_id, tanggal, berat_kg, area_id) VALUES (NEW.id_pegawai, NEW.tanggal, NEW.berat_kg, NEW.id_area_kerja);
    CALL sp_update_rapot_periode(NEW.tanggal, NEW.tanggal);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `log_absensi_activity`
--

CREATE TABLE `log_absensi_activity` (
  `id_log` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `jam_checkin` time DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_absensi_activity`
--

INSERT INTO `log_absensi_activity` (`id_log`, `user_id`, `tanggal`, `status`, `jam_checkin`, `waktu`) VALUES
(1, 1, '2025-12-02', 'tepat waktu', '04:58:10', '2025-12-01 21:58:10'),
(2, 3, '2025-12-02', 'tepat waktu', '05:02:20', '2025-12-01 22:02:20'),
(3, 3, '2025-12-02', 'tepat waktu', '05:04:19', '2025-12-01 22:04:19'),
(4, 3, '2025-12-02', 'tepat waktu', '05:13:20', '2025-12-01 22:13:20'),
(5, 3, '2025-12-02', 'tepat waktu', '05:24:26', '2025-12-01 22:24:26'),
(6, 3, '2025-12-02', 'tepat waktu', '05:32:29', '2025-12-01 22:32:29'),
(7, 7, '2025-12-02', 'terlambat', '08:11:33', '2025-12-02 01:11:33'),
(8, 1, '2025-12-03', 'tepat waktu', '08:00:00', '2025-12-03 15:16:10'),
(9, 1, '2025-12-03', 'tepat waktu', '08:00:00', '2025-12-03 15:16:10'),
(10, 1, '2025-12-03', 'tepat waktu', '07:30:00', '2025-12-03 15:16:42'),
(11, 7, '2025-12-08', 'terlambat', '16:36:40', '2025-12-08 09:36:40'),
(12, 3, '2025-12-09', 'tepat waktu', '03:00:23', '2025-12-08 20:00:23'),
(13, 7, '2025-12-09', 'tepat waktu', '03:19:11', '2025-12-08 20:19:11'),
(14, 1, '2025-12-09', 'terlambat', '20:24:01', '2025-12-09 13:24:02'),
(15, 6, '2025-12-09', 'terlambat', '22:03:46', '2025-12-09 15:03:46'),
(16, 3, '2025-12-15', 'tepat waktu', '01:19:58', '2025-12-14 18:19:58'),
(17, 1, '2025-12-15', 'terlambat', '12:13:24', '2025-12-15 05:13:24'),
(18, 2, '2025-12-02', 'tepat waktu', '07:05:00', '2025-12-02 00:05:00'),
(19, 2, '2025-12-09', 'terlambat', '08:20:00', '2025-12-09 01:20:00'),
(20, 2, '2025-12-15', 'tepat waktu', '07:10:00', '2025-12-15 00:10:00'),
(21, 4, '2025-12-02', 'tepat waktu', '07:00:00', '2025-12-02 00:00:00'),
(22, 4, '2025-12-08', 'terlambat', '08:05:00', '2025-12-08 01:05:00'),
(23, 4, '2025-12-15', 'tepat waktu', '07:15:00', '2025-12-15 00:15:00'),
(24, 5, '2025-12-03', 'tepat waktu', '07:30:00', '2025-12-03 00:30:00'),
(25, 5, '2025-12-09', 'terlambat', '08:25:00', '2025-12-09 01:25:00'),
(26, 5, '2025-12-15', 'tepat waktu', '07:20:00', '2025-12-15 00:20:00'),
(27, 2, '2025-12-03', 'tepat waktu', '07:10:00', '2025-12-16 19:45:23'),
(28, 4, '2025-12-03', 'terlambat', '08:05:00', '2025-12-16 19:45:23'),
(29, 5, '2025-12-03', 'tepat waktu', '07:20:00', '2025-12-16 19:45:23'),
(30, 2, '2025-12-08', 'tepat waktu', '07:05:00', '2025-12-16 19:45:23'),
(31, 4, '2025-12-08', 'terlambat', '08:10:00', '2025-12-16 19:45:23'),
(32, 5, '2025-12-08', 'tepat waktu', '07:25:00', '2025-12-16 19:45:23'),
(33, 2, '2025-12-16', 'tepat waktu', '07:15:00', '2025-12-16 19:45:23'),
(34, 4, '2025-12-16', 'terlambat', '08:20:00', '2025-12-16 19:45:23'),
(35, 5, '2025-12-16', 'tepat waktu', '07:10:00', '2025-12-16 19:45:23'),
(36, 5, '2025-12-18', 'terlambat', '13:31:21', '2025-12-18 06:31:21'),
(37, 1, '2025-12-18', 'terlambat', '13:34:34', '2025-12-18 06:34:34'),
(38, 7, '2025-12-18', 'terlambat', '19:00:06', '2025-12-18 12:00:06'),
(39, 3, '2025-12-18', 'terlambat', '19:08:46', '2025-12-18 12:08:46'),
(40, 7, '2025-12-19', 'terlambat', '23:42:26', '2025-12-19 16:42:26'),
(41, 1, '2025-12-19', 'terlambat', '23:44:07', '2025-12-19 16:44:07'),
(42, 6, '2025-12-19', 'terlambat', '23:45:20', '2025-12-19 16:45:20'),
(43, 1, '2025-12-20', 'terlambat', '23:22:25', '2025-12-20 16:22:25'),
(44, 7, '2025-12-21', 'tepat waktu', '00:24:36', '2025-12-20 17:24:36'),
(45, 3, '2025-12-21', 'tepat waktu', '00:45:39', '2025-12-20 17:45:39'),
(46, 6, '2025-12-21', 'tepat waktu', '01:34:20', '2025-12-20 18:34:20'),
(47, 10, '2025-12-21', 'tepat waktu', '01:50:17', '2025-12-20 18:50:17'),
(48, 1, '2025-12-21', 'tepat waktu', '01:57:53', '2025-12-20 18:57:53'),
(49, 7, '2025-12-22', 'terlambat', '20:40:41', '2025-12-22 13:40:41'),
(50, 1, '2025-12-23', 'tepat waktu', '01:12:13', '2025-12-22 18:12:13'),
(51, 5, '2025-12-23', 'tepat waktu', '01:29:12', '2025-12-22 18:29:12'),
(52, 3, '2025-12-23', 'tepat waktu', '01:38:03', '2025-12-22 18:38:03'),
(53, 7, '2025-12-23', 'tepat waktu', '01:39:58', '2025-12-22 18:39:58'),
(55, 7, '2025-12-23', 'tepat waktu', '02:12:18', '2025-12-22 19:12:18'),
(56, 6, '2025-12-23', 'terlambat', '17:49:36', '2025-12-23 10:49:36'),
(58, 1, '2025-12-24', 'terlambat', '17:53:04', '2025-12-24 10:53:04'),
(59, 7, '2025-12-25', 'terlambat', '19:28:53', '2025-12-25 12:28:53'),
(60, 3, '2025-12-25', 'terlambat', '19:29:06', '2025-12-25 12:29:06'),
(61, 5, '2025-12-25', 'terlambat', '19:29:27', '2025-12-25 12:29:27'),
(62, 6, '2025-12-25', 'terlambat', '19:29:41', '2025-12-25 12:29:41'),
(63, 1, '2025-12-25', 'terlambat', '19:32:38', '2025-12-25 12:32:38'),
(64, 10, '2025-12-25', 'terlambat', '19:35:10', '2025-12-25 12:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas_user`
--

CREATE TABLE `log_aktivitas_user` (
  `id_log` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `aksi` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas_user`
--

INSERT INTO `log_aktivitas_user` (`id_log`, `user_id`, `aksi`, `deskripsi`, `waktu`) VALUES
(1, 5, 'User Created', 'Manager menambahkan user baru: samuel', '2025-12-01 21:51:49'),
(2, 6, 'User Created', 'Manager menambahkan user baru: yosua', '2025-12-01 21:52:40'),
(3, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-02', '2025-12-01 21:58:10'),
(4, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-02', '2025-12-01 22:02:20'),
(5, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-02', '2025-12-01 22:04:19'),
(6, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-02', '2025-12-01 22:13:20'),
(8, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 244.00 kg.', '2025-12-01 22:23:39'),
(9, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-02', '2025-12-01 22:24:26'),
(10, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 233.00 kg.', '2025-12-01 22:24:37'),
(11, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-02', '2025-12-01 22:32:29'),
(12, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 244.00 kg.', '2025-12-01 22:32:38'),
(13, 7, 'User Created', 'Manager menambahkan user baru: hasbi', '2025-12-02 01:11:16'),
(14, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-02', '2025-12-02 01:11:33'),
(15, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 234.00 kg.', '2025-12-02 01:11:42'),
(16, 1, 'TEST', 'Testing procedure', '2025-12-03 15:15:53'),
(17, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-03', '2025-12-03 15:16:10'),
(18, 1, 'Absensi', 'Absensi pada 2025-12-03 status: tepat waktu', '2025-12-03 15:16:10'),
(19, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 150.50 kg.', '2025-12-03 15:16:25'),
(20, 3, 'Tambah Panen', 'Tambah panen 150.50 kg pada 2025-12-03', '2025-12-03 15:16:25'),
(21, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-03', '2025-12-03 15:16:42'),
(22, 8, 'User Created', 'Manager menambahkan user baru: test_user', '2025-12-03 15:16:56'),
(23, 8, 'User Deleted', 'User dihapus: test_user', '2025-12-03 15:17:04'),
(24, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-08', '2025-12-08 09:36:40'),
(25, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 30.00 kg.', '2025-12-08 09:38:54'),
(26, 9, 'User Created', 'Manager menambahkan user baru: admin', '2025-12-08 19:31:27'),
(27, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-09', '2025-12-08 20:00:23'),
(28, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 30.00 kg.', '2025-12-08 20:00:39'),
(29, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 30.00 kg.', '2025-12-08 20:00:39'),
(30, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-09', '2025-12-08 20:19:11'),
(31, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 100.00 kg.', '2025-12-08 20:19:23'),
(32, 10, 'User Created', 'Manager menambahkan user baru: mas', '2025-12-08 20:55:37'),
(33, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-09', '2025-12-09 13:24:02'),
(34, 6, 'Absensi', 'User 6 melakukan absensi pada 2025-12-09', '2025-12-09 15:03:46'),
(35, 11, 'User Created', 'Manager menambahkan user baru: aaa', '2025-12-09 15:23:27'),
(36, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-15', '2025-12-14 18:19:58'),
(37, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 123.00 kg.', '2025-12-14 18:20:08'),
(38, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-15', '2025-12-15 05:13:24'),
(39, 2, 'Absensi', 'User 2 melakukan absensi pada 2025-12-03', '2025-12-16 19:45:23'),
(40, 4, 'Absensi', 'User 4 melakukan absensi pada 2025-12-03', '2025-12-16 19:45:23'),
(41, 5, 'Absensi', 'User 5 melakukan absensi pada 2025-12-03', '2025-12-16 19:45:23'),
(42, 2, 'Absensi', 'User 2 melakukan absensi pada 2025-12-08', '2025-12-16 19:45:23'),
(43, 4, 'Absensi', 'User 4 melakukan absensi pada 2025-12-08', '2025-12-16 19:45:23'),
(44, 5, 'Absensi', 'User 5 melakukan absensi pada 2025-12-08', '2025-12-16 19:45:23'),
(45, 2, 'Absensi', 'User 2 melakukan absensi pada 2025-12-16', '2025-12-16 19:45:23'),
(46, 4, 'Absensi', 'User 4 melakukan absensi pada 2025-12-16', '2025-12-16 19:45:23'),
(47, 5, 'Absensi', 'User 5 melakukan absensi pada 2025-12-16', '2025-12-16 19:45:23'),
(53, 11, 'User Deleted', 'User dihapus: aaa', '2025-12-18 05:41:35'),
(54, 5, 'Absensi', 'User 5 melakukan absensi pada 2025-12-18', '2025-12-18 06:31:21'),
(55, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-18', '2025-12-18 06:34:34'),
(56, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-18', '2025-12-18 12:00:06'),
(61, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 200.00 kg.', '2025-12-18 12:08:28'),
(62, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-18', '2025-12-18 12:08:46'),
(63, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 423.00 kg.', '2025-12-18 12:09:04'),
(64, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-19', '2025-12-19 16:42:26'),
(65, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 60.00 kg.', '2025-12-19 16:42:58'),
(66, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-19', '2025-12-19 16:44:07'),
(67, 6, 'Absensi', 'User 6 melakukan absensi pada 2025-12-19', '2025-12-19 16:45:20'),
(68, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-20', '2025-12-20 16:22:25'),
(69, 12, 'User Created', 'Manager menambahkan user baru: halo', '2025-12-20 16:43:33'),
(70, 12, 'User Deleted', 'User dihapus: halo', '2025-12-20 16:55:41'),
(71, 13, 'User Created', 'Manager menambahkan user baru: halo', '2025-12-20 16:55:56'),
(72, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-21', '2025-12-20 17:24:36'),
(73, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 123.00 kg.', '2025-12-20 17:24:56'),
(74, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-21', '2025-12-20 17:45:39'),
(75, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 123.00 kg.', '2025-12-20 18:28:16'),
(76, 6, 'Absensi', 'User 6 melakukan absensi pada 2025-12-21', '2025-12-20 18:34:20'),
(77, 10, 'Absensi', 'User 10 melakukan absensi pada 2025-12-21', '2025-12-20 18:50:17'),
(78, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-21', '2025-12-20 18:57:53'),
(79, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-22', '2025-12-22 13:40:41'),
(80, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 70.00 kg.', '2025-12-22 13:40:53'),
(81, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-23', '2025-12-22 18:12:13'),
(82, 5, 'Absensi', 'User 5 melakukan absensi pada 2025-12-23', '2025-12-22 18:29:12'),
(83, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-23', '2025-12-22 18:38:03'),
(84, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 1000.00 kg.', '2025-12-22 18:38:24'),
(85, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-23', '2025-12-22 18:39:58'),
(86, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 855.00 kg.', '2025-12-22 18:40:12'),
(87, 6, 'Absensi', 'User 6 melakukan absensi pada 2025-12-23', '2025-12-22 19:07:38'),
(88, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-23', '2025-12-22 19:12:18'),
(89, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 67.00 kg.', '2025-12-22 19:18:22'),
(90, 6, 'Absensi', 'User 6 melakukan absensi pada 2025-12-23', '2025-12-23 10:49:36'),
(92, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-24', '2025-12-24 10:53:04'),
(93, 7, 'Absensi', 'User 7 melakukan absensi pada 2025-12-25', '2025-12-25 12:28:53'),
(94, 3, 'Absensi', 'User 3 melakukan absensi pada 2025-12-25', '2025-12-25 12:29:06'),
(95, 5, 'Absensi', 'User 5 melakukan absensi pada 2025-12-25', '2025-12-25 12:29:27'),
(96, 6, 'Absensi', 'User 6 melakukan absensi pada 2025-12-25', '2025-12-25 12:29:41'),
(97, 1, 'Absensi', 'User 1 melakukan absensi pada 2025-12-25', '2025-12-25 12:32:38'),
(98, 7, 'Tambah Panen', 'Panen ditambahkan sebanyak 120.00 kg.', '2025-12-25 12:34:19'),
(99, 3, 'Tambah Panen', 'Panen ditambahkan sebanyak 150.00 kg.', '2025-12-25 12:34:49'),
(100, 10, 'Absensi', 'User 10 melakukan absensi pada 2025-12-25', '2025-12-25 12:35:10'),
(101, 14, 'User Created', 'Manager menambahkan user baru: San Jossye Sitanggang', '2025-12-25 17:04:54'),
(102, 14, 'User Deleted', 'User dihapus: San Jossye Sitanggang', '2025-12-25 17:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `log_error_event`
--

CREATE TABLE `log_error_event` (
  `id_error` bigint(20) UNSIGNED NOT NULL,
  `modul` varchar(100) NOT NULL,
  `pesan_error` text DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_panen_activity`
--

CREATE TABLE `log_panen_activity` (
  `id_log` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `berat_kg` decimal(9,2) DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_panen_activity`
--

INSERT INTO `log_panen_activity` (`id_log`, `user_id`, `tanggal`, `berat_kg`, `area_id`, `waktu`) VALUES
(2, 3, '2025-12-02', 244.00, NULL, '2025-12-01 22:23:39'),
(3, 3, '2025-12-02', 233.00, NULL, '2025-12-01 22:24:37'),
(4, 3, '2025-12-02', 244.00, NULL, '2025-12-01 22:32:38'),
(5, 7, '2025-12-02', 234.00, NULL, '2025-12-02 01:11:42'),
(6, 3, '2025-12-03', 150.50, 1, '2025-12-03 15:16:25'),
(7, 3, '2025-12-03', 150.50, 1, '2025-12-03 15:16:25'),
(8, 7, '2025-12-08', 30.00, NULL, '2025-12-08 09:38:54'),
(9, 3, '2025-12-09', 30.00, NULL, '2025-12-08 20:00:39'),
(10, 3, '2025-12-09', 30.00, NULL, '2025-12-08 20:00:39'),
(11, 7, '2025-12-09', 100.00, NULL, '2025-12-08 20:19:23'),
(12, 3, '2025-12-15', 123.00, NULL, '2025-12-14 18:20:08'),
(22, 7, '2025-12-18', 200.00, NULL, '2025-12-18 12:08:28'),
(23, 3, '2025-12-18', 423.00, NULL, '2025-12-18 12:09:04'),
(24, 7, '2025-12-19', 60.00, NULL, '2025-12-19 16:42:58'),
(25, 7, '2025-12-21', 123.00, NULL, '2025-12-20 17:24:56'),
(26, 3, '2025-12-21', 123.00, NULL, '2025-12-20 18:28:16'),
(27, 7, '2025-12-22', 70.00, NULL, '2025-12-22 13:40:53'),
(28, 3, '2025-12-23', 1000.00, NULL, '2025-12-22 18:38:24'),
(29, 7, '2025-12-23', 855.00, NULL, '2025-12-22 18:40:12'),
(30, 7, '2025-12-23', 67.00, NULL, '2025-12-22 19:18:22'),
(31, 7, '2025-12-25', 120.00, NULL, '2025-12-25 12:34:19'),
(32, 3, '2025-12-25', 150.00, NULL, '2025-12-25 12:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `log_system_event`
--

CREATE TABLE `log_system_event` (
  `id_event` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `waktu` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_system_event`
--

INSERT INTO `log_system_event` (`id_event`, `event_name`, `deskripsi`, `waktu`) VALUES
(1, 'USER_ADD', 'User baru ditambahkan oleh manager: samuel', '2025-12-01 21:51:49'),
(2, 'USER_ADD', 'User baru ditambahkan oleh manager: yosua', '2025-12-01 21:52:40'),
(3, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-01 21:58:10'),
(4, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-01 22:02:20'),
(5, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-01 22:04:19'),
(6, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-01 22:13:20'),
(8, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-01 22:23:39'),
(9, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-01 22:24:26'),
(10, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-01 22:24:37'),
(11, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-01 22:32:29'),
(12, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-01 22:32:38'),
(13, 'USER_ADD', 'User baru ditambahkan oleh manager: hasbi', '2025-12-02 01:11:16'),
(14, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-02 01:11:33'),
(15, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-02 01:11:42'),
(16, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-03 15:16:10'),
(17, 'ABSENSI_INSERT_OR_UPDATE', 'User 1 attendance for 2025-12-03', '2025-12-03 15:16:10'),
(18, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-03 15:16:25'),
(19, 'PANEN_INSERT', 'Panen oleh user 3 tanggal 2025-12-03', '2025-12-03 15:16:25'),
(20, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-03 15:16:42'),
(21, 'USER_ADD', 'User baru ditambahkan oleh manager: test_user', '2025-12-03 15:16:56'),
(22, 'USER_DELETE', 'Data user dihapus: test_user', '2025-12-03 15:17:04'),
(23, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-08 09:36:40'),
(24, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-08 09:38:54'),
(25, 'USER_ADD', 'User baru ditambahkan oleh manager: admin', '2025-12-08 19:31:27'),
(26, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-08 20:00:23'),
(27, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-08 20:00:39'),
(28, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-08 20:00:39'),
(29, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-08 20:19:11'),
(30, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-08 20:19:23'),
(31, 'USER_ADD', 'User baru ditambahkan oleh manager: mas', '2025-12-08 20:55:37'),
(32, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-09 13:24:02'),
(33, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 6', '2025-12-09 15:03:46'),
(34, 'USER_ADD', 'User baru ditambahkan oleh manager: aaa', '2025-12-09 15:23:27'),
(35, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-14 18:19:58'),
(36, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-14 18:20:08'),
(37, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-15 05:13:24'),
(38, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 2', '2025-12-16 19:45:23'),
(39, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 4', '2025-12-16 19:45:23'),
(40, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 5', '2025-12-16 19:45:23'),
(41, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 2', '2025-12-16 19:45:23'),
(42, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 4', '2025-12-16 19:45:23'),
(43, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 5', '2025-12-16 19:45:23'),
(44, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 2', '2025-12-16 19:45:23'),
(45, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 4', '2025-12-16 19:45:23'),
(46, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 5', '2025-12-16 19:45:23'),
(52, 'USER_DELETE', 'Data user dihapus: aaa', '2025-12-18 05:41:35'),
(53, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 5', '2025-12-18 06:31:21'),
(54, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-18 06:34:34'),
(55, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-18 12:00:06'),
(60, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-18 12:08:28'),
(61, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-18 12:08:46'),
(62, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-18 12:09:04'),
(63, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-19 16:42:26'),
(64, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-19 16:42:58'),
(65, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-19 16:44:07'),
(66, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 6', '2025-12-19 16:45:20'),
(67, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-20 16:22:25'),
(68, 'USER_ADD', 'User baru ditambahkan oleh manager: halo', '2025-12-20 16:43:33'),
(69, 'USER_DELETE', 'Data user dihapus: halo', '2025-12-20 16:55:41'),
(70, 'USER_ADD', 'User baru ditambahkan oleh manager: halo', '2025-12-20 16:55:56'),
(71, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-20 17:24:36'),
(72, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-20 17:24:56'),
(73, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-20 17:45:39'),
(74, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-20 18:28:16'),
(75, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 6', '2025-12-20 18:34:20'),
(76, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 10', '2025-12-20 18:50:17'),
(77, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-20 18:57:53'),
(78, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-22 13:40:41'),
(79, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-22 13:40:53'),
(80, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-22 18:12:13'),
(81, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 5', '2025-12-22 18:29:12'),
(82, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-22 18:38:03'),
(83, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-22 18:38:24'),
(84, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-22 18:39:58'),
(85, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-22 18:40:12'),
(86, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 6', '2025-12-22 19:07:38'),
(87, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-22 19:12:18'),
(88, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-22 19:18:22'),
(89, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 6', '2025-12-23 10:49:36'),
(91, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-24 10:53:04'),
(92, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 7', '2025-12-25 12:28:53'),
(93, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 3', '2025-12-25 12:29:06'),
(94, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 5', '2025-12-25 12:29:27'),
(95, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 6', '2025-12-25 12:29:41'),
(96, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 1', '2025-12-25 12:32:38'),
(97, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 7', '2025-12-25 12:34:19'),
(98, 'PANEN_INSERT', 'Panen baru dimasukkan oleh user ID 3', '2025-12-25 12:34:49'),
(99, 'ABSENSI_INSERT', 'Data absensi baru ditambahkan untuk user 10', '2025-12-25 12:35:10'),
(100, 'USER_ADD', 'User baru ditambahkan oleh manager: San Jossye Sitanggang', '2025-12-25 17:04:54'),
(101, 'USER_DELETE', 'Data user dihapus: San Jossye Sitanggang', '2025-12-25 17:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `log_transaksi`
--

CREATE TABLE `log_transaksi` (
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `nama_proses` varchar(150) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'PENDING',
  `pesan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_transaksi`
--

INSERT INTO `log_transaksi` (`id_transaksi`, `nama_proses`, `waktu_mulai`, `waktu_selesai`, `status`, `pesan`, `created_at`) VALUES
(1, 'sp_transaksi_absensi_atomic', '2025-12-03 22:16:10', '2025-12-03 22:16:10', 'SUCCESS', 'Absensi processed for user 1', '2025-12-03 15:16:10'),
(2, 'sp_transaksi_panen_atomic', '2025-12-03 22:16:25', '2025-12-03 22:16:25', 'SUCCESS', 'Inserted panen and updated rapot for user 3', '2025-12-03 15:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2025_11_04_134554_create_attendances_table_new', 1),
(5, '2025_11_06_014752_rename_users_to_pegawai_table', 1),
(6, '0001_01_01_000000_create_users_table', 2),
(7, '0001_01_01_000001_create_cache_table', 2),
(8, '0001_01_01_000002_create_jobs_table', 2),
(9, '2025_11_20_041229_add_palm_weight_to_attendances_table', 3),
(10, '2025_11_23_214648_create_announcements_table', 4),
(11, '2025_12_03_003844_add_judul_isi_to_announcements_table', 4),
(12, '2025_12_09_203038_create_rapots_table', 4),
(13, '2025_12_15_011527_add_evaluation_columns_to_rapot_table', 5),
(14, '2025_12_16_000003_create_rapot_table', 6),
(15, '2025_12_16_181322_rename_id_user_to_user_id_on_rapot', 7),
(16, '2025_12_16_183539_add_total_jam_to_rapot_table', 8),
(17, '2025_12_16_215923_create_rapot_table', 9),
(18, '2025_12_16_235936_fix_attendance_datetime_format', 10),
(19, '2025_12_20_233147_add_shift_fields_to_users_table', 11),
(20, '2025_12_21_010023_add_photo_fields_to_attendances_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_reports`
--

CREATE TABLE `monthly_reports` (
  `id` bigint(20) NOT NULL,
  `report_period` varchar(50) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `total_employees` int(11) DEFAULT 0,
  `total_attendance` int(11) DEFAULT 0,
  `total_late` int(11) DEFAULT 0,
  `total_panen` decimal(12,2) DEFAULT 0.00,
  `avg_panen_per_employee` decimal(10,2) DEFAULT 0.00,
  `top_performer_id` bigint(20) DEFAULT NULL,
  `top_performer_name` varchar(255) DEFAULT NULL,
  `top_performer_panen` decimal(10,2) DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rapot`
--

CREATE TABLE `rapot` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `periode` varchar(100) NOT NULL,
  `periode_start` date DEFAULT NULL,
  `periode_end` date DEFAULT NULL,
  `total_jam` double NOT NULL DEFAULT 0,
  `hari_kerja` int(11) NOT NULL DEFAULT 0,
  `rata_jam_perhari` double NOT NULL DEFAULT 0,
  `nilai` double NOT NULL DEFAULT 0,
  `detail_absen` longtext DEFAULT NULL CHECK (json_valid(`detail_absen`)),
  `evaluator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `evaluasi_kerja` varchar(255) DEFAULT NULL,
  `saran_perbaikan` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `data_evaluasi` longtext DEFAULT NULL CHECK (json_valid(`data_evaluasi`)),
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `tipe` varchar(50) NOT NULL DEFAULT 'standar',
  `generated_at` timestamp NULL DEFAULT NULL,
  `regenerated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rapot`
--

INSERT INTO `rapot` (`id`, `id_user`, `periode`, `periode_start`, `periode_end`, `total_jam`, `hari_kerja`, `rata_jam_perhari`, `nilai`, `detail_absen`, `evaluator_id`, `evaluasi_kerja`, `saran_perbaikan`, `catatan`, `data_evaluasi`, `status`, `tipe`, `generated_at`, `regenerated_at`, `created_at`, `updated_at`) VALUES
(11, 3, '01 Dec 2025 - 31 Dec 2025', '2025-12-01', '2025-12-31', 21.16, 3, 0, -10, '\"[{\\\"tanggal\\\":\\\"02\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"07:00\\\",\\\"check_out\\\":\\\"15:05\\\",\\\"jam_kerja\\\":7.08,\\\"status\\\":\\\"tepat waktu\\\"},{\\\"tanggal\\\":\\\"09\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"07:00\\\",\\\"check_out\\\":\\\"15:00\\\",\\\"jam_kerja\\\":7,\\\"status\\\":\\\"tepat waktu\\\"},{\\\"tanggal\\\":\\\"15\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"07:05\\\",\\\"check_out\\\":\\\"15:10\\\",\\\"jam_kerja\\\":7.08,\\\"status\\\":\\\"tepat waktu\\\"}]\"', 9, 'mengerjakan jangan malas malas', 'kurangin begadang dan yang aneh', '', '\"{\\\"evaluasi_kerja\\\":\\\"mengerjakan jangan malas malas\\\",\\\"saran_perbaikan\\\":\\\"kurangin begadang dan yang aneh\\\",\\\"nilai_akhir\\\":-10,\\\"nilai_skala_10\\\":-1,\\\"hari_hadir\\\":3,\\\"total_jam_kerja\\\":21.16,\\\"rata_jam_perhari\\\":7.05,\\\"total_terlambat\\\":0,\\\"persentase_kehadiran\\\":-10,\\\"total_hari_periode\\\":-29.999999999988425,\\\"status_evaluasi\\\":\\\"dikirim\\\"}\"', 'Perlu Perbaikan', 'evaluasi_kinerja', '2025-12-16 20:22:26', NULL, '2025-12-16 20:22:26', '2025-12-16 20:22:26'),
(14, 2, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-18 12:08:28', '2025-12-25 12:34:49'),
(15, 10, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-18 12:08:28', '2025-12-25 12:34:49'),
(16, 9, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-18 12:08:28', '2025-12-25 12:34:49'),
(17, 3, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 151, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-18 12:08:28', '2025-12-25 12:34:49'),
(18, 5, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-18 12:08:28', '2025-12-25 12:34:49'),
(20, 7, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 121, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-18 12:08:28', '2025-12-25 12:34:49'),
(21, 7, '01 Dec 2025 - 31 Dec 2025', '2025-12-01', '2025-12-31', 21.69, 3, 0, -13.33, '\"[{\\\"tanggal\\\":\\\"02\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"08:11\\\",\\\"check_out\\\":\\\"16:10\\\",\\\"jam_kerja\\\":6.98,\\\"status\\\":\\\"terlambat\\\"},{\\\"tanggal\\\":\\\"08\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"08:00\\\",\\\"check_out\\\":\\\"16:38\\\",\\\"jam_kerja\\\":7.63,\\\"status\\\":\\\"terlambat\\\"},{\\\"tanggal\\\":\\\"09\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"07:15\\\",\\\"check_out\\\":\\\"15:20\\\",\\\"jam_kerja\\\":7.08,\\\"status\\\":\\\"tepat waktu\\\"},{\\\"tanggal\\\":\\\"18\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"19:00\\\",\\\"check_out\\\":\\\"19:08\\\",\\\"jam_kerja\\\":0,\\\"status\\\":\\\"terlambat\\\"}]\"', 9, 'kurang inisiatif', 'harus inisiatif, tidak perlu disuruh suruh', '', '\"{\\\"evaluasi_kerja\\\":\\\"kurang inisiatif\\\",\\\"saran_perbaikan\\\":\\\"harus inisiatif, tidak perlu disuruh suruh\\\",\\\"nilai_akhir\\\":-13.33,\\\"nilai_skala_10\\\":-1.3,\\\"hari_hadir\\\":4,\\\"total_jam_kerja\\\":21.689999999999998,\\\"rata_jam_perhari\\\":7.23,\\\"total_terlambat\\\":3,\\\"persentase_kehadiran\\\":-13.33,\\\"total_hari_periode\\\":-29.999999999988425,\\\"status_evaluasi\\\":\\\"selesai\\\"}\"', 'Perlu Perbaikan', 'evaluasi_kinerja', '2025-12-18 12:19:08', NULL, '2025-12-18 12:19:09', '2025-12-18 12:19:09'),
(23, 4, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-20 18:28:16', '2025-12-25 12:34:49'),
(25, 6, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-20 18:28:16', '2025-12-25 12:34:49'),
(26, 13, 'Dec-2025 - Dec-2025', NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', 'standar', NULL, NULL, '2025-12-20 18:28:16', '2025-12-25 12:34:49'),
(29, 1, '01 Dec 2025 - 31 Dec 2025', '2025-12-01', '2025-12-31', 7.67, 1, 0, -20, '\"[{\\\"tanggal\\\":\\\"09\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"08:20\\\",\\\"check_out\\\":\\\"17:00\\\",\\\"jam_kerja\\\":7.67,\\\"status\\\":\\\"terlambat\\\"},{\\\"tanggal\\\":\\\"18\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"13:34\\\",\\\"check_out\\\":\\\"13:35\\\",\\\"jam_kerja\\\":0,\\\"status\\\":\\\"terlambat\\\"},{\\\"tanggal\\\":\\\"19\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"23:44\\\",\\\"check_out\\\":\\\"23:44\\\",\\\"jam_kerja\\\":0,\\\"status\\\":\\\"terlambat\\\"},{\\\"tanggal\\\":\\\"23\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"01:12\\\",\\\"check_out\\\":\\\"01:12\\\",\\\"jam_kerja\\\":0,\\\"status\\\":\\\"tepat waktu\\\"},{\\\"tanggal\\\":\\\"24\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"17:53\\\",\\\"check_out\\\":\\\"17:55\\\",\\\"jam_kerja\\\":0,\\\"status\\\":\\\"terlambat\\\"},{\\\"tanggal\\\":\\\"25\\\\\\/12\\\\\\/2025\\\",\\\"check_in\\\":\\\"19:32\\\",\\\"check_out\\\":\\\"19:33\\\",\\\"jam_kerja\\\":0,\\\"status\\\":\\\"terlambat\\\"}]\"', 9, 'sudah baik, pertahankan terus', 'tidak ada, pertahankan', 'tidak ada', '\"{\\\"evaluasi_kerja\\\":\\\"sudah baik, pertahankan terus\\\",\\\"saran_perbaikan\\\":\\\"tidak ada, pertahankan\\\",\\\"nilai_akhir\\\":-20,\\\"nilai_skala_10\\\":-2,\\\"hari_hadir\\\":6,\\\"total_jam_kerja\\\":7.67,\\\"rata_jam_perhari\\\":7.67,\\\"total_terlambat\\\":5,\\\"persentase_kehadiran\\\":-20,\\\"total_hari_periode\\\":-29.999999999988425,\\\"status_evaluasi\\\":\\\"dikirim\\\"}\"', 'Perlu Perbaikan', 'evaluasi_kinerja', '2025-12-25 15:44:55', NULL, '2025-12-25 15:44:55', '2025-12-25 15:44:55');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('l8wxi88TC1pPR0yz28WCKFbfLuiEZI2ZhxOvStxM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU3hNVEpIdUZ5TTVlMVY4bFZ5SW1hZnhpOE1lRGx5NnhnQ2Jxbk9jZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21hbmFnZXIvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766679976),
('TWMYzMNbqHRMFxNgzpr8HKh2lwcB2Dbjse7SdSB6', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU05PR2FOSXM3aTVrVmtnUGRTZmRpbnd2WHJuM3ltZW9zRUxpYnI1MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZW5ndW11bWFuIjtzOjU6InJvdXRlIjtzOjE1OiJwZW5ndW11bWFuLnVzZXIiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1766684591);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `no_hp`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'mufti', '081234567890', 'security', '$2y$12$2wdU7r4w3gTZcHE8ezZECuqIqOI5TyUHfOlYrvs.zQVH9SYC.TrFO', '2025-11-22 09:18:19', '2025-11-22 09:18:19'),
(2, 'zaidan', '081234567891', 'manager', '$2y$12$K5BDy6nCs7HwtzfMGHBcT.mmesYJ9qscIzCRxFkLL0XZLXyDnLCAu', '2025-11-22 09:18:22', '2025-11-22 09:18:22'),
(3, 'rio', '082167695491', 'user', '$2y$12$K5BDy6nCs7HwtzfMGHBcT.mmesYJ9qscIzCRxFkLL0XZLXyDnLCAu', '2025-11-22 09:49:23', '2025-11-22 09:49:23'),
(4, 'ronaldo', '070707070707', 'user', '$2y$12$p6CGTUdIMvIxCpQE4/E6neNP8rIc.nbHHbbfx/Y89dz9cz7Oi.CJS', '2025-11-24 01:23:37', '2025-11-24 01:23:37'),
(5, 'samuel', '089538409741', 'cleaning', '$2y$12$8HLZjsdNP6esLND7BgYQWeZlfKUxoaHHedSvn6ZHTA.4KLpgMlg7a', '2025-12-01 21:51:49', '2025-12-01 21:51:49'),
(6, 'yosua', '089538409742', 'kantoran', '$2y$12$eypk5upxwhsW.D34vOxXHe8hUEZcSmZnB.JG4nIbwDcn85HMr9Roa', '2025-12-01 21:52:40', '2025-12-01 21:52:40'),
(7, 'hasbi', '089538409743', 'user', '$2y$12$2JHkgyr08XaDGjQO/vnFweWVZY0YJ3wA9qww9ZE.EZbgkWENz31A6', '2025-12-02 01:11:16', '2025-12-02 01:11:16'),
(9, 'admin', '081234567899', 'admin', '$2y$12$2wdU7r4w3gTZcHE8ezZECuqIqOI5TyUHfOlYrvs.zQVH9SYC.TrFO', '2025-11-22 02:18:19', '2025-11-22 02:18:19'),
(10, 'mas', '081234567897', 'security', '$2y$12$iE3SuZpM4UfNtTzwrE/ca.HZpfytoi3NtfzfVDyGuAUWn20uELRfq', '2025-12-08 20:55:37', '2025-12-08 20:55:37'),
(13, 'Ivan Gunakan', '087915156767', 'security', '$2y$12$jeej0Ava9K60WFZ1FbGV9OaSCIG8sMQGnhIr1cp5FpT9TtuvoPVei', '2025-12-20 16:55:56', '2025-12-25 17:09:17');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `tr_log_user_created` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    CALL sp_log_aktivitas_user(NEW.id, 'User Created', CONCAT('Manager menambahkan user baru: ', NEW.name));
    CALL sp_log_system_event('USER_ADD', CONCAT('User baru ditambahkan oleh manager: ', NEW.name));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_log_user_deleted` AFTER DELETE ON `users` FOR EACH ROW BEGIN
    CALL sp_log_aktivitas_user(OLD.id, 'User Deleted', CONCAT('User dihapus: ', OLD.name));
    CALL sp_log_system_event('USER_DELETE', CONCAT('Data user dihapus: ', OLD.name));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_absensi_dan_panen_harian`
-- (See below for the actual view)
--
CREATE TABLE `view_absensi_dan_panen_harian` (
`date` date
,`user_id` bigint(20) unsigned
,`name` varchar(255)
,`check_in` time
,`check_out` time
,`status` varchar(255)
,`jumlah_tandan` int(10) unsigned
,`berat_kg` decimal(9,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_peringkat_kinerja`
-- (See below for the actual view)
--
CREATE TABLE `view_peringkat_kinerja` (
`user_id` bigint(20) unsigned
,`name` varchar(255)
,`total_berat` decimal(31,2)
,`total_absensi` bigint(21)
,`skor_performa` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_summary_absensi`
-- (See below for the actual view)
--
CREATE TABLE `view_summary_absensi` (
`user_id` bigint(20) unsigned
,`name` varchar(255)
,`total_kehadiran` bigint(21)
,`total_terlambat` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_total_panen_per_area`
-- (See below for the actual view)
--
CREATE TABLE `view_total_panen_per_area` (
`area_id` bigint(20) unsigned
,`area_name` varchar(80)
,`total_berat` decimal(31,2)
,`total_tandan` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_total_panen_per_pegawai`
-- (See below for the actual view)
--
CREATE TABLE `view_total_panen_per_pegawai` (
`user_id` bigint(20) unsigned
,`name` varchar(255)
,`total_berat` decimal(31,2)
,`total_tandan` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_roles_summary`
-- (See below for the actual view)
--
CREATE TABLE `view_user_roles_summary` (
`role` varchar(255)
,`total_user` bigint(21)
);

-- --------------------------------------------------------

--
-- Structure for view `view_absensi_dan_panen_harian`
--
DROP TABLE IF EXISTS `view_absensi_dan_panen_harian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_absensi_dan_panen_harian`  AS SELECT `a`.`date` AS `date`, `u`.`id` AS `user_id`, `u`.`name` AS `name`, `a`.`check_in` AS `check_in`, `a`.`check_out` AS `check_out`, `a`.`status` AS `status`, `cp`.`jumlah_tandan` AS `jumlah_tandan`, `cp`.`berat_kg` AS `berat_kg` FROM ((`users` `u` left join `attendances` `a` on(`u`.`id` = `a`.`user_id`)) left join `catatan_panen` `cp` on(`u`.`id` = `cp`.`id_pegawai` and `a`.`date` = `cp`.`tanggal`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_peringkat_kinerja`
--
DROP TABLE IF EXISTS `view_peringkat_kinerja`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_peringkat_kinerja`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, ifnull(sum(`cp`.`berat_kg`),0) AS `total_berat`, ifnull(count(`a`.`id`),0) AS `total_absensi`, ifnull(sum(`cp`.`berat_kg`),0) + ifnull(count(`a`.`id`),0) AS `skor_performa` FROM ((`users` `u` left join `attendances` `a` on(`u`.`id` = `a`.`user_id`)) left join `catatan_panen` `cp` on(`u`.`id` = `cp`.`id_pegawai`)) GROUP BY `u`.`id`, `u`.`name` ORDER BY ifnull(sum(`cp`.`berat_kg`),0) + ifnull(count(`a`.`id`),0) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_summary_absensi`
--
DROP TABLE IF EXISTS `view_summary_absensi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_summary_absensi`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, count(`a`.`id`) AS `total_kehadiran`, sum(case when `a`.`status` = 'terlambat' then 1 else 0 end) AS `total_terlambat` FROM (`users` `u` left join `attendances` `a` on(`u`.`id` = `a`.`user_id`)) GROUP BY `u`.`id`, `u`.`name` ;

-- --------------------------------------------------------

--
-- Structure for view `view_total_panen_per_area`
--
DROP TABLE IF EXISTS `view_total_panen_per_area`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_total_panen_per_area`  AS SELECT `ak`.`id` AS `area_id`, `ak`.`nama` AS `area_name`, sum(`cp`.`berat_kg`) AS `total_berat`, sum(`cp`.`jumlah_tandan`) AS `total_tandan` FROM (`area_kerja` `ak` left join `catatan_panen` `cp` on(`ak`.`id` = `cp`.`id_area_kerja`)) GROUP BY `ak`.`id`, `ak`.`nama` ;

-- --------------------------------------------------------

--
-- Structure for view `view_total_panen_per_pegawai`
--
DROP TABLE IF EXISTS `view_total_panen_per_pegawai`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_total_panen_per_pegawai`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, sum(`cp`.`berat_kg`) AS `total_berat`, sum(`cp`.`jumlah_tandan`) AS `total_tandan` FROM (`users` `u` left join `catatan_panen` `cp` on(`u`.`id` = `cp`.`id_pegawai`)) GROUP BY `u`.`id`, `u`.`name` ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_roles_summary`
--
DROP TABLE IF EXISTS `view_user_roles_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_roles_summary`  AS SELECT `users`.`role` AS `role`, count(0) AS `total_user` FROM `users` GROUP BY `users`.`role` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengumuman_user` (`created_by`);

--
-- Indexes for table `archive_history`
--
ALTER TABLE `archive_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_archive_date` (`archive_date`);

--
-- Indexes for table `area_kerja`
--
ALTER TABLE `area_kerja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_area_kerja_kode` (`kode`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`),
  ADD KEY `idx_attendance_user_date` (`user_id`,`date`);

--
-- Indexes for table `attendances_archive`
--
ALTER TABLE `attendances_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`),
  ADD KEY `idx_attendance_user_date` (`user_id`,`date`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `catatan_panen`
--
ALTER TABLE `catatan_panen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_panen_pegawai` (`id_pegawai`),
  ADD KEY `fk_panen_area` (`id_area_kerja`),
  ADD KEY `idx_panen_pegawai_tanggal` (`id_pegawai`,`tanggal`);

--
-- Indexes for table `log_absensi_activity`
--
ALTER TABLE `log_absensi_activity`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `log_aktivitas_user`
--
ALTER TABLE `log_aktivitas_user`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_log_aktivitas_user_time` (`user_id`,`waktu`);

--
-- Indexes for table `log_error_event`
--
ALTER TABLE `log_error_event`
  ADD PRIMARY KEY (`id_error`);

--
-- Indexes for table `log_panen_activity`
--
ALTER TABLE `log_panen_activity`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `log_system_event`
--
ALTER TABLE `log_system_event`
  ADD PRIMARY KEY (`id_event`);

--
-- Indexes for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_period` (`month`,`year`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rapot`
--
ALTER TABLE `rapot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rapot_evaluator_id_foreign` (`evaluator_id`),
  ADD KEY `rapot_id_user_index` (`id_user`),
  ADD KEY `rapot_periode_start_index` (`periode_start`),
  ADD KEY `rapot_periode_end_index` (`periode_end`),
  ADD KEY `rapot_status_index` (`status`),
  ADD KEY `rapot_tipe_index` (`tipe`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_no_hp_unique` (`no_hp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `archive_history`
--
ALTER TABLE `archive_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area_kerja`
--
ALTER TABLE `area_kerja`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `attendances_archive`
--
ALTER TABLE `attendances_archive`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `catatan_panen`
--
ALTER TABLE `catatan_panen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `log_absensi_activity`
--
ALTER TABLE `log_absensi_activity`
  MODIFY `id_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `log_aktivitas_user`
--
ALTER TABLE `log_aktivitas_user`
  MODIFY `id_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `log_error_event`
--
ALTER TABLE `log_error_event`
  MODIFY `id_error` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_panen_activity`
--
ALTER TABLE `log_panen_activity`
  MODIFY `id_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `log_system_event`
--
ALTER TABLE `log_system_event`
  MODIFY `id_event` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  MODIFY `id_transaksi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `monthly_reports`
--
ALTER TABLE `monthly_reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rapot`
--
ALTER TABLE `rapot`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_pengumuman_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `fk_attendance_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `catatan_panen`
--
ALTER TABLE `catatan_panen`
  ADD CONSTRAINT `fk_panen_area` FOREIGN KEY (`id_area_kerja`) REFERENCES `area_kerja` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_panen_user` FOREIGN KEY (`id_pegawai`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rapot`
--
ALTER TABLE `rapot`
  ADD CONSTRAINT `rapot_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rapot_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

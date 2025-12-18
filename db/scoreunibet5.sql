-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 18, 2025 alle 20:07
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scoreunibet`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `bets`
--

CREATE TABLE `bets` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_user_id` int(11) NOT NULL,
  `predicted_grade` enum('18','19','20','21','22','23','24','25','26','27','28','29','30','30L') NOT NULL,
  `amount` int(11) NOT NULL,
  `placed_at` datetime NOT NULL,
  `is_winner` tinyint(1) DEFAULT NULL,
  `payout` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `bets`
--

INSERT INTO `bets` (`id`, `group_id`, `user_id`, `target_user_id`, `predicted_grade`, `amount`, `placed_at`, `is_winner`, `payout`) VALUES
(1, 4, 5, 8, '18', 50, '2025-12-14 01:41:34', 1, 100.00),
(4, 4, 8, 8, '26', 50, '2025-12-14 21:23:10', 0, 0.00),
(5, 12, 5, 8, '23', 100, '2025-12-15 14:30:10', 1, 175.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `invite_token` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `colore` text NOT NULL,
  `closed_at` datetime DEFAULT NULL,
  `final_grade` enum('18','19','20','21','22','23','24','25','26','27','28','29','30','30L') DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`id`, `owner_id`, `name`, `invite_token`, `created_at`, `colore`, `closed_at`, `final_grade`, `status`) VALUES
(4, 5, 'gr', 'OpS9N7m0', '0000-00-00 00:00:00', '#1577c1', '2025-12-14 21:28:55', '18', 'closed'),
(8, 8, 'gr1', 'juqCtBYK', '0000-00-00 00:00:00', '#c11515', '2025-12-14 21:24:23', '24', 'closed'),
(9, 5, 'matematca1', 'ZnGnfOXh', '0000-00-00 00:00:00', '#b51212', '2025-12-15 14:27:46', '23', 'closed'),
(10, 5, 'matematca1', 'HZlsBJiP', '0000-00-00 00:00:00', '#b51212', '0000-00-00 00:00:00', '', 'open'),
(11, 5, 'ciao', 'rzfZZiwT', '0000-00-00 00:00:00', '#000000', '0000-00-00 00:00:00', '', 'open'),
(12, 8, 'arch1', 'me8dc652', '0000-00-00 00:00:00', '#22c95c', '2025-12-15 14:31:20', '24', 'closed');

-- --------------------------------------------------------

--
-- Struttura della tabella `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('member','admin') NOT NULL DEFAULT 'member',
  `joined_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `role`, `joined_at`) VALUES
(6, 4, 8, 'member', '0000-00-00 00:00:00'),
(7, 8, 5, 'member', '0000-00-00 00:00:00'),
(8, 10, 5, 'admin', '0000-00-00 00:00:00'),
(9, 11, 5, 'admin', '0000-00-00 00:00:00'),
(11, 12, 8, 'admin', '0000-00-00 00:00:00'),
(12, 9, 8, 'member', '0000-00-00 00:00:00'),
(13, 9, 9, 'member', '0000-00-00 00:00:00'),
(14, 12, 5, 'member', '0000-00-00 00:00:00'),
(15, 10, 8, 'member', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('credit','debit','bet_place','bet_win') NOT NULL,
  `amount` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `related_bet_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `description`, `created_at`, `related_bet_id`, `group_id`) VALUES
(1, 5, 'bet_place', 50, 'Scommessa piazzata sul gruppo/esame 4 (utente 8)', '2025-12-14 01:41:34', 1, 4),
(2, 5, 'bet_place', 50, 'Scommessa piazzata sul gruppo/esame 4 (utente 8)', '2025-12-14 01:47:34', 2, 4),
(3, 5, 'bet_place', 50, 'Scommessa piazzata sul gruppo/esame 4 (utente 8)', '2025-12-14 01:49:06', 3, 4),
(4, 8, 'bet_place', 50, 'Scommessa piazzata sul gruppo/esame 4 (utente 8)', '2025-12-14 21:23:10', 4, 4),
(5, 5, 'bet_win', 100, 'Scommessa vinta sul gruppo 4', '0000-00-00 00:00:00', 1, 4),
(6, 8, '', 0, 'Scommessa persa sul gruppo 4', '0000-00-00 00:00:00', 4, 4),
(7, 5, 'bet_place', 100, 'Scommessa piazzata sul gruppo/esame 12 (utente 8)', '2025-12-15 14:30:10', 5, 12),
(8, 5, 'bet_win', 175, 'Scommessa vinta sul gruppo 12', '0000-00-00 00:00:00', 5, 12);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `credits` decimal(12,2) NOT NULL DEFAULT 1000.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `type` int(1) DEFAULT NULL,
  `password_algo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `credits`, `created_at`, `type`, `password_algo`) VALUES
(5, 'admin', 'admin.io@gmail.com', '$2y$10$rVHUmsLdHLPl9BmEgCWdGeFAPXzfQR4UpqThkh85QU53qnJoTAdJ2', 9925.00, '2025-12-12 01:03:17', NULL, 'bcrypt'),
(8, 'terzo', 'terzo.terzo@terzo.com', '$2y$10$f/cyf1Y4b5UjCqSmqEDyOeReEZl8I63SvnYnxxwFF7v8cXR/HQwEG', 9950.00, '2025-12-12 01:26:31', NULL, NULL),
(9, 'secondo', 'secondo@secondo.com', '$2y$10$B6nGoLn890QDzEq4u3ZRHeabQbTic5GHptxLLnNxf7lzBKBsdykqC', 10000.00, '2025-12-12 01:55:55', NULL, NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `bets`
--
ALTER TABLE `bets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_bets_group` (`group_id`),
  ADD KEY `fk_bets_target` (`target_user_id`);

--
-- Indici per le tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invite_token` (`invite_token`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indici per le tabelle `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_membership` (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_transactions_group` (`group_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `bets`
--
ALTER TABLE `bets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `bets`
--
ALTER TABLE `bets`
  ADD CONSTRAINT `bets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bets_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bets_target` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

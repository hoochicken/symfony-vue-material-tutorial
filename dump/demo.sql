
--
-- Datenbank: `dungeondb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `demo`
--

CREATE TABLE `demo` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` smallint DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `demo`
--

INSERT INTO `demo` (`id`, `title`, `description`, `state`) VALUES
(1, 'Custom Title', 'Custom Description', 1),
(2, 'Another Custom Title', 'Another Custom Description', 0),
(3, 'Yet Another Custom Title', 'Yet Another Custom Description', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `demo`
--
ALTER TABLE `demo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `demo`
--
ALTER TABLE `demo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

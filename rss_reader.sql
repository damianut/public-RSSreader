-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 09 Mar 2020, 15:03
-- Wersja serwera: 8.0.19
-- Wersja PHP: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `rss_reader`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `arts_komputerswiat`
--

CREATE TABLE `arts_komputerswiat` (
  `id` int NOT NULL,
  `unique_id` text NOT NULL,
  `published` datetime NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `arts_komputerswiat`
--

INSERT INTO `arts_komputerswiat` (`id`, `unique_id`, `published`, `title`, `content`, `reg_date`) VALUES
(1, 'urn:uuid:f3246fd0-421b-4625-b602-ad972efc070e', '2020-03-09 13:25:27', 'Cyberpunk 2077 dostanie ekskluzywne figurki za niemal tysiąc złotych', 'Cyberpunk 2077 to nie tylko gra wideo. Już dzisiaj można bez obaw stwierdzić, iż produkty powiązane z tym tytułem znajdziemy także na innych obszarach przenikających się z rynkiem gier wideo. Produkcja RED-ów trafi na nasze półki chociażby za sprawą ekskluzywnych figurek z głównymi bohaterami gry.', '2020-03-09 14:27:36'),
(2, 'urn:uuid:13936f5c-cade-44b5-9054-89856c1ecc27', '2020-03-09 13:00:00', 'Disney+ w Polsce? Jak wypadałby na tle Netfliksa, HBO GO i Amazon Prime Video', 'Disney+ to kolejna usługa strumieniowania filmów, która już pojawiła się na niektórych rynkach. Najnowsze doniesienia wskazywały, że zostanie uruchomiona latem także w naszym kraju, jednak ta informacja szybko zniknęła z oficjalnej wiadomości prasowej. Sprawdźmy jednak, jak wyglądałaby oferta Disney+ w Polsce na tle innych usług VoD.', '2020-03-09 14:26:14'),
(3, 'urn:uuid:c93a8628-e364-53ae-94f0-5f2f1ccef547', '2020-03-09 12:30:00', 'Jaki Xiaomi wybrać? Mi czy Redmi? Wyjaśniamy różnice', 'Chiński koncern Xiaomi ma dwie marki smartfonów - topowe Mi oraz budżetowe Redmi. Wyjaśniamy różnice pomiędzy nimi i prezentujemy najważniejsze smartfony obu linii.', '2020-03-09 13:38:41'),
(4, 'urn:uuid:40dc782a-0285-467e-b133-9890f5e84172', '2020-03-09 12:13:48', 'Disney+ w Polsce już latem 2020 roku? Premiera usługi nadal niepewna', 'Po miesiącach oczekiwania Disney chyba niechcący ujawnił polską datę premiery swojej streamingowej usługi Disney+. Jeśli nikt w koncernie się nie pomylił, to polski debiut nastąpi już niedługo przynosząc ogromną bibliotekę filmów i seriali, z której początkowo najbardziej ucieszą się zapewne fani Marvela i Gwiezdnych wojen.', '2020-03-09 13:38:42'),
(5, 'urn:uuid:b58dffe3-a73a-4c74-9304-eaacfdeb7525', '2020-03-09 11:58:04', 'CEO Apple: koronawirus to bezprecedensowe wydarzenie. Firma zachęca do pracy zdalnej', 'Tim Cook, CEO Apple nazwał chińskiego koronawirusa bezprecedensowym wydarzeniem. Firma w związku z panującą epidemią postanowiła pozwolić swoim pracownikom na pracę zdalną. Dotyczy to zatrudnionych w kilku krajach, również tych europejskich. Wiemy, że SARS-CoV-2 może mieć spory wpływ na kwartalne przychody Apple.', '2020-03-09 13:38:42');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `arts_rmf24`
--

CREATE TABLE `arts_rmf24` (
  `id` int NOT NULL,
  `unique_id` text NOT NULL,
  `published` datetime NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `arts_rmf24`
--

INSERT INTO `arts_rmf24` (`id`, `unique_id`, `published`, `title`, `content`, `reg_date`) VALUES
(1, '4371428', '2020-03-09 13:12:55', 'Coraz dłuższa lista wydarzeń sportowych odwołanych z powodu koronawirusa', 'Zaczęło się od odwołania Halowych Mistrzostw Świata w lekkiej atletyce. Zawody w Chinach przełożona na 2021 rok. Od tego czasu lista odwołanych wydarzeń albo takich, które odbędą się bez udziału publiczności, cały czas się wydłuża.', '2020-03-09 14:08:21'),
(2, '4371375', '2020-03-09 12:10:46', 'Mecze Ligi Mistrzów bez publiczności. Decyzja zapadła', 'Z powodu epidemii koronawirusa, środowy (godz. 21) mecz 1/8 finału piłkarskiej Ligi Mistrzów Paris Saint Germain - Borussia Dortmund na stadionie Parc des Princes odbędzie się przy pustych trybunach - poinformowała w poniedziałek paryska policji.', '2020-03-09 14:08:21'),
(3, '4371327', '2020-03-09 11:42:36', 'Raw Air 2020. Onet: Na trybuny będą już mogli wejść kibice', 'Raw Air 2020 przenosi się do Lillehammer. Jak podaje Onet, dziś kibice zostaną wpuszczeni na trybuny, w przeciwieństwie do zawodów w Oslo. Według portalu, fani skoków mają też pojawić się podczas konkursów w Trondheim i Vikersund.', '2020-03-09 14:08:21'),
(4, '4371247', '2020-03-09 09:07:59', 'Ścisk w tabeli Ekstraklasy. Końcówka sezonu zasadniczego zapowiada się emocjonująco', 'Za nami 26 kolejek Ekstraklasy. Przynajmniej, jeśli chodzi o drużyny z czołówki tabeli. Dziś jeszcze mecz walczących o utrzymanie Korony Kielce i ŁKS-u. W ligowym czubie zrobiło się bardzo tłoczno. Trzecia w tabeli Cracovia ma sześć punktów zapasu nad dziesiątą Wisłą Płock, a to gwarantuje emocje w końcówce sezonu zasadniczego.', '2020-03-09 14:08:21'),
(5, '4371183', '2020-03-09 06:37:11', 'Turniej tenisowy w Indian Wells odwołany. Powodem koronawirus', 'Turniej PNB Paribas Open w kalifornijskim Indian Wells w tym roku się nie odbędzie. Organizatorzy odwołali imprezę tuż przed jej rozpoczęciem z powodu obaw o rozprzestrzenianie się koronawirusa w Kalifornii. Turniej rangi Masters 1000 miał się rozpocząć w poniedziałek meczami kwalifikacyjnymi.', '2020-03-09 14:08:21');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `arts_xmoon`
--

CREATE TABLE `arts_xmoon` (
  `id` int NOT NULL,
  `unique_id` text NOT NULL,
  `published` datetime NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `arts_xmoon`
--

INSERT INTO `arts_xmoon` (`id`, `unique_id`, `published`, `title`, `content`, `reg_date`) VALUES
(1, 'http://xmoon.pl/forma/Krotkie-treningi-ale-systematyczne,1,850,1.html', '2017-11-15 20:58:05', 'Krótkie treningi ale systematyczne', '&lt,b&gt,\nPoczątki zawsze są najgorsze i to nie tylko w ćwiczeniach. Najgorsze też jest to że efektów spodziewamy się bardzo szybko a ich wyraźny brak bardzo szybko może nas zniechęcić do działania.\n&lt,/b&gt, &lt,br/&gt,&lt,br/&gt,Może lepiej zacząć małymi krokami. Duży skok w wir ćwiczeń może nie być tak owocny jak byś chciał.&lt,br&gt,\nŁatwiej będzie jak zaczniesz ćwiczyć codziennie w krótkim czasie i nawet w domu. Pomyśl ...', '2020-03-09 14:07:57'),
(2, 'http://xmoon.pl/seks/Czego-oczekuje-kobieta-na-randce,1,968,1.html', '2017-11-15 20:57:37', 'Czego oczekuje kobieta na randce', '&lt,b&gt,My ciągle mamy problemy ze zrozumieniem kobiet i ciężko też nam czasem odgadnąć czego mogła by oczekiwać od nas albo kogo tak właściwie szuka. Gdybyśmy to wiedzieli dużo łatwiej moglibyśmy zaimponować jej albo przynajmniej czuć się swobodnie.&lt,/b&gt, &lt,br/&gt,&lt,br/&gt,Według statystyk kobiety nie są aż tak nieprzewidywalne, wystarczy że będziesz pewny siebie w granicach rozsądku, w miarę dobrze ubrany, bez udziwnień i zadbany, ale też i trochę męski – nie ...', '2020-03-09 14:07:57'),
(3, 'http://xmoon.pl/seks/Modelka-Mathilde-Gohler,1,148,1.html', '2017-11-13 13:56:59', 'Modelka Mathilde Gøhler', '&lt,b&gt,Pochodzaca z Danii Mathilde Gøhler można bardzo łatwo rozpoznać. Ma niecodzienna śliczna buzię, wspaniałe włosy,a ciało … &lt,br&gt,\nSami zobaczcie.&lt,/b&gt, &lt,br/&gt,&lt,br/&gt,', '2020-03-09 14:07:57'),
(4, 'http://xmoon.pl/extreme/Bolid-formuly-F1-kontra-F18,1,179,1.html', '2017-11-13 11:16:03', 'Bolid formuły F1 kontra F18', '&lt,b&gt,A co Wy na to aby zmierzyły się dwa potwory F1 i F18?&lt,/b&gt, &lt,br/&gt,&lt,br/&gt,Otóż w 2014 został nakręcony materiał w którym bolid Red Bull F1 zmierzył się z maszyną F18 Jet Royal Australian Air Force\n&lt,BR&gt,&lt,BR&gt,\nPieknie to wygląda&lt,BR&gt,&lt,BR&gt,&lt,BR&gt,\n&lt,iframe width=&560& height=&315& ...', '2020-03-09 14:07:57'),
(5, 'http://xmoon.pl/biznes/Mansa-Musa-najbogatszy-czlowiek-w-historii,1,669,1.html', '2017-11-12 08:55:22', 'Mansa Musa najbogatszy człowiek w historii', '&lt,b&gt,Mało kto domyśla się że najbogatszy człowiek w historii żył bardzo dawno temu i to nie w Europie czy Bliskim Wschodzie czy Chinach.&lt,/b&gt, &lt,br/&gt,&lt,br/&gt,Mansa Musa to król Mali, który rządził tamtym krajem w latach 1312-1337.\n&lt,BR&gt,&lt,BR&gt,\nJego majątek przeliczając na dzisiejsze pieniądze to niebagatelna kwota 400 miliardów dolarów. Żeby porównać ...', '2020-03-09 14:07:57');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ip_numbers`
--

CREATE TABLE `ip_numbers` (
  `internal_ip` int NOT NULL,
  `ip` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `refresh` int NOT NULL,
  `moment` datetime NOT NULL,
  `blocked` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Zrzut danych tabeli `ip_numbers`
--

INSERT INTO `ip_numbers` (`internal_ip`, `ip`, `refresh`, `moment`, `blocked`) VALUES
(9, '::1', 2, '2020-03-09 14:41:37', 'NO');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `arts_komputerswiat`
--
ALTER TABLE `arts_komputerswiat`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `arts_rmf24`
--
ALTER TABLE `arts_rmf24`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `arts_xmoon`
--
ALTER TABLE `arts_xmoon`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `ip_numbers`
--
ALTER TABLE `ip_numbers`
  ADD PRIMARY KEY (`internal_ip`);

--
-- AUTO_INCREMENT dla tabel zrzutów
--

--
-- AUTO_INCREMENT dla tabeli `arts_komputerswiat`
--
ALTER TABLE `arts_komputerswiat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `arts_rmf24`
--
ALTER TABLE `arts_rmf24`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `arts_xmoon`
--
ALTER TABLE `arts_xmoon`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `ip_numbers`
--
ALTER TABLE `ip_numbers`
  MODIFY `internal_ip` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

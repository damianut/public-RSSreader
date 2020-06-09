1) GŁÓWNY CEL CZYTNIKA: 

Wyświetlenie pięciu najnowszych artykułów z pewnych trzech stron internetowych.


2) INSTALACJA:

Wymagania:
-serwer HTTP
-przeglądarka
-serwer MySQL

2.1. Sklonować repozytorium do miejsca, z którego serwer będzie mógł uruchomić kod PHP.
W apache2 w systemie Ubuntu jest to domyślnie folder "/var/www/html"

2.2. Utworzyć bazę danych o nazwie `rss_reader`.

2.3. Utworzyć użytkownika z wszystkimi uprawnieniami do tej bazy.

2.4. Importować do niej plik 'rss_reader.sql' ze sklonowanego repozytorium.

2.5. W pliku "php/Configuration.php" edytować następujące linie:
$_ENV['DB_HOST'] = {adres hosta};
$_ENV['DB_USER'] = {nazwa użytkownika};
$_ENV['DB_PASS'] = {jego hasło};

Gdzie zamiast {adres hosta}, {nazwa użytkownika}, {jego hasło} należy wpisać odpowiednio
adres swojego hosta, nazwę użytkownika z wszystkimi uprawnieniami do bazy `rss_reader` oraz jego hasło.

2.6. Zmienić uprawnienia do pliku "accesslog/accesslog.txt" na odczytać i zapis dla wszystkich osób.

2.7. Jeśli serwer HTTP i serwer MySQL są uruchomione, można uruchomić stronę wchodząc pod adres:
'http://adres_hosta/RSSreader'
gdzie 'adres_hosta', to wartość zmiennej $_ENV['DB_HOST'].


3) OGÓLNA ZASADA DZIAŁANIA:

Użytkownik uruchamiając tę stronę uruchamia plik PHP, który pobiera pliki XML z trzech określonych stron internetowych.
Następnie sprawdza, czy pojawiły się artykuły na tych stronach nowsze, niż są w bazie danych mySQL. 
Jeśli tak, to baza danych jest uaktualniana.
Następnie na stronie wyświetlane są najnowszej artykuły zapisane w bazie danych.


4) SZCZEGÓŁOWA ZASADA DZIAŁANIA I DODATKOWE FUNKCJE:

Na początku działania skryptu pobierane są informacje, które zostaną użyte do stworzenia pliku 'accesslog.txt' z informacjami
o obecnym wykonaniu skryptu. Następnie sprawdzam, czy IP użytkownika, który uruchomił tę stronę, nie jest zapisane w bazie
danych na liście IP użytkowników, którzy 'floodowali' - ciągle odświeżali stronę, obciążając serwer. Jeśli nie jest na niej
to skrypt jest wykonywany dalej. Jednocześnie sprawdzam; czy aby ten użytkownik nie zacznie co chwila odświeżać strony. 
Jeśli odświeży określoną ilość razy, to jego IP zostanie zapisane w bazie danych i już dzięki temu IP nie będzie mógł
korzystać z tej strony.

Następnie wykonywany jest skrypt, który pobiera plik XML ze strony internetowej; następnie sprawdza; ile pojawiło się nowych
artykułów na stronie. Następnie usuwa z bazy danych tyle najstarszych artykułów, ile pojawiło się najnowszych (ale nie więcej
niż 5). Następnie dodaje nowe artykuły do bazy. Skrypt ten jest wykonywany dla 3 stron internetowych.

Potem artykuły z bazy danych są pobierane i wyświetlane na stronie.

Na końcu zapisuję dalsze informacje o tym konkretnym wykonaniu całego skryptu; i uaktualniam plik 'accesslog.txt'.

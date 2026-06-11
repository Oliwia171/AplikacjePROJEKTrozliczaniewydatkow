# Rozliczanie wydatkow w grupie

## Temat projektu

Projekt jest aplikacja webowa do rozliczania wspolnych wydatkow w grupach. System jest przeznaczony dla osob, ktore wspolnie placa za wyjazd, mieszkanie, impreze albo inny cel i chca szybko sprawdzic, kto ile zaplacil oraz kto komu powinien oddac pieniadze.

Glowny problem rozwiazywany przez aplikacje to chaos w rozliczeniach grupowych. Zamiast prowadzic notatki w komunikatorze albo arkuszu kalkulacyjnym, uzytkownik tworzy grupe, dodaje uczestnikow, zapisuje rachunki i widzi automatycznie wyliczone salda.

Aplikacja wyroznia sie tym, ze laczy proste formularze z relacyjnym modelem danych:

- rachunek zawsze nalezy do konkretnej grupy,
- platnik rachunku jest wybierany z listy czlonkow grupy, a nie wpisywany recznie jako identyfikator,
- pozycje z paragonu mozna przypisac do konkretnych osob,
- system automatycznie liczy podzial kosztow i saldo netto uzytkownika,
- administrator ma dodatkowy panel do zarzadzania uzytkownikami i rolami.

## Uruchomienie projektu - developer

### Technologie

| Obszar | Technologia | Wersja w projekcie | Link |
| --- | --- | --- | --- |
| Backend | PHP | minimum 8.2.0 (`composer.json`: `^8.2`) | https://www.php.net/ |
| Framework backendowy | Laravel | 12.59.0 (`composer.lock`) | https://laravel.com/ |
| Autoryzacja | Laravel Breeze | 2.4.1 (`composer.lock`) | https://laravel.com/docs/starter-kits |
| Frontend bundler | Vite | 7.3.3 (`package-lock.json`) | https://vite.dev/ |
| CSS | Tailwind CSS | 3.4.19 (`package-lock.json`) | https://tailwindcss.com/ |
| Plugin formularzy | @tailwindcss/forms | 0.5.11 (`package-lock.json`) | https://github.com/tailwindlabs/tailwindcss-forms |
| Interakcje UI | Alpine.js | 3.15.12 (`package-lock.json`) | https://alpinejs.dev/ |
| HTTP client JS | Axios | 1.16.1 (`package-lock.json`) | https://axios-http.com/ |
| Node.js | Node.js | 22.14.0 (srodowisko developerskie) | https://nodejs.org/ |
| Menedzer pakietow JS | npm | 10.9.7 (srodowisko developerskie) | https://www.npmjs.com/ |
| Baza danych lokalna | SQLite | 3.x, plik `database/database.sqlite` | https://www.sqlite.org/ |
| Baza danych opcjonalna | MySQL | 8.0.x dla triggerow i funkcji skladowanej | https://www.mysql.com/ |
| Testy | PHPUnit | 11.5.55 (`composer.lock`) | https://phpunit.de/ |

> Uwaga: obecne repozytorium domyslnie uzywa SQLite, bo jest najprostsze do uruchomienia lokalnie. Migracja z triggerami i funkcja skladowana wykonuje sie tylko na MySQL.

### Wymagania programowe

Na czystym komputerze potrzebne sa:

- system operacyjny: Windows 11, macOS albo Linux,
- PHP 8.2.0 lub nowszy z rozszerzeniami typowymi dla Laravel: `pdo`, `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`,
- Composer 2.x,
- Node.js 22.14.0 lub kompatybilny Node.js 22.x,
- npm 10.9.7 lub kompatybilny npm 10.x,
- SQLite 3.x albo MySQL 8.0.x,
- Git.

### Proces instalacji

1. Pobierz projekt:

```bash
git clone <adres-repozytorium>
cd <katalog-projektu>
```

2. Zainstaluj zaleznosci PHP:

```bash
composer install
```

3. Zainstaluj zaleznosci frontendu:

```bash
npm install
```

### Proces konfiguracji

1. Utworz plik srodowiskowy:

```bash
cp .env.example .env
```

Na Windows PowerShell:

```powershell
copy .env.example .env
```

2. Wygeneruj klucz aplikacji:

```bash
php artisan key:generate
```

3. Skonfiguruj baze danych.

Domyslna konfiguracja w `.env.example`:

```env
DB_CONNECTION=sqlite
```

Dla SQLite utworz pusty plik bazy:

```bash
mkdir -p database
touch database/database.sqlite
```

Na Windows PowerShell:

```powershell
New-Item database\database.sqlite -ItemType File -Force
```

Opcjonalna konfiguracja MySQL 8.0.x:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rozliczenia
DB_USERNAME=root
DB_PASSWORD=
```

4. Uruchom migracje i dane poczatkowe:

```bash
php artisan migrate:fresh --seed
```

Seeder tworzy przykladowa grupe, rachunek i konta testowe.

| Rola | E-mail | Haslo |
| --- | --- | --- |
| admin | `oliwia@example.com` | `password` |
| user | `adam@example.com` | `password` |
| user | `ewa@example.com` | `password` |
| user testowy spoza grupy | `hacker@example.com` | `password` |

5. Zbuduj zasoby frontendu:

```bash
npm run build
```

### Uruchomienie projektu w terminalu

Najprostszy wariant developerski wymaga dwoch terminali.

Terminal 1 - serwer Laravel:

```bash
php artisan serve
```

Aplikacja bedzie dostepna pod adresem:

```text
http://127.0.0.1:8000
```

Terminal 2 - tryb developerski Vite:

```bash
npm run dev
```

Alternatywnie mozna uzyc skryptu z `composer.json`, ktory uruchamia serwer, kolejke, logi i Vite:

```bash
composer run dev
```

### Najczestsze problemy uruchomieniowe

| Problem | Przyczyna | Rozwiazanie |
| --- | --- | --- |
| `php: command not found` | PHP nie jest dodany do PATH | Zainstaluj PHP 8.2+ i otworz nowy terminal |
| `composer: command not found` | Brak Composera | Zainstaluj Composer 2.x |
| Brak styli | Nie zbudowano zasobow Vite | Uruchom `npm install` i `npm run build` albo `npm run dev` |
| Blad SQLite | Brakuje pliku bazy | Utworz `database/database.sqlite` i uruchom migracje |
| Brak triggerow | Uruchomiono SQLite | Triggery i funkcja skladowana sa dostepne tylko na MySQL |

## Uruchomienie projektu - user

Projekt nie ma obecnie publicznego wdrozenia w sieci. Uzytkownik koncowy moze korzystac z aplikacji po uruchomieniu jej lokalnie przez developera pod adresem:

```text
http://127.0.0.1:8000
```

Wymagania sprzetowe dla plynnego dzialania:

- komputer z przegladarka Chrome, Firefox, Edge albo Safari,
- minimum 4 GB RAM dla uruchomienia aplikacji developerskiej,
- dostep do sieci lokalnej, jezeli aplikacja jest wystawiana innym uzytkownikom,
- ekran desktopowy lub mobilny; interfejs korzysta z responsywnych klas Tailwind (`sm`, `md`, `lg`) i posiada menu mobilne.

## Podrecznik uzytkownika

### Role w systemie

| Rola | Zakres dostepu |
| --- | --- |
| Gosc niezalogowany | Moze wejsc na strone glowna, przeczytac opis aplikacji i zobaczyc publiczne statystyki: liczbe grup, uzytkownikow i wydatkow. Nie moze otworzyc prywatnych grup, dodawac, edytowac ani usuwac danych. |
| Uzytkownik (`user`) | Moze rejestrowac konto, logowac sie, tworzyc wlasne grupy, przegladac grupy, do ktorych nalezy, dodawac czlonkow, rachunki i pozycje paragonu oraz zarzadzac swoim profilem. |
| Administrator (`admin`) | Ma uprawnienia zwyklego uzytkownika oraz dodatkowy panel administratora. Moze przegladac wszystkich uzytkownikow, filtrowac ich, zmieniac imiona i role oraz usuwac konta innych uzytkownikow. W widoku grup widzi wszystkie grupy. |

### Uprawnienia uzytkownikow

| Funkcja | Gosc | User | Admin |
| --- | --- | --- | --- |
| Strona glowna i statystyki | tak | tak | tak |
| Rejestracja i logowanie | tak | tak | tak |
| Lista prywatnych grup | nie | tylko swoje grupy | wszystkie grupy |
| Utworzenie grupy | nie | tak | tak |
| Edycja grupy | nie | wlasciciel grupy | tak |
| Usuniecie grupy | nie | wlasciciel grupy | tak |
| Dodanie czlonka do grupy | nie | czlonek grupy | tak |
| Dodanie rachunku | nie | czlonek grupy | tak |
| Usuniecie rachunku | nie | czlonek grupy | tak |
| Dodanie pozycji z paragonu | nie | czlonek grupy | tak |
| Panel administratora | nie | nie | tak |
| Zarzadzanie profilami uzytkownikow | nie | tylko wlasny profil | profile innych uzytkownikow w panelu admina |

### Flow 1: rejestracja i logowanie

1. Uzytkownik wchodzi na strone glowna.
2. Wybiera `Rejestracja`.
3. Podaje imie, e-mail i haslo.
4. Po utworzeniu konta loguje sie i widzi dashboard oraz link `Moje grupy`.

Walidacja:

- e-mail musi miec poprawny format,
- haslo musi spelniac reguly Laravel Breeze,
- powtorzone e-maile nie sa akceptowane.

### Flow 2: dodanie nowej grupy

1. Po zalogowaniu uzytkownik wybiera `Moje grupy`.
2. W formularzu `Dodaj nowa grupe` wpisuje nazwe i opcjonalny opis.
3. System automatycznie ustawia zalogowanego uzytkownika jako wlasciciela (`owner_id`).
4. Tworca jest automatycznie dopisywany do tabeli laczacej `group_user`.

Walidacja klienta:

- pole `Nazwa grupy` ma atrybut `required`,
- formularz pokazuje komunikaty walidacyjne pod polami,
- uklad jest responsywny: na mniejszych ekranach sekcje ukladaja sie jedna pod druga.

Walidacja serwera:

- `name`: wymagane, tekst, maksymalnie 255 znakow, unikalne w tabeli `groups`,
- `description`: opcjonalne, tekst, maksymalnie 1000 znakow.

Przypadki brzegowe:

- pusta nazwa grupy powoduje komunikat `Podaj nazwe grupy.`,
- powtorzona nazwa powoduje komunikat o duplikacie,
- zbyt dlugi opis jest odrzucany.

### Flow 3: filtrowanie i przegladanie grup

Widok `Moje grupy` pokazuje tabele grup z:

- nazwa i opisem,
- liczba osob,
- liczba wydatkow,
- wlascicielem grupy - tylko dla administratora,
- akcjami `Otworz`, `Edytuj`, `Usun`.

Filtrowanie:

- wszyscy zalogowani uzytkownicy moga szukac po nazwie lub opisie,
- administrator moze dodatkowo filtrowac po wlascicielu grupy, wpisujac imie albo e-mail.

Sortowanie:

- lista grup jest sortowana alfabetycznie po nazwie,
- historia rachunkow w grupie jest sortowana od najnowszych wpisow (`date DESC`, potem `id DESC`).

### Flow 4: dodanie czlonka do grupy

1. Uzytkownik otwiera szczegoly grupy.
2. W sekcji `Czlonkowie grupy` wpisuje e-mail osoby.
3. System sprawdza, czy istnieje konto z takim e-mailem.
4. Jezeli uzytkownik istnieje i nie jest jeszcze w grupie, zostaje dopisany do `group_user`.

Walidacja:

- pole e-mail ma typ `email` i `required`,
- serwer wymaga poprawnego e-maila istniejacego w tabeli `users`,
- system blokuje ponowne dodanie tej samej osoby.

### Flow 5: dodanie rachunku do grupy

Rachunek (`bills`) jest zasobem zaleznym od grupy (`groups`) oraz platnika (`users`). Jeden rekord grupy moze miec wiele rachunkow, a jeden uzytkownik moze byc platnikiem wielu rachunkow.

Relacja:

```text
groups 1 --- N bills
users  1 --- N bills jako payer
```

Kroki:

1. Uzytkownik otwiera szczegoly grupy.
2. W sekcji `Dodaj wydatek` podaje nazwe wydatku.
3. Wpisuje kwote.
4. Wybiera platnika z listy czlonkow grupy.
5. Po zapisaniu system tworzy rachunek i automatycznie dzieli koszt po rowno pomiedzy wszystkich czlonkow grupy.

Formularz jest dostosowany do relacji w bazie:

- platnik nie jest wpisywany recznie jako `payer_id`,
- platnik jest wybierany z listy `select`,
- lista zawiera tylko osoby nalezace do tej grupy,
- kwota ma typ `number`, krok `0.01` i minimum `0.01`.

Walidacja klienta:

- `description`: `required`,
- `amount`: `type="number"`, `step="0.01"`, `min="0.01"`, `required`,
- `payer_id`: `select`, `required`.

Walidacja serwera:

- `description`: wymagane, tekst, maksymalnie 255 znakow,
- `amount`: wymagane, liczba, minimum 0.01,
- `payer_id`: wymagane i musi istniec w `group_user` dla aktualnej grupy.

Przypadki brzegowe:

- wpisanie tekstu w pole kwoty jest blokowane przez pole typu `number` i ponownie sprawdzane przez serwer,
- wpisanie zera lub kwoty ujemnej powoduje blad,
- podstawienie w HTML identyfikatora osoby spoza grupy zostanie odrzucone przez regule `Rule::exists('group_user', 'user_id')->where('group_id', $group->id)`.

### Flow 6: historia rachunkow, filtry i podzial kosztow

W szczegolach grupy widoczna jest historia rozliczen. Kazdy rachunek pokazuje:

- opis,
- platnika,
- kwote,
- podzial kosztu na osoby,
- pozycje z paragonu,
- formularz dodania pozycji paragonu,
- przycisk usuniecia rachunku.

Filtry rachunkow:

- szukanie po opisie rachunku,
- wybor platnika z listy czlonkow grupy,
- kwota od,
- kwota do.

Sortowanie:

- domyslnie najnowsze rachunki sa na gorze,
- przy tej samej dacie nowszy rekord ma wyzszy priorytet dzieki sortowaniu po `id DESC`.

Paginacja:

- lista rachunkow jest stronicowana po 5 elementow,
- lista grup jest stronicowana po 8 elementow,
- panel administratora listuje uzytkownikow po 10 elementow.

### Flow 7: dodanie pozycji z paragonu

Pozycja paragonu (`bill_items`) jest zasobem zaleznym od rachunku (`bills`). Jedna pozycja moze byc przypisana do wielu uzytkownikow przez tabele posrednia `bill_item_user`.

Relacje:

```text
bills      1 --- N bill_items
bill_items N --- M users przez bill_item_user
```

Kroki:

1. W historii rozliczen uzytkownik rozwija `Dodaj pozycje z paragonu`.
2. Podaje nazwe pozycji, np. `Pizza`.
3. Podaje cene i liczbe sztuk.
4. Zaznacza osoby, ktorych dotyczy pozycja.
5. System zapisuje pozycje i powiazania z wybranymi uzytkownikami.

Walidacja klienta:

- nazwa jest wymagana,
- cena jest liczba z krokiem `0.01` i minimum `0.01`,
- liczba sztuk ma minimum `1`.

Walidacja serwera:

- `name`: wymagane, tekst, maksymalnie 255 znakow,
- `price`: wymagane, liczba, minimum 0.01,
- `quantity`: wymagane, liczba calkowita, minimum 1,
- `user_ids`: wymagana tablica z co najmniej jedna osoba,
- kazdy `user_ids.*` musi wskazywac uzytkownika nalezacego do tej grupy.

### Flow 8: edycja i usuwanie grupy

Edycja grupy:

1. Wlasciciel grupy albo administrator wybiera `Edytuj`.
2. Zmienia nazwe lub opis.
3. System sprawdza unikalnosc nazwy z pominieciem aktualnie edytowanej grupy.

Walidacja serwera:

- `name`: wymagane, tekst, maksymalnie 255 znakow, unikalne,
- `description`: opcjonalne, maksymalnie 1000 znakow.

Usuniecie grupy:

1. Wlasciciel albo administrator wybiera `Usun`.
2. Przegladarka pokazuje okno potwierdzenia.
3. Formularz wysyla metode `DELETE` z tokenem CSRF.
4. Laravel sprawdza uprawnienia i usuwa rekord.

Konsekwencje usuniecia:

- dzieki `onDelete('cascade')` usuniecie grupy usuwa powiazane rachunki i rekordy laczace,
- zwykly czlonek grupy, ktory nie jest wlascicielem, nie widzi akcji edycji i usuwania grupy.

### Flow 9: zarzadzanie uzytkownikami przez administratora

Panel administratora znajduje sie pod:

```text
/admin/users
```

Widoczny jest tylko dla uzytkownikow z rola `admin`.

Administrator widzi:

- liczbe uzytkownikow,
- liczbe administratorow,
- liczbe grup,
- liczbe wydatkow,
- wyszukiwarke uzytkownikow,
- filtr roli,
- tabele kont.

Mozliwe operacje:

- filtrowanie po imieniu lub e-mailu,
- filtrowanie po roli `user` albo `admin`,
- zmiana imienia uzytkownika,
- zmiana roli uzytkownika,
- usuniecie konta innego uzytkownika.

Walidacja serwera:

- `name`: wymagane, tekst, maksymalnie 255 znakow,
- `role`: wymagane, jedna z wartosci `user` lub `admin`,
- administrator nie moze odebrac roli admina samemu sobie,
- administrator nie moze usunac wlasnego konta.

### Flow 10: zarzadzanie wlasnym profilem

Kazdy zalogowany uzytkownik ma dostep do profilu z menu uzytkownika. Profil korzysta z formularzy Laravel Breeze:

- zmiana imienia i e-maila,
- zmiana hasla,
- usuniecie wlasnego konta po potwierdzeniu haslem.

Administrator dodatkowo zarzadza profilami innych osob w panelu `/admin/users`, gdzie moze zmienic imie i role oraz usunac konto.

## Udokumentowany CRUD zasobu zaleznego od innego zasobu

### Wybrany zasob: grupa rozliczeniowa (`groups`)

Grupa jest zaleznym zasobem uzytkownika, bo kazda grupa ma wlasciciela:

```text
users 1 --- N groups przez groups.owner_id
```

Administrator widzi wszystkie grupy na liscie `Moje grupy`, a zwykly uzytkownik tylko grupy, do ktorych nalezy.

| Operacja | Route | Kontroler | Dostep | Walidacja |
| --- | --- | --- | --- | --- |
| CREATE | `POST /groups` | `GroupController@store` | zalogowany user/admin | `name` wymagane i unikalne, `description` max 1000 |
| READ | `GET /groups`, `GET /groups/{group}` | `GroupController@index`, `show` | user: swoje grupy, admin: wszystkie | filtry `search`, `owner`; sortowanie po nazwie |
| UPDATE | `PUT /groups/{group}` | `GroupController@update` | wlasciciel albo admin | `name` wymagane i unikalne z ignorowaniem aktualnej grupy, `description` max 1000 |
| DELETE | `DELETE /groups/{group}` | `GroupController@destroy` | wlasciciel albo admin | CSRF, metoda `DELETE`, autoryzacja wlasciciela/admina, potwierdzenie w przegladarce |

### Dodatkowy zasob zalezy od grupy: rachunek (`bills`)

Rachunek jest praktycznym zasobem biznesowym zaleznym od grupy i platnika. W aktualnym interfejsie ma operacje tworzenia, odczytu i usuwania, a korekta blednego rachunku odbywa sie przez usuniecie i ponowne dodanie poprawnego wpisu.

| Operacja | Route | Kontroler | Uwagi |
| --- | --- | --- | --- |
| CREATE | `POST /groups/{group}/bills` | `BillController@store` | formularz z nazwa, kwota i wyborem platnika z listy czlonkow grupy |
| READ | `GET /groups/{group}` | `GroupController@show` | lista rachunkow z filtrami opisu, platnika i zakresu kwot |
| UPDATE | brak osobnego formularza w obecnej wersji | - | zaplanowane jako rozbudowa v2.0 |
| DELETE | `DELETE /groups/{group}/bills/{bill}` | `BillController@destroy` | usuwa rachunek i aktualizuje sume grupy na SQLite/PHP lub przez trigger na MySQL |

## Dostep uzytkownika niezalogowanego

Uzytkownik niezalogowany ma dostep tylko do publicznej strony glownej:

- widzi opis aplikacji,
- widzi ogolne liczniki grup, uzytkownikow i rachunkow,
- moze przejsc do logowania lub rejestracji.

Nie moze:

- otworzyc listy prywatnych grup,
- zobaczyc szczegolow prywatnych rozliczen,
- dodawac danych,
- edytowac danych,
- usuwac danych.

Takie ograniczenie jest celowe, bo dane o grupach i rachunkach sa prywatnymi informacjami finansowymi uzytkownikow.

## Dane przechowywane przez system

System przechowuje:

- uzytkownikow: imie, e-mail, zahashowane haslo, rola,
- grupy: nazwa, opis, wlasciciel, suma wydatkow,
- czlonkostwo w grupach: tabela `group_user`,
- rachunki: grupa, platnik, opis, kwota, data,
- pozycje rachunkow: nazwa, cena, liczba sztuk,
- przypisanie pozycji do uzytkownikow: tabela `bill_item_user`,
- podzial kosztow rachunku: tabela `bill_splits`,
- sesje, cache i zadania Laravel.

Hasla sa hashowane przez mechanizm Laravel. Aplikacja nie przechowuje hasel w formie jawnej.

## Dodatkowa nietrywialna logika biznesowa

### Automatyczny podzial rachunku

Po dodaniu rachunku system:

1. sprawdza, czy uzytkownik ma dostep do grupy,
2. sprawdza, czy platnik nalezy do grupy,
3. tworzy rekord w tabeli `bills`,
4. pobiera czlonkow grupy,
5. dzieli kwote rachunku po rowno,
6. tworzy wpis w `bill_splits` dla kazdego czlonka,
7. oznacza czesc platnika jako zaplacona,
8. aktualizuje `groups.total_amount`.

Wzor:

```text
udzial_osoby = kwota_rachunku / liczba_czlonkow_grupy
```

### Obliczanie salda netto

Saldo uzytkownika w grupie jest liczone wedlug wzoru:

```text
saldo = suma_zaplaconych_rachunkow - suma_naleznosci_z_bill_splits
```

Przyklad:

```text
Rachunek: 600 PLN
Czlonkowie: Oliwia, Adam, Ewa
Podzial: 200 PLN na osobe

Oliwia zaplacila: 600 - 200 = 400 PLN
Adam:             0 - 200 = -200 PLN
Ewa:              0 - 200 = -200 PLN
```

Interpretacja:

- saldo dodatnie oznacza, ze dana osoba zaplacila za duzo i inni powinni jej oddac,
- saldo ujemne oznacza, ze dana osoba powinna oddac pieniadze,
- saldo zero oznacza rozliczenie bez zaleglosci.

### Triggery i funkcja skladowana dla MySQL

Migracja `2026_05_14_163307_add_trigger_and_total_to_groups.php` dodaje logike bazodanowa tylko dla MySQL:

- `update_group_total_after_bill_insert` - zwieksza `groups.total_amount` po dodaniu rachunku,
- `update_group_total_after_bill_update` - przelicza `groups.total_amount` po zmianie kwoty rachunku,
- `update_group_total_after_bill_delete` - zmniejsza `groups.total_amount` po usunieciu rachunku,
- `validate_user_in_group_before_item_assign` - blokuje przypisanie pozycji paragonu osobie spoza grupy,
- `get_user_net_balance(user_id, group_id)` - funkcja skladowana liczaca saldo netto uzytkownika.

Na SQLite aplikacja wykonuje czesc tej logiki w PHP, poniewaz migracja z triggerami jest pomijana.

## Funkcjonalnosci dla uzytkownikow koncowych wykraczajace poza proste CRUD

- panel rozliczen pokazujacy aktualne salda czlonkow grupy,
- automatyczne oznaczenie platnika jako osoby, ktora zaplacila swoj udzial,
- filtrowanie historii rachunkow wedlug scenariuszy praktycznych: opis, platnik, minimalna i maksymalna kwota,
- przypisywanie pozycji z paragonu do konkretnych osob,
- izolacja danych: zwykly uzytkownik widzi tylko swoje grupy,
- responsywne menu mobilne,
- tryb jasny/ciemny przez komponent `theme-toggle`,
- panel statystyk administratora.

## Responsywnosc

Interfejs korzysta z klas Tailwind CSS:

- `grid grid-cols-1` dla ukladu mobilnego,
- `md:grid-cols-*` i `lg:grid-cols-*` dla szerszych ekranow,
- `overflow-x-auto` przy tabelach,
- menu mobilne w `resources/views/layouts/navigation.blade.php`,
- przyciski i formularze ukladaja sie pionowo na malych ekranach.

Proponowane zrzuty ekranowe do dokumentacji oddawanej jako PDF:

1. Strona glowna - opis aplikacji, przyciski logowania/rejestracji i publiczne statystyki.
2. Lista `Moje grupy` - formularz tworzenia grupy, filtry i tabela.
3. Szczegoly grupy - czlonkowie, panel rozliczen, formularz dodania rachunku.
4. Historia rozliczen - filtry rachunkow, podzial kosztow i pozycje paragonu.
5. Panel administratora - statystyki, filtrowanie uzytkownikow i edycja roli.
6. Widok mobilny - rozwiniete menu i formularz ustawiony w jednej kolumnie.

Kazdy zrzut powinien miec podpis wyjasniajacy, co znajduje sie na ekranie i jaka funkcje pokazuje.

## Plany rozbudowy

W pierwszej wersji zabraklo:

- osobnego formularza edycji rachunku,
- osobnego formularza edycji i usuwania pozycji paragonu,
- zaproszen do grup przez link,
- powiadomien e-mail po dodaniu do grupy,
- finalnego publicznego wdrozenia aplikacji.

Mozliwe funkcjonalnosci v2.0:

- edycja rachunku z automatycznym przeliczeniem `bill_splits`,
- oznaczanie splat pomiedzy uzytkownikami,
- generowanie raportu PDF z rozliczeniem grupy,
- eksport danych do CSV,
- zaproszenia e-mail do grup,
- powiadomienia o nowych wydatkach,
- integracja z platnosciami online,
- bardziej zaawansowane sortowanie listy rachunkow z poziomu UI,
- dashboard z wykresami wydatkow wedlug osob i kategorii.

Potencjal optymalizacji:

- cache'owanie statystyk na stronie glownej i w panelu admina,
- indeksy na kolumnach filtrowanych: `groups.name`, `groups.owner_id`, `bills.group_id`, `bills.payer_id`, `bills.amount`,
- przeniesienie produkcyjnej bazy z SQLite na MySQL 8.0 albo PostgreSQL,
- kolejki do wysylki powiadomien,
- testy funkcjonalne dla krytycznych flow: dodanie rachunku, saldo, panel admina.

# Jak uruchomic projekt

Pelna dokumentacja projektu, podrecznik uzytkownika, role, uprawnienia, CRUD i plany rozbudowy znajduja sie w pliku:

```text
README.md
```

## Szybkie uruchomienie lokalne

Wymagania:

- PHP 8.2.0+,
- Composer 2.x,
- Node.js 22.x,
- npm 10.x,
- SQLite 3.x albo MySQL 8.0.x.

Domyslnie projekt uzywa SQLite w pliku:

```text
database/database.sqlite
```

Pierwsze uruchomienie:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
mkdir -p database
touch database/database.sqlite
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Windows PowerShell:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
New-Item database\database.sqlite -ItemType File -Force
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Aplikacja bedzie dostepna pod adresem:

```text
http://127.0.0.1:8000
```

## Tryb developerski

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Alternatywnie:

```bash
composer run dev
```

## Konta testowe po seedzie

| Rola | Email | Haslo |
| --- | --- | --- |
| admin | `oliwia@example.com` | `password` |
| user | `adam@example.com` | `password` |
| user | `ewa@example.com` | `password` |
| user testowy spoza grupy | `hacker@example.com` | `password` |

## Szybki test

1. Zaloguj sie jako `oliwia@example.com` / `password`.
2. Wejdz w `Moje grupy`.
3. Otworz grupe `Wycieczka w gory 2026`.
4. Sprawdz panel rozliczen, historie rachunkow i podzial kosztow.
5. Dodaj nowy wydatek oraz pozycje z paragonu.
6. Wejdz w `Panel admina`, aby sprawdzic zarzadzanie uzytkownikami i rolami.

## Uwaga o bazie danych

Na SQLite aplikacja dziala normalnie, a suma grupy i salda sa liczone w PHP.

Triggery i funkcja skladowana z wymagan na wyzsza ocene dzialaja po przelaczeniu projektu na MySQL 8.0.x.

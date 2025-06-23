# 🔁 SyncBridge

**SyncBridge** je bridge aplikacija za sinhronizaciju podataka između lokalnog ERP sistema (Firebird) i udaljenih Laravel API-ja. Ova aplikacija koristi modularne `SyncTask` profile za periodično slanje, ažuriranje i logovanje podataka iz ERP baze prema eksternim servisima.

---

## 🚀 Funkcionalnosti

-   ✅ Dinamička konekcija na različite tipove servera baza podataka Firebird, MSSQL, MySQL
-   ✅ Višestruki `SyncTask` profili (definisani u bazi)
-   ✅ Logovanje svakog izvršenja u `sync_batches` i `sync_task_executions`
-   ✅ Zakazivanje izvršenja kroz Laravel scheduler ili CRONE

---

## 🗂️ Struktura projekta

app/
├── Console/Commands/ # Artisan komande za pokretanje sync taskova
├── Models/ # Modeli za sync profile, logove i izvršenja
├── Services/ # Logika obrade zadataka i sync bridge logika
├── Helpers/ # Dinamička konekcija, parsiranje stringova
├── Jobs/ # Queue-based izvršenje sync taskova (opciono)
config/
|- sync.php / # profile_name - default naziv profila
database/
├── migrations/
routes/
├── console.php # Registracija komandi za scheduler

---

## ⚙️ Konfiguracija

### .env

```env
APP_NAME=SyncBridge
APP_ENV=local
APP_KEY=base64:...

# Naziv default profila za za sinhronizaciju
SYNC_PROFILE_NAME='naziv_profila'

LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=syncbridge
DB_USERNAME=syncbridge_user
DB_PASSWORD=secret

# Queue & Schedule (opciono)
QUEUE_CONNECTION=database
```

### Putanja od dmxsync baze

```
# Sync Database Connection
DB_HOST_DMXSYNC=x.x.x.x|server.name.com
DB_DATABASE_DMXSYNC=dmxsync
DB_USERNAME_DMXSYNC=admin
DB_PASSWORD_DMXSYNC=xxxxxxxx
```

🛠️ Ručno pokretanje

Izvršavanje jednog taska

`php artisan   dmx:sync-task 233`

-   223 je id taska

Izvršavanje profila

`php artisan dmx:sync-profile naziv_profila` ako nije zatat naziv_profila pokusace da izvrsi onaj koji je podesen kao defaul u .env ili config

`php artisan dmx:sync-profile profil_prvi`

Brisanje sync2 tabela
`dmx:delete-sync2-tables --days=5`

## 📦 Deployment

-   Laravel Forge preporučen
-   PHP 8.4

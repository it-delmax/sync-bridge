# 🔁 SyncBridge

**SyncBridge** je bridge aplikacija za sinhronizaciju podataka između lokalnog ERP sistema (Firebird) i udaljenih Laravel API-ja. Ova aplikacija koristi modularne `SyncTask` profile za periodično slanje, ažuriranje i logovanje podataka iz ERP baze prema eksternim servisima.

---

## 🚀 Funkcionalnosti

-   ✅ Dinamička konekcija na više Firebird, MSSQL, MySQL baza
-   ✅ Višestruki `SyncTask` profili (definisani u bazi)
-   ✅ Logovanje svakog izvršenja u `sync_task_executions`
-   ✅ Transakcijski upiti sa rollback-om u slučaju greške
-   ✅ Automatska obrada novih/redovno promenjenih slogova
-   ✅ Podrška za SSO autentifikaciju prema udaljenim servisima

---

## 🗂️ Struktura projekta

app/
├── Console/Commands/ # Artisan komande za pokretanje sync taskova
├── Models/ # Modeli za sync profile, logove i izvršenja
├── Services/ # Logika obrade zadataka i sync bridge logika
├── Helpers/ # Dinamička konekcija, parsiranje stringova
├── Jobs/ # Queue-based izvršenje sync taskova (opciono)
database/
├── migrations/
routes/
├── console.php # Registracija komandi

---

## ⚙️ Konfiguracija

### .env

```env
APP_NAME=SyncBridge
APP_ENV=local
APP_KEY=base64:...

LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=syncbridge
DB_USERNAME=syncbridge_user
DB_PASSWORD=secret

# Queue & Schedule (opciono)
QUEUE_CONNECTION=database
```

### Podesavanje u dmxsync bazi

```
server=10.101.2.205;
database=ETG;
user_name=abcde;
password=secret;
CharacterSet=utf8;
DriverID=MySQL
```

🛠️ Pokretanje sync taska ručno

```
php artisan sync:run-task {task_id}
```

## 📝 Primer task definicije u bazi

Tabela: sync_tasks

| Polje       | Opis                                      |
| ----------- | ----------------------------------------- |
| id          | Primarni ključ                            |
| resource_id | Referenca na konekciju                    |
| table_name  | Naziv tabele u ERP                        |
| handler     | Naziv PHP klase (npr. `StockSyncHandler`) |
| is_active   | Aktivan task                              |
| schedule    | Cron izraz (ako koristi scheduler)        |

## 📦 Deployment

Laravel Forge preporučen

Cron job za php artisan schedule:run (ako koristiš zakazane taskove)

Queue worker (ako koristiš dispatch() sync jobove)

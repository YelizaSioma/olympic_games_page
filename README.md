***

## Project: Slovak Olympic Medals Web Application

### Stack
**LEMP** (Linux, Nginx, PHP-FPM, MariaDB) running inside **Docker** (docker-compose). Local dev environment on `localhost:8080`. Frontend uses **Bootstrap 5** and **DataTables 1.13** with jQuery.
***
**libraries:**
- bootstrap
- datatables
- jquery

**fonts-used:**
>     <link rel="preconnect" href="https://fonts.googleapis.com">
>     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

***

### Composer packages installation
```
composer require robthree/twofactorauth
composer require bacon/bacon-qr-code ^2
composer require google/apiclient
```
***
### Config changes in prod. version:
```
$hostname = "";
$database = "";
$username = "";
$password = "";
```

Inside /etc/nginx/sites-available/

```
...
location ~* /vendor/ {
    deny all;
    return 403;
}
...
```

Inside login.php and oauth2callback.php change link to an existing in your google cloud console
```
$redirect_uri = "https://extisting.link"
```
***

### Database Schema (`app_db`)
![alt text](image2.png)

With the following relationships:

```
countries        ← referenced by athletes (birth_country_id/death_country_id) and olympic_games(country_id)
disciplines      ← referenced by athlete_medals (discipline_id)
medal_types      ← referenced by athlete_medals (medal_type_id)
olympic_games    ← referenced by athlete_medals (olympic_games_id)
athletes         ← referenced by athlete_medals (athlete_id)
user_accounts    ← referenced by login_history (user_id)
```

***

### Config

#### `config.php`
- Defines `$hostname`, `$database`, `$username`, `$password` variables (different in Docker and Server env.)
- Contains `connectDatabase(PDO)` function that returns a PDO object
- Check for errors during PDO mapping

***

#### `index.php`
**CSV format accepted:** Single merged CSV with **16 columns**:
```
placing, discipline, name, surname, birth_day, birth_place, birth_country,
death_day, death_place, death_country, oh_type, oh_year, oh_city,
oh_country, oh_order, oh_code
```
With delimiter: `,`.

***
### API's
**Type:** JSON endpoint (produces only JSON, no HTML)
#### `data_general.php`

- Returns:
```
echo json_encode(['data' => $rows]);
```

***
#### `data_athlete.php`

- Returns:
```json
echo json_encode([
    'athlete' => [$athlete],
    'medals'  => $medals
]);
```
- `athlete` is wrapped in `[ ]` array (single-element) because DataTables requires an array as data source
***

***
#### `login_history.php`

- Returns:
```json
echo json_encode(['data' => $rows]);
```
***

***
#### Not implemented
1) `forgot.password.php` - password reset. But can be changed if user is logged in.
2) 2FA QR code regeneration in case of user lost his app account.
3) Reset imported CSV file option.
***
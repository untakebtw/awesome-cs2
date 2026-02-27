# Awesome

> Веб-интерфейс для плагина [WeaponPaints](https://github.com/Nereziel/cs2-WeaponPaints) — смена скинов, ножей, перчаток, агентов и музыки прямо через браузер.
>
> Web interface for the [WeaponPaints](https://github.com/Nereziel/cs2-WeaponPaints) plugin — change skins, knives, gloves, agents and music kits directly from the browser.

---

## Русский

### Требования

| Компонент | Версия |
|-----------|--------|
| PHP | **7.4** или новее (рекомендуется 8.1+) |
| MySQL / MariaDB | 5.7+ / 10.3+ |
| Расширения PHP | `pdo`, `pdo_mysql`, `curl`, `json`, `session` |
| Веб-сервер | Apache (с `mod_rewrite`) или Nginx |

### Зависимости плагина

> [!IMPORTANT]
> Сайт работает **только** с плагином **[cs2-WeaponPaints](https://github.com/Nereziel/cs2-WeaponPaints)**.
> Он создаёт в базе данных таблицы: `wp_player_skins`, `wp_player_knife`, `wp_player_gloves`, `wp_player_agents`, `wp_player_music`.
> Без этих таблиц сайт не запустится.

### Интеграция с LevelRanks

Сайт поддерживает интеграцию с плагином **[LevelRanks от PiSEX](https://github.com/levelsranks)**.  
При подключении на главной странице отображается статистика игроков (сегодня / неделя / месяц / всего).  
Интеграция настраивается через Админ-панель → **База данных → LevelRanks**.  
По умолчанию таблица: `lvl_base`.

### Установка

1. **Скачай** репозиторий и загрузи все файлы папки `php/` на сервер в папку сайта (например `/var/www/html/`).

2. **Настрой веб-сервер:**

   **Apache** — убедись что `mod_rewrite` включён. Файл `.htaccess` уже есть в репозитории.

   **Nginx** — добавь в конфиг блока `server`:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   location ~ \.php$ {
       fastcgi_pass unix:/run/php/php8.1-fpm.sock;
       fastcgi_param SCRIPT_FILENAME $document_root/index.php;
       include fastcgi_params;
   }
   ```

3. **Права на запись** — папка сайта должна быть доступна для записи PHP (нужно для `config.json`):
   ```bash
   chown -R www-data:www-data /var/www/html/
   chmod 755 /var/www/html/
   ```

4. **Открой браузер** и перейди на адрес сайта — тебя автоматически перенаправит на мастер установки `/install`.

5. **Пройди установку:**
   - Введи название сайта и свой SteamID64 (администратор)
   - Введи Steam API ключ ([получить здесь](https://steamcommunity.com/dev/apikey))
   - Подключи базу данных WeaponPaints (та же БД что использует плагин на сервере CS2)
   - Опционально: подключи LevelRanks

6. **Готово!** Войди через Steam и управляй скинами.

### Настройка Steam API

Без Steam API ключа авторизация работать **не будет**.

1. Перейди на [steamcommunity.com/dev/apikey](https://steamcommunity.com/dev/apikey)
2. В поле "Domain" укажи домен сайта (например `mysite.com`)
3. Скопируй ключ и вставь его в установщик

### Структура проекта

```
php/
├── index.php          # Главный роутер
├── config.json        # Конфиг (создаётся при установке)
├── .htaccess          # Правила Apache rewrite
├── src/
│   ├── api.php        # REST API для скинов
│   ├── auth.php       # Steam авторизация (OpenID)
│   ├── db.php         # Подключение к БД
│   ├── helpers.php    # Вспомогательные функции
│   ├── install.php    # Мастер установки
│   └── lang/          # Языковые файлы (en, ru, pt-BR, zh-CN)
├── views/             # PHP-шаблоны страниц
└── public/            # Статика (CSS, JS, изображения)
```

### Поддерживаемые языки

`en` · `ru` · `pt-BR` · `zh-CN`

Язык выбирается автоматически из конфига или через URL-префикс: `/ru/skins`, `/en/skins`



---

## English

### Requirements

| Component | Version |
|-----------|---------|
| PHP | **7.4** or newer (8.1+ recommended) |
| MySQL / MariaDB | 5.7+ / 10.3+ |
| PHP Extensions | `pdo`, `pdo_mysql`, `curl`, `json`, `session` |
| Web server | Apache (with `mod_rewrite`) or Nginx |

### Plugin Dependency

> [!IMPORTANT]
> This website works **only** with the **[cs2-WeaponPaints](https://github.com/Nereziel/cs2-WeaponPaints)** plugin.
> The plugin creates the following database tables: `wp_player_skins`, `wp_player_knife`, `wp_player_gloves`, `wp_player_agents`, `wp_player_music`.
> The site will not work without these tables.

### LevelRanks Integration

The site supports integration with **[LevelRanks by PiSEX](https://github.com/levelsranks)**.  
When connected, the home page displays player statistics (today / week / month / total).  
Configure it in the Admin panel → **Database → LevelRanks**.  
Default table name: `lvl_base`.

### Installation

1. **Download** the repository and upload all files from the `php/` folder to your web server directory (e.g. `/var/www/html/`).

2. **Configure your web server:**

   **Apache** — make sure `mod_rewrite` is enabled. The `.htaccess` file is already included.

   **Nginx** — add the following to your `server` block:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   location ~ \.php$ {
       fastcgi_pass unix:/run/php/php8.1-fpm.sock;
       fastcgi_param SCRIPT_FILENAME $document_root/index.php;
       include fastcgi_params;
   }
   ```

3. **File permissions** — the site directory must be writable by PHP (needed for `config.json`):
   ```bash
   chown -R www-data:www-data /var/www/html/
   chmod 755 /var/www/html/
   ```

4. **Open your browser** and go to your site's URL — you'll be automatically redirected to the install wizard at `/install`.

5. **Complete the setup:**
   - Enter your site name and your SteamID64 (admin account)
   - Enter your Steam API key ([get it here](https://steamcommunity.com/dev/apikey))
   - Connect the WeaponPaints database (same DB used by the CS2 server plugin)
   - Optionally: connect LevelRanks

6. **Done!** Log in via Steam and manage your skins.

### Steam API Setup

Steam API key is **required** for login to work.

1. Go to [steamcommunity.com/dev/apikey](https://steamcommunity.com/dev/apikey)
2. Set the "Domain" field to your site domain (e.g. `mysite.com`)
3. Copy the key and paste it into the installer

### Project Structure

```
php/
├── index.php          # Main router
├── config.json        # Config file (created during install)
├── .htaccess          # Apache rewrite rules
├── src/
│   ├── api.php        # REST API for skins
│   ├── auth.php       # Steam authentication (OpenID)
│   ├── db.php         # Database connection
│   ├── helpers.php    # Utility functions
│   ├── install.php    # Setup wizard
│   └── lang/          # Language files (en, ru, pt-BR, zh-CN)
├── views/             # PHP page templates
└── public/            # Static assets (CSS, JS, images)
```

### Supported Languages

`en` · `ru` · `pt-BR` · `zh-CN`

Language is set from config or via URL prefix: `/ru/skins`, `/en/skins`

---

## License

MIT

ІС «МАГАЗИН ЕЛЕКТРОНІКИ»

В системі зберігається така інформація як:

- Клієнти (customer)
  - CustomerID — унікальний ідентифікатор клієнта
  - FirstName — ім'я
  - LastName — прізвище
  - City — місто
  - Street — вулиця / адреса доставки
  - ZipCode — поштовий індекс

- Працівники / продавці (employees)
  - EmployeeID — унікальний ідентифікатор працівника
  - FirstName — ім'я
  - LastName — прізвище
  - City — місто
  - Street — вулиця
  - ZipCode — поштовий індекс

- Товари / продукти (items)
  - ProductID — унікальний ідентифікатор товару
  - Name — назва товару (опис)
  - Price — ціна (decimal)
  - Quantity — кількість на складі
  - Guarantee — гарантійний строк (в місяцях)

- Замовлення (orders)
  - OrderID — унікальний ідентифікатор замовлення
  - CustomerID — посилання на покупця (логічна зв'язка з таблицею customer)
  - EmployeeID — посилання на працівника, що оформив замовлення (логічна зв'язка з employees)
  - ShipCity — місто доставки
  - ShipStreet — вулиця доставки
  - ShipZip — поштовий індекс доставки
  - ShipDate — дата відправлення / доставки

- Позиції в замовленні (orderitems)
  - ID — унікальний ідентифікатор позиції в замовленні
  - OrderID — посилання на замовлення
  - ProductID — посилання на товар
  - Quantity — кількість цього товару у замовленні
  - SoldPrice — фактична ціна продажу для цієї позиції (може відрізнятись від поточної ціни в items)

Коротко про зв'язки:
- Кожне замовлення (orders) пов'язане з клієнтом та продавцем (CustomerID, EmployeeID).
- Таблиця orderitems реалізує зв'язок "замовлення — позиції", де для одного OrderID може бути кілька рядків з різними ProductID.
- Таблиця items містить інформацію про асортимент, кількість на складі та гарантію.

Приклади SQL-скриптів для створення таблиць і наповнення тестовими даними знаходяться у файлах:
- [01-schema.sql](https://github.com/isaistvo/db-lab/blob/main/01-schema.sql) — схема БД (структури таблиць)
- [02-dummy-data.sql](https://github.com/isaistvo/db-lab/blob/main/02-dummy-data.sql) — демонстраційні записи

# Панель керування (DB Lab)

Легка панель керування для демонстраційної БД — CRUD для клієнтів, працівників, товарів та замовлень.

## Ключові можливості
- CRUD для Customers, Employees, Items, Orders
- Простий роутинг через `public/index.php` (параметр `r`)
- Легкий шаблон для відображення у `views`
- Логування подій і помилок у файли логів

## Вимоги
- XAMPP з PHP >= 8.0 (проект використовує `mixed` у сигнатурах та nullable-типи)
- MySQL / MariaDB (йде в комплекті з XAMPP)
- Composer (для автозавантаження та залежностей)
- Розширення PHP: PDO (ext-pdo)

## Використані бібліотеки
- monolog/monolog — для логування (клас `Src\Core\Logger` використовує Monolog)
- ext-pdo — для підключення до MySQL через PDO

> Зауваження: у репозиторії є `composer.json` з автозавантаженням (PSR-4). Якщо `monolog/monolog` не встановлено у `composer.json` локально, встановіть його командою нижче.

## Швидка інструкція встановлення для XAMPP (Windows / macOS / Linux)
1. Встановіть XAMPP і запустіть Apache та MySQL.
2. Скопіюйте проект у папку `htdocs` або налаштуйте virtual host, який вказує на папку `project_root/public`.
   - Варіант A: помістіть весь репозиторій в `C:\xampp\htdocs\db-lab` (або відповідну папку в Linux/macOS).
   - Варіант B: налаштуйте VirtualHost, що вказує на `.../db-lab/public` як DocumentRoot (щоб URL був `http://db-lab.local/`).
3. Відкрийте термінал і перейдіть у корінь проекту:
   ```
   cd /шлях/до/db-lab
   ```
4. Встановіть залежності Composer:
   ```
   composer install
   ```
   Якщо Monolog не додано в `composer.json`, встановіть його:
   ```
   composer require monolog/monolog
   ```
5. Створіть файл `.env` в корені проекту, скопіювавши `.env.example`:
   - В корені проекту:
     - `.env.example`:
       ```
       DB_HOST=127.0.0.1
       DB_NAME=demo
       DB_USER=
       DB_PASS=
       DB_CHARSET=utf8mb4
       ```
   - Змініть значення на свої: наприклад `DB_USER=root`, `DB_PASS=` (за замовчуванням у XAMPP).
6. Створіть базу даних і імпортуйте схему та демонстраційні дані:
   - Через phpMyAdmin:
     - Відкрийте `http://localhost/phpmyadmin`
     - Створіть базу `demo` (або інше ім'я згідно з `.env`)
     - Виконайте імпорт файлів `01-schema.sql` (створює таблиці) і `02-dummy-data.sql` (додає тестові записи)
   - Або через CLI:
     ```
     mysql -u root -p demo < 01-schema.sql
     mysql -u root -p demo < 02-dummy-data.sql
     ```
7. Надайте права на запис для директорії `logs` (щоб Monolog міг писати лог-файли):
   - На Windows: переконайтесь, що Apache має доступ до папки.
   - На Linux/macOS:
     ```
     mkdir -p logs
     chmod 0777 logs
     ```
8. Запуск додатка:
   - Якщо ви розмістили проект у `htdocs/db-lab`, відкрийте:
     ```
     http://localhost/db-lab/public/
     ```
   - Якщо налаштовано VirtualHost, відкрийте ваш хост (напр., `http://db-lab.local/`).

## Налаштування маршрутизації та точки входу
- Точка входу: `public/index.php`
- Параметр маршруту: `r` (напр., `?r=customer/index`, `?r=order/create`)
- За замовчуванням рендериться `home` (панель керування)

## Структура основних директорій
- public/ — точка входу (index.php), статичні ресурси (css, js)
- src/ — PHP-код
  - Core/ — конфіг, база даних, view, логер
  - Handlers/ — обробники запитів (Controller-подібні класи)
  - Models/, Repositories/, Services/ — доменна логіка
- views/ — шаблони сторінок
- logs/ — файли логів (створити/дати права)
- 01-schema.sql, 02-dummy-data.sql — скрипти БД

## Поради з налагодження
- Якщо бачите помилку підключення до БД — перевірте `.env` і що MySQL сервер запущений.
- Якщо немає автозавантаження класів — виконайте `composer dump-autoload` або `composer install`.
- Для детальнішого логування перевірте файли в `logs/app.log` і `logs/error.log`.

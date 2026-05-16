# Domain Monitoring System (DMS)

Веб-приложение для мониторинга доступности доменов. Система периодически отправляет HTTP-запросы к добавленным доменам, фиксирует статус, время ответа и коды ответа, сохраняет историю проверок.

## Стек технологий

**Backend**
- PHP 8.4 / Laravel 13
- PostgreSQL 16
- Redis 7 (очередь задач)
- Laravel Sanctum (аутентификация)
- Laravel Telescope (дебаггинг)
- Spatie Laravel Data (DTO)

**Frontend**
- Vue 3 + TypeScript
- Inertia.js 2 (SPA без отдельного API)
- Tailwind CSS 4
- Vite 8
- Ziggy + Wayfinder (типизированные роуты)

**Инфраструктура**
- Docker (Nginx, PHP-FPM, PostgreSQL, Redis, Queue Worker, Scheduler)
- Laravel Pint (линтер PHP)
- ESLint + Prettier (линтер/форматтер JS/TS)

## Архитектура

Проект построен по принципам **Domain-Driven Design (DDD)** и разделён на 4 слоя:

```
src/
├── Application/       # Use Cases (Actions) — оркестрация бизнес-логики
├── Domain/            # Бизнес-логика, модели, сервисы, DTO, джобы
├── Infrastructure/    # БД, middleware, исключения, value objects
└── Presentation/      # HTTP-слой (контроллеры, роуты, Vue-страницы)
    ├── Web/           # Web-приложение (Inertia + Vue)
    └── Background/    # CLI-команды и планировщик
```

### Домены системы

| Домен | Описание |
|-------|----------|
| `Admin` | Пользователь системы (авторизация, email-верификация) |
| `Monitor` | Домены для мониторинга, настройки проверок, логи |
| `Auth` | Аутентификация, верификация email |

### Паттерн Read/Write репозиториев

Доступ к данным разделён на чтение (`ReadEloquent`) и запись (`WriteEloquent`), что предотвращает случайные мутации при чтении.

```
Domain/Monitor/Eloquent/
├── MonitorDomainReadEloquent.php
├── MonitorDomainWriteEloquent.php
├── MonitorLogReadEloquent.php
└── ScheduleSettingWriteEloquent.php
```

## Функциональность

### Мониторинг доменов
- Добавление домена с настройками: метод (GET/HEAD), интервал проверки (1–1440 мин), таймаут (1–60 сек)
- Редактирование и удаление доменов
- Просмотр истории проверок: статус (Up/Down), HTTP-код, время ответа, текст ошибки

### Фоновые проверки
- Планировщик (`monitor:check`) запускается каждую минуту и диспетчеризует `CheckDomainJob` для всех доменов, у которых наступило время проверки
- Джоб отправляет HTTP-запрос, фиксирует результат в `monitor_logs`, поддерживает retry (3 попытки)

### Аутентификация
- Регистрация, вход, выход
- Обязательная верификация email перед доступом к приложению

## База данных

```
admins
├── id, name, email, email_verified_at
└── password (value object с хешированием)

monitor_domains
├── id, admin_id (FK), domain
└── next_check_at

schedule_settings
├── id, monitor_domain_id (FK → cascade delete)
├── method (GET|HEAD), interval (min), timeout (sec)

monitor_logs
├── id, monitor_domain_id (FK → cascade delete)
├── is_up, status_code, response_time (ms), error
└── checked_at
```

## Быстрый старт

### Через Docker (рекомендуется)

```bash
cp .env.example .env
docker compose up -d
docker compose exec api php artisan key:generate
docker compose exec api php artisan migrate
```

Приложение доступно на `http://localhost:8000`.

### Локально

**Требования:** PHP 8.4, Composer, Node.js 20+, PostgreSQL, Redis

```bash
# Установка зависимостей и первичная настройка
composer setup

# Запуск в режиме разработки
# (одновременно: PHP-сервер, queue worker, логи Pail, Vite)
composer dev
```

## Docker-сервисы

| Сервис | Образ | Порт |
|--------|-------|------|
| `api` | PHP 8.4 FPM (кастомный) | — |
| `nginx` | nginx:alpine | `8000:80` |
| `pgsql` | postgres:16-alpine | `5432` |
| `redis` | redis:7-alpine | `6379` |
| `queue` | PHP 8.4 FPM (кастомный) | — |
| `scheduler` | PHP 8.4 FPM (кастомный) | — |

## Команды разработки

### PHP / Backend

```bash
# Запуск тестов
composer test

# Форматирование кода (Laravel Pint)
composer lint

# Проверка форматирования без применения
composer lint:check

# Полная CI-проверка (lint + types + tests)
composer ci:check
```

### JS / Frontend

```bash
# Сборка для продакшена
npm run build

# Dev-сервер Vite
npm run dev

# Проверка TypeScript
npm run types:check

# Форматирование (Prettier)
npm run format

# Проверка форматирования
npm run format:check

# Линтер (ESLint)
npm run lint:check
```

### Artisan

```bash
# Запустить проверку доменов вручную
php artisan monitor:check

# Запустить планировщик локально
php artisan schedule:work

# Запустить очередь
php artisan queue:work

# Telescope (дебаггинг) — http://localhost:8000/telescope
```

## Переменные окружения

Скопируйте `.env.example` в `.env` и заполните:

```dotenv
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=dms
DB_USERNAME=postgres
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379

QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@example.com
```

## Структура Vue-страниц

```
resources/js/
├── Pages/
│   ├── Auth/
│   │   ├── Login.vue          # Вход
│   │   ├── Register.vue       # Регистрация
│   │   └── VerifyEmail.vue    # Верификация email
│   ├── Domain/
│   │   ├── Create.vue         # Добавление домена
│   │   ├── Update.vue         # Редактирование домена
│   │   └── Detail.vue         # История проверок домена
│   ├── Dashboard.vue          # Список доменов с пагинацией
│   └── Error.vue              # Страница ошибок (403, 404, 500)
├── Layouts/
│   ├── AuthenticatedLayout.vue
│   └── GuestLayout.vue
├── Components/                # UI-компоненты (Input, Button, Modal...)
└── routes/                    # Типизированные роуты (Wayfinder)
```

## Роуты

| Метод | URL | Назначение |
|-------|-----|------------|
| `GET` | `/login` | Страница входа |
| `POST` | `/login` | Аутентификация |
| `POST` | `/logout` | Выход |
| `GET` | `/register` | Страница регистрации |
| `POST` | `/register` | Создание аккаунта |
| `GET` | `/verify-email` | Запрос верификации email |
| `GET` | `/verify-email/{id}/{hash}` | Подтверждение email |
| `POST` | `/email/verification-notification` | Повторная отправка письма |
| `GET` | `/dashboard` | Список доменов |
| `GET` | `/domains/create` | Форма создания домена |
| `POST` | `/domains` | Сохранение нового домена |
| `GET` | `/domains/{id}/details` | История проверок |
| `GET` | `/domains/{id}/update` | Форма редактирования |
| `PUT` | `/domains/{id}` | Обновление домена |
| `DELETE` | `/domains/{id}` | Удаление домена |

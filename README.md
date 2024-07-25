# Setup

copy .env.example to .env

```bash
cp .env.example .env
```

create sqlite file or configure database yourself
    
```bash
touch database/database.sqlite
```

install dependencies

```bash
composer install
```

generate key

```bash
php artisan key:generate
```

run migrations

```bash
php artisan migrate --seed
```

run server

```bash
php artisan serve
```

default user is created with seeder so you can use this credentials to login:

email:  test@example.com
password: password

but if not you can create new user via `php artisan make:filament-user` command




# bug

repeater extra actions are not blocked from opening when other action is proccessing

to reproduce:

- create post
- go to edit page
- create comment on edit page
- press publish button
- throttle network speed, 
- press add category button on publish modal
- close category add modal
- quickly before publish modal reopens press publish button again
- new modal will open and then the parent action that was canceled will reopen
- after u close parent that new one will reopen again
- close both of them and then press publish button again and it will not open

## video recording 
[video recording](./bug-proof.mp4)

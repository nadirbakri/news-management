# News Management PT. Jelajah Data Semesta

Proyek ini adalah API untuk Back End Developer Technical Test PT. Jelajah Data Semesta

## Instalasi

### Persyaratan

Pastikan Anda telah menginstal:

- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/)
- [Git](https://git-scm.com/)

### Langkah 1: Clone Repositori

```bash
git clone https://github.com/yourusername/news-management.git
cd news-management
```

### Langkah 2: Instal Dependensi

```bash
cd server
composer install
cp .env.example .env
php artisan key:generate
```

### Langkah 3: Edit ENV

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### Langkah 4: Jalankan Migrasi

```bash
php artisan migrate
```

### Langkah 5: Konfigurasi Laravel Passport

```bash
php artisan passport:install
```

Jika error, tambahkan line dibawah pada composer.json

```bash
"require": {
    ...
    "laravel/passport": "^11"
},
```
lalu jalankan

```bash
composer update
```

### Langkah 6: Jalankan Server

```bash
php artisan serve
```

## PENGGUNAAN

Endpoint berikut merupakan endpoint yang hanya dapat diakses oleh User biasa (non admin) :

- **View User Profile**
  - Method: GET
  - URL: `/v1/user/{id}`
  - Controller: `UserController@show`

- **View List of News**
  - Method: GET
  - URL: `/v1/news`
  - Controller: `NewsController@index`

- **View Single News**
  - Method: GET
  - URL: `/v1/news/{id}`
  - Controller: `NewsController@show`

- **Create Comment**
  - Method: POST
  - URL: `/v1/news/comment/{id}`
  - Controller: `NewsController@comment`

Endpoint berikut merupakan endpoint yang hanya dapat diakses oleh Admin:

- **Register User**
  - Method: POST
  - URL: `/v1/register`
  - Controller: `UserController@register`

- **Login User**
  - Method: POST
  - URL: `/v1/login`
  - Controller: `UserController@login`

- **Manage News (CRUD)**
  - Method: GET, POST, PUT, DELETE
  - URL: `/v1/news`
  - Controller: `NewsController`

- **Update User Status**
  - Method: POST
  - URL: `/v1/user/statusUpdate/{id}`
  - Controller: `UserController@statusUpdate`

- **Manage Users (CRUD)**
  - Method: GET, POST, PUT, DELETE
  - URL: `/v1/user`
  - Controller: `UserController`


# TesztFeladat

Steps to run the project:

* Inside Backend folder:

  1. composer install
  2. cp .env.example .env
  3. php artisan key:generate
  4. update .env file database setup (DB_CONNECTION=mysql_carservice)

     for carservice models

     ```
     DB_CONNECTION_CARSERVICE=mysql
     DB_HOST_CARSERVICE=127.0.0.1
     DB_PORT_CARSERVICE=
     DB_DATABASE_CARSERVICE=
     DB_USERNAME_CARSERVICE=
     DB_PASSWORD_CARSERVICE=
     ```

     for laravel built-in models

     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=
     DB_DATABASE=
     DB_USERNAME=
     DB_PASSWORD=
     ```
  5. change config/database.php according to .env connections
  6. php artisan migrate
  7. php artisan db:seed
  8. php artisan serve
* Inside Frontend folder:

  1. npm install
  2. npm run dev

---

The project took about 17.5  hours to complete.

---

Used components versions:

* Vue CLI v5.0.8
* Laravel v11.41.3
* PHP v8.2
* TypeScript v5.7.3

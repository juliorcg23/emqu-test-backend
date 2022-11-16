#composer install

#cp .env.example .env

#php artisan key:generate

#php artisan migrate --seed

#php artisan migrate

#php artisan serve

<h1>Using docker<h1>

<p>Run backend service</p>
<p>docker-compose up --build -d</p>

<p>Run migrations and seeders</p>
<p>docker-compose exec main php artisan migrate:fresh --seed</p>

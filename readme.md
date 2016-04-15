# PROJECT SETUP
============================

## Git

~~~
git init
git remote add origin git@bitbucket.org:clevercrew4bible/bible.git
git pull origin master
git config core.filemode false
~~~

## Composer

~~~
curl -sS https://getcomposer.org/installer | php
php composer.phar install
~~~


# CONFIGURATION
---------------

~~~
Copy `.env.example` file with name `.env`
Run `php artisan key:generate` command
Create writable (chmod 777) folder `uploads` at `PROJECT_ROOT/public` (`cd PROJECT_ROOT/public && mkdir -m 777 uploads`)
~~~

### Database

Setup in `.env` with real data:


```
DB_HOST=169.45.129.25
DB_DATABASE=bible
DB_USERNAME=bible
DB_PASSWORD=MYpb0wB0IxMB
DB_CONNECTION=pgsql

```

Execute migrations
~~~
php artisan migrate
~~~

Execute data seeders 
~~~
php artisan db:seed
~~~

Process data
~~~
php artisan lexicon:cache --ver=king_james
php artisan lexicon:cache --ver=berean
php artisan lexicon:cache --ver=nasb
php artisan symbolism:cache --ver=king_james
php artisan symbolism:cache --ver=berean
php artisan symbolism:cache --ver=nasb
~~~

FINISH
----------------
Point virtual host document root to `public`.

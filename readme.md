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
php composer.phar update
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
DB_HOST=*.*.*.*
DB_DATABASE=***
DB_USERNAME=***
DB_PASSWORD=*******
DB_CONNECTION=pgsql

```

### Mailchimp

Setup in `.env` with real data:

```
MAILCHIMP_LIST_ID=***********

```
//// DATABASE SECTION ////
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
//// END OF DATABASE SECTION ////

FINISH
----------------
~~~
chmod 0777 -R bootstrap/cache/
chmod 0777 -R storage/logs/
chmod 0777 -R storage/framework/
~~~

Install new cronjob
~~~
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
~~~

Versioning (css/js)
~~~
php artisan asset:dist
chmod 0777 -R public/assets/
~~~
ToDoList with Symfony
========

## Env project development :
```
- PHP : 8.2.1
- Symfony : 5.4.20
- Composer : 2.5.1
- MariaDB : 10.4.22
- Apache : 2.4.52
```

# Installation :
## Clone repo :
```
https://github.com/Jalal-Eddine/p08_oc.git
```
## Install Composer (dependency)
```
https://getcomposer.org/download
```
## Config .env

## Create your database
```
php bin/console doctrine:database:create
```
## Execute your migration
```
php bin/console doctrine:migrations:migrate
```
## Execute your fixtures
```
php bin/console doctrine:fixtures:load
```

## Credentials
```
Admin: all permission : 
{
    "username":"Chester.Dicki", 
    "password":"123"
}
user: permission adapted to client need : 
{
    "username":"Jonathon.Roob39", 
    "password":"123"
}
```
# Tester l'application


## Inside `.env.test.local`, put your database connection:
```
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name_test?serverVersion=5.7"
```

## Create you database :
```
    php bin/console doctrine:database:create --env=test
```

## Execute your test : 
```
    php bin/phpunit
```

## Generate a test coverage 
```
    php bin/phpunit --coverage-html deliverables/test-coverage
```
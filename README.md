ToDoList with Symfony
========
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/e7125f64498e403ab3f0e2a5471fc1ed)](https://www.codacy.com/gh/Jalal-Eddine/p08_oc/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Jalal-Eddine/p08_oc&amp;utm_campaign=Badge_Grade)

## Env project development :
```
- PHP : 8.2.1
- Symfony : 5.4.20 LTS
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
Admin : 
{
    "username":"admin", 
    "password":"Secert"
}
user1 : 
{
    "username":"user1", 
    "password":"Secert"
}
user2 : 
{
    "username":"user2", 
    "password":"Secert"
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

## create the tables/columns in the test database :
```
    php bin/console --env=test doctrine:schema:create
```

## Execute your test : 
```
    php bin/phpunit
```

## Generate a test coverage 
```
    php bin/phpunit --coverage-html livrables/test-coverage
```
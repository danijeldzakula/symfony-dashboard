# Symfony Dashboard application

### Create SQL database:

1. [Localhost](http://localhost/phpmyadmin) - Start wamp localhost server and create database. 
2. Inside `.env` on 30.line of code change database name: (NEW_NAME_OF_DATABASE) - `DATABASE_URL="mysql://root:@127.0.0.1:3306/NEW_NAME_OF_DATABASE?serverVersion=5.7&charset=utf8mb4"`

### Install application:

1. `composer install`
2. `symfony console doctrine:database:create`
3. `symfony console make:migration`
4. `symfony console doctrine:migrations:migrate`
5. `npm install --global yarn` or `yarn install`

### Start application:

1. `symfony server:start -d`
2. `yarn encore dev --watch`
3. `symfony open:local`
4. e.q: `http://127.0.0.1:8000/` add `697868c2e3364b0c6529e42626305015/login` 
5. e.q: [Symfony](http://127.0.0.1:8000/697868c2e3364b0c6529e42626305015/login)


### Tech Documentation:

- [Symfony](https://symfony.com/) - Symfony is a set of reusable PHP components, and a PHP framework for web projects.
- [TwigSymfony](https://twig.symfony.com/) - Twig is a modern template engine for PHP.
- [SassLang](https://sass-lang.com/) - Sass is the most mature, stable, and powerful professional grade CSS extension language in the world.
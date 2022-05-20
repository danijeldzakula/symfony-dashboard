# Symfony Dashboard application

---

### Create SQL database:

1. [Localhost](http://localhost/phpmyadmin) - Start wamp localhost server and create database. 
2. Create new database with name: `users_dashboard`, or change database name but change file in `.env` inside project 30.line of code
    - FROM - `DATABASE_URL="mysql://root:@127.0.0.1:3306/users_dashboard?serverVersion=5.7&charset=utf8mb4"` 
    - TO - `DATABASE_URL="mysql://root:@127.0.0.1:3306/NEW_NAME_OF_DATABASE?serverVersion=5.7&charset=utf8mb4"`

### Install application:

1. `composer install`
2. `symfony console make:migration`
3. `symfony console doctrine:migrations:migrate`
4. `npm install --global yarn`
5. `yarn install`

### Start application:

1. `symfony server:start -d`
2. `symfony open:local`
4. `e.q: http://127.0.0.1:8000/` + `697868c2e3364b0c6529e42626305015/login`
3. `yarn encore dev --watch`

### Tech Documentation:

- [Symfony](https://symfony.com/) - Symfony is a set of reusable PHP components, and a PHP framework for web projects.
- [TwigSymfony](https://twig.symfony.com/) - Twig is a modern template engine for PHP.
- [SassLang](https://sass-lang.com/) - Sass is the most mature, stable, and powerful professional grade CSS extension language in the world.
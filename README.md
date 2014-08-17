4xxi-test
=========
**Instalation**<br/>
1. Setup [important permissions] (http://symfony.com/doc/master/book/installation.html#configuration-and-setup) (you can use *permisions.sh* file with SU access)<br/>
2. Run `composer.phar update` for install all dependencies for project.<br/>
3. Configure *'parameters.yml'*<br/>
3.1. You can use defaults by system for database, mail and e.t.c configurations<br/>
3.2. Need to [register facebook application] (https://developers.facebook.com/) for credentials: *facebook_id* and *facebook_secret*<br/>
4. Run `app/console doctrine:database:create` for create configured by *'parameters.yml'* database<br/>
5. Run `app/console doctrine:migrations:migrate` for build database schemas structure<br/>
6. Run `app/console assets:install --symlink` (for *nix systems) for setting up all JS, images and other project dependencies.




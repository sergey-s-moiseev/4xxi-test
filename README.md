4xxi-test
=========
**Instalation**
1. Setup [important permissions] (http://symfony.com/doc/master/book/installation.html#configuration-and-setup) (you can use *permisions.sh* file with SU access)
2. Run `composer.phar update` for install all dependencies for project.
3. Configure *'parameters.yml'*
3.1. You can use defaults by system for database, mail and e.t.c configurations
3.2. Need to [register facebook application] (https://developers.facebook.com/) for credentials: *facebook_id* and *facebook_secret*
4. Run `app/console doctrine:database:create` for create configured by *'parameters.yml'* database.
5. Run `app/console doctrine:migrations:migrate` for build database schemas structure.
6. Run `app/console assets:install --symlink` (for *nix systems) for setting up all JS, images and other project dependencies.



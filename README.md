# MG-tch

Deploy instructions
 
Environment needs : 
PHP >= 7.2
Composer
NodeJS
GIT

After environment is set up you need to run :
git clone https://github.com/chrisr4g/MG-tch.git
cd /pathToProject
composer install
composer dump-autoload -o
php artisan migrate:fresh

crontab -e
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
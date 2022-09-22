# MG-tch

Deploy instructions
 
Environment needs : 
*PHP >= 7.2
*Composer
*NodeJS
*GIT

After environment is set up you need to run :
1. git clone https://github.com/chrisr4g/MG-tch.git
2. cd /pathToProject
3. composer install
4. composer dump-autoload -o
5. php artisan migrate:fresh
6. crontab -e
```
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

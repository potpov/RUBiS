# RUBiS
three PHP7 implementations of the benchmarking website RUBiS (procedural, OOP, Laravel)

# INSTALLATION
for our study we used apache2, mysql and PHP7.1

OOP and PROCEDURAL: go to your webserver working path, create a directory named "PHP" and move inside of it the project folder.
LARAVEL: change your webserver configuration in order to work with laravel. if you use apache2 copy the file laravel.conf into 
/etc/apache2/sites-available 
and then:
a2dissite 000-default.conf
a2ensite Laravel.conf
sudo a2enmod rewrite
service apache2 reload

then you need to install composer, move to the laravel folder and give:
composer install
in order to load all the dependences.

you also need to install the following extensions for PHP7:
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension

MYSQL: on the RUBiS offial website is available a dump for the rubis database.

unfortunately our study at this stage is only in italian language:
we tested the 3 versions with two range of user (1-40 and 20-800) and we made some consideration on  the results

this is a very interesting guide to set up the rubis client: https://sanifool.wordpress.com/2012/09/03/rubis-workload-simple-installation-guide/
on my profile there are a couple of tools to accelerate the result's collecting from the rubis client.

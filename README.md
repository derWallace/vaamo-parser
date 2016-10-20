# Introduction
I was looking for a good place where to put my money. A while ago I came across
Vaamo and decided to create a account and give it a try.
The problem was that they don´t provide a graph about the development of the fond.
The new data came in every day so i wrote down the numbers to a spreadsheet.
Time passed and I got bugged out by this process. Apparently Vaamo don´t provide
an API so i wrote this crawler to get the data from their website.

I don´t give any gurantee for this script and I am not responsible for what you
do with my script.

# Usage
First set your username and password in the config.php file. Then run

```
composer install
```

(You can get composer at http://getcomposer.org)

The you can start the script with
```
php -f parse.php
```

If you need the data in a different format, feel free to make these changes by
yourself.

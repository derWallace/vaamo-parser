# Introduction
I was looking for a good place where to put my money. A while ago I came across
Vaamo and decided to create a account and give it a try.
The problem was that they don´t provide a graph about the development of the fond.
The new data came in every day so i wrote down the numbers to a spreadsheet.
Time passed and I got bugged out by this process. Apparently Vaamo don´t provide
an API so i wrote this crawler to get the data from their website.

I don´t give any gurantee for this script and I am not responsible for what you
do with my script.

I also added a script that upload this data to a Google Spreadsheet. If you want
to use this, you need to follow the instructions of the following link to
generate a authentication json file:
https://www.npmjs.com/package/google-spreadsheet#authentication

# Usage parser
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


# Usage upload
If you have generated the authentication json file described in the Introduction
you must set the name in the "upload-to-spreadsheet.js" like this:

```
...
var creds = require('./parse-vaamo-a988cb5f1c01.json');
...
```

Make sure you have shared your spreadsheet with the service email address given
in the json file.

The you must gather the key of your spreadsheet. Its the long string in the URL
of the open spreadsheet tab in your browser. Set this in you config.php

```
define('GOOGLE_SPREADSHEET', '...');
```

With the mapping variable in the config.php you can set which "Sparziel" information
will be set to which spreadsheet. The key is the "Sparziel" name and the Value
the spreadsheet tab name.

The last step is to install the npm module google-spreadsheet like this:
```
npm install google-spreadsheet
npm install async
```

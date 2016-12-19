# Importer

Importer for sql files into MySQL database in PHP.

Follow the following instructions to use it:

####Method 1:

* Fork or clone or download the repository.
* Open example.php and follow along with the comments.
* OR Require class.importer.php to your php file, along with functions from `example.php`.
* Replace username with your database username, replace password with password associated with username, replace the database name with database name that you have created.
* Place the sql file in `sqlfiles` folder.
* Run `example.php` or the file that you have created.

####Method 2:

* Install **Composer** <https://getcomposer.org/>
* Add following to `composer.json`
```js
  "repositories": [
      {
          "type": "vcs",
          "url": "https://github.com/priyasonali/importer"
      }
  ],
  "require": {
      "importer/importer": "dev-master"
  }
```
* Run composer.
* Go to `vendors/importer/importer` folder.
* Follow **Method 1** (#2 - #6) or go through `example.php`.

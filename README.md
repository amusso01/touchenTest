##Coding Test For ToucanTech 

**Author:** Andrea Musso

####The file present in the test folder are:

* `**include/dbConfig.php**` ==> Configuration for both databases
* `**include/function.php**` ==> Functions used by the main file (import.php)
* `**bootstrap.php**`        ==> Bootstarp for the main file
* `**import.php**`           ==> Main function to import the DB
* `**source.sql**`           ==> Sql statement to create the external DB, already contains DATA
* `**tt_database.sql**`      ==> Sql statement create the tt_ DB, where the function will import the DATA
  

####Before Deploying || Execute the function

1. Create a new DB for the source DB 
2. Run the `source.sql` into the newly created DB to create tables and add DATA
3. Create a new DB for the tt_ DB (our database)
4. Run the `tt_database.sql` into the DB you've just created to build the tables
5. Configure the `dbConfig.php` inside the include folder to match your DB environment
6. Upload all the file needed by the function to the server root: `import.php`, `bootstrap.php` and the `include/` folder
7. Visit the address to execute the function (ex: _www.localhost/test/import.php_ )


####Assumption

* Reference field must stay capital letters even in tt_ DB
* In source.contact if the _description_ is not specified but entered contact are correct email/phone, the function allows the entry to be registered as _home_ type in our database. This behaviour can be changed.

######Reflection

I've noticed _address.country_ in the source DB are sometimes entered as a 2 letters code, I've discard those values. However, I could have used the public API [REST countries](https://restcountries.eu/) to map those 2 letters code in our DB. Although, the UK code is not recognized as a valid international code (ISO 3166) so a further conversion should be made in order to work with this API (UK is considered a FIPS 10-4 valid code, whilst the ISO is GB). 

**Note:** The function has been tested in a LAMP environment using Php 7.2.4 . Using PDO MySQL could be replace by any RDB, however this has not been tested.
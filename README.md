
  
# Multi-Currency Invoices API
In this exercise, dev must design and implement a backend service to support invoice creation and viewing in
multiple currencies.
# Pre Installation
-  **Composer Download**: https://getcomposer.org/download/
-  **Web Server**: Feel free to use any web server like apache,nginx or software like xampp or wamp. I personally use xampp for mysql and apache server. https://www.apachefriends.org/download.html
-  **MySQL Server**: Feel free to use any MySQL Server
-  **Postman**: Feel free to use any API tools. I personally use Postman for restful and graphQL approach https://www.postman.com/downloads/
# Installation
1.  **Codebase Github Url**: https://github.com/simueljester/multicurrency-api.git
2.  **Clone Url**: `git clone https://github.com/simueljester/multicurrency-api.git`
3.  **Navigate**: `cd multicurrency-api`
4.  **Composer**: Inside the project root directory run `composer install`
5.  **ENV file**: In the root directory, locate the existing **.env.example** file and rename it to **.env** (or create a new .env file if needed). Then, copy the contents of the **.env** file from the attached email and paste them into your local **.env** file.
6.  **Generate Key**: `php artisan key:generate`
7.  **Run Application**: run `php artisan serve` this will run the application based on your preferred host. By default, server running on [http://127.0.0.1:8000].
8.  **Database Setup**: in our .env `DB_DATABASE=db_multi_currency_invoices` is setup, make sure there is a created database named **db_multi_currency_invoices** (or create your preferred name as long as it matches). You can also setup the username and password of db in .env but in our case username = root and password is none.
9.  **Data Migration**: run `php artisan migrate` this will create a tables
10. **Population**: run `php artisan currencies:sync` to populate valid currencies as a look up table.
11. **Open Exchange Sync**: run `php artisan exchange:fetch` to fetch the latest exchange rates based on USD (free tier only) and will save it to our database table **exchange_rates**. **Note that you can always run this command everytime you want an updated exchange rate.** This command is useful in scheduled jobs like CRON and Task Scheduler. 
12. **Postman**: Attached in the email is the post collection file. Just import it in your postman to test the API's. 

  
## Technologies Used
-  **Laravel Ecosystem**: A powerful PHP framework for web applications and API Development.
-  **Postman**: Postman is an all-in-one API platform for building and working with APIs.

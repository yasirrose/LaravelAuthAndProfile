## Setting Up Project

Place project folder in C:\xampp\htdocs:
Open CMD in the project Folder and Follow the Steps below one by one:

Install Composer Files by running : composer install
Create a file .env in project root folder and paste content of .env.example file.
Replace Crendetials for MAIL setting in .env file with the following with your details:

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME= YOUR GMAIL ACCOUNT EMAIL
MAIL_PASSWORD= YOUR GMAIL ACCOUNT PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=YOUR GMAIL EMAIL
MAIL_FROM_NAME="${APP_NAME}"


Generate Key: php artisan key:generate
Run Migration: php artisan migrate
Run Seeder For Dummy Data: php artisan db:seed

Run php artisan passport:client --password
Enter Name and Press Enter
Paste the Client id and Client Secret in .ENV file.

For Admin: 
UN: admin@admin.com
PW: 123456

For User:
UN:test@gmail.com
PW: 123456


NOTE: Import Insomnia_2021-04-14_APIs.json  APIs file in the project root folder  to the Insomnia.

To Test APIs using Virtual Host:

1) Go to  C:\xampp\apache\conf\extra
File: httpd-vhosts.conf

Edit the file and add the following to the end of the file:

<VirtualHost *:80>
    ServerAdmin webmaster@laravel_assignment.test
    DocumentRoot "C:/xampp/htdocs/laravel_assignment/public"
    ServerName laravel_assignment.test
    ServerAlias www.laravel_assignment.test
</VirtualHost>


2) Go the C:\Windows\System32\drivers\etc\
File: hosts

Edit the file and add the following to the end of the file:

127.0.0.1      laravel_assignment.test
::1            laravel_assignment.test

3) Restart Apache and Mysql in Xampp.

4) Test the APIs using Postman collection for this project.


APIs Testing Note:
1) Login the User
2) Copy the token from the response of login API and replace with the token in the APIs that requirs token.
3) Admin can send invitation link after login to the desired user wby providing email in the API. EMail will be received after sending invitation with a dummy link. After that Register API is used to register a user and for the account activation Pin code is sent to the email after registeration and PIN code is used in Activate Account API to activate the account.
4)Update Profile API is used to update the profile information with a respone of updated data after API is hit.








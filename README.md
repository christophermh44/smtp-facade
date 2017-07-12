# SMTP Facade

This micro tool allows you to manage authentications on your website like binding to LDAP but with your SMTP server.

Please note that this tool will not check for you if the user exists or if he is allowed to connect to your application.
Here, it is connected to GMail by default. You will have to check the domain of the mail address and the existence of the user in your database before allowing him to access your application.

## Installation

```
$ composer install christophermh44/smtp-facade
```

## Example usage

See test.php to learn how to use it. Call it simply with login and password in a terminal:

```
$ php test.php 'myemail@domain.tld' 'password'
```

## Configuration

Everything is in the smtp.json file. You have to declare your SMTP server informations in this file. See PHPMailer to know more about parameters. By default, it is configured to ask GMail SMTP server.

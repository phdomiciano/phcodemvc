## About this project

This is a simple CRUD of courses list with MVC but without Frameworks.
The challenge is not to use external frameworks but to develop an entire MVC structure following good practices and concepts.

## Requirements

- PHP ^8.1
- Composer

## This code uses
- PSR-4: Autoloading (Composer)
- DOCTRINE/ORM
- SQLITE

## Installation

Install the package through [Composer](http://getcomposer.org/). 

Run the Composer require command from the Terminal:

    composer create-project phdomiciano/phcodemvc

If necessary update your requiries, run on Terminal:

    composer update

    composer dump-autoload

Create the sqlite database and all tables required, run on terminal:

    php db.php migrate

Run on Terminal your php server and access the url from project in a web browser.

    php -S localhost:8080
    
## Applied solutions

- Requests Forms Validation
- Dependency Injection
- Polymorphism
- Anonymous classes

## Developed solutions

#### Migration
To be used in a development environment, a script was created that can be executed by Terminal, for small instructions to the database, such as creating the database, executing migrations, consulting records and tables, among others.
Para testar e saber mais execute no terminal: 

    php db.php help

For new migrations, a structure similar to other Frameworks was created, just create a new file in the "database/migrate" folder

#### Index
The index.php file is the application's Core, responsible for filtering, forwarding the routes and executing the first validations.

#### Routes
To create a new route, simply add it to the "config/routes.php" file. Intuitively, following in a simplified way the pattern of the main existing Frameworks.

The Route class, located in the "src/infra" folder, is responsible for identifying and filtering the route information, according to the "routes.php" list.

#### Request
The parent Request class, located in "src/requests", manages http requests, facilitates data handling and sanitizes all input data automatically regardless of the submitted method. This class can be used for manual or automatic validation of form data through the "validate()" method.

The "validate()" method can receive an array of filters manually or capture the validation rules automatically from the child classes of Request.

The custom Request class can be configured in the "routes.php" file. If no custom Request is provided for the route, the system automatically loads the parent class, allowing manual validations.

#### Controller
The Controller parent class already has an entityManager and an object of the Request class, which will be explained below.

#### Auth
The Auth class, located in the "src/infra" folder, can be used anywhere on the system. It handles and stores user authentication information, record ownership validation, and CRSF token management.

#### View
The View class, located in the "src/infra" folder, must be used in controllers to display views. This class is responsible for finding view files, managing alerts and errors with automatic capture.

#### CSRF protection
This code is concerned with CSRF (Cross-site request forgery). The Auth class manages random tokens that change with each submit and all forms must contain a hidden one with this token, which is automatically compared by the Request class every time some data is requested.
Form submits don't work if the form doesn't have this hidden with valid token.
Hidden can be easily created in any view, as the View class loads an Auth object with a method that generates a hidden. Just print <?=$auth->token();?> inside each form.

#### ORM by Attributes
Doctrine was used as ORM, configured with mapping by Attributes instead of Annotations, in a more visual way and without having to use comments as code.

#### PSRs and best practices
This code meets PSRs, following some of the established standards like PSR-4, PSR-1 and PSR-12.
Code identification, clean code, code structure, typing variables and function parameters, as well as defining return types.


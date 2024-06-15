# UMS Library Management System

The UMS Library Management System is a web-based application designed to manage the operations of a library. This system allows users to register, log in, borrow books, return books, and view a list of available books. Additionally, it includes an admin dashboard for managing the system.

## Features

- Login: Users can log in to their accounts to access the library system.
- Register: New users can create an account.
- Borrow Book: Users can borrow available books.
- Return Book: Users can return borrowed books.
- Show List of Books: Displays all available books in the library.
- Dashboard: Admin dashboard to manage library operations.


## Installation and Setup

### Prerequisites
![PHP 7.4 or higher](https://img.shields.io/badge/PHP-777BB4.svg?style=for-the-badge&logo=PHP&logoColor=white)
![MySql](https://img.shields.io/badge/MySQL-4479A1.svg?style=for-the-badge&logo=MySQL&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24.svg?style=for-the-badge&logo=XAMPP&logoColor=white)

## Development Setup

1. Clone the Repository

Open your terminal and run the following command to clone the repository into the htdocs folder of your XAMPP installation:
To begin using this template, choose one of the following options to get started:

```
cd C:/xampp/htdocs
git clone https://github.com/your-username/ums-library.git
```

2. Configure the Database

- Start XAMPP and ensure Apache and MySQL are running.
- Open your web browser and go to http://localhost/phpmyadmin.
- Create a new database named ums_library.
- Import the provided SQL file (database/ums_library.sql) into the ums_library database.

3. Update Configuration

Navigate to the project folder:
  
```
cd ums-library
```

Open the config.php file and update the database credentials:
  
```
<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ums_library');
?>
```

4. Run the Application

Open your web browser and go to http://localhost/ums-library


## Contributing
If you would like to contribute to this project, please fork the repository and create a pull request with your changes. For major changes, please open an issue first to discuss what you would like to change.

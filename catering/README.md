# University Catering Services Automated Food Ordering System

## Introduction

This project is an automated food ordering system for the University Catering Services. It allows customers to order food online and choose to pay either through PayPal or in cash upon collection. The system also includes an admin panel for managing orders and transactions.

## Prerequisites

- XAMPP (Apache, MySQL, PHP)
- Web browser

## Installation

1. **Download and Install XAMPP**:
   - Download XAMPP from [Apache Friends](https://www.apachefriends.org/index.html).
   - Install XAMPP on your computer.

2. **Clone the Repository**:
   - Place the project directory inside the `htdocs` folder of your XAMPP installation.
   - The directory structure should look like this: `C:\xampp\htdocs\food-ordering\catering\`

3. **Set Up the Database**:
   - Start XAMPP and ensure Apache and MySQL are running.
   - Open your web browser and go to [phpMyAdmin](http://localhost/phpmyadmin).
   - Create a new database named `universitycatering`.
   - Import the SQL file (`universitycatering.sql`) to set up the database schema and data.
     - To import, click on the `universitycatering` database, go to the Import tab, choose the SQL file, and click Go.

4. **Configure the Project**:
   - Ensure that the project files are correctly placed inside the `C:\xampp\htdocs\food-ordering\catering\` directory.
   - The project should be accessible at [http://localhost/food-ordering/catering/index.php](http://localhost/food-ordering/catering/index.php).

## Usage

1. **Access the Application**:
   - Open your web browser and navigate to [http://localhost/food-ordering/catering/index.php](http://localhost/food-ordering/catering/index.php).

2. **Admin Panel**:
   - Navigate to the admin panel at [http://localhost/food-ordering/catering/admin/](http://localhost/food-ordering/catering/admin/).
   - Login with the following credentials:
     - **Username**: `admin`
     - **Password**: `1234567890`

## Project Structure

- **index.php**: The main entry point for the application.
- **admin/**: Contains files related to the admin panel.
- **payment-method.php**: Page where users can select their payment method.
- **execute-payment.php**: Handles the payment execution logic.
- **config/**: Configuration files including database connection settings.
- **includes/**: Common includes and helper functions.
- **assets/**: Contains CSS, JS, and image files used by the application.

## Features

- **Online Payment with PayPal**: Users can choose to pay online using PayPal.
- **Cash Payment Option**: Users can choose to pay in cash upon collection.
- **Admin Panel**: Manage orders, view transaction details, and update order statuses.
- **Email Notifications**: Sends email notifications upon successful order placement.

## Testing

- The project includes various test scenarios to ensure that both PayPal and cash transactions are handled correctly.
- PayPal Sandbox accounts are used for testing online payments.

## References

- PayPal API documentation: [PayPal Developer](https://developer.paypal.com/docs/api/overview/)
- XAMPP: [Apache Friends](https://www.apachefriends.org/index.html)

## Conclusion

This project provides a robust and secure solution for managing online food orders for university catering services, integrating PayPal for secure online payments, and providing a flexible and user-friendly interface for both customers and administrators.

## Contact

For any issues or inquiries, please contact the developer:

- **Developer**: Saghar Fadaei
- **Email**: [saghar.fadaei@gmail.com]

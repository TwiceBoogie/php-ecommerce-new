# PHP E-commerce Platform

A comprehensive e-commerce platform developed using native PHP, incorporating modern development practices and tools. This project offers a robust foundation for building scalable online stores.

## Features

- **User Authentication**: Secure user registration and login functionalities.
- **Product Management**: Add, update, delete, and display products with detailed information.
- **Shopping Cart**: Seamless addition and removal of products, with quantity adjustments.
- **Order Processing**: Complete order placement and tracking system.
- **Admin Dashboard**: Manage users, products, orders, and view analytics.
- **Responsive Design**: Mobile-friendly interface ensuring a consistent experience across devices.

## Technologies Used

- **Backend**: PHP (native)
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL
- **Package Management**: Composer, npm
- **Build Tools**: Webpack
- **Containerization**: Docker, Docker Compose

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- npm
- Docker & Docker Compose (optional, for containerized setup)

### Steps

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/TwiceBoogie/php-ecommerce-new.git
   ```

2. **Navigate to the Project Directory**:

   ```bash
   cd php-ecommerce-new
   ```

3. **Install PHP Dependencies**:

   ```bash
   composer install
   ```

4. **Install JavaScript Dependencies**:

   ```bash
   npm install
   ```

5. **Set Up Environment Variables**:

   - Duplicate the `.env.example` file and rename it to `.env`.
   - Configure the database and other necessary settings in the `.env` file.

6. **Database Migration**:

   - Import the `php_project.sql` file into your MySQL database.

7. **Build Frontend Assets**:

   ```bash
   npm run build
   ```

8. **Start the Development Server**:

   ```bash
   php -S localhost:8000 -t public
   ```

   Access the application at `http://localhost:8000`.

## Docker Setup (Optional)

For a containerized development environment:

1. **Ensure Docker and Docker Compose are Installed**.

2. **Start the Containers**:

   ```bash
   docker-compose up --build
   ```

   The application will be accessible at `http://localhost`.

## Usage

- **Access the Application**: Open your browser and navigate to `http://localhost:8000` (or the appropriate URL based on your setup).
- **Admin Panel**: Navigate to `/admin` to access the admin dashboard. Default credentials can be set in the database or through the registration process.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your enhancements.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

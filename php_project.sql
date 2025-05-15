-- Set SQL mode and transaction start
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
-- Charset settings
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
-- Create table IF NOT EXISTS for Admins
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Insert default roles
INSERT INTO roles (name)
VALUES ('ADMIN'),
    ('USER');
-- Create table IF NOT EXISTS for Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    role INT NOT NULL DEFAULT 2,
    confirmed ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    register_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role) REFERENCES roles(id) ON DELETE
    SET DEFAULT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for User Details
CREATE TABLE IF NOT EXISTS user_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone VARCHAR(30),
    address VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    -- Null if email doesn't exist in DB
    email VARCHAR(255) NOT NULL,
    -- Track the attempted email
    ip_address VARCHAR(45) NOT NULL,
    -- Store user IP
    user_agent TEXT,
    -- Store browser and device details
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN NOT NULL DEFAULT 0,
    -- 0 = Failed, 1 = Success
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE
    SET NULL
);
CREATE TABLE IF NOT EXISTS user_sessions (
    id CHAR(36) PRIMARY KEY,
    -- UUID for session ID
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    -- Random reset token
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    -- Expiry time for security
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Create table IF NOT EXISTS for Products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    color VARCHAR(100),
    stock_quantity INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Product Images
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_main TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cost DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'canceled') NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Carts (Persistent Cart)
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- For guests, linked to PHP session ID
    user_id INT NOT NULL,
    -- For authenticated users, linked to the `users` table
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
-- Create table IF NOT EXISTS for Payments
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('credit_card', 'paypal', 'bank_transfer') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Discounts
CREATE TABLE IF NOT EXISTS discounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    discount_percentage DECIMAL(5, 2) NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Coupons
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percentage DECIMAL(5, 2) NOT NULL,
    max_uses INT NOT NULL,
    uses INT DEFAULT 0,
    valid_from DATETIME NOT NULL,
    valid_to DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for Product Reviews
CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (
        rating BETWEEN 1 AND 5
    ),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Create table IF NOT EXISTS for API Logs
CREATE TABLE IF NOT EXISTS api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    endpoint VARCHAR(255),
    response_time DECIMAL(10, 4),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE
    SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- DELIMITER // CREATE TRIGGER update_stock_after_order
-- AFTER
-- INSERT ON order_items FOR EACH ROW BEGIN
-- UPDATE products
-- SET stock_quantity = stock_quantity - NEW.product_quantity
-- WHERE product_id = NEW.product_id;
-- END;
-- // DELIMITER;
-- Create Indexes for optimization
CREATE INDEX idx_product_category ON products(category);
CREATE INDEX idx_order_date ON orders(created_at);
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_product_reviews ON product_reviews(product_id, user_id);
CREATE INDEX idx_cart_items ON cart_items(user_id, product_id);
CREATE INDEX idx_product_images_product_id ON product_images(product_id);
INSERT INTO `products` (
        `name`,
        `category`,
        `description`,
        `price`,
        `color`,
        `stock_quantity`
    )
VALUES (
        'Keyboard 1',
        'keyboards',
        'awesome Keyboard 1',
        91.00,
        'silver',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 2',
        'keyboards',
        'Awesome Keyboard 2',
        89.99,
        'black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 3',
        'keyboards',
        'Awesome Keyboard 3',
        89.99,
        'white/blue',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 4',
        'keyboards',
        'Awesome Keyboard 4',
        89.99,
        'black/gold',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 5',
        'keyboards',
        'Awesome Keyboard 5',
        76.00,
        'black/white',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 6',
        'keyboards',
        'Awesome Keyboard 6',
        75.00,
        'white/black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 7',
        'keyboards',
        'Awesome Keyboard 7',
        75.00,
        'gray/white',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Keyboard 8',
        'keyboards',
        'Awesome Keyboard 8',
        75.00,
        'sherbet',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Mouse 1',
        'mice',
        'Mouse 1',
        50.00,
        'black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Mouse 2',
        'mice',
        'Mouse 2',
        50.00,
        'black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Mouse 3',
        'mice',
        'Mouse 3',
        50.00,
        'black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Mouse 4',
        'mice',
        'Mouse 4',
        50.00,
        'white',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Mouse 5',
        'mice',
        'Mouse 5',
        50.00,
        'black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Headset 1',
        'heatset',
        'HeadSet 1',
        50.00,
        'black',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Microphone 1',
        'microphone',
        'Microphone 1',
        50.00,
        'gray',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Sound System 1',
        'soundsystem',
        'Sound System 1',
        50.00,
        'black/wood',
        FLOOR(1 + (RAND() * 100))
    ),
    (
        'Apple Keyboard',
        'keyboards',
        'Apple product',
        201.00,
        'white',
        FLOOR(1 + (RAND() * 100))
    );
INSERT INTO `product_images` (
        `product_id`,
        `image_url`,
        `is_main`
    )
VALUES -- Keyboard 1 Images
    (1, 'prod1.jpg', 1),
    (1, 'prod1.2.jpg', 0),
    (1, 'prod1.3.jpg', 0),
    (1, 'prod1.4.jpg', 0),
    -- Keyboard 2 Images
    (2, 'prod2.jpg', 1),
    (2, 'prod2.2.jpg', 0),
    (2, 'prod2.3.jpg', 0),
    (2, 'prod2.4.jpg', 0),
    -- Keyboard 3 Images
    (3, 'prod3.jpg', 1),
    (3, 'prod3.2.jpg', 0),
    (3, 'prod3.3.jpg', 0),
    (3, 'prod3.4.jpg', 0),
    -- Keyboard 4 Images
    (4, 'prod4.jpg', 1),
    (4, 'prod4.2.jpg', 0),
    (4, 'prod4.3.jpg', 0),
    (4, 'prod4.4.jpg', 0),
    -- Keyboard 5 images
    (5, 'prod5.jpg', 1),
    (5, 'prod5.2.jpg', 0),
    (5, 'prod5.3.jpg', 0),
    (5, 'prod5.4.jpg', 0),
    -- Keyboard 6 images
    (6, 'prod6.jpg', 1),
    (6, 'prod6.2.jpg', 0),
    (6, 'prod6.3.jpg', 0),
    (6, 'prod6.4.jpg', 0),
    -- Keyboard 7 images
    (7, 'prod7.jpg', 1),
    (7, 'prod7.2.jpg', 0),
    (7, 'prod7.3.jpg', 0),
    (7, 'prod7.4.jpg', 0),
    -- Keyboard 8 images
    (8, 'prod8.jpg', 1),
    (8, 'prod8.2.jpg', 0),
    (8, 'prod8.3.jpg', 0),
    (8, 'prod8.4.jpg', 0),
    -- Mice 9 images
    (9, 'prod9.jpg', 1),
    (9, 'prod9.2.jpg', 0),
    (9, 'prod9.3.jpg', 0),
    (9, 'prod9.4.jpg', 0),
    -- Mice 10 images
    (10, 'prod10.jpg', 1),
    (10, 'prod10.2.jpg', 0),
    (10, 'prod10.3.jpg', 0),
    (10, 'prod10.4.jpg', 0),
    -- Mice 11 images
    (11, 'prod11.jpg', 1),
    (11, 'prod11.2.jpg', 0),
    (11, 'prod11.3.jpg', 0),
    (11, 'prod11.4.jpg', 0),
    -- Mice 12 images
    (12, 'prod12.jpg', 1),
    (12, 'prod12.2.jpg', 0),
    (12, 'prod12.3.jpg', 0),
    (12, 'prod12.4.jpg', 0),
    -- Mice 13 images
    (13, 'prod13.jpg', 1),
    (13, 'prod13.2.jpg', 0),
    (13, 'prod13.3.jpg', 0),
    (13, 'prod13.4.jpg', 0),
    -- Headset 14 images
    (14, 'prod14.jpg', 1),
    (14, 'prod14.2.jpg', 0),
    (14, 'prod14.3.jpg', 0),
    (14, 'prod14.4.jpg', 0),
    -- Mice 13 images
    (15, 'prod15.jpg', 1),
    (15, 'prod15.2.jpg', 0),
    (15, 'prod15.3.jpg', 0),
    (15, 'prod15.4.jpg', 0),
    -- Mice 13 images
    (16, 'prod16.jpg', 1),
    (16, 'prod16.2.jpg', 0),
    (16, 'prod16.3.jpg', 0),
    (16, 'prod16.4.jpg', 0),
    -- Example for Apple Keyboard
    (17, 'prod27-0.jpg', 1),
    (17, 'prod27-1.jpg', 0),
    (17, 'prod27-2.jpg', 0),
    (17, 'prod27-3.jpg', 0);
-- Commit changes
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
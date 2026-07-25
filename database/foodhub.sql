-- FoodHub Database Schema & Expanded Initial Data
CREATE DATABASE IF NOT EXISTS `foodhub_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `foodhub_db`;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `delivery_assignments`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `wishlist`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `food_items`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `restaurants`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `role` ENUM('user', 'restaurant', 'delivery', 'admin') NOT NULL DEFAULT 'user',
  `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
  `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Demo Users (Password: password123)
-- Hash for password123: $2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `avatar`, `status`) VALUES
(1, 'System Administrator', 'admin@foodhub.com', '$2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g', '+1 800 555 0199', 'FoodHub HQ, 100 Tech Blvd, Silicon Valley', 'admin', 'admin.png', 'active'),
(2, 'Alex Johnson', 'user@foodhub.com', '$2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g', '+1 555 012 3456', '742 Evergreen Terrace, Springfield', 'user', 'user1.png', 'active'),
(3, 'Chef Mario Rossi', 'restaurant@foodhub.com', '$2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g', '+1 555 987 6543', '123 Italian Way, Downtown', 'restaurant', 'owner1.png', 'active'),
(4, 'David Miller', 'delivery@foodhub.com', '$2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g', '+1 555 444 3322', '456 Express Lane, Metro', 'delivery', 'driver1.png', 'active'),
(5, 'Sarah Connor', 'sarah@foodhub.com', '$2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g', '+1 555 666 7788', '89 Ocean Drive, Bay City', 'user', 'user2.png', 'active'),
(6, 'Sushi Master Kenji', 'kenji@sakura.com', '$2y$10$e8c1Q6/VnL5K0zM6x0vPte1vQ.r1q3e2t1y0u9i8o7p6a5s4d3f2g', '+1 555 333 9900', '88 Sakura Ave, Little Tokyo', 'restaurant', 'owner2.png', 'active');

-- --------------------------------------------------------
-- Table: restaurants
-- --------------------------------------------------------
CREATE TABLE `restaurants` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `cuisine` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT NOT NULL,
  `rating` DECIMAL(2,1) DEFAULT 4.5,
  `delivery_time` VARCHAR(30) DEFAULT '25-35 min',
  `delivery_fee` DECIMAL(6,2) DEFAULT 2.99,
  `min_order` DECIMAL(6,2) DEFAULT 10.00,
  `image` VARCHAR(255) DEFAULT 'default-restaurant.jpg',
  `banner` VARCHAR(255) DEFAULT 'default-banner.jpg',
  `description` TEXT DEFAULT NULL,
  `status` ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `restaurants` (`id`, `user_id`, `name`, `cuisine`, `phone`, `address`, `rating`, `delivery_time`, `delivery_fee`, `min_order`, `image`, `banner`, `description`, `status`) VALUES
(1, 3, 'Bella Italia Bistro', 'Italian & Pizza', '+1 555 987 6543', '123 Italian Way, Downtown', 4.8, '20-30 min', 1.99, 12.00, 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80', 'bella-banner.jpg', 'Authentic Neapolitan wood-fired pizzas, handcrafted pastas, and classic Italian desserts made fresh daily.', 'approved'),
(2, 6, 'Sakura Japanese & Sushi Bar', 'Japanese & Sushi', '+1 555 333 9900', '88 Sakura Ave, Little Tokyo', 4.9, '30-40 min', 3.50, 15.00, 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80', 'sakura-banner.jpg', 'Fresh sashimi, signature sushi rolls, steaming ramen bowls, and izakaya appetizers crafted by Master Kenji.', 'approved'),
(3, 3, 'Burger Craft & Wings', 'American & Fast Food', '+1 555 777 2211', '55 Gourmet St, Midtown', 4.6, '15-25 min', 2.49, 10.00, 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80', 'burger-banner.jpg', 'Smash burgers with 100% Angus beef, crispy seasoned fries, dynamic dipping sauces, and thick hand-spun shakes.', 'approved'),
(4, 6, 'Spice Route Indian Curry', 'Indian & Vegetarian', '+1 555 888 1122', '302 Spice Road, Eastside', 4.7, '30-45 min', 2.99, 14.00, 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=800&q=80', 'spice-banner.jpg', 'Rich aromatic curries, garlic naan baked in traditional tandoor, crisp samosas, and flavorful biryani bowls.', 'approved'),
(5, 3, 'El Taco Loco Cantina', 'Mexican & Street Food', '+1 555 222 3344', '412 Jalisco Blvd, Westville', 4.8, '20-30 min', 2.25, 11.00, 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=800&q=80', 'taco-banner.jpg', 'Handmade corn tortilla tacos, melted cheesy quesadillas, loaded nachos, and house-made salsas.', 'approved'),
(6, 6, 'Green Leaf Salad & Bowl Co.', 'Healthy & Vegan', '+1 555 444 8899', '19 Organic Way, Eco District', 4.9, '15-25 min', 1.99, 12.00, 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=80', 'leaf-banner.jpg', 'Fresh organic grain bowls, custom superfood salads, cold-pressed juices, and protein smoothie bowls.', 'approved'),
(7, 3, 'Golden Dragon Dim Sum', 'Chinese & Asian', '+1 555 666 4433', '88 Dynasty Street, Chinatown', 4.7, '25-35 min', 2.99, 15.00, 'https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?auto=format&fit=crop&w=800&q=80', 'dragon-banner.jpg', 'Authentic Cantonese dim sum, pan-fried dumplings, Peking duck rolls, and wok-charred fried rice.', 'approved'),
(8, 6, 'Sweet Tooth Artisan Bakery', 'Desserts & Pastries', '+1 555 111 7766', '504 Sugar Ave, Sweet District', 4.9, '20-30 min', 2.50, 8.00, 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=800&q=80', 'sweet-banner.jpg', 'Decadent layer cakes, fresh fruit tarts, warm chocolate cookies, and artisanal French pastries.', 'approved');

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `icon` VARCHAR(50) DEFAULT 'fa-utensils',
  `image` VARCHAR(255) DEFAULT 'cat-default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `icon`, `image`) VALUES
(1, 'Pizza & Italian', 'fa-pizza-slice', 'pizza.jpg'),
(2, 'Burgers & Fries', 'fa-hamburger', 'burger.jpg'),
(3, 'Sushi & Asian', 'fa-fish', 'sushi.jpg'),
(4, 'Indian & Curry', 'fa-pepper-hot', 'curry.jpg'),
(5, 'Desserts & Sweets', 'fa-ice-cream', 'dessert.jpg'),
(6, 'Drinks & Beverages', 'fa-glass-cheers', 'beverage.jpg');

-- --------------------------------------------------------
-- Table: food_items
-- --------------------------------------------------------
CREATE TABLE `food_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `restaurant_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(8,2) NOT NULL,
  `image` VARCHAR(255) DEFAULT 'default-food.jpg',
  `is_veg` TINYINT(1) DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('available', 'unavailable') DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `food_items` (`id`, `restaurant_id`, `category_id`, `name`, `description`, `price`, `image`, `is_veg`, `is_featured`, `status`) VALUES
-- Italian & Pizza
(1, 1, 1, 'Truffle Margherita Pizza', 'Fresh mozzarella, San Marzano tomato sauce, fresh basil leaves, and white truffle oil drizzle.', 16.99, 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=800&q=80', 1, 1, 'available'),
(2, 1, 1, 'Creamy Fettuccine Carbonara', 'Handmade fettuccine, crisp pancetta, egg yolk sauce, Parmigiano-Reggiano, cracked black pepper.', 15.50, 'https://images.unsplash.com/photo-1612874742237-6526221588e3?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(3, 1, 1, 'Spicy Pepperoni Feast Pizza', 'Double smoked pepperoni, melted provolone, spicy hot honey glaze on crispy wood-fired crust.', 17.50, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(4, 1, 1, 'Four Cheese Quattro Formaggi', 'Gorgonzola, mozzarella, fontina, and grated parmesan with fresh rosemary.', 16.25, 'https://images.unsplash.com/photo-1573821663912-569905455b1c?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),

-- Japanese & Sushi
(5, 2, 3, 'Dragon Roll Supreme', 'Tempura shrimp and cucumber inside, wrapped with sliced avocado, unagi sauce, and toasted sesame.', 18.50, 'https://images.unsplash.com/photo-1611143669185-af224c5e3252?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(6, 2, 3, 'Tonkotsu Pork Ramen', 'Rich 16-hour pork bone broth, tender chashu pork belly, ajitama ramen egg, and bamboo shoots.', 14.99, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(7, 2, 3, 'Crispy Salmon Aburi Nigiri (6pcs)', 'Flame-torched fresh Atlantic salmon nigiri topped with spicy Japanese mayo drizzle and tobiko caviar.', 16.00, 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),

-- Burgers & Wings
(8, 3, 2, 'Double BBQ Bacon Smash Burger', 'Two 100% Angus beef patties, aged cheddar, crispy bacon, smokey BBQ sauce on toasted brioche.', 13.99, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(9, 3, 2, 'Loaded Truffle Parmesan Fries', 'Hand-cut russet fries tossed with white truffle oil, grated parmesan, and garlic aioli.', 6.50, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?auto=format&fit=crop&w=800&q=80', 1, 1, 'available'),
(10, 3, 2, 'Spicy Crispy Chicken Burger', 'Buttermilk fried chicken breast, spicy coleslaw, dill pickles, and honey mustard sauce.', 12.99, 'https://images.unsplash.com/photo-1625813506062-0aeb1d7a094b?auto=format&fit=crop&w=800&q=80', 0, 0, 'available'),
(11, 3, 2, 'Cheesy Mushroom Swiss Burger', 'Grilled Angus patty topped with sautéed wild mushrooms and melted Swiss cheese.', 14.50, 'https://images.unsplash.com/photo-1583032015879-e5022ab87c3b?auto=format&fit=crop&w=800&q=80', 0, 0, 'available'),

-- Indian & Curry
(12, 4, 4, 'Butter Chicken Masala', 'Tender charred chicken thighs simmered in a velvety tomato, butter, cream, and fenugreek sauce.', 16.49, 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(13, 4, 4, 'Garlic Butter Tandoori Naan', 'Freshly baked clay-tandoor flatbread brushed with garlic butter and fresh coriander.', 3.99, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),
(14, 4, 4, 'Paneer Tikka Masala Bowl', 'Marinated cottage cheese cubes grilled to perfection, served over fragrant basmati rice.', 14.25, 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?auto=format&fit=crop&w=800&q=80', 1, 1, 'available'),
(15, 4, 4, 'Royal Chicken Hyderabadi Biryani', 'Fragrant long-grain basmati rice layered with spiced marinated chicken, saffron, and mint.', 15.99, 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),

-- Mexican & Tacos
(16, 5, 2, 'Authentic Carne Asada Tacos (3pcs)', 'Grilled marinated steak on warm handmade corn tortillas with diced onions, cilantro, and salsa verde.', 12.99, 'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(17, 5, 2, 'Loaded Cheesy Burrito Bowl', 'Seasoned black beans, cilantro lime rice, grilled chicken, fresh guacamole, and pico de gallo.', 13.50, 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=800&q=80', 0, 0, 'available'),

-- Healthy & Salad Bowls
(18, 6, 2, 'Avocado Grain Power Bowl', 'Organic quinoa, fresh avocado, roasted sweet potatoes, kale, chickpeas, and tahini dressing.', 13.99, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80', 1, 1, 'available'),
(19, 6, 6, 'Tropical Mango Smoothie Bowl', 'Blended mango and passionfruit topped with chia seeds, toasted coconut flakes, and berries.', 9.50, 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),

-- Chinese & Asian Dim Sum
(20, 7, 3, 'Steamed Pork & Shrimp Dim Sum', 'Traditional Cantonese Shumai dumplings served with soy ginger dip.', 11.50, 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?auto=format&fit=crop&w=800&q=80', 0, 1, 'available'),
(21, 7, 3, 'Spicy Sichuan Dan Dan Noodles', 'Wheat noodles in a fiery chili oil sesame sauce with minced pork and roasted peanuts.', 13.99, 'https://images.unsplash.com/photo-1552611052-33e04de081de?auto=format&fit=crop&w=800&q=80', 0, 0, 'available'),

-- Desserts & Sweets
(22, 8, 5, 'Classic Venetian Tiramisu', 'Layered espresso-soaked ladyfingers, velvety mascarpone cream, and dark cocoa dusting.', 7.99, 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=800&q=80', 1, 1, 'available'),
(23, 8, 5, 'Molten Lava Chocolate Cake', 'Warm dark chocolate cake with a molten lava center, served with vanilla bean ice cream.', 8.99, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80', 1, 1, 'available'),
(24, 8, 5, 'New York Strawberry Cheesecake', 'Rich graham cracker crust cheesecake topped with fresh glazed strawberries.', 8.50, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),
(25, 8, 5, 'Artisanal Fresh Fruit Tart', 'Butter pastry shell filled with vanilla custard and layered with kiwi, berries, and peach.', 7.50, 'https://images.unsplash.com/photo-1519869325930-281384150729?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),

-- Beverages
(26, 3, 6, 'Salted Caramel Milkshake', 'Creamy vanilla ice cream blended with salted caramel syrup, whipped cream, and pretzel bits.', 5.99, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),
(27, 6, 6, 'Iced Matcha Green Tea Latte', 'Premium Japanese Uji matcha whisked with oat milk and a touch of agave nectar.', 5.50, 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=800&q=80', 1, 0, 'available'),
(28, 5, 6, 'Sparkling Citrus Lemonade', 'Fresh squeezed lemons, lime juice, mint leaves, and sparkling soda water.', 4.50, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80', 1, 0, 'available');

-- --------------------------------------------------------
-- Table: cart
-- --------------------------------------------------------
CREATE TABLE `cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `food_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`food_id`) REFERENCES `food_items`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: orders
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT NOT NULL,
  `restaurant_id` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `delivery_fee` DECIMAL(6,2) NOT NULL DEFAULT 2.99,
  `tax_amount` DECIMAL(6,2) NOT NULL DEFAULT 1.50,
  `discount_amount` DECIMAL(6,2) NOT NULL DEFAULT 0.00,
  `final_amount` DECIMAL(10,2) NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `payment_method` ENUM('cod', 'card', 'upi', 'wallet') NOT NULL DEFAULT 'card',
  `payment_status` ENUM('pending', 'paid', 'failed') DEFAULT 'paid',
  `order_status` ENUM('pending', 'preparing', 'ready_for_delivery', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
  `special_instructions` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `restaurant_id`, `total_amount`, `delivery_fee`, `tax_amount`, `discount_amount`, `final_amount`, `delivery_address`, `payment_method`, `payment_status`, `order_status`, `special_instructions`, `created_at`) VALUES
(1, 'FH-2026-98101', 2, 1, 32.49, 1.99, 2.60, 0.00, 37.08, '742 Evergreen Terrace, Springfield', 'card', 'paid', 'delivered', 'Please leave at front doorstep.', NOW() - INTERVAL 2 DAY),
(2, 'FH-2026-98102', 2, 2, 33.49, 3.50, 2.68, 5.00, 34.67, '742 Evergreen Terrace, Springfield', 'card', 'paid', 'out_for_delivery', 'Ring doorbell upon arrival.', NOW() - INTERVAL 45 MINUTE),
(3, 'FH-2026-98103', 5, 3, 20.49, 2.49, 1.64, 0.00, 24.62, '89 Ocean Drive, Bay City', 'cod', 'pending', 'preparing', 'Extra ranch dip please!', NOW() - INTERVAL 20 MINUTE);

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `food_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(8,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`food_id`) REFERENCES `food_items`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `order_items` (`id`, `order_id`, `food_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 1, 1, 16.99, 16.99),
(2, 1, 2, 1, 15.50, 15.50),
(3, 2, 5, 1, 18.50, 18.50),
(4, 2, 6, 1, 14.99, 14.99),
(5, 3, 8, 1, 13.99, 13.99),
(6, 3, 9, 1, 6.50, 6.50);

-- --------------------------------------------------------
-- Table: delivery_assignments
-- --------------------------------------------------------
CREATE TABLE `delivery_assignments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `driver_id` INT NOT NULL,
  `status` ENUM('assigned', 'picked_up', 'delivered') DEFAULT 'assigned',
  `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `delivered_at` TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`driver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `delivery_assignments` (`id`, `order_id`, `driver_id`, `status`, `assigned_at`) VALUES
(1, 1, 4, 'delivered', NOW() - INTERVAL 2 DAY),
(2, 2, 4, 'assigned', NOW() - INTERVAL 30 MINUTE);

-- --------------------------------------------------------
-- Table: payments
-- --------------------------------------------------------
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `transaction_id` VARCHAR(100) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('success', 'failed', 'refunded') DEFAULT 'success',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`) VALUES
(1, 1, 'TXN-9021849102', 'card', 37.08, 'success'),
(2, 2, 'TXN-9021849103', 'card', 34.67, 'success');

-- --------------------------------------------------------
-- Table: wishlist
-- --------------------------------------------------------
CREATE TABLE `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `food_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`food_id`) REFERENCES `food_items`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `user_food_unique` (`user_id`, `food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `wishlist` (`user_id`, `food_id`) VALUES
(2, 1),
(2, 5),
(2, 8);

-- --------------------------------------------------------
-- Table: reviews
-- --------------------------------------------------------
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `restaurant_id` INT NOT NULL,
  `rating` INT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reviews` (`user_id`, `restaurant_id`, `rating`, `comment`) VALUES
(2, 1, 5, 'Best truffle pizza in town! Delivered piping hot and fresh.'),
(5, 2, 5, 'Unbelievable sushi quality. Super fast delivery!');

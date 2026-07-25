-- Seed data for FoodHub development & demonstration

INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`, `status`) VALUES
('Admin User', 'admin@foodhub.com', '$2y$12$eImiTXuWVxfM37uY4JANjO5yGZt7B5Kz3hG1UeO12345678901234', '1234567890', 'admin', 'active'),
('Gourmet Bistro Owner', 'bistro@foodhub.com', '$2y$12$eImiTXuWVxfM37uY4JANjO5yGZt7B5Kz3hG1UeO12345678901234', '9876543210', 'restaurant', 'active'),
('Delivery Rider John', 'rider@foodhub.com', '$2y$12$eImiTXuWVxfM37uY4JANjO5yGZt7B5Kz3hG1UeO12345678901234', '5551234567', 'delivery', 'active')
ON DUPLICATE KEY UPDATE `status`='active';

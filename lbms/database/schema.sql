CREATE DATABASE IF NOT EXISTS `lbms_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lbms_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS notifications, transactions, appointments, inquiries, verifications, documents, property_images, properties, users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_code VARCHAR(30) DEFAULT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Administrator', 'Broker', 'Seller', 'Buyer') NOT NULL,
    location VARCHAR(255) DEFAULT 'Dhaka, Bangladesh',
    service_area VARCHAR(255) DEFAULT 'Dhaka & Gazipur',
    notify_inquiries TINYINT(1) DEFAULT 1,
    notify_appointments TINYINT(1) DEFAULT 1,
    notify_reviews TINYINT(1) DEFAULT 1,
    status ENUM('Active', 'Suspended') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_ref VARCHAR(30) NOT NULL,
    owner_id INT NOT NULL,
    broker_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    category ENUM('Residential', 'Commercial', 'Agricultural') NOT NULL,
    listing_type ENUM('Sale', 'Rent') NOT NULL,
    size DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) DEFAULT 'Katha (Approx.)',
    price DECIMAL(15,2) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Draft', 'Pending Verification', 'Approved', 'Rejected', 'Sold', 'Archived') DEFAULT 'Pending Verification',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (broker_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 1,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    user_id INT NOT NULL,
    transaction_id INT DEFAULT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    doc_status VARCHAR(50) DEFAULT 'Pending review',
    is_private TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    verifier_id INT NOT NULL,
    status ENUM('Approved', 'Rejected') NOT NULL,
    rejection_reason TEXT,
    verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (verifier_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inquiry_ref VARCHAR(30) DEFAULT NULL,
    property_id INT NOT NULL,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    subject VARCHAR(255) DEFAULT 'Property Inquiry',
    message TEXT NOT NULL,
    broker_reply TEXT DEFAULT NULL,
    status ENUM('Unread', 'Awaiting reply', 'Replied') DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appt_ref VARCHAR(30) DEFAULT NULL,
    property_id INT NOT NULL,
    buyer_id INT NOT NULL,
    broker_id INT NOT NULL,
    appt_date DATE NOT NULL,
    appt_time VARCHAR(50) NOT NULL,
    notes VARCHAR(255) DEFAULT NULL,
    status ENUM('Pending', 'Confirmed', 'Rescheduled', 'Declined') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (broker_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tx_ref VARCHAR(40) NOT NULL,
    property_id INT NOT NULL,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    broker_id INT NOT NULL,
    status ENUM('Initiated', 'Negotiation', 'Pending', 'Completed', 'Cancelled') DEFAULT 'Initiated',
    amount DECIMAL(15,2) NOT NULL,
    commission_pct DECIMAL(5,2) DEFAULT 2.00,
    commission_amount DECIMAL(15,2) NOT NULL,
    completed_date DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (broker_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Demo Users (Password for all accounts is: password)
INSERT INTO users (id, account_code, full_name, email, phone, password_hash, role, location, service_area) VALUES
(1, 'ADM-0001', 'System Admin', 'admin@example.com', '+880 1700-000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Dhaka, Bangladesh', 'All Bangladesh'),
(2, 'BR-0024', 'Nadia Rahman', 'nadia.rahman@example.com', '+880 1712-345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Broker', 'Dhaka, Bangladesh', 'Dhaka & Gazipur'),
(3, 'BY-0102', 'Arif Hossain', 'arif.hossain@example.com', '+880 1812-345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Buyer', 'Dhaka, Bangladesh', 'Dhaka'),
(4, 'SL-0055', 'Mizanur Rahman', 'mizanur@example.com', '+880 1912-345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Seller', 'Dhaka, Bangladesh', 'Dhaka'),
(5, 'BY-0105', 'Samira Ahmed', 'samira@example.com', '+880 1612-345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Buyer', 'Gazipur, Bangladesh', 'Gazipur'),
(6, 'BY-0108', 'Rashid Karim', 'rashid@example.com', '+880 1512-345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Buyer', 'Savar, Dhaka', 'Savar');

-- Demo Properties
INSERT INTO properties (id, listing_ref, owner_id, broker_id, title, location, category, listing_type, size, unit, price, description, status) VALUES
(1, 'LB-1042', 4, 2, 'Residential Plot in Bashundhara', 'Bashundhara R/A, Dhaka', 'Residential', 'Sale', 5, 'Katha (Approx.)', 6000000, 'A 5 Katha residential plot in Bashundhara R/A, suitable for a future home or a long-term land purchase. Local road access and nearby residential development make it easy to plan a site visit. Ask the broker for exact boundaries, access details and current availability.', 'Approved'),
(2, 'LB-1043', 4, 2, 'Commercial Land', 'Tongi, Gazipur', 'Commercial', 'Sale', 10, 'Katha (Approx.)', 15000000, 'Prime 10 Katha commercial plot in Tongi industrial corridor with wide road frontage suitable for warehouse or commercial complex.', 'Approved'),
(3, 'LB-1044', 4, 2, 'Agricultural Land', 'Savar, Dhaka', 'Agricultural', 'Rent', 20, 'Katha (Approx.)', 15000, 'Fertile 20 Katha agricultural land in Savar available for seasonal or long-term lease with irrigation access.', 'Approved'),
(4, 'LB-1045', 4, 2, 'Residential Plot', 'Mirpur, Dhaka', 'Residential', 'Sale', 3, 'Katha (Approx.)', 3500000, 'Compact 3 Katha residential plot in Mirpur with clear access road and utility proximity.', 'Approved'),
(5, 'LB-1046', 4, 2, 'Residential Plot in Uttara', 'Sector 15, Uttara, Dhaka', 'Residential', 'Sale', 4, 'Katha (Approx.)', 4800000, 'A residential plot in Sector 15 with local road access. Contact the broker for exact boundaries and a site visit.', 'Draft'),
(6, 'LB-1047', 4, 2, 'Residential Land Keraniganj', 'Keraniganj, Dhaka', 'Residential', 'Sale', 6, 'Katha (Approx.)', 4200000, '6 Katha plot in developing residential zone in Keraniganj.', 'Pending Verification'),
(7, 'LB-1031', 4, 2, 'Residential Plot Purbachal', 'Purbachal, Dhaka', 'Residential', 'Sale', 5, 'Katha (Approx.)', 6000000, '5 Katha plot in Purbachal.', 'Sold');

INSERT INTO property_images (property_id, file_path, is_primary) VALUES
(1, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80', 1),
(2, 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80', 1),
(3, 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=800&q=80', 1),
(4, 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80', 1),
(5, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80', 1),
(6, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80', 1),
(7, 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80', 1);

INSERT INTO documents (property_id, user_id, file_name, file_path, doc_status) VALUES
(1, 2, 'ownership-record.pdf', 'assets/uploads/documents/ownership-record.pdf', 'Internal review record'),
(7, 2, 'sale-agreement.pdf', 'assets/uploads/documents/sale-agreement.pdf', 'Completed transaction');

INSERT INTO inquiries (inquiry_ref, property_id, sender_id, recipient_id, subject, message, status) VALUES
('INQ-086', 1, 3, 2, 'Road access and a site visit', 'Hello Nadia, I am interested in the 5 Katha residential plot in Bashundhara. Could you clarify the road access? I would also like to visit on 18 October at 10:00 AM. Please let me know if that works.', 'Unread'),
('INQ-085', 2, 5, 2, 'Is the asking price negotiable?', 'Hello, we are looking for commercial land in Tongi. Is the seller open to negotiation on the 1.5 Crore asking price?', 'Awaiting reply'),
('INQ-083', 3, 6, 2, 'Rental availability and visit', 'Is the 20 Katha plot in Savar available from next month?', 'Replied');

INSERT INTO appointments (appt_ref, property_id, buyer_id, broker_id, appt_date, appt_time, notes, status) VALUES
('APT-041', 1, 3, 2, '2026-10-18', '10:00 AM', 'INQ-086', 'Pending'),
('APT-039', 2, 3, 2, '2026-10-17', '11:00 AM', 'Accepted 12 Oct', 'Confirmed'),
('APT-040', 3, 6, 2, '2026-10-19', '02:00 PM', 'Was 16 Oct, 2 PM', 'Rescheduled');

INSERT INTO transactions (tx_ref, property_id, buyer_id, seller_id, broker_id, status, amount, commission_pct, commission_amount, completed_date) VALUES
('TX-2026-018', 7, 3, 4, 2, 'Completed', 6000000, 2.00, 120000, '2026-10-09');
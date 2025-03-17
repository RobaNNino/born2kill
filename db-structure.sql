-- Create the database tables

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    avatar VARCHAR(255) DEFAULT 'default.jpg',
    is_admin TINYINT(1) DEFAULT 0,
    vip_status VARCHAR(20) DEFAULT 'none',
    vip_expires TIMESTAMP NULL,
    level INT DEFAULT 1,
    points INT DEFAULT 0,
    packs INT DEFAULT 0
);

-- Forum categories
CREATE TABLE IF NOT EXISTS forum_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0
);

-- Forum topics
CREATE TABLE IF NOT EXISTS forum_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_pinned TINYINT(1) DEFAULT 0,
    is_locked TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES forum_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Forum posts
CREATE TABLE IF NOT EXISTS forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Server status
CREATE TABLE IF NOT EXISTS servers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    ip VARCHAR(50) NOT NULL,
    port INT NOT NULL,
    mode VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'offline',
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default data

-- Sample admin user (password: adminpass)
INSERT INTO users (username, password, email, is_admin) VALUES 
('AdminB2K', '$2y$10$wv0kZMIRCbOVxZeWVZZrqOYkBdQQE2owMob91N6PvGFOAgDZt85RO', 'admin@born2kill.eu', 1);

-- Sample regular users (password: userpass)
INSERT INTO users (username, password, email) VALUES 
('ZombieSlayer', '$2y$10$jAfiJYO1wKX6.DlJXQyNleEGT0iSw1sqs3p6lp2QzfMd09UwoXYDa', 'zombieslayer@example.com'),
('Veteran1337', '$2y$10$jAfiJYO1wKX6.DlJXQyNleEGT0iSw1sqs3p6lp2QzfMd09UwoXYDa', 'veteran1337@example.com'),
('GunMaster', '$2y$10$jAfiJYO1wKX6.DlJXQyNleEGT0iSw1sqs3p6lp2QzfMd09UwoXYDa', 'gunmaster@example.com'),
('ZombieHunter99', '$2y$10$jAfiJYO1wKX6.DlJXQyNleEGT0iSw1sqs3p6lp2QzfMd09UwoXYDa', 'zombiehunter99@example.com'),
('MapMaker', '$2y$10$jAfiJYO1wKX6.DlJXQyNleEGT0iSw1sqs3p6lp2QzfMd09UwoXYDa', 'mapmaker@example.com');

-- Insert forum categories
INSERT INTO forum_categories (name, description, sort_order) VALUES
('Announcements & News', 'Official announcements and news about the server', 1),
('General Discussion', 'Discuss anything related to the server', 2),
('Help & Support', 'Get help with any issues or questions', 3),
('Suggestions', 'Suggest new features or improvements', 4);

-- Insert sample topics
INSERT INTO forum_topics (category_id, user_id, title, is_pinned) VALUES
(1, 1, 'New Zombie Survival Mode Available!', 1),
(1, 5, 'Server Update: New Maps!', 0),
(1, 1, 'Weekend Event: Double XP!', 1),
(2, 4, 'What zombie class do you prefer?', 0),
(2, 2, 'Tips for new players', 0),
(2, 3, 'Best weapon to fight bosses', 0);

-- Insert sample posts
INSERT INTO forum_posts (topic_id, user_id, content) VALUES
(1, 1, 'We are excited to announce that our new Zombie Survival Mode is now available! This mode features endless waves of zombies with increasing difficulty. How long can you survive?'),
(1, 2, 'Just tried the new survival mode and it''s awesome! Made it to wave 15 before getting overwhelmed.'),
(2, 5, 'We have added 5 new maps to the server rotation: zm_toxic, zm_dust2_final, zm_ice_attack, zm_lila_panic, and zm_atomic. Enjoy!'),
(2, 3, 'The new zm_atomic map is incredible! Love the design and the multiple escape routes.'),
(3, 1, 'This weekend we will be running a Double XP event! From Friday 6PM to Sunday midnight, all players will receive double XP for kills and objectives.'),
(3, 4, 'Can''t wait for the Double XP weekend! Time to level up my character!'),
(4, 4, 'I''ve been playing with different zombie classes and I''m curious which one you guys prefer? I really like the Hunter class for its speed.'),
(4, 2, 'Definitely Tank class for me. The extra health makes a huge difference in surviving longer.'),
(4, 3, 'I prefer Leaper for the jumping ability. Great for catching humans off guard!'),
(5, 2, 'Here are some tips for new players: 1. Always stick with your team 2. Learn the maps and escape routes 3. Save your grenades for emergencies 4. Aim for headshots 5. Level up your character as soon as possible'),
(5, 4, 'Great tips! I would add: make sure to spend your points wisely and prioritize weapons that have high damage output.'),
(6, 3, 'I''ve been experimenting with different weapons against bosses. So far, I think the M249 is the most effective. What do you guys think?'),
(6, 2, 'I prefer the AWP for bosses. The high damage per shot is great, especially if you can land headshots consistently.');

-- Insert server information
INSERT INTO servers (name, ip, port, mode, status) VALUES
('[ZP] Born2Kill | Zombie Plague - #1', 'zp.born2kill.eu', 27015, 'Zombie Plague', 'online'),
('Born2Kill | Classic - #2', 'cs.born2kill.eu', 27015, 'Classic', 'online');

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  username VARCHAR(100) UNIQUE,
  password_hash VARCHAR(255),
  weight INT,
  height INT,
  bench_press INT,
  experience VARCHAR(20)
);

CREATE TABLE matches (
  match_id INT AUTO_INCREMENT PRIMARY KEY,
  challenger_name VARCHAR(100),
  opponent_name VARCHAR(100),
  fight_date DATE
);

INSERT INTO users (username, full_name, email, password_hash, weight, height, bench_press, experience)
VALUES
('user1@example.com', 'John Doe', 'user1@example.com', '$2y$10$examplehash', 180, 72, 225, 'Beginner'),
('user2@example.com', 'Jane Smith', 'user2@example.com', '$2y$10$examplehash', 150, 65, 150, 'Intermediate'),
('user3@example.com', 'Mike Brown', 'user3@example.com', '$2y$10$examplehash', 200, 75, 275, 'Advanced'),
('user4@example.com', 'Emily Davis', 'user4@example.com', '$2y$10$examplehash', 140, 63, 140, 'Beginner'),
('user5@example.com', 'Chris Wilson', 'user5@example.com', '$2y$10$examplehash', 190, 74, 240, 'Intermediate'),
('user6@example.com', 'Sarah Johnson', 'user6@example.com', '$2y$10$examplehash', 175, 68, 220, 'Beginner'),
('user7@example.com', 'David Lee', 'user7@example.com', '$2y$10$examplehash', 210, 78, 300, 'Advanced'),
('user8@example.com', 'Olivia Martinez', 'user8@example.com', '$2y$10$examplehash', 160, 66, 180, 'Intermediate'),
('user9@example.com', 'Ethan Taylor', 'user9@example.com', '$2y$10$examplehash', 185, 70, 230, 'Beginner'),
('user10@example.com', 'Ava Moore', 'user10@example.com', '$2y$10$examplehash', 135, 61, 120, 'Beginner'),
('user11@example.com', 'Noah King', 'user11@example.com', '$2y$10$examplehash', 205, 77, 280, 'Advanced'),
('user12@example.com', 'Isabella Scott', 'user12@example.com', '$2y$10$examplehash', 145, 64, 135, 'Intermediate'),
('user13@example.com', 'Liam Harris', 'user13@example.com', '$2y$10$examplehash', 180, 71, 215, 'Beginner'),
('user14@example.com', 'Sophia Walker', 'user14@example.com', '$2y$10$examplehash', 160, 67, 165, 'Intermediate'),
('user15@example.com', 'Mason Young', 'user15@example.com', '$2y$10$examplehash', 195, 73, 250, 'Intermediate'),
('user16@example.com', 'Mia Allen', 'user16@example.com', '$2y$10$examplehash', 150, 62, 140, 'Beginner'),
('user17@example.com', 'Jacob Hernandez', 'user17@example.com', '$2y$10$examplehash', 175, 69, 210, 'Beginner'),
('user18@example.com', 'Amelia Lewis', 'user18@example.com', '$2y$10$examplehash', 165, 66, 180, 'Intermediate'),
('user19@example.com', 'James Robinson', 'user19@example.com', '$2y$10$examplehash', 185, 72, 225, 'Intermediate'),
('user20@example.com', 'Charlotte Clark', 'user20@example.com', '$2y$10$examplehash', 155, 65, 150, 'Beginner');

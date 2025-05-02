# Software-Engineering-Website

**Group Members:** Rowan Cahill (rcahill@wesleyan.edu) and Alex Glotzer (aglotzer@wesleyan.edu)

---

## Project Description

**Repo contains:**  
A file _index.html_ which serves as the landing page, a file _indexStyle.css_ which is the styling for the landing page, a file _SignUp.html_ which houses the signup form, a file _SignUpStyle.css_ which is the styling for the signup and login pages, and an images folder which has the files for the images that appear on the website. We also have a README, license, and .gitignore. _terms.html_ contains the terms and conditions.  

As of Friday, March 7, at 10:00 AM, the repo now also contains:

- _login.php_: lets users log in and access _dashboard.html_ (styled by _dashboardStyle.css_)
- _logout.php_: handles logout
- _db_implement.php_: connects to the InfinityFree-hosted database
- _register.php_: signs users up
- **CRUD**:
  - Create: _add_match.php_
  - Read: _view_matches.php_
  - Update: _edit_match.php_
  - Delete: _delete_match.php_

Once logged in, users can:
- View other users
- Schedule fights (random date 30–90 days out)
- Reschedule own matches
- View all upcoming matches

---

## Live Deployment

**Hosted via InfinityFree:**  
[https://fightclubcomp333.infinityfreeapp.com](https://fightclubcomp333.infinityfreeapp.com)

---

## Running the App Locally

### Backend Setup (XAMPP)

#### Requirements:
- XAMPP (Apache + MySQL)
- phpMyAdmin

#### Steps:

1. Install XAMPP  
2. Clone repo to `htdocs/`:

   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs
   git clone https://github.com/YOUR_USERNAME/Software-Engineering-Website.git
   ```

3. Start Apache and MySQL in XAMPP  
4. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)  
5. Create a database called `app_db`  
6. Import database schema:  
   - Go to `Import` → select `db_schema.sql` if provided  
7. Update `/api/db_implement.php` with your credentials:

   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $database = "app_db";
   ```

8. Open in browser:  
   [http://localhost/Software-Engineering-Website/index.html](http://localhost/Software-Engineering-Website/index.html)

---

### Mobile App Setup (React Native + Expo)

#### Requirements:
- Node.js + npm
- Expo CLI
- Android Studio (or Expo Go on real device)

#### Steps:

1. Navigate to React Native folder:

   ```bash
   cd fightclub-app
   ```

2. Install dependencies:

   ```bash
   npm install
   npm install react-native-deck-swiper
   ```

3. Update `/services/api.js` with your local IP:

   ```js
   const API_BASE_URL = "http://YOUR_LOCAL_IP/Software-Engineering-Website/api";
   ```

4. Run emulator:

   ```bash
   npx expo start --android
   ```

---

## API Endpoints

| Endpoint               | Method | Description                  |
|------------------------|--------|------------------------------|
| `/api/register.php`    | POST   | Register new user            |
| `/api/login.php`       | POST   | Log in user                  |
| `/api/matchmaking.php` | GET    | Get list of potential fights |
| `/api/swipe.php`       | POST   | Submit swipe result          |
| `/api/matches.php`     | GET    | Get list of scheduled fights |

---

## Testing Instructions

## 🧪 Backend Testing Instructions

This project uses **PHPUnit** to test the backend functionality. Follow these instructions to set up and run the tests in your local development environment.

---

### ✅ Prerequisites

Before running the tests, ensure the following:

1. You are running a **local Apache server with PHP** (e.g., XAMPP).
2. You have **MySQL** running locally and your database is created with the required schema.
3. You have **Composer installed** globally or locally.

---

### 📂 1. Clone the repository and install dependencies

```bash
git clone https://github.com/your-username/your-repo-name.git
cd Software-Engineering-Website
composer install
🗄️ 2. Database setup
Make sure your MySQL database contains a users table with the following schema:

sql
Copy
Edit
CREATE TABLE users (
    username VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    weight INT NOT NULL,
    height INT NOT NULL,
    bench_press INT NOT NULL,
    experience ENUM('Beginner','Intermediate','Advanced') NOT NULL
);
👤 3. Required test user
Before running the tests, insert the following user into your database (replace your_db_name with your actual database name):

sql
Copy
Edit
INSERT INTO users (username, email, full_name, password_hash, weight, height, bench_press, experience)
VALUES (
    '1234@test.com',
    '1234@test.com',
    'Alex Glotzer',
    '$2y$10$8pm77nR6VMI9R3i3rk3AUeAwDD83FK7Q7aOZ4yFfW8fOTKeBYJbny', -- hashed '1234567890'
    180, 72, 225, 'Beginner'
);
✅ The hashed password corresponds to plaintext password 1234567890.

💡 If you prefer to hash manually, use PHP:

php
Copy
Edit
echo password_hash('1234567890', PASSWORD_DEFAULT);
🖥️ 4. Configure server & API endpoints
Ensure your server is running at http://localhost.

All API files (e.g., login.php, signup.php) should be located under /api/ inside your project.

Confirm db_implement.php points to your local database credentials:

php
Copy
Edit
$host = "localhost";
$user = "root";
$password = "";
$database = "app_db";
🏃 5. Run PHPUnit tests
Inside the project folder, run:

bash
Copy
Edit
/Applications/XAMPP/xamppfiles/bin/php ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
If PHP is globally installed, you can also use:

bash
Copy
Edit
php ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
✅ This will execute the test suite located in /tests/UserTest.php.

📝 Test cases
Test	Description
testGet_UserList	GET /api/users.php returns HTTP 200
testPost_CreateUser	POST /api/signup.php returns HTTP 201 for valid signup
testPost_LoginUser	POST /api/login.php returns HTTP 201 for valid login
testPost_FailedLogin	POST /api/login.php returns HTTP 401 for invalid login

⚠️ Common issues
Ensure your Apache server is running before testing.

Ensure the API endpoints are accessible at http://localhost/api/*.php.

Ensure the required user 1234@test.com exists in the users table.

✅ After following these instructions, you should be able to successfully run the PHPUnit test suite locally!

🏁 Example test run output
bash
Copy
Edit
PHPUnit 11.5.x by Sebastian Bergmann and contributors.

....                                                                4 / 4 (100%)

Time: 00:00.045, Memory: 8.00 MB

OK (4 tests, 4 assertions)
yaml
Copy
Edit

---

✅ This Markdown will render beautifully in GitHub or any Markdown viewer! Let me know if you’d like to integrate additional sections.





You said:
Provide this as one contiguous piece of markdown text for seamless copying


ChatGPT said:
Absolutely! Here's the entire documentation in one seamless Markdown block for easy copy-pasting into your README.md:

markdown
Copy
Edit
## 🧪 Backend Testing Instructions

This project uses **PHPUnit** to test the backend functionality. Follow these instructions to set up and run the tests in your local development environment.

---

### ✅ Prerequisites

Before running the tests, ensure the following:

1. You are running a **local Apache server with PHP** (e.g., XAMPP).
2. You have **MySQL** running locally and your database is created with the required schema.
3. You have **Composer installed** globally or locally.

---

### 📂 1. Clone the repository and install dependencies

```bash
git clone https://github.com/your-username/your-repo-name.git
cd Software-Engineering-Website
composer install
🗄️ 2. Database setup
Make sure your MySQL database contains a users table with the following schema:

sql
Copy
Edit
CREATE TABLE users (
    username VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    weight INT NOT NULL,
    height INT NOT NULL,
    bench_press INT NOT NULL,
    experience ENUM('Beginner','Intermediate','Advanced') NOT NULL
);
👤 3. Required test user
Before running the tests, insert the following user into your database (replace your_db_name with your actual database name):

sql
Copy
Edit
INSERT INTO users (username, email, full_name, password_hash, weight, height, bench_press, experience)
VALUES (
    '1234@test.com',
    '1234@test.com',
    'Alex Glotzer',
    '$2y$10$8pm77nR6VMI9R3i3rk3AUeAwDD83FK7Q7aOZ4yFfW8fOTKeBYJbny', -- hashed '1234567890'
    180, 72, 225, 'Beginner'
);
✅ The hashed password corresponds to plaintext password 1234567890.

💡 If you prefer to hash manually, use PHP:

php
Copy
Edit
echo password_hash('1234567890', PASSWORD_DEFAULT);
🖥️ 4. Configure server & API endpoints
Ensure your server is running at http://localhost.

All API files (e.g., login.php, signup.php) should be located under /api/ inside your project.

Confirm db_implement.php points to your local database credentials:

php
Copy
Edit
$host = "localhost";
$user = "root";
$password = "";
$database = "app_db";
🏃 5. Run PHPUnit tests
Inside the project folder, run:

bash
Copy
Edit
/Applications/XAMPP/xamppfiles/bin/php ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
If PHP is globally installed, you can also use:

bash
Copy
Edit
php ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
✅ This will execute the test suite located in /tests/UserTest.php.

📝 Test cases
Test	Description
testGet_UserList	GET /api/users.php returns HTTP 200
testPost_CreateUser	POST /api/signup.php returns HTTP 201 for valid signup
testPost_LoginUser	POST /api/login.php returns HTTP 201 for valid login
testPost_FailedLogin	POST /api/login.php returns HTTP 401 for invalid login

⚠️ Common issues
Ensure your Apache server is running before testing.

Ensure the API endpoints are accessible at http://localhost/api/*.php.

Ensure the required user 1234@test.com exists in the users table.

✅ After following these instructions, you should be able to successfully run the PHPUnit test suite locally!

🏁 Example test run output
bash
Copy
Edit
PHPUnit 11.5.x by Sebastian Bergmann and contributors.

....                                                                4 / 4 (100%)

Time: 00:00.045, Memory: 8.00 MB

OK (4 tests, 4 assertions)
yaml
Copy
Edit

---

✅ Let me know if you'd like this tailored for a specific GitHub repo name or folder! Copy-paste ready for your `README.md`.

### Postman

1. Log in via `POST /api/login.php`
2. Get opponents via `GET /api/matchmaking.php`
3. Record match interest via `POST /api/swipe.php`

### PHPUnit

If you have PHPUnit installed:

```bash
./vendor/bin/phpunit tests/UserTest.php
```

Should return: `OK (3 tests)`

---

## Database Schema

### `users` Table

```sql
CREATE TABLE users (
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL,
    weight INT NOT NULL,
    height INT NOT NULL,
    bench_press INT NOT NULL,
    experience VARCHAR(255) NOT NULL
);
```

### `matches` Table

```sql
CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    challenger_name VARCHAR(255) NOT NULL,
    opponent_name VARCHAR(255) NOT NULL,
    fight_date DATE NOT NULL
);
```

---

## Work Distribution

- **HW1:** Rowan/Alex 53/47 (Rowan came up with the idea, but work distribution was very similar)
- **HW2:** Rowan/Alex 47/53 (Alex did a bit more PHP testing)
- **HW3:** 50/50 — Alex did more testing, Rowan fixed app API in emulator

We consulted ChatGPT for help with understanding HTML, CSS, and Java, as well as debugging PHP files.

---

## Screenshots

**Alex XAMPP Screenshot:**  
![Screenshot 2025-03-06 at 8 02 37 PM](https://github.com/user-attachments/assets/96d15449-60e7-4a0b-8484-ed472cc614c4)

**Rowan XAMPP Screenshot:**  
<img width="1470" alt="Screenshot of Rowan's XAMPP phpMyAdmin" src="https://github.com/user-attachments/assets/d8794f91-06e3-4da2-b669-fc040d098515" />

---

### Rowan Postman Testing

<img width="1258" alt="Screenshot 1 - Rowan's Postman" src="https://github.com/user-attachments/assets/0ed2453f-ceb4-4f8f-b2bb-c103f847947f" />
<img width="1270" alt="Screenshot 2 - Rowan's Postman" src="https://github.com/user-attachments/assets/552f2cf2-e1a7-48a3-bd23-b640d27fdbde" />
<img width="1278" alt="Screenshot 3 - Rowan's Postman" src="https://github.com/user-attachments/assets/90013c3d-59fb-4a25-91d0-d1a7829fcf75" />

---

### Alex Postman Testing (Comprehensive)

<img width="1319" alt="Screenshot 2025-04-09 at 11 36 57 AM" src="https://github.com/user-attachments/assets/609c9788-e887-4488-9f0c-9e62e9cb5af8" />
<img width="1319" alt="Screenshot 2025-04-09 at 11 37 58 AM" src="https://github.com/user-attachments/assets/ee620263-a300-4a0d-82ba-9fd35d619dd5" />
<img width="1318" alt="Screenshot 2025-04-09 at 11 38 37 AM" src="https://github.com/user-attachments/assets/9b4dfeac-ae94-4cc8-a2b8-a5f619cd236b" />
<img width="1320" alt="Screenshot 2025-04-09 at 11 39 31 AM" src="https://github.com/user-attachments/assets/361c766e-b99c-4ca1-94ec-47cc8f2a6ffa" />
<img width="1319" alt="Screenshot 2025-04-09 at 11 57 23 AM" src="https://github.com/user-attachments/assets/74451adb-b888-4336-b116-22609ab010fe" />
<img width="1316" alt="Screenshot 2025-04-09 at 12 00 19 PM" src="https://github.com/user-attachments/assets/1b05ad93-d575-4e96-aaff-0ae32905db5d" />

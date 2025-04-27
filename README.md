# Software-Engineering-Website

**Group Members:** Rowan Cahill (rcahill@wesleyan.edu) and Alex Glotzer (aglotzer@wesleyan.edu)

---

**Repo contains:**  
A file _index.html_ which serves as the landing page, a file _indexStyle.css_ which is the styling for the landing page, a file _SignUp.html_ which houses the signup form, a file _SignUpStyle.css_ which is the styling for the signup and login pages, and an images folder which has the files for the images that appear on the website. We also have a README, license, and .gitignore. _terms.html_ contains the terms and conditions.  
As of Friday, March 7, at 10:00 AM, the repo now also contains a _login.php_, which allows users to access their dashboard in _dashboard.html_ (with the CSS page _dashboardStyle.css_), and a _logout.php_, which gives a logout page. _db_implement.php_ manages the database of emails, passwords, and other information and connects it to InfinityFree. _register.php_ signs a user up. CRUD is implemented to manage users with:

- **Create:** _add_match.php_
- **Read:** _view_matches.php_
- **Update:** _edit_match.php_
- **Delete:** _delete_match.php_

Once logging in, the user will be able to see all other users of the website. You may schedule a fight with another person, and it will choose a date for you in the next 30–90 days. You may reschedule if you are involved in the match. We also give a list of all other matches so all users can show up and watch.

---

## Implementation

The Website is implemented using InfinityFree:  
[https://fightclubcomp333.infinityfreeapp.com](https://fightclubcomp333.infinityfreeapp.com)

One will enter their email which will serve as their username in the database. There are two database tables: a users table, which organizes the information of all the users, and a matches table, which organizes a user's top five most suitable matches.

---

## Local Setup Instructions

This project contains:
- A PHP/MySQL backend with a REST API
- A React Native mobile frontend (built with Expo)
- A working relational database with users and matches

Follow the steps below to run the project locally.

---

### Local Web Instructions

#### Setup (XAMPP + MySQL)

**Requirements:**
- XAMPP
- PHP + MySQL
- phpMyAdmin

**Steps:**
1. Start XAMPP and run Apache and MySQL
2. Clone the GitHub repo
3. Move the `Software-Engineering-Website/` folder to:  
   `/Applications/XAMPP/xamppfiles/htdocs/`
4. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
5. Create a database called `app_db`
6. Run the provided SQL to create and populate the following tables:

**users**
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

**matches**
```sql
CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    challenger_name VARCHAR(255) NOT NULL,
    opponent_name VARCHAR(255) NOT NULL,
    fight_date DATE NOT NULL
);
```

**OPTIONAL sample users**
```sql
CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    challenger_name VARCHAR(255) NOT NULL,
    opponent_name VARCHAR(255) NOT NULL,
    fight_date DATE NOT NULL
);
```

7. Open and verify this works:  
   [http://localhost/Software-Engineering-Website/index.html](http://localhost/Software-Engineering-Website/index.html)

8. Make sure your `db_implement.php` contains the correct local credentials:
```php
$host = "localhost";
$user = "root";
$password = "";
$database = "app_db";
```

---

### React Native Instructions

**Requirements:**
- Node.js and npm
- Expo CLI
- Android Studio (for emulator) or Expo Go app (for real phone testing)

**Steps:**
1. Open terminal from the `Software-Engineering-Website` folder
2. Navigate to the React Native project folder:
```bash
cd fightclub-app
```
3. Install dependencies:
```bash
npm install
```
4. Edit `api.js` with your local IP address  
   Replace `http://localhost/...` with your actual local IP, e.g.:
```javascript
const API_BASE_URL = "http://192.168.X.X/Software-Engineering-Website/api";
```
5. Start the Expo development server:
```bash
npx expo start
```
6. Run the app:
   - Open Android Studio, start an emulator, and press "Run on Android device"
   - OR scan the QR code using the Expo Go app on your real phone

The mobile app will now let you register, log in, view matches, and create/edit/delete fights — all using the REST API.

---

## Work distribution

- **HW1:** Rowan/Alex 53/47 (Rowan came up with the idea, but work distribution was very similar)
- **HW2:** Rowan/Alex 47/53 (Alex did a bit more PHP testing)
- **HW3:** 50/50 (Alex did more testing, Rowan fixed app API in emulator)

We consulted ChatGPT for help with understanding HTML, CSS, and Java, as well as debugging PHP files.

---

## Screenshots

**Alex XAMPP Screenshot:**  
![Screenshot 2025-03-06 at 8 02 37 PM](https://github.com/user-attachments/assets/96d15449-60e7-4a0b-8484-ed472cc614c4)

**Rowan XAMPP Screenshot (sorry it's late!):**  
<img width="1470" alt="Screenshot of Rowan's XAMPP phpMyAdmin" src="https://github.com/user-attachments/assets/d8794f91-06e3-4da2-b669-fc040d098515" />

---

**Rowan Sample Postman Testing:**

<img width="1258" alt="Screenshot 1 - Rowan's Postman" src="https://github.com/user-attachments/assets/0ed2453f-ceb4-4f8f-b2bb-c103f847947f" />
<img width="1270" alt="Screenshot 2 - Rowan's Postman" src="https://github.com/user-attachments/assets/552f2cf2-e1a7-48a3-bd23-b640d27fdbde" />
<img width="1278" alt="Screenshot 3 - Rowan's Postman" src="https://github.com/user-attachments/assets/90013c3d-59fb-4a25-91d0-d1a7829fcf75" />

---

**Alex Postman Testing (Comprehensive):**

<img width="1319" alt="Screenshot 2025-04-09 at 11 36 57 AM" src="https://github.com/user-attachments/assets/609c9788-e887-4488-9f0c-9e62e9cb5af8" />
<img width="1319" alt="Screenshot 2025-04-09 at 11 37 58 AM" src="https://github.com/user-attachments/assets/ee620263-a300-4a0d-82ba-9fd35d619dd5" />
<img width="1318" alt="Screenshot 2025-04-09 at 11 38 37 AM" src="https://github.com/user-attachments/assets/9b4dfeac-ae94-4cc8-a2b8-a5f619cd236b" />
<img width="1320" alt="Screenshot 2025-04-09 at 11 39 31 AM" src="https://github.com/user-attachments/assets/361c766e-b99c-4ca1-94ec-47cc8f2a6ffa" />
<img width="1319" alt="Screenshot 2025-04-09 at 11 57 23 AM" src="https://github.com/user-attachments/assets/74451adb-b888-4336-b116-22609ab010fe" />
<img width="1316" alt="Screenshot 2025-04-09 at 12 00 19 PM" src="https://github.com/user-attachments/assets/1b05ad93-d575-4e96-aaff-0ae32905db5d" />

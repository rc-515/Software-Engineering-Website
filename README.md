# Software-Engineering-Website
**Group Members:** Rowan Cahill (rcahill@wesleyan.edu) and Alex Glotzer (aglotzer@wesleyan.edu)

**Repo contains:** A file _index.html_  which serves as the landing page, a file _indexStyle.css_  which is the styling for the landing page, a file _SignUp.html_ which houses the signup form, a file _SignUpStyle.css_ which is the styling for the signup and login pages, and an images folder which has the files for the images that appear on the website. We also have a readme, license, and .gitignore. _terms.html_ contains the terms and conditions.
As of Friday, March 7, at 10:00 AM, the repo now also contains a _login.php_, which allows users to access their dashboard in _dashboard.html_ (with the CSS page _dashboardStyle.css_), and a _logout.php_, which gives a logout page. _db_implement.php_ manages the database of emails, passwords, and other information. CRUD is implemented to manage users with Create: _add_match.php_, Read: _view_matches.php_, Update: _edit_match.php_, and Delete: _delete_match.php_. _register.php_ signs a user up. _db_implement.php_ connects it to InfinityFree.

Once logging in, the user will be able to see all other users of the website. You may schedule a fight with another person, and it will choose a date for you in the next 30-90 days. You may reschedule if you are involved in the match. We also give a list of all other matches so all users can show up and watch.

**Implementation:** The Website is implemented using InfinityFree: (https://fightclubcomp333.infinityfreeapp.com)

One will enter their email which will serve as their username in the database. There are two database tables: a users table, which organizes the information of all the users, and a matches table, which organizes a user's top five most suitable matches.

**Work distribution:**
- HW1: Rowan/Alex 53/47 (Rowan came up with the idea, but work distribution was very similar)
- HW2: Rowan/Alex 47/53 (Alex did a bit more php testing)

We consulted ChatGPT for help with understanding HTML and CSS, as well as debugging php files.

Alex XAMPP Screenshot: ![Screenshot 2025-03-06 at 8 02 37 PM](https://github.com/user-attachments/assets/96d15449-60e7-4a0b-8484-ed472cc614c4)

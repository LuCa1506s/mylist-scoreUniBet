# 🎓 ScoreUniBet

ScoreUniBet is a web application designed to make exam preparation more engaging and fun.  
Users can create groups, place bets on exam grades, and admins can close exams with transparent and automatic payout logic.

https://supergestional.altervista.org/scoreUniBet/public/login.php

---

## 🚀 Key Features

- **Group Management**
  - Create groups with unique invite tokens.
  - View all groups you belong to.
  - Interactive table with a "Copy Token" button.

- **Participants**
  - Display group members with roles (admin, member).
  - Admin can remove participants directly from the table.

- **Betting**
  - Place bets on exam grades.
  - Choose the amount of credits to wager.
  - Modern and intuitive interface.

- **Exam Closure (Admin Only)**
  - Admin enters the final grade.
  - Automatic payout calculation:
    - Exact match → credits doubled.
    - Within ±2 points → credits increased by 75%.
    - Otherwise → loss.
  - User balance updated and transactions recorded.

- **Authentication**
  - Secure login, registration, and logout.
  - Dedicated styling with `auth.css`.

---

## 🛠️ Technologies Used

- **Backend:** PHP (OOP), MySQL/MariaDB
- **Frontend:** HTML5, CSS3 (custom styles), JavaScript
- **Database:** Normalized tables (`users`, `groups`, `group_members`, `bets`, `transactions`)
- **Architecture:** Simplified MVC with centralized functions in `Db.php`

---

## 🗄️ Database Schema (SQL)

```sql
-- Database: scoreunibet

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    credits DECIMAL(10,2) DEFAULT 0
);

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    owner_id INT NOT NULL,
    invite_token VARCHAR(64) NOT NULL UNIQUE,
    status ENUM('open','closed') DEFAULT 'open',
    final_grade VARCHAR(4),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    colore VARCHAR(20),
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('member','admin') DEFAULT 'member',
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    target_user_id INT NOT NULL,
    predicted_grade VARCHAR(4) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    is_winner TINYINT(1) DEFAULT 0,
    payout DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (target_user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('bet_win','bet_loss') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    related_bet_id INT,
    group_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (related_bet_id) REFERENCES bets(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
);
```

---

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/scoreUniBet.git
   ```

2. Set up the MySQL/MariaDB database:
   - Create a database named `scoreunibet`.
   - Import the SQL schema provided above.

3. Configure the connection in `includes/db.php`:
   ```php
   private $host = "localhost";
   private $user = "root";
   private $pass = "password";
   private $dbname = "scoreunibet";
   ```

4. Start a local server (XAMPP, MAMP, or PHP built-in):
   ```bash
   php -S localhost:8000
   ```

5. Open [http://localhost:8000/public/index.php](http://localhost:8000/public/index.php).

---

## 📌 Roadmap
- [ ] Add real-time notifications (WebSocket).
- [ ] Improve credit management with detailed history.
- [ ] Implement REST API for mobile integration.
- [ ] Add automated tests (PHPUnit).

---

## 📜 License
This project is licensed under the MIT License.  
You are free to use, modify, and distribute it, provided you keep the original license.

---

## 👨‍💻 Author
**Gianluca** – Full-stack developer and project owner of ScoreUniBet.  
Passionate about scalable web apps, modern UI/UX, and transparent game logic.
```

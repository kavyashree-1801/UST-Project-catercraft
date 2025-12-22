## Catercraft
CaterCraft is a web-based catering management system designed to simplify event catering operations. 
It allows users to explore catering services, place orders, and manage bookings, while admins can efficiently manage menus, orders, users, and customer inquiries. 
The application is built with PHP, MySQL, HTML, CSS, and JavaScript, following a clean and responsive design.

## 📸 Demo / Live Link
🔗 Live Preview: https://github.com/user-attachments/assets/3d0c3db4-a7b5-4311-a080-51afbf7e3e18
📂 Repository Link: https://github.com/kavyashree-1801/UST-Project-catercraft.git

## ✨ Features
User Features

User registration & secure login
Browse catering menus & packages
View menu details with pricing
Place catering orders
Contact & feedback forms
Responsive UI for desktop & mobile

Admin Features

Admin login with role-based access
Manage users (view / block / delete)
Add, update & delete catering menus
Manage orders
View contact messages & feedback
Dashboard overview for quick insights

## 🧰 Tech Stack

| Technology   | Purpose                                |
| ------------ | -------------------------------------- |
| PHP          | Backend logic & server-side scripting  |
| MySQL        | Database management                    |
| HTML5        | Page structure                         |
| CSS3         | Styling & responsive design            |
| JavaScript   | Client-side validation & interactivity |
| PHP Sessions | Authentication & authorization         |
| Bootstrap    | Responsive UI components & layout      |

 ##  ⚙️ Installation & Setup

1.Clone or Download the Project
git clone https://github.com/your-username/catercraft.git

2.Move Project to Server Directory
XAMPP: htdocs/catercraft/

3.Create Database
- Open phpMyAdmin
- Create a database named catercraft
- Import the provided .sql file

4. Configure Database
- Update config.php:
 $con = mysqli_connect("localhost", "root", "", "catercraft");

5.Run the Project
http://localhost/catercraft

##  📁 Folder structure

catercraft/
│
├── css/                           # Stylesheets
│   ├── checkout.css
│   ├── contact.css
│   ├── feedback.css
│   ├── forgot_password.css
│   ├── homepage.css
│   ├── login.css
│   ├── order_success.css
│   ├── orders.css
│   ├── product.css
│   ├── profile.css
│   └── reset_password.css
│
├── js/                            # JavaScript files
│   ├── auth.js
│   ├── checkout.js
│   ├── contact.js
│   ├── feedback.js
│   ├── forgot_password.js
│   ├── order_success.js
│   ├── orders.js
│   ├── product.js
│   ├── profile.js
│   └── reset_password.js
│
├── images/                        # Images & uploads
│
├── api/                           # Backend API / AJAX handlers
│   ├── auth.php                   # Login / signup authentication
│   ├── cart_api.php               # Cart operations (add, update, remove)
│   ├── cart_items_api.php         # Fetch cart items
│   ├── contact_submit.php         # Handle contact form submission
│   ├── feedback_submit.php        # Handle feedback submission
│   ├── forgot_password_submit.php # Verify email / security answer
│   ├── orders_api.php             # Order placement & retrieval
│   ├── profile_api.php            # User profile operations
│   └── update_password.php        # Reset / update password
│
├── homepage.php                   # Home page
├── login.php                      # User login
├── signup.php                     # User registration
├── logout.php                     # Logout
│
├── product.php                     # Catering menu listing
├── my_orders.php                  # User orders
├── order_success.php              # Order confirmation page
├── profile.php                    # User profile
├── forgot_password.php            # Forgot password
├── reset_password.php             # Reset password
├── contact.php                    # Contact form
├── feedback.php                   # Feedback form
│
├── admin/
│   ├── homepage.php              # Admin homepage
│   ├── manage_users.php           # Manage users
│   ├── manage_menu.php            # Manage catering menu
│   ├── manage_orders.php          # Manage orders
│   ├── manage_contact.php         # View contact messages
│   └── manage_feedback.php        # View feedback
|   └── manage_payments.php        # View payments
│
├── config.php                     # Database configuration
└── README.md                      # Project documentation

##  🔒 Security Features

- Password hashing
- Session-based authentication
- Role-based access control
- Prepared SQL statements to prevent SQL injection

##  🧠 Future Enhancements

- Online payment integration
- Email notifications for order delivery
- Order status tracking
- Multi-vendor catering support
- Advanced analytics dashboard

## 📧 Contact

**Kavyashree D M **  
📩 Email: kavyashreedmmohan@gmail.com    

## ⭐ Support

If you like this project, please ⭐ the repo!

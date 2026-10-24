# SESA_COUESE_PROJECT-LBMS-

# LBMS (Land Brokerage Management System)
**Software Requirements Specification (SRS) Class Project**  
**Institution:** University of Information Technology and Sciences (UITS)  
**Project Team:** Farjana Ferdosy, Mahmudul Hasan, Mahamud ul Alam Supto  
**SRS Version:** 2.0 (September 2026)

---

Project Overview
The **Land Brokerage Management System (LBMS)** is a centralized, web-based platform designed to streamline real estate and land brokerage operations. It eliminates reliance on fragmented phone calls, paper records, and informal messaging by unifying buyers, property sellers/owners, verified brokers, and administrators into a single secure digital hub.

---

Technology Stack
* **Frontend:** HTML5, CSS3, Bootstrap 5 (Responsive UI), Vanilla JavaScript
* **Backend:** PHP 8.x (Procedural with PDO Architecture)
* **Database:** MySQL 8.x (Relational database management system with strict foreign key constraints)
* **Environment:** XAMPP (Apache web server & MySQL database)

---

Project Directory Structure
```text
lbms/
│── index.php                # Public Homepage with featured listings & search bar
│── properties.php           # Advanced property search & filtering page
│── property_detail.php      # Detailed view + Inquiry & Site Visit request forms
│── about.php                # About LBMS, user roles guide, & project info
│── login.php                # Secure user sign-in portal
│── register.php             # Role-based account registration (Buyer/Seller/Broker)
│── dashboard.php            # Unified role-based workspace overview
│── add_property.php         # Broker/Seller listing submission form
│── inquiries.php            # Client communication & appointment management center
│── verification.php         # Administrative & Broker listing approval workflow
│── profile.php              # User profile settings & notification preferences
│
├── config/
│   └── db.php               # Secure PDO database connection configuration
│
├── includes/
│   ├── header.php           # Shared responsive navbar & role sub-navigation
│   └── footer.php           # Shared site footer
│
├── actions/
│   ├── auth_action.php      # Authentication handler (Login, Register, Logout)
│   ├── property_action.php  # Property CRUD & file upload processor
│   └── interaction_action.php # Handles inquiries, appointment bookings, & approvals
│
├── assets/
│   ├── css/
│   │   └── style.css        # Custom UI styling (Navy #111e36 & Gold #c9a227 palette)
│   └── uploads/
│       ├── images/          # Uploaded property photos directory
│       └── documents/       # Restricted private document directory
│
└── database/
    └── schema.sql           # Complete MySQL database export script

# InkFlow Blog Application - Backend

Complete PHP/MySQL backend API for the InkFlow Blog Application.

## 📁 Directory Structure

```
backend/
├── api/
│   ├── auth/
│   │   ├── signup.php      # User registration endpoint
│   │   └── login.php       # User login endpoint
│   ├── blogs/
│   │   ├── create.php      # Create new blog post
│   │   ├── get.php         # Retrieve blog posts
│   │   ├── update.php      # Update blog post
│   │   └── delete.php      # Delete blog post
│   ├── users/
│   │   ├── profile.php     # Get user profile
│   │   └── update-profile.php  # Update user profile
│   ├── interests/
│   │   └── get.php         # Get all interests/categories
│   └── search/
│       └── index.php       # Global search functionality
├── includes/
│   └── functions.php       # Shared helper functions
├── db/
│   └── db.php             # Database connection (legacy)
├── config.php             # Database configuration
├── index.php              # API overview page
├── API_DOCUMENTATION.md   # Complete API documentation
└── README.md             # This file
```

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- XAMPP or similar local server

### Database Setup

Make sure these tables exist in your `blog_db` database:

```sql
-- User Table
CREATE TABLE user (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  full_name varchar(100),
  email varchar(100) NOT NULL,
  bio varchar(300),
  avatar_url varchar(255),
  created_at timestamp DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
);

-- Blog Table
CREATE TABLE blog (
  blog_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(120) NOT NULL,
  subtitle varchar(180),
  body longtext NOT NULL,
  cover_image_url varchar(255),
  created_at timestamp DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (blog_id),
  FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- Interest Table
CREATE TABLE interest (
  interest_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  PRIMARY KEY (interest_id),
  UNIQUE KEY name (name)
);

-- User Interest Junction Table
CREATE TABLE user_interest (
  user_interest_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  interest_id int(11) NOT NULL,
  PRIMARY KEY (user_interest_id),
  FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
  FOREIGN KEY (interest_id) REFERENCES interest(interest_id) ON DELETE CASCADE
);

-- Insert default interests
INSERT INTO interest (name) VALUES 
  ('Technology'),
  ('Business'),
  ('Health & Wellness'),
  ('Entertainment'),
  ('Sports'),
  ('Travel');
```

### Access the API

1. Open your browser and navigate to:
   ```
   http://localhost/Blog-Application/backend/
   ```

2. You'll see a dashboard with all available endpoints.

## 📚 API Endpoints Overview

### Authentication
- `POST /api/auth/signup.php` - Register new user
- `POST /api/auth/login.php` - User login

### Blogs
- `GET /api/blogs/get.php` - Get blogs (all, by user, or search)
- `POST /api/blogs/create.php` - Create new blog
- `PUT /api/blogs/update.php` - Update blog
- `DELETE /api/blogs/delete.php` - Delete blog

### Users
- `GET /api/users/profile.php` - Get user profile
- `PUT /api/users/update-profile.php` - Update user profile

### Interests
- `GET /api/interests/get.php` - Get all interests

### Search
- `GET /api/search/index.php` - Global search (blogs and users)

## 🔒 Security Features

- **Password Hashing**: Passwords are hashed using bcrypt
- **SQL Injection Prevention**: Prepared statements used for all database queries
- **CORS Support**: Cross-origin requests enabled
- **Input Validation**: All user inputs are validated and sanitized
- **Error Handling**: Comprehensive error handling with proper HTTP status codes

## 📋 Request/Response Format

All requests and responses use JSON format.

### Request Example
```bash
curl -X POST http://localhost/Blog-Application/backend/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"john_doe","password":"password123"}'
```

### Response Example
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "user_id": 1,
    "username": "john_doe",
    "full_name": "John Doe",
    "email": "john@example.com",
    "bio": "",
    "avatar_url": ""
  },
  "token": "base64_encoded_jwt_token"
}
```

## 📖 Full Documentation

For detailed API documentation including all parameters, request/response examples, and error codes, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md).

## 🛠️ Helper Functions

The `includes/functions.php` file provides useful helper functions:

- `fetchAll($conn, $query, $params)` - Execute SELECT query and return all rows
- `fetchOne($conn, $query, $params)` - Execute SELECT query and return one row
- `insert($conn, $query, $params)` - Execute INSERT query
- `update($conn, $query, $params)` - Execute UPDATE query
- `delete($conn, $query, $params)` - Execute DELETE query
- `hashPassword($password)` - Hash a password using bcrypt
- `verifyPassword($password, $hash)` - Verify a password against its hash
- `generateToken($user_id, $username)` - Generate a JWT token
- `sendResponse($data, $statusCode)` - Send JSON response
- `getRequestBody()` - Get JSON request body

## 🔄 Database Operations

All database operations use prepared statements to prevent SQL injection:

```php
// Example: Get user by username
$user = fetchOne($conn, 
  "SELECT user_id, username, email FROM user WHERE username = ?", 
  [$username]
);

// Example: Insert new blog
$result = insert($conn, 
  "INSERT INTO blog (user_id, title, body, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())", 
  [$user_id, $title, $body]
);
```

## 🐛 Debugging

Enable PHP error logging by adding to `config.php`:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'errors.log');
```

## 📝 Development Notes

- All timestamps are stored in `'Y-m-d H:i:s'` format
- User IDs are automatically incremented
- Passwords are case-sensitive and hashed with bcrypt
- Email addresses must be unique
- Usernames must be unique
- Foreign keys are enforced (CASCADE DELETE)

## 🚀 Future Enhancements

- [ ] Add JWT validation middleware
- [ ] Implement rate limiting
- [ ] Add email verification
- [ ] Add password reset functionality
- [ ] Implement user roles and permissions
- [ ] Add blog comments and likes system
- [ ] Add user follow functionality
- [ ] Implement image upload handling

## 📞 Support

For issues or questions, refer to the API documentation or check the error response messages.

---

**InkFlow Blog Application &copy; 2024**

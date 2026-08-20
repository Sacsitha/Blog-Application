# InkFlow Blog Application - Backend API Documentation

## Base URL
```
http://localhost/Blog-Application/backend/api/
```

## API Endpoints

### Authentication

#### 1. Sign Up
- **URL**: `auth/signup.php`
- **Method**: POST
- **Body**:
```json
{
  "username": "string",
  "password": "string",
  "full_name": "string",
  "email": "string"
}
```
- **Response** (201):
```json
{
  "success": true,
  "message": "Account created successfully",
  "user": {
    "user_id": 1,
    "username": "john_doe",
    "full_name": "John Doe",
    "email": "john@example.com",
    "bio": "",
    "avatar_url": "",
    "created_at": "2024-08-20 10:30:00"
  },
  "token": "base64_encoded_token"
}
```

#### 2. Login
- **URL**: `auth/login.php`
- **Method**: POST
- **Body**:
```json
{
  "username": "string",
  "password": "string"
}
```
- **Response** (200):
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "user_id": 1,
    "username": "john_doe",
    "full_name": "John Doe",
    "email": "john@example.com",
    "bio": "My bio",
    "avatar_url": "path/to/avatar.jpg"
  },
  "token": "base64_encoded_token"
}
```

---

### Blogs/Stories

#### 3. Create Blog
- **URL**: `blogs/create.php`
- **Method**: POST
- **Body**:
```json
{
  "user_id": "integer",
  "title": "string",
  "subtitle": "string (optional)",
  "body": "string",
  "cover_image_url": "string (optional)",
  "tags": "string (optional)"
}
```
- **Response** (201):
```json
{
  "success": true,
  "message": "Blog created successfully",
  "blog": {
    "blog_id": 1,
    "user_id": 1,
    "title": "My First Blog",
    "subtitle": "An exciting journey",
    "body": "Content here...",
    "cover_image_url": "path/to/image.jpg",
    "created_at": "2024-08-20 10:30:00",
    "updated_at": "2024-08-20 10:30:00"
  }
}
```

#### 4. Get Blogs
- **URL**: `blogs/get.php`
- **Method**: GET
- **Query Parameters**:
  - `blog_id` (optional): Get specific blog by ID
  - `user_id` (optional): Get all blogs by specific user
  - `search` (optional): Search blogs by title, subtitle, or body
  - `limit` (default: 10): Number of results
  - `offset` (default: 0): Pagination offset

- **Examples**:
  - Get all blogs: `blogs/get.php`
  - Get specific blog: `blogs/get.php?blog_id=1`
  - Get user's blogs: `blogs/get.php?user_id=1&limit=20`
  - Search blogs: `blogs/get.php?search=travel&limit=10`

- **Response** (200):
```json
{
  "success": true,
  "blogs": [
    {
      "blog_id": 1,
      "user_id": 1,
      "title": "My First Blog",
      "subtitle": "An exciting journey",
      "body": "Content here...",
      "cover_image_url": "path/to/image.jpg",
      "created_at": "2024-08-20 10:30:00",
      "updated_at": "2024-08-20 10:30:00",
      "username": "john_doe",
      "full_name": "John Doe",
      "avatar_url": "path/to/avatar.jpg"
    }
  ]
}
```

#### 5. Update Blog
- **URL**: `blogs/update.php`
- **Method**: PUT
- **Body**:
```json
{
  "blog_id": "integer",
  "user_id": "integer",
  "title": "string (optional)",
  "subtitle": "string (optional)",
  "body": "string (optional)",
  "cover_image_url": "string (optional)"
}
```
- **Response** (200):
```json
{
  "success": true,
  "message": "Blog updated successfully",
  "blog": { /* updated blog object */ }
}
```

#### 6. Delete Blog
- **URL**: `blogs/delete.php`
- **Method**: DELETE
- **Body**:
```json
{
  "blog_id": "integer",
  "user_id": "integer"
}
```
- **Response** (200):
```json
{
  "success": true,
  "message": "Blog deleted successfully"
}
```

---

### Users

#### 7. Get User Profile
- **URL**: `users/profile.php`
- **Method**: GET
- **Query Parameters**:
  - `user_id` (optional): Get user by ID
  - `username` (optional): Get user by username

- **Examples**:
  - Get by ID: `users/profile.php?user_id=1`
  - Get by username: `users/profile.php?username=john_doe`

- **Response** (200):
```json
{
  "success": true,
  "user": {
    "user_id": 1,
    "username": "john_doe",
    "full_name": "John Doe",
    "email": "john@example.com",
    "bio": "My bio",
    "avatar_url": "path/to/avatar.jpg",
    "created_at": "2024-08-20 10:30:00",
    "interests": [
      {
        "interest_id": 1,
        "name": "Technology"
      },
      {
        "interest_id": 2,
        "name": "Travel"
      }
    ]
  }
}
```

#### 8. Update User Profile
- **URL**: `users/update-profile.php`
- **Method**: PUT
- **Body**:
```json
{
  "user_id": "integer",
  "full_name": "string (optional)",
  "email": "string (optional)",
  "bio": "string (optional)",
  "avatar_url": "string (optional)",
  "interests": [1, 2, 3] (optional - array of interest IDs)
}
```
- **Response** (200):
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "user": { /* updated user object */ }
}
```

---

### Interests

#### 9. Get All Interests
- **URL**: `interests/get.php`
- **Method**: GET
- **Response** (200):
```json
{
  "success": true,
  "interests": [
    {
      "interest_id": 1,
      "name": "Technology"
    },
    {
      "interest_id": 2,
      "name": "Business"
    },
    {
      "interest_id": 3,
      "name": "Health & Wellness"
    },
    {
      "interest_id": 4,
      "name": "Entertainment"
    },
    {
      "interest_id": 5,
      "name": "Sports"
    },
    {
      "interest_id": 6,
      "name": "Travel"
    }
  ]
}
```

---

### Search

#### 10. Global Search
- **URL**: `search/index.php`
- **Method**: GET
- **Query Parameters**:
  - `q` (required): Search query (minimum 2 characters)
  - `type` (optional): Filter results - 'all' (default), 'blogs', or 'users'
  - `limit` (default: 20): Number of results
  - `offset` (default: 0): Pagination offset

- **Examples**:
  - Search all: `search/index.php?q=travel`
  - Search blogs only: `search/index.php?q=travel&type=blogs`
  - Search users only: `search/index.php?q=john&type=users`

- **Response** (200):
```json
{
  "success": true,
  "query": "travel",
  "results": {
    "blogs": [
      {
        "blog_id": 1,
        "title": "My Travel Adventures",
        "subtitle": "Exploring the world",
        "body": "...",
        "cover_image_url": "...",
        "created_at": "2024-08-20 10:30:00",
        "user_id": 1,
        "username": "john_doe",
        "full_name": "John Doe",
        "avatar_url": "..."
      }
    ],
    "users": [
      {
        "user_id": 2,
        "username": "travel_lover",
        "full_name": "Jane Smith",
        "bio": "Travel enthusiast",
        "avatar_url": "..."
      }
    ]
  }
}
```

---

## Error Responses

### 400 - Bad Request
```json
{
  "error": "Missing required fields or invalid input"
}
```

### 401 - Unauthorized
```json
{
  "error": "Invalid credentials"
}
```

### 403 - Forbidden
```json
{
  "error": "You don't have permission to perform this action"
}
```

### 404 - Not Found
```json
{
  "error": "Resource not found"
}
```

### 405 - Method Not Allowed
```json
{
  "error": "Method not allowed"
}
```

### 409 - Conflict
```json
{
  "error": "Username or email already exists"
}
```

### 500 - Internal Server Error
```json
{
  "error": "Database error message"
}
```

---

## Usage Notes

1. **Authentication**: After login/signup, store the returned token in localStorage or sessionStorage for authenticated requests.
2. **CORS**: The API has CORS headers enabled for cross-origin requests.
3. **Timestamps**: All timestamps are in 'Y-m-d H:i:s' format.
4. **Pagination**: Use `limit` and `offset` parameters for paginated results.
5. **Security**: Always validate and sanitize user input on the frontend before sending.
6. **Password**: Passwords are hashed using bcrypt for security.

---

## Testing with cURL

### Sign Up
```bash
curl -X POST http://localhost/Blog-Application/backend/api/auth/signup.php \
  -H "Content-Type: application/json" \
  -d '{"username":"john_doe","password":"pass123","full_name":"John Doe","email":"john@example.com"}'
```

### Login
```bash
curl -X POST http://localhost/Blog-Application/backend/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"john_doe","password":"pass123"}'
```

### Get All Blogs
```bash
curl http://localhost/Blog-Application/backend/api/blogs/get.php
```

### Create Blog
```bash
curl -X POST http://localhost/Blog-Application/backend/api/blogs/create.php \
  -H "Content-Type: application/json" \
  -d '{"user_id":"1","title":"My Blog","body":"Content here","subtitle":"Subtitle"}'
```

### Get User Profile
```bash
curl http://localhost/Blog-Application/backend/api/users/profile.php?user_id=1
```

### Search
```bash
curl "http://localhost/Blog-Application/backend/api/search/index.php?q=travel&type=blogs"
```

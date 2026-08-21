# InkFlow Blog Application

InkFlow is a PHP/MySQL blog application with a static HTML, CSS, and JavaScript frontend. Users can create accounts, choose interests, publish stories, edit or delete their own stories, update their profiles, and search published content.

## Features

- User registration and login
- Password hashing with PHP bcrypt
- User profiles with biography, avatar, email, and interests
- Create, read, update, and delete blog stories
- User-specific story lists and recent activity
- Public blog browsing
- Search across stories and users
- JSON-based PHP API
- MySQL database with foreign-key relationships
- Responsive frontend styled with Bootstrap and project CSS

## Technology Stack

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend:** PHP 7.4 or newer
- **Database:** MySQL 5.7 or newer
- **Local server:** XAMPP, Apache, or another PHP-compatible web server

## Project Structure

```text
Blog-Application/
├── index.html                    # Root entry point; redirects to the frontend
├── backend/
│   ├── config.php                # Active API configuration and response headers
│   ├── index.php                 # API overview page
│   ├── API_DOCUMENTATION.md      # Detailed endpoint reference
│   ├── api/
│   │   ├── auth/                 # Signup and login
│   │   ├── blogs/                # Story CRUD and retrieval
│   │   ├── interests/            # Interest retrieval
│   │   ├── search/               # Global search
│   │   └── users/                # Profile retrieval and updates
│   ├── db/
│   │   ├── db.php                # Shared database connection
│   │   └── schema.sql             # Database schema and seed interests
│   └── includes/
│       └── functions.php         # Shared database and response helpers
└── frontend/
    ├── index.html                # Public home page
    ├── pages/                    # Login, signup, profile, story, and search pages
    ├── css/                      # Page and component styles
    ├── js/
    │   ├── api-client.js         # Shared API client
    │   └── script.js             # Shared frontend behavior
    └── assets/                   # Images, icons, and logos
```

## Requirements

Install or enable:

- Apache
- PHP with the `mysqli` extension
- MySQL
- A modern browser with JavaScript enabled

For XAMPP on Windows, the project can be placed in:

```text
C:\xampp\htdocs\Blog-Application
```

## Database Setup

1. Start Apache and MySQL in XAMPP.
2. Create an empty MySQL database.
3. Open `backend/db/schema.sql` in phpMyAdmin or the MySQL client.
4. Run the schema against the new database.
5. Configure the connection in `backend/db/db.php`.

The connection file requires these values:

```php
$host = "your-database-host";
$username = "your-database-user";
$password = "your-database-password";
$dbname = "your-database-name";
```

Do not commit database passwords or other credentials to a public repository. Rotate any password that has previously been exposed and prefer environment variables or hosting-panel secrets where available.

## Run Locally

1. Place the project in the Apache document root.
2. Start Apache and MySQL.
3. Open the application:

   ```text
   http://localhost/Blog-Application/
   ```

4. The root `index.html` redirects to `frontend/index.html`.
5. The backend overview is available at:

   ```text
   http://localhost/Blog-Application/backend/
   ```

The frontend API client resolves the backend relative to its deployed location, so the same project layout works locally and on a hosted domain:

```text
frontend/js/api-client.js -> ../../backend/api
```

## Deploy to Hosting

Upload the complete project with this structure to the hosting document root:

```text
public_html/
├── index.html
├── backend/
└── frontend/
```

Then:

1. Create the MySQL database in the hosting control panel.
2. Import `backend/db/schema.sql`.
3. Update `backend/db/db.php` with the hosting database host, username, password, and database name.
4. Upload all project files, including the root `index.html`.
5. Open the domain root, for example `https://example.com/`.
6. Confirm that `https://example.com/backend/` loads the API overview.

A 403 error at the domain root usually means the host is pointing at a directory without a default document. Ensure the document root contains the project’s root `index.html` and that the host is not pointing only at an empty parent directory.

## API Overview

The base API path is:

```text
/backend/api/
```

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `auth/signup.php` | Register a user |
| POST | `auth/login.php` | Authenticate a user |
| GET | `blogs/get.php` | Get all, one, searched, or user-specific stories |
| POST | `blogs/create.php` | Create a story |
| PUT | `blogs/update.php` | Update the owner’s story |
| DELETE | `blogs/delete.php` | Delete the owner’s story |
| GET | `users/profile.php` | Get a profile by ID or username |
| PUT | `users/update-profile.php` | Update profile data and interests |
| GET | `interests/get.php` | Get available interests |
| GET | `search/index.php` | Search stories and users |

For request bodies, query parameters, response examples, and status codes, see [backend/API_DOCUMENTATION.md](backend/API_DOCUMENTATION.md).

## Data Model

The database contains four tables:

- `user`: account and profile information
- `blog`: stories owned by users
- `interest`: available interest options
- `user_interest`: many-to-many relationship between users and interests

Deleting a user cascades to that user’s stories and selected interests through the configured foreign keys.

## Important Behavior

- The home page can display public stories from all users.
- The authenticated profile dashboard displays only the logged-in user’s stories.
- The user profile’s recent activity is loaded from that same user’s stories.
- Story update and delete controls are displayed for the current user’s stories.
- Authentication state is stored in browser `localStorage` as `user` and `token`.

## Troubleshooting

### 403 Forbidden at the domain root

Verify that:

- `index.html` is in the hosting document root.
- `frontend/` and `backend/` are beside that file.
- The domain’s document root points to the folder containing `index.html`.
- No `.htaccess` rule is denying access.

### API requests still use localhost

Make sure the deployed `frontend/js/api-client.js` contains the relative API URL logic and that the `backend/` folder is uploaded beside `frontend/`.

### Database connection failed

Check the four values in `backend/db/db.php`, confirm the hosting database allows remote connections if required, and verify that `mysqli` is enabled in PHP.

### Login or signup fails with a server error

Open the browser developer console and inspect the Network response. Also verify that the schema was imported and that the `user`, `blog`, `interest`, and `user_interest` tables exist.

## Security Notes

- Never publish database credentials.
- Use HTTPS in production.
- Use a strong, unique database password.
- Restrict database users to the required database and permissions.
- Review CORS settings before production; the current API allows requests from any origin.
- Add server-side authentication and authorization middleware before exposing sensitive endpoints publicly.

## Documentation

- [Backend README](backend/README.md)
- [API documentation](backend/API_DOCUMENTATION.md)
- [Database schema](backend/db/schema.sql)
- [Frontend README](frontend/README.md)

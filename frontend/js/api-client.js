/**
 * InkFlow Blog Application - API Client
 * Handles all communication with the backend API
 */

const API_BASE_URL = 'http://localhost/Blog-Application/backend/api';

class APIClient {
  /**
   * Make an API request
   */
  static async request(method, endpoint, data = null) {
    const url = `${API_BASE_URL}${endpoint}`;
    
    const options = {
      method: method,
      headers: {
        'Content-Type': 'application/json',
      }
    };

    // Add token to request if it exists
    const token = localStorage.getItem('token');
    if (token) {
      options.headers['Authorization'] = `Bearer ${token}`;
    }

    // Add body for POST, PUT, DELETE requests
    if (data) {
      options.body = JSON.stringify(data);
    }

    try {
      const response = await fetch(url, options);
      const result = await response.json();

      if (!response.ok) {
        throw {
          status: response.status,
          message: result.error || 'An error occurred',
          ...result
        };
      }

      return result;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  /**
   * Authentication endpoints
   */
  static async signup(username, password, fullName, email, interests = []) {
    return this.request('POST', '/auth/signup.php', {
      username,
      password,
      full_name: fullName,
      email,
      interests
    });
  }

  static async login(username, password) {
    return this.request('POST', '/auth/login.php', {
      username,
      password
    });
  }

  /**
   * Blog endpoints
   */
  static async createBlog(userId, title, body, subtitle = '', coverImageUrl = '', tags = '') {
    return this.request('POST', '/blogs/create.php', {
      user_id: userId,
      title,
      subtitle,
      body,
      cover_image_url: coverImageUrl,
      tags
    });
  }

  static async getBlog(blogId) {
    return this.request('GET', `/blogs/get.php?blog_id=${blogId}`);
  }

  static async getUserBlogs(userId, limit = 10, offset = 0) {
    return this.request('GET', `/blogs/get.php?user_id=${userId}&limit=${limit}&offset=${offset}`);
  }

  static async getAllBlogs(limit = 10, offset = 0) {
    return this.request('GET', `/blogs/get.php?limit=${limit}&offset=${offset}`);
  }

  static async searchBlogs(query, limit = 10, offset = 0) {
    return this.request('GET', `/blogs/get.php?search=${encodeURIComponent(query)}&limit=${limit}&offset=${offset}`);
  }

  static async updateBlog(blogId, userId, updates) {
    return this.request('PUT', '/blogs/update.php', {
      blog_id: blogId,
      user_id: userId,
      ...updates
    });
  }

  static async deleteBlog(blogId, userId) {
    return this.request('DELETE', '/blogs/delete.php', {
      blog_id: blogId,
      user_id: userId
    });
  }

  /**
   * User endpoints
   */
  static async getUserProfile(userId = null, username = null) {
    if (userId) {
      return this.request('GET', `/users/profile.php?user_id=${userId}`);
    }
    if (username) {
      return this.request('GET', `/users/profile.php?username=${username}`);
    }
    throw new Error('userId or username is required');
  }

  static async updateProfile(userId, updates) {
    return this.request('PUT', '/users/update-profile.php', {
      user_id: userId,
      ...updates
    });
  }

  /**
   * Interest endpoints
   */
  static async getInterests() {
    return this.request('GET', '/interests/get.php');
  }

  /**
   * Search endpoints
   */
  static async search(query, type = 'all', limit = 20, offset = 0) {
    return this.request('GET', `/search/index.php?q=${encodeURIComponent(query)}&type=${type}&limit=${limit}&offset=${offset}`);
  }

  /**
   * Local storage helpers
   */
  static setUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
  }

  static getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }

  static setToken(token) {
    localStorage.setItem('token', token);
  }

  static getToken() {
    return localStorage.getItem('token');
  }

  static clearAuth() {
    localStorage.removeItem('user');
    localStorage.removeItem('token');
  }

  static isAuthenticated() {
    return !!localStorage.getItem('token');
  }
}

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
  module.exports = APIClient;
}

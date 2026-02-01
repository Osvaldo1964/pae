/**
 * Config.js
 * Global configuration for PAE Control WebApp
 */

const Config = {
    VERSION: '1.3.5', // Bump this to clear cache
    // Base URL for the application (relative to root or full domain)
    BASE_URL: '/pae/app/',

    // API Base URL
    API_URL: '/pae/api',

    // Assets Base URL
    ASSETS_URL: '/pae/app/assets',

    // Get full asset path
    asset(path) {
        return `${this.ASSETS_URL}/${path}`;
    },

    // Get full API endpoint
    apiEndpoint(endpoint) {
        return `${this.API_URL}${endpoint}`;
    },

    // Get token from localStorage
    getToken() {
        return localStorage.getItem('pae_token');
    },

    // Get headers for API requests
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json'
        };

        const token = this.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        return headers;
    }
};

import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Firebase will be loaded conditionally only on pages that need it
// Removed eager import to reduce initial bundle size by ~140KB
// Use: import('./firebase') in specific pages that need Firebase

// Register Alpine plugins
Alpine.plugin(collapse);

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

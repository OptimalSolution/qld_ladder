/**
 * Crypto API polyfill for older browsers
 * This polyfill provides basic crypto functionality for browsers that don't support the Web Crypto API
 */

if (typeof window !== 'undefined' && window.crypto === undefined) {
  window.crypto = {};
}

if (typeof window !== 'undefined' && window.crypto.getRandomValues === undefined) {
  window.crypto.getRandomValues = function(array) {
    // Simple PRNG fallback
    for (let i = 0; i < array.length; i++) {
      array[i] = Math.floor(Math.random() * 256);
    }
    return array;
  };
}
import { randomBytes } from 'crypto';

if (!window.crypto) {
    window.crypto = {};
}

if (!window.crypto.getRandomValues) {
    window.crypto.getRandomValues = function(array) {
        const bytes = randomBytes(array.length);
        array.set(new Uint8Array(bytes));
        return array;
    };
} 

import.meta.glob([
    '../assets/images/**',
    '../fonts/**',
]);

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

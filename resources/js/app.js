import './bootstrap';

// Import library yang kita butuhkan
import Chart from 'chart.js/auto';
import mask from '@alpinejs/mask';

// Daftarkan Chart ke window object
window.Chart = Chart;

// Gunakan event listener 'alpine:init' untuk mendaftarkan plugin
// Ini adalah cara yang benar agar tidak bentrok dengan Livewire
document.addEventListener('alpine:init', () => {
    Alpine.plugin(mask);
});

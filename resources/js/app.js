import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// Clause autoplay
// tambahkan next-slide pada class
// seperti berikut <a href="#slide2" class="btn btn-circle next-slide">❯</a>
//   document.addEventListener('DOMContentLoaded', function () {
//     const intervalTime = 3000; // ganti sesuai kebutuhan (ms)
    
//     setInterval(() => {
//       // cari tombol ❯ di slide yang sedang terlihat
//       const visibleSlide = document.querySelector('.carousel-item:not([hidden])') || document.querySelector('.carousel-item');
//       const nextBtn = visibleSlide?.querySelector('.next-slide');
//       if (nextBtn) {
//         nextBtn.click();
//       }
//     }, intervalTime);
//   });


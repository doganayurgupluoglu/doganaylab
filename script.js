// Başlangıç tarihi (13 Şubat 2025)
const startDate = new Date('2025-02-10');

// Header scroll efekti
const header = document.querySelector('.header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    // Scroll pozisyonuna göre header'ın görünümünü ayarla
    if (currentScroll > 100) {
        header.classList.add('scrolled');
        header.classList.remove('scrolling');
    } else if (currentScroll > 30) {
        header.classList.add('scrolling');
        header.classList.remove('scrolled');
    } else {
        header.classList.remove('scrolled', 'scrolling');
    }
    
    // Scroll yönüne göre ince ayar
    if (currentScroll > lastScroll && currentScroll > 30) {
        header.style.transform = 'translateY(-2px)';
    } else {
        header.style.transform = 'translateY(0)';
    }
    
    lastScroll = currentScroll;
});

function updateDuration() {
    const now = new Date();
    const diff = now - startDate;
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    
    const durationText = document.getElementById('learning-duration');
    if (durationText) {
        if (days < 7) {
            durationText.textContent = `Front-end geliştirme yolculuğuma başlayalı ${days} gün oldu.`;
        } else if (days < 30) {
            const weeks = Math.floor(days / 7);
            const remainingDays = days % 7;
            if (remainingDays === 0) {
                durationText.textContent = `Front-end geliştirme yolculuğuma başlayalı ${weeks} hafta oldu.`;
            } else {
                durationText.textContent = `Front-end geliştirme yolculuğuma başlayalı ${weeks} hafta ${remainingDays} gün oldu.`;
            }
        } else {
            const months = Math.floor(days / 30);
            const remainingDays = days % 30;
            if (remainingDays === 0) {
                durationText.textContent = `Front-end geliştirme yolculuğuma başlayalı ${months} ay oldu.`;
            } else {
                durationText.textContent = `Front-end geliştirme yolculuğuma başlayalı ${months} ay ${remainingDays} gün oldu.`;
            }
        }
    }
}

// Sayfa yüklendiğinde çalıştır
updateDuration();

// Her gün güncelle
setInterval(updateDuration, 24 * 60 * 60 * 1000);

// Theme switching functionality
document.addEventListener('DOMContentLoaded', () => {
    const lightModeBtn = document.getElementById('lightMode');
    const darkModeBtn = document.getElementById('darkMode');
    const root = document.documentElement;

    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        root.classList.add('dark-mode');
    }

    // Light mode button click handler
    lightModeBtn.addEventListener('click', () => {
        root.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
    });

    // Dark mode button click handler
    darkModeBtn.addEventListener('click', () => {
        root.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
    });
}); 
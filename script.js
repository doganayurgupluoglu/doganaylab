// Constants
const START_DATE = new Date('2025-02-10');
const HEADER_SCROLL_THRESHOLD = 100;
const HEADER_SCROLL_START = 30;

// DOM Elements
const header = document.querySelector('.header');
const learningDuration = document.getElementById('learning-duration');
const lightModeBtn = document.getElementById('lightMode');
const darkModeBtn = document.getElementById('darkMode');
const root = document.documentElement;

// Theme Management
class ThemeManager {
    constructor() {
        this.THEMES = {
            LIGHT: 'light',
            DARK: 'dark'
        };
        
        this.STORAGE_KEY = 'theme-preference';
        this.themeToggle = document.querySelector('.theme-toggle');
        this.buttons = {
            light: lightModeBtn,
            dark: darkModeBtn
        };
        
        this.init();
    }
    
    init() {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const savedTheme = localStorage.getItem(this.STORAGE_KEY);
        
        // Varsayılan olarak karanlık mod
        const initialTheme = savedTheme || this.THEMES.DARK;
        this.setTheme(initialTheme);
        
        this.buttons.light?.addEventListener('click', () => this.handleThemeChange(this.THEMES.LIGHT));
        this.buttons.dark?.addEventListener('click', () => this.handleThemeChange(this.THEMES.DARK));
    }
    
    handleThemeChange(theme) {
        this.setTheme(theme);
        localStorage.setItem(this.STORAGE_KEY, theme);
    }
    
    setTheme(theme) {
        if (theme === this.THEMES.LIGHT) {
            root.classList.remove('dark-mode');
            this.themeToggle?.classList.remove('dark');
        } else {
            root.classList.add('dark-mode');
            this.themeToggle?.classList.add('dark');
        }
    }
}

// Header Scroll Effect
const HeaderScroll = {
    lastScroll: 0,
    isMobile: false,
    
    init() {
        this.checkMobile();
        window.addEventListener('resize', () => this.checkMobile());
        window.addEventListener('scroll', () => this.handleScroll());
    },

    checkMobile() {
        this.isMobile = window.innerWidth <= 768;
        if (this.isMobile) {
            header?.classList.remove('scrolled', 'scrolling');
            header.style.transform = '';
        }
    },
    
    handleScroll() {
        if (!header || this.isMobile) return;
        
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > HEADER_SCROLL_THRESHOLD) {
            header.classList.add('scrolled');
            header.classList.remove('scrolling');
        } else if (currentScroll > HEADER_SCROLL_START) {
            header.classList.add('scrolling');
            header.classList.remove('scrolled');
        } else {
            header.classList.remove('scrolled', 'scrolling');
        }
        
        header.style.transform = currentScroll > this.lastScroll && currentScroll > HEADER_SCROLL_START
            ? 'translateY(-2px)'
            : 'translateY(0)';
            
        this.lastScroll = currentScroll;
    }
};

// Learning Duration Calculator
const LearningDuration = {
    updateDuration() {
        if (!learningDuration) return;
        
        const now = new Date();
        const diff = now - START_DATE;
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        
        let durationText = '';
        
        if (days < 7) {
            durationText = `${days} gün`;
        } else if (days < 30) {
            const weeks = Math.floor(days / 7);
            const remainingDays = days % 7;
            durationText = remainingDays === 0 
                ? `${weeks} hafta` 
                : `${weeks} hafta ${remainingDays} gün`;
        } else {
            const months = Math.floor(days / 30);
            const remainingDays = days % 30;
            durationText = remainingDays === 0 
                ? `${months} ay` 
                : `${months} ay ${remainingDays} gün`;
        }
        
        learningDuration.textContent = `Front-end geliştirme yolculuğuma başlayalı ${durationText} oldu.`;
    },
    
    init() {
        this.updateDuration();
        // Update duration every day
        setInterval(() => this.updateDuration(), 24 * 60 * 60 * 1000);
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
    HeaderScroll.init();
    LearningDuration.init();
}); 
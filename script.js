// Başlangıç tarihi (13 Şubat 2025)
const startDate = new Date('2025-02-10');

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
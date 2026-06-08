document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
        this.classList.add('nav-tab-active');
        
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        document.getElementById('tab-' + this.dataset.tab).style.display = 'block';
    });
});

// Live color preview (optional enhancement)
console.log('Bilohash Smart Popups Admin loaded');
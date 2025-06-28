document.addEventListener('DOMContentLoaded', function() {
    const loginBtn = document.getElementById('login-link');
    
    if (loginBtn) {
        loginBtn.addEventListener('click', function(e) {
            console.log('Redirecting to:', this.dataset.loginUrl);
        });
    }
});


function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + 'Toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
        
// Add random health elements periodically
function addRandomHealthElement() {
    const healthIcons = [
        'fas fa-syringe',
        'fas fa-stethoscope', 
        'fas fa-pills',
        'fas fa-hospital',
        'fas fa-heartbeat',
        'fas fa-band-aid',
        'fas fa-flask',
        'fas fa-microscope',
        'fas fa-user-md',
        'fas fa-ambulance',
        'fas fa-thermometer',
        'fas fa-briefcase-medical',
        'fas fa-tooth',
        'fas fa-eye',
        'fas fa-lungs'
    ];
    const container = document.querySelector('.floating-health-elements');
    
    if (container) {
        const element = document.createElement('i');
        element.className = 'health-element ' + healthIcons[Math.floor(Math.random() * healthIcons.length)];
        element.style.left = Math.random() * 100 + '%';
        element.style.fontSize = (20 + Math.random() * 25) + 'px';
        element.style.animationDuration = (15 + Math.random() * 10) + 's';
        element.style.animationDelay = '0s';
        
        container.appendChild(element);
        
        // Remove element after animation
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 25000);
    }
}

// Add new health elements every 3 seconds
setInterval(addRandomHealthElement, 3000);

// Initial health elements
setTimeout(() => {
    for (let i = 0; i < 3; i++) {
        setTimeout(addRandomHealthElement, i * 1000);
    }
}, 1000);
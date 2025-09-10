// menu&navbar
let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');

menu.onclick = () => {
        menu.classList.toggle('fa-times');
        navbar.classList.toggle('active');
};

window.onscroll = () => {
        menu.classList.remove('fa-times');
        navbar.classList.remove('active');
};

// home swiper
var homeSwiper = new Swiper(".home-slider", {
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
});

// feedback message
function showModal(title, message) {
    const modal = document.getElementById('message-modal');
    const modalTitle = modal.querySelector('.modal-title');
    const modalMessage = modal.querySelector('.modal-message');

    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('message-modal');
    modal.style.display = 'none';
}

// review swiper
var reviewSwiper = new Swiper(".review-slider", {
    loop: true,
    spaceBetween: 20,
    autoHeight: true,
    grabCursor: true,
    autoplay: {
        delay: 2000,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        },
    },
});

function refreshSwiper() {
    if (reviewSwiper) {
        reviewSwiper.update();
        reviewSwiper.slideTo(reviewSwiper.slides.length - 1, 0);
    }
}

function scrollToReviews() {
    const reviewSection = document.querySelector('.review');
    if (reviewSection) {
        reviewSection.scrollIntoView({ behavior: 'smooth' });
    }
}

const feedbackForm = document.querySelector('.feedback-form');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function () {
            setTimeout(() => {
                refreshSwiper();
                scrollToReviews();
            }, 500);
        });
    }



// appointment
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');

    dateInput.addEventListener('change', function () {
        const selectedDate = this.value;

        if (!selectedDate) return;

        fetch('get_booked_times.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ date: selectedDate }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                const bookedTimes = data.bookedTimes || [];
                timeInput.innerHTML = '';

                const allTimes = [];
                for (let hour = 9; hour <= 18; hour++) {
                    allTimes.push(`${hour.toString().padStart(2, '0')}:00`);
                }

                allTimes.forEach((time) => {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    if (bookedTimes.includes(time)) {
                        option.disabled = true;
                    }
                    timeInput.appendChild(option);
                });
            })
            .catch((error) => {
                console.error('Error fetching booked times:', error);
                alert('An error occurred while fetching available time slots. Please try again.');
            });
    });
});

// service btn
const loadMoreBtn = document.querySelector('.load-more .btn');
const boxes = [...document.querySelectorAll('.service .box-container .box')];

let currentItem = 0;

loadMoreBtn.onclick = () => {
    for (let i = currentItem; i < currentItem + 6 && i < boxes.length; i++) {
        boxes[i].style.display = 'inline-block';
    }
    currentItem += 6;

    if (currentItem >= boxes.length) {
        loadMoreBtn.style.display = 'none';
    }
};

// register
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.message .fa-times').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });

    document.querySelectorAll('.show-password').forEach(eyeIcon => {
        eyeIcon.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordField = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            const isPassword = passwordField.type === "password";
            passwordField.type = isPassword ? "text" : "password";
            icon.classList.toggle('fa-eye-slash', !isPassword);
            icon.classList.toggle('fa-eye', isPassword);
        });
    });

    const registerForm = document.getElementById('registerForm');
    if(registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password')?.value;
            const cpassword = document.getElementById('cpassword')?.value;
            
            if (password && password.length < 8) {
                alert('Password must be at least 8 characters!');
                e.preventDefault();
                return;
            }
            
            if (password && cpassword && password !== cpassword) {
                alert('Passwords do not match!');
                e.preventDefault();
                return;
            }
        });
    }

    const loginForm = document.getElementById('loginForm');
    if(loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email')?.value;
            const password = document.getElementById('password')?.value;
            
            if (!email || !password) {
                alert('Both email and password are required!');
                e.preventDefault();
                return;
            }
        });
    }

    if(window.location.search.includes('success')) {
        sessionStorage.removeItem('formData');
    }
});
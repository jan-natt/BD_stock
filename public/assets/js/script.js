
//Bootstrap JS = partnarship script home
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('partners-slider');
            const items = slider.querySelectorAll('.item');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.getElementById('prev-partner');
            const nextBtn = document.getElementById('next-partner');
            
            let currentIndex = 0;
            const itemsPerPage = 4;
            const totalItems = items.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            
            // Initialize dots
            function initDots() {
                const dotsContainer = document.getElementById('slider-dots');
                dotsContainer.innerHTML = '';
                
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('span');
                    dot.className = 'dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('data-index', i);
                    dot.addEventListener('click', function() {
                        goToPage(parseInt(this.getAttribute('data-index')));
                    });
                    dotsContainer.appendChild(dot);
                }
            }
            
            // Update dots
            function updateDots() {
                document.querySelectorAll('.dot').forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }
            
            // Go to specific page
            function goToPage(pageIndex) {
                if (pageIndex < 0 || pageIndex >= totalPages) return;
                
                currentIndex = pageIndex;
                const translateX = -currentIndex * 100;
                slider.style.transform = `translateX(${translateX}%)`;
                updateDots();
            }
            
            // Next page
            function nextPage() {
                if (currentIndex < totalPages - 1) {
                    goToPage(currentIndex + 1);
                } else {
                    goToPage(0);
                }
            }
            
            // Previous page
            function prevPage() {
                if (currentIndex > 0) {
                    goToPage(currentIndex - 1);
                } else {
                    goToPage(totalPages - 1);
                }
            }
            
            // Event listeners
            prevBtn.addEventListener('click', prevPage);
            nextBtn.addEventListener('click', nextPage);
            
            // Initialize slider
            function initSlider() {
                // Set the width of the slider
                slider.style.width = `${totalPages * 100}%`;
                slider.style.display = 'flex';
                slider.style.transition = 'transform 0.5s ease';
                
                // Set the width of each item
                items.forEach(item => {
                    item.style.width = `${100 / (totalPages * itemsPerPage)}%`;
                    item.style.flexShrink = '0';
                });
                
                initDots();
            }
            
            // Auto slide
            let autoSlideInterval = setInterval(nextPage, 5000);
            
            // Pause auto slide on hover
            const partnersSection = document.querySelector('.partners-slider');
            partnersSection.addEventListener('mouseenter', function() {
                clearInterval(autoSlideInterval);
            });
            
            partnersSection.addEventListener('mouseleave', function() {
                autoSlideInterval = setInterval(nextPage, 5000);
            });
            
            // Initialize on window load
            window.addEventListener('load', initSlider);
            
            // Also initialize when DOM is ready
            if (document.readyState === 'complete') {
                initSlider();
            }
        });



  
   
    
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        
                        // Animate progress bar for how-to-trade section
                        if (entry.target.closest('.how-to-trade')) {
                            const progressBar = document.querySelector('.progress-bar-fill');
                            if (progressBar) {
                                progressBar.style.height = '70%';
                            }
                        }
                    }
                });
            }, {
                threshold: 0.1
            });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
            
            // Simple market data animation
            const tickerItems = document.querySelectorAll('.ticker-item');
            tickerItems.forEach((item, index) => {
                item.style.transition = 'all 0.5s ease';
                item.style.transitionDelay = (index * 0.1) + 's';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            });
            
            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.padding = '10px 0';
                    navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                } else {
                    navbar.style.padding = '15px 0';
                    navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                }
            });
        });
   
    
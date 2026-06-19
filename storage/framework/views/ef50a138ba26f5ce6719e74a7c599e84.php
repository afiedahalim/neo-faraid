<footer class="footer">
    <div class="footer-container">
        <!-- Main Footer Content -->
        <div class="footer-main">
            <!-- Brand Section -->
            <div class="footer-brand">
                <a href="<?php echo e(url('/')); ?>" class="footer-logo">
                    <div class="footer-logo-icon">
                        <svg viewBox="0 0 32 32" fill="currentColor">
                            <path d="M16 2L2 10l14 8 14-8-14-8zM2 22l14 8 14-8M2 16l14 8 14-8"/>
                        </svg>
                    </div>
                    <div>
                        <div class="footer-logo-text">Neo Faraid</div>
                        <div class="footer-logo-subtitle">Islamic Inheritance Solutions</div>
                    </div>
                </a>
                
                <p class="footer-description">
                    Accurate, Shariah-compliant inheritance calculations for Muslims worldwide. 
                    Simplifying Faraid since 2025.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="footer-links">
                <h3 class="footer-heading">Quick Links</h3>
                <ul class="footer-links-list">
                    <li><a href="<?php echo e(url('/')); ?>" class="footer-link">Home</a></li>
                    <li><a href="<?php echo e(route('instant-estate.index')); ?>" class="footer-link">Instant Estate</a></li>
                    <li><a href="<?php echo e(route('calculator.index')); ?>" class="footer-link">Calculator</a></li>
                    <li><a href="<?php echo e(route('about')); ?>" class="footer-link">About</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>" class="footer-link">Contact</a></li>
                    <li><a href="<?php echo e(route('faq.index')); ?>" class="footer-link">FAQ</a></li>
                    <li><a href="<?php echo e(route('feedback.index')); ?>" class="footer-link">Feedback</a></li>
            </div>

            <!-- Contact Info -->
            <div class="footer-contact">
                <h3 class="footer-heading">Contact Us</h3>
                <div class="contact-info">
                    <a href="mailto:info@neofaraid.com" class="contact-item">
                        <svg class="contact-icon" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <span>neofaraidadmin@gmail.com</span>
                    </a>
                    <a href="tel:+60123456789" class="contact-item">
                        <svg class="contact-icon" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <span>+60 12-345 6789</span>
                    </a>
                </div>
                
                <!-- Social Media -->
                <div class="footer-social">
                    <h3 class="footer-heading">Follow Us</h3>
                    <div class="social-icons">
                        <a href="#" class="social-icon" aria-label="Facebook">
                            <svg viewBox="0 0 24 24">
                                <path fill="currentColor" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Twitter">
                            <svg viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <svg viewBox="0 0 24 24">
                                <path fill="currentColor" d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5zM12 7a5 5 0 110 10 5 5 0 010-10zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm5.75-2a1.25 1.25 0 110 2.5 1.25 1.25 0 010-2.5z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                &copy; <?php echo e(date('Y')); ?> Neo Faraid. All rights reserved.
            </div>
            
            <div class="footer-prayer">
                <span class="prayer-text">And Allah knows best.</span>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Base Styles */
    .footer {
        background: linear-gradient(135deg, #0a2351 0%, #164b8c 100%);
        color: #ffffff;
        padding: 3rem 0 1.5rem;
        margin-top: auto;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Main Footer Layout */
    .footer-main {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3rem;
        margin-bottom: 3rem;
    }

    /* Brand Section */
    .footer-brand {
        grid-column: span 1;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        text-decoration: none;
        color: inherit;
    }

    .footer-logo-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #4dabf7 0%, #228be6 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .footer-logo-icon svg {
        width: 24px;
        height: 24px;
        color: white;
    }

    .footer-logo-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
    }

    .footer-logo-subtitle {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 0.25rem;
    }

    .footer-description {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin: 0;
        font-size: 0.95rem;
    }

    /* Links Section */
    .footer-links {
        grid-column: span 1;
    }

    .footer-heading {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: white;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .footer-heading::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #4dabf7, #228be6);
        border-radius: 2px;
    }

    .footer-links-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links-list li {
        margin-bottom: 0.75rem;
    }

    .footer-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-link:hover {
        color: #4dabf7;
        transform: translateX(5px);
    }

    /* Contact Section */
    .footer-contact {
        grid-column: span 1;
    }

    .contact-info {
        margin-bottom: 2rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.95rem;
    }

    .contact-icon {
        width: 20px;
        height: 20px;
        color: #4dabf7;
        flex-shrink: 0;
    }

    /* Social Icons */
    .social-icons {
        display: flex;
        gap: 1rem;
    }

    .social-icon {
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        background: rgba(77, 171, 247, 0.2);
        color: #4dabf7;
        transform: translateY(-3px);
    }

    .social-icon svg {
        width: 20px;
        height: 20px;
    }

    /* Bottom Footer */
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .footer-copyright {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
    }

    .footer-prayer {
        text-align: right;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
    }

    .prayer-text {
        font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .footer-main {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        
        .footer-container {
            padding: 0 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .footer-main {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        
        .footer-bottom {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }
        
        .footer-prayer {
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .footer-container {
            padding: 0 1rem;
        }
        
        .footer {
            padding: 2rem 0 1rem;
        }
        
        .footer-logo-text {
            font-size: 1.25rem;
        }
        
        .footer-heading {
            font-size: 1rem;
        }
        
        .footer-link,
        .contact-item,
        .footer-description {
            font-size: 0.9rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Link hover effects
    const footerLinks = document.querySelectorAll('.footer-link');
    footerLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Social icon hover effects
    const socialIcons = document.querySelectorAll('.social-icon');
    socialIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script><?php /**PATH C:\laragon\www\neo-faraid\resources\views/partials/footer.blade.php ENDPATH**/ ?>
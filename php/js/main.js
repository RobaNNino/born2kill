// js/main.js
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.createElement('button');
    mobileMenuBtn.className = 'mobile-menu-btn';
    mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
    
    const nav = document.querySelector('nav');
    const navLinks = document.querySelector('.nav-links');
    
    if (nav && navLinks && window.innerWidth <= 768) {
        nav.insertBefore(mobileMenuBtn, navLinks);
        
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            
            if (navLinks.classList.contains('active')) {
                mobileMenuBtn.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    }
    
    // Pricing section accordions
    const pricingCategories = document.querySelectorAll('.pricing-category');
    
    pricingCategories.forEach(category => {
        category.addEventListener('click', () => {
            const priceList = category.nextElementSibling;
            
            // Toggle the active class for the category
            category.classList.toggle('active');
            
            // Toggle the active class for the price list
            priceList.classList.toggle('active');
        });
    });
    
    // Initially open the first pricing category
    if (pricingCategories.length > 0) {
        pricingCategories[0].classList.add('active');
        pricingCategories[0].nextElementSibling.classList.add('active');
    }
    
    // Profile tabs
    const profileTabs = document.querySelectorAll('.profile-tab');
    const profileTabContents = document.querySelectorAll('.profile-tab-content');
    
    profileTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            profileTabs.forEach(t => t.classList.remove('active'));
            profileTabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            tab.classList.add('active');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
    
    // Banner reload - refresh GameTracker banners periodically
    function reloadGameTrackerBanners() {
        const banners = document.querySelectorAll('.gametracker-widget img');
        banners.forEach(banner => {
            const currentSrc = banner.src;
            // Add timestamp to force reload without caching
            banner.src = currentSrc.split('?')[0] + '?t=' + new Date().getTime();
        });
        
        // Reload banners every 2 minutes
        setTimeout(reloadGameTrackerBanners, 120000);
    }
    
    // Start periodic reloading of banners
    setTimeout(reloadGameTrackerBanners, 120000);
    
    // Smooth scrolling for navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            // Only apply to internal links
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                
                // Skip if it's just "#"
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Update active nav link
                    document.querySelectorAll('.nav-links a').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            }
        });
    });
});
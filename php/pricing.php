<!-- pricing.php -->
<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Born2Kill Community</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing">
        <div class="container">
            <h2 class="section-title">BORN2KILL ZP PRICES</h2>
            
            <!-- PACKS PRICE -->
            <div class="price-section">
                <h3 class="pricing-category">PACKS PRICE</h3>
                <ul class="price-list">
                    <li><span class="price-item">1,000 packs:</span> <span class="price-value">3 €</span></li>
                    <li><span class="price-item">3,000 packs:</span> <span class="price-value">5 €</span></li>
                    <li><span class="price-item">5,000 packs:</span> <span class="price-value">10 €</span></li>
                    <li><span class="price-item">7,500 packs:</span> <span class="price-value">15 €</span></li>
                    <li><span class="price-item">10,000 packs:</span> <span class="price-value">20 €</span></li>
                    <li><span class="price-item">15,000 packs:</span> <span class="price-value">25 €</span></li>
                    <li><span class="price-item">20,000 packs:</span> <span class="price-value">35 €</span></li>
                </ul>
            </div>
            
            <!-- POINTS PRICE -->
            <div class="price-section">
                <h3 class="pricing-category">POINTS PRICE</h3>
                <ul class="price-list">
                    <li><span class="price-item">500 points:</span> <span class="price-value">3 €</span></li>
                    <li><span class="price-item">1,000 points:</span> <span class="price-value">5 €</span></li>
                    <li><span class="price-item">2,500 points:</span> <span class="price-value">10 €</span></li>
                    <li><span class="price-item">5,000 points:</span> <span class="price-value">20 €</span></li>
                    <li><span class="price-item">7,000 points:</span> <span class="price-value">30 €</span></li>
                    <li><span class="price-item">10,000 points:</span> <span class="price-value">35 €</span></li>
                </ul>
            </div>
            
            <!-- Premium VIP PRICE -->
            <div class="price-section">
                <h3 class="pricing-category">Premium VIP PRICE</h3>
                <ul class="price-list">
                    <li><span class="price-item">1 WEEK:</span> <span class="price-value">3 €</span></li>
                    <li><span class="price-item">2 WEEKS:</span> <span class="price-value">5 €</span></li>
                    <li><span class="price-item">1 MONTH:</span> <span class="price-value">10 €</span></li>
                    <li><span class="price-item">PERMANENT:</span> <span class="price-value">50 € <span class="limited">[LIMITED]</span></span></li>
                </ul>
            </div>
            
            <!-- Level PRICE -->
            <div class="price-section">
                <h3 class="pricing-category">Level PRICE</h3>
                <ul class="price-list">
                    <li><span class="price-item">5 Levels:</span> <span class="price-value">3 €</span></li>
                    <li><span class="price-item">10 Levels:</span> <span class="price-value">5 €</span></li>
                    <li><span class="price-item">20 Levels:</span> <span class="price-value">10 €</span></li>
                    <li><span class="price-item">30 Levels:</span> <span class="price-value">15 €</span></li>
                    <li><span class="price-item">40 Levels:</span> <span class="price-value">20 €</span></li>
                    <li><span class="price-item">50 Levels:</span> <span class="price-value">25 €</span></li>
                    <li><span class="price-item">70 Levels:</span> <span class="price-value">30 €</span></li>
                </ul>
            </div>
            
            <!-- Payment Information -->
            <div class="payment-info">
                <p>If you are interested in buying something, open a ticket in our Discord server.</p>
                
                <h3 class="payment-methods-title">Available Payment Methods:</h3>
                <ul class="payment-methods">
                    <li><a href="#" class="payment-link">Paypal</a></li>
                    <li><span class="payment-method">Revolut: @andreipxil</span></li>
                </ul>
                
                <p class="payment-thanks">Thank you for supporting the server as always!</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>
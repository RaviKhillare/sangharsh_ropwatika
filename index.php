<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>अंकुर रोपवाटिका - प्रीमियम रोपवाटिका</title>
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="#" class="logo">अंकुर रोपवाटिका <i class="fas fa-leaf"></i></a>
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">मुख्य पृष्ठ</a></li>
                <li><a href="#about" class="nav-link">आमच्याबद्दल</a></li>
                <li><a href="#products" class="nav-link">रोपे खरेदी करा</a></li>
                <li><a href="#services" class="nav-link">सेवा</a></li>
                <li><a href="#contact" class="nav-link">संपर्क</a></li>
                <li><a href="login.php" class="nav-link">एडमीन लॉगिन</a></li>
            </ul>
        </div>
    </nav>

    <!-- Notification Section -->
    <?php
    $notif_sql = "SELECT * FROM notifications ORDER BY id DESC LIMIT 1";
    $notif_result = $conn->query($notif_sql);
    if ($notif_result && $notif_result->num_rows > 0) {
        $notif = $notif_result->fetch_assoc();
    ?>
    <div class="notification-bar">
        <div class="container">
            <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                <p style="display:inline-block;"><i class="fas fa-bullhorn"></i> <?php echo $notif['message']; ?></p>
            </marquee>
        </div>
    </div>
    <?php } ?>

    <!-- Hero Section -->
    <?php
    // Fetch dynamic hero image
    $hero_img = 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1920&q=80'; // Default fallback
    $hero_sql = "SELECT image_url FROM hero_images ORDER BY id DESC LIMIT 1";
    $hero_result = $conn->query($hero_sql);
    if ($hero_result && $hero_result->num_rows > 0) {
        $hero_row = $hero_result->fetch_assoc();
        $hero_img = $hero_row['image_url'];
    }
    ?>
    <section id="home" class="hero" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('<?php echo $hero_img; ?>'); background-size: cover; background-position: center;">
        <div class="hero-content">
            <h1>निसर्गाला आपल्या घरी आणा</h1>
            <p>इनडोअर आणि आउटडोअर रोपे, बियाणे आणि बागकामाच्या साधनांची विस्तृत श्रेणी शोधा.</p>
            <a href="#products" class="btn btn-primary">आत्ताच खरेदी करा</a>
        </div>
    </section>

    <!-- Features / About Snippet -->
    <section id="about" class="features section-padding">
        <div class="container">
            <div class="feature-grid">
                <?php
                $feat_sql = "SELECT * FROM features";
                $feat_result = $conn->query($feat_sql);
                if ($feat_result->num_rows > 0) {
                    while($feat = $feat_result->fetch_assoc()) {
                        echo '<div class="feature-box">';
                        echo '<i class="' . $feat['icon'] . '"></i>';
                        echo '<h3>' . $feat['title'] . '</h3>';
                        echo '<p>' . $feat['description'] . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products section-padding">
        <div class="container">
            <h2 class="section-title">वैशिष्ट्यपूर्ण रोपे</h2>
            <div class="product-grid">
                <?php
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $count = 0;
                    while($row = $result->fetch_assoc()) {
                        $count++;
                        $theme = ($count % 3) == 0 ? 3 : ($count % 3);
                        $btn_class = $theme == 1 ? 'btn-blue' : ($theme == 2 ? 'btn-purple' : 'btn-dark');
                        ?>
                        <div class="card card-<?php echo $theme; ?>">
                            <div class="illustration-box">
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                            </div>
                            <div class="content">
                                <h2><?php echo $row['name']; ?></h2>
                                <p><?php echo $row['description']; ?></p>
                                <p class="price">किंमत: ₹<?php echo $row['price']; ?></p>
                                <button class="<?php echo $btn_class; ?>">कार्टमध्ये टाका</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p style='text-align:center; width:100%;'>कोणतीही उत्पादने सापडली नाहीत.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section-padding">
        <div class="container">
            <h2 class="section-title">आमच्या रोपवाटिकेला भेट द्या</h2>
            <div class="contact-wrapper">
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> १२३ ग्रीन स्ट्रीट, गार्डन सिटी, भारत</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                    <p><i class="fas fa-envelope"></i> info@ankurropvatika.com</p>
                </div>
                <form class="contact-form" action="submit_contact.php" method="POST">
                    <input type="text" name="name" placeholder="तुमचे नाव" required>
                    <input type="email" name="email" placeholder="तुमचा ईमेल" required>
                    <textarea name="message" placeholder="संदेश" rows="5" required></textarea>
                    <button type="submit" class="btn btn-primary">संदेश पाठवा</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 अंकुर रोपवाटिका. सर्व हक्क राखीव.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
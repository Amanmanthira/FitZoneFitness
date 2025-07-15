<?php
$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch plans
$plans = $conn->query("SELECT name, price, duration, features FROM membership_plans ORDER BY id ASC");

// Fetch blogs (latest 3 only)
$blogs = $conn->query("SELECT title, content, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FitZone Fitness Center</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">

  <!-- AOS for Scroll Animations -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Lightbox -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />

  <style>
    * {
      margin: 0; padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: #f9f9f9; color: #333;
      scroll-behavior: smooth;

    }
 
 #custom-cursor {
    position: fixed;
    top: 0; left: 0;
    width: 92px;
    height: 92px;
    background: url('src/curser.png') no-repeat center center;
    background-size: contain;
    pointer-events: none; /* so it doesn't block clicks */
    transform: translate(-50%, -50%);
    transition: transform 0.1s ease-out;
    z-index: 9999;
  }



    /* Video Background Styling */
header {
  position: relative;
  height: 100vh;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
}

#heroVideo {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 1;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
  /* Optional overlay styling */
  background: rgba(0, 0, 0, 0.3);
}

.hero-content {
  position: relative;
  z-index: 2;
}

    section {
      padding: 60px 20px;
      margin: auto;
    }

    .section-title {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 40px;
      color: #222;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
    }

    .card:hover {
      transform: scale(1.03);
    }

   

    
    /* FAQ Accordion */
    .faq-item {
      background: white;
      margin-bottom: 15px;
      padding: 20px;
      border-radius: 10px;
      cursor: pointer;
    }

    .faq-answer {
      display: none;
      padding-top: 10px;
      color: #444;
    }

    /* Footer */
    footer {
      background: #222;
      color: #ccc;
      text-align: center;
      padding: 25px;
    }

    footer a {
      color: #ff3d00;
      text-decoration: none;
      margin: 0 10px;
    }

    @media (max-width: 600px) {
      header h1 { font-size: 2.5rem; }
    }
  </style>
</head>

<body>
  <div id="custom-cursor"></div>
<script>
  const cursor = document.getElementById('custom-cursor');
  window.addEventListener('mousemove', e => {
    cursor.style.top = e.clientY + 'px';
    cursor.style.left = e.clientX + 'px';
  });
</script>
<!-- Navigation Bar -->
<nav style="position: fixed; top: 0; left: 0; width: 100%; background: rgba(0,0,0,0.7); padding: 15px 30px; z-index: 10; display: flex; justify-content: center; align-items: center; font-family: 'Segoe UI', sans-serif; backdrop-filter: blur(10px);">
  <div style="width: 100%; max-width: 1200px; display: flex; justify-content: space-between; align-items: center;">
    <a href="#" style="color: white; font-size: 1.8rem; font-weight: bold; text-decoration: none;">❚█══█❚ FitZone</a>
    <ul style="list-style: none; display: flex; gap: 25px; margin: 0; padding: 0;">
      <li><a href="#about" style="color: white; text-decoration: none; font-weight: 500;">About</a></li>
      <li><a href="#programs" style="color: white; text-decoration: none; font-weight: 500;">Programs</a></li>
      <li><a href="#membership" style="color: white; text-decoration: none; font-weight: 500;">Membership</a></li>
      <li><a href="#trainers" style="color: white; text-decoration: none; font-weight: 500;">Trainers</a></li>
      <li><a href="#contact" style="color: white; text-decoration: none; font-weight: 500;">Contact</a></li>
      <li><a href="login.php" style="color: white; padding: 8px 18px; background: #ff3d00; border-radius: 20px; text-decoration: none; font-weight: bold; transition: background 0.3s;">Login</a></li>
    </ul>
  </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="height: 80px;"></div>

<!-- Hero Section with Video -->
<header>
  <video autoplay muted loop playsinline id="heroVideo">
    <source src="src/hero.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>
  <button onclick="toggleSound()" style="position: absolute; bottom: 20px; right: 20px; z-index: 3; background: rgba(0,0,0,0.6); color: white; border: none; padding: 10px 15px; border-radius: 5px;">🔊 Sound</button>
  <div class="hero-overlay"></div>
</header>


<!-- About Section -->
<section  id="about"
  class="about" 
  style="background: linear-gradient(to right, #b5adadff, #f0f4f8); padding: 80px 20px; text-align: center; animation: fadeIn 1s ease-in-out;"
>
  <h2 
    class="section-title" 
    style="font-size: 2.5rem; font-weight: bold; color: #333333; margin-bottom: 20px;"
  >
    About <span style="color: #00B894;">FitZone</span>
  </h2>
  <p 
    style="max-width: 800px; margin: auto; font-size: 1.125rem; color: #555555; line-height: 1.6;"
  >
    FitZone Fitness Center is Kurunegala’s premier gym, featuring state-of-the-art equipment, certified trainers, 
    and personalized fitness plans. But we’re more than just a gym—we’re a supportive community dedicated 
    to helping you build a stronger, healthier future.
  </p>

  <!-- Inline fade-in animation -->
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</section>


<!-- Programs -->
<section id="programs" class="programs" style="padding: 80px 20px; background: linear-gradient(to right, #f8fbff, #eef4f9); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center;">
  <h2 class="section-title" style="font-size: 2.8rem; font-weight: 700; margin-bottom: 50px; color: #2c3e50;">
    Our <span style="color: #00b894;">Programs</span>
  </h2>

  <div class="grid" style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; max-width: 1200px; margin: 0 auto;">
    
    <!-- Card 1 -->
    <div class="card" style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); width: 300px; transition: transform 0.4s ease, box-shadow 0.4s ease;">
      <h3 style="font-size: 1.5rem; color: #333333; margin-bottom: 15px;">Cardio Training</h3>
      <p style="color: #555555; font-size: 1rem; line-height: 1.6;">Improve heart health, burn calories, and build endurance with our intensive cardio classes.</p>
    </div>

    <!-- Card 2 -->
    <div class="card" style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); width: 300px; transition: transform 0.4s ease, box-shadow 0.4s ease;">
      <h3 style="font-size: 1.5rem; color: #333333; margin-bottom: 15px;">Strength Training</h3>
      <p style="color: #555555; font-size: 1rem; line-height: 1.6;">Build muscle and tone your body with weightlifting sessions and resistance workouts.</p>
    </div>

    <!-- Card 3 -->
    <div class="card" style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); width: 300px; transition: transform 0.4s ease, box-shadow 0.4s ease;">
      <h3 style="font-size: 1.5rem; color: #333333; margin-bottom: 15px;">Yoga & Flexibility</h3>
      <p style="color: #555555; font-size: 1rem; line-height: 1.6;">Enhance mental calmness and body flexibility with expert-led yoga classes.</p>
    </div>
  </div>

  <!-- Hover and animation effects -->
  <style>
    .card:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .programs {
      animation: fadeInUp 1s ease-in-out;
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</section>

<section id="membership" style="padding: 80px 20px; background: linear-gradient(90deg, #0a0a0a 0%, #1a1a1a 100%); color: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <h2 style="text-align: center; font-size: 2.8rem; font-weight: 700; margin-bottom: 50px; color: #7ef1ff;">
    Membership <span style="color: #ffffff;">Plans</span>
  </h2>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">

    <?php while ($row = $plans->fetch_assoc()) { ?>
      <div style="
        background: linear-gradient(145deg, #222222, #1a1a1a);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(30, 144, 255, 0.12);
        transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.3s ease;
        text-align: center;
        cursor: default;
      "
      onmouseover="this.style.transform='translateY(-10px) scale(1.04)'; this.style.boxShadow='0 20px 40px rgba(30, 144, 255, 0.3)'; this.style.background='linear-gradient(145deg, #1c84e8, #145bb5)';"
      onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 20px rgba(30, 144, 255, 0.12)'; this.style.background='linear-gradient(145deg, #222222, #1a1a1a)';"
      >
        <h3 style="font-size: 1.7rem; color: #7ef1ff; margin-bottom: 12px; font-weight: 700;"><?php echo htmlspecialchars($row['name']); ?></h3>
        <p style="font-size: 1.3rem; color: #cbd5e1; margin-bottom: 15px; font-weight: 600;">
          Rs. <?php echo htmlspecialchars($row['price']); ?> / <?php echo htmlspecialchars($row['duration']); ?>
        </p>
        <p style="color: #a0aec0; font-size: 1rem; line-height: 1.7; font-weight: 400;">
          <?php echo htmlspecialchars($row['features']); ?>
        </p>
      </div>
    <?php } ?>

  </div>

  <style>
    @keyframes fadeInUpSmooth {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    section {
      animation: fadeInUpSmooth 1s ease forwards;
    }
  </style>
</section>





<section id="trainers" class="trainers" style="padding: 80px 20px; background: #f5f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <h2 class="section-title" style="text-align: center; font-size: 2.8rem; font-weight: 700; margin-bottom: 50px; color: #2c3e50;">
    Meet Our <span style="color: #00b894;">Trainers</span>
  </h2>

  <div class="grid" style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; max-width: 1200px; margin: 0 auto;">

    <!-- Trainer Card 1 -->
    <div class="card" style="background: #ffffff; padding: 30px; border-radius: 15px; width: 280px; text-align: center; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: transform 0.4s ease, box-shadow 0.4s ease;">
      <h3 style="font-size: 1.5rem; color: #333333; margin-bottom: 10px;">John Silva</h3>
      <p style="color: #555555; font-size: 0.95rem; line-height: 1.6;">Certified strength coach with 10+ years experience in bodybuilding & powerlifting.</p>
    </div>

    <!-- Trainer Card 2 -->
    <div class="card" style="background: #ffffff; padding: 30px; border-radius: 15px; width: 280px; text-align: center; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: transform 0.4s ease, box-shadow 0.4s ease;">
      <h3 style="font-size: 1.5rem; color: #333333; margin-bottom: 10px;">Meena Fernando</h3>
      <p style="color: #555555; font-size: 0.95rem; line-height: 1.6;">Yoga & wellness expert focusing on flexibility, mindfulness, and recovery.</p>
    </div>

    <!-- Trainer Card 3 -->
    <div class="card" style="background: #ffffff; padding: 30px; border-radius: 15px; width: 280px; text-align: center; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: transform 0.4s ease, box-shadow 0.4s ease;">
      <h3 style="font-size: 1.5rem; color: #333333; margin-bottom: 10px;">Sam Perera</h3>
      <p style="color: #555555; font-size: 0.95rem; line-height: 1.6;">Cardio specialist & HIIT trainer who creates custom fat-burn routines.</p>
    </div>
  </div>

  <!-- Inline CSS Animations and Hover -->
  <style>
    .card:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .trainers {
      animation: fadeInUpTrainers 1s ease-in-out;
    }

    @keyframes fadeInUpTrainers {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</section>

<section  id="blogs" style="padding: 80px 20px; background: linear-gradient(to right, #ffffff, #f0f4f8); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <h2 style="text-align: center; font-size: 2.8rem; font-weight: 700; margin-bottom: 50px; color: #2c3e50;">
    Latest <span style="color: #00b894;">Blog Posts</span>
  </h2>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
    <?php while ($row = $blogs->fetch_assoc()) { ?>
      <div style="background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: transform 0.4s ease, box-shadow 0.4s ease;"
           onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0, 0, 0, 0.1)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.08)'">

        <h3 style="font-size: 1.5rem; color: #00b894; margin-bottom: 10px;"><?php echo htmlspecialchars($row['title']); ?></h3>
        <p style="color: #555555; font-size: 1rem; line-height: 1.6; margin-bottom: 15px;">
          <?php echo htmlspecialchars(substr($row['content'], 0, 100)); ?>...
        </p>
        <p style="font-size: 0.9rem; color: #999;">Posted on <?php echo date("F j, Y", strtotime($row['created_at'])); ?></p>
      </div>
    <?php } ?>
  </div>

  <!-- Animation -->
  <style>
    @keyframes fadeInBlogLight {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    section {
      animation: fadeInBlogLight 1s ease-in-out;
    }
  </style>
</section>

<!-- Motivational Video Section -->
<section id="motivation-video" style="position: relative; height: 90vh; overflow: hidden; display: flex; align-items: center; justify-content: center;">
  <!-- YouTube Embed -->
  <iframe
    width="100%"
    height="100%"
    src="https://www.youtube.com/embed/TFO9hBtLVec?autoplay=1&mute=1&controls=0&loop=1&playlist=TFO9hBtLVec&rel=0&modestbranding=1&showinfo=0"
    title="Motivational Video"
    frameborder="0"
    allow="autoplay; encrypted-media"
    allowfullscreen
    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;"
  ></iframe>

  <!-- Overlay -->
  <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(44, 42, 42, 0.5); z-index: 2;"></div>

  
</section>



<section class="testimonials" style="background: #ffffff; padding: 80px 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; color: #344055;">
  <h2 style="font-size: 3rem; font-weight: 800; margin-bottom: 60px; letter-spacing: 2px; color: #1e2a38;">
    What Our <span style="color: #00b894;">Members Say</span>
  </h2>

  <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; max-width: 1100px; margin: 0 auto;">

    <!-- Testimonial Card -->
    <div style="
      background: #ffffff;
      border-radius: 20px;
      padding: 40px 35px 50px;
      max-width: 420px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      border: 1.5px solid #e1e8f7;
      position: relative;
      transition: transform 0.35s ease, box-shadow 0.35s ease;
      cursor: default;
      ">
      <!-- Quote Icon -->
      <svg width="30" height="30" fill="#a3b1c2" style="position: absolute; top: 25px; left: 30px;" viewBox="0 0 24 24">
        <path d="M7.17 6.17a5 5 0 0 1 7.66 6.66l-2.04-2.04a2 2 0 0 0-3.2 2.44L12 15H7v-4.83l.17-.17Zm9.66 0a5 5 0 0 1 7.66 6.66l-2.04-2.04a2 2 0 0 0-3.2 2.44L21 15h-5v-4.83l.83-.83Z"/>
      </svg>

      <p style="font-size: 1.1rem; line-height: 1.7; color: #4a5a74; margin-bottom: 35px; padding-left: 50px;">
        “FitZone completely changed my life. The trainers are amazing, the equipment is excellent, and I feel stronger every day.”
      </p>

      <div style="display: flex; align-items: center; gap: 15px;">
        <div style="
          width: 50px; height: 50px;
          border-radius: 50%;
          background: linear-gradient(135deg, #b3c7f9 0%, #91a7ff 100%);
          color: white;
          font-weight: 700;
          font-size: 1.4rem;
          display: flex;
          align-items: center;
          justify-content: center;
          user-select: none;
          box-shadow: 0 3px 10px rgba(145, 158, 171, 0.3);
          ">
          N
        </div>
        <span style="font-weight: 700; font-size: 1rem; color: #344055;">Nadeesha K.</span>
      </div>
    </div>

    <!-- Testimonial Card -->
    <div style="
      background: #ffffff;
      border-radius: 20px;
      padding: 40px 35px 50px;
      max-width: 420px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      border: 1.5px solid #e1e8f7;
      position: relative;
      transition: transform 0.35s ease, box-shadow 0.35s ease;
      cursor: default;
      ">
      <!-- Quote Icon -->
      <svg width="30" height="30" fill="#a3b1c2" style="position: absolute; top: 25px; left: 30px;" viewBox="0 0 24 24">
        <path d="M7.17 6.17a5 5 0 0 1 7.66 6.66l-2.04-2.04a2 2 0 0 0-3.2 2.44L12 15H7v-4.83l.17-.17Zm9.66 0a5 5 0 0 1 7.66 6.66l-2.04-2.04a2 2 0 0 0-3.2 2.44L21 15h-5v-4.83l.83-.83Z"/>
      </svg>

      <p style="font-size: 1.1rem; line-height: 1.7; color: #4a5a74; margin-bottom: 35px; padding-left: 50px;">
        “The atmosphere is welcoming and motivating. Easily the best gym in Kurunegala with real results and great people.”
      </p>

      <div style="display: flex; align-items: center; gap: 15px;">
        <div style="
          width: 50px; height: 50px;
          border-radius: 50%;
          background: linear-gradient(135deg, #f9a8d4 0%, #f87272 100%);
          color: white;
          font-weight: 700;
          font-size: 1.4rem;
          display: flex;
          align-items: center;
          justify-content: center;
          user-select: none;
          box-shadow: 0 3px 10px rgba(249, 168, 212, 0.3);
          ">
          R
        </div>
        <span style="font-weight: 700; font-size: 1rem; color: #344055;">Ruwan P.</span>
      </div>
    </div>

  </div>

  <script>
    // Add subtle hover effect on cards (for smooth scaling)
    document.querySelectorAll('.testimonials div[style*="max-width"]').forEach(card => {
      card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-12px) scale(1.05)';
        card.style.boxShadow = '0 20px 38px rgba(0,0,0,0.12)';
      });
      card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) scale(1)';
        card.style.boxShadow = '0 8px 24px rgba(0,0,0,0.1)';
      });
    });
  </script>
</section>




<!-- Gallery -->
<section class="gallery" style="padding: 80px 20px; background: linear-gradient(145deg, #111, #1c1c1c); color: white; text-align: center;">
  <h2 class="section-title" data-aos="fade-up" style="font-size: 3rem; margin-bottom: 40px; color: #fff; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">Gallery</h2>
  
  <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
    
    <a href="assets/images/gallery1.jpg" data-lightbox="fitzone" style="overflow: hidden; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.6); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.8)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.6)'">
      <img src="https://static.wixstatic.com/media/ae6abf_2c382d4213ca4996b7cc2348dc504dc0~mv2.jpg/v1/fill/w_2500,h_1666,al_c/ae6abf_2c382d4213ca4996b7cc2348dc504dc0~mv2.jpg" alt="Gym 1" style="width: 100%; height: auto; display: block; filter: grayscale(20%); transition: filter 0.3s ease;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(20%)'">
    </a>

    <a href="https://img.grouponcdn.com/iam/41XbcCup5FSj7q6et7R7Tk4RSXuy/41-2048x1229/v1/t2001x1212.webp" data-lightbox="fitzone" style="overflow: hidden; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.6); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.8)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.6)'">
      <img src="https://img.grouponcdn.com/iam/41XbcCup5FSj7q6et7R7Tk4RSXuy/41-2048x1229/v1/t2001x1212.webp" alt="Gym 2" style="width: 100%; height: auto; display: block; filter: grayscale(20%); transition: filter 0.3s ease;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(20%)'">
    </a>

    <a href="https://livefitgym.com/wp-content/uploads/2024/07/Screenshot-2024-07-18-104426-960x637.png" data-lightbox="fitzone" style="overflow: hidden; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.6); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.8)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.6)'">
      <img src="https://livefitgym.com/wp-content/uploads/2024/07/Screenshot-2024-07-18-104426-960x637.png" alt="Gym 3" style="width: 100%; height: auto; display: block; filter: grayscale(20%); transition: filter 0.3s ease;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(20%)'">
    </a>

  </div>
</section>


<section class="faq" style="max-width: 700px; margin: 60px auto; padding: 0 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #2e3a59;">
  <h2 style="font-size: 2.8rem; font-weight: 700; text-align: center; margin-bottom: 50px; color: #1c2538; letter-spacing: 1.5px;">
    Frequently Asked Questions
  </h2>

  <div style="border-radius: 12px; background: #f5f7fb; box-shadow: 0 8px 25px rgba(46, 58, 89, 0.08); overflow: hidden;">

    <div class="faq-item" onclick="toggleFAQ(this)" style="cursor: pointer; border-bottom: 1px solid #d9e2ec; padding: 20px 25px; transition: background-color 0.3s ease;">
      <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0; display: flex; justify-content: space-between; align-items: center; color: #344767;">
        What are your opening hours?
        <span style="font-size: 1.6rem; user-select: none; transition: transform 0.3s ease;" class="faq-icon">+</span>
      </h3>
      <div class="faq-answer" style="max-height: 0; overflow: hidden; color: #546e7a; font-size: 1rem; margin-top: 10px; line-height: 1.5; transition: max-height 0.35s ease, padding 0.35s ease;">
        We’re open from 5am to 11pm, Monday to Sunday.
      </div>
    </div>

    <div class="faq-item" onclick="toggleFAQ(this)" style="cursor: pointer; border-bottom: 1px solid #d9e2ec; padding: 20px 25px; transition: background-color 0.3s ease;">
      <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0; display: flex; justify-content: space-between; align-items: center; color: #344767;">
        Do you offer diet planning?
        <span style="font-size: 1.6rem; user-select: none; transition: transform 0.3s ease;" class="faq-icon">+</span>
      </h3>
      <div class="faq-answer" style="max-height: 0; overflow: hidden; color: #546e7a; font-size: 1rem; margin-top: 10px; line-height: 1.5; transition: max-height 0.35s ease, padding 0.35s ease;">
        Yes! Our premium and gold members receive personalized nutrition plans.
      </div>
    </div>

  </div>

  <script>
    function toggleFAQ(element) {
      const answer = element.querySelector('.faq-answer');
      const icon = element.querySelector('.faq-icon');

      if (answer.style.maxHeight && answer.style.maxHeight !== "0px") {
        answer.style.maxHeight = null;
        answer.style.paddingTop = "0";
        answer.style.paddingBottom = "0";
        icon.style.transform = "rotate(0deg)";
        element.style.backgroundColor = "#f5f7fb";
      } else {
        // Close any other open answers (optional)
        document.querySelectorAll('.faq-answer').forEach(ans => {
          ans.style.maxHeight = null;
          ans.style.paddingTop = "0";
          ans.style.paddingBottom = "0";
        });
        document.querySelectorAll('.faq-icon').forEach(ic => ic.style.transform = "rotate(0deg)");
        document.querySelectorAll('.faq-item').forEach(item => item.style.backgroundColor = "#f5f7fb");

        answer.style.maxHeight = answer.scrollHeight + "px";
        answer.style.paddingTop = "12px";
        answer.style.paddingBottom = "12px";
        icon.style.transform = "rotate(45deg)";
        element.style.backgroundColor = "#e3e8f4";
      }
    }
  </script>
</section>

<!-- Newsletter -->
<section class="newsletter-section" data-aos="zoom-in" style="text-align: center; padding: 60px 20px; background: #f9f9f9; border-radius: 12px; max-width: 1600px; margin: auto; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
  <h2 style="font-size: 2rem; margin-bottom: 10px; color: #333;">Stay Updated with <span style="color: #ff3d00;">FitZone</span></h2>
  <p style="font-size: 1rem; color: #666;">Subscribe to our newsletter for tips, updates, and exclusive offers!</p>
  
  <form style="margin-top: 30px; display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">
    <input 
      type="email" 
      placeholder="Enter your email" 
      required 
      style="padding: 12px 16px; border: 1px solid #ccc; border-radius: 30px; width: 250px; font-size: 1rem;"
    >
    <button 
      type="submit" 
      style="padding: 12px 24px; border: none; background-color: #ff3d00; color: white; border-radius: 30px; font-size: 1rem; cursor: pointer; transition: background 0.3s;"
      onmouseover="this.style.backgroundColor='#e63600';"
      onmouseout="this.style.backgroundColor='#ff3d00';"
    >
      Subscribe
    </button>
  </form>
</section>


<section id="contact" class="contact" style="padding: 80px 20px; max-width: 1100px; margin: 0 auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #222;">
  <h2 class="section-title" data-aos="fade-up" style="text-align: center; font-size: 2.8rem; font-weight: 700; letter-spacing: 2px; margin-bottom: 50px; color: #0f0f0fff; text-transform: uppercase; ">
    Contact <span style="color: #00b894;">Us</span>
  </h2>
  
  <div class="grid" style="display: flex; flex-wrap: wrap; gap: 50px; justify-content: space-between;">

    <!-- Contact Details -->
    <div style="flex: 1 1 400px; min-width: 320px; background: #1e1e1e; border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(255, 69, 0, 0.3); color: #f5f5f5;">
      <h3 style="font-size: 1.8rem; margin-bottom: 25px; border-bottom: 2px solid #ff4500; padding-bottom: 10px; letter-spacing: 1.5px;">Location</h3>
      <p style="font-size: 1.1rem; margin-bottom: 18px; line-height: 1.5;">
        123 Main Street,<br>Kurunegala, Sri Lanka
      </p>
      <p style="font-size: 1.1rem; margin-bottom: 12px;"><strong>Email:</strong> <a href="mailto:contact@fitzone.lk" style="color: #ff784e; text-decoration: none;">contact@fitzone.lk</a></p>
      <p style="font-size: 1.1rem;"><strong>Phone:</strong> <a href="tel:+94771234567" style="color: #ff784e; text-decoration: none;">+94 77 123 4567</a></p>
    </div>

    <!-- Map -->
    <div style="flex: 1 1 550px; min-width: 320px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(255, 69, 0, 0.3);">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18..."
        width="100%" height="350" style="border:0;"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" 
        title="FitZone Fitness Center Location"
      ></iframe>
    </div>

  </div>
</section>


<footer style="
  background: linear-gradient(135deg, #1a1a1a, #121212);
  color: #ccc;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding: 50px 20px 30px;
  text-align: center;
  box-shadow: inset 0 2px 8px rgba(255,255,255,0.05);
">

  <div style="
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 40px;
    align-items: flex-start;
  ">

    <!-- About -->
    <div style="flex: 1 1 250px; min-width: 250px; color: #bbb;">
      <h3 style="color: #7ef1ff; font-weight: 700; margin-bottom: 15px;">FitZone Fitness Center</h3>
      <p style="line-height: 1.6; font-size: 0.95rem;">
        Kurunegala’s premier fitness hub — committed to your health, strength, and community.
      </p>
    </div>

    <!-- Quick Links -->
    <div style="flex: 1 1 150px; min-width: 150px; color: #bbb; text-align: left;">
      <h4 style="color: #7ef1ff; font-weight: 700; margin-bottom: 15px;">Quick Links</h4>
      <nav style="display: flex; flex-direction: column; gap: 10px;">
        <a href="#" style="color: #bbb; text-decoration: none; font-size: 0.95rem; transition: color 0.3s;">Home</a>
        <a href="#" style="color: #bbb; text-decoration: none; font-size: 0.95rem; transition: color 0.3s;">Programs</a>
        <a href="#" style="color: #bbb; text-decoration: none; font-size: 0.95rem; transition: color 0.3s;">Membership</a>
        <a href="#" style="color: #bbb; text-decoration: none; font-size: 0.95rem; transition: color 0.3s;">Contact</a>
      </nav>
    </div>

    <!-- Newsletter Signup -->
    <div style="flex: 1 1 280px; min-width: 280px; color: #bbb; text-align: left;">
      <h4 style="color: #7ef1ff; font-weight: 700; margin-bottom: 15px;">Subscribe to our Newsletter</h4>
      <form onsubmit="event.preventDefault(); alert('Thank you for subscribing!');" style="display: flex; gap: 10px; max-width: 320px;">
        <input type="email" required placeholder="Your email address" style="
          flex: 1;
          padding: 10px 15px;
          border-radius: 30px;
          border: none;
          outline: none;
          font-size: 0.95rem;
          background: #2a2a2a;
          color: #eee;
          transition: background 0.3s;
        " onfocus="this.style.background='#3b3b3b'" onblur="this.style.background='#2a2a2a'">
        <button type="submit" style="
          background: #7ef1ff;
          border: none;
          border-radius: 30px;
          padding: 10px 20px;
          font-weight: 700;
          color: #121212;
          cursor: pointer;
          transition: background 0.3s;
        " onmouseover="this.style.background='#59c6d9'" onmouseout="this.style.background='#7ef1ff'">Subscribe</button>
      </form>
    </div>

  </div>

  <!-- Social Media -->
  <div style="margin-top: 40px;">
    <a href="#" aria-label="Facebook" style="margin: 0 12px; color: #bbb; transition: color 0.3s;" onmouseover="this.style.color='#7ef1ff'" onmouseout="this.style.color='#bbb'">
      <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.877v-6.987H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.466h-1.26c-1.243 0-1.63.772-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.991 22 12z"/>
      </svg>
    </a>
    <a href="#" aria-label="Instagram" style="margin: 0 12px; color: #bbb; transition: color 0.3s;" onmouseover="this.style.color='#7ef1ff'" onmouseout="this.style.color='#bbb'">
      <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
        <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 2A3.75 3.75 0 004 7.75v8.5A3.75 3.75 0 007.75 20h8.5a3.75 3.75 0 003.75-3.75v-8.5A3.75 3.75 0 0016.25 4h-8.5zm9.5 2.25a1 1 0 110 2 1 1 0 010-2zm-4.25 2a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6z"/>
      </svg>
    </a>
    <a href="#" aria-label="YouTube" style="margin: 0 12px; color: #bbb; transition: color 0.3s;" onmouseover="this.style.color='#7ef1ff'" onmouseout="this.style.color='#bbb'">
      <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
        <path d="M10 15l5.19-3L10 9v6zm11.54-3.95c-.21-1.56-1.63-2.79-3.38-2.95-2.98-.28-7.46-.28-7.46-.28s-4.48 0-7.46.28c-1.75.16-3.17 1.39-3.38 2.95-.18 1.33-.18 4.12-.18 4.12s0 2.79.18 4.12c.21 1.56 1.63 2.79 3.38 2.95 2.98.28 7.46.28 7.46.28s4.48 0 7.46-.28c1.75-.16 3.17-1.39 3.38-2.95.18-1.33.18-4.12.18-4.12s0-2.79-.18-4.12z"/>
      </svg>
    </a>
  </div>

  <p style="margin-top: 40px; font-size: 0.9rem; color: #555;">
    © <script>document.write(new Date().getFullYear());</script> FitZone Fitness Center | Kurunegala, Sri Lanka
  </p>
</footer>


<!-- Scripts -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
  AOS.init();

  function toggleFAQ(element) {
    const answer = element.querySelector('.faq-answer');
    answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
  }

  function toggleSound() {
    const video = document.getElementById('heroVideo');
    video.muted = !video.muted;
    alert(video.muted ? 'Sound Off' : 'Sound On');
  }
</script>

</body>
</html>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MUSEBOOK</title>
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="style1.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
   <link rel="stylesheet" href="botstyle.css">

  </head>

<body id="top">
<!-- chat-body -->
 <!-- Chatbot Icon -->
 <div class="chat-icon" id="chat-icon">
        <i class="fas fa-comments"></i>
    </div>
    
    <!-- Chatbot Container -->
    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <h2 id="museum-title">Museum Assistant</h2>
            <div class="actions">
                <select id="museum-selector" class="museum-selector">
                    <option value="victoria">Victoria Memorial</option>
                    <option value="national">National Museum</option>
                    <option value="salarjung">Salar Jung Museum</option>
                    <option value="csmvs">CSMVS</option>
                    <option value="calico">Calico Museum</option>
                </select>
                <select id="language-selector" class="language-selector">
                    <option value="en">English</option>
                    <option value="hi">हिंदी</option>
                    <option value="bn">বাংলা</option>
                </select>
                <button class="close-btn" id="close-chat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div id="chat-messages" class="chat-messages">
            <!-- Messages will be added here by JavaScript -->
        </div>
        
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Type your message...">
            <button id="send-button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

<!-- Include Razorpay SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Include chatbot script -->
<script src="smusebot.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const openChatbot = urlParams.get('chatbot');

    if (openChatbot === '1') {
        const chatIcon = document.getElementById('chat-icon');
        if (chatIcon) {
            chatIcon.click(); // Simulate a click to open the chatbot
        }

        // Remove the 'chatbot' parameter from the URL
        urlParams.delete('chatbot');
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.history.replaceState({}, document.title, newUrl);
    }
});
</script>

 <!-- chat-body -->
  <header class="header">

    <div class="overlay"></div>

    <div class="header-top">
      <div class="container">

        <!-- Add logo image here -->
        <a href="#" class="logo">
        <img  src="favicon.svg" alt="Tourly logo" />
        </a>

        <!-- Move header-btn-group to the right side -->
        <div class="header-btn-group" style="margin-left: auto;">
          <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
          <?php else: ?>

          <button class="login-btn btn btn-secondary" aria-label="Login" onclick="window.location.href='login.php'">Login</button>
          <?php endif; ?>
          <button class="nav-open-btn" aria-label="Open Menu" data-nav-open-btn>
            <ion-icon name="menu-outline"></ion-icon>
          </button>
        </div>

      </div>
    </div>

    <div class="header-bottom">
      <div class="container" style="display: flex; justify-content: center;">

      
        
        <ul class="social-list">
          <li>
            <a href="https://www.facebook.com/login/" class="social-link" target="_blank">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://twitter.com/login" class="social-link" target="_blank">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>
          <li>
            <a href="https://www.youtube.com/" class="social-link" target="_blank">
              <ion-icon name="logo-youtube"></ion-icon>
            </a>
          </li>
        </ul>
       

        <nav class="navbar" data-navbar>

          <div class="navbar-top">

            <!-- Remove the logo image -->
            
            <!-- <a href="#" class="logo">
              <img src="favicon.svg" alt="Tourly logo">
            </a>
            -->

            <button class="nav-close-btn" aria-label="Close Menu" data-nav-close-btn>
              <ion-icon name="close-outline"></ion-icon>
            </button>

          </div>

          <ul class="navbar-list">

            <li>
              <a href="#home" class="navbar-link">home</a>
            </li>

            <li>
              <a href="#about" class="navbar-link">about us</a> 
            </li>

            <li>
              <a href="#destination" class="navbar-link">destination</a>
            </li>

            <li>
              <a href="#package" class="navbar-link">packages</a>
            </li>

            <li>
              <a href="#gallery" class="navbar-link">gallery</a>
            </li>

            <li>
              <a href="#contact" class="navbar-link">contact us</a>
            </li>

          </ul>

        </nav>

        

      </div>
    </div>

  </header>





  <main>
    <article>



      <section class="hero" id="home">
        <div class="container">

          <h2 class="h1 hero-title">Journey to explore India</h2>

          <p class="hero-text">
          
          </p>

          <div class="btn-group">
  <a href="mybookings.php" class="btn btn-primary">My Booking</a>
  <button class="btn btn-secondary" id="bookNow">Book Now</button>
</div>


        </div>
      </section>







      <section class="tour-search">
    <div class="container">

        <form action="" class="tour-search-form" id="tourSearchForm">

            <div class="input-wrapper">
                <label for="destination" class="input-label">Search Museum*</label>
                <select name="destination" id="destination" required class="input-field">
                    <option value="" disabled selected>Select a Museum</option>
                    <option value="Victoria Memorial">Victoria Memorial</option>
                    <option value="National Museum">National Museum</option>
                    <option value="Salar Jung Museum">Salar Jung Museum</option>
                    <option value="Chhatrapati Shivaji Maharaj Vastu Sangrahalaya">Chhatrapati Shivaji Maharaj Vastu Sangrahalaya</option>
                    <option value="Calico Museum of Textiles">Calico Museum of Textiles</option>
                </select>
            </div>

            <div class="input-wrapper">
                <label for="people" class="input-label">No. of Tickets*</label>
                <input type="number" name="people" id="people" required placeholder="Enter No. of Tickets" class="input-field">
            </div>

            <div class="input-wrapper">
                <label for="checkin" class="input-label">Checkin Time*</label>
                <input type="time" name="checkin" id="checkin" required class="input-field" value="10:00" readonly>
            </div>

            <div class="input-wrapper">
                <label for="checkout" class="input-label">Checkout Time*</label>
                <input type="time" name="checkout" id="checkout" required class="input-field" value="17:00" readonly>
            </div>

            <button type="button" class="btn btn-secondary" id="checkAvailability">Check Availability</button>

        </form>

        <!-- Availability Section -->
        <div id="availabilityResult" class="availability-result" style="margin-top: 20px;">
            <!-- Ticket availability will be displayed here -->
        </div>

    </div>
</section>

<script>
document.getElementById("checkAvailability").addEventListener("click", function () {
    const museumName = document.getElementById("destination").value.trim();
    const ticketCount = parseInt(document.getElementById("people").value.trim());

    // Validate input
    if (!museumName || isNaN(ticketCount) || ticketCount <= 0) {
        alert("Please enter a valid museum name and number of tickets.");
        return;
    }

    if (ticketCount > 10) {
        alert("The number of tickets cannot exceed 10.");
        return;
    }

    // Send AJAX request to fetch availability
    fetch(`check_availability.php?museum_name=${encodeURIComponent(museumName)}&people_count=${ticketCount}`)
        .then(response => response.json())
        .then(data => {
            const resultContainer = document.getElementById("availabilityResult");
            resultContainer.innerHTML = ""; // Clear previous results

            if (data.length === 0) {
                resultContainer.innerHTML = `<p>No availability found for the selected museum.</p>`;
                return;
            }

            // Create a grid layout for cards
            const grid = document.createElement("div");
            grid.style.display = "grid";
            grid.style.gridTemplateColumns = "repeat(auto-fit, minmax(180px, 1fr))"; // Adjusted min width for smaller cards
            grid.style.gap = "15px"; // Reduced gap between cards

            // Generate cards for each date
            data.forEach(row => {
                const card = document.createElement("div");
                card.style.border = "1px solid #ddd";
                card.style.borderRadius = "8px"; // Slightly smaller border radius
                card.style.padding = "10px"; // Reduced padding
                card.style.boxShadow = "0 2px 4px rgba(0, 0, 0, 0.1)"; // Slightly smaller shadow
                card.style.backgroundColor = "#fff";
                card.style.textAlign = "center";
                card.style.display = "flex";
                card.style.flexDirection = "column";
                card.style.justifyContent = "center";
                card.style.alignItems = "center";

                card.innerHTML = `
                    <h3 style="margin-bottom: 8px; color: #4361ee; font-size: 16px;">${row.date}</h3>
                    <p style="margin-bottom: 8px; font-size: 14px;">Tickets Available: <strong>${row.available_tickets}</strong></p>
                    <button style="padding: 8px 16px; background-color: ${row.can_book ? '#3880ff' : '#dc3545'}; color: white; border: none; border-radius: 5px; cursor: ${row.can_book ? 'pointer' : 'not-allowed'}; margin-top: 10px;" 
                        ${row.can_book ? `onclick="redirectToBookNow('${museumName}', '${row.date}')"` : 'disabled'}>
                        ${row.can_book ? 'Click to Book' : 'Fully Booked'}
                    </button>
                `;

                grid.appendChild(card);
            });

            resultContainer.appendChild(grid);
        })
        .catch(error => {
            console.error("Error fetching availability:", error);
            alert("An error occurred while checking availability.");
        });
});

// Redirect function
function redirectToBookNow(museumName, visitDate) {
    const url = `booknow.php?museum=${encodeURIComponent(museumName)}&visit_date=${encodeURIComponent(visitDate)}`;
    window.location.href = url;
}
</script>





      <!-- 
        - #POPULAR
      -->

      <section class="popular" id="destination">
        <div class="container">

          <p class="section-subtitle">Uncover place</p>

          <h2 class="h2 section-title">Popular destination</h2>

          <p class="section-text">
            
          </p>

          <ul class="popular-list">

            <li>
              <div class="popular-card">

                <figure class="card-img">
                  <img src="popular5.jpg" alt="Salar jung Museum" loading="lazy">
                </figure>

                <div class="card-content">

                  <div class="card-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>

                  <p class="card-subtitle">
                    <a href="#">Hydrabad</a>
                  </p>

                  <h3 class="h3 card-title">
                    <a href="#">Salar jung Museum</a>
                  </h3>

                  <p class="card-text">
                    One of the largest one-man collections of antiques in the world.
                  </p>

                </div>

              </div>
            </li>

            <li>
              <div class="popular-card">

                <figure class="card-img">
                  <img src="popular6.jpg" alt="Odisha State Museum" loading="lazy">
                </figure>

                <div class="card-content">

                  <div class="card-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>

                  <p class="card-subtitle">
                    <a href="#">Bhubaneswar</a>
                  </p>

                  <h3 class="h3 card-title">
                    <a href="#">Odisha State Museum</a>
                  </h3>

                  <p class="card-text">
                    one of the most prominent museums in the state of Odisha, India.
                  </p>

                </div>

              </div>
            </li>

            <li>
              <div class="popular-card">

                <figure class="card-img">
                  <img src="popular4.jpg" alt="National Museum" loading="lazy">
                </figure>

                <div class="card-content">

                  <div class="card-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>

                  <p class="card-subtitle">
                    <a href="#">New Delhi</a>
                  </p>

                  <h3 class="h3 card-title">
                    <a href="#">National Museum</a>
                  </h3>

                  <p class="card-text">
                    One of the largest museums in India.
                  </p>

                </div>

              </div>
            </li>

          </ul>

          <button class="btn btn-primary" onclick="window.location.href='destination.html'">More destination</button>

        </div>
      </section>





      <!-- 
        - #PACKAGE
      -->

      <section class="package" id="package">
        <div class="container">

          <p class="section-subtitle">Checkout Our</p>

          <h2 class="h2 section-title">Museum Ticket Prices</h2>

          <p class="section-text">
          We offer a variety of ticket options to make your visit accessible and enjoyable.
           General admission is available for all age groups, with discounted rates for students and senior citizens.
            Children under the age of 5 can enter free of charge. We also provide special pricing for school visits and group bookings. 
            Ticket prices may vary depending on the museum or exhibition, so please check the details below before planning your visit.
          </p>

          <ul class="package-list">

            <li>
              <div class="package-card">

                <figure class="card-banner">
                  <img src="pokemon2.jpg" alt="Victoria Memorial" loading="lazy">
                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">Victoria Memorial </h3>

                  <p class="card-text">
                    A grand marble building housing a museum with a collection of British colonial artifacts, paintings,
                    and documents.
                  </p>

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="time"></ion-icon>

                        <p class="text">8AM-10PM</p>
                      </div>
                    </li>

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="people"></ion-icon>

                        <p class="text">NOP: 10</p>
                      </div>
                    </li>

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="location"></ion-icon>

                        <p class="text">Kolkata</p>
                      </div>
                    </li>

                  </ul>

                </div>

                <div class="card-price">

                  <div class="wrapper">

                    <p class="reviews">(25 reviews)</p>

                    <div class="card-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>

                  </div>

                  <p class="price">
                    ₹120
                    <span>/ per person</span>
                  </p>

                  <button class="btn btn-secondary" onclick="redirectToBooking('Victoria Memorial')">Book Now</button>

                </div>

              </div>
            </li>

            <li>
              <div class="package-card">

                <figure class="card-banner">
                  <img src="pokemon.jpg" alt="Chhatrapati Shivaji Maharaj Vastu Sangrahalaya"
                    loading="lazy">
                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">Chhatrapati Shivaji Maharaj Vastu Sangrahalaya</h3>

                  <p class="card-text">
                    A premier museum in Mumbai, known for its collection of ancient Indian art, sculptures, and
                    artifacts.
                  </p>

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="time"></ion-icon>

                        <p class="text">10AM-9PM</p>
                      </div>
                    </li>

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="people"></ion-icon>

                        <p class="text">NOP: 10</p>
                      </div>
                    </li>

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="location"></ion-icon>

                        <p class="text">Mumbai</p>
                      </div>
                    </li>

                  </ul>

                </div>

                <div class="card-price">

                  <div class="wrapper">

                    <p class="reviews">(20 reviews)</p>

                    <div class="card-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>

                  </div>

                  <p class="price">
                    ₹120
                    <span>/ per person</span>
                  </p>

                  <button class="btn btn-secondary" onclick="redirectToBooking('Chhatrapati Shivaji Maharaj Vastu Sangrahalaya')">Book Now</button>

                </div>

              </div>
            </li>

            <li>
              <div class="package-card">

                <figure class="card-banner">
                  <img src="pokemon1.jpg" alt="Calico Museum of Textiles" loading="lazy">
                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">Calico Museum of Textiles</h3>

                  <p class="card-text">
                    A museum dedicated to the history and art of Indian textiles, particularly focusing on the rich
                    textile traditions of Gujarat.
                  </p>

                  <ul class="card-meta-list">

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="time"></ion-icon>

                        <p class="text">9AM-7PM</p>
                      </div>
                    </li>

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="people"></ion-icon>

                        <p class="text">NOP: 10</p>
                      </div>
                    </li>

                    <li class="card-meta-item">
                      <div class="meta-box">
                        <ion-icon name="location"></ion-icon>

                        <p class="text">Ahmedabad</p>
                      </div>
                    </li>

                  </ul>

                </div>

                <div class="card-price">

                  <div class="wrapper">

                    <p class="reviews">(40 reviews)</p>

                    <div class="card-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>

                  </div>

                  <p class="price">
                    ₹120
                    <span>/ per person</span>
                  </p>

                  
                  <button class="btn btn-secondary" onclick="redirectToBooking('Calico Museum of Textiles')">Book Now</button>


                </div>

              </div>
            </li>

          </ul>

          <button class="btn btn-primary" onclick="window.location.href='viewalltickets.html'">View All Tickets</button>

        </div>
      </section>





      <!-- 
        - #GALLERY
      -->

      <section class="gallery" id="gallery">
        <div class="container">

          <p class="section-subtitle">Photo Gallery</p>

          <h2 class="h2 section-title">Photo's From Visitors</h2>

          <p class="section-text">
          Discover the museum through the eyes of our visitors!
From stunning exhibits to memorable moments, these snapshots capture the joy, curiosity, and wonder experienced by our guests.

Want to be featured? Tag us in your photos or upload your favorite museum memories!
           </p>

          <ul class="gallery-list">

            <li class="gallery-item">
              <figure class="gallery-image">
                <img src="gallery-6.jpg" alt="Gallery image">
              </figure>
            </li>

            <li class="gallery-item">
              <figure class="gallery-image">
                <img src="gallery-7.jpg" alt="Gallery image">
              </figure>
            </li>

            <li class="gallery-item">
              <figure class="gallery-image">
                <img src="gallery-9.jpg" alt="Gallery image">
              </figure>
            </li>

            <li class="gallery-item">
              <figure class="gallery-image">
                <img src="gallery-8.jpg" alt="Gallery image">
              </figure>
            </li>

            <li class="gallery-item">
              <figure class="gallery-image">
                <img src="gallery-10.jpg" alt="Gallery image">
              </figure>
            </li>

          </ul>

        </div>
      </section>





      <!-- 
        - #CTA
      -->

      <section class="cta" id="contact">
        <div class="container">

          <div class="cta-content">
            <p class="section-subtitle">Call To Action</p>

            <h2 class="h2 section-title">Ready For Unforgatable Experience. Remember Us!</h2>

            <p class="section-text">
            Have questions, need help, or want to know more?
            We’re here for you!
           </p>
          </div>

          <button class="btn btn-secondary">Contact Us !</button>

        </div>
      </section>

      <section class="about-us" id="about">
        <div class="container">
          <h2 class="h2 section-title">About Us</h2>
          <p class="section-text">
          Welcome to Muse-Book,MuseBot is an AI-powered chatbot that helps you book tickets, get museum info, and ask questions—all in your preferred language. Enjoy a quick, friendly, and hassle-free experience, right from chat!</p>
          </p>
          <p class="section-text">
          Founded in 2025 by SQUAD 4 , It provides multilingual support, answers frequently asked questions, and guides users through the ticket booking process in a conversational format</p>
          </p>
          <p class="section-text">
          Users can select their preferred museum, language, number of tickets, and visit date—all through an easy-to-use chat interface.</p>
          </p>
        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="footer-top">
      <div class="container">

        <div class="footer-brand">

          <div class="id">
            <div class="svg2">
              <h2 style="color: rgb(184, 191, 210) ;"><B>MuseBook</B></h2>
            </div>
          </div>
          <p class="footer-text">
          MuseBot is an interactive AI-powered chatbot designed to simplify the ticket booking experience for museum visitors.
           It provides multilingual support, answers frequently asked questions, and guides users through the ticket booking process in a conversational format.
           Users can select their preferred museum, language, number of tickets, and visit date, all through a smooth chat interface.
           </p>

        </div>

        <div class="footer-contact">

          <h4 class="contact-title">Contact Us</h4>

          <p class="contact-text">
            Feel free to contact and reach us !!
          </p>

          <ul>

            <li class="contact-item">
              <ion-icon name="call-outline"></ion-icon>

              <a href="tel:+01123456790" class="contact-link">+91 9692058554</a>
            </li>

            <li class="contact-item">
              <ion-icon name="mail-outline"></ion-icon>

              <a href="mailto:info.tourly.com" class="contact-link">info.musebook.com</a>
            </li>

            <li class="contact-item">
              <ion-icon name="location-outline"></ion-icon>

              <address>patia,Bhubaneswar</address>
            </li>

          </ul>

        </div>

        <div class="footer-form">

          <p class="form-text">
            Subscribe our newsletter for more update & news !!
          </p>

          <form action="" class="form-wrapper">
            <input type="email" name="email" class="input-field" placeholder="Enter Your Email" required>

            <button type="submit" class="btn btn-secondary">Subscribe</button>
          </form>

        </div>

        <!-- Add social media icons -->
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          &copy; 2024 <a href="">BookWithMuseBook</a>. All rights reserved
        </p>

        <ul class="footer-bottom-list">

          <li>
            <a href="#" class="footer-bottom-link">Privacy Policy</a>
          </li>

          <li>
            <a href="#" class="footer-bottom-link">Term & Condition</a>
          </li>

          <li>
            <a href="#" class="footer-bottom-link">FAQ</a>
          </li>

        </ul>

      </div>
    </div>

  </footer>

  <!-- Add Move to Top Button -->
  <a href="#top" class="move-to-top" id="moveToTop" style="display: none; position: fixed; bottom: 20px; left: 20px; background-color: #4361ee; color: white; padding: 10px 15px; border-radius: 50%; text-align: center; font-size: 18px; cursor: pointer; z-index: 1000;">
      <i class="fas fa-arrow-up"></i>
  </a>

  <script>
  document.addEventListener('scroll', () => {
      const moveToTopButton = document.getElementById('moveToTop');
      if (window.scrollY > 200) {
          moveToTopButton.style.display = 'block';
      } else {
          moveToTopButton.style.display = 'none';
      }
  });

  document.getElementById('moveToTop').addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
  });
  </script>

  <!-- 
    - #GO TO TOP
  -->

  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up-outline"></ion-icon>
  </a>

  <!-- 
    - custom js link
  -->
  <script src="script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
document.getElementById("bookNow").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
    document.getElementById("package").scrollIntoView({ behavior: "smooth" });
});
function redirectToBooking(museum) {
    const url = `booknow.php?museum=${encodeURIComponent(museum)}`;
    window.location.href = url; // Redirect without price
}
</script>


</body>

</html>
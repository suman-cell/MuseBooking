<?php
session_start();
include 'db.php';

// Get the selected visit date from the URL parameter or default to today's date
$selected_date = isset($_GET['visit_date']) ? $_GET['visit_date'] : date('Y-m-d');

// Fetch ticket availability for each museum on the selected date
$query = "SELECT m.museum_name AS museum, 
                 GREATEST(10 - COALESCE(SUM(b.adult_tickets + b.child_tickets), 0), 0) AS available_tickets
          FROM museums m
          LEFT JOIN bookings b 
          ON m.museum_name = b.museum 
          AND b.booking_status != 'Cancelled' 
          AND b.visit_date = '$selected_date'
          GROUP BY m.museum_name";
$result = mysqli_query($conn, $query);

$availability = [];
while ($row = mysqli_fetch_assoc($result)) {
    $availability[$row['museum']] = (int)$row['available_tickets'];
}

// Get the selected museum from the URL parameter
$selected_museum = isset($_GET['museum']) ? $_GET['museum'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Museum Ticket Booking</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --secondary: #3f37c9;
            --accent: #f72585;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #ef233c;
            --gray: #6c757d;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        header {
            text-align: center;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .booking-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .form-section {
            background: var(--light);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .form-section h2 {
            font-size: 1.5rem;
            color: var(--secondary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        input, select {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .ticket-selector {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--white);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .ticket-info {
            flex: 1;
        }

        .ticket-info h3 {
            font-size: 1rem;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .ticket-info p {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .ticket-controls {
            display: flex;
            align-items: center;
        }

        .ticket-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .ticket-btn:hover {
            background: var(--secondary);
        }

        .ticket-btn:disabled {
            background: #e2e8f0;
            cursor: not-allowed;
        }

        .ticket-count {
            margin: 0 1rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        .summary-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 1.5rem;
            border-radius: 12px;
            color: var(--white);
        }

        .summary-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .museum-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .museum-icon {
            font-size: 2rem;
            margin-right: 1rem;
            color: var(--white);
        }

        .museum-info h3 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .museum-info p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .ticket-summary {
            margin-bottom: 1.5rem;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ticket-row:last-child {
            border-bottom: none;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1.5rem 0;
            text-align: right;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background: #d91a6d;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        .availability-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: var(--success);
            color: white;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .sold-out {
            background: var(--danger);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .booking-card {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 1rem;
                margin: 1rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section, .summary-section {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .summary-section {
            animation-delay: 0.1s;
        }

        /* Styles for the availability box */
        .availability-box {
            margin-top: 1rem;
            padding: 1rem;
            border: 2px solid #6c757d; /* Default border color */
            border-radius: 8px;
            background-color: #f8f9fa;
            font-size: 1rem;
            color: #343a40;
            transition: border-color 0.3s ease;
        }

        .availability-box h3 {
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
            color: #495057;
        }

        .availability-box p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Museum Ticket Booking</h1>
            <p class="subtitle">Explore the wonders of history and culture with our easy booking system</p>
        </header>

        <div class="booking-card">
            <div class="form-section">
                <h2><i class="fas fa-user-circle"></i> Visitor Information</h2>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" placeholder="John Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="9876543210" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="visit_date">Visit Date</label>
                    <div class="input-icon">
                        <i class="fas fa-calendar-day"></i>
                        <input type="date" id="visit_date" name="visit_date" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="event">Select Museum</label>
                    <div class="input-icon">
                        <i class="fas fa-landmark"></i>
                        <select id="event" name="event" required>
                            <option value="National Museum" <?= ($selected_museum == 'National Museum') ? 'selected' : '' ?> <?= ($availability['National Museum'] ?? 10) == 0 ? 'disabled' : '' ?>>
                                National Museum
                            </option>
                            <option value="Salar Jung Museum" <?= ($selected_museum == 'Salar Jung Museum') ? 'selected' : '' ?> <?= ($availability['Salar Jung Museum'] ?? 10) == 0 ? 'disabled' : '' ?>>
                                Salar Jung Museum
                            </option>
                            <option value="Victoria Memorial" <?= ($selected_museum == 'Victoria Memorial') ? 'selected' : '' ?> <?= ($availability['Victoria Memorial'] ?? 10) == 0 ? 'disabled' : '' ?>>
                                Victoria Memorial
                            </option>
                            <option value="Chhatrapati Shivaji Maharaj Vastu Sangrahalaya" <?= ($selected_museum == 'Chhatrapati Shivaji Maharaj Vastu Sangrahalaya') ? 'selected' : '' ?> <?= ($availability['Chhatrapati Shivaji Maharaj Vastu Sangrahalaya'] ?? 10) == 0 ? 'disabled' : '' ?>>
                                CSMVS Museum
                            </option>
                            <option value="Calico Museum of Textiles" <?= ($selected_museum == 'Calico Museum of Textiles') ? 'selected' : '' ?> <?= ($availability['Calico Museum of Textiles'] ?? 10) == 0 ? 'disabled' : '' ?>>
                                Calico Textile Museum
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Box to display available tickets -->
                <div id="availabilityBox" class="availability-box">
                    <h3>Available Tickets</h3>
                    <p id="availabilityText">Select a museum to see availability.</p>
                </div>

                <h2 style="margin-top: 2rem;"><i class="fas fa-ticket-alt"></i> Ticket Selection</h2>

                <div class="ticket-selector">
                    <div class="ticket-info">
                        <h3>Adult Ticket</h3>
                        <p>₹120 per person (Age 12+)</p>
                    </div>
                    <div class="ticket-controls">
                        <button class="ticket-btn" id="decreaseAdult">-</button>
                        <span class="ticket-count" id="adultCount">0</span>
                        <button class="ticket-btn" id="increaseAdult">+</button>
                    </div>
                </div>

                <div class="ticket-selector">
                    <div class="ticket-info">
                        <h3>Child Ticket</h3>
                        <p>₹60 per person (Under 12)</p>
                    </div>
                    <div class="ticket-controls">
                        <button class="ticket-btn" id="decreaseChild">-</button>
                        <span class="ticket-count" id="childCount">0</span>
                        <button class="ticket-btn" id="increaseChild">+</button>
                    </div>
                </div>

                <input type="hidden" id="adults" name="adults" value="0">
                <input type="hidden" id="children" name="children" value="0">
            </div>

            <div class="summary-section">
                <h2><i class="fas fa-receipt"></i> Booking Summary</h2>
                
                <div class="museum-card">
                    <div class="museum-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="museum-info">
                        <h3 id="summary-museum">Select a Museum</h3>
                        <p id="summary-date">Select a date</p>
                    </div>
                </div>

                <div class="ticket-summary"></div>

                <div class="total-amount" id="totalAmount">
                    ₹0
                </div>

                <button class="btn" id="proceedToPayment">
                    <i class="fas fa-lock"></i> Proceed to Secure Payment
                </button>

                <div style="margin-top: 1rem; text-align: center; font-size: 0.8rem; opacity: 0.8;">
                    <i class="fas fa-shield-alt"></i> Your information is secure with us
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('visit_date').min = today;
            document.getElementById('visit_date').value = '<?= $selected_date ?>';

            // Ticket controls
            const adultCount = document.getElementById('adultCount');
            const childCount = document.getElementById('childCount');
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');
            
            let adults = 0;
            let children = 0;

            // Update summary information
            function updateSummary() {
                document.getElementById('summary-museum').textContent = document.getElementById('event').value;
                document.getElementById('summary-date').textContent = document.getElementById('visit_date').value;
                
                const adultPrice = adults * 120;
                const childPrice = children * 60;
                const total = adultPrice + childPrice;
                
                // Update ticket summary rows dynamically
                const ticketSummary = document.querySelector('.ticket-summary');
                ticketSummary.innerHTML = ''; // Clear existing rows

                if (adults > 0) {
                    const adultRow = document.createElement('div');
                    adultRow.className = 'ticket-row';
                    adultRow.innerHTML = `<span>Adult Tickets (x${adults}):</span><span>₹${adultPrice}</span>`;
                    ticketSummary.appendChild(adultRow);
                }

                if (children > 0) {
                    const childRow = document.createElement('div');
                    childRow.className = 'ticket-row';
                    childRow.innerHTML = `<span>Child Tickets (x${children}):</span><span>₹${childPrice}</span>`;
                    ticketSummary.appendChild(childRow);
                }

                document.getElementById('totalAmount').textContent = `₹${total}`;
                
                // Update hidden inputs
                adultsInput.value = adults;
                childrenInput.value = children;

                // Ensure booking summary ticket counts match
                document.getElementById('adultCount').textContent = adults;
                document.getElementById('childCount').textContent = children;
            }

            // Adult ticket controls
            document.getElementById('increaseAdult').addEventListener('click', function() {
                adults++;
                adultCount.textContent = adults;
                updateSummary();
            });

            document.getElementById('decreaseAdult').addEventListener('click', function() {
                if (adults > 0) {
                    adults--;
                    adultCount.textContent = adults;
                    updateSummary();
                }
            });

            // Child ticket controls
            document.getElementById('increaseChild').addEventListener('click', function() {
                children++;
                childCount.textContent = children;
                updateSummary();
            });

            document.getElementById('decreaseChild').addEventListener('click', function() {
                if (children > 0) {
                    children--;
                    childCount.textContent = children;
                    updateSummary();
                }
            });

            // Update summary when museum or date changes
            document.getElementById('event').addEventListener('change', updateSummary);
            document.getElementById('visit_date').addEventListener('change', function() {
                const selectedDate = this.value;
                
                // Fetch updated availability
                fetch(`fetch_availability.php?visit_date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        const eventDropdown = document.getElementById('event');
                        
                        // Update each option's availability badge
                        eventDropdown.querySelectorAll('option').forEach(option => {
                            const museum = option.value;
                            const available = data[museum] || 0;
                            
                            if (available <= 0) {
                                option.disabled = true;
                            } else {
                                option.disabled = false;
                            }
                        });
                        
                        updateSummary();
                    })
                    .catch(error => {
                        console.error('Error fetching availability:', error);
                        alert('Failed to fetch ticket availability. Please try again.');
                    });
            });

            // Initialize summary
            updateSummary();

            // Payment button handler
            document.getElementById('proceedToPayment').addEventListener('click', function() {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const visitDate = document.getElementById('visit_date').value;
                const selectedMuseum = document.getElementById('event').value;
                const totalAmount = parseInt(document.getElementById('totalAmount').textContent.replace('₹', ''));

                // Validation
                if (!name || !email || !phone || !visitDate || !selectedMuseum) {
                    alert('Please fill in all required fields.');
                    return;
                }

                if (totalAmount <= 0) {
                    alert('Please select at least one ticket.');
                    return;
                }

                // Name validation
                const nameRegex = /^[a-zA-Z\s]+$/;
                if (!nameRegex.test(name)) {
                    alert("Name must contain only letters and spaces!");
                    return;
                }

                // Email validation
                const emailRegex = /^[a-z]+(?:\.[a-z]+)*[a-z0-9]*@[a-z0-9]+\.[a-z]{2,}$/;
                if (!emailRegex.test(email)) {
                    alert("Invalid email format! Email must start with lowercase letters, dots cannot be consecutive, and numbers are allowed only after letters.");
                    return;
                }

                if (email.length < 16 || email.length > 40) {
                    alert("Email must be between 16 to 40 characters!");
                    return;
                }

                // Phone validation
                const phoneRegex = /^[6-9]\d{9}$/;
                if (!phoneRegex.test(phone)) {
                    alert("Phone number must be exactly 10 digits and start with 6, 7, 8, or 9!");
                    return;
                }

                // Ensure not all digits are the same
                if (/^(\d)\1{9}$/.test(phone)) {
                    alert("Phone number cannot have all digits the same!");
                    return;
                }

                // Ensure no more than three consecutive identical digits
                if (/(\d)\1{3,}/.test(phone)) {
                    alert("Phone number cannot have more than three consecutive identical digits!");
                    return;
                }

                // Visit date validation
                const today = new Date();
                const selectedDate = new Date(visitDate);
                if (selectedDate < today.setHours(0, 0, 0, 0)) {
                    alert("Visit date must be today or a future date!");
                    return;
                }

                // Check ticket availability
                fetch(`fetch_availability.php?visit_date=${visitDate}`)
                    .then(response => response.json())
                    .then(data => {
                        const availableTickets = data[selectedMuseum] || 0;
                        const totalTickets = adults + children;

                        if (totalTickets > availableTickets) {
                            alert(`Sorry, only ${availableTickets} tickets are available for ${selectedMuseum} on ${visitDate}. Please adjust your selection.`);
                            return;
                        }

                        // Proceed with payment
                        const formData = {
                            billing_name: name,
                            billing_email: email,
                            billing_mobile: phone,
                            shipping_name: name,
                            shipping_email: email,
                            shipping_mobile: phone,
                            paymentOption: 'museum_ticket',
                            payAmount: totalAmount,
                            action: 'payOrder'
                        };

                        return fetch('submitpayment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(formData)
                        });
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.res === 'success') {
                            const options = {
                                key: data.razorpay_key,
                                amount: data.userData.amount * 100,
                                currency: 'INR',
                                name: 'Museum Ticket Booking',
                                description: data.userData.description,
                                order_id: data.userData.rpay_order_id,
                                handler: function (response) {
                                    const urlParams = new URLSearchParams({
                                        oid: data.order_number,
                                        rp_payment_id: response.razorpay_payment_id,
                                        rp_signature: response.razorpay_signature,
                                        email: data.userData.email,
                                        name: data.userData.name,
                                        phone: data.userData.mobile,
                                        visit_date: visitDate,
                                        museum: selectedMuseum,
                                        adults: adults,
                                        children: children,
                                        total: totalAmount
                                    });
                                    window.location.href = `payment-success.php?${urlParams.toString()}`;
                                },
                                prefill: {
                                    name: data.userData.name,
                                    email: data.userData.email,
                                    contact: data.userData.mobile
                                },
                                theme: {
                                    color: '#4361ee'
                                }
                            };
                            const rzp = new Razorpay(options);
                            rzp.open();
                        } else {
                            alert(data.info || 'Payment initialization failed. Please try again.');
                        }
                    })
                  
            });

            // Availability box update
            const eventDropdown = document.getElementById('event');
            const visitDateInput = document.getElementById('visit_date');
            const availabilityBox = document.getElementById('availabilityBox');
            const availabilityText = document.getElementById('availabilityText');

            function updateAvailability() {
                const selectedMuseum = eventDropdown.value;
                const selectedDate = visitDateInput.value;

                fetch(`fetch_availability.php?visit_date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        const availableTickets = data[selectedMuseum] || 0;

                        if (availableTickets > 0) {
                            availabilityText.textContent = `${availableTickets} tickets available for ${selectedMuseum} on ${selectedDate}.`;
                            availabilityBox.style.borderColor = '#28a745';
                        } else {
                            availabilityText.textContent = `No tickets available for ${selectedMuseum} on ${selectedDate}.`;
                            availabilityBox.style.borderColor = '#dc3545';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching availability:', error);
                        availabilityText.textContent = 'Error fetching ticket availability. Please try again.';
                        availabilityBox.style.borderColor = '#ffc107';
                    });
            }

            eventDropdown.addEventListener('change', updateAvailability);
            visitDateInput.addEventListener('change', updateAvailability);
            updateAvailability();
        });
    </script>
</body>
</html>
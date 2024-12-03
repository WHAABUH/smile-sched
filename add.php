    <?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: http://localhost/smile-sched/login.php");
        exit();
    }

    // Greet the user
    $fullname = htmlspecialchars($_SESSION['fullname']);
    $tomorrow = date("Y-m-d", strtotime("+2 day"));
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./CSS/add.css" />
        <title>Home</title>
    </head>
    <body>
        <div class="nav-bar">
            <div class="logo-title">
                <img src="./Images/logo.png" alt="logo">
                <h1>Smile-Sched</h1>
            </div>
            <div class="links">
                <a href="home.php" id="home">Home</a>
                <a href="services.php" id="services">Services</a>
                <a href="./pending.php" id="reservation">Appointment</a>
                <a href="./add.php" id="add" style="text-decoration: underline; font-weight: bolder;">Add</a>
            </div>
            <div class="logout">
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>
        </div>
        
        <div class="main-container">
            <div class="container">
                <!-- Page 1 -->
                <div class="page active">

                    <div class="values-container">
                        <div class="service">
                            <label for="service">Select a Service</label>
                            <select name="service" id="service">
                                <option value="Consultation">Consultation</option>
                                <option value="Cleaning">Cleaning</option>
                                <option value="Whitening">Whitening</option>
                                <option value="Filling">Filling</option>
                                <option value="Wisdom Removal">Wisdom Removal</option>
                                <option value="Tooth Removal">Tooth Removal</option>
                            </select>
                        </div>

                        <div class="amount">
                            <p>Amount:&nbsp;&nbsp;</p>
                            <div class="amount-holder">
                                <p id="amount-text">   ₱ 1000</p>
                            </div>
                        </div>
                    </div>
                   

                    <div class="navigation">
                        <img class="next-button" src="./Images/next-button.png" alt="next">
                    </div>

                </div>
                
                <!-- Page 2 -->
                <div class="page">

                    <div class="values-container">

                        <div class="date">
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" min="<?php echo $tomorrow; ?>">
                        </div>

                        <div class="time">
                            <label for="time">Time:</label>
                            <select name="time">
                                <option value="9AM - 10AM">9AM - 10AM</option>
                                <option value="10AM - 11AM">10AM - 11AM</option>
                                <option value="11AM - 12PM">11AM - 12PM</option>
                                <option value="1PM - 2PM">1PM - 2PM</option>
                                <option value="2PM - 3PM">2PM - 3PM</option>
                                <option value="4PM - 5PM">4PM - 5PM</option>
                                <option value="5PM - 6PM">5PM - 6PM</option>
                            </select>
                        </div>

                    </div>

                    
                    <div class="navigation" id="datetime-nav">
                        <img class="prev-button" src="./Images/previous-button.png" alt="next">
                        <img class="next-button" src="./Images/next-button.png" alt="next">
                    </div>

                </div>
                
                <!-- Page 3 -->
                <div class="page">

                    <div class="values-container">

                        <div class="dentist">
                            <label for="dentist">Assigned Dentist</label>
                            <div class="name-holder">
                                <p>Leonie von Meusebach–Zesch</p>
                            </div>
                        </div>

                    </div>
                    
                    <div class="navigation">
                        <img class="prev-button" src="./Images/previous-button.png" alt="next">
                        <img class="next-button" src="./Images/next-button.png" alt="next">
                    </div>

                </div>

                <div class="page">

                    <div class="values-container">

                        <div class="service-amount">
                            <div class="receipt-service">
                                <p>Service:&nbsp;</p>
                                <p class="values" id="final-service">Not Selected</p>
                            </div>
                            <div class="receipt-amount">
                                <p>Amount:&nbsp;</p>
                                <p class="values" id="final-amount">Not Selected</p>
                            </div>
                        </div>

                        <div class="date-time">

                            <div class="receipt-date">
                                <p>Date:&nbsp;</p>
                                <p class="values" id="final-date">Not Selected</p>
                            </div>
                            <div class="receipt-time">
                                <p>Time:&nbsp;</p>
                                <p class="values" id="final-time">Not Selected</p>
                            </div>
                            
                        </div>

                        <div class="dentist-assigned">

                            <div class="receipt-dentist">
                                <p>Dentist:&nbsp;</p>
                                <p class="values" id="final-dentist">Leonie von Meusebach–Zesch</p>
                            </div>

                        </div>
                    </div>

                    <div class="navigation">
                        <img class="prev-button" src="./Images/previous-button.png" alt="next">
                        <button id="add-appointment">Set Appointment</button>
                    </div>

                </div>

            </div>
        </div>
        
        <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Prices map
        const prices = {
            "Consultation": 1000,
            "Cleaning": 1500,
            "Whitening": 3000,
            "Filling": 500,
            "Wisdom Removal": 4000,
            "Tooth Removal": 2500
        };

        // Elements for user inputs
        const serviceDropdown = document.getElementById("service");
        const dateInput = document.getElementById("date");
        const timeDropdown = document.querySelector("select[name='time']");
        const amountText = document.getElementById("amount-text");

        // Final values elements
        const finalService = document.getElementById("final-service");
        const finalAmount = document.getElementById("final-amount");
        const finalDate = document.getElementById("final-date");
        const finalTime = document.getElementById("final-time");
        const finalDentist = document.getElementById("final-dentist");

        // Navigation buttons
        const nextButtons = document.querySelectorAll(".next-button");
        const prevButtons = document.querySelectorAll(".prev-button");
        const pages = document.querySelectorAll(".page");
        let currentPage = 0;

        // Update amount dynamically
        serviceDropdown.addEventListener("change", () => {
            const selectedService = serviceDropdown.value;
            amountText.textContent = `₱ ${prices[selectedService]}`;
        });

        // Function to update final values
        const updateFinalValues = () => {
            finalService.textContent = serviceDropdown.value;
            finalAmount.textContent = `₱ ${prices[serviceDropdown.value]}`;
            finalDate.textContent = dateInput.value || "Not Selected";
            finalTime.textContent = timeDropdown.value || "Not Selected";
            finalDentist.textContent = "Leonie von Meusebach–Zesch"; // Static dentist value
        };

        // Navigation logic
        nextButtons.forEach((button, index) => {
            button.addEventListener("click", () => {
                if (currentPage < pages.length - 1) {
                    pages[currentPage].classList.remove("active");
                    currentPage++;
                    pages[currentPage].classList.add("active");

                    // Update final values on last page
                    if (currentPage === pages.length - 1) {
                        updateFinalValues();
                    }
                }
            });
        });

        prevButtons.forEach((button, index) => {
            button.addEventListener("click", () => {
                if (currentPage > 0) {
                    pages[currentPage].classList.remove("active");
                    currentPage--;
                    pages[currentPage].classList.add("active");
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
    const appointmentButton = document.getElementById("add-appointment");

    appointmentButton.addEventListener("click", async () => {
        const service = document.getElementById("final-service").textContent.trim();
        const amount = document.getElementById("final-amount").textContent.replace("₱", "").trim();
        const date = document.getElementById("final-date").textContent.trim();
        const time = document.getElementById("final-time").textContent.trim();
        const dentist = document.getElementById("final-dentist").textContent.trim();

        // Check for empty fields
        if (
            service === "Not Selected" ||
            amount === "Not Selected" ||
            date === "Not Selected" ||
            time === "Not Selected" ||
            dentist === "Not Selected"
        ) {
            alert("Please complete all the fields before proceeding.");
            return;
        }

        // Send data to the server
        const formData = new FormData();
        formData.append("service", service);
        formData.append("amount", amount);
        formData.append("date", date);
        formData.append("time", time);
        formData.append("dentist", dentist);

        try {
            const response = await fetch("process_appointment.php", {
                method: "POST",
                body: formData,
            });
            const result = await response.json();

            if (result.status === "success") {
                alert(result.message);
                window.location.href = "home.php"; // Redirect to home after success
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert("An error occurred. Please try again.");
        }
    });
});

</script>

        <script src="./script.js"></script>
    </body>
</html>

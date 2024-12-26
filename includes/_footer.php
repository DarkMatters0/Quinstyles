<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizontal Footer</title>
    <style>
        .footer {
            background-color: #2c3e50;
            color: #fff;
            padding: 0px 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .footer-container {
            display: flex;
            justify-content: space-around; /* Distribute items horizontally with space between */
            align-items: center;
            max-width: 900px;
            margin: 0 auto;
            text-align: left; /* Align text inside each section to the left */
        }

        .footer-section {
            padding: 10px;
            min-width: 250px; /* Ensure sections maintain a minimum width */
        }

        .footer-section h3, 
        .footer-section h5 {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .footer-section p {
            font-size: 14px;
            margin: 5px 0;
            line-height: 1.0; /* Maintain good spacing */
        }

        .footer-bottom {
            text-align: center;
            margin-top: 0px;
            font-size: 12px;
            color: #bdc3c7;
        }

        @media (max-width: 600px) {
            .footer-container {
                flex-direction: column; /* Stack sections vertically on smaller screens */
                text-align: center; /* Center text for smaller screens */
            }

            .footer-section {
                min-width: 100%; /* Allow sections to take full width */
            }
        }
    </style>
</head>
<body>

<footer class="footer">
    <div class="footer-container">
        <!-- About Us Section -->
        <div class="footer-section">
            <h3>About Us</h3>
            <p>The Garments System simplifies uniform ordering and customization for college students.</p>
        </div>

        <!-- Contact Section -->
        <div class="footer-section">
            <h5>Contact</h5>
            <p>Email: wmsugarmentsystem.edu.ph</p>
            <p>Phone: 0919 381 4277</p>
        </div>

        <!-- Footer Bottom Section -->
        <div class="footer-section footer-bottom">
            <p>&copy; 2024 Garments System. All Rights Reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>

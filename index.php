<?php
session_start();
include 'db.php';

$appointment_id = null;

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Get the next upcoming appointment for this user
$query = "SELECT id FROM appointments 
          WHERE user_id = '$user_id' 
          AND appointment_date >= CURDATE() 
          ORDER BY appointment_date, appointment_time 
          LIMIT 1";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $appointment_id = $row['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NHS Appointment Booking System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      color: #333;
      line-height: 1.6;
      text-align: center;
    }
    h1 {
      color: #005eb8;
      font-size: 2.2rem;
      margin-bottom: 1rem;
      font-weight: bold;
    }
    h2 {
      color: #005eb8;
      font-size: 1.6rem;
      margin: 2rem 0 1rem;
      border-bottom: 2px solid #005eb8;
      padding-bottom: 0.5rem;
      display: inline-block;
    }
    h3 {
      color: #005eb8;
      font-size: 1.3rem;
      margin: 1.5rem 0 0.5rem;
      font-weight: bold;
    }
    .intro-text {
      max-width: 800px;
      margin: 0 auto 2rem;
    }
    .services-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    .service-square {
      border: 2px solid #005eb8;
      border-radius: 5px;
      padding: 25px;
      background-color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 280px;
      transition: transform 0.3s ease;
    }
    .service-square:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .service-square ul {
      padding: 0;
      margin: 15px 0;
      width: 100%;
    }
    .service-square li {
      list-style: none;
      margin-bottom: 15px;
    }
    .btn-nhs {
      background-color: #005eb8;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 4px;
      text-decoration: none;
      width: 80%;
      margin-top: auto;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    .btn-nhs:hover {
      background-color: #003d73;
    }
    .btn-emergency {
      background-color: #da291c;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 4px;
      text-decoration: none;
      width: 80%;
      margin-top: auto;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    .btn-emergency:hover {
      background-color: #a51e15;
    }
    .how-it-works {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .step {
      padding: 0 15px;
    }
    .step-icon {
      background-color: #005eb8;
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 1.8rem;
      font-weight: bold;
    }
    hr {
      border-top: 2px solid #005eb8;
      margin: 40px auto;
      width: 80%;
    }
    .footer {
      background-color: #005eb8;
      color: white;
      padding: 25px 0;
      text-align: center;
      margin-top: 50px;
    }
    .icon {
      margin-right: 10px;
      color: #005eb8;
      font-size: 1.2rem;
    }
    small {
      display: block;
      margin-top: 5px;
      color: #666;
    }
    
    /* Responsive adjustments */
    @media (max-width: 900px) {
      .services-container {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      }
    }
    @media (max-width: 600px) {
      h1 {
        font-size: 1.8rem;
      }
      h2 {
        font-size: 1.4rem;
      }
      .service-square {
        min-height: 240px;
        padding: 20px;
      }
      .how-it-works {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<h1>Welcome to NHS Appointment Booking System</h1>
<p class="intro-text">Book and manage your NHS appointments online, including A&E check ups and general practitioner visits.</p>



<div class="services-container">
  <div class="service-square">
    <h3><i class="fas fa-calendar-alt icon"></i>Book Now</h3>
    <ul>
      <li>Register<small>Create your NHS online account</small></li>
    </ul>
    <a href="book_appointment.php" class="btn-nhs">Book Now</a>
  </div>

  <div class="service-square">
    <h3><i class="fas fa-hospital icon"></i>A&E Check-in</h3>
    <p>Save time by checking in online for A&E visits before arrival.</p>
    <ul>
      <li>
        <a href="<?= $appointment_id ? 'checkin_appointment.php?id=' . $appointment_id : '#' ?>" 
           class="btn-nhs" <?= $appointment_id ? '' : 'disabled' ?>>
           Check In
        </a>
      </li>
    </ul>
  </div>

  <div class="service-square">
    <h3><i class="fas fa-phone-alt icon"></i>Emergency Contact</h3>
    <p>For medical emergencies, always call 999 immediately.</p>
    <ul>
      <li><a href="tel:999" class="btn-emergency">Call 999</a></li>
    </ul>
  </div>
</div>

<hr>

<h2>How It Works</h2>

<div class="how-it-works">
  <div class="step">
    <div class="step-icon">1</div>
    <h3>Register</h3>
    <p>Create your NHS online account</p>
  </div>
  <div class="step">
    <div class="step-icon">2</div>
    <h3>Book</h3>
    <p>Choose your appointment type and time.</p>
  </div>
  <div class="step">
    <div class="step-icon">3</div>
    <h3>Confirm</h3>
    <p>Receive confirmation and reminders.</p>
  </div>
  <div class="step">
    <div class="step-icon">4</div>
    <h3>Attend</h3>
    <p>Check in and see your healthcare provider</p>
  </div>
</div>

<div class="footer">
  &copy; 2025 NHS Appointment Booking System
</div>
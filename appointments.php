<?php
session_start();
if(!isset($_SESSION['username']))
{
    header('Location: login.php?redirect=appointments.php');
    exit;
}

$username = $_SESSION['username'];

include "includes/functions/connect.php";

// Merr doktorët nga databaza
$doctors_query = "SELECT id, username FROM users WHERE role_id = 2";
$doctors_result = mysqli_query($conn, $doctors_query);

// Variabël për të ruajtur mesazhin e statusit për SweetAlert
$swal_script = '';

if (isset($_POST['submit'])) {
    // Merr dhe filtro të dhënat nga forma
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $doctor_id = (int)$_POST['doctor'];

    // Kontrollo nëse të gjitha fushat e detyrueshme janë plotësuar
    if (empty($name) || empty($lastname) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($doctor_id)) {
        $swal_script = "
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill in all the required fields!'
                });
            });
            </script>
        ";
    } else {
        // Formato kohën dhe datën
        $time = date('H:i:s', strtotime($time));
        $appointmentDateTime = $date . ' ' . $time;

        // Kontrollo disponueshmërinë e terminit
        $check_query = "SELECT id FROM appointments 
                       WHERE doctor_id = ? 
                       AND (
                           appointment_date BETWEEN 
                           DATE_SUB(?, INTERVAL 29 MINUTE) AND 
                           DATE_ADD(?, INTERVAL 29 MINUTE)
                       )";
        
        $stmt = mysqli_prepare($conn, $check_query);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "iss", $doctor_id, $appointmentDateTime, $appointmentDateTime);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $swal_script = "
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Time Slot Unavailable',
                        text: 'Sorry, there is an appointment around this time. Please choose a different time.'
                    });
                });
                </script>
            ";
        } else {
            // Regjistro terminin në databazë
            $insert_query = "INSERT INTO appointments 
                            (name, lastname, email, phone, gender, city, appointment_date, message, doctor_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $insert_query);
            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt, "ssssssssi", $name, $lastname, $email, $phone, $gender, $city, $appointmentDateTime, $message, $doctor_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $swal_script = "
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Appointment Scheduled',
                            text: 'Appointment successfully scheduled!',
                            willClose: () => {
                                window.location.href = 'appointments.php';
                            }
                        });
                    });
                    </script>
                ";
            } else {
                $swal_script = "
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: 'Error: Appointment could not be registered. Please try again later!'
                        });
                    });
                    </script>
                ";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
        <title>Appointment</title>
        <link rel="stylesheet" href="menus.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
           #time-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .time-slot {
            padding: 8px 12px;
            background-color: #eaf2f8;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .time-slot:hover {
            background-color: #d4e6f1;
        }
        
        .time-slot.selected {
            background-color: #3498db;
            color: white;
        }
        
        .gender-option {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .gender-option label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        .appointment-paragraph{
            margin-left: 20px; 
            color: #666;
            font-size:16px;
            padding-bottom: 20px;
            text-align:center;
        }
        .form .input-group input, .form .input-group text, .form .input-group textarea{
            padding:0px;
            padding-left:10px;
        }
          @media (max-width: 992px) {
            .content {
                padding: 15px;
            }
            
            .appointment-container {
                padding: 20px;
            }
            
            .form {
                gap: 15px;
            }
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 15px;
            }
            
            .form {
                grid-template-columns: 1fr;
            }
            
            .gender-box,
            .address,
            .input-box.address,
            .input-group:last-child {
                grid-column: span 1;
            }
            
            #time-slots {
                justify-content: center;
            }
            
            .time-slot {
                padding: 6px 10px;
                font-size: 14px;
            }
        }
          @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:15px;
            }
            .appointment-container h1{
                font-size:24px;
            }
            .appointment-paragraph{
                font-size:14px;
            }
            .input-group label, .address-text{
                font-size:14px;
            }
            .gender-box h3{
                font-size:14px;
            }
            .form .input-group input, .form .input-group text, .select-box select{
              height: 35px;
              width: 100%;
              font-size:14px;
            }
            .form .gender label{
                font-size:14px;
            }
            .form .input-group textarea{
                height:100px;
                font-size:14px;
            }
            .form button{
                height:40px;
                font-size:14px;
            }
        }
        </style>
    </head>

    <body>
       <?php include "includes/templates/sidebar.php";?>

      <div class="content">
        <div class="appointment-container">
           <h1>Make an appointment online!</h1>
           <p class="appointment-paragraph">Schedule your appointment quickly and easily by filling out the form below. Choose your preferred date and time. We look forward to seeing you!</p>

        <form action="" method="post" id="appointment-form" class="form">
        <input type="hidden" name="time" id="selected-time" required>

            <div class="input-group">
                <label>Name*</label>
                <input type="text" id="name" placeholder="Enter your name" name="name" required>
            </div>

            <div class="input-group">
                <label>Last Name*</label>
                <input type="text" id="lastname" placeholder="Enter your last name" name="lastname" required>
            </div>

            <div class="input-group">
                <label>Email Address*</label>
                <input type="email" id="email" placeholder="username@gmail.com" name="email" required>
            </div>

            <div class="input-group">
                <label>Phone Number*</label>
                <input type="number" id="phone" placeholder="123456789" name="phone" required>
            </div>
    
            <div class="gender-box">
              <h3>Gender*</h3>
              <div class="gender-option">
                <div class="gender appointment-gender">
                  <input type="radio" name="gender" value="F" checked />
                  <label>Female</label>
                </div>
                <div class="gender appointment-gender">
                  <input type="radio" name="gender" value="M" />
                  <label>Male</label>
                </div>
              </div>
            </div>
            <div class="input-box address">
              <label class="address-text">Select the city you live in*</label>
                <div class="select-box">
                  <select id="city" name="city">
                    <option hidden>Other</option>
                    <option>Prishtine</option>
                    <option>Prizren</option>
                    <option>Peje</option>
                    <option>Gjakove</option>
                  </select>
                </div>
            </div>


            <div class="input-box address">
              <label class="address-text" for="doctor-select">Select Doctor*</label>
              <div class="select-box">
                <select id="doctor-select" name="doctor" required>
                  <option value="" hidden>Select Doctor</option>
                  <?php 
                  mysqli_data_seek($doctors_result, 0);
                  while ($doctor = mysqli_fetch_assoc($doctors_result)): ?>
                      <option value="<?= htmlspecialchars($doctor['id']) ?>">
                        <?= htmlspecialchars($doctor['username']) ?>
                      </option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="input-group">
                <label for="date">Date*</label>
                <input type="text" id="date" name="date" placeholder="Zgjidhni një datë" required>
            </div>
            <div class="input-group">
                <label>Available hours*</label>
                <div id="time-slots">
                    <p>Please select a doctor and a date to see the available hours for appointment!</p>
                </div>
            </div>
            
            <div class="input-group">
              <label for="message">Do you have anything you want to add?</label>
              <textarea id="message" name="message" rows="8" placeholder="Please write your message here if you have something to say... " ></textarea>
            </div>

            <div class="input-group">
              <button type="submit" name="submit" id="submit-btn">Submit</button>
            </div>
        </form>
        </div>

          <?php include "includes/templates/footer.php";?>

      </div> 

<script src="main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#date", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    const doctorSelect = document.getElementById('doctor-select');
    const dateInput = document.getElementById('date');
    const timeSlotsDiv = document.getElementById('time-slots');
    const selectedTimeInput = document.getElementById('selected-time');

    function loadTimeSlots() {
        const doctorId = doctorSelect.value;
        const date = dateInput.value;

        if (!doctorId || !date) {
            timeSlotsDiv.innerHTML = "<p>Please select a doctor and date to see available hours.</p>";
            return;
        }

        fetch(`fetch_times.php?doctor_id=${doctorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                timeSlotsDiv.innerHTML = '';

                if (data.length === 0) {
                    timeSlotsDiv.innerHTML = '<p style="color:red;">There are no free hours for this date.</p>';
                    return;
                }

                data.forEach(time => {
                    const slot = document.createElement('div');
                    slot.className = 'time-slot';
                    slot.textContent = time;
                    slot.addEventListener('click', () => {
                        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                        slot.classList.add('selected');
                        selectedTimeInput.value = time;
                    });
                    timeSlotsDiv.appendChild(slot);
                });
            })
            .catch(err => {
                timeSlotsDiv.innerHTML = "<p>Error loading hours.</p>";
                console.error(err);
            });
    }

    doctorSelect.addEventListener('change', loadTimeSlots);
    dateInput.addEventListener('change', loadTimeSlots);
});

</script>

<?php 
// Shfaq SweetAlert nëse ka mesazh
if (!empty($swal_script)) {
    echo $swal_script;
}
?>

    </body>
</html>
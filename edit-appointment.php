<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'] ?? null;
$appointment_id = intval($_GET['id'] ?? 0);

// Get appointment data
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    header("Location: manage-appointments.php");
    exit();
}

$doctors = $conn->query("SELECT id, username FROM users WHERE role_id = 2");

// Split appointment date and time
$appointmentDateTime = new DateTime($appointment['appointment_date']);
$appointmentDate = $appointmentDateTime->format('Y-m-d');
$appointmentTime = $appointmentDateTime->format('H:i');

if (isset($_POST['edit_appointment'])) {
    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $city = trim($_POST['city']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $message = trim($_POST['message']);
    $doctor_id = intval($_POST['doctor_id']);

    // Check required fields
    if (empty($name) || empty($lastname) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($doctor_id)) {
        echo "<script>alert('Please fill all required fields.')</script>";
    } else {
        // Format time and combine with date
        $time = date('H:i:s', strtotime($time));
        $appointmentDateTime = $date . ' ' . $time;

        // Check for time conflicts (29 minute buffer, excluding current appointment)
        $check_query = "SELECT id FROM appointments 
                       WHERE doctor_id = ? 
                       AND id != ?
                       AND (
                           appointment_date BETWEEN 
                           DATE_SUB(?, INTERVAL 29 MINUTE) AND 
                           DATE_ADD(?, INTERVAL 29 MINUTE)
                       )";
        
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("iiss", $doctor_id, $appointment_id, $appointmentDateTime, $appointmentDateTime);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Sorry, there is an appointment around this time. Please choose a different time.')</script>";
        } else {
            // Update the appointment
            $update_query = "UPDATE appointments SET 
                           name = ?, lastname = ?, email = ?, phone = ?, gender = ?, 
                           city = ?, appointment_date = ?, message = ?, doctor_id = ? 
                           WHERE id = ?";
            
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssssssii", $name, $lastname, $email, $phone, $gender, 
                            $city, $appointmentDateTime, $message, $doctor_id, $appointment_id);
            
            if ($stmt->execute()) {
                echo "<script>
                        alert('Appointment updated successfully!');
                        window.location.href = 'manage-appointments.php';
                      </script>";
                exit;
            } else {
                echo "<script>alert('Error: Appointment could not be updated. Please try again later!')</script>";
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
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        body { background: #f5f7fa; }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 40px auto;
            max-width: 800px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size:16px;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .gender-options {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }
        .gender-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-save {
            background-color: #4CAF50;
        }
        .btn-cancel {
            background-color: #f44336;
        }
        h1 { 
            font-size: 32px; 
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        p.subtitle {
            color:#666;
            font-size:16px;
            margin-top:0px;
            text-align:center;
        }
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
        .btn-cancel:hover{
                background-color: rgb(207, 12, 12);
        }
        .btn-save:hover {
                background-color:rgb(50, 130, 53);
        }
        .footer{
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
         @media (max-width: 580px) {
            .content {
                left: 0%;
            }
            .footer{
                position: relative;
                font-size:10px;
                height:30px;
                padding-top:22px;
                padding-left:70px;
            }
            h1{
                font-size:24px;
            }
            p.subtitle{
                font-size:14px;
                padding:10px;
            }
            .card{
                max-width:342px;
            }
            label{
                font-size:14px;
            }
            input, select, textarea{
                height:35px;
                font-size:12px;
                padding:5px;
            }
            .btn{
                height:30px;
                font-size:12px;
                padding:0px;
                display:flex;
                justify-content:center;
                align-items:center;
            }
            .time-slot{
                width:80px;
                height:35px;
                font-size:12px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Edit Appointment</h1>
    <p class="subtitle">Update the appointment details below. Make sure to verify all information including the patient details, doctor selection, and appointment time before saving changes.</p>

    <div class="card">
        <form method="POST" id="appointment-form">
            <input type="hidden" name="time" id="selected-time" required value="<?= $appointmentTime ?>">
            
            <div class="form-group">
                <label for="name">Patient First Name*</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($appointment['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lastname">Patient Last Name*</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($appointment['lastname']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($appointment['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number*</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($appointment['phone']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Gender*</label>
                <div class="gender-options">
                    <div class="gender-option">
                        <input type="radio" id="male" name="gender" value="M" <?= $appointment['gender'] == 'M' ? 'checked' : '' ?>>
                        <label for="male" style="font-weight:normal">Male</label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" id="female" name="gender" value="F" <?= $appointment['gender'] == 'F' ? 'checked' : '' ?>>
                        <label for="female" style="font-weight:normal">Female</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="city">City*</label>
                <select id="city" name="city" required>
                    <option value="Prishtine" <?= $appointment['city'] == 'Prishtine' ? 'selected' : '' ?>>Prishtine</option>
                    <option value="Prizren" <?= $appointment['city'] == 'Prizren' ? 'selected' : '' ?>>Prizren</option>
                    <option value="Peje" <?= $appointment['city'] == 'Peje' ? 'selected' : '' ?>>Peje</option>
                    <option value="Gjakove" <?= $appointment['city'] == 'Gjakove' ? 'selected' : '' ?>>Gjakove</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="doctor_id">Select Doctor*</label>
                <select id="doctor_id" name="doctor_id" required>
                    <?php while ($doctor = $doctors->fetch_assoc()): ?>
                        <option value="<?= $doctor['id'] ?>" <?= $doctor['id'] == $appointment['doctor_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($doctor['username']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date">Date*</label>
                <input type="text" id="date" name="date" value="<?= $appointmentDate ?>" required>
            </div>
            
            <div class="form-group">
                <label>Available hours*</label>
                <div id="time-slots">
                    <p>Loading available hours...</p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="message">Additional Message</label>
                <textarea id="message" name="message" rows="4"><?= htmlspecialchars($appointment['message']) ?></textarea>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="edit_appointment" class="btn btn-save">Save Changes</button>
                <a href="manage-appointments.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
    
    <?php include "includes/templates/footer.php"; ?>
</div>
<script src="main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#date", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    const doctorSelect = document.getElementById('doctor_id');
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
                    timeSlotsDiv.innerHTML = '<p style="color:red;">No available hours for this date.</p>';
                    return;
                }

                data.forEach(time => {
                    const slot = document.createElement('div');
                    slot.className = 'time-slot';
                    slot.textContent = time;
                    
                    // Mark the current time as selected
                    if (time === selectedTimeInput.value) {
                        slot.classList.add('selected');
                    }
                    
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

    // Initial load
    loadTimeSlots();

    doctorSelect.addEventListener('change', loadTimeSlots);
    dateInput.addEventListener('change', loadTimeSlots);
});
</script>

</body>
</html>
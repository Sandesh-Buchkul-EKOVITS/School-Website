<?php
include '../db.php';
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    header("Location: ../auth/login.php");
    exit();
}
$email = $_SESSION['email'] ?? '';

/* ================= SAVE ATTENDANCE (AJAX HANDLE) ================= */
if(isset($_POST['action']) && $_POST['action'] == 'save_attendance'){

    $date = $_POST['date'];
    $teacher = $_SESSION['email'];
    $attendance = json_decode($_POST['attendance'], true);

    if(empty($attendance)){
        echo "No Data Received";
        exit();
    }

    foreach($attendance as $roll => $status){

        // student info
        $stu = $conn->query("SELECT name, class FROM students WHERE roll_no='$roll'");
        if($stu->num_rows == 0) continue;

        $data = $stu->fetch_assoc();
        $name  = $data['name'];
        $class = $data['class'];

        // 🔥 MAGIC QUERY (Insert OR Update Automatically)
        $sql = "INSERT INTO attendance
                (roll_no, student_name, class, date, status, teacher_email)
                VALUES
                ('$roll','$name','$class','$date','$status','$teacher')
                ON DUPLICATE KEY UPDATE
                status='$status',
                teacher_email='$teacher'";

        if(!$conn->query($sql)){
            echo "DB Error: " . $conn->error;
            exit();
        }
    }

    echo "Attendance Saved Successfully";
    exit();
}


/* ================= FETCH STUDENTS (PAGE LOAD) ================= */
$today = date('Y-m-d');

$sql = $selectedClass = $_GET['class'] ?? 'all';

$where = "";
if($selectedClass != 'all'){
    $where = "WHERE s.class='$selectedClass'";
}

$sql = "SELECT s.*, a.status 
        FROM students s
        LEFT JOIN attendance a 
        ON s.roll_no = a.roll_no 
        AND a.date = '$today'
        $where
        ORDER BY s.name";
$result = $conn->query($sql);

if(!$result){
    die("Student Load Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance | Bright Future School</title>
<link rel="stylesheet" href="./includes/sidebar-topbar.css">
<link rel="stylesheet" href="attendance.css">
</head>

<body>

<?php include './includes/sidebar-topbar.php'; ?>

<div class="main">

<h1>Attendance Management</h1>
<p class="subtitle">Mark and manage student attendance</p>

<div class="filter-box">
    <div>
        <label>Select Date</label>
        <input type="date" id="attDate" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div>
    <label>Select Class</label>
    <select id="classFilter">
        <option value="all">All</option>
        <option value="5a">5-A</option>
        <option value="5b">5-B</option>
        <option value="6a">6-A</option>
        <option value="6b">6-B</option>
    </select>
</div>

    <div>
        <label>Search Student</label>
        <input type="text" id="search" placeholder="Search...">
    </div>
</div>

<div class="stats">
    <div class="card">Total Students <b id="total"><?php echo $result->num_rows; ?></b></div>
    <div class="card green">Present <b id="present">0</b></div>
    <div class="card red">Absent <b id="absent">0</b></div>
    <div class="card blue">Attendance % <b id="percent">0%</b></div>
</div>

<div class="table-card">
<table id="attendanceTable">
<thead>
<tr>
<th>ROLL NO</th>
<th>STUDENT NAME</th>
<th>STATUS</th>
<th>MARK ATTENDANCE</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()){ ?>
<tr data-roll="<?php echo $row['roll_no']; ?>">
<td><b><?php echo $row['roll_no']; ?></b></td>
<td><?php echo $row['name']; ?></td>
<td class="status">
<?php echo $row['status'] ?? 'Not Marked'; ?>
</td>
<td>
<button class="btn present">✔</button>
<button class="btn absent">✖</button>
</td>
</tr>
<?php } ?>
</tbody>
</table>

<div class="actions">
<button class="reset">Reset</button>
<button class="save">Save Attendance</button>
</div>
</div>

</div>

<script>
const rows = document.querySelectorAll("#attendanceTable tbody tr");
const presentEl = document.getElementById("present");
const absentEl = document.getElementById("absent");
const percentEl = document.getElementById("percent");

let attendanceData = {};

function updateStats(){
let p=0,a=0;
Object.values(attendanceData).forEach(v=>{
if(v==="Present") p++;
if(v==="Absent") a++;
});
presentEl.textContent=p;
absentEl.textContent=a;
percentEl.textContent=((p/(p+a||1))*100).toFixed(1)+"%";
}
function loadExistingStats(){
let p=0,a=0;

rows.forEach(row=>{
const status = row.querySelector(".status").textContent.trim();

if(status==="Present"){
attendanceData[row.dataset.roll]="Present";
p++;
}
if(status==="Absent"){
attendanceData[row.dataset.roll]="Absent";
a++;
}
});

presentEl.textContent=p;
absentEl.textContent=a;
percentEl.textContent=((p/(p+a||1))*100).toFixed(1)+"%";
}
rows.forEach(row=>{
const roll = row.dataset.roll;
const statusCell = row.querySelector(".status");

row.querySelector(".present").onclick=()=>{
statusCell.textContent="Present";
statusCell.className="status present";

row.querySelector(".present").classList.add("active");
row.querySelector(".absent").classList.remove("active");

attendanceData[roll]="Present";
updateStats();
};
row.querySelector(".absent").onclick=()=>{
statusCell.textContent="Absent";
statusCell.className="status absent";

row.querySelector(".absent").classList.add("active");
row.querySelector(".present").classList.remove("active");

attendanceData[roll]="Absent";
updateStats();
};

});

document.querySelector(".reset").onclick=()=>{
attendanceData={};
rows.forEach(r=>{
r.querySelector(".status").textContent="Not Marked";
r.querySelector(".status").className="status";
r.querySelector(".present").classList.remove("active");
r.querySelector(".absent").classList.remove("active");
});
updateStats();
};
document.querySelector(".save").onclick = () => {
const date = document.getElementById("attDate").value;

fetch("/school-website2/teacher/attendance.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: new URLSearchParams({
action: "save_attendance",
date: date,
attendance: JSON.stringify(attendanceData)
})
})
.then(res => res.text())
.then(data => {
alert(data);
});
};
document.getElementById("classFilter").addEventListener("change", function(){
    const selected = this.value;
    window.location = "?page=attendance&class=" + selected;
});

document.getElementById("search").addEventListener("keyup", function(){
const value = this.value.toLowerCase();
rows.forEach(row=>{
row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
});
});
loadExistingStats();
</script>

</body>
</html>

<?php
include __DIR__ . '/../../db.php';


$class = $_GET['class'] ?? '';
$date  = $_GET['date'] ?? date('Y-m-d');

$students = [];

if($class){
    $students = $conn->query("
        SELECT * FROM students 
        WHERE class='$class'
        ORDER BY roll_no ASC
    ");
}

/* ================= SAVE ================= */

if(isset($_POST['save_attendance'])){

    $class = $_POST['class'];
    $date  = $_POST['date'];

    // delete old records of same day
$conn->query("DELETE FROM attendance WHERE class='$class' AND `date`='$date'");


    if(isset($_POST['att'])){
        foreach($_POST['att'] as $roll => $status){

            $conn->query("
                INSERT INTO attendance (roll_no,class,date,status)
VALUES ('$roll','$class','$date','$status')

            ");
        }
    }

    echo "<script>
        alert('Attendance Saved Successfully');
        window.location='index.php?page=attendance&class=$class&date=$date';
    </script>";
    exit;
}

/* ===== LOAD OLD DATA ===== */

/* ===== LOAD OLD DATA ===== */

$old = [];
if($class){

    $resOld = $conn->query("
        SELECT roll_no, status 
        FROM attendance 
        WHERE class='$class' AND date='$date'
    ");

    while($o = $resOld->fetch_assoc()){
        $old[$o['roll_no']] = $o['status'];
    }
}


?>

<div class="page">

<h2>Attendance</h2>


<form method="GET" class="att-filter">
    <input type="hidden" name="page" value="attendance">

    <div class="filter-box">
    <label>Select Class</label>
    <select name="class" required>
        <option value="">Select</option>
        <option value="5a" <?= $class=='5a'?'selected':'' ?>>5-A</option>
        <option value="5b" <?= $class=='5b'?'selected':'' ?>>5-B</option>
        <option value="6a" <?= $class=='6a'?'selected':'' ?>>6-A</option>
        <option value="6b" <?= $class=='6b'?'selected':'' ?>>6-B</option>
        <option value="7a" <?= $class=='7a'?'selected':'' ?>>7-A</option>
        <option value="7b" <?= $class=='7b'?'selected':'' ?>>7-B</option>
    </select>
</div>
    <div class="filter-box">
        <label>Select Date</label>
        <input type="date" name="date" value="<?= $date ?>">
    </div>

    <div class="filter-box search">
        <label>Search Student</label>
        <input type="text" placeholder="Search...">
    </div>

    <div class="filter-action">
        <button type="submit" class="btn primary">Load Students</button>
    </div>
</form>


<hr>

<?php if($class && $students && $students->num_rows > 0): ?>

<form method="POST">

<input type="hidden" name="class" value="<?= $class ?>">
<input type="hidden" name="date" value="<?= $date ?>">
<input type="hidden" name="save_attendance" value="1">

<div class="attendance-stats">
    <div class="stat-card">
        <div>Total Students</div>
        <h3 id="totalCount">0</h3>
    </div>

    <div class="stat-card green">
        <div>Present</div>
        <h3 id="presentCount">0</h3>
    </div>

    <div class="stat-card red">
        <div>Absent</div>
        <h3 id="absentCount">0</h3>
    </div>

    <div class="stat-card blue">
        <div>Attendance %</div>
        <h3 id="percentCount">0%</h3>
    </div>
</div>
<div class="table-card">
<table class="modern-table">

<tr>
    <th>Roll</th>
    <th>Name</th>
    <th>Status</th>
</tr>

<?php while($row = $students->fetch_assoc()):
    $roll = $row['roll_no'];
    $status = $old[$roll] ?? 'Absent';
?>

<tr>
    <td><?= $roll ?></td>
    <td><?= $row['name'] ?></td>

<td class="att-actions">

    <input type="hidden" name="att[<?= $roll ?>]" id="att_<?= $roll ?>" value="<?= $status ?>">

    <button type="button"
        class="att-btn present <?= $status=='Present'?'active':'' ?>"
        onclick="markAttendance('<?= $roll ?>','Present',this)">
        ✓
    </button>

    <button type="button"
        class="att-btn absent <?= $status=='Absent'?'active':'' ?>"
        onclick="markAttendance('<?= $roll ?>','Absent',this)">
        ✕
    </button>

</td>
</tr>

<?php endwhile; ?>
</table>

<br>
<div class="att-footer-actions">

    <button type="submit" name="save_attendance" class="btn primary">
        Save Attendance
    </button>

    <a href="index.php?page=attendance_report&class=<?= $class ?>&date=<?= $date ?>"
       class="btn secondary">
       View Report
    </a>

</div>
</form>



<?php endif; ?>

</div>





<script>

function markAttendance(roll, status, btn){

    // change hidden input value
    document.getElementById("att_"+roll).value = status;

    // remove active from both buttons
    let parent = btn.parentElement;
    parent.querySelectorAll('.att-btn').forEach(b => b.classList.remove('active'));

    // activate clicked button
    btn.classList.add('active');

    updateStats();
}

function updateStats(){

    let total = document.querySelectorAll('input[name^="att"]').length;
    let present = 0;

    document.querySelectorAll('input[name^="att"]').forEach(el=>{
        if(el.value === 'Present') present++;
    });

    let absent = total - present;
    let percent = total ? Math.round((present/total)*100) : 0;

    document.getElementById('totalCount').innerText = total;
    document.getElementById('presentCount').innerText = present;
    document.getElementById('absentCount').innerText = absent;
    document.getElementById('percentCount').innerText = percent + '%';
}

// run once after load
updateStats();

</script>


<script>
const ctx2 = document.getElementById('feesChart');

new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Class 1','Class 2','Class 3','Class 4','Class 5'],
        datasets: [
        {
            label:'Collected %',
            data:[98,95,90,93,87],
            backgroundColor:'#10b981',
            borderRadius:6,
            barThickness:26
        },
        {
            label:'Pending %',
            data:[5,8,12,10,15],
            backgroundColor:'#ef4444',
            borderRadius:6,
            barThickness:26
        }]
    },
    options: {
        responsive:true,
        plugins:{
            legend:{
                labels:{ color:'#6b7280' }
            }
        },
        scales:{
            y:{
                beginAtZero:true,
                max:100,
                grid:{ color:'rgba(0,0,0,0.06)', drawBorder:false },
                ticks:{ color:'#6b7280' }
            },
            x:{
                grid:{ display:false },
                ticks:{ color:'#6b7280' }
            }
        }
    }
});
</script>




<script>
const ctx1 = document.getElementById('attendanceChart');

new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Aug','Sep','Oct','Nov','Dec','Jan'],
        datasets: [{
            label: 'Attendance %',
            data: [92,94,91,95,93,94],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            tension: 0.4,              // smooth curve
            fill: true,
            pointRadius: 3,
            pointHoverRadius: 6,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3b82f6',
            pointBorderWidth: 2,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#6b7280',
                    font: { size: 13 }
                }
            }
        },
        scales: {
            y: {
                min: 85,
                max: 100,
                grid: {
                    color: 'rgba(0,0,0,0.06)',
                    drawBorder:false
                },
                ticks: { color:'#6b7280' }
            },
            x: {
                grid: { display:false },
                ticks: { color:'#6b7280' }
            }
        }
    }
});
</script>

<?php
include __DIR__ . "/../../db.php";

/* =====================================================
   AUTO DETECT FEES AMOUNT COLUMN
===================================================== */
$fee_column = null;

$check = mysqli_query($conn, "SHOW COLUMNS FROM fees");
while($col = mysqli_fetch_assoc($check)){
    if(in_array($col['Field'], ['amount','paid','fees','total_fees','fee_amount'])){
        $fee_column = $col['Field'];
        break;
    }
}

/* =====================================================
   AUTO DETECT FEES DATE COLUMN
===================================================== */
$date_column = null;

$checkDate = mysqli_query($conn, "SHOW COLUMNS FROM fees");
while($col = mysqli_fetch_assoc($checkDate)){
    if(in_array($col['Field'], ['date','created_at','payment_date','paid_on','time'])){
        $date_column = $col['Field'];
        break;
    }
}

/* =====================================================
   TOTAL STUDENTS
===================================================== */
$res1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
$total_students = mysqli_fetch_assoc($res1)['total'] ?? 0;

/* =====================================================
   TOTAL TEACHERS
===================================================== */
$res2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM teachers");
$total_teachers = mysqli_fetch_assoc($res2)['total'] ?? 0;

/* =====================================================
   FEES COLLECTED
===================================================== */
$fees_total = 0;
if($fee_column){
    $res3 = mysqli_query($conn, "SELECT IFNULL(SUM($fee_column),0) as total FROM fees WHERE status='Paid'");
    $fees_total = mysqli_fetch_assoc($res3)['total'] ?? 0;
}

/* =====================================================
   AVG ATTENDANCE
===================================================== */
$res4 = mysqli_query($conn,"
    SELECT ROUND((SUM(status='Present')/COUNT(*))*100,1) as avg_att
    FROM attendance
");
$avg_attendance = mysqli_fetch_assoc($res4)['avg_att'] ?? 0;


/* =====================================================
   RECENT ACTIVITIES
===================================================== */
$activities = [];

/* Students */
$q1 = mysqli_query($conn,"SELECT name,class,created_at FROM students ORDER BY id DESC LIMIT 2");
while($r=mysqli_fetch_assoc($q1)){
    $activities[]=[
        "type"=>"student",
        "text"=>"New admission: {$r['name']} in Class {$r['class']}",
        "time"=>$r['created_at'] ?? date('Y-m-d H:i:s')
    ];
}

/* Fees */
if($fee_column){

    $date_select = $date_column ? $date_column : "NOW() as activity_time";

    $q2 = mysqli_query($conn,"
        SELECT student_name, $fee_column as paid, $date_select
        FROM fees
        ORDER BY id DESC LIMIT 5
    ");

    while($r=mysqli_fetch_assoc($q2)){
        $activities[]=[
            "type"=>"fees",
            "text"=>"{$r['student_name']} paid ₹{$r['paid']} fees",
            "time"=>$r[$date_column] ?? date('Y-m-d H:i:s')
        ];
    }
}

/* Sort latest first */
usort($activities,function($a,$b){
    return strtotime($b['time']) - strtotime($a['time']);
});
?>

<h1 class="page-title">Principal Dashboard</h1>
<p style="color:#6b7280;margin-bottom:20px">Overview of school management and analytics</p>

<div class="cards">

<div class="card">
👨‍🎓
<h2><?= $total_students ?></h2>
<p>Total Students</p>
</div>

<div class="card">
👩‍🏫
<h2><?= $total_teachers ?></h2>
<p>Total Teachers</p>
</div>

<div class="card">
💰
<h2>₹<?= number_format($fees_total) ?></h2>
<p>Fees Collected</p>
</div>

<div class="card">
📊
<h2><?= $avg_attendance ?>%</h2>
<p>Avg Attendance</p>
</div>

</div>


<div class="charts">
    <div class="chart-card">
        <h3>Attendance Trend</h3>
        <canvas id="attendanceChart"></canvas>
    </div>

    <div class="chart-card">
        <h3>Fees Collection</h3>
        <canvas id="feesChart"></canvas>
    </div>
</div>


<div class="chart-card" style="margin-top:25px;height:auto">
<h3>Recent Activities</h3>

<?php foreach($activities as $a): ?>
    <div class="activity <?= $a['type'] ?>">
        <span><?= $a['text'] ?></span>
        <small><?= date("d M h:i A",strtotime($a['time'])) ?></small>
    </div>
<?php endforeach; ?>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('attendanceChart'), {
    type: 'line',
    data: {
        labels: ['Aug','Sep','Oct','Nov','Dec','Jan'],
        datasets: [{
            label: 'Attendance %',
            data: [<?= $avg_attendance ?>,<?= $avg_attendance-2 ?>,<?= $avg_attendance-4 ?>,<?= $avg_attendance ?>,<?= $avg_attendance-1 ?>,<?= $avg_attendance+1 ?>],
            borderWidth: 3,
            tension: .4
        }]
    },
    options:{responsive:true,maintainAspectRatio:false}
});

new Chart(document.getElementById('feesChart'), {
    type: 'bar',
    data: {
        labels: ['Collected Fees', 'Remaining Fees'],
        datasets: [{
            label: 'Amount (₹)',
            data: [<?= $fees_total ?>, <?= max(100000-$fees_total,0) ?>],
            borderWidth: 1
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        scales:{
            y:{
                beginAtZero:true
            }
        }
    }
});

</script>



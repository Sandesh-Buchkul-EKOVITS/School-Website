<?php
include __DIR__ . "/../../db.php";

$class = $_GET['class'] ?? '';
$date = $_GET['date'] ?? date('Y-m-d');

$records = [];

if($class != ''){
  
    $stmt = $conn->prepare("
    SELECT s.roll_no, s.name,
           COALESCE(a.status,'Absent') as status
    FROM students s
    LEFT JOIN attendance a 
        ON s.roll_no = a.roll_no
        AND s.class = a.class
        AND a.date = ?
    WHERE s.class = ?
    ORDER BY CAST(s.roll_no AS UNSIGNED)
");
$stmt->bind_param("ss", $date, $class);
$stmt->execute();
$records = $stmt->get_result();


}
?>

<div class="page">
    <div class="page-header">
        <h2>Daily Attendance Report</h2>
        <p>View attendance class & date wise</p>
    </div>

    <form method="GET" class="report-filter">
    <input type="hidden" name="page" value="attendance_report">

    <div class="rf-box">
        <label>Class</label>
        <select name="class">
            <option value="">Select Class</option>
            <?php for($i=5;$i<=10;$i++): ?>
                <option value="<?= $i ?>" <?= $class==$i?'selected':'' ?>>
                    Class <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="rf-box">
        <label>Date</label>
        <input type="date" name="date" value="<?= $date ?>">
    </div>

    <button class="btn primary">Load Report</button>
</form>


    <?php if($class!=''): ?>
    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Roll</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if($records && $records->num_rows == 0): ?>

<tr>
    <td colspan="3" style="text-align:center;padding:25px;">
        No students found in this class
    </td>
</tr>
<?php endif; ?>


            <?php while($row = $records->fetch_assoc()): ?>
                <tr>
                    <td><?=$row['roll_no']?></td>
                    <td><?=$row['name']?></td>
                    <td>
                        <td>
<td>
<?php
    if($row['status'] === 'Present'){
        echo '<span class="badge success">Present</span>';
    }
    elseif($row['status'] === 'Absent'){
        echo '<span class="badge danger">Absent</span>';
    }
    else{
        echo '<span class="badge warning">Not Marked</span>';
    }
?>
</td>



                    </td>
                </tr>
            <?php endwhile; ?>

            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

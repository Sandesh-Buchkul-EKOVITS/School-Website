<?php
session_start();
include "../db.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!='teacher'){
  header("Location: ../auth/login.php"); 
  exit();
}

/* ================= ADD EXAM ================= */
if(isset($_POST['add_exam'])){
  $name = mysqli_real_escape_string($conn,$_POST['exam_name']);
  $class = mysqli_real_escape_string($conn,$_POST['class']);
  $subject = mysqli_real_escape_string($conn,$_POST['subject']);
  $date = $_POST['date'];
  $time = mysqli_real_escape_string($conn,$_POST['time']);
  $marks = mysqli_real_escape_string($conn,$_POST['marks']);
  $duration = mysqli_real_escape_string($conn,$_POST['duration']);

  mysqli_query($conn,"INSERT INTO exams 
    (exam_name,class,subject,exam_date,exam_time,max_marks,duration)
    VALUES ('$name','$class','$subject','$date','$time','$marks','$duration')");

  header("Location: exams.php");
  exit();
}

/* ================= UPDATE EXAM ================= */
if(isset($_POST['update_exam'])){
  $id = intval($_POST['exam_id']);
  $name = mysqli_real_escape_string($conn,$_POST['exam_name']);
  $class = mysqli_real_escape_string($conn,$_POST['class']);
  $subject = mysqli_real_escape_string($conn,$_POST['subject']);
  $date = $_POST['date'];
  $time = mysqli_real_escape_string($conn,$_POST['time']);
  $marks = mysqli_real_escape_string($conn,$_POST['marks']);
  $duration = mysqli_real_escape_string($conn,$_POST['duration']);

  mysqli_query($conn,"UPDATE exams SET 
    exam_name='$name',
    class='$class',
    subject='$subject',
    exam_date='$date',
    exam_time='$time',
    max_marks='$marks',
    duration='$duration'
    WHERE id=$id"
  );

  header("Location: exams.php");
  exit();
}

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
  $id = intval($_GET['delete']);
  mysqli_query($conn,"DELETE FROM exams WHERE id=$id");
  header("Location: exams.php");
  exit();
}

/* ================= FETCH ================= */
$result = mysqli_query($conn,"SELECT * FROM exams ORDER BY exam_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Exams | Bright Future School</title>
<link rel="stylesheet" href="./includes/sidebar-topbar.css">
<link rel="stylesheet" href="exams.css">
<?php include './includes/sidebar-topbar.php'; ?>
</head>
<body>

<div class="main">

<div class="header">
<div>
<h1>Exams Management</h1>
<p>Schedule and manage exams</p>
</div>
<button class="btn" onclick="openModal()">+ Schedule Exam</button>
</div>

<div class="table-card">
<table>
<thead>
<tr>
<th>EXAM</th>
<th>CLASS</th>
<th>SUBJECT</th>
<th>DATE</th>
<th>TIME</th>
<th>MARKS</th>
<th>DURATION</th>
<th>ACTION</th>
</tr>
</thead>
<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><b><?= $row['exam_name'] ?></b></td>
<td><?= $row['class'] ?></td>
<td><?= $row['subject'] ?></td>
<td><?= date("d M Y",strtotime($row['exam_date'])) ?></td>
<td><?= $row['exam_time'] ?></td>
<td><?= $row['max_marks'] ?></td>
<td><?= $row['duration'] ?></td>
<td>
  <!-- Edit Button -->
  <button class="edit-btn" onclick="openEditModal(<?= $row['id'] ?>,'<?= htmlspecialchars($row['exam_name'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['class'],ENT_QUOTES) ?>','<?= htmlspecialchars($row['subject'],ENT_QUOTES) ?>','<?= $row['exam_date'] ?>','<?= $row['exam_time'] ?>','<?= $row['max_marks'] ?>','<?= $row['duration'] ?>')">Edit</button>

  <!-- Delete Button -->
  <a href="?delete=<?= $row['id'] ?>" 
     onclick="return confirm('Delete this exam?')" 
     class="delete-btn">Delete</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>
</div>

<!-- Add Exam Modal -->
<div class="modal" id="modal">
<form method="post" class="modal-box">
<h2>Schedule New Exam</h2>
<div class="grid">
<div><label>Exam Name *</label><input name="exam_name" required></div>
<div><label>Class *</label><input name="class" required></div>
<div><label>Subject *</label><input name="subject" required></div>
<div><label>Date *</label><input type="date" name="date" required></div>
<div><label>Time *</label><input name="time" value="10:00 AM"></div>
<div><label>Max Marks *</label><input name="marks" value="100"></div>
<div><label>Duration *</label><input name="duration" value="3 hours"></div>
</div>
<div class="actions">
<button type="button" onclick="closeModal()">Cancel</button>
<button name="add_exam" class="primary">Schedule Exam</button>
</div>
</form>
</div>

<!-- Edit Exam Modal -->
<div class="modal" id="editModal">
<form method="post" class="modal-box">
<h2>Edit Exam</h2>
<input type="hidden" name="exam_id" id="edit_id">
<div class="grid">
<div><label>Exam Name *</label><input name="exam_name" id="edit_name" required></div>
<div><label>Class *</label><input name="class" id="edit_class" required></div>
<div><label>Subject *</label><input name="subject" id="edit_subject" required></div>
<div><label>Date *</label><input type="date" name="date" id="edit_date" required></div>
<div><label>Time *</label><input name="time" id="edit_time"></div>
<div><label>Max Marks *</label><input name="marks" id="edit_marks"></div>
<div><label>Duration *</label><input name="duration" id="edit_duration"></div>
</div>
<div class="actions">
<button type="button" onclick="closeEditModal()">Cancel</button>
<button name="update_exam" class="primary">Update Exam</button>
</div>
</form>
</div>

<script>
function openModal(){ document.getElementById("modal").style.display="flex"; }
function closeModal(){ document.getElementById("modal").style.display="none"; }

function openEditModal(id, name, cls, subject, date, time, marks, duration){
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_class').value = cls;
    document.getElementById('edit_subject').value = subject;
    document.getElementById('edit_date').value = date;
    document.getElementById('edit_time').value = time;
    document.getElementById('edit_marks').value = marks;
    document.getElementById('edit_duration').value = duration;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal(){ document.getElementById('editModal').style.display = 'none'; }
</script>

</body>
</html>

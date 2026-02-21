<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher'){
    header("Location: ../auth/login.php");
    exit();
}
$email = $_SESSION['email'] ?? 'teacher@email.com';
include '../db.php'; 
$sql = "SELECT * FROM students ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students | Bright Future School</title>
<link rel="stylesheet" href="./includes/sidebar-topbar.css">
<link rel="stylesheet" href="students.css">
</head>
<body>

<!-- ✅ Sidebar + Topbar Include -->
<?php include './includes/sidebar-topbar.php'; ?>
<!-- ===== Main ===== -->
<div class="main">

  <div class="page-title">
    <h1>Students</h1>
    <p>View students in your classes</p>
  </div>

  <!-- Search + Filter -->
  <div class="filter-box">
    <div class="search">
      🔍
      <input type="text" id="searchInput" placeholder="Search by name or roll number...">
    </div>

    <select id="classFilter">
      <option value="all">All Classes</option>
      <option value="5-A">5-A</option>
      <option value="5-B">5-B</option>
      <option value="6-A">6-A</option>
      <option value="6-B">6-B</option>
    </select>
  </div>

  <!-- Students Table -->
  <div class="table-card">
    <table id="studentsTable">
      <thead>
        <tr>
          <th>ROLL NO</th>
          <th>NAME</th>
          <th>CLASS</th>
          <th>PHONE</th>
          <th>EMAIL</th>
        </tr>
      </thead>
      <tbody>
<?php if($result && $result->num_rows > 0){ ?>
  <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
      <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo htmlspecialchars($row['class']); ?></td>
      <td><?php echo htmlspecialchars($row['phone']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
    </tr>
  <?php } ?>
<?php } else { ?>
    <tr>
      <td colspan="5" style="text-align:center;">No Students Added Yet</td>
    </tr>
<?php } ?>
</tbody>
    </table>
  </div>

</div>

<!-- ===== JavaScript ===== -->
<script>
const searchInput = document.getElementById("searchInput");
const classFilter = document.getElementById("classFilter");
const rows = document.querySelectorAll("#studentsTable tbody tr");

function filterStudents() {
  const searchText = searchInput.value.toLowerCase();
  const selectedClass = classFilter.value;

  rows.forEach(row => {
    const roll = row.cells[0].innerText.toLowerCase();
    const name = row.cells[1].innerText.toLowerCase();
    const studentClass = row.cells[2].innerText;

    const matchSearch =
      roll.includes(searchText) || name.includes(searchText);

    const matchClass =
      selectedClass === "all" || studentClass === selectedClass;

    if (matchSearch && matchClass) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

searchInput.addEventListener("keyup", filterStudents);
classFilter.addEventListener("change", filterStudents);
</script>

</body>
</html>

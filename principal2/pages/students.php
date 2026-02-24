<?php
include __DIR__ . '/../../db.php';

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location: index.php?page=students");
    exit;
}

/* ================= ADD / UPDATE ================= */
/* ================= ADD / UPDATE ================= */
if(isset($_POST['save_student'])){

    $id    = $_POST['id'] ?? "";
    $name  = $_POST['name'];
    $class = $_POST['class'];
    $gender= $_POST['gender'];
    $phone = $_POST['phone'];

    if($id == ""){

        // 🔥 Auto Generate Roll Number Class-wise
        $getLast = $conn->prepare("
            SELECT roll_no 
            FROM students 
            WHERE class=? 
            ORDER BY 
            CAST(SUBSTRING_INDEX(roll_no,'-',-1) AS UNSIGNED) DESC 
            LIMIT 1
        ");
        $getLast->bind_param("s",$class);
        $getLast->execute();
        $res = $getLast->get_result();

        $newIndex = 1;

        if($res->num_rows > 0){
            $last = $res->fetch_assoc()['roll_no'];
            $parts = explode('-', $last);
            $newIndex = intval($parts[1]) + 1;
        }

        $newRoll = $class . "-" . $newIndex;

        $stmt = $conn->prepare("
            INSERT INTO students (name, roll_no, `class`, gender, phone)
            VALUES (?,?,?,?,?)
        ");
        $stmt->bind_param("sssss",$name,$newRoll,$class,$gender,$phone);

    } else {

        // 🔥 Update without changing roll number
        $stmt = $conn->prepare("
            UPDATE students SET
            name=?,
            `class`=?,
            gender=?,
            phone=?
            WHERE id=?
        ");
        $stmt->bind_param("ssssi",$name,$class,$gender,$phone,$id);
    }

    $stmt->execute();
    header("Location: index.php?page=students");
    exit;
}

/* ================= FETCH ================= */
$result = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<!-- ================= HEADER ================= -->
<div class="page-header">
    <div>
        <h2>Student Management</h2>
        <p>Manage student records and information</p>
    </div>

    <button id="openStudentModal" class="btn primary">+ Add Student</button>
</div>


<!-- ================= TABLE ================= -->
<div class="table-card">
<table class="student-table">
<thead>
<tr>
<th>ROLL</th>
<th>NAME</th>
<th>CLASS</th>
<th>GENDER</th>
<th>PHONE</th>
<th>ACTIONS</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['roll_no']) ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['class']) ?></td>
<td><?= htmlspecialchars($row['gender']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td>

<td class="actions">
<button class="edit-btn editStudentBtn"
data-id="<?= $row['id'] ?>"
data-name="<?= htmlspecialchars($row['name']) ?>"
data-roll="<?= $row['roll_no'] ?>"
data-class="<?= $row['class'] ?>"
data-gender="<?= $row['gender'] ?>"
data-phone="<?= $row['phone'] ?>">
Edit
</button>

<a class="btn delete-btn"
   onclick="return confirm('Delete this student?')"
   href="?page=students&delete=<?= $row['id'] ?>">
Delete
</a>
</td>

</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>


<!-- ================= MODAL ================= -->
<div id="studentModal" class="modal">
  <div class="modal-content">

    <div class="modal-header">
      <h3 id="modalTitle">Add New Student</h3>
      <span class="close">&times;</span>
    </div>

<form method="POST">

<input type="hidden" name="id" id="student_id">

<div class="form-grid">

<div class="input-group">
<label>Name</label>
<input type="text" name="name" id="name" required>
</div>

<div class="input-group">
<label>Class</label>
<input type="text" name="class" id="class" required>
</div>

<div class="input-group">
<label>Gender</label>
<select name="gender" id="gender">
<option>Male</option>
<option>Female</option>
</select>
</div>

<div class="input-group">
<label>Phone</label>
<input type="text" name="phone" id="phone" required>
</div>

</div>

<div class="modal-actions">
<button type="button" class="cancel-btn">Cancel</button>
<button type="submit" name="save_student" class="save-btn" id="saveBtn">Add Student</button>
</div>

</form>

  </div>
</div>

<script>
const modal = document.getElementById("studentModal");
const openBtn = document.getElementById("openStudentModal");
const closeBtn = document.querySelector(".close");
const cancelBtn = document.querySelector(".cancel-btn");

/* form fields */
const student_id = document.getElementById("student_id");
const name = document.getElementById("name");
const roll = document.getElementById("roll");
const student_class = document.getElementById("class");
const gender = document.getElementById("gender");
const phone = document.getElementById("phone");
const modalTitle = document.getElementById("modalTitle");
const saveBtn = document.getElementById("saveBtn");

/* ADD */
if(openBtn){
openBtn.addEventListener("click", () => {
    modal.classList.add("show");

    student_id.value="";
    name.value="";
    roll.value="";
    student_class.value="";
    gender.value="Male";
    phone.value="";

    modalTitle.innerText="Add New Student";
    saveBtn.innerText="Add Student";
});
}

/* EDIT */
document.querySelectorAll(".editStudentBtn").forEach(btn=>{
    btn.addEventListener("click", function(){
        modal.classList.add("show");

        student_id.value=this.dataset.id;
        name.value=this.dataset.name;
        roll.value=this.dataset.roll;
        student_class.value=this.dataset.class;
        gender.value=this.dataset.gender;
        phone.value=this.dataset.phone;

        modalTitle.innerText="Update Student";
        saveBtn.innerText="Update Student";
    });
});

/* CLOSE */
if(closeBtn) closeBtn.onclick=()=>modal.classList.remove("show");
if(cancelBtn) cancelBtn.onclick=()=>modal.classList.remove("show");

window.onclick=(e)=>{
    if(e.target===modal) modal.classList.remove("show");
};
</script>

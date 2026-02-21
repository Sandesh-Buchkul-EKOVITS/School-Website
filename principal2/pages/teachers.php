<?php
include __DIR__ . "/../../db.php";
$page = $_GET['page'] ?? 'dashboard';
$content = __DIR__ . "/pages/$page.php";


/* ADD TEACHER */
if(isset($_POST['add_teacher'])){

    $id = $_POST['id'] ?? '';

    $name = $_POST['name'];
    $employee_id = $_POST['employee_id'];
    $subject = $_POST['subject'];
    $qualification = $_POST['qualification'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    if($id != ""){

        // UPDATE
        $stmt = $conn->prepare("UPDATE teachers SET
            name=?, employee_id=?, subject=?, qualification=?, phone=?, email=?, status=?
            WHERE id=?");

        $stmt->bind_param("sssssssi",
            $name,$employee_id,$subject,$qualification,$phone,$email,$status,$id
        );

    } else {

        // INSERT
        $stmt = $conn->prepare("INSERT INTO teachers
            (name, employee_id, subject, qualification, phone, email, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss",
            $name,$employee_id,$subject,$qualification,$phone,$email,$status
        );
    }

    $stmt->execute();
    header("Location: index.php?page=teachers&success=1");
    exit;
}


$result = $conn->query("SELECT * FROM teachers");
?>
<div class="teacher-page">

<div class="card">

    <div class="card-header">
        <div>
            <h2>Teacher Management</h2>
            <p>Manage teacher records and assignments</p>
        </div>

        <button id="openTeacherModal" class="btn-add modern-btn">
            <span>＋</span> Add Teacher
        </button>
    </div>

    <div class="search-wrapper">
        <input type="text" placeholder="Search by name, employee ID, or subject..." id="teacherSearch">
    </div>

    <!-- table here -->

</div>

</div>



    
       <!-- TABLE -->
    <table class="modern-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Phone</th>
                <th>Status</th>
                <th width="140">Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>

                <td>
                    <span class="status <?= strtolower($row['status']) ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>

                <td class="actions">
                   <button type="button" class="editBtn edit-btn" data-id="<?= $row['id'] ?>">
    Edit
</button>


                    <a class="delete-btn"
                       onclick="return confirm('Delete teacher?')"
                       href="index.php?page=teacher_delete&id=<?= $row['id'] ?>">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>



<!-- MODAL -->
<div id="teacherModal" class="modal">
  <div class="modal-content">

    <span class="close-modal">&times;</span>
    <h2 id="modalTitle">Add New Teacher</h2>


    <form method="POST">

        <div class="form-grid">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="employee_id" placeholder="Employee ID" required>

            <input type="text" name="subject" placeholder="Subject" required>
            <input type="text" name="qualification" placeholder="Qualification" required>

            <input type="text" name="phone" placeholder="Phone" required>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <select name="status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>

        <div class="modal-actions">
            <button type="button" class="cancel-btn">Cancel</button>
          <input type="hidden" name="id" id="teacher_id">

<button type="submit" id="submitBtn" name="add_teacher" class="btn-primary">
    Add Teacher
</button>

        </div>

    </form>
  </div>
</div>




<script>
document.getElementById("teacherSearch").addEventListener("keyup", function() {

    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll(".modern-table tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});
</script>



<script>
const modal = document.getElementById("teacherModal");
const openBtn = document.getElementById("openTeacherModal");
const closeBtn = document.querySelector(".close-modal");
const cancelBtn = document.querySelector(".cancel-btn");

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";
cancelBtn.onclick = () => modal.style.display = "none";

window.onclick = function(e){
    if(e.target == modal){
        modal.style.display = "none";
    }
}
</script>



<script>
document.querySelectorAll(".editBtn").forEach(btn => {

    btn.addEventListener("click", function(){

        let id = this.dataset.id;

        fetch("pages/get_teacher.php?id=" + id)
        .then(res => res.json())
        .then(data => {

            modal.style.display = "flex";

            document.getElementById("modalTitle").innerText = "Edit Teacher";
            document.getElementById("submitBtn").innerText = "Update Teacher";

            document.getElementById("teacher_id").value = data.id;

            document.querySelector("[name=name]").value = data.name;
            document.querySelector("[name=employee_id]").value = data.employee_id;
            document.querySelector("[name=subject]").value = data.subject;
            document.querySelector("[name=qualification]").value = data.qualification;
            document.querySelector("[name=phone]").value = data.phone;
            document.querySelector("[name=email]").value = data.email;
            document.querySelector("[name=status]").value = data.status;

        });

    });

});
</script>





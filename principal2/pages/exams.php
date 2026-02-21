<?php
include __DIR__ . '/../../db.php';
$result = $conn->query("SELECT * FROM exams ORDER BY id DESC");
?>



<div class="exams-page">

<div class="page">

 <div class="page-header">
    <div>
        <h2>Exams Management</h2>
        <p>Schedule and manage exams</p>
    </div>

    <button class="btn primary" id="openExamModal">+ Schedule Exam</button>
</div>

    <!-- EXAM MODAL -->
<!-- ================= EXAM MODAL ================= -->
         <!-- EXAM MODAL -->
<div id="examModal" class="modal">
  <div class="modal-card">
    <div class="modal-header">
      <h3 id="modalTitle">Schedule New Exam</h3>
      <span class="closeModal">&times;</span>
    </div>

    <form method="POST" action="index.php?page=exam_save">
      <input type="hidden" name="id" id="exam_id">

      <div class="form-grid">

    <div class="form-group">
        <label>Exam Name</label>
        <input type="text" name="exam_name" id="exam_name" required>
    </div>

    <div class="form-group">
        <label>Class</label>
        <input type="text" name="class" id="class" required>
    </div>

    <div class="form-group">
        <label>Subject</label>
        <input type="text" name="subject" id="subject" required>
    </div>

    <div class="form-group">
        <label>Date</label>
        <input type="date" name="exam_date" id="exam_date" required>
    </div>

    <div class="form-group">
        <label>Time</label>
        <input type="time" name="exam_time" id="exam_time" required>
    </div>

    <div class="form-group">
        <label>Max Marks</label>
        <input type="number" name="max_marks" id="max_marks" required>
    </div>

    <div class="form-group full">
        <label>Duration</label>
        <input type="text" name="duration" id="duration" required>
    </div>

</div>

   <div class="modal-footer">
    <button type="button" id="cancelExam">Cancel</button>
    <button type="submit">Save Exam</button>
</div>


    </form>
  </div>
</div>



    <div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Time</th>
                <th>Max Marks</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>

   <tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['exam_name'] ?></td>
    <td><?= $row['class'] ?></td>
    <td><?= $row['subject'] ?></td>
    <td><?= $row['exam_date'] ?></td>
    <td><?= $row['exam_time'] ?></td>
    <td><?= $row['max_marks'] ?></td>
    <td><?= $row['duration'] ?></td>

    <td>
        <button class="btn editBtn"
            data-id="<?= $row['id'] ?>"
            data-name="<?= $row['exam_name'] ?>"
            data-class="<?= $row['class'] ?>"
            data-subject="<?= $row['subject'] ?>"
            data-date="<?= $row['exam_date'] ?>"
            data-time="<?= $row['exam_time'] ?>"
            data-marks="<?= $row['max_marks'] ?>"
            data-duration="<?= $row['duration'] ?>"
        >Edit</button>

        <a href="index.php?page=exam_delete&id=<?= $row['id'] ?>"
           class="btn delete"
           onclick="return confirm('Delete this exam?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>

</div>

    </div>

</div>
            </div>


<script>
const modal = document.getElementById("examModal");
const openBtn = document.getElementById("openExamModal");
const closeBtn = document.getElementById("closeExamModal");
const cancelBtn = document.getElementById("cancelExamModal");

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";
cancelBtn.onclick = () => modal.style.display = "none";

window.onclick = e => {
    if(e.target === modal) modal.style.display="none";
};
</script>

<script>
document.querySelectorAll(".editBtn").forEach(btn=>{
    btn.addEventListener("click", function(){

        // open modal
        document.getElementById("examModal").style.display="flex";

        // fill form
        document.getElementById("exam_id").value = this.dataset.id;
        document.getElementById("exam_name").value = this.dataset.name;
        document.getElementById("class").value = this.dataset.class;
        document.getElementById("subject").value = this.dataset.subject;
        document.getElementById("exam_date").value = this.dataset.date;
        document.getElementById("exam_time").value = this.dataset.time;
        document.getElementById("max_marks").value = this.dataset.marks;
        document.getElementById("duration").value = this.dataset.duration;

    });
});
</script>



<script>
// close button (X)
document.querySelectorAll(".closeModal").forEach(btn=>{
    btn.onclick = ()=> document.getElementById("examModal").style.display="none";
});

// cancel button
document.getElementById("cancelExam").onclick = function(){
    document.getElementById("examModal").style.display="none";
};

// click outside modal closes
window.onclick = function(e){
    const modal = document.getElementById("examModal");
    if(e.target === modal){
        modal.style.display="none";
    }
};
</script>


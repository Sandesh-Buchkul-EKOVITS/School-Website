<?php
include __DIR__ . '/../../db.php';
$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC");
?>

<div class="card">

    <!-- HEADER -->
   <div class="page-header">
    <div class="header-left">
        <h2>Notice Management</h2>
        <p>Manage school notices</p>
    </div>

    <div class="header-right">
        <button class="btn primary" id="openNoticeModal">+ Create Notice</button>
    </div>
</div>


    <!-- TABLE -->
    <!-- TABLE -->
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
        <?php while($row = $notices->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['notice_date'] ?></td>
                <td class="actions">

                    <button 
                    class="btn btn-edit editNoticeBtn"
                    data-id="<?= $row['id'] ?>"
                    data-title="<?= htmlspecialchars($row['title']) ?>"
                    data-description="<?= htmlspecialchars($row['description']) ?>"
                    data-date="<?= $row['notice_date'] ?>">
                    Edit
                    </button>

                  <form action="index.php?page=notice_delete" method="POST" style="display:inline;">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <button type="submit" class="btn btn-delete"
        onclick="return confirm('Delete this notice?')">
        Delete
    </button>
</form>


                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>



<!-- ================= MODAL ================= -->
<div class="notice-page">
<div id="noticeModal" class="modal">
    <div class="modal-box">

        <div class="modal-header">
            <h3 id="modalTitle">Create Notice</h3>
            <span class="closeModal" id="closeNoticeModal">&times;</span>
        </div>

        <form action="index.php?page=notice_save" method="POST" id="noticeForm">

            <input type="hidden" name="id" id="notice_id">

            <label>Title *</label>
            <input type="text" name="title" id="title" required>

            <label>Date *</label>
            <input type="date" name="date" id="date" required>

            <label>Description *</label>
            <textarea name="description" id="description" required></textarea>

            <div class="modal-actions">
                <button type="button" class="cancelBtn" id="cancelNotice">Cancel</button>
                <button type="submit" class="publishBtn">Save Notice</button>
            </div>

        </form>

    </div>
</div>
        </div>


<script>

/* OPEN CREATE */
document.getElementById("openNoticeModal").onclick = () => {
    document.getElementById("noticeForm").reset();
    document.getElementById("notice_id").value = "";
    document.getElementById("modalTitle").innerText = "Create Notice";
    document.getElementById("noticeModal").style.display = "flex";
};

/* EDIT BUTTON */
document.querySelectorAll(".editNoticeBtn").forEach(btn => {
    btn.addEventListener("click", function(){

        document.getElementById("notice_id").value = this.dataset.id;
        document.getElementById("title").value = this.dataset.title;
        document.getElementById("description").value = this.dataset.description;
        document.getElementById("date").value = this.dataset.date;

        document.getElementById("modalTitle").innerText = "Edit Notice";
        document.getElementById("noticeModal").style.display = "flex";
    });
});

/* CLOSE */
document.getElementById("closeNoticeModal").onclick =
document.getElementById("cancelNotice").onclick = () => {
    document.getElementById("noticeModal").style.display = "none";
};

window.onclick = e => {
    if(e.target == document.getElementById("noticeModal")){
        document.getElementById("noticeModal").style.display = "none";
    }
};

</script>

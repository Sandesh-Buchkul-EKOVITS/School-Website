<?php
include __DIR__ . "/../../db.php";

$id = $_GET['id'] ?? '';
$name = $subject = $phone = $status = '';

if($id){
    $res = $conn->query("SELECT * FROM teachers WHERE id=$id");
    $data = $res->fetch_assoc();

    $name = $data['name'];
    $subject = $data['subject'];
    $phone = $data['phone'];
    $status = $data['status'];
}
?>
<!-- EDIT TEACHER MODAL -->
<div id="editTeacherModal" class="modal">
  <div class="modal-box">
    <div class="modal-header">
      <h3>Edit Teacher</h3>
      <span class="closeModal">&times;</span>
    </div>

    <form id="editTeacherForm">
      <input type="hidden" name="id" id="edit_id">

      <div class="grid">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" name="name" id="edit_name" required>
        </div>

        <div class="form-group">
          <label>Employee ID *</label>
          <input type="text" name="employee_id" id="edit_employee_id" required>
        </div>

        <div class="form-group">
          <label>Subject *</label>
          <input type="text" name="subject" id="edit_subject" required>
        </div>

        <div class="form-group">
          <label>Qualification *</label>
          <input type="text" name="qualification" id="edit_qualification" required>
        </div>

        <div class="form-group">
          <label>Phone *</label>
          <input type="text" name="phone" id="edit_phone" required>
        </div>

        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" id="edit_email" required>
        </div>

        <div class="form-group full">
          <label>Status *</label>
          <select name="status" id="edit_status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div class="actions">
        <button type="button" class="cancelBtn">Cancel</button>
        <button type="submit" class="saveBtn">Update Teacher</button>
      </div>
    </form>
  </div>
</div>


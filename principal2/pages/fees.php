<?php
include __DIR__ . '/../../db.php';

/* ================= SEARCH + FILTER ================= */
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'All';

/* BUILD QUERY */
$sql = "SELECT * FROM fees WHERE 1";

/* SEARCH FILTER */
if($search != ''){
    $search = $conn->real_escape_string($search);
    $sql .= " AND (roll_no LIKE '%$search%' OR student_name LIKE '%$search%')";
}

/* STATUS FILTER */
if($status != 'All'){
    $status = $conn->real_escape_string($status);
    $sql .= " AND status='$status'";
}

$sql .= " ORDER BY id DESC";

$fees = $conn->query($sql);



/* ================= ADD PAYMENT ================= */
if(isset($_POST['save_fee'])){

    $roll_no = $_POST['roll_no'];
    $student_name = $_POST['student_name'];
    $class = $_POST['class'];
    $total_fee = (int)$_POST['total_fee'];
    $paid = (int)$_POST['paid'];

    $pending = $total_fee - $paid;

    if($pending <= 0){
        $status = 'Paid';
        $pending = 0;
    } elseif($paid > 0){
        $status = 'Partial';
    } else{
        $status = 'Pending';
    }

    $last_payment = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO fees (roll_no, student_name, class, total_fee, paid, pending, status, last_payment) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssiiiss',$roll_no,$student_name,$class,$total_fee,$paid,$pending,$status,$last_payment);
    $stmt->execute();

    header('Location: index.php?page=fees');
    exit;
}


/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM fees WHERE id=$id");
    header('Location: index.php?page=fees');
    exit;
}
?>
<div class="fee-summary">

    <div class="summary-card total">
        <div class="icon">₹</div>
        <div>
            <h3 id="totalFees">₹0</h3>
            <p>Total Fees</p>
        </div>
    </div>

    <div class="summary-card collected">
        <div class="icon">₹</div>
        <div>
            <h3 id="collectedFees">₹0</h3>
            <p>Collected</p>
        </div>
    </div>

    <div class="summary-card pending">
        <div class="icon">₹</div>
        <div>
            <h3 id="pendingFees">₹0</h3>
            <p>Pending</p>
        </div>
    </div>

</div>


<div class="page">

    <div class="page-header">

    <div class="page-title">
        <h2>Fees Management</h2>
        <p>Add and track student payments</p>
    </div>

    <button class="btn-save add-btn" onclick="openFeeModal()">
        + Add Payment
    </button>

</div>


    <!-- SEARCH + FILTER -->
<div class="filter-bar">

    <div class="search-box">
        <i class="fa fa-search"></i>
        <input type="text" id="searchInput" placeholder="Search by name or roll number...">
    </div>

    <div class="filter-box">
        <i class="fa fa-filter"></i>
        <select id="statusFilter">
            <option value="">All Status</option>
            <option value="Paid">Paid</option>
            <option value="Partial">Partial</option>
            <option value="Pending">Pending</option>
        </select>
    </div>

</div>




    <!-- TABLE -->
    <div class="card table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Pending</th>
                    <th>Status</th>
                    <th>Last Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $fees->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['roll_no'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td><?= $row['class'] ?></td>

                    <td>₹<?= number_format($row['total_fee']) ?></td>

                    <td class="amount-paid">
                        ₹<?= number_format($row['paid']) ?>
                    </td>

                    <td class="amount-pending">
                        ₹<?= number_format($row['pending']) ?>
                    </td>

                    <td>
                        <span class="status <?= strtolower($row['status']) ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>

                    <td><?= $row['last_payment'] ?></td>

                    <td>
                        <a href="index.php?page=fees&delete=<?= $row['id'] ?>"
                           class="btn danger"
                           onclick="return confirm('Delete this record?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>


<!-- PAYMENT MODAL -->
<div id="feeModal" class="modal">
    <div class="modal-box">

        <div class="modal-header">
            <h3>Add Student Payment</h3>
            <span onclick="closeFeeModal()">&times;</span>
        </div>

        <form method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>Roll No</label>
                    <input type="text" name="roll_no" required>
                </div>

                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" name="student_name" required>
                </div>

                <div class="form-group">
                    <label>Class</label>
                    <input type="text" name="class" required>
                </div>

                <div class="form-group">
                    <label>Total Fee</label>
                    <input type="number" name="total_fee" required>
                </div>

                <div class="form-group">
                    <label>Paid Amount</label>
                    <input type="number" name="paid" required>
                </div>

            </div>

            <br>
            <button type="submit" name="save_fee" class="btn-save">Save Payment</button>
        </form>

    </div>
</div>


<script>
function openFeeModal(){
    document.getElementById('feeModal').style.display='flex';
}
function closeFeeModal(){
    document.getElementById('feeModal').style.display='none';
}
</script>


<script>
const searchInput = document.getElementById("searchInput");
const statusFilter = document.getElementById("statusFilter");

searchInput.addEventListener("keyup", filterTable);
statusFilter.addEventListener("change", filterTable);

function filterTable() {
    const search = searchInput.value.toLowerCase();
    const status = statusFilter.value.toLowerCase();

    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const roll = row.cells[0].innerText.toLowerCase();
        const rowStatus = row.cells[6].innerText.toLowerCase();

        if (
            (name.includes(search) || roll.includes(search)) &&
            (status === "" || rowStatus.includes(status))
        ) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });

    updateSummary(); // ⭐ THIS MAKES CARDS LIVE
}


</script>



<script>
function updateSummary() {

    let total = 0;
    let collected = 0;
    let pending = 0;

    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {

        if(row.style.display === "none") return; // only visible rows

        const totalFee = parseInt(row.cells[3].innerText.replace(/[₹,]/g,''));
        const paidFee = parseInt(row.cells[4].innerText.replace(/[₹,]/g,''));
        const pendingFee = parseInt(row.cells[5].innerText.replace(/[₹,]/g,''));

        total += totalFee;
        collected += paidFee;
        pending += pendingFee;
    });

    document.getElementById("totalFees").innerText = formatMoney(total);
    document.getElementById("collectedFees").innerText = formatMoney(collected);
    document.getElementById("pendingFees").innerText = formatMoney(pending);
}

function formatMoney(num){
    if(num >= 100000) return "₹" + (num/100000).toFixed(2) + "L";
    if(num >= 1000) return "₹" + (num/1000).toFixed(1) + "K";
    return "₹" + num;
}
</script>




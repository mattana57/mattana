<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$update_success = false;

// --- ระบบบันทึกการแก้ไขข้อมูล ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $province = $conn->real_escape_string($_POST['province']);
    $zipcode = $conn->real_escape_string($_POST['zipcode']);

    $sql = "UPDATE users SET 
            fullname = '$fullname', 
            email = '$email', 
            phone = '$phone', 
            address = '$address', 
            province = '$province', 
            zipcode = '$zipcode' 
            WHERE id = $user_id";
    
    if($conn->query($sql)) {
        $update_success = true;
    }
}

// --- ระบบล้างข้อมูลที่อยู่ ---
if (isset($_GET['clear_address'])) {
    $conn->query("UPDATE users SET fullname='', address='', province='', zipcode='', phone='' WHERE id = $user_id");
    header("Location: profile.php");
    exit();
}

$user_q = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user_data = $user_q->fetch_assoc();
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ | Goods Secret Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* --- Neon Mystery Theme --- */
        body {
            background: radial-gradient(circle at 20% 30%, #4b2c63 0%, transparent 40%), 
                        radial-gradient(circle at 80% 70%, #6a1b9a 0%, transparent 40%), 
                        linear-gradient(135deg,#120018,#2a0845,#3d1e6d);
            color: #fff; font-family: 'Segoe UI', sans-serif; min-height: 100vh;
        }

        .card-profile {
            background: rgba(26, 0, 40, 0.65);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(187, 134, 252, 0.3);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .text-neon-cyan { color: #00f2fe; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .info-label { font-size: 0.85rem; color: rgba(255,255,255,0.5); margin-bottom: 2px; }
        .info-value { font-size: 1.1rem; color: #fff; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px; }

        .custom-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(187, 134, 252, 0.4) !important;
            color: #fff !important;
            border-radius: 12px !important;
        }

        .btn-edit-toggle {
            background: rgba(187, 134, 252, 0.1);
            color: #bb86fc; border: 1px solid #bb86fc;
            border-radius: 50px; padding: 5px 20px; transition: 0.3s;
        }

        .btn-neon-save {
            background: linear-gradient(45deg, #00f2fe, #00c6ff);
            color: #0f172a; border: none; font-weight: bold;
            border-radius: 12px; padding: 12px; transition: 0.3s;
        }
        .btn-neon-save:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0, 242, 254, 0.5); }

        .btn-outline-neon {
            border: 1px solid #bb86fc; color: #bb86fc;
            background: transparent; border-radius: 12px; padding: 12px; transition: 0.3s;
        }
        .btn-outline-neon:hover { background: rgba(187, 134, 252, 0.1); color: #fff; box-shadow: 0 0 15px #bb86fc; }

        .modal-content.custom-popup {
            background: rgba(26, 0, 40, 0.9); backdrop-filter: blur(20px);
            border: 1px solid rgba(187, 134, 252, 0.4); border-radius: 25px; color: #fff;
        }
        .neon-icon {
            font-size: 4rem; color: #bb86fc; text-shadow: 0 0 20px #bb86fc;
            animation: neon-glow 1.5s ease-in-out infinite alternate;
        }
        @keyframes neon-glow {
            from { opacity: 0.8; transform: scale(1); }
            to { opacity: 1; transform: scale(1.1); text-shadow: 0 0 20px #f107a3, 0 0 30px #f107a3; color: #f107a3; }
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card-profile p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-neon-cyan mb-0"><i class="bi bi-person-badge me-2"></i> โปรไฟล์ของฉัน</h4>
                    <button class="btn-edit-toggle" id="toggleEditBtn" onclick="toggleEdit()">
                        <i class="bi bi-pencil-square me-1"></i> แก้ไขข้อมูล
                    </button>
                </div>

                <div id="displayMode">
                    <div class="info-label">อีเมลผู้ใช้งาน</div>
                    <div class="info-value"><?= $user_data['email'] ?></div>

                    <div class="info-label">ชื่อ-นามสกุล</div>
                    <div class="info-value" style="color:#bb86fc;"><?= $user_data['fullname'] ?: 'ยังไม่ได้ระบุ' ?></div>

                    <div class="info-label">เบอร์โทรศัพท์</div>
                    <div class="info-value"><?= $user_data['phone'] ?: 'ยังไม่ได้ระบุ' ?></div>

                    <div class="info-label">ที่อยู่จัดส่ง</div>
                    <div class="info-value small"><?= $user_data['address'] ?: 'ยังไม่ได้ระบุ' ?></div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <a href="index.php" class="btn btn-outline-neon w-100">
                                <i class="bi bi-house-door me-1"></i> หน้าหลัก
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="logout.php" class="btn btn-outline-danger w-100 rounded-3 py-2" style="border-radius:12px !important;">ออกจากระบบ</a>
                        </div>
                    </div>
                </div>

                <div id="editMode" style="display: none;">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="small text-white-50 mb-1">อีเมล</label>
                            <input type="email" name="email" class="form-control custom-input" value="<?= $user_data['email'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-white-50 mb-1">ชื่อ-นามสกุล</label>
                            <input type="text" name="fullname" class="form-control custom-input" value="<?= $user_data['fullname'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small text-white-50 mb-1">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="form-control custom-input" value="<?= $user_data['phone'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small text-white-50 mb-1">ที่อยู่จัดส่ง</label>
                            <textarea name="address" class="form-control custom-input" rows="2"><?= $user_data['address'] ?? '' ?></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6"><input type="text" name="province" class="form-control custom-input" placeholder="จังหวัด" value="<?= $user_data['province'] ?? '' ?>"></div>
                            <div class="col-6"><input type="text" name="zipcode" class="form-control custom-input" placeholder="รหัสไปรษณีย์" value="<?= $user_data['zipcode'] ?? '' ?>"></div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-outline-light w-50 rounded-pill" onclick="toggleEdit()">ยกเลิก</button>
                            <button type="submit" name="update_profile" class="btn btn-neon-save w-50">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-profile p-4 h-100">
                <h4 class="text-neon-cyan mb-4"><i class="bi bi-clock-history me-2"></i> ประวัติการสั่งซื้อ</h4>
                <?php if($orders->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table text-white border-0">
                            <thead><tr class="text-white-50 small border-0"><th>ออเดอร์</th><th>ยอดรวม</th><th class="text-end">สถานะ</th></tr></thead>
                            <tbody>
                                <?php while($row = $orders->fetch_assoc()): ?>
                                <tr class="border-0 align-middle" style="background: rgba(255,255,255,0.03);">
                                    <td class="py-3 px-3 fw-bold" style="color:#bb86fc;">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td class="text-neon-cyan">฿<?= number_format($row['total_price']) ?></td>
                                    <td class="text-end">
                                        <span class="badge" style="border:1px solid #bb86fc; color:#bb86fc; border-radius:20px; font-size:12px;">
                                            <?= $row['status'] == 'pending' ? 'รอตรวจสอบ' : 'สำเร็จ' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 opacity-25"><i class="bi bi-bag-x display-1"></i><p class="mt-3">ไม่มีรายการสั่งซื้อ</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-popup text-center py-5">
            <div class="modal-body">
                <i class="bi bi-check2-circle neon-icon mb-4"></i>
                <h3 class="fw-bold mb-3" style="color: #00f2fe;">เรียบร้อย!</h3>
                <p class="fs-5 opacity-75 mb-4">อัปเดตข้อมูลส่วนตัวของคุณสำเร็จ ✨</p>
                <button type="button" class="btn px-5 py-2 rounded-pill text-white" style="background: linear-gradient(45deg, #7c3aed, #db2777); border:none;" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleEdit() {
        const display = document.getElementById('displayMode');
        const edit = document.getElementById('editMode');
        const btn = document.getElementById('toggleEditBtn');
        if (display.style.display === 'none') {
            display.style.display = 'block';
            edit.style.display = 'none';
            btn.innerHTML = '<i class="bi bi-pencil-square me-1"></i> แก้ไขข้อมูล';
        } else {
            display.style.display = 'none';
            edit.style.display = 'block';
            btn.innerHTML = '<i class="bi bi-eye me-1"></i> ดูข้อมูล';
        }
    }
</script>
<?php if ($update_success): ?>
<script>new bootstrap.Modal(document.getElementById('successModal')).show();</script>
<?php endif; ?>
</body>
</html>
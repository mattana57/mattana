<?php
// กำหนดให้แสดงผลข้อความภาษาไทยได้ถูกต้อง
header('Content-Type: text/html; charset=utf-8');

// ฟังก์ชันสำหรับดึงข้อมูลจาก $_POST และแสดงผลในรูปแบบ Bootstrap Table Row
function display_data($label, $name, $post_data) {
    $value = isset($post_data[$name]) ? htmlspecialchars($post_data[$name]) : '— ไม่ได้กรอก/เลือก —';
    echo '<tr>';
    echo '<th scope="row" class="text-primary">' . $label . '</th>';
    echo '<td>' . nl2br($value) . '</td>'; // ใช้ nl2br สำหรับ textarea เพื่อให้ขึ้นบรรทัดใหม่ได้
    echo '</tr>';
}

// ฟังก์ชันสำหรับจัดการข้อมูล Checkbox (แหล่งข้อมูล)
function display_checkbox_data($label, $name, $post_data) {
    echo '<tr>';
    echo '<th scope="row" class="text-primary">' . $label . '</th>';
    echo '<td>';
    if (isset($post_data[$name]) && is_array($post_data[$name])) {
        // นำค่าที่เลือกมาต่อกันด้วยเครื่องหมายจุลภาคและวรรค
        echo htmlspecialchars(implode(', ', $post_data[$name]));
    } else {
        echo '— ไม่ได้เลือก —';
    }
    echo '</td>';
    echo '</tr>';
}

// ฟังก์ชันสำหรับแสดงข้อมูลไฟล์ที่อัปโหลด
function display_file_info($label, $name) {
    echo '<tr>';
    echo '<th scope="row" class="text-primary">' . $label . '</th>';
    echo '<td>';
    if (isset($_FILES[$name]) && $_FILES[$name]['error'] == UPLOAD_ERR_OK) {
        // แสดงชื่อไฟล์ที่อัปโหลด (ใน Production ควรมีการย้ายไฟล์ไปเก็บที่อื่นด้วย)
        echo 'ชื่อไฟล์: ' . htmlspecialchars($_FILES[$name]['name']);
        echo '<br>ขนาด: ' . round($_FILES[$name]['size'] / 1024, 2) . ' KB';
    } else if (isset($_FILES[$name]) && $_FILES[$name]['name'] != "") {
         echo '<span class="text-danger">เกิดข้อผิดพลาดในการอัปโหลดไฟล์: Code ' . $_FILES[$name]['error'] . '</span>';
    } else {
        echo '— ไม่ได้แนบไฟล์ —';
    }
    echo '</td>';
    echo '</tr>';
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการสมัครงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Sukhumvit Set', 'Kanit', sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card-header {
            background-color: #28a745; /* สีเขียวสำหรับผลลัพธ์ */
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
        }
        .table th {
            width: 30%;
            vertical-align: top;
        }
        .table td {
            font-weight: 400;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-lg border-0">
        <div class="card-header rounded-top">
            ✅ ข้อมูลใบสมัครที่ส่งเรียบร้อยแล้ว
        </div>
        <div class="card-body p-4">
            
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <p class="lead mb-4 text-success">
                    ขอบคุณสำหรับการสมัครงาน! ข้อมูลของคุณได้รับการบันทึกแล้ว
                </p>

                <h4 class="mb-3 text-primary">สรุปข้อมูลที่กรอก</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <?php display_data('ตำแหน่งที่สมัคร', 'position', $_POST); ?>
                            
                            <tr class="table-info">
                                <td colspan="2" class="fw-bold">ข้อมูลส่วนตัว</td>
                            </tr>
                            
                            <?php 
                            $fullname = (isset($_POST['prefix']) ? htmlspecialchars($_POST['prefix']) : '') . 
                                        ' ' . (isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '') . 
                                        ' ' . (isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '');
                            ?>
                            <tr>
                                <th scope="row" class="text-primary">ชื่อ - สกุล</th>
                                <td><?php echo trim($fullname); ?></td>
                            </tr>
                            <?php display_data('วันเดือนปีเกิด', 'dob', $_POST); ?>
                            <?php display_data('อีเมล', 'email', $_POST); ?>

                            <tr class="table-info">
                                <td colspan="2" class="fw-bold">การศึกษาและประสบการณ์</td>
                            </tr>
                            
                            <?php display_data('ระดับการศึกษาสูงสุด', 'education', $_POST); ?>
                            <?php display_data('ความสามารถพิเศษ / ทักษะ', 'skills', $_POST); ?>
                            <?php display_data('ประสบการณ์ทำงานโดยสรุป', 'experience', $_POST); ?>

                            <tr class="table-info">
                                <td colspan="2" class="fw-bold">เอกสารและแหล่งที่มา</td>
                            </tr>
                            
                            <?php display_file_info('ไฟล์ Resume/CV', 'resume'); ?>
                            <?php display_file_info('ไฟล์ Portfolio', 'portfolio'); ?>

                            <?php display_checkbox_data('รู้จักเราจากช่องทาง', 'source', $_POST); ?>

                        </tbody>
                    </table>
                </div>
                
                <p class="mt-4 text-muted fst-italic">
                    *หมายเหตุ: ในการใช้งานจริง ข้อมูลนี้จะถูกบันทึกไปยังฐานข้อมูล (Database) และไฟล์เอกสารจะถูกย้ายไปเก็บในเซิร์ฟเวอร์
                </p>

            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    ⚠️ การเข้าถึงโดยตรงไม่ได้รับอนุญาต โปรดกรอกข้อมูลผ่าน <a href="index.html" class="alert-link">หน้าฟอร์มใบสมัคร</a>
                </div>
            <?php endif; ?>

        </div>
        <div class="card-footer text-muted text-center">
            Tech Innovate Solution | ทีมงานจะติดต่อกลับโดยเร็วที่สุด
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
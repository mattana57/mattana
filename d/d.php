<!-- กรอกฟอร์ม กรอก เลือกสี เลื่อนหาสาขา กดปุ่ม ด้วยจี-->
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>66010914057 มัทนา รัตนแสง</title>
  <!-- Bootstrap 5.3 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .form-container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-family: 'Arial', sans-serif;
      color: #4e73df;
    }

    .form-label {
      font-weight: 600;
      color: #333;
    }

    .form-control, .form-select {
      border-radius: 10px;
      box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn {
      border-radius: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
      background-color: #4e73df;
      border: none;
    }

    .btn-primary:hover {
      background-color: #3e60d0;
    }

    .btn-secondary {
      background-color: #6c757d;
      border: none;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
    }

    .btn-info {
      background-color: #17a2b8;
      border: none;
    }

    .btn-info:hover {
      background-color: #138496;
    }

    .btn-warning {
      background-color: #ffc107;
      border: none;
    }

    .btn-warning:hover {
      background-color: #e0a800;
    }

    .form-container .btn {
      padding: 10px 25px;
      font-size: 16px;
    }

    .color-display {
      width: 100px;
      height: 30px;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>

<body>

  <div class="container py-5">
    <div class="form-container">
      <h1 class="text-center mb-4">ฟอร์มรับข้อมูล - มัทนา รัตนแสง (น้ำฝน) - ChatGPT</h1>

      <form method="post" action="">

        <div class="mb-3">
          <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
          <input type="text" class="form-control" id="fullname" name="fullname" required autofocus>
        </div>

        <div class="mb-3">
          <label for="phone" class="form-label">เบอร์โทร</label>
          <input type="text" class="form-control" id="phone" name="phone" required>
        </div>

        <div class="mb-3">
          <label for="height" class="form-label">ส่วนสูง (ซม.)</label>
          <input type="number" class="form-control" id="height" name="height" min="100" max="200" required>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">ที่อยู่</label>
          <textarea class="form-control" id="address" name="address" rows="4"></textarea>
        </div>

        <div class="mb-3">
          <label for="birthday" class="form-label">วันเดือนปีเกิด</label>
          <input type="date" class="form-control" id="birthday" name="birthday">
        </div>

        <div class="mb-3">
          <label for="color" class="form-label">สีที่ชอบ</label>
          <input type="color" class="form-control form-control-color" id="color" name="color">
          <div class="color-display" style="background-color:{{isset($_POST['color']) ? $_POST['color'] : '#ffffff'}}"></div>
        </div>

        <div class="mb-3">
          <label for="major" class="form-label">สาขาวิชา</label>
          <select class="form-select" id="major" name="major">
            <option value="การบัญชี">การบัญชี</option>
            <option value="การตลาด">การตลาด</option>
            <option value="การจัดการ">การจัดการ</option>
            <option value="คอมพิวเตอร์ธุรกิจ">คอมพิวเตอร์ธุรกิจ</option>
          </select>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="submit" name="Submit" class="btn btn-primary">สมัครสมาชิก</button>
          <button type="reset" class="btn btn-secondary">ยกเลิก</button>
          <button type="button" class="btn btn-info" onClick="window.location='https://www.msu.ac.th/';">Go to MSU</button>
          <button type="button" class="btn btn-warning" onMouseOver="alert('ล่ามเคะกั๊ก');">Hello</button>
          <button type="button" class="btn btn-success" onClick="window.print();">พิมพ์</button>
        </div>

      </form>
    </div>

    <hr class="my-4">

    <?php
    if (isset($_POST['Submit'])){
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $height = $_POST['height'];
        $address = $_POST['address'];
        $birthday = $_POST['birthday'];
        $color = $_POST['color'];
        $major = $_POST['major'];

        echo "<h4>ข้อมูลที่กรอก:</h4>";
        echo "<p><strong>ชื่อ-สกุล: </strong>".$fullname."</p>";
        echo "<p><strong>เบอร์โทร: </strong>".$phone."</p>";
        echo "<p><strong>ส่วนสูง: </strong>".$height." ซม.</p>";
        echo "<p><strong>ที่อยู่: </strong>".$address."</p>";
        echo "<p><strong>วันเดือนปีเกิด: </strong>".$birthday."</p>";
        echo "<p><strong>สีที่ชอบ: </strong><div style='background-color:{$color};width:100px;height:30px;'></div></p>";
        echo "<p><strong>สาขาวิชา: </strong>".$major."</p>";
    }
    ?>

  </div>

  <!-- Bootstrap 5.3 JS and Popper.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>

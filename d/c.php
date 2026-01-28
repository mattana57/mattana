<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>66010914057 มัทนา รัตนแสง (ปรับปรุงด้วย Bootstrap)</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
    /* Custom styling for better color swatch display */
    .color-swatch-display {
        display: flex;
        align-items: center;
    }
    .color-box {
        width: 40px;
        height: 40px;
        border: 1px solid #ccc;
        margin-right: 10px;
        border-radius: 5px;
    }
</style>
</head>

<body>
<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h1 class="h3 mb-0">📝 ฟอร์มรับข้อมูล - มัทนา รัตนแสง (น้ำฝน) - Gemini</h1>
        </div>
        <div class="card-body">
            <form method="post" action="">
                
                <div class="mb-3">
                    <label for="fullname" class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fullname" name="fullname" autofocus required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">เบอร์โทร <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="mb-3">
                    <label for="height" class="form-label">ส่วนสูง (ซม.) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="height" name="height" min="100" max="200" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">ที่อยู่</label>
                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="birthday" class="form-label">วันเดือนปีเกิด</label>
                    <input type="date" class="form-control" id="birthday" name="birthday">
                </div>
                
                <div class="mb-3">
                    <label for="color" class="form-label">สีที่ชอบ</label>
                    <input type="color" class="form-control form-control-color" id="color" name="color" value="#000000" title="เลือกสี">
                </div>

                <div class="mb-4">
                    <label for="major" class="form-label">สาขาวิชา</label>
                    <select class="form-select" id="major" name="major">
                        <option value="การบัญชี">การบัญชี</option>
                        <option value="การตลาด">การตลาด</option>
                        <option value="การจัดการ">การจัดการ</option>
                        <option value="คอมพิวเตอร์ธุรกิจ">คอมพิวเตอร์ธุรกิจ</option>
                    </select>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="submit" name="Submit" class="btn btn-success me-md-2">✅ สมัครสมาชิก</button>
                    <button type="reset" class="btn btn-secondary me-md-2">🔄 ยกเลิก</button>
                    <button type="button" onClick="window.location='https://www.msu.ac.th/';" class="btn btn-info text-white me-md-2">🏫 Go to MSU</button>
                    <button type="button" onMouseOver="alert('ล่ามเคะกั๊ก');" class="btn btn-warning me-md-2">👋 Hello</button>
                    <button type="button" onClick="window.print();" class="btn btn-light border">🖨️ พิมพ์</button>
                </div>
            </form>

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

                echo "<h3>✅ ข้อมูลที่ส่ง:</h3>";
                echo "<ul class='list-group'>";
                echo "<li class='list-group-item'><strong>ชื่อ-สกุล:</strong> ".$fullname."</li>";
                echo "<li class='list-group-item'><strong>เบอร์โทร:</strong> ".$phone."</li>";
                echo "<li class='list-group-item'><strong>ส่วนสูง:</strong> ".$height." ซม.</li>";
                echo "<li class='list-group-item'><strong>ที่อยู่:</strong> ".$address."</li>";
                echo "<li class='list-group-item'><strong>วันเดือนปีเกิด:</strong> ".$birthday."</li>";
                
                // แสดงสีที่เลือกให้สวยงามขึ้น
                echo "<li class='list-group-item color-swatch-display'><strong>สีที่ชอบ:</strong> 
                        <div class='color-box' style='background-color:{$color};'></div>
                        <span>".$color."</span>
                      </li>";
                
                echo "<li class='list-group-item'><strong>สาขาวิชา:</strong> ".$major."</li>";
                echo "</ul>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
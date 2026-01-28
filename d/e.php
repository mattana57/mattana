<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบสมัครงาน - Tech Innovate Solution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* กำหนดรูปแบบเพิ่มเติมให้ดูสวยงามและทันสมัย */
        body {
            background-color: #f8f9fa; /* สีพื้นหลังอ่อนๆ */
            font-family: 'Sukhumvit Set', 'Kanit', sans-serif;
        }
        .container {
            max-width: 900px; /* จำกัดความกว้างของฟอร์ม */
            margin-top: 30px;
            margin-bottom: 50px;
        }
        .card-header {
            background-color: #007bff; /* สีหลักของบริษัท (น้ำเงิน) */
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }
        .btn-primary {
            background-color: #28a745; /* สีปุ่มส่ง (เขียว) */
            border-color: #28a745;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1e7e34;
            border-color: #1c7430;
        }
        .form-label {
            font-weight: 500;
        }
        .header-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }
        /* กำหนดสีให้กับหัวข้อในแต่ละส่วน */
        .section-header {
            color: #007bff;
            border-left: 5px solid #007bff;
            padding-left: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-lg border-0">        
        <div class="card-header rounded-top-0">
            Tech Innovate Solution: แบบฟอร์มใบสมัคร<br>
            มัทนา รัตนแสง
        </div>
        <div class="card-body p-4">
        <form action="f.php" method="post" enctype="multipart/form-data">                
                <h4 class="section-header">💼 ตำแหน่งที่สนใจสมัคร</h4>
                <div class="mb-4">
                    <label for="position" class="form-label">เลือกตำแหน่งงาน</label>
                    <select class="form-select" id="position" name="position" required>
                        <option value="">-- โปรดเลือกตำแหน่งที่ต้องการสมัคร --</option>
                        <option value="software-engineer">Software Engineer (Full-Stack)</option>
                        <option value="data-scientist">Data Scientist</option>
                        <option value="ux-designer">UX/UI Designer</option>
                        <option value="marketing-specialist">Digital Marketing Specialist</option>
                        <option value="hr-officer">HR Officer</option>
                    </select>
                </div>

                <hr class="my-4">

                <h4 class="section-header">👤 ข้อมูลส่วนตัว</h4>
                <div class="row g-3">
                    
                    <div class="col-md-3">
                        <label for="prefix" class="form-label">คำนำหน้าชื่อ</label>
                        <select class="form-select" id="prefix" name="prefix" required>
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label for="firstname" class="form-label">ชื่อ (ภาษาไทย)</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>

                    <div class="col-md-4">
                        <label for="lastname" class="form-label">นามสกุล (ภาษาไทย)</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>

                    <div class="col-md-6">
                        <label for="dob" class="form-label">วัน/เดือน/ปีเกิด</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@techinnovate.com" required>
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="section-header">🎓 การศึกษาและประสบการณ์</h4>
                
                <div class="mb-3">
                    <label for="education" class="form-label">ระดับการศึกษาสูงสุด</label>
                    <select class="form-select" id="education" name="education" required>
                        <option value="">-- โปรดเลือกระดับการศึกษาสูงสุด --</option>
                        <option value="bachelor">ปริญญาตรี</option>
                        <option value="master">ปริญญาโท</option>
                        <option value="doctoral">ปริญญาเอก</option>
                        <option value="other">อื่นๆ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="skills" class="form-label">ความสามารถพิเศษ / ทักษะที่โดดเด่น</label>
                    <textarea class="form-control" id="skills" name="skills" rows="3" placeholder="ตัวอย่าง: ภาษาอังกฤษดีเยี่ยม, Python, JavaScript, Figma, Adobe Photoshop"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="experience" class="form-label">ประสบการณ์ทำงานโดยสรุป (ระบุตำแหน่งล่าสุด, ระยะเวลา, และหน้าที่)</label>
                    <textarea class="form-control" id="experience" name="experience" rows="5" placeholder="ตัวอย่าง: ตำแหน่ง: Software Developer, บริษัท ABC Tech, ระยะเวลา 2 ปี, หน้าที่หลัก: พัฒนาและดูแลระบบ E-commerce"></textarea>
                </div>

                <hr class="my-4">
                
                <h4 class="section-header">📄 การแนบเอกสาร</h4>
                
                <div class="mb-3">
                    <label for="resume" class="form-label">แนบไฟล์ Resume/CV <span class="text-danger">*</span> (บังคับ: PDF, DOCX, ขนาดไม่เกิน 5MB)</label>
                    <input class="form-control" type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                </div>

                <div class="mb-4">
                    <label for="portfolio" class="form-label">แนบไฟล์ Portfolio (ถ้ามี: PDF, URL ในไฟล์แนบ)</label>
                    <input class="form-control" type="file" id="portfolio" name="portfolio" accept=".pdf">
                </div>

                <hr class="my-4">

                <h4 class="section-header">🔎 คุณรู้จักเราจากช่องทางใด</h4>
                <p class="text-muted">โปรดเลือกได้มากกว่า 1 ข้อ</p>
                <div class="row g-2 mb-4">
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="JobSite" id="source1" name="source[]">
                            <label class="form-check-label" for="source1">
                                เว็บไซต์จัดหางาน (Job Site)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="SocialMedia" id="source2" name="source[]">
                            <label class="form-check-label" for="source2">
                                สื่อโซเชียลมีเดีย (Facebook, LinkedIn)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="EmployeeReferral" id="source3" name="source[]">
                            <label class="form-check-label" for="source3">
                                พนักงานปัจจุบันแนะนำ
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="CompanyWebsite" id="source4" name="source[]">
                            <label class="form-check-label" for="source4">
                                เว็บไซต์บริษัท (Tech Innovate)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Other" id="source5" name="source[]">
                            <label class="form-check-label" for="source5">
                                อื่นๆ
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="dataConsent" required>
                    <label class="form-check-label fw-bold" for="dataConsent">
                        ข้าพเจ้าขอรับรองว่าข้อมูลข้างต้นเป็นความจริงทุกประการ
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg" type="submit">🚀 ส่งใบสมัคร</button>
                </div>

            </form>
        </div>
        <div class="card-footer text-muted text-center">
            Tech Innovate Solution | ร่วมสร้างสรรค์นวัตกรรมไปกับเรา
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
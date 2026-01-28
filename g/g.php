<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>66010914057 มัทนา รัตนแสง (น้ำฝน)</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
        }
        /* Layout Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .full-width { grid-column: 1 / -1; }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8f9fa;
            color: #6c757d;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #dee2e6;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        tr:hover { background-color: #fdfdfd; }

        /* Chart Header */
        .chart-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #555;
            display: block;
            border-left: 4px solid #4e73df;
            padding-left: 10px;
        }
    </style>
</head>

<body>

<div class="container">
    <h1>มัทนา รัตนแสง (น้ำฝน)</h1>

    <?php
        include_once("connectdb.php");
        // สร้าง Array ชื่อเดือนภาษาไทย
        $monthNames = [
            1 => "มกราคม", 2 => "กุมภาพันธ์", 3 => "มีนาคม", 4 => "เมษายน", 
            5 => "พฤษภาคม", 6 => "มิถุนายน", 7 => "กรกฎาคม", 8 => "สิงหาคม", 
            9 => "กันยายน", 10 => "ตุลาคม", 11 => "พฤศจิกายน", 12 => "ธันวาคม"
        ];

        $sql = "SELECT MONTH(p_date) AS Month, SUM(p_amount) AS Total_Sales FROM popsupermarket GROUP BY MONTH(p_date) ORDER BY Month;";
        $rs = mysqli_query($conn, $sql);

        $labels = [];
        $values = [];

        while ($data = mysqli_fetch_array($rs)) {
            $labels[] = $monthNames[$data['Month']]; // เก็บชื่อเดือนแทนตัวเลข
            $values[] = $data['Total_Sales'];
        }
    ?>

    <div class="dashboard-grid">
        <div class="card">
            <span class="chart-title">ตารางสรุปยอดขายรายเดือน</span>
            <table>
                <thead>
                    <tr>
                        <th>เดือน</th>
                        <th style="text-align: right;">ยอดขาย (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($labels as $index => $month): ?>
                    <tr>
                        <td><strong><?php echo $month; ?></strong></td>
                        <td align="right"><?php echo number_format($values[$index], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <span class="chart-title">สัดส่วนยอดขาย (Doughnut)</span>
            <canvas id="doughnutChart"></canvas>
        </div>

        <div class="card full-width">
            <span class="chart-title">แนวโน้มยอดขายรายเดือน (Bar Chart)</span>
            <canvas id="barChart" height="100"></canvas>
        </div>
    </div>
</div>

<script>
    const labels = <?php echo json_encode($labels); ?>;
    const dataValues = <?php echo json_encode($values); ?>;
    
    // โทนสี Modern Pastel
    const colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
        '#858796', '#5a5c69', '#f8f9fc', '#4e73df', '#b5d1ff'
    ];

    // ข้อมูลสำหรับกราฟ
    const dataConfig = {
        labels: labels,
        datasets: [{
            label: 'ยอดขาย',
            data: dataValues,
            backgroundColor: colors,
            hoverOffset: 15,
            borderRadius: 5
        }]
    };

    // สร้าง Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: dataConfig,
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // สร้าง Doughnut Chart
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: dataConfig,
        options: {
            cutout: '70%', // ทำให้รูตรงกลางกว้างขึ้น ดูทันสมัย
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

<?php mysqli_close($conn); ?>
</body>
</html>
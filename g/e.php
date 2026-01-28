<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>66010914057 มัทนา รัตนแสง (น้ำฝน)</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4e73df;
            --bg-color: #f8f9fc;
            --text-color: #5a5c69;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            color: #2e59d9;
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
        }

        /* ส่วนของตาราง */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f8f9fc;
            color: #4e73df;
            padding: 15px;
            border-bottom: 2px solid #e3e6f0;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e3e6f0;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f1f4ff; transition: 0.2s; }

        /* ส่วนของกราฟ จัดวางแบบคู่กัน */
        .chart-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap; /* รองรับมือถือ */
        }

        .chart-card {
            flex: 1;
            min-width: 300px; /* ขนาดขั้นต่ำของกราฟ */
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            text-align: center;
        }

        .chart-label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>

<body>

<div class="container">
    <h1>📊 สรุปยอดขายรายประเทศ - มัทนา รัตนแสง</h1>

    <?php
        include_once("connectdb.php");
        $sql = "SELECT p_country, SUM(p_amount) AS total FROM popsupermarket GROUP BY p_country";
        $rs = mysqli_query($conn, $sql);

        $labels = []; 
        $values = []; 
        
        while ($data = mysqli_fetch_array($rs)) {
            $labels[] = $data['p_country'];
            $values[] = $data['total'];
        }
    ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>🌍 ประเทศ</th>
                    <th style="text-align: right;">💰 ยอดขาย (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($labels as $index => $country): ?>
                <tr>
                    <td><strong><?php echo $country; ?></strong></td>
                    <td style="text-align: right;"><?php echo number_format($values[$index], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="chart-row">
        <div class="chart-card">
            <span class="chart-label">กราฟแท่งแสดงยอดขาย</span>
            <canvas id="barChart"></canvas>
        </div>
        <div class="chart-card">
            <span class="chart-label">สัดส่วนยอดขาย (Doughnut)</span>
            <canvas id="doughnutChart"></canvas>
        </div>
    </div>
</div>

<script>
    const labels = <?php echo json_encode($labels); ?>;
    const values = <?php echo json_encode($values); ?>;
    
    // ชุดสีแบบ Modern 
    const colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
    ];

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'ยอดขาย (บาท)',
            data: values,
            backgroundColor: colors,
            borderWidth: 0,
            hoverOffset: 15
        }]
    };

    // 1. Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: chartData,
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Doughnut Chart (แทน Pie Chart เพราะดูทันสมัยกว่า)
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: chartData,
        options: {
            cutout: '65%', /* ทำรูตรงกลาง */
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });
</script>

<?php mysqli_close($conn); ?>
</body>
</html>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="index.php">Goods Secret</a>

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="index.php">หน้าแรก</a>
</li>

<?php if(isset($_SESSION['user'])): ?>

<li class="nav-item">
<a class="nav-link">👤 <?= $_SESSION['user']['username']; ?></a>
</li>

<li class="nav-item">
<a class="nav-link" href="logout.php">ออกจากระบบ</a>
</li>

<?php else: ?>

<li class="nav-item">
<a class="nav-link" href="register.php">สมัครสมาชิก</a>
</li>

<li class="nav-item">
<a class="nav-link" href="login.php">เข้าสู่ระบบ</a>
</li>

<?php endif; ?>

</ul>
</div>
</nav>

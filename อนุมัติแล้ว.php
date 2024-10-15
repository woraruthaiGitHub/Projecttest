<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// รับข้อมูลผู้ใช้จากฐานข้อมูล
$username = $_SESSION['username'];
$sql = "SELECT * FROM UserRole WHERE Username='$username'";
$result = $conn->query($sql);
$userData = $result->fetch_assoc();
$department = $userData['Department'];

// ดึงข้อมูลผู้ที่อนุมัติแล้ว
$approvedSql = "
    SELECT EmployeeID, Position, TaskName, FirstName, LastName FROM approvals 
    WHERE status='อนุมัติแล้ว' AND department='$department'
";
$approvedResult = $conn->query($approvedSql);

// ตรวจสอบว่าคำสั่ง SQL ทำงานสำเร็จหรือไม่
if (!$approvedResult) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $conn->error;
    exit();
}

// แสดงจำนวนผู้ที่อนุมัติแล้ว
echo "จำนวนผู้ที่อนุมัติแล้ว: " . $approvedResult->num_rows;

// สร้างอาร์เรย์เก็บข้อมูล
$approvedData = [];

if ($approvedResult->num_rows > 0) {
    while ($row = $approvedResult->fetch_assoc()) {
        $approvedData[] = [
            'FirstName' => $row['FirstName'],
            'LastName' => $row['LastName'],
            'EmployeeID' => $row['EmployeeID'],
            'Position' => $row['Position'],
            'TaskName' => $row['TaskName'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>การอนุมัติสิทธิ</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">ทบทวนสิทธิ</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

  
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
    
      <!--    <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="max-height: 300px; overflow-y: auto;">
            <li class="dropdown-header">
             4 รายการ 
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <a href="components-cards.html">
              <div>
                
                <h4>แจ้งเตือนรายงาน</h4>
                <p>รายงานการขอเข้าใช้สิทธิ</p>
                <p>30 นาที. ที่ผ่านมา</p>
              
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

          <!--     <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>การแจ้งเตือนระบบ</h4>
                <p>............</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>แจ้งเตือน......</h4>
                <p>............</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>ระบบ.............</h4>
                <p>..............</p>
                <p>4 hrs. ago</p>
              </div>
            </li>
          </a>
            <li>
              <hr class="dropdown-divider">
            </li>

          </ul><!-- End Notification Dropdown Items 

        </li><!-- End Notification Nav 
</a> -->

        <li class="nav-item dropdown pe-3">
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2">
            <?php echo htmlspecialchars($username); ?>
        </span>
    </a><!-- End Profile Image Icon -->

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
            <h6><?php echo htmlspecialchars($username); ?></h6>
            <span><?php echo htmlspecialchars($position); ?></span>
            <span><?php echo 'ID: ' . htmlspecialchars($employeesID); ?></span>
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>โปรไฟล์</span>
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="pages-login.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>ออกจากระบบ</span>
            </a>
        </li>
    </ul><!-- End Profile Dropdown Items -->
</li><!-- End Profile Nav -->
              </a>
            </li>
      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-grid"></i>
          <span>หน้าหลัก</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>ประวัติ</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="history.php">
              <i class="bi bi-circle"></i><span>ประวัติการเข้าใช้</span>
            </a>
          </li>
          <li>
            <a href="ประวัติการเปลี่ยนแปลง.php">
              <i class="bi bi-circle"></i><span>ประวัติการเปลี่ยนแปลง</span>
            </a>
          </li>
          
         
    </ul>
  </aside><!-- End Sidebar -->


  <main id="main" class="main">
    <div class="pagetitle">
        <h1>อนุมัติแล้ว</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item active">อนุมัติแล้ว</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ตารางแสดงรายการอนุมัติแล้ว</h5>

                        <!-- แสดงจำนวนผู้ที่อนุมัติแล้ว -->
                        <p>จำนวนผู้ที่อนุมัติแล้ว: <?php echo $approvedResult->num_rows; ?></p>

                        <style>
    table {
        width: 100%; /* ขยายตารางให้เต็มความกว้าง */
        border-collapse: collapse; /* รวมเส้นขอบให้เป็นเส้นเดียว */
    }
    th, td {
        border: 1px solid #ddd; /* เส้นขอบสีเทาอ่อน */
        padding: 8px; /* ระยะห่างภายในเซลล์ */
    }
    th {
        background-color: #f2f2f2; /* สีพื้นหลังของหัวตาราง */
        font-weight: bold; /* ทำให้ข้อความในหัวตารางหนา */
    }
    th:first-child, td:first-child {
        text-align: right; /* จัดข้อความให้ชิดขวาสำหรับคอลัมน์แรก */
    }
    td {
        text-align: left; /* จัดข้อความให้ชิดซ้ายสำหรับทุกคอลัมน์ */
    }
    tr:hover {
        background-color: #f5f5f5; /* สีพื้นหลังเมื่อชี้เมาส์ไปที่แถว */
    }
</style>

<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">ลำดับ</th>
            <th scope="col">รหัสพนักงาน</th>
            <th scope="col">ชื่อ-นามสกุล</th>
            <th scope="col">ตำแหน่ง</th>
            <th scope="col">ชื่อข้อมูล</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $count = 1;
        $approvedResult->data_seek(0);
        while ($row = $approvedResult->fetch_assoc()): ?>
            <tr>
                <th scope="row"><?php echo $count++; ?></th>
                <td>
                    <a href="employee_tasks.php?employee_id=<?php echo urlencode($row['EmployeeID']); ?>">
                        <?php echo htmlspecialchars($row['EmployeeID']); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td> <!-- รวมชื่อและนามสกุล -->
                <td><?php echo htmlspecialchars($row['Position']); ?></td>
                <td><?php echo htmlspecialchars($row['TaskName']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; สำนักงานหลักประกันสุขภาพแห่งชาติ <strong><span>ทบทวนสิทธิ</span></strong>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
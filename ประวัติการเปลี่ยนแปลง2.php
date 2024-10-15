<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // ถ้ายังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

// ดึง username ของผู้ใช้ที่ล็อกอิน
$username = $_SESSION['username'];

// ดึงข้อมูลจากตาราง tasktransactions
$sql_tasks = "SELECT ID, EmployeeID, TaskName, Start, End, Status FROM tasktransactions";
$result_tasks = $conn->query($sql_tasks);

// ดึงข้อมูลจากตาราง deleted_tasks
$sql_deleted_tasks = "SELECT ID, TaskName, Status, EmployeeID, DeletedAt FROM deleted_tasks";
$result_deleted_tasks = $conn->query($sql_deleted_tasks);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>ข้อความ</title>
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
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">ทบทวนสิทธิ</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> End Search Bar -->

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
    <!-- แสดงชื่อผู้ใช้ ถ้าไม่มีจะแสดง "ชื่อผู้ใช้" -->
    <span class="d-none d-md-block dropdown-toggle ps-2">
        <?php 
        if (!empty($username)) { 
            echo htmlspecialchars($username); 
        } else { 
            echo 'ชื่อผู้ใช้';  // ข้อความเริ่มต้น ถ้าไม่มีข้อมูล username
        } 
        ?>
    </span>
</a><!-- End Profile Image Icon -->

<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
    <li class="dropdown-header">
        <!-- แสดงชื่อผู้ใช้ ถ้าไม่มีจะแสดง "ชื่อผู้ใช้" -->
        <h6>
            <?php 
            if (!empty($username)) { 
                echo htmlspecialchars($username); 
            } else { 
                echo 'ชื่อผู้ใช้';  // ข้อความเริ่มต้น ถ้าไม่มีข้อมูล username
            } 
            ?>
        </h6>
        <!-- แสดงตำแหน่ง ถ้าไม่มีจะแสดง "ตำแหน่ง" -->
        <span>
            <?php 
            if (!empty($position)) { 
                echo htmlspecialchars($position); 
            } else { 
                echo 'ตำแหน่ง';  // ข้อความเริ่มต้น ถ้าไม่มีข้อมูล position
            } 
            ?>
        </span>
    </li>
    <li>
        <hr class="dropdown-divider">
    </li>
    <li>
        <a class="dropdown-item d-flex align-items-center" href="users-profile2.php">
            <i class="bi bi-person"></i>
            <span>โปรไฟล์</span>
        </a>
    </li>

    <li>
        <hr class="dropdown-divider">
    </li>

    <li>
        <a class="dropdown-item d-flex align-items-center" href="login.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>ออกจากระบบ</span>
        </a>
    </li>

</ul><!-- End Profile Dropdown Items -->
</li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>

<body>

  <!-- ======= Header ======= -->
  

  <aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link collapsed" href="indexadmin.php"class="active">
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
        <a href="history2.php">
          <i class="bi bi-circle"></i><span>ประวัติการเข้าใช้</span>
        </a>
      </li>
   
    </a>
  </li>
  </ul>
  
  <li class="nav-item">
    <a class="nav-link " href="ประวัติการเปลี่ยนแปลง2.php">
      <i class="bi bi-envelope"></i>
      <span>ข้อความ </span>
    </a>
  </li><!-- End Contact Page Nav -->
  
    </a>
  </li>
</ul>

</aside>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>หน้าหลัก</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
      table {
          width: 100%;
          border-collapse: collapse;
      }
      table, th, td {
          border: 1px solid black;
      }
      th, td {
          padding: 8px;
          text-align: left;
      }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // ฟังก์ชันพิมพ์ตารางข้อมูล
        document.getElementById('printButton').onclick = function() {
            printTable('taskTransactionsTable');
        };
        
        // ฟังก์ชันพิมพ์ตารางข้อมูลที่ถูกลบ
        document.getElementById('printDeletedTasksButton').onclick = function() {
            printTable('deletedTasksTable');
        };

        function printTable(tableId) {
            var printContents = document.getElementById(tableId).outerHTML;
            var newWindow = window.open('', '', 'height=600,width=800');
            newWindow.document.write('<html><head><title>Print</title></head><body>');
            newWindow.document.write(printContents);
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.print();
        }
    });
  </script>
</head>



  <main id="main" class="main">
    <div class="pagetitle">
      <h1>ข้อความการเปลี่ยนแปลงทั้งหมด</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="indexadmin.php">หน้าหลัก</a></li>
          <li class="breadcrumb-item"><a href="indexadmin.php">ข้อความ</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <h2>Task Transactions (ข้อมูลการเพิ่ม)</h2>
          <?php if ($result_tasks->num_rows > 0): ?>
            <table class="table datatable" id="taskTransactionsTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>EmployeeID</th>
                  <th>TaskName</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result_tasks->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['ID']); ?></td>
                    <td><a href="confirm_permissions.php?employeeid=<?php echo urlencode($row['EmployeeID']); ?>"><?php echo htmlspecialchars($row['EmployeeID']); ?></a></td>
                    <td><?php echo htmlspecialchars($row['TaskName']); ?></td>
                    <td><?php echo htmlspecialchars($row['Start']); ?></td>
                    <td><?php echo htmlspecialchars($row['End']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p>ไม่พบข้อมูลในตาราง tasktransactions</p>
          <?php endif; ?>

          <h2>Deleted Tasks (ข้อมูลที่ถูกลบ)</h2>
          <?php if ($result_deleted_tasks->num_rows > 0): ?>
            <table class="table datatable" id="deletedTasksTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>EmployeeID</th>
                  <th>TaskName</th>
                  <th>DeletedAt</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result_deleted_tasks->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['ID']); ?></td>
                    <td><a href="confirm_permissions.php?employeeid=<?php echo urlencode($row['EmployeeID']); ?>"><?php echo htmlspecialchars($row['EmployeeID']); ?></a></td>
                    <td><?php echo htmlspecialchars($row['TaskName']); ?></td>
                    <td><?php echo htmlspecialchars($row['DeletedAt']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p>ไม่พบข้อมูลในตาราง deleted_tasks</p>
          <?php endif; ?>

          <!-- ปิดการเชื่อมต่อฐานข้อมูล -->
          <?php $conn->close(); ?>
        </div>
      </div>

      <div>
        <button id="printButton" class="btn btn-primary">ปริ้นตารางข้อมูลที่ถูกเพิ่ม</button>
        <button id="printDeletedTasksButton" class="btn btn-danger">ปริ้นตารางข้อมูลที่ถูกลบ</button>
      </div>
    </section>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">&copy; สำนักงานหลักประกันสุขภาพแห่งชาติ <strong><span>ทบทวนสิทธิ</span></strong></div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
  </main>
</body>

</html>
<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สมมติว่า $employeeId ถูกกำหนดค่ามาจากฟอร์มหรือ session
$employeeId = $_SESSION['employee_id']; // ใช้ session เพื่อเก็บรหัสพนักงาน

// ดึงข้อมูลทั้งหมด
$sql = "SELECT * FROM your_table_name WHERE EmployeeID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeId); // ใช้รหัสพนักงาน
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบผลลัพธ์
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าสิทธิ์</title>
</head>
<body>
    <table class="table datatable">
        <thead>
            <tr>
                <th><b>ลำดับ</b></th>
                <th>รหัสผู้ใช้</th>
                <th>สิทธิ์</th>
                <th>วันเริ่มต้น</th>
                <th>วันสิ้นสุด</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $index => $task): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($task['EmployeeID']); ?></td>
                    <td><?php echo htmlspecialchars($task['TaskName']); ?></td>
                    <td><?php echo htmlspecialchars($task['Start']); ?></td>
                    <td><?php echo htmlspecialchars($task['End']); ?></td>
                    <td><?php echo htmlspecialchars($task['Status']); ?></td>
                    <td>
                        <form action="delete_permission.php" method="POST" style="display:inline;">
                            <input type="hidden" name="taskName" value="<?php echo htmlspecialchars($task['TaskName']); ?>">
                            <input type="hidden" name="employeeID" value="<?php echo htmlspecialchars($task['EmployeeID']); ?>">
                            <button type="submit" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสิทธิ์นี้?');">ลบ</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

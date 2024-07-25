<?php
require_once ('fpdf184/fpdf.php');
require_once ('db_connection.php');

$emp_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($emp_id) {
    // Define the current month period
    $pay_period_start = date('Y-m-01');
    $pay_period_end = date('Y-m-d'); // Up to today

    // Fetch user's hourly rate
    $stmt = $pdo->prepare("SELECT hourly_rate FROM users WHERE user_id = ?");
    $stmt->execute([$emp_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $hourly_rate = $user['hourly_rate'];

        // Calculate total hours worked in the current month
        $stmt = $pdo->prepare("SELECT SUM(work_hours) as total_hours FROM attendance WHERE user_id = ? AND date BETWEEN ? AND ?");
        $stmt->execute([$emp_id, $pay_period_start, $pay_period_end]);
        $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

        $total_hours = $attendance['total_hours'] ?? 0;

        // Calculate the amount
        $amount = $total_hours * $hourly_rate;

        // Generate PDF
        generatePDF($emp_id, $pay_period_start, $pay_period_end, $total_hours, $hourly_rate, $amount);
    } else {
        echo "Error: User not found.";
    }
} else {
    echo "Error: Employee ID is missing.";
}

function generatePDF($user_id, $pay_period_start, $pay_period_end, $total_hours, $hourly_rate, $amount)
{
    // Create new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 16);

    // Add content to PDF
    $pdf->Cell(0, 10, 'Salary Invoice', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "User Id: $user_id", 0, 1);
    $pdf->Cell(0, 10, "Pay Period Start: $pay_period_start", 0, 1);
    $pdf->Cell(0, 10, "Pay Period End: $pay_period_end", 0, 1);
    $pdf->Cell(0, 10, "Total Hours Worked: $total_hours", 0, 1);
    $pdf->Cell(0, 10, "Hourly Rate: $hourly_rate", 0, 1);
    $pdf->Cell(0, 10, "Salary: $amount", 0, 1);

    // Output PDF to the browser
    $pdf->Output('invoice.pdf', 'D');
}
?>

<?php
require_once ('fpdf184/fpdf.php');
require_once ('db_connection.php');
$emp_id = isset($_GET['id']) ? $_GET['id'] : 'default_value';
$salary_data = $pdo->prepare("SELECT * FROM salaries WHERE user_id=$emp_id");
$salary_data->execute();
    if ($salary_details = $salary_data->fetch(PDO::FETCH_ASSOC)) {
        $salary_id= $salary_details['salary_id'];
        $user_id= $salary_details['user_id'];
        $pay_period_start= $salary_details['salary_id'];
        $pay_period_end= $salary_details['salary_id'];
        $amount= $salary_details['amount'];


// Function to generate Invoice
    function generatePDF($salary_id, $user_id, $pay_period_start, $pay_period_end, $amount)
    {
        // Create new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('Arial', 'B', 16);

        // Add content to PDF
        $pdf->Cell(0, 10, 'Salary Invoice', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Salary Id: $salary_id", 0, 1);
        $pdf->Cell(0, 10, "User Id: $user_id", 0, 1);
        $pdf->Cell(0, 10, "Pay Period Start: $pay_period_start", 0, 1);
        $pdf->Cell(0, 10, "Pay Period End: $pay_period_end", 0, 1);
        $pdf->Cell(0, 10, "Basic Pay: $2500", 0, 1);
        $pdf->Cell(0, 10, "Deduction: 500", 0, 1);
        $pdf->Cell(0, 10, "Salary: $amount", 0, 1);

        // Output PDF to the browser
        $pdf->Output('invoice.pdf', 'D');
    }

    // Generate PDF
    generatePDF($salary_id, $user_id, $pay_period_start, $pay_period_end, $amount);
} else {
    echo "Error: Required parameters are missing.";
}
?>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FPDF;
use Illuminate\Support\Facades\Log;

class PDFWithHeader extends FPDF
{
    // Path to the logo image
    protected $logoPath = 'images/enhs.jpg';

    // Custom header
    function Header()
    {
        // Logo
        $this->Image(public_path($this->logoPath), 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Helvetica', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'Echague National High School', 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    // Custom footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Times', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        try {
            // Get the filtered subjects from the request
            $subjects = $request->input('subjects');

            if (empty($subjects)) {
                return response()->json(['error' => 'No subjects provided'], 400);
            }

            // Create a new PDF instance
            $pdf = new PDFWithHeader();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Exams Result', 0, 1, 'C');
            $pdf->Ln(10);

            // Add table headers
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(10, 15, 'No.', 1);
            $pdf->Cell(60, 15, 'Subject Name', 1);
            $pdf->Cell(30, 15, 'Year Level', 1);
            $pdf->Cell(30, 15, 'Strand', 1);
            $pdf->Cell(30, 15, 'Semester', 1);
            $pdf->Cell(35, 15, 'Generated Code', 1);
            $pdf->Ln();

            // Add table data
            $pdf->SetFont('Arial', '', 12);
            foreach ($subjects as $index => $subject) {
                $pdf->Cell(10, 15, $index + 1, 1);
                $pdf->Cell(60, 15, $subject['subjectname'], 1);
                $pdf->Cell(30, 15, $subject['yearlevel'], 1);
                $pdf->Cell(30, 15, $subject['strand'], 1);
                $pdf->Cell(30, 15, $subject['semester'], 1);
                $pdf->Cell(35, 15, $subject['gen_code'], 1);
                $pdf->Ln();
            }

            // Output the PDF
            return response($pdf->Output('S', 'subjects_report.pdf'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="subjects_report.pdf"');

        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}


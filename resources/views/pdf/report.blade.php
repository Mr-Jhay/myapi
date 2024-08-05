<!-- resources/views/pdf/report.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Laravel PDF Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>This is a sample PDF generated using Laravel and dompdf.</p>
</body>
</html>

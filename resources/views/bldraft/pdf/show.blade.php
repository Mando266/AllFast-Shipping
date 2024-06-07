<!-- pdf/show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>My PDF Form</title>
</head>
<body>
    <iframe srcdoc="{{ $pdfContent }}" style="width: 100%; height: 800px;"></iframe>
</body>
</html>

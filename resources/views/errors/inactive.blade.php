<!-- resources/views/account/inactive.blade.php -->
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حساب غير مفعل</title>
</head>
<body>
<div class="alert alert-warning">
    {{ session('error') }}  <!-- Display the error message passed from middleware -->
</div>
<!-- Optionally provide a link or a button to return to the home page -->
<a href="{{ route('login') }}">العودة إلى الصفحة الرئيسية</a>
</body>
</html>

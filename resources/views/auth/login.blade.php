<!DOCTYPE html>
<html>
<head>
    <title>Login Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card p-4 shadow-lg rounded-4" style="width: 400px;">
        <h3 class="text-center mb-4 fw-bold">Login Petugas</h3>
        
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
            <a href="{{ route('home') }}" class="btn btn-link w-100 mt-2">Kembali ke Dashboard Pemohon</a>
        </form>
    </div>
</body>
</html>
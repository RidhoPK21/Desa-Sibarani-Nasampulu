<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Test - Statistic Service</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 24px; }
        .wrapper { max-width: 980px; margin: auto; background: #fff; border-radius: 12px; box-shadow: 0 12px 40px rgba(0,0,0,.08); padding: 24px; }
        h1 { margin-top: 0; }
        .actions { margin-bottom: 20px; }
        .actions a { display: inline-block; margin-right: 12px; padding: 10px 16px; text-decoration: none; color: #fff; background: #2563eb; border-radius: 8px; }
        .actions a.secondary { background: #4b5563; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f3f4f6; color: #111827; }
        tr:hover { background: #f9fafb; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 999px; background: #e0f2fe; color: #0369a1; font-size: 12px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Daftar Test</h1>
        <div class="actions">
            <a href="/test-results">Buka Coverage Test</a>
            <a href="/" class="secondary">Kembali ke Home</a>
        </div>

        <p>Di bawah ini adalah semua file test yang terdaftar dalam folder <code>tests/</code>.</p>

        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>Jenis</th>
                    <th>Nama Test</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tests as $test)
                    <tr>
                        <td>{{ $test['relative'] }}</td>
                        <td><span class="badge">{{ $test['type'] }}</span></td>
                        <td>{{ $test['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

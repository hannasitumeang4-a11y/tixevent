<!DOCTYPE html>
<html>
<head>
    <title>Detail Event</title>
</head>
<body>

    <h1>{{ $event->title }}</h1>

    <p><strong>Deskripsi:</strong> {{ $event->description }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($event->price, 0, ',', '.') }}</p>
    <p><strong>Lokasi:</strong> {{ $event->location }}</p>
    <p><strong>Tanggal:</strong> {{ $event->date }}</p>

    <br>

    <a href="/">← Kembali ke Daftar Event</a>

</body>
</html>
{{-- Anda bisa mengganti ini dengan layout utama Anda --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-20 max-w-md">
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <h1 class="text-2xl font-bold text-center mt-4">Pendaftaran Berhasil!</h1>
            <p class="text-center text-gray-600 my-4">Terima kasih, {{ $visit->guest->name }}.<br>Tunjukkan QR Code ini kepada resepsionis.</p>
            <div id="qrcode" class="flex justify-center p-4"></div>
            <p class="text-xs text-gray-500 mt-4">Anda dapat melakukan screenshot halaman ini.</p>
        </div>
    </div>
    <script type="text/javascript">
        var qr = qrcode(4, 'L');
        qr.addData('{{ $visit->uuid }}');
        qr.make();
        document.getElementById('qrcode').innerHTML = qr.createImgTag(5, 10); // (size, margin)
    </script>
</body>
</html>
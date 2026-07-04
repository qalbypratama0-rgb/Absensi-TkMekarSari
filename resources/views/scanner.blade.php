<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiosk Scanner - AbsenQR</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .clock-font {
            font-family: 'Share Tech Mono', monospace;
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col justify-between overflow-hidden relative">
    <!-- Decorative background glow -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- Header Section -->
    <header class="h-20 border-b border-white/5 flex items-center justify-between px-8 z-10">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-600/10 border border-blue-500/20 rounded-xl text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M4 8h16M4 16h16" />
                </svg>
            </div>
            <div>
                <h1 class="text-sm font-bold tracking-tight uppercase text-gray-200">Kiosk Scanner</h1>
                <p class="text-[10px] text-gray-500 font-semibold tracking-wide uppercase">SMK NEGERI 1 JAKARTA</p>
            </div>
        </div>
        
        <!-- Live Clock -->
        <div class="text-right">
            <div id="clock" class="clock-font text-2xl font-bold tracking-wider text-blue-400">00:00:00</div>
            <div id="date" class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mt-0.5">Sabtu, 4 Juli 2026</div>
        </div>
    </header>

    <!-- Main Scanner Panel -->
    <main class="flex-1 flex flex-col items-center justify-center p-8 z-10">
        <div class="w-full max-w-lg bg-slate-900/60 border border-white/5 backdrop-blur-xl rounded-[28px] p-6 shadow-2xl flex flex-col items-center">
            <!-- Target Camera Frame -->
            <div class="w-full aspect-[4/3] bg-black/40 rounded-2xl overflow-hidden border border-white/5 relative flex items-center justify-center mb-6">
                <!-- Camera view portal -->
                <div id="reader" class="w-full h-full object-cover"></div>
                
                <!-- Scanner Overlay lines -->
                <div class="absolute inset-0 border-2 border-blue-500/20 pointer-events-none rounded-2xl"></div>
                <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-blue-500/60 shadow-[0_0_15px_rgba(59,130,246,0.6)] animate-pulse pointer-events-none"></div>
            </div>

            <div class="text-center">
                <h3 class="text-base font-bold text-gray-100">Arahkan Kartu QR Anda</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-[280px] mx-auto leading-relaxed">Dekatkan kode QR pada lensa kamera untuk mencatat kehadiran siswa secara instan.</p>
            </div>
        </div>
    </main>

    <!-- Footer System Status -->
    <footer class="h-14 border-t border-white/5 flex items-center justify-between px-8 text-[10px] text-gray-500 font-semibold tracking-wider uppercase z-10">
        <span>Sistem Absensi v1.0</span>
        <span class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span>
            Kiosk Online
        </span>
    </footer>

    <!-- Audio Beep and Scanner Logic -->
    <script>
        // Digital Clock & Date Update
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date').textContent = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Web Audio API Synth Beeps
        function playBeep(type = 'success') {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                
                if (type === 'success') {
                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // high pitch
                    gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);
                    oscillator.start();
                    oscillator.stop(audioCtx.currentTime + 0.15);
                } else {
                    oscillator.type = 'sawtooth';
                    oscillator.frequency.setValueAtTime(220, audioCtx.currentTime); // low buzz
                    gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);
                    oscillator.start();
                    oscillator.stop(audioCtx.currentTime + 0.35);
                }
            } catch (e) {
                console.error('Audio Context Error:', e);
            }
        }

        // html5-qrcode scanner setup
        let isCooldown = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isCooldown) return;
            isCooldown = true;

            // Send to POST /api/absen
            fetch('/api/absen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ nis: decodedText })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 || status === 201) {
                    playBeep('success');
                    
                    let badgeColor = body.data.status === 'Hadir' ? '#16a34a' : '#d97706';
                    
                    Swal.fire({
                        title: body.message,
                        html: `
                            <div class="mt-2 text-center">
                                <div class="text-lg font-bold text-slate-800">${body.data.nama}</div>
                                <div class="text-xs text-slate-400 mt-0.5">NIS: ${body.data.nis} (${body.data.kelas})</div>
                                <div class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-bold text-white" style="background-color: ${badgeColor}">
                                    ${body.data.status} pada ${body.data.waktu}
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-[20px]',
                        }
                    });
                } else {
                    playBeep('error');
                    
                    Swal.fire({
                        title: 'Gagal Absen',
                        text: body.message || 'Terjadi kesalahan sistem.',
                        icon: 'warning',
                        timer: 2500,
                        showConfirmButton: false,
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-[20px]',
                        }
                    });
                }
            })
            .catch(error => {
                playBeep('error');
                console.error('Scan API Error:', error);
                Swal.fire({
                    title: 'System Error',
                    text: 'Koneksi ke server terputus.',
                    icon: 'error',
                    timer: 2500,
                    showConfirmButton: false
                });
            })
            .finally(() => {
                // Cooldown 3 seconds
                setTimeout(() => {
                    isCooldown = false;
                }, 3000);
            });
        }

        // Initialize Camera
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 15, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.333333
            },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>

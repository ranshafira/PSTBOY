<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian PST - BPS Kabupaten Boyolali</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bps-primary': '#002D72',
                        'bps-dark': '#001C48',
                        'bps-orange': '#ff6600',
                    },
                    animation: {
                        'pulse-slow': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-subtle': 'bounce 1s ease-in-out 2',
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'blink': 'blink 1.5s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        glow: {
                            '0%': {
                                boxShadow: '0 0 20px rgba(255, 102, 0, 0.5)'
                            },
                            '100%': {
                                boxShadow: '0 0 40px rgba(255, 102, 0, 0.8)'
                            }
                        },
                        blink: {
                            '0%, 100%': {
                                opacity: '1'
                            },
                            '50%': {
                                opacity: '0.5'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Roboto+Mono:wght@400;700&display=swap');

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #001C48 0%, #002D72 50%, #001C48 100%);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        .digital-font {
            font-family: 'Roboto Mono', 'Courier New', monospace;
        }

        .text-glow {
            text-shadow: 0 0 10px rgba(255, 102, 0, 0.8);
        }

        .counter-display {
            background: linear-gradient(180deg, #1a1a1a 0%, #000000 100%);
            border: 3px solid #333;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5), inset 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .running-text {
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .video-container {
            position: relative;
            background: #000;
            border: 4px solid #ff6600;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
            height: 500px;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .video-container:hover .video-overlay {
            opacity: 1;
        }

        .video-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 10px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: 20;
        }

        .video-container:hover .video-controls {
            transform: translateY(0);
        }

        .video-title {
            background: rgba(0, 45, 114, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10px;
        }

        .play-button {
            width: 60px;
            height: 60px;
            background: rgba(255, 102, 0, 0.9);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 102, 0, 0.4);
        }

        .play-button:hover {
            transform: scale(1.1);
            background: rgba(255, 102, 0, 1);
            box-shadow: 0 6px 30px rgba(255, 102, 0, 0.6);
        }

        .video-status {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(0, 0, 0, 0.8);
            color: #00ff00;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 15;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #00ff00;
            animation: pulse 2s infinite;
        }

        .queue-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            height: 100%;
        }

        .video-section {
            position: relative;
        }

        .queue-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .current-queue {
            background: linear-gradient(135deg, #002D72 0%, #001C48 100%);
            border: 3px solid #ff6600;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 20px rgba(255, 102, 0, 0.4);
        }

        .next-queues {
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #333;
            border-radius: 8px;
            padding: 15px;
        }

        .next-queue-item {
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px solid #333;
        }

        .next-queue-item:last-child {
            border-bottom: none;
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(to right, #001C48, #002D72);
            border-top: 3px solid #ff6600;
            padding: 15px 0;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.2);
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 5px;
            padding: 0 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .footer-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
        }

        .footer-developer {
            font-weight: 600;
            color: #ff6600;
        }

        @media (max-width: 768px) {
            .footer-content {
                padding: 0 10px;
            }
        }
    </style>
</head>

<body class="min-h-screen text-white overflow-x-hidden flex flex-col">

    <!-- Header dan bagian atas sama seperti sebelumnya -->
    <header class="bg-gradient-to-r from-bps-dark to-bps-primary shadow-2xl border-b-4 border-bps-orange">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center animate-fade-in">
                <img src="{{ asset('images/BPS-logo.png') }}" alt="BPS Logo" class="h-16 mr-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">BPS Kabupaten Boyolali</h1>
                    <p class="text-blue-200 text-sm">Pelayanan Statistik Terpadu</p>
                </div>
            </div>
            <div class="text-right animate-slide-up">
                <div id="jam" class="text-3xl font-bold text-bps-orange digital-font px-3 py-1">--:--:--</div>
                <div id="tanggal" class="text-sm text-blue-200 mt-1">-- ---- ----</div>
            </div>
        </div>
    </header>

    <!-- Running Text Banner -->
    <div class="bg-bps-orange text-white py-2 overflow-hidden">
        <div class="running-text text-sm font-medium px-4">
            Selamat datang di Pelayanan Statistik Terpadu BPS Kabupaten Boyolali. Silakan ambil nomor antrian sesuai dengan kebutuhan Anda. Terima kasih atas kunjungan Anda.
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-6 flex-grow">
        <div class="queue-grid">
            <div class="video-section">
                <div class="video-container animate-fade-in">
                    <div class="video-status" id="videoStatus">
                        <div class="status-indicator"></div>
                        VIDEO OFFLINE BPS
                    </div>

                    <video id="mainVideo" class="w-full h-full object-contain" style="display: none;">
                        Your browser does not support the video tag.
                    </video>

                    <div id="defaultVideoContent" class="w-full h-full flex flex-col justify-center items-center relative bg-black">
                        <img src="{{ asset('images/BPS-logo.png') }}" alt="BPS Logo" class="h-48 mx-auto mb-4 opacity-90 animate-pulse">
                        <h3 class="text-3xl font-bold text-white mb-2">Area Video Offline BPS</h3>
                        <p class="text-blue-200">Video informasi dan promosi BPS akan ditampilkan di sini</p>
                        <div class="mt-8 flex justify-center items-center gap-4">
                            <button class="play-button" onclick="loadVideo()">▶</button>
                        </div>
                    </div>

                    <!-- Overlay dan controls tetap -->
                    <div class="video-overlay">
                        <div class="video-title" id="videoTitle">BPS Kabupaten Boyolali - Pelayanan Statistik Terpadu</div>
                        <button class="play-button" onclick="toggleVideo()">
                            <span id="playPauseIcon">▶</span>
                        </button>
                    </div>

                    <div class="video-controls">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <button onclick="previousVideo()" class="text-white hover:text-bps-orange transition-colors">⏮</button>
                                <button onclick="toggleVideo()" class="text-white hover:text-bps-orange transition-colors">
                                    <span id="playPauseIcon2">▶</span>
                                </button>
                                <button onclick="nextVideo()" class="text-white hover:text-bps-orange transition-colors">⏭</button>
                            </div>
                            <div class="text-white text-sm">
                                <span id="currentTime">00:00</span> / <span id="duration">00:00</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-white text-sm">🔊</span>
                                <input type="range" id="volumeSlider" min="0" max="100" value="50" class="w-16">
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="bg-gray-600 rounded-full h-1">
                                <div id="progressBar" class="bg-bps-orange rounded-full h-1 w-0 transition-all"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="queue-section">
                <!-- Current Queue -->
                <div class="current-queue">
                    <div class="text-center text-yellow-500 text-sm font-bold mb-1">SEDANG DILAYANI</div>
                    <div id="nomorAktif" class="text-center text-5xl font-black text-green-500 digital-font animate-blink">
                        @if($antrianAktif)
                        {{ $antrianAktif->nomor_antrian }}
                        @else
                        ---
                        @endif
                    </div>
                    <div id="layananAktif" class="text-center text-sm text-white mt-1">
                        @if($antrianAktif && $antrianAktif->jenisLayanan)
                        {{ strtoupper($antrianAktif->jenisLayanan->nama_layanan) }}
                        @else
                        TIDAK ADA ANTRIAN AKTIF
                        @endif
                    </div>
                </div>

                <!-- Next Queues -->
                <div class="next-queues">
                    <h3 class="text-lg font-semibold text-blue-200 mb-4 text-center">ANTRIAN BERIKUTNYA</h3>
                    <div id="antrianBerikutnya">
                        @php
                        $colors = ['text-yellow-200','text-blue-200','text-green-200','text-purple-200','text-pink-200'];
                        @endphp

                        @forelse($antrianBerikutnya as $index => $antrian)
                        <div class="next-queue-item {{ $colors[$index] ?? $colors[0] }}">
                            <span class="font-semibold">{{ $antrian->nomor_antrian }}</span>
                            <span class="text-sm opacity-80">{{ $antrian->jenisLayanan->nama_layanan ?? 'Layanan' }}</span>
                        </div>
                        @empty
                        <div class="text-center text-gray-400 py-4">
                            <p>Tidak ada antrian selanjutnya</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div>© {{ date('Y') }} Pelayanan Statistik Terpadu BPS Kabupaten Boyolali</div>
            <div>Dikembangkan oleh <span class="footer-developer">MAGANGSBOY</span></div>
        </div>
    </footer>

    <audio id="notificationSound" preload="auto">
        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('sounds/notification.ogg') }}" type="audio/ogg">
    </audio>

    <script>
        // Daftar video diarahkan ke video publik
        let videoList = [{
                title: "Video Offline BPS 1",
                src: "/videos/video1.mp4",
                duration: "03:45"
            },
            {
                title: "Video Offline BPS 2",
                src: "/videos/video2.mp4",
                duration: "02:30"
            },
            {
                title: "Video Offline BPS 3",
                src: "/videos/video3.mp4",
                duration: "04:15"
            }
        ];

        let isVideoPlaying = false;
        let currentVideoIndex = 0;

        function loadVideo() {
            const video = document.getElementById('mainVideo');
            const defaultContent = document.getElementById('defaultVideoContent');
            const statusEl = document.getElementById('videoStatus');

            if (videoList.length > 0) {
                video.src = videoList[currentVideoIndex].src;
                video.load();

                video.onloadeddata = function() {
                    defaultContent.style.display = 'none';
                    video.style.display = 'block';
                    statusEl.innerHTML = '<div class="status-indicator"></div> VIDEO ON AIR';
                    statusEl.style.background = 'rgba(0, 255, 0, 0.8)';
                    updateVideoTitle();
                };

                video.onerror = function(e) {
                    console.error('Video tidak dapat dimuat:', e);
                    showDefaultVideo();
                };
            } else {
                showDefaultVideo();
            }
        }

        function showDefaultVideo() {
            const video = document.getElementById('mainVideo');
            const defaultContent = document.getElementById('defaultVideoContent');
            const statusEl = document.getElementById('videoStatus');

            defaultContent.style.display = 'flex';
            video.style.display = 'none';
            statusEl.innerHTML = '<div class="status-indicator"></div> VIDEO OFFLINE BPS';
            statusEl.style.background = 'rgba(255, 0, 0, 0.8)';
        }

        function toggleVideo() {
            const video = document.getElementById('mainVideo');
            const icon1 = document.getElementById('playPauseIcon');
            const icon2 = document.getElementById('playPauseIcon2');

            if (video.style.display === 'block') {
                if (video.paused) {
                    video.play();
                    icon1.textContent = '⏸';
                    icon2.textContent = '⏸';
                    isVideoPlaying = true;
                } else {
                    video.pause();
                    icon1.textContent = '▶';
                    icon2.textContent = '▶';
                    isVideoPlaying = false;
                }
            } else {
                loadVideo();
            }
        }

        function nextVideo() {
            if (videoList.length > 1) {
                currentVideoIndex = (currentVideoIndex + 1) % videoList.length;
                loadVideo();
                if (isVideoPlaying) {
                    setTimeout(() => {
                        document.getElementById('mainVideo').play();
                    }, 100);
                }
            }
        }

        function previousVideo() {
            if (videoList.length > 1) {
                currentVideoIndex = (currentVideoIndex - 1 + videoList.length) % videoList.length;
                loadVideo();
                if (isVideoPlaying) {
                    setTimeout(() => {
                        document.getElementById('mainVideo').play();
                    }, 100);
                }
            }
        }

        function updateVideoTitle() {
            const titleEl = document.getElementById('videoTitle');
            if (videoList[currentVideoIndex]) {
                titleEl.textContent = videoList[currentVideoIndex].title;
            }
        }

        function updateVideoProgress() {
            const video = document.getElementById('mainVideo');
            const progressBar = document.getElementById('progressBar');
            const currentTimeEl = document.getElementById('currentTime');
            const durationEl = document.getElementById('duration');

            if (video && video.duration) {
                const pct = (video.currentTime / video.duration) * 100;
                progressBar.style.width = pct + '%';
                currentTimeEl.textContent = formatTime(video.currentTime);
                durationEl.textContent = formatTime(video.duration);
            }
        }

        function formatTime(sec) {
            const m = Math.floor(sec / 60);
            const s = Math.floor(sec % 60);
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        function updateWaktu() {
            const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const now = new Date();
            const tanggal = now.getDate();
            const namaHari = hari[now.getDay()];
            const namaBulan = bulan[now.getMonth()];
            const tahun = now.getFullYear();

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('tanggal').textContent = `${namaHari}, ${tanggal} ${namaBulan} ${tahun}`;
            document.getElementById('jam').textContent = `${jam}:${menit}:${detik}`;
        }

        function updateNextQueues(queues) {
            const container = document.getElementById('antrianBerikutnya');
            const colors = ['text-yellow-200', 'text-blue-200', 'text-green-200', 'text-purple-200', 'text-pink-200'];

            if (queues && Array.isArray(queues) && queues.length > 0) {
                container.innerHTML = queues.map((antrian, index) => {
                    const nomor = antrian.nomor_antrian || '---';
                    const layanan = antrian.jenis_layanan?.nama_layanan || 'Layanan';
                    return `
                        <div class="next-queue-item ${colors[index] || colors[0]}">
                            <span class="font-semibold">${nomor}</span>
                            <span class="text-sm opacity-80">${layanan}</span>
                        </div>
                    `;
                }).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-4">
                        <p>Tidak ada antrian selanjutnya</p>
                    </div>
                `;
            }
        }

        function animateQueueChange(element, newQueue) {
            const layananTextEl = document.getElementById('layananAktif');
            element.style.transform = 'scale(0.8)';
            element.style.opacity = '0.5';

            setTimeout(() => {
                element.textContent = newQueue?.nomor_antrian || '---';
                layananTextEl.textContent = (newQueue?.jenis_layanan?.nama_layanan || 'LAYANAN').toUpperCase();

                element.style.transform = 'scale(1)';
                element.style.opacity = '1';
                element.classList.add('animate-bounce-subtle');

                setTimeout(() => {
                    element.classList.remove('animate-bounce-subtle');
                }, 1000);
            }, 500);
        }

        async function fetchUpdatedData() {
            try {
                const response = await axios.get('{{ route("display.api") }}');
                const data = response.data;

                // update antrian aktif
                const nomorAktifEl = document.getElementById('nomorAktif');
                const layananAktifEl = document.getElementById('layananAktif');

                if (data.antrianAktif) {
                    if (!window.currentQueue || window.currentQueue.nomor_antrian !== data.antrianAktif.nomor_antrian) {
                        animateQueueChange(nomorAktifEl, data.antrianAktif);
                        document.getElementById('notificationSound').play();
                        window.currentQueue = data.antrianAktif;
                    }
                } else {
                    nomorAktifEl.textContent = '---';
                    layananAktifEl.textContent = 'TIDAK ADA ANTRIAN AKTIF';
                    window.currentQueue = null;
                }

                updateNextQueues(data.antrianBerikutnya);

            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // init
        window.currentQueue = null;
        updateWaktu();
        setInterval(updateWaktu, 1000);

        fetchUpdatedData();
        setInterval(fetchUpdatedData, 5000);

        // video listeners & init
        const videoElem = document.getElementById('mainVideo');
        videoElem.addEventListener('timeupdate', updateVideoProgress);
        videoElem.addEventListener('ended', () => {
            // Naikkan indeks video, dan ulang ke awal jika sudah sampai akhir
            currentVideoIndex = (currentVideoIndex + 1) % videoList.length;
            loadVideo();

            // Putar otomatis jika sebelumnya sedang berjalan
            if (isVideoPlaying) {
                setTimeout(() => {
                    videoElem.play();
                }, 200);
            }
        });
    </script>

</body>

</html>
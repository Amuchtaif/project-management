<?php
// app/helpers/Flasher.php

class Flasher {
    public static function setFlash($pesan, $aksi, $tipe) {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe
        ];
    }

    private static function formatMessage($pesan, $aksi) {
        $pesan = trim($pesan ?? '');
        $aksi = trim($aksi ?? '');
        if (empty($aksi)) {
            $text = $pesan;
        } elseif (strtolower($pesan) === 'berhasil' && in_array(strtolower($aksi), ['dihapus', 'ditambahkan', 'diperbarui'])) {
            $text = 'Data berhasil ' . $aksi;
        } elseif (strtolower($pesan) === 'gagal' && in_array(strtolower($aksi), ['dihapus', 'ditambahkan', 'diperbarui'])) {
            $text = 'Data gagal ' . $aksi;
        } elseif (stripos($aksi, $pesan) !== false) {
            $text = $aksi;
        } else {
            $text = $pesan . ' ' . $aksi;
        }
        $text = ucfirst(trim($text));
        if (!empty($text) && !in_array(substr($text, -1), ['.', '!', '?'])) {
            $text .= '.';
        }
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $rawTipe = strtolower($_SESSION['flash']['tipe'] ?? 'success');
            
            if ($rawTipe === 'success') {
                $bgClass = 'bg-emerald-600';
                $iconClass = 'fa-solid fa-check';
                $titleText = 'Berhasil';
            } elseif ($rawTipe === 'warning') {
                $bgClass = 'bg-amber-600';
                $iconClass = 'fa-solid fa-triangle-exclamation';
                $titleText = 'Perhatian';
            } elseif ($rawTipe === 'info') {
                $bgClass = 'bg-indigo-600';
                $iconClass = 'fa-solid fa-info';
                $titleText = 'Informasi';
            } else {
                $bgClass = 'bg-rose-600';
                $iconClass = 'fa-solid fa-xmark';
                $titleText = 'Gagal';
            }

            $message = self::formatMessage($_SESSION['flash']['pesan'], $_SESSION['flash']['aksi']);
            
            echo '<div id="notification-bar" class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] max-w-[92vw] sm:max-w-md w-full pointer-events-none transform transition-all duration-300 ease-out -translate-y-8 opacity-0 scale-95">
                    <div class="' . $bgClass . ' text-white px-4 py-3 sm:px-4.5 sm:py-3.5 rounded-2xl shadow-xl shadow-black/20 border border-white/20 flex items-center justify-between gap-3 pointer-events-auto relative overflow-hidden" role="alert">
                        <div class="flex items-center gap-3.5 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-white shadow-inner">
                                <i class="' . $iconClass . ' text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-white/80 leading-none mb-1">' . $titleText . '</p>
                                <p class="text-xs sm:text-sm font-semibold text-white leading-snug break-words">' . $message . '</p>
                            </div>
                        </div>
                        <button type="button" onclick="hideNotification()" class="p-1.5 text-white/70 hover:text-white hover:bg-white/20 rounded-xl transition flex-shrink-0" title="Tutup notifikasi">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                        <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/20">
                            <div id="toast-progress-bar" class="h-full bg-white/60 transition-all duration-[3500ms] ease-linear w-full"></div>
                        </div>
                    </div>
                    <script>
                        setTimeout(() => {
                            const bar = document.getElementById("notification-bar");
                            if (bar) {
                                bar.classList.remove("-translate-y-8", "opacity-0", "scale-95");
                                bar.classList.add("translate-y-0", "opacity-100", "scale-100");
                            }
                            const prog = document.getElementById("toast-progress-bar");
                            if (prog) prog.style.width = "0%";
                        }, 30);
                        if (typeof notificationTimer !== "undefined" && notificationTimer) clearTimeout(notificationTimer);
                        notificationTimer = setTimeout(() => {
                            if (typeof hideNotification === "function") hideNotification();
                        }, 3600);
                    </script>
                  </div>';
            
            unset($_SESSION['flash']);
        }
    }

    public static function flashInline() {
        if (isset($_SESSION['flash'])) {
            $rawTipe = strtolower($_SESSION['flash']['tipe'] ?? 'success');
            $isSuccess = ($rawTipe === 'success');
            
            $bg = $isSuccess ? 'bg-emerald-600' : 'bg-rose-600';
            $icon = $isSuccess 
                ? '<i class="fa-solid fa-check text-base"></i>'
                : '<i class="fa-solid fa-xmark text-base"></i>';
            $title = $isSuccess ? 'Berhasil' : 'Pemberitahuan';
            $message = self::formatMessage($_SESSION['flash']['pesan'], $_SESSION['flash']['aksi']);
            
            echo '<div id="inline-notification" class="flex items-center gap-3.5 p-4 mb-6 text-white ' . $bg . ' rounded-2xl shadow-lg border border-white/20 transition-all duration-300 overflow-hidden relative">
                    <div class="flex-shrink-0 bg-white/20 w-9 h-9 rounded-xl flex items-center justify-center">
                        ' . $icon . '
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-white/80 leading-none mb-1">' . $title . '</p>
                        <p class="text-xs sm:text-sm font-semibold text-white leading-snug break-words">' . $message . '</p>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/20">
                        <div id="inline-notification-progress" class="h-full bg-white/60 transition-all duration-[3000ms] ease-linear w-full"></div>
                    </div>
                    
                    <script>
                        setTimeout(() => {
                            const bar = document.getElementById("inline-notification-progress");
                            if(bar) bar.style.width = "0%";
                        }, 10);
                        
                        setTimeout(() => {
                            const notify = document.getElementById("inline-notification");
                            if(notify) {
                                notify.classList.add("opacity-0", "scale-95", "-translate-y-2");
                                setTimeout(() => notify.remove(), 300);
                            }
                        }, 3300);
                    </script>
                  </div>';
            
            unset($_SESSION['flash']);
        }
    }
}

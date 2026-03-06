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

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $isSuccess = $_SESSION['flash']['tipe'] == 'success';
            $bg = $isSuccess 
                ? 'bg-green-100 dark:bg-green-900/30 border-green-500 text-green-700 dark:text-green-400' 
                : 'bg-red-100 dark:bg-red-900/30 border-red-500 text-red-700 dark:text-red-400';
            
            $iconBg = $isSuccess ? 'bg-white/50 dark:bg-green-800/20' : 'bg-white/50 dark:bg-red-800/20';
            $iconColor = $isSuccess ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
            $iconSvg = $isSuccess 
                ? '<svg class="w-5 h-5 ' . $iconColor . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                : '<svg class="w-5 h-5 ' . $iconColor . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            
            echo '<div id="notification-bar" class="fixed top-8 left-1/2 -translate-x-1/2 z-[9999] transform transition-all duration-500 -translate-y-10 opacity-0 pointer-events-none">
                    <div class="' . $bg . ' border-l-4 p-4 shadow-2xl rounded-2xl flex items-center justify-between min-w-[280px] xs:min-w-[320px] pointer-events-auto" role="alert">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-full ' . $iconBg . '">
                                ' . $iconSvg . '
                            </div>
                            <div>
                                <p class="font-black text-sm uppercase tracking-tight">' . ucfirst($_SESSION['flash']['tipe']) . '</p>
                                <p class="text-xs font-bold opacity-80">' . $_SESSION['flash']['pesan'] . ' ' . $_SESSION['flash']['aksi'] . '.</p>
                            </div>
                        </div>
                    </div>
                    <script>
                        setTimeout(() => {
                            const bar = document.getElementById("notification-bar");
                            if(bar) bar.classList.remove("-translate-y-10", "opacity-0");
                        }, 100);
                        setTimeout(() => {
                            const bar = document.getElementById("notification-bar");
                            if(bar) {
                                bar.classList.add("-translate-y-10", "opacity-0");
                                setTimeout(() => bar.remove(), 500);
                            }
                        }, 4000);
                    </script>
                  </div>';
            
            unset($_SESSION['flash']);
        }
    }
    public static function flashInline() {
        if (isset($_SESSION['flash'])) {
            $isSuccess = $_SESSION['flash']['tipe'] == 'success';
            $bg = $isSuccess ? 'bg-emerald-600 shadow-emerald-100' : 'bg-rose-600 shadow-rose-100';
            $icon = $isSuccess 
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            
            echo '<div id="inline-notification" class="flex items-center gap-4 p-4 mb-8 text-white ' . $bg . ' rounded-2xl shadow-lg transition-all duration-500 overflow-hidden relative">
                    <div class="flex-shrink-0 bg-white/20 p-2 rounded-xl">
                        ' . $icon . '
                    </div>
                    <div class="flex-grow">
                        <p class="text-xs font-black uppercase tracking-[0.1em] opacity-80 mb-0.5">' . $_SESSION['flash']['pesan'] . '</p>
                        <p class="text-sm font-bold leading-tight">' . ucfirst($_SESSION['flash']['aksi']) . '</p>
                    </div>
                    <div class="absolute bottom-0 left-0 h-1 bg-white/30 transition-all duration-[3000ms] ease-linear w-full" id="notification-progress"></div>
                    
                    <script>
                        setTimeout(() => {
                            const bar = document.getElementById("notification-progress");
                            if(bar) bar.style.width = "0%";
                        }, 10);
                        
                        setTimeout(() => {
                            const notify = document.getElementById("inline-notification");
                            if(notify) {
                                notify.classList.add("opacity-0", "scale-95", "-translate-y-2");
                                setTimeout(() => notify.remove(), 500);
                            }
                        }, 3000);
                    </script>
                  </div>';
            
            unset($_SESSION['flash']);
        }
    }
}

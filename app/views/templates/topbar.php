<!-- Main Content Area -->
<div class="flex-grow flex flex-col h-screen overflow-hidden">
    <!-- Top Header -->
    <header class="h-16 md:h-20 bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between px-4 md:px-8 flex-shrink-0 transition-colors duration-300">
        <div class="flex items-center gap-4">
            <!-- MOBILE MENU TOGGLE -->
            <button class="md:hidden p-2 text-gray-400 hover:text-indigo-600 rounded-xl transition" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <div class="relative flex-grow max-w-[160px] sm:max-w-xs md:max-w-md cursor-pointer" onclick="openCommandPalette()">
                <input type="text" placeholder="Cari... (Ctrl + K)" readonly class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700/80 rounded-xl md:rounded-2xl pl-9 pr-14 md:pl-10 md:pr-16 py-2.5 md:py-3 text-[11px] md:text-sm text-gray-500 dark:text-gray-300 focus:outline-none cursor-pointer transition select-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 absolute left-3.5 md:left-4 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
                <kbd class="hidden sm:flex items-center gap-0.5 absolute right-3 top-1/2 -translate-y-1/2 px-2 py-0.5 text-[10px] font-black text-gray-400 dark:text-gray-500 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xs pointer-events-none">
                    <span class="text-[9px]">⌘</span>K
                </kbd>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-4">
            <!-- Theme Toggle (Desktop Only) -->
            <button onclick="toggleDarkMode()" class="hidden sm:flex p-2 text-gray-400 hover:text-indigo-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition relative overflow-hidden" id="theme-toggle">
                <!-- Sun Icon -->
                <i id="sun-icon" class="fa-solid fa-sun text-lg hidden dark:block"></i>
                <!-- Moon Icon -->
                <i id="moon-icon" class="fa-solid fa-moon text-lg block dark:hidden"></i>
            </button>

            <!-- Notifications -->
            <div class="relative custom-dropdown" id="notifDropdown">
                <button onclick="toggleDropdown('notifDropdown')" class="p-2 text-gray-400 hover:text-indigo-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition relative">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <?php if (!empty($data['notifications'])) : ?>
                        <span id="notifRedDot" class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white dark:border-slate-900 rounded-full animate-pulse"></span>
                    <?php endif; ?>
                </button>
                
                <div class="dropdown-menu fixed sm:absolute inset-x-4 sm:inset-auto top-20 sm:top-full sm:right-0 sm:mt-2 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-3xl shadow-2xl py-3 hidden z-50 overflow-hidden" style="width: 360px; max-width: calc(100vw - 2rem);">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-slate-700/80 mb-2 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-black text-gray-900 dark:text-gray-100">Notifikasi</h4>
                            <?php if (!empty($data['notifications'])) : ?>
                                <span id="notifBadgeCount" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400"><?= count($data['notifications']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($data['notifications'])) : ?>
                            <button type="button" id="btnClearAllNotif" onclick="clearAllNotifications(event)" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 flex items-center gap-1.5 transition px-2 py-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/30" title="Bersihkan semua notifikasi">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                <span>Clear Semua</span>
                            </button>
                        <?php endif; ?>
                    </div>
                    <div id="notifListContainer" class="max-h-[65vh] sm:max-h-84 overflow-y-auto px-2 space-y-1.5">
                        <?php if (empty($data['notifications'])) : ?>
                            <div class="px-4 py-8 text-center" id="emptyNotifState">
                                <i class="fa-solid fa-bell-slash text-2xl text-gray-300 dark:text-gray-600 mb-2 block"></i>
                                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium italic">Tidak ada notifikasi baru.</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($data['notifications'] as $notif) : 
                                $type = $notif['type'] ?? 'deadline';
                                if ($type === 'new_project') {
                                    $borderColor = 'border-indigo-500';
                                    $bgColor = 'bg-indigo-50/70 dark:bg-indigo-950/20';
                                    $textColor = 'text-indigo-700 dark:text-indigo-400';
                                    $icon = '<i class="fa-solid fa-rocket text-indigo-500 text-xs"></i>';
                                    $badge = 'PROJECT BARU';
                                    $badgeClass = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300';
                                } elseif ($type === 'new_task') {
                                    $borderColor = 'border-sky-500';
                                    $bgColor = 'bg-sky-50/70 dark:bg-sky-950/20';
                                    $textColor = 'text-sky-700 dark:text-sky-400';
                                    $icon = '<i class="fa-solid fa-list-check text-sky-500 text-xs"></i>';
                                    $badge = 'TASK BARU';
                                    $badgeClass = 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300';
                                } elseif ($type === 'new_comment') {
                                    $borderColor = 'border-emerald-500';
                                    $bgColor = 'bg-emerald-50/70 dark:bg-emerald-950/20';
                                    $textColor = 'text-emerald-700 dark:text-emerald-400';
                                    $icon = '<i class="fa-solid fa-comment-dots text-emerald-500 text-xs"></i>';
                                    $badge = 'KOMENTAR';
                                    $badgeClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300';
                                } elseif ($type === 'new_subtask') {
                                    $borderColor = 'border-purple-500';
                                    $bgColor = 'bg-purple-50/70 dark:bg-purple-950/20';
                                    $textColor = 'text-purple-700 dark:text-purple-400';
                                    $icon = '<i class="fa-solid fa-square-check text-purple-500 text-xs"></i>';
                                    $badge = 'SUB-TUGAS';
                                    $badgeClass = 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300';
                                } else {
                                    $isVeryNear = isset($notif['days_left']) ? ($notif['days_left'] <= 1) : (strpos($notif['message'] ?? '', 'hari ini') !== false || strpos($notif['message'] ?? '', '1 hari') !== false);
                                    $borderColor = $isVeryNear ? 'border-rose-500' : 'border-amber-500';
                                    $bgColor = $isVeryNear ? 'bg-rose-50/70 dark:bg-rose-950/20' : 'bg-amber-50/70 dark:bg-amber-950/20';
                                    $textColor = $isVeryNear ? 'text-rose-700 dark:text-rose-400' : 'text-amber-700 dark:text-amber-400';
                                    $icon = '<i class="fa-solid fa-clock ' . ($isVeryNear ? 'text-rose-500' : 'text-amber-500') . ' text-xs"></i>';
                                    $badge = 'DEADLINE';
                                    $badgeClass = $isVeryNear ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300';
                                }
                                $link = $notif['link'] ?? (BASEURL . '/task?project_id=' . ($notif['project_id'] ?? ''));
                            ?>
                                <a href="<?= $link ?>" class="block px-3.5 py-3 hover:bg-gray-100/80 dark:hover:bg-slate-700/60 transition border-l-[4px] <?= $borderColor ?> <?= $bgColor ?> rounded-2xl shadow-xs">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <?= $icon ?>
                                            <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded <?= $badgeClass ?>"><?= $badge ?></span>
                                        </div>
                                        <?php if (!empty($notif['time_ago'])) : ?>
                                            <span class="text-[10px] text-gray-400 font-bold"><?= $notif['time_ago'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs font-black <?= $textColor ?> mb-0.5 truncate"><?= htmlspecialchars($notif['title']) ?></p>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium line-clamp-2 leading-tight"><?= htmlspecialchars($notif['message'] ?? '') ?></p>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="px-5 py-3 border-t border-gray-100 dark:border-slate-700/80 mt-2">
                        <a href="<?= BASEURL ?>/task" class="text-xs font-black text-indigo-600 dark:text-indigo-400 hover:underline flex items-center justify-between">
                            <span>Buka Semua Task</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 relative custom-dropdown" id="profileDropdown">
                <button onclick="toggleDropdown('profileDropdown')" class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-slate-800 p-1.5 rounded-2xl transition">
                    <div class="text-right hidden sm:block overflow-hidden">
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-none truncate"><?= $_SESSION['user']['name']; ?></p>
                        <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 mt-1 capitalize transition-colors truncate"><?= $_SESSION['user']['role']; ?></p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&background=E2E8F0&color=475569" class="w-10 h-10 md:w-11 md:h-11 rounded-full border-2 border-indigo-50 dark:border-slate-800 transition-colors flex-shrink-0" alt="Avatar">
                </button>
                
                <div class="dropdown-menu absolute top-full right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-xl py-2 hidden z-50">
                    <a href="<?= BASEURL; ?>/settings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        <i class="fa-solid fa-gear text-sm text-gray-400 w-4 text-center"></i>
                        <span>Pengaturan</span>
                    </a>
                    <?php if(isset($_SESSION['user']) && (int)$_SESSION['user']['id'] === 1) : ?>
                    <a href="<?= BASEURL; ?>/danger" class="flex items-center gap-3 px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition font-bold">
                        <i class="fa-solid fa-triangle-exclamation text-sm text-rose-500 w-4 text-center"></i>
                        <span>Danger Area</span>
                    </a>
                    <?php endif; ?>
                    <!-- Mobile Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="w-full flex sm:hidden items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        <div class="relative w-4 h-4 flex items-center justify-center">
                            <i class="fa-solid fa-moon text-sm text-gray-400 block dark:hidden"></i>
                            <i class="fa-solid fa-sun text-sm text-gray-400 hidden dark:block"></i>
                        </div>
                        <span class="flex-grow text-left">Mode <span class="dark:hidden">Gelap</span><span class="hidden dark:inline">Terang</span></span>
                    </button>
                    <hr class="border-gray-50 dark:border-slate-700 my-1">
                    <a href="<?= BASEURL; ?>/login/logout" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition font-bold">
                        <i class="fa-solid fa-right-from-bracket text-sm w-4 text-center"></i>
                        <span>Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Scroll Area -->
    <main class="flex-grow p-4 md:p-8 overflow-y-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 md:mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#111827] dark:text-white"><?= $data['judul_halaman'] ?? 'Dashboard'; ?></h1>
                <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 mt-1"><?= $data['sub_judul'] ?? 'Selamat datang kembali, ' . $_SESSION['user']['name']; ?></p>
            </div>
            <div id="header-action-area" class="w-full md:w-auto flex justify-end"></div>
        </div>
        <?php Flasher::flash(); ?>

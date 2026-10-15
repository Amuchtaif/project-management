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
                <button onclick="toggleDropdown('notifDropdown')" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition relative" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <?php if (!empty($data['notifications'])) : ?>
                        <span id="notifRedDot" class="absolute top-2 right-2 w-2 h-2 bg-indigo-600 dark:bg-indigo-400 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
                    <?php endif; ?>
                </button>
                
                <div class="dropdown-menu fixed sm:absolute inset-x-4 sm:inset-auto top-20 sm:top-full sm:right-0 sm:mt-2 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-black/40 py-0 hidden z-50 overflow-hidden w-auto sm:w-[380px]">
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">Notifikasi</h4>
                            <?php if (!empty($data['notifications'])) : ?>
                                <span id="notifBadgeCount" class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-200/80 dark:bg-slate-800 text-slate-700 dark:text-slate-300"><?= count($data['notifications']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($data['notifications'])) : ?>
                            <button type="button" id="btnClearAllNotif" onclick="clearAllNotifications(event)" class="text-xs font-medium text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 flex items-center gap-1.5 transition-colors px-2 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Tandai semua notifikasi telah dibaca">
                                <i class="fa-solid fa-check-double text-[11px]"></i>
                                <span>Tandai semua dibaca</span>
                            </button>
                        <?php endif; ?>
                    </div>
                    <div id="notifListContainer" class="max-h-[60vh] sm:max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/70">
                        <?php if (empty($data['notifications'])) : ?>
                            <div class="px-6 py-10 text-center" id="emptyNotifState">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-2.5">
                                    <i class="fa-regular fa-bell text-sm"></i>
                                </div>
                                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Tidak ada notifikasi baru</p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Semua notifikasi telah dibaca</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($data['notifications'] as $notif) : 
                                $type = $notif['type'] ?? 'deadline';
                                $notifId = (int)$notif['id'];
                                $link = $notif['link'] ?? (BASEURL . '/task?project_id=' . ($notif['project_id'] ?? ''));

                                if ($type === 'new_project') {
                                    $iconClass = 'fa-solid fa-folder-plus text-indigo-600 dark:text-indigo-400';
                                    $iconBg = 'bg-indigo-50 dark:bg-indigo-950/50';
                                    $categoryLabel = 'Proyek Baru';
                                } elseif ($type === 'new_task') {
                                    $iconClass = 'fa-solid fa-list-check text-sky-600 dark:text-sky-400';
                                    $iconBg = 'bg-sky-50 dark:bg-sky-950/50';
                                    $categoryLabel = 'Tugas Baru';
                                } elseif ($type === 'new_comment') {
                                    $iconClass = 'fa-solid fa-comment-dots text-emerald-600 dark:text-emerald-400';
                                    $iconBg = 'bg-emerald-50 dark:bg-emerald-950/50';
                                    $categoryLabel = 'Komentar';
                                } elseif ($type === 'new_subtask') {
                                    $iconClass = 'fa-solid fa-check-double text-purple-600 dark:text-purple-400';
                                    $iconBg = 'bg-purple-50 dark:bg-purple-950/50';
                                    $categoryLabel = 'Sub-tugas';
                                } else {
                                    $isVeryNear = isset($notif['days_left']) ? ($notif['days_left'] <= 1) : (strpos($notif['message'] ?? '', 'hari ini') !== false || strpos($notif['message'] ?? '', '1 hari') !== false);
                                    if ($isVeryNear) {
                                        $iconClass = 'fa-solid fa-clock text-rose-600 dark:text-rose-400';
                                        $iconBg = 'bg-rose-50 dark:bg-rose-950/50';
                                        $categoryLabel = 'Tenggat Waktu';
                                    } else {
                                        $iconClass = 'fa-solid fa-clock text-amber-600 dark:text-amber-400';
                                        $iconBg = 'bg-amber-50 dark:bg-amber-950/50';
                                        $categoryLabel = 'Tenggat Waktu';
                                    }
                                }
                            ?>
                                <div data-notif-id="<?= $notifId ?>" 
                                     onclick="handleNotificationClick(event, <?= $notifId ?>, '<?= htmlspecialchars($link, ENT_QUOTES) ?>')" 
                                     class="notif-item group relative flex items-start gap-3 p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors cursor-pointer select-none">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400 shrink-0 mt-2"></span>
                                    <div class="w-8 h-8 rounded-lg <?= $iconBg ?> flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="<?= $iconClass ?> text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 pr-1">
                                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500 mb-0.5 leading-none">
                                            <span class="font-medium text-slate-600 dark:text-slate-400"><?= $categoryLabel ?></span>
                                            <?php if (!empty($notif['time_ago'])) : ?>
                                                <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                                                <span><?= $notif['time_ago'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h5 class="text-xs font-semibold text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug line-clamp-1">
                                            <?= htmlspecialchars($notif['title']) ?>
                                        </h5>
                                        <?php if (!empty($notif['message'])) : ?>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-normal line-clamp-2 leading-relaxed mt-0.5">
                                                <?= htmlspecialchars($notif['message']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" 
                                            onclick="dismissSingleNotification(event, <?= $notifId ?>, this.closest('.notif-item'))" 
                                            class="btn-dismiss-single shrink-0 p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-all opacity-0 group-hover:opacity-100 max-sm:opacity-70 focus:opacity-100" 
                                            title="Tandai dibaca">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="p-2.5 bg-slate-50/60 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 text-center">
                        <a href="<?= BASEURL ?>/task" class="inline-flex items-center justify-center gap-1.5 text-xs font-medium text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 py-1 transition-colors">
                            <span>Buka semua tugas</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
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

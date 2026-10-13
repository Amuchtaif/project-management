<!-- Sidebar MOBILE OVERLAY -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="mainSidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-[#313491] dark:bg-slate-900 border-r border-[#313491] dark:border-slate-800 text-white flex-shrink-0 flex flex-col z-50 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shadow-xl">
    <div class="p-6">
        <!-- Logo Section -->
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-1.5">
                <div class="w-14 h-14 flex-shrink-0 flex items-center justify-center">
                    <img src="<?= BASEURL; ?>/img/logo.png" alt="Logo" class="w-full h-full object-contain brightness-0 invert">
                </div>
                <div class="flex flex-col">
                    <span class="text-[15px] font-bold text-white leading-tight tracking-tight">Manajemen Project</span>
                    <span class="text-[11px] font-semibold text-indigo-300 mt-0.5 capitalize"><?= $_SESSION['user']['role'] ?? 'Admin'; ?> Panel</span>
                </div>
            </div>
            <!-- MOBILE CLOSE BUTTON -->
            <button class="md:hidden p-2 text-white/50 hover:text-white transition" onclick="toggleSidebar()">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="mb-2">
            <span class="text-[10px] font-black text-indigo-300/50 uppercase tracking-[0.2em] ml-3">Menu Utama</span>
        </div>
        <nav class="space-y-1.5 transition-colors">
            <a href="<?= BASEURL; ?>/dashboard" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Dashboard') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Dashboard') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-house text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Dashboard</span>
            </a>
            
            <a href="<?= BASEURL; ?>/project" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Project') !== false || strpos($data['judul'], 'Proyek') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Project') !== false || strpos($data['judul'], 'Proyek') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-folder-open text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Project</span>
            </a>

            <a href="<?= BASEURL; ?>/task" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Tugas') !== false && strpos($data['judul'], 'Kalender') === false && strpos($data['judul'], 'Roadmap') === false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Tugas') !== false && strpos($data['judul'], 'Kalender') === false && strpos($data['judul'], 'Roadmap') === false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-list-check text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Papan Tugas</span>
            </a>

            <a href="<?= BASEURL; ?>/task/calendar" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Kalender') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Kalender') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-calendar-days text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Kalender Tugas</span>
            </a>

            <a href="<?= BASEURL; ?>/task/timeline" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Roadmap') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Roadmap') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-timeline text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Timeline</span>
            </a>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
            <a href="<?= BASEURL; ?>/team" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Tim') !== false && strpos($data['judul'], 'Timeline') === false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Tim') !== false && strpos($data['judul'], 'Timeline') === false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-users text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Tim</span>
            </a>
            <?php endif; ?>

            <a href="<?= BASEURL; ?>/activity" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Aktivitas') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Aktivitas') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <i class="fa-solid fa-clock-rotate-left text-base"></i>
                </div>
                <span class="text-sm font-bold tracking-wide">Log Aktivitas</span>
            </a>

            <?php if(isset($_SESSION['user']) && (int)$_SESSION['user']['id'] === 1) : ?>
            <div class="pt-3 mt-3 border-white/10 dark:border-slate-800">
                <span class="text-[10px] font-black text-rose-300/60 uppercase tracking-wider ml-3 mb-1.5 block">Sistem & Keamanan</span>
                <a href="<?= BASEURL; ?>/danger" class="group flex items-center justify-between gap-2 px-3.5 py-2.5 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Danger') !== false) ? 'bg-rose-600 text-white shadow-lg shadow-rose-900/30' : 'text-rose-200 hover:bg-rose-500/20 dark:hover:bg-rose-950/40' ?>">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Danger') !== false) ? 'text-white' : 'text-rose-400 group-hover:text-rose-300' ?>">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                        </div>
                        <span class="text-sm font-bold tracking-wide truncate">Danger Area</span>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Bottom User Section -->
    <div class="mt-auto p-4 md:p-6 transition-colors">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&background=4f46e5&color=fff" class="w-10 h-10 rounded-full object-cover border-2 border-white/10 flex-shrink-0" alt="Avatar">
                <div class="flex flex-col min-w-0 max-w-[120px]">
                    <p class="text-[13px] font-bold text-white truncate"><?= $_SESSION['user']['name']; ?></p>
                    <p class="text-[11px] font-medium text-indigo-300 dark:text-indigo-400 capitalize transition-colors truncate"><?= $_SESSION['user']['role']; ?></p>
                </div>
            </div>
            <a href="<?= BASEURL; ?>/login/logout" class="text-indigo-300 dark:text-slate-500 hover:text-red-400 transition flex-shrink-0">
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
            </a>
        </div>
    </div>
</aside>

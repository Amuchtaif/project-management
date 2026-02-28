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
                    <span class="text-[15px] font-bold text-white leading-tight tracking-tight">Manajemen Proyek</span>
                    <span class="text-[11px] font-semibold text-indigo-300 mt-0.5 capitalize"><?= $_SESSION['user']['role'] ?? 'Admin'; ?> Panel</span>
                </div>
            </div>
            <!-- MOBILE CLOSE BUTTON -->
            <button class="md:hidden p-2 text-white/50 hover:text-white transition" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="mb-2">
            <span class="text-[10px] font-black text-indigo-300/50 uppercase tracking-[0.2em] ml-3">Menu Utama</span>
        </div>
        <nav class="space-y-1.5 transition-colors">
            <a href="<?= BASEURL; ?>/dashboard" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Dashboard') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Dashboard') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">Dashboard</span>
            </a>
            
            <a href="<?= BASEURL; ?>/project" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Proyek') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Proyek') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">Proyek</span>
            </a>

            <a href="<?= BASEURL; ?>/task" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Tugas') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Tugas') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">Tugas</span>
            </a>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
            <a href="<?= BASEURL; ?>/team" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Tim') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Tim') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">Tim</span>
            </a>
            <?php endif; ?>

            <a href="<?= BASEURL; ?>/activity" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?= (strpos($data['judul'], 'Aktivitas') !== false) ? 'bg-white text-[#313491] dark:bg-slate-800 dark:text-indigo-400 shadow-lg shadow-black/10' : 'text-indigo-100 hover:bg-white/10 dark:hover:bg-slate-800/50' ?>">
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center transition-colors <?= (strpos($data['judul'], 'Aktivitas') !== false) ? 'text-[#313491] dark:text-indigo-400' : 'text-indigo-300 group-hover:text-white' ?>">
                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sm font-bold tracking-wide">Log Aktivitas</span>
            </a>
        </nav>
    </div>

    <!-- Bottom User Section -->
    <div class="mt-auto p-4 md:p-6 border-t border-white/5 dark:border-slate-800 transition-colors">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&background=4f46e5&color=fff" class="w-10 h-10 rounded-full object-cover border-2 border-white/10 flex-shrink-0" alt="Avatar">
                <div class="flex flex-col min-w-0 max-w-[120px]">
                    <p class="text-[13px] font-bold text-white truncate"><?= $_SESSION['user']['name']; ?></p>
                    <p class="text-[11px] font-medium text-indigo-300 dark:text-indigo-400 capitalize transition-colors truncate"><?= $_SESSION['user']['role']; ?></p>
                </div>
            </div>
            <a href="<?= BASEURL; ?>/login/logout" class="text-indigo-300 dark:text-slate-500 hover:text-red-400 transition flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </a>
        </div>
    </div>
</aside>

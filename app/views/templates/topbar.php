<!-- Main Content Area -->
<div class="flex-grow flex flex-col h-screen overflow-hidden">
    <!-- Top Header -->
    <header class="h-16 md:h-20 bg-white border-b border-gray-100 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
        <div class="flex items-center gap-4">
            <!-- MOBILE MENU TOGGLE -->
            <button class="md:hidden p-2 text-gray-400 hover:text-indigo-600 rounded-xl transition" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
            <div class="relative w-48 sm:w-64 md:w-96">
                <input type="text" placeholder="Cari..." class="w-full bg-gray-50 border-none rounded-xl md:rounded-2xl px-10 py-2.5 md:py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400 absolute left-3 md:left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-4">
            <!-- Notifications -->
            <div class="relative custom-dropdown" id="notifDropdown">
                <button onclick="toggleDropdown('notifDropdown')" class="p-2 text-gray-400 hover:text-indigo-600 rounded-xl hover:bg-gray-50 transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <?php if (!empty($data['notifications'])) : ?>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                    <?php endif; ?>
                </button>
                
                <div class="dropdown-menu absolute top-full right-0 mt-2 w-80 bg-white border border-gray-100 rounded-2xl shadow-xl py-3 hidden z-50">
                    <div class="px-4 py-2 border-b border-gray-50 mb-2">
                        <h4 class="text-sm font-black text-gray-900">Notifikasi Deadline</h4>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <?php if (empty($data['notifications'])) : ?>
                            <div class="px-4 py-8 text-center">
                                <p class="text-xs text-gray-400 font-medium italic">Tidak ada tugas mendekati deadline.</p>
                            </div>
                        <?php else : ?>
                            <?php foreach ($data['notifications'] as $notif) : 
                                $color = $notif['days_left'] == 1 ? 'border-red-500 bg-red-50 text-red-700' : 'border-orange-400 bg-orange-50 text-orange-700';
                            ?>
                                <a href="<?= BASEURL ?>/task?project_id=<?= $notif['project_id'] ?>" class="block px-4 py-3 hover:bg-gray-50 transition border-l-4 <?= $color ?> mb-1">
                                    <p class="text-xs font-black truncate"><?= $notif['title'] ?></p>
                                    <p class="text-[10px] opacity-70 mt-0.5">Deadline dalam <?= $notif['days_left'] ?> hari (<?= $notif['project_name'] ?>)</p>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-50 mt-2">
                        <a href="<?= BASEURL ?>/task" class="text-[11px] font-bold text-indigo-600 hover:underline">Lihat Semua Tugas</a>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 relative custom-dropdown" id="profileDropdown">
                <button onclick="toggleDropdown('profileDropdown')" class="flex items-center gap-3 hover:bg-gray-50 p-1.5 rounded-2xl transition">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 leading-none"><?= $_SESSION['user']['name']; ?></p>
                        <p class="text-[11px] font-semibold text-gray-400 mt-1 capitalize"><?= $_SESSION['user']['role']; ?></p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&background=E2E8F0&color=475569" class="w-10 h-10 md:w-11 md:h-11 rounded-full border-2 border-indigo-50" alt="Avatar">
                </button>
                
                <div class="dropdown-menu absolute top-full right-0 mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 hidden z-50">
                    <a href="<?= BASEURL; ?>/settings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Pengaturan</span>
                    </a>
                    <hr class="border-gray-50 my-1">
                    <a href="<?= BASEURL; ?>/login/logout" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6-10V7a3 3 0 00-6 0v1"></path></svg>
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
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#111827]"><?= $data['judul_halaman'] ?? 'Dashboard'; ?></h1>
                <p class="text-sm md:text-base text-gray-500 mt-1"><?= $data['sub_judul'] ?? 'Selamat datang kembali, ' . $_SESSION['user']['name']; ?></p>
            </div>
            <div id="header-action-area" class="w-full md:w-auto flex justify-end"></div>
        </div>
        <?php Flasher::flash(); ?>

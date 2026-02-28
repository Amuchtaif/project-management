<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
    <div class="flex flex-wrap gap-2 transition-colors">
        <a href="<?= BASEURL; ?>/activity" class="px-5 py-2 <?= !isset($_GET['user_id']) && !isset($_GET['date']) && !isset($_GET['type']) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 dark:shadow-none' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-100 dark:border-slate-700' ?> rounded-lg font-bold text-sm transition transition-colors">Semua</a>
        
        <!-- Date Filter -->
        <div class="relative custom-dropdown" id="dateDropdown">
            <button onclick="toggleDropdown('dateDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-4 py-2 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                <?= isset($_GET['date']) ? date('d M Y', strtotime($_GET['date'])) : 'Pilih Tanggal' ?>
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div class="dropdown-menu absolute top-full left-0 mt-2 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl p-4 hidden z-50 min-w-[200px] transition-colors">
                <div class="flex flex-col gap-3">
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">Pilih Tanggal</label>
                    <input type="date" id="filter-date" class="w-full text-sm border-gray-100 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="<?= $_GET['date'] ?? '' ?>" onchange="applyFilter('date', this.value)">
                    <?php if(isset($_GET['date'])) : ?>
                        <button onclick="applyFilter('date', '')" class="text-xs text-red-500 font-bold hover:underline">Hapus Filter</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Member Filter (Admin Only) -->
        <?php if($data['user_role'] === 'admin') : ?>
        <div class="relative custom-dropdown" id="userDropdown">
            <button onclick="toggleDropdown('userDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-4 py-2 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <?php 
                    $selectedUser = 'Semua Anggota';
                    if(isset($_GET['user_id'])) {
                        foreach($data['team'] as $m) {
                            if($m['id'] == $_GET['user_id']) $selectedUser = $m['name'];
                        }
                    }
                    echo $selectedUser;
                ?>
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div class="dropdown-menu absolute top-full left-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl py-2 hidden z-50 max-h-60 overflow-y-auto transition-colors">
                <button onclick="applyFilter('user_id', '')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors <?= !isset($_GET['user_id']) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/20' : '' ?>">Semua Anggota</button>
                <?php foreach($data['team'] as $m) : ?>
                    <button onclick="applyFilter('user_id', '<?= $m['id'] ?>')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors <?= (isset($_GET['user_id']) && $_GET['user_id'] == $m['id']) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/20' : '' ?>"><?= $m['name'] ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Activity Type Filter -->
        <div class="relative custom-dropdown" id="typeDropdown">
            <button onclick="toggleDropdown('typeDropdown')" class="flex items-center gap-2 bg-white dark:bg-slate-800 px-4 py-2 rounded-lg border border-gray-100 dark:border-slate-700 shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition transition-colors">
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <?php
                    $types = ['menambah' => 'Menambah', 'mengubah' => 'Mengubah', 'menghapus' => 'Menghapus'];
                    echo isset($_GET['type']) ? ($types[$_GET['type']] ?? 'Tipe Aktivitas') : 'Tipe Aktivitas';
                ?>
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl py-2 hidden z-50 transition-colors">
                <button onclick="applyFilter('type', '')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors <?= !isset($_GET['type']) ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/20' : '' ?>">Semua Tipe</button>
                <button onclick="applyFilter('type', 'menambah')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors <?= (isset($_GET['type']) && $_GET['type'] == 'menambah') ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/20' : '' ?>">Menambah</button>
                <button onclick="applyFilter('type', 'mengubah')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors <?= (isset($_GET['type']) && $_GET['type'] == 'mengubah') ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/20' : '' ?>">Mengubah</button>
                <button onclick="applyFilter('type', 'menghapus')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors <?= (isset($_GET['type']) && $_GET['type'] == 'menghapus') ? 'font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/20' : '' ?>">Menghapus</button>
            </div>
        </div>
    </div>

    <button class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold text-sm hover:text-indigo-800 dark:hover:text-indigo-300 transition transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Ekspor CSV
    </button>
</div>

<div class="space-y-12 pb-12">
    <?php if(empty($data['activities'])) : ?>
        <div class="bg-white dark:bg-slate-800 p-12 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-sm text-center transition-colors">
            <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 transition-colors">
                <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-[#111827] dark:text-gray-100 mb-2 tracking-tight transition-colors">Belum ada aktivitas</h3>
            <p class="text-gray-400 dark:text-gray-500 font-bold transition-colors">Semua aktivitas tim akan muncul di sini secara real-time.</p>
        </div>
    <?php endif; ?>

    <?php foreach($data['activities'] as $date => $logs) : ?>
    <div>
        <div class="flex items-center gap-4 mb-8 transition-colors">
            <h3 class="text-xs font-black text-[#9ca3af] dark:text-gray-500 uppercase tracking-[0.3em] whitespace-nowrap transition-colors"><?= $date; ?></h3>
            <div class="h-[1px] w-full bg-gray-100 dark:bg-slate-700 transition-colors"></div>
        </div>

        <div class="space-y-4">
            <?php foreach($logs as $log) : ?>
            <div class="group relative bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-sm hover:shadow-md transition transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-5">
                        <div class="relative flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($log['user']); ?>&background=random" class="w-14 h-14 rounded-full border-2 border-white dark:border-slate-800 shadow-sm transition-colors" alt="Avatar">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 <?= $log['icon_bg']; ?> rounded-full border-2 border-white dark:border-slate-800 flex items-center justify-center text-[11px] font-black shadow-sm transition-colors">
                                <?= $log['icon']; ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-[15px] text-[#4b5563] dark:text-gray-300 font-bold leading-relaxed mb-1 transition-colors">
                                <span class="text-[#111827] dark:text-gray-100 font-black transition-colors"><?= $log['user']; ?></span> 
                                <?= $log['action']; ?> 
                                <span class="text-[#4f46e5] dark:text-indigo-400 font-black underline decoration-indigo-100 dark:decoration-indigo-900/50 underline-offset-4 transition-colors"><?= $log['target']; ?></span>
                                <?php if($log['to']) : ?>
                                    ke <span class="px-2 py-0.5 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded text-[11px] font-black border border-green-100 dark:border-green-900/50 transition-colors"><?= strtoupper($log['to']); ?></span>
                                <?php endif; ?>
                            </p>
                            <p class="text-[13px] text-[#9ca3af] dark:text-gray-500 font-bold italic line-clamp-1 group-hover:line-clamp-none transition-all transition-colors"><?= $log['info']; ?></p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center gap-4 transition-colors">
                        <span class="text-[12px] font-black text-[#9ca3af] dark:text-gray-500 uppercase tracking-tighter bg-gray-50 dark:bg-slate-900 px-3 py-1.5 rounded-xl transition-colors"><?= $log['time']; ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

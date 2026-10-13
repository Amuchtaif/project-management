<div id="project-action-content" class="hidden">
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="<?= BASEURL; ?>/project/exportCsv" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black flex items-center gap-2 shadow-sm transition" title="Unduh CSV / Excel">
            <i class="fa-solid fa-file-excel text-sm"></i>
            <span>Ekspor CSV</span>
        </a>
        <a href="<?= BASEURL; ?>/project/reportPrint" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl text-xs font-black flex items-center gap-2 shadow-sm transition" title="Cetak Laporan Resmi">
            <i class="fa-solid fa-print text-sm"></i>
            <span>Cetak Laporan</span>
        </a>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
        <button class="bg-[#4f46e5] text-white px-5 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none text-xs btnTambahProyek">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Buat Project Baru</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden p-6 scale-95 md:scale-100 transition-colors">
    <!-- Filters & Flash (Keep existing structure) -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div class="flex flex-wrap items-center gap-4">
            <div class="relative custom-dropdown" id="projectStatusDropdown">
                <button onclick="toggleDropdown('projectStatusDropdown')" class="bg-gray-50 dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer tracking-tight flex items-center gap-2 transition-colors">
                    <?= isset($_GET['status']) ? $_GET['status'] : 'Semua Status' ?>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 dark:text-gray-500"></i>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl py-2 hidden z-50">
                    <button onclick="applyFilter('status', '')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">Semua Status</button>
                    <button onclick="applyFilter('status', 'Sesuai Jadwal')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">Sesuai Jadwal</button>
                    <button onclick="applyFilter('status', 'Terhambat')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">Terhambat</button>
                    <button onclick="applyFilter('status', 'Selesai')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">Selesai</button>
                </div>
            </div>

            <div class="relative">
                <input type="text" id="projectSearch" placeholder="Cari project..." value="<?= $_GET['search'] ?? '' ?>" 
                    class="bg-gray-50 dark:bg-slate-900 border border-gray-100 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none pr-10 transition-colors"
                    onkeypress="if(event.key === 'Enter') applyFilter('search', this.value)">
                <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500 absolute right-3.5 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
            </div>
            
            <?php if(isset($_GET['status']) || isset($_GET['search'])) : ?>
                <a href="<?= BASEURL ?>/project" class="text-xs font-bold text-red-500 hover:underline transition">Hapus Filter</a>
            <?php endif; ?>
        </div>
        
        <div class="flex bg-gray-50 dark:bg-slate-900 p-1.5 rounded-xl gap-1 transition-colors">
            <button onclick="applyFilter('status', 'Sesuai Jadwal')" class="px-3 md:px-5 py-1.5 rounded-lg text-[10px] md:text-sm font-bold transition <?= (isset($_GET['status']) && $_GET['status'] == 'Sesuai Jadwal') ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-slate-800 hover:shadow-sm' ?>">Sesuai Jadwal</button>
            <button onclick="applyFilter('status', 'Terhambat')" class="px-3 md:px-5 py-1.5 rounded-lg text-[10px] md:text-sm font-bold transition <?= (isset($_GET['status']) && $_GET['status'] == 'Terhambat') ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-slate-800 hover:shadow-sm' ?>">Terhambat</button>
            <button onclick="applyFilter('status', 'Selesai')" class="px-3 md:px-5 py-1.5 rounded-lg text-[10px] md:text-sm font-bold transition <?= (isset($_GET['status']) && $_GET['status'] == 'Selesai') ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-slate-800 hover:shadow-sm' ?>">Selesai</button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto -mx-6 px-6">
        <table class="w-full text-left min-w-[800px]">
            <thead>
                <tr class="text-[11px] font-extrabold text-[#9ca3af] dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-50 dark:border-slate-700 transition-colors">
                    <th class="pb-6 pl-2">Nama Project</th>
                    <th class="pb-6">Klien</th>
                    <th class="pb-6">Tenggat Waktu</th>
                    <th class="pb-6">Status</th>
                    <th class="pb-6">Progres</th>
                    <th class="pb-6">Tim</th>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
                    <th class="pb-6 text-right pr-4">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50/50 dark:divide-slate-700/50 transition-colors">
                <?php if(empty($data['projects'])) : ?>
                    <tr><td colspan="6" class="py-20 text-center text-gray-400 font-medium">Belum ada project ditambahkan.</td></tr>
                <?php endif; ?>
                <?php foreach($data['projects'] as $p) : ?>
                <tr class="project-row group hover:bg-gray-50/50 dark:hover:bg-slate-900/30 transition-colors">
                    <td class="py-6 pl-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 <?= $p['status_color']; ?> rounded-2xl flex items-center justify-center bg-opacity-10 shadow-sm border border-gray-100/50 dark:border-slate-700/50">
                                <?php if($p['icon'] == 'rocket') : ?>
                                    <i class="fa-solid fa-rocket text-xl"></i>
                                <?php elseif($p['icon'] == 'bank') : ?>
                                    <i class="fa-solid fa-building-columns text-xl"></i>
                                <?php elseif($p['icon'] == 'leaf') : ?>
                                    <i class="fa-solid fa-leaf text-xl"></i>
                                <?php else : ?>
                                    <i class="fa-solid fa-code text-xl"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-[#111827] dark:text-gray-100 text-[15px] tracking-tight transition-colors"><?= $p['name']; ?></h4>
                            </div>
                        </div>
                    </td>
                    <td class="py-6">
                        <span class="text-[#6b7280] dark:text-gray-400 font-bold text-[14px] transition-colors"><?= $p['client']; ?></span>
                    </td>
                    <td class="py-6 font-bold text-[14px] text-[#4b5563] dark:text-gray-300 transition-colors">
                        <?= date('d M Y', strtotime($p['due_date'])); ?>
                    </td>
                    <td class="py-6">
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black tracking-[0.1em] <?= $p['status_color']; ?> bg-opacity-20 flex w-fit items-center justify-center leading-none border border-current bg-white dark:bg-slate-800 transition-colors">
                            <?= strtoupper($p['status']); ?>
                        </span>
                    </td>
                    <td class="py-6">
                        <?php 
                            $progress = (int)round($p['avg_progress'] ?? 0);
                            $pColor = 'bg-red-500';
                            $textCol = 'text-red-500';
                            if ($progress >= 71) { $pColor = 'bg-green-500'; $textCol = 'text-green-500'; }
                            elseif ($progress >= 41) { $pColor = 'bg-yellow-400'; $textCol = 'text-yellow-400'; }
                        ?>
                        <div class="flex flex-col gap-1.5 w-28">
                            <div class="flex justify-between items-center text-[10px] font-black transition-colors">
                                <span class="<?= $textCol ?> uppercase tracking-wider"><?= $progress; ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden transition-colors">
                                <div class="<?= $pColor ?> h-full rounded-full transition-all duration-500" style="width: <?= $progress; ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-6">
                        <div class="flex flex-col gap-3">
                            <?php if(!empty($p['leader_name'])) : ?>
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['leader_name']); ?>&background=random" class="w-8 h-8 rounded-full border-2 border-white shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-gray-900 dark:text-gray-100 leading-tight transition-colors"><?= $p['leader_name']; ?></span>
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">Project Lead</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($p['member_names'])) : 
                                $members = explode(',', $p['member_names']);
                                $count = count($members);
                            ?>
                            <div class="flex items-center -space-x-3">
                                <?php foreach(array_slice($members, 0, 3) as $m) : ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(trim($m)); ?>&background=random" 
                                         class="w-7 h-7 rounded-full border-2 border-white dark:border-slate-800 shadow-sm" 
                                         title="<?= trim($m); ?>">
                                <?php endforeach; ?>
                                <?php if($count > 3) : ?>
                                    <div class="w-7 h-7 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-black text-indigo-600 dark:text-indigo-400 shadow-sm transition-colors">
                                        +<?= $count - 3; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php elseif(empty($p['leader_name'])) : ?>
                                <span class="text-[11px] font-bold text-gray-400 italic">Belum ada tim</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
                    <td class="py-6 text-right pr-4">
                        <div class="flex items-center justify-end gap-5">
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900 btnEditProyek" data-id="<?= $p['id']; ?>">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button class="p-2 text-[#9ca3af] dark:text-gray-500 hover:text-red-500 transition rounded-lg hover:bg-gray-50 dark:hover:bg-slate-900" onclick="showDeleteModal('<?= BASEURL; ?>/project/delete/<?= $p['id']; ?>', '<?= addslashes($p['name']); ?>')">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-gray-100 dark:border-slate-700/80 transition-colors" id="projectPaginationWrapper">
        <div class="flex flex-wrap items-center gap-3 text-[13px] text-gray-500 dark:text-gray-400 font-medium">
            <span id="projectPaginationInfo">Menampilkan <?= count($data['projects']); ?> project</span>
            <div class="flex items-center gap-2 pl-3 border-l border-gray-200 dark:border-slate-700">
                <span class="text-xs text-gray-400 dark:text-gray-500 font-bold">Baris per halaman:</span>
                <select id="projectPageSize" class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-2.5 py-1 text-xs font-bold text-gray-700 dark:text-gray-300 outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="all">Semua</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-1.5" id="projectPaginationControls">
            <!-- Dynamically populated buttons -->
        </div>
    </div>
</div>

<!-- Modal Form Project -->
<div id="projectModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleProjectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors">
            <form action="<?= BASEURL; ?>/project/add" method="post" id="projectForm">
                <input type="hidden" name="id" id="project_id">
                <div class="p-8 md:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 tracking-tight transition-colors" id="modalTitle">Tambah Project</h3>
                            <p class="text-gray-400 dark:text-gray-500 text-sm font-bold mt-1 transition-colors">Lengkapi informasi detail project di bawah ini.</p>
                        </div>
                        <button type="button" onclick="toggleProjectModal()" class="text-gray-300 dark:text-gray-600 hover:text-gray-500 dark:hover:text-gray-400 transition">
                            <i class="fa-solid fa-xmark text-2xl"></i>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Nama Project</label>
                            <input type="text" name="name" id="name" required class="w-full bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" placeholder="masukkan nama project">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Klien</label>
                                <input type="text" name="client" id="client" required class="w-full bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" placeholder="masukan klien">
                            </div>
                            <div>
                                <label for="due_date" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Tenggat Waktu</label>
                                <input type="date" name="due_date" id="due_date" required class="w-full bg-white dark:bg-slate-900 border-[1.5px] border-gray-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer" onclick="this.showPicker()">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Status Utama</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Status...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="Sesuai Jadwal">Sesuai Jadwal</div>
                                        <div class="select-option" data-value="Terhambat">Terhambat</div>
                                        <div class="select-option" data-value="Selesai">Selesai</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status" value="Sesuai Jadwal">
                            </div>
                            <div>
                                <label for="icon" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Ikon Representasi</label>
                                <div class="select-container" id="iconSelectContainer">
                                    <div class="select-trigger">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Ikon...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="rocket">🚀 Roket</div>
                                        <div class="select-option" data-value="bank">🏦 Finansial</div>
                                        <div class="select-option" data-value="leaf">🌿 Lingkungan</div>
                                        <div class="select-option" data-value="code">💻 Teknologi</div>
                                    </div>
                                </div>
                                <input type="hidden" name="icon" id="icon" value="rocket">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Project Leader (Single Select with Search) -->
                            <div>
                                <label for="leader_id" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">Project Leader</label>
                                <div class="select-container" id="leaderSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Leader...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Leader...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box Header -->
                                        <div class="p-2.5 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl pl-8 pr-3 py-2 text-xs font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Cari nama leader...">
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['team'] as $m) : ?>
                                                <div class="select-option flex items-center justify-between gap-3" data-value="<?= $m['id']; ?>" data-name="<?= htmlspecialchars($m['name'], ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($m['name']); ?>&background=random" class="w-6 h-6 rounded-full border border-gray-200 dark:border-slate-700 flex-shrink-0" alt="Avatar">
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($m['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate"><?= htmlspecialchars($m['role_label'] ?? $m['role'] ?? ''); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="select-no-results py-4 text-center text-xs text-gray-400 font-medium" style="display: none;">Tidak ada leader yang cocok</div>
                                    </div>
                                </div>
                                <input type="hidden" name="leader_id" id="leader_id">
                            </div>

                            <!-- Anggota Tim Lainnya (Multi-Select with Search) -->
                            <div>
                                <div class="flex items-center justify-between mb-2 ml-1">
                                    <label for="member_ids" class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest transition-colors">Anggota Tim Lainnya</label>
                                    <span class="select-count-badge text-[10px] font-bold text-indigo-600 dark:text-indigo-400" style="display: none;">0 dipilih</span>
                                </div>
                                <div class="select-container" id="memberSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Anggota...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Anggota...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon flex items-center justify-center text-xs"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box & Quick Actions Header -->
                                        <div class="p-2.5 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20 space-y-2">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl pl-8 pr-3 py-2 text-xs font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Cari nama anggota...">
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] px-1 font-bold">
                                                <button type="button" class="select-all-btn text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                                    <i class="fa-solid fa-check-double text-[10px]"></i> Pilih Semua
                                                </button>
                                                <button type="button" class="select-clear-btn text-gray-400 hover:text-rose-500 hover:underline flex items-center gap-1">
                                                    <i class="fa-solid fa-rotate-left text-[10px]"></i> Hapus Pilihan
                                                </button>
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['team'] as $m) : ?>
                                                <div class="select-option flex justify-between items-center gap-3" data-value="<?= $m['id']; ?>" data-name="<?= htmlspecialchars($m['name'], ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($m['name']); ?>&background=random" class="w-6 h-6 rounded-full border border-gray-200 dark:border-slate-700 flex-shrink-0" alt="Avatar">
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($m['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate"><?= htmlspecialchars($m['role_label'] ?? $m['role'] ?? ''); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="check-icon opacity-0 bg-indigo-600 text-white rounded-md p-0.5 flex-shrink-0 transition-opacity">
                                                        <i class="fa-solid fa-check text-[10px] w-3 h-3 flex items-center justify-center"></i>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="select-no-results py-4 text-center text-xs text-gray-400 font-medium" style="display: none;">Tidak ada anggota yang cocok</div>
                                    </div>
                                </div>
                                <input type="hidden" name="member_ids" id="member_ids">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Warna Aksen</label>
                            <div class="flex gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-indigo-600" class="hidden peer" checked>
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 peer-checked:ring-4 ring-indigo-100 ring-offset-2 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-purple-600" class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl bg-purple-600 peer-checked:ring-4 ring-purple-100 ring-offset-2 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-emerald-600" class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 peer-checked:ring-4 ring-emerald-100 ring-offset-2 transition"></div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_color" value="text-amber-600" class="hidden peer">
                                    <div class="w-10 h-10 rounded-xl bg-amber-600 peer-checked:ring-4 ring-amber-100 ring-offset-2 transition"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="toggleProjectModal()" class="flex-1 px-8 py-4 rounded-2xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 font-black text-sm uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-slate-600 transition transition-colors">Batal</button>
                        <button type="submit" class="flex-1 px-8 py-4 rounded-2xl bg-indigo-600 text-white font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none">Simpan Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const projectModal = document.getElementById('projectModal');
    const projectForm = document.getElementById('projectForm');
    const modalTitle = document.getElementById('modalTitle');

    function toggleProjectModal() {
        if (projectModal.classList.contains('hidden')) {
            projectModal.classList.remove('hidden');
            setTimeout(() => {
                projectModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            projectModal.classList.remove('modal-active');
            setTimeout(() => {
                projectModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                projectForm.reset();
                document.getElementById('project_id').value = '';
            }, 300);
        }
    }

    // Modal behavior handled via jQuery if available, or native JS
    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action buttons to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('project-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }

        function syncMemberExclusion() {
            const leaderId = document.getElementById('leader_id').value;
            const memberIdsInput = document.getElementById('member_ids');
            const memberContainer = document.getElementById('memberSelectContainer');
            if (!memberContainer) return;
            
            const memberOptions = memberContainer.querySelectorAll('.select-option');
            
            // Hide leader from member options
            memberOptions.forEach(opt => {
                const val = opt.getAttribute('data-value');
                if (val && val === leaderId) {
                    opt.classList.add('excluded-member', 'hidden');
                    opt.style.display = 'none';
                    // If this leader was also in members, unselect it
                    if (opt.classList.contains('selected')) {
                        opt.classList.remove('selected');
                        // Update hidden input
                        try {
                            let members = JSON.parse(memberIdsInput.value || '[]');
                            members = members.filter(id => id !== leaderId);
                            memberIdsInput.value = JSON.stringify(members);
                            // Refresh multi-select UI
                            initMultiSelect('memberSelectContainer', 'member_ids');
                        } catch(e) {}
                    }
                } else {
                    opt.classList.remove('excluded-member', 'hidden');
                    opt.style.display = '';
                }
            });
        }

        // Initialize Custom Selects
        initCustomSelect('statusSelectContainer', 'status');
        initCustomSelect('iconSelectContainer', 'icon');
        initCustomSelect('leaderSelectContainer', 'leader_id', () => {
            syncMemberExclusion();
        });
        initMultiSelect('memberSelectContainer', 'member_ids', () => {
            // Optional: if we want to ensure leader isn't picked in multi-select 
            // but we already hide it in syncMemberExclusion
        });

        const btnsTambah = document.querySelectorAll('.btnTambahProyek');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitle.innerText = 'Tambah Project Baru';
                projectForm.setAttribute('action', '<?= BASEURL; ?>/project/add');
                
                document.getElementById('status').value = 'Sesuai Jadwal';
                document.getElementById('icon').value = 'rocket';
                document.getElementById('leader_id').value = '';
                
                initCustomSelect('statusSelectContainer', 'status');
                initCustomSelect('iconSelectContainer', 'icon');
                initCustomSelect('leaderSelectContainer', 'leader_id', () => syncMemberExclusion());
                initMultiSelect('memberSelectContainer', 'member_ids');
                syncMemberExclusion();

                toggleProjectModal();
            });
        });

        const btnsEdit = document.querySelectorAll('.btnEditProyek');
        btnsEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                modalTitle.innerText = 'Ubah Detail Project';
                projectForm.setAttribute('action', '<?= BASEURL; ?>/project/edit');
                
                fetch('<?= BASEURL; ?>/project/getEdit/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('project_id').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('client').value = data.client;
                        document.getElementById('due_date').value = data.due_date;
                        
                        document.getElementById('status').value = data.status;
                        document.getElementById('icon').value = data.icon;
                        document.getElementById('leader_id').value = data.leader_id || '';
                        
                        initCustomSelect('statusSelectContainer', 'status');
                        initCustomSelect('iconSelectContainer', 'icon');
                        initCustomSelect('leaderSelectContainer', 'leader_id', () => syncMemberExclusion());

                        document.getElementById('member_ids').value = JSON.stringify(data.member_ids || []);
                        initMultiSelect('memberSelectContainer', 'member_ids');
                        syncMemberExclusion();
                        
                        const radios = document.querySelectorAll('input[name="status_color"]');
                        radios.forEach(r => {
                            if (r.value === data.status_color) r.checked = true;
                        });

                        toggleProjectModal();
                    });
            });
        });

        // Initialize Project Table Pagination
        initProjectPagination();
    });

    function initProjectPagination() {
        const rows = Array.from(document.querySelectorAll('.project-row'));
        const pageSizeSelect = document.getElementById('projectPageSize');
        const infoEl = document.getElementById('projectPaginationInfo');
        const controlsEl = document.getElementById('projectPaginationControls');
        
        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect.value) || 10;
        const total = rows.length;

        function render() {
            const effectivePageSize = (pageSizeSelect.value === 'all') ? (total || 1) : pageSize;
            const totalPages = Math.max(1, Math.ceil(total / effectivePageSize));

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * effectivePageSize;
            const endIndex = (pageSizeSelect.value === 'all') ? total : Math.min(startIndex + effectivePageSize, total);

            rows.forEach((r, idx) => {
                if (pageSizeSelect.value === 'all' || (idx >= startIndex && idx < endIndex)) {
                    r.classList.remove('hidden');
                } else {
                    r.classList.add('hidden');
                }
            });

            if (total === 0) {
                infoEl.innerText = 'Menampilkan 0 project';
            } else if (pageSizeSelect.value === 'all' || total <= effectivePageSize) {
                infoEl.innerText = `Menampilkan ${total} project`;
            } else {
                infoEl.innerText = `Menampilkan ${startIndex + 1} - ${endIndex} dari ${total} project`;
            }

            controlsEl.innerHTML = '';
            if (totalPages <= 1 && total <= effectivePageSize) {
                return;
            }

            // Prev Button
            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = `px-3 py-1.5 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 ${currentPage === 1 ? 'border-gray-100 dark:border-slate-800 text-gray-300 dark:text-gray-600 cursor-not-allowed opacity-60' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-2xs'}`;
            prevBtn.disabled = (currentPage === 1);
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left text-[10px]"></i> <span class="hidden sm:inline">Sebelumnya</span>';
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; render(); } };
            controlsEl.appendChild(prevBtn);

            // Page numbers
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 6 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
                    if (p === 2 || p === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.className = 'px-1 text-xs text-gray-400 font-bold';
                        dots.innerText = '...';
                        controlsEl.appendChild(dots);
                    }
                    continue;
                }

                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                pageBtn.className = `min-w-[32px] h-8 px-2 rounded-xl text-xs font-black transition ${p === currentPage ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200 dark:shadow-none' : 'border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'}`;
                pageBtn.innerText = p;
                pageBtn.onclick = () => { currentPage = p; render(); };
                controlsEl.appendChild(pageBtn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = `px-3 py-1.5 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 ${currentPage === totalPages ? 'border-gray-100 dark:border-slate-800 text-gray-300 dark:text-gray-600 cursor-not-allowed opacity-60' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-2xs'}`;
            nextBtn.disabled = (currentPage === totalPages);
            nextBtn.innerHTML = '<span class="hidden sm:inline">Selanjutnya</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>';
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; render(); } };
            controlsEl.appendChild(nextBtn);
        }

        pageSizeSelect.addEventListener('change', function() {
            pageSize = (this.value === 'all') ? total : parseInt(this.value);
            currentPage = 1;
            render();
        });

        render();
    }
</script>

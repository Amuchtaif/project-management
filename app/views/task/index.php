<div id="task-action-content" class="hidden">
    <div class="flex items-center gap-2.5">
        <!-- Ekspor CSV -->
        <a href="<?= BASEURL ?>/task/exportCsv<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 transition shadow-sm text-xs" title="Ekspor daftar tugas ke CSV">
            <i class="fa-solid fa-file-excel text-sm"></i>
            <span>Ekspor CSV</span>
        </a>

        <!-- Tambah Task -->
        <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 dark:shadow-none btnTambahTugas text-xs">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Task</span>
        </button>
    </div>
</div>

<!-- Toolbar Container: View Switcher, Filter Project, Cari Tugas, Reset, Filter Pills -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200/90 dark:border-slate-700/80 shadow-xs p-4 md:p-5 mb-6 space-y-4">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <!-- View Switcher -->
            <div class="flex items-center bg-gray-100 dark:bg-slate-900/80 p-1 rounded-xl border border-gray-200 dark:border-slate-700">
                <a href="<?= BASEURL ?>/task<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-xs" title="Tampilan Papan Kanban">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>Papan</span>
                </a>
                <a href="<?= BASEURL ?>/task/calendar<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Kalender">
                    <i class="fa-solid fa-calendar-days text-xs"></i>
                    <span>Kalender</span>
                </a>
                <a href="<?= BASEURL ?>/task/timeline<?= isset($_GET['project_id']) ? '?project_id='.$_GET['project_id'] : '' ?>" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition" title="Tampilan Roadmap & Timeline">
                    <i class="fa-solid fa-timeline text-xs"></i>
                    <span>Roadmap</span>
                </a>
            </div>

            <!-- Project Filter -->
            <div class="relative custom-dropdown" id="taskProjectDropdown">
                <button onclick="toggleDropdown('taskProjectDropdown')" class="flex items-center gap-2 bg-gray-50 dark:bg-slate-900 px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-tight">Project:</span>
                    <span class="text-xs font-bold text-gray-900 dark:text-gray-100">
                        <?php 
                            $selectedProj = 'Pilih Project';
                            $selectedLead = '';
                            if(isset($_GET['project_id'])) {
                                foreach($data['projects'] as $p) {
                                    if($p['id'] == $_GET['project_id']) {
                                        $selectedProj = $p['name'];
                                        $selectedLead = $p['leader_name'] ?? '';
                                    }
                                }
                            } elseif (count($data['projects']) == 1) {
                                $selectedProj = $data['projects'][0]['name'];
                                $selectedLead = $data['projects'][0]['leader_name'] ?? '';
                            }
                            echo htmlspecialchars($selectedProj);
                        ?>
                    </span>
                    <?php if(!empty($selectedLead)) : ?>
                        <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-semibold bg-indigo-50 dark:bg-indigo-950/60 px-2 py-0.5 rounded-md hidden xl:inline-block">Lead: <?= htmlspecialchars($selectedLead) ?></span>
                    <?php endif; ?>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1"></i>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-72 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl py-2 hidden z-[100] max-h-72 overflow-y-auto">
                    <!-- Search Box in Filter -->
                    <div class="p-2 border-b border-gray-100 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-800 z-10">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <input type="text" id="filterProjectSearchInput" placeholder="Cari project atau lead..." oninput="filterTaskProjects(this.value)" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs font-medium text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>
                    <div id="filterProjectItemsList" class="py-1">
                        <?php foreach($data['projects'] as $p) : ?>
                            <button onclick="applyFilter('project_id', '<?= $p['id'] ?>')" 
                                    data-search="<?= htmlspecialchars(strtolower($p['name'] . ' ' . ($p['leader_name'] ?? '')), ENT_QUOTES) ?>"
                                    class="filter-project-item w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center justify-between gap-3 <?= (isset($_GET['project_id']) && $_GET['project_id'] == $p['id']) ? 'bg-indigo-50/70 dark:bg-slate-700/80 border-l-4 border-indigo-600' : '' ?>">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate"><?= htmlspecialchars($p['name']) ?></p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 flex items-center gap-1 mt-0.5 truncate">
                                        <i class="fa-solid fa-user-tie text-[10px] text-indigo-500"></i>
                                        <span>Lead: <strong class="text-gray-600 dark:text-gray-300 font-semibold"><?= htmlspecialchars($p['leader_name'] ?? 'Belum ditentukan') ?></strong></span>
                                    </p>
                                </div>
                                <?php if(isset($_GET['project_id']) && $_GET['project_id'] == $p['id']) : ?>
                                    <i class="fa-solid fa-check text-xs text-indigo-600 flex-shrink-0"></i>
                                <?php endif; ?>
                            </button>
                        <?php endforeach; ?>
                        <div id="filterProjectEmptyState" class="hidden px-4 py-6 text-center text-xs text-gray-400 font-medium italic">
                            Project tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Task -->
            <div class="relative flex-1 sm:flex-none">
                <input type="text" id="taskSearch" placeholder="Cari tugas..." value="<?= $_GET['search'] ?? '' ?>" 
                    class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-200 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 outline-none pr-10 w-full sm:w-60 transition"
                    onkeypress="if(event.key === 'Enter') applyFilter('search', this.value)">
                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                </div>
            </div>
            
            <?php if(isset($_GET['project_id']) || isset($_GET['search']) || isset($_GET['filter_type'])) : ?>
                <a href="<?= BASEURL ?>/task" class="flex items-center gap-1.5 bg-red-50 dark:bg-red-950/30 px-3 py-2 rounded-xl border border-red-200 dark:border-red-900/50 hover:bg-red-100 dark:hover:bg-red-900/50 transition text-xs font-bold text-red-600 dark:text-red-400">
                    <i class="fa-solid fa-xmark text-xs"></i>
                    <span>Reset</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter Pills Bar (Semua, Terlambat, Prioritas Tinggi, Tugas Saya) -->
    <div class="flex items-center gap-2 overflow-x-auto pt-1 pb-1 scrollbar-none border-t border-gray-100 dark:border-slate-700/60">
        <button onclick="applyFilter('filter_type', '')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 <?= empty($data['active_filter_type']) ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-600' ?>">
            <i class="fa-solid fa-layer-group text-[11px]"></i>
            <span>Semua Tugas</span>
        </button>

        <button onclick="applyFilter('filter_type', 'overdue')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2 flex-shrink-0 <?= ($data['active_filter_type'] === 'overdue') ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 hover:bg-rose-100' ?>">
            <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
            <span>Terlambat</span>
            <?php if (!empty($data['overdue_count']) && $data['overdue_count'] > 0) : ?>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold <?= ($data['active_filter_type'] === 'overdue') ? 'bg-white text-rose-700' : 'bg-rose-500 text-white' ?>">
                    <?= $data['overdue_count'] ?>
                </span>
            <?php endif; ?>
        </button>

        <button onclick="applyFilter('filter_type', 'urgent')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 <?= ($data['active_filter_type'] === 'urgent') ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900/60 hover:bg-amber-100' ?>">
            <i class="fa-solid fa-fire text-[11px]"></i>
            <span>Prioritas Tinggi</span>
        </button>

        <button onclick="applyFilter('filter_type', 'my_tasks')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0 <?= ($data['active_filter_type'] === 'my_tasks') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/60 hover:bg-indigo-100' ?>">
            <i class="fa-solid fa-user-check text-[11px]"></i>
            <span>Tugas Saya</span>
        </button>
    </div>
</div>

<?php if ($data['is_empty_state']) : ?>
    <div class="flex flex-col items-center justify-center min-h-[400px] bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-200 dark:border-slate-700 p-12 text-center">
        <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-5 text-indigo-600 dark:text-indigo-400">
            <i class="fa-solid fa-folder-open text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1.5">Pilih Project Terlebih Dahulu</h3>
        <p class="text-gray-400 dark:text-gray-500 font-medium text-xs max-w-sm">Silakan pilih project di bagian atas untuk mulai melihat dan mengelola tugas tim secara real-time.</p>
    </div>
<?php else : ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


    <!-- TO DO COLUMN -->
    <div class="kanban-column flex flex-col" data-status="todo">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">To Do</h3>
                <span id="kanban-count-todo" class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($data['tasks']['todo']); ?></span>
            </div>
            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition text-xs">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="space-y-3.5 kanban-dropzone flex-1 min-h-[220px] p-1.5 -m-1.5 rounded-2xl transition-all duration-200" data-status="todo">
            <?php 
            foreach($data['tasks']['todo'] as $task) : 
                $date = strtotime($task['due_date']);
                $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $indoDate = date('d', $date) . ' ' . $months[(int)date('m', $date)] . ' ' . date('Y', $date);
                
                // Datediff Logic
                $now = new DateTime();
                $due = new DateTime($task['due_date']);
                $diff = $now->diff($due);
                $daysLeft = $diff->days * ($diff->invert ? -1 : 1);
                
                // Priority Logic
                $priorityLabel = ucfirst($task['priority'] ?? 'biasa');
                $priorityClass = "bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800";
                if (($task['priority'] ?? '') == 'tinggi') $priorityClass = "bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800";
                elseif (($task['priority'] ?? '') == 'sedang') $priorityClass = "bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 border-orange-100 dark:border-orange-800";

                $projectSuffix = isset($_GET['project_id']) && !empty($_GET['project_id']) ? '?project_id=' . $_GET['project_id'] : '';
            ?>
            <div class="kanban-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-200/90 dark:border-slate-700/80 shadow-xs hover:shadow-md hover:border-gray-300 dark:hover:border-slate-600 transition group relative overflow-hidden cursor-grab active:cursor-grabbing select-none"
                 draggable="true"
                 data-id="<?= $task['id']; ?>"
                 data-status="todo"
                 data-tag="<?= htmlspecialchars($task['tag'], ENT_QUOTES); ?>"
                 data-tag-color="<?= $task['tag_color']; ?>"
                 data-priority="<?= $task['priority'] ?? 'biasa'; ?>"
                 data-priority-class="<?= $priorityClass; ?>"
                 data-priority-label="<?= $priorityLabel; ?>"
                 data-title="<?= htmlspecialchars($task['title'], ENT_QUOTES); ?>"
                 data-project-name="<?= htmlspecialchars($task['project_name'], ENT_QUOTES); ?>"
                 data-assignee-name="<?= htmlspecialchars($task['assignee_name'], ENT_QUOTES); ?>"
                 data-due-date="<?= $task['due_date']; ?>"
                 data-indo-date="<?= $indoDate; ?>"
                 data-progress="<?= $task['progress']; ?>">
                
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <i class="fa-solid fa-grip-vertical text-gray-300 dark:text-gray-600 group-hover:text-indigo-400 text-xs mr-0.5 cursor-grab drag-handle transition-colors" title="Tarik untuk memindahkan"></i>
                        <span class="task-tag-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold <?= $task['tag_color']; ?> border border-current bg-white/50 dark:bg-slate-700/50 uppercase">
                            <?= $task['tag']; ?>
                        </span>
                        <span class="task-priority-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold <?= $priorityClass ?> border uppercase">
                            <?= $priorityLabel ?>
                        </span>
                        <?php if ($daysLeft < 0) : ?>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Terlambat
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <h4 class="task-title font-bold text-gray-900 dark:text-gray-100 mb-1 leading-tight transition-colors cursor-pointer hover:text-indigo-600" onclick="openTaskDetailModal(<?= $task['id']; ?>)"><?= $task['title']; ?></h4>
                <p class="task-project text-[12px] text-gray-400 dark:text-gray-500 mb-5 transition-colors"><?= $task['project_name']; ?></p>
                
                <div class="task-card-footer flex items-center justify-between border-t border-gray-100 dark:border-slate-700/80 pt-3">
                    <div class="task-date-area flex items-center gap-2 text-gray-400 dark:text-gray-500 text-[12px]">
                        <i class="fa-solid fa-calendar text-xs"></i>
                        <span class="font-medium"><?= $indoDate; ?></span>
                        <?php if ($daysLeft < 0) : ?>
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/60 px-1.5 py-0.5 rounded border border-rose-200 dark:border-rose-900/60 flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[9px]"></i> Terlambat <?= abs($daysLeft) ?>h
                            </span>
                        <?php elseif ($daysLeft == 0) : ?>
                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-950/40 px-1.5 py-0.5 rounded border border-amber-200 dark:border-amber-900/40">Hari ini</span>
                        <?php elseif ($daysLeft <= 3) : ?>
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-950/40 px-1.5 py-0.5 rounded border border-indigo-200 dark:border-indigo-900/40">H-<?= $daysLeft ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <button type="button" class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg transition" title="Lihat Sub-tugas & Komentar" onclick="event.stopPropagation(); openTaskDetailModal(<?= $task['id']; ?>)">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </button>
                            <button type="button" class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg transition btnEditTugas" title="Edit" data-id="<?= $task['id']; ?>">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button type="button" class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition" title="Delete" onclick="showDeleteModal('<?= BASEURL; ?>/task/delete/<?= $task['id'] . $projectSuffix; ?>', '<?= addslashes($task['title']); ?>')">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']); ?>&background=random" class="w-7 h-7 rounded-full border border-white dark:border-slate-700 shadow-xs" alt="Avatar">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button class="w-full py-3 border border-dashed border-gray-200 dark:border-slate-700 rounded-xl text-gray-500 dark:text-gray-400 text-xs font-bold flex items-center justify-center gap-2 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:bg-slate-700/50 transition mt-3 btnTambahTugas" data-status="todo">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Tugas</span>
        </button>
    </div>

    <!-- IN PROGRESS COLUMN -->
    <div class="kanban-column flex flex-col" data-status="in_progress">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">In Progress</h3>
                <span id="kanban-count-in_progress" class="bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($data['tasks']['in_progress']); ?></span>
            </div>
            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition text-xs">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="space-y-3.5 kanban-dropzone flex-1 min-h-[220px] p-1.5 -m-1.5 rounded-2xl transition-all duration-200" data-status="in_progress">
            <?php 
            foreach($data['tasks']['in_progress'] as $task) : 
                $date = strtotime($task['due_date']);
                $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $indoDate = date('d', $date) . ' ' . $months[(int)date('m', $date)] . ' ' . date('Y', $date);
                
                // Datediff Logic
                $now = new DateTime();
                $due = new DateTime($task['due_date']);
                $diff = $now->diff($due);
                $daysLeft = $diff->days * ($diff->invert ? -1 : 1);
                
                // Priority Logic
                $priorityLabel = ucfirst($task['priority'] ?? 'biasa');
                $priorityClass = "bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800";
                if (($task['priority'] ?? '') == 'tinggi') $priorityClass = "bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800";
                elseif (($task['priority'] ?? '') == 'sedang') $priorityClass = "bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 border-orange-100 dark:border-orange-800";

                $projectSuffix = isset($_GET['project_id']) && !empty($_GET['project_id']) ? '?project_id=' . $_GET['project_id'] : '';
            ?>
            <div class="kanban-card bg-[#FEF9C3] dark:bg-amber-950/40 p-5 rounded-2xl border border-[#FDE047] dark:border-amber-700/60 shadow-xs hover:shadow-md transition group relative overflow-hidden cursor-grab active:cursor-grabbing select-none"
                 draggable="true"
                 data-id="<?= $task['id']; ?>"
                 data-status="in_progress"
                 data-tag="<?= htmlspecialchars($task['tag'], ENT_QUOTES); ?>"
                 data-tag-color="<?= $task['tag_color']; ?>"
                 data-priority="<?= $task['priority'] ?? 'biasa'; ?>"
                 data-priority-class="<?= $priorityClass; ?>"
                 data-priority-label="<?= $priorityLabel; ?>"
                 data-title="<?= htmlspecialchars($task['title'], ENT_QUOTES); ?>"
                 data-project-name="<?= htmlspecialchars($task['project_name'], ENT_QUOTES); ?>"
                 data-assignee-name="<?= htmlspecialchars($task['assignee_name'], ENT_QUOTES); ?>"
                 data-due-date="<?= $task['due_date']; ?>"
                 data-indo-date="<?= $indoDate; ?>"
                 data-progress="<?= $task['progress']; ?>">
                
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <i class="fa-solid fa-grip-vertical text-amber-500/70 dark:text-amber-500/70 group-hover:text-amber-700 text-xs mr-0.5 cursor-grab drag-handle transition-colors" title="Tarik untuk memindahkan"></i>
                        <span class="task-tag-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold <?= $task['tag_color']; ?> border border-current bg-white/60 dark:bg-slate-800/60 uppercase">
                            <?= $task['tag']; ?>
                        </span>
                        <span class="task-priority-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold <?= $priorityClass ?> border uppercase">
                            <?= $priorityLabel ?>
                        </span>
                        <?php if ($daysLeft < 0) : ?>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60 uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Terlambat
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <h4 class="task-title font-bold text-slate-900 dark:text-amber-100 mb-1 leading-tight transition-colors cursor-pointer hover:text-amber-800" onclick="openTaskDetailModal(<?= $task['id']; ?>)"><?= $task['title']; ?></h4>
                <p class="task-project text-[12px] text-amber-800/80 dark:text-amber-300/80 mb-4 transition-colors"><?= $task['project_name']; ?></p>

                <div class="task-progress-bar mb-4">
                    <div class="flex items-center justify-between text-[11px] font-bold mb-1.5">
                        <span class="text-amber-800/80 dark:text-amber-300/80 uppercase tracking-wider text-[10px] font-bold">Progress</span>
                        <span class="progress-val-label font-black text-amber-900 dark:text-amber-100"><?= $task['progress']; ?>%</span>
                    </div>
                    <div class="w-full bg-amber-200/80 dark:bg-amber-900/60 h-1.5 rounded-full overflow-hidden">
                        <div class="progress-bar-fill bg-amber-500 h-full rounded-full transition-all duration-300" style="width: <?= $task['progress']; ?>%"></div>
                    </div>
                </div>
                
                <div class="task-card-footer flex items-center justify-between border-t border-amber-200/80 dark:border-amber-800/50 pt-3">
                    <div class="task-date-area flex items-center gap-2 text-amber-800/80 dark:text-amber-300/80 text-[12px]">
                        <i class="fa-solid fa-calendar text-xs"></i>
                        <span class="font-medium"><?= $indoDate; ?></span>
                        <?php if ($daysLeft < 0) : ?>
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/60 px-1.5 py-0.5 rounded border border-rose-200 dark:border-rose-900/60 flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[9px]"></i> Terlambat <?= abs($daysLeft) ?>h
                            </span>
                        <?php elseif ($daysLeft == 0) : ?>
                            <span class="text-[10px] font-bold text-amber-800 bg-amber-200/60 dark:bg-amber-950/40 px-1.5 py-0.5 rounded border border-amber-300 dark:border-amber-800">Hari ini</span>
                        <?php elseif ($daysLeft <= 3) : ?>
                            <span class="text-[10px] font-bold text-amber-800 bg-amber-200/60 dark:bg-amber-950/40 px-1.5 py-0.5 rounded border border-amber-300 dark:border-amber-800">H-<?= $daysLeft ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <button type="button" class="p-1 text-amber-700 hover:text-amber-950 hover:bg-amber-200/60 rounded-lg transition" title="Lihat Sub-tugas & Komentar" onclick="event.stopPropagation(); openTaskDetailModal(<?= $task['id']; ?>)">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </button>
                            <button type="button" class="p-1 text-amber-700 hover:text-amber-950 hover:bg-amber-200/60 rounded-lg transition btnEditTugas" title="Edit" data-id="<?= $task['id']; ?>">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button type="button" class="p-1 text-amber-700 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition" title="Delete" onclick="showDeleteModal('<?= BASEURL; ?>/task/delete/<?= $task['id'] . $projectSuffix; ?>', '<?= addslashes($task['title']); ?>')">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']); ?>&background=random" class="w-7 h-7 rounded-full border border-white dark:border-amber-900 shadow-xs" alt="Avatar">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button class="w-full py-3 border border-dashed border-gray-200 dark:border-slate-700 rounded-xl text-gray-500 dark:text-gray-400 text-xs font-bold flex items-center justify-center gap-2 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:bg-slate-700/50 transition mt-3 btnTambahTugas" data-status="in_progress">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Tugas</span>
        </button>
    </div>

    <!-- DONE COLUMN -->
    <div class="kanban-column flex flex-col" data-status="done">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Done</h3>
                <span id="kanban-count-done" class="bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($data['tasks']['done']); ?></span>
            </div>
            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition text-xs">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
        </div>

        <div class="space-y-3.5 kanban-dropzone flex-1 min-h-[220px] p-1.5 -m-1.5 rounded-2xl transition-all duration-200" data-status="done">
            <?php 
            foreach($data['tasks']['done'] as $task) : 
                $date = strtotime($task['due_date']);
                $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $indoDate = date('d', $date) . ' ' . $months[(int)date('m', $date)] . ' ' . date('Y', $date);

                // Datediff Logic
                $now = new DateTime();
                $due = new DateTime($task['due_date']);
                $diff = $now->diff($due);
                $daysLeft = $diff->days * ($diff->invert ? -1 : 1);
                
                // Priority Logic
                $priorityLabel = ucfirst($task['priority'] ?? 'biasa');
                $priorityClass = "bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800";
                if (($task['priority'] ?? '') == 'tinggi') $priorityClass = "bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800";
                elseif (($task['priority'] ?? '') == 'sedang') $priorityClass = "bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 border-orange-100 dark:border-orange-800";

                $projectSuffix = isset($_GET['project_id']) && !empty($_GET['project_id']) ? '?project_id=' . $_GET['project_id'] : '';
            ?>
            <div class="kanban-card bg-[#DCFCE7] dark:bg-emerald-950/40 p-5 rounded-2xl border border-[#86EFAC] dark:border-emerald-700/60 shadow-xs hover:shadow-md transition group relative overflow-hidden cursor-grab active:cursor-grabbing select-none"
                 draggable="true"
                 data-id="<?= $task['id']; ?>"
                 data-status="done"
                 data-tag="<?= htmlspecialchars($task['tag'], ENT_QUOTES); ?>"
                 data-tag-color="<?= $task['tag_color']; ?>"
                 data-priority="<?= $task['priority'] ?? 'biasa'; ?>"
                 data-priority-class="<?= $priorityClass; ?>"
                 data-priority-label="<?= $priorityLabel; ?>"
                 data-title="<?= htmlspecialchars($task['title'], ENT_QUOTES); ?>"
                 data-project-name="<?= htmlspecialchars($task['project_name'], ENT_QUOTES); ?>"
                 data-assignee-name="<?= htmlspecialchars($task['assignee_name'], ENT_QUOTES); ?>"
                 data-due-date="<?= $task['due_date']; ?>"
                 data-indo-date="<?= $indoDate; ?>"
                 data-progress="<?= $task['progress']; ?>">
                
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <i class="fa-solid fa-grip-vertical text-emerald-500/70 dark:text-emerald-500/70 group-hover:text-emerald-700 text-xs mr-0.5 cursor-grab drag-handle transition-colors" title="Tarik untuk memindahkan"></i>
                        <span class="task-tag-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold <?= $task['tag_color']; ?> border border-current bg-white/60 dark:bg-slate-800/60 uppercase">
                            <?= $task['tag']; ?>
                        </span>
                        <span class="task-done-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold bg-emerald-200/80 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 border border-emerald-300/80 uppercase flex items-center gap-1">
                            <i class="fa-solid fa-check text-[10px]"></i> Selesai
                        </span>
                    </div>
                </div>

                <h4 class="task-title font-bold text-slate-900 dark:text-emerald-100 mb-1 leading-tight transition-colors cursor-pointer hover:text-emerald-800" onclick="openTaskDetailModal(<?= $task['id']; ?>)"><?= $task['title']; ?></h4>
                <p class="task-project text-[12px] text-emerald-800/80 dark:text-emerald-300/80 mb-5 transition-colors"><?= $task['project_name']; ?></p>
                
                <div class="task-card-footer flex items-center justify-between border-t border-emerald-200/80 dark:border-emerald-800/50 pt-3">
                    <div class="task-date-area flex items-center gap-2 text-emerald-800/80 dark:text-emerald-300/80 text-[12px]">
                        <i class="fa-solid fa-calendar text-xs"></i>
                        <span class="font-medium"><?= $indoDate; ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <button type="button" class="p-1 text-emerald-700 hover:text-emerald-950 hover:bg-emerald-200/60 rounded-lg transition" title="Lihat Sub-tugas & Komentar" onclick="event.stopPropagation(); openTaskDetailModal(<?= $task['id']; ?>)">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </button>
                            <button type="button" class="p-1 text-emerald-700 hover:text-emerald-950 hover:bg-emerald-200/60 rounded-lg transition btnEditTugas" title="Edit" data-id="<?= $task['id']; ?>">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button type="button" class="p-1 text-emerald-700 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition" title="Delete" onclick="showDeleteModal('<?= BASEURL; ?>/task/delete/<?= $task['id'] . $projectSuffix; ?>', '<?= addslashes($task['title']); ?>')">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']); ?>&background=random" class="w-7 h-7 rounded-full border border-white dark:border-emerald-900 shadow-xs" alt="Avatar">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button class="w-full py-3 border border-dashed border-gray-200 dark:border-slate-700 rounded-xl text-gray-500 dark:text-gray-400 text-xs font-bold flex items-center justify-center gap-2 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50/50 dark:hover:bg-slate-700/50 transition mt-3 btnTambahTugas" data-status="done">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Tugas</span>
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Modal Form Tugas -->
<div id="taskModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleTaskModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full transition-colors border border-gray-100 dark:border-slate-700">
            <form action="<?= BASEURL; ?>/task/add" method="post" id="taskForm">
                <input type="hidden" name="id" id="task_id">
                <input type="hidden" name="current_project_filter" value="<?= $_GET['project_id'] ?? '' ?>">

                <div class="p-6 md:p-7">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-700/80 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 tracking-tight" id="taskModalTitle">Buat Tugas Baru</h3>
                                <p class="text-gray-400 dark:text-gray-500 text-xs">Delegasikan pekerjaan ke tim Anda dengan detail jelas.</p>
                            </div>
                        </div>
                        <button type="button" onclick="toggleTaskModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Judul Tugas -->
                        <div>
                            <label for="title" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Judul Tugas</label>
                            <input type="text" name="title" id="title" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition h-[38px] placeholder-gray-400 dark:placeholder-gray-500" placeholder="Contoh: Slicing Homepage">
                        </div>

                        <!-- Project & Assignee -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="project_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Pilih Project</label>
                                <div class="select-container" id="projectSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Project...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Project...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <!-- Search Box Header -->
                                        <div class="p-2 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-20">
                                            <div class="relative">
                                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                                <input type="text" class="select-search-input w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs font-semibold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari nama project atau lead...">
                                            </div>
                                        </div>
                                        <div class="select-options-list">
                                            <?php foreach($data['projects'] as $p) : ?>
                                                <div class="select-option flex items-center justify-between gap-3" 
                                                     data-value="<?= $p['id']; ?>" 
                                                     data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES); ?>"
                                                     data-search="<?= htmlspecialchars(strtolower($p['name'] . ' ' . ($p['leader_name'] ?? '')), ENT_QUOTES); ?>">
                                                    <div class="flex items-center gap-2.5 min-w-0">
                                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs">
                                                            <i class="fa-solid fa-folder"></i>
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="font-bold text-xs truncate leading-tight text-gray-800 dark:text-gray-200"><?= htmlspecialchars($p['name']); ?></span>
                                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate">
                                                                Lead: <?= htmlspecialchars($p['leader_name'] ?? '-'); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <i class="fa-solid fa-check text-xs text-indigo-600 opacity-0 check-icon flex-shrink-0"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="select-no-results py-4 text-center text-xs text-gray-400 font-medium" style="display: none;">Tidak ada project yang cocok</div>
                                    </div>
                                </div>
                                <input type="hidden" name="project_id" id="project_id_field">
                            </div>

                            <?php if($_SESSION['user']['role'] === 'admin') : ?>
                            <div>
                                <label for="assignee_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Penanggung Jawab</label>
                                <div class="select-container" id="assigneeSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Anggota...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Anggota...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <?php foreach($data['team'] as $m) : ?>
                                            <div class="select-option flex items-center gap-2" data-value="<?= $m['id']; ?>">
                                                <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[9px] font-bold flex-shrink-0">
                                                    <?= strtoupper(substr($m['name'], 0, 2)); ?>
                                                </div>
                                                <span class="truncate"><?= htmlspecialchars($m['name']); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field">
                            </div>
                            <?php else : ?>
                            <div class="flex flex-col justify-end">
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Penanggung Jawab</label>
                                <div class="bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-2 h-[38px]">
                                    <div class="w-5 h-5 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[9px] font-bold">
                                        <?= strtoupper(substr($_SESSION['user']['name'], 0, 2)); ?>
                                    </div>
                                    <span><?= htmlspecialchars($_SESSION['user']['name']); ?> (Anda)</span>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field" value="<?= $_SESSION['user']['id']; ?>">
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tag & Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tag" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Label / Tag</label>
                                <input type="text" name="tag" id="tag" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition h-[38px] placeholder-gray-400 dark:placeholder-gray-500" placeholder="Contoh: Design">
                            </div>
                            <div>
                                <label for="priority_field" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Prioritas</label>
                                <div class="select-container" id="prioritySelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Prioritas...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Prioritas...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option flex items-center gap-2" data-value="tinggi">
                                            <span class="w-2 h-2 rounded-full bg-rose-500 flex-shrink-0"></span>
                                            <span>Tinggi</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="sedang">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                                            <span>Sedang</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="biasa">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                            <span>Biasa</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="priority" id="priority_field" value="biasa">
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Tenggat Waktu</label>
                            <input type="date" name="due_date" id="due_date" required class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-semibold text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition cursor-pointer h-[38px]" onclick="this.showPicker()">
                        </div>

                        <!-- Status & Progress -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_field" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5">Status KanBan</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Status...">
                                        <span class="select-trigger-text text-gray-400 dark:text-gray-500">Pilih Status...</span>
                                        <i class="fa-solid fa-chevron-down select-trigger-icon"></i>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option flex items-center gap-2" data-value="todo">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                                            <span>To Do</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="in_progress">
                                            <span class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                                            <span>In Progress</span>
                                        </div>
                                        <div class="select-option flex items-center gap-2" data-value="done">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                            <span>Done</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status_field" value="todo">
                            </div>
                            <div id="progress-container">
                                <label for="progress" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5 ml-0.5 flex justify-between items-center">
                                    <span>Progress</span>
                                    <span id="progress-val-text" class="text-xs font-bold text-indigo-600 dark:text-indigo-400">0%</span>
                                </label>
                                <div class="h-[38px] flex items-center px-1">
                                    <input type="range" name="progress" id="progress" min="0" max="100" class="w-full h-1.5 bg-gray-100 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600 transition hover:bg-gray-200 dark:hover:bg-slate-600" value="0">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tag_color" id="tag_color" value="bg-blue-50 text-blue-600">
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="flex items-center justify-end gap-2.5 mt-6 pt-4 border-t border-gray-100 dark:border-slate-700/80">
                        <button type="button" onclick="toggleTaskModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 font-semibold text-xs hover:bg-gray-50 dark:hover:bg-slate-700 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-xs hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 dark:shadow-none flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Simpan Tugas</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Tugas, Sub-tasks, dan Komentar -->
<div id="taskDetailModal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="task-detail-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeTaskDetailModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left shadow-2xl transform sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full overflow-hidden transition-all border border-gray-100 dark:border-slate-700">
            <!-- Modal Header -->
            <div class="p-6 md:p-8 bg-gray-50/70 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <span id="detailTaskTag" class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">Tag</span>
                        <span id="detailTaskPriority" class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300">Prioritas</span>
                        <span id="detailTaskStatus" class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300">Status</span>
                        <span id="detailTaskOverdueBadge" class="hidden px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-900/60">Terlambat</span>
                    </div>
                    <h3 id="detailTaskTitle" class="text-xl md:text-2xl font-black text-gray-900 dark:text-white leading-tight">Memuat judul tugas...</h3>
                    <div class="flex items-center gap-3 mt-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                        <span id="detailTaskProject" class="flex items-center gap-1.5"><i class="fa-solid fa-folder text-indigo-500"></i> Project</span>
                        <span>•</span>
                        <span id="detailTaskDueDate" class="flex items-center gap-1.5"><i class="fa-solid fa-calendar text-gray-400"></i> Due Date</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <button type="button" onclick="closeTaskDetailModal()" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 flex items-center justify-center transition border border-gray-200 dark:border-slate-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Two-Column: Sub-tasks & Comments) -->
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left: Sub-tasks Checklist -->
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <i class="fa-solid fa-list-check text-sm"></i>
                            </div>
                            <h4 class="text-base font-black text-gray-900 dark:text-white">Sub-tugas (Checklist)</h4>
                        </div>
                        <span id="subtasksSummaryText" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-1 rounded-full">0/0 Selesai (0%)</span>
                    </div>

                    <!-- Subtasks Progress Bar -->
                    <div class="w-full bg-gray-100 dark:bg-slate-700 h-2 rounded-full overflow-hidden mb-5">
                        <div id="subtasksProgressBar" class="bg-indigo-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>

                    <!-- Subtask Input Form -->
                    <div class="flex gap-2 mb-4">
                        <input type="text" id="newSubtaskTitle" placeholder="Tambah sub-tugas baru..." class="flex-1 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-800 dark:text-gray-200 focus:outline-none focus:border-indigo-500 transition" onkeypress="if(event.key === 'Enter') submitNewSubtask()">
                        <button type="button" onclick="submitNewSubtask()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah</span>
                        </button>
                    </div>

                    <!-- Subtasks List Container -->
                    <div id="subtasksListContainer" class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        <!-- Loaded dynamically via AJAX -->
                    </div>
                </div>

                <!-- Right: Discussion & Team Comments -->
                <div class="flex flex-col border-t md:border-t-0 md:border-l border-gray-100 dark:border-slate-700 pt-6 md:pt-0 md:pl-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <i class="fa-solid fa-comments text-sm"></i>
                            </div>
                            <h4 class="text-base font-black text-gray-900 dark:text-white">Diskusi & Komentar</h4>
                        </div>
                        <span id="commentsCountText" class="text-xs font-bold text-gray-400 dark:text-gray-500">0 Komentar</span>
                    </div>

                    <!-- Comments List Container -->
                    <div id="commentsListContainer" class="space-y-3 max-h-64 overflow-y-auto pr-1 flex-1 mb-4">
                        <!-- Loaded dynamically via AJAX -->
                    </div>

                    <!-- Typing Indicator -->
                    <div id="typingIndicatorContainer" class="hidden flex items-center gap-2 py-1.5 px-3 text-xs text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50/70 dark:bg-emerald-950/30 rounded-xl mb-2 transition-all">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce"></span>
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce [animation-delay:0.15s]"></span>
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce [animation-delay:0.3s]"></span>
                        </span>
                        <span id="typingIndicatorText">User sedang mengetik...</span>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="mt-auto flex gap-2">
                        <input type="text" id="newCommentText" placeholder="Tulis komentar atau update..." class="flex-1 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-800 dark:text-gray-200 focus:outline-none focus:border-emerald-500 transition" onkeypress="if(event.key === 'Enter') submitNewComment()">
                        <button type="button" onclick="submitNewComment()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition shadow-sm">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const taskModal = document.getElementById('taskModal');
    const taskForm = document.getElementById('taskForm');
    const taskModalTitle = document.getElementById('taskModalTitle');

    function showKanbanToast(message, type = 'success') {
        const notifType = (type === 'error' || type === 'danger') ? 'danger' : 'success';
        if (typeof showGlobalNotification === 'function') {
            showGlobalNotification(message, '', notifType);
        } else if (typeof window.showGlobalNotification === 'function') {
            window.showGlobalNotification(message, '', notifType);
        }
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.kanban-card:not(.is-dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function updateCardAppearance(card, newStatus, newProgress) {
        const titleEl = card.querySelector('.task-title');
        const projEl = card.querySelector('.task-project');
        const dateArea = card.querySelector('.task-date-area');
        const gripIcon = card.querySelector('.drag-handle');
        const tagBadge = card.querySelector('.task-tag-badge');
        const priorityBadge = card.querySelector('.task-priority-badge');
        const deadlineBadge = card.querySelector('.task-deadline-badge');
        const editBtn = card.querySelector('.btnEditTugas');
        const deleteBtn = card.querySelector('button[title="Delete"]');
        const footer = card.querySelector('.task-card-footer');
        const detailBtn = card.querySelector('button[title*="Sub-tugas"]') || card.querySelector('button[onclick*="openTaskDetailModal"]');
        let progressBar = card.querySelector('.task-progress-bar');
        let doneBadge = card.querySelector('.task-done-badge');

        const tagColor = card.getAttribute('data-tag-color') || 'bg-blue-50 text-blue-600';
        const priorityClass = card.getAttribute('data-priority-class') || '';
        const priorityLabel = card.getAttribute('data-priority-label') || 'Biasa';

        if (newStatus === 'done') {
            card.className = 'kanban-card bg-[#DCFCE7] dark:bg-emerald-950/40 p-5 rounded-2xl border border-[#86EFAC] dark:border-emerald-700/60 shadow-xs hover:shadow-md transition group relative overflow-hidden cursor-grab active:cursor-grabbing select-none';
            if (projEl) projEl.className = 'task-project text-[12px] text-emerald-800/80 dark:text-emerald-300/80 mb-5 transition-colors';
            if (dateArea) dateArea.className = 'task-date-area flex items-center gap-2 text-emerald-800/80 dark:text-emerald-300/80 text-[12px]';
            if (gripIcon) gripIcon.className = 'fa-solid fa-grip-vertical text-emerald-500/70 dark:text-emerald-500/70 group-hover:text-emerald-700 text-xs mr-0.5 cursor-grab drag-handle transition-colors';
            if (tagBadge) tagBadge.className = 'task-tag-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold ' + tagColor + ' border border-current bg-white/60 dark:bg-slate-800/60 uppercase';
            if (footer) footer.className = 'task-card-footer flex items-center justify-between border-t border-emerald-200/80 dark:border-emerald-800/50 pt-3';
            if (detailBtn) detailBtn.className = 'p-1 text-emerald-700 hover:text-emerald-950 hover:bg-emerald-200/60 rounded-lg transition';
            if (editBtn) editBtn.className = 'p-1 text-emerald-700 hover:text-emerald-950 hover:bg-emerald-200/60 rounded-lg transition btnEditTugas';
            if (deleteBtn) deleteBtn.className = 'p-1 text-emerald-700 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition';

            if (titleEl) titleEl.className = 'task-title font-bold text-slate-900 dark:text-emerald-100 mb-1 leading-tight transition-colors cursor-pointer hover:text-emerald-800';
            if (priorityBadge) priorityBadge.classList.add('hidden');
            if (progressBar) progressBar.remove();

            if (!doneBadge) {
                const badgeContainer = card.querySelector('.flex.items-center.gap-2.flex-wrap') || card.querySelector('.flex.items-center.gap-1\\.5.flex-wrap');
                if (badgeContainer) {
                    const badge = document.createElement('span');
                    badge.className = 'task-done-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold bg-emerald-200/80 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 border border-emerald-300/80 uppercase flex items-center gap-1';
                    badge.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i> Selesai';
                    badgeContainer.appendChild(badge);
                }
            } else {
                doneBadge.className = 'task-done-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold bg-emerald-200/80 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 border border-emerald-300/80 uppercase flex items-center gap-1';
                doneBadge.classList.remove('hidden');
            }
        } else if (newStatus === 'in_progress') {
            card.className = 'kanban-card bg-[#FEF9C3] dark:bg-amber-950/40 p-5 rounded-2xl border border-[#FDE047] dark:border-amber-700/60 shadow-xs hover:shadow-md transition group relative overflow-hidden cursor-grab active:cursor-grabbing select-none';
            if (projEl) projEl.className = 'task-project text-[12px] text-amber-800/80 dark:text-amber-300/80 mb-4 transition-colors';
            if (dateArea) dateArea.className = 'task-date-area flex items-center gap-2 text-amber-800/80 dark:text-amber-300/80 text-[12px]';
            if (gripIcon) gripIcon.className = 'fa-solid fa-grip-vertical text-amber-500/70 dark:text-amber-500/70 group-hover:text-amber-700 text-xs mr-0.5 cursor-grab drag-handle transition-colors';
            if (tagBadge) tagBadge.className = 'task-tag-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold ' + tagColor + ' border border-current bg-white/60 dark:bg-slate-800/60 uppercase';
            if (footer) footer.className = 'task-card-footer flex items-center justify-between border-t border-amber-200/80 dark:border-amber-800/50 pt-3';
            if (detailBtn) detailBtn.className = 'p-1 text-amber-700 hover:text-amber-950 hover:bg-amber-200/60 rounded-lg transition';
            if (editBtn) editBtn.className = 'p-1 text-amber-700 hover:text-amber-950 hover:bg-amber-200/60 rounded-lg transition btnEditTugas';
            if (deleteBtn) deleteBtn.className = 'p-1 text-amber-700 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition';

            if (titleEl) titleEl.className = 'task-title font-bold text-slate-900 dark:text-amber-100 mb-1 leading-tight transition-colors cursor-pointer hover:text-amber-800';
            if (priorityBadge) {
                priorityBadge.classList.remove('hidden');
                priorityBadge.className = 'task-priority-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold ' + priorityClass + ' border uppercase';
            }
            if (doneBadge) doneBadge.classList.add('hidden');

            const progressVal = (typeof newProgress === 'number') ? newProgress : (parseInt(card.getAttribute('data-progress')) || 50);

            if (!progressBar) {
                const pBar = document.createElement('div');
                pBar.className = 'task-progress-bar mb-4';
                pBar.innerHTML = `
                    <div class="flex items-center justify-between text-[11px] font-bold mb-1.5">
                        <span class="text-amber-800/80 dark:text-amber-300/80 uppercase tracking-wider text-[10px] font-bold">Progress</span>
                        <span class="progress-val-label font-black text-amber-900 dark:text-amber-100">${progressVal}%</span>
                    </div>
                    <div class="w-full bg-amber-200/80 dark:bg-amber-900/60 h-1.5 rounded-full overflow-hidden">
                        <div class="progress-bar-fill bg-amber-500 h-full rounded-full transition-all duration-300" style="width: ${progressVal}%"></div>
                    </div>`;
                if (footer) card.insertBefore(pBar, footer);
                else card.appendChild(pBar);
            } else {
                const fill = progressBar.querySelector('.progress-bar-fill');
                if (fill) {
                    fill.className = 'progress-bar-fill bg-amber-500 h-full rounded-full transition-all duration-300';
                    fill.style.width = `${progressVal}%`;
                }
                const label = progressBar.querySelector('.progress-val-label');
                if (label) {
                    label.className = 'progress-val-label font-black text-amber-900 dark:text-amber-100';
                    label.innerText = `${progressVal}%`;
                }
                const titleText = progressBar.querySelector('span:first-child');
                if (titleText) {
                    titleText.className = 'text-amber-800/80 dark:text-amber-300/80 uppercase tracking-wider text-[10px] font-bold';
                }
                const bgBar = progressBar.querySelector('.w-full');
                if (bgBar) {
                    bgBar.className = 'w-full bg-amber-200/80 dark:bg-amber-900/60 h-1.5 rounded-full overflow-hidden';
                }
            }
        } else {
            // todo
            card.className = 'kanban-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-200/90 dark:border-slate-700/80 shadow-xs hover:shadow-md hover:border-gray-300 dark:hover:border-slate-600 transition group relative overflow-hidden cursor-grab active:cursor-grabbing select-none';
            if (projEl) projEl.className = 'task-project text-[12px] text-gray-400 dark:text-gray-500 mb-5 transition-colors';
            if (dateArea) dateArea.className = 'task-date-area flex items-center gap-2 text-gray-400 dark:text-gray-500 text-[12px]';
            if (gripIcon) gripIcon.className = 'fa-solid fa-grip-vertical text-gray-300 dark:text-gray-600 group-hover:text-indigo-400 text-xs mr-0.5 cursor-grab drag-handle transition-colors';
            if (tagBadge) tagBadge.className = 'task-tag-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold ' + tagColor + ' border border-current bg-white/50 dark:bg-slate-700/50 uppercase';
            if (footer) footer.className = 'task-card-footer flex items-center justify-between border-t border-gray-100 dark:border-slate-700/80 pt-3';
            if (detailBtn) detailBtn.className = 'p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg transition';
            if (editBtn) editBtn.className = 'p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-700 rounded-lg transition btnEditTugas';
            if (deleteBtn) deleteBtn.className = 'p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 rounded-lg transition';

            if (titleEl) titleEl.className = 'task-title font-bold text-gray-900 dark:text-gray-100 mb-1 leading-tight transition-colors cursor-pointer hover:text-indigo-600';
            if (priorityBadge) {
                priorityBadge.classList.remove('hidden');
                priorityBadge.className = 'task-priority-badge px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold ' + priorityClass + ' border uppercase';
            }
            if (doneBadge) doneBadge.classList.add('hidden');
            if (progressBar) progressBar.remove();
        }
    }

    function initKanbanDragAndDrop() {
        let draggedCard = null;
        let sourceContainer = null;
        let originalNextSibling = null;
        let currentPlaceholder = null;

        function createPlaceholder() {
            const el = document.createElement('div');
            el.className = 'kanban-drop-placeholder h-20 border-2 border-dashed border-indigo-400 dark:border-indigo-500 rounded-2xl bg-indigo-50/40 dark:bg-indigo-950/20 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 transition-all duration-200 pointer-events-none my-2';
            el.innerHTML = '<div class="flex items-center gap-2"><i class="fa-solid fa-arrow-down text-indigo-500 text-xs"></i> Pindahkan ke sini</div>';
            return el;
        }

        // Attach listeners to all draggable cards
        document.querySelectorAll('.kanban-card').forEach(card => {
            card.addEventListener('dragstart', function(e) {
                if (e.target.closest('button, a, input, select')) {
                    e.preventDefault();
                    return;
                }

                draggedCard = this;
                sourceContainer = this.parentElement;
                originalNextSibling = this.nextElementSibling;

                e.dataTransfer.setData('text/plain', this.getAttribute('data-id'));
                e.dataTransfer.effectAllowed = 'move';

                setTimeout(() => {
                    this.classList.add('is-dragging', 'opacity-30', 'scale-[0.97]', 'ring-2', 'ring-indigo-500');
                }, 0);
            });

            card.addEventListener('dragend', function() {
                this.classList.remove('is-dragging', 'opacity-30', 'scale-[0.97]', 'ring-2', 'ring-indigo-500');
                if (currentPlaceholder) {
                    currentPlaceholder.remove();
                    currentPlaceholder = null;
                }
                document.querySelectorAll('.kanban-dropzone').forEach(dz => {
                    dz.classList.remove('bg-indigo-50/30', 'dark:bg-indigo-900/10', 'ring-2', 'ring-indigo-400', 'dark:ring-indigo-600');
                });
            });
        });

        // Dropzones
        document.querySelectorAll('.kanban-dropzone').forEach(dropzone => {
            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                this.classList.add('bg-indigo-50/30', 'dark:bg-indigo-900/10', 'ring-2', 'ring-indigo-400', 'dark:ring-indigo-600');

                if (!currentPlaceholder) {
                    currentPlaceholder = createPlaceholder();
                }

                const afterElement = getDragAfterElement(this, e.clientY);
                if (afterElement == null) {
                    this.appendChild(currentPlaceholder);
                } else {
                    this.insertBefore(currentPlaceholder, afterElement);
                }
            });

            dropzone.addEventListener('dragleave', function(e) {
                if (!this.contains(e.relatedTarget)) {
                    this.classList.remove('bg-indigo-50/30', 'dark:bg-indigo-900/10', 'ring-2', 'ring-indigo-400', 'dark:ring-indigo-600');
                    if (currentPlaceholder && currentPlaceholder.parentElement === this) {
                        currentPlaceholder.remove();
                        currentPlaceholder = null;
                    }
                }
            });

            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('bg-indigo-50/30', 'dark:bg-indigo-900/10', 'ring-2', 'ring-indigo-400', 'dark:ring-indigo-600');

                if (!draggedCard) return;

                const taskId = draggedCard.getAttribute('data-id');
                const oldStatus = draggedCard.getAttribute('data-status');
                const newStatus = this.getAttribute('data-status');

                const afterElement = getDragAfterElement(this, e.clientY);
                if (afterElement == null) {
                    this.appendChild(draggedCard);
                } else {
                    this.insertBefore(draggedCard, afterElement);
                }

                if (currentPlaceholder) {
                    currentPlaceholder.remove();
                    currentPlaceholder = null;
                }

                if (oldStatus === newStatus) {
                    return;
                }

                // Update status attribute and appearance
                draggedCard.setAttribute('data-status', newStatus);
                updateCardAppearance(draggedCard, newStatus);

                // Update column counters
                const oldCountEl = document.getElementById('kanban-count-' + oldStatus);
                const newCountEl = document.getElementById('kanban-count-' + newStatus);
                if (oldCountEl) oldCountEl.innerText = Math.max(0, parseInt(oldCountEl.innerText || '0') - 1);
                if (newCountEl) newCountEl.innerText = parseInt(newCountEl.innerText || '0') + 1;

                // Visual flash
                draggedCard.classList.add('ring-4', 'ring-indigo-400/50');
                setTimeout(() => draggedCard.classList.remove('ring-4', 'ring-indigo-400/50'), 700);

                // AJAX call to persist status update
                fetch('<?= BASEURL; ?>/task/updateStatus', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: taskId, status: newStatus })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showGlobalNotification(data.message || 'Status berhasil diperbarui', '', 'success');
                        if (typeof data.new_progress !== 'undefined') {
                            draggedCard.setAttribute('data-progress', data.new_progress);
                            updateCardAppearance(draggedCard, newStatus, data.new_progress);
                        }
                    } else {
                        showGlobalNotification(data.message || 'Gagal memindahkan tugas', '', 'danger');
                        revertCard();
                    }
                })
                .catch(err => {
                    console.error(err);
                    showGlobalNotification('Gagal terhubung ke server', '', 'danger');
                    revertCard();
                });

                function revertCard() {
                    draggedCard.setAttribute('data-status', oldStatus);
                    updateCardAppearance(draggedCard, oldStatus);
                    if (originalNextSibling && originalNextSibling.parentElement === sourceContainer) {
                        sourceContainer.insertBefore(draggedCard, originalNextSibling);
                    } else {
                        sourceContainer.appendChild(draggedCard);
                    }
                    if (oldCountEl) oldCountEl.innerText = parseInt(oldCountEl.innerText || '0') + 1;
                    if (newCountEl) newCountEl.innerText = Math.max(0, parseInt(newCountEl.innerText || '0') - 1);
                }
            });
        });
    }


    function toggleTaskModal() {
        if (taskModal.classList.contains('hidden')) {
            taskModal.classList.remove('hidden');
            setTimeout(() => {
                taskModal.classList.add('modal-active');
                document.body.classList.add('overflow-hidden');
            }, 10);
        } else {
            taskModal.classList.remove('modal-active');
            setTimeout(() => {
                taskModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                taskForm.reset();
                document.getElementById('task_id').value = '';
            }, 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Teleport action buttons to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('task-action-content');
        if (actionArea && actionContent) {
            actionArea.innerHTML = '';
            while (actionContent.firstChild) {
                actionArea.appendChild(actionContent.firstChild);
            }
        }

        // Initialize Custom Selects
        initCustomSelect('projectSelectContainer', 'project_id_field');
        if (document.getElementById('assigneeSelectContainer')) {
            initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
        }
        initCustomSelect('statusSelectContainer', 'status_field');
        initCustomSelect('prioritySelectContainer', 'priority_field');


        // Progress Slider Logic
        const progressSlider = document.getElementById('progress');
        const progressText = document.getElementById('progress-val-text');
        const progressContainer = document.getElementById('progress-container');
        const statusField = document.getElementById('status_field');

        function updateProgressVisibility() {
            const val = parseInt(progressSlider.value);
            
            // Text sync
            progressText.innerText = val + '%';
            progressText.className = 'text-xs font-bold text-indigo-600 dark:text-indigo-400';

            // Auto-sync status from progress visibility
            if (statusField.value === 'done') {
                progressContainer.classList.add('hidden');
            } else {
                progressContainer.classList.remove('hidden');
            }
        }

        // Initialize Custom Selects with logic
        initCustomSelect('projectSelectContainer', 'project_id_field');
        if (document.getElementById('assigneeSelectContainer')) {
            initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
        }
        
        initCustomSelect('statusSelectContainer', 'status_field', (val) => {
            // When status changes via dropdown
            if (val === 'done') {
                progressSlider.value = 100;
            } else if (val === 'todo') {
                progressSlider.value = 0;
            }
            updateProgressVisibility();
        });

        // Initialize visibility
        updateProgressVisibility();

        progressSlider.addEventListener('input', function() {
            const val = parseInt(this.value);
            
            // Auto update status value
            if (val === 100) {
                statusField.value = 'done';
            } else if (val > 0) {
                statusField.value = 'in_progress';
            } else {
                statusField.value = 'todo';
            }
            
            // Sync the custom select UI without re-binding
            initCustomSelect('statusSelectContainer', 'status_field');
            updateProgressVisibility();
        });

        const btnsTambah = document.querySelectorAll('.btnTambahTugas');
        btnsTambah.forEach(btn => {
            btn.addEventListener('click', function() {
                taskModalTitle.innerText = 'Buat Tugas Baru';
                taskForm.setAttribute('action', '<?= BASEURL; ?>/task/add');
                
                const status = this.getAttribute('data-status');
                statusField.value = status || 'todo';
                
                // Use active_project_id passed from controller
                const currentProjFilter = '<?= $data['active_project_id'] ?>';

                // Reset other fields
                document.getElementById('project_id_field').value = currentProjFilter || '';
                if (document.getElementById('assigneeSelectContainer')) {
                    document.getElementById('assignee_id_field').value = '';
                    initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
                }
                
                progressSlider.value = (status === 'done' ? 100 : 0);
                progressText.innerText = progressSlider.value + '%';
                
                document.getElementById('priority_field').value = 'biasa';
                
                // Sync all
                initCustomSelect('projectSelectContainer', 'project_id_field');
                initCustomSelect('statusSelectContainer', 'status_field');
                initCustomSelect('prioritySelectContainer', 'priority_field');
                updateProgressVisibility();
                
                toggleTaskModal();
            });
        });

        const btnsEdit = document.querySelectorAll('.btnEditTugas');
        btnsEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                taskModalTitle.innerText = 'Ubah Detail Tugas';
                taskForm.setAttribute('action', '<?= BASEURL; ?>/task/edit');
                
                fetch('<?= BASEURL; ?>/task/getEdit/' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('task_id').value = data.id;
                        document.getElementById('title').value = data.title;
                        document.getElementById('project_id_field').value = data.project_id;
                        document.getElementById('assignee_id_field').value = data.assignee_id;
                        document.getElementById('tag').value = data.tag;
                        document.getElementById('due_date').value = data.due_date;
                        statusField.value = data.status;
                        document.getElementById('priority_field').value = data.priority;
                        
                        // Set slider & text
                        progressSlider.value = data.progress;
                        progressText.innerText = data.progress + '%';
                        
                        document.getElementById('tag_color').value = data.tag_color;

                        // Sync all custom selects
                        initCustomSelect('projectSelectContainer', 'project_id_field');
                        if (document.getElementById('assigneeSelectContainer')) {
                            initCustomSelect('assigneeSelectContainer', 'assignee_id_field');
                        }
                        initCustomSelect('statusSelectContainer', 'status_field');
                        initCustomSelect('prioritySelectContainer', 'priority_field');
                        updateProgressVisibility();

                        toggleTaskModal();
                    });
            });
        });

        // Inisialisasi Drag and Drop Kanban
        initKanbanDragAndDrop();
    });

    // ================= TASK DETAIL, SUBTASKS & COMMENTS ENGINE =================
    let currentDetailTaskId = null;
    let commentPollingInterval = null;
    let typingDebounceTimer = null;
    let lastTypingSentAt = 0;
    let cachedCommentsCount = -1;

    function openTaskDetailModal(taskId) {
        currentDetailTaskId = taskId;
        const modal = document.getElementById('taskDetailModal');
        if (!modal) return;

        // Reset state & clear old intervals
        if (commentPollingInterval) {
            clearInterval(commentPollingInterval);
            commentPollingInterval = null;
        }
        cachedCommentsCount = -1;

        document.getElementById('detailTaskTitle').innerText = 'Memuat judul tugas...';
        document.getElementById('subtasksListContainer').innerHTML = '<div class="py-6 text-center text-gray-400 text-xs">Memuat sub-tugas...</div>';
        document.getElementById('commentsListContainer').innerHTML = '<div class="py-6 text-center text-gray-400 text-xs">Memuat komentar...</div>';
        document.getElementById('subtasksSummaryText').innerText = '0/0 Selesai (0%)';
        document.getElementById('subtasksProgressBar').style.width = '0%';
        document.getElementById('commentsCountText').innerText = '0 Komentar';
        renderTypingIndicator([]);

        modal.classList.remove('hidden');

        fetch('<?= BASEURL ?>/task/getDetail/' + taskId)
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    showKanbanToast(res.message || 'Gagal memuat detail tugas', 'danger');
                    closeTaskDetailModal();
                    return;
                }
                const task = res.task;
                document.getElementById('detailTaskTitle').innerText = task.title;
                document.getElementById('detailTaskProject').innerHTML = `<i class="fa-solid fa-folder text-indigo-500"></i> ${task.project_name || '-'}`;
                document.getElementById('detailTaskDueDate').innerHTML = `<i class="fa-solid fa-calendar text-gray-400"></i> Tenggat: ${task.due_date || '-'}`;
                document.getElementById('detailTaskTag').innerText = task.tag || 'Tugas';
                document.getElementById('detailTaskPriority').innerText = (task.priority || 'Biasa').toUpperCase();
                document.getElementById('detailTaskStatus').innerText = (task.status || 'todo').replace('_', ' ').toUpperCase();

                // Overdue check
                const today = new Date().toISOString().split('T')[0];
                const overdueBadge = document.getElementById('detailTaskOverdueBadge');
                if (task.due_date < today && task.status !== 'done') {
                    overdueBadge.classList.remove('hidden');
                } else {
                    overdueBadge.classList.add('hidden');
                }

                renderSubtasks(res.subtasks || []);
                renderComments(res.comments || [], true);
                renderTypingIndicator(res.typing_users || []);

                // Start realtime comments & typing polling (setiap 2.5 detik)
                startCommentRealtimePolling(taskId);
            })
            .catch(err => {
                console.error(err);
                showKanbanToast('Terjadi kesalahan memuat detail tugas', 'danger');
            });
    }

    function closeTaskDetailModal() {
        const modal = document.getElementById('taskDetailModal');
        if (modal) modal.classList.add('hidden');

        // Stop polling & clear typing status
        if (commentPollingInterval) {
            clearInterval(commentPollingInterval);
            commentPollingInterval = null;
        }
        if (currentDetailTaskId) {
            notifyTypingStatus(currentDetailTaskId, false);
        }
        currentDetailTaskId = null;
        cachedCommentsCount = -1;
        renderTypingIndicator([]);
    }

    function startCommentRealtimePolling(taskId) {
        if (commentPollingInterval) clearInterval(commentPollingInterval);
        commentPollingInterval = setInterval(() => {
            if (!currentDetailTaskId || currentDetailTaskId !== taskId) return;

            fetch('<?= BASEURL ?>/task/getCommentsRealtime/' + taskId)
                .then(res => res.json())
                .then(data => {
                    if (data.success && currentDetailTaskId === taskId) {
                        const newComments = data.comments || [];
                        if (newComments.length !== cachedCommentsCount) {
                            renderComments(newComments, true);
                        }
                        renderTypingIndicator(data.typing_users || []);
                    }
                })
                .catch(err => console.debug('Polling realtime comments error', err));
        }, 2500);
    }

    function renderSubtasks(subtasks) {
        const container = document.getElementById('subtasksListContainer');
        const total = subtasks.length;
        const completed = subtasks.filter(s => s.is_completed == 1).length;
        const pct = total > 0 ? Math.round((completed / total) * 100) : 0;

        document.getElementById('subtasksSummaryText').innerText = `${completed}/${total} Selesai (${pct}%)`;
        document.getElementById('subtasksProgressBar').style.width = pct + '%';

        if (total === 0) {
            container.innerHTML = '<p class="text-xs text-gray-400 dark:text-gray-500 italic py-6 text-center">Belum ada sub-tugas. Tambahkan di atas.</p>';
            return;
        }

        container.innerHTML = subtasks.map(s => `
            <div class="flex items-center justify-between p-2.5 rounded-xl bg-gray-50 dark:bg-slate-900/60 border border-gray-100 dark:border-slate-700/60 hover:bg-gray-100/60 dark:hover:bg-slate-700/50 transition group">
                <label class="flex items-center gap-2.5 cursor-pointer flex-1 min-w-0 select-none">
                    <input type="checkbox" ${s.is_completed == 1 ? 'checked' : ''} onchange="toggleSubtaskItem(${s.id})" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-slate-600 cursor-pointer">
                    <span class="text-xs font-bold ${s.is_completed == 1 ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-700 dark:text-gray-200'} truncate">${escapeHtml(s.title)}</span>
                </label>
                <button type="button" onclick="deleteSubtaskItem(${s.id})" class="text-gray-300 hover:text-rose-500 p-1 opacity-0 group-hover:opacity-100 transition" title="Hapus sub-tugas">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </div>
        `).join('');
    }

    function submitNewSubtask() {
        if (!currentDetailTaskId) return;
        const input = document.getElementById('newSubtaskTitle');
        const title = input.value.trim();
        if (!title) return;

        fetch('<?= BASEURL ?>/task/addSubtask', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: currentDetailTaskId, title: title })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                input.value = '';
                renderSubtasks(res.subtasks);
                showKanbanToast('Sub-tugas berhasil ditambahkan');
            } else {
                showKanbanToast(res.message || 'Gagal menambahkan sub-tugas', 'danger');
            }
        });
    }

    function toggleSubtaskItem(subtaskId) {
        fetch('<?= BASEURL ?>/task/toggleSubtask/' + subtaskId, { method: 'POST' })
            .then(res => res.json())
            .then(res => {
                if (res.success && currentDetailTaskId) {
                    fetch('<?= BASEURL ?>/task/getDetail/' + currentDetailTaskId)
                        .then(r => r.json())
                        .then(r => { if(r.success) renderSubtasks(r.subtasks); });
                }
            });
    }

    function deleteSubtaskItem(subtaskId) {
        fetch('<?= BASEURL ?>/task/deleteSubtask/' + subtaskId, { method: 'POST' })
            .then(res => res.json())
            .then(res => {
                if (res.success && currentDetailTaskId) {
                    fetch('<?= BASEURL ?>/task/getDetail/' + currentDetailTaskId)
                        .then(r => r.json())
                        .then(r => { if(r.success) renderSubtasks(r.subtasks); });
                    showKanbanToast('Sub-tugas berhasil dihapus');
                }
            });
    }

    function renderComments(comments, shouldAutoScroll = false) {
        const container = document.getElementById('commentsListContainer');
        cachedCommentsCount = comments.length;
        document.getElementById('commentsCountText').innerText = `${comments.length} Komentar`;

        if (!comments.length) {
            container.innerHTML = '<p class="text-xs text-gray-400 dark:text-gray-500 italic py-8 text-center">Belum ada diskusi. Mulai tulis komentar pertama!</p>';
            return;
        }

        container.innerHTML = comments.map(c => `
            <div class="p-3 rounded-2xl bg-gray-50 dark:bg-slate-900/60 border border-gray-100 dark:border-slate-700/60 flex items-start gap-3 transition">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(c.user_name || 'User')}&background=random" class="w-7 h-7 rounded-full border border-gray-200 dark:border-slate-700 flex-shrink-0" alt="Avatar">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-black text-gray-800 dark:text-gray-100 truncate">${escapeHtml(c.user_name)}</span>
                        <span class="text-[10px] text-gray-400 font-semibold flex-shrink-0">${c.created_at ? c.created_at.substring(0, 16) : ''}</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed font-medium break-words">${escapeHtml(c.comment)}</p>
                </div>
            </div>
        `).join('');

        if (shouldAutoScroll) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function renderTypingIndicator(typingUsers) {
        const container = document.getElementById('typingIndicatorContainer');
        const textEl = document.getElementById('typingIndicatorText');
        if (!container || !textEl) return;

        if (Array.isArray(typingUsers) && typingUsers.length > 0) {
            const names = typingUsers.slice(0, 2).join(' & ');
            const label = typingUsers.length > 2 
                ? `${names} dan ${typingUsers.length - 2} lainnya sedang mengetik...`
                : `${names} sedang mengetik...`;
            textEl.innerText = label;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function notifyTypingStatus(taskId, isTyping) {
        if (!taskId) return;
        const now = Date.now();
        if (isTyping && (now - lastTypingSentAt < 1500)) return;
        lastTypingSentAt = now;

        fetch('<?= BASEURL ?>/task/setTyping', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: taskId, is_typing: isTyping })
        }).catch(err => console.debug('Sync typing error', err));
    }

    // Input Typing Listener
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-open task detail modal if task_id is present in URL
        const urlParams = new URLSearchParams(window.location.search);
        const autoOpenTaskId = urlParams.get('task_id');
        if (autoOpenTaskId) {
            setTimeout(() => {
                openTaskDetailModal(parseInt(autoOpenTaskId));
            }, 100);
        }

        const commentInput = document.getElementById('newCommentText');
        if (commentInput) {
            commentInput.addEventListener('input', function() {
                if (!currentDetailTaskId) return;
                const hasText = this.value.trim().length > 0;
                if (hasText) {
                    notifyTypingStatus(currentDetailTaskId, true);
                    if (typingDebounceTimer) clearTimeout(typingDebounceTimer);
                    typingDebounceTimer = setTimeout(() => {
                        if (currentDetailTaskId) notifyTypingStatus(currentDetailTaskId, false);
                    }, 3500);
                } else {
                    notifyTypingStatus(currentDetailTaskId, false);
                }
            });

            commentInput.addEventListener('blur', function() {
                if (currentDetailTaskId) notifyTypingStatus(currentDetailTaskId, false);
            });
        }
    });

    function submitNewComment() {
        if (!currentDetailTaskId) return;
        const input = document.getElementById('newCommentText');
        const comment = input.value.trim();
        if (!comment) return;

        notifyTypingStatus(currentDetailTaskId, false);

        fetch('<?= BASEURL ?>/task/addComment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: currentDetailTaskId, comment: comment })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                input.value = '';
                renderComments(res.comments, true);
                showKanbanToast('Komentar berhasil dikirim');
            } else {
                showKanbanToast(res.message || 'Gagal mengirim komentar', 'danger');
            }
        });
    }

    function filterTaskProjects(query) {
        const q = (query || '').toLowerCase().trim();
        const items = document.querySelectorAll('.filter-project-item');
        let visibleCount = 0;
        items.forEach(item => {
            const search = (item.getAttribute('data-search') || '').toLowerCase();
            const match = search.includes(q);
            item.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
        const emptyState = document.getElementById('filterProjectEmptyState');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }
</script>


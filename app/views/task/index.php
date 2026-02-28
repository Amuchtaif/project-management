<div id="task-action-content" class="hidden">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 py-1">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Project Filter -->
            <div class="relative custom-dropdown" id="taskProjectDropdown">
                <button onclick="toggleDropdown('taskProjectDropdown')" class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-xl border border-gray-100 shadow-sm hover:bg-gray-50 transition border-s-4 border-s-indigo-500">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-tight">Proyek:</span>
                    <span class="text-sm font-black text-gray-900">
                        <?php 
                            $selectedProj = 'Pilih Proyek';
                            if(isset($_GET['project_id'])) {
                                foreach($data['projects'] as $p) {
                                    if($p['id'] == $_GET['project_id']) $selectedProj = $p['name'];
                                }
                            } elseif (count($data['projects']) == 1) {
                                $selectedProj = $data['projects'][0]['name'];
                            }
                            echo $selectedProj;
                        ?>
                    </span>
                    <svg class="w-4 h-4 text-indigo-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="dropdown-menu absolute top-full left-0 mt-2 w-64 bg-white border border-gray-100 rounded-2xl shadow-2xl py-3 hidden z-[100] max-h-60 overflow-y-auto">
                    <?php foreach($data['projects'] as $p) : ?>
                        <button onclick="applyFilter('project_id', '<?= $p['id'] ?>')" class="w-full text-left px-5 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= (isset($_GET['project_id']) && $_GET['project_id'] == $p['id']) ? 'font-black text-indigo-700 bg-indigo-50/50' : 'font-semibold' ?>"><?= $p['name'] ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="relative">
                <input type="text" id="taskSearch" placeholder="Cari tugas..." value="<?= $_GET['search'] ?? '' ?>" 
                    class="bg-white border border-gray-100 rounded-xl px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-indigo-500 outline-none pr-12 w-full sm:w-64"
                    onkeypress="if(event.key === 'Enter') applyFilter('search', this.value)">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            
            <?php if(isset($_GET['project_id']) || isset($_GET['search'])) : ?>
                <a href="<?= BASEURL ?>/task" class="flex items-center gap-2 bg-red-50 px-5 py-2.5 rounded-xl border border-red-100 shadow-sm hover:bg-red-100 transition text-xs font-black text-red-600 uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
            <?php endif; ?>
        </div>
        <button class="w-full lg:w-auto bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 btnTambahTugas">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Task
        </button>
    </div>

</div>

<?php if ($data['is_empty_state']) : ?>
    <div class="flex flex-col items-center justify-center min-h-[400px] bg-white rounded-3xl border-2 border-dashed border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 text-indigo-600">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Pilih Proyek Terlebih Dahulu</h3>
        <p class="text-gray-400 font-medium max-w-sm">Silakan pilih proyek di bagian atas untuk mulai melihat dan mengelola tugas tim secara real-time.</p>
    </div>
<?php else : ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


    <!-- TO DO COLUMN -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h3 class="text-lg font-black text-gray-900">To Do</h3>
                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full"><?= count($data['tasks']['todo']); ?></span>
            </div>
            <button class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
            </button>
        </div>

        <div class="space-y-4">
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
                
                $deadlineLabel = "";
                $deadlineClass = "bg-gray-100 text-gray-500";
                if ($daysLeft < 0) {
                    $deadlineLabel = "TERLAMBAT " . abs($daysLeft) . " HARI";
                    $deadlineClass = "bg-red-500 text-white shadow-lg shadow-red-100";
                } elseif ($daysLeft == 0) {
                    $deadlineLabel = "HARI INI";
                    $deadlineClass = "bg-orange-500 text-white shadow-lg shadow-orange-100";
                } elseif ($daysLeft == 1) {
                    $deadlineLabel = "H-1";
                    $deadlineClass = "bg-red-400 text-white shadow-lg shadow-red-50";
                } elseif ($daysLeft <= 3) {
                    $deadlineLabel = "H-" . $daysLeft;
                    $deadlineClass = "bg-orange-400 text-white shadow-lg shadow-orange-50";
                } else {
                    $deadlineLabel = "H-" . $daysLeft;
                    $deadlineClass = "bg-indigo-50 text-indigo-500 border border-indigo-100";
                }
                
                // Priority Logic
                $priorityLabel = ucfirst($task['priority'] ?? 'biasa');
                $priorityClass = "bg-emerald-50 text-emerald-600 border-emerald-100";
                if (($task['priority'] ?? '') == 'tinggi') $priorityClass = "bg-red-50 text-red-600 border-red-100";
                elseif (($task['priority'] ?? '') == 'sedang') $priorityClass = "bg-orange-50 text-orange-600 border-orange-100";

                
                $projectSuffix = isset($_GET['project_id']) && !empty($_GET['project_id']) ? '?project_id=' . $_GET['project_id'] : '';
            ?>
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition group relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-black tracking-widest <?= $task['tag_color']; ?> border border-current bg-white/10 uppercase">
                            <?= $task['tag']; ?>
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-black tracking-widest <?= $priorityClass ?> border uppercase">
                            <?= $priorityLabel ?>
                        </span>
                    </div>
                    
                    <?php if($deadlineLabel): ?>
                        <div class="absolute top-0 right-0 px-5 py-2.5 rounded-bl-[1.5rem] <?= $deadlineClass ?> text-[11px] font-black tracking-widest shadow-sm">
                            <?= $deadlineLabel ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h4 class="font-bold text-gray-900 mb-1 leading-tight"><?= $task['title']; ?></h4>
                <p class="text-[13px] text-gray-400 mb-6"><?= $task['project_name']; ?></p>
                
                <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                    <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[12px] font-bold"><?= $indoDate; ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <button class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition btnEditTugas" title="Edit" data-id="<?= $task['id']; ?>">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete" onclick="showDeleteModal('<?= BASEURL; ?>/task/delete/<?= $task['id'] . $projectSuffix; ?>', '<?= addslashes($task['title']); ?>')">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']); ?>&background=random" class="w-7 h-7 rounded-full border border-white shadow-sm" alt="Avatar">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <button class="w-full py-4 border-2 border-dashed border-gray-100 rounded-2xl text-gray-400 text-sm font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition mt-6 btnTambahTugas" data-status="todo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add new task
            </button>
        </div>
    </div>

    <!-- IN PROGRESS COLUMN -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h3 class="text-lg font-black text-gray-900">In Progress</h3>
                <span class="bg-indigo-100 text-indigo-600 text-xs font-bold px-2.5 py-1 rounded-full"><?= count($data['tasks']['in_progress']); ?></span>
            </div>
            <button class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
            </button>
        </div>

        <div class="space-y-4">
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
                
                $deadlineLabel = "";
                $deadlineClass = "bg-gray-100 text-gray-500";
                if ($daysLeft < 0) {
                    $deadlineLabel = "TERLAMBAT " . abs($daysLeft) . " HARI";
                    $deadlineClass = "bg-red-500 text-white shadow-lg shadow-red-100";
                } elseif ($daysLeft == 0) {
                    $deadlineLabel = "HARI INI";
                    $deadlineClass = "bg-orange-500 text-white shadow-lg shadow-orange-100";
                } elseif ($daysLeft == 1) {
                    $deadlineLabel = "H-1";
                    $deadlineClass = "bg-red-400 text-white shadow-lg shadow-red-50";
                } elseif ($daysLeft <= 3) {
                    $deadlineLabel = "H-" . $daysLeft;
                    $deadlineClass = "bg-orange-400 text-white shadow-lg shadow-orange-50";
                } else {
                    $deadlineLabel = "H-" . $daysLeft;
                    $deadlineClass = "bg-indigo-50 text-indigo-500 border border-indigo-100";
                }
                
                // Priority Logic
                $priorityLabel = ucfirst($task['priority'] ?? 'biasa');
                $priorityClass = "bg-emerald-50 text-emerald-600 border-emerald-100";
                if (($task['priority'] ?? '') == 'tinggi') $priorityClass = "bg-red-50 text-red-600 border-red-100";
                elseif (($task['priority'] ?? '') == 'sedang') $priorityClass = "bg-orange-50 text-orange-600 border-orange-100";


                $projectSuffix = isset($_GET['project_id']) && !empty($_GET['project_id']) ? '?project_id=' . $_GET['project_id'] : '';
                
                // Dynamic Progress Color
                $pColor = 'bg-red-500'; // Default 0-40
                if ($task['progress'] >= 71) $pColor = 'bg-green-500';
                elseif ($task['progress'] >= 41) $pColor = 'bg-yellow-400';
            ?>
            <div class="bg-white p-5 rounded-3xl border-l-[4px] border-l-indigo-600 border border-gray-100 shadow-sm hover:shadow-md transition group relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-black tracking-widest <?= $task['tag_color']; ?> border border-current bg-white/10 uppercase">
                            <?= $task['tag']; ?>
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-black tracking-widest <?= $priorityClass ?> border uppercase">
                            <?= $priorityLabel ?>
                        </span>
                    </div>

                    <?php if($deadlineLabel): ?>
                        <div class="absolute top-0 right-0 px-5 py-2.5 rounded-bl-[1.5rem] <?= $deadlineClass ?> text-[11px] font-black tracking-widest shadow-sm">
                            <?= $deadlineLabel ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h4 class="font-bold text-gray-900 mb-1 leading-tight"><?= $task['title']; ?></h4>
                <p class="text-[13px] text-gray-400 mb-4"><?= $task['project_name']; ?></p>

                <div class="mb-6">
                    <div class="w-full bg-gray-50 h-1.5 rounded-full overflow-hidden">
                        <div class="<?= $pColor ?> h-full rounded-full transition-all duration-300" style="width: <?= $task['progress']; ?>%"></div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                    <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[12px] font-bold"><?= $indoDate; ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <button class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition btnEditTugas" title="Edit" data-id="<?= $task['id']; ?>">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete" onclick="showDeleteModal('<?= BASEURL; ?>/task/delete/<?= $task['id'] . $projectSuffix; ?>', '<?= addslashes($task['title']); ?>')">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']); ?>&background=random" class="w-7 h-7 rounded-full border border-white shadow-sm" alt="Avatar">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <button class="w-full py-4 border-2 border-dashed border-gray-100 rounded-2xl text-gray-400 text-sm font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition mt-6 btnTambahTugas" data-status="in_progress">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add new task
            </button>
        </div>
    </div>

    <!-- DONE COLUMN -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h3 class="text-lg font-black text-gray-900">Done</h3>
                <span class="bg-green-100 text-green-600 text-xs font-bold px-2.5 py-1 rounded-full"><?= count($data['tasks']['done']); ?></span>
            </div>
            <button class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
            </button>
        </div>

        <div class="space-y-4">
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
                
                $deadlineLabel = "";
                $deadlineClass = "bg-white/10 text-white";
                if ($daysLeft < 0) {
                    $deadlineLabel = "SELESAI (TERLAMBAT)";
                } elseif ($daysLeft == 0) {
                    $deadlineLabel = "SELESAI HARI INI";
                } else {
                    $deadlineLabel = "SELESAI";
                }
                
                // Priority Logic
                $priorityLabel = ucfirst($task['priority'] ?? 'biasa');
                $priorityClass = "bg-white/20 text-white border-white/30";
                if (($task['priority'] ?? '') == 'tinggi') $priorityClass = "bg-red-400 text-white border-red-300";
                elseif (($task['priority'] ?? '') == 'sedang') $priorityClass = "bg-orange-400 text-white border-orange-300";

                $projectSuffix = isset($_GET['project_id']) && !empty($_GET['project_id']) ? '?project_id=' . $_GET['project_id'] : '';
            ?>
            <div class="bg-blue-600 p-5 rounded-3xl border border-blue-700 shadow-xl transition group relative overflow-hidden hover:shadow-2xl hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-black tracking-widest bg-white/20 text-white border border-white/30 backdrop-blur-sm uppercase">
                            <?= $task['tag']; ?>
                        </span>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-black tracking-widest <?= $priorityClass ?> border backdrop-blur-sm uppercase">
                            <?= $priorityLabel ?>
                        </span>
                    </div>

                    <?php if($deadlineLabel): ?>
                        <div class="absolute top-0 right-0 px-5 py-2.5 rounded-bl-[1.5rem] bg-white/20 text-white border border-white/30 backdrop-blur-sm text-[11px] font-black tracking-widest shadow-sm">
                            <?= $deadlineLabel ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h4 class="font-bold text-white mb-1"><?= $task['title']; ?></h4>
                <p class="text-[13px] text-blue-100 font-bold mb-6 opacity-80"><?= $task['project_name']; ?></p>
                
                <div class="flex items-center justify-between border-t border-white/10 pt-4">
                    <div class="flex items-center gap-2 text-blue-100">
                        <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[12px] font-bold"><?= $indoDate; ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <button class="p-1 text-white/50 hover:text-white hover:bg-white/10 rounded-lg transition btnEditTugas" title="Edit" data-id="<?= $task['id']; ?>">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button class="p-1 text-white/50 hover:text-red-200 hover:bg-white/10 rounded-lg transition" title="Delete" onclick="showDeleteModal('<?= BASEURL; ?>/task/delete/<?= $task['id'] . $projectSuffix; ?>', '<?= addslashes($task['title']); ?>')">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <div class="bg-white p-1 rounded-full text-blue-600 shadow-sm" title="Selesai">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']); ?>&background=random" class="w-7 h-7 rounded-full border border-blue-400 shadow-sm" alt="Avatar">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <button class="w-full py-4 border-2 border-dashed border-gray-100 rounded-2xl text-gray-400 text-sm font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition mt-6 btnTambahTugas" data-status="done">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add new task
            </button>
        </div>
    </div>
</div>
<?php endif; ?>
</div>

<!-- Modal Form Tugas -->
<div id="taskModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0 transition-opacity" aria-hidden="true" onclick="toggleTaskModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <form action="<?= BASEURL; ?>/task/add" method="post" id="taskForm">
                <input type="hidden" name="id" id="task_id">
                <input type="hidden" name="current_project_filter" value="<?= $_GET['project_id'] ?? '' ?>">
                <div class="p-8 md:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight" id="taskModalTitle">Buat Tugas Baru</h3>
                            <p class="text-gray-400 text-sm font-bold mt-1">Delegasikan pekerjaan ke tim Anda dengan detail jelas.</p>
                        </div>
                        <button type="button" onclick="toggleTaskModal()" class="text-gray-300 hover:text-gray-500 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Judul Tugas</label>
                            <input type="text" name="title" id="title" required class="w-full bg-white border-[1.5px] border-gray-200 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" placeholder="Contoh: Slicing Homepage">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Pilih Proyek</label>
                                <div class="select-container" id="projectSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Proyek...">
                                        <span class="select-trigger-text text-gray-400">Pilih Proyek...</span>
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <?php foreach($data['projects'] as $p) : ?>
                                            <div class="select-option" data-value="<?= $p['id']; ?>"><?= $p['name']; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="project_id" id="project_id_field">
                            </div>
                            <?php if($_SESSION['user']['role'] === 'admin') : ?>
                            <div>
                                <label for="assignee_id" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Penanggung Jawab</label>
                                <div class="select-container" id="assigneeSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Anggota...">
                                        <span class="select-trigger-text text-gray-400">Pilih Anggota...</span>
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <?php foreach($data['team'] as $m) : ?>
                                            <div class="select-option" data-value="<?= $m['id']; ?>"><?= $m['name']; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field">
                            </div>
                            <?php else : ?>
                            <div class="flex flex-col justify-end">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Penanggung Jawab</label>
                                <div class="bg-white border-[1.5px] border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-500 flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']); ?>&background=random" class="w-6 h-6 rounded-full">
                                    <span><?= $_SESSION['user']['name']; ?> (Anda)</span>
                                </div>
                                <input type="hidden" name="assignee_id" id="assignee_id_field" value="<?= $_SESSION['user']['id']; ?>">
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tag" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Label / Tag</label>
                                <input type="text" name="tag" id="tag" required class="w-full bg-white border-[1.5px] border-gray-200 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" placeholder="Contoh: Design">
                            </div>
                            <div>
                                <label for="priority_field" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Prioritas</label>
                                <div class="select-container" id="prioritySelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Prioritas...">
                                        <span class="select-trigger-text text-gray-400">Pilih Prioritas...</span>
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="tinggi">Tinggi</div>
                                        <div class="select-option" data-value="sedang">Sedang</div>
                                        <div class="select-option" data-value="biasa">Biasa</div>
                                    </div>
                                </div>
                                <input type="hidden" name="priority" id="priority_field" value="biasa">
                            </div>
                        </div>

                        <div>
                            <label for="due_date" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tenggat Waktu</label>
                            <input type="date" name="due_date" id="due_date" required class="w-full bg-white border-[1.5px] border-gray-200 rounded-2xl px-5 py-4 text-sm font-bold text-gray-700 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition cursor-pointer" onclick="this.showPicker()">
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status_field" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Status KanBan</label>
                                <div class="select-container" id="statusSelectContainer">
                                    <div class="select-trigger" data-placeholder="Pilih Status...">
                                        <span class="select-trigger-text text-gray-400">Pilih Status...</span>
                                        <svg class="select-trigger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div class="select-options">
                                        <div class="select-option" data-value="todo">To Do</div>
                                        <div class="select-option" data-value="in_progress">In Progress</div>
                                        <div class="select-option" data-value="done">Done</div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status_field" value="todo">
                            </div>
                            <div id="progress-container">
                                <label for="progress" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1 flex justify-between">
                                    Progress (%)
                                    <span id="progress-val-text" class="text-indigo-600 font-black">0%</span>
                                </label>
                                <div class="relative py-2 px-1">
                                    <input type="range" name="progress" id="progress" min="0" max="100" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-indigo-600 transition-all hover:bg-gray-200" value="0">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tag_color" id="tag_color" value="bg-blue-50 text-blue-600">
                    </div>

                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="toggleTaskModal()" class="flex-1 px-8 py-4 rounded-2xl bg-gray-100 text-gray-700 font-black text-sm uppercase tracking-widest hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="flex-1 px-8 py-4 rounded-2xl bg-indigo-600 text-white font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Simpan Tugas</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const taskModal = document.getElementById('taskModal');
    const taskForm = document.getElementById('taskForm');
    const taskModalTitle = document.getElementById('taskModalTitle');

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
        // Teleport button/filters to header
        const actionArea = document.getElementById('header-action-area');
        const actionContent = document.getElementById('task-action-content');
        if (actionArea && actionContent) {
            const innerContent = actionContent.querySelector('div');
            innerContent.classList.remove('hidden');
            actionArea.appendChild(innerContent);
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
            
            // Slider Color
            progressSlider.classList.remove('accent-red-500', 'accent-yellow-400', 'accent-green-500');
            if (val >= 71) progressSlider.classList.add('accent-green-500');
            else if (val >= 41) progressSlider.classList.add('accent-yellow-400');
            else progressSlider.classList.add('accent-red-500');

            // Text color & sync
            progressText.innerText = val + '%';
            progressText.className = 'font-black';
            if (val >= 71) progressText.classList.add('text-green-500');
            else if (val >= 41) progressText.classList.add('text-yellow-400');
            else progressText.classList.add('text-red-500');

            // Auto-sync status from progress visibility
            if (statusField.value === 'done') {
                progressContainer.classList.add('hidden');
                // Don't override slider value here if we're just syncing UI
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
    });
</script>

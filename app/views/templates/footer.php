    </main>
</div> <!-- End Main Content Area -->

<!-- REUSABLE DELETE MODAL (Design updated to match) -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 backdrop-blur-sm modal-overlay opacity-0" aria-hidden="true" onclick="toggleDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform modal-content sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-red-50">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">Konfirmasi Hapus</h3>
                        <p class="text-gray-500 text-sm" id="deleteModalText">Apakah Anda yakin ingin menghapus data ini?</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row-reverse gap-3">
                    <a id="confirmDeleteBtn" href="#" class="w-full inline-flex justify-center rounded-xl px-6 py-3 bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-100">Hapus Data</a>
                    <button type="button" onclick="toggleDeleteModal()" class="w-full inline-flex justify-center rounded-xl px-6 py-3 bg-gray-100 text-gray-700 font-bold hover:bg-gray-200 transition">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // SIDEBAR TOGGLE FOR MOBILE
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        setTimeout(() => {
            overlay.classList.toggle('opacity-0');
        }, 10);
        
        if (!sidebar.classList.contains('-translate-x-full')) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    }

    // AUTO HIDE NOTIFICATION
    function hideNotification() {
        const notification = document.getElementById('notification-bar');
        if (notification) {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => notification.remove(), 300);
        }
    }

    window.onload = () => {
        const notification = document.getElementById('notification-bar');
        if (notification) {
            setTimeout(hideNotification, 3000);
        }
    }

    // REUSABLE DROPDOWN TOGGLE
    function toggleDropdown(id) {
        // Find the specific menu for this dropdown
        const menu = document.querySelector(`#${id} .dropdown-menu`);
        
        // Close ALL other dropdowns first
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            if (m !== menu) {
                m.classList.add('hidden');
            }
        });
        
        // Toggle current menu
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // SHARED FILTER ENGINE
    function applyFilter(key, value) {
        const url = new URL(window.location.href);
        if(value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        window.location.href = url.toString();
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // REUSABLE DELETE MODAL
    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteModalText = document.getElementById('deleteModalText');

    function showDeleteModal(url, dataName) {
        deleteModalText.innerText = `Apakah Anda yakin ingin menghapus "${dataName}"? Tindakan ini tidak dapat dibatalkan.`;
        confirmDeleteBtn.setAttribute('href', url);
        
        deleteModal.classList.remove('hidden');
        // Let the browser paint the hidden change first
        setTimeout(() => {
            deleteModal.classList.add('modal-active');
        }, 10);
        document.body.classList.add('overflow-hidden');
    }

    function toggleDeleteModal() {
        deleteModal.classList.remove('modal-active');
        // Wait for animation to finish before hiding
        setTimeout(() => {
            deleteModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
</script>

</body>
</html>

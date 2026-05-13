<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abi Raja Wari | Backoffice</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #ffffff;
            --bg-secondary: #f9f9f9;
            --text-main: #000000;
            --accent-color: #000000;
            --accent-text: #ffffff;
            --border-color: #000000;
            --shadow-color: #000000;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-main);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .font-display { font-family: 'Outfit', sans-serif; }
        .shadow-premium { box-shadow: 8px 8px 0px 0px var(--shadow-color); }
        .btn-black { background: var(--accent-color); color: var(--accent-text); transition: all 0.2s ease; border: 2px solid var(--border-color); }
        .btn-black:hover { transform: translate(-2px, -2px); box-shadow: 4px 4px 0px 0px rgba(0,0,0,0.1); }
        
        .custom-scroll::-webkit-scrollbar { width: 10px; height: 10px; }
        .custom-scroll::-webkit-scrollbar-track { background: var(--bg-secondary); }
        .custom-scroll::-webkit-scrollbar-thumb { background: var(--border-color); border: 2px solid var(--bg-secondary); }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: var(--accent-color); }

        [x-cloak] { display: none !important; }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body x-data="excelApp()" x-init='initData(@json($sheets))' x-cloak>
    
    <!-- Global Loading -->
    <div x-show="saving" class="loading-overlay">
        <div class="flex flex-col items-center gap-4">
            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="3"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <p class="font-black text-xs uppercase tracking-widest">Menyimpan Ke Database...</p>
        </div>
    </div>

    <!-- Header -->
    <header class="border-b-4 border-black px-6 py-3 flex justify-between items-center bg-white shrink-0" style="border-color: var(--border-color); background-color: var(--bg-main);">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-black text-white flex items-center justify-center font-bold text-xl border-2 border-black rotate-2" style="background-color: var(--accent-color); color: var(--accent-text);">A</div>
            <div>
                <h1 class="font-display font-black tracking-tighter text-2xl uppercase leading-none">Abi Raja Wari</h1>
                <p class="text-[9px] font-bold tracking-[0.2em] uppercase opacity-40 mt-0.5">Control Center</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showSettings = true" class="p-2 border-2 border-black hover:bg-black hover:text-white transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg></button>
            <button @click="saveCurrentSheet()" class="btn-black px-5 py-2 font-black text-[10px]">
                SIMPAN MANUAL
            </button>
        </div>
    </header>

    <!-- Toolbar -->
    <div class="px-6 py-3 border-b-2 border-black flex flex-wrap items-center justify-between gap-4 shrink-0" style="border-color: var(--border-color);">
        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar">
            <button @click="addRow()" class="px-4 py-2 border-2 border-black font-black text-[9px] uppercase hover:bg-black hover:text-white transition-all">+ Baris</button>
            <button @click="addColumn()" class="px-4 py-2 border-2 border-black font-black text-[9px] uppercase hover:bg-black hover:text-white transition-all">+ Kolom</button>
            <button @click="createNewSheet()" class="px-4 py-2 border-2 border-black border-dashed font-black text-[9px] uppercase hover:bg-black hover:text-white transition-all">+ Sheet Baru</button>
            <div class="w-[1px] bg-black/10 mx-2 h-5"></div>
            <div class="relative group">
                <input type="file" @change="importExcel($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".xlsx, .xls, .csv">
                <button class="px-4 py-2 border-2 border-blue-600 text-blue-600 font-black text-[9px] uppercase">Import Excel</button>
            </div>
            <button @click="exportToExcel()" class="px-4 py-2 border-2 border-green-600 text-green-600 font-black text-[9px] uppercase">Export Excel</button>
        </div>

        <div class="flex items-center gap-4 ml-auto">
            <div class="flex items-center gap-1.5" x-show="totalPages() > 1">
                <button @click="prevPage()" :disabled="page === 1" class="p-1.5 border-2 border-black disabled:opacity-20"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="15 18 9 12 15 6"/></svg></button>
                <div class="px-3 py-1 border-2 border-black font-black text-[9px]">HALAMAN <span x-text="page"></span> / <span x-text="totalPages()"></span></div>
                <button @click="nextPage()" :disabled="page >= totalPages()" class="p-1.5 border-2 border-black disabled:opacity-20"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="9 18 15 12 9 6"/></svg></button>
            </div>
            <div class="relative w-40 md:w-56">
                <input type="text" x-model="search" placeholder="Cari data..." class="w-full pl-8 pr-4 py-2 border-2 border-black text-[10px] font-black outline-none focus:bg-black focus:text-white transition-all">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 opacity-30" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
        </div>
    </div>

    <!-- Workspace -->
    <div class="flex-1 overflow-hidden flex flex-col p-4 md:p-6" style="background-color: var(--bg-secondary);">
        <div class="flex-1 overflow-auto border-4 border-black shadow-premium bg-white custom-scroll" style="border-color: var(--border-color);">
            <table class="w-full text-left border-collapse min-w-max relative">
                <thead class="sticky top-0 z-20">
                    <tr class="bg-black text-white" style="background-color: var(--accent-color); color: var(--accent-text);">
                        <th class="px-4 py-3 w-14 text-center border-r border-white/20 text-[9px] font-black">#</th>
                        <template x-for="(header, hIndex) in currentSheet().data.headers" :key="hIndex">
                            <th class="px-5 py-3 text-[10px] uppercase tracking-widest font-black border-r border-white/20 relative group">
                                <div class="flex justify-between items-center gap-2">
                                    <span x-text="header"></span>
                                    <button @click="removeColumn(hIndex)" class="opacity-0 group-hover:opacity-100 text-red-500 hover:scale-125 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            </th>
                        </template>
                        <th class="px-4 py-3 w-16 text-center font-black text-[9px]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black">
                    <template x-for="(row, rIndex) in paginatedRows()" :key="rIndex">
                        <tr class="hover:bg-black/[0.02] group transition-all">
                            <td class="px-3 py-3 text-center bg-black/5 font-black text-[9px]" x-text="(page - 1) * perPage + rIndex + 1"></td>
                            <template x-for="(header, hIndex) in currentSheet().data.headers" :key="hIndex">
                                <td class="px-0 py-0 border-r" style="border-color: var(--border-color);">
                                    <input type="text" x-model="row[header]" @change="hasChanges = true" class="w-full h-full px-5 py-3 text-xs font-medium bg-transparent outline-none focus:bg-black focus:text-white transition-all">
                                </td>
                            </template>
                            <td class="px-3 py-3 text-center">
                                <button @click="deleteRow((page - 1) * perPage + rIndex)" class="text-black/10 hover:text-red-600 transition-all opacity-0 group-hover:opacity-100 scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Tabs -->
    <footer class="border-t-4 border-black bg-white flex items-center shrink-0 h-14 overflow-hidden" style="border-color: var(--border-color); background-color: var(--bg-main);">
        <div class="flex flex-1 overflow-x-auto hide-scrollbar h-full items-center">
            <template x-for="(sheet, index) in sheets" :key="sheet.id">
                <div class="h-full border-r-2 border-black flex items-center shrink-0 group relative" :class="activeSheetIndex === index ? 'bg-black text-white' : 'hover:bg-black/5'">
                    <button @click="activeSheetIndex = index; page = 1" class="h-full px-6 flex items-center gap-3 transition-all">
                        <span class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap" x-text="sheet.name"></span>
                    </button>
                    <button @click="renameSheet(sheet.id, index)" class="p-1.5 opacity-0 group-hover:opacity-100 hover:bg-white/20 transition-all text-[10px]" title="Rename">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button @click="deleteSheet(sheet.id, index)" class="p-1.5 opacity-0 group-hover:opacity-100 text-red-500 hover:scale-125 transition-all mr-2" x-show="sheets.length > 1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </template>
        </div>
        <div class="px-6 border-l-2 border-black h-full hidden md:flex items-center bg-black/5">
            <p class="text-[9px] font-black uppercase tracking-widest opacity-20 italic">DATABASE SYNC ACTIVE</p>
        </div>
    </footer>

    <!-- Settings -->
    <div x-show="showSettings" class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm" x-transition>
        <div class="bg-white border-4 border-black w-full max-w-sm shadow-premium p-8 relative" @click.away="showSettings = false">
            <h2 class="font-display font-black text-xl uppercase mb-6 italic underline decoration-4">Theme Configuration</h2>
            <div class="grid grid-cols-2 gap-4">
                <template x-for="(label, key) in {bgMain: 'Halaman', bgSecondary: 'Tabel', textMain: 'Teks Utama', accentColor: 'Aksen', accentText: 'Teks Aksen', borderColor: 'Garis'}">
                    <div class="flex flex-col gap-1">
                        <label class="text-[9px] font-black uppercase opacity-60" x-text="label"></label>
                        <input type="color" x-model="theme[key]" @input="updateTheme()" class="w-full h-8 cursor-pointer">
                    </div>
                </template>
            </div>
            <div class="mt-8 flex gap-2">
                <button @click="resetTheme()" class="flex-1 py-2 border-2 border-black font-black text-[10px] uppercase">Reset</button>
                <button @click="showSettings = false" class="flex-1 py-2 bg-black text-white font-black text-[10px] uppercase">Selesai</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div x-show="toast.show" class="fixed bottom-20 right-8 z-[100]" x-transition>
        <div class="bg-black text-white px-6 py-3 border-2 border-black shadow-premium font-black text-[9px] uppercase tracking-[0.2em]" x-text="toast.message"></div>
    </div>

    <script>
        function excelApp() {
            return {
                sheets: [], activeSheetIndex: 0, search: '', saving: false, hasChanges: false, toast: { show: false, message: '' }, showSettings: false,
                page: 1, perPage: 100,
                theme: { bgMain: '#ffffff', bgSecondary: '#f9f9f9', textMain: '#000000', accentColor: '#000000', accentText: '#ffffff', borderColor: '#000000', shadowColor: '#000000' },

                initData(data) {
                    // Ensure data is properly parsed
                    try {
                        this.sheets = typeof data === 'string' ? JSON.parse(data) : data;
                    } catch(e) {
                        this.sheets = data;
                    }
                    const saved = localStorage.getItem('abiraja_theme');
                    if (saved) { this.theme = JSON.parse(saved); this.updateTheme(); }
                },

                updateTheme() {
                    const root = document.documentElement;
                    Object.keys(this.theme).forEach(key => {
                        let cssVar = '--' + key.replace(/[A-Z]/g, m => "-" + m.toLowerCase());
                        root.style.setProperty(cssVar, this.theme[key]);
                    });
                    root.style.setProperty('--shadow-color', this.theme.borderColor);
                    localStorage.setItem('abiraja_theme', JSON.stringify(this.theme));
                },

                resetTheme() {
                    this.theme = { bgMain: '#ffffff', bgSecondary: '#f9f9f9', textMain: '#000000', accentColor: '#000000', accentText: '#ffffff', borderColor: '#000000', shadowColor: '#000000' };
                    this.updateTheme();
                },

                currentSheet() { return this.sheets[this.activeSheetIndex] || { data: { headers: [], rows: [] } }; },

                totalRows() {
                    const rows = this.currentSheet().data.rows || [];
                    if (!this.search) return rows.length;
                    return rows.filter(row => Object.values(row).some(val => String(val).toLowerCase().includes(this.search.toLowerCase()))).length;
                },

                totalPages() { return Math.ceil(this.totalRows() / this.perPage); },

                paginatedRows() {
                    let rows = this.currentSheet().data.rows || [];
                    if (this.search) {
                        rows = rows.filter(row => Object.values(row).some(val => String(val).toLowerCase().includes(this.search.toLowerCase())));
                    }
                    const start = (this.page - 1) * this.perPage;
                    return rows.slice(start, start + this.perPage);
                },

                nextPage() { if (this.page < this.totalPages()) this.page++; },
                prevPage() { if (this.page > 1) this.page--; },

                addRow() {
                    const h = this.currentSheet().data.headers;
                    const newRow = {}; h.forEach(col => newRow[col] = '');
                    this.currentSheet().data.rows.unshift(newRow);
                    this.hasChanges = true; this.page = 1;
                },

                deleteRow(globalIndex) {
                    if(confirm('Hapus baris ini?')) { this.currentSheet().data.rows.splice(globalIndex, 1); this.hasChanges = true; }
                },

                addColumn() {
                    let name = prompt("Nama Kolom Baru:");
                    if (name && !this.currentSheet().data.headers.includes(name)) {
                        this.currentSheet().data.headers.push(name);
                        this.currentSheet().data.rows.forEach(r => r[name] = '');
                        this.hasChanges = true;
                    }
                },

                removeColumn(i) {
                    if(confirm('Hapus kolom ini?')) {
                        const name = this.currentSheet().data.headers[i];
                        this.currentSheet().data.headers.splice(i, 1);
                        this.currentSheet().data.rows.forEach(r => delete r[name]);
                        this.hasChanges = true;
                    }
                },

                async createNewSheet() {
                    let name = prompt("Nama Sheet Baru:"); if (!name) return;
                    this.saving = true;
                    try {
                        const res = await fetch('/admin/sheets', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ name }) });
                        const result = await res.json();
                        if (result.success) { this.sheets.push(result.sheet); this.activeSheetIndex = this.sheets.length - 1; this.page = 1; this.showToast('Sheet Berhasil Dibuat!'); }
                    } finally { this.saving = false; }
                },

                async renameSheet(id, index) {
                    let newName = prompt("Nama Baru:", this.sheets[index].name);
                    if (newName && newName !== this.sheets[index].name) {
                        this.saving = true;
                        try {
                            const res = await fetch(`/admin/sheets/${id}/rename`, { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ name: newName }) });
                            if (res.ok) { this.sheets[index].name = newName; this.showToast('Rename Berhasil!'); }
                        } finally { this.saving = false; }
                    }
                },

                async deleteSheet(id, index) {
                    if(!confirm('Hapus sheet ini permanen dari database?')) return;
                    this.saving = true;
                    try {
                        const res = await fetch(`/admin/sheets/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                        if (res.ok) { this.sheets.splice(index, 1); this.activeSheetIndex = 0; this.page = 1; this.showToast('Sheet Dihapus!'); }
                    } finally { this.saving = false; }
                },

                async saveCurrentSheet() {
                    if (!this.currentSheet().id) return;
                    this.saving = true;
                    try {
                        // UN-PROXY DATA BEFORE SENDING
                        const cleanData = JSON.parse(JSON.stringify(this.currentSheet().data));
                        const res = await fetch(`/admin/sheets/${this.currentSheet().id}`, { 
                            method: 'PUT', 
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, 
                            body: JSON.stringify({ data: cleanData }) 
                        });
                        if (res.ok) { this.showToast('Tersimpan Ke Database!'); this.hasChanges = false; }
                        else { alert('Gagal menyimpan! Cek koneksi atau database.'); }
                    } catch(e) {
                        alert('Error: ' + e.message);
                    } finally { this.saving = false; }
                },

                exportToExcel() {
                    const sheet = this.currentSheet();
                    const ws = XLSX.utils.json_to_sheet(sheet.data.rows, { header: sheet.data.headers });
                    const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, sheet.name);
                    XLSX.writeFile(wb, `${sheet.name}.xlsx`);
                },

                importExcel(event) {
                    const file = event.target.files[0]; if (!file) return;
                    this.saving = true;
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, { type: 'array' });
                        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
                        const json = XLSX.utils.sheet_to_json(worksheet, { defval: "" });
                        if (json.length > 0) {
                            this.currentSheet().data.headers = Object.keys(json[0]);
                            this.currentSheet().data.rows = json;
                            this.hasChanges = true; this.page = 1;
                            await this.saveCurrentSheet();
                            this.showToast('Import & Sync Berhasil!');
                            event.target.value = "";
                        } else {
                            this.saving = false;
                            alert('File kosong atau format tidak didukung.');
                        }
                    };
                    reader.readAsArrayBuffer(file);
                },

                showToast(msg) { this.toast.message = msg; this.toast.show = true; setTimeout(() => this.toast.show = false, 3000); }
            }
        }
    </script>
</body>
</html>

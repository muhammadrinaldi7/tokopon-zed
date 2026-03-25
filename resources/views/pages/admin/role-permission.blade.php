<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

new #[Layout('layouts.admin', ['title' => 'Kelola Role & Akses - TokoPun'])] class extends Component {
    public $roles = [];
    public $permissions = [];
    public $rolePermissions = [];

    // Form for new role/permission
    public $newRoleName = '';
    public $newPermissionName = '';

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'superadmin'])) {
            return redirect('/admin/dashboard');
        }

        $this->loadData();
    }

    public function loadData()
    {
        // Don't show superadmin as its permissions are usually implicitly bypassed
        $this->roles = Role::where('name', '!=', 'superadmin')->get();
        $this->permissions = Permission::all();

        // Initialize the matrix
        $this->rolePermissions = [];
        foreach ($this->roles as $role) {
            $this->rolePermissions[$role->id] = [];
            foreach ($role->permissions as $p) {
                $this->rolePermissions[$role->id][] = $p->id;
            }
        }
    }

    public function togglePermission($roleId, $permissionId)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findById($permissionId);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        $this->loadData();
        $this->dispatch('admin-alert', type: 'success', message: 'Hak akses berhasil diperbarui!');
    }

    public function confirmDeleteRole($roleId): void
    {
        $this->dispatch('show-confirm', title: 'Hapus Role', message: 'Apakah Anda yakin ingin menghapus role ini?', confirmParams: [$roleId], confirmEvent: 'do-delete-role', type: 'warning', confirmText: 'Ya, Hapus', cancelText: 'Batal');
    }

    public function createRole()
    {
        $this->validate(['newRoleName' => 'required|string|min:2|unique:roles,name']);

        Role::create(['name' => strtolower($this->newRoleName), 'guard_name' => 'web']);

        $this->newRoleName = '';
        $this->loadData();
        $this->dispatch('admin-alert', type: 'success', message: 'Role baru berhasil ditambahkan!');
    }

    public function createPermission()
    {
        $this->validate(['newPermissionName' => 'required|string|min:2|unique:permissions,name']);

        Permission::create(['name' => strtolower(str_replace(' ', '_', $this->newPermissionName)), 'guard_name' => 'web']);

        $this->newPermissionName = '';
        $this->loadData();
        $this->dispatch('admin-alert', type: 'success', message: 'Permission baru berhasil ditambahkan!');
    }

    #[On('do-delete-role')]
    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        if (!in_array($role->name, ['admin', 'cs'])) {
            $role->delete();
            $this->loadData();
            $this->dispatch('admin-alert', type: 'success', message: 'Role berhasil dihapus!');
        } else {
            $this->dispatch('admin-alert', type: 'error', message: 'Role inti sistem (' . $role->name . ') tidak bisa dihapus!');
        }
    }
};
?>

<div class="px-6 py-8 w-full max-w-7xl mx-auto" x-data="{ alert: null, activeTab: 'matrix' }"
    @admin-alert.window="
        alert = $event.detail;
        setTimeout(() => alert = null, 3000);
    ">

    <!-- Alpine Notification Setup -->
    <div x-show="alert" x-transition.opacity.duration.300ms style="display: none;"
        class="mb-6 px-4 py-3 rounded-xl border flex items-center gap-3 text-sm font-medium shadow-sm transition-all"
        :class="alert?.type === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-800' :
            'bg-red-50 border-red-100 text-red-800'">
        <svg x-show="alert?.type === 'success'" class="w-5 h-5 text-emerald-500 shrink-0" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg x-show="alert?.type === 'error'" class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span x-text="alert?.message"></span>
    </div>

    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Role & Akses</h1>
            <p class="text-sm text-gray-500 mt-1">Tentukan hak akses spesifik apa saja yang diizinkan untuk setiap
                kelompok entitas pengguna.</p>
        </div>

        <!-- Tabs -->
        <div
            class="flex p-1 bg-gray-100/80 rounded-xl border border-gray-200 max-w-full overflow-x-auto shrink-0 w-max">
            <button @click="activeTab = 'matrix'"
                :class="activeTab === 'matrix' ? 'bg-white text-gray-900 shadow-xs border-gray-200' :
                    'text-gray-500 hover:text-gray-800 border-transparent'"
                class="px-5 py-2 text-sm font-semibold rounded-lg transition-all border">Matriks Izin</button>
            <button @click="activeTab = 'add'"
                :class="activeTab === 'add' ? 'bg-white text-gray-900 shadow-xs border-gray-200' :
                    'text-gray-500 hover:text-gray-800 border-transparent'"
                class="px-5 py-2 text-sm font-semibold rounded-lg transition-all border">Tambah Data</button>
        </div>
    </div>

    <!-- Matrix Tab -->
    <div x-show="activeTab === 'matrix'" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden"
        x-transition.opacity>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-gray-700 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-64 border-r border-gray-100">Daftar Permission (Hak Akses)</th>
                        @foreach ($roles as $role)
                            <th
                                class="px-6 py-4 text-center border-r border-gray-100 last:border-0 capitalize min-w-[140px]">
                                <div class="flex items-center justify-center gap-2">
                                    <span
                                        class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-md text-xs">{{ $role->name }}</span>
                                    @if (!in_array($role->name, ['admin', 'cs']))
                                        <button wire:click="confirmDeleteRole({{ $role->id }})"
                                            class="text-red-400 hover:text-red-600 cursor-pointer" title="Hapus Role">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-gray-50/30 transition-colors" wire:key="perm-{{ $permission->id }}">
                            <td
                                class="px-6 py-4 font-medium text-gray-900 border-r border-gray-100 border-b  bg-gray-50/20">
                                <span
                                    class="font-mono px-2 py-1 text-[13px] bg-white border border-gray-200 text-gray-600 rounded drop-shadow-xs">{{ $permission->name }}</span>
                            </td>
                            @foreach ($roles as $role)
                                <td class="px-6 py-4 text-center border-r border-gray-100 last:border-0 border-b ">
                                    @php
                                        $hasPerm = in_array($permission->id, $rolePermissions[$role->id] ?? []);
                                    @endphp
                                    <label class="relative inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" style="display: none;"
                                            wire:click="togglePermission({{ $role->id }}, {{ $permission->id }})"
                                            {{ $hasPerm ? 'checked' : '' }}>
                                        <!-- Container Background -->
                                        <div class="w-11 h-6 rounded-full transition-colors shadow-inner"
                                            style="background-color: {{ $hasPerm ? '#00bfa5' : '#e5e7eb' }};"></div>
                                        <!-- Dot Indicator -->
                                        <div class="absolute left-[2px] top-[2px] bg-white border rounded-full h-5 w-5 transition-transform duration-200 pointer-events-none drop-shadow-sm"
                                            style="{{ $hasPerm ? 'transform: translateX(100%); border-color: transparent;' : 'border-color: #e5e7eb;' }}">
                                        </div>
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($roles) + 1 }}" class="px-6 py-16 text-center text-gray-400">
                                Belum ada permission tersimpan di tabel database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div
            class="bg-blue-50/70 p-4 border-t border-blue-100 text-xs text-blue-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Perubahan permission otomatis tersimpan secara real-time saat Anda mengatur tuas (toggle switch). Role
            'superadmin' tidak dimunculkan.
        </div>
    </div>

    <!-- Add Form Tab -->
    <div x-show="activeTab === 'add'" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;"
        x-transition.opacity>
        <!-- Add Role Form -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div
                    class="w-12 h-12 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Tambah Master Role Baru</h3>
                <p class="text-sm text-gray-500 mb-6">Role merupakan sebuah jabatan atau grup untuk mengelompokkan
                    pengguna. (Contoh: manager, supervisor, agen).</p>
            </div>

            <form wire:submit="createRole">
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Role</label>
                    <input type="text" wire:model="newRoleName"
                        placeholder="Ketik nama role huruf kecil (misal: kurir)..." required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition-all">
                    @error('newRoleName')
                        <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full px-5 py-3 text-sm font-bold text-white bg-[#4E44DB] rounded-xl hover:bg-[#3c34af] shadow-md shadow-[#4E44DB]/20 transition-all flex items-center justify-center gap-2 cursor-pointer border border-[#4E44DB]">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Role
                </button>
            </form>
        </div>

        <!-- Add Permission Form -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div
                    class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Tambah Permission Sistem</h3>
                <p class="text-sm text-gray-500 mb-6">Permission adalah variabel titik akses dalam code aplikasi
                    (Contoh: edit_product, delete_receipt).</p>
            </div>

            <form wire:submit="createPermission">
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Permission (Gunakan *Snake
                        Case*)</label>
                    <input type="text" wire:model="newPermissionName" placeholder="Contoh: verify_transaction..."
                        required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#00bfa5]/20 focus:border-[#00bfa5] transition-all">
                    @error('newPermissionName')
                        <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full px-5 py-3 text-sm font-bold text-white bg-[#00bfa5] rounded-xl hover:bg-[#00a68f] shadow-md shadow-[#00bfa5]/20 transition-all flex items-center justify-center gap-2 cursor-pointer border border-[#00bfa5]">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Daftarkan Permission
                </button>
            </form>
        </div>
    </div>
</div>

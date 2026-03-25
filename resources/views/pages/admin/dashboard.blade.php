<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new
#[Layout('layouts.admin', ['title' => 'Dashboard - TokoPun'])]
class extends Component {
    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->roles->count() === 0 || $user->hasRole('user')) {
             return redirect('/');
        }
    }
};
?>

<div>
    {{-- Banner --}}
    <div class="bg-linear-to-r from-[#4E44DB] to-[#766bf2] rounded-4xl p-8 text-white mb-8 flex flex-col md:flex-row items-start md:items-center justify-between shadow-xl shadow-[#4E44DB]/20 gap-6">
        <div>
            <h2 class="text-2xl font-bold mb-2">Upgrade ke iPhone 15 Pro?</h2>
            <p class="text-indigo-100 text-sm">Khusus member Gold: Diskon Trade-In hingga Rp 1.500.000!</p>
        </div>
        <button class="bg-amber-400 hover:bg-amber-500 text-amber-950 font-bold px-8 py-3.5 rounded-full transition-colors shadow-md whitespace-nowrap text-sm cursor-pointer">
            Klaim Voucher
        </button>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        {{-- Pesanan Card --}}
        <div class="bg-white rounded-3xl p-7 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-800 text-sm">Pesanan: iPhone 15 Baru</h3>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full tracking-wide">Dikirim</span>
            </div>
            
            <p class="text-xs text-gray-400 mb-5 font-medium">No. Resi: JNE123456789</p>
            
            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-5 overflow-hidden">
                <div class="bg-[#4E44DB] h-1.5 rounded-full w-3/4"></div>
            </div>
            
            <p class="text-[11px] text-gray-500 font-medium">Kurir sedang menuju lokasi Anda.</p>
        </div>

        {{-- Servis Card --}}
        <div class="bg-white rounded-3xl p-7 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-bold text-gray-800 text-sm">Servis: Ganti LCD iPhone 12</h3>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full tracking-wide">Pengerjaan</span>
            </div>
            
            <p class="text-xs text-gray-400 mb-5 font-medium">ID Servis: SRV-0098</p>
            
            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-5 overflow-hidden">
                <div class="bg-amber-400 h-1.5 rounded-full w-1/2"></div>
            </div>
            
            <p class="text-[11px] text-gray-500 font-medium">Teknisi sedang memasang komponen baru.</p>
        </div>
    </div>

    {{-- Tips Section --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Tips & Update Gadget</h2>
        <a href="#" class="text-xs font-bold text-[#4E44DB] hover:text-blue-700 transition-colors">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Tip 1 --}}
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer group">
            <div class="h-48 bg-linear-to-br from-blue-100 to-indigo-50 relative overflow-hidden">
                <img src="https://images.unsplash.com/photo-1605236453806-6ff36851218e?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="iPhone">
            </div>
            <div class="p-6">
                <h3 class="font-bold text-gray-800 text-[13px] leading-relaxed mb-4">5 Cara Menjaga Battery Health iPhone Tetap 100%</h3>
                <p class="text-[11px] font-bold text-[#4E44DB] flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Baca Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </p>
            </div>
        </div>

        {{-- Tip 2 --}}
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer group">
            <div class="h-48 bg-gray-900 relative overflow-hidden">
                <img src="https://images.unsplash.com/photo-1591815152092-271d996beddf?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90" alt="iPhone Lockscreen">
            </div>
            <div class="p-6">
                <h3 class="font-bold text-gray-800 text-[13px] leading-relaxed mb-4">Awas! Kenali Ciri-ciri HP Bekas Rekondisi</h3>
                <p class="text-[11px] font-bold text-[#4E44DB] flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Baca Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </p>
            </div>
        </div>

        {{-- Tip 3 --}}
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer group">
            <div class="h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1603898037225-b6fb1fd9b009?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="iPhone Frame">
            </div>
            <div class="p-6">
                <h3 class="font-bold text-gray-800 text-[13px] leading-relaxed mb-4">Berapa Lama Garansi Servis di PhoneFlow?</h3>
                <p class="text-[11px] font-bold text-[#4E44DB] flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                    Baca Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </p>
            </div>
        </div>
    </div>
</div>

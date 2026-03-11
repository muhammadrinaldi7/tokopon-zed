<?php
// ⚡ footer

use Livewire\Component;

new class extends Component {
    //
};

?>

<footer class="bg-slate-900 text-slate-400 mt-auto">
    <div class="max-w-7xl mx-auto px-6 pt-14">
        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-12">
            {{-- Brand Column --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="/" wire:navigate class="inline-block mb-4">
                    <span
                        class="text-2xl font-bold bg-linear-to-r from-[#0097FF] via-[#4E44DB] to-[#013559] bg-clip-text text-transparent">Tokopon</span>
                </a>
                <p class="text-sm leading-relaxed text-slate-400 mb-6">
                    Platform jual beli smartphone terpercaya. Beli HP baru, jual HP bekas, tukar tambah, dan layanan
                    reparasi — semua dalam satu tempat.
                </p>
                <div class="flex gap-3">
                    {{-- Instagram --}}
                    <a href="#" aria-label="Instagram"
                        class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/5 text-slate-400 transition hover:bg-blue-500 hover:text-white hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    {{-- Facebook --}}
                    <a href="#" aria-label="Facebook"
                        class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/5 text-slate-400 transition hover:bg-blue-500 hover:text-white hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    {{-- WhatsApp --}}
                    <a href="#" aria-label="WhatsApp"
                        class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/5 text-slate-400 transition hover:bg-blue-500 hover:text-white hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                            </path>
                        </svg>
                    </a>
                    {{-- TikTok --}}
                    <a href="#" aria-label="TikTok"
                        class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/5 text-slate-400 transition hover:bg-blue-500 hover:text-white hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Services Column --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wide mb-5">Layanan</h3>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Beli
                            HP Baru</a></li>
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Jual
                            HP Bekas</a></li>
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Tukar
                            Tambah</a></li>
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Reparasi
                            HP</a></li>
                </ul>
            </div>

            {{-- Support Column --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wide mb-5">Bantuan</h3>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">FAQ</a>
                    </li>
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Cara
                            Pemesanan</a></li>
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Kebijakan
                            Privasi</a></li>
                    <li><a href="#"
                            class="text-sm text-slate-400 transition hover:text-blue-400 hover:translate-x-1 inline-block">Syarat
                            & Ketentuan</a></li>
                </ul>
            </div>

            {{-- Contact Column --}}
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wide mb-5">Kontak</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3 text-sm text-slate-400">
                        <svg class="shrink-0 mt-0.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>Jl. Contoh No. 123, Jakarta</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-400">
                        <svg class="shrink-0 mt-0.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                        <span>+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm text-slate-400">
                        <svg class="shrink-0 mt-0.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>info@tokopun.com</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-white/10 py-6 text-center">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} <strong class="text-slate-400">TokoPun</strong>. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Models\UserProfile as ProfileModel;
use App\Models\UserAddress;
use App\Models\UserBankAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app', ['title' => 'Profil Pengguna'])]
class UserProfile extends Component
{
    use WithFileUploads;

    public $activeTab = 'profile';

    // Data Pribadi
    public $full_name, $phone_number, $birth_date, $gender;

    // Identitas
    public $identity, $npwp, $ktp_photo;
    public $current_ktp_photo_url;

    // Alamat
    public $address_id;
    public $recipient_name, $address_phone, $full_address, $postal_code;
    public $province_id, $city_id, $district_id; // Jika nantinya pakai RajaOngkir/Biteship

    // Bank
    public $bank_account_id;
    public $bank_name, $account_number, $account_name;

    public function mount()
    {
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $user = Auth::user();

        // 1. Profil Dasar
        if ($user->profile) {
            $this->full_name = $user->profile->full_name;
            $this->phone_number = $user->profile->phone_number;
            $this->birth_date = Carbon::parse($user->profile->birth_date)->format('Y-m-d');
            $this->gender = $user->profile->gender;
        }

        // 2. Identitas
        $this->identity = $user->identity;
        $this->npwp = $user->npwp;
        $ktpMedia = $user->getFirstMedia('ktp_photo');
        $this->current_ktp_photo_url = $ktpMedia ? $ktpMedia->getUrl() : null;

        // 3. Alamat (Ambil alamat primary atau pertama)
        $address = $user->addresses()->where('is_primary', true)->first() ?? $user->addresses()->first();
        if ($address) {
            $this->address_id = $address->id;
            $this->recipient_name = $address->recipient_name;
            $this->address_phone = $address->phone_number;
            $this->full_address = $address->full_address;
            $this->postal_code = $address->postal_code;
            $this->province_id = $address->province_id;
            $this->city_id = $address->city_id;
            $this->district_id = $address->district_id;
        }

        // 4. Rekening Bank (Ambil primary atau pertama)
        $bank = $user->bankAccounts()->where('is_primary', true)->first() ?? $user->bankAccounts()->first();
        if ($bank) {
            $this->bank_account_id = $bank->id;
            $this->bank_name = $bank->bank_name;
            $this->account_number = $bank->account_number;
            $this->account_name = $bank->account_name;
        }
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function saveProfile()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
        ]);

        ProfileModel::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'birth_date' => $this->birth_date,
                'gender' => $this->gender,
            ]
        );

        $this->dispatch('show-toast', type: 'success', message: 'Profil dasar berhasil disimpan.');
    }

    public function saveIdentity()
    {
        $this->validate([
            'identity' => 'required|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'ktp_photo' => 'nullable|image|max:5120', // Maks 5MB
        ]);

        $user = Auth::user();
        $user->identity = $this->identity;
        $user->npwp = $this->npwp;
        $user->save();

        if ($this->ktp_photo) {
            $user->clearMediaCollection('ktp_photo');
            $user->addMedia($this->ktp_photo->getRealPath())
                ->usingFileName($this->ktp_photo->getClientOriginalName())
                ->toMediaCollection('ktp_photo');

            $this->current_ktp_photo_url = $user->getFirstMediaUrl('ktp_photo');
            $this->ktp_photo = null; // Reset file input
        }

        $this->dispatch('show-toast', type: 'success', message: 'Identitas berhasil disimpan.');
    }

    public function saveAddress()
    {
        $this->validate([
            'recipient_name' => 'required|string|max:255',
            'address_phone' => 'required|string|max:20',
            'full_address' => 'required|string',
            'postal_code' => 'required|string|max:10',
        ]);

        UserAddress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'id' => $this->address_id ?? null,
            ],
            [
                'label_address' => 'Utama',
                'recipient_name' => $this->recipient_name,
                'phone_number' => $this->address_phone,
                'full_address' => $this->full_address,
                'postal_code' => $this->postal_code,
                'province_id' => $this->province_id,
                'city_id' => $this->city_id,
                'district_id' => $this->district_id,
                'is_primary' => true,
            ]
        );

        $this->dispatch('show-toast', type: 'success', message: 'Alamat berhasil disimpan.');
    }

    public function saveBank()
    {
        $this->validate([
            'bank_name' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ]);

        UserBankAccount::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'id' => $this->bank_account_id ?? null,
            ],
            [
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_name' => $this->account_name,
                'is_primary' => true,
            ]
        );

        $this->dispatch('show-toast', type: 'success', message: 'Rekening Bank berhasil disimpan.');
    }

    // Helper untuk progress bar
    public function getBuyerProgressProperty()
    {
        $score = 0;
        if (!empty($this->full_name) && !empty($this->phone_number)) $score += 34; // Profil
        if (!empty($this->identity)) $score += 33; // Identitas
        if (!empty($this->full_address) && !empty($this->postal_code)) $score += 33; // Alamat
        return min(100, $score);
    }

    public function getSellerProgressProperty()
    {
        $score = 0;
        if (!empty($this->full_name) && !empty($this->phone_number)) $score += 20; // Profil
        if (!empty($this->identity)) $score += 20; // NIK
        if (!empty($this->current_ktp_photo_url) || !empty($this->ktp_photo)) $score += 20; // KTP
        if (!empty($this->npwp)) $score += 20; // NPWP
        if (!empty($this->bank_name) && !empty($this->account_number)) $score += 20; // Bank
        return min(100, $score);
    }

    public function render()
    {
        return view('livewire.pages.user-profile', [
            'buyerProgress' => $this->buyerProgress,
            'sellerProgress' => $this->sellerProgress,
        ]);
    }
}

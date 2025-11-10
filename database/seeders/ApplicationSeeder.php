<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user to set as creator
        $adminUser = User::where("email", "pn_tanahgrogot@gmail.com")->first();

        if (!$adminUser) {
            $this->command->warn(
                "Admin user not found. Please run UserSeeder first.",
            );
            return;
        }

        // Temporarily authenticate as admin for created_by
        Auth::login($adminUser);

        // Kesekretariatan Applications
        Application::create([
            "name" => "SIPP (Sistem Informasi Penelusuran Perkara)",
            "url" => "http://app.pn/SIPP311",
            "description" =>
                "Sistem untuk penelusuran informasi dan register perkara",
            "category" => "kepaniteraan",
        ]);

        Application::create([
            "name" =>
                "SIMANJA (Sistem Informasi Manajemen Kepaniteraan dan Kesekretariatan)",
            "url" => "https://simanja.example.com",
            "description" =>
                "Sistem manajemen administrasi kepaniteraan dan kesekretariatan pengadilan",
            "category" => "kesekretariatan",
        ]);

        Application::create([
            "name" => "SIKEP (Sistem Informasi Kepegawaian)",
            "url" => "https://sikep.mahkamahagung.go.id",
            "description" =>
                "Sistem informasi untuk pengelolaan data kepegawaian dan SDM Mahkamah Agung Republik Indonesia",
            "category" => "kesekretariatan",
        ]);

        Application::create([
            "name" => "SIMA (Sistem Informasi Manajemen Aset)",
            "url" => "https://sima.example.com",
            "description" =>
                "Sistem untuk pengelolaan dan monitoring aset barang milik negara",
            "category" => "kesekretariatan",
        ]);

        // Kepaniteraan Applications
        Application::create([
            "name" => "SIWAS (Sistem Informasi Pengawasan)",
            "url" => "https://siwas.example.com",
            "description" =>
                "Sistem informasi untuk pengawasan dan monitoring kinerja pengadilan",
            "category" => "kepaniteraan",
        ]);

        Application::create([
            "name" => "e-Court",
            "url" => "https://ecourt.example.com",
            "description" =>
                "Sistem administrasi perkara elektronik untuk pendaftaran dan pengelolaan perkara",
            "category" => "kepaniteraan",
        ]);

        Application::create([
            "name" => "e-Litigation",
            "url" => "https://elitigation.example.com",
            "description" =>
                "Sistem persidangan elektronik untuk persidangan jarak jauh",
            "category" => "kepaniteraan",
        ]);

        Application::create([
            "name" =>
                "SIPP Wasmat (Sistem Informasi Penelusuran Perkara Pengawasan Masyarakat)",
            "url" => "https://sippwasmat.example.com",
            "description" =>
                "Sistem untuk pengawasan dan monitoring perkara oleh masyarakat",
            "category" => "kepaniteraan",
        ]);

        Auth::logout();

        $this->command->info("Applications seeded successfully!");
    }
}

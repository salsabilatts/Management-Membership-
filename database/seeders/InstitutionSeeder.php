<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        $institutions = [
            [
                'name' => 'SDN 01 Jakarta Pusat',
                'type' => 'sekolah',
                'address' => 'Jl. Pendidikan No. 123, Jakarta Pusat',
                'phone' => '021-1234567',
                'email' => 'sdn01@school.com',
                'contact_person' => 'Kepala Sekolah',
                'status' => 'active',
                'programs' => json_encode(['PIP', 'KIP']),
            ],
            [
                'name' => 'Dinas Kesehatan Kota Jakarta',
                'type' => 'dinas_kesehatan',
                'address' => 'Jl. Kesehatan No. 456, Jakarta',
                'phone' => '021-7654321',
                'email' => 'dinkes@jakarta.go.id',
                'contact_person' => 'Kepala Dinas',
                'status' => 'active',
                'programs' => json_encode(['Medical Check-Up', 'Vaksinasi']),
            ],
            [
                'name' => 'LBH Jakarta',
                'type' => 'lbh',
                'address' => 'Jl. Hukum No. 789, Jakarta',
                'phone' => '021-9876543',
                'email' => 'info@lbhjakarta.org',
                'contact_person' => 'Direktur LBH',
                'status' => 'active',
                'programs' => json_encode(['Bantuan Hukum Gratis', 'Konsultasi Hukum']),
            ],
            [
                'name' => 'Yayasan Sosial Peduli',
                'type' => 'lembaga_sosial',
                'address' => 'Jl. Sosial No. 321, Jakarta',
                'phone' => '021-5555555',
                'email' => 'info@yayanpeduli.org',
                'contact_person' => 'Ketua Yayasan',
                'status' => 'active',
                'programs' => json_encode(['Bantuan Bencana', 'Kegiatan Sosial']),
            ],
        ];

        foreach ($institutions as $institution) {
            Institution::create($institution);
        }
    }
}

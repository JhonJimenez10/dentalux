<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // ── Dayana Bermeo ──
            [
                'bank_name'      => 'Banco Pichincha',
                'account_type'   => 'Cuenta de Ahorros',
                'account_number' => '2209XXXXXXXX',
                'owner_name'     => 'Dayana Bermeo',
                'owner_id'       => '0XXXXXXXXX',
                'phone'          => '09XXXXXXXX',
                'active'         => true,
                'order'          => 1,
            ],
            [
                'bank_name'      => 'Banco Guayaquil',
                'account_type'   => 'Cuenta de Ahorros',
                'account_number' => '0XXXXXXXXX',
                'owner_name'     => 'Dayana Bermeo',
                'owner_id'       => '0XXXXXXXXX',
                'phone'          => '09XXXXXXXX',
                'active'         => true,
                'order'          => 2,
            ],
            // ── Flor Bravo ──
            [
                'bank_name'      => 'Cooperativa JEP',
                'account_type'   => 'Cuenta de Ahorros',
                'account_number' => '0XXXXXXXXX',
                'owner_name'     => 'Flor Bravo',
                'owner_id'       => '0XXXXXXXXX',
                'phone'          => '09XXXXXXXX',
                'active'         => true,
                'order'          => 3,
            ],
            [
                'bank_name'      => 'Banco Pichincha',
                'account_type'   => 'Cuenta Corriente',
                'account_number' => '0XXXXXXXXX',
                'owner_name'     => 'Flor Bravo',
                'owner_id'       => '0XXXXXXXXX',
                'phone'          => '09XXXXXXXX',
                'active'         => true,
                'order'          => 4,
            ],
        ];

        foreach($accounts as $acc){
            BankAccount::updateOrCreate(
                ['account_number' => $acc['account_number']],
                $acc
            );
        }
    }
}
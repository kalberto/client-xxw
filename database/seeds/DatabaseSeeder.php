<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissoesTableSeeder::class);
	    $this->call(ModuloAdministradorTableSeeder::class);
	    $this->call(ModAdmPermissaoTableSeeder::class);
	    $this->call(PerfisTableSeeder::class);
        $this->call(AdministradorTableSeeder::class);
		$this->call(MediaMakerSeeder::class);
		$this->call(ConfiguracaoTableSeeder::class);
		$this->call(PerfisUsuariosTableSeeder::class);
        $this->call(CategoriasTableSeeder::class);
        $this->call(AssuntosTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(VPCTableSeeder::class);

    }
}

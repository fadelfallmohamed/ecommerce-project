<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {email? : The email of the admin user} {--password= : The new password (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the password for an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: 'admin@example.com';
        $password = $this->option('password') ?: 'password123'; // Mot de passe par défaut

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Aucun utilisateur trouvé avec l'email: {$email}");
            return 1;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Mot de passe réinitialisé avec succès pour l'utilisateur: {$user->email}");
        $this->line("Nouveau mot de passe: {$password}");

        return 0;
    }
}

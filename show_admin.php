<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illware_Console_Kernel::class);

try {
    $user = \App\Models\User::where('is_admin', 1)->first();
    
    if ($user) {
        echo "Admin User Found:\n";
        echo "ID: " . $user->id . "\n";
        echo "Name: " . $user->name . "\n";
        echo "Email: " . $user->email . "\n";
        echo "Password: " . $user->password . "\n";
    } else {
        echo "No admin user found. Creating one...\n";
        $admin = new \App\Models\User();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('admin123');
        $admin->is_admin = true;
        $admin->save();
        
        echo "Admin user created successfully!\n";
        echo "Email: admin@example.com\n";
        echo "Password: admin123\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trying alternative method...\n";
    
    // Alternative method using DB facade
    try {
        $users = \Illuminate\Support\Facades\DB::table('users')
            ->where('is_admin', 1)
            ->orWhere('is_admin', true)
            ->get();
            
        if ($users->isNotEmpty()) {
            echo "Admin Users Found:\n";
            foreach ($users as $user) {
                echo "ID: " . $user->id . "\n";
                echo "Name: " . $user->name . "\n";
                echo "Email: " . $user->email . "\n\n";
            }
        } else {
            echo "No admin users found in the database.\n";
        }
    } catch (Exception $e) {
        echo "Could not access the database. Make sure your .env file is properly configured.\n";
        echo "Error details: " . $e->getMessage() . "\n";
    }
}
?>

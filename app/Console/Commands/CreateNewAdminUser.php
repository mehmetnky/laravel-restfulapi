<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CreateNewAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:new {name} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user with given credentials.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $isUserExists = User::where('name', $name)->orWhere('email', $email)->exists();

        if ($isUserExists) {
            $this->error('This user already exists!');

            return;
        }

        $roleName = $this->choice(
            'Select a role for the user',
            Role::pluck('name')->toArray()
        );

        $password = $this->generatePassword();

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->assignRole($roleName);

        if($user->save()){
	        $this->newLine();
            $this->line('Created a new admin user for '.env('APP_NAME').'!');
            $this->line('Name: '.$name);
            $this->line('Email: '.$email);
            $this->line('Password: '.$password);
	        $this->newLine();
        }else{
            $this->error('An unexpected error occured!');
        }

        return;
    }

    private function generatePassword(int $length = 13) : string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        
        $str = '';
        $max = strlen($chars) - 1;
        
        for ($i=0; $i < $length; $i++)
            $str .= $chars[random_int(0, $max)];
        
        return $str;
    }
}

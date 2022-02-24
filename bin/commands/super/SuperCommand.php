<?php

use App\Auth\Guard\Role;
use App\Auth\Guard\User;
use App\Bootstrap\Path;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuperCommand extends Command
{
    protected $signature = 'install:super';
    protected $description = 'Create super folder , install database for it.';

    /**
     * @throws Throwable
     * @throws Throwable
     */
    public function handle(): void
    {
        if (!Storage::exists(Path::root('super'))) {
            $zip = new ZipArchive;
            if ($zip->open(Path::templates('zips/super.zip')) === TRUE) {
                $zip->extractTo(Path::root(''));
                $zip->close();
                $this->info('Super created successfully, happy to see you.');

                // create schema
                DB::connection()->unprepared(file_get_contents(Path::bin('commands/super/super_schema.sql')));
                $this->info('Super schema install successfully.');
                // install data
                DB::connection()->unprepared(file_get_contents(Path::bin('commands/super/super_init.sql')));
                $this->info('Super data install successfully.');

                // install main user and role
                $params = [
                    'name' => 'admin',
                    'slug' => 'admin',
                    'languages' => [
                        ['language_name' => 'ar', 'name' => 'مدير'],
                        ['language_name' => 'en', 'name' => 'Admin'],
                    ]
                ];

                $params = json_decode(json_encode($params, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
                $role_id = Role::add($params);

                $user_params = [
                    'email' => 'admin@ionzile.com',
                    'first_name' => 'Ion',
                    'last_name' => 'Zile',
                    'status' => 1,
                    'mobile' => '011',
                    'mobile_2' => null,
                    'password' => '$l^w1f1HozlFo~OKeM',
                    'address' => '',
                    'notes' => '',
                    'image' => null,
                    'image_name' => null,
                    'role_id' => $role_id
                ];
                User::add((object)$user_params,true);

                $this->info('Super main role added successfully.');

            } else {
                $this->error('Super failed.');
            }
        } else {
            $this->comment('Super already installed.');
        }
    }
}

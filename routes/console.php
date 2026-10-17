<?php

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('staff:create {role : admin or teacher}', function (string $role) {
    if (! in_array($role, ['admin', 'teacher'], true)) {
        $this->error('El rol debe ser admin o teacher.');

        return 1;
    }

    $name = $this->ask('Nombre completo');
    $email = $this->ask('Correo institucional');
    $password = $this->secret('Contraseña (mínimo 8 caracteres)');

    $validator = Validator::make(
        ['name' => $name, 'email' => $email, 'password' => $password],
        [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]
    );

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    DB::transaction(function () use ($role, $name, $email, $password) {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        if ($role === 'teacher') {
            $specialty = $this->ask('Especialidad');
            $documentNumber = $this->ask('Número de documento');

            Validator::make(
                ['specialty' => $specialty, 'document_number' => $documentNumber],
                [
                    'specialty' => ['required', 'string', 'max:255'],
                    'document_number' => ['required', 'string', 'max:255', 'unique:teachers,document_number'],
                ]
            )->validate();

            Teacher::create([
                'user_id' => $user->id,
                'specialty' => $specialty,
                'document_number' => $documentNumber,
            ]);
        }
    });

    $this->info('Cuenta de '.$role.' creada correctamente.');

    return 0;
})->purpose('Crear una cuenta institucional de administración o docente');

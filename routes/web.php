<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Salud de la aplicación y de la base de datos
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok']);
    } catch (\Throwable $e) {
        Log::error('Base de datos no disponible', ['error' => $e->getMessage()]);
        return response()->json(['status' => 'error'], 503);
    }
});

// Consulta
Route::get('/usuarios', function () {
    Log::info('Consulta de usuarios');
    return User::all();
});

// Registro
Route::get('/usuarios/nuevo', function () {
    $u = User::create([
        'name' => 'Usuario ' . rand(1, 999),
        'email' => Str::random(8) . '@prueba.com',
        'password' => bcrypt('12345678'),
    ]);
    Log::info('Usuario registrado', ['id' => $u->id]);
    return $u;
});

// Error controlado
Route::get('/error-prueba', function () {
    throw new \Exception('Error de prueba para monitoreo');
});
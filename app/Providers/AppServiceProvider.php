<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot()
    {
        $map = [
            'الاسم' => 'name',
            'الجنس' => 'gender',
            'تاريخ_الميلاد' => 'date_of_birth',
            'البريد_الإلكتروني' => 'email',
            'الايميل' => 'email',
            'رقم_الهاتف' => 'phone',
            'الهاتف' => 'phone',
            'المدينة' => 'city',
            'العنوان' => 'address',
            'المعدل' => 'gpa',
            'التخصص' => 'major',

            'name' => 'name',
            'gender' => 'gender',
            'date_of_birth' => 'date_of_birth',
            'date of birth' => 'date_of_birth',
            'email' => 'email',
            'phone' => 'phone',
            'city' => 'city',
            'address' => 'address',
            'gpa' => 'gpa',
            'g_p_a' => 'gpa',
            'major' => 'major',
        ];

        HeadingRowFormatter::extend('custom', function ($value) use ($map) {
            $key = Str::lower(str_replace(' ', '_', trim($value)));
            return $map[$key] ?? $key;
        });

        HeadingRowFormatter::default('custom');
    }
}

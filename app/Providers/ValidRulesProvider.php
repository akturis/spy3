<?php
namespace App\Providers;

use App\Providers\ValidRules;
use Illuminate\Support\ServiceProvider;

class ValidRulesProvider extends ServiceProvider{

    public function boot()
    {
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new ValidRules($translator, $data, $rules, $messages);
        });
    }

    public function register()
    {
    }
}
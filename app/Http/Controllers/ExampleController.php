<?php

declare(strict_types=1);

namespace App\Plugin\Core\Http\Controllers;

use App\Plugin\Core\Libraries\Datagrid\Table;
use App\Plugin\Core\Libraries\Panel\Entry;
use Faker\Factory;
use Illuminate\Support\Facades\Cache;

class ExampleController extends Controller
{
    // Importante:
    // Sempre que sobrescrever o construtor é preciso invocar 
    // a sobrecarga, pois contém implementações originais do módulo
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    public function app()
    {
        //$this->changeTheme('core');
        // $this->breadCrumb()->append(new Entry('Grade de Dados Legal'));


        // return vue('core::example');

        return view('core::app');
    }

    public function page()
    {
        sleep(3);
        return vue('core::example');
    }

    public function indexDataProvider()
    {
        $data = Cache::remember('grid-data', 3600, function () {

            $faker = Factory::create('pt_BR');
            return array_map(fn() => [
                $faker->name,
                $faker->phoneNumber,
                $faker->company,
                $faker->city,
                $faker->cpf
            ], array_fill(0, 20, null));

        });
        
        return (new Table())->fromArray($data)->response();
    }

    public function create()
    {
        // $this->changeTheme('craftoob');

        $this->breadCrumb()->append(new Entry('Formulário'));

        return view('core::examples.admin-form')->with([
            'title' => 'Formulário de Teste',
            'description' => 'Um formulário usando BS4'
        ]);
    }

    public function createService()
    {
        return [
            
        ];
    }
}

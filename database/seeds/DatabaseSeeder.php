<?php

use App\User;
use App\Role;
use App\UsersRoles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

    /**
      php artisan db:seed
      php artisan make:migration create_sharks_table --table=sharks --create
      php artisan make:model Flight -m
      api: php artisan make:controller sharkController --resource

     * @return void
     */
    public function run() {
        $function = '
            CREATE FUNCTION format_cpf(cpf VARCHAR(11)) RETURNS VARCHAR(14)
                BEGIN
                    RETURN CONCAT( SUBSTRING(cpf,1,3), ".", SUBSTRING(cpf,4,3), ".", SUBSTRING(cpf,7,3), "-", SUBSTRING(cpf,10,2));
                END
        ';
        DB::unprepared("DROP FUNCTION IF EXISTS format_cpf;");
        DB::unprepared($function);

        $function = '
            CREATE FUNCTION format_cnpj(cnpj VARCHAR(14)) RETURNS VARCHAR(18)
                BEGIN
                    RETURN CONCAT( SUBSTRING(cnpj,1,2), ".", SUBSTRING(cnpj,3,3), ".", SUBSTRING(cnpj,6,3), "/", SUBSTRING(cnpj,9,4), "-", SUBSTRING(cnpj,13,2));
                END
        ';
        DB::unprepared("DROP FUNCTION IF EXISTS format_cnpj;");
        DB::unprepared($function);

        //$this->command->call('composer dumpautoload');
        //php artisan make:model Company -mc
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {
            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Data cleared, starting from blank database.");
        }

        $this->call(StateTableSeeder::class);
        $this->call(CityTableSeeder::class);

        $this->command->call('passport:install');

//        DB::table('roles')->truncate();

        $roles = array(
            [
                'id' => 1,
                'name' => 'Root',
                'role' => 'root'
            ],
            [
                'id' => 2,
                'name' => 'Administrador',
                'role' => 'administrador'
            ],
            /*
            [
                'id' => 3,
                'name' => 'Gerente',
                'role' => 'gerente'
            ],
            [
                'id' => 4,
                'name' => 'Funcionário',
                'role' => 'funcionario'
            ],
            [
                'id' => 5,
                'name' => 'Essencial I',
                'role' => 'essencial1'
            ],
            [
                'id' => 6,
                'name' => 'Essencial II',
                'role' => 'essencial2'
            ],
            [
                'id' => 7,
                'name' => 'Essencial III',
                'role' => 'essencial3'
            ],
            [
                'id' => 8,
                'name' => 'Visitante',
                'role' => 'visitante'
            ]
             * 
             */
        );

        $item = 1;
        foreach ($roles as $i => $role) {
            $item = $i + 1;
            $this->command->info('Criando Usuário ' . $item . '...');
            $this->createUser($item, Role::create($role));
        }

        //$this->criar_questao();
    }

    function criar_questao() {
        $questao1 = \App\Question::create([
                    'id' => 1,
                    "question" => "Quem descobriu o Brasil?",
                    "weight" => 3
        ]);

        $questao2 = \App\Question::create([
                    'id' => 2,
                    "question" => "Qual o resultado de 1+1",
                    "weight" => 1
        ]);

        $resposaQuestao1 = \App\Answer::create([
                    "id" => 1,
                    "question_id" => 1,
                    "option" => "Cristovão Colombo",
        ]);

        $resposaQuestao1 = \App\Answer::create([
                    "id" => 2,
                    "question_id" => 1,
                    "option" => "Americo Vespucio",
        ]);

        $resposaQuestao1 = \App\Answer::create([
                    "id" => 3,
                    "question_id" => 1,
                    "option" => "Pedro Alvares Cabral",
        ]);

        $resposaQuestao1 = \App\Answer::create([
                    "id" => 4,
                    "question_id" => 2,
                    "option" => "1",
        ]);

        $resposaQuestao1 = \App\Answer::create([
                    "id" => 5,
                    "question_id" => 2,
                    "option" => "2",
        ]);

        $resposaQuestao1 = \App\Answer::create([
                    "id" => 6,
                    "question_id" => 2,
                    "option" => "3",
        ]);

        $question = \App\Question::find(1);
        $question->update([
            "answer_id" => 3,
        ]);

        $question = \App\Question::find(2);
        $question->update([
            "answer_id" => 5,
        ]);
    }

    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($i, $role) {
        if ($i == 1) {
            $user = User::create([
                        'name' => 'Ronaldo Alves Nascimento',
                        'role_id' => $role->id,
                        'email' => 'bhzdado@gmail.com',
                        'cpf' => '04753120660',
                        'cep' => '30520540',
                        'address' => 'Rua Gentil Portugal do Brasil',
                        'number' => '55',
                        'Complement' => 'Apto 304 Bloco 02',
                        'city_id' => '2308',
                        'state_id' => '11',
                        'neighborhood' => 'Camargos',
                        'telephone' => '3139152491',
                        'cellphone' => '31991226212',
                        'activation_code' => md5("bhzdado@gmail.com" . time()),
                        'password' => Hash::make('teste')]);
        } else if ($i == 2) {
            $user = User::create([
                        'name' => 'Romilda Alves Nascimento',
                        'role_id' => $role->id,
                        'email' => 'contato@naper.com.br',
                        'cpf' => '04753120660',
                        'cep' => '30520540',
                        'address' => 'Rua Gentil Portugal do Brasil',
                        'number' => '55',
                        'Complement' => 'Apto 304 Bloco 02',
                        'city_id' => '2308',
                        'state_id' => '11',
                        'neighborhood' => 'Camargos',
                        'telephone' => '3139152491',
                        'cellphone' => '31991226212',
                        'activation_code' => md5("contato@naper.com.br" . time()),
                        'password' => Hash::make('teste')]);
        } else {
            $user = factory(User::class)->create(['role_id' => $role->id]);
        }
    }

}

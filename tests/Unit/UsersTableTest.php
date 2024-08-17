<?php

namespace Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;


class UsersTableTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
      * @test
      * @return void
      */
    public function users_table_has_expected_columns()
    {
        $columns = [
            'id',
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
            'email_verified_at',
            'password',
            'photo',
            'type',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        foreach ($columns as $column) {
            $this->assertTrue(Schema::hasColumn('users', $column));
        }

    }
}

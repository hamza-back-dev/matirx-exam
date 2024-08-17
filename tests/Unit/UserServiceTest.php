<?php

namespace Tests\Unit;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Mockery;
use Tests\TestCase;


class UserServiceTest extends TestCase
{
    use WithFaker, DatabaseMigrations;


    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = resolve(UserService::class); 
    }


    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
        $photo = time() . '_' . UploadedFile::fake()->image('avatar.jpg')->getClientOriginalName();

        for ($i=0; $i < 20 ; $i++) { 
            User::create([
                'prefixname' => 'Mr',
                'firstname' => 'First-Name '.$i.'-'.random_int(0, 9999),
                'middlename' => 'Middle-Name '.$i.'-'.random_int(0, 9999),
                'lastname' => 'Last-Name '.$i,
                'suffixname' => 'User-'.$i.'-'.random_int(0, 9999),
                'username' => 'userr-'.$i.'-'.random_int(0, 9999),
                'email' => 'userr-'.$i.'-'.random_int(0, 9999).'@gmail.com',
                'photo' => $photo,
                'type' => 'user',
                'password' => Hash::make('00000000'),
            ]);
        }

        $pageSize = 7;
        $pageNumber = 1;
        $paginatedUsers = $this->userService->getUsers($pageSize, $pageNumber);
        $this->assertCount($pageSize, $paginatedUsers->items());

        $this->assertEquals($pageSize, $paginatedUsers->perPage());
        $this->assertEquals($pageNumber, $paginatedUsers->currentPage());
    }

     /**
      * @test
      * @return void
      */
    public function it_can_store_a_user_to_database()
    {
        $request = Mockery::mock(UserRequest::class);

        $photo = time() . '_' . UploadedFile::fake()->image('avatar.jpg')->getClientOriginalName();
        $request->shouldReceive('validated')->andReturn([
            'prefixname' => 'Mrs',
            'firstname' => 'Create First-Name ',
            'middlename' => 'Create Middle-Name ',
            'lastname' => 'Create Last-Name ',
            'suffixname' => 'Create User-'.random_int(0, 9999),
            'username' => 'Create userr-'.random_int(0, 9999),
            'email' => 'Create-userr-test@gmail.com',
            'type' => 'user',
            'photo' => $photo,
            'password' => Hash::make('00000000'),
        ]);
      
        $request->shouldReceive('hasFile')->with('photo')->andReturn(true);
        $request->shouldReceive('file')->with('photo')->andReturn(UploadedFile::fake()->image('avatar.jpg'));
        
        $expectedPath = public_path() . '/' . 'photos/' . $photo;
        
        // Act
        $user = $this->userService->createUser($request);
    
        // Assert
        $this->assertNotNull($user->id);
        $this->assertEquals('Create-userr-test@gmail.com', $user->email);
        $this->assertEquals('Create First-Name ', $user->firstname);
        $this->assertTrue(file_exists($expectedPath), "The file does not exist at {$expectedPath}");

    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Arrangements
        $user = User::factory()->create();

        // Actions
        $findUser = $this->userService->getUser($user->id);
        // Assertions

        $this->assertEquals($user->id, $findUser->id);
        $this->assertEquals($user->email, $findUser->email);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        // Arrangements
        $user = User::factory()->create();
        $request = Mockery::mock(UserRequest::class);
        // Actions
        $request->shouldReceive('hasFile')->andReturn(false);
        $request->shouldReceive('all')->andReturn([
            'prefixname' => 'Mrs',
            'firstname' => 'Updated Firstname',
            'middlename' => 'Middle-Name ',
            'lastname' => 'Last-Name ',
            'suffixname' => 'User'.'-'.random_int(0, 9999),
            'username' => 'updateduser',
            'email' => 'user_'.random_int(0, 9999).'@gmail.com',
            'type' => 'user',
            'photo' => null,
            'password' => null,
        ]);
        $updatedUser = $this->userService->updateUser($user->id, $request);
        // Assertions
        $this->assertEquals('Updated Firstname', $updatedUser->firstname);
        $this->assertEquals('updateduser', $updatedUser->username);
        $this->assertNull($updatedUser->photo);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Action
        $this->userService->destroyUser($user->id);

        // Assert
        $this->assertSoftDeleted($user);
    }

     /**
      * @test
      * @return void
      */

    public function it_can_return_a_paginated_list_of_trashed_users()
    {

        for ($i=0; $i < 20 ; $i++) { 
           $user = User::create([
                'prefixname' => 'Mr',
                'firstname' => 'First-Name rr'.$i.$i,
                'middlename' => 'Middle-Name rr'.$i.$i,
                'lastname' => 'Last-Name rr'.$i.$i,
                'suffixname' => 'Userrr-'.$i.$i,
                'username' => 'userrrr-'.$i.$i,
                'email' => 'userrr-'.$i.$i. '@gmail.com',
                'photo' => null,
                'type' => 'user',
                'password' => Hash::make('00000000'),
            ]);

           $this->userService->destroyUser($user->id);
        }
       
        $pageSize = 7;
        $pageNumber = 1;
        $paginatedUsers = $this->userService->trashedUsers($pageSize, $pageNumber);
        $this->assertCount($pageSize, $paginatedUsers->items());

        $this->assertEquals($pageSize, $paginatedUsers->perPage());
        $this->assertEquals($pageNumber, $paginatedUsers->currentPage());
    }

     /**
      * @test
      * @return void
      */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrangements
        $user = User::factory()->create();

        $deleted = $this->userService->destroyUser($user->id);
        // Actions
        $restoredUser = $this->userService->restoreUser($deleted->id);
        // Assertions
        $this->assertEquals($user->id, $restoredUser->id);
        $this->assertEquals($restoredUser->deleted_at, null);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        // Arrangements
        $user = User::factory()->create();

        $deleted = $this->userService->destroyUser($user->id);
        // Actions
        $deletedUser = $this->userService->deleteUser($deleted->id);
        // Assertions
        $this->assertEquals($user->id, $deletedUser->id);
        // $this->assertEquals($deletedUser->deleted_at, null);
    
    }

     /**
      * @test
      * @return void
      */
    public function it_can_upload_photo()
    {
        // Arrangements
        $photo = time() . '_' . UploadedFile::fake()->image('avatar.jpg')->getClientOriginalName();
        // Actions
        $expectedPath = public_path() . '/' . 'photos/' . $photo;
        $this->userService->upload(UploadedFile::fake()->image('avatar.jpg'));
        // Assertions
        $this->assertTrue(file_exists($expectedPath), "The file does not exist at {$expectedPath}");
    }
}

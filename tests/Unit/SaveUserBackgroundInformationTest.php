<?php

namespace Tests\Unit;

use App\Events\UserSaved;
use App\Http\Requests\UserRequest;
use App\Listeners\SaveUserBackgroundInformation;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class SaveUserBackgroundInformationTest extends TestCase
{

    use WithFaker;


    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = resolve(UserService::class); 
    }


    
    public function test_if_saves_user_details()
    {
        $request = Mockery::mock(UserRequest::class);
        Event::fake([UserSaved::class]);

        $request->shouldReceive('validated')->andReturn([
            'prefixname' => 'Mrs',
            'firstname' => 'Details ',
            'middlename' => 'middle details',
            'lastname' => 'last details',
            'suffixname' => 'lddd-'.random_int(0, 100),
            'username' => 'uu-'.random_int(0, 100),
            'email' => 'testing'.random_int(0, 100).'@gmail.com',
            'type' => 'user',
            'photo' => null,
            'password' => Hash::make('00000000'),
        ]);
        $request->shouldReceive('hasFile')->with('photo')->andReturn(true);
        $request->shouldReceive('file')->with('photo')->andReturn(HttpUploadedFile::fake()->image('avatar.jpg'));
        
        $user = $this->userService->createUser($request);
        
        $event = new UserSaved($user);
        $listener = new SaveUserBackgroundInformation($this->userService);
        $listener->handle($event);
        Event::assertDispatched(UserSaved::class);
        Event::assertListening(UserSaved::class, SaveUserBackgroundInformation::class);
    }
}

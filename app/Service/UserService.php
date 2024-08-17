<?php

namespace App\Service;

use App\Http\Requests\UserRequest;
use App\Models\Detail;
use App\Models\User;
use App\Service\UserServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface {
    
    public function getUsers() {
        return User::paginate(7);
    }

    public function getUser($id) {
        return User::find($id);
    }

    public function getTrashedUser($id) {
        return User::onlyTrashed()->find($id);
    }

    public function createUser(UserRequest $request) {
        $formFields = $request->validated();
        
        if ($request->hasFile('photo')) {
            $formFields['photo'] = $this->upload($request->file('photo'));
        }

        return User::create($formFields);
    }

    public function updateUser($id, UserRequest $request) {
        $user = $this->getUser($id);
        
        if ($request->hasFile('photo')) {
            $userPhoto = $this->upload($request->file('photo'));
            $user->photo =  basename($userPhoto);
        }
        
        $user->password = !is_null($request->password) ? $this->hash($request->password) : $user->password;
        $user->prefixname = !is_null($request->prefixname) ? $request->prefixname : $user->prefixname;
        $user->firstname = $request->firstname;
        $user->middlename = $request->middlename;
        $user->lastname = $request->lastname;
        $user->suffixname = $request->suffixname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->type = !is_null($request->type) ? $request->type : $user->type;
        
        $user->save();
        return $user;
    }

    public function destroyUser($id) {
        $user = $this->getUser($id);
        $user->delete();
        return $user;
    }


    public function trashedUsers() {
        return User::onlyTrashed()->paginate(7);
    }


    public function restoreUser($id) {
        $user = $this->getTrashedUser($id);
        $user->restore();
        return $user;
    }


    public function deleteUser($id) {
        $user = $this->getTrashedUser($id);
        $user->forceDelete();
        return $user;
    }


    public function hash($password){
        return Hash::make($password);
    }
    
    public function upload(UploadedFile $file)
    {
        $path = public_path('photos');

        $filename = time().'_'.$file->getClientOriginalName();

        $file->move($path, $filename);

        return $filename;
    }


    public function saveBackgroundInformation(User $user)
    {
        $fullName = $user->firstname.' '.$user->middleinitial.' '.$user->lastname;
        $prefixName = $user->prefixname == null ? '-' : ($user->prefixname == 'Mr' ? 'Male' : 'Female');
        $details = [
            ['key' => 'Full name', 'value' => $fullName, 'type' => 'bio', 'user_id' => $user->id],
            ['key' => 'Middle Initial', 'value' => $user->middleinitial, 'type' => 'bio', 'user_id' => $user->id],
            ['key' => 'Avatar', 'value' => $user->photo ?? null, 'type' => 'bio', 'user_id' => $user->id],
            ['key' => 'Gender', 'value' => $prefixName, 'type' => 'bio', 'user_id' => $user->id],
        ];

        foreach ($details as $detail) {
            Detail::updateOrCreate(['user_id' => $user->id, 'key' => $detail['key']], ['value' => $detail['value']]);
        }
    }

}
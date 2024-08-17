<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public $userService;

    function __construct(UserService $userService)
    {   
        $this->userService = $userService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getUsers();
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prefixnames = User::prefixnames();
        $types = User::types();
        return view('users.create')->with(['prefixnames' => $prefixnames, 'types' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $this->userService->createUser($request);
            return redirect()->route('users.index');
        }
        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->getUser($id);
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->userService->getUser($id);
        $prefixnames = User::prefixnames();
        $types = User::types();
        return view('users.edit')->with(['user' => $user, 'prefixnames' => $prefixnames, 'types' => $types]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            $this->userService->updateUser($id, $request);
            return redirect()->route('users.index');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->destroyUser($id);
            return redirect()->route('users.index');
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    
    /**
     * Get Trashed Resources.
     */

    public function trashed()
    {
        $users = $this->userService->trashedUsers();
        return view('users.trashedUsers')->with('users', $users);
    }
    
    
    public function restore($id)
    {
        try {
            $this->userService->restoreUser($id);
            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }


    public function delete($id)
    {
        try {
            $this->userService->deleteUser($id);
            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

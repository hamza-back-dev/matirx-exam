@extends('layouts.app')
@section('content') 


<div class="container">

    <a class="btn btn-dark" href="{{ route('users.index') }}">Back</a>  
@if($users->count() <= 0)
    <center><h4>No records</p></center>
@else

<table class="table table-bordered" style=" margin: auto; margin-top: 100px; overflow-y:auto;">
    <thead class="table-dark text-center">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Photo</th>
        <th scope="col">Full name</th>
        <th scope="col">User name</th>
        <th scope="col">Suffix name</th>
        <th scope="col">Email</th>
        <th scope="col">Type</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody class="text-center" style="vertical-align: middle">
        @if(isset($users))
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td><img src="{{ $user->photo ? URL::asset('photos/'.$user->photo) : URL::asset('avatar/'.$user->avatar) }}" alt="avatar" width="60" height="60" style="border-radius: 50%;"/></td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->suffixname ?? '-' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->type }}</td>
                    <td>
                        <div class="dropright">
                            <i class="btn btn-dark dropdown-toggle fa fa-ellipsis-v" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">View</a>
                            <a class="dropdown-item" href="#" onclick="if(confirmAction(event, 'restore', 'restored to your system.', 'restore-form-{{ $user->id }}')) { document.getElementById('restore-form-{{ $user->id }}').submit(); }">Restore</a>
                            <a class="dropdown-item" href="#" onclick="if(confirmAction(event, 'remove', 'deleted permanently from your system.', 'delete-form-{{ $user->id }}')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>

                <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('users.delete', $user->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                </form>

                <form id="restore-form-{{ $user->id }}" method="POST" action="{{ route('users.restore', $user->id) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                </form>

            @endforeach
        @endif
    </tbody>
  </table>
  @endif
</div>


@stop
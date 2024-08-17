@extends('layouts.app')

@section('content')

@if ($user !== null )
    
<section class="h-100 gradient-custom-2">

    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center">
        <div class="col col-lg-9 col-xl-8">
          <div class="card">
            <div class="rounded-top text-white d-flex flex-row" style="background-color: #000; height:200px;">
              <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                <img src="{{ $user->photo ? URL::asset('photos/'.$user->photo) : URL::asset('avatar/'.$user->avatar) }}"
                  alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2"
                  style="width: 150px; z-index: 1">
                <a href="{{ route('users.edit',$user->id) }}" class="btn btn-dark text-white" style="z-index: 1;">
                  Edit profile
                </a>
              </div>
              <div class="ms-3" style="margin-top: 130px;">
                <h5>{{ $user->prefixname ?? '' }} {{ $user->fullname }}</h5>
                <p>{{ $user->email }}</p>
              </div>
            </div>
            <div class="p-4 text-black bg-body-tertiary">
              <div class="d-flex justify-content-end text-center py-1 text-body">
                <div>
                  <p class="mb-1 h5">{{ $user->created_at->diffForHumans() }}</p>
                  <p class="small text-muted mb-0">Joined</p>
                </div>
               
              </div>
            </div>
            <div class="card-body p-4 text-black">
              <div class="mb-5  text-body">
                <p class="lead fw-normal mb-1">About</p>
                <div class="p-4 bg-body-tertiary">
                  <p class="font-italic mb-1">First name : {{ $user->firstname }}</p>
                  <p class="font-italic mb-1">Middle name : {{ $user->middlename ?? '-' }}</p>
                  <p class="font-italic mb-1">Last name : {{ $user->lastname }}</p>
                  <p class="font-italic mb-0">Suffix name : {{ $user->suffixname ?? '' }}</p>
                  <p class="font-italic mb-0">Type : {{ $user->type ?? '' }}</p>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif

@stop
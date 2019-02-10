@extends('login_layout')
@section('title','Login')
@section('content')

    <div class="container">
        <img class="ml-auto mr-auto d-block mt-5" src="/images/sadas.png" width="100px">
        <h1 class="text-center p-3">O.H.D. Semper Excelsius dashboard</h1>
        <form class="form-login card" method="POST" action="/login">
            @csrf
            <h2 class="h3 mb-3 font-weight-normal">Log in</h2>

            <label for="inputEmail" class="">Email addres</label>
            <input type="email" id="inputEmail" class="form-control" placeholder="" name="email" required autofocus>
            <label for="inputPassword" class="">Wachtwoord</label>
            <input type="password" id="inputPassword" class="form-control mb-4" placeholder="" name="password" required>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
        </form>

    </div>
@endsection
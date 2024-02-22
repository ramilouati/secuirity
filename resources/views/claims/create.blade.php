<!-- resources/views/claims/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Claim</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('claims.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="content">Title :</label>
                                <input type="mail" class="form-control" id="title" name="title"  required/>
                            </div>
                            <div class="form-group">
                                <label for="content">Mail employeur:</label>
                                <select name="mail_employeur" id="mail_employeur">
                                    <option value="">Choisir un employeur</option>

                                @foreach($employeurs as $employeur)
                                    <option value="{{$employeur->email}}">{{$employeur->email}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="content">Content:</label>
                                <textarea class="form-control" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

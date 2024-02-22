<!-- resources/views/claims/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">Claims</div>

                    <div class="card-body">
                        @if($user->is_responsable==0)
                        <div class="mb-3">
                            <a href="{{ route('claims.create') }}" class="btn btn-success">Ajouter Réclamation</a>
                        </div>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Encrypted Content</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($claims as $claim)
                                <tr>
                                    <td>{{ $claim->id }}</td>
                                    <td style="max-width:100px;">{{ $claim->encrypted_content }}</td>
                                    <td>
                                        <a href="{{ route('claims.show', $claim->id) }}" class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

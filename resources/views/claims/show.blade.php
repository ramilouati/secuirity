<!-- resources/views/claims/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Claim Details</div>

                    <div class="card-body">
                        <div>
                            <strong>ID:</strong> {{ $claim->id }}
                        </div>
                        <div>
                            <strong>Title :</strong> {{ $claim->title }}
                        </div>
                        @if($user->is_responsable==1)
                        <div>
                            <strong>Employeur first name :</strong> {{ $employeur->prenom }}
                            <strong>Employeur last name :</strong> {{ $employeur->nom }}
                            <strong>Employeur mail :</strong> {{ $employeur->mail }}
                            <strong>Employeur grade :</strong> {{ $employeur->grade }}
                        </div>
                        @endif
                        <div>
                            <strong>Decrypted Claim Content:</strong> {{ $decryptedContent }}
                        </div>
                        @if($claim->encrypted_reponse)
                        <div>
                            <strong>Decrypted Claim Response:</strong> {{ $decryptedResponse }}
                        </div>
                        @else
                            @if($user->is_responsable==1)

                            <form method="POST" action="{{ route('claims.response',['id'=>$claim->id]) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="content">Response:</label>
                                    <textarea class="form-control" id="response" name="response" rows="4" required>{{ old('content') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            @endif
                        @endif
                        <!-- Ajoutez ici d'autres détails de la réclamation que vous souhaitez afficher -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

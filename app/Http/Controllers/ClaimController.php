<?php

namespace App\Http\Controllers;

use App\Models\Employeurs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Claim;

class ClaimController extends Controller
{
    public function index()
    {
        $user=Auth::user();
        if($user->is_responsable==1){
            $claims = Claim::all();

        }else{
            $claims = Claim::where('user_id',$user->id)->get();

        }
        return view('claims.index', ['claims' => $claims,'user'=>$user]);
    }

    public function store(Request $request)
    {

     //   dd($request->all());
        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'mail_employeur' => 'required',
        ]);

        $responsable=User::where('is_responsable',1)->first();
        // Récupérer la clé publique de l'utilisateur authentifié
        $responsablePublicKey = $responsable->public_key;

        // Chiffrer les données avec la clé publique
        openssl_public_encrypt($validatedData['content'], $encryptedData, $responsablePublicKey);

        // Enregistrer la réclamation dans la base de données avec les données chiffrées
        $claim = new Claim();
        $claim->user_id = Auth::id();
        $claim->title = $validatedData['title'];
        $claim->encrypted_content = base64_encode($encryptedData); // Assurez-vous de stocker les données chiffrées de manière sécurisée
        $claim->encrypted_empyloeur_mail = Crypt::encryptString($validatedData['mail_employeur']); // Assurez-vous de stocker les données chiffrées de manière sécurisée
        $claim->save();

        // Rediriger avec un message de succès
        return redirect()->route('claims')->with('success', 'Claim created successfully');
    }
    public function show($id)
    {

        $user=Auth::user();
        // Récupérer la réclamation par son ID
        $claim = Claim::findOrFail($id);
        // Récupérer la clé privée de l'utilisateur authentifié
        $userPrivateKey = Auth::user()->private_key;

        // Déchiffrer les données avec la clé privée
        openssl_private_decrypt(base64_decode($claim->encrypted_reponse), $decryptedResponse, $userPrivateKey);

        openssl_private_decrypt(base64_decode($claim->encrypted_content), $decryptedContent, $userPrivateKey);

        $employeur=Employeurs::where('email',Crypt::decryptString($claim->encrypted_empyloeur_mail))->first();
        return view('claims.show', ['user'=>$user,'claim' => $claim, 'decryptedContent' => $decryptedContent,'decryptedResponse'=>$decryptedResponse,'employeur'=>$employeur]);


    }
    public function create()
    {
        $employeurs=Employeurs::all();
        $user=Auth::user();
        //dd(1);
        return view('claims.create',compact('employeurs','user'));
    }

    public function response(Request $request,$id)
    {
        $claim=Claim::findOrFail($id);
        // Valider les données de la requête
        $validatedData = $request->validate([
            'response' => 'required',
        ]);

        $user=User::find($claim->user_id);
        // Récupérer la clé publique de l'utilisateur authentifié
        $userPublicKey = $user->public_key;

        // Chiffrer les données avec la clé publique
        openssl_public_encrypt($validatedData['response'], $encryptedResponse, $userPublicKey);


        $claim->encrypted_reponse = base64_encode($encryptedResponse); // Assurez-vous de stocker les données chiffrées de manière sécurisée
        $claim->save();

        // Rediriger avec un message de succès
        return redirect()->route('claims')->with('success', 'Claim responsed successfully');
    }


    // Les méthodes pour la mise à jour et la suppression peuvent être ajoutées si nécessaire
}

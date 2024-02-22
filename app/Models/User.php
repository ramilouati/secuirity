<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'poste_travail',
        'grade',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Générer les clés publique et privée
            $keys = self::generateKeyPair();

            // Stocker les clés dans le modèle utilisateur
            $user->public_key = $keys['public'];
            $user->private_key = $keys['private'];
        });
    }

    private static function generateKeyPair()
    {
        // Générer une paire de clés RSA
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Générer la ressource de clé privée
        $privateKey = openssl_pkey_new($config);

        if (!$privateKey) {
            $error = openssl_error_string();
            // Log the error or handle it appropriately
            throw new \Exception("Erreur lors de la génération de la clé privée : $error");
        }

        // Extraire la clé privée de la ressource
        openssl_pkey_export($privateKey, $privateKeyPEM);

        if (!$privateKeyPEM) {
            $error = openssl_error_string();
            // Log the error or handle it appropriately
            throw new \Exception("Erreur lors de l'exportation de la clé privée : $error");
        }

        // Extraire la clé publique de la ressource
        $publicKeyDetails = openssl_pkey_get_details($privateKey);
        $publicKeyPEM = $publicKeyDetails['key'];

        // Retourner la paire de clés
        return [
            'public' => $publicKeyPEM,
            'private' => $privateKeyPEM
        ];
    }
}

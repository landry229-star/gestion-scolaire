<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


    \App\Models\User::create([
    'nom' => 'Landry',
    'prenom' => 'Admin',
    'email' => 'admin.systeme@uac.bj',
    'password' => Hash::make('landry123'),
    'type_utilisateur' => 'admin',
    'id_personnel' => null,
]);
}
//public function updateNote(Request $request, $id)
//{
  //  $request->validate([
    //    'valeur_note' => 'required|numeric|min:0|max:20',
     //   'session'     => 'required|in:normale,rattrapage',
  //  ]);

  //  Notes::findOrFail($id)->update([
   //     'valeur_note' => $request->valeur_note,
   //     'session'     => $request->session,
   // ]);

  //  return back()->with('success', 'Note mise à jour avec succès.');
//}
    }


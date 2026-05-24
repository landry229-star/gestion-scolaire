namespace App\Exports;

use App\Models\Inscriptions;
use Maatwebsite\Excel\Concerns\FromCollection;

class EtudiantsFiliereExport implements FromCollection
{
    protected $id_filiere;

    public function __construct($id_filiere) {
        $this->id_filiere = $id_filiere;
    }

    public function collection() {
        // Récupère tous les inscrits de la filière avec les infos de l'étudiant
        return Inscriptions::where('id_filiere', $this->id_filiere)
            ->join('etudiants', 'inscriptions.id_etudiant', '=', 'etudiants.id_etudiant')
            ->select('etudiants.matricule', 'etudiants.nom', 'etudiants.prenom')
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\XmlToCollection;
use Illuminate\Support\Facades\Validator;
use Ramsey\Collection\Collection;
use Symfony\Component\HttpFoundation\Response;

class CalculatorController extends Controller
{
    //Affichage de la page index avec le formulaire
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        $xmlObjetClient = new XmlToCollection('client.xml','ObjectClient');
        $clients = $xmlObjetClient->getData();
        return view("welcome")->with(compact('clients'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculator()
    {
        //Validation des données envoyées par l'utilisateur
        $validator = Validator::make(request()->all(), [
            'expediteur' => 'required|numeric',
            'destinataire' => 'required|numeric',
            'nombre' => 'required|numeric',
            'poids' => 'required|numeric',
            'port' => 'required|string',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST,
            ]);
        }

        $expediteur = $this->getClient(request()->expediteur);
        $destinataire = $this->getClient(request()->destinataire);
        $localite = $this->getLocalite($destinataire['codePostal']);
        $tarif = $this->getTarif($localite['codePostal'],$localite['zone'],$destinataire['idClient']);

        if(request()->port === 'EXPEDITEUR'){
            $conditionTaxation = $this->getConditionTaxation($expediteur['idClient']);
        }else{
            $conditionTaxation = $this->getConditionTaxation($destinataire['idClient']);
        }

        $montantTconditionTaxation = 0;
        if($conditionTaxation['useTaxePortDuGenerale'] == false && $conditionTaxation['useTaxePortPayeGenerale'] === false){
            $montantTconditionTaxation = $conditionTaxation['taxePortDu'];
        }elseif ($conditionTaxation['useTaxePortDuGenerale'] == true){
            $montantTconditionTaxation = $conditionTaxation['taxePortDu'];
        }elseif ($conditionTaxation['useTaxePortPayeGenerale'] == true){
            $montantTconditionTaxation = $conditionTaxation['taxePortPaye'];
        }

        $montant = (double)$tarif['montant'] + (double)$montantTconditionTaxation;

        return response()->json(["expediteur" => $expediteur['raisonSociale'],
            "destinataire" => $destinataire['raisonSociale'],
            "localite" => $localite['ville'],
            "montant" => $montant]);
    }

    /**
     * Cette meéthode permet de récupérer le client
     * @param int $idClient
     * @return Collection
     */
    private function getClient(int $idClient){
        $xmlObjetClient = new XmlToCollection('client.xml','ObjectClient');
        $clients = $xmlObjetClient->getData();
        return $clients->where("idClient",$idClient)->first();
    }

    /**
     * Cette meéthode permet de récupérer la localité
     * @param int $codePostal
     * @return Collection
     */
    private function getLocalite(int $codePostal){
        $xmlObjectLocalite = new XmlToCollection('localite.xml','ObjectLocalite');
        $localites = $xmlObjectLocalite->getData();
        return $localites->where("codePostal",$codePostal)->first();
    }

    /**
     * Cette meéthode permet de récupérer les tarifs
     * @param int $codeDepartement
     * @param $zone
     * @param $idClient
     * @return Collection
     */
    private function getTarif(int $codeDepartement, $zone, $idClient){
        $xmlObjectTarif= new XmlToCollection('tarif.xml','ObjectTarif');
        $tarifs = $xmlObjectTarif->getData();
        $tarifsDepartement = $tarifs->where("codeDepartement",$codeDepartement);

        $tarifClients = $tarifsDepartement->where("idClient",$idClient);

         if ($tarifClients->isEmpty()){
            $tarifClients = $tarifsDepartement->where("idClient",0);
        }
        if($tarifClients->first()['idClientHeritage'] == "1"){
            $idClientHeritage = $tarifClients->first()['idClientHeritage'];
            $tarifClients = $tarifsDepartement->where("idClient",$idClientHeritage);
        }

        $tarifsZone = $tarifClients->where("zone",$zone)->first();

         if(is_null($tarifsZone)){
             $tarifsZone = $tarifClients->where("zone", 1)->first();
         }


         return $tarifsZone;
    }

    /**
     * Cette meéthode permet de récupérer les ConditionTaxations
     * @param int $idClient
     * @return Collection
     */
    private function getConditionTaxation(int $idClient){
        $xmlObjectConditionTaxation= new XmlToCollection('conditiontaxation.xml','ObjectConditionTaxation');
        $conditionTaxations = $xmlObjectConditionTaxation->getData();
        $conditionTaxation = $conditionTaxations->where("idClient",$idClient)->first();

        if(is_null($conditionTaxation)){
            $conditionTaxation = $conditionTaxations->first();
        }
        return $conditionTaxation;
    }
}

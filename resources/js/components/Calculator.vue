<template>
    <div class="container pt-2">
        <div class="alert alert-danger" role="alert" v-if="error">
            {{error}}
        </div>
        <form class="mt-1" @submit.prevent="handleSubmit">
            <h2 class="text-center">Transport Calculator</h2>
            <div class="mt-2">
                <label class="form-label" >Expéditeur</label>
                <select class="form-select" v-model="expediteurSelected" >
                    <option v-for="expediteur in expediteurs" :key="expediteur.idClient" :value="expediteur.idClient">{{expediteur.raisonSociale}}</option>
                </select>
            </div>

            <div class="mt-2">
                <label class="form-label" >Destinataire</label>
                <select class="form-select" v-model="destinataireSelected">
                    <option v-for="destinataire in destinataires" :key="destinataire.idClient" :value="destinataire.idClient">{{destinataire.raisonSociale}}</option>
                </select>
            </div>
            <div class="mt-2">
                <label class="form-label" >Nom de colis</label>
                <input type="text"  class="form-control" v-model="nombreColis">
            </div>
            <div class="mt-2">
                <label class="form-label" >Poids de colis</label>
                <input type="text"  class="form-control" v-model="poidsColis">
            </div>
           <div class="mt-2">
               <p>Qui paie le transport ?</p>
               <div class="form-check">
                   <input type="radio" value="EXPEDITEUR" v-model="port" id="portExp" class="form-check-input">
                   <label for="portExp" class="form-check-label">Expéditeur</label>
               </div>
               <div class="form-check">
                   <input type="radio" value="DESTINATAIRE" v-model="port" id="portDest" class="form-check-input">
                   <label for="portDest" class="form-check-label">Destinataire</label>
               </div>
           </div>
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-primary" @click.prevent="handleSubmit">Calculer</button>
            </div>
        </form>
        <div class="alert alert-success mt-2" role="alert" v-if="result">
            <p><strong>Expéditeur</strong>: {{result.expediteur}}</p>
            <p><strong>Destinataire</strong>: {{result.destinataire}}</p>
            <p><strong>Localité</strong>: {{result.localite}}</p>
            <p><strong>Montant</strong>: {{result.montant}}</p>
        </div>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import axios from "axios";

const props = defineProps(['clients'])
const expediteurSelected = ref(null);
const destinataireSelected = ref(null);
const nombreColis = ref(null);
const poidsColis = ref(null);
const port = ref(null);
const error = ref(null);
const result = ref(null);
const expediteurs = ref(JSON.parse(props.clients))

const destinataires = computed(() => {
    return expediteurs.value.filter(el => el.idClient !== expediteurSelected.value);
})

const handleSubmit = async () => {
    if(expediteurSelected.value !== null && destinataireSelected.value !== null
        && nombreColis.value !== null && poidsColis.value !== null && port.value !== null){
        const data = {
            expediteur : expediteurSelected.value,
            destinataire : destinataireSelected.value,
            nombre : nombreColis.value,
            poids : poidsColis.value,
            port : port.value
        }
        const response = await  axios.post("http://localhost:8000/api/calculator",data);
        result.value = response.data

        expediteurSelected.value = null;
            destinataireSelected.value = null;
             nombreColis.value = null;
             poidsColis.value = null;
            port.value = null;

    }else{
        error.value = "Veuillez renseigner tous les champs."
    }
}
</script>

<style scoped>

</style>

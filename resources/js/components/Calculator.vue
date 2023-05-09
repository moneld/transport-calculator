<template>
    <div class="container">
        <form class="mt-5">
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
                <button type="submit" class="btn btn-primary">Calculer</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";

const props = defineProps(['clients'])
const expediteurSelected = ref(null);
const destinataireSelected = ref(null);
const nombreColis = ref(null);
const poidsColis = ref(null);
const port = ref(null);
const expediteurs = ref(JSON.parse(props.clients))

const destinataires = computed(() => {
    return expediteurs.value.filter(el => el.idClient !== expediteurSelected.value);
})
</script>

<style scoped>

</style>

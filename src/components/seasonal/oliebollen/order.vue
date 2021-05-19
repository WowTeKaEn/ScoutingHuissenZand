<template>
  <b-modal modal-class="modal-fullscreen" body-class="d-flex flex-column" :id="modal" ok-only>
    <template #modal-title>{{ title }}</template>

    <form v-if="!result" class="order-form" :key="check">
        Persoonlijke gegevens:
      <b-input-group class="mb-1">
        <b-form-input :state="states['name']" v-model="form.name" placeholder="Naam" type="text"></b-form-input>
      </b-input-group>
      <b-input-group class="mb-1">
        <b-form-input :state="states['mail']" v-model="form.mail" placeholder="Email" type="email"></b-form-input>
      </b-input-group>
      <b-input-group class="mb-1">
        <b-form-input :state="states['street']" v-model="form.street" placeholder="Straat" type="text"></b-form-input>
      </b-input-group>
      <b-input-group class="mb-1">
        <b-form-input :state="states['postcode']" v-model="form.postcode" placeholder="Postcode" class="w-50" type="text"></b-form-input>
        <b-form-input :state="states['housenumber']" v-model="form.housenumber" placeholder="Huisnummer" class="w-25" type="number"></b-form-input>
      </b-input-group>
      <b-input-group class="mb-1">
        <b-form-input :state="states['phone']" v-model="form.phone" placeholder="Telefoon nummer" type="tel"></b-form-input>
      </b-input-group>
      Bestelling:
      <b-input-group class="mb-1" v-for="(order, i) in form.orders" :key="i">
        <b-form-input :state="states.orders[i]" v-model="order.amount" min=0 max=30 placeholder="Aantal" class="w-25" type="number"></b-form-input>
        <b-form-select :state="states.orders[i]" v-model="order.choice" :options="options" placeholder="Soort" class="w-75" type="text"></b-form-select>
      </b-input-group>
      <b-input-group class="mb-1">
          <b-form-select :state="states['moment']" v-model="form.moment" :options="momentOptions" placeholder="Soort" class="w-75" type="text"></b-form-select>
      </b-input-group>
      <div class="d-flex mb-0 mt-2 mr-1">
        <p>Voeg hier meer soorten toe <b-icon-arrow-right /></p>
        <p class="ml-auto"></p>
        <b-icon-plus-circle-fill variant="success" class="icon-btn h4 mr-1" v-if="this.form.orders.length < 4" @click="() => {if(this.form.orders.length < 4) this.form.orders.push({choice:null,amount:null})}"></b-icon-plus-circle-fill>
        <b-icon-dash-circle-fill variant="danger" class="icon-btn h4" v-if="this.form.orders.length > 1" @click="() => {if(this.form.orders.length > 1) this.form.orders.pop()}"></b-icon-dash-circle-fill>
      </div>
    <p class="mb-0 mt-3" v-if="!isVerified" style="color:red">{{ this.error || "Vul alle velden in" }}</p>
    </form>
    <p v-else>{{ result }}</p>
    <div class="mt-auto">
      <hr class="mb-3"/>
      <p class="mb-0">Totaal:</p>
      <p class="mb-0">€4.25 x {{ totalAmount }} zakken = €{{ (totalAmount * 4.25).toFixed(2) }}</p>
    </div>
    <template #modal-footer v-if="!result">
        <slot name="modal-footer" ></slot>
        <button @click="attemptSend" type="button" delivery="true" class="order-submit btn btn-primary">
          <span v-if="!sending">Versturen</span>
          <span v-else>
            <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
          </span>
        </button>
    </template>
  </b-modal>
</template>

<style>
.icon-btn{
  cursor: pointer;
}
</style>

<script>
import axios from "@/plugins/axios"

export default {
    props: ["modal", "title", 'type'],
    data() {
        return {
          form: {
            name: null,
            postcode: null,
            housenumber: null,
            phone: null,
            mail: null,
            street: null,
            orders: [
              {
                amount: null,
                choice: null,
              }
            ],
            moment: null,
          },
          check: 0,
          options: [
            { value: null, text: "Selecteer soort:"},
            'zak(ken) á 10 oliebollen',
            'zak(ken) á 8 krentenbollen',
            'zak(ken) á 8 appelbollen' ,
            'zak(ken) á 5 appelbeignets',    
          ],
          momentOptions: this.getOptions(),
          states: { orders:[] },
          sending: false,
          result: null,
          error: null,
        }
  },
  computed:{
    isVerified(){
      return !Object.keys(this.states).length > 1 || !this.states.orders.length || this.error
    },
    totalAmount(){
     return this.form.orders.map((order) => parseInt(order.amount || 0)).reduce((carry, amount) => carry + amount) || 0
    }
  },
  methods: {
    getOptions() {
      switch(this.type){
        case "home": return [
            { value: null, text: "Ik wil mijn bestelling laten bezorgen op:"},
            "29 december tussen 14:00 en 18:00 uur",
            "30 december tussen 10:00 en 13:00 uur",
            "30 december tussen 14:00 en 18:00 uur"
          ];
        case "pickup": return [
            { value: null, text: "Ik wil mijn bestelling ophalen op:"},
            "29 december tussen 10:00 en 13:00 uur",
            "30 december tussen 10:00 en 13:00 uur",
            "31 december tussen 10:00 en 13:00 uur"
        ]
      }
    },
    attemptSend() {
      if(this.sending) return;
      this.error = null;
      this.states = { orders:[] };
      for(const prop in this.form){
        if(this.form[prop] === "" || this.form[prop] === null) this.states[prop] = false;
      }
      this.form.orders.forEach((order, i) => {
        if(order.amount === null || order.choice === null) this.states.orders[i] = false;
      })
      this.check++;
      if(this.isVerified){
        this.sending = true;
      axios.post("/order",{...this.form, type: this.type}).then(response => {
        this.result = response.data.message;
        this.sending = false;
      }).catch(error =>{
        console.log(error.response)
        this.error = error.response.data.message;
        this.sending = false;
      })
      }
    }
  }
};
</script>
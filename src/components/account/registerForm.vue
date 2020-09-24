<template>
    <b-card>
          <b-form novalidate @submit.stop.prevent>
            <h2>Account aanvragen</h2>
            <div class="form-group">
              <b-input
                v-model="email"
                :state="emailValidation"
                type="email"
                class="check-input form-control email"
                required
                name="email"
                placeholder="Email"
              ></b-input>
              <b-form-invalid-feedback :state="emailValidation">Vul een correcte email in.</b-form-invalid-feedback>
              <b-form-valid-feedback :state="emailValidation">Looks Good.</b-form-valid-feedback>
            </div>
            <div class="form-group mb-0">
              <b-input
                v-model="password"
                :state="passwordValidation"
                type="password"
                class="form-control pass"
                name="password"
                required
                placeholder="Wachtwoord"
              ></b-input>
              <b-form-invalid-feedback :state="passwordValidation">
                Wachtwoord moet tussen de 6 en twintig karakters zijn, een hoofdletter en een cijfer
                bevatten
              </b-form-invalid-feedback>
            </div>
            <div class="form-group">
              <b-input
                v-model="passRepeat"
                :state="passRepeatValidation"
                type="password"
                class="form-control mt-1 pass-repeat"
                name="confirm_password"
                required
                placeholder="Wachtwoord herhalen"
              ></b-input>
              <b-form-invalid-feedback :state="passRepeatValidation">Wachtwoorden moeten gelijk zijn</b-form-invalid-feedback>
              <b-form-valid-feedback :state="passRepeatValidation && passwordValidation">Looks Good.</b-form-valid-feedback>
            </div>
            <div class="form-group m-0">
              <b-button variant="primary" block  size="lg" v-on:click="attemptToSubmit" class="btn-block">
                <span v-if="!submitting">Vraag account aan</span>
                <span v-else>
                  <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
                </span>
              </b-button>
            </div>
          </b-form>
        </b-card>
</template>


<script>
import axios from "@/plugins/axios.js";
import Vue from "@/main.js"
 
export default {
  name: "registerForm",
  data() {
    return {
      password: "",
      passRepeat: "",
      email: "",
      attemptSubmit: false,
      submitting: false
    };
  },
  computed: {
    emailValidation() {
      if (!this.attemptSubmit) {
        return null;
      } else {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(this.email).toLowerCase());
      }
    },
    passwordValidation() {
      if (!this.attemptSubmit) {
        return null;
      } else {
        if (this.password === "") {
          return false;
        }
        var passCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;

        return passCheck.test(this.password);
      }
    },
    passRepeatValidation() {
      if (!this.attemptSubmit) {
        return null;
      } else {
        if (this.passRepeat === "") {
          return false;
        }
        return this.password === this.passRepeat;
      }
    }
  },
  methods: {
    attemptToSubmit() {
      this.attemptSubmit = true;
      if (
        this.passwordValidation &&
        this.passRepeatValidation &&
        this.emailValidation
      ) {
        this.submitting = true;
        axios
          .post("user/signup", { 
            password: this.password,
            email: this.email
          })
          .then(response => {
            this.submitting = false;
            if (response.status === 201) {
              this.$bvToast.toast("Account aangemaakt. Er is een email verstuurd naar uw email voor validatie (dit kan 10 minuten duren)", Vue.toastObject("Succes"));
            }
          })
          .catch(error => {
            this.submitting = false;
            if(error.response.status === 409){
              this.$bvToast.toast("Email bestaat al", Vue.toastObject("Error"));
            }else{
                this.$bvToast.toast(error + '', Vue.toastObject("Error"));
            }     
          });
      }
    }
  }
};
</script>


<template>
  <b-card class="mb-3">
    <b-form novalidate @submit.stop.prevent>
      <h2>Login</h2>
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
      <div class="form-group mb-0 mt-3">
        <b-button variant="primary" block  size="lg" v-on:click="attemptToSubmit" class="btn-block">
          <span v-if="!submitting">Inloggen</span>
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

export default {
  name: "loginForm",
  props: ["what","who"],
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
        if (re.test(String(this.email).toLowerCase())) {
          return null;
        } else {
          return false;
        }
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
        if (passCheck.test(this.password)) {
          return null;
        } else {
          return false;
        }
      }
    }
  },
  methods: {
    attemptToSubmit() {
      this.attemptSubmit = true;
      if (this.passwordValidation === null && this.emailValidation === null) {
        this.submitting = true;
        axios
          .post("/user/login", {
            password: this.password,
            email: this.email
          })
          .then(response => {
            this.submitting = false;
            if (response.status == 201) {
              if (this.what == null && this.who == null) {
                window.location.href = "/staffpanel"
              } else {
                window.location.href = "/" + this.what + "/" + this.who;
              }
            } else {
              this.$bvToast.toast("Unknown", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            }
          })
          .catch(error => {
            this.submitting = false;
            if (error.response.status === 401) {
              this.$bvToast.toast("Email of wachtwoord klopt niet", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            } else if (error.response.status === 403) {
              this.$bvToast.toast("Account nog niet geverifieerd", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            } else {
              this.$bvToast.toast(error + "", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            }
          });
      }
    }
  }
};
</script>


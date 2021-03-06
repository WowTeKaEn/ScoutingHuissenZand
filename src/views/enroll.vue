<template>
  <section class="clean-block p-0 py-5 dark">
    <b-container v-if="!enrolled">
      <a :href="'/speltak/' + branch.branchName">&crarr; terug</a>
      <h2>Inschrijven voor {{ branch.branchName }}</h2>
      <b-card>
        <b-form ref="form">
          <b-container fluid class="p-0">
            <h4 class="m-0 p-1">Persoonlijke informatie</h4>
            <b-row class="mb-3 mw-100 m-0">
              <b-col class="p-1">
                <b-input
                  required
                  id="firstname"
                  v-model="enrollment.firstname"
                  :state="missing.includes('firstname') ? false : null"
                  placeholder="Voornaam"
                ></b-input>
              </b-col>
              <b-col class="p-1">
                <b-input
                  required
                  id="surname"
                  v-model="enrollment.surname"
                  :state="missing.includes('surname') ? false : null"
                  placeholder="Achternaam"
                ></b-input>
              </b-col>
              <b-col md="2" class="p-1">
                <b-input
                  required
                  id="age"
                  v-model="enrollment.age"
                  :state="missing.includes('age') ? false : null"
                  class="small-col"
                  type="number"
                  placeholder="Leeftijd"
                ></b-input>
              </b-col>
            </b-row>
            <h4 class="m-0 p-1">Adres</h4>
            <b-row class="mb-3 mw-100 m-0">
              <b-col class="p-1">
                <b-input
                  required
                  id="town"
                  v-model="enrollment.town"
                  :state="missing.includes('town') ? false : null"
                  placeholder="Woonplaats"
                ></b-input>
              </b-col>
              <b-col class="p-1">
                <b-input
                  required
                  id="postcode"
                  v-model="enrollment.postcode"
                  :state="missing.includes('postcode') ? false : null"
                  placeholder="Postcode"
                ></b-input>
              </b-col>
              <b-col md="2" class="p-1">
                <b-input
                  required
                  id="housenumber"
                  v-model="enrollment.housenumber"
                  :state="missing.includes('housenumber') ? false : null"
                  class="small-col"
                  type="number"
                  placeholder="Huisnummer"
                ></b-input>
              </b-col>
            </b-row>
            <h4 class="m-0 p-1">Contact informatie</h4>
            <b-row class="mb-3 mw-100 m-0">
              <b-col class="p-1">
                <b-input
                  required
                  id="email"
                  v-model="enrollment.email"
                  :state="missing.includes('email') ? false : null"
                  type="email"
                  placeholder="Email"
                ></b-input>
              </b-col>
              <b-col class="p-1">
                <b-input
                  id="phonenumber"
                  v-model="phonenumber"
                  type="number"
                  placeholder="Telefoon nummer"
                ></b-input>
              </b-col>
            </b-row>
            <b-row class="mb-3 mw-100 m-0">
              <b-col cols="8" v-if="missing.length != 0" class="p-1">
                <p style="color: red">Vul alle benodigde velden in.</p>
              </b-col>
              <b-col class="p-1">
                <div class="d-flex">
                  <b-btn class="ml-auto" @click="submit" variant="primary" v-if="!submitting">Aanmelden</b-btn>
                  <b-btn class="ml-auto" v-else variant="primary" ><b-spinner  variant="light" type="grow" label="Spinning"></b-spinner></b-btn>
                </div>
              </b-col>
            </b-row>
          </b-container>
        </b-form>
      </b-card>
    </b-container>
    <b-container v-else>
      <a :href="'/speltak/' + branch.branchName">&crarr; terug</a>
      <h2>Je aanmelding is verstuurd!</h2>
      <b-card>
        <p>
          Je aanmelding wordt nu verwerkt. Er is een bevestigings email verstuurd naar {{ enrollment.email }}.
          <br />Klik
          <a :href="'/speltak/' + branch.branchName">hier</a> om terug te gaan naar de speltak.
        </p>
      </b-card>
    </b-container>
  </section>
</template>

<style lang="css">
input {
  min-width: 200px;
}
.small-col {
  min-width: unset;
}
</style>

<script>
import router from "@/router/index.js";
import axios from "@/plugins/axios.js";
import VueMixin from "@/main.js"

export default {
  name: "enroll",
  props: ["branchName", "branches"],
  data() {
    return {
      enrollment: {
        branchName: this.branchName,
        firstname: null,
        surname: null,
        age: null,
        town: null,
        postcode: null,
        email: null,
        housenumber: null,
      },
      phonenumber: null,
      submitting: false,
      branch: null,
      try: false,
      enrolled: false,
    };
  },
  methods: {
    submit() {
      this.try = true;
      if (this.missing.length == 0) {
        this.submitting = true;
        let enrollment = this.enrollment;
        enrollment.phonenumber = this.phonenumber;
        axios
          .post("/enroll", enrollment)
          .then((response) => {
            this.returned = true;
            if (response.status === 200) {
              this.enrolled = true;
            } else {
              this.$bvToast.toast("Unknown", VueMixin.toastObject("Error"));
            }
          })
          .catch((error) => {
            this.$bvToast.toast(error.response.data.message, VueMixin.toastObject("Error"));
          });
      }
    },
  },
  computed: {
    missing() {
      if (this.try) {
        var arr = [];
        for (const el in this.enrollment) {
          if (this.enrollment[el] == null) {
            arr.push(el);
          }
        }
        return arr;
      }
      return [];
    },
  },
  created() {
    this.branches.forEach((branch) => {
      if (branch.branchName == this.branchName) {
        this.branch = branch;
      }
    });
    if (this.branch == null) {
      router.push("/error/404");
    }
  },
};
</script>
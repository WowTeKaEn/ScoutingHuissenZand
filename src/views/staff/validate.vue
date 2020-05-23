<template>
  <main class="page">
    <section class="clean-block p-0 py-5 dark">
      <b-container>
        <b-card v-if="returned">
          <h1>Valideren</h1>
          <p>{{ validated ? "Uw account is gevalideerd en er is een email naar de admin gestuurd" : "Er is iets mis gegaan" }}</p>
        </b-card>
      </b-container>
    </section>
  </main>
</template>



<script>
import axios from "@/plugins/axios.js";

export default {
  name: "validate",
  props: ["who", "token"],
  data() {
    return {
      validated: null,
      returned: false
    };
  },
  methods: {
    editTab(tab) {
      this.tab = tab;
    }
  },
  created() {
    axios
      .post("/user/validate",{who: this.who, token: this.token})
      .then(response => {
        this.returned = true;
        if (response.status === 200) {
          this.validated = true;
        } else {
          this.validated = false;
          this.$bvToast.toast("Unknown", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        }
      })
      .catch(error => {
        this.validated = false;
        this.returned = true;
        this.$bvToast.toast(error + "", {
          title: "Error",
          autoHideDelay: 5000,
          appendToast: true
        });
      });
  }
};
</script>
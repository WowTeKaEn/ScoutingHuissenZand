<template>
  <main class="page">
    <section class="clean-block p-0 py-5 dark">
      <b-container>
        <h1>{{ branchName }}</h1>
        <b-card class="clean-card">
          <quillViewer v-bind:ready="returned" v-bind:quill="branch.branchDescription"></quillViewer>
          <hr />
          <h2>Kalender</h2>
          <calendarViewer v-bind:ready="calendarReturned" v-bind:events="events"></calendarViewer>
          <div class="d-flex mt-3">
          <ul class="list-inline social-buttons mx-auto">
            <li v-if="branch.instaUsername != ''" class="list-inline-item">
              <a class="social-link" :href="'https://www.instagram.com/' + branch.instaUsername">
                <i class="fab fa-instagram fa-2x"></i>
              </a>
            </li>
            <li v-if="branch.facebookUsername != ''" class="list-inline-item">
              <a class="social-link" :href="'https://www.facebook.com/' + branch.facebookUsername">
                <i class="fab fa-facebook fa-2x"></i>
              </a>
            </li>
          </ul>
          </div>
          <div
            class="fb-comments"
            :data-href="'http://www.scoutinghuissenzand.space/#scoutinghuissenzand'+ branchName"
            data-numposts="10"
            data-width="fluid"
          ></div>
        </b-card>
      </b-container>
    </section>
  </main>
</template>

<style lang="css">
.page {
  background-color: #28a745;
}

.fb-comments iframe {
  width: 100% !important;
}
</style>

<script>
import Vue from "vue";
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import quillViewer from "@/components/content/quillViewer";
// import teammember from "@/components/teammember.vue";
import calendarViewer from "@/components/content/calendarViewer";

export default {
  name: "branch",
  props: ["branchName"],
  components: { calendarViewer, quillViewer },
  data() {
    return {
      branch: {},
      returned: false,
      calendarReturned: false,
      events: []
    };
  },
  methods: {},
  created() {
    axios
      .post("/branch/get", { branchName: this.branchName })
      .then(response => {
        this.returned = true;
        if (response.status === 201) {
          if (response.data == null) {
            router.push("/error/404");
          }
          this.branch = response.data;
        } else {
          router.push("/error/404");
        }
      })
      .catch(error => {
        this.$bvToast.toast(error + "", {
          title: "Error",
          autoHideDelay: 5000,
          appendToast: true
        });
        router.push("/error/404");
      });
    Vue.loadScript(
      "https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4"
    ).then(() => {
      window.FB.XFBML.parse();
    });
    axios
      .post("/event/get/branch", { branchName: this.branchName })
      .then(response => {
        response.data.forEach(event => {
          this.events.push({
            title: event.eventName,
            start: event.startDate,
            end: event.endDate,
            description: event.eventDescription
          });
        });
        this.calendarReturned = true;
      })
      .catch(error => {
        this.calendarReturned = true;
        if (error.response.status != 404) {
          this.$bvToast.toast("Kalender kon niet worden opgehaald", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        }
      });
  }
};
</script>


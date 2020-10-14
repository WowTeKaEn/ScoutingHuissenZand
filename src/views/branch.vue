<template>
  <section id="background-img" :style="backGround" class="clean-block p-0 py-5 dark">
    <b-container>
      <div class="d-flex justify-content-between">
        <h2 style="color:white;text-shadow: 1px 2px black;" >{{ branchName }}</h2>
        <b-btn
          :href="branchName + '/inschrijven'"
          class="mb-2"
          variant="primary"
          >Inschrijven</b-btn
        >
      </div>
      <b-card class="clean-card">
        <quillViewer
          v-bind:ready="returned"
          v-bind:quill="branch.branchDescription"
        ></quillViewer>

        <div v-if="returned && branch.albums.length > 0">
          <hr />
          <h2>Foto's</h2>

          <albumViewer :albums="branch.albums"></albumViewer>
        </div>
        <hr class="mt-5" />
        <h2>Kalender</h2>
        <calendarViewer
          v-if="calendarReturned"
          v-bind:events="events"
        ></calendarViewer>
        <div class="d-flex mt-4">
          <ul class="list-inline social-buttons mx-auto">
            <li v-if="branch.instaUsername != ''" class="list-inline-item">
              <a
                class="social-link"
                :href="'https://www.instagram.com/' + branch.instaUsername"
              >
                <i class="fab fa-instagram fa-2x"></i>
              </a>
            </li>
            <li v-if="branch.facebookUsername != ''" class="list-inline-item">
              <a
                class="social-link"
                :href="'https://www.facebook.com/' + branch.facebookUsername"
              >
                <i class="fab fa-facebook fa-2x"></i>
              </a>
            </li>
          </ul>
        </div>
        <div
          class="fb-comments"
          :data-href="
            'http://www.scoutinghuissenzand.nl/#scoutinghuissenzand' +
            branchName
          "
          data-numposts="10"
          data-width="100%"
        ></div>
      </b-card>
    </b-container>
  </section>
</template>



<script>
import VueMixin from "@/main.js";
import Vue from "vue";
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import quillViewer from "@/components/content/editor/quillViewer";
import calendarViewer from "@/components/content/events/calendarViewer";
import albumViewer from "@/components/content/albumViewer";


export default {
  name: "branch",
  props: ["branchName"],
  components: { calendarViewer, quillViewer, albumViewer },
  data() {
    return {
      backGround: "",
      branch: {},
      returned: false,
      calendarReturned: false,
      events: [],
      facebookLoading: true,
    };
  },
  created() {
    axios
      .post("/branch/get", { branchName: this.branchName })
      .then((response) => {
        this.returned = true;
        if (response.status === 201) {
          if (response.data == null) {
            router.push("/error/404");
          }
          this.branch = response.data;
          this.backGround = "background-image: url('"+this.branch.backgroundImg+"');"
        } else {
          router.push("/error/404");
        }
      })
      .catch((error) => {
        this.$bvToast.toast(error + "", VueMixin.toastObject("Error"));
        router.push("/error/404");
      });

    Vue.loadScript(
      "https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v8.0&appId=273465613781500&autoLogAppEvents=1"
    ).then(() => {
      window.FB.XFBML.parse();
    });
    axios
      .post("/event/get/branch", { branchName: this.branchName })
      .then((response) => {
        response.data.forEach((event) => {
          this.events.push({
            title: event.eventName,
            start: event.startDate,
            end: event.endDate,
            description: event.eventDescription,
          });
        });
        this.calendarReturned = true;
      })
      .catch((error) => {
        this.calendarReturned = true;
        if (error.response.status != 404) {
          this.$bvToast.toast(
            "Kalender kon niet worden opgehaald",
            VueMixin.toastObject("Error")
          );
        }
      });
  },
};
</script>
<style lang="scss">
.fb-comments iframe {
  width: 100% !important;
}

#background-img{
    background-size: cover;
    background-attachment: fixed;
    background-image: url(~@/assets/img/20190725_084919.jpg);
}
</style>

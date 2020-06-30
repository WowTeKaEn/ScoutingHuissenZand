<template>
    <section class="clean-block p-0 py-5 dark">
      <b-container>
        <div class="d-flex justify-content-between">
          <h2>{{ branchName }}</h2>
          <b-btn :href="branchName + '/inschrijven'" class="mb-2" variant="primary">Inschrijven</b-btn>
        </div>
        <b-card class="clean-card">
          <quillViewer v-bind:ready="returned" v-bind:quill="branch.branchDescription"></quillViewer>

          <div v-if="returned && branch.images.length > 0">
            <hr />
            <h2>Foto's</h2>

            <div class="w-100 d-flex justify-content-center">
              <v-gallery :images="gallery" :index="index" @close="index = null" @onopen="addState" @onclose="removeState"/>
              <div
                class="image"
                v-for="(image, imageIndex) in galleryFilter"
                :key="`${imageIndex}`"
                @click="index = imageIndex"
                :style="{
					backgroundImage: `url('${image}')`,
				}"
              >
                <div
                  class="w-100 h-100 d-flex align-center justify-content-center text-center"
                  v-if="checkOverlay(imageIndex)"
                  style="background-color:black; opacity:0.8"
                ></div>
                <div
                  class="image-text d-flex justify-content-center"
                  v-if="checkOverlay(imageIndex)"
                >+ {{ gallery.length - photoAmount }}</div>
              </div>
            </div>
          </div>
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
            :data-href="'http://www.scoutinghuissenzand.nl/#scoutinghuissenzand'+ branchName"
            data-numposts="10"
            data-width="fluid"
          ></div>
        </b-card>
      </b-container>
    </section>
</template>

<style lang="css">

.fb-comments iframe {
  width: 100% !important;
}

.image {
  background-size: cover;
  background-repeat: no-repeat;
  background-position: 50% 50%;
  margin-left: 0.25em;
  margin-right: 0.25em;
  width: 100%;
  height: 200px;
  cursor: pointer;
  display: inline-block;
  border-radius: 6px !important;
}

.image-text {
  display: flex;
  position: relative;
  top: -50%;
  transform: translate(0%, -50%);
  color: white;
}
</style>

<script>
import Vue from "vue";
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import quillViewer from "@/components/content/editor/quillViewer";
import calendarViewer from "@/components/content/events/calendarViewer";
import isMobile from "@/plugins/isMobile";

export default {
  name: "branch",
  props: ["branchName"],
  components: { calendarViewer, quillViewer },
  data() {
    return {
      branch: {},
      returned: false,
      calendarReturned: false,
      events: [],
      index: null,
      gallery: [],
      photoAmount: 3
    };
  },
  computed: {
    galleryFilter() {
      var temp = this.gallery.slice();
      temp.splice(this.photoAmount, temp.length - this.photoAmount);
      return temp;
    }
  },
  beforeMount() {
    window.addEventListener("hashchange", () => {
      if (this.index != null && isMobile()) {
        this.index = null;
      } else {
        return;
      }
    });
  },
  methods: {
    addState() {
      if (this.index != null && isMobile()) {
        history.pushState(null, null, "#gallery"); 
      }
    },
    removeState(){
      if (location.hash == "#gallery" && isMobile()) {
         window.history.back();
      }
    },
    setPhotoAmount() {
      var temp = Math.floor(window.innerWidth / 375);
      if (temp < 1) {
        temp = 1;
      }
      this.photoAmount = temp;
    },
    checkOverlay(imageIndex) {
      if (this.photoAmount >= this.gallery.length) {
        return false;
      }
      return imageIndex == this.photoAmount - 1;
    }
  },
  destroyed() {
    window.removeEventListener("resize", this.setPhotoAmount);
  },
  created() {
    this.setPhotoAmount();
    window.addEventListener("resize", this.setPhotoAmount);

    axios
      .post("/branch/get", { branchName: this.branchName })
      .then(response => {
        this.returned = true;
        if (response.status === 201) {
          if (response.data == null) {
            router.push("/error/404");
          }
          response.data.images.forEach(image => this.gallery.push(image.url));
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
      "https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.4"
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
  },
};
</script>


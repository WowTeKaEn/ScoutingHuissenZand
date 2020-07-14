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
          <flickity
            v-images-loaded="loaded"
            class="carousel"
            ref="flickity"
            :options="flickityOptions"
          >
            <div
              v-for="(image, imageIndex) in gallery"
              :key="`${imageIndex}`"
              class="carousel-cell"
              @pointerdown="handleDown"
              @pointerup="handleUp(imageIndex, $event)"

            >
              <img class="image" :src="image" alt />
            </div>
          </flickity>
        </div>
        <hr class="mt-5" />
        <h2>Kalender</h2>
        <calendarViewer v-bind:ready="calendarReturned" v-bind:events="events"></calendarViewer>
        <div class="d-flex mt-4">
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
          :data-href="'http://www.scoutinghuissenzand.nl/#scoutinghuissenzand'+ branchName"
          data-numposts="10"
          data-width="fluid"
        ></div>
      </b-card>
    </b-container>
  </section>
</template>



<script>
import Vue from "vue";
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import quillViewer from "@/components/content/editor/quillViewer";
import calendarViewer from "@/components/content/events/calendarViewer";
import isMobile from "@/plugins/isMobile";
import Flickity from "vue-flickity";
import imagesLoaded from "vue-images-loaded";
import "flickity-fullscreen";

export default {
  directives: {
    imagesLoaded
  },
  name: "branch",
  props: ["branchName"],
  components: { calendarViewer, quillViewer, Flickity },
  data() {
    return {
      branch: {},
      returned: false,
      calendarReturned: false,
      events: [],
      gallery: [],
      drag: false,
      x: 0,
      y: 0,
      flickityOptions: {
        initialIndex: 1,
        prevNextButtons: false,
        pageDots: true,
        contain: false,
        autoPlay: true,
        fullscreen: true,
        imagesLoaded: true,
        pauseAutoPlayOnHover: false,
      }
    };
  },
  destroyed() {
    window.removeEventListener("hashchange");
  },
  methods: {
    loaded() {
      this.$refs.flickity.reloadCells();
      this.$refs.flickity.select(1);
    },
    handleDown(event) {
      const { pageX, pageY } = event;
      this.x = pageX;
      this.y = pageY;
    },
    handleUp(index, event) {
      const { pageX, pageY } = event;
      if (this.x == pageX && this.y == pageY) {
        if (!this.$refs.flickity.$flickity.isFullscreen) {
        this.$refs.flickity.$flickity.select(index);
        this.$refs.flickity.$flickity.toggleFullscreen();
      }
      }
      this.x = 0;
      this.y = 0;
    },
    addListeners() {
      setTimeout(() => {
        if (isMobile()) {
          this.$refs.flickity.on("fullscreenChange", function(isFullscreen) {
            if (isFullscreen) {
              history.pushState(null, null, "#gallery");
            } else if (window.location.hash == "#gallery") {
              window.history.back();
            }
          });
          window.addEventListener("hashchange", () => {
            if (this.$refs.flickity != undefined) {
              if (this.$refs.flickity.$flickity.isFullscreen) {
                this.$refs.flickity.$flickity.toggleFullscreen();
                return;
              }
            } else {
              return;
            }
          });
        }
      }, 10);
    }
  },
  created() {
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
          this.addListeners();
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
  }
};
</script>


<style lang="css">
.fb-comments iframe {
  width: 100% !important;
}

.image {
  width: auto;
  height: 100%;
  /* object-fit: contain; */
  object-fit: contain;
}

.image-text {
  display: flex;
  position: relative;
  top: -50%;
  transform: translate(0%, -50%);
  color: white;
}

.carousel-cell {
  width: auto;
  height: 400px;
  /* max-height: 95vw; */
  border-radius: 6px;
  margin-right: 6px;
}

.is-fullscreen .carousel-cell {
  background-color: rgba(0, 0, 0, 0);
  width: 100vw;
  height: 100%;
}

.is-fullscreen .image {
  margin: auto;
  width: 100%;
  height: 100%;
}

.flickity-enabled.is-fullscreen {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: hsla(0, 0%, 0%, 0.9);
  padding-bottom: 35px;
  z-index: 10000;
}

.flickity-enabled.is-fullscreen .flickity-page-dots {
  bottom: 10px;
}

.flickity-enabled.is-fullscreen .flickity-page-dots .dot {
  background: white;
}

/* prevent page scrolling when flickity is fullscreen */
html.is-flickity-fullscreen {
  overflow: hidden;
}

/* ---- flickity-fullscreen-button ---- */

.flickity-fullscreen-button {
  display: block;
  right: 10px;
  top: 10px;
  width: 24px;
  height: 24px;
  border-radius: 4px;
}

/* right-to-left */
.flickity-rtl .flickity-fullscreen-button {
  right: auto;
  left: 10px;
}

.flickity-fullscreen-button-exit {
  display: none;
}

.flickity-enabled.is-fullscreen .flickity-fullscreen-button-exit {
  display: block;
}
.flickity-enabled.is-fullscreen .flickity-fullscreen-button-view {
  display: none;
}

.flickity-fullscreen-button .flickity-button-icon {
  position: absolute;
  width: 16px;
  height: 16px;
  left: 4px;
  top: 4px;
}
</style>

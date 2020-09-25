<template>
  <section class="clean-block p-0 py-5 dark">
    <b-container>
      <div class="d-flex justify-content-between">
        <h2>{{ branchName }}</h2>
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

          <vue-preview
            :key="currentAlbum"
            :slides="branch.albums[currentAlbum].images"
          ></vue-preview>
          <b-overlay class="mt-3" opacity="1" variant="white">
            <flickity
              v-images-loaded="loaded"
              class="carousel"
              ref="flickity"
              :options="flickityOptions"
              @init="onInit"
              v-if="branch.albums.length > 1"
            >
              <div
                v-for="(album, albumIndex) in branch.albums"
                :key="`${albumIndex}`"
                class="carousel-cell"
              >
                <p class="album-text">{{ album.name }}</p>
                <img class="image" :src="album.images[0].msrc" alt />
              </div>
            </flickity>
          </b-overlay>
        </div>
        <hr class="mt-5" />
        <h2>Kalender</h2>
        <calendarViewer
          v-bind:ready="calendarReturned"
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
import Flickity from "vue-flickity";
import imagesLoaded from "vue-images-loaded";
import "flickity-fullscreen";

export default {
  directives: {
    imagesLoaded,
  },
  name: "branch",
  props: ["branchName"],
  components: { calendarViewer, quillViewer, Flickity },
  data() {
    return {
      branch: {},
      currentAlbum: 0,
      returned: false,
      calendarReturned: false,
      events: [],
      drag: false,
      x: 0,
      y: 0,
      flickityOptions: {
        initialIndex: 0,
        prevNextButtons: true,
        pageDots: true,
        contain: false,
        autoPlay: false,
        imagesLoaded: true,
        dragThreshold: 10,
        freeScroll: false,
      },
      imagesLoading: true,
      facebookLoading: true,
      style: null
    };
  },
  methods: {
    checkImageAmount() {
      if (this.currentAlbum != null && this.branch.albums[this.currentAlbum] != null) {
        let images = this.branch.albums[this.currentAlbum].images.length;
        if (images > 5) {
          this.style.innerHTML = `.my-gallery  :nth-child(4) a:after {content: '${
            images - 5
          }+';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          text-align: center;
          vertical-align: middle;
          }
          .my-gallery  :nth-child(4) a:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            top: 0;
            bottom: 0;
          }`;
        }else{
          this.style.innerHTML = "";
        }
        if (images > 4) {
          this.style.innerHTML += `.my-gallery :nth-child(4) {
            grid-row: span 2
          }`;
          
        }
      }
    },
    showPhotoSwipe(index) {
      this.isOpen = true;
      this.$set(this.options, "index", index);
    },
    hidePhotoSwipe() {
      this.isOpen = false;
    },
    loaded() {
      this.imagesLoading = false;
      this.$refs.flickity.reloadCells();
      this.$refs.flickity.select(0);
    },
    onInit() {
      this.$refs.flickity.on("change", (event) => {
        this.currentAlbum = event;
        this.checkImageAmount();
      });
      this.$refs.flickity.on("staticClick", (_, __, ___, cellIndex) => {
        this.$refs.flickity.select(cellIndex);
      });
    },
  },
  created() {
    this.style = document.createElement("style");
    document.head.appendChild(this.style);
    axios
      .post("/branch/get", { branchName: this.branchName })
      .then((response) => {
        this.returned = true;
        if (response.status === 201) {
          if (response.data == null) {
            router.push("/error/404");
          }
          this.branch = response.data;
          if (this.branch.albums.length > 0) {
            this.branch.albums = this.branch.albums.filter(
              (a) => a.images.length > 0
            );
            this.branch.albums.forEach((a) => {
              if (a.images.length === 0) {
                return;
              }
              let images = a.images;
              a.images = [];
              images.forEach((i) => {
                a.images.push({
                  src: i.url,
                  msrc: i.url.replace("upload/", "upload/q_20/"),
                  w: i.w,
                  h: i.h,
                });
              });
            });
            this.checkImageAmount();
          }
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
:root {
  --gutter: 1px;
  --rh: calc((var(--wrapperWidth) - (11 * var(--gutter))) / 12);
}

.grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-gap: var(--gutter);
}

@for $i from 1 through 12 {
  .grid.column-#{$i} {
    grid-template-columns: repeat($i, 1fr);
    --rh: calc((var(--wrapperWidth) - ((#{$i} - 1) * var(--gutter))) / #{$i});
  }
}

.grid.square {
  grid-auto-rows: minmax(var(--rh), auto);
}

.grid-item {
  position: relative;
}

@for $i from 1 through 12 {
  .grid-item.width-#{$i} {
    grid-column: span $i;
  }
}

@for $i from 1 through 12 {
  .grid-item.row-#{$i} {
    grid-row: span $i;
  }
}

.grid-item.no-padding {
  padding: 0;
}

.grid-item .full-img,
.grid-item.full-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.fb-comments iframe {
  width: 100% !important;
}

.album-text {
  /* position: absolute;
  left: 50%;
    transform: translate(-50%, 0%) */

  position: absolute;
  top: 0;
  padding-top: 10px;
  left: 0;
  right: 0;
  height: 100%;
  width: 100%;
  bottom: 0;
  text-align: center;
  color: #fff;
  font-size: 1.5rem !important;
  background-color: rgba(0, 0, 0, 0.4);
}

.my-gallery figure {
  align-items: center;
  width: 80%;

  margin: 0;

  @extend .grid-item;
}

.my-gallery img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  // border-radius: 20px;
}

.my-gallery {
  // justify-content: center;
  // display: flex;
  // flex-wrap: wrap;
  // margin: auto;
  // margin-bottom: 1rem;
  @extend .grid;
  @extend .square;
  @extend .column-2;
}

.my-gallery :nth-child(1) {
  @extend .row-3;
  margin-left: auto;
}

.my-gallery :nth-child(5) {
  margin-left: auto;
}

.my-gallery :nth-child(5) img {
  height: 20vh;
}

.my-gallery :nth-child(3) img {
  max-height: 20vh;
}

.my-gallery :nth-child(4) a {
  height: 100%;
  display: flex;
  position: relative;
  color: white;
  font-size: 5rem;
  &:hover {
    text-decoration: none;
    color: white;
  }
}

.my-gallery :nth-child(n + 6) {
  display: none !important;
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
  height: 100px;
  /* max-height: 95vw; */
  border: 1px;
  margin-right: 6px;
  cursor: pointer;
}

.carousel-cell .image,
.carousel-cell .album-text {
  border-radius: 10px;
}
</style>

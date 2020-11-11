<template>
  <div>
    <imageViewer
      :key="currentAlbum"
      :images="mutatedAlbums[currentAlbum].images"
    ></imageViewer>
    <b-overlay class="mt-3" opacity="1" variant="white">
      <flickity
        v-images-loaded="loaded"
        class="album-carousel"
        ref="flickity"
        :options="flickityOptions"
        @init="onInit"
        v-if="mutatedAlbums.length > 1"
      >
        <div
          v-for="(album, albumIndex) in mutatedAlbums"
          :key="`${albumIndex}`"
          class="album-carousel-cell"
        >
          <p :style="imageString(album.images[0].msrc)" class="album-text" v-html="getName(album)"></p>
        </div>
      </flickity>
    </b-overlay>
  </div>
</template>

<script>
import imageViewer from "@/components/content/imageViewer";
import Flickity from "vue-flickity";
import imagesLoaded from "vue-images-loaded";

export default {
  name: "albumViewer",
  props: ["albums"],
  directives: {
    imagesLoaded,
  },
  components: { Flickity, imageViewer },
  data() {
    return {
        mutatedAlbums: this.albums,
      imagesLoading: true,
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
      currentAlbum: 0,
    };
  },
  created() {
    if (this.mutatedAlbums.length > 0) {
      this.mutatedAlbums = this.mutatedAlbums.filter(
        (a) => a.images.length > 0
      );
      this.mutatedAlbums.forEach((a) => {
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
    }
  },
  methods: {
    loaded() {
      this.imagesLoading = false;
      this.$refs.flickity.reloadCells();
      this.$refs.flickity.select(0);
    },
    onInit() {
      this.$refs.flickity.on("change", (event) => {
        this.currentAlbum = event;
      });
      this.$refs.flickity.on("staticClick", (_, __, ___, cellIndex) => {
        this.$refs.flickity.select(cellIndex);
      });
    },
<<<<<<< HEAD
    imageString(img){
      return "background-image: url('" + img + "')"; 
    },
    getName(album){
      let str = album.name.replace(/ /, "<br>");
      return str.replace(/ /, "<br>");

    }
=======
>>>>>>> f6ac8843627dfa066d1fefeb463a026b9eec98b5
  },
};
</script>

<style>
.album-text {
<<<<<<< HEAD
  /* position: absolute; */
  opacity: 1 !important;
  background-size: cover;
  padding-top: 0.5em;
  padding-left: 1em;
  padding-right: 1em;
  line-height: 1em;
  height: 100%;
  width: 100%;
=======
  position: absolute;
  top: 0;
  padding-top: 10px;
  left: 0;
  right: 0;
  height: 100%;
  width: 100%;
  bottom: 0;
>>>>>>> f6ac8843627dfa066d1fefeb463a026b9eec98b5
  text-align: center;
  color: #fff;
  font-size: 1.5rem !important;
  background-color: rgba(0, 0, 0, 0.4);
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

.album-carousel-cell {
  width: auto;
  height: 100px;
  /* max-height: 95vw; */
  border: 1px;
  margin-right: 6px;
  cursor: pointer;
}

.album-carousel-cell .image,
.album-carousel-cell .album-text {
  border-radius: 10px;
}
</style>
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
          <p class="album-text">{{ album.name }}</p>
          <img class="image" :src="album.images[0].msrc" alt />
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
  },
};
</script>

<style>
.album-text {
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
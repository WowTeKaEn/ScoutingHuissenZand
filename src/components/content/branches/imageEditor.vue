<template>
  <b-card class="mb-3">
    <h1>Foto's</h1>
        <imageUpload @saveImage="addToImages" v-bind:branchName="branch.branchName"></imageUpload>
      <hr />
      <b-container>
        <b-row align-v="center">
          <b-col class="img-col p-1" v-for="image in images" :key="image.url">
              <b-overlay class="d-flex img-controls-out" :show="image.deleting" rounded="sm">
                <div class="img-smooth" :style="{backgroundImage: `url('${image.url}')`}"></div>
                <b-button
                  class="img-controls m-auto"
                  @click="removeImage(image)"
                  variant="danger"
                >Verwijderen</b-button>
                
              </b-overlay>
          </b-col>
        </b-row>
      </b-container>
  </b-card>
</template>

<script>
import axios from "@/plugins/axios.js";
import imageUpload from "./imageUpload.vue";

export default {
  name: "imageEditor",
  props: ["user", "branch"],
  components: { imageUpload },
  data() {
    return {
      images: []
    };
  },
  created(){
    this.branch.images.forEach(img => {
      this.images.push({ branchName: this.branch.branchName, url: img.url, deleting:false, });
    })
  },
  methods: {
    addToImages(image) {
      this.images.push({ branchName: this.branch.branchName, url: image, deleting:false, });
    },
    removeImage(image) {
      this.images.forEach(i => {
        if(i.url == image.url){
          i.deleting = true;
        }
      })
      axios
        .post("/branch/photo/delete", {
          branchName: this.branch.branchName,
          image: image.url
        })
        .then(response => {
          this.uploading = false;
          if (response.status == 200) {
            this.images = this.images.filter(i => i.url != image.url);
          } else {
            this.$bvToast.toast("Unknown", {
              title: "Error",
              autoHideDelay: 1000,
              appendToast: true
            });
          }
        })
        .catch(error => {
          this.uploading = false;
          if (error.response.status === 401) {
            this.$bvToast.toast("Unauthorised", {
              title: "Error",
              autoHideDelay: 1000,
              appendToast: true
            });
          } else {
            this.$bvToast.toast(error + "", {
              title: "Error",
              autoHideDelay: 1000,
              appendToast: true
            });
          }
        });
    }
  },
};
</script>


<style>
.img-smooth {
  background-size: cover;
  background-repeat: no-repeat;
  background-position: 50% 50%;
  margin-left: 0.25em;
  margin-right: 0.25em;
  width: 100%;
  height: 200px;
  display: inline-block;
  border-radius: 6px !important;
}

.img-col {
  min-width: 25%;
  max-width: 50%;
}

@media only screen and (max-width: 750px) {
  .img-col {
    min-width: 50%;
  }
}

@media only screen and (max-width: 450px) {
  .img-col {
    min-width: 100%;
  }
}

.img-controls {
  display: none;
  text-align: center;
}

.img-controls-out:hover .img-controls {
  display: inline;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
.img-controls-out div {
  transition: 0.3s;
}

.img-controls-out:hover div {
  opacity: 0.8;
}
</style>


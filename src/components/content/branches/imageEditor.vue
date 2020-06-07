<template>
  <b-card v-if="branches.length != 0" class="mb-3">
    <h1>Foto's voor speltakken</h1>
    <b-form novalidate @submit.stop.prevent>
      <div class="form-group">
        <b-form-select v-model="getBranch">
          <template v-slot:first>
            <b-form-select-option disabled :value="null">-- Kies een speltak --</b-form-select-option>
          </template>
          <b-form-select-option
            v-for="branch in branches"
            :key="branch.branchName"
            :value="branch.branchName"
          >{{ branch.branchName }}</b-form-select-option>
        </b-form-select>
      </div>
      <div :key="branch" v-if="getBranch != null">
        <imageUpload @saveImage="addToImages" v-bind:branchName="getBranch"></imageUpload>
      </div>
      <hr />
      <b-container>
        <b-row align-v="center">
          <b-col class="img-col p-1 " v-for="image in images" :key="image.url">
            <div class=" d-flex img-controls-out">
              <div class="img-smooth" :style="{
					backgroundImage: `url('${image.url}')`,
				}"></div>
              <b-button class="img-controls m-auto" @click="removeImage(image)" variant="danger">Verwijderen</b-button>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </b-form>
  </b-card>
</template>

<script>
import axios from "@/plugins/axios.js";
import imageUpload from "./imageUpload.vue";

export default {
  name: "events",
  props: ["user", "branches"],
  components: { imageUpload },
  data() {
    return {
      branch: null,
      returnedImages: false,
      images: []
    };
  },
  methods: {
    addToImages(image) {
      this.images.push({ branchName: this.getBranch, url: image });
    },
    removeImage(image){
      
      axios
          .post("/branch/photo/delete", {
            branchName: this.getBranch,
            image: image.url
          })
          .then(response => {
            this.uploading = false;
            if(response.status == 200){
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
  computed: {
    getBranch: {
      get: function() {
        return this.branch;
      },
      set: function(val) {
        this.branch = val;
        this.images = axios
          .post("/branch/get", { branchName: this.getBranch })
          .then(response => {
            this.returnedImages = true;
            this.images = response.data.images;
          })
          .catch(() => {
            this.returnedImages = true;
          });
      }
    }
  }
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
.img-controls-out div{
transition: 0.3s;
}

.img-controls-out:hover div{
  opacity: 0.8;
}
</style>


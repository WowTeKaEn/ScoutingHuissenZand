<template>
  <b-form-group class="m-0">
    <b-overlay :show="uploading" rounded="sm">
      <b-form-file
        placeholder="Geen bestanden geselecteerd"
        class="w-100"
        accept="image/*"
        @input="setPreview"
        id="file-default"
        multiple
      ></b-form-file>
      <div v-if="images" class="my-3 d-flex justify-content-center">
        <div class="w-50">
         <span class="img-text" v-if="amount > 1"> {{ amount }}</span>
          <b-img class="img-upload" fluid-grow :src="imageUrl" alt="null"></b-img>
        </div>
      </div>
      <div v-if="images" class="mt-3 d-flex w-100">
        <b-button @click="saveImages" class="mx-auto mb-3" variant="primary">Opslaan</b-button>
      </div>
    </b-overlay>
    <b-progress v-if="uploading"  height="2px"  :value="progress" :max="120"></b-progress>
  </b-form-group>
</template>


<style>
.img-upload {
  margin-left: 0.25em;
  margin-right: 0.25em;
  width: auto;
  height: 100%;
  border-radius: 6px !important;
}

.custom-file-label::after {
  content: "Zoeken" !important;
}
</style>

<script>
import axios from "@/plugins/axios.js";
import Vue from "@/main.js"

export default {
  name: "imageUpload",
  props: ["album","index"],
  data() {
    return {
      images: null,
      uploading: false,
      progress: 0,
      amount: 0
    };
  },
  methods: {
    setPreview(files) {
      this.images = files;
      this.amount = files.length
    },
    saveImages() {
      var temp = this;
      const config = {
        onUploadProgress: function(progressEvent) {
          temp.progress = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );       
        }
      };
      this.uploading = true;
      var formData = new FormData();
      for(let i = 0; i < this.images.length; i++){
        formData.append(`files[${i}]`,this.images[i])
      }

      axios
        .post("/albums/" + this.album.id, formData, config)
        .then(response => {
          this.uploading = false;
          this.progress = 0;
          if (response.status == 200) {
            this.$emit("saveImages", response.data,this.index);
          } else {
            this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
          }
        })
        .catch(error => {
          this.progress = 0;
          this.uploading = false;
          this.$bvToast.toast(error + "", Vue.toastObject("Error"));
        });
    }
  },
  computed: {
    imageUrl() {
      return URL.createObjectURL(this.images[0]);
    }
  }
};
</script>


<style>

.img-text{
  position: absolute;
  z-index: 1000;
    top: 42%;
    left: 48.7%;
  color: white;
  font-size: 2rem;
}
  
</style>
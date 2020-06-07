<template>
  <b-form-group>
    <b-overlay :show="uploading" rounded="sm">
    <b-form-file placeholder="Geen bestanden geselecteerd"  class="w-100" accept="image/*" @input="setPreview" id="file-default"></b-form-file>
    <div  v-if="image" class="my-3 d-flex justify-content-center">
      <div class="w-50">
        <b-img class="img-upload" fluid-grow :src="imageUrl" alt="null"></b-img>
      </div>
    </div>
    <div v-if="image" class="mt-3 d-flex w-100">
        <b-button @click="saveImage" class="mx-auto" variant="primary">Opslaan</b-button>
    </div>
    </b-overlay>
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
  content: 'Zoeken' !important;
}
</style>

<script>
import axios from "@/plugins/axios.js";

export default {
  name: "imageUpload",
  props: ["branchName"],
  data() {
    return {
      image: null,
      uploading: false,
    };
  },
  methods: {
    setPreview(file) {
      this.image = file;
    },
    saveImage(){
      this.uploading = true
      var formData = new FormData();
      formData.append("file", this.image);
      formData.append("branchName", this.branchName);

        axios
          .post("/branch/photo/upload", formData)
          .then(response => {
            this.uploading = false;
            if(response.status == 201){
            this.$emit('saveImage',URL.createObjectURL(this.image));
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
  computed:{
      imageUrl(){
          return URL.createObjectURL(this.image)
      }
  }
};
</script>
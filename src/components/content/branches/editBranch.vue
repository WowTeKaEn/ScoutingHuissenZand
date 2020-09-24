<template>
  <b-card class="mb-3">
    <b-form novalidate @submit.stop.prevent>
      <h2>Speltak aanpassen</h2>
      <p>{{ branch.branchName }}</p>
      <editor :key="branch.branchName" ref="editor"  v-bind:description="branch.branchDescription"></editor>
      <b-input class="mt-3" v-model="branch.instaUsername" placeholder="Instagram Gebruikersnaam"></b-input>
      <b-input class="mt-3" v-model="branch.facebookUsername" placeholder="Facebook Gebruikersnaam"></b-input>
      <p class="mt-3">Gebruikers namen worden gebruikt als links en om foto's op te halen. Facebook heeft prioriteit omdat deze hogere kwaliteit foto's heeft.
        Check na het invullen de homepagina of speltak pagina of deze goed werkt.
      </p>
      <b-form-checkbox
        v-model="branch.visible"
        value="1"
        unchecked-value="0"
      >Zichtbaar</b-form-checkbox>
      <div class="form-group d-flex justify-content-end mb-0 mt-3">
        <b-button variant="primary" v-on:click="attemptToSubmit">
          <span v-if="!submitting">Opslaan</span>
          <span v-else>
            <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
          </span>
        </b-button>
      </div>
    </b-form>
  </b-card>
</template>

<script>
import axios from "@/plugins/axios.js";
import editor from "../editor/editor";
import Vue from "@/main.js"


export default {
  name: "editBranch",
  props: ["branch"],
  components: { editor},
  data() {
    return {
      submitting: false,
    };
  },
  methods: {
    attemptToSubmit() {
      this.branchDescription = this.$refs.editor.editDescription;
      this.submitting = true;
      if(this.branch.instaUsername == null){
        this.branch.instaUsername = ""
      }
      if(this.branch.facebookUsername == null){
        this.branch.facebookUsername = ""
      }
      axios
        .post("/branch/update", {
          branchName: this.branch.branchName,
          branchDescription: this.$refs.editor.getDelta(),
          instaUsername: this.branch.instaUsername,
          facebookUsername: this.branch.facebookUsername,
          visible: this.branch.visible,
          images: this.$refs.editor.images,
          deletedImages: this.$refs.editor.deletedImages,
        })
        .then(response => {
          this.submitting = false;
          if (response.status == 200) {
            this.$bvToast.toast("Bestaande speltak aangepast", Vue.toastObject("Succes"));
          } else {
            this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
          }
        })
        .catch(error => {
          this.submitting = false;
          if (error.response.status === 401) {
            this.$bvToast.toast("Ongemachtigd", Vue.toastObject("Error"));
          } else if (error.response.status == 403) {
            this.$bvToast.toast("Tak niet aangepast", Vue.toastObject("Error"));
          } else {
            this.$bvToast.toast(error + "", Vue.toastObject("Error"));
          }
        });
    }
  }
};
</script>
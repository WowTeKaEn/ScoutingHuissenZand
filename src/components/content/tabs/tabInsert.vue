<template>
  <b-card class="mb-3">
    <b-form novalidate @submit.stop.prevent>
      <h2>Tab aanmaken</h2>
      <div class="form-group">
        <b-input
          v-model="tabName"
          type="text"
          class="form-control"
          name="tabName"
          placeholder="Tab Naam"
        ></b-input>
      </div>
      <editor ref="editor" :key="tabKey" v-bind:description="tabDescription"></editor>
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
import editor from "../editor/editor"
// import router from "@/router/index.js"

export default {
  name: "tabInsert",
  components: { editor },
  data() {
    return {
      submitting: false,
      tabName: null,
      tabDescription: null,
      
      tabKey: 0
    };
  },
  props: ["tabs",],
  methods: {
    setEditTab(tabName, tabDescription){
      this.tabKey += 1;
      this.tabName = tabName;
      this.tabDescription = tabDescription;
    },
    attemptToSubmit() {
      this.tabDescription = this.$refs.editor.editDescription;
      if (this.tabName !== null && this.tabDescription !== "") {
        this.submitting = true;
        axios
          .post("/tab/insert", {
            tabName: this.tabName,
            tabDescription: this.$refs.editor.getDelta(),
            images: this.$refs.editor.images,
            deletedImages: this.$refs.editor.deletedImages,
          })
          .then(response => {
            this.submitting = false;
            if (response.status == 201) {
              this.tabs.push({ tabName: this.tabName });
              this.$bvToast.toast("Tab toegevoegd", {
                title: "Succes",
                autoHideDelay: 5000,
                appendToast: true
              });
            }else if(response.status == 200){
              this.$bvToast.toast("Bestaande tab aangepast", {
                title: "Succes",
                autoHideDelay: 5000,
                appendToast: true
              });
            } else {
              this.$bvToast.toast("Unknown", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            }
          })
          .catch(error => {
            this.submitting = false;
            if (error.response.status === 401) {
              this.$bvToast.toast("Unauthorised", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            } else if (error.response.status == 400) {
              this.$bvToast.toast("Tab niet toegevoegd", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true,
              });
            } else {
              this.$bvToast.toast(error + "", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            }
          });
      } else {
        this.$bvToast.toast("Vul alle velden in.", {
          title: "Error",
          autoHideDelay: 5000,
          appendToast: true
        });
      }
    }
  }
};
</script>


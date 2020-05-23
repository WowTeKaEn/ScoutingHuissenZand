<template>
  <b-card class="mb-3" v-if="tabs !== null && tabs.length !== 0">
    <h2>Tab aanpassen</h2>
    <div v-for="tab in tabs" :key="tab.tabName" class="d-flex justify-content-between mb-2">
      <span class="my-auto">{{ tab.tabName }}</span>
      <div class="d-flex">
        <b-button variant="primary" class="mr-3" @click="editTab(tab)">
          <span v-if="!isLoading(tab)">Aanpassen</span>
          <span v-else>
            <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
          </span>
        </b-button>
        <b-button class="btn-danger" @click="deleteTab(tab)">Verwijder</b-button>
      </div>
    </div>
  </b-card>
</template>

<script>
import axios from "@/plugins/axios.js";
// import router from "@/router/index.js"
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";

export default {
  name: "loginForm",
  props: ["tabs"],

  methods: {
    isLoading(tab) {
      if (tab.loading == null) {
        return false;
      } else if (tab.loading) {
        return true;
      }
      return false;
    },
    deleteTab(tab) {
      tab.loading = true;
      axios
        .post("/tab/delete",{tabName: tab.tabName})
        .then(response => {
          tab.loading = false;
          if (response.status === 200) {
            for (var i = 0; i < this.tabs.length; i++) {
              if (this.tabs[i].tabName === tab.tabName) {
                this.tabs.splice(i, 1);
              }
            }
          } else {
            this.$bvToast.toast("Unknown", {
              title: "Error",
              autoHideDelay: 5000,
              appendToast: true
            });
          }
        })
        .catch(error => {
          tab.loading = false;
          this.$bvToast.toast(error + "", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        });
    },
    editTab(tab) {
      axios
        .post("/tab/get",{tab: tab.tabName})
        .then(response => {
          this.returned = true;
          if (response.status === 201) {
            var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(response.data.tabDescription),
            {multiLineParagraph: false, multiLineBlockquote: false, multiLineHeader: false, multiLineCodeblock: false}
          );
            tab.tabDescription = converter.convert();
            this.$emit("editTab", tab);
          }
        })
        .catch(error => {
          this.$bvToast.toast(error + "", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        });
    }
  }
};
</script>


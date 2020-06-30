<template>
    <section class="clean-block p-0 py-5 dark">
      <b-container>
        <h1>{{ tab }}</h1>
        <b-card class="clean-card">
          <quillViewer v-bind:ready="tabDescription !== null" v-bind:quill="tabDescription"></quillViewer>
        </b-card>
      </b-container>
    </section>
</template>




<style lang="css">
.clean-card p {
  opacity: 1;
}
</style>


<script>
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import quillViewer from "@/components/content/editor/quillViewer";

export default {
  name: "tab",
  props: ["tab"],
  components:{quillViewer},
  data() {
    return {
      tabDescription: null
    };
  },
  created() {
    axios
      .post("/tab/get", {tab: this.tab})
      .then(response => {
        this.returned = true;
        if (response.status === 201) {
          if (response.data.tabDescription == null) {
            router.push("/error/404");
          }
          this.tabDescription = response.data.tabDescription;
        } else {
          router.push("/error/404");
        }
      })
      .catch(error => {
        if (error.status == 404) {
          router.push("/error/404");
        } else {
          this.$bvToast.toast(error + "", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        }
      });
  },
};
</script>
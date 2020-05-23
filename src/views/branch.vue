<template>
  <main class="page">
    <section class="clean-block p-0 py-5 dark">
      <b-container>
        <h1>{{ branchName }}</h1>
        <b-card class="clean-card">
          <article v-if="returned" class="ql-editor" v-html="compiledHtml"></article>
          <div v-else class="d-flex justify-content-center">
            <span class="my-auto">
              <b-spinner variant="primary" label="Spinning"></b-spinner>
            </span>
          </div>
        </b-card>
        <teammember class="mt-5" v-if="returned" v-bind:branch="branch"></teammember>
        <div
          class="fb-comments"
          :data-href="'http://www.scoutinghuissenzand.space/#scoutinghuissenzand'+ branchName"
          data-numposts="10"
          data-width="fluid"
        ></div>
      </b-container>
    </section>
  </main>
</template>

<style lang="css">
@import "~vue2-editor/dist/vue2-editor.css";

/* Import the Quill styles you want */
@import "~quill/dist/quill.core.css";
@import "~quill/dist/quill.bubble.css";
@import "~quill/dist/quill.snow.css";

.card img {
  padding: 0.25rem;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  max-width: 100%;
  opacity: 1;
}

.page {
  background-color: #28a745;
}

.fb-comments iframe {
  width: 100% !important;
}
</style>

<script>
import Vue from "vue";
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";
import teammember from "@/components/teammember.vue";

export default {
  name: "branch",
  props: ["branchName"],
  components: { teammember },
  data() {
    return {
      branch: {},
      returned: false,
    };
  },
  created() {
    axios
      .post("/branch/get", { branchName: this.branchName })
      .then(response => {
        this.returned = true;
        if (response.status === 201) {
          if (response.data == null) {
            router.push("/error/404");
          }
          var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(response.data.branchDescription),
            {multiLineParagraph: false, multiLineBlockquote: false, multiLineHeader: false, multiLineCodeblock: false}
          );
          response.data.branchDescription = converter.convert();
          this.branch = response.data;
        } else {
          router.push("/error/404");
        }
      })
      .catch(error => {
        this.$bvToast.toast(error + "", {
          title: "Error",
          autoHideDelay: 5000,
          appendToast: true
        });
        router.push("/error/404");
      });
    Vue.loadScript("https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4").then(() => {
      window.FB.XFBML.parse()
    })

    
  },
  computed: {
    compiledHtml: function() {
      return this.branch.branchDescription;
    }
  }
};
</script>


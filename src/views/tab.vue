<template>
  <main class="page">
    <section class="clean-block p-0 py-5 dark">
      <b-container>
        <h1>{{ tab }}</h1>
        <b-card class="clean-card">
          <article v-if="tabDescription !== null" class="ql-editor" v-html="compiledHtml"></article>
          <div v-else class="d-flex justify-content-center">
            <span  class="my-auto">
              <b-spinner variant="primary" label="Spinning"></b-spinner>
            </span>
          </div>
        </b-card>
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

img {
  padding: 0.25rem;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  max-width: 100%;
}

.clean-card p {
  opacity: 1;
}
</style>


<script>
import axios from "@/plugins/axios.js";
import router from "@/router/index.js";
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";

export default {
  name: "tab",
  props: ["tab"],
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
          var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(response.data.tabDescription),
            {multiLineParagraph: false, multiLineBlockquote: false, multiLineHeader: false, multiLineCodeblock: false}
          );
          this.tabDescription = converter.convert();
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
  computed: {
    compiledHtml: function() {
      return this.tabDescription;
    }
  }
};
</script>
<template>
<div>
  <b-overlay :show="disabled" rounded="sm">
    <vue-editor
      ref="editor"
      :customModules="customModulesForEditor"
      :editorOptions="editorSettings"
      v-model="editDescription"
      :useCustomImageHandler="true"
      @image-added="handleImageAdded"
      @image-removed="handleImageRemoved"
      :disabled="disabled"
    ></vue-editor>
  </b-overlay>
  <b-progress v-if="disabled"  height="2px"  :value="progress" :max="120"></b-progress>
</div>
</template>

<style lang="css">
@import "~vue2-editor/dist/vue2-editor.css";

/* Import the Quill styles you want */
@import "~quill/dist/quill.core.css";
@import "~quill/dist/quill.bubble.css";
@import "~quill/dist/quill.snow.css";
.ql-editor img{
  max-width: 100%;
  height: auto;
}

</style>

<script>
import { VueEditor } from "vue2-editor";
import ImageResize from "quill-image-resize";
import axios from "@/plugins/axios.js";
import Vue from "@/main.js"

 
export default {
  name: "editor",
  components: { VueEditor },
  props: ["description"],
  data() {
    return {
      customModulesForEditor: [{ alias: "imageResize", module: ImageResize }],
      editorSettings: {
        modules: {
          imageResize: { modules: ["Resize", "DisplaySize"] },
        }
      },
      disabled: false,
      editDescription: this.description,
      images: [],
      deletedImages: [],
      progress: 0,
    };
  },
  methods: {
    handleImageAdded(file, Editor, cursorLocation, resetUploader) {
      var temp = this;
      var config = {
        onUploadProgress: function(progressEvent) {
          temp.progress = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );      
        }
      }



      console.log("handling image upload");
      var formData = new FormData();
      formData.append("file", file);
      this.disabled = true;
      axios
        .post("/images", formData, config)
        .then(result => {
          this.progress = 0;
          this.disabled = false;
          let url = result.data.url; // Get url from response
          this.images.push(result.data.public_id);
          Editor.insertEmbed(cursorLocation, "image", url);
          resetUploader();
          console.log("handled image upload");
        })
        .catch(err => {
          this.disabled = false;
          this.$bvToast.toast(err + "", Vue.toastObject("Error"));
        });
    },
    handleImageRemoved(file) {
      if (this.description ) {
        this.deletedImages.push(file);
      } else {
        console.log("handling image deletion");
        axios
          .delete("/images",{
            data:{
              image: file
            }
          })
          .then(() => {
            console.log("handled image deletion");
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    getDelta() {
      return JSON.stringify(this.$refs.editor.quill.editor.delta.ops);
    },
  },
};
</script>
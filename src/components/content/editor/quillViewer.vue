<template>    
<article v-if="ready" class="ql-editor" v-html="getHtml"></article>
<div v-else class="d-flex justify-content-center">
            <span class="my-auto">
              <b-spinner variant="primary" label="Spinning"></b-spinner>
            </span>
          </div>
</template>


<style>

@import "~vue2-editor/dist/vue2-editor.css";

/* Import the Quill styles you want */
 @import "~quill/dist/quill.core.css";

.clean-card .ql-editor p{
opacity: 1!important;
}

 .ql-image{
     padding: 0.25rem;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
     max-width: 100%;
opacity: unset;
height: auto;
  
 }

 .ql-editor{
  height: unset;
 }

</style>

<script>
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";


export default {
    name: "quillViewer",
    props:["ready","quill"],
    computed:{
        getHtml(){
            var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(this.quill),
            {
              multiLineParagraph: false,
              multiLineBlockquote: false,
              multiLineHeader: false,
              multiLineCodeblock: false
            }
          );
          return converter.convert();
        }
    }
}
</script>
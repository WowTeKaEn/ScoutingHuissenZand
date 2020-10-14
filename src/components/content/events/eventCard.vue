<template>
  <b-card :title="event.title">
    <b-card-text>
      <quillViewer v-bind:ready="true" v-bind:quill="event.description"></quillViewer>
    </b-card-text>
  </b-card>
</template>


<script>

import quillViewer from "@/components/content/editor/quillViewer";

export default {
  props: ["event"],
  components:{quillViewer}
};
</script>

<style scoped>
.ql-editor{
    min-height: unset;
}
</style>
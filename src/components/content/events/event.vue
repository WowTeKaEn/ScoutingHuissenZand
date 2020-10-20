<template>
  <div class="d-flex flex-column form-group">
    <b-form-input
      v-if="newCheckEventName == null"
      v-model="eventName"
      placeholder="Evenement naam"
    ></b-form-input>
    <editor class="mt-3" ref="editor" v-bind:description="getHtml"></editor>
    <b-form-checkbox v-model="visible" value="1" unchecked-value="0"
      >Zichtbaar</b-form-checkbox
    >
  </div>
</template>

<script>
import editor from "../editor/editor";
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";
import axios from "@/plugins/axios.js";
import Vue from "@/main.js";

export default {
  name: "event",
  props: ["event", "events", "branch"],
  components: { editor },
  data() {
    return {
      eventName: this.event.title,
      newCheckEventName: this.event.title,
      visible: this.event.extendedProps.visible,
      dateFormat: "D MMM",
      submitting: false,
      editingEvent: this.event,
    };
  },
  methods: {
    attemptToSubmit() {
      if (this.eventName  && this.$refs.editor.editDescription ) {
        this.submitting = true;
        axios
          .put("/event/" + this.branch, {
            eventName: this.eventName,
            eventDescription: this.$refs.editor.getDelta(),
            startDate: this.editingEvent.start,
            endDate: this.editingEvent.end,
            visible: this.visible,
            images: this.$refs.editor.images,
            deletedImages: this.$refs.editor.deletedImages,
          })
          .then((response) => {
            this.submitting = false;
            if (response.status == 201) {
              this.events.push({
                title: this.eventName,
                start: this.editingEvent.start,
                end: this.editingEvent.end,
                extendedProps: {
                  description: this.$refs.editor.getDelta(),
                  visible: this.visible,
                },
              });
              this.$emit("correct-submit", response.status);
            } else if (response.status == 200) {
              this.event.setExtendedProp(
                "description",
                this.$refs.editor.getDelta()
              );
              this.event.setExtendedProp("visible", this.visible);
              this.$emit("correct-submit", response.status);
            } else {
              this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
            }
          })
          .catch(() => {
            this.submitting = false;
            this.$bvToast.toast(
                "Evenement niet toegevoegd",
                Vue.toastObject("Error")
              );
          });
      } else {
        this.$bvToast.toast("Vul alle velden in.", Vue.toastObject("Error"));
      }
    },
  },
  computed: {
    getHtml() {
      if (
        this.editingEvent  &&
        this.editingEvent.extendedProps.description 
      ) {
        var converter = new QuillDeltaToHtmlConverter(
          JSON.parse(this.editingEvent.extendedProps.description),
          {
            multiLineParagraph: false,
            multiLineBlockquote: false,
            multiLineHeader: false,
            multiLineCodeblock: false,
          }
        );
        return converter.convert();
      }
      return "";
    },
  },
};
</script>
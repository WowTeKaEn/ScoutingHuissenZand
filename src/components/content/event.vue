<template>
  <div class="d-flex flex-column form-group">
    <b-form-input v-model="editingEvent.eventName" placeholder="Evenement naam"></b-form-input>
    <div class="datepicker-trigger mt-3">
      <b-form-input 
        type="text"
        id="datepicker-trigger"
        placeholder="Selecteer datum"
        :value="formatDates(editingEvent.startDate, editingEvent.endDate)"
      ></b-form-input>
      <AirbnbStyleDatepicker 
        :trigger-element-id="'datepicker-trigger'"
        :mode="'range'"
        :fullscreen-mobile="true"
        :date-one="editingEvent.startDate"
        :date-two="editingEvent.endDate"
        @date-one-selected="val => { editingEvent.startDate = val }"
        @date-two-selected="val => { editingEvent.endDate = val }"
      />
    </div>
    <editor
      class="mt-3"
      ref="editor"
      v-bind:description="editingEvent.eventDescription"
    ></editor>
    <b-form-checkbox
        v-model="editingEvent.visible"
        value="1"
        unchecked-value="0"
      >Zichtbaar</b-form-checkbox>
    <b-button variant="primary" class="mt-3 ml-auto" @click="attemptToSubmit()">Evenement Opslaan</b-button>
  </div>
</template>


<script>
import format from "date-fns/format";
import editor from "./editor/editor";
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";
import axios from "@/plugins/axios.js";

export default {
  name: "event",
  props: ["event","branchEvents","branch"],
  components: { editor },
  data() {
    return {
      dateFormat: "D MMM",
      nlLocale: require("date-fns/locale/nl"),
      submitting: false,
      editingEvent: this.event
    };
  },
  methods: {
    formatDates(dateOne, dateTwo) {
      let formattedDates = "";
      if (dateOne) {
        formattedDates = format(dateOne, this.dateFormat, {
          locale: this.nlLocale
        });
      }
      if (dateTwo) {
        formattedDates +=
          " - " + format(dateTwo, this.dateFormat, { locale: this.nlLocale });
      }
      return formattedDates;
    },
    attemptToSubmit() {
      this.editingEvent.EventDescription = this.$refs.editor.editDescription;
      if (this.editingEvent.eventName !== null && this.editingEvent.eventDescription !== "") {
        this.submitting = true;
        axios
          .post("/event/insert", {
            eventName: this.editingEvent.eventName,
            eventDescription: this.$refs.editor.getDelta(),
            startDate: this.editingEvent.dateOne,
            endDate: this.editingEvent.dateTwo,
            visible: this.editingEvent.visible,
            branchName: this.branch,
            images: this.$refs.editor.images,
            deletedImages: this.$refs.editor.deletedImages,
          })
          .then(response => {
            this.submitting = false;
            if (response.status == 201) {
              this.branchEvents.push(this.editingEvent);
              this.$bvToast.toast("Evenement toegevoegd", {
                title: "Succes",
                autoHideDelay: 5000,
                appendToast: true
              });
            }else if(response.status == 200){
              this.event = this.editingEvent;
              this.$bvToast.toast("Bestaand evenement aangepast", {
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
              this.$bvToast.toast("Evenement niet toegevoegd", {
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
  },
  created(){
    var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(this.event.eventDescription),
      {multiLineParagraph: false, multiLineBlockquote: false, multiLineHeader: false, multiLineCodeblock: false});
      this.editingEvent.eventDescription = converter.convert();
  }
};
</script>
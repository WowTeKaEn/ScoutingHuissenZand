<template>
  <b-card class="mb-3">
    <h1>Evenementen</h1>
    <b-form novalidate @submit.stop.prevent>
      <div>
        <FullCalendar
          defaultView="dayGridMonth"
          :editable="true"
          :eventResizableFromStart="true"
          :selectable="true"
          :events="events"
          :locale="nlLocale"
          @eventDrop="handleEventDateChange"
          @eventResize="handleEventDateChange"
          @select="handleSelect"
          @eventClick="handleEventSelect"
          :plugins="calendarPlugins"
          timeZone="UTC"
          themeSystem="bootstrap"
        />
      </div>
      <b-modal
        id="modal"
        v-model="modal"
        modal-class="modal-fullscreen"
        :title="newEvent ? 'Nieuw Evenement':currentEvent.title "
        @shown="addState"
        @hide="removeState"
      >
        <event
          @correct-submit="closeModal"
          v-if="currentEvent != null"
          :key="currentEvent.title"
          ref="eventEditor"
          v-bind:event="currentEvent"
          v-bind:branch="branchName"
          v-bind:events="events"
        ></event>
        <div slot="modal-footer" class="w-100 d-flex">
          <b-button
            v-if="!newEvent"
            variant="danger"
            class="mr-3"
            @click="deleteEvent()"
          >Verwijderen</b-button>
          <b-button
            variant="primary"
            class="ml-auto"
            @click="$refs.eventEditor.attemptToSubmit()"
          >Opslaan</b-button>
        </div>
      </b-modal>
    </b-form>
  </b-card>
</template>

<style>
.btn-sm {
  padding: 0.12rem 0.25rem;
  font-size: 0.7rem;
}

.asd__wrapper--full-screen {
  z-index: 100000 !important;
}

.fc-title {
  color: white;
}

@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
@import "~@fullcalendar/bootstrap/main.css";
</style>


<script>
import FullCalendar from "@fullcalendar/vue";
import interactionPlugin from "@fullcalendar/interaction";
import event from "./event";
import axios from "@/plugins/axios.js";
import dayGridPlugin from "@fullcalendar/daygrid";
import bootstrap from "@fullcalendar/bootstrap";
import nlLocale from "@fullcalendar/core/locales/nl";
import isMobile from "@/plugins/isMobile";
import keyBoardResize from "@/plugins/keyBoardResize";
import Vue from "@/main.js"

export default {
  name: "events",
  props: ["user", "branchName"],
  components: { event, FullCalendar },
  data() {
    return {
      returnedEvents: false,
      currentEvent: null,
      calendarPlugins: [dayGridPlugin, interactionPlugin, bootstrap],
      nlLocale: nlLocale,
      events: [],
      modal: false,
    };
  },
  created() {
    if (isMobile()) {
      let file = document.createElement("link");
      file.rel = "stylesheet";
      file.href = "/css/bootstrap-fs-modal.css";
      document.head.appendChild(file);
      keyBoardResize();
    }
    this.returnedEvents = false;
    this.branchEvents = [];
    this.currentEvent = null;
    this.events = [];
    axios
      .post("/event/get/branch", { branchName: this.branchName })
      .then((response) => {
        this.returnedEvents = true;
        response.data.forEach((event) => {
          this.events.push({
            title: event.eventName,
            start: event.startDate,
            end: event.endDate,
            extendedProps: {
              description: event.eventDescription,
              visible: event.visible,
            },
          });
        });
      })
      .catch(() => {
        this.returnedEvents = true;
      });
  },
  beforeMount() {
    window.addEventListener("hashchange", () => {
      if (this.modal && isMobile()) {
        this.$bvModal.hide("modal");
      } else {
        return;
      }
    });
  },
  methods: {
    addState() {
      if (this.modal && isMobile()) {
        history.pushState(null, null, "#event");
      }
    },
    removeState() {
      if (location.hash == "#event" && isMobile()) {
        window.history.back();
      }
    },
    deleteEvent() {
      axios
        .post("/event/delete", {
          eventName: this.currentEvent.title,
          branchName: this.branchName,
          startDate: this.currentEvent.start,
          endDate: this.currentEvent.end,
        })
        .then((response) => {
          if (response.status == 200) {
            this.events = this.events.filter(
              (event) =>
                event.title != this.currentEvent.title ||
                event.start !=
                  this.currentEvent.start.toISOString().split("T")[0] ||
                event.end != this.currentEvent.end.toISOString().split("T")[0]
            );
            this.$bvModal.hide("modal");
            this.$bvToast.toast("Evenement verwijderd", Vue.toastObject("Succes"));
          } else {
            this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
          }
        })
        .catch((error) => {
          this.submitting = false;
          if (error.response.status === 401) {
            this.$bvToast.toast("Unauthorised", Vue.toastObject("Error"));
          } else {
            this.$bvToast.toast(error + "", Vue.toastObject("Error"));
          }
        });
    },
    closeModal(responseCode) {
      if (responseCode != 200 && responseCode != 201) {
        this.$bvToast.toast("Unkown",Vue.toastObject("Error"));
      }
      this.currentEvent = null;
      this.$bvModal.hide("modal");
    },
    handleSelect(arg) {
      arg.start = arg.start.toISOString().split("T")[0];
      arg.end = arg.end.toISOString().split("T")[0];
      this.currentEvent = {
        title: null,
        start: arg.start,
        end: arg.end,
        extendedProps: { description: "[]", visible: 0 },
      };
      this.$bvModal.show("modal");
    },
    handleEventSelect(arg) {
      this.currentEvent = arg.event;
      this.$bvModal.show("modal");
    },
    handleEventDateChange(arg) {
      var previousEvent;
      if (arg.oldEvent == null) {
        previousEvent = arg.prevEvent;
      } else {
        previousEvent = arg.oldEvent;
      }
      axios
        .post("/event/update", {
          eventName: arg.event.title,
          startDate: arg.event.start,
          endDate: arg.event.end,
          branchName: this.branchName,
          prevStartDate: previousEvent.start,
          prevEndDate: previousEvent.end,
        })
        .then((response) => {
          if (response.status == 200) {
            var event = this.events.filter(
              (event) =>
                event.title == arg.event.title &&
                event.start ==
                  previousEvent.start.toISOString().split("T")[0] &&
                event.end == previousEvent.end.toISOString().split("T")[0]
            )[0];
            event.start = arg.event.start.toISOString().split("T")[0];
            event.end = arg.event.end.toISOString().split("T")[0];
          } else {
            arg.revert();
            this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
          }
        })
        .catch((error) => {
          arg.revert();
          this.submitting = false;
          if (error.response.status === 401) {
            this.$bvToast.toast("Unauthorised", Vue.toastObject("Error"));
          } else {
            this.$bvToast.toast(error + "", Vue.toastObject("Error"));
          }
        });
    },
  },
  computed: {
    newEvent() {
      if (this.currentEvent != null && this.currentEvent.title != null) {
        return false;
      }
      return true;
    },
  },
};
</script>


<template>
  <div>
    <FullCalendar
      defaultView="dayGridMonth"
      :events="events"
      :locale="nlLocale"
      :plugins="calendarPlugins"
      timeZone="UTC"
      @eventClick="handleEventClick"
      themeSystem="bootstrap"
      :header="header"
      :views="views"
      :height="height"
    />

    <b-sidebar v-model="sideBarEvent" id="sidebar-right" :title="sidebarTitle" right shadow>
      <div class="w-100 d-flex">
        <span class="mx-auto pl-3 pr-1" v-if="sideBarEvent">{{ getFormattedDate(sidebarStart,sidebarEnd) }}</span>
      </div>
      <quillViewer v-bind:ready="sideBarEvent" v-bind:quill="sidebarDescription"></quillViewer>
    </b-sidebar>
  </div>
</template>


<style>
@media only screen and (max-width: 768px) {
  .fc-header-toolbar {
    flex-direction: column;
  }
  .fc-center {
    margin-top: 1em;
    margin-bottom: 1em;
  }
}


.fc-event-container:hover, .fc-list-item:hover {
  cursor: pointer;
}

.fc-title {
  color: white;
}

.b-sidebar-header {
  text-align: right !important;
}

.fc-scroller{
  height: 384px !important;
}

@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
@import "~@fullcalendar/list/main.css";
</style>

<script>
import dayGridPlugin from "@fullcalendar/daygrid";
import FullCalendar from "@fullcalendar/vue";
import interactionPlugin from "@fullcalendar/interaction";
import nlLocale from "@fullcalendar/core/locales/nl";
import bootstrap from "@fullcalendar/bootstrap";
import listPlugin from "@fullcalendar/list";
import quillViewer from "@/components/content/editor/quillViewer";

export default {
  name: "calendarViewer",
  props: ["events"],
  components: { FullCalendar, quillViewer },
  data() {
    return {
      calendarPlugins: [
        dayGridPlugin,
        interactionPlugin,
        bootstrap,
        listPlugin
      ],
      header: {
        left: "title",
        center: "dayGridMonth,listMonth",
        right: "today prev,next"
      },
      views: {
        listMonth: { buttonText: "Lijst" }
      },
      height: "parent",
      nlLocale: nlLocale,
      calendarReturned: false,
      sidbarStart: "",
      sidebarEnd: "",
      sidebarTitle: "",
      sideBarEvent: false,
      sidebarDescription: "",
      dateFormat: "D MMM"
    };
  },
  methods: {
    handleEventClick(arg) {
      this.sideBarEvent = false;
      this.sidebarTitle = arg.event.title;
      this.sidebarStart = arg.event.start;
      this.sidebarEnd = arg.event.end;
      this.sidebarDescription = arg.event.extendedProps.description;
      this.sideBarEvent = true;
    }
  },
};
</script>
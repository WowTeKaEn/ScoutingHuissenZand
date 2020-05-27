<template>
  <main class="page landing-page">
    <section>
      <carousel></carousel>
    </section>
    <section class="clean-block clean-info dark">
      <b-container class="mt-5">
        <div class="block-heading">
          <h2 class="text-info">Speltakken</h2>
          <p>Dit zijn de verschillende speltakken van Scouting Huissen Zand</p>
        </div>
        <b-row>
          <b-col v-for="branch in branches" :key="branch.branchName" class="d-flex flex-column">
            <teammember v-bind:branch="branch"></teammember>
          </b-col>
        </b-row>
      </b-container>
    </section>
    <section class="clean-block clean-info dark">
      <b-container>
        <div class="block-heading p-0">
          <h2 class="text-info">Evenementen</h2>
          <p>Deze kalender bevat alle aankomende evenementen van de scouting</p>
        </div>
        <b-card class="clean-card">
          <h2>Kalender</h2>
          <calendarViewer v-bind:ready="calendarReturned" v-bind:events="events"></calendarViewer>
        </b-card>
      </b-container>
    </section>
  </main>
</template>

<script>
import axios from "@/plugins/axios.js";
import teammember from "@/components/teammember.vue";
import carousel from "@/components/layout/carousel.vue";
import calendarViewer from "@/components/content/calendarViewer";

export default {
  name: "Home",
  props: ["tabs", "branches"],
  components: { teammember, carousel, calendarViewer },
  data() {
    return {
      calendarReturned: false,
      events: []
    };
  },
  methods: {
    hashCode(str) {
      var hash = 0;
      for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
      }
      return hash;
    },
    intToRGB(i) {
      var c = (i & 0x00ffffff).toString(16).toUpperCase();
      return "00000".substring(0, 6 - c.length) + c;
    }
  },
  created() {
    axios
      .post("/event/getall")
      .then(response => {
        response.data.forEach(event => {
          this.events.push({
            title: event.branchName + " - " +event.eventName,
            start: event.startDate,
            end: event.endDate,
            description: event.eventDescription,
            color: "#" + this.intToRGB(this.hashCode(event.branchName))
          });
        });
        this.calendarReturned = true;
      })
      .catch(error => {
        this.calendarReturned = true;
        if (error.response.status != 404) {
          this.$bvToast.toast("Kalender kon niet worden opgehaald", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        }
      });
  }
};
</script>

<style>
.clean-hero {
  color: #28a745;
}
</style>
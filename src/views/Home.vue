<template>
<div id="homepage" class="d-flex flex-column flex-grow">
    <section>
      <carousel></carousel>
      
    </section>
    
    <section class="clean-block clean-info dark" style="position:relative">
      <div class="section-decoration bottom"></div>
      <b-container  class="mt-5">
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
      <div class="section-decoration top"></div>
    </section>

    <section v-if="albums != null && albums.length > 0" class="p-0 py-5 photo-block">
      
      <b-container class="py-5">
        
        <b-card class="my-5 clean-block clean-card pb-5">
          <div class="block-heading p-0">
          <h2 class="text-info">Foto's</h2>
          <p style="font-size:1em">Hier staan een aantal foto's van de verschillende speltakken</p>
        </div>
        <albumViewer :albums="albums"></albumViewer>
        </b-card>
      </b-container>
      
    </section>
    <section class="clean-block clean-info p-0 py-5" style="position:relative">
      <div style="background-color:white" class="section-decoration bottom"></div>
      <b-container>
        <div class="block-heading p-0">
          <h2 class="text-info">Evenementen</h2>
          <p>Deze kalender bevat alle aankomende evenementen van de scouting</p>
        </div>
        <h2>Kalender</h2>
        <calendarViewer v-bind:ready="calendarReturned" v-bind:events="events"></calendarViewer>
      </b-container>
      
    </section>
</div>
</template>

<script>
import axios from "@/plugins/axios.js";
import teammember from "@/components/teammember.vue";
import carousel from "@/components/layout/carousel.vue";
import calendarViewer from "@/components/content/events/calendarViewer";
import albumViewer from "@/components/content/albumViewer";
import Vue from "@/main.js"
 
export default {
  name: "Home",
  props: ["tabs", "branches"],
  components: { teammember, carousel, calendarViewer, albumViewer },
  data() {
    return {
      calendarReturned: false,
      events: [],
      albums: null
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
            title: event.branchName + " - " + event.eventName,
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
          this.$bvToast.toast("Kalender kon niet worden opgehaald", Vue.toastObject("Error"));
        }
      });
    axios
    .get("albums/get")
    .then(response => {
      this.albums = response.data;
    });
  },
};
</script>

<style>
.section-decoration {
    background-color: #f6f6f6;
    background-position: center top;
    height: 20px;
    width: 100%;
      position: absolute;
    left: 0;
    z-index: 2;
    mask: url(~@/assets/img/section-border.svg);
    -webkit-mask: url(~@/assets/img/section-border.svg);
  }
  .section-decoration.top{
    bottom: -20px;
    transform: scale(-1,-1);

  }
  .section-decoration.bottom{
    top: -20px;
  }

  .clean-block{
    background-color:white
  }

  .clean-hero {
    color: #28a745;
  }

.photo-block{
  background-attachment: fixed;
  background-image: url(~@/assets/img/20190725_083553.jpg);
}

</style>
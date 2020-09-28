<template>
  <b-navbar
    id="navbar"
    toggleable="lg"
    class="flex-fill navbar-light fixed-top bg-white clean-navbar"
  >
    <div class="w-25 d-flex">
      <b-navbar-brand href="/index" class="logo d-flex flex-row ml-auto">
        <img class="mr-3" src="@/assets/img/navicon.png" />
        <div class="my-auto" id="brand-name">
          Scouting
          <br />Huissen Zand
        </div>
      </b-navbar-brand>
    </div>
    <b-navbar-toggle target="nav-collapse">
      <template v-slot:default="{ expanded }">
        <div :class="expanded ? 'nav-collapse-btn active':'nav-collapse-btn'">
            <div></div>
          </div>
      </template>
    </b-navbar-toggle>
    <b-collapse id="nav-collapse" is-nav class="w-75 ml-auto">
      <b-navbar-nav class="ml-auto">
        <b-nav-item
          href="/index"
        >Home</b-nav-item>
        <b-nav-item
          v-for="tab in $props.tabs"
          :key="tab.tabName"
          :href="'/tab/' + tab.tabName"
        >{{ tab.tabName}}</b-nav-item>
        <div @mouseover="onOver" @mouseleave="onLeave">
          <b-nav-item-dropdown ref="dropdown" text="Speltakken" v-if="branches.length">
            <b-dropdown-item
              v-for="branch in $props.branches"
              :key="branch.branchName"
              :href="'/speltak/' + branch.branchName"
            >{{ branch.branchName }}</b-dropdown-item>
          </b-nav-item-dropdown>
        </div>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<style>
.navbar-nav .dropdown-menu {
  position: absolute !important;
  float: none;
}
.navbar-toggler {
  height: 70px;
  width: 70px;
  display: flex;
  flex-direction: column;
  border-radius: 0;
  border: none
}

.navbar-toggler:hover {
  background-color: #3636361e;
}

.nav-link{
  color:#28a745 !important;
}


.nav-collapse-btn {
  margin: auto auto;
  position: relative;
  right: 0;
  top: 0;
  width: 48px;
  height: 41px;
  padding: 0.5em 0.5em 0.5em 0.5em;
}


.nav-collapse-btn:after,
.nav-collapse-btn:before,
.nav-collapse-btn div {
  background-color: #363636;
  content: "";
  display: block;
  height: 1px;
  transition: all 0.1s ease-in-out;
}

.nav-collapse-btn div {
  margin: 9px 0;
}

.nav-collapse-btn.active:before {
  transform: translateY(10px) rotate(45deg);
}

.nav-collapse-btn.active:after {
  transform: translateY(-10px) rotate(-45deg);
}

.nav-collapse-btn.active div {
  transform: scale(0);
}

.navbar.transition {
  transition: all 0.3s;
}

.navbar.small-nav {
  padding: 0 1rem 0 1rem !important;
  
}

.navbar .navbar-brand.transition{
  transition: all 0.3s;
}

.navbar.small-nav .navbar-brand{
  padding: 0 !important;
}
@media only screen and (max-width: 600px) {
  #brand-name {
    display: none;
  }
}
.navbar img {
  border: 0 !important;
}
.dropdown-menu {
  top: 90% !important;
}
</style>

<script>
import isMobile from "@/plugins/isMobile";

export default {
  name: "vueNav",
  props: ["tabs", "branches"],
  methods: {
    onOver() {
      if (!isMobile()) {
        this.$refs.dropdown.visible = true;
      }
    },
    onLeave() {
      if (!isMobile()) {
        this.$refs.dropdown.visible = false;
      }
    },
    scrollFunction() {
      if (
        document.body.scrollTop > 10 ||
        document.documentElement.scrollTop > 10
      ) {
        document.getElementById("navbar").classList.add("small-nav");
      } else {
        document.getElementById("navbar").classList.remove("small-nav");
      }
    }
  },
  mounted() {
    if(!isMobile()){
      window.onscroll = this.scrollFunction
      document.getElementById("navbar").classList.add("transition");
      document.querySelector('.navbar .navbar-brand').classList.add("transition");
    }else{
      document.getElementById("navbar").classList.add("small-nav");
    }
  }
};
</script>
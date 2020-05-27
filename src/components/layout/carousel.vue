<template>
  <b-carousel
    id="carousel-1"
    v-model="slide"
    :interval="5000"
    indicators
    background="#ababab"
    style="text-shadow: 1px 1px 2px #333; height: 60vw; max-height: 60vh"
    @sliding-start="onSlideStart"
    @sliding-end="onSlideEnd"
    no-hover-pause
  >
    <b-carousel-slide class="clean-block clean-hero">
      <template v-slot:img>
        <div class="text">
          <h2>Welkom bij Scouting Huissen Zand</h2>
          <p>Welkom bij de vernieuwde website van Scouting Huissen Zand. Hier kun je informatie over de verschillende speltakken en aankomende evenementen vinden</p>
        </div>
      </template>
    </b-carousel-slide>
    <b-carousel-slide caption="Gidsen" text="Dacht ik zo eventjes te zien">
      <template v-slot:img>
        <img
          style="object-fit: cover;"
          class="d-block w-100"
          src="https://scontent-amt2-1.xx.fbcdn.net/v/t1.0-9/78408294_2536457219779413_5672881358192508928_o.jpg?_nc_cat=108&_nc_sid=110474&_nc_ohc=P7Gy9lnAerUAX_GXKaL&_nc_ht=scontent-amt2-1.xx&oh=ab194c14eef63046f22a3b74184ebc20&oe=5EF12326&dl=1"
          alt="image slot"
        />
      </template>
    </b-carousel-slide>
    <b-carousel-slide caption="Verkenners" text="Leuk groepie hoor">
      <template v-slot:img>
        <img
          style="object-fit: cover;"
          class="d-block w-100"
          src="https://scontent-amt2-1.xx.fbcdn.net/v/t1.0-9/88248349_2759330350825431_3042933313594458112_o.jpg?_nc_cat=105&_nc_sid=110474&_nc_ohc=DFcmPnrFsKEAX-cZycP&_nc_ht=scontent-amt2-1.xx&oh=6b53306b56e9cc91e586a1ae60930221&oe=5EF1D317&dl=1"
          alt="image slot"
        />
      </template>
    </b-carousel-slide>
  </b-carousel>
</template>

<style>
.carousel-inner,
.carousel-item,
img {
  height: 100%;
}

.clean-hero {
  display: none !important;
}

.clean-hero.active, .clean-hero.carousel-item-next, .clean-hero.carousel-item-prev{
  display: flex !important;
}
</style>


<script>
export default {
  data() {
    return {
      slide: 0,
      sliding: null
    };
  },
  methods: {
    onSlideStart() {
      this.sliding = true;
    },
    onSlideEnd() {
      this.sliding = false;
    }
  }
};
</script>
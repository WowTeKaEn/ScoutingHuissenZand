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
          src="https://res.cloudinary.com/duj1jfopx/image/upload/v1593523963/78408294_2536457219779413_5672881358192508928_o_ghls3z.jpg"
          alt="image slot"
        />
      </template>
    </b-carousel-slide>
    <b-carousel-slide caption="Verkenners" text="Leuk groepie hoor">
      <template v-slot:img>
        <img
          style="object-fit: cover;"
          class="d-block w-100"
          src="https://res.cloudinary.com/duj1jfopx/image/upload/v1593523635/88248349_2759330350825431_3042933313594458112_o_fva19f.jpg"
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

.clean-block.clean-hero .text{
  top: -20% ;
  position: relative;
}

@media only screen and (max-width: 575px) {
  .clean-block.clean-hero .text{
  top: -5%;
}

.carousel.slide.pointer-event{
  height: 100vw !important;
}
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
      sliding: false
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
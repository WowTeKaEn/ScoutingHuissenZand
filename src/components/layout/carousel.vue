<template>
  <flickity ref="flickity" :options="flickityOptions">
    <div class="carousel-cell clean-block clean-hero">
      <div class="text">
        <h2>Welkom bij Scouting Huissen Zand</h2>
        <p>Welkom bij de vernieuwde website van Scouting Huissen Zand. Hier kun je informatie over de verschillende speltakken en aankomende evenementen vinden</p>
      </div>
    </div>
    <div class="carousel-cell clean-block clean-hero">
      <div class="text carousel-text">
        <h3>Gidsen</h3>
      </div>
      <img
        style="object-fit: cover; position:absolute;"
        class="d-block w-100"
        src="https://res.cloudinary.com/duj1jfopx/image/upload/v1593523963/78408294_2536457219779413_5672881358192508928_o_ghls3z.jpg"
        alt="image slot"
      />
    </div>
    <div class="carousel-cell clean-block clean-hero">
      <div class="text carousel-text">
        <h3>Verkenners</h3>
      </div>
      <img
          style="object-fit: cover;"
          class="d-block w-100"
          src="https://res.cloudinary.com/duj1jfopx/image/upload/v1593523635/88248349_2759330350825431_3042933313594458112_o_fva19f.jpg"
          alt="image slot"
        />
    </div>
        <div class="carousel-cell clean-block clean-hero">
      <div class="text carousel-text">
        <h3>Rowans</h3>
      </div>
      <img
          style="object-fit: cover;"
          class="d-block w-100"
          src="https://res.cloudinary.com/duj1jfopx/image/upload/v1600950464/IMG-20190711-WA0004_z0ekqw.jpg"
          alt="image slot"
        />
    </div>
      <div class="carousel-cell clean-block clean-hero">
      <div class="text carousel-text">
        <h3>Welpen</h3>
      </div>
      <img
          style="object-fit: cover;"
          class="d-block w-100"
          src="https://res.cloudinary.com/duj1jfopx/image/upload/v1601669294/a6dc5816-0709-48e3-9849-4afe30ab1dab_vmvyra.jpg"
          alt="image slot"
        />
    </div>
  </flickity>
</template>



<script>
import Flickity from "vue-flickity";

export default {
  components: {
    Flickity
  },
  data() {
    return {
      slide: 0,
      sliding: false,
      flickityOptions: {
        initialIndex: 0,
        prevNextButtons: false,
        pageDots: true,
        contain: true,
        wrapAround: false,
        autoPlay: true,
        pauseAutoPlayOnHover: false,
        imagesLoaded: true,
      }
    };
  },
};
</script>


<style>
.clean-block.clean-hero img {
  position: absolute;
  z-index: 1;
  height: 100%;
}
.clean-block.clean-hero .text {
  top: 5%;
  position: relative;
}
.carousel-text{
  top:unset !important;
  bottom: -40% !important;
  text-shadow: 2px 2px black !important;
}
.carousel-text p{
  opacity: .8;
}
@media only screen and (max-width: 575px) {
  .clean-block.clean-hero .text {
    top: -5%;
  }
}
.carousel-cell {
  width: 100%;
  height: 75vh;
  background: white;
  border-radius: 5px;
}
.flickity-page-dots {
  z-index: 10;
}
</style>

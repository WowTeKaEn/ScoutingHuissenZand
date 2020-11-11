<template>
  <vue-preview :slides="images"></vue-preview>
</template>


<script>
export default {
  name: "image-viewer",
  props: ["images"],
  data() {
    return {
      style: null,
    };
  },
  created() {
    this.style = document.createElement("style");
    document.head.appendChild(this.style);
    this.checkImageAmount();
  },
  beforeDestroy() {
    this.style.parentNode.removeChild(this.style);
  },
  methods: {
    checkImageAmount() {
      let images = this.images.length;
      if (images > 5) {
        this.style.innerHTML = `.my-gallery  :nth-child(4) a:after {content: '${
          images - 5
        }+';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          text-align: center;
          transform: translate(0, 50%);
          line-height: 0;
          }
          .my-gallery  :nth-child(4) a:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            top: 0;
            bottom: 0;
          }`;
      } else {
        this.style.innerHTML = "";
      }
      if (images > 4) {
        this.style.innerHTML += `.my-gallery :nth-child(4) {
            grid-row: span 2
          }`;
      }
    },
  },
};
</script>

<style lang="scss">
:root {
  --gutter: 1px;
  --rh: calc((var(--wrapperWidth) - (11 * var(--gutter))) / 12);
}

.grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-gap: var(--gutter);
}

@for $i from 1 through 12 {
  .grid.column-#{$i} {
    grid-template-columns: repeat($i, 1fr);
    --rh: calc((var(--wrapperWidth) - ((#{$i} - 1) * var(--gutter))) / #{$i});
  }
}

.grid.square {
  grid-auto-rows: minmax(var(--rh), auto);
}

.grid-item {
  position: relative;
}

@for $i from 1 through 12 {
  .grid-item.width-#{$i} {
    grid-column: span $i;
  }
}

@for $i from 1 through 12 {
  .grid-item.row-#{$i} {
    grid-row: span $i;
  }
}

.grid-item.no-padding {
  padding: 0;
}

.grid-item .full-img,
.grid-item.full-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.my-gallery figure {
  align-items: center;
  // max-width: 80%;

  margin: 0;

  @extend .grid-item;
}

.my-gallery {
  margin:auto;
  max-width: 800px;
  @extend .grid;
  @extend .square;
  @extend .column-2;
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.my-gallery :nth-child(1) {
  @extend .row-3;
  img{
    max-height: 50.3vh;
  }
}

.my-gallery :nth-child(n + 2) img{
  max-height: 25vh;
}

.my-gallery :nth-child(4) a {
  height: 100%;
  display: flex;
  position: relative;
  color: white;
  font-size: 5rem;
  &:hover {
    text-decoration: none;
    color: white;
  }
}

.my-gallery :nth-child(n + 6) {
  display: none !important;
}
</style>

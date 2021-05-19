<template>
  <div class="photo-gallery">
    <div
      v-for="(image, index) in images"
      :key="image.src"
      :style="
        images.length > 4 && index === 3
          ? 'grid-row: span 2; background-color:black'
          : null
      "
    >
      <img
        v-preview:album
        :src="image.msrc"
        :data-origin="image.src"
        :msrc="image.msrc"
        :style="index === 3 && images.length > 5 ? 'opacity: 0.5;' : ''"
        :width="image.w"
        :height="image.h"
        :ref="`gallery-image-${index}`"
      />
      <p
        class="image-amount-overlay m-0"
        style="opacity: 1"
        v-if="index === 3 && images.length > 5"
      >
        <span class="image-amount-overlay-text m-auto">
          {{ images.length - 5 }}+
        </span>
      </p>
    </div>
  </div>
</template>


<script>
import createPreviewDirective from "vue-photoswipe-directive";
import PhotoSwipe from "photoswipe/dist/photoswipe";
import PhotoSwipeUI from "photoswipe/dist/photoswipe-ui-default";
import "photoswipe/dist/photoswipe.css";
import "photoswipe/dist/default-skin/default-skin.css";

export default {
  directives: {
    preview: createPreviewDirective(
      {
        getThumbBoundsFn: (index) => {
          var thumbnail = document.querySelectorAll(".photo-gallery > div")[
            index
          ].firstElementChild;

          var pageYScroll =
            window.pageYOffset || document.documentElement.scrollTop;

          var ratio = thumbnail.naturalWidth / thumbnail.naturalHeight;
          var width = thumbnail.height * ratio;
          var height = thumbnail.height;
          if (width < thumbnail.width) {
            width = thumbnail.width;
            height = thumbnail.width / ratio;
          }
          thumbnail.style.transform = `translate(${
            (thumbnail.width - width) / 2
          }px,${(thumbnail.height - height) / 2}px)`;

          var rect = thumbnail.getBoundingClientRect();

          thumbnail.style.transform = "unset";
          return {
            x: rect.left,
            y: rect.top + pageYScroll,
            w: width,
          };
        },
      },
      PhotoSwipe,
      PhotoSwipeUI
    ),
  },
  name: "image-viewer",
  props: ["images", "album"],
};
</script>

<style lang="scss">
:root {
  --gutter: 1px;
  --rh: calc((var(--wrapperWidth) - (11 * var(--gutter))) / 12);
}

.image-amount-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  pointer-events: none;
}

.image-amount-overlay-text {
  color: white;
  font-size: 70px;
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

.photo-gallery {
  margin: auto;
  max-width: 800px;
  @extend .grid;
  @extend .square;
  @extend .column-2;
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  div {
    align-items: center;
    margin: 0;
    @extend .grid-item;
  }
}

.photo-gallery :nth-child(1) {
  @extend .row-3;
  img {
    max-height: 50.3vh;
  }
}

.photo-gallery :nth-child(n + 2) img {
  max-height: 25vh;
}

.photo-gallery :nth-child(4) a {
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

.photo-gallery :nth-child(n + 6) {
  display: none !important;
}
</style>

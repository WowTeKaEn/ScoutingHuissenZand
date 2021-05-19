<template>
    <div
        :style="color ? `background-color: ${color}` : null"
        :class="`section-decoration ${className}`"
      ></div>
</template>

<script>
export default {
    props: ["color","className"]
}
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
.section-decoration.top {
  bottom: -20px;
  transform: scale(-1, -1);
}
.section-decoration.bottom {
  top: -20px;
}
</style>
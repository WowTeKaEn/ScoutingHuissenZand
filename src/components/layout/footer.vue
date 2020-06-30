<template>
    <footer class="page-footer dark">
        <div class="footer-decoration"></div>
        <b-container>
            <div class="row d-flex justify-content-center">
                <div class="col-sm-3">
                    <h5>Speltakken</h5>
                    <ul>
                        <li v-for="branch in branches" :key="branch.branchName"><a :href="'/speltak/' + branch.branchName">{{ branch.branchName }}</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5>Tabbladen</h5>
                    <ul>
                        <li v-for="tab in tabs" :key="tab.tabName"><a :href="'/tab/' + tab.tabName">{{ tab.tabName }}</a></li>
                        <li ><a href="/index">Home</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="/staf">Staff login</a></li>
                    </ul>
                </div>
            </div>
        </b-container>
        <div class="footer-copyright">
            <p>© 2020 Copyright</p>
        </div>
    </footer>
</template>

<script>
export default {
  name: 'vueFooter',
  props: ["tabs", "branches"],
}
</script>

<style>
footer{
    position: relative;
}

.footer-decoration {
  background-position: center top;
  height: 20px;
  width: 100%;
    position: absolute;
  left: 0;
  z-index: 2;
  background-image: url(~@/assets/img/footer-border.svg);
  fill: #f6f6f6;
  top: -20px;
}



</style>
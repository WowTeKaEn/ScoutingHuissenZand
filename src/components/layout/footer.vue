<template>
    <footer class="page-footer dark">
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
                        <li><a href="/staff">Staff login</a></li>
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
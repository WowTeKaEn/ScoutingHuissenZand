<template>
  <div >
    <section id="background-img" class="clean-block p-0 py-5 dark">
      <b-container>
        <b-card>
          <div class="d-flex flex-column m-auto">
            <img
              class="rounded-circle mx-auto"
              src="https://res.cloudinary.com/duj1jfopx/image/upload/v1606665521/Screenshot_20200105-181425_giro0d.jpg"
            />
            <h2 class="text-center">Wout Knuiman</h2>
            <p class="text-center m-auto" style="max-width:500px">
                Leuk dat je op deze pagina bent gekomen!<br>
                Ik ga hier eigenlijk niks belangrijk zeggen dus als je weinig tijd hebt of geen zin hebt om dit te lezen zou ik dat ook niet doen.<br>
                Ik heb deze website voor de scouting zelf gebouwd en ben er redelijk trots op dus daarom heb ik deze pagina maar even aangemaakt.<br>
                <br>

                Ben je nou benieuwd hoe ik deze website heb gebouwd 
                of zoek je zelf nog iemand die een website voor jou kan bouwen 
                kijk dan even op mijn website: <a href="https://WuttiFrutti.nl">WuttiFrutti.nl</a>
            </p>
          </div>
        </b-card>
      </b-container>
    </section>
  </div>
</template>



<script>
export default {
  name: "creator",
  data() {
    return {};
  },
};
</script>

<style>
.rounded-circle.mx-auto {
  width: 225px;
  height: 225px;
  border: 7px solid #fff !important;
}

#background-img{
    background-image: url(~@/assets/img/20190725_083553.jpg);
    background-size: cover;
    background-attachment: fixed;
}
</style>
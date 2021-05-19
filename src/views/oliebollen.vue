<template>
  <div class="oliebollen-container">
    <section class="py-5 text-white">
      <div class="container my-5 py-5">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading">Bestel hier uw Oliebollen!</h2>
          </div>
        </div>
        <div class="row text-center d-flex justify-content-center py-5">
          <a class="col-md-4 order-btn" v-b-modal.church-order-modal
            ><span class="fa-stack fa-4x"
              ><i class="fa fa-circle fa-stack-2x text-primary"></i
              ><i class="fa fa-church fa-stack-1x fa-inverse"></i
            ></span>
            <h4 class="section-heading">Ophalen</h4>
            <p class="">
              U kunt uw Oliebollen naast de kerk van huissen zand ophalen
            </p>
          </a>
          <a class="col-md-4 order-btn" v-b-modal.home-order-modal
            ><span class="fa-stack fa-4x"
              ><i class="fa fa-circle fa-stack-2x text-primary"></i
              ><i class="fa fa-home fa-stack-1x fa-inverse"></i
            ></span>
            <h4 class="section-heading">Thuisbezorgen</h4>
            <p class="">U kunt uw Oliebollen ook thuis laten bezorgen</p>
          </a>
        </div>
      </div>

      <order modal="home-order-modal" title="Thuisbezorgen" type="home">
        <template #modal-footer>
          <p class="mr-auto my-auto">Betaling met pin of contant bij bezorgen</p>
        </template>
      </order>
      <order modal="church-order-modal" title="Ophalen" type="pickup">
        <template #modal-footer>
          <p class="mr-auto my-auto">Betaling met pin of contant bij ophalen</p>
        </template>
      </order>
    </section>
    <section style="background-color: #2b2f31; color: white" class="m-0 position-relative">
      <SectionDecoration className="bottom" color="#2b2f31" />
      <div class="container mx-md-5 p-5">
        <h2 class="section-heading">Over ons</h2>
        <p>
          Ook dit jaar organiseren wij, welpen van Scouting Huissen-Zand, onze
          jaarlijkse oliebollen actie! Wij verkopen deze oliebollen op 29, 30 en
          31 december 2020. Met de opbrengst kunnen wij weer leuke dingen doen
          met de kinderen, zoals op kamp gaan of een dagje weg.
        </p>
        <p>
          Helaas zullen wij door de coronamaatregelen dit jaar geen kraampjes
          plaatsen waar wij onze oliebollen verkopen. U kunt nog steeds genieten
          van onze heerlijke oliebollen door ze te bestellen en op te halen bij
          ons clubgebouw of ze thuis te laten bezorgen. Wij bezorgen alleen in
          Huissen.
        </p>
      </div>
      <SectionDecoration className="top" color="#2b2f31" />

    </section>
    <section style="background-color: white" class="m-0 position-relative">
      <div class="container mx-5 ml-auto p-5 text-right">
        <h2 class="section-heading">Bestellen</h2>
        <p>
          U kunt uw bestelling via deze webpagina doen. Als u uw bestelling
          heeft verstuurd, krijgt u een bevestigingsmail met daarin uw
          bestelling en het tijdvak waarin u uw bestelling kunt ophalen of uw
          bestelling bezorgd wordt. Als u ervoor kiest om uw bestelling op te
          halen kunt u deze ophalen bij ons clubgebouw naast de Zandse kerk, van
          Wijkstraat 31 A Huissen.
        </p>
        <p>
          Bestellingen kunnen gedaan worden tot 28 december. Mocht u na deze
          datum bestellen, kunnen wij niet garanderen dat u een bestelling
          ontvangt.
        </p>
        <p>
          Mocht de actie niet door kunnen gaan in verband met de
          coronamaatregelen zullen wij u daarvan op de hoogte stellen.
        </p>
      </div>
      <SectionDecoration className="top" color="white" />
    </section>
    <section style="color: white" class="m-0">
      <div class="container p-5">
        <h2 class="section-heading">Prijzen</h2>
        <p>
         U kunt uw bestelling betalen bij het ophalen of bezorgen. Vanaf dit jaar is het ook mogelijk om bij ons te pinnen! 
        </p>
        <p>
         Wij hanteren de volgende aantallen en prijzen:
        </p>
        <table style="width:200px;">
          <tbody>
            <tr>
            <td>
              10 oliebollen 
            </td>
            <td>
              €4,25
            </td>
          </tr>
          <tr>
            <td>
              8 krentenbollen
            </td>
            <td>
              €4,25
            </td>
          </tr>
          <tr>
            <td>
              8 appelbollen
            </td>
            <td>
              €4,25
            </td>
          </tr>
          <tr>
            <td>
              5 appelbeignets 
            </td>
            <td>
              €4,25
            </td>
          </tr>
          </tbody>
        </table>
        <br/>
        <p>Mocht u nog vragen hebben kunt u een mail sturen naar welpen-huissenzand@live.nl of ons bellen op 06-83558207 of 06-20027450.</p>
        <p class="text-center small pt-5">Aan de gestelde bezorgtijden kunnen geen rechten worden ontleend.</p>
      </div>
    </section>
  </div>
</template>

<script>
import order from "@/components/seasonal/oliebollen/order.vue";
import isMobile from "@/plugins/isMobile";
import keyBoardResize from "@/plugins/keyBoardResize";
import SectionDecoration from "@/components/layout/section-decoration.vue"

export default {
  name: "Oliebollen",
  components: {
    order,
    SectionDecoration
  },
  created() {
    if (isMobile()) {
      let file = document.createElement("link");
      file.rel = "stylesheet";
      file.href = "/css/bootstrap-fs-modal.css";
      document.head.appendChild(file);
      keyBoardResize();
    }
  },
};
</script>

<style>
.oliebollen-container {
  background-image: url(~@/assets/img/oliebollen.jpg);
  background-size: cover;
  background-attachment: fixed;
  flex-grow: 1;
}
.order-btn {
  text-decoration: none;
  color: black;
  transition: all 0.1s ease-in-out;
}
.order-btn:hover {
  cursor: pointer;
  transform: scale(1.05);
  color: black;
}
</style>
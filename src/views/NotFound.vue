<template>
    <main class="page">
        <section class="clean-block clean-pricing dark">
            <b-container>
                <h1 class="text-center mt-5">{{ getError }}</h1>
            </b-container>
        </section>
    </main>
</template>

<script>
export default {
  name: 'NotFound',
  props: ["error"],
  computed:{
      getError(){
          if(this.error == 401){
            return "401 - Niet Gemachtigd"
          }else{
              return "404 - Niet Gevonden"
          }
      }
  }
}
</script>

<template> <!-- El que es veurà -->
    <span v-text="formatearFecha"></span> <!-- Imprimim la funció -->
    <!-- <span>{{formatearFecha}}</span> Es pot imprimir de les dues maneres-->
</template>
    
<script>
export default {
    props: ['fecha'], // A la vista tenim <fecha-receta hola="hola">. Aquell atribut 'hola' serà el props
    /*mounted() { // S'executa un cop el component està llest
        console.log('La fecha es ', this.fecha);
    },*/
    computed: { // S'utilitza per escriure la lògica del programa
        formatearFecha() { // Creem una funció que retorna el momentJS de props fecha en format local
            return moment(this.fecha).locale('es').format('DD [de] MMMM [del] YYYY');
        }
    }
}
</script>
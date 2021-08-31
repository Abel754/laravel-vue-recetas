<template>
    <input 
        type="submit" 
        class="btn btn-danger mr-1 d-block w-100 mb-2" 
        value="Eliminar ×"
        v-on:click="eliminarReceta"
    />
</template>

<script>
export default {
    props: ['recetaId'], // Tot i que a la vista és receta-id, es substitueix per majúscula
    mounted() {
        console.log('Receta actual', this.recetaId);
    },
    methods: { // S'utilitza methods per posar funcions
        eliminarReceta() {
            this.$swal({ // Mètode swal
            title: '¿Deseas eliminar esta receta?',
            text: "Una vez eliminada, no se puede recuperar",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {                                                                                                                   

                    const params = { // Hem de crear si o si la const params amb la ID
                        id: this.recetaId
                    }
                    // Utilitzem axios i enviem a la URL del web.php del mètode delete utilitzant la const creada adalt
                    axios.post(`/recetas/${this.recetaId}`, {params, _method: 'delete'})
                        .then(respuesta => {
                            this.$swal({
                                title: 'Receta Eliminada!',
                                text: 'Se eliminó la receta',
                                icon: 'success'
                            })

                            // Eliminar receta de la pantalla
                            // $el és una variable reservada que agafa el nostre objecte, en aquest cas <input>
                            // Amb els parentNode accedim al tbody i esborrem el tr
                            this.$el.parentNode.parentNode.parentNode.removeChild(this.$el.parentNode.parentNode);
                        })
                        .catch(error => {
                            console.log(error);
                        })

                    
                }
            })
        }
    }
}
</script>
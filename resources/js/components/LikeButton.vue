<template>
    <div>
        <!-- Li indiquem que si existeix el this.like, que li assigni la classe -->
        <span class="like-btn" @click="likeReceta" :class="{'like-active' : isActive}"></span>

        <p>{{cantidadLikes}} Les gustó esta receta</p>
    </div>
</template>

<script>
export default {
    props: ['recetaId', 'like', 'likes'], // Agafem l'atribut recetaId que es troba a la vista show 
    data: function() { // Per realitzar el tema dels likes necessitarem el data. Creem una variable que agafarà el total de likes de la recepta passada per props
        return {
            isActive: this.like,
            totalLikes: this.likes
        }
    },
    mounted() {
        console.log(this.like);
    },
    methods: { // S'utilitza methods per posar funcions
        likeReceta() {
            axios.post('/recetas/' + this.recetaId) // És com si fos un AJAX, mitjançant axios que treballa amb Vue, envia recetaID a aquella url
                .then(respuesta => {
                    console.log(respuesta) // Si tot va bé, imprimeix tot el response
                    if(respuesta.data.attached.length > 0) { // Accedint dins de la data de la resposta, hi ha dos atributs. Attached i dettached. Attached reb si l'usuari registrat ha donat like i farem que sumi quan cliquem si no ha donat o resti si ha donat ja.
                        this.$data.totalLikes++;
                    } else {
                        this.$data.totalLikes--; // 
                    }
                    this.isActive = !this.isActive;
                })
                .catch(error => {
                    if(error.response.status === 401) {
                        window.location = '/register';
                    }
                });
        }
    },
    computed: { // Executa la lògica del programa
        cantidadLikes: function() {
            return this.totalLikes;
        }
    }
}
</script>
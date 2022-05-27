<template>
    <div>
         <div class="row p-3">
             <!--             -->
             <Room></Room>
            <!--             -->

             <!--             -->
             <People></People>
             <!--             -->

         </div>
    </div>
</template>

<script>
import Room from './room/Room';
import People from './peolple/People';

export default {
    name: "ContentChat",
    components:{
        Room,People
    },
}
</script>

<style scoped>

</style>

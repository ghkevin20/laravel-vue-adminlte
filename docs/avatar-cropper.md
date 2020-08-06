# Avatar Cropper

`resources/js/components/AvatarCropper.vue`

Uses `Cropperjs`.

This component allows you to crop the image before uploading. 

This simple return returns Binary Large OBject(BLOB) which you can use for uploading.

```vue
<template>
    <avatar-cropper @updateBlob="updateBlob"></avatar-cropper>
</template>
<script>
    import AvatarCropper from "../../components/AvatarCropper";

    export default {
        name: "Upload",
        components: {
           AvatarCropper
        },
        methods:{
            updateBlob(blob){
              // upload
            }
        }
    }
</script>
```
